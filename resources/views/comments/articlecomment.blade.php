@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="title">
                All Comments
            </div>
            <div>
                {{ $success = Session::get('status') }}
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Comment</th>
                    <th>Article</th>
                    <th>Published</th>
                    <th>Delete</th>
                </tr>
                </thead>
                @if($comments)
                    <tbody>
                    @foreach($comments as $comment)
                        {!! Form::open(array('url' => 'admin/comment/'.$comment->id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("Confirm to delete this category?");')) !!}
                        <tr>
                            <td>{{ $comment->comment }}</td>
                            <td><span id="article_{{ $comment->id }}">{{ $comment->article->title }}</span></td>
                            <td>
                                <input type="checkbox" name="published[{{ $comment->id }}]" {{ $comment->published ? 'checked' : '' }}/>
                            </td>
                            <td>
                                <input type="checkbox" name="delete[{{ $comment->id }}]" />
                            </td>
                            <td>
                                {!! Form::token() !!}
                            </td>
                            <td>
                                {!! Html::link('admin/zan/'.$comment->id, 'Zans ('.count($comment->Zan).' )') !!}
                            </td>
                        </tr>
                        <tr>
                            <td>{!! Html::link('admin/article', 'back to article list') !!}</td>
                            <td>{!! Form::submit('Submit', array('class'=>'btn btn-primary')) !!}</td>
                        </tr>
                        {!! Form::close() !!}
                    @endforeach
                    </tbody>
                @endif
            </table>
        </div>
        <div>
            {!! $comments->links() !!}
        </div>
    </div>
@endsection