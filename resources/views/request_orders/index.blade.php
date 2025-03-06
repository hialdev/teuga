@extends('layouts.base')
@section('css')
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Permintaan Client</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Semua Logistic</li>
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
        <h1>Permintaan Client</h1>
        <div style="aspect-ratio:1/1; width:3em; height:3em"
            class="bg-primary text-white d-flex align-items-center justify-content-center rounded-5 me-auto">
            {{ count($reqorders) }}</div>
        <a href="{{ route('request-order.add') }}" class="btn btn-primary btn-al-primary">Tambah</a>
        {{-- <a href="{{route('pdf.preview.blade', ['bladePath' => 'Clients.stok'])}}" target="_blank" class="btn btn-danger"><i class="ti ti-file-download me-2"></i>Laporan Stok</a> --}}
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('request-order.index') }}" method="GET">
                <div class="row align-items-end mb-3 flex-wrap">
                    <div class="col-md-4 mb-2">
                        <label for="search" class="form-label">Filter Kata</label>
                        <input type="text" class="form-control" placeholder="Cari Kode" name="search"
                            value="{{ $filter->q ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="field" class="form-label">Urutkan Berdasarkan</label>
                        <select name="field" id="field" class="form-select">
                            @foreach (getModelAttributes('RequestOrder', ['attachment', 'client_id', 'client_pic_id']) as $atr)
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
                                <h6 class="fs-3 fw-semibold mb-0">Permintaan Client</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Detail</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Client</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Produk Dipesan</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Kalkulasi</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Pemrosesan</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Lampiran</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Timestamp</h6>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reqorders as $reqorder)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                        <h6 class="fw-semibold text-success mb-1" style="">
                                            {{ \Carbon\Carbon::parse($reqorder->date)->format('d F Y') }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Permintaan
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ $reqorder->code }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Referensi
                                            Permintaan (PO)</div>
                                        <h6 class="fw-semibold text-secondary mb-1" style="">
                                            {{ $reqorder->no_refrence }}</h6>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">File Refrensi (PO dari Client)</div>
                                        <a href="{{ $reqorder->attachment ? '/storage/'.$reqorder->attachment : '#' }}" class="badge fs-2 mb-1 bg-primary-subtle text-primary border-primary">
                                            <i class="ti ti-file"></i>
                                            {{ $reqorder->attachment ? 'File Asli PO dari Client' : 'Tidak ada Lampiran'}}
                                        </a>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Deskripsi</div>
                                        <div class="fw-normal fs-2" style="white-space:normal; font-size:13px; ">
                                            {{ $reqorder->description ?? 'Tidak ada' }}</div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $status = [
                                            '0' => ['label' => 'Pending','color' => 'secondary'],
                                            '1' => ['label' => 'Diproses','color' => 'warning',],
                                            '2' => ['label' => 'Selesai','color' => 'success'],
                                        ];

                                        $statusInvoice = [
                                            '0' => ['label' => 'Belum Ditagih / Stock','color' => 'secondary'],
                                            '1' => ['label' => 'Ditagih','color' => 'success'],
                                            '2' => ['label' => 'Ditagih Bertahap','color' => 'success'],
                                        ];
                                    @endphp
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Permintaan
                                        </div>
                                        <h6 class="fw-semibold fs-2 text-{{ $status[$reqorder->status]['color'] }} mb-1" style="">{{ $status[$reqorder->status]['label'] }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Penagihan Invoice
                                        </div>
                                        <h6 class="fw-semibold fs-2 text-{{ $statusInvoice[($reqorder->is_partial ?? $reqorder->generate_invoice)]['color'] }} mb-1" style="">{{ $statusInvoice[($reqorder->is_partial ?? $reqorder->generate_invoice)]['label'] }}</h6>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{route('client.setting', $reqorder->client->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-building-skyscraper mb-0 fs-3"></i> {{ $reqorder->client->name }}
                                    </a>
                                    <div class="fw-normal fs-1 text-muted" style="">PIC Client</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $reqorder->pic->name }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $reqorder->pic->email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $reqorder->pic->phone ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <button type="button"
                                        class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-secondary text-white rounded-3"
                                        data-bs-toggle="modal" data-bs-target="#produkModal-{{$reqorder->id}}"><i
                                            class="fs-4 ti ti-package"></i> {{ count($reqorder->products) }} Produk</button>
                                    
                                    @if($reqorder->products->count() > 0)
                                    <!-- Add Address modal -->
                                    <div class="modal fade " id="produkModal-{{$reqorder->id}}" tabindex="-1"
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
                                                    @foreach ($reqorder->products as $reqproduct)
                                                        @php
                                                            $priceSale = $reqproduct->price_sale ?? 0; // Harga jual (diambil dari cart)
                                                            $subtotal = $priceSale * $reqproduct->qty; // Hitung subtotal awal
                                                        @endphp
                                                        <div class="border border-1 border-dashed border-primary {{$loop->index+1 == count($reqorder->products) ? '' : 'mb-2'}} p-3 rounded-3">
                                                            <div
                                                                class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                                                <img src="{{ $reqproduct->product->image ? '/storage/' . $reqproduct->product->image : 'https://placehold.co/300?text=' . $reqproduct->product->name }}"
                                                                    alt="Image Product {{ $reqproduct->product->name }} in Cart" class="d-block rounded-2"
                                                                    style="width: 5em; height:5em; object-fit:cover">
                                                                <div>
                                                                    <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                                        {{ $reqproduct->product->name }}</div>
                                                                    <div class="text-muted fs-2 mb-2">
                                                                        {{ $reqproduct->product->description ?? 'tidak ada deskripsi' }}</div>
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
                                                                    <label for="qty" class="text-muted fs-1">Dibeli Sebanyak
                                                                        ({{ $reqproduct->product->unit->code }})</label>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        {{ $reqproduct->qty }}
                                                                    </div>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <label for="price_sale" class="text-muted fs-1">Dengan Harga Jual</label>
                                                                    <div>{{ formatRupiah($reqproduct->price_sale) }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <div class="pt-3 mt-3 border-top border-2">
                                                        <div class="d-flex align-items-center gap-2 justify-content-between">
                                                            <div class="fs-3 fw-semibold">Total</div>
                                                            <div class="fs-4 fw-bold">{{ formatRupiah($reqorder->total_price) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    @if($reqorder->products->count() > 0)
                                        <div>
                                            <div class="fw-normal fs-1 text-muted" style="">Total Nilai Awal</div>
                                            <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ formatRupiah($reqorder->total_price) }}</h6>
                                        </div>
                                        <div>
                                            <div class="fw-normal fs-1 text-muted" style="">Pajak</div>
                                            <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ $reqorder->tax ?? '11' }}%</h6>
                                        </div>
                                        <div>
                                            <div class="fw-normal fs-1 text-muted" style="">Total Dengan Pajak</div>
                                            <h6 class="fw-semibold fs-2 text-primary mb-1">
                                                {{ 
                                                    formatRupiah($reqorder->total_price)
                                                }}
                                            </h6>
                                        </div>
                                    @else
                                        <div>Tidak ada Products</div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('request-order.setting', $reqorder->id).'#process'}}" class="fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 text-white bg-primary rounded-3"
                                    >
                                        <i class="fs-4 ti ti-building-factory"></i> {{ count($reqorder->purchaseOrders) }} Pembelian Principal
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('request-order.setting', $reqorder->id).'#lampiran'}}"
                                        class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-danger text-white rounded-3">
                                        <i class="fs-4 ti ti-files"></i> {{ count($reqorder->files) }} File
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-start gap-2">
                                        <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">
                                            Updated
                                            at
                                            : {{ $reqorder->updated_at }}</div>
                                        <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">
                                            Created
                                            at
                                            : {{ $reqorder->created_at }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown dropstart">
                                        <a href="#" class="text-muted" id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if($reqorder->status == '2' && $reqorder->invoices->count() == 0)
                                            <li>
                                                <button type="button" class="dropdown-item d-flex text-white bg-primary align-items-center gap-3"
                                                    data-bs-toggle="modal" data-bs-target="#invoicingModal-{{$reqorder->id}}"><i
                                                        class="fs-4 ti ti-credit-card"></i>Generate Invoice</button>
                                            </li>
                                            @endif
                                            <li>
                                                <a href="{{ route('request-order.setting', $reqorder->id) . '#data' }}"
                                                    class="dropdown-item text-primary d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-settings"></i>Kelola</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('request-order.setting', $reqorder->id) . '#invoices' }}"
                                                    class="dropdown-item text-secondary d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-file-invoice"></i>Lihat Invoices</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('request-order.setting', $reqorder->id) . '#produk' }}"
                                                    class="dropdown-item text-secondary d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-package"></i>Kelola Produk</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('request-order.setting', $reqorder->id) . '#lampiran' }}"
                                                    class="dropdown-item text-secondary d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-files"></i>Kelola File</a>
                                            </li>
                                            <li>
                                                <button type="button"
                                                    class="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal-{{ $reqorder->id }}"><i
                                                        class="fs-4 ti ti-trash"></i>Delete</button>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    @if($reqorder->status == '2' && $reqorder->invoices->count() == 0)
                                    <!-- invoicing Modal -->
                                    <div class="modal fade" id="invoicingModal-{{$reqorder->id}}" tabindex="-1"
                                        aria-labelledby="vertical-center-modal" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header d-flex align-items-center">
                                                    <h4 class="modal-title" id="myLargeModalLabel" style="white-space: normal">
                                                        Buat Penagihan / Invoice {{$reqorder->code}}
                                                    </h4>
                                                    <button type="button" class="btn-close mb-auto" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body pt-0">
                                                    <form action="{{ route('request-order.generate', $reqorder->id) }}" method="POST">
                                                        @csrf
                                                        <p class="text-muted" style="white-space: normal">Pastikan Keadaan lapangan sudah sesuai, dan dapat dipertanggung jawabkan dengan baik untuk <strong>Buat Penagihan / Invoice dengan kode Permintaan Client {{$reqorder->code}} (sebagai Piutang)</strong>. Jika terdapat Invoice Partial maka tidak dapat membuat Invoice kesuluruhan untuk Permintaan, Hapus Invoice Partial atau Buat Invoice Permintaan secara Partial semuanya.</p>
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
                                    <div id="deleteModal-{{ $reqorder->id }}" class="modal fade" tabindex="-1"
                                        aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                            <div class="modal-content p-3 modal-filled bg-danger">
                                                <div class="modal-header modal-colored-header text-white">
                                                    <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                                        Yakin ingin menghapus Permintaan Client {{ $reqorder->code }} ?
                                                    </h4>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="width: fit-content; white-space:normal">
                                                    <h5 class="mt-0 text-white">Permintaan Client {{ $reqorder->code }}
                                                        akan dihapus</h5>
                                                    <p class="text-white">Segala data yang berkaitan dengan Permintaan Client
                                                        tersebut juga akan dihapus secara permanen.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <form action="{{ route('request-order.destroy', $reqorder->id) }}"
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
