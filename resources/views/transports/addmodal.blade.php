<!-- Add New Customer modal -->
<div class="modal fade" id="{{$id}}" tabindex="-1" aria-labelledby="vertical-center-modal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Tambah Pengangkutan
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('transport.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tanggal PO Logistik</label>
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
          
                    <label for="text" class="form-label">Angkut Dengan Logistik</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text px-6" id="basic-addon1"><i class="ti ti-package fs-6"></i></span>
                        <div style="flex-grow:1">
                            <select name="logistic_id" id="unit" class="select2 form-select">
                                <option value="">-- Pilih Logistik --</option>
                                @foreach (\App\Models\Logistic::orderBy('name', 'asc')->get() as $logistic)
                                    <option value="{{$logistic->id}}" {{ $logistic->id == old('logistic_id') ? 'selected' : '' }}>
                                        {{ $logistic->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <label for="total_price" class="form-label">Total Harga Pengangkutan</label>
                    <input type="text" name="total_price" class="form-control input-rupiah mb-2" value="{{ old('total_price') }}"
                      placeholder="Rp 0">

                    <label for="tax" class="form-label">Ditambah Pajak (%)</label>
                    <input type="number" name="tax" class="form-control mb-2" value="{{ old('tax') }}"
                      placeholder="x%">
                    
                    <div class="d-flex gap-1 align-items-center justify-content-end">
                        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start"
                            data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary btn-al-primary">Tambah Pengangkutan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>