@extends('layouts.base')
@include('layouts.mineSideBar')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">创建个人资料</h1>

                @if ($fail = Session::get('warning'))
                    <div class="col-md-12 bs-example-bg-classes" >
                        <p class="bg-danger">
                            {{ $fail }}
                        </p>
                    </div>
                @endif

                <div class="panel-body">
                    {!! Form::open(array('url' => 'admin/profile', 'class' => 'form', 'method'=>'post')) !!}
                    <div class="form-group  col-lg-12 col-md-12 col-sm-12" >
                        <div>
                            {!! Form::label('name', '姓名', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::text('name', '', array('class'=>'col-md-6 form-control', 'placeholder' => '姓名')) !!}
                            </div>
                        </div>
                        <div>
                            {!! Form::label('dob', '生日', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::date('dob', '', array('class'=>'col-md-6 form-control', 'placeholder' => '生日')) !!}
                            </div>
                        </div>
                        <div>
                            {!! Form::label('aboutself', '自我介绍', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::textarea('aboutself', '', array('class'=>'col-md-6 form-control', 'height'=>"20", 'placeholder' => '自我介绍')) !!}
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
                                {!! Form::text('phone', '', array('class'=>'col-md-6 form-control', 'placeholder' => '电话')) !!}
                            </div>
                        </div>
                        <div>
                            {!! Form::label('cellphone', '手机', array('class'=>'col-md-6')) !!}
                            <div class="col-md-12">
                                {!! Form::text('cellphone', '', array('class'=>'col-md-6 form-control', 'placeholder' => '手机')) !!}
                            </div>
                        </div>

                        {!! Form::text('user_id', $user->id, array('hidden'=>'hidden')) !!}
                        {!! Form::token() !!}
                        <div class=" col-lg-12 col-md-12 col-sm-12">
                            {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection