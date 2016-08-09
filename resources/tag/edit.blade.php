@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">New Category</div>
                <div class="panel-body">
                    {!! Form::open(array('url' => 'admin/category/'.$category->id, 'class' => 'form', 'method'=>'put')) !!}
                    {!! Form::label('name', 'Name', array('class'=>'col-md-12')) !!}
                    {!! Form::text('name', $category->name, array('class'=>'name input col-md-12', 'placeholder' => 'Name')) !!}
                    {!! Form::label('description', 'Description', array('class'=>'col-md-12')) !!}
                    {!! Form::text('description', $category->description, array('class' => 'description input col-md-12', 'placeholder' => 'Description')) !!}
                    {!! Form::submit('确定', array('class'=>'btn btn-primary')) !!}
                    {!! Form::token() !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
     </div>
@endsection