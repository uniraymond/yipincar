@extends('layouts.base')

@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">文章评论</h1>
                <div>
                    {{ $success = Session::get('status') }}
                </div>
            @if($comments)
                <table class="table">
                    <thead>
                        <tr>
                            <th>文章评论</th>
                            <th>文章</th>
                            <th>发表</th>
                            <th>删除</th>
                        </tr>
                    </thead>
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
                </table>
            @endif
        </div>
        <div>
            {!! $comments->links() !!}
        </div>
    </div>
@endsection