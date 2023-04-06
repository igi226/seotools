@section('site_title', formatTitle([__('User-Agent parser'), __('Tool'), config('settings.title')]))

@section('head_content')

@endsection

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('tools'), 'title' => __('Tools')],
    ['title' => __('Tool')],
]])

<div class="d-flex">
    <h1 class="h2 mb-3 text-break">{{ __('User-Agent parser') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('User-Agent parser') }}</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('tools.user_agent_parser') }}" method="post" enctype="multipart/form-data" @cannot('developerTools', ['App\Models\User']) class="position-absolute left-5 right-5 opacity-20" @endcannot>
            @cannot('developerTools', ['App\Models\User'])
                <div class="position-absolute top-0 right-0 bottom-0 left-0 z-1 more-gradient"></div>
            @endcannot

            @csrf

            <div class="form-group">
                <label for="i-user-agent">{{ __('User-Agent') }}</label>
                <div class="input-group">
                    <input type="text" dir="ltr" name="user_agent" id="i-user-agent" class="form-control{{ $errors->has('user_agent') ? ' is-invalid' : '' }}" value="{{ $userAgent ?? (old('user_agent') ?? request()->header('User-Agent')) }}">
                    <div class="input-group-append">
                        <div class="btn btn-primary" data-tooltip-copy="true" title="{{ __('Copy') }}" data-text-copy="{{ __('Copy') }}" data-text-copied="{{ __('Copied') }}" data-clipboard="true" data-clipboard-target="#i-user-agent">{{ __('Copy') }}</div>
                    </div>
                </div>

                @if ($errors->has('user_agent'))
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $errors->first('user_agent') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row mx-n2">
                <div class="col px-2">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Parse') }}</button>
                </div>
                <div class="col-auto px-2">
                    <a href="{{ route('tools.user_agent_parser') }}" class="btn btn-outline-secondary ml-auto">{{ __('Reset') }}</a>
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
            <div class="row mx-n2">
                <div class="col-12 col-md-6 px-2">
                    <div class="form-group">
                        <label for="i-result-browser">{{ __('Browser') }}</label>
                        <input id="i-result-browser" class="form-control" type="text" value="{{ $result->browser->name ?? null }} {{ $result->browser->version->value ?? null }}" readonly>
                    </div>
                </div>

                <div class="col-12 col-md-6 px-2">
                    <div class="form-group">
                        <label for="i-result-operating-system">{{ __('Operating system') }}</label>
                        <input id="i-result-operating-system" class="form-control" type="text" value="{{ $result->os->name ?? null }} {{ $result->os->version->value ?? null }}" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="i-result-user-agent">{{ __('User-Agent') }}</label>

                <div class="position-relative">
                    <textarea id="i-result-user-agent" class="form-control" onclick="this.select();" readonly>{{ $userAgent ?? null }}</textarea>

                    <div class="position-absolute top-0 right-0">
                        <div class="btn btn-sm btn-primary m-2" data-tooltip-copy="true" title="{{ __('Copy') }}" data-text-copy="{{ __('Copy') }}" data-text-copied="{{ __('Copied') }}" data-clipboard="true" data-clipboard-target="#i-result-user-agent">{{ __('Copy') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@include('tools.related')
