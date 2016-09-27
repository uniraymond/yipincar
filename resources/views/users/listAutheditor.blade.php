@if(count($authUsers)>0)
    <table class="table table-striped">
        <thead>
        <tr>
            <th>用户名</th>
            <th>电子邮件</th>
            <th>编辑</th>
            <th>屏蔽</th>
            <th>删除</th>
        </tr>
        </thead>
        <tbody>
        @foreach($authUsers as $user)
                <tr>
                    <td><a href="{{ url('/admin/profile/'.$user->id) }}">{{ $user->name }}</a></td>
                    <td><span id="email_{{ $user->id }}">{{ $user->email }}</span></td>

                    <td><a class="btn btn-default" href="/admin/user/{{ $user->id }}/edit" id="editBtn_{{ $user->id }}">编辑</a></td>

                    <td>
                        @if ($user->banned)
                            {!! Form::open(array('url' => 'admin/user/'.$user->id.'/active', 'class' => 'form', 'method'=>'put', 'onsubmit'=>'return confirm("确定屏蔽这个账号?");')) !!}
                            {!! Form::text('id', $user->id, array('hidden'=>'hidden', 'readonly' => true)) !!}
                            {!! Form::submit('恢复', array('class'=>'btn btn-primary')) !!}
                        @else
                            {!! Form::open(array('url' => 'admin/user/'.$user->id.'/banned', 'class' => 'form', 'method'=>'put', 'onsubmit'=>'return confirm("确定屏蔽这个账号?");')) !!}
                            {!! Form::text('id', $user->id, array('hidden'=>'hidden', 'readonly' => true)) !!}
                            {!! Form::submit('屏蔽', array('class'=>'btn btn-warning')) !!}
                        @endif
                        {!! Form::token() !!}
                        {!! Form::close() !!}
                    </td>
                    <td>
                        {!! Form::open(array('url' => 'admin/user/'.$user->id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("确定删除这个账号?");')) !!}
                        {!! Form::text('id', $user->id, array('hidden'=>'hidden', 'readonly' => true)) !!}
                        {!! Form::submit('删除', array('class'=>'btn btn-danger')) !!}
                        {!! Form::token() !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
        @endforeach
        </tbody>
    </table>
@endif
