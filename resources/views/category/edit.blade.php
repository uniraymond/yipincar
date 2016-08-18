@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-11 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        编辑类别
                    </div>
                    @if ($fail = Session::get('warning'))
                        <div class=" col-lg-12 col-md-12 col-sm-12  bs-example-bg-classes" >
                            <p class="bg-danger">
                                {{ $fail }}
                            </p>
                        </div>
                    @endif
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'admin/category/'.$category->id, 'class' => 'form', 'method'=>'put')) !!}
                        {!! Form::label('name', '标题', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                        {!! Form::text('name', $category->name, array('class'=>'name col-lg-12 col-md-12 col-sm-12', 'placeholder' => '标题')) !!}
                        {!! Form::label('description', '简介', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                        {!! Form::text('description', $category->description, array('class' => 'description input col-lg-12 col-md-12 col-sm-12', 'placeholder' => '简介')) !!}
                        {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                        {!! Form::token() !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection