@section('site_title', formatTitle([__('UTM builder'), __('Tool'), config('settings.title')]))

@section('head_content')

@endsection

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('tools'), 'title' => __('Tools')],
    ['title' => __('Tool')],
]])

<div class="d-flex">
    <h1 class="h2 mb-3 text-break">{{ __('UTM builder') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('UTM builder') }}</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('tools.utm_builder') }}" method="post" enctype="multipart/form-data" @cannot('developerTools', ['App\Models\User']) class="position-absolute left-5 right-5 opacity-20" @endcannot>
            @cannot('developerTools', ['App\Models\User'])
                <div class="position-absolute top-0 right-0 bottom-0 left-0 z-1 more-gradient"></div>
            @endcannot

            @csrf

            <div class="form-group">
                <label for="i-url">{{ __('URL') }}</label>
                <input type="text" dir="ltr" name="url" id="i-url" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" value="{{ old('url') ?? ($url ?? null) }}">
                @if ($errors->has('url'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('url') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-source">{{ __('Source') }}</label>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><code class="code">utm_source</code></span>
                    </div>
                    <input type="text" name="source" id="i-source" class="form-control{{ $errors->has('source') ? ' is-invalid' : '' }}" value="{{ old('source') ?? ($source ?? null) }}">
                    @if ($errors->has('source'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('source') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="i-medium">{{ __('Medium') }}</label>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><code class="code">utm_medium</code></span>
                    </div>
                    <input type="text" name="medium" id="i-medium" class="form-control{{ $errors->has('medium') ? ' is-invalid' : '' }}" value="{{ old('medium') ?? ($medium ?? null) }}">
                    @if ($errors->has('medium'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('medium') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="i-campaign">{{ __('Campaign') }}</label>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><code class="code">utm_campaign</code></span>
                    </div>
                    <input type="text" name="campaign" id="i-campaign" class="form-control{{ $errors->has('campaign') ? ' is-invalid' : '' }}" value="{{ old('campaign') ?? ($campaign ?? null) }}">
                    @if ($errors->has('campaign'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('campaign') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="i-term">{{ __('Term') }}</label>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><code class="code">utm_term</code></span>
                    </div>
                    <input type="text" name="term" id="i-term" class="form-control{{ $errors->has('term') ? ' is-invalid' : '' }}" value="{{ old('term') ?? ($term ?? null) }}">
                    @if ($errors->has('term'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('term') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="i-content">{{ __('Content') }}</label>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><code class="code">utm_content</code></span>
                    </div>
                    <input type="text" name="content" id="i-content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" value="{{ old('content') ?? ($content ?? null) }}">
                    @if ($errors->has('content'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('content') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="row mx-n2">
                <div class="col px-2">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Generate') }}</button>
                </div>
                <div class="col-auto px-2">
                    <a href="{{ route('tools.utm_builder') }}" class="btn btn-outline-secondary ml-auto">{{ __('Reset') }}</a>
                </div>
            </div>
        </form>

        @cannot('developerTools', ['App\Models\User'])
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
                <label for="i-result-content">{{ __('Content') }}</label>

                <div class="position-relative">
                    <textarea name="result-content" id="i-result-content" class="form-control" onclick="this.select();" readonly>{{ $result }}</textarea>

                    <div class="position-absolute top-0 right-0">
                        <div class="btn btn-sm btn-primary m-2" data-tooltip-copy="true" title="{{ __('Copy') }}" data-text-copy="{{ __('Copy') }}" data-text-copied="{{ __('Copied') }}" data-clipboard="true" data-clipboard-target="#i-result-content">{{ __('Copy') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@include('tools.related')
