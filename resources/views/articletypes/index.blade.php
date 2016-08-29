@extends('layouts.appfull')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h1>文章类型</h1>
            </div>

            {{--new blog link--}}
            <div class="col-lg-2 col-md-2 col-sm-2 pull-right clearfix">
                {{ link_to('admin/articletypes/create', '新建', ['class'=>'btn btn-default']) }}
            </div>

            {{--flash alert--}}
            @if ($success = Session::get('status'))
                <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >
                    <p class="bg-success">
                        {{ $success }}
                    </p>
                </div>
            @endif

            @if($articletypes)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>标题</th>
                            <th>简介</th>
                            <th>编辑</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($articletypes as $articletype)
                        <tr>
                            <td>{{ $articletype->name }}</td>
                            <td><span id="articletypeDesc_{{ $articletype->id }}">{{ $articletype->description }}</span></td>
                            <td><a class="btn btn-default" href="/admin/articletypes/{{ $articletype->id }}/edit" id="editBtn_{{ $articletype->id }}">编辑</a></td>
                            <td>
                                {!! Form::open(array('url' => 'admin/articletypes/'.$articletype->id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("确定删除?");')) !!}
                                {!! Form::text('id', $articletype->id, array('hidden'=>'hidden', 'readonly' => true)) !!}
                                {!! Form::submit('删除', array('class'=>'btn btn-primary')) !!}
                                {!! Form::token() !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
                    <h4>文章类别不存在.</h4>.</h4>
                </div>
            @endif

        </div>
    </div>
@endsection