@extends('layouts.base')
@include('layouts.contentSideBar')
<link rel="stylesheet" href="{{ asset("/src/css/colorbox.css") }}" />
{{--@include('articles.sidebarCategory',['categories'=>$categories, 'types'=>$types, 'tag'=>$tags, 'currentAction'=>$currentAction])--}}
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">文章详情</h1>

                @if ($success = Session::get('status'))
                    <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >
                        <p class="bg-success">
                            {{ $success }}
                        </p>
                    </div>
                @endif

                <div class="panel panel-default">
                    <div class="panel-heading heading_block_title col-lg-9 col-md-8">
                        <h3>{{ $article->title }}</h3>
                    </div>
                    <div id="heading_block" class="panel-heading col-lg-3 col-md-4">
                        <div>
                            <small><span>作者: </span>{{ $article->created_by ? $article->user_created_by->name : '无名' }}</small>
                        </div>
                        <div>
                            <small><span>完成日期: </span>{{ $article->created_at }}</small>
                        </div>
                        <div>
                            <small><span>文章状态: </span>{{ count($article->article_status)>0 ? $article->article_status->title : '草稿' }}</small>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12" >
                            <div>简述:</div>
                            <div> <p>{{ $article->description }}</p> </div>
                            <div class="clearfix"></div>
                            <div id="preview" >详细内容: </div>
                            <div> {!! $article->content !!} </div>
                            <div class="list-group">
                                <div class="list-group-item list-group-item-action"> 栏目: {{ $article->categories->name }} </div>
                                <div class="list-group-item list-group-item-action"> 类型: {{ $article->article_types->name }} </div>
                                <div class="list-group-item list-group-item-action"> 标签:
                                    @if (count($article->tags)>0)
                                        @foreach($article->tags as $article_tag)
                                            {{ $article_tag->name . ' ' }}
                                        @endforeach
                                    @else
                                        {{ '暂时还没有选择标签' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ( Null !== Auth::user() && $article->created_by == Auth::user()->id || Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor']) )
                        <div class="col-lg-4 col-md-4 col-sm-4 edit_article pull-right clearfix">
                            <a id="pv" class="inline cboxElement btn btn-primary" href="#preview">预览</a> {{ link_to('admin/article/'.$article->id.'/edit', '编辑', ['class'=>'btn btn-primary']) }}
                        </div>
                    @endif

                </div>
                <div class="clearfix"></div>
                <div class="panel-body">
                    <div class="form-group  col-lg-12 col-md-12 col-sm-12" id="accordion">
                        @if (count($allStatusChecks) > 0 )
                            @foreach ($allStatusChecks as $statusName => $statusCheck)
                                @include('articles.reviewForm', [
                                                                        'statusCheck'=>$statusCheck,
                                                                        'currentUser'=>Auth::user(),
                                                                        'statusName'=>$statusName,
                                                                        'article'=>$article
                                                                    ])
                                <div class="clearfix"></div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ url('/src/js/jquery.min.2.2.4.js') }}"></script>
    <script src="{{ url('/src/js/jquery.colorbox-min.js') }}"></script>
    <script>
        jQuery("#pv").colorbox({inline:true, href:"#preview", width:"376px", height: "667px"});
    </script>
@endsection

