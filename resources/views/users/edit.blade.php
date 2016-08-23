@extends('layouts.app')

@section('content')
    @foreach($user->userrole as $ur)
        @php $userIds[] = $ur->role_id; @endphp
    @endforeach


    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">修改用户</div>
                <div class="panel-body">
                    {!! Form::open(array('url' => 'admin/user/'.$user->id, 'class' => 'form form-horizontal', 'method'=>'put')) !!}
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {!! Form::label('name', '名字', array('class'=>'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                            {!! Form::text('name', $user->name , array('class'=>'form-control', 'placeholder' => '名字', 'required'=>'required' )) !!}

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
                            {!! Form::text('email', $user->email , array('class'=>'form-control', 'placeholder' => '电子邮件', 'required'=>'required' )) !!}

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
                            <input id="password" type="password" class="form-control" name="password" placeholder="不录入密码可以保持密码不改变">
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

                    <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
                        {!! Form::label('roles', '角色', array('class'=>'col-md-4 control-label')) !!}

                        <div class="col-md-6">
                            <select multiple="multiple" name="roles[]" id="roles" class="form-control">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @if(in_array($role->id, $userIds))selected="selected"@endif >{{ $role->description }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('roles'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('roles') }}</strong>
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