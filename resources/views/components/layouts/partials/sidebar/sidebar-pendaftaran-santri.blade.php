<ul class="menu">
    <li class="sidebar-title">Menu</li>

    {{-- Link Dashboard Utama --}}
    <li class="sidebar-item {{ Request::routeIs('e-ppdb.dashboard') ? 'active' : '' }}">
        <a href="{{ route('e-ppdb.dashboard') }}" class='sidebar-link'>
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="sidebar-title">Manajemen Pendaftaran</li>

    {{-- DROPDOWN 1: MASTER PSB --}}
    @php
        // Daftar route yang akan mengaktifkan dropdown Master PSB
        $masterPsbRoutes = [
            'admin.master-periode.dashboard',
            'admin.master-psb.show-registrations',
            'admin.master-psb.interview-list',
            'ppdb.dashboard-daftar-ulang'
        ];
    @endphp
    <li class="sidebar-item has-sub {{ Request::routeIs($masterPsbRoutes) ? 'active' : '' }}">
        <a href="#" class='sidebar-link'>
            <i class="bi bi-journal-text"></i>
            <span>Master PSB</span>
        </a>

        <ul class="submenu {{ Request::routeIs($masterPsbRoutes) ? 'active' : '' }}">
            <li class="submenu-item {{ Request::routeIs('admin.master-periode.dashboard') ? 'active' : '' }}">
                {{-- Nama link diubah sesuai permintaan --}}
                <a href="{{ route('admin.master-periode.dashboard') }}" wire:navigate>Dashboard Periode</a>
            </li>
            <li class="submenu-item {{ Request::routeIs('admin.master-psb.show-registrations') ? 'active' : '' }}">
                <a href="{{ route('admin.master-psb.show-registrations') }}" wire:navigate>List Santri</a>
            </li>
            <li class="submenu-item {{ Request::routeIs('admin.master-psb.interview-list') ? 'active' : '' }}">
                <a href="{{ route('admin.master-psb.interview-list') }}" wire:navigate>List Wawancara</a>
            </li>
            <li class="submenu-item {{ Request::routeIs('ppdb.dashboard-daftar-ulang') ? 'active' : '' }}">
                <a href="{{ route('ppdb.dashboard-daftar-ulang') }}" wire:navigate>Daftar Ulang</a>
            </li>
        </ul>
    </li>


    {{-- DROPDOWN 2: MASTER UJIAN --}}
    @php
        // Daftar route yang akan mengaktifkan dropdown Master Ujian
        $masterUjianRoutes = [
            'admin.master-ujian.*', // Wildcard agar mencakup detail, dll.
            'admin.psb.ujian.hasil'
        ];
    @endphp
    <li class="sidebar-item has-sub {{ Request::routeIs($masterUjianRoutes) ? 'active' : '' }}">
        <a href="#" class='sidebar-link'>
            <i class="bi bi-file-earmark-check-fill"></i>
            <span>Master Ujian</span>
        </a>

        <ul class="submenu {{ Request::routeIs($masterUjianRoutes) ? 'active' : '' }}">
            <li class="submenu-item {{ Request::routeIs('admin.master-ujian.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.master-ujian.dashboard') }}" wire:navigate>Dashboard Ujian</a>
            </li>
            <li class="submenu-item {{ Request::routeIs('admin.psb.ujian.hasil') ? 'active' : '' }}">
                <a href="{{ route('admin.psb.ujian.hasil') }}" wire:navigate>Hasil Ujian Santri</a>
            </li>
        </ul>
    </li>
</ul>