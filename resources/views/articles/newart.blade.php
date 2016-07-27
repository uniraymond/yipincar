@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">New Article</div>
                    <div class="panel-body">
                        <form class="form" action="\api\article" method="post" enctype="multipart/form-data">
                            <div class="form-group col-md-6" >
                                <label class="col-md-12">Title</label>
                                <input class="col-md-12" type="text" id="title" name="title" />
                                <label class="col-md-12">Content</label>
                                <input class="col-md-12" type="text" id="content" name="content" />
                                <label class="col-md-12">Select Type</label>
                                <select class="col-md-12" name="arttype">
                                    @foreach ($articletypes as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12" class="form-group">
                                <label>Resource</label>
                                <input type="file" id="file" name="file" />
                                <input type="text" id="filedescription" name="filedescription" placeholder="File Description" />

                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            </div>

                            <input type="submit" id="submit" value="Upload" />
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection