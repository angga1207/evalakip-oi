<div>
    <div class="card rounded-4 mb-0 border-top border-4 border-primary border-gradient-1">
        <div class="card-body p-5">
            {{-- <img src="{{ asset('assets/images/logo1.png') }}" class="mb-4" width="145" alt=""> --}}
            <h2 class="mb-4 text-center">
                {{ env('APP_NAME') }}
            </h2>
            <h4 class="fw-bold">
                Login
            </h4>
            <p class="mb-0">
                Masuk ke aplikasi {{ env('APP_NAME') }} menggunakan akun <span class="fw-bold">SEMESTA</span> untuk
                melanjutkan.
            </p>

            <div class="form-body my-5">
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
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" wire:model="remember" value="1">
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
                </form>
            </div>

        </div>
    </div>
</div>
