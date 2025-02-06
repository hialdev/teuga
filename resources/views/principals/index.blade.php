@extends('layouts.base')
@section('css')
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Principal</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Semua Principal</li>
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

    <div class="mb-3 d-flex align-items-center gap-2 justify-content-between">
        <h1>Principal</h1>
        <div style="aspect-ratio:1/1; width:3em; height:3em"
            class="bg-primary text-white d-flex align-items-center justify-content-center rounded-5 me-auto">
            {{ count($principals) }}</div>
        <a href="{{ route('principal.add') }}" class="btn btn-primary btn-al-primary">Tambah</a>
        {{-- <a href="{{route('pdf.preview.blade', ['bladePath' => 'principals.stok'])}}" target="_blank" class="btn btn-danger"><i class="ti ti-file-download me-2"></i>Laporan Stok</a> --}}
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{route('principal.index')}}" method="GET">
                <div class="row align-items-end mb-3 flex-wrap">
                    <div class="col-md-4 mb-2">
                        <label for="search" class="form-label">Filter Kata</label>
                        <input type="text" class="form-control" placeholder="Cari Nama / Deskripsi" name="search" value="{{$filter->q ?? ''}}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="field" class="form-label">Urutkan Berdasarkan</label>
                        <select name="field" id="field" class="form-select">
                            @foreach (getModelAttributes('Principal', ['pack_id', 'image', 'slug']) as $atr)
                            <option value="{{$atr}}" {{$filter->field == $atr ? 'selected' : ''}}>{{toPascalCase($atr)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="order" class="form-label">Dengan urutan</label>
                        <select name="order" id="order" class="form-select">
                            <option value="newest" {{$filter->order == 'desc' ? 'selected' : ''}}>Terbaru / Terbesar</option>
                            <option value="oldest" {{$filter->order == 'asc' ? 'selected' : ''}}>Terlama / Terkecil</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="d-flex align-items-center gap-1">
                            <button type="submit" class="btn btn-primary w-100" style="white-space: nowrap">Apply</button>
                            <a href="{{url()->current()}}" class="btn btn-secondary" style="white-space: nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2v2a8 8 0 1 0 4.5 1.385V8h-2V2h6v2H18a9.99 9.99 0 0 1 4 8" />
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
                                <h6 class="fs-3 fw-semibold mb-0">Principal</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Kontak</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Alamat Utama</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">PIC / Sales</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Alamat Pabrik / Lainnya</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Timestamp</h6>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($principals as $principal)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center" style="width:15em">
                                        <img src="{{ $principal->image ? asset('/storage/'.$principal->image) : '/assets/images/profile/user-1.jpg' }}"
                                            class="rounded-2" alt="principal Image {{ $principal->name }}" style="width: 4em" />
                                        <div class="ms-3">
                                            <h6 class="fw-semibold mb-1" style="white-space: normal !important">{{ $principal->name }}</h6>
                                            <div class="fw-normal text-muted" style="white-space:normal; font-size:13px; ">{{ $principal->description ?? 'Tidak ada deskripsi' }}</div>    
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-building-bank mb-0 fs-3"></i> {{ $principal->npwp}}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-mail mb-0 fs-3"></i> {{ $principal->email}}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone mb-0 fs-3"></i> {{ $principal->phone}}
                                    </div>
                                    <div class="d-flex align-items-center fs-2 mb-1 gap-2">
                                        <i class="ti ti-phone-check mb-0 fs-3"></i> {{ $principal->fax}}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-normal fs-2" style="white-space:normal; font-size:13px; ">{{ $principal->address }}</div>    
                                    <div class="text-primary fs-2">{{ $principal->city }}. {{$principal->postal_code}}</div>
                                </td>
                                <td>
                                    <a href="{{route('principal.setting', $principal->id).'#pic'}}" class="fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 text-primary bg-primary-subtle rounded-3"
                                    >
                                        <i class="fs-4 ti ti-user-circle"></i> {{ count($principal->pics) }} PIC / Sales
                                    </a>
                                </td>
                                <td>
                                    <button type="button" class="dropdown-item fs-2 text-center d-inline-flex p-2 px-3 align-items-center gap-2 bg-secondary text-white rounded-3"
                                            data-bs-toggle="modal" data-bs-target="#addressOtherModal-{{$principal->id}}"><i
                                                class="fs-4 ti ti-map-2"></i> {{ count($principal->addresses) }} Alamat Lainnya</button>
                                    
                                    <!-- Add Address modal -->
                                    <div class="modal fade " id="addressOtherModal-{{$principal->id}}" tabindex="-1" aria-labelledby="vertical-center-modal"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header d-flex align-items-center">
                                                    <h4 class="modal-title" id="myLargeModalLabel">
                                                        Alamat Pabrik / Lainnya
                                                    </h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body pt-0">
                                                    @foreach ($principal->addresses as $address)
                                                        <div class="d-flex align-items-center gap-2 {{$loop->index+1 != $principal->addresses->count() ? 'mb-2' : ''}} p-4 rounded-3 bg-secondary-subtle">
                                                            <i class="ti ti-map-pin fs-7 me-2 text-secondary"></i>
                                                            <div class="flex-grow-1">
                                                                <div class="fw-bold">{{$address->name}}</div>
                                                                <div class="fw-normal fs-2" style="white-space:normal; font-size:13px; ">{{ $address->address }}</div>    
                                                                <div class="text-primary fs-2">{{ $address->city }}. {{$address->postal_code}}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-start gap-2">
                                        <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">Updated
                                            at
                                            : {{ $principal->updated_at }}</div>
                                        <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">Created
                                            at
                                            : {{ $principal->created_at }}</div>
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
                                                <a href="{{route('principal.setting', $principal->id).'#data-principal'}}" class="dropdown-item text-primary d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-settings"></i>Kelola</a>
                                            </li>
                                            <li>
                                                <a href="{{route('principal.setting', $principal->id).'#alamat-pabrik'}}" class="dropdown-item text-secondary d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-map-2"></i>Kelola Alamat</a>
                                            </li>
                                            <li>
                                                <a href="{{route('principal.setting', $principal->id).'#pic'}}" class="dropdown-item text-secondary d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-user-circle"></i>Kelola PIC</a>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal-{{$principal->id}}"><i
                                                        class="fs-4 ti ti-trash"></i>Delete</button>
                                            </li>
                                        </ul>
                                    </div>


                                    <!-- Delete Modal -->
                                    <div id="deleteModal-{{$principal->id}}" class="modal fade" tabindex="-1"
                                        aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                            <div class="modal-content p-3 modal-filled bg-danger">
                                                <div class="modal-header modal-colored-header text-white">
                                                    <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                                        Yakin ingin menghapus principal ?
                                                    </h4>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="width: fit-content; white-space:normal">
                                                    <h5 class="mt-0 text-white">principal {{$principal->title}} akan dihapus</h5>
                                                    <p class="text-white">Segala data yang berkaitan dengan principal tersebut juga akan dihapus secara permanen.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <form action="{{route('principal.destroy', $principal->id)}}" method="POST">
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
    
@endsection
@section('scripts')
@endsection
