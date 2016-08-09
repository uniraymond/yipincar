@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="title">
                {{--{{  }}--}}
                {{ $success = Session::get('status')}}
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Comment</th>
                    <th>Published</th>
                    {{--<th>编辑</th>--}}
                </tr>
                </thead>
                @if($comments)
                    <tbody>
                    @foreach($comments as $comment)
                        {!! Form::open() !!}
                        <tr>
                            <td>{{ $comment->comment }}</td>
                            <td><span id="article_{{ $comment->id }}">{{ $comment->article()->title }}</span></td>
                            <td>
                                <input type="checkbox" name="published[{{ $comment->id }}]" {{ $comment->published ? 'checked' : '' }}/>
                            </td>
                            <td>
                                {!! Form::token() !!}
                                {!! Form::open(array('url' => 'admin/comment/'.$comment->id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("Confirm to delete this category?");')) !!}
                            </td>
                        </tr>
                        {!! Form::submit('Delete', array('class'=>'btn btn-primary')) !!}
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