@extends('layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/libs/select2/dist/css/select2.min.css">
@endsection

@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Chart Of Accounts</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola Chart Of Accounts</li>
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

    <div class="mb-3 d-flex align-items-center gap-2 justify-content-between">
        <h1>Chart Of Accounts</h1>
        <div style="aspect-ratio:1/1; width:3em; height:3em"
            class="bg-primary text-white d-flex align-items-center justify-content-center rounded-5 me-auto">
            {{ count($accounts) }}</div>
        <button class="btn btn-primary btn-al-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">Tambah</button>
        @include('coa.addmodal', ['id' => 'addAccountModal'])
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="mb-4">
                <div class="row align-items-end flex-wrap">
                    <div class="col-md-4 mb-2">
                        <label for="search" class="form-label">Filter Kata</label>
                        <input type="text" class="form-control" placeholder="Cari Chart Of Accounts" name="search"
                            value="{{ $filter->q ?? '' }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="field" class="form-label">Urutkan Berdasarkan</label>
                        <select name="field" id="field" class="form-select">
                            <option value="full_code" {{ $filter->field == 'full_code' ? 'selected' : '' }}>Full Code</option>
                            @foreach (getModelAttributes('Account', ['master_account_id', 'parent_account_id']) as $atr)
                                <option value="{{ $atr }}" {{ $filter->field == $atr ? 'selected' : '' }}>
                                    {{ toPascalCase($atr) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="order" class="form-label">Dengan urutan</label>
                        <select name="order" id="order" class="form-select">
                            <option value="newest" {{ $filter->order == 'desc' ? 'selected' : '' }}>Terbaru / Terbesar
                            </option>
                            <option value="oldest" {{ $filter->order == 'asc' ? 'selected' : '' }}>Terlama / Terkecil
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="d-flex align-items-center gap-1">
                            <button type="submit" class="btn btn-primary w-100" style="white-space: nowrap">Apply</button>
                            <a href="{{ url()->current() }}" class="btn btn-secondary" style="white-space: nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2v2a8 8 0 1 0 4.5 1.385V8h-2V2h6v2H18a9.99 9.99 0 0 1 4 8" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table border text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Kode Lengkap Account</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Kode Single</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Account</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Balance</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Timestamp</h6>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $account)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($account->parent)
                                            <div class="bg-primary d-inline-block" style="width:2em; height:2px"></div>
                                        @endif
                                        <div>
                                            <div class="fw-normal fs-1 mb-1 text-muted" style="white-space:normal;">Kode Lengkap Akun</div>
                                            <div class="d-flex align-items-center gap-1">
                                                <h6 class="fw-semibold mb-1 fs-2 badge bg-{{$account->parent ? 'light text-dark' : 'primary text-white'}} " style="">{{$account->full_code}}</h6>
                                                <h6 class="fw-semibold mb-1 fs-2 badge bg-secondary text-white" style="">{{$account->master->type}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 mb-1 text-muted" style="white-space:normal;">Kode Single</div>
                                        <h6 class="fw-semibold mb-1 fs-2 badge bg-primary text-white" style="">{{$account->code}}</h6>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Account
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style=""><i class="ti ti-lock me-1 {{!$account->is_logical ? 'd-none' : ''}}"></i>{{ $account->account_name }}</h6>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Balance
                                        </div>
                                        <h6 class="fw-semibold text-primary mb-1" style="">{{ formatRupiah($account->balance) }}</h6>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-start gap-2">
                                        <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">Updated
                                            at
                                            : {{ $account->updated_at }}</div>
                                        <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">Created
                                            at
                                            : {{ $account->created_at }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown dropstart">
                                        <a href="#" class="text-muted" id="dropdownMenuButton"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal-{{ $account->id }}"><i
                                                        class="fs-4 ti ti-pencil"></i>Edit COA</button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal-{{ $account->id }}"><i
                                                        class="fs-4 ti ti-trash"></i>Delete</button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal-{{ $account->id }}" tabindex="-1"
                                        aria-labelledby="vertical-center-modal" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header d-flex align-items-center">
                                                    <h4 class="modal-title" id="myLargeModalLabel">
                                                        Perbarui Chart Of Accounts {{ $account->code }}
                                                    </h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('account.update', $account->id) }}" method="POST">
                                                        @csrf
                                                        
                                                        <label for="text" class="form-label">Account</label>
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text px-6" id="basic-addon1"><i class="ti ti-calculator fs-6"></i></span>
                                                            <div style="flex-grow:1">
                                                                <select name="master_account_id" id="master_account_id" class="select2 form-select">
                                                                    @foreach ($masters as $master)
                                                                    <option value="{{$master->id}}" {{$master->id == old('master_account_id', $account->master_account_id) ? 'selected' : ''}}>{{ $master->code.' - '.ucfirst($master->type) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <label for="account_name" class="form-label">Nama Account</label>
                                                        <input type="text" name="account_name" class="form-control mb-3" value="{{ old('account_name', $account->account_name) }}"
                                                        placeholder="{{$account->is_logical ? 'Akun diperuntukan sebagai '.$account->account_name : 'Nama untuk Account'}}">
                                                        
                                                        <div class="mb-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" name="is_sub" type="checkbox" value="1" id="is_sub"/>
                                                                <label class="form-check-label" for="is_sub">Akun ini adalah Sub Akun</label>
                                                            </div>
                                                        </div>
                                                        <div class="p-3 rounded-3 bg-primary-subtle mb-3 d-none" id="parent_input">
                                                            <div class="">
                                                                <label class="form-label fw-semibold">Dengan Parent Akun</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text px-6" id="basic-addon1"><i
                                                                            class="ti ti-building-skyscraper fs-6"></i></span>
                                                                    <div style="flex-grow:1">
                                                                        <select name="parent_account_id" id="parent_account_id" class="select2-normal form-select">
                                                                            <option value="">-- Pilih Parent Account --</option>
                                                                            @foreach ($parents as $parent)
                                                                                <option value="{{$parent->id}}" {{$parent->id == old('parent_account_id', $account->parent_account_id) ? 'selected' : ''}}>{{$parent->full_code.' - '.$parent->account_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                @error('parent_account_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" name="is_logical" type="checkbox" value="1" id="is_logical" {{old('is_logical', $account->is_logical) ? 'checked' : ''}} {{$account->is_logical ? 'disabled' : ''}}/>
                                                                <label class="form-check-label" for="is_logical">Akun ini untuk keperluan sistem</label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Deskripsi</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text px-6" id="basic-addon1"><i
                                                                        class="ti ti-align-justified fs-6"></i></span>
                                                                <textarea class="form-control ps-2" name="description" id="description" cols="20" rows="5"
                                                                    placeholder="Keterangan tentang akun ini">{{ old('description', $account->description) }}</textarea>
                                                            </div>
                                                            @error('description')
                                                                <span class="invalid-feedback" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="d-flex gap-1 align-items-center justify-content-end">
                                                            <button type="button"
                                                                class="btn bg-danger-subtle text-danger  waves-effect text-start"
                                                                data-bs-dismiss="modal">
                                                                Close
                                                            </button>
                                                            <button type="submit"
                                                                class="btn btn-primary btn-al-primary">Perbarui</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div id="deleteModal-{{ $account->id }}" class="modal fade" tabindex="-1"
                                        aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                            <div class="modal-content p-3 modal-filled bg-danger">
                                                <div class="modal-header modal-colored-header text-white">
                                                    <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                                        Yakin ingin menghapus Chart Of Accounts ?
                                                    </h4>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="width: fit-content; white-space:normal">
                                                    <h5 class="mt-0 text-white">Chart Of Accounts {{ $account->code }} akan dihapus
                                                    </h5>
                                                    <p class="text-white">Segala data yang berkaitan dengan Chart Of Accounts
                                                        tersebut juga akan dihapus secara permanen.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <form action="{{ route('account.destroy', $account->id) }}" method="POST">
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        
    <!-- Modal for QR Code Preview -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Preview QR Code -->
                    <img id="qrCodeImage" src="" class="img-fluid rounded" alt="QR Code Preview">
                    <a id="downloadLink" href="#" class="btn btn-primary mt-3" download="qr_code.png">Download QR
                        Code</a>
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
            $('.select2').each(function() {
                let modal = $(this).closest('.modal'); // Cari modal terdekat
                $(this).select2({
                    dropdownParent: modal // Pasang dropdown di dalam modal
                });
            });

            $('input[name="is_sub"]').on('change', function(){
                let check = $(this).is(':checked');
                if(check) {
                    $('#parent_input').removeClass('d-none');
                }else{
                    $('#parent_input').addClass('d-none');
                }
            })
        });
    </script>
@endsection
