@extends('layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/libs/select2/dist/css/select2.min.css">
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-3">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Edit Rekening Bank {{$bank->bank_name}}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('bank.index') }}">Rekening Bank</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Edit {{$bank->bank_name}}</li>
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

    <div class="row">
        <div class="col-md-4 h-100 mb-3">
            <img src="/storage/{{$bank->logo}}" id="placeholder-image" alt="Rekening Bank Image Data" class="rounded-4 shadow w-100"
                style="">
            <img src="" id="preview-image" alt="Rekening Bank Image Preview" class="d-none rounded-4 shadow w-100"
                style="">
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="px-4 py-3 border-bottom">
                    <h5 class="card-title fw-semibold mb-0">Edit Rekening Bank</h5>
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
                    <form action="{{ route('bank.update', $bank->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Bank</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-text-caption fs-6"></i></span>
                                <input type="text" name="nama_bank" value="{{old('nama_bank', $bank->bank_name)}}" class="form-control ps-2" placeholder="nama_bank Rekening Bank">
                            </div>
                            @error('nama_bank')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            @php
                            $parent = \App\Models\Account::whereHas('master', fn($q) => $q->where('type', 'assets'))
                                                                ->where('parent_account_id', null)
                                                                ->where('code', config('al.coa')['bank'])
                                                                ->first();
                            $bankAccounts = \App\Models\Account::whereHas('master', fn($q) => $q->where('type', 'assets'))
                                                                        ->where('parent_account_id', $parent->id)
                                                                        ->get()->sortBy('full_code');
                            @endphp
                            <!-- Akun Bank -->
                            <label for="text" class="form-label">Akun Bank pada COA</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-package fs-6"></i></span>
                                <div style="flex-grow:1">
                                    <select name="account_id" id="account_id" class="select2-normal form-select">
                                        <option value="">-- Pilih Akun Bank --</option>
                                        
                                        @foreach ($bankAccounts as $account)

                                            <option value="{{$account->id}}" {{ $account->id == old('account_id', $bank->account_id) ? 'selected' : '' }}>
                                            {{ $account->full_code.' - '.$account->account_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                Tidak ada yang cocok ? 
                                <a href="{{route('account.index')}}" target="_blank" class="btn btn-sm text-primary bg-primary-subtle"
                                    >Buat Baru
                                </a>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Logo Rekening Bank</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-photo fs-6"></i></span>
                                <input type="file" name="logo" class="form-control ps-2">
                            </div>
                            @error('logo')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Rekening</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-credit-card fs-6"></i></span>
                                <input type="text" name="nama_rekening" value="{{old('nama_rekening', $bank->account_name)}}" class="form-control ps-2" placeholder="Nama Rekening">
                            </div>
                            @error('nama_rekening')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">No. Rekening</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-credit-card fs-6"></i></span>
                                <input type="number" name="no_rekening" value="{{old('no_rekening', $bank->account_number)}}" class="form-control ps-2" placeholder="No. Rekening">
                            </div>
                            @error('no_rekening')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-align-justified fs-6"></i></span>
                                <textarea class="form-control ps-2" name="keterangan" id="keterangan" cols="20" rows="5"
                                    placeholder="Keterangan Rekening Bank">{{old('keterangan', $bank->description)}}</textarea>
                            </div>
                            @error('keterangan')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Perbarui Rekening Bank
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{env('APP_URL')}}/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="{{env('APP_URL')}}/assets/libs/select2/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').each(function () {
                $(this).select2({
                    dropdownParent: modal // Pasang dropdown di dalam modal
                });
            });
            $('.select2-normal').select2();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.querySelector('input[type="file"][name="logo"]');
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
        });
    </script>
@endsection
