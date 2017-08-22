@extends('layouts.app')
@section('content')
  <div class="content-header">
    <h1>单词本列表</h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li><a href="{{ url('/admin/wordbook') }}"><i class="fa fa-list"></i> 单词本列表</a></li>
      <li class="active">单词本内容列表</li>
    </ol>
  </div>
  <div class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <!--<h3 class="box-title"></h3>-->
            <a href="{{ route('admin.wordbook.create') }}" class="btn btn-info btn-xs">上传单词本</a>
          </div>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover ">
              <thead>
              <tr>
                <th>正面</th>
                <th>背面</th>
              </tr>
              </thead>
              <tbody>
              @foreach($contents as $content)
                <tr>
                  <td>{{ $content->facade }}</td>
                  <td>{{ $content->back }}</td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          <div class="box-footer">
            {{ $contents->links() }}
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