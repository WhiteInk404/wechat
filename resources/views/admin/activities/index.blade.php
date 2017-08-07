@extends('layouts.app')
@section('content')
  <div class="content-header">
    <h1>活动列表</h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li class="active">活动列表</li>
    </ol>
  </div>
  <div class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <!--<h3 class="box-title"></h3>-->
            <a href="{{ route('admin.activities.create') }}" class="btn btn-info btn-xs">添加活动</a>
          </div>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover ">
              <thead>
              <tr>
                <th>ID</th>
                <th>活动名称</th>
                <th>开始时间</th>
                <th>结束时间</th>
                <th>标签</th>
                <th>参与队伍数量</th>
                <th>操作</th>
              </tr>
              </thead>
              <tbody>
              @foreach($activities as $activity)
                <tr>
                  <td>{{ $activity->id }}</td>
                  <td>{{ $activity->name }}</td>
                  <td>{{ $activity->begin_time }}</td>
                  <td>{{ $activity->end_time }}</td>
                  <td>{{ $activity->labels }}</td>
                  <td>{{ $activity->teams()->count() }}</td>
                  <td>
                    <a href="{{ route('admin.activities.show',['id'=>$activity->id]) }}"
                       class="btn btn-info btn-xs">查看</a>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          <div class="box-footer">
            {{ $activities->links() }}
          </div><!-- box-footer -->
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
      $(function () {
        $('.grid-switch-status').bootstrapSwitch({
          size: 'mini',
          onText: '开启',
          offText: '关闭',
          onColor: 'success',
          offColor: 'danger',
          onSwitchChange: function (event, state) {
            $(this).val(state ? '1' : '3');
            var pk = $(this).data('key');
            var value = $(this).val();
            $.ajax({
              url: "/admin/activities/change_states/" + pk,
              type: "POST",
              data: {
                states: value,
                _token: "{{csrf_token()}}",
                _method: 'POST'
              },
              success: function (data) {

              }
            });
          }
        });
      });
  </script>
@endsection