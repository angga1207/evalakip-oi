<?php
    $auth = auth()->user();
?>
<div>
    <!--start sidebar-->
    <aside class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div class="logo-icon">
                <img src="{{ asset('assets/logo-only.png') }}" class="logo-img" alt="">
            </div>
            <div class="logo-name flex-grow-1">
                <h6 class="mb-0">
                    {{ env('APP_NAME') }}
                </h6>
            </div>
            <div class="sidebar-close">
                <span class="material-icons-outlined">close</span>
            </div>
        </div>
        <div class="sidebar-nav">
            <!--navigation-->
            <ul class="metismenu" id="sidenav">
                <li>
                    <a href="/">
                        <div class="parent-icon">
                            <i class="material-icons-outlined">home</i>
                        </div>
                        <div class="menu-title">
                            Dashboard
                        </div>
                    </a>
                </li>

                @if($auth->role_id === 1)
                <li>
                    <a href="{{ route('components.index') }}">
                        <div class="parent-icon">
                            <i class="material-icons-outlined">widgets</i>
                        </div>
                        <div class="menu-title">
                            Komponen
                        </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('criterias.index') }}">
                        <div class="parent-icon">
                            <i class="material-icons-outlined">diversity_1</i>
                        </div>
                        <div class="menu-title">
                            Kriteria
                        </div>
                    </a>
                </li>
                @endif

                @if($auth->role_id === 2)
                <li>
                    <a href="{{ route('penilaian') }}">
                        <div class="parent-icon">
                            <i class="material-icons-outlined">app_registration</i>
                        </div>
                        <div class="menu-title">
                            Penilaian
                        </div>
                    </a>
                </li>
                @endif

                {{-- @if($auth->role_id === 3)
                <li>
                    <a href="{{ route('penilaian') }}">
                        <div class="parent-icon">
                            <i class="material-icons-outlined">add_task</i>
                        </div>
                        <div class="menu-title">
                            Evaluasi
                        </div>
                    </a>
                </li>
                @endif --}}

                @if($auth->role_id === 1)
                <li class="menu-label">
                    Referensi
                </li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon">
                            <i class="material-icons-outlined">join_right</i>
                        </div>
                        <div class="menu-title">
                            Referensi
                        </div>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('instansi.index') }}">
                                <i class="material-icons-outlined">arrow_right</i>
                                Instansi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('periode.index') }}">
                                <i class="material-icons-outlined">arrow_right</i>
                                Periode
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('grades.index') }}">
                                <i class="material-icons-outlined">arrow_right</i>
                                Grade
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('answers.index') }}">
                                <i class="material-icons-outlined">arrow_right</i>
                                Jawaban
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('import') }}">
                                <i class="material-icons-outlined">arrow_right</i>
                                Import
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-label">
                    Manajemen Pengguna
                </li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon">
                            <i class="material-icons-outlined">person</i>
                        </div>
                        <div class="menu-title">
                            Pengguna
                        </div>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('users.index') }}">
                                <i class="material-icons-outlined">arrow_right</i>
                                Daftar Pengguna
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
            <!--end navigation-->
        </div>
    </aside>
    <!--end sidebar-->
</div>
