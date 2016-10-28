@extends('layouts.base')

@include('layouts.contentSideBar')
{{--@include('advsetting.sidebarType',['types'=>$types])--}}
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 35px">
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
                    <div style="margin-bottom: 55px; margin-top: 55px">
                        <img src="/{{ $advSettings->resources->link }}" alt="{{ $advSettings->description }}" width="300px"/>
                    </div>
                    <br>

                    {!! Form::date('published_at', date('Y-m-d', strtotime($advSettings->published_at)), array('class'=>'col-md-12','hidden', 'placehold'=>'开始日期')) !!}
                    <div class="clearfix"></div>
                    {!! Form::label('category_id', '栏目', array('class'=>'col-md-1')) !!}
                    <div class="col-md-2" style="margin-bottom: 55px">
                        <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="category_id">
                            <option value="3">动态</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}" {{ $category->id == $advSettings->category_id ? 'selected' : '' }}>{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>

                    {!! Form::open(array('url' => 'admin/advsetting/updateimage', 'class' => 'form', 'enctype'=>'multipart/form-data')) !!}

                    {!! Form::label('type_id', '选择类型', array('class'=>'col-md-1')) !!}
                    <div class="col-md-2" style="margin-bottom: 55px">
                        <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="type_id">
                            @foreach ($types as $type)
                                <option value="{{$type->id}}" {{ $type->id == $advSettings->type_id ? 'selected' : '' }}>{{$type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    {!! Form::label('position_id', '选择位置', array('class'=>'col-md-1')) !!}
                    <div class="col-md-2" style="margin-bottom: 55px">
                        <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="position_id">
                            @foreach ($positions as $position)
                                <option value="{{$position->id}}" {{ $position->id == $advSettings->type_id ? 'selected' : '' }}>{{$position->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>

                    {!! Form::label('order', '显示顺序', array('class'=>'col-md-1')) !!}
                    <div class="col-md-2" style="margin-bottom: 55px">
                        <select class="col-lg-12 col-md-12 col-sm-12 form-control" name="order">
                            @for($i=1; $i<=10; $i++)
                                <option value="{{ $i }}" {{ $i == $advSettings->order ? 'selected' : '' }} >{{ $i }}</option>i
                            @endfor
                        </select>
                    </div>

                    <div class="clearfix"></div>
                    {!! Form::label('title', '标题', array('class'=>'col-md-1')) !!}
                    <div class="col-md-11" style="margin-bottom: 55px">
                        {!! Form::text('title', $advSettings->title, array('class' => 'input col-md-12 form-control', 'placeholder' => '标题')) !!}
                    </div>
                    <div class="clearfix"></div>
                    {!! Form::label('description', '广告描述', array('class'=>'col-md-1')) !!}
                    <div class="col-md-11" style="margin-bottom: 55px">
                        <textarea name="description" class="description input col-md-12 form-control" placeholder="详细信息" rows="3" >{{ $advSettings->description }}</textarea>
                    </div>
                    <div class="clearfix"></div>


                    @if ($errors->has('links'))
                        <span class="help-block">
                            <strong>{{ $errors->first('links') ? '链接不能为空' : '' }}</strong>
                        </span>
                    @endif
                    <div class="clearfix"></div>
                    {!! Form::label('links', '链接*', array('class'=>'col-md-1')) !!}
                    <div class="col-md-11" style="margin-bottom: 55px">
                        {!! Form::text('links', $advSettings->links, array('class' => 'input col-md-12 form-control', 'placeholder' => '链接')) !!}
                    </div>
                    {{--<div class="clearfix"></div>--}}
{{--                    {!! Form::label('published_at', '开始显示日期', array('class'=>'col-md-12')) !!}--}}


                    @if( Auth::user()->hasAnyRole(['adv_editor']) )
                        <div>
                            <label class="col-md-3 published_label" for="status">
                                <input class="status" type="checkbox" name="status" {{ $advSettings->status > 1 ? 'checked' : '' }} /> 提交审查
                            </label>
                        </div>
                    @endif

                    <div class="clearfix"></div>

                    <div>
                        @if (Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'adv_editor']) && $advSettings->status == 4)
                            <div id="settop_error" class="alert-danger"></div>
                            <div class="clearfix"></div>
                            <label class="col-md-3 published_label" for="top">
                                <input id="settop" class="top" type="checkbox" name="top" {{ $advSettings->top ? 'checked' : '' }} /> 置顶
                            </label>
                        @endif
                    </div>

                    {!! Form::token() !!}
                    {!! Form::text('id', $advSettings->id, array('hidden', 'readonly')) !!}
                    <div class="clearfix"></div>
                    <br>
                    <br>
                    <div class=" col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 55px">
                        {!! Form::submit('保存', array('class'=>'btn btn-primary')) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}"></script>
    <script>
        jQuery(document).ready(function(){
            jQuery('#settop').click(function(){
                jQuery.ajax({
                    url: '/admin/advsetting/checktop',
                    type: "GET",
                    success: function(data){
                        console.log(data.status);
                        if (data.status == 'faild') {
                            jQuery('#settop_error').html('文章或广告已达置顶上限.').delay(3000).fadeOut('slow');;
                            jQuery('#settop').prop("disabled", true).prop('checked', false).val(0);
                        }
                    }
                });
            });
        });
    </script>
@endsection