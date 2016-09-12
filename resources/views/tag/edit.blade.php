@extends('layouts.base')
@include('layouts.settingSideBar')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">编辑标签</h1>
                @if ($fail = Session::get('warning'))
                    <div class=" col-lg-12 col-md-12 col-sm-12  bs-example-bg-classes" >
                        <p class="bg-danger">
                            {{ $fail }}
                        </p>
                    </div>
                @endif

                <div class="panel-body">
                    {!! Form::open(array('url' => 'admin/tag/'.$tag->id, 'class' => 'form', 'method'=>'put')) !!}
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {!! Form::label('name', '标题', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                        <div class="col-md-6">
                            {!! Form::text('name',  $tag->name , array('class'=>'form-control col-lg-12 col-md-12 col-sm-12', 'placeholder' => '标题', 'required'=>'required')) !!}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') ? '标题不能为空' : '' }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    {!! Form::label('description', '简介', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                    <div class="col-md-6">
                        {!! Form::text('description', $tag->description, array('class' => 'description input col-lg-12 col-md-12 col-sm-12 form-control', 'placeholder' => '简介')) !!}
                    </div>
                    {!! Form::submit('保存', array('class'=>'btn btn-primary pull-right')) !!}
                    {!! Form::token() !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection