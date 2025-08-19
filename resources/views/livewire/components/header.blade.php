<div>
    <!--start header-->
    <header class="top-header">
        <nav class="navbar navbar-expand align-items-center gap-4">
            <div class="btn-toggle">
                <a href="javascript:;"><i class="material-icons-outlined">menu</i></a>
            </div>

            <div class="search-bar flex-grow-1">

            </div>

            <ul class="navbar-nav gap-1 nav-right-links align-items-center">
                <li class="nav-item dropdown">
                    <a href="javascrpt:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
                        <img src="{{ asset(auth()->user()->image) }}" style="object-fit: cover;"
                            onerror="this.onerror=null;this.src='{{ asset(auth()->user()->getImageIfError()) }}';"
                            class="rounded-circle p-1 border" width="45" height="45" alt="">
                    </a>
                    <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
                        <a class="dropdown-item  gap-2 py-2" href="javascript:;">
                            <div class="text-center">
                                <img src="{{ asset(auth()->user()->image) }}" style="object-fit: cover;"
                                    onerror="this.onerror=null;this.src='{{ asset(auth()->user()->getImageIfError()) }}';"
                                    class="rounded-circle p-1 shadow mb-3" width="90" height="90" alt="">
                                <h5 class="user-name mb-0 fw-bold text-truncate">
                                    {{ auth()->user()->name ?? '' }}
                                </h5>
                                <p>
                                    {{ auth()->user()->Role->name ?? '' }}
                                </p>
                            </div>
                        </a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('profile') }}">
                            <i class="material-icons-outlined">person_outline</i>
                            Profil
                        </a>
                        <hr class="dropdown-divider">

                        @php
                        $impersonate = app('impersonate');
                        @endphp
                        @if ($impersonate->isImpersonating())
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                            href="{{ route('impersonate.leave') }}">
                            <i class="material-icons-outlined">power_settings_new</i>
                            Kembali ke Admin
                        </a>
                        @else
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"
                            wire:click="logout">
                            <i class="material-icons-outlined">power_settings_new</i>
                            Logout
                        </a>
                        @endif
                    </div>
                </li>
            </ul>

        </nav>
    </header>
    <!--end top header-->
</div>
