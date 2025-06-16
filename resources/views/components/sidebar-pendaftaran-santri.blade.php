<div>
    <div class="flex flex-col flex-1">
        <!-- Navigation -->
        <nav class="flex-1 px-2 py-4 space-y-1">
            <x-sidebar.nav-item title="Dashboard" icon="home" :link="route('ppdb.dashboard')" />
            <x-sidebar.nav-item title="Master Periode" icon="calendar" :link="route('ppdb.master-periode.dashboard-periode')" />
            <x-sidebar.nav-item title="Master Soal" icon="document-text" :link="route('ppdb.master-soal.dashboard-soal')" />
            <x-sidebar.nav-item title="Hasil Ujian" icon="clipboard-check" :link="route('ppdb.hasil-ujian')" />
            <x-sidebar.nav-item title="List Daftar Ulang" icon="clipboard-list" :link="route('ppdb.list-daftar-ulang')" />
            <x-sidebar.nav-item title="Pengaturan Daftar Ulang" icon="cog" :link="route('ppdb.daftar-ulang-settings')" />
        </nav>
    </div>
</div> 