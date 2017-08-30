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
          <div class="box-body">
            <div class="well well-sm">
              <h3>单词本格式说明</h3>
              <p>1. `.apkg` 格式文件请使用 `Anki` 软件导出纯文本格式文件（`.txt`）。</p>
              <p>
                2. 纯文本文件（`.txt`）格式请参考如下格式：<br>
                <pre>anecdote	/ˈænɪkdəʊt/ &lt;br&gt; n.逸事，趣闻 &lt;br&gt;
anniversary	/ænɪˈvɜːsərɪ/ &lt;br&gt; n.周年纪念日 &lt;br&gt;
announcement	/əˈnaʊnsmənt/ &lt;br&gt; n.通告，通知 &lt;br&gt;</pre>
              </p>
            </div>
            <form id="activity_form" enctype="multipart/form-data" class="form-horizontal" action="{{ route('admin.wordbook.upload') }}" method="post">
              <div class="form-group {{ $errors->has('upload') ? ' has-error' : '' }}">
                <label class="col-sm-4 control-label">选择单词本文件</label>
                <div class="col-sm-8">
                  <input type="file" name="upload" class="form-control" accept="text/plain" placeholder="请上传单词本文件">
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