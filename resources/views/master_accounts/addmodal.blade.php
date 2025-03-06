<!-- Add New Customer modal -->
<div class="modal fade" id="{{$id}}" tabindex="-1" aria-labelledby="vertical-center-modal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Tambah Master Account
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('master-account.store') }}" method="POST">
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
                                <option value="{{$type}}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <label for="code" class="form-label">Kode Master Account</label>
                    <input type="text" name="code" class="form-control mb-2" value="{{ old('code') }}"
                      placeholder="Penanda Unik Master Account">
                    
                    <div class="d-flex gap-1 align-items-center justify-content-end">
                        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start"
                            data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary btn-al-primary">Tambah Master Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>