@extends('layouts.base')

@include('layouts.contentSideBar')
<link rel="stylesheet" href="{{ asset("/src/css/colorbox.css") }}"/>
<link rel="stylesheet" href="{{ asset("/src/css/preview.css") }}"/>
{{--@include('articles.sidebarCategory',['categories'=>$categories, 'types'=>$types, 'tag'=>$tags, 'currentAction'=>$currentAction])--}}
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 35px">
                <h1 class="page-header">文章详情</h1>

                @if ($success = Session::get('status'))
                    <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes">
                        <p class="bg-success">
                            {{ $success }}
                        </p>
                    </div>
                    <div class="clearfix"></div>
                @endif
                <div class="panel panel-default">
                    <div class="top-title-block">
                        <div class="panel-heading heading_block_title col-lg-9 col-md-8">
                            <h3>{{ $article->title }}</h3>
                        </div>
                        <div id="heading_block" class="panel-heading col-lg-3 col-md-4">
                            <div>
                                <small>
                                    <span>作者: </span>{{ $article->authname ? $article->authname : $article->user_created_by->name }}
                                </small>
                            </div>
                            <div>
                                <small><span>完成日期: </span>{{ $article->created_at }}</small>
                            </div>
                            <div>
                                <small><span>文章状态: </span>@if ($article->published == 1 || $article->published == 0) 草稿
                                    @elseif($article->published == 2) 初审
                                    @elseif($article->published == 3) 终审
                                    @elseif($article->published == 4) 发布 @endif</small>
                            </div>
                            <div>
                                <small>{{ $article->top ? '置顶' : ''  }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                            <div>首页图片:</div>
                            <div>
                                @php
                                    $articleLinks = $article->resources;
                                    $links = array();
                                    if (count($articleLinks) > 0) {
                                        foreach($articleLinks as $artLink) {
                                            array_push($links, $artLink);
                                        }
                                    }
                                usort($links, 'cmp');

                                function  cmp ( $linka ,  $linkb )
                                {
                                    return $linka->order  >  $linkb->order;
                                }
                                @endphp
                                @if(count($links))
                                    <img id="image" width="200" src="{{ $links[0]->link }}" alt="{{ $article->title }}" />
                                    @if($article->template_id == 3 && count($links) >= 3)
                                        <img id="image" width="200" src="{{ $links[1]->link  }}" alt="{{ $article->title }}" />
                                        <img id="image" width="200" src="{{ $links[2]->link  }}" alt="{{ $article->title }}" />
                                    @endif
                                @endif
                            </div>
                            <div>简述:</div>
                            <div class="description" style="margin-bottom: 15px">{{ $article->description }}</div>
                            <div class="clearfix"></div>
                            <div id="preview" >详细内容:</div>
                            <div class="content"> {!! $article->content !!} </div>
                            <div class="list-group">
                                <div class="list-group-item list-group-item-action">
                                    栏目: {{ $article->categories->name }} </div>
                                <div class="list-group-item list-group-item-action">
                                    类型: {{ $article->article_types->name }} </div>
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
                    @if ( Null !== Auth::user() && $article->created_by == Auth::user()->id || Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor']) )
                        <div class="col-lg-2 col-md-3 col-sm-4 edit_article pull-right clearfix"  style="margin-top: 55px">
                            <a id="pv" class="inline cboxElement btn btn-default" href="{{ url('/preview/'.$article->id) }}" target="_blank">预览</a>
                            {{--<a id="pv" class="inline cboxElement btn btn-primary" href="#preview">预览</a>--}}
                            @if ( Null !== Auth::user() && $article->created_by == Auth::user()->id && ($article->published == 1 || $article->published == 0) || Auth::user()->hasAnyRole(['super_admin', 'admin', 'chef_editor', 'main_editor', 'adv_editor']))
                                {{ link_to('admin/article/'.$article->id.'/edit', '编辑', ['class'=>'btn btn-second']) }}
                            @endif
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
    <script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}"></script>
{{--    <script src="{{ url('/src/js/jquery.colorbox-min.js') }}"></script>--}}
    <script>
//        jQuery("#pv").colorbox();

        jQuery(function(){
            jQuery('.article-content img').width(500);
        });
    </script>
@endsection

