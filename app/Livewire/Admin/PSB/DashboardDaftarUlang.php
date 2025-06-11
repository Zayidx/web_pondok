<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

#[Title('Dashboard Daftar Ulang')] // Sets the page title for the Livewire component
class DashboardDaftarUlang extends Component
{
    use WithPagination; // Enables pagination functionality for the component

    // Public properties for filters, search, and pagination settings
    public int $perPage = 10; // Number of items to display per page
    public string $search = ''; // Search term for filtering registrations
    public array $filters = ['status' => '', 'tipe' => '', 'urutan' => 'terbaru']; // Array for various filters (status, type, order)
    public string $sortField = 'created_at'; // Default field for sorting
    public string $sortDirection = 'desc'; // Default sort direction

    // Public properties for modal states and selected data
    public bool $showDetailModal = false; // Controls visibility of the detail modal
    public bool $showProofModal = false; // Controls visibility of the payment proof modal
    public ?PendaftaranSantri $selectedRegistration = null; // Holds the selected registration model for details
    public ?string $proofImageUrl = null; // Stores the URL of the payment proof image

    protected string $paginationTheme = 'bootstrap'; // Specifies the Bootstrap pagination theme

    /**
     * Resets pagination to the first page when any of the specified properties change.
     * This ensures that filters and search terms are applied correctly.
     *
     * @param string $key The name of the property being updated.
     */
    public function updating(string $key): void
    {
        // Check if the updated key is related to search, perPage, or any filter
        if (in_array(explode('.', $key)[0], ['search', 'perPage', 'filters'])) {
            $this->resetPage(); // Reset to the first page
        }
    }

