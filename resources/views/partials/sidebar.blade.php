<!-- Sidebar Start -->
<aside class="left-sidebar with-vertical">
    <div><!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="text-nowrap logo-img">
                <img src="{{filePath(setting('site.logo'))}}" class="{{env('APP_NAME')}}" alt="{{env('APP_NAME')}} Logo in Sidebar"
                    style="height: 3em; width:auto; object-fit:contain;" />
            </a>
            <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
                <i class="ti ti-x"></i>
            </a>
        </div>


        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
                <!-- ---------------------------------- -->
                <!-- Home -->
                <!-- ---------------------------------- -->
                <li class="nav-small-cap mt-2">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item {{ Route::is('home') ? 'selected' : '' }}">
                    <a href="{{ route('home') }}" class="sidebar-link" aria-expanded="false">
                        <span>
                            <i class="ti ti-home"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                
                @if(auth()->user() && auth()->user()->getRoleNames()[0] != 'pelanggan')
                    <li class="nav-small-cap mt-2">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Data Master</span>
                    </li>
                    <li class="sidebar-item {{ Route::is('product.*') ? 'selected' : '' }}">
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <span class="d-flex">
                                <i class="ti ti-package"></i>
                            </span>
                            <span class="hide-menu">Produk</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="{{ route('product.index') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Semua Produk</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('product.add') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Tambah Produk</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('unit.index') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Satuan Produk</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('pack.index') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Pengemasan</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item {{ Route::is('principal.*') ? 'selected' : '' }}">
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <span class="d-flex">
                                <i class="ti ti-building-factory"></i>
                            </span>
                            <span class="hide-menu">Principal</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="{{ route('principal.index') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Semua Principal</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('principal.add') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Tambah Baru</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item {{ Route::is('client.*') ? 'selected' : '' }}">
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <span class="d-flex">
                                <i class="ti ti-building-skyscraper"></i>
                            </span>
                            <span class="hide-menu">Client</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="{{ route('client.index') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Semua Client</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('client.add') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Tambah Baru</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item {{ Route::is('logistic.*') ? 'selected' : '' }}">
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <span class="d-flex">
                                <i class="ti ti-truck-delivery"></i>
                            </span>
                            <span class="hide-menu">Logistic</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="{{ route('logistic.index') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Semua Logistic</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('logistic.add') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Tambah Baru</span>
                                </a>
                            </li>
                        </ul>
                    </li>


                    <li class="nav-small-cap mt-0">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Business Process</span>
                    </li>

                    <li class="sidebar-item {{ Route::is('request-order.*') && !Route::is('request-order.invoice*') ? 'selected' : '' }}">
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <span class="d-flex">
                                <i class="ti ti-building-skyscraper"></i>
                            </span>
                            <span class="hide-menu">Permintaan Client</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="{{ route('request-order.index') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Semua Permintaan</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('request-order.add') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Tambah Baru</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item {{ Route::is('purchase-order.*') && !Route::is('purchase-order.invoice*') ? 'selected' : '' }}">
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <span class="d-flex">
                                <i class="ti ti-building-factory"></i>
                            </span>
                            <span class="hide-menu">Pembelian ke Principal</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="{{ route('purchase-order.index') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Semua Pembelian</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('purchase-order.add') }}" class="sidebar-link">
                                    <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                    </div>
                                    <span class="hide-menu">Tambah Baru</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item {{ Route::is('transport.*') && !Route::is('transport.invoice*') ? 'selected' : '' }}">
                        <a href="{{ route('transport.index') }}" class="sidebar-link" aria-expanded="false">
                            <span>
                                <i class="ti ti-truck-delivery"></i>
                            </span>
                            <span class="hide-menu">Pengangkutan</span>
                        </a>
                    </li>

                    <li class="nav-small-cap mt-0">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Invoice / Penagihan</span>
                    </li>

                    <li class="sidebar-item {{ Route::is('request-order.invoice*') ? 'selected' : '' }}">
                        <a href="{{ route('request-order.invoice') }}" class="sidebar-link" aria-expanded="false">
                            <span>
                                <i class="ti ti-building-skyscraper"></i>
                            </span>
                            <span class="hide-menu">Invoice Permintaan</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ Route::is('purchase-order.invoice*') ? 'selected' : '' }}">
                        <a href="{{ route('purchase-order.invoice') }}" class="sidebar-link" aria-expanded="false">
                            <span>
                                <i class="ti ti-building-factory"></i>
                            </span>
                            <span class="hide-menu">Invoice Pembelian</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ Route::is('transport.invoice*') ? 'selected' : '' }}">
                        <a href="{{ route('transport.invoice') }}" class="sidebar-link" aria-expanded="false">
                            <span>
                                <i class="ti ti-truck-delivery"></i>
                            </span>
                            <span class="hide-menu">Invoice Pengangkutan</span>
                        </a>
                    </li>

                    
                @endif
            </ul>
        </nav>
        <div class="fixed-profile p-3 mx-4 mb-2 bg-secondary-subtle rounded mt-3">
            <div class="hstack gap-3">
                <div class="john-img">
                    <img src="{{ auth()->user() && auth()->user()->image ? filePath(auth()->user()->image) : '/assets/images/profile/user-1.jpg' }}"
                        class="rounded-circle" width="40" height="40" style="object-fit: cover"
                        alt="Image User {{ auth()->user() ? auth()->user()->name : 'Pengunjung' }}" />
                </div>
                <div class="john-title">
                    <h6 class="mb-0 fs-2 fw-semibold">{{ auth()->user() ? auth()->user()->name : 'Pengunjung' }}</h6>
                    <span class="fs-2">{{ auth()->user() ? auth()->user()->getRoleNames()[0] : 'Masuk Dahulu' }}</span>
                </div>
                @if(auth()->user())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="border-0 bg-transparent text-primary ms-auto" tabindex="0"
                        aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Keluar">
                        <i class="ti ti-power fs-6"></i>
                    </button>
                </form>
                @else
                    <a href="{{route('login')}}" class="border-0 bg-transparent text-primary ms-auto" tabindex="0"
                        aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Masuk">
                        <i class="ti ti-login fs-6"></i>
                    </a>
                @endif
            </div>
        </div>
        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
    </div>
</aside>
<!--  Sidebar End -->
