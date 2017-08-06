@extends('layouts.app')
@include('vendor.ueditor.assets')
@section('content')
  <style>
    .file-drop-zone {
      height: auto
    }
  </style>
  <div class="content-header">
    <h1>修改活动</h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li class="active">修改活动</li>
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
            <form id="activity-form" class="form-horizontal" action="{{ route('admin.activities.update',['id' =>$activity->id]) }}" method="post">
              <div class="form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">用户名（ID）</label>
                <div class="col-sm-10">
                  <select name="user_id" class="form-control">
                    <option value="">请选择用户...</option>
                    @foreach($users as $user)
                      <option {{ $activity->user_id == $user->id ? "selected" : (request('user_id') == $user->id ? "selected" : "")}} value="{{ $user->id }}">{{ $user->name }}（ID:{{ $user->id }}）</option>
                    @endforeach
                  </select>
                  @if ($errors->has('user_id'))
                    <span class="help-block">
                      {{ $errors->first('user_id') }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">活动标题</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" value=" {{ $activity->name }}" name="name">
                  @if ($errors->has('name'))
                    <span class="help-block">
                      {{ $errors->first('name') }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group {{ $errors->has('date') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">活动日期</label>
                <div class="col-sm-10">
                  <input type="text" name="date" class="form-control pull-right" id="datetimepicker" value="{{ $activity->date }}">
                  @if ($errors->has('date'))
                    <span class="help-block">
                      {{ $errors->first('date') }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">活动地址</label>
                <div class="col-sm-10">
                  <input type="text" name="address" class="form-control" placeholder="请输入活动地址" value="{{$activity->address }}">
                  @if ($errors->has('address'))
                    <span class="help-block">
                      {{ $errors->first('address') }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group {{ $errors->has('cost') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">活动费用</label>
                <div class="col-sm-10">
                  <input type="text" name="cost" class="form-control" placeholder="请输入活动费用" value="{{ $activity->cost_friendly }}">
                  @if ($errors->has('cost'))
                    <span class="help-block">
                      {{ $errors->first('cost') }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group {{ $errors->has('contact_phone') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">联系人号码</label>
                <div class="col-sm-10">
                  <input type="text" name="contact_phone" class="form-control" placeholder="请输入联系人号码" value="{{ $activity->contact_phone }}">
                  @if ($errors->has('contact_phone'))
                    <span class="help-block">
                      {{ $errors->first('contact_phone') }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group {{ $errors->has('total_num') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">可参与人总数</label>
                <div class="col-sm-10">
                  <input type="text" name="total_num" class="form-control" placeholder="请输入可参与人总数" value="{{ $activity->total_num }}">
                  @if ($errors->has('total_num'))
                    <span class="help-block">
                      {{ $errors->first('total_num') }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group {{ $errors->has('covers') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">封面介绍</label>
                <div class="col-sm-10">
                  <input type="file" name="covers_upload" id="covers-upload" multiple class="form-control">
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
              <div class="form-group {{ $errors->has('qr_code') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">群聊二维码</label>
                <div class="col-sm-10">
                  <input id="qrcode-upload" name="qrcode_upload" type="file" class="form-control">
                  @if ($errors->has('qr_code'))
                    <span class="help-block">
                      {{ $errors->first('qr_code') }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group {{ $errors->has('summary') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">内容摘要（用于分享）</label>
                <div class="col-sm-10">
                  <textarea name="summary" rows="5" class="form-control">{{old('summary')?:$activity->content->summary}}</textarea>
                  @if ($errors->has('summary'))
                      <span class="help-block">
                        {{ $errors->first('summary') }}
                      </span>
                  @endif
                </div>
              </div>
              <div class="form-group {{ $errors->has('content') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">内容</label>
                <div class="col-sm-10">
                  <script id="container" name="content" type="text/plain">{!! old('content')?:$activity->content->body !!}</script>
                  <!--<textarea name="content" class="form-control">{{old('content')}}</textarea>-->
                  @if ($errors->has('content'))
                    <span class="help-block">
                      {{ $errors->first('content') }}
                    </span>
                  @endif
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  {{ csrf_field() }}
                  {{ method_field('put') }}
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
        var ue = UE.getEditor('container', {
          toolbars: [
            [
              'anchor', //锚点
              'undo', //撤销
              'redo', //重做
              'bold', //加粗
              'indent', //首行缩进
              'snapscreen', //截图
              'italic', //斜体
              'underline', //下划线
              'strikethrough', //删除线
              'subscript', //下标
              'fontborder', //字符边框
              'superscript', //上标
              'formatmatch', //格式刷
              'source', //源代码
              'blockquote', //引用
              'pasteplain', //纯文本粘贴模式
              'selectall', //全选
              'preview', //预览
              'horizontal', //分隔线
              'removeformat', //清除格式
              'time', //时间
              'date', //日期
              'unlink', //取消链接
              'cleardoc', //清空文档
              'insertcode', //代码语言
              'fontfamily', //字体
              'fontsize', //字号
              'paragraph', //段落格式
              'simpleupload', //单图上传
              'insertimage', //多图上传
              'link', //超链接
              'insertvideo', //视频
              'help', //帮助
              'justifyleft', //居左对齐
              'justifyright', //居右对齐
              'justifycenter', //居中对齐
              'justifyjustify', //两端对齐
              'forecolor', //字体颜色
              'backcolor', //背景色
              'insertorderedlist', //有序列表
              'insertunorderedlist', //无序列表
              'fullscreen', //全屏
              'directionalityltr', //从左向右输入
              'directionalityrtl', //从右向左输入
              'rowspacingtop', //段前距
              'rowspacingbottom', //段后距
              'pagebreak', //分页
              'imagenone', //默认
              'imageleft', //左浮动
              'imageright', //右浮动
              'attachment', //附件
              'imagecenter', //居中
              'wordimage', //图片转存
              'lineheight', //行间距
              'edittip ', //编辑提示
              'autotypeset' //自动排版
            ]
          ]
        });
        ue.ready(function () {
          ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });

        $('#datetimepicker').datetimepicker({
          locale: 'zh-CN',
          format: 'YYYY-MM-DD HH:mm'
        });

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue'
        });

        var activity = JSON.parse('{!! $activity_json !!}')
        cover_previes_list = [];
        cover_preview_config = [];
        covers = activity.covers
        if (covers) {
          for (i = 0; i < covers.length; i++) {
            var cover = covers[i]
            var html = cover.full_path;
            cover_previes_list.push(html);
            config = {
              caption: cover.path, // 展示的文件名
              url: '{{ route("admin.covers.delete") }}', // 删除url
              type: cover.type == 1 ? 'image' : 'video',
              key: cover.id, // 删除时Ajax向后台传递的参数
              width: '120px',
              filetype: cover.type == 1 ? 'image/jpg' : "video/mp4",
              extra: {id: cover.id, _token: '{{ csrf_token() }}'}
            }
            cover_preview_config.push(config);
          }
        }
        $("#covers-upload").fileinput({
          "uploadUrl": "{{ route('admin.covers.upload') }}",
          "language": "zh",
          "uploadAsync": true,
          "purifyHtml": true,
          "initialPreviewAsData": true,
          "overwriteInitial": false,
          "initialPreviewFileType": 'image',
          "initialPreview": cover_previes_list,
          'initialPreviewConfig': cover_preview_config,
          "uploadExtraData": {
            _token: "{{ csrf_token() }}"
          }
        }).on('fileuploaded', function (event, data, previewId, index) {
          var response = data.response;
          if (response.success) {
            $('#activity-form').append('<input type="hidden" name="covers[' + index + '][path]" value="' + response.data.path + '">');
            $('#activity-form').append('<input type="hidden" name="covers[' + index + '][mime_type]" value="' + response.data.mime_type + '">');
          } else {
            alert('上传失败');
          }
        }).on('filesuccessremove', function (event, id) {
          var index = $('#zoom-' + id).data('fileindex');
          $('input[name="covers[' + index + ']"]').remove();
        });
        qu_code = activity.qr_code
        previes_list = [];
        previes_config = [];
        if (qu_code) {
          previes_list.push(qu_code);
          previes_config.push(
              {
                caption: qu_code, // 展示的文件名
                type: 'image',
                url: '{{ route("admin.activities.qrcode_delete") }}', // 删除url
                key: activity.id, // 删除时Ajax向后台传递的参数
                extra: {id: activity.id, _token: '{{ csrf_token() }}'}
              });
        }
        $("#qrcode-upload").fileinput({
          "uploadUrl": "{{ route('admin.activities.qrcode_upload') }}",
          "language": "zh",
          "uploadAsync": true,
          "initialPreviewAsData": true,
          "overwriteInitial": false,
          "initialPreview": previes_list,
          "initialPreviewConfig": previes_config,
          "uploadExtraData": {
            _token: "{{ csrf_token() }}"
          }
        }).on('fileuploaded', function (event, data, previewId, index) {
          var response = data.response;
          if (response.success) {
            $('#activity-form').append('<input type="hidden" name="qr_code" value="' + response.data.path + '">');
          } else {
            alert('上传失败');
          }
        }).on('filesuccessremove', function (event, id) {
          $('input[name="qr_code"]').remove();
        });
      });
  </script>
@endsection