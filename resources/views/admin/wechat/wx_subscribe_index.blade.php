@extends('layouts.app')
@section('title', '自动回复')

@section('css')
    @include('admin.wechat.css')
    <link href="/assets/admin/wechat/css/media_news.css" rel="stylesheet">
@endsection

@section('content')
<section id="main-content" class="content">
    <div class="row">
        <input  id="_token" type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-lg-12">
            <section class="panel">
                <div class="ibox-title">
                    设置
                    @if($actionType == 'subscribe')
                        关注
                    @elseif($actionType == 'nomatch')
                        默认
                    @else
                        自定义
                    @endif
                    回复
                </div>
                <div class="ibox-content">
                    <div class=" form" id="reply-form">
                        <reply-form :form-data="formData"></reply-form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    @include('admin.wechat.js')
<script>
    var actionUrl = '{{ $actionUrl }}';
    var actionType = '{{ $actionType }}';
    @if ($reply)
        var replyType = '{{ $reply->reply_type }}';
        var id = {{ $reply->id }};
        var mid = {{ $reply->mid }};
        var content = {!! json_encode($reply->content) !!};
        var keyword = '{{ $reply->keyword }}';
        var matchType = '{{ $reply->match_type }}';
        var status = '{{ $reply->status }}';
    @else
        var replyType = '6';
        var id = 0;
        var mid = 0;
        var content = '';
        var keyword = '';
        var matchType = '1';
        var status = '1';
    @endif
</script>
<script src="/assets/admin/wechat/js/bootbox.min.js"></script>
<script src="/assets/admin/wechat/js/jquery.noty.min.js"></script>
<script src="/assets/admin/wechat/js/wx_reply_form_build.js"></script>
@endsection

