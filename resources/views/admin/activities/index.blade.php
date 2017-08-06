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
            <h3 class="box-title"></h3>
          </div>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover ">
              <thead>
              <tr>
                <th>ID</th>
                <th>专属用户</th>
                <th>活动名称</th>
                <th>活动日期</th>
                <th>活动地址</th>
                <th>活动费用</th>
                <th>联系人手机</th>
                <th>可报名人数</th>
                <th>封面</th>
                <th>群聊二维码</th>
                <!--<th>活动状态</th>-->
                <th>创建时间</th>
                <th>最后修改时间</th>
                <th>操作</th>
              </tr>
              </thead>
              <tbody>
              @foreach($activities as $activity)
                <tr>
                  <td>{{ $activity->id }}</td>
                  <td>{{ $activity->user ? $activity->user->name.'（ID：'.$activity->user_id.'）' : '无' }} </td>
                  <td>{{ $activity->name }}</td>
                  <td>{{ $activity->date }}</td>
                  <td>{{ $activity->address }}</td>
                  <td>{{ $activity->cost_friendly }}</td>
                  <td>{{ $activity->contact_phone }}</td>
                  <td>{{ $activity->total_num }}</td>
                  <td>
                    @foreach($activity->covers as $cover)
                      @if($cover->type == 1)
                      <img src="{{ $cover->full_path.'?imageView2/2/w/274/h/348/q/75|imageslim' }}" height="80" alt="">
                      @else
                      <video style="vertical-align: middle" height="80" controls="">
                        <source src="{{ $cover->full_path }}" type="video/mp4">
                      </video>
                      @endif
                    @endforeach
                  </td>
                  <td>
                    @if($activity->qr_code)
                      <img src="{{ $activity->qr_code }}" height="80" alt="">
                    @endif
                  </td>
                  <!--<td>
                    @if($activity->states == 1)
                      <input type="checkbox" class="grid-switch-status" data-key="{{$activity->id}}" checked/>
                    @elseif($activity->states == 2)
                      <span class="label-warning">已满</span>
                    @elseif($activity->states == 3)
                      <input type="checkbox" class="grid-switch-status" data-key="{{$activity->id}}"/>
                    @endif
                  </td>-->
                  <td>{{ $activity->created_at }}</td>
                  <td>{{ $activity->updated_at }}</td>
                  <td>
                    <form style="display: inline-block" action="{{ route('admin.activities.destroy',['id' =>$activity->id]) }}" method="post">
                      {{ csrf_field() }}{{ method_field('delete') }}
                      <input type="submit" class="btn btn-danger btn-xs" value="删除">
                    </form>
                    <a href="{{ route('admin.activities.edit',['id'=>$activity->id]) }}"
                       class="btn btn-info btn-xs">修改</a>
                    <a href="{{ route('admin.activities_orders.index', ['activity_id' => $activity->id]) }}" class="btn btn-success btn-xs">报名列表</a>
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
              size:'mini',
              onText: '开启',
              offText: '关闭',
              onColor: 'success',
              offColor: 'danger',
              onSwitchChange: function(event, state){
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