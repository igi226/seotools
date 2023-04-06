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
                    <a href="{{ url('convert-word-to-pdf') }}">Convert Word To PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@include('shared.sidebars.user')
