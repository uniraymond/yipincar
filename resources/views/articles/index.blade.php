
@extends('layouts.app')

@section('content')
    <div class="container container-full">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h1>文章</h1>
            </div>

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

            @if($articles)
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>文章</th>
                        <th>类别</th>
                        <th>类型</th>
                        <th>关键字</th>
                        @if ( Null !== Auth::user() )
                            <th>发表</th>
                        @endif
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
                                    @if ( Null !== Auth::user() && $article->created_by == Auth::user()->id || Auth::user()->hasAnyRole(['main_editor']))
                                        <input type="checkbox" name="published[{{ $article->id }}]" {{ $article->published ? 'checked' : '' }}/>
                                    @endif
                                </td>
                                <td><a href="{{ url('admin/articlecomment/'.$article->id) }}" class="btn btn-default" id="commentBtn_{{ $article->id }}">评论({{ count($article->comments) }})</a></td>
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
        <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
            {!! $articles->links() !!}
        </div>
    </div>
@endsection