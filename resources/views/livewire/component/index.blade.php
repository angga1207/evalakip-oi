<?php

?>
<div>

    <div class="w-100">

        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <input type="search" class="form-control" placeholder="Pencarian..." wire:model.live='search'>
                </div>
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    @foreach($datas as $index => $data)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <div class="accordion-button collapsed border d-flex align-items-center justify-content-between"
                                data-bs-toggle="collapse" data-bs-target="#flush-collapse-{{ $data->id }}"
                                aria-expanded="false" aria-controls="flush-collapse-{{ $data->id }}">
                                <div class="flex-grow-1" style="cursor: pointer;">
                                    {{ $loop->iteration }}.
                                    {{ $data->nama }}
                                </div>
                                <div class="d-flex gap-4 align-items-center me-4">
                                    <div class="">
                                        <small class="text-muted" style="font-size: 12px;">
                                            Bobot:
                                        </small>
                                        <span class="badge bg-grd-primary">
                                            {{ $data->bobot }}
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('components.edit', $data->id) }}"
                                        class="btn btn-primary btn-sm px-2 py-1">
                                        <i class="material-icons-outlined" style="font-size: 12px;">edit</i>
                                    </a>
                                    <button class="btn btn-danger btn-sm px-2 py-1"
                                        wire:click="confirmDelete({{ $data->id }})">
                                        <i class="material-icons-outlined" style="font-size: 12px;">delete</i>
                                    </button>
                                </div>
                            </div>
                        </h2>
                        <div id="flush-collapse-{{ $data->id }}" class="accordion-collapse collapse border"
                            aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <a href="{{ route('components.create', ['parent_id' => $data->id]) }}"
                                        class="btn btn-success btn-sm px-2 py-1">
                                        <i class="material-icons-outlined" style="font-size: 12px;">add</i>
                                        Tambah Sub Komponen
                                    </a>
                                </div>
                                @if($data->Children->count() > 0)
                                <table id="myDataTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="10">
                                                No.
                                            </th>
                                            <th>
                                                Nama Sub Komponen
                                            </th>
                                            <th class="text-center" width="100">
                                                Bobot
                                            </th>
                                            <th class="no-export text-center" width="100">
                                                Opsi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data->Children as $index => $child)
                                        <tr wire:key="data-{{ $child->id }}">
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <h6 class="mb-0">
                                                    {{ $child['nama'] }}
                                                </h6>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-grd-primary">
                                                {{ $child->bobot }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('components.edit', $child->id) }}"
                                                        class="btn btn-primary btn-sm px-2 py-1">
                                                        <i class="material-icons-outlined"
                                                            style="font-size: 12px;">edit</i>
                                                    </a>
                                                    <button class="btn btn-danger btn-sm px-2 py-1"
                                                        wire:click="confirmDelete({{ $child->id }})">
                                                        <i class="material-icons-outlined"
                                                            style="font-size: 12px;">delete</i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                <div class="text-center">
                                    <span class="text-muted">
                                        Tidak ada sub komponen
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .accordion-button::after {
            display: none !important;
        }
    </style>
    @endpush
</div>
