@extends('layouts.base')

@include('advsetting.sidebarType',['types'=>$types])
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">编辑广告</h1>

                <div>
                    @if ($fail = Session::get('warning'))
                        <div class="col-md-12 bs-example-bg-classes" >
                            <p class="bg-danger">
                                {{ $fail }}
                            </p>
                        </div>
                    @endif
                    {{ $success = Session::get('filestatus')}}
                    @if (count($errors) > 0)
                        @foreach ($errors->all() as $error)
                            <div class="col-md-12 bs-example-bg-classes" >
                                <p class="bg-danger">
                                    {{ $error }}
                                </p>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div>
                        <img src="/{{ $image->link }}" alt="{{ $image->description }}" width="300px"/>
                    </div>
                    {!! Form::open(array('url' => 'admin/advsetting/updateimage', 'class' => 'form', 'enctype'=>'multipart/form-data')) !!}
                    {!! Form::label('name', '广告标题', array('class'=>'col-md-12')) !!}
                    {!! Form::text('name', $image->name, array('class' => 'input col-md-12 form-control', 'placeholder' => '广告标题')) !!}
                    {!! Form::label('description', '广告描述', array('class'=>'col-md-12')) !!}
                    {!! Form::textarea('description', $image->description, array('class' => 'description input col-md-12 form-control', 'placeholder' => 'Description')) !!}
                    {!! Form::label('displayorder', '显示顺序', array('class'=>'col-md-12')) !!}
                    {!! Form::text('displayorder', $image->order, array('class'=>'col-md-12 form-control')) !!}

                    <div class=" col-lg-12 col-md-12 col-sm-12">
                        {!! Form::label('published', '显示', array('class'=>'col-md-12')) !!}
                        <input type="checkbox" name="published" @if($image->published) checked="checked" @endif />
                    </div>
                    {!! Form::label('types', '选择广告类型', array('class'=>'col-md-12')) !!}
                    <select class="col-md-12 form-control" name="type_id">
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}"
                            @if ($type->id == $image->type_id)
                                "selected"="selected"
                            @endif >{{$type->name}}</option>
                        @endforeach
                    </select>
                    {!! Form::text('id', $image->id, array('hidden'=>'hidden', 'readonly'=>'readonly')) !!}
                    {!! Form::token() !!}
                    <div class="clearfix"></div>
                    <div class=" col-lg-12 col-md-12 col-sm-12">
                        {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection