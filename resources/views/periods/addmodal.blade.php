<!-- Add New Customer modal -->
<div class="modal fade" id="{{$id}}" tabindex="-1" aria-labelledby="vertical-center-modal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Tambah Periode
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('period.store') }}" method="POST">
                    @csrf

                    <label for="name" class="form-label">Nama Periode</label>
                    <input type="text" name="name" class="form-control mb-2" value="{{ old('name') }}"
                      placeholder="Nama Periode (contoh. Triwulan x / Priode Tahun xxxx)">
                    <label for="text" class="form-label">Mulai dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control mb-2" value="{{ old('start_date') }}" required>
                    
                    <label for="text" class="form-label">Hingga Tanggal</label>
                    <input type="date" name="end_date" class="form-control mb-2" value="{{ old('end_date') }}" required>

                    <div class="d-flex gap-1 align-items-center justify-content-end">
                        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start"
                            data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary btn-al-primary">Tambah Periode</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>