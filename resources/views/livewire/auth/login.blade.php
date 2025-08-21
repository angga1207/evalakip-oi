<div>
    <div class="card rounded-4 mb-0 border-top border-4 border-primary border-gradient-1">
        <div class="card-body p-5">
            <div class="d-flex align-items-center justify-content-center mb-4 gap-4">
                <img src="{{ asset('assets/logo-oi.png') }}" class="mb-4" width="100" alt="">
                <img src="{{ asset('assets/logo.png') }}" class="mb-4" width="100" alt="">
            </div>
            <h4 class="fw-bold">
                Login
            </h4>
            <p class="mb-0">
                Masuk ke aplikasi {{ env('APP_NAME') }} menggunakan akun <span class="fw-bold">SEMESTA</span> untuk
                melanjutkan.
            </p>

            <div class="form-body my-3">
                <form class="row g-3" wire:submit.prevent="login">
                    <div class="col-12">
                        <label for="nip" class="form-label">
                            NIP
                        </label>
                        <input type="text" class="form-control" id="nip" placeholder="Masukkan NIP Semesta"
                            wire:model="username">
                        @error('username')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="inputChoosePassword" class="form-label">
                            Kata Sandi
                        </label>
                        <div class="input-group" id="show_hide_password">
                            <input type="password" class="form-control border-end-0" id="inputChoosePassword"
                                placeholder="Masukkan Kata Sandi" wire:model="password">
                            <a href="javascript:;" class="input-group-text bg-transparent">
                                <i class="bi bi-eye-slash-fill"></i>
                            </a>
                        </div>
                        @error('password')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- <div class="col-12">
                        <div class="d-flex flex-column justify-content-center rounded mb-3">
                            <div class="captcha-container rounded">
                                <div class="captcha-wrapper">
                                    <div class="d-flex flex-column flex-md-row align-items-center">
                                        <div class="captcha-image-container border rounded p-2 bg-light">
                                            {!! captcha_img() !!}
                                        </div>
                                        <button
                                            class="refresh-button btn btn-outline-primary d-flex align-items-center ms-md-3 mt-3 mt-md-0"
                                            id="reload" wire:click.prevent="reloadCaptcha()">
                                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" fill="currentColor"
                                                width="16" height="16" viewBox="0 0 30 30" class="me-2">
                                                <path
                                                    d="M 15 3 C 12.053086 3 9.3294211 4.0897803 7.2558594 5.8359375 A 1.0001 1.0001 0 1 0 8.5449219 7.3652344 C 10.27136 5.9113916 12.546914 5 15 5 C 20.226608 5 24.456683 8.9136179 24.951172 14 L 22 14 L 26 20 L 30 14 L 26.949219 14 C 26.441216 7.8348596 21.297943 3 15 3 z M 4.3007812 9 L 0.30078125 15 L 3 15 C 3 21.635519 8.3644809 27 15 27 C 17.946914 27 20.670579 25.91022 22.744141 24.164062 A 1.0001 1.0001 0 1 0 21.455078 22.634766 C 19.72864 24.088608 17.453086 25 15 25 C 9.4355191 25 5 20.564481 5 15 L 8.3007812 15 L 4.3007812 9 z">
                                                </path>
                                            </svg>
                                            Refresh
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <label for="captcha" class="form-label">
                            Captcha
                        </label>
                        <input type="text" class="form-control" id="captcha" placeholder="Captcha" wire:model='captcha'>
                        @error('captcha')
                        <div class="text-danger mt-1" style="font-size: 0.8rem;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div> --}}

                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                wire:model="remember" value="1">
                            <label class="form-check-label" for="flexSwitchCheckChecked">
                                Tetap Masuk
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">

                    </div>
                    <div class="col-12">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-grd-primary text-white">
                                Masuk
                            </button>
                        </div>
                    </div>

                    <div class="col-12">
                        <p class="mb-0 text-center">
                            © {{ date('Y') == 2025 ? 2025 : '2025 - ' . date('Y') }}.
                            Diskomifo Kabupaten Ogan Ilir.
                        </p>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
