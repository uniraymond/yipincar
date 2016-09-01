@extends('layouts.base')

@include('advsetting.sidebarType',['types'=>$types])
@section('content')
  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">新建广告</h1>

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
          {!! Form::open(array('url' => 'admin/advsetting/uploadimage', 'class' => 'form', 'enctype'=>'multipart/form-data', 'method'=>'put')) !!}
          {!! Form::label('name', '广告标题', array('class'=>'col-md-12')) !!}
          {!! Form::text('name', '', array('class' => 'input col-md-12 form-control', 'placeholder' => '广告标题')) !!}
          {!! Form::label('description', '广告描述', array('class'=>'col-md-12')) !!}
          {!! Form::textarea('description', '', array('class' => 'description input col-md-12 form-control', 'placeholder' => '广告详细描述')) !!}
          {!! Form::label('images', '上传广告图片', array('class'=>'col-md-12')) !!}
          {!! Form::file('images', '', array('class'=>'col-md-12 form-control-file form-control')) !!}
          {!! Form::label('order', '显示顺序', array('class'=>'col-md-12')) !!}
          {!! Form::text('order', '', array('class'=>'col-md-12 form-control', 'placehoder'=>'Order')) !!}
          <div class="col-lg-12 col-md-12 col-sm-12">
          <label for="published">
            <input type="checkbox" name="published" />
            显示</label>
          </div>
          {!! Form::label('types', '选择广告类型', array('class'=>'col-md-12')) !!}
          <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="type_id">
            @foreach ($types as $type)
              <option value="{{$type->id}}">{{$type->name}}</option>
            @endforeach
          </select>
          {!! Form::token() !!}
          {!! Form::text('type_id', 1, array('hidden'=>'hidden')) !!}
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