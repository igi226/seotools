@section('site_title', formatTitle([__('IDN converter'), __('Tool'), config('settings.title')]))

@section('head_content')

@endsection

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('tools'), 'title' => __('Tools')],
    ['title' => __('Tool')],
]])

<div class="d-flex">
    <h1 class="h2 mb-3 text-break">{{ __('IDN converter') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('IDN converter') }}</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('tools.idn_converter') }}" method="post" enctype="multipart/form-data" @cannot('researchTools', ['App\Models\User']) class="position-absolute left-5 right-5 opacity-20" @endcannot>
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

            <div class="form-group">
                <div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="i-type-sentence-case" name="type" class="custom-control-input{{ $errors->has('type') ? ' is-invalid' : '' }}" value="punycode" @if ((old('type') !== null && old('type') == 'punycode') || (isset($type) && $type == 'punycode' && old('type') == null)) checked @endif>
                        <label class="custom-control-label" for="i-type-sentence-case">{{ __('Text to punycode') }}</label>
                    </div>

                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="i-type-text-case" name="type" class="custom-control-input{{ $errors->has('type') ? ' is-invalid' : '' }}" value="text" @if ((old('type') !== null && old('type') == 'text') || (isset($type) && $type == 'text' && old('type') == null)) checked @endif>
                        <label class="custom-control-label" for="i-type-text-case">{{ __('Punycode to text') }}</label>
                    </div>
                </div>

                @if ($errors->has('type'))
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row mx-n2">
                <div class="col px-2">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Convert') }}</button>
                </div>
                <div class="col-auto px-2">
                    <a href="{{ route('tools.idn_converter') }}" class="btn btn-outline-secondary ml-auto">{{ __('Reset') }}</a>
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
        <div class="card-body mb-n3">
            <div class="form-group">
                <label for="i-result-domain">{{ __('Domain') }}</label>
                <div class="input-group">
                    <input id="i-result-domain" class="form-control" type="text" value="{{ $result }}" readonly>
                    <div class="input-group-append">
                        <div class="btn btn-primary" data-tooltip-copy="true" title="{{ __('Copy') }}" data-text-copy="{{ __('Copy') }}" data-text-copied="{{ __('Copied') }}" data-clipboard="true" data-clipboard-target="#i-result-domain">{{ __('Copy') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@include('tools.related')