    /**
     * Sorts the table by the given field. Toggles sort direction if the same field is clicked again.
     *
     * @param string $field The database column to sort by.
     */
    public function sortBy(string $field): void
    {
        // If clicking the currently sorted field, toggle direction
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Otherwise, set new field and default to ascending
            $this->sortDirection = 'asc';
            $this->sortField = $field;
        }
        // Ensure manual 'urutan' filter doesn't conflict with direct column sorting
        $this->filters['urutan'] = '';
    }

    /**
     * Resets all search and filter parameters to their default values.
     * Also resets the pagination to the first page.
     */
    public function resetFilters(): void
    {
        $this->reset(['search', 'filters']); // Reset search term and filters array
        $this->resetPage(); // Reset pagination
    }

    /**
     * Displays the detail modal for a specific registration.
     *
     * @param int $id The ID of the PendaftaranSantri to display.
     */
    public function showDetail(int $id): void
    {
        // Find the registration by ID; if not found, $selectedRegistration will be null
        $this->selectedRegistration = PendaftaranSantri::find($id);
        $this->showDetailModal = true; // Open the detail modal
    }

    /**
     * Closes the detail modal and clears the selected registration data.
     */
    public function closeModal(): void
    {
        $this->showDetailModal = false; // Close the detail modal
        $this->selectedRegistration = null; // Clear selected registration to prevent data leakage
    }

    /**
     * Displays the payment proof image modal for a specific registration.
     *
     * @param int $id The ID of the PendaftaranSantri whose payment proof is to be viewed.
     */
    public function viewPaymentProof(int $id): void
    {
        $registration = PendaftaranSantri::find($id); // Find the registration by ID
        // Check if registration exists and has a payment proof
        if ($registration && $registration->bukti_pembayaran) {
            // Generate a public URL for the stored image
            $this->proofImageUrl = Storage::url($registration->bukti_pembayaran);
            $this->showProofModal = true; // Open the payment proof modal
        } else {
            // Flash an error message if proof is not found
            session()->flash('error', 'Bukti pembayaran untuk santri ini tidak ditemukan.');
        }
    }

    /**
     * Closes the payment proof modal and clears the image URL.
     */
    public function closeProofModal(): void
    {
        $this->showProofModal = false; // Close the payment proof modal
        $this->proofImageUrl = null; // Clear the image URL
    }

    /**
     * Verifies a registration, updating its status to 'verified' and 'diterima'.
     *
     * @param int $id The ID of the PendaftaranSantri to verify.
     */
    public function verifyRegistration(int $id): void
    {
        // Find the registration or throw 404 if not found
        $registration = PendaftaranSantri::findOrFail($id);

        // Update registration status and details
        $registration->update([
            'status_santri' => 'diterima', // Set santri status to 'accepted'
            'status_pembayaran' => 'verified', // Set payment status to 'verified'
            'verified_at' => now(), // Record verification timestamp
            'verified_by' => auth()->id(), // Record user who verified
        ]);

        // Flash a success message
        session()->flash('success', 'Pendaftaran ulang ' . $registration->nama_lengkap . ' telah diterima.');
        $this->closeModal(); // Close any open modals
        $this->dispatch('registrationVerified'); // Optional: dispatch an event for other components to listen
    }

    /**
     * Rejects a registration's payment proof, setting status to 'rejected' and clearing the proof.
     *
     * @param int $id The ID of the PendaftaranSantri whose payment proof is to be rejected.
     */
    public function rejectRegistration(int $id): void
    {
        // Find the registration or throw 404 if not found
        $registration = PendaftaranSantri::findOrFail($id);

        // Update registration status to 'rejected' and clear payment proof
        $registration->update([
            'status_pembayaran' => 'rejected', // Set payment status to 'rejected'
            'bukti_pembayaran' => null, // Clear the proof so santri can re-upload
        ]);

        // Flash a success message
        session()->flash('success', 'Bukti pembayaran ' . $registration->nama_lengkap . ' ditolak.');
        $this->closeModal(); // Close any open modals
        $this->dispatch('registrationRejected'); // Optional: dispatch an event
    }

    /**
     * Computed property that returns options for 'tipe pendaftaran' (registration type) filter.
     * This is cached and only re-evaluated if dependencies change.
     *
     * @return array
     */
    #[Computed]
    public function tipeOptions(): array
    {
        return [
            '' => 'Semua Tipe', // Added 'All Types' option
            'reguler' => 'Reguler',
            'olimpiade' => 'Olimpiade',
            'internasional' => 'Internasional',
        ];
    }

    /**
     * Computed property that returns options for 'status pembayaran' (payment status) filter.
     *
     * @return array
     */
    #[Computed]
    public function statusPaymentOptions(): array
    {
        return [
            '' => 'Semua Status', // Added 'All Status' option
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            'no_proof' => 'Menunggu Bukti', // Represents registrations without proof
        ];
    }

    /**
     * The main rendering method for the Livewire component.
     * This method constructs the query for registrations based on current filters and pagination.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        $query = PendaftaranSantri::query(); // Start with a fresh query builder instance

        // Filter by specific 'status_santri' (diterima or daftar_ulang)
        $query->whereIn('status_santri', ['diterima', 'daftar_ulang']);

        // Apply search filter if 'search' property is not empty
        $query->when($this->search, function ($q) {
            $q->where(function ($subQuery) {
                // Search across 'nama_lengkap' or 'nisn'
                $subQuery->where('nama_lengkap', 'like', '%' . $this->search . '%')
                         ->orWhere('nisn', 'like', '%' . $this->search . '%');
            });
        });

        // Apply 'tipe_pendaftaran' filter if 'filters.tipe' is set
        $query->when($this->filters['tipe'], function ($q) {
            $q->where('tipe_pendaftaran', $this->filters['tipe']);
        });

        // Apply 'status_pembayaran' filter if 'filters.status' is set
        $query->when($this->filters['status'], function ($q) {
            if ($this->filters['status'] === 'no_proof') {
                $q->whereNull('bukti_pembayaran'); // Filter for registrations with no payment proof
            } else {
                $q->where('status_pembayaran', $this->filters['status']);
            }
        });

        // Apply ordering based on 'filters.urutan' or default sort field/direction
        if ($this->filters['urutan'] === 'terbaru') {
            $query->orderBy('created_at', 'desc');
        } elseif ($this->filters['urutan'] === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            // Fallback to general sortField and sortDirection
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        // Paginate the results and pass them to the view
        return view('livewire.admin.psb.dashboard-daftar-ulang', [
            'registrations' => $query->paginate($this->perPage),
        ]);
    }
}