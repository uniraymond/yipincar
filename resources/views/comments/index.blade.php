@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h1>文章评论</h1>
            </div>

            {{--flash alert--}}
            @if ($success = Session::get('status'))
                <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >
                    <p class="bg-success">
                        {{ $success }}
                    </p>
                </div>
            @endif

            @if($comments)
            <table class="table table-striped">
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
                                {!! Html::link('admin/zan/'.$comment->id, 'Zans') !!}
                            </td>
                        </tr>
                        <tr>
                            <td>{!! Form::submit('删除', array('class'=>'btn btn-primary')) !!}</td>
                        </tr>
                        {!! Form::close() !!}
                    @endforeach
                    </tbody>
            </table>
            @else
                <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
                    <h4>还没有任何评论.</h4>
                </div>
            @endif
        </div>
        <div>
            {!! $comments->links() !!}
        </div>
    </div>
@endsection