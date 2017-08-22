@extends('layouts.app')
@section('content')
  <style>
    .file-drop-zone {
      height: auto
    }
  </style>
  <div class="content-header">
    <h1>上传单词本</h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li class="active">上传单词本</li>
    </ol>
  </div>
  <div class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"></h3>
          </div>
          <div class="box-body">
            <form id="activity_form" enctype="multipart/form-data" class="form-horizontal" action="{{ route('admin.wordbook.upload') }}" method="post">
              <div class="form-group {{ $errors->has('upload') ? ' has-error' : '' }}">
                <label class="col-sm-4 control-label">选择单词本文件</label>
                <div class="col-sm-8">
                  <input type="file" name="upload" class="form-control" placeholder="请上传单词本文件">
                  @if ($errors->has('upload'))
                    <span class="help-block">
                      {{ $errors->first('upload') }}
                    </span>
                  @endif
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-info">上传</button>
                </div>
              </div>
            </form>
          </div>
          <div class="box-footer">
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