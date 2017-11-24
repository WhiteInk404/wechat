@extends('layouts.app')
@section('title', '素材库-图文编辑')

@section('css')
    @include('admin.wechat.css')
    <link href="/assets/admin/wechat/css/media_news.css" rel="stylesheet">
@endsection
@section('content')
<section id="main-content" class="content">
    <div class="tab-pane" id="news_list">
        <input  id="_token" type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel" id="news_form" data-data="{{ isset($data)?$data:'' }}" data-id="{{ isset($id)?$id:'' }}">
                    <news_form :cell-data.sync="cellData" :cell-id="id"></news_form>
                    <div class="bottom"><span class="btn btn-w-m btn-success" @click="newsSave">保存内容</span></div>
                </section>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
    @include('admin.wechat.js')
<!--  ueditor -->
<script src="/js/plugins/ueditor/ueditor.config.js"></script>
<script src="/js/plugins/ueditor/ueditor.all.min.js"></script>
<script src="/js/plugins/ueditor/lang/zh-cn/zh-cn.js"></script>
<script src="/assets/admin/wechat/js/setting_media_news_create_build.js"></script>
<script src="/js/plugins/iCheck/icheck.min.js"></script>
<script>
    $(document).ready(function () {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green'
        });
    });
</script>
@endsection
