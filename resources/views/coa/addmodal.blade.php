<!-- Add New Customer modal -->
<div class="modal fade" id="{{$id}}" tabindex="-1" aria-labelledby="vertical-center-modal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Tambah Account
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('account.store') }}" method="POST">
                    @csrf

                    <label for="text" class="form-label">Account</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text px-6" id="basic-addon1"><i class="ti ti-calculator fs-6"></i></span>
                        <div style="flex-grow:1">
                            <select name="master_account_id" id="master_account_id" class="select2 form-select">
                                @foreach ($masters as $master)
                                <option value="{{$master->id}}">{{ $master->code.' - '.ucfirst($master->type) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <label for="account_name" class="form-label">Nama Account</label>
                    <input type="text" name="account_name" class="form-control mb-3" value="{{ old('account_name') }}"
                      placeholder="Nama untuk Account">
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="is_sub" type="checkbox" value="1" id="is_sub" />
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
                                    <select name="parent_account_id" id="parent_account_id_add" class="select2 form-select">
                                        <option value="">-- Pilih Parent Account --</option>
                                        @foreach ($parents as $parent)
                                            <option value="{{$parent->id}}" {{$parent->id == old('parent_account_id') ? 'selected' : ''}}>{{$parent->full_code.' - '.$parent->account_name}}</option>
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
                            <input class="form-check-input" name="is_logical" type="checkbox" value="1" id="is_logical" />
                            <label class="form-check-label" for="is_logical">Akun ini untuk keperluan sistem</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <div class="input-group">
                            <span class="input-group-text px-6" id="basic-addon1"><i
                                    class="ti ti-align-justified fs-6"></i></span>
                            <textarea class="form-control ps-2" name="description" id="description" cols="20" rows="5"
                                placeholder="Keterangan tentang akun ini">{{ old('description') }}</textarea>
                        </div>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="d-flex gap-1 align-items-center justify-content-end">
                        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start"
                            data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary btn-al-primary">Tambah Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

