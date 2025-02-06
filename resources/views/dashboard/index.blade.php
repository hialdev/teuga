@extends('layouts.base')
@section('css')
    <link rel="stylesheet" href="/assets/libs/owl.carousel/dist/assets/owl.carousel.min.css" />
@endsection
@section('content')
    <section>
        <div class="card w-100 bg-primary-subtle overflow-hidden shadow-none">
            <div class="card-body position-relative">
                <div class="row">
                    <div class="col-sm-7">
                        <div class="d-flex align-items-center mb-7">
                            <div class="rounded-circle overflow-hidden me-6">
                                <img src="{{ auth()->user() && auth()->user()->image ? '/storage/' . auth()->user()->image : '/assets/images/profile/user-1.jpg' }}"
                                    alt="" width="40" height="40" class="object-fit-cover">
                            </div>
                            <h5 class="fw-semibold mb-0 fs-5">Selamat Datang
                                {{ auth()->user() ? auth()->user()->name : '"Silahkan Login"' }}!</h5>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="border-end pe-4 border-muted border-opacity-10">
                                <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center">
                                    123
                                </h3>
                                <p class="mb-0 text-dark">Pesanan Barang Anda</p>
                            </div>
                            <div class="ps-4">
                                <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center">
                                    123
                                </h3>
                                <p class="mb-0 text-dark">Pesanan Khusus</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="welcome-bg-img mb-n7 text-end">
                            <img src="/assets/images/backgrounds/welcome-bg.svg" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="row gx-3">
            <div class="col-md-4 col-lg-2 col-6">
                <div class="card text-white text-bg-dark rounded">
                    <div class="card-body p-4">
                        <span>
                            <i class="ti ti-layout-grid fs-8"></i>
                        </span>
                        <h3 class="card-title mt-3 mb-0 text-white">
                            123
                        </h3>
                        <p class="card-text text-white-50 fs-3 fw-normal">
                            Total Pesanan Selesai
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2 col-6">
                <div class="card text-dark text-bg-light shadow rounded">
                    <div class="card-body p-4">
                        <span>
                            <i class="ti ti-credit-card-off fs-8"></i>
                        </span>
                        <h3 class="card-title mt-3 mb-0 text-dark">123</h3>
                        <p class="card-text text-dark-50 fs-3 fw-normal">
                            Pesanan Belum Dibayar
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2 col-6">
                <div class="card text-white text-bg-warning rounded">
                    <div class="card-body p-4">
                        <span>
                            <i class="ti ti-clock fs-8"></i>
                        </span>
                        <h3 class="card-title mt-3 mb-0 text-white">123</h3>
                        <p class="card-text text-white-50 fs-3 fw-normal">
                            Pesanan Dalam Proses
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2 col-6">
                <div class="card text-white text-bg-info rounded">
                    <div class="card-body p-4">
                        <span>
                            <i class="ti ti-credit-card fs-8"></i>
                        </span>
                        <h3 class="card-title mt-3 mb-0 text-white">123</h3>
                        <p class="card-text text-white-50 fs-3 fw-normal">
                            Pesanan Khusus belum Lunas
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2 col-6">
                <div class="card text-white text-bg-secondary rounded">
                    <div class="card-body p-4">
                        <span>
                            <i class="ti ti-tag fs-8"></i>
                        </span>
                        <h3 class="card-title mt-3 mb-0 text-white">122</h3>
                        <p class="card-text text-white-50 fs-3 fw-normal">
                            Penentapan Harga Pesanan Khusus
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2 col-6">
                <div class="card text-white text-bg-danger rounded">
                    <div class="card-body p-4">
                        <span>
                            <i class="ti ti-truck-return fs-8"></i>
                        </span>
                        <h3 class="card-title mt-3 mb-0 text-white">123</h3>
                        <p class="card-text text-white-50 fs-3 fw-normal">
                            Total Pengembalian Pesanan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="row">
            <div class="col-md-4 d-flex align-items-stretch">
                <a href="{{route('home')}}" class="card text-bg-primary text-white w-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-layout-grid display-6"></i>
                            <div class="ms-auto">
                                <i class="ti ti-arrow-right fs-8"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h4 class="card-title mb-1 text-white">
                                Lihat Etalase
                            </h4>
                            <h6 class="card-text fw-normal text-white-50">
                                Kami memiliki banyak produk yang siap kamu pesan
                            </h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 d-flex align-items-stretch">
                <a href="{{route('home')}}" class="card text-bg-info text-white w-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-shopping-cart display-6"></i>
                            <div class="ms-auto">
                                <i class="ti ti-arrow-right fs-8"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h4 class="card-title mb-1 text-white">Kelola Pesanan</h4>
                            <h6 class="card-text fw-normal text-white-50">
                                Lihat Pesanan Barang Anda
                            </h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 d-flex align-items-stretch">
                <a href="{{route('home')}}" class="card text-bg-secondary text-white w-100 card-hover">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-checklist display-6"></i>
                            <div class="ms-auto">
                                <i class="ti ti-arrow-right fs-8"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h4 class="card-title mb-1 text-white">
                                Kelola Pesanan Khusus
                            </h4>
                            <h6 class="card-text fw-normal text-white-50">
                                Pesanan sesuai dengan maumu, kamu yang menentukan semuanya, biar kita wujudkan
                            </h6>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="/assets/libs/owl.carousel/dist/owl.carousel.min.js"></script>
    <script src="/assets/libs/apexcharts/dist/apexcharts.min.js"></script>

    <script>
        $(function() {
            $(".counter-carousel").owlCarousel({
                loop: true,
                rtl: true,
                margin: 30,
                mouseDrag: true,
                autoplay: true,
                autoplayDuration: 2000,
                autoplayHoverPause: true,
                dots: false,
                nav: false,

                responsive: {
                    0: {
                        items: 2,
                        loop: true,
                    },
                    576: {
                        items: 2,
                        loop: true,
                    },
                    768: {
                        items: 3,
                        loop: true,
                    },
                    1200: {
                        items: 5,
                        loop: true,
                    },
                    1400: {
                        items: 6,
                        loop: true,
                    },
                },
            });
        })
    </script>
@endsection
