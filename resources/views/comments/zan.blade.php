@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="title">
                All Comments
            </div>
            <div>
{{--                {{ $success = Session::get('status') }}--}}
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Zan Token</th>
                    <th>Comment</th>
                    <th>Published</th>
                    <th>Delete</th>
                </tr>
                </thead>
                @if($zans)
                    <tbody>
                    {!! Form::open(array('url' => 'admin/zan/'.$commentId, 'class' => 'form', 'method'=>'post')) !!}
                    @foreach($zans as $zan)
                        <tr>
                            <td>{{ $zan->token }}</td>
                            <td><span id="zan_{{ $zan->id }}">{{ $zan->comment->comment }}</span></td>
                            <td>
                                <input type="checkbox" name="published[{{ $zan->id }}]" {{ $zan->comfirmed ? 'checked' : '' }}/>
                            </td>
                            <td>
                                <input type="checkbox" name="delete[{{ $zan->id }}]" />
                            </td>
                            <td>
                                {!! Form::token() !!}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>
                            {!! Form::submit('Submit', array('class'=>'btn btn-primary')) !!}
                        </td>
                    </tr>
                    {!! Form::close() !!}
                    </tbody>
                @endif
            </table>
        </div>
        <div>
            {!! $zans->links() !!}
        </div>
    </div>
@endsection