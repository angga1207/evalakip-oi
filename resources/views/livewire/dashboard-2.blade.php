<?php

use Carbon\Carbon;

?>
<div>

    <div class="row">
        <div class="col-xxl-12 d-flex align-items-stretch">
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
                                    <h4 class="mb-1 fw-semibold d-flex align-content-center">
                                        {{ auth()->user()->Instance->name ?? 'Instance' }}
                                        <i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                                    </h4>
                                    {{-- <p class="mb-3">Today's Sales</p>
                                    <div class="progress mb-0" style="height:5px;">
                                        <div class="progress-bar bg-grd-success" role="progressbar" style="width: 60%"
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div> --}}

                                    <div class="">
                                        @if($isSubmitted)
                                        <div class="badge bg-grd-success">
                                            <i class="ti ti-check"></i>
                                            Form Penilaian sudah disubmit
                                        </div>
                                        <div>
                                            <small class="">
                                                {{ Carbon::parse($submittedAt)->isoFormat('dddd, D MMMM Y : HH:mm
                                                [WIB]') }}
                                            </small>
                                        </div>
                                        @else
                                        <div class="badge bg-grd-danger">
                                            <i class="ti ti-x"></i>
                                            Form Penilaian belum disubmit
                                        </div>
                                        @endif
                                    </div>

                                    <div>
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
                                </div>
                                <div class="vr"></div>
                                <div class="">
                                    <h4 class="mb-1 fw-semibold" style="white-space: nowrap;">
                                        {{ number_format($skor, 2) }} ({{ $grade->predikat ?? 'E' }})
                                    </h4>
                                    <p class="mb-3" style="white-space: nowrap;">
                                        Skor Penilaian
                                    </p>
                                    <div class="progress mb-0" style="height:5px;">
                                        <div class="progress-bar bg-grd-success" role="progressbar"
                                            style="width: {{ $skor }}%" aria-valuenow="25" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>

                                    @if(auth()->user()->role_id === 2)
                                    <a href="{{ route('penilaian') }}"
                                        class="btn btn-sm btn-grd-royal mt-3 d-flex align-items-center gap-2 text-white">
                                        <i class="material-icons-outlined">app_registration</i>
                                        Penilaian
                                    </a>
                                    @endif
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

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4>
                        Daftar Grade
                    </h4>
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" width="15%">
                                    Grade
                                </th>
                                <th scope="col" class="text-center" width="15%">
                                    Nilai
                                </th>
                                <th scope="col">
                                    Penjelasan
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listGrade as $grd)
                            <tr>
                                <th class="text-center">
                                    {{ $grd->predikat }}
                                </th>
                                <td class="text-center">
                                    {{ $grd->nilai }}
                                </td>
                                <td>{{ $grd->keterangan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">

            <div class="card">
                <div class="card-body">
                    <h4>
                        Penilai
                    </h4>
                    <table class="table mb-0 align-middle">
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <th>
                                    <div class="d-flex align-items-center">
                                        <img wire:ignore src="{{ asset($user['image']) }}" width="40" height="40"
                                            class="rounded-circle raised bg-white p-1" style="object-fit: cover;"
                                            alt="{{ $user['name'] }}"
                                            onerror="this.onerror=null;this.src='{{ asset($user->getImageIfError()) }}';">
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0">
                                                {{ $user['name'] }}
                                            </h6>
                                        </div>
                                    </div>
                                </th>
                                <th>
                                    {{ $user->Role->name ?? '' }}
                                </th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4>
                        Evaluator
                    </h4>
                    <table class="table mb-0 align-middle">
                        <tbody>
                            @foreach($evaluators as $evaluator)
                            <tr>
                                <th>
                                    <div class="d-flex align-items-center">
                                        <img wire:ignore src="{{ asset($evaluator['image']) }}" width="40" height="40"
                                            class="rounded-circle raised bg-white p-1" style="object-fit: cover;"
                                            alt="{{ $evaluator['name'] }}"
                                            onerror="this.onerror=null;this.src='{{ asset($evaluator->getImageIfError()) }}';">
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0">
                                                {{ $evaluator['name'] }}
                                            </h6>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>
