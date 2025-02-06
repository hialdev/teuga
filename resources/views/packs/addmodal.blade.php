<!-- Add New Customer modal -->
<div class="modal fade" id="{{$id}}" tabindex="-1" aria-labelledby="vertical-center-modal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Tambah Pengemasan
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pack.store') }}" method="POST">
                    @csrf

                    <label for="name" class="form-label">Nama Pengemasan</label>
                    <input type="text" name="name" class="form-control mb-2" value="{{ old('name') }}"
                      placeholder="Nama Pengemasan">
                    <label for="name" class="form-label">Kapasitas</label>
                    <input type="number" name="capacity" class="form-control mb-2" value="{{ old('capacity') }}"
                      placeholder="Kapasitas">
                    <label for="text" class="form-label">Satuan</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text px-6" id="basic-addon1"><i
                                class="ti ti-package fs-6"></i></span>
                        <div style="flex-grow:1">
                            <select name="unit_id" id="unit" class="select2 form-select">
                                <option value="">-- Pilih Satuan --</option>
                                @foreach ($units as $unit)
                                    <option value="{{$unit->id}}" {{ $unit->id == old('unit_id') ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        Tidak menemukan Satuan ? 
                        <button type="button" class="btn btn-sm text-primary bg-primary-subtle"
                            data-bs-toggle="modal"
                            data-bs-target="#addSatuanModal"
                        >Tambah Satuan</button>
                    </div>
                    
                    <div class="d-flex gap-1 align-items-center justify-content-end">
                        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start"
                            data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary btn-al-primary">Tambah Pengemasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('units.addmodal', ['id' => 'addSatuanModal'])
