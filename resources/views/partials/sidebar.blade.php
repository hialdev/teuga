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

                <!-- ---------------------------------- -->
                <!-- Accounting -->
                <!-- ---------------------------------- -->
                <li class="nav-small-cap mt-0">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Accounting</span>
                </li>
                <li class="sidebar-item {{ Route::is('master-account.*') ? 'selected' : '' }}">
                    <a href="{{route('master-account.index')}}" class="sidebar-link" aria-expanded="false">
                        <span>
                            <i class="ti ti-key"></i>
                        </span>
                        <span class="hide-menu">Master Account</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Route::is('account.*') ? 'selected' : '' }}">
                    <a href="{{route('account.index')}}" class="sidebar-link" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-list"></i>
                        </span>
                        <span class="hide-menu">Chart of Accounts</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Route::is('period.*') ? 'selected' : '' }}">
                    <a href="{{route('period.index')}}" class="sidebar-link" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar-event"></i>
                        </span>
                        <span class="hide-menu">Periode Transaksi</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Route::is('transaction.*') ? 'selected' : '' }}">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span class="d-flex">
                            <i class="ti ti-book-2"></i>
                        </span>
                        <span class="hide-menu">Jurnal Transaksi</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('transaction.index') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Semua Transaksi</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('transaction.add') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Input Transaksi</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-small-cap mt-0">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Special Account</span>
                </li>

                <li class="sidebar-item {{ Route::is('bank.*') ? 'selected' : '' }}">
                    <a href="{{route('bank.index')}}" class="sidebar-link" aria-expanded="false">
                        <span>
                            <i class="ti ti-credit-card"></i>
                        </span>
                        <span class="hide-menu">Bank Account</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Route::is('asset.*') ? 'selected' : '' }}">
                    <a href="{{route('asset.index')}}" class="sidebar-link" aria-expanded="false">
                        <span>
                            <i class="ti ti-asset"></i>
                        </span>
                        <span class="hide-menu">Aset Tetap</span>
                    </a>
                </li>

                <li class="nav-small-cap mt-0">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Generate Transaction From</span>
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

                <li class="nav-small-cap mt-0">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Reports</span>
                </li>
                <li class="sidebar-item {{ Route::is('report.invoice*') ? 'selected' : '' }}">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span class="d-flex">
                            <i class="ti ti-report-analytics"></i>
                        </span>
                        <span class="hide-menu">Laporan Keuangan</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('report.balance-sheet') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Balance Sheet</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('report.cash-flow') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Cash Flow</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('report.income-statement') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Income Statement</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('report.general-ledger') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">General Ledger</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('report.changes-in-equity') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Changes in Equity</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('report.receivable-and-payable') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Receivable and Payable</span>
                            </a>
                        </li>
                    </ul>
                </li>
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
