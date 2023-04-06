@section('site_title', formatTitle([__('Text cleaner'), __('Tool'), config('settings.title')]))

@section('head_content')

@endsection

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('tools'), 'title' => __('Tools')],
    ['title' => __('Tool')],
]])

<div class="d-flex">
    <h1 class="h2 mb-3 text-break">{{ __('Text cleaner') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Text cleaner') }}</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('tools.text_cleaner') }}" method="post" enctype="multipart/form-data" @cannot('contentTools', ['App\Models\User']) class="position-absolute left-5 right-5 opacity-20" @endcannot>
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
                <label for="i-html-tags">{{ __('HTML tags') }}</label>
                <select name="html_tags" id="i-html-tags" class="custom-select{{ $errors->has('html_tags') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('All'), 0 => __('None')] as $key => $value)
                        <option value="{{ $key }}" @if ((old('html_tags') !== null && old('html_tags') == $key) || (isset($htmlTags) && $htmlTags == $key && old('html_tags') == null)) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('html_tags'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('html_tags') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-spaces">{{ __('Spaces') }}</label>
                <select name="spaces" id="i-spaces" class="custom-select{{ $errors->has('spaces') ? ' is-invalid' : '' }}">
                    @foreach([2 => __('Duplicated'), 1 => __('All'), 0 => __('None')] as $key => $value)
                        <option value="{{ $key }}" @if ((old('spaces') !== null && old('spaces') == $key) || (isset($spaces) && $spaces == $key && old('spaces') == null)) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('spaces'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('spaces') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-line-breaks">{{ __('Line breaks') }}</label>
                <select name="line_breaks" id="i-line-breaks" class="custom-select{{ $errors->has('line_breaks') ? ' is-invalid' : '' }}">
                    @foreach([2 => __('Duplicated'), 1 => __('All'), 0 => __('None')] as $key => $value)
                        <option value="{{ $key }}" @if ((old('line_breaks') !== null && old('line_breaks') == $key) || (isset($lineBreaks) && $lineBreaks == $key && old('line_breaks') == null)) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('line_breaks'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('line_breaks') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row mx-n2">
                <div class="col px-2">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Remove') }}</button>
                </div>
                <div class="col-auto px-2">
                    <a href="{{ route('tools.text_cleaner') }}" class="btn btn-outline-secondary ml-auto">{{ __('Reset') }}</a>
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
