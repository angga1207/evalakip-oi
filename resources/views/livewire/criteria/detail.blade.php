<div>
    <div class="row">
        <div class="col-md-12 card rounded-4 border-top border-4 border-primary border-gradient-1">
            <div class="card-body p-4">
                <form class="row g-4" wire:submit.prevent="save">

                    <div class="col-md-6">
                        <label class="form-label">
                            Komponen <span class="text-danger">*</span>
                        </label>
                        <select wire:model.live="parentComponentId" class="form-select">
                            <option value="" hidden>Pilih Komponen</option>
                            @foreach($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->nama }}</option>
                            @endforeach
                        </select>
                        @error('parentComponentId')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Sub Komponen <span class="text-danger">*</span>
                        </label>
                        <select wire:model.live="data.component_id" class="form-select">
                            <option value="" hidden>Pilih Sub Komponen</option>
                            @foreach($subComponents as $com)
                            <option value="{{ $com->id }}">{{ $com->nama }}</option>
                            @endforeach
                        </select>
                        @error('data.component_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($parentComponentId && $data['component_id'])
                    <div class="col-md-6">
                        <label class="form-label">
                            Nama Kriteria <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" wire:model.live="data.nama"
                            placeholder="Masukkan nama kriteria">
                        @error('data.nama')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Pilih Jawaban <span class="text-danger">*</span>
                        </label>
                        <select wire:model.live="data.ref_jawaban_id" class="form-select">
                            <option value="" hidden>Pilih Jawaban</option>
                            @foreach($answers as $answer)
                            <option value="{{ $answer->id }}">{{ $answer->label }}</option>
                            @endforeach
                        </select>
                        @error('data.ref_jawaban_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Deskripsi Kriteria
                        </label>
                        <textarea class="form-control" wire:model.live="data.penjelasan"
                            placeholder="Masukkan deskripsi kriteria"></textarea>
                        @error('data.penjelasan')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Dinilai <span class="text-danger">*</span>
                        </label>
                        <select wire:model.live="data.is_active" class="form-select">
                            <option value="" hidden>Pilih Opsi</option>
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                        @error('data.is_active')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center justify-content-end gap-3">
                            <div class="">
                                <div class="btn-group">
                                    <a href="{{ route('criterias.index') }}"
                                        class="btn btn-primary d-flex align-items-center gap-1">
                                        <i class="material-icons-outlined">arrow_back</i>
                                        <small>
                                            Kembali
                                        </small>
                                    </a>
                                </div>
                            </div>
                            <div class="">
                                <button type="submit" class="btn btn-grd-primary text-white px-4">
                                    @if($isCreate)
                                    Simpan
                                    @else
                                    Perbarui
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
