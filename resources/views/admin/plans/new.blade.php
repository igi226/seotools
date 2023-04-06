@section('site_title', formatTitle([__('New'), __('Plan'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['url' => route('admin.plans'), 'title' => __('Plans')],
    ['title' => __('New')],
]])

<h1 class="h2 mb-3 d-inline-block">{{ __('New') }}</h1>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Plan') }}</div></div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('admin.plans.new') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="i-name">{{ __('Name') }}</label>
                <input type="text" name="name" id="i-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-description">{{ __('Description') }}</label>
                <input type="text" name="description" id="i-description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" value="{{ old('description') }}">
                @if ($errors->has('description'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-trial-days">{{ __('Trial days') }}</label>
                <input type="number" name="trial_days" id="i-trial-days" class="form-control{{ $errors->has('trial_days') ? ' is-invalid' : '' }}" value="{{ old('trial_days') ?? 0 }}">
                @if ($errors->has('trial_days'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('trial_days') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-currency">{{ __('Currency') }}</label>
                <select name="currency" id="i-currency" class="custom-select{{ $errors->has('currency') ? ' is-invalid' : '' }}">
                    @foreach(config('currencies.all') as $key => $value)
                        <option value="{{ $key }}" @if(old('currency') == $key) selected @endif>{{ $key }} - {{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('currency'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('currency') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row mx-n2">
                <div class="col-12 col-lg-6 px-2">
                    <div class="form-group">
                        <label for="i-amount-month">{{ __('Monthly amount') }}</label>
                        <input type="text" name="amount_month" id="i-amount-month" class="form-control{{ $errors->has('amount_month') ? ' is-invalid' : '' }}" value="{{ old('amount_month') }}">
                        @if ($errors->has('amount_month'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('amount_month') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6 px-2">
                    <div class="form-group">
                        <label for="i-amount-year">{{ __('Yearly amount') }}</label>
                        <input type="text" name="amount_year" id="i-amount-year" class="form-control{{ $errors->has('amount_year') ? ' is-invalid' : '' }}" value="{{ old('amount_year') }}">
                        @if ($errors->has('amount_year'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('amount_year') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mx-n2">
                <div class="col-12 col-lg-6 px-2">
                    <div class="form-group">
                        <label for="i-tax-rates">{{ __('Tax rates') }}</label>
                        <select name="tax_rates[]" id="i-tax-rates" class="custom-select{{ $errors->has('tax_rates') ? ' is-invalid' : '' }}" size="3" multiple>
                            @foreach($taxRates as $taxRate)
                                <option value="{{ $taxRate->id }}" @if(old('tax_rates') !== null && in_array($taxRate->id, old('tax_rates'))) selected @endif>{{ $taxRate->name }} ({{ number_format($taxRate->percentage, 2, __('.'), __(',')) }}% {{ ($taxRate->type ? __('Exclusive') : __('Inclusive')) }})</option>
                            @endforeach
                        </select>
                        @if ($errors->has('tax_rates'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('tax_rates') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6 px-2">
                    <div class="form-group">
                        <label for="i-coupons">{{ __('Coupons') }}</label>
                        <select name="coupons[]" id="i-coupons" class="custom-select{{ $errors->has('coupons') ? ' is-invalid' : '' }}" size="3" multiple>
                            @foreach($coupons as $coupon)
                                <option value="{{ $coupon->id }}" @if(old('coupons') !== null && in_array($coupon->id, old('coupons'))) selected @endif>{{ $coupon->name }} ({{ number_format($coupon->percentage, 2, __('.'), __(',')) }}% {{ ($coupon->type ? __('Redeemable') : __('Discount')) }})</option>
                            @endforeach
                        </select>
                        @if ($errors->has('coupons'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('coupons') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="i-visibility">{{ __('Visibility') }}</label>
                <select name="visibility" id="i-visibility" class="custom-select{{ $errors->has('visibility') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('Public'), 0 => __('Unlisted')] as $key => $value)
                        <option value="{{ $key }}" @if(old('visibility') == $key && old('visibility') !== null) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('visibility'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('visibility') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-position">{{ __('Position') }}</label>
                <input type="number" name="position" id="i-position" class="form-control{{ $errors->has('position') ? ' is-invalid' : '' }}" value="{{ old('position') ?? 0 }}">
                @if ($errors->has('position'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('position') }}</strong>
                    </span>
                @endif
            </div>

            <div class="hr-text"><span class="font-weight-medium text-body">{{ __('Features') }}</span></div>

            <div class="form-group">
                <label for="i-features-reports">{{ __('Reports') }}</label>
                <input type="text" name="features[reports]" id="i-features-reports" class="form-control{{ $errors->has('features.reports') ? ' is-invalid' : '' }}" value="{{ old('features.reports') }}">
                @if ($errors->has('features.reports'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('features.reports') }}</strong>
                    </span>
                @endif
                <small class="form-text text-muted">{!! __(':value for unlimited.', ['value' => '<code class="badge badge-secondary">-1</code>']) !!} {!! __(':value for none.', ['value' => '<code class="badge badge-secondary">0</code>']) !!} {!! __(':value for number.', ['value' => '<code class="badge badge-secondary">N</code>']) !!}</small>
            </div>

            <div class="form-group">
                <label for="i-features-branded-reports">{{ __('Branded reports') }}</label>
                <select name="features[branded_reports]" id="i-features-branded-reports" class="custom-select{{ $errors->has('features.branded_reports') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('On'), 0 => __('Off')] as $key => $value)
                        <option value="{{ $key }}" @if(old('features.branded_reports') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('features.branded_reports'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('features.branded_reports') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-features-white-label-reports">{{ __('White-label reports') }}</label>
                <select name="features[white_label_reports]" id="i-features-white-label-reports" class="custom-select{{ $errors->has('features.white_label_reports') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('On'), 0 => __('Off')] as $key => $value)
                        <option value="{{ $key }}" @if(old('features.white_label_reports') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('features.white_label_reports'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('features.white_label_reports') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-features-research-tools">{{ __('Research tools') }}</label>
                <select name="features[research_tools]" id="i-features-research-tools" class="custom-select{{ $errors->has('features.research_tools') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('On'), 0 => __('Off')] as $key => $value)
                        <option value="{{ $key }}" @if(old('features.research_tools') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('features.research_tools'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('features.research_tools') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-features-developer-tools">{{ __('Developer tools') }}</label>
                <select name="features[developer_tools]" id="i-features-developer-tools" class="custom-select{{ $errors->has('features.developer_tools') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('On'), 0 => __('Off')] as $key => $value)
                        <option value="{{ $key }}" @if(old('features.developer_tools') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('features.developer_tools'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('features.developer_tools') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-features-content-tools">{{ __('Content tools') }}</label>
                <select name="features[content_tools]" id="i-features-content-tools" class="custom-select{{ $errors->has('features.content_tools') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('On'), 0 => __('Off')] as $key => $value)
                        <option value="{{ $key }}" @if(old('features.content_tools') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('features.content_tools'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('features.content_tools') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-features-data-export">{{ __('Data export') }}</label>
                <select name="features[data_export]" id="i-features-data-export" class="custom-select{{ $errors->has('features.data_export') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('On'), 0 => __('Off')] as $key => $value)
                        <option value="{{ $key }}" @if(old('features.data_export') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('features.data_export'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('features.data_export') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-features-api">{{ __('API') }}</label>
                <select name="features[api]" id="i-features-api" class="custom-select{{ $errors->has('features.api') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('On'), 0 => __('Off')] as $key => $value)
                        <option value="{{ $key }}" @if(old('features.api') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('features.api'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('features.api') }}</strong>
                    </span>
                @endif
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
</div>
