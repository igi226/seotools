@section('site_title', formatTitle([__('Password Encryption'), __('Tool'), config('settings.title')]))

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
    <h1 class="h2 mb-3 text-break">{{ __('Password Encryption') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Password Encryption') }}</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('tools.processPasswordEncryption') }}" method="post"
            @cannot('contentTools', ['App\Models\User']) class="position-absolute left-5 right-5 opacity-20" @endcannot>
            @cannot('contentTools', ['App\Models\User'])
                <div class="position-absolute top-0 right-0 bottom-0 left-0 z-1 more-gradient"></div>
            @endcannot

            @csrf

            <div class="form-group">
                <label for="i-content">{{ __('Enter Password') }}</label>


                {{-- <input type="number" name="content"
                            class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}"
                            value="{{ $content ?? (old('content') ?? '') }}"> --}}
                <input type="text" name="content" id="i-min"
                    class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}"
                    value="{{ $content ?? (old('content') ?? '') }}">
                @if ($errors->has('content'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('content') }}</strong>
                    </span>
                @endif




                {{-- <textarea name="content" id="i-content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}">{{ $content ?? (old('content') ?? '') }}</textarea> --}}



            </div>

            <div class="row mx-n2">
                <div class="col px-2">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Encrypt') }}</button>
                </div>
                <div class="col-auto px-2">
                    <a href="{{ route('tools.passwordEncryption') }}"
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
                    
                    <b>Given Password:</b> {{ $result['given_password'] }} <br><hr>
                    <b>Md5:</b> {{ $result['md5'] }} <br><hr>
                    <b>Sha1:</b> {{ $result['sh1'] }} <br><hr>
                    <b>Uuencode:</b> {{ $result['uuencode'] }} <br><hr>
                    <b>Base 64:</b> {{ $result['base64_encode'] }} <br><hr>
                    <b>Hash:</b> {{ $result['hash'] }} <br><hr>
                    
                </div>
            </div>
        </div>
    </div>
@endif

@include('tools.related')
