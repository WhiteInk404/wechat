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
              <p>1. 请使用 Excel，支持后缀为：`.xls`、`.xlsx`</p>
                <p>2. Excel 文件名即为单词本名称，可能用于发送提醒时的单词名</p>
                <p>3. 为了统一，每个 Excel 文件里只读取第一个 sheet。sheet 名称不重要。</p>
                <p>4. 为了保证单词的音标和释义排版的准确度，释义之前请确保包含`adj.`、`n.` 等词性代号，以下为目前全部，如有缺失，请联系补充：</p>
                <pre>n. 名词 v. 动词 pron. 代词 adj. 形容词 a. 形容词 adv. 副词 ad. 副词 num. 数词 art. 冠词 prep. 介词 conj. 连词 interj. 感叹词 int. 感叹词 vi. 不及物动词 vt. 及物动词 u. 不可数名词 c. 可数名词 cn. 可数名词 pl. 复数 abbr. 略语 aux. 助动词 pers. 人称代词</pre>
              </p>
            </div>
            <form id="activity_form" enctype="multipart/form-data" class="form-horizontal" action="{{ route('admin.wordbook.upload') }}" method="post">
              <div class="form-group {{ $errors->has('upload') ? ' has-error' : '' }}">
                <label class="col-sm-4 control-label">选择单词本文件</label>
                <div class="col-sm-8">
                  <input type="file" name="upload" class="form-control" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" placeholder="请上传单词本文件">
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