@extends('layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/libs/select2/dist/css/select2.min.css">
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-3">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Edit Aset Tetap</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('asset.index') }}">Aset Tetap</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Edit Aset Tetap : {{$asset->name}}</li>
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
            <div id="placeholder-image"
                class="d-flex {{!$asset->image ? '' : 'd-none'}} p-5 text-center align-items-center justify-content-center rounded-5 border-2 border-dashed"
                style="aspect-ratio:1/1">
                <div>
                    <div class="fs-4">If Image Selected, it will show (Preview)</div>
                </div>
            </div>
            <img src="" id="preview-image" alt="Asset Image Preview" class="d-none rounded-4 shadow w-100"
                style="">
            <img src="/storage/{{$asset->image}}" id="own-image" alt="Asset {{$asset->name}} Image Preview" class="{{$asset->image ? 'd-block' : 'd-none'}} rounded-4 shadow w-100"
                style="">
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="px-4 py-3 border-bottom">
                    <h5 class="card-title fw-semibold mb-0">Edit Aset Tetap</h5>
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
                    <form action="{{ route('asset.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            @php
                            $parent = \App\Models\Account::whereHas('master', fn($q) => $q->where('type', 'assets'))
                                                                ->where('parent_account_id', null)
                                                                ->where('code', config('al.coa')['aset_tetap'])
                                                                ->first();
                            $assetAccounts = \App\Models\Account::whereHas('master', fn($q) => $q->where('type', 'assets'))
                                                                        ->where('parent_account_id', $parent->id)
                                                                        ->get()->sortBy('full_code');
                            @endphp
                            <!-- Akun Asset -->
                            <label for="text" class="form-label">Akun Asset pada COA</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-package fs-6"></i></span>
                                <div style="flex-grow:1">
                                    <select name="account_id" id="account_id" class="select2-normal form-select">
                                        <option value="">-- Pilih Akun Asset Tetap --</option>
                                        
                                        @foreach ($assetAccounts as $account)

                                            <option value="{{$account->id}}" {{ $account->id == old('account_id', $asset->account_id) ? 'selected' : '' }}>
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
                            <label class="form-label fw-semibold">Image Asset</label>
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
                            <label class="form-label fw-semibold">Nama Asset</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-credit-card fs-6"></i></span>
                                <input type="text" name="name" value="{{old('name', $asset->name)}}" class="form-control ps-2" placeholder="Nama Asset">
                            </div>
                            @error('name')
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
                                <textarea class="form-control ps-2" name="description" id="description" cols="20" rows="5"
                                    placeholder="description Aset Tetap">{{old('description', $asset->description)}}</textarea>
                            </div>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="p-3 rounded-3 bg-primary-subtle mb-3">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tanggal Pembelian</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-calendar-event fs-6"></i></span>
                                    <input type="date" name="date" value="{{old('date', $asset->date)}}" class="form-control bg-white ps-2" placeholder="Tanggal Pembelian Asset">
                                </div>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="">
                                <label class="form-label fw-semibold">Harga Beli</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-currency-dollar fs-6"></i></span>
                                    <input type="text" name="purchase_price" value="{{formatRupiah(old('purchase_price', $asset->purchase_price))}}" class="input-rupiah form-control bg-white ps-2" placeholder="Harga Beli">
                                </div>
                                @error('purchase_price')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="is_appreciating" type="checkbox" value="1" id="is_appreciating" {{old('is_appreciating', $asset->is_appreciating) ? 'checked' : ''}} />
                                <label class="form-check-label" for="is_appreciating">Nilai Aset Tetap ini terus bertambah</label>
                            </div>
                        </div>
                        <div class="p-3 rounded-3 bg-primary-subtle mb-3 {{old('is_appreciating', $asset->is_appreciating) ? '' : 'd-none'}}" id="appreciate-box">
                            <label class="form-label fw-semibold">Presepsi Pertambahan nilai (%)</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-percentage fs-6"></i></span>
                                <input type="text" name="appreciation_rate" value="{{old('appreciation_rate', $asset->appreciation_rate)}}" class="form-control bg-white ps-2" placeholder="Persentase kenaikan setiap tahun">
                            </div>
                            @error('appreciation_rate')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="p-3 mb-3 rounded-3 bg-primary-subtle {{old('is_appreciating', $asset->is_appreciating) ? 'd-none' : ''}}" id="depreciate-box">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Masa Manfaat (Usefull Life) dalam tahun</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-clock fs-6"></i></span>
                                    <input type="number" name="usefull_life" value="{{old('usefull_life', $asset->useful_life)}}" class="form-control bg-white ps-2" placeholder="Berapa tahun masa guna pakai aset ini ?">
                                </div>
                                @error('usefull_life')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold">Harga Residu</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-currency-dollar fs-6"></i></span>
                                    <input type="text" name="residu_price" value="{{formatRupiah(old('residu_price', $asset->residu_price))}}" class="input-rupiah form-control bg-white ps-2" placeholder="Perkiraan harga terakhir setelah masa guna habis">
                                </div>
                                @error('residu_price')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Edit Aset Tetap
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
            const fileInput = document.querySelector('input[type="file"][name="image"]');
            const placeholder = document.getElementById('placeholder-image');
            const previewImage = document.getElementById('preview-image');
            const ownImage = document.getElementById('own-image');

            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.src = e.target.result; // Set preview image source
                        previewImage.classList.remove('d-none'); // Show the preview image
                        placeholder.classList.add('d-none'); // Hide the placeholder
                        ownImage.classList.add('d-none'); // Hide the placeholder
                    };

                    reader.readAsDataURL(file);
                } else {
                    // Reset if no file or file is not an image
                    previewImage.src = ownImage.src;
                }
            });
            $('input[name="is_appreciating"]').on('change', function(){
                let check = $(this).is(':checked');
                if(check) {
                    $('#appreciate-box').removeClass('d-none');
                    $('#depreciate-box').addClass('d-none');
                }else{
                    $('#appreciate-box').addClass('d-none');
                    $('#depreciate-box').removeClass('d-none');
                }
            })
        });
    </script>
@endsection
