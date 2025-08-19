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

                                    <span class="badge bg-grd-royal fs-6">
                                        {{ number_format($dataPenilaian[$key]['skor'],2) ?? 0 }}
                                    </span>

                                    <div class="badge bg-grd-primary fs-6">
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
                                        <span class="badge bg-grd-royal fs-6">
                                            Skor : {{ number_format($dataPenilaian[$key]['children'][$keySub]['skor'],
                                            2) ?? 0 }}
                                        </span>
                                        <span class="badge bg-grd-primary fs-6 mr-2">
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
                                                <th scope="col" class="text-white" width="200">
                                                    Catatan Evaluator
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
                                                    <select class="form-select form-select-sm"
                                                        wire:model.live="dataPenilaian.{{ $key }}.children.{{ $keySub }}.criterias.{{ $keyKriteria }}.jawaban">
                                                        <option value="" hidden>Jawaban</option>
                                                        @foreach($kriteria['jawaban']['values'] as $opsi)
                                                        <option value="{{ $opsi['nilai'] }}">
                                                            {{ $opsi['label'] }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @else
                                                    <span class="text-center text-danger" style="white-space: nowrap;">
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
                                                    <span class="text-center text-danger" style="white-space: nowrap;">
                                                        Tidak Aktif
                                                    </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($kriteria['is_active'])
                                                    <textarea class="form-control"
                                                        wire:model="dataPenilaian.{{ $key }}.children.{{ $keySub }}.criterias.{{ $keyKriteria }}.catatan"
                                                        placeholder="Catatan..."></textarea>
                                                    @else
                                                    <span class="text-center text-danger" style="white-space: nowrap;">
                                                        Tidak Aktif
                                                    </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($dataPenilaian[$key]['children'][$keySub]['criterias'][$keyKriteria]['evidence'])
                                                    <div class="mt-2 d-flex align-items-center gap-2">
                                                        <a href="{{ asset($dataPenilaian[$key]['children'][$keySub]['criterias'][$keyKriteria]['evidence']) }}"
                                                            target="_blank" class="text-primary">
                                                            Lihat Evidence
                                                        </a>
                                                    </div>
                                                    @else
                                                    <span class="text-center text-danger" style="white-space: nowrap;">
                                                        Tidak Ada Evidence
                                                    </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($kriteria['is_active'])
                                                    <textarea class="form-control"
                                                        wire:model="dataPenilaian.{{ $key }}.children.{{ $keySub }}.criterias.{{ $keyKriteria }}.catatan_evaluator"
                                                        placeholder="Catatan Evaluator..."></textarea>
                                                    @else
                                                    <span class="text-center text-danger" style="white-space: nowrap;">
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
                    <a href="{{ route('recap', ['instance' => $instance]) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>

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

                    @if($isVerified)
                    <div class="btn btn-grd-info text-white">
                        Data sudah dievaluasi
                    </div>
                    @endif
                    @endif
                </div>
                @if(count($dataQuestionaries) > 0)
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div class="btn btn-grd-primary text-white" style="white-space: nowrap;">
                        Skor : {{ number_format($totalSkor, 2) }} / {{ number_format($totalBobot, 2) }}
                    </div>
                    @if($isSubmitted && !$isVerified)
                    <button type="button" class="btn btn-secondary d-flex align-items-center gap-1"
                        wire:click='confirmReset'>
                        <i class="material-icons-outlined me-1">replay</i>
                        Reset
                    </button>
                    <button type="button" class="btn btn-success d-flex align-items-center gap-1"
                        wire:click='confirmVerify'>
                        <i class="material-icons-outlined me-1">add_task</i>
                        Evaluasi
                    </button>
                    <button type="button" class="btn btn-primary" wire:click='save'
                        wire:loading.attr="disabled">
                        <i class="bi bi-save me-1"></i>
                        Simpan
                    </button>
                    @endif

                </div>
                @endif
            </div>
        </div>
    </form>
</div>
