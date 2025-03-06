@extends('layouts.base')

@section('content')
<div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Laporan Neraca (Balance Sheet)</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a class="text-muted text-decoration-none" href="javascript:void(0);">Laporan Keuangan</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Laporan Neraca (Balance Sheet)</li>
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
    <h1>Laporan Neraca</h1>
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reportModal"><i class="ti ti-printer me-2"></i>Cetak</button>

    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1"
        aria-labelledby="vertical-center-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center pb-0">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Generate Laporan
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="pdfReportForm">
                        @csrf
                        <p>Laporan Neraca mengevaluasi Seluruh Periode (dari awal hingga akhir)</p>
                        <div class="mb-2">
                            <label for="period" class="form-label d-none">Pilih Periode untuk Dicetak</label>
                            <select name="period" id="period-print" class="form-select d-none">
                                @foreach ($periods as $period)
                                    <option value="{{ $period->id }}" {{ $filter->period == $period->id ? 'selected' : '' }}
                                            data-start="{{ $period->start_date }}"
                                            data-end="{{ $period->end_date }}">
                                        {{ $period->name.' ('.\Carbon\Carbon::parse($period->start_date)->format('d M y').' s.d '.\Carbon\Carbon::parse($period->end_date)->format('d M y').')' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" id="bladePath" name="bladePath" value="pdf.reports.balance_sheet">
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
{{-- 
<form action="{{url()->current()}}" method="GET">
    <div class="row align-items-end mb-3 flex-wrap">
        <div class="col-md-10 mb-2">
            <label for="period" class="form-label">Transaksi untuk Priode</label>
            <select name="period" id="period" class="form-select">
                @foreach ($periods as $period)
                    <option value="{{ $period->id }}" {{ $filter->period == $period->id ? 'selected' : '' }}>
                        {{ $period->name.' ('.\Carbon\Carbon::parse($period->start_date)->format('d M y').' s.d '.\Carbon\Carbon::parse($period->end_date)->format('d M y').')' }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <div class="d-flex align-items-center gap-1">
                <button type="submit" class="btn btn-primary w-100" style="white-space: nowrap">Apply</button>
                <a href="{{route('report.balance-sheet')}}" class="btn btn-secondary" style="white-space: nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2v2a8 8 0 1 0 4.5 1.385V8h-2V2h6v2H18a9.99 9.99 0 0 1 4 8" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</form> --}}

<div class="table-responsive">
    {{-- Tabel Assets --}}
    <h5 class="mb-3">Aset (Assets)</h5>
    <table class="table table-bordered">
        <thead class="table-primary">
            <tr>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th class="text-end">Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $asset)
                <tr>
                    <td>{{ $asset->full_code }}</td>
                    <td>{{ $asset->account_name }}</td>
                    <td class="text-end">{{ formatRupiah($asset->balance_amount) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pada Priode yang dipilih</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="fw-bold">
                <td colspan="2" class="text-end">Total Aset</td>
                <td class="text-end">{{ formatRupiah($totalAssets) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Tabel Liabilities --}}
    <h5 class="mb-3">Kewajiban (Liabilities)</h5>
    <table class="table table-bordered">
        <thead class="table-danger">
            <tr>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th class="text-end">Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($liabilities as $liability)
                <tr>
                    <td>{{ $liability->full_code }}</td>
                    <td>{{ $liability->account_name }}</td>
                    <td class="text-end">{{ formatRupiah($liability->balance_amount) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pada Priode yang dipilih</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="fw-bold">
                <td colspan="2" class="text-end">Total Kewajiban</td>
                <td class="text-end">{{ formatRupiah($totalLiabilities) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Tabel Equity --}}
    <h5 class="mb-3">Ekuitas (Equity)</h5>
    <table class="table table-bordered">
        <thead class="table-success">
            <tr>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th class="text-end">Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($equity as $eq)
                <tr>
                    <td>{{ $eq->full_code }}</td>
                    <td>{{ $eq->account_name }}</td>
                    <td class="text-end">{{ formatRupiah($eq->balance_amount) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pada Priode yang dipilih</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="fw-bold">
                <td colspan="2" class="text-end">Total Ekuitas</td>
                <td class="text-end">{{ formatRupiah($totalEquity) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Total Keseluruhan --}}
    <h5 class="mb-3">Total</h5>
    <table class="table table-bordered">
        <tr class="fw-bold">
            <td class="text-end">Total Aset</td>
            <td class="text-end">{{ formatRupiah($totalAssets) }}</td>
        </tr>
        <tr class="fw-bold">
            <td class="text-end">Total Kewajiban + Ekuitas</td>
            <td class="text-end">{{ formatRupiah($totalLiabilitiesEquity) }}</td>
        </tr>
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
