@extends('layouts.base')

@include('layouts.contentSideBar')
{{--<link rel="stylesheet" href="{{ asset("/src/css/colorbox.css") }}"/>--}}
{{--<link rel="stylesheet" href="{{ asset("/src/css/preview.css") }}"/>--}}
{{--@include('articles.sidebarCategory',['categories'=>$categories, 'types'=>$types, 'tag'=>$tags, 'currentAction'=>$currentAction])--}}
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">广告详细情况</h1>

                @if ($success = Session::get('status'))
                    <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes">
                        <p class="bg-success">
                            {{ $success }}
                        </p>
                    </div>
                @endif

                <div class="panel panel-default">
                    <div class="panel-heading heading_block_title col-lg-9 col-md-8">
                        <h3>{{ $advSetting->title }}</h3>
                    </div>
                    <div id="heading_block" class="panel-heading col-lg-3 col-md-4">
                        <div>
                            <small><span>作者: </span>{{ $advSetting->created_by ? $advSetting->users->name : '无名' }}
                            </small><br>
                        </div>
                        <div>
                            <small><span>广告状态: </span>@if ($advSetting->status == 1 || $advSetting->status == 0) 草稿
                                @elseif( $advSetting->status == 2) 申请审查
                                @elseif( $advSetting->status == 3) 已经审查
                                @elseif( $advSetting->status == 4) 发布 @endif</small>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                            <div>简述:</div>
                            <div><p>{{ $advSetting->description }}</p></div>
                            <div>图片</div>
                            <div>
                                <img src="/{{ $advSetting->resources->link }}" alt="{{ $advSetting->description }}" width="300px"/>
                            </div>
                            <div class="clearfix"></div>
                            <div class="list-group">
                                <div class="list-group-item list-group-item-action">
                                    类型: {{ $advSetting->adv_types->name }} </div>
                                <div class="list-group-item list-group-item-action">
                                    位置: {{ $advSetting->adv_positions->name }} </div>
                                <div class="list-group-item list-group-item-action">
                                    顺序: {{ $advSetting->order }} </div>
                                <div class="list-group-item list-group-item-action">
                                    链接: {{ $advSetting->links }} </div>
                                <div class="list-group-item list-group-item-action">
                                    链接: {{ $advSetting->categories->name }} </div>
                            </div>
                        </div>
                    </div>
                    @if ( Null !== Auth::user() && $advSetting->created_by == Auth::user()->id || Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor']) )
                        <div class="col-lg-4 col-md-4 col-sm-4 edit_article pull-right clearfix">
                            <a id="pv" class="inline cboxElement btn btn-primary" href="{{ url('/admin/advsetting/'.$advSetting->id.'/preview') }}">预览</a>
                            {{--<a id="pv" class="inline cboxElement btn btn-primary" href="#preview">预览</a>--}}
                            @if ( Null !== Auth::user() && $advSetting->created_by == Auth::user()->id && ($advSetting->status == 1 || $advSetting->status == 0) || Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor']))
                                {{ link_to('/admin/advsetting/editimage/'.$advSetting->id, '编辑', ['class'=>'btn btn-primary']) }}
                            @endif
                        </div>
                    @endif

                </div>
                <div class="clearfix"></div>
                {{--<div class="panel-body">--}}
                    {{--<div class="form-group  col-lg-12 col-md-12 col-sm-12" id="accordion">--}}
                        {{--@if (count($allStatusChecks) > 0 )--}}
                            {{--@foreach ($allStatusChecks as $statusName => $statusCheck)--}}
                                {{--@include('advsetting.reviewForm', [--}}
                                                                        {{--'statusCheck'=>$statusCheck,--}}
                                                                        {{--'currentUser'=>Auth::user(),--}}
                                                                        {{--'statusName'=>$statusName,--}}
                                                                        {{--'advsetting'=>$advsetting--}}
                                                                    {{--])--}}
                                {{--<div class="clearfix"></div>--}}
                            {{--@endforeach--}}
                        {{--@endif--}}
                    {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
    <script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}"></script>
    <script src="{{ url('/src/js/jquery.colorbox-min.js') }}"></script>
    <script>
        jQuery("#pv").colorbox();
    </script>
@endsection

