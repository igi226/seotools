<div class="position-relative width-4 height-4 fill-current flex-shrink-0 d-flex align-items-center justify-content-center {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
    @if($report['results'][$name]['passed'])
        @include('icons.checkmark', ['class' => 'width-4 height-4 fill-current flex-shrink-0 text-success'])
    @else
        @include('icons.' . ($report['results'][$name]['importance'] == 'high' ? 'triangle' : ($report['results'][$name]['importance'] == 'medium' ? 'square' : 'circle')), ['class' => 'width-4 height-4 fill-current flex-shrink-0 ' . ($report['results'][$name]['importance'] == 'high' ? 'text-danger' : ($report['results'][$name]['importance'] == 'medium' ? 'text-warning' : 'text-secondary'))])
    @endif
</div>
