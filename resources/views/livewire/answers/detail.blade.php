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

                    <div class="col-md-12">
                        <label for="input4" class="form-label">
                            Jenis Penilaian <span class="text-danger">*</span>
                        </label>
                        <select id="input4" class="form-select" wire:model.live="valueType">
                            <option value="" selected hidden>Pilih Jenis Penilaian</option>
                            <option value="boolean">Ya/Tidak</option>
                            <option value="abc">A,B,C</option>
                            <option value="abcd">A,B,C,D</option>
                            <option value="abcde">A,B,C,D,E</option>
                        </select>
                        @error('valueType')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <hr>
                        @if($valueType === 'boolean')
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Nilai Ya <span class="text-danger">*</span>
                                </label>
                                <input type="number" min="0" max="1" step="1" class="form-control" placeholder="Nilai Ya"
                                    wire:model="arrValues.0.value">
                                @error('arrValues.0.value')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Nilai Tidak <span class="text-danger">*</span>
                                </label>
                                <input type="number" min="0" max="1" step="1" class="form-control" placeholder="Nilai Tidak"
                                    wire:model="arrValues.1.value">
                                @error('arrValues.1.value')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @elseif($valueType === 'abc' || $valueType === 'abcd' || $valueType === 'abcde')
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Nilai A <span class="text-danger">*</span>
                                </label>
                                <input type="text"class="form-control"
                                    placeholder="Nilai A" wire:model="arrValues.0.value">
                                @error('arrValues.0.value')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Nilai B <span class="text-danger">*</span>
                                </label>
                                <input type="text"class="form-control"
                                    placeholder="Nilai B" wire:model="arrValues.1.value">
                                @error('arrValues.1.value')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Nilai C <span class="text-danger">*</span>
                                </label>
                                <input type="text"class="form-control"
                                    placeholder="Nilai C" wire:model="arrValues.2.value">
                                @error('arrValues.2.value')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if($valueType === 'abcd' || $valueType === 'abcde')
                            <div class="col-md-6">
                                <label class="form-label">
                                    Nilai D <span class="text-danger">*</span>
                                </label>
                                <input type="text"class="form-control"
                                    placeholder="Nilai D" wire:model="arrValues.3.value">
                                @error('arrValues.3.value')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif
                            @if($valueType === 'abcde')
                            <div class="col-md-6">
                                <label class="form-label">
                                    Nilai E <span class="text-danger">*</span>
                                </label>
                                <input type="text"class="form-control"
                                    placeholder="Nilai E" wire:model="arrValues.4.value">
                                @error('arrValues.4.value')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif
                        </div>
                        @endif
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
