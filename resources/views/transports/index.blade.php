@extends('layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/libs/select2/dist/css/select2.min.css">
@endsection

@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Pengangkutan</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola Pengangkutan</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ env('APP_URL') }}/assets/images/breadcrumb/ChatBc.png" alt=""
                            class="img-fluid mb-n4" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3 d-flex align-items-center gap-2 justify-content-between">
        <h1>Pengangkutan</h1>
        <div style="aspect-ratio:1/1; width:3em; height:3em"
            class="bg-primary text-white d-flex align-items-center justify-content-center rounded-5 me-auto">
            {{ count($transports) }}</div>
        <button class="btn btn-primary btn-al-primary" data-bs-toggle="modal" data-bs-target="#addTransportModal">Tambah</button>
        @include('transports.addmodal', ['id' => 'addTransportModal'])
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('transport.index') }}" method="GET">
                <div class="row align-items-end mb-3 flex-wrap">
                    <div class="col-md-4 mb-2">
                        <label for="search" class="form-label">Filter Kata</label>
                        <input type="text" class="form-control" placeholder="Cari Pengangkutan" name="search"
                            value="{{ $filter->q ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="field" class="form-label">Urutkan Berdasarkan</label>
                        <select name="field" id="field" class="form-select">
                            @foreach (getModelAttributes('Transport', ['logistic_id']) as $atr)
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
                                <h6 class="fs-3 fw-semibold mb-0">Pengangkutan</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Pembelian ke Principal</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Logistik</h6>
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
                        @forelse ($transports as $transport)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                        <h6 class="fw-semibold text-success mb-1 fs-2" style="">
                                            {{ \Carbon\Carbon::parse($transport->date)->format('d F Y') }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Permintaan
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ $transport->code }}</h6>
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
                                        <h6 class="fw-semibold fs-2 text-{{ $status[$transport->status]['color'] }} mb-1" style="">{{ $status[$transport->status]['label'] }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Penagihan Invoice
                                        </div>
                                        <h6 class="fw-semibold fs-2 text-{{ $statusInvoice[$transport->generate_invoice]['color'] }} mb-1" style="">{{ $statusInvoice[$transport->generate_invoice]['label'] }}</h6>
                                    </div>
                                </td>
                                <td>
                                    @if($transport->purchaseOrder)
                                        <a href="{{route('purchase-order.setting', $transport->purchaseOrder->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-flex align-items-center fs-2 mb-1 gap-2">
                                            <i class="ti ti-building-factory mb-0 fs-3"></i> {{ $transport->purchaseOrder->code }}
                                        </a>
                                        <div>
                                            <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                            <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                                {{ \Carbon\Carbon::parse($transport->purchaseOrder->date)->format('d F Y') }}</h6>
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
                                            <h6 class="fw-semibold fs-2 text-{{ $status[$transport->purchaseOrder->status]['color'] }} mb-1" style="">{{ $status[$transport->purchaseOrder->status]['label'] }}</h6>
                                        </div>
                                        <div>
                                            <div class="fw-normal fs-1 text-muted" style="">Status Penagihan Invoice
                                            </div>
                                            <h6 class="fw-semibold fs-2 text-{{ $statusInvoice[$transport->purchaseOrder->generate_invoice]['color'] }} mb-1" style="">{{ $statusInvoice[$transport->purchaseOrder->generate_invoice]['label'] }}</h6>
                                        </div>
                                    @else
                                    Belum dikaitkan ke Pembelian Principal
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('logistic.setting', $transport->logistic->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-truck-delivery mb-0 fs-3"></i> {{ $transport->logistic->name }}
                                    </a>
                                    <div class="fw-normal fs-1 text-muted" style="">Contact Person</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $transport->logistic->cp_name }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $transport->logistic->cp_email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $transport->logistic->cp_phone ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Total Nilai Awal</div>
                                        <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ formatRupiah($transport->total_price) }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Pajak</div>
                                        <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ $transport->tax }}%</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Total Dengan Pajak</div>
                                        <h6 class="fw-semibold fs-2 text-primary mb-1">
                                            {{ $transport->total_price_taxed && $transport->total_price_taxed != 0 ? formatRupiah($transport->total_price_taxed) : formatRupiah($transport->total_price + $transport->total_price * ($transport->tax / 100)) }}
                                        </h6>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-start gap-2">
                                        <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">Updated
                                            at
                                            : {{ $transport->updated_at }}</div>
                                        <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">Created
                                            at
                                            : {{ $transport->created_at }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown dropstart">
                                        <a href="#" class="text-muted" id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if($transport->purchaseOrder->requestOrder && $transport->purchaseOrder->is_handle_logistic)
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3 text-danger"
                                                    onclick="seePDF('pdf.spk','{{$transport->purchaseOrder->id}}')"><i
                                                        class="fs-4 ti ti-printer"></i>Cetak SPK</button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3 text-danger"
                                                    onclick="seePDF('pdf.sj','{{$transport->purchaseOrder->id}}')"><i
                                                        class="fs-4 ti ti-printer"></i>Cetak Surat Jalan</button>
                                            </li>
                                            @endif
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal-{{ $transport->id }}"><i
                                                        class="fs-4 ti ti-pencil"></i>Edit Pengangkutan</button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal-{{ $transport->id }}"><i
                                                        class="fs-4 ti ti-trash"></i>Delete</button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal-{{ $transport->id }}" tabindex="-1"
                                        aria-labelledby="vertical-center-modal" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header d-flex align-items-center">
                                                    <h4 class="modal-title" id="myLargeModalLabel">
                                                        Perbarui Pengangkutan {{ $transport->name }}
                                                    </h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('transport.update', $transport->id) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-4">
                                                            <label class="form-label fw-semibold">Tanggal PO
                                                                Logistik</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text px-6" id="basic-addon1"><i
                                                                        class="ti ti-calendar-event fs-6"></i></span>
                                                                <input type="date" name="date"
                                                                    class="form-control ps-2"
                                                                    value="{{ old('date', $transport->date) }}">
                                                            </div>
                                                            @error('date')
                                                                <span class="invalid-feedback" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>

                                                        <label for="text" class="form-label">Angkut Dengan
                                                            Logistik</label>
                                                        <div class="input-group mb-2">
                                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                                    class="ti ti-package fs-6"></i></span>
                                                            <div style="flex-grow:1">
                                                                <select name="logistic_id" id="unit"
                                                                    class="select2 form-select">
                                                                    <option value="">-- Pilih Logistik --</option>
                                                                    @foreach (\App\Models\Logistic::orderBy('name', 'asc')->get() as $logistic)
                                                                        <option value="{{ $logistic->id }}"
                                                                            {{ $logistic->id == old('logistic_id', $transport->logistic_id) ? 'selected' : '' }}>
                                                                            {{ $logistic->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <label for="total_price" class="form-label">Total Harga
                                                            Pengangkutan</label>
                                                        <input type="text" name="total_price"
                                                            class="form-control input-rupiah mb-2"
                                                            value="{{ old('total_price', formatRupiah($transport->total_price)) }}" placeholder="Rp 0">

                                                        <label for="tax" class="form-label">Ditambah Pajak
                                                            (%)
                                                        </label>
                                                        <input type="number" name="tax"
                                                            class="form-control mb-2"
                                                            value="{{ old('tax', $transport->tax) }}" placeholder="x%">

                                                        <div class="d-flex gap-1 align-items-center justify-content-end">
                                                            <button type="button"
                                                                class="btn bg-danger-subtle text-danger  waves-effect text-start"
                                                                data-bs-dismiss="modal">
                                                                Close
                                                            </button>
                                                            <button type="submit"
                                                                class="btn btn-primary btn-al-primary">Perbarui</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div id="deleteModal-{{ $transport->id }}" class="modal fade" tabindex="-1"
                                        aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                            <div class="modal-content p-3 modal-filled bg-danger">
                                                <div class="modal-header modal-colored-header text-white">
                                                    <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                                        Yakin ingin menghapus Pengangkutan ?
                                                    </h4>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="width: fit-content; white-space:normal">
                                                    <h5 class="mt-0 text-white">Pengangkutan {{ $transport->code }} akan dihapus
                                                    </h5>
                                                    <p class="text-white">Segala data yang berkaitan dengan Pengangkutan
                                                        tersebut juga akan dihapus secara permanen.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <form action="{{ route('transport.destroy', $transport->id) }}" method="POST">
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
    <!-- Modal for QR Code Preview -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Preview QR Code -->
                    <img id="qrCodeImage" src="" class="img-fluid rounded" alt="QR Code Preview">
                    <a id="downloadLink" href="#" class="btn btn-primary mt-3" download="qr_code.png">Download QR
                        Code</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ env('APP_URL') }}/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="{{ env('APP_URL') }}/assets/libs/select2/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').each(function() {
                let modal = $(this).closest('.modal'); // Cari modal terdekat
                $(this).select2({
                    dropdownParent: modal // Pasang dropdown di dalam modal
                });
            });
        });
    </script>
@endsection
