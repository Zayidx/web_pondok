<ul class="menu">
    <li class="sidebar-title">Menu</li>

    <li class="sidebar-item {{ Request::routeIs('spp.dashboard') ? 'active' : '' }}">
        <a href="{{ route('spp.dashboard') }}" class='sidebar-link'>
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="sidebar-title">Pembayaran</li>

    <li class="sidebar-item {{ Request::routeIs('spp.pembayaran') ? 'active' : '' }}">
        <a href="{{ route('spp.pembayaran') }}" class='sidebar-link'>
            <i class="bi bi-cash-coin"></i>
            <span>Pembayaran SPP Santri</span>
        </a>
    </li>
    <li class="sidebar-item {{ Request::routeIs('spp.list-item-pembayaran') ? 'active' : '' }}">
        <a href="{{ route('spp.list-item-pembayaran') }}" class='sidebar-link'>
            <i class="bi bi-list-check"></i>
            <span>List Item Pembayaran</span>
        </a>
    </li>
    <li class="sidebar-item {{ Request::routeIs('spp.pembayaran-cicilan') ? 'active' : '' }}">
        <a href="{{ route('spp.pembayaran-cicilan') }}" class='sidebar-link'>
            <i class="bi bi-cash-stack"></i>
            <span>Pembayaran Cicilan</span>
        </a>
    </li>
    <li class="sidebar-item {{ Request::routeIs('spp.tambah-santri') ? 'active' : '' }}">
        <a href="{{ route('spp.tambah-santri') }}" class='sidebar-link'>
            <i class="bi bi-cash-stack"></i>
            <span>Tambah Santri</span>
        </a>
    </li>

    <li class="sidebar-title">Laporan Keuangan</li>

    <li class="sidebar-item {{ Request::routeIs('spp.laporan-keuangan') ? 'active' : '' }}">
        <a href="{{ route('spp.laporan-keuangan') }}" class='sidebar-link'>
            <i class="bi bi-bar-chart-fill"></i>
            <span>Laporan keuangan SPP</span>
        </a>
    </li>
</ul>
