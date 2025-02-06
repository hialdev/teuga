@extends('layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/libs/select2/dist/css/select2.min.css">
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Tambah Permintaan Client</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('logistic.index') }}">Client</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Tambah Permintaan Client</li>
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
        <div class="row">
            <div class="col-md-4 h-100 mb-3">
                <div id="placeholder-image"
                    class="d-flex p-5 text-center align-items-center justify-content-center rounded-5 border-2 border-dashed"
                    style="aspect-ratio:1/1">
                    <div>
                        <div class="fs-4">If Image / File Selected, it will show (Preview)</div>
                    </div>
                </div>
                <img src="" id="preview-image" alt="Bahan Baku Image Preview" class="d-none rounded-4 shadow w-100"
                    style="">
                <div id="preview-file" class="d-none">
                    <a href="" id="preview-file-link" class="d-flex align-items-center gap-2 p-2 rounded-2 border border-al-primary" target="_blank">
                        <i class="ti ti-file fs-6"></i>
                        <div id="preview-file-text" class="fs-2 line-clamp line-clamp-2">File Name</div>
                    </a>
                    <embed id="preview-file-embed" src="" class="rounded-4 overflow-hidden mt-3" width="100%" height="600px" type="application/pdf">
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="px-4 py-3 border-bottom">
                        <h5 class="card-title fw-semibold mb-0">Permintaan Client</h5>
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
                        <form action="{{ route('request-order.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Tanggal Permintaan</label>
                                <div class="input-group">
                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                            class="ti ti-calendar-event fs-6"></i></span>
                                    <input type="date" name="date" class="form-control ps-2" value="{{old('date', \Carbon\Carbon::parse(now())->format('Y-m-d'))}}">
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
                                        <select name="client_id" id="client_id" class="select2-normal form-select">
                                            <option value="">-- Pilih Client --</option>
                                            @foreach ($clients as $client)
                                                <option value="{{$client->id}}" {{$client->id == old('client_id') ? 'selected' : ''}}>{{$client->name}}</option>
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
                                        <select name="client_pic_id" id="client_pic_id" class="select2-normal form-select">
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
                                <label class="form-label fw-semibold">Lampirkan Foto / File Permintaan Client</label>
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
                                    <input type="text" name="no_refrence" value="{{ old('no_refrence') }}"
                                        class="form-control ps-2" placeholder="Nomor pada surat PO (Permintaan) yang diberikan Client">
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
                                        placeholder="Deskripsi untuk Permintaan Client">{{old('description')}}</textarea>
                                </div>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Simpan dan Lanjut ke Produk Permintaan
                            </button>
                        </form>
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
                'data': 1,
                'produk': 2,
                'lampiran': 3
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

            $('#client_id').on('change', async function () {
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
                            reader.onload = function (e) {
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
        });
    </script>
@endsection
