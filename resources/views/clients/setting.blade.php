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
            width: 120px;
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
                    <h4 class="fw-semibold mb-8">Kelola Client : {{ $client->name }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('client.index') }}">Client</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola {{ $client->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{env('APP_URL')}}/assets/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="stepper">
            <div class="step active" data-step="1">
                <div class="circle">1</div>
                <div class="label fs-2">Data Client</div>
                <div class="line"></div>
            </div>
            <div class="step" data-step="2">
                <div class="circle">2</div>
                <div class="label fs-2">Alamat Proyek / Lainnya</div>
                <div class="line"></div>
            </div>
            <div class="step" data-step="3">
                <div class="circle">3</div>
                <div class="label fs-2">PIC / Sales</div>
            </div>
        </div>

        <!-- Form Step 1 -->
        <div id="stepper-form">
            <div class="step-content active" data-step="1">
                <div class="card" id="view-principal">
                    <div class="px-4 py-3 border-bottom w-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title fw-semibold mb-3">Data Client</h5>
                            <div class="ms-auto d-flex align-items-center gap-2">
                                <button type="button" class="btn-principal-edit btn btn-sm btn-secondary d-flex align-items-center gap-2"><i class="ti ti-edit"></i> Edit</button>
                                <button type="button" class="btn btn-sm btn-danger d-flex align-items-center gap-2"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                ><i class="ti ti-trash"></i> Hapus</button>
                            </div>

                            <!-- Delete Modal -->
                            <div id="deleteModal" class="modal fade" tabindex="-1"
                                aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                    <div class="modal-content p-3 modal-filled bg-danger">
                                        <div class="modal-header modal-colored-header text-white">
                                            <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                                Yakin ingin menghapus Client {{$client->name}} ?
                                            </h4>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" style="width: fit-content; white-space:normal">
                                            <h5 class="mt-0 text-white">Client {{$client->name}} akan dihapus</h5>
                                            <p class="text-white">Segala data yang berkaitan dengan alamat tersebut juga akan dihapus secara permanen.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <form action="{{route('client.destroy', $client->id)}}" method="POST">
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
                        <div class="d-flex flex-wrap align-items-center mb-3 w-100" style="width:15em">
                            <img src="{{ $client->image ? asset('/storage/'.$client->image) : '/assets/images/profile/user-1.jpg' }}"
                                class="rounded-2" alt="principal Image {{ $client->name }}" style="width: 4em" />
                            <div class="ms-3 flex-grow-1">
                                <h6 class="fw-semibold mb-1" style="white-space: normal !important">{{ $client->name }}</h6>
                                <div class="fw-normal text-muted" style="white-space:normal; font-size:13px; ">{{ $client->description ?? 'Tidak ada deskripsi' }}</div>    
                            </div>
                            <div class="ms-auto">
                                <a href="#alamat-proyek" class="btn btn-light btn-sm rounded-pill p-2 px-3 mb-2"><i class="ti ti-map-2 fs-3 me-2"></i>{{ $client->addresses->count() }} Alamat Proyek / Lainnya</a>
                            </div>
                        </div>
                        <div class="text-dark mb-2">
                            <div class="fw-normal fs-2" style="white-space:normal; font-size:13px; ">{{ $client->address }}</div>    
                            <div class="text-primary fs-2">{{ $client->city }}. {{$client->postal_code}}</div>
                        </div>
                        <div class="text-dark ">
                            <div class="d-flex align-items-center fs-2 mb-2 gap-2">
                                <i class="ti ti-building-bank mb-0 fs-3"></i> {{ $client->npwp}}
                            </div>
                            <div class="d-flex align-items-center fs-2 mb-2 gap-2">
                                <i class="ti ti-mail mb-0 fs-3"></i> {{ $client->email}}
                            </div>
                            <div class="d-flex align-items-center fs-2 mb-2 gap-2">
                                <i class="ti ti-phone mb-0 fs-3"></i> {{ $client->phone}}
                            </div>
                            <div class="d-flex align-items-center fs-2 mb-2 gap-2">
                                <i class="ti ti-phone-check mb-0 fs-3"></i> {{ $client->fax}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-none" id="edit-principal">
                    <div class="col-md-4 h-100 mb-3">
                        @if($client->image)
                        <img src="{{ '/storage/'.$client->image }}" alt="principal Image Preview" class="rounded-4 shadow w-100"
                            style="">
                        @else
                        <div id="placeholder-image"
                            class="d-flex p-5 text-center align-items-center justify-content-center rounded-5 border-2 border-dashed"
                            style="aspect-ratio:1/1">
                            <div>
                                <div class="fs-4">If Image Selected, it will show (Preview)</div>
                            </div>
                        </div>
                        @endif
                        <img src="" id="preview-image" alt="principal Image Preview"
                            class="d-none rounded-4 shadow w-100" style="">
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="px-4 py-3 border-bottom">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="card-title fw-semibold mb-3">Perbarui Client</h5>
                                    <div class="ms-auto">
                                        <button type="button" class="btn-principal-close btn btn-sm btn-danger d-flex align-items-center gap-2"><i class="ti ti-x"></i> Batal Edit</button>
                                    </div>
                                </div>
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
                                <form action="{{ route('client.update', $client->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Image Client / Logo</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-photo fs-6"></i></span>
                                            <input type="file" name="image" class="form-control ps-2">
                                        </div>
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nama</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-text-caption fs-6"></i></span>
                                            <input type="text" name="name" value="{{ old('name', $client->name) }}"
                                                class="form-control ps-2" placeholder="Name Client">
                                        </div>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">NPWP</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-text-caption fs-6"></i></span>
                                            <input type="number" name="npwp" value="{{ old('npwp') }}"
                                                class="form-control ps-2" placeholder="NPWP Client">
                                        </div>
                                        @error('npwp')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-mail fs-6"></i></span>
                                            <input type="text" name="email" value="{{ old('email', $client->email) }}"
                                                class="form-control ps-2" placeholder="your@mail.com">
                                        </div>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">No. Telp / Whatsapp</label>
                                                <div class="input-group">
                                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                                            class="ti ti-phone fs-6"></i></span>
                                                    <input type="number" name="phone" value="{{ old('phone', $client->phone) }}"
                                                        class="form-control ps-2" placeholder="No. Telp / Whatsapp">
                                                </div>
                                                @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">No. Faksimili</label>
                                                <div class="input-group">
                                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                                            class="ti ti-phone fs-6"></i></span>
                                                    <input type="number" name="fax" value="{{ old('fax', $client->fax) }}"
                                                        class="form-control ps-2" placeholder="No. Faksimili">
                                                </div>
                                                @error('fax')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Deskripsi</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-align-justified fs-6"></i></span>
                                            <textarea class="form-control ps-2" name="description" id="description" cols="20" rows="5"
                                                placeholder="Description about this Client">{{ old('description', $client->description) }}</textarea>
                                        </div>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="p-4 bg-primary-subtle rounded-4 mb-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <h5>Alamat Utama Client</h5>
                                                <hr style="border-color: #cecece">
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="text" class="form-label">Kota / Kabupaten</label>
                                                    <div class="input-group mb-2">
                                                        <span class="input-group-text px-6" id="basic-addon1"><i
                                                                class="ti ti-package fs-6"></i></span>
                                                        <div class="flex-grow-1">
                                                            <select name="city" id="city_primary" class="select2-normal form-select">
                                                                <option value="">-- Pilih Kota / Kabupaten --</option>
                                                                @foreach ($cities as $city)
                                                                    <option value="{{$city->city_name}}" {{ $city->city_name == old('city', $client->city) || $city->city_name == old('city', strtoupper($client->city)) ? 'selected' : '' }}>
                                                                        {{ $city->city_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Kode Pos (Postal Code)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text px-6" id="basic-addon1"><i
                                                                class="ti ti-text-caption fs-6"></i></span>
                                                        <input type="text" name="postal_code" value="{{ old('postal_code', $client->postal_code) }}"
                                                            class="form-control ps-2 bg-white" placeholder="Kode Pos Alamat utama">
                                                    </div>
                                                    @error('postal_code')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <label class="form-label fw-semibold">Alamat</label>
                                            <div class="input-group">
                                                <span class="input-group-text px-6" id="basic-addon1"><i
                                                        class="ti ti-map-2 fs-6"></i></span>
                                                <textarea class="form-control bg-white ps-2" name="address" id="address" cols="20" rows="5"
                                                    placeholder="Alamat utama Client">{{ old('address', $client->address) }}</textarea>
                                            </div>
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary next-step">Perbarui Data Client</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Step 2 -->
            <div class="step-content" data-step="2" style="display: none;">
                <div class="d-flex mb-3 align-items-center gap-3">
                    <i class="ti ti-map-2 fs-8"></i>
                    <h5 class="mb-0">Alamat Proyek / Lainnya</h5>
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle" style="aspect-ratio:1/1; width:2.5em; height:2.5em">{{ count($client->addresses) }}</div>
                    @if($client->addresses->count() > 0)
                    <div class="ms-auto">
                        <button class="btn btn-primary btn-al-primary"
                            data-bs-toggle="modal" data-bs-target="#addAddressOtherModal"
                        >Tambah</button>
                    </div>
                    @endif
                </div>
                <div class="row">
                    @foreach ($client->addresses as $address)
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ti ti-map-pin fs-7 me-2"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{$address->name}}</div>
                                        <div class="fw-normal fs-2" style="white-space:normal; font-size:13px; ">{{ $address->address }}</div>    
                                        <div class="text-primary fs-2">{{ $address->city }}. {{$address->postal_code}}</div>
                                    </div>
                                    <div>
                                        <div class="dropdown dropstart">
                                            <a href="#" class="text-muted" id="dropdownMenuButton"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots fs-5"></i>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a href="#" class="dropdown-item text-secondary d-flex align-items-center gap-3 editAddressBtn" 
                                                    data-id="{{$address->id}}" 
                                                    data-name="{{$address->name}}" 
                                                    data-city="{{$address->city}}" 
                                                    data-postal-code="{{$address->postal_code}}" 
                                                    data-address="{{$address->address}}" 
                                                    data-url="{{ route('client.address.update', ['id' => $client->id, 'address_id' => $address->id]) }}" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editAddressModal">
                                                        <i class="fs-4 ti ti-edit"></i> Edit Alamat
                                                </a>
                                                <li>
                                                    <button type="button" class="dropdown-item d-flex align-items-center gap-3 delAddressBtn"
                                                        data-bs-toggle="modal" data-bs-target="#deleteAddressModal"
                                                        data-id="{{$address->id}}" 
                                                        data-name="{{$address->name}}" 
                                                        data-url="{{ route('client.address.destroy', ['id' => $client->id, 'address_id' => $address->id]) }}" 
                                                        ><i
                                                            class="fs-4 ti ti-trash"></i>Delete</button>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($client->addresses->count() > 0)
                <!-- Add Address modal -->
                <div class="modal fade " id="addAddressOtherModal" tabindex="-1" aria-labelledby="vertical-center-modal"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h4 class="modal-title" id="myLargeModalLabel">
                                    Tambah Alamat
                                </h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('client.address.store', $client->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nama Alamat (Unik)</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-text-caption fs-6"></i></span>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                class="form-control ps-2" placeholder="Nama / Penanda Alamat">
                                        </div>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="text" class="form-label">Kota / Kabupaten</label>
                                                <div class="input-group flex-nowrap mb-2">
                                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                                            class="ti ti-package fs-6"></i></span>
                                                    <div class="flex-grow-1">
                                                        <select name="city" id="city_added_modal" class="select2 form-select">
                                                            <option value="">-- Pilih Kota / Kabupaten --</option>
                                                            @foreach ($cities as $city)
                                                                <option value="{{$city->city_name}}" {{ $city->city_name == old('city') ? 'selected' : '' }}>
                                                                    {{ $city->city_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Kode Pos (Postal Code)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                                            class="ti ti-text-caption fs-6"></i></span>
                                                    <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                                        class="form-control ps-2 bg-white" placeholder="Kode Pos">
                                                </div>
                                                @error('postal_code')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Alamat</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-map-2 fs-6"></i></span>
                                            <textarea class="form-control bg-white ps-2" name="address" id="address" cols="20" rows="5"
                                                placeholder="Alamat Proyek / Lainnya Client">{{ old('address') }}</textarea>
                                        </div>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="d-flex gap-1 align-items-center justify-content-end">
                                        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start"
                                            data-bs-dismiss="modal">
                                            Close
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-al-primary">Tambah Alamat</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <form action="{{route('client.address.store', $client->id)}}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Alamat (Unik)</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-text-caption fs-6"></i></span>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control ps-2" placeholder="Nama / Penanda Alamat">
                                </div>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="text" class="form-label">Kota / Kabupaten</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-package fs-6"></i></span>
                                            <div class="flex-grow-1">
                                                <select name="city" id="city_added" class="select2-normal form-select">
                                                    <option value="">-- Pilih Kota / Kabupaten --</option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{$city->city_name}}" {{ $city->city_name == old('city') ? 'selected' : '' }}>
                                                            {{ $city->city_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Kode Pos (Postal Code)</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-text-caption fs-6"></i></span>
                                            <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                                class="form-control ps-2 bg-white" placeholder="Kode Pos">
                                        </div>
                                        @error('postal_code')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Alamat</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-map-2 fs-6"></i></span>
                                    <textarea class="form-control bg-white ps-2" name="address" id="address" cols="20" rows="5"
                                        placeholder="Alamat Proyek / Lainnya Client">{{ old('address') }}</textarea>
                                </div>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary next-step">Tambah Alamat</button>
                        </div>
                    </div>
                </form>
                @endif

                <!-- Delete Modal -->
                <div id="deleteAddressModal" class="modal fade" tabindex="-1"
                    aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                        <div class="modal-content p-3 modal-filled bg-danger">
                            <div class="modal-header modal-colored-header text-white">
                                <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                    Yakin ingin menghapus Alamat <span class="address_name"></span> ?
                                </h4>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="width: fit-content; white-space:normal">
                                <h5 class="mt-0 text-white">Alamat Client  <span class="address_name"></span> akan dihapus</h5>
                                <p class="text-white">Segala data yang berkaitan dengan alamat tersebut juga akan dihapus secara permanen.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <form action="" id="deleteAddressForm" method="POST">
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

                <!-- Modal Edit Address (Hanya Satu) -->
                <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h4 class="modal-title">Edit Alamat</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editAddressForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nama Alamat (Unik)</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6"><i class="ti ti-text-caption fs-6"></i></span>
                                            <input type="text" name="name" id="editName" class="form-control ps-2" placeholder="Nama / Penanda Alamat">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Kota / Kabupaten</label>
                                                <div class="input-group flex-nowrap mb-2">
                                                    <span class="input-group-text px-6"><i class="ti ti-package fs-6"></i></span>
                                                    <div class="flex-grow-1">
                                                        <select name="city" id="editCity" class="select2 form-select">
                                                            <option value="">-- Pilih Kota / Kabupaten --</option>
                                                            @foreach ($cities as $city)
                                                                <option value="{{ $city->city_name }}">{{ $city->city_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Kode Pos</label>
                                                <div class="input-group">
                                                    <span class="input-group-text px-6"><i class="ti ti-text-caption fs-6"></i></span>
                                                    <input type="text" name="postal_code" id="editPostalCode" class="form-control ps-2 bg-white" placeholder="Kode Pos">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Alamat</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6"><i class="ti ti-map-2 fs-6"></i></span>
                                            <textarea class="form-control bg-white ps-2" name="address" id="editAddress" cols="20" rows="5" placeholder="Alamat"></textarea>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-1 align-items-center justify-content-end">
                                        <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Form Step 3 -->
            <div class="step-content" data-step="3" style="display: none;">
                <div class="d-flex mb-3 align-items-center gap-3">
                    <i class="ti ti-user-circle fs-9"></i>
                    <h5 class="mb-0">PIC / Sales</h5>
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white p-2 rounded-circle" style="aspect-ratio:1/1; width:2.5em; height:2.5em">{{ count($client->pics) }}</div>
                    <div class="ms-auto">
                        <button id="addPICSales" class="d-inline-flex align-items-center gap-2 btn btn-primary btn-al-primary"
                        ><i class="ti ti-plus"></i> Tambah</button>
                        <button id="cancelPICSales" class="d-inline-flex d-none align-items-center gap-2 btn btn-danger"
                        ><i class="ti ti-x"></i> Batal</button>
                    </div>
                </div>

                <div class="row" id="showPICSales">
                    @forelse ($client->pics as $pic)
                    <div class="col-md-4 col-lg-3 mb-2">
                        <div class="card h-100">
                            <div class="card-body p-4 h-100 d-flex flex-column pb-0 position-relative">
                                <img src="{{ $pic->image ? '/storage/'.$pic->image : '/assets/images/profile/user-4.jpg'}}" alt="Image PIC / Sales {{$pic->name}}" class="img-fluid rounded-circle bg-light border border-primary mb-4"
                                    width="80" height="80" style="aspect-ratio:1/1; object-fit:cover;">
                                <h5 class="fw-semibold fs-4">{{$pic->name}}</h5>
                                <div class="fs-2 text-muted line-clamp mb-2">{{$pic->description}}</div>
                                <div class="mb-2 d-flex align-items-center gap-2"><i class="ti ti-mail"></i>{{$pic->email ?? 'email belum diset'}}</div>
                                <div class="d-flex align-items-center gap-2"><i class="ti ti-phone"></i>{{$pic->phone ?? 'nomor belum diset'}}</div>
                                <div class="d-flex align-items-center gap-4 mt-auto mb-4">
                                    <div class="d-flex align-items-center">
                                    @forelse ($pic->sales as $sales)
                                        <a href="#" title="{{$sales->name}}">
                                            <img src="{{$sales->image ? '/storage/'.$sales->image : '/assets/images/profile/user-4.jpg'}}"
                                            class="rounded-circle me-n2 card-hover border border-white bg-light" width="32" height="32" style="aspect-ratio:1/1;object-fit:cover">
                                        </a>
                                    @empty
                                        <a href="#" title="None">
                                            <img src="https://placehold.co/300?text=?"
                                            class="rounded-circle me-n2 card-hover border border-white bg-light" width="32" height="32" style="aspect-ratio:1/1;object-fit:cover">
                                        </a>
                                    @endforelse
                                    </div>
                                    <p class="mb-0">{{$pic->sales->count()}} Sales</p>
                                </div>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <div class="dropdown dropstart">
                                        <a href="#" class="text-muted" id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href="#" class="dropdown-item text-secondary d-flex align-items-center gap-3 editPICSalesBtn" 
                                                data-id="{{$pic->id}}" 
                                                data-image="{{$pic->image}}" 
                                                data-name="{{$pic->name}}" 
                                                data-phone="{{$pic->phone}}" 
                                                data-description="{{$pic->description}}" 
                                                data-email="{{$pic->email}}" 
                                                data-parent="{{$pic->parent_pic_id}}" 
                                                data-url="{{ route('client.pic.update', ['id' => $client->id, 'pic_id' => $pic->id]) }}" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editPICSalesModal">
                                                    <i class="fs-4 ti ti-edit"></i> Edit PIC / Sales
                                            </a>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3 delPICSalesBtn"
                                                    data-bs-toggle="modal" data-bs-target="#deletePICSalesModal"
                                                    data-id="{{$pic->id}}" 
                                                    data-name="{{$pic->name}}" 
                                                    data-url="{{ route('client.pic.destroy', ['id' => $client->id, 'pic_id' => $pic->id]) }}" 
                                                    ><i
                                                        class="fs-4 ti ti-trash"></i>Delete</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-5 text-center border rounded-4 border-2 text-muted border-dashed">
                        Belum ada PIC / Sales
                    </div>
                    @endforelse

                    <!-- Delete Modal -->
                    <div id="deletePICSalesModal" class="modal fade" tabindex="-1"
                        aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                            <div class="modal-content p-3 modal-filled bg-danger">
                                <div class="modal-header modal-colored-header text-white">
                                    <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                        Yakin ingin menghapus PIC atau Sales Client <span class="del-pic-name"></span> ?
                                    </h4>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="width: fit-content; white-space:normal">
                                    <h5 class="mt-0 text-white">PIC atau Sales Client <span class="del-pic-name"></span> akan dihapus</h5>
                                    <p class="text-white">Segala data yang berkaitan dengan alamat tersebut juga akan dihapus secara permanen.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <form action="" method="POST" id="delPICSalesForm">
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

                    <!-- Modal Edit PIC / Sales (Hanya Satu) -->
                    <div class="modal fade" id="editPICSalesModal" tabindex="-1" aria-labelledby="editAddressLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header d-flex align-items-center">
                                    <h4 class="modal-title">Edit PIC / Sales : <span class="pic-name"></span></h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row" id="">
                                        <div class="col-md-4 h-100 mb-3">
                                            <img src="" id="existing-image" alt="PIC / Sales Existing Image" class="d-none d-block rounded-circle shadow w-100"
                                                style="aspect-ratio:1/1; object-fit:cover;;max-width:25em; margin: 0px auto;">
                                            <div id="placeholder-image"
                                                class="d-flex p-5 text-center rounded-circle align-items-center justify-content-center border-2 border-dashed"
                                                style="aspect-ratio:1/1;max-width:25em; margin: 0px auto;">
                                                <div>
                                                    <div class="fs-4">If Image Selected, it will show (Preview)</div>
                                                </div>
                                            </div>
                                            <img src="" id="preview-image" alt="PIC / Sales Image Preview" class="d-none d-block rounded-circle shadow w-100"
                                                style="aspect-ratio:1/1; object-fit:cover;;max-width:25em; margin: 0px auto;">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card">
                                                <div class="px-4 py-3 border-bottom">
                                                    <h5 class="card-title fw-semibold mb-0">Perbarui PIC / Sales</h5>
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
                                                    <form id="editPICSalesForm" action="{{ route('client.pic.store', ['id' => $client->id]) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="mb-4">
                                                            <label class="form-label fw-semibold">Image PIC / Sales</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text px-6" id="basic-addon1"><i
                                                                        class="ti ti-photo fs-6"></i></span>
                                                                <input type="file" name="image" class="form-control ps-2">
                                                            </div>
                                                            @error('image')
                                                                <span class="invalid-feedback" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="mb-4">
                                                                    <label class="form-label fw-semibold">Name</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text px-6" id="basic-addon1"><i
                                                                                class="ti ti-user-circle fs-6"></i></span>
                                                                        <input type="text" name="name" class="form-control ps-2"
                                                                            placeholder="Name Surname" value="{{old('name')}}">
                                                                    </div>
                                                                    @error('name')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            {{ $message }}
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-4">
                                                                    <label class="form-label fw-semibold">Email</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text px-6" id="basic-addon1"><i
                                                                                class="ti ti-mail fs-6"></i></span>
                                                                        <input type="email" name="email" class="form-control ps-2"
                                                                            placeholder="user@mail.com" value="{{old('email')}}">
                                                                    </div>
                                                                    @error('email')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            {{ $message }}
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-4">
                                                                    <label class="form-label fw-semibold">Phone / Whatsapp</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text px-6" id="basic-addon1"><i
                                                                                class="ti ti-phone fs-6"></i></span>
                                                                        <input type="number" name="phone" class="form-control ps-2"
                                                                            placeholder="62xxx" value="{{old('phone')}}">
                                                                    </div>
                                                                    @error('phone')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            {{ $message }}
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="mb-4">
                                                                    <label class="form-label fw-semibold">Deskripsi</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text px-6" id="basic-addon1"><i
                                                                                class="ti ti-text-caption fs-6"></i></span>
                                                                        <textarea class="form-control bg-white ps-2" name="description" id="description" cols="20" rows="5"
                                                                            placeholder="Keterangan untuk PIC / Sales">{{ old('description') }}</textarea>
                                                                    </div>
                                                                    @error('description')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            {{ $message }}
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="mb-4 p-3 bg-primary-subtle rounded-3">
                                                                    <label class="form-label fw-semibold">Apakah Ini Sales ? Jika iya Siapa PIC nya ? (Kosongkan jika bukan sales)</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text px-6" id="basic-addon1"><i
                                                                                class="ti ti-fingerprint fs-6"></i></span>
                                                                        <div class="flex-grow-1">
                                                                            <select name="parent_pic_id" id="edit_pic_sales" class="select2 form-select">
                                                                                <option value="">-- Pilih PIC --</option>
                                                                                @foreach ($pics as $pic)
                                                                                    <option value="{{$pic->id}}" {{$pic->id == old('parent_pic_id') ? 'selected' : ''}} class="text-lowercase">{{$pic->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    @error('parent_pic_id')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            {{ $message }}
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Perbarui PIC / Sales</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row d-none" id="addPICSalesForm">
                    <div class="col-md-4 h-100 mb-3">
                        <div id="placeholder-image"
                            class="d-flex p-5 text-center rounded-circle align-items-center justify-content-center border-2 border-dashed"
                            style="aspect-ratio:1/1;max-width:25em; margin: 0px auto;">
                            <div>
                                <div class="fs-4">If Image Selected, it will show (Preview)</div>
                            </div>
                        </div>
                        <img src="" id="preview-image" alt="PIC / Sales Image Preview" class="d-none d-block rounded-circle shadow w-100"
                            style="aspect-ratio:1/1; object-fit:cover;;max-width:25em; margin: 0px auto;">
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="px-4 py-3 border-bottom">
                                <h5 class="card-title fw-semibold mb-0">Tambah PIC / Sales</h5>
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
                                <form action="{{ route('client.pic.store', ['id' => $client->id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Image PIC / Sales</label>
                                        <div class="input-group">
                                            <span class="input-group-text px-6" id="basic-addon1"><i
                                                    class="ti ti-photo fs-6"></i></span>
                                            <input type="file" name="image" class="form-control ps-2">
                                        </div>
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-4">
                                                <label class="form-label fw-semibold">Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                                            class="ti ti-user-circle fs-6"></i></span>
                                                    <input type="text" name="name" class="form-control ps-2"
                                                        placeholder="Name Surname" value="{{old('name')}}">
                                                </div>
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label class="form-label fw-semibold">Email</label>
                                                <div class="input-group">
                                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                                            class="ti ti-mail fs-6"></i></span>
                                                    <input type="email" name="email" class="form-control ps-2"
                                                        placeholder="user@mail.com" value="{{old('email')}}">
                                                </div>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label class="form-label fw-semibold">Phone / Whatsapp</label>
                                                <div class="input-group">
                                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                                            class="ti ti-phone fs-6"></i></span>
                                                    <input type="number" name="phone" class="form-control ps-2"
                                                        placeholder="62xxx" value="{{old('phone')}}">
                                                </div>
                                                @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-4">
                                                <label class="form-label fw-semibold">Deskripsi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                                            class="ti ti-text-caption fs-6"></i></span>
                                                    <textarea class="form-control bg-white ps-2" name="description" id="description" cols="20" rows="5"
                                                        placeholder="Keterangan untuk PIC / Sales">{{ old('description') }}</textarea>
                                                </div>
                                                @error('description')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-4 p-3 bg-primary-subtle rounded-3">
                                                <label class="form-label fw-semibold">Apakah Ini Sales ? Jika iya Siapa PIC nya ? (Kosongkan jika bukan sales)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                                            class="ti ti-fingerprint fs-6"></i></span>
                                                    <div class="flex-grow-1">
                                                        <select name="parent_pic_id" id="parent_pic_id" class="select2-normal form-select">
                                                            <option value="">-- Pilih PIC --</option>
                                                            @foreach ($pics as $pic)
                                                                <option value="{{$pic->id}}" {{$pic->id == old('parent_pic_id') ? 'selected' : ''}} class="text-lowercase">{{$pic->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @error('parent_pic_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Tambah PIC / Sales</button>
                                </form>
                            </div>
                        </div>
                    </div>
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
            const stepMap = {
                'data-client': 1,
                'alamat-proyek': 2,
                'pic': 3
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

            $('.btn-principal-edit').on('click', function(){
                $('#view-principal').addClass('d-none');
                $('#edit-principal').removeClass('d-none');
            });
            $('.btn-principal-close').on('click', function(){
                $('#view-principal').removeClass('d-none');
                $('#edit-principal').addClass('d-none');
            });

            $('#addPICSales').on('click', function(){
                $(this).addClass('d-none');
                $('#showPICSales').addClass('d-none');
                $('#addPICSalesForm').removeClass('d-none');
                $('#cancelPICSales').removeClass('d-none');
            });
            $('#cancelPICSales').on('click', function(){
                $(this).addClass('d-none');
                $('#addPICSalesForm').addClass('d-none');
                $('#addPICSales').removeClass('d-none');
                $('#showPICSales').removeClass('d-none');
            });

            document.querySelectorAll(".editAddressBtn").forEach(button => {
                button.addEventListener("click", function () {
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

            document.querySelectorAll(".delAddressBtn").forEach(button => {
                button.addEventListener("click", function () {
                    let name = this.getAttribute("data-name");
                    let url = this.getAttribute("data-url");

                    // Isi teks dalam semua elemen dengan class 'address_name'
                    document.querySelectorAll(".address_name").forEach(span => {
                        span.textContent = name; // Atau gunakan .innerText jika perlu
                    });

                    // Ubah action form agar sesuai dengan alamat yang dihapus
                    document.getElementById("deleteAddressForm").setAttribute("action", url);
                });
            });

            // ------------------------------
            // PIC / Sales
            // ------------------------------
            document.querySelectorAll(".editPICSalesBtn").forEach(button => {
                button.addEventListener("click", function () {
                    // Ambil data dari atribut tombol
                    let id = this.getAttribute("data-id");
                    let image = this.getAttribute("data-image");
                    let name = this.getAttribute("data-name");
                    let email = this.getAttribute("data-email");
                    let phone = this.getAttribute("data-phone");
                    let parent = this.getAttribute("data-parent");
                    let description = this.getAttribute("data-description");
                    let url = this.getAttribute("data-url");

                    // Isi data ke dalam modal
                    let modalParent = $('#editPICSalesModal');
                    modalParent.find('span.pic-name').text(name);
                    modalParent.find('input[name="name"]').val(name);
                    modalParent.find('input[name="email"]').val(email);
                    modalParent.find('input[name="phone"]').val(phone);
                    modalParent.find('textarea[name="description"]').val(description);

                    // **Set nilai Select2 dan trigger change event**
                    modalParent.find("#edit_pic_sales").val(parent).trigger("change");
                    modalParent.find('#edit_pic_sales option[value="' + id + '"]').remove();

                    if(image){
                        modalParent.find('img#existing-image').attr('src', '/storage/'+image);
                        modalParent.find('img#existing-image').removeClass('d-none');
                        modalParent.find('#placeholder-image').addClass('d-none');
                    }

                    // Ubah action form agar sesuai dengan alamat yang diedit
                    document.getElementById("editPICSalesForm").setAttribute("action", url);
                });
            });

            document.querySelectorAll(".delPICSalesBtn").forEach(button => {
                button.addEventListener("click", function () {
                    let id = this.getAttribute("data-id");
                    let name = this.getAttribute("data-name");
                    let url = this.getAttribute("data-url");

                    // Isi teks dalam semua elemen dengan class 'del-pic-name'
                    document.querySelectorAll(".del-pic-name").forEach(span => {
                        span.textContent = name; // Atau gunakan .innerText jika perlu
                    });

                    // Ubah action form agar sesuai dengan alamat yang dihapus
                    document.getElementById("delPICSalesForm").setAttribute("action", url);
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
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var redirectHash = "{{ session('redirect_hash') }}";
            if (redirectHash) {
                window.location.hash = redirectHash;
            }

            const fileInput = document.querySelector('input[type="file"][name="image"]');
            const placeholder = document.getElementById('placeholder-image');
            const previewImage = document.getElementById('preview-image');

            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.src = e.target.result; // Set preview image source
                        previewImage.classList.remove('d-none'); // Show the preview image
                        placeholder.classList.add('d-none'); // Hide the placeholder
                    };

                    reader.readAsDataURL(file);
                } else {
                    // Reset if no file or file is not an image
                    previewImage.src = '';
                    previewImage.classList.add('d-none');
                    placeholder.classList.remove('d-none');
                }
            });

            document.addEventListener('change', function(event) {
                // Cek apakah event berasal dari input file yang memiliki atribut name="image"
                if (event.target.matches('input[type="file"][name="image"]')) {
                    const fileInput = event.target;
                    const formContainer = fileInput.closest('.row'); // Mencari form terkait dalam satu grup

                    if (!formContainer) return;

                    const placeholder = formContainer.querySelector('#placeholder-image');
                    const previewImage = formContainer.querySelector('#preview-image');

                    const file = fileInput.files[0];

                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewImage.classList.remove('d-none'); // Tampilkan preview
                            placeholder.classList.add('d-none'); // Sembunyikan placeholder
                            $('#existing-image').addClass('d-none'); // Sembunyikan placeholder
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // Reset tampilan jika file tidak valid
                        previewImage.src = '';
                        previewImage.classList.add('d-none');
                        placeholder.classList.remove('d-none');
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
        });
    </script>
@endsection
