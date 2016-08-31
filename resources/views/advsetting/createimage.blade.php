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
          {!! Form::open(array('url' => 'admin/advsetting/uploadimage', 'class' => 'form', 'enctype'=>'multipart/form-data', 'method'=>'put')) !!}
          {!! Form::label('description', '广告描述', array('class'=>'col-md-12')) !!}
          {!! Form::textarea('description', '', array('class' => 'description input col-md-12', 'placeholder' => 'Description')) !!}
          {!! Form::label('images', '上传广告图片', array('class'=>'col-md-12')) !!}
          {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file')) !!}
          {!! Form::label('order', '显示顺序', array('class'=>'col-md-12')) !!}
          {!! Form::text('order', '', array('class'=>'col-md-12', 'placehoder'=>'Order')) !!}
          <label for="published">
            <input type="checkbox" name="published" />
          显示</label>

          {!! Form::label('types', '选择广告类型', array('class'=>'col-md-12')) !!}
          <select class="col-lg-12 col-md-12 col-sm-12" name="type_id">
            @foreach ($types as $type)
              <option value="{{$type->id}}">{{$type->name}}</option>
            @endforeach
          </select>
          {!! Form::token() !!}
          {!! Form::text('type_id', 1, array('hidden'=>'hidden')) !!}
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