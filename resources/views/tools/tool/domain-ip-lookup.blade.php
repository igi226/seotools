@section('site_title', formatTitle([__('Domain IP lookup'), __('Tool'), config('settings.title')]))

@section('head_content')

@endsection

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('tools'), 'title' => __('Tools')],
    ['title' => __('Tool')],
]])

<div class="d-flex">
    <h1 class="h2 mb-3 text-break">{{ __('Domain IP lookup') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Domain IP lookup') }}</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('tools.domain_ip_lookup') }}" method="post" enctype="multipart/form-data" @cannot('researchTools', ['App\Models\User']) class="position-absolute left-5 right-5 opacity-20" @endcannot>
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
                    <a href="{{ route('tools.domain_ip_lookup') }}" class="btn btn-outline-secondary ml-auto">{{ __('Reset') }}</a>
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

@if(!empty($result))
    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header align-items-center">
            <div class="row">
                <div class="col">
                    <div class="font-weight-medium py-1">{{ __('Result') }}</div>
                </div>
            </div>
        </div>
        <div class="card-body mb-n3">
            <div class="row mx-n2">
                <div class="col-12 px-2">
                    <div class="form-group">
                        <label for="i-result-ip">{{ __('IP') }}</label>
                        <div class="input-group">
                            <input id="i-result-ip" class="form-control" type="text" value="{{ __($result['traits']['ip_address'] ?? 'Unknown') }}" readonly>
                            <div class="input-group-append">
                                <div class="btn btn-primary" data-tooltip-copy="true" title="{{ __('Copy') }}" data-text-copy="{{ __('Copy') }}" data-text-copied="{{ __('Copied') }}" data-clipboard="true" data-clipboard-target="#i-result-ip">{{ __('Copy') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 px-2">
                    <div class="form-group">
                        <label for="i-result-country">{{ __('Country') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <img src="{{ asset('/images/icons/countries/'. mb_strtolower($result['country']['iso_code'] ?? 'unknown')) }}.svg" class="width-4 height-4">
                                </div>
                            </div>
                            <input id="i-result-country" class="form-control" type="text" value="{{ __($result['country']['names']['en'] ?? 'Unknown') }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 px-2">
                    <div class="form-group">
                        <label for="i-result-city">{{ __('City') }}</label>
                        <input id="i-result-city" class="form-control" type="text" value="{{ __($result['city']['names']['en'] ?? 'Unknown') }}" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-4 px-2">
                    <div class="form-group">
                        <label for="i-result-postal-code">{{ __('Postal code') }}</label>
                        <input id="i-result-postal-code" class="form-control" type="text" value="{{ __($result['postal']['code'] ?? 'Unknown') }}" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-4 px-2">
                    <div class="form-group">
                        <label for="i-result-latitude">{{ __('Latitude') }}</label>
                        <input id="i-result-latitude" class="form-control" type="text" value="{{ __($result['location']['latitude'] ?? 'Unknown') }}" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-4 px-2">
                    <div class="form-group">
                        <label for="i-result-longitude">{{ __('Longtitude') }}</label>
                        <input id="i-result-longitude" class="form-control" type="text" value="{{ __($result['location']['longitude'] ?? 'Unknown') }}" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-4 px-2">
                    <div class="form-group">
                        <label for="i-result-timezone">{{ __('Timezone') }}</label>
                        <input id="i-result-timezone" class="form-control" type="text" value="{{ __($result['location']['time_zone'] ?? 'Unknown') }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@include('tools.related')
