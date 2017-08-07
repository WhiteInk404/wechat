@extends('layouts.app')
@include('vendor.ueditor.assets')
@section('content')
  <style>
    .file-drop-zone {
      height: auto
    }
  </style>
  <div class="content-header">
    <h1>添加活动</h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li class="active">添加活动</li>
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
            <form id="activity_form" class="form-horizontal" action="{{ route('admin.activities.store') }}" method="post">
              <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">活动标题</label>
                <div class="col-sm-10">
                  <input type="text" name="name" class="form-control" placeholder="请输入活动标题" value="{{old('name')}}">
                  @if ($errors->has('name'))
                    <span class="help-block">
                      {{ $errors->first('name') }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group {{ $errors->has('start_time') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">开始时间</label>
                <div class="col-sm-10">
                  <input type="text" name="date" class="form-control pull-right datetimepicker" id="start_time" value="{{old('start_time')}}">
                  @if ($errors->has('start_time'))
                    <span class="help-block">
                      {{ $errors->first('start_time') }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group {{ $errors->has('end_time') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">结束时间</label>
                <div class="col-sm-10">
                  <input type="text" name="date" class="form-control pull-right datetimepicker" id="end_time" value="{{old('end_time')}}">
                  @if ($errors->has('end_time'))
                    <span class="help-block">
                      {{ $errors->first('end_time') }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group {{ $errors->has('covers') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">活动海报</label>
                <div class="col-sm-10">
                  <input type="file" name="upload" id="upload" class="form-control">
                  @if ($errors->has('covers'))
                    <span class="help-block">
                      {{ $errors->first('covers') }}
                    </span>
                  @endif
                  <span class="help-block">
                      <i class="fa fa-info-circle"></i>
                      建议上传图片的大小为： 752*955
                  </span>
                </div>
              </div>
              <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">描述</label>
                <div class="col-sm-10">
                  <textarea name="content" class="form-control">{{old('description')}}</textarea>
                  @if ($errors->has('description'))
                    <span class="help-block">
                        {{ $errors->first('description') }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-info">提交</button>
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
      $(function () {
        $('.datetimepicker').datetimepicker({
          locale: 'zh-CN',
          format: 'YYYY-MM-DD HH:mm'
        });

        $("#upload").fileinput({
          "uploadUrl": "{{ route('admin.activity.upload') }}",
          "language": "zh",
          "uploadAsync": true,
          "uploadExtraData": {
            _token: "{{ csrf_token() }}"
          }
        }).on('fileuploaded', function (event, data, previewId, index) {
          var response = data.response;
          if (response.success) {
            $('#activity_form').append('<input type="hidden" name="pic_url" value="' + response.data.path + '">');
          } else {
            alert('上传失败');
          }
        }).on('filesuccessremove', function (event, id) {
          var index = $('#zoom-' + id).data('fileindex');
          $('input[name="pic_url"]').remove();
        });
      });
  </script>
@endsection