@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-11 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Edit Category
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
                        {!! Form::label('name', 'Name', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                        {!! Form::text('name', '' , array('class'=>'name input col-lg-12 col-md-12 col-sm-12', 'placeholder' => 'Name', 'required')) !!}
                        {!! Form::label('description', 'Description', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                        {!! Form::text('description', '', array('class' => 'description input col-lg-12 col-md-12 col-sm-12', 'placeholder' => 'Description')) !!}
                        {!! Form::submit('Submit', array('class'=>'btn btn-primary pull-right')) !!}
                        {!! Form::token() !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection