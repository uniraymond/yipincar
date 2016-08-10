
@extends('layouts.app')

@section('content')
    <div class="container container-full" ng-app="articleApp">
        <div class="row" ng-controller="articleController">
            <a class="col-md-offset-2 btn btn-default right" href="{{ url('admin/article/create') }}" target="_blank">New Article</a>
            <div class="col-lg-9 col-md-11 col-sm-10" >
                {{ $success = Session::get('status') }}
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Article</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Tag</th>
                    <th>Published</th>
                    <th>编辑</th>
                    <th>Comments</th>
                    <th>删除</th>
                </tr>
                </thead>
                @if($articles)
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
                            <td><a href="{{ url('admin/article/'.$article->id.'/edit') }}" target="_blank" class="btn btn-default" id="editBtn_{{ $article->id }}">编辑</a></td>
                            <td><a href="{{ url('admin/articlecomment/'.$article->id) }}" target="_blank" class="btn btn-default" id="commentBtn_{{ $article->id }}">Comments({{ count($article->comments) }})</a></td>
                            <td><input type="checkbox" name="delete[{{ $article->id }}]" ng-checked="delete_{{$article->id}}" ng-click="confirmDelete('{{ $article->id }}')"/></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5"> </td>
                        <td>
                            <input class="btn btn-primary" type="submit" value="submit" />
                        </td>
                    </tr>
                    </tbody>
                    {!! Form::close() !!}
                @endif
            </table>
        </div>
        <div>
            {!! $articles->links() !!}
        </div>
    </div>
@endsection