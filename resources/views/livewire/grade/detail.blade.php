<div>
    <div class="row">
        <div class="col-md-6 card rounded-4 border-top border-4 border-primary border-gradient-1">
            <div class="card-body p-4">
                <form class="" wire:submit.prevent="save">
                    <div class="mb-4">
                        <label class="form-label">
                            Predikat <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" placeholder="Predikat" wire:model="data.predikat">
                        @error('data.predikat')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            Nilai <span class="text-danger">*</span>
                        </label>
                        <input type="number" min="0" max="100" class="form-control" placeholder="Nilai"
                            wire:model="data.nilai">
                        @error('data.nilai')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            Penjelasan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" placeholder="Penjelasan" rows="5"
                            wire:model="data.keterangan"></textarea>
                        @error('data.keterangan')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="">
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
