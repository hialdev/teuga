@extends('layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/libs/select2/dist/css/select2.min.css">
@endsection

@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Transaksi</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Kelola Transaksi</li>
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
        <h1>Transaksi</h1>
        <div style="aspect-ratio:1/1; width:3em; height:3em"
            class="bg-primary text-white d-flex align-items-center justify-content-center rounded-5 me-auto">
            {{ count($transactions) }}</div>
        @if(getDataPeriod())
        @php
            $periodData = \App\Models\TransactionPeriod::find(request()->get('period', getDataPeriod()->id));
        @endphp
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#closeBookModal-{{$periodData->id}}"><i class="ti ti-book-off me-2"></i>Tutup Jurnal</button>
        <!-- Close Book Modal -->
        <div id="closeBookModal-{{$periodData->id}}" class="modal fade" tabindex="-1"
            aria-labelledby="danger-header-modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content p-3 modal-filled bg-danger">
                    <div class="modal-header modal-colored-header text-white">
                        <h4 class="modal-title text-white" id="danger-header-modalLabel">
                            Yakin ingin menutup Buku Periode {{$periodData->name}} ?
                        </h4>
                        <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="width: fit-content; white-space:normal">
                        <h5 class="mt-0 text-white">Periode {{$periodData->name}} akan ditutup, {{$periodData->transactions->count()}} Transaksi akan dikunci</h5>
                        <p class="text-white">Setelah menutup priode, transaksi terkait akan dikunci dan perhitungan Pajak serta Penyusutan / Kenaikan Aset Tetap akan dikalkulasi otomatis. Anda masih bisa membuka buku kembali</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Close
                        </button>
                        <form action="{{route('period.close', $periodData->id)}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-dark">Ya, Tutup Buku</button>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        @endif
        <a href="{{ route('transaction.add') }}" class="btn btn-primary btn-al-primary"><i class="ti ti-plus me-2"></i>Input</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET">
                <div class="row align-items-end mb-3 flex-wrap">
                    <div class="col-md-3 mb-2">
                        <label for="search" class="form-label">Filter Kata</label>
                        <input type="text" class="form-control" placeholder="Cari Transaksi" name="search"
                            value="{{ $filter->q ?? '' }}">
                    </div>
                    
                    <div class="col-md-3 mb-2">
                        <label for="period" class="form-label">Transaksi untuk Priode</label>
                        <select name="period" id="period" class="form-select">
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}" {{ $filter->period == $period->id ? 'selected' : '' }}>
                                    {{ $period->name.' ('.\Carbon\Carbon::parse($period->start_date)->format('d M y').' s.d '.\Carbon\Carbon::parse($period->end_date)->format('d M y').')' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="field" class="form-label">Urutkan Berdasarkan</label>
                        <select name="field" id="field" class="form-select">
                            @foreach (getModelAttributes('Transaction', []) as $atr)
                                <option value="{{ $atr }}" {{ $filter->field == $atr ? 'selected' : '' }}>
                                    {{ toPascalCase($atr) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
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
                                <h6 class="fs-3 fw-semibold mb-0">Transaksi</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Tanggal</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Keterangan</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                            </th>
                            <th>
                                <h6 class="fs-3 fw-semibold mb-0">Timestamp</h6>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 mb-1 text-muted" style="white-space:normal;">Kode Transaksi</div>
                                        <h6 class="fw-semibold mb-1 fs-2 badge bg-primary text-white" style="">{{$transaction->code}}</h6>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 mb-1 text-muted" style="white-space:normal;">Periode Transaksi</div>
                                        <div class="fw-semibold mb-1 fs-2 text-primary" style="">{{$transaction->period->name}}</div>
                                    </div>
                                    <div>
                                        <div class="fw-normal fs-1 mb-1 text-muted" style="white-space:normal;">Tanggal Transaksi</div>
                                        <div class="fw-semibold mb-1 fs-2 text-secondary" style="">{{\Carbon\Carbon::parse($transaction->date)->format('d F Y')}}</div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Keterangan Transaksi
                                        </div>
                                        <p class="fs-2 text-primary mb-1" style="white-space:normal">{{ $transaction->description }}</p>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-normal fs-1 mb-1 text-muted" style="white-space:normal;">Status Transaksi</div>
                                        <h6 class="fw-semibold mb-1 fs-2 badge bg-{{$transaction->period->is_closed ? 'danger' : 'secondary'}} text-white" style="">{{$transaction->period->is_closed ? 'Telah Tutup Buku' : 'Belum Tutup Buku'}}</h6>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-start gap-2">
                                        <div class="badge bg-success-subtle text-success rounded-3 fw-semibold fs-2">Updated
                                            at
                                            : {{ $transaction->updated_at }}</div>
                                        <div class="badge bg-primary-subtle text-primary rounded-3 fw-semibold fs-2">Created
                                            at
                                            : {{ $transaction->created_at }}</div>
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
                                                <a href="{{route('transaction.edit', $transaction->id)}}" class="dropdown-item d-flex align-items-center gap-3">
                                                   <i class="fs-4 ti ti-pencil"></i>Edit Transaksi</a>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex align-items-center gap-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal-{{ $transaction->id }}"><i
                                                        class="fs-4 ti ti-trash"></i>Delete</button>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal-{{ $transaction->id }}" tabindex="-1"
                                        aria-labelledby="vertical-center-modal" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header d-flex align-items-center">
                                                    <h4 class="modal-title" id="myLargeModalLabel">
                                                        Perbarui Transaksi {{ $transaction->code }}
                                                    </h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('master-account.update', $transaction->id) }}" method="POST">
                                                        @csrf
                                                        <label for="text" class="form-label">Kategori Account</label>
                                                        <div class="input-group mb-2">
                                                            <span class="input-group-text px-6" id="basic-addon1"><i class="ti ti-calculator fs-6"></i></span>
                                                            <div style="flex-grow:1">
                                                                @php
                                                                    $types = config('al.account_types', []);
                                                                @endphp
                                                                <select name="type" id="type" class="select2 form-select">
                                                                    @foreach ($types as $type)
                                                                    <option value="{{$type}}" {{$type == old('type', $transaction->type) ? 'selected' : ''}}>{{ ucfirst($type) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <label for="code" class="form-label">Kode Transaksi</label>
                                                        <input type="text" name="code" class="form-control mb-2" value="{{ old('code', $transaction->code) }}"
                                                        placeholder="Penanda Unik Transaksi">
                                                        
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
                                    <div id="deleteModal-{{ $transaction->id }}" class="modal fade" tabindex="-1"
                                        aria-labelledby="danger-header-modalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                            <div class="modal-content p-3 modal-filled bg-danger">
                                                <div class="modal-header modal-colored-header text-white">
                                                    <h4 class="modal-title text-white" id="danger-header-modalLabel">
                                                        Yakin ingin menghapus Transaksi ?
                                                    </h4>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="width: fit-content; white-space:normal">
                                                    <h5 class="mt-0 text-white">Transaksi {{ $transaction->code }} akan dihapus
                                                    </h5>
                                                    <p class="text-white">Segala data yang berkaitan dengan Transaksi
                                                        tersebut juga akan dihapus secara permanen.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <form action="{{ route('transaction.destroy', $transaction->id) }}" method="POST">
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
                            
                            <tr>
                                <td colspan="6">
                                    <div class="btn-accordion p-3 border border-2 rounded-3 border-dashed">
                                        <div class="d-flex align-items-center gap-2 justify-content-between flex-wrap" style="cursor: pointer">
                                            <h6 class="mb-0">Detail Transaksi</h6>
                                            <div class="d-flex me-auto fs-2 align-items-center justify-content-center p-2 bg-primary text-white rounded-circle" style="aspect-ratio:1/1; width:2em; height:2em">
                                                {{$transaction->details->count()}}
                                            </div>
                                            <div class="ms-md-auto text-start">
                                                <div class="fs-1 text-muted">Klik untuk melihat detail</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn-accordion-content mt-3">
                                    @foreach ($transaction->details as $tdetail)
                                        <div class="row mb-2 px-2">
                                            <div class="col-2">
                                                <div>
                                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kode Akun
                                                    </div>
                                                    <h6 class="fw-semibold mb-1 fs-2" style="">{{ $tdetail->account->full_code }}</h6>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div>
                                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Nama Akun
                                                    </div>
                                                    <h6 class="fw-semibold mb-1 fs-2" style="white-space:normal !important">{{ $tdetail->account->account_name }}</h6>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div>
                                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Keterangan
                                                    </div>
                                                    <p class="fs-2 mb-1" style="white-space:normal">{{ $tdetail->description }}</p>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div>
                                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Debit
                                                    </div>
                                                    <h6 class="fw-semibold mb-1 fs-2" style="">{{ formatRupiah($tdetail->debit) }}</h6>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div>
                                                    <div class="fw-normal fs-1 text-muted" style="white-space:normal;">Kredit
                                                    </div>
                                                    <h6 class="fw-semibold mb-1 fs-2" style="">{{ formatRupiah($tdetail->credit) }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data untuk Periode yang dipilih</td>
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
@endsection
