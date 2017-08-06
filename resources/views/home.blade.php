@extends('layouts.app')

@section('content')
<div class="content-header">
  <h1>
    管理后台
    <small> 1.0</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">Dashboard</li>
  </ol>
</div>
<div class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">box title</h3>
        </div>
        <div class="box-body">
          box body 示例
        </div>
        <div class="box-footer">
          The footer of the box
        </div><!-- box-footer -->
      </div>
    </div>
  </div>
</div>

@endsection
