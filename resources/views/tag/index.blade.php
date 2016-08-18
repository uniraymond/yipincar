@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h1>关键词</h1>
            </div>

            {{--new blog link--}}
            <div class="col-lg-2 col-md-2 col-sm-2 pull-right clearfix">
                {{ link_to('admin/tag/create', '新建', ['class'=>'btn btn-default']) }}
                {{--<a class="btn btn-default" href="{{ route('admin.tag.create') }}">New</a>--}}
            </div>

            {{--flash alert--}}
            @if ($success = Session::get('status'))
                <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >
                    <p class="bg-success">
                        {{ $success }}
                    </p>
                </div>
            @endif

            @if($tags)
                <table class="table">
                    <thead>
                    <tr>
                        <th>标题</th>
                        <th>简介</th>
                        <th>编辑</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tags as $tag)
                        <tr>
                            <td>{{ $tag->name }}</td>
                            <td><span id="tagDesc_{{ $tag->id }}">{{ $tag->description }}</span></td>
                            <td><a class="btn btn-default" href="/admin/tag/{{ $tag->id }}/edit" id="editBtn_{{ $tag->id }}">编辑</a></td>
                            <td>
                                {!! Form::open(array('url' => 'admin/tag/'.$tag->id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("确定删除?");')) !!}
                                {!! Form::text('id', $tag->id, array('hidden'=>'hidden', 'readonly' => true)) !!}
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
                    <h4>还没有关键词.</h4>
                </div>
            @endif
        </div>
    </div>
@endsection