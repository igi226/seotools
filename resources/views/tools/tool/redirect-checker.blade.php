@section('site_title', formatTitle([__('Redirect checker'), __('Tool'), config('settings.title')]))

@section('head_content')

@endsection

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('tools'), 'title' => __('Tools')],
    ['title' => __('Tool')],
]])

<div class="d-flex">
    <h1 class="h2 mb-3 text-break">{{ __('Redirect checker') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Redirect checker') }}</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('tools.redirect_checker') }}" method="post" enctype="multipart/form-data" @cannot('researchTools', ['App\Models\User']) class="position-absolute left-5 right-5 opacity-20" @endcannot>
            @cannot('researchTools', ['App\Models\User'])
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

            <div class="row mx-n2">
                <div class="col px-2">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Analyze') }}</button>
                </div>
                <div class="col-auto px-2">
                    <a href="{{ route('tools.redirect_checker') }}" class="btn btn-outline-secondary ml-auto">{{ __('Reset') }}</a>
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

@if(isset($results))
    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header align-items-center">
            <div class="row">
                <div class="col">
                    <div class="font-weight-medium py-1">{{ __('Result') }}</div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(empty($results))
                {{ __('No results found.') }}
            @else
                <div class="list-group list-group-flush my-n3">
                    <div class="list-group-item px-0 text-muted">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="row">
                                    <div class="col d-flex align-items-center">
                                        <div class="flex-shrink-0 width-8 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">#</div>
                                        {{ __('URL') }}
                                    </div>

                                    <div class="col-auto">
                                        {{ __('Status') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto {{ (__('lang_dir') == 'rtl' ? 'text-right' : 'text-left') }}">
                                <div class="invisible btn btn-sm btn-outline-primary">{{ __('Copy') }}</div>
                            </div>
                        </div>
                    </div>

                    @foreach($results as $result)
                        <div class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col text-truncate">
                                    <div class="row text-truncate">
                                        <div class="col d-flex text-truncate">
                                            <div class="text-truncate">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 width-8 text-dark {{ $result['http_code'] == '200' ? 'font-weight-medium' : '' }}">{{ ($loop->index + 1) }}</div>

                                                    <img src="https://icons.duckduckgo.com/ip3/{{ parse_url($result['url'], PHP_URL_HOST) }}.ico" rel="noreferrer" class="flex-shrink-0 width-4 height-4 mx-3">

                                                    <div class="text-truncate">
                                                        <a href="{{ $result['url'] }}" class="{{ $result['http_code'] == '200' ? 'text-dark font-weight-medium' : 'text-secondary' }}" dir="ltr" rel="nofollow" target="_blank">{{ $result['url'] }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto d-flex text-truncate {{ (__('lang_dir') == 'rtl' ? 'text-right' : 'text-left') }}">
                                            <span class="{{ $result['http_code'] == '200' ? 'text-success font-weight-medium' : 'text-secondary' }}">{{ $result['http_code'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="btn btn-sm btn-outline-primary" data-tooltip-copy="true" data-clipboard-copy="{{ $result['url'] }}" title="{{ __('Copy') }}" data-text-copy="{{ __('Copy') }}" data-text-copied="{{ __('Copied') }}">{{ __('Copy') }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif

@include('tools.related')
