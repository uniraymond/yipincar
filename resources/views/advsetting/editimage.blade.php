@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8" id="add_adv_images">
                <div class="panel panel-default">
                    <div class="panel-heading">上传图片</div>
                    <div class="panel-body">
                        <div>
                            {{ $success = Session::get('filestatus')}}
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            @endif
                        </div>
                        <div>
                            <img src="/{{ $image->link }}" alt="{{ $image->description }}" width="300px"/>
                        </div>
                        {!! Form::open(array('url' => 'admin/advsetting/updateimage', 'class' => 'form', 'enctype'=>'multipart/form-data')) !!}
                        {!! Form::label('description', '广告描述', array('class'=>'col-md-12')) !!}
                        {!! Form::textarea('description', $image->description, array('class' => 'description input col-md-12', 'placeholder' => 'Description')) !!}
                        {!! Form::label('displayorder', '显示顺序', array('class'=>'col-md-12')) !!}
                        {!! Form::text('displayorder', $image->order, array('class'=>'col-md-12')) !!}
                        {!! Form::label('published', '显示', array('class'=>'col-md-12')) !!}
                        <input type="checkbox" name="published" @if($image->published) checked="checked" @endif />
                        {!! Form::label('types', '选择广告类型', array('class'=>'col-md-12')) !!}
                        <select class="col-md-12" name="type_id">
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
                        {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        @include('advsetting.sidebarType',['types'=>$types])
    </div>
@endsection