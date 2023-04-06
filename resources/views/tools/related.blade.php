<h4 class="mt-5">{{ __('Related') }}</h4>

<div class="row m-n2">
    @foreach(collect(array_merge(config('tools'), config('tools')))->where('category', '=', collect(config('tools'))->where('route', '=', 'tools.' . str_replace('-', '_', request()->segment(2)))->value('category'))->filter(function ($value, $key) { return $key > collect(config('tools'))->where('route', 'tools.' . str_replace('-', '_', request()->segment(2)))->keys()[0]; })->splice(0, 6) as $tool)
        <div class="col-12 col-md-6 col-lg-4 p-2">
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
    @endforeach
</div>
