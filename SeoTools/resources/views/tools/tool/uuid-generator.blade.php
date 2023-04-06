@section('site_title', formatTitle([__('UUID generator'), __('Tool'), config('settings.title')]))

@section('head_content')

@endsection

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('tools'), 'title' => __('Tools')],
    ['title' => __('Tool')],
]])

<div class="d-flex">
    <h1 class="h2 mb-3 text-break">{{ __('UUID generator') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('UUID generator') }}</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('tools.uuid_generator') }}" method="post" enctype="multipart/form-data" @cannot('developerTools', ['App\Models\User']) class="position-absolute left-5 right-5 opacity-20" @endcannot>
            @cannot('developerTools', ['App\Models\User'])
                <div class="position-absolute top-0 right-0 bottom-0 left-0 z-1 more-gradient"></div>
            @endcannot

            @csrf

            <div class="form-group">
                <label for="i-uuid-generator" class="d-inline-flex align-items-center"><span class="{{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ __('UUID') }}</span><span class="badge badge-secondary">{{ __('v4') }}</span></label>
                <div class="input-group">
                    <input type="text" name="uuid_generator" id="i-uuid-generator" class="form-control" value="{{ Str::uuid() }}">
                    <div class="input-group-append">
                        <div class="btn btn-primary" data-tooltip-copy="true" title="{{ __('Copy') }}" data-text-copy="{{ __('Copy') }}" data-text-copied="{{ __('Copied') }}" data-clipboard="true" data-clipboard-target="#i-uuid-generator">{{ __('Copy') }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Regenerate') }}</button>
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

@include('tools.related')
