@extends('layouts.base')
@include('layouts.settingSideBar')
<link rel="stylesheet" href="{{ asset("/src/css/select2.min.css") }}" />
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">敏感词列表</h1>

                <div class="col-md-6">
                    {{--<form action="/admin/taboo/search" method="get">--}}
                    <select class="js-example-basic-single" name="content" id="searchContent">
                        @foreach ($tabooSelects as $tsCategory => $ts)
                            <option value="0">查询敏感词</option>
                            <optgroup label="{{ $tsCategory }}">
                                @foreach($ts as $tab)
                                    <option value="{{ $tab->id }}" > {{ $tab->content }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <button id="search_word" value="查找" class="btn btn-default">查找</button>
                    {{--</form>--}}
                </div>
                <div class="col-md-5">
                    <select id="select-category" name="category">
                        <option value="0">选择类别</option>
                        @foreach ($categories as $cg)
                            <option value="{{ $cg->category }}">
                                {{ $cg->category }}
                            </option>
                        @endforeach
                    </select>
                    <button id="search_category" value="查找"  class="btn btn-default">查找</button>
                </div>
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
                    <div class="form-group  col-lg-12 col-md-12 col-sm-12" id="search_result">
                        @if(isset($taboos) && count($taboos)>0)
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>敏感词</th>
                                    <th>类别</th>
                                    <th>编辑</th>
                                    <th>删除</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($taboos as $taboo)
                                    <tr>
                                        <td>{{ $taboo->id }}</td>
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
                        <span class="totalpage pagination">敏感词总数：{{ $totalTaboos }}个</span>  {!! $taboos->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ url('/src/js/jQuery.min.2.2.4.js') }}" ></script>
    <script src="{{ url('/src/js/select2/select2.full.min.js') }}" ></script>
    <script type="text/javascript">
        $(".js-example-basic-single").select2();
        $('#select-category').select2();

        $(document).ready(function(){
            $('#search_word').click(function(){
                var searchdata = $('#searchContent').val();
                console.log($.get('/admin/taboo/searchcontent/'+searchdata));
                $('#search_result').load('/admin/taboo/searchcontent/'+searchdata);
            });

            $('#search_category').click(function(){
                var searchdata = $('#select-category').val();
                console.log($.get('/admin/taboo/searchcategory/'+searchdata));
                $('#search_result').load('/admin/taboo/searchcategory/'+searchdata);
            });
        });
    </script>
@endsection