<div>
    {{-- @dd($breadcrumbs) --}}
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">
            {{ $breadcrumbs[0]['name'] ?? 'Dashboard' }}
        </div>
        @if($breadcrumbs && $breadcrumbs->count() > 0)
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    @foreach($breadcrumbs->skip(1) as $breadcrumb)
                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" aria-current="page">
                        @if($loop->last === false)
                        <a href="{{ $breadcrumb['url'] ?? 'javascript:;' }}">
                            {{ $breadcrumb['name'] }}
                        </a>
                        @else
                        {{ $breadcrumb['name'] }}
                        @endif
                    </li>
                    @endforeach
                </ol>
            </nav>
        </div>
        @endif

        @if($addButton)
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ $addButton['url'] ?? 'javascript:;' }}"
                    class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="material-icons-outlined">{{ $addButton['icon'] }}</i>
                    <small>
                        {{ $addButton['name'] }}
                    </small>
                </a>
            </div>
        </div>
        @endif
    </div>

    <!--end breadcrumb-->
</div>
