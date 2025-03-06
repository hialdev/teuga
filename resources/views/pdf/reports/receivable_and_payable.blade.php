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
    <h1 class="text-center">Laporan Hutang Piutang (Receivable and Payable)</h1>
    @if($date['start'] && $date['end'])
        <div class="text-center text-muted mb-2">{{\Carbon\Carbon::parse($start_date)->format('d F Y').' s.d '.\Carbon\Carbon::parse($end_date)->format('d F Y')}}</div>
    @else
        <div class="text-center text-muted mb-2"></div>
    @endif
    <div class="table-responsive">
        <h5 class="mb-3">Piutang (Receivable)</h5>
        <table class="table table-bordered">
            <thead class="table-success">
                <tr>
                    <th>Tanggal</th>
                    <th>Akun</th>
                    <th>Deskripsi</th>
                    <th width="80">Debet</th>
                    <th width="80">Kredit</th>
                    <th width="80">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $runningReceivable = 0;
                @endphp
                @foreach ($transactionReceivables as $receivable)
                    @php
                        $runningReceivable += ($receivable->debit - $receivable->credit);
                        $balance = $runningReceivable;
                    @endphp
                    <tr>
                        <td>{{ date('d M Y', strtotime($receivable->transaction->date)) }}</td>
                        <td>{{ $receivable->account->full_code.' - '.$receivable->account->account_name }}</td>
                        <td>{{ $receivable->description ?? '-' }}</td>
                        <td>{{ formatRupiah($receivable->debit) }}</td>
                        <td>{{ formatRupiah($receivable->credit) }}</td>
                        <td>{{ formatRupiah($balance) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="table-responsive">
        <h5 class="mb-3">Hutang (Payable)</h5>
        <table class="table table-bordered">
            <thead class="table-danger">
                <tr>
                    <th>Tanggal</th>
                    <th>Akun</th>
                    <th>Deskripsi</th>
                    <th width="70">Debet</th>
                    <th width="70">Kredit</th>
                    <th width="70">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $runningPayable = 0;
                @endphp
                @foreach ($transactionPayables as $payable)
                    @php
                        $runningPayable += ($payable->credit - $payable->debit);
                        $balance = $runningPayable;
                    @endphp
                    <tr>
                        <td>{{ date('d M Y', strtotime($payable->transaction->date)) }}</td>
                        <td>{{ $payable->account->full_code.' - '.$payable->account->account_name }}</td>
                        <td>{{ $payable->description ?? '-' }}</td>
                        <td>{{ formatRupiah($payable->debit) }}</td>
                        <td>{{ formatRupiah($payable->credit) }}</td>
                        <td>{{ formatRupiah($balance) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
