@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <a class="btn btn-default" href="{{ route('admin.category.create') }}">New</a>
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
                @if($categories)
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td><span id="categoryDesc_{{ $category->id }}">{{ $category->description }}</span></td>
                            <td><a class="btn btn-default" href="/admin/category/{{ $category->id }}/edit" id="editBtn_{{ $category->id }}">编辑</a></td>
                            <td>
                                {!! Form::open(array('url' => 'admin/category/'.$category->id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("Confirm to delete this category?");')) !!}
                                {!! Form::text('id', $category->id, array('hidden'=>'hidden', 'readonly' => true)) !!}
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