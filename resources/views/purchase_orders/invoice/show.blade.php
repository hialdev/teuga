@extends('layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/libs/select2/dist/css/select2.min.css">
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Kelola Invoice Pembelian {{ $poInvoice->code }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('purchase-order.invoice') }}">Invoice
                                    Pembelian</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola Invoice Pembelian</li>
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
                        <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between"
                            style="cursor: pointer">
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Invoice
                                </div>
                                <h6 class="fw-semibold text-primary mb-1" style="">{{ $poInvoice->code }}</h6>
                            </div>
                            @php
                                $status = [
                                    '0' => ['label' => 'Belum dibayar', 'color' => 'secondary'],
                                    '1' => ['label' => 'Dibayar bertahap', 'color' => 'warning'],
                                    '2' => ['label' => 'Lunas', 'color' => 'success'],
                                ];
                            @endphp
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="">Status Pembayaran
                                </div>
                                <h6 class="fw-semibold fs-2 text-{{ $status[$poInvoice->payment_status]['color'] }} mb-1"
                                    style="">{{ $status[$poInvoice->payment_status]['label'] }}</h6>
                            </div>
                            <div class="flex-grow-1 text-end">
                                <div class="fs-1 text-muted">Klik untuk melihat detail</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="w-100">
                                <div class="progress mt-1">
                                    <div class="progress-bar progress-bar-striped text-bg-{{ $poInvoice->payment_percentage && $poInvoice->payment_percentage == '100' ? 'success' : 'info' }} progress-bar-animated"
                                        role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"
                                        style="width: {{ $poInvoice->payment_percentage ?? '0' }}%">
                                    </div>
                                </div>
                            </div>
                            <div class="fs-2" style="white-space: nowrap">
                                <strong>{{ $poInvoice->payment_percentage ?? '0' }}%</strong> Dibayar</div>
                        </div>
                    </div>
                    <div class="btn-accordion-content row mt-3">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Invoice
                                </div>
                                <h6 class="fw-semibold text-primary fs-5" style="">{{ $poInvoice->code }}</h6>
                            </div>
                            <hr>
                            <h6 class="mb-2">Pembelian Ke Principal</h6>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                    {{ \Carbon\Carbon::parse($poInvoice->purchaseOrder->date)->format('d F Y') }}</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Pembelian
                                </div>
                                <a class="d-flex align-items-center gap-2 mb-1"
                                    href="{{ route('purchase-order.setting', $poInvoice->purchaseOrder->id) }}">
                                    <h6 class="fw-semibold text-primary mb-0" style="">
                                        {{ $poInvoice->purchaseOrder->code }}</h6>
                                    <i class="ti ti-external-link"></i>
                                </a>
                            </div>
                            @php
                                $status = [
                                    '0' => ['label' => 'Pending', 'color' => 'secondary'],
                                    '1' => ['label' => 'Diproses', 'color' => 'warning'],
                                    '2' => ['label' => 'Selesai', 'color' => 'success'],
                                ];

                                $statusInvoice = [
                                    '0' => ['label' => 'Belum Ditagih / Stock', 'color' => 'secondary'],
                                    '1' => ['label' => 'Ditagih', 'color' => 'success'],
                                ];
                            @endphp
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="">Status Permintaan
                                </div>
                                <h6 class="fw-semibold fs-2 text-{{ $status[$poInvoice->purchaseOrder->status]['color'] }} mb-1"
                                    style="">{{ $status[$poInvoice->purchaseOrder->status]['label'] }}</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="">Status Penagihan Invoice
                                </div>
                                <h6 class="fw-semibold fs-2 text-{{ $statusInvoice[$poInvoice->purchaseOrder->generate_invoice]['color'] }} mb-1"
                                    style="">
                                    {{ $statusInvoice[$poInvoice->purchaseOrder->generate_invoice]['label'] }}</h6>
                            </div>
                            <div style="min-width: 10em">
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Deksripsi</div>
                                <p class="mb-1 fs-2" style="white-space:normal !important;">
                                    {{ $poInvoice->purchaseOrder->description ?? 'tidak ada deskripsi' }}</p>
                            </div>
                            <hr>
                            <h6>Permintaan Client (Request Order)</h6>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                    {{ \Carbon\Carbon::parse($poInvoice->purchaseOrder->requestOrder->date)->format('d F Y') }}
                                </h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Permintaan
                                </div>
                                <a class="d-flex align-items-center gap-2 mb-1"
                                    href="{{ route('request-order.setting', $poInvoice->purchaseOrder->requestOrder->id) }}">
                                    <h6 class="fw-semibold text-primary mb-0" style="">
                                        {{ $poInvoice->purchaseOrder->requestOrder->code }}</h6>
                                    <i class="ti ti-external-link"></i>
                                </a>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Referensi
                                    Permintaan (PO)</div>
                                <a href="{{ $poInvoice->purchaseOrder->requestOrder->attachment ? '/storage/' . $poInvoice->purchaseOrder->requestOrder->attachment : '#' }}"
                                    class="badge fs-2 mb-1 bg-primary-subtle text-primary border-primary">
                                    <i class="ti ti-file"></i>
                                    {{ $poInvoice->purchaseOrder->requestOrder->attachment ? $poInvoice->purchaseOrder->requestOrder->no_refrence : 'Tidak ada Lampiran' }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="">
                                <h6>Memesan Ke Principal</h6>
                                <a href="{{ route('principal.setting', $poInvoice->purchaseOrder->principal->id) }}"
                                    target="_blank"
                                    class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                    <i class="ti ti-building-skyscraper mb-0 fs-3"></i>
                                    {{ $poInvoice->purchaseOrder->principal->name }}
                                </a>
                                <div class="fw-normal fs-1 text-muted" style="">PIC Principal</div>
                                <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                    <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $poInvoice->purchaseOrder->pic->name }}
                                </div>
                                <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                    <i class="ti ti-mail mb-0 fs-3"></i>
                                    {{ $poInvoice->purchaseOrder->pic->email ?? '-' }}
                                </div>
                                <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                    <i class="ti ti-phone mb-0 fs-3"></i>
                                    {{ $poInvoice->purchaseOrder->pic->phone ?? '-' }}
                                </div>
                            </div>
                            <hr>
                            <div class="">
                                <h6>Dengan Detail Logistik / Pengangkutan</h6>
                                @if ($poInvoice->purchaseOrder->transport)
                                    <a href="{{ route('logistic.setting', $poInvoice->purchaseOrder->transport->logistic->id) }}"
                                        target="_blank"
                                        class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-truck-delivery mb-0 fs-3"></i>
                                        {{ $poInvoice->purchaseOrder->transport->logistic->name }}
                                    </a>
                                    <a href="javascript:void(0);" title="Klik untuk melihat detail"
                                        class="d-flex text-secondary align-items-center fs-2 mb-1 gap-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailDelivery-{{ $poInvoice->purchaseOrder->id }}">
                                        <i class="ti ti-exchange mb-0 fs-3"></i> Detail Antar Jemput
                                    </a>
                                    <!-- List Product modal -->
                                    <div class="modal fade " id="detailDelivery-{{ $poInvoice->purchaseOrder->id }}"
                                        tabindex="-1" aria-labelledby="vertical-center-modal" aria-hidden="true">
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
                                                            <div class="fs-3">
                                                                {{ $poInvoice->purchaseOrder->pickup->address . ', ' . $poInvoice->purchaseOrder->pickup->city . '. ' . $poInvoice->purchaseOrder->pickup->postal_code }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="p-3 rounded-3 border border-dashed border-primary">
                                                        <div class="fs-2 text-muted">Pengantaran Barang</div>
                                                        <div>
                                                            <div class="fs-3">
                                                                {{ $poInvoice->purchaseOrder->delivery->address . ', ' . $poInvoice->purchaseOrder->delivery->city . '. ' . $poInvoice->purchaseOrder->delivery->postal_code }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fw-normal fs-1 text-muted" style="">Contact Person</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-user-circle mb-0 fs-3"></i>
                                        {{ $poInvoice->purchaseOrder->transport->logistic->cp_name }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i>
                                        {{ $poInvoice->purchaseOrder->transport->logistic->cp_email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i>
                                        {{ $poInvoice->purchaseOrder->transport->logistic->cp_phone ?? '-' }}
                                    </div>
                                @else
                                    <div>Diurus oleh Principal</div>
                                @endif

                                <hr>
                                <h6>Timestamp</h6>
                                <div class="d-flex flex-column align-items-start gap-2">
                                    <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">
                                        Updated
                                        at
                                        : {{ $poInvoice->purchaseOrder->updated_at }}</div>
                                    <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">
                                        Created
                                        at
                                        : {{ $poInvoice->purchaseOrder->created_at }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="mb-3">
                                <h6>Produk yang diproses</h6>
                                <button type="button"
                                    class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-secondary text-white rounded-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#produkModal-{{ $poInvoice->purchaseOrder->id }}"><i
                                        class="fs-4 ti ti-package"></i> {{ count($poInvoice->purchaseOrder->products) }}
                                    Produk</button>

                                <!-- List Product modal -->
                                <div class="modal fade " id="produkModal-{{ $poInvoice->purchaseOrder->id }}"
                                    tabindex="-1" aria-labelledby="vertical-center-modal" aria-hidden="true">
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
                                                @foreach ($poInvoice->purchaseOrder->products as $productPo)
                                                    <div
                                                        class="border border-1 border-dashed border-primary {{ $loop->index + 1 == count($poInvoice->purchaseOrder->products) ? '' : 'mb-2' }} p-3 rounded-3">
                                                        <div
                                                            class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                                            <img src="{{ $productPo->product->image ? '/storage/' . $productPo->product->image : 'https://placehold.co/300?text=' . $productPo->product->name }}"
                                                                alt="Image Product {{ $productPo->product->name }} in Cart"
                                                                class="d-block rounded-2"
                                                                style="width: 5em; height:5em; object-fit:cover">
                                                            <div>
                                                                <div
                                                                    class="text-decoration-none text-dark fs-3 fw-semibold">
                                                                    {{ $productPo->product->name }}</div>
                                                                <div class="text-muted fs-2 mb-2">
                                                                    {{ $productPo->product->description ?? 'tidak ada deskripsi' }}
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="flex-grow-1 d-flex flex-column align-items-end gap-2 justify-content-between">
                                                                <div class="fs-2 fw-semibold">Sub Total</div>
                                                                <div class="fs-3 fw-bold subtotal">
                                                                    {{ formatRupiah($productPo->price_buy * $productPo->qty) }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-end gap-2">
                                                            <div>
                                                                <label for="qty" class="text-muted fs-1">Memproses
                                                                    Sebanyak
                                                                    ({{ $productPo->product->unit->code }})
                                                                </label>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    {{ $productPo->qty }}
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <label for="price_buy" class="text-muted fs-1">Dengan
                                                                    Harga Beli</label>
                                                                <div>{{ formatRupiah($productPo->price_buy) }}</div>
                                                            </div>
                                                            <div>
                                                                <label for="qty" class="text-muted fs-1">Dikemas
                                                                    Dengan </label>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    {{ $productPo->pack->name . ' @ ' . $productPo->pack->capacity . ' ' . $productPo->pack->unit->code }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="pt-3 mt-3 border-top border-2">
                                                    <div class="d-flex align-items-center gap-2 justify-content-between">
                                                        <div class="fs-3 fw-semibold">Total</div>
                                                        <div class="fs-4 fw-bold">
                                                            {{ formatRupiah($poInvoice->purchaseOrder->total_price) }}
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 justify-content-between">
                                                        <div class="fs-3 fw-semibold">PPN</div>
                                                        <div class="fs-4 fw-bold">{{ $poInvoice->purchaseOrder->tax }}%
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 justify-content-between">
                                                        <div class="fs-3 fw-semibold">Grand Total</div>
                                                        <div class="fs-4 fw-bold">
                                                            {{ formatRupiah($poInvoice->purchaseOrder->total_price_taxed) }}
                                                        </div>
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
                                <h6 class="fw-semibold fs-2 text-primary mb-1" style="">
                                    {{ formatRupiah($poInvoice->product_qty_price['total_price']) }}</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="">Pajak</div>
                                <h6 class="fw-semibold fs-2 text-primary mb-1" style="">
                                    {{ $poInvoice->purchaseOrder->tax ?? '11' }}%</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="">Total Dengan Pajak</div>
                                {{-- @php
                                    dd($poInvoice->purchaseOrder->total_price, $poInvoice->purchaseOrder->tax, $poInvoice->purchaseOrder->tax && (int)$poInvoice->purchaseOrder->tax != '0' ? $poInvoice->purchaseOrder->tax : 11, ));
                                @endphp --}}
                                <h6 class="fw-semibold fs-2 text-primary mb-1">
                                    {{ formatRupiah($poInvoice->product_qty_price['total_price_taxed']) }}
                                </h6>
                            </div>
                            <hr>
                            <h6>Statistik Pembayaran</h6>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Total Tagihan</div>
                                <h6 class="fs-2 fw-semibold text-secondary mb-1" style="">
                                    {{ formatRupiah($poInvoice->product_qty_price['total_price_taxed']) }}</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Dibayarkan</div>
                                <h6 class="fs-2 fw-semibold text-success mb-1" style="">{{ formatRupiah($poInvoice->sum_paid_total) }}</h6>
                            </div>
                            <div>
                                <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Belum Dibayar</div>
                                <h6 class="fs-2 fw-semibold text-danger mb-1" style="">{{ formatRupiah($poInvoice->remaining_payment) }}</h6>
                            </div>
                        </div>
                    </div>
                    @forelse($poInvoice->transactions as $trx)
                    <div class="mt-2 p-3 bg-primary-subtle rounded-3">
                        <a href="{{route('transaction.index', ['search' => $trx->code])}}" class="fw-semibold d-flex flex-wrap align-items-center gap-2"><div class="badge text-bg-primary rounded-pill">Generated</div><div>{{$trx->code}}</div> <div class="ms-auto fs-1">{{\Carbon\Carbon::parse($trx->created_at)->format('d M Y')}}</div></a>
                    </div>
                    @empty
                    <form action="{{route('purchase-order.invoice.generateTrx')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{$poInvoice->id}}">
                        <button class="btn btn-primary mt-2 w-100">Generate Transaksi PO (Hutang ke {{ $poInvoice->purchaseOrder->principal->name }})</button>
                    </form>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="mb-3 d-flex align-items-center gap-2 justify-content-between">
                <h4 class="mb-0">Pembayaran (Hutang)</h4>
                <div style="aspect-ratio:1/1; width:3em; height:3em"
                    class="bg-primary text-white d-flex align-items-center justify-content-center rounded-5 me-auto">
                    {{ count($poInvoice->payments) }}</div>
                <a href="{{ urlApp('OSN', '/invoice/purchase-order/'.$poInvoice->id) }}" target="_blank" class="btn btn-primary btn-al-primary"
                    >Kelola</a>
            </div>
        </div>

        <div class="col-12">
            @forelse($poInvoice->payments as $pay)
                <div class="card">
                    <div class="card-body">
                        <div class="row position-relative">
                            <div class="col-md-6 d-flex align-items-center flex-wrap gap-2 gap-md-3">
                                <h5 class="mb-0">{{ $pay->code }}</h5>
                                <a href="{{ $pay->file ? urlApp('OSN','/storage/' . $pay->file) : '#' }}"
                                    target="_blank"
                                    class="p-2 bg-primary-subtle gap-2 border border-primary text-primary rounded-2 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-file fs-5"></i>
                                    Bukti Pembayaran
                                </a>
                            </div>
                            <div class="col-md-6">
                                <div class=" d-flex align-items-center gap-2">
                                    <div class="ms-md-auto text-md-end mt-3 mt-md-0">
                                        <div class="fs-2 text-muted">Total Pembayaran</div>
                                        <div class="fs-5 mb-0 fw-bold text-primary">{{ formatRupiah($pay->paid_total) }}</div>
                                        <div class="fs-2 text-{{$poInvoice->remaining_payment != 0 ? 'danger' : 'success'}}">dari sisa
                                            {{ formatRupiah($poInvoice->remaining_payment) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                @forelse($pay->transactions as $trx)
                                <div class="mt-2 p-3 bg-primary-subtle rounded-3">
                                    <a href="{{route('transaction.index', ['search' => $trx->code])}}" class="fw-semibold d-flex flex-wrap align-items-center gap-2"><div class="badge text-bg-primary rounded-pill">Generated</div><div>{{$trx->code}}</div> <div class="ms-auto fs-1">{{\Carbon\Carbon::parse($trx->created_at)->format('d M Y')}}</div></a>
                                </div>
                                @empty
                                <button class="btn btn-primary mt-2 w-100" data-bs-toggle="modal" data-bs-target="#generatePaymentTrxModal-{{$pay->id}}">Catat Pembayaran {{$pay->code}} ke Jurnal</button>
                                @php
                                $parents = \App\Models\Account::whereHas('master', fn($q) => $q->where('type', 'assets'))
                                                                    ->where('parent_account_id', null)
                                                                    ->whereIn('code', [config('al.coa')['kas'], config('al.coa')['bank']])
                                                                    ->pluck('id')->toArray();
                                $cashBanks = \App\Models\Account::whereHas('master', fn($q) => $q->where('type', 'assets'))
                                                                            ->whereIn('code', [config('al.coa')['kas'], config('al.coa')['bank']])
                                                                            ->where(function ($q) use ($parents) {
                                                                                $q->whereIn('id', $parents)
                                                                                  ->orWhereIn('parent_account_id', $parents);
                                                                            })
                                                                            ->get()->sortBy('full_code');
                                @endphp
                                <!-- Generate Payment Transaction to Jurnal -->
                                <div class="modal fade" id="generatePaymentTrxModal-{{$pay->id}}" tabindex="-1" aria-labelledby="vertical-center-modal"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header pb-0 d-flex align-items-center">
                                                <h4 class="modal-title" id="myLargeModalLabel">
                                                    Tentukan Akun Pembayaran
                                                </h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body mt-0">
                                                <form action="{{ route('purchase-order.payment.generateTrx', ['id' => $poInvoice->id, 'pay_id' => $pay->id]) }}" method="POST">
                                                    @csrf

                                                    <label for="text" class="form-label">Akun Pembayaran</label>
                                                    <div class="input-group mb-2">
                                                        <span class="input-group-text px-6" id="basic-addon1"><i
                                                                class="ti ti-package fs-6"></i></span>
                                                        <div style="flex-grow:1">
                                                            <select name="account_id" id="account-{{$pay->id}}" class="select2 form-select">
                                                                <option value="">-- Pilih Akun Pembayaran --</option>
                                                                
                                                                @foreach ($cashBanks as $account)

                                                                    <option value="{{$account->id}}" {{ $account->id == old('account_id') ? 'selected' : '' }}>
                                                                    {{ $account->full_code.' - '.$account->account_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        Tidak ada yang cocok ? 
                                                        <a href="{{route('account.index')}}" target="_blank" class="btn btn-sm text-primary bg-primary-subtle"
                                                            >Buat Baru
                                                        </a>
                                                    </div>
                                                    
                                                    <div class="d-flex gap-1 pt-2 align-items-center justify-content-end">
                                                        <button type="submit" class="w-100 btn btn-primary btn-al-primary">Generate Transaksi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                @endforelse
                            </div>
                        </div>
                    </div>
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
    <script src="{{env('APP_URL')}}/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="{{env('APP_URL')}}/assets/libs/select2/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').each(function () {
                let modal = $(this).closest('.modal'); // Cari modal terdekat
                $(this).select2({
                    dropdownParent: modal // Pasang dropdown di dalam modal
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Tutup semua content accordion saat halaman dimuat
            $(".btn-accordion-content").hide();

            // Tambahkan event klik untuk setiap .btn-accordion
            $(".btn-accordion").on("click", function() {
                let content = $(this).next(".btn-accordion-content");

                // Tutup accordion lain (opsional, jika hanya satu yang boleh terbuka)
                $(".btn-accordion-content").not(content).slideUp();

                // Toggle slide untuk content yang diklik
                content.slideToggle();
            });
        });
    </script>
@endsection
