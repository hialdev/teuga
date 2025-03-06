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

div, p {
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
    <h1 class="text-center">Laporan Buku Besar (General Ledger)</h1>
    
    @if($date['start'] && $date['end'])
        <div class="text-center text-muted mb-2">{{\Carbon\Carbon::parse($start_date)->format('d F Y').' s.d '.\Carbon\Carbon::parse($end_date)->format('d F Y')}}</div>
    @else
        <div class="text-center text-muted mb-2"></div>
    @endif

    @foreach ($groupedLedger as $accountCode => $entries)
        <div class="">
            <div class="table-success p-2 fw-semibold me-2" style="display: inline-block">{{ $accountCode }}</div>
            <h5 class="mb-0" style="display: inline-block">{{ $entries->first()->account->account_name }}</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered mb-4">
                <thead class="table-primary">
                    <tr>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th width="80" class="text-end">Debit</th>
                        <th width="80" class="text-end">Kredit</th>
                        <th width="80" class="text-end">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @php $runningBalance = 0; @endphp
                    @foreach ($entries as $entry)
                        @php
                            $runningBalance = $entry->getRunningBalance($runningBalance, $entry);
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($entry->transaction->date)->format('d M Y') }}</td>
                            <td>{{ $entry->description ?? '-' }}</td>
                            <td class="text-end">{{ formatRupiah($entry->debit) }}</td>
                            <td class="text-end">{{ formatRupiah($entry->credit) }}</td>
                            <td class="text-end">{{ formatRupiah($runningBalance) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>

@endsection
