@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <a class="btn btn-default" href="{{ route('admin.articletypes.create') }}">New</a>
            <div class="title">
                {{ $success = Session::get('status')}}
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Details</th>
                    <th>编辑</th>
                </tr>
                </thead>
                @if($articletypes)
                    <tbody>
                    @foreach($articletypes as $articletype)
                        <tr>
                            <td>{{ $articletype->name }}</td>
                            <td><span id="articletypeDesc_{{ $articletype->id }}">{{ $articletype->description }}</span></td>
                            <td><a class="btn btn-default" href="/admin/articletypes/{{ $articletype->id }}/edit" id="editBtn_{{ $articletype->id }}">编辑</a></td>
                            <td>
                                {!! Form::open(array('url' => 'admin/articletypes/'.$articletype->id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("Confirm to delete this article type?");')) !!}
                                {!! Form::text('id', $articletype->id, array('hidden'=>'hidden', 'readonly' => true)) !!}
                                {!! Form::submit('Delete', array('class'=>'btn btn-primary')) !!}
                                {!! Form::token() !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                @endif
            </table>
        </div>
    </div>
@endsection