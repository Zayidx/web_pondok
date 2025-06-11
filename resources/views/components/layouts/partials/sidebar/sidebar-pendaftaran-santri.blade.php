<ul class="menu">
    <li class="sidebar-title">Menu</li>

    <li class="sidebar-item {{ Request::routeIs('e-ppdb.dashboard') ? 'active' : '' }}">
        <a href="{{ route('e-ppdb.dashboard') }}" class='sidebar-link'>
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="sidebar-title">Master PSB</li>

   
    <li class="sidebar-item {{ Request::routeIs('admin.master-periode.dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.master-periode.dashboard') }}" wire:navigate class='sidebar-link'>
        <i class="bi bi-clock"></i>
            <span>Dashboard Periode</span>
        </a>
    </li>
    <li class="sidebar-item {{ Request::routeIs('admin.master-psb.show-registrations') ? 'active' : '' }}">
        <a href="{{ route('admin.master-psb.show-registrations') }}" wire:navigate class='sidebar-link'>
        <i class="bi bi-people"></i>
            <span>List Santri Baru</span>
        </a>
    </li>
    <li class="sidebar-item {{ Request::routeIs('admin.master-psb.interview-list') ? 'active' : '' }}">
        <a href="{{ route('admin.master-psb.interview-list') }}" wire:navigate class='sidebar-link'>
        <i class="bi bi-megaphone"></i>
            <span>List Wawancara</span>
        </a>
    </li>
    <li class="sidebar-item {{ Request::routeIs('ppdb.dashboard-daftar-ulang') ? 'active' : '' }}">
        <a href="{{ route('ppdb.dashboard-daftar-ulang') }}" wire:navigate class='sidebar-link'>
        <i class="bi bi-arrow-counterclockwise"></i>
            <span>List Daftar Ulang</span>
        </a>
    </li>

    
    <li class="sidebar-item {{ Request::routeIs('admin.master-ujian.dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.master-ujian.dashboard') }}" wire:navigate class='sidebar-link'>
        <i class="bi bi-journal-text"></i> 
            <span>Dashboard Ujian</span>
        </a>
    </li>
    <li class="sidebar-item {{ Request::routeIs('admin.psb.ujian.hasil') ? 'active' : '' }}">
        <a href="{{ route('admin.psb.ujian.hasil') }}" wire:navigate class='sidebar-link'>
            <i class="bi bi-file-earmark-check-fill"></i> {{-- Icon dari Master Ujian --}}
            <span>Hasil Ujian Santri</span>
        </a>
    </li>
</ul>