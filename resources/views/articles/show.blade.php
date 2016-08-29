@extends('layouts.app')
@section('content')
    @php ( $currentUser = Auth::user() )
    <div class="container">
        @if ($success = Session::get('status'))
            <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >
                <p class="bg-success">
                    {{ $success }}
                </p>
            </div>
            <div class="clearfix"></div>
        @endif
        <div class="row">
            <div class="col-md-11 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1>{{ $article->title }}</h1>
                        <div>
                            <small><span>作者: </span>{{ $article->created_by ? $article->user_created_by->name : '无名' }}</small>
                        </div>
                        <div>
                            <small><span>完成日期: </span>{{ date('Y m d, H:s', strtotime($article->created_date)) }}</small>
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
                            <div>详细内容: </div>
                            <div> {!! $article->content !!} </div>
                            <div class="list-group">
                                <div class="list-group-item list-group-item-action"> 类别: {{ $article->categories->name }} </div>
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
                        @if ( Null !== Auth::user() && $article->created_by == Auth::user()->id )
                            <div class="col-lg-2 col-md-2 col-sm-2 pull-right clearfix">
                                {{ link_to('admin/article/'.$article->id.'/edit', '编辑', ['class'=>'btn btn-primary']) }}
                            </div>
                        @endif
                    </div>
                </div>

                @if ($fail = Session::get('warning'))
                    <div class=" col-lg-12 col-md-12 col-sm-12  bs-example-bg-classes" >
                        <p class="bg-danger">
                            {{ $fail }}
                        </p>
                    </div>
                @endif

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>文章审阅</h3>
                    </div>

                    <div class="panel-body">
                        <div class="form-group  col-lg-12 col-md-12 col-sm-12" id="accordion">
                            @if (count($allStatusChecks) > 0 )
                            @foreach ($allStatusChecks as $statusName => $statusCheck)
                                <div class="col-lg-12 col-md-12 col-sm-12 article_reviews" id="heading_edit_status_check_form">
                                @include('articles.reviewForm', [
                                                                    'statusCheck'=>$statusCheck,
                                                                    'currentUser'=>$currentUser,
                                                                    'statusName'=>$statusName,
                                                                    'article'=>$article
                                                                ])
                                </div>
                                <div class="clearfix col-lg-12 col-md-12 col-sm-12"></div>
                            @endforeach
                                @endif
                        </div>
                    </div>
                </div>
            </div>
            @include('articles.sidebarCategory',['categories'=>$categories, 'types'=>$types, 'tag'=>$tags, 'currentAction'=>$currentAction])
        </div>
    </div>
@endsection
