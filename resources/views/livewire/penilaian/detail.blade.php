<?php

?>
<div>
    <div wire:init='_getDataQuestionaries'></div>
    <form class="card" wire:submit.prevent='save'>
        <div x-data="{ tab: 'tab-0' }" class="card-body" style="overflow-y: auto; height: calc(100vh - 300px);">

            <ul class="nav nav-tabs nav-primary flex-nowrap" role="tablist"
                style="overflow-x: auto; white-space: nowrap;">

                @foreach($dataQuestionaries as $key => $questionary)
                <li class="nav-item flex-grow-1" role="presentation">
                    <a class="nav-link"
                        :class="tab === 'tab-{{ $key }}' ? 'bg-primary text-white' : 'border border-primary text-primary'"
                        href="#" @click="tab = 'tab-{{ $key }}'">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="tab-icon">
                                {{-- <i class="bi bi-house-door me-1 fs-6"></i> --}}
                            </div>
                            <div class="tab-title d-flex align-items-center gap-2">
                                <div>
                                    {{ $questionary['nama'] }}
                                </div>
                                <div class="">

                                    <span class="badge bg-grd-royal">
                                        {{ number_format($dataPenilaian[$key]['skor'],2) ?? 0 }}
                                    </span>

                                    <div class="badge bg-grd-primary">
                                        {{ $questionary['bobot'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                @endforeach

            </ul>

            <div class="tab-content py-3" style="overflow-y: auto; max-height: calc(100vh - 380px);">
                @foreach($dataQuestionaries as $key => $komponen)
                <div class="" x-show="tab === 'tab-{{ $key }}'" role="tabpanel"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100">

                    <div wire:ignore.self class="accordion accordion-flush" id="accordionFlush{{ $key }}">
                        @foreach($komponen['children'] as $keySub => $subKomponen)
                        <div wire:ignore.self class="accordion-item">
                            <h2 class="accordion-header w-100" id="flush-headingOne">
                                <button
                                    class="accordion-button px-0 collapsed d-flex align-items-center justify-content-between gap-4"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-{{ $subKomponen['id'] }}" aria-expanded="false"
                                    aria-controls="flush-{{ $subKomponen['id'] }}">
                                    <div class="">
                                        {{ $loop->iteration }}. {{ $subKomponen['nama'] }}
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-grd-royal">
                                            Skor : {{ $dataPenilaian[$key]['children'][$keySub]['skor'] ?? 0 }}
                                        </span>
                                        <span class="badge bg-grd-primary mr-2">
                                            Bobot : {{ $subKomponen['bobot'] }}
                                        </span>
                                    </div>
                                </button>
                            </h2>
                            <div wire:ignore.self id="flush-{{ $subKomponen['id'] }}"
                                class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                                data-bs-parent="#accordionFlush{{ $key }}" style="">
                                <div class="pb-4 pt-2 table-responsive">
                                    <table class="table mb-0 align-middle">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th scope="col" class="text-white" width="10">
                                                    #
                                                </th>
                                                <th scope="col" class="text-white" width="200">
                                                    Nama Kriteria
                                                </th>
                                                <th scope="col" class="text-white" width="200">
                                                    Penjelasan Kriteria
                                                </th>
                                                <th scope="col" class="text-white" width="50">
                                                    Jawaban
                                                </th>
                                                <th scope="col" class="text-white" width="100">
                                                    Skor
                                                </th>
                                                <th scope="col" class="text-white" width="200">
                                                    Catatan
                                                </th>
                                                <th scope="col" class="text-white" width="200">
                                                    Evidence
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($subKomponen['criterias'] as $keyKriteria => $kriteria)
                                            <tr>
                                                <th scope="row">
                                                    {{ $keyKriteria + 1 }}
                                                </th>
                                                <td>{{ $kriteria['nama'] }}</td>
                                                <td>{{ $kriteria['penjelasan'] }}</td>
                                                <td>
                                                    @if($kriteria['is_active'])
                                                    <select class="form-select form-select-sm" @if($isSubmitted)
                                                        disabled @endif
                                                        wire:model.live="dataPenilaian.{{ $key }}.children.{{ $keySub }}.criterias.{{ $keyKriteria }}.jawaban">
                                                        <option value="" hidden>Pilih Jawaban</option>
                                                        @foreach($kriteria['jawaban']['values'] as $opsi)
                                                        <option value="{{ $opsi['nilai'] }}">
                                                            {{ $opsi['label'] }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @else
                                                    <span class="text-center text-danger">
                                                        Tidak Aktif
                                                    </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($kriteria['is_active'])
                                                    <div class="text-center">
                                                        {{
                                                        $dataPenilaian[$key]['children'][$keySub]['criterias'][$keyKriteria]['jawaban']
                                                        ?? '' }}
                                                    </div>
                                                    @else
                                                    <span class="text-center text-danger">
                                                        Tidak Aktif
                                                    </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($kriteria['is_active'])
                                                    <textarea class="form-control" @if($isSubmitted) disabled @endif
                                                        wire:model="dataPenilaian.{{ $key }}.children.{{ $keySub }}.criterias.{{ $keyKriteria }}.catatan"
                                                        placeholder="Catatan..."></textarea>
                                                    @else
                                                    <span class="text-center text-danger">
                                                        Tidak Aktif
                                                    </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($kriteria['is_active'])
                                                    <input type="file" class="form-control form-control-sm"
                                                        wire:model="dataPenilaian.{{ $key }}.children.{{ $keySub }}.criterias.{{ $keyKriteria }}.new_evidence"
                                                        @if($isSubmitted) disabled @endif placeholder="Evidence...">
                                                    @if($dataPenilaian[$key]['children'][$keySub]['criterias'][$keyKriteria]['evidence'])
                                                    <div class="mt-2 d-flex align-items-center gap-2">
                                                        <a href="{{ asset($dataPenilaian[$key]['children'][$keySub]['criterias'][$keyKriteria]['evidence']) }}"
                                                            target="_blank" class="text-primary">
                                                            Lihat Evidence
                                                        </a>
                                                        <a href="#" class="text-danger"
                                                            wire:click='confirmDeleteEvidence({{ $key }}, {{ $keySub }}, {{ $keyKriteria }})'
                                                            @if($isSubmitted) disabled @endif>
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                    @endif
                                                    @else
                                                    <span class="text-center text-danger">
                                                        Tidak Aktif
                                                    </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
                @endforeach
            </div>
        </div>
        <div class="">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-2 p-3">
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-secondary" wire:click='back'>
                        <i class="bi bi-arrow-left"></i> Kembali
                    </button>

                    @if(count($dataQuestionaries) > 0)
                    @if($isSubmitted)
                    <div class="btn btn-grd-success text-white text-success">
                        Data sudah disubmit
                    </div>
                    @else
                    <div class="btn btn-grd-royal text-white">
                        Data belum disubmit
                    </div>
                    @endif
                    @endif
                </div>
                @if(count($dataQuestionaries) > 0)
                <div class="d-flex align-items-center gap-2">
                    <div class="btn btn-grd-primary text-white">
                        Skor : {{ number_format($totalSkor, 2) }} / {{ $totalBobot }}
                    </div>
                    @if($isSubmitted)
                    <button type="button" class="btn btn-success" disabled>
                        <i class="bi bi-upload"></i>
                        Submit
                    </button>
                    @else
                    <button type="button" class="btn btn-success" wire:click='confirmSubmit'
                        wire:loading.attr="disabled">
                        <i class="bi bi-upload"></i>
                        Submit
                    </button>
                    <button type="button" class="btn btn-primary" wire:click='save' wire:loading.attr="disabled">
                        <i class="bi bi-save"></i>
                        Simpan
                    </button>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </form>
</div>
