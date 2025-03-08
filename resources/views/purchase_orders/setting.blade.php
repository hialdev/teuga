@extends('layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/libs/select2/dist/css/select2.min.css">
    <style>
        .stepper {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
        }

        .stepper .step {
            width: 100px;
            text-align: center;
            position: relative;
            cursor: pointer;
        }

        .stepper .step .circle {
            width: 35px;
            height: 35px;
            background-color: #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            margin: 0 auto;
            transition: 0.3s;
        }

        .stepper .step.active .circle {
            background: rgba(var(--bs-primary-rgb));
        }

        .stepper .step .label {
            margin-top: 8px;
            font-size: 14px;
        }

        .stepper .line {
            position: absolute;
            top: 17px;
            left: 50%;
            width: 100%;
            height: 5px;
            background-color: #ccc;
            z-index: -1;
        }

        .stepper .step.active .line {
            background: rgba(var(--bs-primary-rgb));
        }

        .stepper .step:last-child .line {
            display: none;
        }
    </style>
@endsection
@section('content')

    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Kelola Pembelian ke Principal : {{ $purchase->code }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('purchase-order.index') }}">Pembelian ke Principal</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola Pembelian ke Principal : {{ $purchase->code }}
                            </li>
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
    <div class="">
        <div class="row">
            <div class="col-6 col-md-3 mb-auto d-flex align-items-center gap-2">
                @if($purchase->status == 2)
                <form action="{{ route('purchase-order.generate', $purchase->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary {{$purchase->generate_invoice ? 'd-none' : ''}}"><i class="ti ti-credit-card me-1"></i> Tagih</button>
                </form>
                @endif
                @if($purchase->status != 2)
                <button type="button" class="btn p-2 px-3 d-flex {{$purchase->status == 0 ? 'btn-warning' : 'btn-success'}} align-items-center gap-2"
                            data-bs-toggle="modal" data-bs-target="#processModal-{{$purchase->id}}"><i
                                class="fs-4 ti {{$purchase->status == 0 ? 'ti-loader-3' : 'ti-check'}}"></i><span class="d-none d-sm-block">{{$purchase->status == 0 ? 'Proses' : 'Selesaikan'}}</span></button>
                <!-- Process Modal -->
                <div class="modal fade" id="processModal-{{$purchase->id}}" tabindex="-1"
                    aria-labelledby="vertical-center-modal" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h4 class="modal-title" id="myLargeModalLabel" style="white-space: normal">
                                    {{$purchase->status == 0 ? 'Proses Pembelian' : 'Selesaikan Pembelian'}} {{$purchase->code}}
                                </h4>
                                <button type="button" class="btn-close mb-auto" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body pt-0">
                                <form action="{{ route('purchase-order.process', $purchase->id) }}" method="POST">
                                    @csrf
                                    <p class="text-muted" style="white-space: normal">Pastikan Keadaan lapangan sudah sesuai, dan dapat dipertanggung jawabkan dengan baik untuk <strong>{{$purchase->status == 0 ? 'Proses Pembelian' : 'Selesaikan Pembelian'}} dengan kode {{$purchase->code}}</strong></p>
                                    <div class="d-flex gap-1 align-items-center justify-content-end">
                                        <button type="submit"
                                            class="btn {{$purchase->status == 0 ? 'btn-warning' : 'btn-success'}}">{{$purchase->status == 0 ? 'Proses Pembelian' : 'Selesaikan Pembelian'}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @php
                    $status = [
                        '0' => ['label' => 'Pending','color' => 'secondary'],
                        '1' => ['label' => 'Diproses','color' => 'warning',],
                        '2' => ['label' => 'Selesai','color' => 'success'],
                    ];
                @endphp
                <div>
                    <div class="fw-normal fs-1 text-muted" style="">Status Permintaan
                    </div>
                    <h6 class="fw-semibold fs-2 text-{{ $status[$purchase->status]['color'] }} mb-1" style="">{{ $status[$purchase->status]['label'] }}</h6>
                </div>
            </div>
            <div class="col-md-6 order-first order-md-0 d-flex align-items-start gap-2 flex-wrap">
                <div class="stepper flex-grow-1 overflow-auto">
                    <div class="step active" data-step="1">
                        <div class="circle">1</div>
                        <div class="label fs-2">Data Pembelian</div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="circle">2</div>
                        <div class="label fs-2">Produk diproses</div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="circle">3</div>
                        <div class="label fs-2">Lampiran</div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="4">
                        <div class="circle">4</div>
                        <div class="label fs-2">Penagihan / Invoice</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4 d-flex justify-content-end align-items-start gap-2">
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRequestOrder-{{$purchase->id}}"><i class="ti ti-trash"></i> <span class="ms-1 d-none d-md-inline-block">Hapus</span></button>
                <button onclick="seePDF('pdf.po','{{$purchase->id}}')" class="btn btn-danger" style="background:rgb(186, 55, 55); border-color:rgb(186, 55, 55)"><i class="ti ti-printer me-2"></i><span class="d-none d-sm-inline-block">Cetak</span> PO</button>
            </div>

            <!-- Delete Modal -->
            <div id="deleteRequestOrder-{{$purchase->id}}" class="modal fade" tabindex="-1"
                aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                    <div class="modal-content p-3 modal-filled bg-danger">
                        <div class="modal-header modal-colored-header text-white">
                            <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                Yakin ingin menghapus Pembelian ke Principal (Purchase Order) {{$purchase->code}} ?
                            </h4>
                            <button type="button" class="btn-close btn-close-white mb-auto"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="width: fit-content; white-space:normal">
                            <h5 class="mt-0 text-white">Pembelian ke Principal (Purchase Order) {{$purchase->code}} akan dihapus</h5>
                            <p class="text-white">Segala data yang berkaitan dengan Request Order tersebut juga akan dihapus secara permanen. Penghapusan dapat dilakukan jika tidak ada Transaksi penting yang terkait.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Close
                            </button>
                            <form action="{{route('purchase-order.destroy', $purchase->id)}}" method="POST">
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
        </div>

        <!-- Form Step 1 -->
        <div id="stepper-form">
            <div class="step-content active" data-step="1">
                <div class="card position-relative" id="showDataBox">
                    <button id="btnEditData" class="btn btn-sm btn-secondary position-absolute top-0 end-0 m-3"><i class="ti ti-edit"></i></button>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <h6>Pembelian</h6>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                    <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                        {{ \Carbon\Carbon::parse($purchase->date)->format('d F Y') }}</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Pembelian
                                    </div>
                                    <h6 class="fw-semibold text-primary mb-1" style="">{{ $purchase->code }}</h6>
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
                                    <h6 class="fw-semibold fs-2 text-{{ $status[$purchase->status]['color'] }} mb-1" style="">{{ $status[$purchase->status]['label'] }}</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="">Status Penagihan Invoice
                                    </div>
                                    <h6 class="fw-semibold fs-2 text-{{ $statusInvoice[$purchase->generate_invoice]['color'] }} mb-1" style="">{{ $statusInvoice[$purchase->generate_invoice]['label'] }}</h6>
                                </div>
                                <div style="min-width: 10em">
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Deksripsi</div>
                                    <p class="mb-1 fs-2" style="white-space:normal !important;">{{ $purchase->description ?? 'tidak ada deskripsi' }}</p>
                                </div>
                                <hr>

                                @if( $purchase->requestOrder )
                                <h6>Permintaan Client (Request Order)</h6>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                    <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                        {{ \Carbon\Carbon::parse($purchase->requestOrder->date)->format('d F Y') }}</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Permintaan
                                    </div>
                                    <a href="{{ route('request-order.setting', $purchase->requestOrder->id) }}" target="_blank"><h6 class="fw-semibold text-primary mb-1" style="">{{ $purchase->requestOrder->code }} <i class="ti ti-external-link"></i></h6></a>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Referensi
                                        Permintaan (PO)</div>
                                    <a href="{{ $purchase->requestOrder->attachment ? '/storage/'.$purchase->requestOrder->attachment : '#' }}" class="badge fs-2 mb-1 bg-primary-subtle text-primary border-primary">
                                        <i class="ti ti-file"></i>
                                        {{ $purchase->requestOrder->attachment ? $purchase->requestOrder->no_refrence : 'Tidak ada Lampiran'}}
                                    </a>
                                </div>
                                @else
                                <div class="fs-2">Tidak berdasarkan Permintaan Client</div>
                                @endif
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="">
                                    <h6>Memesan Ke Principal</h6>
                                    <a href="{{route('principal.setting', $purchase->principal->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-building-skyscraper mb-0 fs-3"></i> {{ $purchase->principal->name }}
                                    </a>
                                    <div class="fw-normal fs-1 text-muted" style="">PIC Principal</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $purchase->pic->name }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $purchase->pic->email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $purchase->pic->phone ?? '-' }}
                                    </div>
                                </div>
                                <hr>
                                <div class="">
                                    <h6>Dengan Detail Logistik / Pengangkutan</h6>
                                    @if( $purchase->tarnsport )
                                    <a href="{{route('logistic.setting', $purchase->transport->logistic->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-truck-delivery mb-0 fs-3"></i> {{ $purchase->transport->logistic->name }}
                                    </a>
                                    <a href="javascript:void(0);" title="Klik untuk melihat detail" class="d-flex text-secondary align-items-center fs-2 mb-1 gap-2"
                                        data-bs-toggle="modal" data-bs-target="#detailDelivery-{{$purchase->id}}"
                                    >
                                        <i class="ti ti-exchange mb-0 fs-3"></i> Detail Antar Jemput
                                    </a>
                                    <!-- List Product modal -->
                                    <div class="modal fade " id="detailDelivery-{{$purchase->id}}" tabindex="-1"
                                        aria-labelledby="vertical-center-modal" aria-hidden="true">
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
                                                            <div class="fs-3">{{$purchase->pickup->address.', '.$purchase->pickup->city.'. '.$purchase->pickup->postal_code}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="p-3 rounded-3 border border-dashed border-primary">
                                                        <div class="fs-2 text-muted">Pengantaran Barang</div>
                                                        <div>
                                                            <div class="fs-3">{{$purchase->delivery->address.', '.$purchase->delivery->city.'. '.$purchase->delivery->postal_code}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fw-normal fs-1 text-muted" style="">Contact Person</div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $purchase->transport->logistic->cp_name }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $purchase->transport->logistic->cp_email ?? '-' }}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $purchase->transport->logistic->cp_phone ?? '-' }}
                                    </div>
                                    @else
                                    <div class="fs-2">Diurus Oleh Principal</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <h6>Kalkulasi Pembayaran</h6>
                                @if($purchase->products->count() > 0)
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="">Total Nilai Awal</div>
                                    <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ formatRupiah($purchase->total_price) }}</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="">Pajak</div>
                                    <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ $purchase->tax ?? '11' }}%</h6>
                                </div>
                                <div>
                                    <div class="fw-normal fs-1 text-muted" style="">Total Dengan Pajak</div>
                                    {{-- @php
                                        dd($purchase->total_price, $purchase->tax, $purchase->tax && (int)$purchase->tax != '0' ? $purchase->tax : 11, ));
                                    @endphp --}}
                                    <h6 class="fw-semibold fs-2 text-primary mb-1">
                                        {{ 
                                            (int) $purchase->total_price_taxed && (int) $purchase->total_price_taxed != 0 
                                                ? formatRupiah($purchase->total_price_taxed) 
                                                : formatRupiah((int) $purchase->total_price + ((int) $purchase->total_price * (($purchase->tax && (int) $purchase->tax != 0 ? $purchase->tax : 11) / 100)))
                                        }}
                                    </h6>
                                </div>
                                @else
                                Lengkapi Data Dahulu
                                @endif

                                <hr>
                                <h6>Timestamp</h6>
                                <div class="d-flex flex-column align-items-start gap-2">
                                    <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">
                                        Updated
                                        at
                                        : {{ $purchase->updated_at }}</div>
                                    <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">
                                        Created
                                        at
                                        : {{ $purchase->created_at }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row d-none" id="editDataBox">
                    <div class="col-12">
                        <div class="card">
                            <div class="px-4 py-3 border-bottom">
                                <h5 class="card-title fw-semibold mb-0">Pembelian ke Principal</h5>
                                <button id="btnCloseData" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-3"><i class="ti ti-x"></i></button>
                            </div>
                            <div class="card-body p-4">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ route('purchase-order.store')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Tanggal Pembelian</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-calendar-event fs-6"></i></span>
                                            <input type="date" name="date" class="form-control ps-2" value="{{old('date', $purchase->date)}}">
                                        </div>
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Beli ke Principal</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-building-factory fs-6"></i></span>
                                            <div style="flex-grow:1">
                                                <select name="principal_id" id="principal_id" class="select2-normal form-select">
                                                    <option value="">-- Pilih Principal --</option>
                                                    @foreach ($principals as $principal)
                                                        <option value="{{$principal->id}}" {{$principal->id == old('principal_id', $purchase->principal_id) ? 'selected' : ''}}>{{$principal->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @error('principal_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Dengan PIC Principal</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-user-circle fs-6"></i></span>
                                            <div style="flex-grow:1">
                                                <select name="principal_pic_id" id="principal_pic_id" class="select2-normal form-select">
                                                    <option value="">!!! Pilih Principal terlebih dahulu !!!</option>
                                                </select>
                                            </div>
                                        </div>
                                        @error('principal_pic_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="is_for_client" type="checkbox" value="1" id="is_for_client" {{count($reqorders) > 0 ? 'checked' : ''}} />
                                            <label class="form-check-label" for="is_for_client">Pembelian ini untuk Permintaan Client</label>
                                        </div>
                                    </div>
                                    <div class="p-3 rounded-3 bg-primary-subtle mb-4" id="client_input">
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">Dengan Permintaan Client</label>
                                            <div class="input-group">
                                                <span class="input-group-text px-6" id="basic-addon1"><i
                                                        class="ti ti-building-skyscraper fs-6"></i></span>
                                                <div style="flex-grow:1">
                                                    <select name="request_order_id" id="request_order_id" class="select2-normal form-select">
                                                        <option value="">-- Pilih Permintaan Client --</option>
                                                        @foreach ($reqorders as $reqorder)
                                                            <option value="{{$reqorder->id}}" {{$reqorder->id == old('request_order_id', $purchase->request_order_id) ? 'selected' : ''}}>{{$reqorder->code.' - '.$reqorder->client->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @error('request_order_id')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="">
                                            <label class="form-label fw-semibold">Kirimkan ke Alamat Client</label>
                                            <div class="input-group">
                                                <span class="input-group-text px-6" id="basic-addon1"><i
                                                        class="ti ti-map-pin fs-6"></i></span>
                                                <div style="flex-grow:1">
                                                    <select name="delivery_address_id" id="delivery_address_id" class="select2-normal form-select">
                                                        <option value="">!!! Pilih Permintaan Client terlebih dahulu !!!</option>
                                                    </select>
                                                </div>
                                            </div>
                                            @error('delivery_address_id')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="is_handle_logistic" type="checkbox" value="1" id="is_handle_logistic" {{ old('is_handle_logistic', $purchase->is_handle_logistic) ? 'checked' : '' }} />
                                            <label class="form-check-label" for="is_handle_logistic">Perusahaan mengurus Logistik / Pengangkutan</label>
                                        </div>
                                    </div>
                                    <div class="p-3 bg-primary-subtle rounded-3 mb-4 {{ !$purchase->is_handle_logistic ? 'd-none' : '' }}" id="logistic_input">
                                        <label class="form-label fw-semibold">Logistik Diurus Sesuai Data Angkut</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-truck-delivery fs-6"></i></span>
                                            <div style="flex-grow:1">
                                                <select name="transport_id" id="transport_id" class="select2-normal form-select">
                                                    <option value="">-- Pilih Pengangkutan --</option>
                                                    @foreach ($transports as $transport)
                                                        <option value="{{$transport->id}}" {{$transport->id == old('transport_id', $purchase->transport_id) ? 'selected' : ''}}>{{$transport->code.' | Memakai Logistik : '.$transport->logistic->name.', Dengan Total Harga : '.formatRupiah($transport->total_price).', PPN : '.($transport->tax ?? 0).'%' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @error('transport_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                        <div class="d-flex align-items-center gap-2 my-2">
                                            Tidak menemukan Pengangkutan yang sesuai ? 
                                            <button type="button" class="btn btn-sm text-primary bg-primary-subtle"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addPengangkutanModal"
                                            >Tambah Pengangkutan</button>
                                        </div>

                                        <div class="mt-2">
                                            <label class="form-label fw-semibold">Angkut Barang di Alamat</label>
                                            <div class="input-group">
                                                <span class="input-group-text px-6" id="basic-addon1"><i
                                                        class="ti ti-map-pin fs-6"></i></span>
                                                <div style="flex-grow:1">
                                                    <select name="pickup_address_id" id="pickup_address_id" class="select2-normal form-select">
                                                        <option value="">!!! Pilih Principal terlebih dahulu !!!</option>
                                                    </select>
                                                </div>
                                            </div>
                                            @error('pickup_address_id')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Deskripsi</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-align-justified fs-6"></i></span>
                                            <textarea class="form-control ps-2" name="description" id="description" cols="20" rows="5"
                                                placeholder="Deskripsi untuk Pembelian ke Principal">{{old('description', $purchase->description)}}</textarea>
                                        </div>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary" {{$purchase->status != 0 ? 'disabled' : ''}}>
                                        Simpan dan Lanjut ke Produk Diproses
                                    </button>
                                </form>
                            </div>
                        </div>

                        @include('transports.addmodal',['id' => 'addPengangkutanModal'])

                    </div>
                </div>
            </div>

            @php
                $carts = session()->get('cart_' . $purchase->id, []);
                $totalPrice = 0;
            @endphp
            <!-- Form Step 2 -->
            <div class="step-content" data-step="2" style="display: none;">
                <div class="row">
                    <div class="col-md-5">
                        <div>
                            <form action="{{ url()->current() }}" method="GET" class="w-100">
                                <input type="hidden" name="hashProduct" value="1">
                                <div class="row align-items-end mb-3 flex-wrap">
                                    <div class="col-md-8 mb-2 flex-grow-1">
                                        <label for="search" class="form-label">Menampilkan Produk {{ $purchase->requestOrder ? 'yang Diminta' : ''}}</label>
                                        <input type="text" class="form-control" placeholder="Cari Produk"
                                            name="search" value="{{ $filter->q ?? '' }}">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="d-flex align-items-center gap-1">
                                            <button type="submit" class="btn btn-primary w-100"
                                                style="white-space: nowrap">Apply</button>
                                            <a href="{{ url()->current() }}" class="btn btn-secondary"
                                                style="white-space: nowrap">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em"
                                                    viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2v2a8 8 0 1 0 4.5 1.385V8h-2V2h6v2H18a9.99 9.99 0 0 1 4 8" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @php
                        @endphp
                        <div class="row">
                            @foreach ($products as $product)
                                @php
                                    if($purchase->requestOrder)
                                        $product = $product->product;
                                @endphp
                                <div class="col-6 mb-2">
                                    <div class="card rounded-4 h-100 overflow-hidden">
                                        <div class="card-body p-0 h-100 d-flex flex-column">
                                            <img src="{{ $product->image ? '/storage/' . $product->image : 'https://placehold.co/160x90?text=' . $product->name }}"
                                                alt="Image {{ $product->name }}"
                                                class="d-block w-100 mb-2 bg-primary-subtle"
                                                style="aspect-ratio:16/9; object-fit:contain;">
                                            <div class="p-1 h-100 d-flex flex-column justify-content-between px-3">
                                                <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                    {{ $product->name }}</div>
                                                <div class="text-muted fs-2 mb-2">
                                                    {{ $product->description ?? 'tidak ada deskripsi' }}</div>
                                                <div class="d-flex mt-auto align-items-center gap-2 mb-3">
                                                    <button type="submit"
                                                        class="{{ isset($carts[$product->id]) ? '' : 'd-none' }} fs-3 px-3 w-100 text-center justify-content-center btn-sm btn btn-secondary-subtle rounded-2 d-flex align-items-center gap-2"
                                                        disabled>
                                                        <i class="ti ti-package-off"></i>
                                                        <span class="fs-2" style="white-space: nowrap">Sudah Ada</span>
                                                    </button>
                                                    <form action="{{ route('purchase-order.addCart', $purchase->id) }}"
                                                        method="POST"
                                                        class="{{ isset($carts[$product->id]) ? 'd-none' : '' }}"
                                                        style="flex-grow: 1">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">
                                                        <button type="submit"
                                                            class="fs-3 px-3 w-100 text-center justify-content-center btn-sm btn btn-primary rounded-2 d-flex align-items-center gap-2"
                                                            {{
                                                                $purchase->requestOrder &&
                                                                    $purchase->requestOrder->getProcessingAnalytics()[$product->id]['remaining_qty'] == 0
                                                                ? 'disabled' : ''
                                                            }}
                                                            {{ $purchase->status != 0 ? 'disabled' : '' }}
                                                            >
                                                            <i class="ti ti-packge-export"></i>
                                                            <span class="fs-2">
                                                            {{
                                                                $purchase->requestOrder &&
                                                                    $purchase->requestOrder->getProcessingAnalytics()[$product->id]['remaining_qty'] == 0
                                                                ? 'Sudah Diproses Semua' : 'Pilih'
                                                            }}
                                                            </span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @include('partials.paginate',['datas' => $products])
                        </div>
                    </div>
                    <div class="col-lg-7 mb-3 order-first">
                        <div class="d-flex mb-3 align-items-center gap-3">
                            <i class="ti ti-package fs-8"></i>
                            <h5 class="mb-0">Produk yang diproses</h5>
                            <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle"
                                style="aspect-ratio:1/1; width:2.5em; height:2.5em">
                                {{ count($carts) }}
                            </div>
                        </div>
                        <form action="{{ route('purchase-order.product.store', $purchase->id) }}" method="POST"
                            class="d-block bg-white">
                            @csrf
                            <div class="border border-2 border-dashed border-dark-subtle rounded-4 p-3">
                                <p class="fs-2"><span class="text-danger">*</span><i>Perubahan tidak disimpan sampai
                                        anda menekan tombol Simpan</i></p>
                                @php $totalPrice = 0; @endphp
                                @forelse ($carts as $item)
                                    @php
                                        $cart = \App\Models\Product::find($item['id']);
                                        $reqproduct = \App\Models\RequestOrderProduct::where('request_order_id', $purchase->request_order_id)->where('product_id', $item['id'])->first();
                                        $purproduct = \App\Models\PurchaseOrderProduct::where('purchase_order_id', $purchase->id)->where('product_id', $item['id'])->first();
                                        $priceBuy = $item['price_buy'] ?? 0; // Harga jual (diambil dari cart)
                                        $subtotal = $priceBuy * $item['qty']; // Hitung subtotal awal
                                        $totalPrice += $subtotal;
                                    @endphp
                                    <div id="cart-item-{{ $item['id'] }}">
                                        <div
                                            class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                            <img src="{{ $cart->image ? '/storage/' . $cart->image : 'https://placehold.co/300?text=' . $cart->name }}"
                                                alt="Image Product {{ $cart->name }} in Cart" class="d-block rounded-2"
                                                style="width: 5em; height:5em; object-fit:cover">
                                            <div>
                                                <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                    {{ $cart->name }}</div>
                                                <div class="text-muted fs-2 mb-2">
                                                    {{ $cart->description ?? 'tidak ada deskripsi' }}</div>
                                            </div>
                                            <div
                                                class="flex-grow-1 d-flex flex-column align-items-end gap-2 justify-content-between">
                                                <div class="fs-2 fw-semibold">Sub Total</div>
                                                <div class="fs-3 fw-bold subtotal" id="subtotal_{{ $item['id'] }}">
                                                    {{ formatRupiah($subtotal) }}</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end flex-wrap flex-sm-nowrap gap-2">
                                            <div class="flex-grow-1">
                                                <label for="qty" class="form-label mb-0 fs-2">Proses Sebanyak
                                                    ({{ $cart?->unit?->code }})</label>
                                                @if($purchase->requestOrder)
                                                    <div class="fs-2 mb-1 text-muted">
                                                        Belum diproses : 
                                                        {{ 
                                                            $reqproduct->requestOrder->getProcessingAnalytics()[$reqproduct->product_id]['remaining_qty']
                                                            .' '.$cart?->unit?->code
                                                        }}
                                                    </div>
                                                @endif
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="hidden" name="product_id[]"
                                                        value="{{ $item['id'] }}">
                                                    <input type="number" name="qty[]" id="qty_{{ $item['id'] }}"
                                                        class="form-control {{$purchase->requestOrder ? 'form-control-sm' : 'mt-2'}} qty-input"
                                                        data-id="{{ $item['id'] }}" value="{{ $item['qty'] }}"
                                                        min="1" {{$purchase->status != 0 ? 'disabled' : ''}} />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <label for="price_buy" class="form-label mb-0 fs-2">Harga Beli ke Principal</label>
                                                @if($purchase->requestOrder)
                                                    <div class="fs-2 mb-1 text-muted">Harga Jual : {{ formatRupiah($reqproduct->price_sale) }}</div>
                                                @endif
                                                <input type="text" name="price_buy[]"
                                                    id="price_buy_{{ $item['id'] }}"
                                                    class="form-control {{$purchase->requestOrder ? 'form-control-sm' : 'mt-2'}} input-rupiah price-sale-input"
                                                    data-id="{{ $item['id'] }}" value="{{ formatRupiah($priceBuy) }}"
                                                    min="0" {{$purchase->status != 0 ? 'disabled' : ''}} />
                                            </div>
                                            <div class="flex-grow-1 d-flex align-items-end gap-2">
                                                <div class="flex-grow-1">
                                                    <label class="form-label fw-semibold fs-2">Kemas Dengan</label>
                                                    <div style="">
                                                        <select name="pack_id[]" id="pack_id-{{$item['id']}}" class="select2-normal form-select-sm form-select" {{$purchase->status != 0 ? 'disabled' : ''}}>
                                                            <option value="">-- Pilih Kemasan --</option>
                                                            @foreach (\App\Models\Pack::where('unit_id', $cart->unit_id)->get() as $pack)
                                                                <option value="{{$pack->id}}" {{$pack->id == old('pack_id', isset($item['pack_id']) ? $item['pack_id'] : '') ? 'selected' : ''}}>{{$pack->name.' @ '.$pack->capacity.' '.$pack->unit->code}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('pack_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                                <button class="btn btn-sm btn-danger remove-cart"
                                                    data-url="{{ route('purchase-order.removeCart', $purchase->id) }}"
                                                    data-product-id="{{ $item['id'] }}"
                                                    data-reqorder-id="{{ $purchase->id }}" {{$purchase->status != 0 ? 'disabled' : ''}}>
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="text-center fs-3 p-5 border border-2 border-dashed border-primary rounded-4">
                                        Belum ada produk yang dipilih
                                    </div>
                                @endforelse
                                <div class="pt-3 mt-3 border-top border-2">
                                    <div class="d-flex align-items-center mb-2 gap-2 justify-content-between">
                                        <div class="fs-3 fw-semibold">Total</div>
                                        <div class="fs-4 fw-bold" id="totalPrice">{{ formatRupiah($totalPrice) }}</div>
                                        <input type="hidden" name="total_price" value="" id="totalPriceInput">
                                    </div>
                                    <div class="d-flex align-items-center mb-2 gap-2 justify-content-between">
                                        <div class="fs-3 fw-semibold" style="white-space: nowrap">Pajak / VAT</div>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="number" name="tax" value="{{ old('tax', $purchase->tax ) ?? setting('site.ppn') }}" id="tax" placeholder="0" style="width: 5em;" class="form-control text-center form-control-sm">
                                            %
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 justify-content-between">
                                        <div class="fs-3 fw-semibold">Total Termasuk Pajak</div>
                                        <div class="fs-4 fw-bold" id="totalPriceTaxed">-</div>
                                        <input type="hidden" name="total_price_taxed" value="" id="totalPriceTaxedInput">
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        <button type="button" id="refetchBtn" class="btn btn-secondary {{ count($carts) == 0 ? 'd-none' : ''}}"
                                            title="Muat Ulang Pemrosesan Produk"><i class="ti ti-refresh"></i></button>
                                        <button type="submit" class="btn btn-primary w-100" {{$purchase->status != 0 ? 'disabled' : ''}}>Simpan dan Lanjut <span class="d-none d-md-inline-block">ke
                                            Lampiran</span></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Form Step 3 -->
            <div class="step-content" data-step="3" style="display: none;">
                @php
                    $stateFile = [
                        'file' => ['icon' => 'file', 'color' => 'text-primary'],
                        'data' => ['icon' => 'file-database', 'color' => 'text-success'],
                        'report' => ['icon' => 'file-analytics', 'color' => 'text-danger'],
                        'zip' => ['icon' => 'file-zip', 'color' => 'text-danger'],
                        'image' => ['icon' => 'photo', 'color' => 'text-secondary'],
                    ];
                @endphp

                <div class="d-flex mb-3 align-items-center gap-3">
                    <i class="ti ti-files fs-9"></i>
                    <h5 class="mb-0">Lampiran</h5>
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle" style="aspect-ratio:1/1; width:2.5em; height:2.5em">{{ count($purchase->files) }}</div>
                    <div class="ms-auto">
                        <button data-bs-toggle="modal" data-bs-target="#addLampiranModal" class="d-inline-flex align-items-center gap-2 btn btn-primary btn-al-primary"
                        >
                            <i class="ti ti-file-plus"></i> Tambah
                        </button>
                    </div>
                </div>

                <div class="row" id="showLampiran">
                    @forelse ($purchase->files as $file)
                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-3 card-hover">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <span class="flex-shrink-0"><i class="ti ti-{{$stateFile[getTypeFile($file->file)]['icon']}} {{$stateFile[getTypeFile($file->file)]['color']}} display-6"></i></span>
                                    <a href="{{ $file->file ? asset('storage').'/'.$file->file : '#'}}" target="_blank" class="ms-3 flex-grow-1">
                                        <h4 class="card-title text-dark mb-1 fs-3">{{$file->name}}</h4>
                                        <p class="mb-0 fs-2 text-muted fw-normal">
                                            <i class="ti ti-clock me-2"></i>{{ \Carbon\Carbon::parse($file->created_at)->format('d M Y H:i') }}
                                        </p>
                                        <p class="mb-0 fs-2 text-muted fw-normal">
                                            <i class="ti ti-file me-2"></i>{{ getExt($file->file) }}
                                        </p>
                                    </a>
                                    <button data-bs-toggle="modal" data-bs-target="#deleteLampiranModal-{{$file->id}}" class="btn-sm btn btn-danger"
                                    >
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div id="deleteLampiranModal-{{$file->id}}" class="modal fade" tabindex="-1"
                        aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                            <div class="modal-content p-3 modal-filled bg-danger">
                                <div class="modal-header modal-colored-header text-white">
                                    <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                        Yakin ingin menghapus Lampiran {{$file->name}} ?
                                    </h4>
                                    <button type="button" class="btn-close btn-close-white mb-auto"
                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="width: fit-content; white-space:normal">
                                    <h5 class="mt-0 text-white">Lampiran {{$file->name}} akan dihapus</h5>
                                    <p class="text-white">Segala data yang berkaitan dengan alamat tersebut juga akan dihapus secara permanen.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <form action="{{route('purchase-order.file.destroy', ['id' => $purchase->id, 'file_id' => $file->id])}}" method="POST">
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
                    @empty
                    <div class="p-5 text-center border rounded-4 border-dashed border-2 bg-light border-primary">
                        Belum ada Lampiran
                    </div>
                    @endforelse

                    <!-- Add Lampiran modal -->
                    <div class="modal fade" id="addLampiranModal" tabindex="-1" aria-labelledby="vertical-center-modal"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header d-flex align-items-center">
                                    <h4 class="modal-title" id="myLargeModalLabel">
                                        Tambah File Lampiran
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('purchase-order.file.store', $purchase->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold">File Lampiran</label>
                                            <div class="input-group">
                                                <span class="input-group-text px-6" id="basic-addon1"><i
                                                        class="ti ti-file fs-6"></i></span>
                                                <input type="file" name="file" class="form-control bg-white ps-2">
                                            </div>
                                        </div>
                                        <label for="nama" class="form-label">Nama File</label>
                                        <input type="text" name="name" class="form-control mb-2" value="{{ old('nama') }}"
                                        placeholder="Nama File Lampiran">
                                        
                                        <div class="d-flex gap-1 align-items-center justify-content-end">
                                            <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start"
                                                data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-primary btn-al-primary">Tambah File Lampiran</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="step-content" data-step="4" style="display: none;">
                <div class="d-flex mb-3 align-items-center gap-3">
                    <i class="ti ti-credit-card fs-9"></i>
                    <h5 class="mb-0">Tagihan / Invoice</h5>
                </div>
                @if ($purchase->generate_invoice && $purchase->invoice)
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-secondary" style="width: 3em; height:2px"></div>
                        <h6 class="mb-0">Purchase Order Invoice</h6>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="btn-accordion p-3 border border-2 rounded-3 border-dashed">
                                <div class="d-flex align-items-center gap-2 justify-content-between flex-wrap" style="cursor: pointer">
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Invoice
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ $purchase->invoice->code }}</h6>
                                    </div>
                                    @php
                                        $statusPembayaran = [
                                            '0' => ['label' => 'Belum dibayar','color' => 'secondary'],
                                            '1' => ['label' => 'Dibayar Bertahap','color' => 'warning',],
                                            '2' => ['label' => 'Lunas','color' => 'success'],
                                        ];

                                    @endphp
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Pembayaran
                                        </div>
                                        <h6 class="fw-semibold text-start fs-2 text-{{ $statusPembayaran[$purchase->invoice->payment_status]['color'] }} mb-1" style="">{{ $statusPembayaran[$purchase->invoice->payment_status]['label'] }}</h6>
                                    </div>
                                    <div class="ms-md-auto text-start">
                                        <div class="fs-1 text-muted">Klik untuk melihat detail</div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{route('purchase-order.invoice.show', $purchase->invoice->id)}}" target="_blank" class="btn btn-primary"><i class="ti ti-credit-card me-2"></i>Pembayaran</a>
                                        @if(!$purchase->clientInvoice && $purchase->requestOrder && !$purchase->requestOrder->invoice)
                                            <button data-bs-toggle="modal" data-bs-target="#partialClientInvoiceModal-{{$purchase->id}}" class="btn btn-secondary" style=""><i class="ti ti-file-invoice"></i> Buat Partial Invoice</button>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            
                            <div class="btn-accordion-content row mt-3">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Invoice</div>
                                    <h5 class="fw-semibold text-primary mb-1" style="">{{ $purchase->invoice->code }}</h5>
                                    @if($purchase->requestOrder)
                                        <hr>
                                        <h6>Permintaan Client (Request Order)</h6>
                                        <div>
                                            <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                            <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                                {{ \Carbon\Carbon::parse($purchase->requestOrder->date)->format('d F Y') }}</h6>
                                        </div>
                                        <div>
                                            <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Permintaan
                                            </div>
                                            <a href="{{ route('request-order.setting', $purchase->requestOrder->id) }}" class="d-flex align-items-center gap-2 mb-1"><h6 class="fw-semibold mb-0" style="">{{ $purchase->requestOrder->code }}</h6> <i class="ti ti-external-link"></i></a>
                                        </div>
                                        <div>
                                            <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Referensi
                                                Permintaan (PO)</div>
                                            <a href="{{ $purchase->requestOrder->attachment ? '/storage/'.$purchase->requestOrder->attachment : '#' }}" class="badge fs-2 mb-1 bg-primary-subtle text-primary border-primary">
                                                <i class="ti ti-file"></i>
                                                {{ $purchase->requestOrder->attachment ? $purchase->requestOrder->no_refrence : 'Tidak ada Lampiran'}}
                                            </a>
                                        </div>
                                    @endif
                                    <hr>
                                    <div class="">
                                        <h6>Memesan Ke Principal</h6>
                                        <a href="{{route('principal.setting', $purchase->principal->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                            <i class="ti ti-building-skyscraper mb-0 fs-3"></i> {{ $purchase->principal->name }}
                                        </a>
                                        <div class="fw-normal fs-1 text-muted" style="">PIC Principal</div>
                                        <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                            <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $purchase->pic->name }}
                                        </div>
                                        <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                            <i class="ti ti-mail mb-0 fs-3"></i> {{ $purchase->pic->email ?? '-' }}
                                        </div>
                                        <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                            <i class="ti ti-phone mb-0 fs-3"></i> {{ $purchase->pic->phone ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="">
                                        <h6>Dengan Detail Logistik / Pengangkutan</h6>
                                        @if($purchase->transport)
                                            <a href="{{route('logistic.setting', $purchase->transport->logistic->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
                                                <i class="ti ti-truck-delivery mb-0 fs-3"></i> {{ $purchase->transport->logistic->name }}
                                            </a>
                                            <a href="javascript:void(0);" title="Klik untuk melihat detail" class="d-flex text-secondary align-items-center fs-2 mb-1 gap-2"
                                                data-bs-toggle="modal" data-bs-target="#detailDelivery-{{$purchase->id}}"
                                            >
                                                <i class="ti ti-exchange mb-0 fs-3"></i> Detail Antar Jemput
                                            </a>
                                            <!-- List Product modal -->
                                            <div class="modal fade " id="detailDelivery-{{$purchase->id}}" tabindex="-1"
                                                aria-labelledby="vertical-center-modal" aria-hidden="true">
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
                                                                    <div class="fs-3">{{$purchase->pickup->address.', '.$purchase->pickup->city.'. '.$purchase->pickup->postal_code}}</div>
                                                                </div>
                                                            </div>
                                                            @if($purchase->delivery)
                                                            <div class="p-3 rounded-3 border border-dashed border-primary">
                                                                <div class="fs-2 text-muted">Pengantaran Barang</div>
                                                                <div>
                                                                    <div class="fs-3">{{$purchase->delivery->address.', '.$purchase->delivery->city.'. '.$purchase->delivery->postal_code}}</div>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fw-normal fs-1 text-muted" style="">Contact Person</div>
                                            <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                                <i class="ti ti-user-circle mb-0 fs-3"></i> {{ $purchase->transport->logistic->cp_name }}
                                            </div>
                                            <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                                <i class="ti ti-mail mb-0 fs-3"></i> {{ $purchase->transport->logistic->cp_email ?? '-' }}
                                            </div>
                                            <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                                <i class="ti ti-phone mb-0 fs-3"></i> {{ $purchase->transport->logistic->cp_phone ?? '-' }}
                                            </div>
                                        @else
                                            <div class="fs-2">Diurus Oleh Principal</div>
                                        @endif
                                        
                                            <hr>
                                            <div class="mb-3">
                                            <h6>Produk yang diproses</h6>
                                            <button type="button"
                                                class="dropdown-item fs-2 text-center d-inline-flex w-content p-2 px-3 align-items-center gap-2 bg-secondary text-white rounded-3"
                                                data-bs-toggle="modal" data-bs-target="#produkModal-{{$purchase->id}}"><i
                                                    class="fs-4 ti ti-package"></i> {{ count($purchase->products) }} Produk</button>

                                            <!-- List Product modal -->
                                            <div class="modal fade " id="produkModal-{{$purchase->id}}" tabindex="-1"
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
                                                            @foreach ($purchase->products as $purchaseProduct)
                                                                @php
                                                                    $priceSale = $purchaseProduct->price_buy ?? 0; // Harga jual (diambil dari cart)
                                                                    $subtotal = $priceSale * $purchaseProduct->qty; // Hitung subtotal awal
                                                                @endphp
                                                                <div class="border border-1 border-dashed border-primary {{$loop->index+1 == count($purchase->products) ? '' : 'mb-2'}} p-3 rounded-3">
                                                                    <div
                                                                        class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                                                        <img src="{{ $purchaseProduct->product->image ? '/storage/' . $purchaseProduct->product->image : 'https://placehold.co/300?text=' . $purchaseProduct->product->name }}"
                                                                            alt="Image Product {{ $purchaseProduct->product->name }} in Cart" class="d-block rounded-2"
                                                                            style="width: 5em; height:5em; object-fit:cover">
                                                                        <div>
                                                                            <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                                                {{ $purchaseProduct->product->name }}</div>
                                                                            <div class="text-muted fs-2 mb-2">
                                                                                {{ $purchaseProduct->product->description ?? 'tidak ada deskripsi' }}</div>
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
                                                                            <label for="qty" class="text-muted fs-1">Memproses Sebanyak
                                                                                ({{ $purchaseProduct->product->unit->code }})</label>
                                                                            <div class="d-flex align-items-center gap-2">
                                                                                {{ $purchaseProduct->qty }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <label for="price_buy" class="text-muted fs-1">Dengan Harga Beli</label>
                                                                            <div>{{ formatRupiah($purchaseProduct->price_buy) }}</div>
                                                                        </div>
                                                                        <div>
                                                                            <label for="qty" class="text-muted fs-1">Dikemas Dengan </label>
                                                                            <div class="d-flex align-items-center gap-2">
                                                                                {{ $purchaseProduct->pack->name.' @ '.$purchaseProduct->pack->capacity.' '.$purchaseProduct->pack->unit->code }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            <div class="pt-3 mt-3 border-top border-2">
                                                                <div class="d-flex align-items-center gap-2 justify-content-between">
                                                                    <div class="fs-3 fw-semibold">Total</div>
                                                                    @if($purchase->products->count() > 0)
                                                                    <div class="fs-4 fw-bold">{{ formatRupiah($purchase->total_price) }}</div>
                                                                    @else
                                                                    <div class="fs-4 fw-bold">Lengkapi Data Dahulu <a href="{{ route('purchase-order.setting', $purchase->id).'#produk' }}" class="btn btn-sm btn-warning ms-2">Lengkapi</a></div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6>Timestamp</h6>
                                        <div class="d-flex flex-column align-items-start gap-2">
                                            <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">
                                                Updated
                                                at
                                                : {{ $purchase->updated_at }}</div>
                                            <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">
                                                Created
                                                at
                                                : {{ $purchase->created_at }}</div>
                                        </div>
                                    
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h6>Kalkulasi Pembayaran</h6>
                                    @if($purchase->products->count() > 0)
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Total Nilai Awal</div>
                                        <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ formatRupiah($purchase->total_price) }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Pajak</div>
                                        <h6 class="fw-semibold fs-2 text-primary mb-1" style="">{{ $purchase->tax ?? '11' }}%</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Total Dengan Pajak</div>
                                        {{-- @php
                                            dd($purchase->total_price, $purchase->tax, $purchase->tax && (int)$purchase->tax != '0' ? $purchase->tax : 11, ));
                                        @endphp --}}
                                        <h6 class="fw-semibold fs-2 text-primary mb-1">
                                            {{ 
                                                (int) $purchase->total_price_taxed && (int) $purchase->total_price_taxed != 0 
                                                    ? formatRupiah($purchase->total_price_taxed) 
                                                    : formatRupiah((int) $purchase->total_price + ((int) $purchase->total_price * (($purchase->tax && (int) $purchase->tax != 0 ? $purchase->tax : 11) / 100)))
                                            }}
                                        </h6>
                                    </div>
                                    @else
                                    Lengkapi Data Dahulu
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!$purchase->clientInvoice && $purchase->requestOrder && !$purchase->requestOrder->invoice)
                    <!-- invoicing Modal -->
                    <div class="modal fade" id="partialClientInvoiceModal-{{$purchase->id}}" tabindex="-1"
                        aria-labelledby="vertical-center-modal" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header d-flex align-items-center">
                                    <h4 class="modal-title" id="myLargeModalLabel" style="white-space: normal">
                                        Buat Penagihan / Invoice Partial Client
                                    </h4>
                                    <button type="button" class="btn-close mb-auto" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body pt-0">
                                    <form action="{{ route('purchase-order.generatePartialClient', $purchase->id) }}" method="POST">
                                        @csrf
                                        <p class="text-muted" style="white-space: normal">Membuat penagihan / Invoice ke Client Secara Partial (Terpecah) dari Request Order {{$purchase->requestOrder->code}} ? <strong>Invoice Partial akan dibuat untuk Client {{$purchase->requestOrder->client->name}}</strong>. Pembayaran dilakukan per Invoice yang dibuat.</p>
                                        <div class="d-flex gap-1 align-items-center justify-content-end">
                                            <button type="submit"
                                                class="btn btn-primary w-100">Ya, Buat Partial Invoice</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($purchase->clientInvoice)
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-secondary" style="width: 3em; height:2px"></div>
                        <h6 class="mb-0">Partial Invoice Client</h6>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="">
                                <div class="d-flex align-items-center gap-2 justify-content-between flex-wrap">
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Partial Invoice Client
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ $purchase->clientInvoice->code }}</h6>
                                    </div>
                                    @php
                                        $statusPembayaran = [
                                            '0' => ['label' => 'Belum dibayar','color' => 'secondary'],
                                            '1' => ['label' => 'Dibayar Bertahap','color' => 'warning',],
                                            '2' => ['label' => 'Lunas','color' => 'success'],
                                        ];

                                    @endphp
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Pembayaran
                                        </div>
                                        <h6 class="fw-semibold text-start text-sm-end text-md-start fs-2 text-{{ $statusPembayaran[$purchase->clientInvoice->payment_status]['color'] }} mb-1" style="">{{ $statusPembayaran[$purchase->clientInvoice->payment_status]['label'] }}</h6>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 ms-auto">
                                        <a href="{{route('request-order.invoice.show', $purchase->clientInvoice->id)}}" target="_blank" class="btn btn-primary"><i class="ti ti-credit-card me-0 me-sm-2"></i><span class="d-none d-md-inline-block">Pembayaran</span></a>
                                        <button onclick="seePDF('pdf.partial','{{$purchase->clientInvoice->id}}')" class="btn btn-secondary" style="background:rgb(186, 55, 55); border-color:rgb(186, 55, 55)"><i class="ti ti-printer me-2"></i>Cetak Partial Invoice</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($purchase->transport && $purchase->transport->invoice)
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-secondary" style="width: 3em; height:2px"></div>
                        <h6 class="mb-0">Invoice Pengangkutan</h6>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="btn-accordion p-3 border border-2 rounded-3 border-dashed">
                                <div class="d-flex align-items-center gap-3 justify-content-between flex-wrap" style="cursor: pointer">
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Invoice
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ $purchase->invoice->code }}</h6>
                                    </div>
                                    @php
                                        $statusPembayaran = [
                                            '0' => ['label' => 'Belum dibayar','color' => 'secondary'],
                                            '1' => ['label' => 'Dibayar Bertahap','color' => 'warning',],
                                            '2' => ['label' => 'Lunas','color' => 'success'],
                                        ];

                                    @endphp
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Pembayaran
                                        </div>
                                        <h6 class="fw-semibold text-end text-sm-start fs-2 text-{{ $statusPembayaran[$purchase->invoice->payment_status]['color'] }} mb-1" style="">{{ $statusPembayaran[$purchase->invoice->payment_status]['label'] }}</h6>
                                    </div>
                                    <div class="ms-md-auto text-start">
                                        <div class="fs-1 text-muted">Klik untuk melihat detail</div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{route('purchase-order.invoice.show', $purchase->invoice->id)}}" target="_blank" class="btn btn-primary"><i class="ti ti-credit-card me-2"></i>Pembayaran</a>
                                        @if($purchase->requestOrder)
                                            <button onclick="seePDF('pdf.spk','{{$purchase->id}}')" class="btn btn-secondary" style="background:rgb(186, 55, 55); border-color:rgb(186, 55, 55)"><i class="ti ti-printer me-2"></i>SPK</button>
                                            <button onclick="seePDF('pdf.sj','{{$purchase->id}}')" class="btn btn-secondary" style="background:rgb(186, 55, 55); border-color:rgb(186, 55, 55)"><i class="ti ti-printer me-2"></i>SJ</button>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            
                            <div class="btn-accordion-content row mt-3">
                                
                            </div>
                        </div>
                    </div>
                    @endif


                @else
                    <div class="p-5 text-center border rounded-3 border-dashed border-2 bg-light border-primary">
                        <div class="mb-2 mx-auto" style="max-width:40em">Jika pesanan telah "Selesai" namun Belum ada Penagihan, maka akan berstatus sebagai Stock. Pastikan Jangan Lupa Membuat Tagihan / Invoice untuk {{$purchase->requestOrder ? $purchase->requestOrder->client->name : 'Pembelian ke Principal'}} atas Kode Permintaan {{ $purchase->requestOrder ? $purchase->requestOrder->code : $purchase->code }}.</div>
                        @if($purchase->status != 2)
                            <div class="fs-2">Pesanan belum Selesai</div>
                            <button type="button" class="btn p-2 px-3 d-flex w-content mx-auto my-2 {{$purchase->status == 0 ? 'btn-warning' : 'btn-success'}} align-items-center justify-content-center gap-2"
                            data-bs-toggle="modal" data-bs-target="#processModal-{{$purchase->id}}"><i
                                class="fs-4 ti {{$purchase->status == 0 ? 'ti-loader-3' : 'ti-check'}}"></i>{{$purchase->status == 0 ? 'Proses' : 'Selesaikan'}}</button>
                        @else
                        <form action="{{ route('purchase-order.generate', $purchase->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-primary {{$purchase->generate_invoice ? 'd-none' : ''}}"><i class="ti ti-credit-card me-1"></i> Buat Penagihan</button>
                        </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        
    </div>
@endsection
@section('scripts')
    <script src="{{ env('APP_URL') }}/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="{{ env('APP_URL') }}/assets/libs/select2/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            const stepMap = {
                'data': 1,
                'produk': 2,
                'lampiran': 3,
                'invoice': 4
            };

            function updateStepFromHash() {
                let hash = window.location.hash.replace('#', '');
                let step = stepMap[hash] || 1; // Default ke step pertama jika tidak ditemukan
                setActiveStep(step);
            }

            function setActiveStep(step) {
                $(".step").removeClass("active");
                $(".step-content").hide();
                $(".step[data-step='" + step + "']").addClass("active");
                $(".step-content[data-step='" + step + "']").fadeIn();
            }

            $(".step").click(function() {
                let step = $(this).data("step");
                let hashKey = Object.keys(stepMap).find(key => stepMap[key] === step);
                if (hashKey) {
                    window.location.hash = hashKey; // Perbarui hash di URL
                }
            });

            // Jalankan saat halaman dimuat
            updateStepFromHash();

            // Jalankan saat hash berubah
            $(window).on('hashchange', function() {
                updateStepFromHash();
            });

            document.querySelectorAll(".editAddressBtn").forEach(button => {
                button.addEventListener("click", function() {
                    // Ambil data dari atribut tombol
                    let id = this.getAttribute("data-id");
                    let name = this.getAttribute("data-name");
                    let city = this.getAttribute("data-city");
                    let postalCode = this.getAttribute("data-postal-code");
                    let address = this.getAttribute("data-address");
                    let url = this.getAttribute("data-url");

                    // Isi data ke dalam modal
                    document.getElementById("editName").value = name;
                    document.getElementById("editPostalCode").value = postalCode;
                    document.getElementById("editAddress").value = address;

                    // **Set nilai Select2 dan trigger change event**
                    $("#editCity").val(city).trigger("change");

                    // Ubah action form agar sesuai dengan alamat yang diedit
                    document.getElementById("editAddressForm").setAttribute("action", url);
                });
            });
        
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').each(function() {
                let modal = $(this).closest('.modal'); // Cari modal terdekat
                $(this).select2({
                    dropdownParent: modal // Pasang dropdown di dalam modal
                });
            });
            $('.select2-normal').each(function() {
                $(this).select2();
            });

            $('.remove-cart').on('click', function(e) {
                e.preventDefault();

                var url = $(this).data('url');
                var productId = $(this).data('product-id');
                var reqorderId = $(this).data('reqorder-id');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#refetchBtn').on('click', function(e) {
                e.preventDefault();
                let id = "{{ $purchase->id }}"
                $.ajax({
                    url: "{{ route('purchase-order.refetch', $purchase->id) }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#principal_id').on('change', async function () {
                fetchShowBy({
                    url: "{{ route('ajax.showBy') }}",
                    model: "PrincipalPic", // Model yang akan di-fetch
                    key: "principal_id", // Kolom yang digunakan untuk filter
                    data: $(this).val(), // Ambil nilai dari selector
                    isCollection: true, // Apakah hasil koleksi?
                    affectSelectorId: '#principal_pic_id', // Selector untuk menerima data
                    optionPlaceholder: '-- Pilih PIC Principal --' // Placeholder untuk opsi pertama
                });

                fetchByRelation({
                    url: "{{ route('ajax.showRelation') }}",
                    model: "Principal", // Model yang akan di-fetch
                    relation: "addresses", // Kolom yang digunakan untuk filter
                    key: "id", // Kolom yang digunakan untuk filter
                    data: $(this).val(), // Ambil nilai dari selector
                    affectSelectorId: '#pickup_address_id', // Selector untuk menerima data
                    optionPlaceholder: '-- Pilih Alamat Pengambilan Barang --' // Placeholder untuk opsi pertama
                });
            });

            $('#request_order_id').on('change', async function () {
                fetchByRelation({
                    url: "{{ route('ajax.showRelation') }}",
                    model: "RequestOrder", // Model yang akan di-fetch
                    relation: "client.addresses", // Kolom yang digunakan untuk filter
                    key: "id", // Kolom yang digunakan untuk filter
                    data: $(this).val(), // Ambil nilai dari selector
                    affectSelectorId: '#delivery_address_id', // Selector untuk menerima data
                    optionPlaceholder: '-- Pilih Alamat Pengantaran --' // Placeholder untuk opsi pertama
                });
            });

            let purchase = @json($purchase);
            console.log(purchase);
            if (purchase.id) {
                $('#principal_id').val(purchase.principal_id).trigger('change');
                $('#request_order_id').val(purchase.request_order_id).trigger('change');
                setTimeout(() => {
                    $('#principal_pic_id').val(purchase.principal_pic_id).trigger('change'); // Pilih PIC sesuai dengan purchase
                }, 500);
                setTimeout(() => {
                    $('#pickup_address_id').val(purchase.pickup_address_id).trigger('change'); // Pilih PIC sesuai dengan purchase
                }, 1000);
                setTimeout(() => {
                    $('#delivery_address_id').val(purchase.delivery_address_id).trigger('change'); // Pilih PIC sesuai dengan purchase
                }, 1500);
            }

            $('#btnEditData').on('click', function(){
                $('#showDataBox').addClass('d-none');
                $('#editDataBox').removeClass('d-none');
            });

            $('#btnCloseData').on('click', function(){
                $('#showDataBox').removeClass('d-none');
                $('#editDataBox').addClass('d-none');
            });
        });

        $(document).ready(function () {
            // Tutup semua content accordion saat halaman dimuat
            $(".btn-accordion-content").hide();

            // Tambahkan event klik untuk setiap .btn-accordion
            $(".btn-accordion").on("click", function () {
                let content = $(this).next(".btn-accordion-content");

                // Tutup accordion lain (opsional, jika hanya satu yang boleh terbuka)
                $(".btn-accordion-content").not(content).slideUp();

                // Toggle slide untuk content yang diklik
                content.slideToggle();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var redirectHash = "{{ session('redirect_hash') }}";
            if (redirectHash) {
                window.location.hash = redirectHash;
            }

            document.addEventListener('change', function(event) {
                // Cek apakah event berasal dari input file yang memiliki atribut name="image"
                if (event.target.matches('input[type="file"][name="attachment"]')) {
                    const fileInput = event.target;
                    const formContainer = fileInput.closest('.row'); // Mencari form terkait dalam satu grup

                    if (!formContainer) return;

                    const placeholder = formContainer.querySelector('#placeholder-image');
                    const previewImage = formContainer.querySelector('#preview-image');
                    const previewFile = formContainer.querySelector('#preview-file');
                    const previewFileLink = formContainer.querySelector('#preview-file-link');
                    const previewFileEmbed = formContainer.querySelector('#preview-file-embed');
                    const previewFileName = formContainer.querySelector('#preview-file-text');

                    const file = fileInput.files[0];

                    if (file) {
                        if (file.type.startsWith('image/')) {
                            // Preview image
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewImage.src = e.target.result;
                                previewImage.classList.remove('d-none'); // Show image preview
                                previewFile.classList.add('d-none'); // Hide file preview
                                placeholder.classList.add('d-none'); // Hide placeholder
                            };
                            reader.readAsDataURL(file);
                        } else {
                            // Preview file
                            previewFileName.textContent = file.name;
                            previewFileEmbed.src = URL.createObjectURL(file); // Temporary file link
                            previewFileLink.href = URL.createObjectURL(file); // Temporary file link
                            previewFile.classList.remove('d-none'); // Show file preview
                            previewImage.classList.add('d-none'); // Hide image preview
                            placeholder.classList.add('d-none'); // Hide placeholder
                        }
                    } else {
                        // Reset previews
                        previewImage.src = '';
                        previewImage.classList.add('d-none');
                        previewFile.classList.add('d-none');
                        placeholder.classList.remove('d-none'); // Show placeholder
                    }
                }
            });

            $('input[name="name"]').on('input', function() {
                const name = $(this).val();
                $('input[name="slug"]').val(makeSlug(name));
            });

            $('input[name="price"]').on('input', function() {
                $(this).val(formatRupiah($(this).val()));
            })

            // -----------------------
            // Product
            // -----------------------
            function cleanRupiah(value) {
                return parseFloat(value.replace(/[^\d]/g, '') || 0);
            }

            function updateSubtotalAndTotal() {
                let total = 0;

                document.querySelectorAll('.qty-input').forEach(input => {
                    let id = input.dataset.id;
                    let qty = parseInt(input.value) || 1;
                    let priceBuyInput = document.getElementById(`price_buy_${id}`);

                    if (!priceBuyInput) return; // Cegah error jika elemen tidak ditemukan

                    let priceBuy = cleanRupiah(priceBuyInput.value);
                    let subtotal = priceBuy * qty;

                    document.getElementById(`subtotal_${id}`).textContent = formatRupiah(subtotal);
                    total += subtotal;
                });

                document.getElementById('totalPrice').textContent = formatRupiah(total);
                document.getElementById('totalPriceInput').value = total;

                updateTotalWithTax(); // Pastikan pajak diperbarui setiap subtotal berubah
            }

            function updateTotalWithTax() {
                let total = parseFloat(document.getElementById('totalPriceInput').value) || 0;
                let taxInput = document.querySelector('#tax');
                
                if (!taxInput) return; // Cegah error jika input pajak tidak ada

                let taxValue = parseFloat(taxInput.value) || 0;
                let grandTotal = Math.round(total + (total * taxValue / 100));

                document.getElementById('totalPriceTaxed').textContent = formatRupiah(grandTotal);
                document.getElementById('totalPriceTaxedInput').value = grandTotal;
            }

            // Event listener untuk qty dan price_buy
            document.querySelectorAll('.qty-input, .price-sale-input').forEach(input => {
                input.addEventListener('input', updateSubtotalAndTotal);
            });

            $('#tax').on('input',updateTotalWithTax)

            // Event listener untuk memastikan format harga tetap dalam format rupiah setelah diedit
            document.querySelectorAll('.price-sale-input').forEach(input => {
                input.addEventListener('blur', function() {
                    let value = cleanRupiah(this.value);
                    this.value = formatRupiah(value);
                });
            });

            // Jalankan update awal saat halaman dimuat
            updateSubtotalAndTotal();
        });

        $('input[name="is_handle_logistic"]').on('change', function(){
            let check = $(this).is(':checked');
            if(check) {
                $('#logistic_input').removeClass('d-none');
            }else{
                $('#logistic_input').addClass('d-none');
            }
        })

        $('input[name="is_for_client"]').on('change', function(){
            let check = $(this).is(':checked');
            if(check) {
                $('#client_input').removeClass('d-none');
            }else{
                $('#client_input').addClass('d-none');
            }
        })
    </script>
@endsection
