@extends('layouts.base')
@section('css')
@endsection
@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Assset Tetap</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Semua Asset Tetap</li>
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
        <h1>Asset Tetap</h1>
        <div style="aspect-ratio:1/1; width:3em; height:3em"
            class="bg-primary text-white d-flex align-items-center justify-content-center rounded-5 me-auto">
            {{ count($assets) }}</div>
        <a href="{{ route('asset.add') }}" class="btn btn-primary btn-al-primary">Tambah</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{route('asset.index')}}" method="GET">
                <div class="row align-items-end mb-3 flex-wrap">
                    <div class="col-md-4 mb-2">
                        <label for="search" class="form-label">Filter Kata</label>
                        <input type="text" class="form-control" placeholder="Cari Asset" name="search" value="{{$filter->q ?? ''}}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="field" class="form-label">Urutkan Berdasarkan</label>
                        <select name="field" id="field" class="form-select">
                            @foreach (getModelAttributes('Asset', ['image']) as $atr)
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
                            <a href="{{route('asset.index')}}" class="btn btn-secondary" style="white-space: nowrap">
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
                                <h6 class="fs-3 fw-semibold mb-0">Asset</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Pembelian</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Penurunan</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Kenaikan</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Timestamp</h6>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assets as $asset)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center" >
                                        <img src="{{ $asset->image ? asset('storage/' . $asset->image) : '/assets/images/profile/user-1.jpg' }}"
                                            class="rounded-2" alt="Aset Tetap Image {{ $asset->name }}" style="width: 4em" />
                                        <div class="ms-3">
                                            <div class="d-flex mb-1 fs-2 align-items-center gap-2">
                                                <div class="fs-1 badge bg-primary text-white">{{ $asset->account ? $asset->account->full_code : '?'}}</div>
                                                {{ $asset->account ? $asset->account->account_name : 'Belum dikaitkan ke Account' }}
                                            </div>
                                            <h6 class="fw-semibold mb-0">{{ $asset->name }}</h6>
                                            <span class="fw-normal text-muted fs-2 line-clamp line-clamp-2"
                                                style="white-space:normal; ">{{ $asset->description }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Tanggal Pembelian</div>
                                        <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                            {{ \Carbon\Carbon::parse($asset->date)->format('d F Y') }}</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Harga Beli</div>
                                        <h6 class="fs-2 fw-semibold mb-1 text-primary" style="">
                                            {{ formatRupiah($asset->purchase_price) }}</h6>
                                    </div>
                                </td>
                                <td>
                                    @if(!$asset->is_appreciating)
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Masa Manfaat</div>
                                        <h6 class="fs-2 fw-semibold text-success mb-1" style="">
                                            {{ $asset->useful_life }} tahun</h6>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Harga Residu</div>
                                        <h6 class="fs-2 fw-semibold mb-1 text-primary" style="">
                                            {{ formatRupiah($asset->residu_price) }}</h6>
                                    </div>
                                    @else
                                    <div class="fs-2 text-muted">Tidak ada penurunan</div>
                                    @endif
                                </td>
                                <td>
                                    @if($asset->is_appreciating)
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Persentase Kenaikan</div>
                                        <h6 class="fs-2 fw-semibold text-secondary mb-1" style="">
                                            {{ $asset->appreciation_rate }} % / tahun</h6>
                                    </div>
                                    @else
                                    <div class="fs-2 text-muted">Tidak ada kenaikan</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-start gap-2">
                                        <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">Updated
                                            at
                                            : {{ $asset->updated_at }}</div>
                                        <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">Created
                                            at
                                            : {{ $asset->created_at }}</div>
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
                                                <a href="{{route('transaction.add', ['trxdate' => $asset->date, 'trxdesc' => 'Pembelian aset '. $asset->name, 'trxaccount' => $asset->account_id, 'trxprice' => $asset->purchase_price])}}" class="dropdown-item d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-book-2"></i>Catat Pembelian</a>
                                            </li>
                                            <li>
                                                <a href="{{route('asset.edit', $asset->id)}}" class="dropdown-item d-flex align-items-center gap-3"><i
                                                        class="fs-4 ti ti-edit"></i>Edit</a>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal-{{$asset->id}}"><i
                                                        class="fs-4 ti ti-trash"></i>Delete</button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div id="deleteModal-{{$asset->id}}" class="modal fade" tabindex="-1"
                                        aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                            <div class="modal-content p-3 modal-filled bg-danger">
                                                <div class="modal-header modal-colored-header text-white">
                                                    <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                                        Yakin ingin menghapus Bank ?
                                                    </h4>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="width: fit-content; white-space:normal">
                                                    <h5 class="mt-0 text-white">Bank {{$asset->name}} akan dihapus</h5>
                                                    <p class="text-white">Segala data yang berkaitan dengan Bank tersebut juga akan dihapus secara permanen.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <form action="{{route('asset.destroy', $asset->id)}}" method="POST">
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
