@extends('layouts.base')

@section('content')
<div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Laporan Perubahan Ekuitas</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a class="text-muted text-decoration-none" href="javascript:void(0);">Laporan Keuangan</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Laporan Perubahan Ekuitas</li>
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
    <h1>Laporan Perubahan Ekuitas (Changes in Equity)</h1>
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reportModal"><i class="ti ti-printer me-2"></i>Cetak</button>

    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1"
        aria-labelledby="vertical-center-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Generate Laporan
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="pdfReportForm">
                        @csrf
                        <div class="mb-2">
                            <label for="period" class="form-label">Pilih Periode untuk Dicetak</label>
                            <select name="period" id="period-print" class="form-select">
                                <option value="" {{ $filter->period == "" ? 'selected' : '' }}
                                    data-start=""
                                    data-end="">
                                        Default - Keseluruhan</option>
                                @foreach ($periods as $period)
                                    <option value="{{ $period->id }}" {{ $filter->period == $period->id ? 'selected' : '' }}
                                            data-start="{{ $period->start_date }}"
                                            data-end="{{ $period->end_date }}">
                                        {{ $period->name.' ('.\Carbon\Carbon::parse($period->start_date)->format('d M y').' s.d '.\Carbon\Carbon::parse($period->end_date)->format('d M y').')' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" id="bladePath" name="bladePath" value="pdf.reports.changes_in_equity">
                       
                        <div class="d-flex gap-1 align-items-center justify-content-end">
                            <button type="button"
                                class="btn bg-danger-subtle text-danger waves-effect text-start"
                                data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="button" id="generatePdfButton" class="btn btn-danger">Generate Laporan</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<form action="{{url()->current()}}" method="GET">
    <div class="row align-items-end mb-3 flex-wrap">
        <div class="col-md-10 mb-2">
            <label for="period" class="form-label">Transaksi untuk Priode</label>
            <select name="period" id="period" class="form-select">
                <option value="" {{ $filter->period == "" ? 'selected' : '' }}>
                        Default - Keseluruhan</option>
                @foreach ($periods as $period)
                    <option value="{{ $period->id }}" {{ $filter->period == $period->id ? 'selected' : '' }}>
                        {{ $period->name.' ('.\Carbon\Carbon::parse($period->start_date)->format('d M y').' s.d '.\Carbon\Carbon::parse($period->end_date)->format('d M y').')' }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <div class="d-flex align-items-center gap-1">
                <button type="submit" class="btn btn-primary w-100" style="white-space: nowrap">Apply</button>
                <a href="{{route('report.changes-in-equity')}}" class="btn btn-secondary" style="white-space: nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2v2a8 8 0 1 0 4.5 1.385V8h-2V2h6v2H18a9.99 9.99 0 0 1 4 8" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</form>
<div class="table-responsive">
    <h5 class="mb-3 fw-semibold">Laporan Perubahan Ekuitas</h5>
    <table class="table table-bordered">
        <thead class="table-primary">
            <tr>
                <th>Tanggal</th>
                <th>Akun</th>
                <th>Deskripsi</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php $runningBalance = 0; @endphp
            @forelse ($equityChanges as $change)
                @php $runningBalance += ($change->credit - $change->debit); @endphp
                <tr>
                    <td>{{ date('d M Y', strtotime($change->transaction->date)) }}</td>
                    <td>{{ $change->account->account_name }}</td>
                    <td>{{ $change->description ?? '-' }}</td>
                    <td>{{ formatRupiah($change->debit) }}</td>
                    <td>{{ formatRupiah($change->credit) }}</td>
                    <td>{{ formatRupiah($runningBalance) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pada Priode yang dipilih</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('generatePdfButton').addEventListener('click', function() {
        const periodSelect = document.getElementById('period-print');
        const start_date = periodSelect.options[periodSelect.selectedIndex].getAttribute('data-start');
        const end_date = periodSelect.options[periodSelect.selectedIndex].getAttribute('data-end');
        const bladePath = document.getElementById('bladePath').value;

        seePDF(bladePath, start_date, end_date);
    });
</script>
@endsection