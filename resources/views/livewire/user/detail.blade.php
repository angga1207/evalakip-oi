<div>
    <div class="card rounded-4 border-top border-4 border-primary border-gradient-1">
        <div class="card-body p-4">
            <form class="row g-4" wire:submit.prevent="save">
                <div class="col-md-12">
                    <label for="input1" class="form-label">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="input1" placeholder="Nama Lengkap"
                        wire:model="user.name">
                    @error('user.name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="input2" class="form-label">
                        Username <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="input2" placeholder="Username"
                        wire:model="user.username">
                    @error('user.username')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="input3" class="form-label">
                        Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" class="form-control" id="input3" placeholder="Email" wire:model="user.email">
                    @error('user.email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">
                        Jenis Pengguna <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" wire:model.live="user.role_id">
                        <option selected="" hidden>Pilih Jenis Pengguna</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('user.role_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="input5" class="form-label">
                        Instansi <span class="text-danger">*</span>
                    </label>
                    <select id="input5" class="form-select" wire:model="user.instance_id">
                        <option selected="" hidden>Pilih Instansi</option>
                        @foreach($instances as $instance)
                        <option value="{{ $instance->id }}">{{ $instance->name }}</option>
                        @endforeach
                    </select>
                    @error('user.instance_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @if($user['role_id'] == 3)
                <div class="col-md-12">
                    <label for="selectInstances" class="form-label">
                        Evaluator Instansi <span class="text-danger">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="selectInstances" class="form-select" wire:model="selectedInstances" multiple x-init="
                            $($el).select2({
                                theme: 'bootstrap-5',
                                width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                                placeholder: $( this ).data( 'placeholder' ),
                                closeOnSelect: false,
                                allowClear: true
                            });

                            $($el).on('change', function (e) {
                                @this.set('selectedInstances', $(this).val());
                            });
                        ">
                            <option selected="" hidden>Pilih Instansi</option>
                            @foreach($instances as $instance)
                            <option value="{{ $instance->id }}">{{ $instance->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('selectedInstances')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                @if($isCreate)
                <div class="col-md-6">
                    <label for="input5" class="form-label">
                        Kata Sandi
                        @if($isCreate)
                        <span class="text-danger">*</span>
                        @endif
                    </label>
                    <input type="password" class="form-control" id="input5" autocomplete="new-password"
                        placeholder="Kata Sandi" wire:model="user.password">
                    @error('user.password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="input6" class="form-label">
                        Konfirmasi Kata Sandi
                        @if($isCreate)
                        <span class="text-danger">*</span>
                        @endif
                    </label>
                    <input type="password" class="form-control" id="input6" autocomplete="new-password"
                        placeholder="Konfirmasi Kata Sandi" wire:model="user.password_confirmation">
                    @error('user.password_confirmation')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @endif
                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center justify-content-end gap-3">
                        <button type="submit" class="btn btn-grd-primary text-white px-4">
                            @if($isCreate)
                            Simpan
                            @else
                            Perbarui
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    @endpush

    @push('scripts')
    <script src="{{ asset('assets/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#selectInstances').select2({
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: false,
        });
    </script>
    @endpush
</div>
