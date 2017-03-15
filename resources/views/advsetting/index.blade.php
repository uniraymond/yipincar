@extends('layouts.base')
@include('layouts.contentSideBar')
{{--@include('advsetting.sidebarType',['types'=>$types, 'positions'=>$positions])--}}
@section('content')
    {!! Form::open(array('url' => 'admin/advsetting/update', 'class'=>'form', 'onsubmit'=>'return confirm("确定提交修改?");')) !!}
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 35px">
                <h1 class="page-header">广告管理</h1>

                {{--new blog link--}}
                <div class="col-lg-2 col-md-3 col-sm-4 pull-right clearfix text-right">
                    {{ link_to('admin/advsetting/createimage', '新建', ['class'=>'btn btn-second']) }}
                    <input class="btn btn-default" type="submit" value="提交" />
                </div>

                {{--flash alert--}}
                @if ($success = Session::get('status'))
                    <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >
                        <p class="bg-success">
                            {{ $success }}
                        </p>
                    </div>
                @endif

                @if(count($advsettings)>0)
                    @if ( Auth::user()->hasAnyRole(['super_admin', 'admin','chef_editor', 'main_editor', 'adv_editor']) )
                        <div class="col-md-12">还可以再置顶{{ (6 - $totalTop) > 0 ? (6 - $totalTop) : 0 }}篇广告</div>
                    @endif
                    <table class="table">
                        <thead>
                            <tr>
                                <th>置顶</th>
                                <th>标题</th>
                                <th>位置</th>
                                <th>类型</th>
                                <th>状态</th>
                                {{--<th>日期</th>--}}
                                <th>顺序</th>
                                <th>删除</th>
                                <th>编辑</th>
                            </tr>
                        </thead>

                            <tbody>
                                @foreach($advsettings as $advsetting)
                                    <tr>
                                        <td>
                                            @if ($advsetting->status == 4)
                                            <input class="articl_top" id="article_{{ $advsetting->id }}" type="checkbox"
                                                   @if ($advsetting->top)
                                                   checked
                                                   @elseif ($totalTop >= 6)
                                                   disabled
                                                   @endif
                                                   name="top[{{ $advsetting->id }}]" />
                                            @endif
                                        </td>
                                        <td><a href="{{ url('/admin/advsetting/show/'.$advsetting->id) }}" class="">{{ str_limit($advsetting->title, 20) }}</a> </td>
                                        <td><span>{{ $advsetting->adv_positions->name }}</span></td>
                                        <td><span>{{ $advsetting->adv_types->name }}</span></td>
                                        <td><span>
                                            @if ($advsetting->status == 1 || $advsetting->status == 0) 草稿
                                                @elseif($advsetting->status == 2) 初审
                                                @elseif($advsetting->status == 3) 终审
                                                @elseif($advsetting->status == 4) 发布
                                                @endif
                                            </span></td>
{{--                                        <td><span>{{ date('Y-m-d', strtotime($advsetting->published_at)) }}</span></td>--}}
{{--                                        <td>{{ $advsetting->order }}</td>--}}
                                        <td><input type="text" name="order[{{ $advsetting->id }}]" value="{{ $advsetting->order }}" style="width:30px;"/></td>
                                        <td><input type="checkbox" name="delete[{{ $advsetting->id }}]" ng-click="confirmDelete('{{ $advsetting->id }}')"/></td>
                                        <td>
                                            <a href="{{ url('admin/advsetting/editimage/'.$advsetting->id) }}" class="btn btn-default">编辑</a>
                                         </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {!! Form::token() !!}
                        {!! Form::close() !!}
                    </table>
                    <div class="col-lg-2 col-md-3 col-sm-4 pull-right clearfix text-right">
                        <input class="btn btn-default" type="submit" value="提交" />
                    </div>
                @endif
                <div class="col-lg-12 col-md-12 col-sm-12 clearfix">
                    <span class="totalpage pagination">广告总数：{{ ($totalAdvs) }}篇</span>  {!! $advsettings->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
