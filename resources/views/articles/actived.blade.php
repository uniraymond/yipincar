@extends('layouts.base')
@include('layouts.contentSideBar')
{{--@include('articles.sidebarCategory',['categories'=>$categories, 'types'=>$types, 'tag'=>$tags, 'currentAction'=>$currentAction])--}}
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">文章</h1>

                {{--new blog link--}}
                <div class="col-lg-2 col-md-2 col-sm-2 pull-right clearfix">
                    {{ link_to('admin/article/create', '新建', ['class'=>'btn btn-default']) }}
                </div>

                {{--flash alert--}}
                @if ($success = Session::get('status'))
                    <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >
                        <p class="bg-success">
                            {{ $success }}
                        </p>
                    </div>
                @endif

                @if(count($articles)>0)
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>文章</th>
                            <th>栏目</th>
                            <th>类型</th>
                            <th>关键字</th>
                            <th>作者</th>
                            <th>评论</th>
                            @if ( Null !== Auth::user() )
                                <th>编辑</th>
                                <th>删除</th>
                            @endif
                            <th>文章状态</th>
                        </tr>
                        </thead>
                        {!! Form::open(array('url' => 'admin/article/groupupdate', 'class'=>'form', 'method'=>'POST')) !!}
                        {!! Form::token() !!}
                        <tbody>
                        @foreach($articles as $article)
                            <tr>
                                <td>{{ link_to('admin/article/'.$article->id, $article->title) }}</td>
                                <td>{{ $article->categories->name }}</td>
                                <td>{{ $article->article_types->name }}</td>
                                <td>
                                    @foreach ($article->tags as $t)
                                        {{ $t->name }},
                                    @endforeach
                                </td>
                                <td>
                                    {!! Form::text('id['.$article->id.']', $article->id , array('class'=>'id input col-md-12', 'hidden', 'readonly')) !!}
                                    {!! Form::text('type_id['.$article->type_id.']', $article->type_id , array('class'=>'id input col-md-12', 'hidden', 'readonly')) !!}
                                    {{--                                    @if ( Null !== Auth::user() && $article->created_by == Auth::user()->id || Auth::user()->hasAnyRole(['main_editor']))--}}
                                    {{--                                        <input type="checkbox" name="published[{{ $article->id }}]" {{ $article->published ? 'checked' : '' }}/>--}}
                                    {{--@endif--}}
                                    {{--{{ link_to('admin/user/'.$article->user_created_by->id, $article->user_created_by->name) }}--}}
                                    @if ($article->user_created_by)
                                        <a href="{{ url('admin/user/'.$article->user_created_by->id) }}"> <i class="fa fa-user"></i> {{ $article->user_created_by->name }}</a>
                                    @else

                                    @endif
                                </td>
                                <td><a href="{{ url('admin/articlecomment/'.$article->id) }}" id="commentBtn_{{ $article->id }}"><i class="fa fa-comments-o"></i> 评论({{ count($article->comments) }})</a></td>
                                @if ( Null !== Auth::user() && $article->created_by == Auth::user()->id || Auth::user()->hasAnyRole(['main_editor']))
                                    <td>
                                        <a href="{{ url('admin/article/'.$article->id.'/edit') }}" class="btn btn-default" id="editBtn_{{ $article->id }}">编辑</a>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="delete[{{ $article->id }}]" ng-checked="delete_{{$article->id}}" ng-click="confirmDelete('{{ $article->id }}')"/>
                                    </td>
                                @else
                                    <td></td>
                                    <td></td>
                                @endif
                                <td>
                                    {{ count($article->article_status)>0 ? $article->article_status->title : '草稿' }}
                                </td>
                            </tr>
                        @endforeach
                        @if ( Null !== Auth::user() )
                            <tr>
                                <td colspan="7"> </td>
                                <td>
                                    <input class="btn btn-primary" type="submit" value="保存" />
                                </td>
                            </tr>
                        @endif
                        </tbody>
                        {!! Form::close() !!}
                    </table>
                @else
                    <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
                        <h4>暂时还没有文章.</h4>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
            {!! $articles->links() !!}
        </div>
    </div>
@endsection