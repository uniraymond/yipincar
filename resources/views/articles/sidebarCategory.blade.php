<div class="collapse navbar-collapse navbar-toggleable-sm navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav list-group">
        <li class="list-group-item">
            <a href="javascript:;" data-toggle="collapse" data-target="#category_sidenave" class="@if($currentAction != 'category') collapsed @endif" aria-expanded="@if($currentAction == 'category') true @else false @endif">
                <i class="fa fa-fw fa-arrows-v"></i> 展开类别 <i class="fa fa-fw fa-caret-down"></i>
            </a>
            <ul class="collapse @if($currentAction == 'category') in @endif" id="category_sidenave" aria-expanded="@if($currentAction == 'category') true @else false @endif" style="height: auto;">
                <li class="list-group-item"><a href="{{ url('admin/article') }}"><i class="fa fa-fw fa-desktop"></i>All</a></li>
                @foreach($categories as $category)
                    <li class="list-group-item">
                        <a href="{{ url('admin/article/category/'.$category->id) }}"><i class="fa fa-fw fa-desktop"></i> {{ $category->description }}</a>
                    </li>
                @endforeach
            </ul>
        </li>

        <li class="list-group-item">
            <a href="javascript:;" data-toggle="collapse" data-target="#type_sidenave" class="@if($currentAction != 'type') collapsed @endif" aria-expanded="@if($currentAction == 'type') true @else false @endif">
                <i class="fa fa-fw fa-arrows-v"></i> 展开类型 <i class="fa fa-fw fa-caret-down"></i>
            </a>
            <ul class="collapse @if($currentAction == 'type') in @endif" id="type_sidenave" aria-expanded="@if($currentAction == 'type') true @else false @endif" style="height: auto;">
                <li class="list-group-item">
                    <a href="{{ url('admin/article') }}"><i class="fa fa-fw fa-desktop"></i>All</a>
                </li>
                @foreach($types as $type)
                    <li class="list-group-item">
                        <a href="{{ url('admin/article/type/'.$type->id) }}"><i class="fa fa-fw fa-desktop"></i> {{ $type->description }}</a>
                    </li>
                @endforeach
            </ul>
        </li>

        <li class="list-group-item">
            <a href="javascript:;" data-toggle="collapse" data-target="#tag_sidenave" class="@if($currentAction != 'tag') collapsed @endif" aria-expanded="@if($currentAction == 'tag') true @else false @endif">
                <i class="fa fa-fw fa-arrows-v"></i> 展开标签 <i class="fa fa-fw fa-caret-down"></i>
            </a>
            <ul class="collapse @if($currentAction == 'tag') in @endif" id="tag_sidenave" aria-expanded="@if($currentAction == 'tag') true @else false @endif" style="height: auto;">
                <li class="list-group-item">
                    <a href="{{ url('admin/article') }}"><i class="fa fa-fw fa-desktop"></i>All Tags</a>
                </li>
                @foreach($tags as $tag)
                    <li class="list-group-item">
                        <a href="{{ url('admin/article/tag/'.$tag->id) }}"><i class="fa fa-fw fa-desktop"></i> {{ $tag->description }}</a>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</div>