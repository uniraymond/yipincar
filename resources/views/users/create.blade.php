@extends('layouts.base')
@include('layouts.userSideBar')
{{--@include('users.side',['usergroups'=>$usergroups])--}}
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 35px">
                <h1 class="page-header">添加用户</h1>
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
                    <div class="panel-heading">创建用户</div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'admin/user/', 'class' => 'form form-horizontal', 'method'=>'post')) !!}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            {!! Form::label('name', '名字', array('class'=>'col-md-4 control-label')) !!}
                            <div class="col-md-6">
                                {!! Form::text('name', $user && $user->name ? $user->name : '' , array('class'=>'form-control', 'placeholder' => '名字', 'required'=>'required' )) !!}

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            {!! Form::label('email', '电子邮件(用于登录)', array('class'=>'col-md-4 control-label')) !!}

                            <div class="col-md-6">
                                {!! Form::text('email', $user && $user->email ? $user->email : '' , array('class'=>'form-control', 'placeholder' => '电子邮件', 'required'=>'required' )) !!}

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">密码</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" placeholder="密码" required >
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password_confirmation" class="col-md-4 control-label">确认密码</label>

                            <div class="col-md-6">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required >

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
                            {!! Form::label('roles', '角色', array('class'=>'col-md-4 control-label')) !!}

                            <div class="col-md-6">
                                <select multiple="multiple" name="roles[]" id="roles" class="form-control">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" >{{ $role->description }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('roles'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('roles') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('status_id', '账户状态', array('class'=>'col-md-4 control-label')) !!}
                            <div class="col-md-6">
                                <select name="status_id" id="status_id" class="form-control">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" @if($user && $status->id == $user->status_id)selected="selected"@endif>{{ $status->name }}</option>
                                    @endforeach
                                </select>
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
//    jQuery( document ).ready(function() { 
//        jQuery('#email').on('click', function(){ 
//            var email = jQuery('#email').val(); 
//            jQuery.ajax({ 
//                method: 'GET', 
//                dataType: 'json', 
//                url: '/emailvalidate/'+ email, 
//                success: function(data){ 
//                    console.log(data); 
//                    console.log(data.code); 
//                    console.log(data['status']); 
//                    if(data.status == 400) { 
//                        jQuery('#phone').append('<span class="help-block"><strong>邮箱已经存在</strong></span>'); 
//                        alert('邮箱已被注册'); 
//                        return false; 
//                    } 
//                } 
//            }) 
//        }); 
//    });

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