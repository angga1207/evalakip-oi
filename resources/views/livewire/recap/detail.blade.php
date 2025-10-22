<?php

use App\Models\Data\Grade;
    $totalSkor = 0;
    $totalBobot = 0;
?>
<div>
    <div class="card w-100 rounded-4">
        <div class="card-body d-flex flex-column gap-3">
            <div class="">
                <h4 class="mb-0 fw-bold text-center">
                    {{ $instanceData->name ?? 'Instansi' }}
                </h4>
            </div>

            <div class="">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <small>
                        Legenda :
                    </small>
                    <span class="badge bg-grd-royal">
                        SKOR
                    </span>
                    <span class="badge bg-grd-primary mr-2">
                        BOBOT
                    </span>
                </div>
            </div>

            <ul class="list-group list-group-flush" style="flex: 1; overflow-y: auto; max-height: 490px;">
                @foreach($datas as $keyComponent => $data)
                <li class="list-group-item px-2" style="background-color: #cdcdcd;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="wh-42 d-flex align-items-center justify-content-center rounded-3 bg-grd-primary">
                            <span class="material-icons-outlined text-white">calendar_today</span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted fw-bold">
                                {{ $data['component']['nama'] ?? '' }}
                            </h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-grd-royal fs-6">
                                @php
                                $skor = 0;
                                foreach($data['component']['children'] as $keySub => $subComponent) {
                                    $skor = 0;
                                    $average = 0;

                                    $sumJawabanSkor = collect($data['jawaban'][$keySub])->sum('skor') ?? 0;
                                    $countActiveCriteria = collect($data['criterias'][$keySub])->where('is_active',
                                    true)->count();
                                    $average = $countActiveCriteria > 0 ? $sumJawabanSkor / $countActiveCriteria : 0;
                                    $skr = $average * $subComponent['bobot'];
                                    $skor += $skr;
                                    $totalSkor += $skor;
                                    $totalBobot += $subComponent['bobot'];
                                }
                                @endphp
                                {{ number_format($skor, 2, ',', '.') }}
                            </span>
                            <span class="badge bg-grd-primary fs-6 mr-2">
                                {{ number_format($data['component']['bobot'], 2, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </li>

                @foreach($data['component']['children'] as $keySub => $subComponent)
                <li class="list-group-item px-2 ps-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="wh-42 d-flex align-items-center justify-content-center rounded-3 bg-grd-success"
                            style="width: 42px !important; height: 42px !important; flex: none">
                            <span class="material-icons-outlined text-white">calendar_today</span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">
                                {{ $subComponent['nama'] ?? '' }}
                            </h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-grd-royal fs-6">
                                @php
                                $skor = 1;
                                $average = 0;

                                $sumJawabanSkor = collect($data['jawaban'][$keySub])->sum('skor') ?? 0;
                                $countActiveCriteria = collect($data['criterias'][$keySub])->where('is_active',
                                true)->count();
                                $average = $countActiveCriteria > 0 ? $sumJawabanSkor / $countActiveCriteria : 0;
                                $skor = $average * $subComponent['bobot'];

                                @endphp
                                {{ number_format($skor, 2, ',', '.') }}
                            </span>
                            <span class="badge bg-grd-primary fs-6 mr-2">
                                {{ number_format($subComponent['bobot'], 2, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </li>
                @endforeach

                @endforeach
            </ul>

            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2 mt-3">
                    <div class="">
                        <span class="badge border text-dark fs-5">
                            {{ number_format($totalSkor, 2, '.',',') }} /
                            <small style="font-size: 0.7rem;">
                                {{ $totalBobot > 0 ? number_format($totalBobot, 2, '.',',') : '0.00' }}
                            </small>
                        </span>
                    </div>
                    <div class="">
                        @php
                        $grade = Grade::where('nilai', '>=', $totalSkor)
                        ->orderBy('nilai', 'asc')
                        ->first();
                        @endphp
                        <span class="badge bg-grd-royal fs-5">
                            {{ $grade->predikat ?? 'E' }}
                        </span>
                    </div>
                </div>
                <div class="">
                    <a href="{{ route('evaluasi', ['instance' => $instanceData->id]) }}"
                        class="btn btn-grd-primary d-flex align-items-center text-white">
                        <span class="material-icons-outlined me-2">file_open</span>
                        Buka Evaluasi
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
