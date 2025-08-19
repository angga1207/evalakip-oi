<?php

?>
<div>
    <div wire:init='_FetchSemestaUsers'></div>
    <div class="row" style="--bs-gutter-x: 0.5rem;">
        <div class="col-12 col-lg-4 d-flex">
            <div class="card w-100">
                <div class="card-body">
                    <div class="position-relative">
                        <img src="{{ asset('assets/images/gallery/18.png') }}" class="img-fluid rounded" alt="">
                        <div class="position-absolute top-100 start-50 translate-middle">
                            <img src="{{ asset($instance['logo']) }}" width="100" height="100"
                                class="rounded-circle raised p-1 bg-white" alt="">
                        </div>
                    </div>
                    <div class="text-center mt-5 pt-4">
                        <h4 class="mb-1">
                            {{ $instance['name'] }}
                        </h4>
                        <div class="mt-4">
                            <select class="form-select" wire:model.live="instance.unit_id">
                                <option value="" selected hidden>Pilih Unit</option>
                                @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                            @error('instance.unit_id">')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="d-flex align-items-center justify-content-around mt-4">
                        <div class="d-flex flex-column gap-2">
                            <p class="mb-0">
                                Nilai
                            </p>
                            <h4 class="mb-0">
                                10
                            </h4>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <h4 class="mb-0">48K</h4>
                            <p class="mb-0">Following</p>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <h4 class="mb-0">24.3M</h4>
                            <p class="mb-0">Followers</p>
                        </div>
                    </div> --}}

                </div>
                <ul class="list-group list-group-flush d-none">
                    <li class="list-group-item border-top">
                        <b>Address</b>
                        <br>
                        123 Street Name, City, Australia
                    </li>
                    <li class="list-group-item">
                        <b>Email</b>
                        <br>
                        mail.com
                    </li>
                    <li class="list-group-item">
                        <b>Phone</b>
                        <br>
                        Toll Free (123) 472-796
                        <br>
                        Mobile : +91-9910XXXX
                    </li>
                </ul>


            </div>
        </div>

        <div class="col-12 col-lg-8 d-flex">
            <div class="card w-100">
                <div x-data="{ showTab : 'primaryhome' }" class="card-body">
                    <ul wire:ignore class="nav nav-tabs nav-primary" role="tablist">
                        <li class="nav-item flex-grow-1" role="presentation">
                            <a class="nav-link" href="#" :class="showTab == 'primaryhome' ? 'active' : ''"
                                @click.prevent="showTab = 'primaryhome'">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="tab-icon"><i class="bi bi-people me-1 fs-6"></i>
                                    </div>
                                    <div class="tab-title">
                                        Pengguna Terpilih
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item flex-grow-1" role="presentation">
                            <a class="nav-link" href="#" :class="showTab == 'primaryprofile' ? 'active' : ''"
                                @click.prevent="showTab = 'primaryprofile'">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="tab-icon"><i class="bi bi-people-fill me-1 fs-6"></i>
                                    </div>
                                    <div class="tab-title">
                                        Daftar Pengguna Semesta
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div wire:ignore.self class="tab-content p-3 border border-top-0">

                        <div class="" x-show="showTab === 'primaryhome'">
                            <h5 class="mb-3">
                                Daftar Pengguna Terpilih
                            </h5>

                            <div class="product-table">
                                <div class="table-responsive white-space-nowrap">
                                    <table class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="40%">
                                                    Pengguna
                                                </th>
                                                <th class="text-start">
                                                    Jabatan
                                                </th>
                                                <th class="text-center">
                                                    Jenis Pengguna
                                                </th>
                                                <th class="text-center">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse($users as $user)
                                            <tr>
                                                <td>
                                                    <div wire:ignore class="d-flex">
                                                        <img src="{{ asset($user['image']) }}" width="40" height="40"
                                                            class="rounded-circle raised bg-white"
                                                            style="object-fit: cover;" alt="{{ $user['name'] }}"
                                                            onerror="this.onerror=null;this.src='{{ asset($user->getImageIfError()) }}';">

                                                        <div class="flex-grow-1 ms-2">
                                                            <h6 class="mb-0">
                                                                {{ $user['name'] }}
                                                            </h6>
                                                            <p class="mb-0 text-muted">
                                                                {{ $user['username'] }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $user['jabatan'] ?? '-' }}
                                                </td>
                                                <th class="text-center">
                                                    {{ $user->Role->name ?? '' }}
                                                    </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($instance['id'] == 28)
                                                        <a href="{{ route('users.edit', $user['id']) }}"
                                                            class="btn btn-outline-primary px-2 btn-sm d-flex gap-1"
                                                            style="white-space: nowrap;">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        @endif
                                                        <button type="button"
                                                            class="btn btn-outline-danger px-2 btn-sm d-flex gap-1 ms-2"
                                                            wire:click.prevent="unlinkUser({{ $user['id'] }})"
                                                            style="white-space: nowrap;">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="100" class="text-center">
                                                    <div class="text-muted">
                                                        Tidak ada pengguna terpilih.
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="" x-show="showTab === 'primaryprofile'">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h5 class="">
                                        Daftar Pengguna Semesta
                                    </h5>
                                    <div class="">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari pengguna..."
                                                wire:model.live="searchSemestaUser">
                                            <button class="btn btn-primary" type="button"
                                                wire:click.prevent="_FetchSemestaUsers">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-table">
                                    <div class="table-responsive white-space-nowrap"
                                        style="height: calc(100vh - 200px); overflow-y: auto;">
                                        <table class="table align-middle">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th>
                                                        Pengguna
                                                    </th>
                                                    <th>
                                                        Jabatan
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @forelse($semestaUsers as $semUser)
                                                <tr>
                                                    <td>
                                                        <div wire:ignore class="d-flex">
                                                            @php
                                                            $defaultImg = 'https://ui-avatars.com/api/?name=' .
                                                            urlencode($semUser['nama_lengkap'] ?? 'User') .
                                                            '&size=60'
                                                            @endphp
                                                            <img src="{{ asset($semUser['foto_pegawai']) }}" width="40"
                                                                height="40" class="rounded-circle raised bg-white"
                                                                style="object-fit: cover;"
                                                                alt="{{ $semUser['nama_lengkap'] }}"
                                                                onerror="this.onerror=null;this.src='{{ asset($defaultImg) }}';">
                                                            <div class="flex-grow-1 ms-2">
                                                                <h6 class="mb-0">
                                                                    {{ $semUser['nama_lengkap'] }}
                                                                </h6>
                                                                <p class="mb-0 text-muted">
                                                                    {{ $semUser['nip'] }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ $semUser['jabatan'] ?? '-' }}
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-outline-primary px-1 btn-sm d-flex gap-1"
                                                            wire:click.prevent="linkUser({{ $semUser['id'] }})"
                                                            style="white-space: nowrap;">
                                                            <i class="bi bi-link"></i>
                                                            Tautkan
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="100" class="text-center">
                                                        <div class="text-muted">
                                                            Tidak ada pengguna semesta.
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
