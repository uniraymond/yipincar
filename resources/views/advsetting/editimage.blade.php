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
                    {{ $success = Session::get('status')}}
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
                        <img src="/{{ $advSettings->resources->link }}" alt="{{ $advSettings->description }}" width="300px"/>
                    </div>
                    <br>
                    {!! Form::open(array('url' => 'admin/advsetting/updateimage', 'class' => 'form', 'enctype'=>'multipart/form-data')) !!}

                    {!! Form::label('type_id', '选择类型', array('class'=>'col-md-12')) !!}
                    <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="type_id">
                        @foreach ($types as $type)
                            <option value="{{$type->id}}" {{ $type->id == $advSettings->type_id ? 'selected' : '' }}>{{$type->name}}</option>
                        @endforeach
                    </select>
<div class="clearfix"></div>
                    {!! Form::label('position_id', '选择位置', array('class'=>'col-md-12')) !!}
                    <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="position_id">
                        @foreach ($positions as $position)
                            <option value="{{$position->id}}" {{ $position->id == $advSettings->type_id ? 'selected' : '' }}>{{$position->name}}</option>
                        @endforeach
                    </select>

                    <div class="clearfix"></div>
                    {!! Form::label('title', '内容', array('class'=>'col-md-12')) !!}
                    {!! Form::text('title', $advSettings->title, array('class' => 'input col-md-12 form-control', 'placeholder' => '内容')) !!}

                    <div class="clearfix"></div>
                    {!! Form::label('description', '广告描述', array('class'=>'col-md-12')) !!}
                    <textarea name="description" class="description input col-md-12 form-control" placeholder="详细信息" rows="3" >{{ $advSettings->description }}</textarea>

                    <div class="clearfix"></div>
                    {!! Form::label('order', '显示顺序', array('class'=>'col-md-12')) !!}
                    <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="order">
                        @for($i=1; $i<=6; $i++)
                            <option value="{{ $i }}" {{ $i == $advSettings->order ? 'selected' : '' }} >{{ $i }}</option>i
                        @endfor
                    </select>

                    <div class="clearfix"></div>
                    {!! Form::label('links', '链接', array('class'=>'col-md-12')) !!}
                    {!! Form::text('links', $advSettings->links, array('class' => 'input col-md-12 form-control', 'placeholder' => '链接')) !!}

                    <div class="clearfix"></div>
                    {!! Form::label('published_at', '开始显示日期', array('class'=>'col-md-12')) !!}
                    {!! Form::date('published_at', date('Y-m-d', strtotime($advSettings->published_at)), array('class'=>'col-md-12', 'placehold'=>'开始日期')) !!}
                    <div class="clearfix"></div>
                    {!! Form::label('category_id', '栏目', array('class'=>'col-md-12')) !!}
                    <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="category_id">
                        @foreach ($categories as $category)
                            <option value="{{$category->id}}" {{ $category->id == $advSettings->category_id ? 'selected' : '' }}>{{$category->name}}</option>
                        @endforeach
                    </select>

                    {!! Form::token() !!}
                    {!! Form::text('id', $advSettings->id, array('hidden', 'readonly')) !!}
                    <div class="clearfix"></div>
                    <br>
                    <br>
                    <div class=" col-lg-12 col-md-12 col-sm-12">
                        {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection