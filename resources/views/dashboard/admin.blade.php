@extends ('layouts.base')
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
                            <div class="rounded-circle overflow-hidden me-6" style="aspect-ratio:1/1 !important; object-position:center; object-fit:cover;">
                                <img src="{{ auth()->user()->image ? filePath(auth()->user()->image) : '/assets/images/profile/user-1.jpg'}}" alt="" width="70"
                                    height="70" class="object-fit-cover d-block" style="aspect-ratio:1/1 !important;">
                            </div>
                            <h5 class="fw-semibold mb-0 fs-5">Selamat Datang {{auth()->user()->name}}!</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="border-end pe-4 border-muted border-opacity-10">
                                    <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center">
                                        {{formatRupiah(\App\Models\RequestOrder::all()->sum('total_price_taxed'))}}
                                    </h3>
                                    <p class="mb-0 text-dark fs-2">Total Permintaan Client</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="">
                                    <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center">
                                        {{formatRupiah(\App\Models\PurchaseOrder::all()->sum('total_price_taxed') + \App\Models\Transport::all()->sum('total_price_taxed'))}}
                                    </h3>
                                    <p class="mb-0 text-dark fs-2">Total Pembelian Principal + Pengangkutan</p>
                                </div>
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
        <div class="row">
            <!-- Column -->
            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-3 flex-row flex-wrap">
                            <div
                                class="round-40 rounded-circle text-white d-flex align-items-center justify-content-center text-bg-success">
                                <i class="ti ti-building-skyscraper fs-6"></i>
                            </div>
                            <div class="ms-sm-3 align-self-center">
                                <h4 class="mb-0 fs-5">Pembayaran Client</h4>
                                <span class="text-muted">Total Permintaan Client Dibayar</span>
                            </div>
                            <div class="align-self-center mt-3 text-start border-top pt-3 w-100">
                                <h2 class="fs-7 fw-bold mb-0" style="white-space: nowrap">
                                    {{ formatRupiah(\App\Models\RequestOrder::totalProcessed()) }}</h2>
                                <h6 class="text-{{ \App\Models\RequestOrder::remainingProcessed() == '0' ? 'success' : 'danger'}} fs-2 mt-1">
                                    Sisa {{ formatRupiah(\App\Models\RequestOrder::remainingProcessed()) }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-3 flex-row flex-wrap">
                            <div
                                class="round-40 rounded-circle text-white d-flex align-items-center justify-content-center text-bg-warning">
                                <i class="ti ti-truck-return fs-6"></i>
                            </div>
                            <div class="ms-sm-3 align-self-center">
                                <h4 class="mb-0 fs-5">Pembayaran Logistic</h4>
                                <span class="text-muted">Total Pembayaran Pengangkutan</span>
                            </div>
                            <div class="align-self-center mt-3 text-start border-top pt-3 w-100">
                                <h2 class="fs-7 fw-bold mb-0" style="white-space: nowrap">
                                    {{ formatRupiah(\App\Models\Transport::totalProcessed()) }}</h2>
                                <h6 class="text-{{ \App\Models\Transport::remainingProcessed() == '0' ? 'success' : 'danger'}} fs-2 mt-1">
                                    Sisa {{ formatRupiah(\App\Models\Transport::remainingProcessed()) }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-3 flex-row flex-wrap">
                            <div
                                class="round-40 rounded-circle me-2 me-sm-0 text-white d-flex align-items-center justify-content-center text-bg-info">
                                <i class="ti ti-building-factory fs-6"></i>
                            </div>
                            <div class="ms-sm-3 align-self-center">
                                <h4 class="mb-0 fs-5">Pembayaran Principal</h4>
                                <span class="text-muted">Total Pembayaran ke Principal</span>
                            </div>
                            <div class="align-self-center mt-3 text-start border-top pt-3 w-100">
                                <h2 class="fs-7 fw-bold mb-0" style="white-space: nowrap">
                                    {{ formatRupiah(\App\Models\PurchaseOrder::totalProcessed()) }}</h2>
                                <h6 class="text-{{ \App\Models\PurchaseOrder::remainingProcessed() == '0' ? 'success' : 'danger'}} fs-2 mt-1">
                                    Sisa {{ formatRupiah(\App\Models\PurchaseOrder::remainingProcessed()) }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
    </section>

    <section>
        <!--  Owl carousel -->
        <div class="owl-carousel counter-carousel owl-theme">
        @php
            $classArrays = [
                'bg-primary-subtle text-primary',
                'bg-secondary-subtle',
                'bg-info-subtle',
            ];
        @endphp
        {{-- {{(string) $classArrays[$loop->index]}} --}}
            @forelse (auth()->user()->applications() as $app)
            <div class="item">
                <a href="{{urlApp($app->code)}}" class="card zoom-in {{$app->url == url()->current() ? 'bg-danger-subtle shadow-md' : 'bg-primary-subtle text-primary shadow-none'}}" target="_blank" style="aspect-ratio:1/1 !important">
                    <div class="card-body">
                        <div class="text-center">
                            @if ($app->icon)
                                <i class="ti ti-{{$app->icon}} fs-12"></i>
                            @else
                                <img src="{{$app->image ? filePath($app->image) : filePath(setting('site.logo'))}}" width="50" height="50" class="mb-3 object-fit-contain"
                                alt="" />
                            @endif
                            <p class="fw-semibold fs-5 mt-4 mb-1">
                                {{$app->name}}
                            </p>
                            <div class="fw-semibold fs-2 line-clamp line-clamp-2 text-muted mb-0">{{$app->url}}</div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="p-5 rounded-4 border border-dashed text-center">Tidak ada akses aplikasi</div>
            @endforelse
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
