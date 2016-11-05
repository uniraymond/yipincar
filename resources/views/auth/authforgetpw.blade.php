@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3" style="margin-bottom: 115px; margin-top: 155px">
                <div class="panel panel-red">
                    <div class="panel-heading">密码重设</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/autheditorPassReset') }}">
                            {{ csrf_field() }}

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

                                <div class="col-md-3">
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

                            <div class="form-group" style="margin-top: 25px">
                                <div class="col-md-6 col-md-offset-8">
                                    <button type="submit" class="btn btn-primary" id="submitbtn">
                                        <i class="fa fa-btn fa-user"></i> 重置密码
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
            jQuery('#submitbtn').prop('disabled', true);
            jQuery('#textReview').on('click', function(){
                var mobile = jQuery('#phone').val();
                jQuery.ajax({
                    method: 'GET',
                    dataType: 'json',
                    url: '/authsendtxtpw/'+ mobile,
                    success: function(data){
                        console.log(data);
                        console.log(data.code);
                        console.log(data['status']);
                        if(data.status == 400) {
                            jQuery('#phone').append('<span class="help-block"><strong>手机号码不存在</strong></span>');
                            alert('手机号码不存在，请验证后再试');
                            return false;
                        } else if(data.status == 100 || data.status == '100') {
                            var btn = jQuery('#textReview');
                            btn.disabled=true;
                            d(60);

                            jQuery('#message').blur(function(){
                                var code = jQuery('#message').val();
                                if (data.code != code) {
                                    alert('验证码输入错误');
                                } else {
                                    jQuery('#submitbtn').prop('disabled', false);
                                }
                                console.log(code);
                                console.log(data.code);
                                return false;
                            });
                        } else {
                            jQuery('#textReview').append('<span class="help-block"><strong>短信发送失败</strong></span>')
                        }
                    }
                })
            });
        });
        function d(i){
            console.log(i);
            var btn = jQuery('#textReview');
            i--;
            if(i==0){
                btn.val("重新发送验证码");
                btn.disabled=false;
                btn.prop('disabled', false);
            } else {
                btn.prop('disabled', true);
                btn.val("("+i+")秒之后可以重发验证码");
                setTimeout("d("+i+")",1000);
            }
        }
    </script>

@endsection
