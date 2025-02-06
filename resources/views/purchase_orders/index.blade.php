@extends('layouts.base')
@section('css')
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Pembelian ke Principal</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Semua Pembelian</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="/assets/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3 d-flex align-items-center gap-2 justify-content-between">
        <h1>Pembelian ke Principal</h1>
        <div style="aspect-ratio:1/1; width:3em; height:3em"
            class="bg-primary text-white d-flex align-items-center justify-content-center rounded-5 me-auto">
            {{ count($purchases) }}</div>
        <a href="{{ route('purchase-order.add') }}" class="btn btn-primary btn-al-primary">Tambah</a>
        {{-- <a href="{{route('pdf.preview.blade', ['bladePath' => 'Clients.stok'])}}" target="_blank" class="btn btn-danger"><i class="ti ti-file-download me-2"></i>Laporan Stok</a> --}}
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchase-order.index') }}" method="GET">
                <div class="row align-items-end mb-3 flex-wrap">
                    <div class="col-md-4 mb-2">
                        <label for="search" class="form-label">Filter Kata</label>
                        <input type="text" class="form-control" placeholder="Cari Kode / Deskripsi" name="search"
                            value="{{ $filter->q ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="field" class="form-label">Urutkan Berdasarkan</label>
                        <select name="field" id="field" class="form-select">
                            @foreach (getModelAttributes('PurchaseOrder', ['delivery_address_id', 'pickup_address_id', 'principal_id', 'principal_pic_id', 'transport_id', 'request_order_id']) as $atr)
                                <option value="{{ $atr }}" {{ $filter->field == $atr ? 'selected' : '' }}>
                                    {{ toPascalCase($atr) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="order" class="form-label">Dengan urutan</label>
                        <select name="order" id="order" class="form-select">
                            <option value="newest" {{ $filter->order == 'desc' ? 'selected' : '' }}>Terbaru / Terbesar
                            </option>
                            <option value="oldest" {{ $filter->order == 'asc' ? 'selected' : '' }}>Terlama / Terkecil
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="d-flex align-items-center gap-1">
                            <button type="submit" class="btn btn-primary w-100" style="white-space: nowrap">Apply</button>
                            <a href="{{ url()->current() }}" class="btn btn-secondary" style="white-space: nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2v2a8 8 0 1 0 4.5 1.385V8h-2V2h6v2H18a9.99 9.99 0 0 1 4 8" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table border text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Pembelian</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Deskripsi</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Permintaan Client</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Principal</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Pengangkutan</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Produk Diproses</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Kalkulasi</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Timestamp</h6>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $purchase)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                        <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                            {{ \Carbon\Carbon::parse($purchase->date)->format('d F Y') }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Permintaan
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ $purchase->code }}</h6>
                                    </div>
                                    @php
                                        $status = [
                                            '0' => ['label' => 'Pending','color' => 'secondary'],
                                            '1' => ['label' => 'Diproses','color' => 'warning',],
                                            '2' => ['label' => 'Selesai','color' => 'success'],
                                        ];

                                        $statusInvoice = [
                                            '0' => ['label' => 'Belum Ditagih / Stock','color' => 'secondary'],
                                            '1' => ['label' => 'Ditagih','color' => 'success'],
                                        ];
                                    @endphp
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Permintaan
                                        </div>
                                        <h6 class="fw-semibold fs-2 text-{{ $status[$purchase->status]['color'] }} mb-1" style="">{{ $status[$purchase->status]['label'] }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Penagihan Invoice
                                        </div>
                                        <h6 class="fw-semibold fs-2 text-{{ $statusInvoice[$purchase->generate_invoice]['color'] }} mb-1" style="">{{ $statusInvoice[$purchase->generate_invoice]['label'] }}</h6>
                                    </div>
                                </td>
                                <td>
                                    <div style="min-width: 10em">
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Deksripsi</div>
                                        <p class="mb-1 fs-2" style="white-space:normal !important;">{{ $purchase->description ?? 'tidak ada deskripsi' }}</p>
                                    </div>
                                </td>
                                <td>
                                    @if( $purchase->requestOrder )
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                        <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                            {{ \Carbon\Carbon::parse($purchase->requestOrder->date)->format('d F Y') }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Permintaan
                                        </div>
                                        <a href="{{route('request-order.setting', $purchase->requestOrder->id)}}" target="_blank" class="fw-semibold text-primary mb-1" style="">{{ $purchase->requestOrder->code }} <i class="ti ti-external-link"></i></a>
                                    </div>
                                    @php
                                        $status = [
                                            '0' => ['label' => 'Pending','color' => 'secondary'],
                                            '1' => ['label' => 'Diproses','color' => 'warning',],
                                            '2' => ['label' => 'Selesai','color' => 'success'],
                                        ];

                                        $statusInvoice = [
                                            '0' => ['label' => 'Belum Ditagih / Stock','color' => 'secondary'],
                                            '1' => ['label' => 'Ditagih','color' => 'success'],
                                        ];
                                    @endphp
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Permintaan
                                        </div>
                                        <h6 class="fw-semibold fs-2 text-{{ $status[$purchase->requestOrder->status]['color'] }} mb-1" style="">{{ $status[$purchase->requestOrder->status]['label'] }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Penagihan Invoice
                                        </div>
                                        <h6 class="fw-semibold fs-2 text-{{ $statusInvoice[$purchase->requestOrder->generate_invoice]['color'] }} mb-1" style="">{{ $statusInvoice[$purchase->requestOrder->generate_invoice]['label'] }}</h6>
                                    </div>
                                    @else
                                    <div class="fs-2">Tidak berdasarkan Permintaan Client</div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('principal.setting', $purchase->principal->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-building-skyscraper mb-0 fs-3"></i> {{ $purchase->principal->name }}
                                    </a>
                                    <div class="fw-normal fs-1 text-muted" style="">PIC Principal</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $purchase->pic->name }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $purchase->pic->email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $purchase->pic->phone ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    @if ( $purchase->transport )
                                    <a href="{{route('logistic.setting', $purchase->transport->logistic->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-truck-delivery mb-0 fs-3"></i> {{ $purchase->transport->logistic->name }}
                                    </a>
                                    <a href="javascript:void(0);" title="Klik untuk melihat detail" class="d-flex text-secondary align-items-center fs-2 mb-1 gap-2"
                                        data-bs-toggle="modal" data-bs-target="#detailDelivery-{{$purchase->id}}"
                                    >
                                        <i class="ti ti-exchange mb-0 fs-3"></i> Detail Antar Jemput
                                    </a>
                                    <!-- List Product modal -->
                                    <div class="modal fade " id="detailDelivery-{{$purchase->id}}" tabindex="-1"
                                        aria-labelledby="vertical-center-modal" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header d-flex align-items-center">
                                                    <h4 class="modal-title" id="myLargeModalLabel">
                                                        Pengantaran dan Penjemputan Barang
                                                    </h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body pt-0">
                                                    <div class="p-3 rounded-3 border border-dashed mb-2 border-secondary">
                                                        <div class="fs-2 text-muted">Penjemputan Barang</div>
                                                        <div>
                                                            <div class="fs-3" style="white-space: normal">{{$purchase->pickup->address.', '.$purchase->pickup->city.'. '.$purchase->pickup->postal_code}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="p-3 rounded-3 border border-dashed border-primary">
                                                        <div class="fs-2 text-muted">Pengantaran Barang</div>
                                                        <div>
                                                            <div class="fs-3" style="white-space: normal">
                                                            {{
                                                                $purchase->requestOrder 
                                                                ? $purchase->delivery->address.', '.$purchase->delivery->city.'. '.$purchase->delivery->postal_code 
                                                                : setting('company.address').'. '.setting('company.postal-code')  
                                                            }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fw-normal fs-1 text-muted" style="">Contact Person</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $purchase->transport->logistic->cp_name }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $purchase->transport->logistic->cp_email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $purchase->transport->logistic->cp_phone ?? '-' }}
                                    </div>
                                    @else
                                    <div>Diurus oleh Principal</div>
                                    @endif
                                </td>
                                <td>
                                    <button type="button"
                                        class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-secondary text-white rounded-3"
                                        data-bs-toggle="modal" data-bs-target="#produkModal-{{$purchase->id}}"><i
                                            class="fs-4 ti ti-package"></i> {{ count($purchase->products) }} Produk</button>

                                    <!-- List Product modal -->
                                    <div class="modal fade " id="produkModal-{{$purchase->id}}" tabindex="-1"
                                        aria-labelledby="vertical-center-modal" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header d-flex align-items-center">
                                                    <h4 class="modal-title" id="myLargeModalLabel">
                                                        Produk yang Dipesan
                                                    </h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body pt-0">
                                                    @foreach ($purchase->products as $purchaseProduct)
                                                        @php
                                                            $priceSale = $purchaseProduct->price_buy ?? 0; // Harga jual (diambil dari cart)
                                                            $subtotal = $priceSale * $purchaseProduct->qty; // Hitung subtotal awal
                                                        @endphp
                                                        <div class="border border-1 border-dashed border-primary {{$loop->index+1 == count($purchase->products) ? '' : 'mb-2'}} p-3 rounded-3">
                                                            <div
                                                                class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                                                <img src="{{ $purchaseProduct->product->image ? '/storage/' . $purchaseProduct->product->image : 'https://placehold.co/300?text=' . $purchaseProduct->product->name }}"
                                                                    alt="Image Product {{ $purchaseProduct->product->name }} in Cart" class="d-block rounded-2"
                                                                    style="width: 5em; height:5em; object-fit:cover">
                                                                <div>
                                                                    <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                                        {{ $purchaseProduct->product->name }}</div>
                                                                    <div class="text-muted fs-2 mb-2">
                                                                        {{ $purchaseProduct->product->description ?? 'tidak ada deskripsi' }}</div>
                                                                </div>
                                                                <div
                                                                    class="flex-grow-1 d-flex flex-column align-items-end gap-2 justify-content-between">
                                                                    <div class="fs-2 fw-semibold">Sub Total</div>
                                                                    <div class="fs-3 fw-bold subtotal">
                                                                        {{ formatRupiah($subtotal) }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-end gap-2">
                                                                <div>
                                                                    <label for="qty" class="text-muted fs-1">Memproses Sebanyak
                                                                        ({{ $purchaseProduct->product->unit->code }})</label>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        {{ $purchaseProduct->qty }}
                                                                    </div>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <label for="price_buy" class="text-muted fs-1">Dengan Harga Beli</label>
                                                                    <div>{{ formatRupiah($purchaseProduct->price_buy) }}</div>
                                                                </div>
                                                                <div>
                                                                    <label for="qty" class="text-muted fs-1">Dikemas Dengan </label>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        {{ $purchaseProduct->pack->name.' @ '.$purchaseProduct->pack->capacity.' '.$purchaseProduct->pack->unit->code }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <div class="pt-3 mt-3 border-top border-2">
                                                        <div class="d-flex align-items-center gap-2 justify-content-between">
                                                            <div class="fs-3 fw-semibold">Total</div>
                                                            @if($purchase->products->count() > 0)
                                                            <div class="fs-4 fw-bold">{{ formatRupiah($purchase->total_price) }}</div>
                                                            @else
                                                            <div class="fs-4 fw-bold">Lengkapi Data Dahulu <a href="{{ route('purchase-order.setting', $purchase->id).'#produk' }}" class="btn btn-sm btn-warning ms-2">Lengkapi</a></div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($purchase->products->count() > 0)
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Total Nilai Awal</div>
                                        <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ formatRupiah($purchase->total_price) }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Pajak</div>
                                        <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ $purchase->tax ?? '11' }}%</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Total Dengan Pajak</div>
                                        {{-- @php
                                            dd($purchase->total_price, $purchase->tax, $purchase->tax && (int)$purchase->tax != '0' ? $purchase->tax : 11, ));
                                        @endphp --}}
                                        <h6 class="fw-semibold fs-2 text-primary mb-1">
                                            {{ 
                                                (int) $purchase->total_price_taxed && (int) $purchase->total_price_taxed != 0 
                                                    ? formatRupiah($purchase->total_price_taxed) 
                                                    : formatRupiah((int) $purchase->total_price + ((int) $purchase->total_price * (($purchase->tax && (int) $purchase->tax != 0 ? $purchase->tax : 11) / 100)))
                                            }}
                                        </h6>
                                    </div>
                                    @else
                                    Lengkapi Data Dahulu
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('purchase-order.setting', $purchase->id).'#lampiran'}}"
                                        class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-danger text-white rounded-3">
                                        <i class="fs-4 ti ti-files"></i> {{ count($purchase->files) }} File
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-start gap-2">
                                        <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">
                                            Updated
                                            at
                                            : {{ $purchase->updated_at }}</div>
                                        <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">
                                            Created
                                            at
                                            : {{ $purchase->created_at }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown dropstart">
                                        <a href="#" class="text-muted" id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if($purchase->status != '2')
                                            <li>
                                                <button type="button" class="dropdown-item d-flex {{$purchase->status == 0 ? 'text-warning' : 'text-success'}} align-items-center gap-3"
                                                    data-bs-toggle="modal" data-bs-target="#processModal-{{$purchase->id}}"><i
                                                        class="fs-4 ti {{$purchase->status == 0 ? 'ti-loader-3' : 'ti-check'}}"></i>{{$purchase->status == 0 ? 'Proses Pembelian' : 'Selesaikan Pembelian'}}</button>
                                            </li>
                                            @endif
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3 text-danger"
                                                    onclick="seePDF('pdf.po','{{$purchase->id}}')"><i
                                                        class="fs-4 ti ti-printer"></i>Cetak PO</button>
                                            </li>
                                            @if($purchase->status == '2')
                                            <li>
                                                <button type="button" class="dropdown-item d-flex text-white bg-primary align-items-center gap-3"
                                                    data-bs-toggle="modal" data-bs-target="#invoicingModal-{{$purchase->id}}"><i
                                                        class="fs-4 ti ti-credit-card"></i>Generate Invoice</button>
                                            </li>
                                            @endif
                                            <li>
                                                <a href="{{ route('purchase-order.setting', $purchase->id) . '#data' }}"
                                                    class="dropdown-item text-primary d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-settings"></i>Kelola</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('purchase-order.setting', $purchase->id) . '#produk' }}"
                                                    class="dropdown-item text-secondary d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-package"></i>Kelola Produk</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('purchase-order.setting', $purchase->id) . '#lampiran' }}"
                                                    class="dropdown-item text-secondary d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-files"></i>Kelola File</a>
                                            </li>
                                            <li>
                                                <button type="button"
                                                    class="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal-{{ $purchase->id }}"><i
                                                        class="fs-4 ti ti-trash"></i>Delete</button>
                                            </li>
                                        </ul>
                                    </div>

                                    @if($purchase->status != '2')
                                    <!-- Process Modal -->
                                    <div class="modal fade" id="processModal-{{$purchase->id}}" tabindex="-1"
                                        aria-labelledby="vertical-center-modal" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header d-flex align-items-center">
                                                    <h4 class="modal-title" id="myLargeModalLabel" style="white-space: normal">
                                                        {{$purchase->status == 0 ? 'Proses Pembelian' : 'Selesaikan Pembelian'}} {{$purchase->code}}
                                                    </h4>
                                                    <button type="button" class="btn-close mb-auto" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body pt-0">
                                                    <form action="{{ route('purchase-order.process', $purchase->id) }}" method="POST">
                                                        @csrf
                                                        <p class="text-muted" style="white-space: normal">Pastikan Keadaan lapangan sudah sesuai, dan dapat dipertanggung jawabkan dengan baik untuk <strong>{{$purchase->status == 0 ? 'Proses Pembelian' : 'Selesaikan Pembelian'}} dengan kode {{$purchase->code}}</strong></p>
                                                        <div class="d-flex gap-1 align-items-center justify-content-end">
                                                            <button type="submit"
                                                                class="btn {{$purchase->status == 0 ? 'btn-warning' : 'btn-success'}}">{{$purchase->status == 0 ? 'Proses Pembelian' : 'Selesaikan Pembelian'}}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($purchase->status == '2')
                                    <!-- invoicing Modal -->
                                    <div class="modal fade" id="invoicingModal-{{$purchase->id}}" tabindex="-1"
                                        aria-labelledby="vertical-center-modal" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header d-flex align-items-center">
                                                    <h4 class="modal-title" id="myLargeModalLabel" style="white-space: normal">
                                                        Buat Penagihan / Invoice {{$purchase->code}}
                                                    </h4>
                                                    <button type="button" class="btn-close mb-auto" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body pt-0">
                                                    <form action="{{ route('purchase-order.generate', $purchase->id) }}" method="POST">
                                                        @csrf
                                                        <p class="text-muted" style="white-space: normal">Pastikan Keadaan lapangan sudah sesuai, dan dapat dipertanggung jawabkan dengan baik untuk <strong>Buat Penagihan / Invoice dengan kode Pembelian ke Principal {{$purchase->code}} (sebagai Hutang)</strong>. Seluruh Invoice akan dibuat termasuk Permintaan (sebagai Piutang) dan Pengangkutan (sebagai Hutang)</p>
                                                        <div class="d-flex gap-1 align-items-center justify-content-end">
                                                            <button type="submit"
                                                                class="btn btn-primary w-100">Ya, Hasilkan Invoice</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Delete Modal -->
                                    <div id="deleteModal-{{ $purchase->id }}" class="modal fade" tabindex="-1"
                                        aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                            <div class="modal-content p-3 modal-filled bg-danger">
                                                <div class="modal-header modal-colored-header text-white">
                                                    <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                                        Yakin ingin menghapus Pembelian {{ $purchase->code }} ?
                                                    </h4>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="width: fit-content; white-space:normal">
                                                    <h5 class="mt-0 text-white">Pembelian ke Principal {{ $purchase->code }}
                                                        akan dihapus</h5>
                                                    <p class="text-white">Segala data yang berkaitan dengan Pembelian ke Principal
                                                        tersebut juga akan dihapus secara permanen.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <form action="{{ route('purchase-order.destroy', $purchase->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-dark">Ya, Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
@endsection
