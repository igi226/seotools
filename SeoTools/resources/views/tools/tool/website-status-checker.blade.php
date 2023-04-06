@section('site_title', formatTitle([__('Website status checker'), __('Tool'), config('settings.title')]))

@section('head_content')

@endsection

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('tools'), 'title' => __('Tools')],
    ['title' => __('Tool')],
]])

<div class="d-flex">
    <h1 class="h2 mb-3 text-break">{{ __('Website status checker') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Website status checker') }}</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('tools.website_status_checker') }}" method="post" enctype="multipart/form-data" @cannot('researchTools', ['App\Models\User']) class="position-absolute left-5 right-5 opacity-20" @endcannot>
            @cannot('researchTools', ['App\Models\User'])
                <div class="position-absolute top-0 right-0 bottom-0 left-0 z-1 more-gradient"></div>
            @endcannot

            @csrf

            <div class="form-group">
                <label for="i-domain">{{ __('Domain') }}</label>
                <input type="text" dir="ltr" name="domain" id="i-domain" class="form-control{{ $errors->has('domain') ? ' is-invalid' : '' }}" value="{{ $domain ?? (old('domain') ?? '') }}">

                @if ($errors->has('domain'))
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $errors->first('domain') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row mx-n2">
                <div class="col px-2">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Search') }}</button>
                </div>
                <div class="col-auto px-2">
                    <a href="{{ route('tools.website_status_checker') }}" class="btn btn-outline-secondary ml-auto">{{ __('Reset') }}</a>
                </div>
            </div>
        </form>

        @cannot('researchTools', ['App\Models\User'])
            @if(paymentProcessors())
                @include('shared.features.locked')
            @else
                @include('shared.features.unavailable')
            @endif
        @endcannot
    </div>
</div>

@if(isset($result))
    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header align-items-center">
            <div class="row">
                <div class="col">
                    <div class="font-weight-medium py-1">{{ __('Result') }}</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="list-group list-group-flush my-n3">
                <div class="list-group-item px-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-4 text-truncate text-muted">{{ __('URL') }}</div>
                        <div class="col-12 col-lg-8 text-truncate d-flex align-items-center">
                            <img src="https://icons.duckduckgo.com/ip3/{{ $domain }}.ico" rel="noreferrer" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">

                            <span dir="ltr">{{ $domain }}</span>
                        </div>
                    </div>
                </div>

                <div class="list-group-item px-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-4 text-truncate text-muted">{{ __('Status') }}</div>
                        <div class="col-12 col-lg-8 text-truncate d-flex align-items-center">
                            @if($result)
                                <div class="bg-success width-4 height-4 rounded-circle {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"></div>

                                <div class="text-truncate">{{ __('Online') }}</div>
                            @else
                                <div class="bg-danger width-4 height-4 rounded-circle {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"></div>

                                <div class="text-truncate">{{ __('Offline') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="list-group-item px-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-4 text-truncate text-muted">{{ __('Code') }}</div>
                        <div class="col-12 col-lg-8 text-truncate d-flex align-items-center">{{ $result->getStatusCode() }}</div>
                    </div>
                </div>

                <div class="list-group-item px-0">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-4 text-break text-muted">{{ __('Load time') }}</div>
                        <div class="col-12 col-lg-8 text-break">{{ __(':value seconds', ['value' => number_format($stats['total_time'] ?? 0, 2, __('.'), __(','))]) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@include('tools.related')
