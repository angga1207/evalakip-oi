<?php

use App\Models\Data\Grade;

?>
<div>

    <div class="row mb-4">
        <div class="col-md-12 d-flex align-items-stretch">
            <div class="card w-100 overflow-hidden rounded-4">
                <div class="card-body position-relative p-4">
                    <div class="row">
                        <div class="col-12 col-sm-7">
                            <div class="d-flex align-items-center gap-3 mb-5">
                                <img src="{{ asset(auth()->user()->image) }}" class="rounded-circle bg-grd-info p-1"
                                    width="60" height="60" alt="user" style="object-fit: cover;"
                                    onerror="this.onerror=null;this.src='{{ asset(auth()->user()->getImageIfError()) }}';">
                                <div class="">
                                    <p class="mb-0 fw-semibold">
                                        Selamat Datang,
                                    </p>
                                    <h4 class="fw-semibold mb-0 fs-4">
                                        {{ auth()->user()->name }}
                                    </h4>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-5">
                                <div class="">
                                    <h4 class="mb-1 fw-semibold d-flex align-content-center"
                                        style="white-space: nowrap">
                                        {{ auth()->user()->Role->name ?? '' }}
                                        <i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                                    </h4>

                                    @if(auth()->user()->role_id === 1)
                                    <a href="{{ asset('Manual-Books/ManualBookAdmin.pdf') }}" target="_blank"
                                        class="d-flex align-items-center gap-1 mt-2" style="white-space: nowrap;">
                                        <i class="material-icons-outlined">picture_as_pdf</i>
                                        Manual Book
                                    </a>
                                    @elseif(in_array(auth()->user()->role_id, [2,4]))
                                    <a href="{{ asset('Manual-Books/ManualBookPenilai.pdf') }}" target="_blank"
                                        class="d-flex align-items-center gap-1 mt-2" style="white-space: nowrap;">
                                        <i class="material-icons-outlined">picture_as_pdf</i>
                                        Manual Book
                                    </a>
                                    @elseif(in_array(auth()->user()->role_id, [3]))
                                    <a href="{{ asset('Manual-Books/ManualBookEvaluator.pdf') }}" target="_blank"
                                        class="d-flex align-items-center gap-1 mt-2" style="white-space: nowrap;">
                                        <i class="material-icons-outlined">picture_as_pdf</i>
                                        Manual Book
                                    </a>
                                    @endif
                                </div>
                                <div class="vr"></div>
                                <div class="">
                                    <h4 class="mb-1 fw-semibold d-flex align-content-center"
                                        style="white-space: nowrap">
                                        {{ number_format($totalSkor, 2) ?? 0 }}
                                        ({{ $grade->predikat ?? 'E' }})
                                        <i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                                    </h4>
                                    <span>
                                        Hasil Evaluasi Akuntabilitas Kinerja Pemerintah Daerah Kabupaten Ogan Ilir
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-5">
                            <div class="welcome-back-img pt-4">
                                <img src="assets/images/gallery/welcome-back-3.png" height="180" alt="">
                            </div>
                        </div>
                    </div>
                    <!--end row-->
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="d-flex flex-column flex-md-row justify-content-md-between">
                <h4 class="mb-4">
                    Instansi yang dinilai
                </h4>

                <div class="">

                    <div class="position-relative">
                        <input class="form-control rounded-5 ps-5 search-control d-lg-block d-none" type="search"
                            placeholder="Pencarian...." wire:model.live='search'>
                        <span
                            class="material-icons-outlined position-absolute d-lg-block d-none ms-3 translate-middle-y start-0 top-50">search</span>
                    </div>

                </div>
            </div>
            <div class="row g-4">
                @foreach($arrInstance as $instance)
                @php
                $penilaian = collect($instance->Penilaian())->first();
                $cardBg = 'bg-grd-primary';
                if($instance->unit_id === 1) {
                $cardBg = 'bg-grd-info';
                } elseif($instance->unit_id === 2) {
                $cardBg = 'bg-grd-warning';
                } elseif($instance->unit_id === 3) {
                $cardBg = 'bg-grd-danger';
                } elseif($instance->unit_id === 4) {
                $cardBg = 'bg-grd-royal';
                }
                @endphp
                <div class="col-md-3">
                    {{-- <a
                        href="{{ $penilaian ? ($penilaian['is_submitted'] ? route('evaluasi', ['instance' => $instance->id]) : '#') : '#' }}"
                        class="card shadow-none {{ $cardBg }} mb-0" style="height: 160px;"> --}}
                        <a href="{{ route('recap', ['instance' => $instance->id]) }}"
                            class="card shadow-none {{ $cardBg }} mb-0" style="height: 160px;">
                            <div class="card-body">
                                <h6 class="mb-0 text-white" style="
                                display: -webkit-box;
                                -webkit-box-orient: vertical;
                                -webkit-line-clamp: 3; /* Limits text to 3 lines */
                                overflow: hidden;">
                                    {{ $instance->name }}
                                </h6>
                                <div class="d-flex align-items-center gap-4 mt-3">
                                    <h3 class="text-white fw-bold text-start mb-0">
                                        {{ number_format($instance->GetSkor() ?? 0, 2) }}
                                    </h3>

                                    <div class="">
                                        @php
                                        $grade = Grade::where('nilai', '>=', $totalSkor)
                                        ->orderBy('nilai', 'asc')
                                        ->first();
                                        @endphp
                                        <span class="badge border fs-4">
                                            {{ $grade->predikat ?? 'E' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="position-absolute" style="bottom: 10px; left: 10px;">
                                    @if($penilaian)
                                    @if($penilaian['is_submitted'] == true && $penilaian['is_verified'] == false)
                                    <span class="badge bg-success">
                                        Sudah Disubmit
                                    </span>
                                    @elseif($penilaian['is_submitted'] == false && $penilaian['is_verified'] == false)
                                    <span class="badge bg-warning text-muted">
                                        Belum Disubmit
                                    </span>
                                    @elseif($penilaian['is_submitted'] == true && $penilaian['is_verified'] == true)
                                    <span class="badge bg-success">
                                        Sudah Diverifikasi
                                    </span>
                                    @endif
                                    @else
                                    <span class="badge bg-danger">
                                        Belum Ada Penilaian
                                    </span>
                                    @endif
                                </div>
                                <img src="{{ asset($instance->logo) }}" class="position-absolute"
                                    style="bottom:5px; right: 5px; z-index:0" width="90" alt="{{ $instance->name }}">
                            </div>
                        </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
