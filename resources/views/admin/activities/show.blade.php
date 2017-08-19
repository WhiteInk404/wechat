@extends('layouts.app')
@section('content')
  <div class="content-header">
    <h1>活动详情</h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li class="active">活动详情</li>
    </ol>
  </div>
  <div class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <!--<h3 class="box-title"></h3>-->
            <a href="{{ route('admin.activities.edit',['id'=>$activity->id]) }}" class="btn btn-info btn-xs">编辑活动</a>
            <form style="display: inline-block" onclick="if(!confirm('确定删除？')){return false;}" action="{{ route('admin.activities.destroy',['id' =>$activity->id]) }}" method="post">
              {{ csrf_field() }}{{ method_field('delete') }}
              <input type="submit" class="btn btn-danger btn-xs" value="删除">
            </form>
          </div>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover ">
              <thead>
              <tr>
                <th>活动名称</th>
                <th>开始时间</th>
                <th>结束时间</th>
                <th>海报</th>
              </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{ $activity->name }}</td>
                  <td>{{ $activity->begin_time }}</td>
                  <td>{{ $activity->end_time }}</td>
                  <td> <img src="{{ $activity->full_pic_url }}" alt=""></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="box-footer">
          </div><!-- box-footer -->
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">参与队伍排名（数量{{$activity->teams()->count()}}）</h3>
          </div>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover ">
              <thead>
              <tr>
                <th>排名</th>
                <th>团队名</th>
                <th>参与人数</th>
              </tr>
              </thead>
              <tbody>
              @foreach($teams as $key => $team)
                <tr>
                  <td>{{ $key + $per_page*($page-1) + 1 }}</td>
                  <td>{{ $team->name }}</td>
                  <td>{{ $team->count }}</td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          <div class="box-footer">
            {{ $teams->links() }}
          </div><!-- box-footer -->
        </div>
      </div>
    </div>
    
    
  </div>
@endsection
@section('scripts')
  <script>
  
  </script>
@endsection