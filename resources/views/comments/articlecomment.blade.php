@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="title">
                文章评论
            </div>
            <div>
                {{ $success = Session::get('status') }}
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>文章评论</th>
                    <th>文章</th>
                    <th>发表</th>
                    <th>删除</th>
                </tr>
                </thead>
                @if($comments)
                    <tbody>
                    @foreach($comments as $comment)
                        {!! Form::open(array('url' => 'admin/comment/'.$comment->id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("确定删除?");')) !!}
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
                            <td>{!! Html::link('admin/article', '返回文章列表') !!}</td>
                            <td>{!! Form::submit('删除', array('class'=>'btn btn-primary')) !!}</td>
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