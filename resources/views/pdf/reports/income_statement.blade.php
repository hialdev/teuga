@extends('layouts.pdf')

@section('title', $title.' | '.setting('company.legal-name'))

@section('css')
<style>
.font-bold {
    font-weight: 700;
}

.bg-primary {
    background-color: #212121 !important;
    color: #fff;
}

.text-primary {
    color: #435ebe;
    background: #ebf3ff;
}

.bg-light-warning {
    background-color: #fffdd8;
    color: #3f3c00;
}

.book {
    margin: 0;
    padding: 0;
    background-color: #fff;
    font-family: 'Arial', sans-serif !important;
    transform-origin: 0 0;
}

* {
    box-sizing: border-box;
    -moz-box-sizing: border-box;
}

div, p, tr, td, th {
    font-size: 12px !important;
}

.subpage {
    outline: 0cm #FAFAFA solid;
}

@page {
    size: A4;
    margin: 0cm 0cm;
}

/** Define now the real margins of every page in the PDF **/
body {
    margin-top: 3.5cm;
    margin-left: 1cm;
    margin-right: 1cm;
    margin-bottom: 3.8cm;
}

/** Define the header rules **/
header {
    position: fixed;
    top: 0cm;
    left: 0cm;
    right: 0cm;
    height: 4cm;
    z-index: -1;
}

/** Define the footer rules **/
footer {
    position: fixed; 
    bottom: 0cm; 
    left: 0cm; 
    right: 0cm;
    height: 3.8cm;
    z-index: -1;
}

tr>td, tr>th {
    vertical-align: top;
}

h4, .h4 {
    font-size: 21px;
    color: #25396f;
}

table.main-table {border-collapse: collapse;width:100%;background: #fff}

table.main-table>thead>tr>th,
table.main-table>tbody>tr>td,
table.main-table>tfoot>tr>td {
    border: 1px solid #cecece;
    padding: 8px;
    text-align: left;
}

table.second-table {
    border-collapse: collapse;width:100%;
}

table.second-table>thead>tr>th,
table.second-table>tbody>tr>td,
table.second-table>tfoot>tr>td {
    border-left: 1px solid black;
    border-right: 1px solid black;
    padding: 3px;
    text-align: left;
}

table.second-table>thead,
table.second-table>tbody,
table.second-table>tfoot {
    border: 1px solid black;
}

.table-success {
    background: rgb(206, 234, 217);
}

.table-primary {
    background: rgb(199, 199, 226);
}

.table-danger {
    background: rgb(240, 203, 203);
}
</style>
@endsection

@section('content')

<!-- Header di tiap halaman -->
<header id="header">
    <img src="{{ filePath(setting('letter.kop-header')) }}" alt="Header Kop" style="width: 100%; height: auto;">
</header>

<!-- Footer di tiap halaman -->
<footer id="footer">
    <img src="{{ filePath(setting('letter.kop-footer')) }}" alt="Footer Kop" style="width: 100%; height: auto;">
</footer>

<!-- Isi Konten -->
<div class="subpage p-4" id='editor-container' style="">
    <h1 class="text-center">Laporan Laba Rugi (Income Statement)</h1>
    @if($date['start'] && $date['end'])
        <div class="text-center text-muted mb-2">{{\Carbon\Carbon::parse($start_date)->format('d F Y').' s.d '.\Carbon\Carbon::parse($end_date)->format('d F Y')}}</div>
    @else
        <div class="text-center text-muted mb-2"></div>
    @endif
    
    <div class="table-responsive">
        <!-- Pendapatan -->
        <h5 class="mb-3">Pendapatan (Revenue)</h5>
        <table class="table table-bordered">
            <thead class="table-success">
                <tr>
                    <th>Tanggal</th>
                    <th>Akun</th>
                    <th>Deskripsi</th>
                    <th class="text-end" width="80">Kredit (Pendapatan)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($revenues as $rev)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($rev->transaction->date)->format('d M Y') }}</td>
                        <td>{{ $rev->account->full_code.' - '.$rev->account->account_name }}</td>
                        <td>{{ $rev->description ?? '-' }}</td>
                        <td class="text-end">{{ formatRupiah($rev->credit) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total Pendapatan</th>
                    <th class="text-end" width="80">{{ formatRupiah($totalRevenue) }}</th>
                </tr>
            </tfoot>
        </table>

        <!-- Beban -->
        <h5 class="mb-3">Beban (Expenses)</h5>
        <table class="table table-bordered">
            <thead class="table-danger">
                <tr>
                    <th>Tanggal</th>
                    <th>Akun</th>
                    <th>Deskripsi</th>
                    <th class="text-end" width="80">Debet (Beban)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $exp)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($exp->transaction->date)->format('d M Y') }}</td>
                        <td>{{ $exp->account->full_code.' - '.$exp->account->account_name }}</td>
                        <td>{{ $exp->description ?? '-' }}</td>
                        <td class="text-end">{{ formatRupiah($exp->debit) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total Beban</th>
                    <th class="text-end" width="70">{{ formatRupiah($totalExpense) }}</th>
                </tr>
            </tfoot>
        </table>

        <!-- Laba Bersih -->
        <div class="mb-5 text-end mt-3 d-flex align-items-center fw-semibold gap-3">
            <div class="badge text-bg-success">Laba Bersih </div>
            <div class="fs-6 fw-semibold text-primary">{{ formatRupiah($netIncome) }}</div>
        </div>
    </div>
</div>

@endsection
