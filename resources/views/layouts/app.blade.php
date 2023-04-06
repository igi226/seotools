@extends('layouts.wrapper')

@section('body')
    <body class="d-flex flex-column" @if(request()->is('reports/*') && !request()->is('reports/*/edit')) data-spy="scroll" data-target="#reports-navbar" data-offset="{{ Auth::check() ? '73' : '145' }}" @endif>
        @guest
            @if(config('settings.announcement_guest'))
                @include('shared.announcement', ['message' => config('settings.announcement_guest'), 'type' => config('settings.announcement_guest_type'), 'id' => config('settings.announcement_guest_id')])
            @endif
        @else
            @if(config('settings.announcement_user'))
                @include('shared.announcement', ['message' => config('settings.announcement_user'), 'type' => config('settings.announcement_user_type'), 'id' => config('settings.announcement_user_id')])
            @endif
        @endguest

        @include('shared.header')

        <div class="d-flex flex-column flex-fill @auth content @endauth">
            @yield('content')

            @include('shared.modals.confirmation')
            @include('shared.footer')
        </div>
        <script>
            $('#summernote').summernote({
              placeholder: 'Hello stand alone ui',
              tabsize: 2,
              height: 120,
              toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
              ]
            });
          </script>
    </body>
@endsection