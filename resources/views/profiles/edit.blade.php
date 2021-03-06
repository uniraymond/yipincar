@extends('layouts.base')
@include('layouts.mineSideBar')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 35px">
                <h1 class="page-header">编辑个人资料</h1>

                @if ($fail = Session::get('warning'))
                    <div class="col-md-12 bs-example-bg-classes" >
                        <p class="bg-danger">
                            {{ $fail }}
                        </p>
                    </div>
                @endif

                    <div class="panel-body">
                        {!! Form::open(array('url' => 'admin/profile/'.$profile->user_id, 'class' => 'form', 'method'=>'put')) !!}
                        <div class="form-group  col-lg-12 col-md-12 col-sm-12" >
                            <div>
                                {!! Form::label('name', '姓名', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('name', $profile->name, array('class'=>'col-md-6 form-control', 'placeholder' => '姓名')) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('dob', '生日', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::date('dob', $profile->dob, array('class'=>'col-md-6 form-control', 'placeholder' => '生日')) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('aboutself', '自我介绍', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::textarea('aboutself', $profile->address, array('class'=>'col-md-6 form-control', 'height'=>"20", 'height'=>'30', 'placeholder' => '自我介绍')) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('gender', '性别', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {{--<select name="gender">--}}
                                        {{--<option {{ ($profile->gender) ? 'selected="selected"' : '' }}> 男</option>--}}
                                        {{--<option {{ (!$profile->gender) ? 'selected="selected"' : '' }}>女</option>--}}
                                    {{--</select>--}}
                                    {!! Form::select('gender',['male'=>'男','female'=>'女'], $profile->gender) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('phone', '电话', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('phone', $profile->phone, array('class'=>'col-md-6 form-control', 'placeholder' => '电话')) !!}
                                </div>
                            </div>
                            <div>
                                {!! Form::label('cellphone', '手机', array('class'=>'col-md-6')) !!}
                                <div class="col-md-12">
                                    {!! Form::text('cellphone', $profile->cellphone, array('class'=>'col-md-6 form-control', 'placeholder' => '手机')) !!}
                                </div>
                            </div>

                            {!! Form::text('user_id', $user->id, array('hidden'=>'hidden')) !!}
                            {!! Form::token() !!}
                            <div class=" col-lg-12 col-md-12 col-sm-12">
                                {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                                <a class="btn btn-default btn-close" href="{{ url('/admin/profile/'.$profile->user_id) }}">取消</a>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection