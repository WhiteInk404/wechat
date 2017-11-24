@extends('layouts.app')

@section('title', '素材库-文章-添加文章')

@section('css')
    @include('admin.wechat.css')
    <link href="/js/plugins/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<section id="main-content" class="content">
    <input  id="_token" type="hidden" name="_token" value="{{ csrf_token() }}">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <div class="ibox-title">
                        @if ($action == 'add')添加@else编辑@endif文章
                    </div>
                    <div class="ibox-content">
                        <div class=" form">
                            <form class="cmxform form-horizontal tasi-form" method="post" id="articleForm"
                                  action="/admin/wechat/media/article" enctype="multipart/form-data">
                                <input type="hidden" name="id" id="id" value="{{ $id?$id:'' }}">
                                <input type="hidden" name="action" id="action" value="{{ $action?$action:'' }}">
                                <div class="form-group ">
                                    <label for="title" class="control-label col-lg-2">标题</label>
                                    <div class="col-lg-6">
                                        <input class="form-control" type="text" id="name" name="name"
                                               value="{{ isset($article->name)?$article->name:'' }}"
                                               maxlength="50" required/>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="title" class="control-label col-lg-2">分类</label>
                                    <div class="col-lg-6">
                                        <select class="form-control" id="mcid" name="mcid">
                                            @if ($cates)
                                                @foreach($cates as $cate)
                                                    <option value="{{ $cate->id }}"
                                                            @if (isset($article) && $article->mcid == $cate->id)
                                                            selected
                                                            @endif
                                                    >{{ $cate->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="title" class="control-label col-lg-2">内容</label>
                                    <div class="col-lg-10">
                                        <textarea style='height: 280px;border: 1px'id="content"  name="content" type="text" required>{{isset($article->content->content)?$article->content->content:''}}
                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="title" class="control-label col-lg-2">时间</label>
                                    <div class="col-lg-6">
                                        <input class="form-control" type="text" id="datetimepicker" name="atime"
                                               value="{{ isset($article->content->publish_date)?$article->content->publish_date:'' }}" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-primary" type="submit">保存内容</button>
                                        <button class="btn btn-default" type="reset">重置</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
</section>
@endsection

@section('scripts')
    @include('admin.wechat.js')
    <script type="text/javascript" src="/js/plugins/datetimepicker/jquery.datetimepicker.js"></script>
    <script src="/js/plugins/ueditor/ueditor.config.js"></script>
    <script src="/js/plugins/ueditor/ueditor.all.min.js"></script>
    <script src="/js/plugins/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
        var serverPath = "/admin/wechat/media/news/image/upload/ue";
        var editor = UE.getEditor('content', {
            allHtmlEnabled: true,
            allowDivTransToP: false,
            removeFormatAttributes: 'hspace',
            enableAutoSave: false,
            serverUrl:serverPath,
            wordCount:false,
            elementPathEnabled:false,
            placeHolder: '请在这里输入正文',
            removeFormatTags: '',
            toolbars: [[
                'source', '|', 'undo', 'redo', '|',
                'bold', 'italic', 'underline', '|',
                'lineheight', '|',
                'paragraph', 'fontfamily', 'fontsize', '|',
                'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                'link', 'simpleupload', '|', 'time'
            ]]
        });
        editor.ready(function() {
            //此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
            editor.execCommand('serverparam', '_token', '{{ csrf_token() }}');
        });

    // 时间设置
    $(function(){
        $('#datetimepicker').datetimepicker({ lang:'ch', format: 'Y-m-d H:i:s', dayOfWeekStart: 1,maxDate: new Date()});
    });

        $.noty.defaults.theme = 'relax';
        $("#articleForm").validate({
            submitHandler: function () {
                var data = {
                    id: $('#id').val(),
                    action: $('#action').val(),
                    mcid: $('#mcid').val(),
                    name: $('#name').val(),
                    content: editor.getContent('content'),
                    atime: $('#datetimepicker').val()
                };
                $.post("/admin/wechat/media/article", data, function (data) {
                    if (data.error_code == 0) {
                        noty({
                            text: '{{ $action == 'add'?'添加':'编辑' }}文章成功，跳转至列表',
                            type: 'success',
                            timeout: 2000,
                            callback: {
                                afterClose: function () {
                                    window.location.href = '/admin/wechat/media/article';
                                }
                            }
                        });
                    } else {
                        noty({
                            text: '{{ $action == 'add'?'添加':'编辑' }}文章失败，请重试',
                            type: 'error',
                            timeout: 2000
                        });
                    }
                }, "json")
            }
        });
</script>
@endsection
