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
                    <h4 class="fw-semibold mb-8">Kelola Permintaan Client : {{ $reqorder->code }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('request-order.index') }}">Permintaan Client</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola Permintaan Client : {{ $reqorder->code }}
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
            <div class="col-6 col-md-3  mb-auto d-flex align-items-center gap-2">
                @php
                $status = [
                    '0' => ['label' => 'Pending','color' => 'secondary'],
                    '1' => ['label' => 'Diproses','color' => 'warning',],
                    '2' => ['label' => 'Selesai','color' => 'success'],
                ];    
                @endphp
                <a href="{{ route('purchase-order.add', ['reqid' => $reqorder->id]) }}" class="btn btn-primary {{$reqorder->status == '2' ? 'd-none' : ''}}"><i class="ti ti-building-factory me-1"></i> <span class="d-none d-sm-inline-block">Proses</span></a>
                @if($reqorder->status == '2' && !$reqorder->invoice)
                <button data-bs-toggle="modal" data-bs-target="#generateInvoiceModal-{{$reqorder->id}}" class="btn btn-secondary" style=""><i class="ti ti-file-invoice"></i> Buat Invoice</button>
                @endif
                <div>
                    <div class="fw-normal fs-1 text-muted" style="">Status</div>
                    <h6 class="fw-semibold fs-2 text-{{ $status[$reqorder->status]['color'] }} mb-1" style="">{{ $status[$reqorder->status]['label'] }}</h6>
                </div>

                @if($reqorder->status == '2')
                <!-- invoicing Modal -->
                <div class="modal fade" id="generateInvoiceModal-{{$reqorder->id}}" tabindex="-1"
                    aria-labelledby="vertical-center-modal" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h4 class="modal-title" id="myLargeModalLabel" style="white-space: normal">
                                    Buat Penagihan / Invoice Client Keseluruhan
                                </h4>
                                <button type="button" class="btn-close mb-auto" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body pt-0">
                                <form action="{{ route('request-order.generate', $reqorder->id) }}" method="POST">
                                    @csrf
                                    <p class="text-muted" style="white-space: normal">Membuat penagihan / Invoice ke Client Secara Keseluruhan dari Request Order {{$reqorder->code}} ? <strong>Invoice akan dibuat untuk Client {{$reqorder->client->name}}</strong>. Akan gagal apabila terdapat Invoice Partial pada permintaan ini.</p>
                                    <div class="d-flex gap-1 align-items-center justify-content-end">
                                        <button type="submit"
                                            class="btn btn-primary w-100">Ya, Buat Invoice</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-6 order-first order-md-0 d-flex align-items-start gap-2 flex-wrap">
                <div class="stepper flex-grow-1 overflow-auto">
                    <div class="step active" data-step="1">
                        <div class="circle">1</div>
                        <div class="label fs-2">Data <span class="d-none d-sm-block">Permintaan Client</span></div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="circle">2</div>
                        <div class="label fs-2">Produk <span class="d-none d-sm-block">dipesan</span></div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="circle">3</div>
                        <div class="label fs-2">Lampiran</div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="4">
                        <div class="circle">4</div>
                        <div class="label fs-2">Proses <span class="d-none d-sm-block">Permintaan</span></div>
                        <div class="line"></div>
                    </div>
                    <div class="step" data-step="5">
                        <div class="circle">5</div>
                        <div class="label fs-2"><span class="d-none d-sm-block">Penagihan / </span>Invoice</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4 d-flex justify-content-end align-items-start gap-2">
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRequestOrder-{{$reqorder->id}}"><i class="ti ti-trash"></i> <span class="ms-1 d-none d-md-inline-block">Hapus</span></button>
                <a href="#" class="btn btn-danger" style="background:rgb(186, 55, 55); border-color:rgb(186, 55, 55)"><i class="ti ti-printer"></i></a>
            </div>

            <!-- Delete Modal -->
            <div id="deleteRequestOrder-{{$reqorder->id}}" class="modal fade" tabindex="-1"
                aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                    <div class="modal-content p-3 modal-filled bg-danger">
                        <div class="modal-header modal-colored-header text-white">
                            <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                Yakin ingin menghapus Permintaan Client (Request Order) {{$reqorder->code}} ?
                            </h4>
                            <button type="button" class="btn-close btn-close-white mb-auto"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="width: fit-content; white-space:normal">
                            <h5 class="mt-0 text-white">Permintaan Client (Request Order) {{$reqorder->code}} akan dihapus</h5>
                            <p class="text-white">Segala data yang berkaitan dengan Request Order tersebut juga akan dihapus secara permanen. Penghapusan dapat dilakukan jika tidak ada Transaksi penting yang terkait.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Close
                            </button>
                            <form action="{{route('request-order.destroy', $reqorder->id)}}" method="POST">
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

                <div id="showDataBox">
                    <div class="card position-relative">
                        <button id="btnEditData" class="btn btn-sm btn-secondary position-absolute top-0 end-0 m-3"><i class="ti ti-edit"></i></button>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Permintaan Client</h6>
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
                                </div>

                                <div class="col-md-4">
                                    <h6>Client yang Meminta</h6>
                                    <a href="{{route('client.setting', $reqorder->client->id)}}" target="_blank" class="border border-primary p-1 px-2 rounded-2 d-inline-flex align-items-center fs-2 mb-1 gap-2">
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
                                </div>

                                <div class="col-md-4">
                                    <h6>Status</h6>
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
                                    <hr>
                                    <h6>Timestamp</h6>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="p-3 border border-2 rounded-3 border-dashed">
                                <div class="d-flex align-items-center gap-3 justify-content-between" style="cursor: pointer">
                                    <div>
                                        <h6 class="fw-semibold text-dark mb-1" style="">Statistik Pemrosesan Produk</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @foreach ($reqorder->getProcessingAnalytics() as $analytic)
                                <div class="col-md-4 py-2 mt-2">
                                    <div class=""><i class="ti ti-package me-2"></i> {{\App\Models\Product::find($analytic['product_id'])->name}}</div>
                                    <hr>
                                    <div class="d-flex align-items-center justify-content-around gap-3">
                                        <div class="d-flex align-items-center gap-2 text-secondary"><i class="ti ti-clock"></i> <span class="text-dark fw-bold fs-2">{{ $analytic['remaining_qty'] }}</span></div>
                                        <div class="d-flex align-items-center gap-2 text-warning"><i class="ti ti-truck-delivery"></i> <span class="text-dark fw-bold fs-2">{{ $analytic['processed_qty'] }}</span></div>
                                        <div class="d-flex align-items-center gap-2 text-success"><i class="ti ti-check"></i> <span class="text-dark fw-bold fs-2">{{ $analytic['finished_qty'] }}</span></div>
                                    </div>
                                    <div class="progress my-2" style="height: 10px">
                                        <div class="progress-bar text-bg-primary" style="width: {{$analytic['percentage']}}%" role="progressbar">
                                            {{$analytic['processed_qty']}} / {{$analytic['requested_qty']}}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row d-none" id="editDataBox">
                    <div class="col-md-4 h-100 mb-3">
                        @if (isPdf($reqorder->attachment))
                            <div id="placeholder-file" class="">
                                <a href="{{ $reqorder->attachment ? '/storage/' . $reqorder->attachment : '#' }}"
                                    target="_blank" id="preview-file-link"
                                    class="d-flex align-items-center gap-2 p-2 rounded-2 border border-al-primary"
                                    target="_blank">
                                    <i class="ti ti-file fs-6"></i>
                                    <div id="preview-file-text" class="fs-2 line-clamp line-clamp-2">
                                        {{ $reqorder->attachment }}</div>
                                </a>
                                <embed src="{{ asset('storage/' . $reqorder->attachment) }}"
                                    class="rounded-4 overflow-hidden mt-3" width="100%" height="600px"
                                    type="application/pdf">
                            </div>
                        @elseif(isImage($reqorder->attachment))
                            <img src="{{ $reqorder->attachment ? '/storage/' . $reqorder->attachment : '#' }}"
                                id="placeholder-file" alt="Raw Material Image Preview" class="rounded-4 shadow w-100"
                                style="">
                        @else
                            <div id="placeholder-file" class="">
                                <a href="{{ $reqorder->attachment ? '/storage/' . $reqorder->attachment : '#' }}"
                                    target="_blank" id="preview-file-link"
                                    class="d-flex align-items-center gap-2 p-2 rounded-2 border border-al-primary"
                                    target="_blank">
                                    <i class="ti ti-file fs-6"></i>
                                    <div id="preview-file-text" class="fs-2 line-clamp line-clamp-2">
                                        {{ $reqorder->attachment ?? 'Tidak ada lampiran' }}</div>
                                </a>
                                <embed src="{{ asset('storage/' . $reqorder->attachment) }}"
                                    class="{{ $reqorder->attachment ? '' : 'd-none' }} rounded-4 overflow-hidden mt-3"
                                    width="100%" height="600px" type="application/pdf">
                            </div>
                        @endif
                        <img src="" id="preview-image" alt="Raw Material Image Preview"
                            class="d-none rounded-4 shadow w-100" style="">
                        <div id="preview-file" class="d-none">
                            <a href="" id="preview-file-link"
                                class="d-flex align-items-center gap-2 p-2 rounded-2 border border-al-primary"
                                target="_blank">
                                <i class="ti ti-file fs-6"></i>
                                <div id="preview-file-text" class="fs-2 line-clamp line-clamp-2">File Name</div>
                            </a>
                            <embed id="preview-file-embed" src="" class="rounded-4 overflow-hidden mt-3"
                                width="100%" height="600px" type="application/pdf">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="px-4 py-3 border-bottom">
                                <h5 class="card-title fw-semibold mb-0">Permintaan Client</h5>
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
                                <form action="{{ route('request-order.update', $reqorder->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Tanggal Permintaan</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-calendar-event fs-6"></i></span>
                                            <input type="date" name="date" class="form-control ps-2"
                                                value="{{ old('date', $reqorder?->date) }}">
                                        </div>
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Client yang membuat permintaan</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-building-skyscraper fs-6"></i></span>
                                            <div style="flex-grow:1">
                                                <select name="client_id" id="client_id"
                                                    class="select2-normal form-select">
                                                    <option value="">-- Pilih Client --</option>
                                                    @foreach ($clients as $client)
                                                        <option value="{{ $client->id }}"
                                                            {{ $client->id == old('client_id', $reqorder?->client_id) ? 'selected' : '' }}>
                                                            {{ $client->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @error('client_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">PIC Client terhadap permintaan ini</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-user-circle fs-6"></i></span>
                                            <div style="flex-grow:1">
                                                <select name="client_pic_id" id="client_pic_id"
                                                    class="select2-normal form-select">
                                                    <option value="">!!! Pilih Client terlebih dahulu !!!</option>
                                                </select>
                                            </div>
                                        </div>
                                        @error('client_pic_id')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Lampirkan Foto / File Permintaan
                                            Client</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-file fs-6"></i></span>
                                            <input type="file" name="attachment" class="form-control ps-2">
                                        </div>
                                        @error('attachment')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nomor Refrensi Permintaan Client</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-text-caption fs-6"></i></span>
                                            <input type="text" name="no_refrence"
                                                value="{{ old('no_refrence', $reqorder?->no_refrence) }}"
                                                class="form-control ps-2"
                                                placeholder="Nomor pada surat PO (Permintaan) yang diberikan Client">
                                        </div>
                                        @error('no_refrence')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Deskripsi</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-align-justified fs-6"></i></span>
                                            <textarea class="form-control ps-2" name="description" id="description" cols="20" rows="5"
                                                placeholder="Deskripsi untuk Permintaan Client">{{ old('description', $reqorder?->description) }}</textarea>
                                        </div>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary" {{$reqorder->status != 0 ? 'disabled' : ''}}>
                                        Simpan dan Lanjut ke Produk Permintaan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $carts = session()->get('cart_' . $reqorder->id, []);
                $totalPrice = 0;
            @endphp
            <!-- Form Step 2 -->
            <div class="step-content" data-step="2" style="display: none;">
                <div class="row">
                    <div class="col-md-7">
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="btn-accordion p-3 border border-2 rounded-3 border-dashed">
                                    <div class="d-flex align-items-center gap-3 justify-content-between" style="cursor: pointer">
                                        <div>
                                            <h6 class="fw-semibold text-dark mb-1" style="">Statistik Pemrosesan Produk</h6>
                                        </div>
                                        <div class="flex-grow-1 text-end">
                                            <div class="fs-1 text-muted">Klik untuk melihat detail</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row btn-accordion-content mt-3">
                                @foreach ($reqorder->getProcessingAnalytics() as $analytic)
                                    <div class="col-md-4 py-2">
                                        <div class=""><i class="ti ti-package me-2"></i> {{\App\Models\Product::find($analytic['product_id'])->name}}</div>
                                        <div class="progress my-2" style="height: 10px">
                                            <div class="progress-bar text-bg-primary" style="width: {{$analytic['percentage']}}%" role="progressbar">
                                            {{$analytic['processed_qty']}} / {{$analytic['requested_qty']}}
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-around gap-3">
                                            <div class="d-flex align-items-center gap-2 text-secondary"><i class="ti ti-clock"></i> <span class="text-dark fw-bold fs-2">{{ $analytic['remaining_qty'] }}</span></div>
                                            <div class="d-flex align-items-center gap-2 text-warning"><i class="ti ti-truck-delivery"></i> <span class="text-dark fw-bold fs-2">{{ $analytic['processed_qty'] }}</span></div>
                                            <div class="d-flex align-items-center gap-2 text-success"><i class="ti ti-check"></i> <span class="text-dark fw-bold fs-2">{{ $analytic['finished_qty'] }}</span></div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        </div>

                        <div>
                            <form action="{{ url()->current() }}" method="GET" class="w-100">
                                <input type="hidden" name="hashProduct" value="1">
                                <div class="row align-items-end mb-3 flex-wrap">
                                    <div class="col-md-9 mb-2 flex-grow-1">
                                        <label for="search" class="form-label">Filter Produk</label>
                                        <input type="text" class="form-control" placeholder="Cari Produk"
                                            name="search" value="{{ $filter->q ?? '' }}">
                                    </div>
                                    <div class="col-md-3 mb-2">
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
                        <div class="row">
                            @foreach ($products as $product)
                                <div class="col-6 col-md-4 mb-2">
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
                                                    <form action="{{ route('request-order.addCart', $reqorder->id) }}"
                                                        method="POST"
                                                        class="{{ isset($carts[$product->id]) ? 'd-none' : '' }}"
                                                        style="flex-grow: 1">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">
                                                        <button type="submit"
                                                            class="fs-3 px-3 w-100 text-center justify-content-center btn-sm btn btn-primary rounded-2 d-flex align-items-center gap-2">
                                                            <i class="ti ti-packge-export"></i>
                                                            <span class="fs-2">Pilih</span>
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
                    <div class="col-md-5 mb-3 order-first">
                        
                        <div class="d-flex mb-3 align-items-center gap-3">
                            <i class="ti ti-package fs-8"></i>
                            <h5 class="mb-0">Produk yang dipesan</h5>
                            <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle"
                                style="aspect-ratio:1/1; width:2.5em; height:2.5em">
                                {{ count($carts) }}
                            </div>
                        </div>
                        
                        <form action="{{ route('request-order.product.store', $reqorder->id) }}" method="POST"
                            class="d-block bg-white">
                            @csrf
                            <div class="border border-2 border-dashed border-dark-subtle rounded-4 p-3">
                                <p class="fs-2"><span class="text-danger">*</span><i>Perubahan tidak disimpan sampai
                                        anda menekan tombol Simpan</i></p>
                                @php $totalPrice = 0; @endphp
                                @forelse ($carts as $item)
                                    @php
                                        $cart = \App\Models\Product::find($item['id']);
                                        $priceSale = $item['price_sale'] ?? 0; // Harga jual (diambil dari cart)
                                        $subtotal = $priceSale * $item['qty']; // Hitung subtotal awal
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
                                        <div class="d-flex align-items-end gap-2">
                                            <div>
                                                <label for="qty" class="form-label {{ $cart->unit ? '' : 'text-danger'}} fs-2">
                                                    {{ $cart->unit ? 'Dibeli Sebanyak ('.$cart?->unit?->code.')' : 'Satuan produk belum diatur' }}</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="hidden" name="product_id[]"
                                                        value="{{ $item['id'] }}">
                                                    <input type="number" name="qty[]" id="qty_{{ $item['id'] }}"
                                                        class="form-control form-control-sm qty-input"
                                                        data-id="{{ $item['id'] }}" value="{{ $item['qty'] }}"
                                                        min="1" {{$reqorder->status != 0 ? 'disabled' : ''}} />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <label for="price_sale" class="form-label fs-2">Dengan Harga Jual</label>
                                                <input type="text" name="price_sale[]"
                                                    id="price_sale_{{ $item['id'] }}"
                                                    class="form-control form-control-sm input-rupiah price-sale-input"
                                                    data-id="{{ $item['id'] }}" value="{{ formatRupiah($priceSale) }}"
                                                    min="0" {{$reqorder->status != 0 ? 'disabled' : ''}} />
                                            </div>
                                            <button class="btn btn-sm btn-danger remove-cart"
                                                data-url="{{ route('request-order.removeCart', $reqorder->id) }}"
                                                data-product-id="{{ $item['id'] }}"
                                                data-reqorder-id="{{ $reqorder->id }}" {{$reqorder->status != 0 ? 'disabled' : ''}}>
                                                <i class="ti ti-trash"></i>
                                            </button>
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
                                            <input type="number" name="tax" value="{{ old('tax', $reqorder->tax ) ?? setting('site.ppn')}}" id="tax" placeholder="0" style="width: 5em;" class="form-control text-center form-control-sm">
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
                                            title="Muat Ulang Pemrosesan Produk" {{$reqorder->status != 0 ? 'disabled' : ''}}><i class="ti ti-refresh"></i></button>
                                        <button type="submit" class="btn btn-primary w-100" {{$reqorder->status != 0 ? 'disabled' : ''}}>Simpan dan Lanjut ke
                                            Lampiran</button>
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
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle" style="aspect-ratio:1/1; width:2.5em; height:2.5em">{{ count($reqorder->files) }}</div>
                    <div class="ms-auto">
                        <button data-bs-toggle="modal" data-bs-target="#addLampiranModal" class="d-inline-flex align-items-center gap-2 btn btn-primary btn-al-primary"
                        >
                            <i class="ti ti-file-plus"></i> Tambah
                        </button>
                    </div>
                </div>

                <div class="row" id="showLampiran">
                    @forelse ($reqorder->files as $file)
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
                                    <form action="{{route('request-order.file.destroy', ['id' => $reqorder->id, 'file_id' => $file->id])}}" method="POST">
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
                                    <form action="{{ route('request-order.file.store', $reqorder->id) }}" method="POST" enctype="multipart/form-data">
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
                    <i class="ti ti-building-factory fs-9"></i>
                    <h5 class="mb-0">Proses Ke Principal</h5>
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle"
                        style="aspect-ratio:1/1; width:2.5em; height:2.5em">
                        {{ $reqorder->purchaseOrders->count() }}
                    </div>
                </div>
                @if ($reqorder->purchaseOrders->count() > 0)
                    @foreach ($reqorder->purchaseOrders as $purchase)
                        <div class="card">
                            <div class="card-body">
                                <div class="btn-accordion p-3 border border-2 rounded-3 border-dashed">
                                    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-between" style="cursor: pointer">
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
                                        <div class="flex-grow-1 text-end">
                                            <div class="fs-1 text-muted">Klik untuk melihat detail</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-accordion-content row mt-3">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <a class="d-flex align-items-center gap-2 mb-2" href="{{route('purchase-order.setting', $purchase->id)}}"><h6 class="mb-0">Pembelian</h6> <i class="ti ti-external-link"></i></a>
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
                                        <h6>Permintaan Client (Request Order)</h6>
                                        <div>
                                            <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                            <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                                {{ \Carbon\Carbon::parse($purchase->requestOrder->date)->format('d F Y') }}</h6>
                                        </div>
                                        <div>
                                            <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Permintaan
                                            </div>
                                            <h6 class="fw-semibold text-primary mb-1" style="">{{ $purchase->requestOrder->code }}</h6>
                                        </div>
                                        <div>
                                            <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Referensi
                                                Permintaan (PO)</div>
                                            <a href="{{ $purchase->requestOrder->attachment ? '/storage/'.$purchase->requestOrder->attachment : '#' }}" class="badge fs-2 mb-1 bg-primary-subtle text-primary border-primary">
                                                <i class="ti ti-file"></i>
                                                {{ $purchase->requestOrder->attachment ? $purchase->requestOrder->no_refrence : 'Tidak ada Lampiran'}}
                                            </a>
                                        </div>
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
                                            @if($purchase->transport)
                                                <h6>Dengan Detail Logistik / Pengangkutan</h6>
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
                                        <div class="mb-3">
                                            <h6>Produk yang diproses</h6>
                                            <button type="button"
                                                class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-secondary text-white rounded-3"
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
                    @endforeach
                @else
                    <div class="p-5 text-center border rounded-3 border-dashed border-2 bg-light border-primary">
                        <div class="mb-2">Permintaan ini Belum di Proses</div>
                        <a href="{{ route('purchase-order.add', ['reqid' => $reqorder->id]) }}" class="btn btn-primary">Proses Permintaan</a>
                    </div>
                @endif  
            </div>  
            
             <div class="step-content" data-step="5" style="display: none;">
                <div class="d-flex mb-3 align-items-center gap-3">
                    <i class="ti ti-credit-card fs-9"></i>
                    <h5 class="mb-0">Penagihan / Invoices</h5>
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle"
                        style="aspect-ratio:1/1; width:2.5em; height:2.5em">
                        {{ $reqorder->invoices->count() }}
                    </div>
                </div>

                @if ($reqorder->invoice && !$reqorder->invoice->purchaseOrder)
                    @php
                        $roInvoice = $reqorder->invoice;
                    @endphp
                    <div class="card">
                        <div class="card-body">
                            <div class="btn-accordion p-3 border border-2 rounded-3 border-dashed">
                                <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between" style="cursor: pointer">
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Invoice
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ $roInvoice->code }}</h6>
                                    </div>
                                    @php
                                        $status = [
                                            '0' => ['label' => 'Pending','color' => 'secondary'],
                                            '1' => ['label' => 'Diproses','color' => 'warning',],
                                            '2' => ['label' => 'Selesai','color' => 'success'],
                                        ];
                                    @endphp
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Pembayaran
                                        </div>
                                        <h6 class="fw-semibold fs-2 text-{{ $status[$roInvoice->payment_status]['color'] }} mb-1" style="">{{ $status[$roInvoice->payment_status]['label'] }}</h6>
                                    </div>
                                    <div class="flex-grow-1 text-end mb-2 d-flex align-items-center justify-content-end gap-2">
                                        <div class="fs-1 text-muted">Klik untuk melihat detail</div>
                                        <a href="{{route('request-order.invoice.show', $roInvoice->id)}}" class="btn btn-primary"><i class="ti ti-credit-card"></i></a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-100">
                                        <div class="progress mt-1">
                                            <div class="progress-bar progress-bar-striped text-bg-{{$roInvoice->payment_percentage && $roInvoice->payment_percentage == '100' ? 'success' : 'info' }} progress-bar-animated" role="progressbar"
                                                aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: {{$roInvoice->payment_percentage ?? '0'}}%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fs-2" style="white-space: nowrap"><strong>{{$roInvoice->payment_percentage ?? '0'}}%</strong> Dibayar</div>
                                </div>
                            </div>
                            <div class="btn-accordion-content row mt-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Invoice
                                        </div>
                                        <h6 class="fw-semibold text-primary fs-5" style="">{{ $roInvoice->code }}</h6>
                                    </div>
                                    <hr>
                                    <h6>Permintaan Client (Request Order)</h6>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                        <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                            {{ \Carbon\Carbon::parse($roInvoice->requestOrder->date)->format('d F Y') }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Permintaan
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ $roInvoice->requestOrder->code }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Referensi
                                            Permintaan (PO)</div>
                                        <a href="{{ $roInvoice->requestOrder->attachment ? '/storage/'.$roInvoice->requestOrder->attachment : '#' }}" class="badge fs-2 mb-1 bg-primary-subtle text-primary border-primary">
                                            <i class="ti ti-file"></i>
                                            {{ $roInvoice->requestOrder->attachment ? $roInvoice->requestOrder->no_refrence : 'Tidak ada Lampiran'}}
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="mb-3">
                                        <h6>Produk yang diproses</h6>
                                        <button type="button"
                                            class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-secondary text-white rounded-3"
                                            data-bs-toggle="modal" data-bs-target="#produkProcessedModal-{{$roInvoice->purchaseOrder ? $roInvoice->purchaseOrder->id : $roInvoice->id}}"><i
                                                class="fs-4 ti ti-package"></i> {{ count($roInvoice->purchaseOrder ? $roInvoice->purchaseOrder->products : $roInvoice->requestOrder->products) }} Produk</button>

                                        <!-- List Product modal -->
                                        <div class="modal fade " id="produkProcessedModal-{{$roInvoice->purchaseOrder ? $roInvoice->purchaseOrder->id : $roInvoice->id}}" tabindex="-1"
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
                                                        @foreach ($roInvoice->product_qty_price['products'] as $productId => $productRo)
                                                            @php
                                                            $productPo = (object) ['product' => \App\Models\Product::where('id',$productId)->first()];
                                                            if($roInvoice->purchaseOrder)
                                                                $productPo = \App\Models\PurchaseOrderProduct::where('purchase_order_id', $roInvoice->purchase_order_id)->where('product_id',$productId)->first(); // Harga jual (diambil dari cart)
                                                            @endphp
                                                            <div class="border border-1 border-dashed border-primary {{$loop->index+1 == count($roInvoice->purchaseOrder ? $roInvoice->purchaseOrder->products : $roInvoice->requestOrder->products) ? '' : 'mb-2'}} p-3 rounded-3">
                                                                <div
                                                                    class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                                                    <img src="{{ $productPo->product->image ? '/storage/' . $productPo->product->image : 'https://placehold.co/300?text=' . $productPo->product->name }}"
                                                                        alt="Image Product {{ $productPo->product->name }} in Cart" class="d-block rounded-2"
                                                                        style="width: 5em; height:5em; object-fit:cover">
                                                                    <div>
                                                                        <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                                            {{ $productPo->product->name }}</div>
                                                                        <div class="text-muted fs-2 mb-2">
                                                                            {{ $productPo->product->description ?? 'tidak ada deskripsi' }}</div>
                                                                    </div>
                                                                    <div
                                                                        class="flex-grow-1 d-flex flex-column align-items-end gap-2 justify-content-between">
                                                                        <div class="fs-2 fw-semibold">Sub Total</div>
                                                                        <div class="fs-3 fw-bold subtotal">
                                                                            {{ formatRupiah($productRo['total_price_sale']) }}</div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-end gap-2">
                                                                    <div>
                                                                        <label for="qty" class="text-muted fs-1">Memproses Sebanyak
                                                                            ({{ $productPo->product->unit->code }})</label>
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            {{ $productRo['qty'] }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <label for="price_buy" class="text-muted fs-1">Dengan Harga Jual</label>
                                                                        <div>{{ formatRupiah($productRo['price_sale']) }}</div>
                                                                    </div>
                                                                    @if($roInvoice->purchaseOrder)
                                                                    <div>
                                                                        <label for="qty" class="text-muted fs-1">Dikemas Dengan </label>
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            {{ $productPo->pack->name.' @ '.$productPo->pack->capacity.' '.$productPo->pack->unit->code }}
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <div class="pt-3 mt-3 border-top border-2">
                                                            <div class="d-flex align-items-center gap-2 justify-content-between">
                                                                <div class="fs-3 fw-semibold">Total</div>
                                                                <div class="fs-4 fw-bold">{{ formatRupiah($roInvoice->product_qty_price['total_price']) }}</div>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2 justify-content-between">
                                                                <div class="fs-3 fw-semibold">PPN</div>
                                                                <div class="fs-4 fw-bold">{{ $roInvoice->product_qty_price['tax'] }}%</div>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2 justify-content-between">
                                                                <div class="fs-3 fw-semibold">Grand Total</div>
                                                                <div class="fs-4 fw-bold">{{ formatRupiah($roInvoice->product_qty_price['total_price_taxed']) }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h6>Statistik Pembayaran</h6>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Total Tagihan</div>
                                        <h6 class="fs-2 fw-semibold text-secondary mb-1" style="">{{ $roInvoice->purchase_order_id ? formatRupiah($roInvoice->product_qty_price['total_price_taxed']) : formatRupiah($roInvoice->requestOrder->total_price_taxed) }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Dibayarkan</div>
                                        <h6 class="fs-2 fw-semibold text-success mb-1" style="">{{ formatRupiah($roInvoice->sum_paid_total) }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Belum Dibayar</div>
                                        <h6 class="fs-2 fw-semibold text-danger mb-1" style="">{{ formatRupiah($roInvoice->remaining_payment) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($reqorder->invoices->count() > 0 && $reqorder->invoice->purchaseOrder)
                    @foreach ($reqorder->invoices as $roInvoice)
                        <div class="card">
                        <div class="card-body">
                            <div class="btn-accordion p-3 border border-2 rounded-3 border-dashed">
                                <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between" style="cursor: pointer">
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Invoice
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ $roInvoice->code }}</h6>
                                    </div>
                                    @php
                                        $status = [
                                            '0' => ['label' => 'Pending','color' => 'secondary'],
                                            '1' => ['label' => 'Diproses','color' => 'warning',],
                                            '2' => ['label' => 'Selesai','color' => 'success'],
                                        ];
                                    @endphp
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="">Status Pembayaran
                                        </div>
                                        <h6 class="fw-semibold fs-2 text-{{ $status[$roInvoice->payment_status]['color'] }} mb-1" style="">{{ $status[$roInvoice->payment_status]['label'] }}</h6>
                                    </div>
                                    <div class="flex-grow-1 text-end mb-2 d-flex align-items-center justify-content-end gap-2">
                                        <div class="fs-1 text-muted">Klik untuk melihat detail</div>
                                        <a href="{{route('request-order.invoice.show', $roInvoice->id)}}" class="btn btn-primary"><i class="ti ti-credit-card"></i></a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-100">
                                        <div class="progress mt-1">
                                            <div class="progress-bar progress-bar-striped text-bg-{{$roInvoice->payment_percentage && $roInvoice->payment_percentage == '100' ? 'success' : 'info' }} progress-bar-animated" role="progressbar"
                                                aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: {{$roInvoice->payment_percentage ?? '0'}}%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fs-2" style="white-space: nowrap"><strong>{{$roInvoice->payment_percentage ?? '0'}}%</strong> Dibayar</div>
                                </div>
                            </div>
                            <div class="btn-accordion-content row mt-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Invoice
                                        </div>
                                        <h6 class="fw-semibold text-primary fs-5" style="">{{ $roInvoice->code }}</h6>
                                    </div>
                                    <hr>
                                    <h6>Permintaan Client (Request Order)</h6>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal</div>
                                        <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                            {{ \Carbon\Carbon::parse($roInvoice->requestOrder->date)->format('d F Y') }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Permintaan
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ $roInvoice->requestOrder->code }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Referensi
                                            Permintaan (PO)</div>
                                        <a href="{{ $roInvoice->requestOrder->attachment ? '/storage/'.$roInvoice->requestOrder->attachment : '#' }}" class="badge fs-2 mb-1 bg-primary-subtle text-primary border-primary">
                                            <i class="ti ti-file"></i>
                                            {{ $roInvoice->requestOrder->attachment ? $roInvoice->requestOrder->no_refrence : 'Tidak ada Lampiran'}}
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="mb-3">
                                        <h6>Produk yang diproses</h6>
                                        <button type="button"
                                            class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-secondary text-white rounded-3"
                                            data-bs-toggle="modal" data-bs-target="#produkProcessedModal-{{$roInvoice->purchaseOrder ? $roInvoice->purchaseOrder->id : $roInvoice->id}}"><i
                                                class="fs-4 ti ti-package"></i> {{ count($roInvoice->purchaseOrder ? $roInvoice->purchaseOrder->products : $roInvoice->requestOrder->products) }} Produk</button>

                                        <!-- List Product modal -->
                                        <div class="modal fade " id="produkProcessedModal-{{$roInvoice->purchaseOrder ? $roInvoice->purchaseOrder->id : $roInvoice->id}}" tabindex="-1"
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
                                                        @foreach ($roInvoice->product_qty_price['products'] as $productId => $productRo)
                                                            @php
                                                            $productPo = (object) ['product' => \App\Models\Product::where('id',$productId)->first()];
                                                            if($roInvoice->purchaseOrder)
                                                                $productPo = \App\Models\PurchaseOrderProduct::where('purchase_order_id', $roInvoice->purchase_order_id)->where('product_id',$productId)->first(); // Harga jual (diambil dari cart)
                                                            @endphp
                                                            <div class="border border-1 border-dashed border-primary {{$loop->index+1 == count($roInvoice->purchaseOrder ? $roInvoice->purchaseOrder->products : $roInvoice->requestOrder->products) ? '' : 'mb-2'}} p-3 rounded-3">
                                                                <div
                                                                    class="d-flex align-items-center gap-2 mb-2 {{ $loop->index == 0 ? '' : 'mt-3' }}">
                                                                    <img src="{{ $productPo->product->image ? '/storage/' . $productPo->product->image : 'https://placehold.co/300?text=' . $productPo->product->name }}"
                                                                        alt="Image Product {{ $productPo->product->name }} in Cart" class="d-block rounded-2"
                                                                        style="width: 5em; height:5em; object-fit:cover">
                                                                    <div>
                                                                        <div class="text-decoration-none text-dark fs-3 fw-semibold">
                                                                            {{ $productPo->product->name }}</div>
                                                                        <div class="text-muted fs-2 mb-2">
                                                                            {{ $productPo->product->description ?? 'tidak ada deskripsi' }}</div>
                                                                    </div>
                                                                    <div
                                                                        class="flex-grow-1 d-flex flex-column align-items-end gap-2 justify-content-between">
                                                                        <div class="fs-2 fw-semibold">Sub Total</div>
                                                                        <div class="fs-3 fw-bold subtotal">
                                                                            {{ formatRupiah($productRo['total_price_sale']) }}</div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-end gap-2">
                                                                    <div>
                                                                        <label for="qty" class="text-muted fs-1">Memproses Sebanyak
                                                                            ({{ $productPo->product->unit->code }})</label>
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            {{ $productRo['qty'] }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <label for="price_buy" class="text-muted fs-1">Dengan Harga Jual</label>
                                                                        <div>{{ formatRupiah($productRo['price_sale']) }}</div>
                                                                    </div>
                                                                    @if($roInvoice->purchaseOrder)
                                                                    <div>
                                                                        <label for="qty" class="text-muted fs-1">Dikemas Dengan </label>
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            {{ $productPo->pack->name.' @ '.$productPo->pack->capacity.' '.$productPo->pack->unit->code }}
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <div class="pt-3 mt-3 border-top border-2">
                                                            <div class="d-flex align-items-center gap-2 justify-content-between">
                                                                <div class="fs-3 fw-semibold">Total</div>
                                                                <div class="fs-4 fw-bold">{{ formatRupiah($roInvoice->product_qty_price['total_price']) }}</div>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2 justify-content-between">
                                                                <div class="fs-3 fw-semibold">PPN</div>
                                                                <div class="fs-4 fw-bold">{{ $roInvoice->product_qty_price['tax'] }}%</div>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2 justify-content-between">
                                                                <div class="fs-3 fw-semibold">Grand Total</div>
                                                                <div class="fs-4 fw-bold">{{ formatRupiah($roInvoice->product_qty_price['total_price_taxed']) }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h6>Statistik Pembayaran</h6>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Total Tagihan</div>
                                        <h6 class="fs-2 fw-semibold text-secondary mb-1" style="">{{ $roInvoice->purchase_order_id ? formatRupiah($roInvoice->product_qty_price['total_price_taxed']) : formatRupiah($roInvoice->requestOrder->total_price_taxed) }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Dibayarkan</div>
                                        <h6 class="fs-2 fw-semibold text-success mb-1" style="">{{ formatRupiah($roInvoice->sum_paid_total) }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Belum Dibayar</div>
                                        <h6 class="fs-2 fw-semibold text-danger mb-1" style="">{{ formatRupiah($roInvoice->remaining_payment) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="p-4 text-center border border-dash">
                    Belum ada Invoice, Buat Invoice secara partial pada Pembelian ke Principal, atau Secara keseluruhan jika status Permintaan Client telah selesai.
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
                'process': 4,
                'invoices': 5
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
                let modal = $(this).closest('.modal'); // Cari modal terdekat
                $(this).select2();
            });

            $('#client_id').on('change', async function() {
                fetchShowBy({
                    url: "{{ route('ajax.showBy') }}",
                    model: "ClientPic", // Model yang akan di-fetch
                    key: "client_id", // Kolom yang digunakan untuk filter
                    data: $(this).val(), // Ambil nilai dari selector
                    isCollection: true, // Apakah hasil koleksi?
                    affectSelectorId: '#client_pic_id', // Selector untuk menerima data
                    optionPlaceholder: '-- Pilih PIC Client --' // Placeholder untuk opsi pertama
                });
            });
            let reqorder = @json($reqorder);
            if (reqorder.id) {
                $('#client_id').val(reqorder.client_id).trigger('change');
                setTimeout(() => {
                    $('#client_pic_id').val(reqorder.client_pic_id).trigger(
                    'change'); // Pilih PIC sesuai dengan reqorder
                }, 500);
            }

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
                let id = "{{ $reqorder->id }}"
                $.ajax({
                    url: "{{ route('request-order.refetch', $reqorder->id) }}",
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
                    let priceSaleInput = document.getElementById(`price_sale_${id}`);
                    let priceSale = cleanRupiah(priceSaleInput
                    .value); // Bersihkan format "Rp 2.312" menjadi 2312
                    let subtotal = priceSale * qty;

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

            $('#tax').on('input',updateTotalWithTax)

            document.querySelectorAll('.qty-input, .price-sale-input').forEach(input => {
                input.addEventListener('input', updateSubtotalAndTotal);
            });

            // Event listener untuk memastikan price_sale tetap dalam format Rupiah saat diinput
            document.querySelectorAll('.price-sale-input').forEach(input => {
                input.addEventListener('blur', function() {
                    let value = cleanRupiah(this.value); // Bersihkan nilai
                    this.value = formatRupiah(value); // Format kembali ke Rp setelah edit
                });
            });

            // Jalankan update awal saat halaman dimuat
            updateSubtotalAndTotal();
        });
    </script>
@endsection
