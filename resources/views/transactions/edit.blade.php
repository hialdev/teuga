@extends('layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/libs/select2/dist/css/select2.min.css">
@endsection

@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Perbarui Transaksi Baru</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('transaction.index') }}">Transaksi</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Perbarui Transaksi Baru</li>
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

    <section class="pb-5">
        <form action="{{ route('transaction.update', $transaction->id) }}" method="POST">
            @csrf

            <!-- Form Perbarui Transaksi -->
            <div class="card mb-4">
                <div class="px-4 py-3 border-bottom">
                    <h1 class="card-title fw-semibold mb-0">Perbarui Transaksi Baru</h1>
                </div>
                <div class="card-body">
                    <div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Berada di Periode Transaksi</label>
                            <div class="input-group">
                                <span class="input-group-text px-6" id="basic-addon1"><i
                                        class="ti ti-calendar-event fs-6"></i></span>
                                <div style="flex-grow:1">
                                    <select name="transaction_period_id" id="transaction_period_id" class="select2-period form-select">
                                        <option value="">-- Pilih Periode --</option>
                                        @foreach ($periods as $period)
                                            <option value="{{$period->id}}" data-year="{{\Carbon\Carbon::parse($period->start_date)->format('Y')}}" {{$period->id == old('transaction_period_id', $transaction->transaction_period_id) ? 'selected' : ''}}>{{$period->name.' - '.\Carbon\Carbon::parse($period->start_date)->format('d M Y').' s.d '.\Carbon\Carbon::parse($period->end_date)->format('d M Y') }}</option>
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
                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal Transaksi</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ old('date' , $transaction->date ?? date('Y-m-d')) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description_primary" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description_primary" name="description_primary" placeholder="Masukkan deskripsi transaksi">{{ old('description_primary', $transaction->description) }}</textarea>
                        </div>
                    </div>
                    <hr>
                    <h6 class="mb-3 fw-semibold">Detail Transaksi</h6>
                    <div class="table-responsive">
                        <table class="table border text-nowrap mb-0 align-middle" id="transaction-details">
                            <thead>
                                <tr>
                                    <th>Akun</th>
                                    <th>Deskripsi</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($transaction->details->count() > 0)
                                    @foreach($transaction->details as $trx)
                                    <tr class="">
                                        <td>
                                            <div style="width:20em">
                                                <select name="account_id[]" class="form-control select2-normal" required>
                                                    <option value="">Pilih Akun</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}" {{$account->id == $trx->account->id ? 'selected' : ''}}>{{ $account->full_code }} - {{ $account->account_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width:100%;min-width:13em;max-width:15em">
                                                <input type="text" name="description[]" value="{{old('description', $trx->description)}}" class="form-control" placeholder="Keterangan Transaksi">
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width:100%;min-width:13em;max-width:15em">
                                                <input type="text" name="debit[]" value="{{formatRupiah(old('debit', $trx->debit))}}" class="form-control input-rupiah" placeholder="Rp">
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width:100%;min-width:13em;max-width:15em">
                                                <input type="text" name="credit[]" value="{{formatRupiah(old('credit', $trx->credit))}}" class="form-control input-rupiah" placeholder="Rp">
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-danger remove-row"><i class="ti ti-minus"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @elseif(count(old('account_id', [])) > 0)
                                    @foreach(old('account_id',[]) as $index => $accountId)
                                    <tr class="">
                                        <td>
                                            <div style="width:20em">
                                                <select name="account_id[]" class="form-control select2-normal" required>
                                                    <option value="">Pilih Akun</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}" {{$account->id == $accountId ? 'selected' : ''}}>{{ $account->full_code }} - {{ $account->account_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width:100%;min-width:13em;max-width:15em">
                                                <input type="text" name="description[]" value="{{old('description')[$index]}}" class="form-control" placeholder="Keterangan Transaksi">
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width:100%;min-width:13em;max-width:15em">
                                                <input type="text" name="debit[]" value="{{formatRupiah(old('debit')[$index])}}" class="form-control input-rupiah" placeholder="Rp">
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width:100%;min-width:13em;max-width:15em">
                                                <input type="text" name="credit[]" value="{{formatRupiah(old('credit')[$index])}}" class="form-control input-rupiah" placeholder="Rp">
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-danger remove-row"><i class="ti ti-minus"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr class="">
                                    <td>
                                        <div style="width:20em">
                                            <select name="account_id[]" class="form-control select2-normal" required>
                                                <option value="">Pilih Akun</option>
                                                @foreach($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->full_code }} - {{ $account->account_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="width:100%;min-width:13em;max-width:15em">
                                            <input type="text" name="description[]" class="form-control" placeholder="Keterangan Transaksi">
                                        </div>
                                    </td>
                                    <td>
                                        <div style="width:100%;min-width:13em;max-width:15em">
                                            <input type="text" name="debit[]" class="form-control input-rupiah" placeholder="Rp">
                                        </div>
                                    </td>
                                    <td>
                                        <div style="width:100%;min-width:13em;max-width:15em">
                                            <input type="text" name="credit[]" class="form-control input-rupiah" placeholder="Rp">
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <button type="button" class="btn btn-danger remove-row"><i class="ti ti-minus"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 d-flex align-items-center justify-content-between">
                        <button type="submit" class="btn btn-primary"><i class="ti ti-book-2 me-2"></i>Simpan Transaksi</button>
                        <button type="button" class="btn btn-secondary" id="add-row"><i class="ti ti-row-insert-top me-2"></i> Tambah</button>
                    </div>
                </div>
            </div>

            <!-- Detail Transaksi (Input Dinamis) -->

            <!-- Tombol Submit -->
        </form>

    </section>

@endsection
@section('scripts')
    <script src="{{ env('APP_URL') }}/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="{{ env('APP_URL') }}/assets/libs/select2/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Inisialisasi Select2
            $('.select2-normal').select2({
                placeholder: "Pilih Akun",
                allowClear: true
            });

            // Tambah baris baru
            $('#add-row').click(function () {
                let newRow = `
                    <tr class="">
                        <td>
                            <div style="width:20em">
                                <select name="account_id[]" class="form-control select2-normal" required>
                                    <option value="">Pilih Akun</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->full_code }} - {{ $account->account_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td>
                            <div style="width:100%;min-width:13em;max-width:15em">
                                <input type="text" name="description[]" class="form-control" placeholder="Deskripsi akun">
                            </div>
                        </td>
                        <td>
                            <div style="width:100%;min-width:13em;max-width:15em">
                                <input type="text" name="debit[]" class="form-control input-rupiah" placeholder="Rp">
                            </div>
                        </td>
                        <td>
                            <div style="width:100%;min-width:13em;max-width:15em">
                                <input type="text" name="credit[]" class="form-control input-rupiah" placeholder="Rp">
                            </div>
                        </td>
                        <td>
                            <div>
                                <button type="button" class="btn btn-danger remove-row"><i class="ti ti-minus"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
                $('#transaction-details tbody').append(newRow);
                $('.select2-normal').select2();
            });

            // Hapus baris
            $(document).on('click', '.remove-row', function () {
                $(this).closest('tr').remove();
            });

            $(document).on('change', '#transaction_period_id', function () {
                let selectedOption = $(this).find(':selected'); // Ambil option yang dipilih
                let year = selectedOption.data('year'); // Ambil nilai data-year
                let today = new Date(); // Ambil tanggal hari ini
                let currentYear = today.getFullYear(); // Ambil tahun sekarang

                if (year) {
                    let minDate = `${year}-01-01`; // Awal tahun yang dipilih
                    let maxDate = `${year}-12-31`; // Akhir tahun yang dipilih
                    let defaultDate = (year == currentYear) 
                        ? today.toISOString().split('T')[0]  // Gunakan tanggal hari ini jika tahun yang dipilih adalah tahun sekarang
                        : minDate; // Jika beda tahun, atur ke 1 Januari tahun yang dipilih

                    $('#date').attr('min', minDate); // Set batas minimal
                    $('#date').attr('max', maxDate); // Set batas maksimal
                    $('#date').val(defaultDate); // Tetapkan nilai awal
                }
            });
        });
    </script>
@endsection
