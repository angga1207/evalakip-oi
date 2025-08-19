<div>
    <div class="row">
        <div class="col-md-6 card rounded-4 border-top border-4 border-primary border-gradient-1">
            <div class="card-body p-4">
                <form class="row g-4" wire:submit.prevent="save">
                    <div class="col-md-12">
                        <label for="input1" class="form-label">
                            Label <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="input1" placeholder="Label" wire:model="data.label">
                        @error('data.label')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="input2" class="form-label">
                            Tanggal Mulai <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="input2" wire:model="data.tanggal_mulai">
                        @error('data.tanggal_mulai')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="input3" class="form-label">
                            Tanggal Selesai <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="input3" wire:model="data.tanggal_selesai">
                        @error('data.tanggal_selesai')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="input4" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select id="input4" class="form-select" wire:model="data.is_active">
                            <option value="" selected hidden>Pilih Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                        @error('data.is_active')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center justify-content-end gap-3">
                            <button type="submit" class="btn btn-grd-primary text-white px-4">
                                @if($isCreate)
                                Simpan
                                @else
                                Perbarui
                                @endif
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
