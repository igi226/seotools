@extends('layouts.app')

@section('site_title', formatTitle([__('Tools'), config('settings.title')]))

@section('head_content')

@endsection

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">
        <div class="row">
            <div class="col-12">
                @if(config('settings.tools_guest') && !Auth::check())
                    <div class="text-center mt-3 mb-5">
                        <h1 class="h2 my-3 d-inline-block">{{ __('Tools') }}</h1>
                        <div class="m-auto">
                            <p class="text-muted font-weight-normal font-size-lg mb-0">{{ __('Web tools and utilities.') }}</p>
                        </div>
                    </div>
                @else
                    @include('shared.breadcrumbs', ['breadcrumbs' => [
                        ['url' => route('dashboard'), 'title' => __('Home')],
                        ['title' => __('Tools')],
                    ]])

                    <div class="d-flex align-items-end">
                        <h1 class="h2 mb-3 flex-grow-1 text-truncate">{{ __('Tools') }}</h1>
                    </div>
                @endif

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <form enctype="multipart/form-data" autocomplete="off" id="form-tools-search" onsubmit="event.preventDefault();">
                            @csrf

                            <div class="input-group input-group-lg">
                                <input type="text" name="search" class="form-control font-size-lg" autocapitalize="none" spellcheck="false" id="i-search" placeholder="{{ __('Search') }}" autofocus>
                            </div>

                            <div class="input-group-append border-left-0 d-none" data-tooltip="true" id="clear-button-container">
                                <button type="button" class="btn text-secondary bg-transparent input-group-text d-flex align-items-center" id="b-clear">
                                    @include('icons.close', ['class' => 'fill-current width-4 height-4'])
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row m-n2" id="tools">
                    @foreach(config('tools') as $tool)
                        @if(!isset($category) || isset($category) && $category != $tool['category'])
                            <div class="col-12 p-2 mt-3" data-tool-category="{{ $tool['category'] }}">
                                <div class="badge badge-{{ ($tool['category'] == 'research' ? 'danger' : ($tool['category'] == 'developer' ? 'success' : 'info')) }}">{{ __(Str::ucfirst($tool['category'])) }}</div>
                            </div>
                        @endif

                        <div class="col-12 col-md-6 col-lg-4 p-2" data-tool-title="{{ __($tool['title']) }}" data-tool-parent="{{ $tool['category'] }}">
                            <div class="card border-0 h-100 shadow-sm">
                                <div class="card-body d-flex align-items-center text-truncate">
                                    <div class="d-flex position-relative text-{{ ($tool['category'] == 'research' ? 'danger' : ($tool['category'] == 'developer' ? 'success' : 'info')) }} width-8 height-8 align-items-center justify-content-center flex-shrink-0">
                                        <div class="position-absolute bg-{{ ($tool['category'] == 'research' ? 'danger' : ($tool['category'] == 'developer' ? 'success' : 'info')) }} opacity-10 top-0 right-0 bottom-0 left-0 border-radius-lg"></div>
                                        @include('icons.' . $tool['icon'], ['class' => 'fill-current width-4 height-4'])
                                    </div>

                                    <a href="{{ route($tool['route']) }}" class="text-dark font-weight-medium stretched-link text-decoration-none text-truncate mx-3">{{ __($tool['title']) }}</a>

                                    <div class="text-muted d-flex align-items-center text-truncate {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                        @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'flex-shrink-0 width-3 height-3 fill-current mx-2'])
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php $category = $tool['category']; @endphp
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@include('shared.sidebars.user')
