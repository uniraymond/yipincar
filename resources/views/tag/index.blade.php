@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <a class="btn btn-default" href="{{ route('admin.tag.create') }}">New</a>
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
                @if($tags)
                    <tbody>
                    @foreach($tags as $tag)
                        <tr>
                            <td>{{ $tag->name }}</td>
                            <td><span id="tagDesc_{{ $tag->id }}">{{ $tag->description }}</span></td>
                            <td><a class="btn btn-default" href="/admin/tag/{{ $tag->id }}/edit" id="editBtn_{{ $tag->id }}">编辑</a></td>
                            <td>
                                {!! Form::open(array('url' => 'admin/tag/'.$tag->id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("Confirm to delete this tag?");')) !!}
                                {!! Form::text('id', $tag->id, array('hidden'=>'hidden', 'readonly' => true)) !!}
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