
@extends('layouts.app')

@section('content')
    <div class="container container-full">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h1>Articles</h1>
            </div>

            {{--new blog link--}}
            <div class="col-lg-2 col-md-2 col-sm-2 pull-right clearfix">
                {{ link_to('admin/article/create', 'New Article', ['class'=>'btn btn-default', 'target'=>'_blank']) }}
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
                        <th>Article</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Tag</th>
                        <th>Published</th>
                        @if ( Null !== Auth::user() )
                            <th>编辑</th>
                        @endif
                        <th>Comments</th>
                        @if ( Null !== Auth::user() )
                            <th>删除</th>
                        @endif
                    </tr>
                    </thead>
                        {!! Form::open(array('url' => 'admin/article/groupupdate', 'class'=>'form', 'method'=>'POST')) !!}
                        {!! Form::token() !!}
                        <tbody>
                        @foreach($articles as $article)
                            <tr>
                                <td>{{ $article->title }}</td>
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
                                    <input type="checkbox" name="published[{{ $article->id }}]" {{ $article->published ? 'checked' : '' }}/>
                                </td>
                                @if ( Null !== Auth::user() )
                                <td>
                                    <a href="{{ url('admin/article/'.$article->id.'/edit') }}" target="_blank" class="btn btn-default" id="editBtn_{{ $article->id }}">编辑</a>
                                </td>
                                @endif
                                <td><a href="{{ url('admin/articlecomment/'.$article->id) }}" target="_blank" class="btn btn-default" id="commentBtn_{{ $article->id }}">Comments({{ count($article->comments) }})</a></td>
                                @if ( Null !== Auth::user() )
                                <td>
                                    <input type="checkbox" name="delete[{{ $article->id }}]" ng-checked="delete_{{$article->id}}" ng-click="confirmDelete('{{ $article->id }}')"/>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                        @if ( Null !== Auth::user() )
                            <tr>
                                <td colspan="7"> </td>
                                <td>
                                    <input class="btn btn-primary" type="submit" value="submit" />
                                </td>
                            </tr>
                        @endif
                        </tbody>
                        {!! Form::close() !!}
                </table>
            @else
                <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
                    <h4>No article is available.</h4>
                </div>
            @endif
        </div>
        @if ( Null !== Auth::user() )
            <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
                {!! $articles->links() !!}
            </div>
        @endif
    </div>
@endsection