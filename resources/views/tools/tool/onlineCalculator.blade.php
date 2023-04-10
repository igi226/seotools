@section('site_title', formatTitle([__('Online Calculator'), __('Tool'), config('settings.title')]))

@section('head_content')

@endsection

@include('shared.breadcrumbs', [
    'breadcrumbs' => [
        ['url' => route('dashboard'), 'title' => __('Home')],
        ['url' => route('tools'), 'title' => __('Tools')],
        ['title' => __('Tool')],
    ],
])

<div class="d-flex">
    <h1 class="h2 mb-3 text-break">{{ __('Online Calculator') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Online Calculator') }}</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('tools.processOnlineCalculator') }}" method="post"
            @cannot('contentTools', ['App\Models\User']) class="position-absolute left-5 right-5 opacity-20" @endcannot>
            @cannot('contentTools', ['App\Models\User'])
                <div class="position-absolute top-0 right-0 bottom-0 left-0 z-1 more-gradient"></div>
            @endcannot

            @csrf

            <div class="form-group">
                <label for="i-content">{{ __('Content') }}</label>
                <div class="row">
                    <div class="col-md-5 m-2">
                        <input type="number" name="first_number"
                            class="form-control{{ $errors->has('first_number') ? ' is-invalid' : '' }}"
                            value="{{ $first_number ?? (old('first_number') ?? '') }}">
                        @if ($errors->has('first_number'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('first_number') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-2 m-2">
                        <select name="operation" class="form-control" value="{{ $operation ?? (old('operation') ?? '') }}">
                            <option value="+">+</option>
                            <option value="-">-</option>
                            <option value="*">*</option>
                            <option value="/">/</option>
                        </select>
                        @if ($errors->has('operation'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('operation') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-5 m-2">
                        <input type="number" name="second_number"
                            class="form-control{{ $errors->has('second_number') ? ' is-invalid' : '' }}"
                            value="{{ $second_number ?? (old('second_number') ?? '') }}">
                        @if ($errors->has('second_number'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('second_number') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                {{-- <textarea name="content" id="i-content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}">{{ $content ?? (old('content') ?? '') }}</textarea> --}}



            </div>

            <div class="row mx-n2">
                <div class="col px-2">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Calculate') }}</button>
                </div>
                <div class="col-auto px-2">
                    <a href="{{ route('tools.onlineCalculator') }}"
                        class="btn btn-outline-secondary ml-auto">{{ __('Reset') }}</a>
                </div>
            </div>
        </form>

        @cannot('contentTools', ['App\Models\User'])
            @if (paymentProcessors())
                @include('shared.features.locked')
            @else
                @include('shared.features.unavailable')
            @endif
        @endcannot
    </div>
</div>

@if (isset($result))
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
                        <div class="btn btn-sm btn-primary m-2" data-tooltip-copy="true" title="{{ __('Copy') }}"
                            data-text-copy="{{ __('Copy') }}" data-text-copied="{{ __('Copied') }}"
                            data-clipboard="true" data-clipboard-target="#i-result-content">{{ __('Copy') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@include('tools.related')
