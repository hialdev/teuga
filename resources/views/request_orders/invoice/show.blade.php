@extends('layouts.base')
@section('css')
<link rel="stylesheet" href="{{env('APP_URL')}}/assets/libs/select2/dist/css/select2.min.css">
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Kelola Invoice Permintaan Client {{$roInvoice->code}}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('request-order.invoice') }}">Invoice Permintaan Client</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola Invoice Permintaan Client</li>
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-accordion p-3 border border-2 rounded-3 border-dashed">
                        <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between" style="cursor: pointer">
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Invoice
                                </div>
                                <h6 class="fw-semibold text-primary mb-1" style="">{{ $roInvoice->code }}</h6>
                            </div>
                            @php
                                $status = [
                                    '0' => ['label' => 'Pending','color' => 'secondary'],
                                    '1' => ['label' => 'Diproses','color' => 'warning',],
                                    '2' => ['label' => 'Selesai','color' => 'success'],
                                ];
                            @endphp
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="">Status Pembayaran
                                </div>
                                <h6 class="fw-semibold fs-2 text-{{ $status[$roInvoice->payment_status]['color'] }} mb-1" style="">{{ $status[$roInvoice->payment_status]['label'] }}</h6>
                            </div>
                            <div class="flex-grow-1 text-end">
                                <div class="fs-1 text-muted">Klik untuk melihat detail</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="w-100">
                                <div class="progress mt-1">
                                    <div class="progress-bar progress-bar-striped text-bg-{{$roInvoice->payment_percentage && $roInvoice->payment_percentage == '100' ? 'success' : 'info' }} progress-bar-animated" role="progressbar"
                                        aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: {{$roInvoice->payment_percentage ?? '0'}}%">
                                    </div>
                                </div>
                            </div>
                            <div class="fs-2" style="white-space: nowrap"><strong>{{$roInvoice->payment_percentage ?? '0'}}%</strong> Dibayar</div>
                        </div>
                    </div>
                    <div class="btn-accordion-content row mt-3">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Invoice
                                </div>
                                <h6 class="fw-semibold text-primary fs-5" style="">{{ $roInvoice->code }}</h6>
                            </div>
                            <hr>
                            <h6 class="mb-2">Pembelian Ke Principal</h6>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                    {{ \Carbon\Carbon::parse($roInvoice->purchaseOrder->date)->format('d F Y') }}</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Pembelian
                                </div>
                                <a class="d-flex align-items-center gap-2 mb-1" href="{{route('purchase-order.setting', $roInvoice->purchaseOrder->id)}}">
                                    <h6 class="fw-semibold text-primary mb-0" style="">{{ $roInvoice->purchaseOrder->code }}</h6>
                                    <i class="ti ti-external-link"></i>
                                </a>
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
                                <h6 class="fw-semibold fs-2 text-{{ $status[$roInvoice->purchaseOrder->status]['color'] }} mb-1" style="">{{ $status[$roInvoice->purchaseOrder->status]['label'] }}</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="">Status Penagihan Invoice
                                </div>
                                <h6 class="fw-semibold fs-2 text-{{ $statusInvoice[$roInvoice->purchaseOrder->generate_invoice]['color'] }} mb-1" style="">{{ $statusInvoice[$roInvoice->purchaseOrder->generate_invoice]['label'] }}</h6>
                            </div>
                            <div style="min-width: 10em">
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Deksripsi</div>
                                <p class="mb-1 fs-2" style="white-space:normal !important;">{{ $roInvoice->purchaseOrder->description ?? 'tidak ada deskripsi' }}</p>
                            </div>
                            <hr>
                            <h6>Permintaan Client (Request Order)</h6>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                    {{ \Carbon\Carbon::parse($roInvoice->purchaseOrder->requestOrder->date)->format('d F Y') }}</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Permintaan
                                </div>
                                <h6 class="fw-semibold text-primary mb-1" style="">{{ $roInvoice->purchaseOrder->requestOrder->code }}</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Referensi
                                    Permintaan (PO)</div>
                                <a href="{{ $roInvoice->purchaseOrder->requestOrder->attachment ? '/storage/'.$roInvoice->purchaseOrder->requestOrder->attachment : '#' }}" class="badge fs-2 mb-1 bg-primary-subtle text-primary border-primary">
                                    <i class="ti ti-file"></i>
                                    {{ $roInvoice->purchaseOrder->requestOrder->attachment ? $roInvoice->purchaseOrder->requestOrder->no_refrence : 'Tidak ada Lampiran'}}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="">
                                <h6>Memesan Ke Principal</h6>
                                <a href="{{route('principal.setting', $roInvoice->purchaseOrder->principal->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                    <i class="ti ti-building-skyscraper mb-0 fs-3"></i> {{ $roInvoice->purchaseOrder->principal->name }}
                                </a>
                                <div class="fw-normal fs-1 text-muted" style="">PIC Principal</div>
                                <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                    <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $roInvoice->purchaseOrder->pic->name }}
                                </div>
                                <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                    <i class="ti ti-mail mb-0 fs-3"></i> {{ $roInvoice->purchaseOrder->pic->email ?? '-' }}
                                </div>
                                <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                    <i class="ti ti-phone mb-0 fs-3"></i> {{ $roInvoice->purchaseOrder->pic->phone ?? '-' }}
                                </div>
                            </div>
                            <hr>
                            <div class="">
                                @if($roInvoice->purchaseOrder->transport)
                                    <h6>Dengan Detail Logistik / Pengangkutan</h6>
                                    <a href="{{route('logistic.setting', $roInvoice->purchaseOrder->transport->logistic->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-truck-delivery mb-0 fs-3"></i> {{ $roInvoice->purchaseOrder->transport->logistic->name }}
                                    </a>
                                    <a href="javascript:void(0);" title="Klik untuk melihat detail" class="d-flex text-secondary align-items-center fs-2 mb-1 gap-2"
                                        data-bs-toggle="modal" data-bs-target="#detailDelivery-{{$roInvoice->purchaseOrder->id}}"
                                    >
                                        <i class="ti ti-exchange mb-0 fs-3"></i> Detail Antar Jemput
                                    </a>
                                    <!-- List Product modal -->
                                    <div class="modal fade " id="detailDelivery-{{$roInvoice->purchaseOrder->id}}" tabindex="-1"
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
                                                            <div class="fs-3">{{$roInvoice->purchaseOrder->pickup->address.', '.$roInvoice->purchaseOrder->pickup->city.'. '.$roInvoice->purchaseOrder->pickup->postal_code}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="p-3 rounded-3 border border-dashed border-primary">
                                                        <div class="fs-2 text-muted">Pengantaran Barang</div>
                                                        <div>
                                                            <div class="fs-3">{{$roInvoice->purchaseOrder->delivery->address.', '.$roInvoice->purchaseOrder->delivery->city.'. '.$roInvoice->purchaseOrder->delivery->postal_code}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fw-normal fs-1 text-muted" style="">Contact Person</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $roInvoice->purchaseOrder->transport->logistic->cp_name }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $roInvoice->purchaseOrder->transport->logistic->cp_email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $roInvoice->purchaseOrder->transport->logistic->cp_phone ?? '-' }}
                                    </div>
                                
                                @endif

                                <hr>
                                <h6>Timestamp</h6>
                                <div class="d-flex flex-column align-items-start gap-2">
                                    <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">
                                        Updated
                                        at
                                        : {{ $roInvoice->purchaseOrder->updated_at }}</div>
                                    <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">
                                        Created
                                        at
                                        : {{ $roInvoice->purchaseOrder->created_at }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="mb-3">
                                <h6>Produk yang diproses</h6>
                                <button type="button"
                                    class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-secondary text-white rounded-3"
                                    data-bs-toggle="modal" data-bs-target="#produkModal-{{$roInvoice->purchaseOrder->id}}"><i
                                        class="fs-4 ti ti-package"></i> {{ count($roInvoice->purchaseOrder->products) }} Produk</button>

                                <!-- List Product modal -->
                                <div class="modal fade " id="produkModal-{{$roInvoice->purchaseOrder->id}}" tabindex="-1"
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
                                                @foreach ($roInvoice->product_qty_price['products'] as $productId => $productRo)
                                                    @php
                                                    $productPo = \App\Models\PurchaseOrderProduct::where('purchase_order_id', $roInvoice->purchase_order_id)->where('product_id',$productId)->first(); // Harga jual (diambil dari cart)
                                                    @endphp
                                                    <div class="border border-1 border-dashed border-primary {{$loop->index+1 == count($roInvoice->purchaseOrder->products) ? '' : 'mb-2'}} p-3 rounded-3">
                                                        <div
                                                            class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                                            <img src="{{ $productPo->product->image ? '/storage/' . $productPo->product->image : 'https://placehold.co/300?text=' . $productPo->product->name }}"
                                                                alt="Image Product {{ $productPo->product->name }} in Cart" class="d-block rounded-2"
                                                                style="width: 5em; height:5em; object-fit:cover">
                                                            <div>
                                                                <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                                    {{ $productPo->product->name }}</div>
                                                                <div class="text-muted fs-2 mb-2">
                                                                    {{ $productPo->product->description ?? 'tidak ada deskripsi' }}</div>
                                                            </div>
                                                            <div
                                                                class="flex-grow-1 d-flex flex-column align-items-end gap-2 justify-content-between">
                                                                <div class="fs-2 fw-semibold">Sub Total</div>
                                                                <div class="fs-3 fw-bold subtotal">
                                                                    {{ formatRupiah($productRo['total_price_sale']) }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-end gap-2">
                                                            <div>
                                                                <label for="qty" class="text-muted fs-1">Memproses Sebanyak
                                                                    ({{ $productPo->product->unit->code }})</label>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    {{ $productRo['qty'] }}
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <label for="price_buy" class="text-muted fs-1">Dengan Harga Jual</label>
                                                                <div>{{ formatRupiah($productRo['price_sale']) }}</div>
                                                            </div>
                                                            <div>
                                                                <label for="qty" class="text-muted fs-1">Dikemas Dengan </label>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    {{ $productPo->pack->name.' @ '.$productPo->pack->capacity.' '.$productPo->pack->unit->code }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="pt-3 mt-3 border-top border-2">
                                                    <div class="d-flex align-items-center gap-2 justify-content-between">
                                                        <div class="fs-3 fw-semibold">Total</div>
                                                        <div class="fs-4 fw-bold">{{ formatRupiah($roInvoice->product_qty_price['total_price']) }}</div>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 justify-content-between">
                                                        <div class="fs-3 fw-semibold">PPN</div>
                                                        <div class="fs-4 fw-bold">{{ $roInvoice->product_qty_price['tax'] }}%</div>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 justify-content-between">
                                                        <div class="fs-3 fw-semibold">Grand Total</div>
                                                        <div class="fs-4 fw-bold">{{ formatRupiah($roInvoice->product_qty_price['total_price_taxed']) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h6>Kalkulasi Pembayaran</h6>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="">Total Nilai Awal</div>
                                <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ formatRupiah($roInvoice->product_qty_price['total_price']) }}</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="">Pajak</div>
                                <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ $roInvoice->purchaseOrder->tax ?? '11' }}%</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="">Total Dengan Pajak</div>
                                {{-- @php
                                    dd($roInvoice->purchaseOrder->total_price, $roInvoice->purchaseOrder->tax, $roInvoice->purchaseOrder->tax && (int)$roInvoice->purchaseOrder->tax != '0' ? $roInvoice->purchaseOrder->tax : 11, ));
                                @endphp --}}
                                <h6 class="fw-semibold fs-2 text-primary mb-1">
                                    {{ 
                                        formatRupiah($roInvoice->product_qty_price['total_price_taxed'])
                                    }}
                                </h6>
                            </div>
                            <hr>
                            <h6>Statistik Pembayaran</h6>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Total Tagihan</div>
                                <h6 class="fs-2 fw-semibold text-secondary mb-1" style="">{{ formatRupiah($roInvoice->product_qty_price['total_price_taxed']) }}</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Dibayarkan</div>
                                <h6 class="fs-2 fw-semibold text-success mb-1" style="">{{ formatRupiah($roInvoice->sum_paid_total) }}</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Belum Dibayar</div>
                                <h6 class="fs-2 fw-semibold text-danger mb-1" style="">{{ formatRupiah($roInvoice->remaining_payment) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center gap-2 justify-content-between">
                <h4>Pembayaran (Hutang)</h4>
                <div style="aspect-ratio:1/1; width:3em; height:3em"
                    class="bg-primary text-white d-flex align-items-center justify-content-center rounded-5 me-auto">
                    {{ count($roInvoice->payments) }}</div>
                <button class="btn btn-primary btn-al-primary" data-bs-toggle="modal"
                    data-bs-target="#paymentModal">Tambah</button>
            </div>

            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">Tambah Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-0" style="white-space:normal !important">
                            <form action="{{ route('request-order.payment.store', $roInvoice->id) }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="date" class="form-label">Tanggal Pembayaran</label>
                                    <input type="date" name="date" id="date" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="file" class="form-label">File Bukti Pembayaran</label>
                                    <input type="file" name="file" id="file" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="paid_total" class="form-label">Tulis Nominal Pembayaran</label>
                                    <input type="text" name="paid_total" id="paid_total"
                                        placeholder="Sisa {{ formatRupiah($roInvoice->remaining_payment) }}"
                                        class="input-rupiah form-control">
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Tambah Pembayaran</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            @forelse($roInvoice->payments as $pay)
                <div class="card">
                    <div class="card-body">
                        <div class="row position-relative">
                            <div class="col-md-6 d-flex align-items-center flex-wrap gap-4">
                                <h5 class="mb-0">{{ $pay->code }}</h5>
                                <a href="{{ $pay->file ? '/storage/' . $pay->file : '#' }}"
                                    target="_blank"
                                    class="p-2 bg-primary-subtle gap-2 border border-primary text-primary rounded-2 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-file fs-5"></i>
                                    Bukti Pembayaran
                                </a>
                            </div>
                            <div class="col-md-6">
                                <div class=" d-flex align-items-center gap-2">
                                    <div class="ms-auto text-end">
                                        <div class="fs-2 text-muted">Total Pembayaran</div>
                                        <div class="fs-5 mb-0 fw-bold text-primary">{{ formatRupiah($pay->paid_total) }}</div>
                                        <div class="fs-2 text-{{$roInvoice->remaining_payment != 0 ? 'danger' : 'success'}}">dari sisa
                                            {{ formatRupiah($roInvoice->remaining_payment) }}</div>
                                    </div>
                                    <div class="dropdown dropstart">
                                        <a href="#" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="ti ti-dots fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $pay->id }}"><i
                                                        class="fs-4 ti ti-trash"></i>Delete</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!-- Delete Modal -->
                <div id="deleteModal-{{ $pay->id }}" class="modal fade" tabindex="-1"
                    aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                        <div class="modal-content p-3 modal-filled bg-danger">
                            <div class="modal-header modal-colored-header text-white">
                                <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                    Yakin ingin menghapus Pembayaran ({{ $pay->code }}) ?
                                </h4>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="width: fit-content; white-space:normal">
                                <h5 class="mt-0 text-white">Pembayaran {{$pay->code}} sebesar {{ formatRupiah($pay->paid_total) }}
                                    pada Invoice {{ $roInvoice->code }} akan dihapus</h5>
                                <p class="text-white">Segala data yang berkaitan dengan Pembayaran tersebut juga akan
                                    dihapus secara permanen.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <form
                                    action="{{ route('request-order.payment.destroy', ['id' => $roInvoice->id, 'pay_id' => $pay->id]) }}"
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
            @empty
                <div
                    class="d-flex align-items-center justify-content-center p-4 rounded-4 border border-dashed border-primary">
                    Jika ada Pembayaran yang dilakukan untuk Invoice ini maka Tambahkan Pembayaran
                </div>
            @endforelse
        </div>
    </div>
@endsection
@section('scripts')
    <script>
    $(document).ready(function () {
          // Tutup semua content accordion saat halaman dimuat
          $(".btn-accordion-content").hide();

          // Tambahkan event klik untuk setiap .btn-accordion
          $(".btn-accordion").on("click", function () {
              let content = $(this).next(".btn-accordion-content");

              // Tutup accordion lain (opsional, jika hanya satu yang boleh terbuka)
              $(".btn-accordion-content").not(content).slideUp();

              // Toggle slide untuk content yang diklik
              content.slideToggle();
          });
      });
    </script>
@endsection
