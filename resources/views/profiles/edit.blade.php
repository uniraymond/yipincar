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
                        {!! Form::open(array('url' => 'admin/profile/'.$profile->user_id, 'class' => 'form', 'method'=>'put')) !!}
                        <div class="form-group  col-lg-12 col-md-12 col-sm-12" >
                            <div>
                                {!! Form::label('lname', '姓', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('lname', $profile->fname, array('class'=>'col-md-6', 'placeholder' => '姓')) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('fname', '名', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('fname', $profile->lname, array('class'=>'col-md-6', 'placeholder' => '名')) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('dob', '生日', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::date('dob', $profile->dob, array('class'=>'col-md-6', 'placeholder' => '生日')) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('address', '地址', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::textarea('address', $profile->address, array('class'=>'col-md-6', 'height'=>'30', 'placeholder' => '地址')) !!}
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
                                    {!! Form::text('phone', $profile->phone, array('class'=>'col-md-6', 'placeholder' => '电话')) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('cellphone', '手机', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('cellphone', $profile->cellphone, array('class'=>'col-md-6', 'placeholder' => '手机')) !!}
                                </div>
                            </div>

                            {!! Form::text('user_id', $user->id, array('hidden'=>'hidden')) !!}
                            {!! Form::token() !!}
                        {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection