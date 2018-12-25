@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-8">
            <div class="card grey-thick-border p-5 login-card">
                {{--<div class="card-header">{{ __('Login') }}</div>--}}

                <div class="card-body bg-white">
                    <h1 class="uppercase mb-2">so glad you are here</h1>
                    <p class="mt-1 mb-5">
                        Please login with the provided user name and password to share your raster graphics with us.
                    </p>
                    <form method="POST" action="{{ route('login') }}" class="login-form">
                       @csrf

                        <div class="form-group row">
                            {{--<label for="username" class="col-sm-4 col-form-label text-md-right">{{ __('Username') }}</label>--}}

                            <div class="col-md-12">
                                <input id="username" type="text"
                                       class="{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                       name="username" value="{{ old('username') }}" required
                                       placeholder="User name *" autofocus>

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
{{--                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

                            <div class="col-md-12">
                                <input id="password" type="password"
                                       class="{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password" placeholder="Password *" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{--<div class="form-group row">--}}
                            {{--<div class="col-md-6 offset-md-4">--}}
                                {{--<div class="form-check">--}}
                                    {{--<input class="form-check-input" type="checkbox" n--}}
                                           {{--ame="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

                                    {{--<label class="form-check-label" for="remember">--}}
                                        {{--{{ __('Remember Me') }}--}}
                                    {{--</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="form-group row mb-0">
                            <div class="col-md-12">

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link mt-2 mb-4" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif



                            </div>
                        </div>
                        <div class="form-group row text-center">
                            <button type="submit" class="btn btn-outline-dark mx-auto lg-btn-padding">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
