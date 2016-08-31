@extends('layouts.appfull')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-11 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        编辑个人资料
                    </div>
                    @if ($fail = Session::get('warning'))
                        <div class=" col-lg-12 col-md-12 col-sm-12  bs-example-bg-classes" >
                            <p class="bg-danger">
                                {{ $fail }}
                            </p>
                        </div>
                    @endif
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'admin/profile', 'class' => 'form', 'method'=>'post')) !!}
                        <div class="form-group  col-lg-12 col-md-12 col-sm-12" >
                            <div>
                                {!! Form::label('lname', '姓', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('lname', '', array('class'=>'col-md-6', 'placeholder' => '姓')) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('fname', '名', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('fname', '', array('class'=>'col-md-6', 'placeholder' => '名')) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('dob', '生日', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::date('dob', '', array('class'=>'col-md-6', 'placeholder' => '生日')) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('address', '地址', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::textarea('address', '', array('class'=>'col-md-6', 'height'=>'30', 'placeholder' => '地址')) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('gender', '性别', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::select('gender',['male'=>'Male','female'=>'Female']) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('phone', '电话', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('phone', '', array('class'=>'col-md-6', 'placeholder' => '电话')) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('cellphone', '手机', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('cellphone', '', array('class'=>'col-md-6', 'placeholder' => '手机')) !!}
                                </div>
                            </div>
                            {!! Form::text('user_id', $user->id, array('hidden'=>'hidden')) !!}
                            {!! Form::token() !!}
                            <div class="col-md-12">
                            {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection