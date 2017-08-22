@extends('layouts.app')
@section('content')
  <div class="content-header">
    <h1>单词本列表</h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li class="active">单词本列表</li>
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
                <th>ID</th>
                <th>单词本名称</th>
                <th>创建时间</th>
                <th>单词数</th>
                <th>排序</th>
                <th>操作</th>
              </tr>
              </thead>
              <tbody>
              @foreach($wordbooks as $wordbook)
                <tr>
                  <td>{{ $wordbook->id }}</td>
                  <td>{{ $wordbook->name }}</td>
                  <td>{{ $wordbook->created_at }}</td>
                  <td> <a href="{{ route('admin.wordbook.contents',['id'=>$wordbook->id]) }}">{{ $wordbook->contents()->count() }}</a></td>
                  <td>
                    <form style="display: inline-block" onsubmit="if(!confirm('确定修改排序？')){return false;}" action="{{ route('admin.wordbook.sort',['id'=>$wordbook->id]) }}" method="post">
                      {{ csrf_field() }}{{ method_field('put') }}
                      <input class="sort" name="sort" type="text" value="{{ $wordbook->sort }}">
                    </form>
                  </td>
                  <td>
                    <form style="display: inline-block" onclick="if(!confirm('确定删除？')){return false;}" action="{{ route('admin.wordbook.destroy',['id' =>$wordbook->id]) }}" method="post">
                      {{ csrf_field() }}{{ method_field('delete') }}
                      <input type="submit" class="btn btn-danger btn-xs" value="删除">
                    </form>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          <div class="box-footer">
            {{ $wordbooks->links() }}
          </div><!-- box-footer -->
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(function () {
      $('input.sort').change(function () {
        $(this).parents('form').submit();
      });
    })
  </script>
@endsection