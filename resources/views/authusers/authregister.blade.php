@extends('layouts.base')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">注册一品汽车通行证</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/autheditorStore') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('captcha') ? ' has-error' : ''}}">
                            <label for="captcha" class="col-md-4 control-label">
                                <img src="{{ captcha_src() }}" alt="captcha" class="captcha-img" data-refresh-config="default" >
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="captcha" />
                                @if ($errors->has('captcha'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('captcha') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 control-label">手机号码</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" name="phone" >

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                            <label for="message" class="col-md-4 control-label">短信验证码</label>

                            <div class="col-md-4">
                                <input id="message" type="message" class="form-control" name="message" >

                                @if ($errors->has('message'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <input type="button" class="btn btn-default" id="textReview" value="发送短信验证码"/>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">密码</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">确认密码</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('confirmterm') ? ' has-error' : '' }}">
                            <label for="confirmterm" class="col-md-4 control-label"></label>

                            <div class="col-md-6">
                                <label for="confirmterm">
                                    <input id="confirmterm" type="checkbox" name="confirmterm" />
                                    我已经阅读并且同意<<<a href="{{ url('termandconditions') }}" >一品汽车用户协议</a>>>
                                </label>

                                @if ($errors->has('confirmterm'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('confirmterm') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i> 注册
                                </button>
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

        jQuery('#textReview').on('click', function(){
            var mobile = jQuery('#phone').val();
           jQuery.ajax({
               method: 'POST',
               url: '/authsendtxt/'+ mobile,
               success: function(data){
                   if(data.status == 100) {

                   } else if(data.status == 400) {
                       jQuery('#phone').append('<span class="help-block"><strong>手机号码已经存在</strong></span>');
                   } else {
                       jQuery('#textReview').append('<span class="help-block"><strong>短信发送失败</strong></span>')
                   }
               }
           })
        });
    });
</script>

@endsection
