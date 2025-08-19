<div>

    <div class="card">
        <div class="card-body">
            <ul wire:ignore class="nav nav-tabs nav-primary" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab" aria-selected="true">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon">
                                <i class="material-icons-outlined me-1 fs-5">widgets</i>
                            </div>
                            <div class="tab-title">
                                Komponen
                            </div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile" role="tab" aria-selected="false"
                        tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon">
                                <i class="material-icons-outlined me-1 fs-5">diversity_1</i>
                            </div>
                            <div class="tab-title">
                                Kriteria
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content py-3">
                <div wire:ignore.self class="tab-pane fade show active" id="primaryhome" role="tabpanel">
                    <form class="row" wire:submit.prevent="importKomponen">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="formFile" class="form-label">
                                    Pilih File Excel
                                </label>
                                <input class="form-control" type="file" id="formFile"
                                    wire:model="dataKomponen.fileExcel">
                                @error('dataKomponen.fileExcel')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="float-end">
                                <button type="submit"
                                    class="btn btn-grd btn-grd-primary d-flex align-items-center gap-2"
                                    wire:loading.attr="disabled">
                                    <i class="material-icons-outlined">file_upload</i>
                                    Import Komponen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div wire:ignore.self class="tab-pane fade" id="primaryprofile" role="tabpanel">
                    <form class="row" wire:submit.prevent="importKriteria">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="formFile" class="form-label">
                                    Pilih File Excel
                                </label>
                                <input class="form-control" type="file" id="formFile"
                                    wire:model="dataKriteria.fileExcel">
                                @error('dataKriteria.fileExcel')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="float-end">
                                <button type="submit"
                                    class="btn btn-grd btn-grd-primary d-flex align-items-center gap-2"
                                    wire:loading.attr="disabled">
                                    <i class="material-icons-outlined">file_upload</i>
                                    Import Kriteria
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
