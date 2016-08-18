@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-11 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        新建类别
                    </div>
                    @if ($fail = Session::get('warning'))
                        <div class=" col-lg-12 col-md-12 col-sm-12  bs-example-bg-classes" >
                            <p class="bg-danger">
                                {{ $fail }}
                            </p>
                        </div>
                    @endif

                    <div class="panel-body">
                        {!! Form::open(array('url' => 'admin/category', 'class' => 'form', 'method'=>'post')) !!}
                        {!! Form::label('name', '标题', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                        {!! Form::text('name', '' , array('class'=>'name input col-lg-12 col-md-12 col-sm-12', 'placeholder' => '标题', 'required')) !!}
                        {!! Form::label('description', '简介', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                        {!! Form::text('description', '', array('class' => 'description input col-lg-12 col-md-12 col-sm-12', 'placeholder' => '简介')) !!}
                        <div>
                            <label class="col-lg-12 col-md-12 col-sm-12">选择上一级类别</label>
                            <select class="col-lg-12 col-md-12 col-sm-12" name="category_id">
                                <option selected value="0">选择一个类别</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        {!! Form::submit('保存', array('class'=>'btn btn-primary pull-right')) !!}
                        {!! Form::token() !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection