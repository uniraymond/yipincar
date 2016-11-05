@extends('layouts.base')

@section('content')
<div class="container logincontainer">
    <div class="row">
        <div class="col-md-4 col-md-offset-4"  style="margin-bottom: 185px; margin-top: 155px">
            <div class="panel panel-red">
                <div class="panel-heading">登陆</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/postauthlogin') }}">
                        {{ csrf_field() }}

{{--                        {!! var_dump($login_errors) !!}--}}
                        @if (Session::has('login_errors'))
                            <div>
                            <span class="help-block">
                                <strong>
                                    {{ Session::get('login_errors') }}
                                </strong>
                            </span>
                            </div>
                        @endif
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-3 control-label">电话</label>

                            <div class="col-md-8">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}">

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-3 control-label">密码</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('captcha') ? ' has-error' : ''}}">
                            <label for="captcha" class="col-md-5 control-label ">
                                <img src="{{ captcha_src() }}" alt="captcha" class="captcha-img" data-refresh-config="default" >
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="captcha" class="form-control" style="margin-top: 8px"/>
                                @if ($errors->has('captcha'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('captcha') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3 col-sm-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> 登录
                                </button>
                            </div>
                            <div class="col-md-6 col-sm-5">
                                <a href="{{ url('authforgetpw') }}" class="btn btn-second">
                                    <i class="fa fa-btn fa-sign-in"></i> 忘记密码
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-3">
                                <a href="{{ url('authregister') }}" class="btn btn-second">
                                    <i class="fa fa-btn fa-sign-in"></i> 注册
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
<script>
    jQuery( document ).ready(function() {
        jQuery('img.captcha-img').on('click', function () {
            var captcha = jQuery(this);
            var config = captcha.data('refresh-config');
            jQuery.ajax({
                method: 'GET',
                url: '/get_captcha/' + config,
            }).done(function (response) {
                captcha.prop('src', response);
            });
        });
    });
</script>
@endsection
