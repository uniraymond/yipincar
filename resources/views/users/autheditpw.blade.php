@extends('layouts.base')
@include('layouts.mineSideBar')
{{--@include('users.side',['usergroups'=>$usergroups])--}}
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">修改密码</h1>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="panel panel-default">
                    <div class="panel-heading">修改密码</div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'admin/user/auth', 'class' => 'form form-horizontal', 'method'=>'post')) !!}

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">原密码</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="oldpassword" placeholder="原密码" required >
                                @if ($errors->has('oldpassword'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('oldpassword') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">新密码</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" placeholder="新密码" required >
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password_confirmation" class="col-md-4 control-label">确认新密码</label>

                            <div class="col-md-6">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required >

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group{{ $errors->has('captcha') ? ' has-error' : ''}}">
                            <label for="captcha" class="col-md-4 control-label">
                                <img src="{{ captcha_src() }}" alt="captcha" class="captcha-img" data-refresh-config="default" />
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

                        <div class="clearfix"></div>
                        {!! Form::submit('保存', array('class'=>'btn btn-primary col-lg-offset-10 col-md-offset-10 col-sm-offset-10')) !!}

                        {!! Form::token() !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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