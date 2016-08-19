@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">New Category</div>
                <div class="panel-body">
                    {!! Form::open(array('url' => 'admin/category', 'class' => 'form', 'method'=>'post')) !!}
                    {!! Form::label('name', '名字', array('class'=>'col-md-12')) !!}
                    {!! Form::text('name', '' , array('class'=>'name input col-md-12', 'placeholder' => '名字')) !!}
                    {!! Form::label('description', '简介', array('class'=>'col-md-12')) !!}
                    {!! Form::text('description', '', array('class' => 'description input col-md-12', 'placeholder' => '简介')) !!}
                    {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                    {!! Form::token() !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
     </div>
@endsection