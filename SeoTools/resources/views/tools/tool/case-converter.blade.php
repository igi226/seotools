@section('site_title', formatTitle([__('Case converter'), __('Tool'), config('settings.title')]))

@section('head_content')

@endsection

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('tools'), 'title' => __('Tools')],
    ['title' => __('Tool')],
]])

<div class="d-flex">
    <h1 class="h2 mb-3 text-break">{{ __('Case converter') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Case converter') }}</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('tools.case_converter') }}" method="post" enctype="multipart/form-data" @cannot('contentTools', ['App\Models\User']) class="position-absolute left-5 right-5 opacity-20" @endcannot>
            @cannot('contentTools', ['App\Models\User'])
                <div class="position-absolute top-0 right-0 bottom-0 left-0 z-1 more-gradient"></div>
            @endcannot

            @csrf

            <div class="form-group">
                <label for="i-content">{{ __('Content') }}</label>
                <textarea name="content" id="i-content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}">{{ $content ?? (old('content') ?? '') }}</textarea>
                @if ($errors->has('content'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('content') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="i-type-sentence-case" name="type" class="custom-control-input{{ $errors->has('type') ? ' is-invalid' : '' }}" value="ucfirst" @if ((old('type') !== null && old('type') == 'ucfirst') || (isset($type) && $type == 'ucfirst' && old('type') == null)) checked @endif>
                        <label class="custom-control-label" for="i-type-sentence-case">{{ Str::ucfirst(__('Sentence case')) }}</label>
                    </div>

                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="i-type-lower-case" name="type" class="custom-control-input{{ $errors->has('type') ? ' is-invalid' : '' }}" value="lower" @if ((old('type') !== null && old('type') == 'lower') || (isset($type) && $type == 'lower' && old('type') == null)) checked @endif>
                        <label class="custom-control-label" for="i-type-lower-case">{{ Str::lower(__('Lower case')) }}</label>
                    </div>

                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="i-type-upper-case" name="type" class="custom-control-input{{ $errors->has('type') ? ' is-invalid' : '' }}" value="upper" @if ((old('type') !== null && old('type') == 'upper') || (isset($type) && $type == 'upper' && old('type') == null)) checked @endif>
                        <label class="custom-control-label" for="i-type-upper-case">{{ Str::upper(__('Upper case')) }}</label>
                    </div>

                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="i-type-capitalized-case" name="type" class="custom-control-input{{ $errors->has('type') ? ' is-invalid' : '' }}" value="title" @if ((old('type') !== null && old('type') == 'title') || (isset($type) && $type == 'title' && old('type') == null)) checked @endif>
                        <label class="custom-control-label" for="i-type-capitalized-case">{{ Str::title(__('Capitalized case')) }}</label>
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
                    <a href="{{ route('tools.case_converter') }}" class="btn btn-outline-secondary ml-auto">{{ __('Reset') }}</a>
                </div>
            </div>
        </form>

        @cannot('contentTools', ['App\Models\User'])
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
                    <textarea id="i-result-content" class="form-control" onclick="this.select();" readonly>{{ $result }}</textarea>

                    <div class="position-absolute top-0 right-0">
                        <div class="btn btn-sm btn-primary m-2" data-tooltip-copy="true" title="{{ __('Copy') }}" data-text-copy="{{ __('Copy') }}" data-text-copied="{{ __('Copied') }}" data-clipboard="true" data-clipboard-target="#i-result-content">{{ __('Copy') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@include('tools.related')
