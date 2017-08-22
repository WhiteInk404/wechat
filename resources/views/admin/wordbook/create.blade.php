@extends('layouts.app')
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
            <form id="activity_form" enctype="multipart/form-data"  class="form-horizontal" action="{{ route('admin.wordbook.upload') }}" method="post">
              <div class="form-group {{ $errors->has('upload') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">上传单词本文件</label>
                <div class="col-sm-10">
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
      $(function () {
        $('.datetimepicker').datetimepicker({
          locale: 'zh-CN',
          format: 'YYYY-MM-DD HH:mm'
        });

        var preview_path = "{{old('pic_url') ? env('QINIU_DOMAIN').old('pic_url'): ''}}";

        var preview_config = [{
          caption: "{{old('pic_url')}}", // 展示的文件名
          url: '{{ route("admin.activity.remove_pic") }}', // 删除url
          type: 'image',
          key: preview_path, // 删除时Ajax向后台传递的参数
          width: '120px',
          filetype: 'image/jpg',
          extra: {_token: '{{ csrf_token() }}'}
        }];

        var preview = [];
        if (preview_path) {
          preview.push(preview_path);
          var pic_url = "{{old('pic_url')}}";
          $('#activity_form').append('<input type="hidden" name="pic_url" value="' + pic_url + '">');
        }

        $("#upload").fileinput({
          "uploadUrl": "{{ route('admin.activity.upload') }}",
          "language": "zh",
          "initialPreviewAsData": true,
          "overwriteInitial": false,
          "initialPreviewFileType": 'image',
          "initialPreview": preview,
          'initialPreviewConfig': preview_config,
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