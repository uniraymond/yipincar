@extends('layouts.base')
@include('layouts.settingSideBar')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">敏感词列表</h1>

                @if ($fail = Session::get('warning'))
                    <div class="col-md-12 bs-example-bg-classes" >
                        <p class="bg-danger">
                            {{ $fail }}
                        </p>
                    </div>
                @endif
                <div class="col-lg-2 col-md-2 col-sm-2 pull-right clearfix">
                    {{ link_to('admin/taboo/create', '新建', ['class'=>'btn btn-default']) }}
                </div>

                {{--flash alert--}}
                @if ($success = Session::get('status'))
                    <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >
                        <p class="bg-success">
                            {{ $success }}
                        </p>
                    </div>
                @endif

                <div class="panel-body">
                    <div class="form-group  col-lg-12 col-md-12 col-sm-12" >
                        @if(isset($taboos) && count($taboos)>0)
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>敏感词</th>
                                    <th>类别</th>
                                    <th>编辑</th>
                                    <th>删除</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($taboos as $taboo)
                                    <tr>
                                        <td>{{ $taboo->content }}</td>
                                        <td>{{ $taboo->category }}</td>
                                        <td><a class="btn btn-default" href="/admin/taboo/{{ $taboo->id }}/edit" id="editBtn_{{ $taboo->id }}">编辑</a></td>
                                        <td>
                                            {!! Form::open(array('url' => 'admin/taboo/'.$taboo->id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("确定删除?");')) !!}
                                            {!! Form::text('id', $taboo->id, array('hidden'=>'hidden', 'readonly' => true)) !!}
                                            {!! Form::submit('删除', array('class'=>'btn btn-danger')) !!}
                                            {!! Form::token() !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
                                <h4>还没有标签.</h4>
                            </div>
                        @endif
                    <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
                        {!! $taboos->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection