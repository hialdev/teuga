@extends('layouts.base')
@section('css')
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Invoice Pengangkutan</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Manage Invoice Pengangkutan</li>
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
        <h1>Invoice Pengangkutan</h1>
        <div style="aspect-ratio:1/1; width:3em; height:3em"
            class="bg-primary text-white d-flex align-items-center justify-content-center rounded-5 me-auto">
            {{ count($tpInvoices) }}</div>
        
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET">
                <div class="row align-items-end mb-3 flex-wrap">
                    <div class="col-md-4 mb-2">
                        <label for="search" class="form-label">Filter Kata</label>
                        <input type="text" class="form-control" placeholder="Cari Kode Invoice" name="search" value="{{$filter->q ?? ''}}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="field" class="form-label">Urutkan Berdasarkan</label>
                        <select name="field" id="field" class="form-select">
                            @foreach (getModelAttributes('TransportInvoice', []) as $atr)
                            <option value="{{$atr}}" {{$filter->field == $atr ? 'selected' : ''}}>{{toPascalCase($atr)}}</option>
                            @endforeach                            
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="order" class="form-label">Dengan urutan</label>
                        <select name="order" id="order" class="form-select">
                            <option value="newest" {{$filter->order == 'desc' ? 'selected' : ''}}>Terbaru / Terbesar</option>
                            <option value="oldest" {{$filter->order == 'asc' ? 'selected' : ''}}>Terlama / Terkecil</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="d-flex align-items-center gap-1">
                            <button type="submit" class="btn btn-primary w-100" style="white-space: nowrap">Apply</button>
                            <a href="{{url()->current()}}" class="btn btn-secondary" style="white-space: nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2v2a8 8 0 1 0 4.5 1.385V8h-2V2h6v2H18a9.99 9.99 0 0 1 4 8" />
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
                                <h6 class="fs-3 fw-semibold mb-0">Invoice</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Pengangkutan</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Statistik Pembayaran</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Timestamp</h6>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tpInvoices as $tpInvoice)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal Dibuat</div>
                                        <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                            {{ \Carbon\Carbon::parse($tpInvoice->created_at)->format('d F Y') }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Invoice
                                        </div>
                                        <a class="d-flex align-items-center gap-1 mb-1" href="{{route('purchase-order.invoice.show', $tpInvoice->id)}}">
                                            <h6 class="fw-semibold text-primary mb-0" style="">{{ $tpInvoice->code }}</h6>
                                            <i class="ti ti-external-link"></i>
                                        </a>
                                    </div>
                                    @php
                                        $statusPembayaran = [
                                            '0' => ['label' => 'Belum dibayar','color' => 'secondary'],
                                            '1' => ['label' => 'Bayar Sebagian','color' => 'warning',],
                                            '2' => ['label' => 'Lunas','color' => 'success'],
                                        ];
                                    @endphp
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Pembayaran
                                        </div>
                                        <h6 class="fw-semibold fs-2 text-{{ $statusPembayaran[$tpInvoice->payment_status]['color'] }} mb-1" style="">{{ $statusPembayaran[$tpInvoice->payment_status]['label'] }}</h6>
                                    </div>
                                </td>
                                <td>
                                    @if($tpInvoice->transport)
                                        <a href="{{route('transport.setting', $tpInvoice->transport->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                            <i class="ti ti-truck-delivery mb-0 fs-3"></i> {{ $tpInvoice->transport->code }}
                                        </a>
                                        <div>
                                            <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                            <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                                {{ \Carbon\Carbon::parse($tpInvoice->transport->date)->format('d F Y') }}</h6>
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
                                            <h6 class="fw-semibold fs-2 text-{{ $status[$tpInvoice->transport->status]['color'] }} mb-1" style="">{{ $status[$tpInvoice->transport->status]['label'] }}</h6>
                                        </div>
                                        <div>
                                            <div class="fw-normal fs-1 text-muted" style="">Status Penagihan Invoice
                                            </div>
                                            <h6 class="fw-semibold fs-2 text-{{ $statusInvoice[$tpInvoice->transport->generate_invoice]['color'] }} mb-1" style="">{{ $statusInvoice[$tpInvoice->transport->generate_invoice]['label'] }}</h6>
                                        </div>
                                    @else
                                    Belum dikaitkan ke Pembelian Principal
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Total Tagihan</div>
                                        <h6 class="fs-2 fw-semibold text-secondary mb-1" style="">{{ formatRupiah($tpInvoice->product_qty_price['total_price_taxed']) }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Dibayarkan</div>
                                        <h6 class="fs-2 fw-semibold text-success mb-1" style="">{{ formatRupiah($tpInvoice->sum_paid_total) }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Belum Dibayar</div>
                                        <h6 class="fs-2 fw-semibold text-danger mb-1" style="">{{ formatRupiah($tpInvoice->remaining_payment) }}</h6>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-start gap-2">
                                        <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">Updated
                                            at
                                            : {{ $tpInvoice->updated_at }}</div>
                                        <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">Created
                                            at
                                            : {{ $tpInvoice->created_at }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown dropstart">
                                        <a href="#" class="text-muted" id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a href="{{route('transport.invoice.show', $tpInvoice->id)}}" class="dropdown-item d-flex align-items-center gap-3 bg-primary text-white"><i
                                                        class="fs-4 ti ti-credit-card"></i>Kelola</a>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal-{{$tpInvoice->id}}"><i
                                                        class="fs-4 ti ti-trash"></i>Delete</button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div id="deleteModal-{{$tpInvoice->id}}" class="modal fade" tabindex="-1"
                                        aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                            <div class="modal-content p-3 modal-filled bg-danger">
                                                <div class="modal-header modal-colored-header text-white">
                                                    <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                                        Yakin ingin menghapus Invoice Pembelian ke Principal ?
                                                    </h4>
                                                    <button type="button" class="btn-close btn-close-white mb-auto"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="width: fit-content; white-space:normal">
                                                    <h5 class="mt-0 text-white">Invoice Pembelian ke Principal {{$tpInvoice->code}} akan dihapus</h5>
                                                    <p class="text-white">Segala data yang berkaitan dengan Invoice Pembelian ke Principal tersebut juga akan dihapus secara permanen.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <form action="{{route('purchase-order.invoice.destroy', $tpInvoice->id)}}" method="POST">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
        });
    </script>
@endsection
