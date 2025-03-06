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
    font-size: 13px !important;
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
    <h1 class="text-center ">Laporan Neraca (Balance Sheet)</h1>
    {{-- <div class="text-center text-muted mb-2">{{\Carbon\Carbon::parse($start_date)->format('d F Y').' s.d '.\Carbon\Carbon::parse($end_date)->format('d F Y')}}</div> --}}
    <div class="">
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
                @foreach($assets as $asset)
                    <tr>
                        <td>{{ $asset->full_code }}</td>
                        <td>{{ $asset->account_name }}</td>
                        <td class="text-end">{{ formatRupiah($asset->balance_amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="2" class="text-start">Total Aset</td>
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
                @foreach($liabilities as $liability)
                    <tr>
                        <td>{{ $liability->full_code }}</td>
                        <td>{{ $liability->account_name }}</td>
                        <td class="text-end">{{ formatRupiah($liability->balance_amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="2" class="text-start">Total Kewajiban</td>
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
                @foreach($equity as $eq)
                    <tr>
                        <td>{{ $eq->full_code }}</td>
                        <td>{{ $eq->account_name }}</td>
                        <td class="text-end">{{ formatRupiah($eq->balance_amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="2" class="text-start">Total Ekuitas</td>
                    <td class="text-end">{{ formatRupiah($totalEquity) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- Total Keseluruhan --}}
        <h5 class="mb-3">Total</h5>
        <table class="table table-bordered">
            <tr class="fw-bold">
                <td class="text-start">Total Aset</td>
                <td class="text-end">{{ formatRupiah($totalAssets) }}</td>
            </tr>
            <tr class="fw-bold">
                <td class="text-start">Total Kewajiban + Ekuitas</td>
                <td class="text-end">{{ formatRupiah($totalLiabilitiesEquity) }}</td>
            </tr>
        </table>
    </div>
</div>

@endsection
