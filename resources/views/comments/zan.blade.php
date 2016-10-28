@extends('layouts.base')
@include('layouts.settingSideBar')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 35px">
                <h1 class="page-header">赞</h1>
                <div>
                    {{ $success = Session::get('status') }}
                </div>
                @if(count($zans)>0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>赞</th>
                            <th>评论</th>
                            <th>发表</th>
                            <th>删除</th>
                        </tr>
                    </thead>
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
                                {!! Form::submit('确定', array('class'=>'btn btn-primary')) !!}
                            </td>
                        </tr>
                        {!! Form::close() !!}
                    </tbody>
                    </table>
                @endif
            </div>
        </div>
        <div>
            {!! $zans->links() !!}
        </div>
    </div>
@endsection