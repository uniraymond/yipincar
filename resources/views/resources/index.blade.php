@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Resource Upload</div>
                    <div class="panel-body">
                        <form action="{{ URL::to('resource/upload') }}" method="post" id="upload" enctype="multipart/form-data">
                            <label>Choose file</label>
                            <input type="file" id="file" class="file" name="file" />
                            <input type="text" id="description" name="description" placeholder="File Description" />
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="submit" id="submit" value="Upload" />
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection