<ul class="menu">
    <li class="sidebar-title">Menu</li>

    <li wire:click='$refresh' class="sidebar-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>
    </li>

    {{-- Data Pendaftaran Santri --}}
    <li class="sidebar-title">Data Pendaftaran Santri</li>
       

    {{-- Master Pendaftaran Santri Baru --}}
    <li class="sidebar-item has-sub {{ Request::routeIs('admin.master-psb*') ? 'active' : '' }}">
        <a href="" class='sidebar-link'>
            <i class="bi bi-journal-text"></i>
            <span>Master PSB</span>
        </a>

        <ul class="submenu ">
            <li class="submenu-item  ">
                <a href="{{ route('admin.master-periode.dashboard') }}" wire:navigate  class="submenu-link {{ Request::routeIs('admin.master-periode.dashboard') ? 'text-primary fs-6' : '' }}">Periode Ujian</a>
            </li>
            <li class="submenu-item  ">
                <a href="{{ route('admin.master-psb.show-registrations') }}" wire:navigate  class="submenu-link {{ Request::routeIs('admin.show-registrations') ? 'text-primary fs-6' : '' }}">List Santri Baru</a>
            </li>
            <li class="submenu-item  ">
                <a href="{{ route('admin.master-psb.interview-list') }}" wire:navigate  class="submenu-link {{ Request::routeIs('admin.interview-list') ? 'text-primary fs-6' : '' }}">Wawancara Santri Baru</a>
            </li>
            <li class="submenu-item  ">
                <a href="{{ route('admin.master-ujian.dashboard') }}" wire:navigate class="submenu-link {{ Request::routeIs('admin.ujian.dashboard') ? 'text-primary fs-6' : '' }}">List Ujian</a>
            </li>
            <li class="submenu-item">
                <a href="{{ route('admin.psb.ujian.hasil') }}" wire:navigate class="submenu-link {{ Request::routeIs('admin.psb.ujian.hasil') ? 'text-primary fs-6' : '' }}">Hasil Ujian Santri</a>
            </li>
        </ul>
 
    </li>

</ul>
