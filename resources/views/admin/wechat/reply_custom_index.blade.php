@extends('layouts.app')
@section('title', '自动回复管理')
@section('css')
    @include('admin.wechat.css')
    <link href="/assets/admin/wechat/css/media_news.css" rel="stylesheet">
@endsection

@section('content')
<section id="main-content" class="content">
    <!-- page start-->
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    自定义回复规则列表
                </div>
                <div class="ibox-content">
                    <div class="ibox-tools">
                    <span class="tools pull-right">
                        <a href="/admin/wechat/reply/custom/create" class="btn btn-default" style="color: #FFF"><i class="fa fa-plus"style="margin-right:5px;"></i>添加</a>
                    </span>
                    </div>
                    <div class="adv-table">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>关键字</th>
                                <th class="seventy-five-width">匹配规则</th>
                                <th class="seventy-five-width"> 回复类型</th>
                                <th>回复内容</th>
                                <th class="operate-width">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($replys as $item)
                                <tr class="gradeA" hw_one>
                                    <td>{{ $item->keyword }}</td>
                                    <td>@if($item->match_type == 1)完全匹配@else模糊匹配@endif</td>
                                    <td>@if($item->reply_type == 1)文章
                                        @elseif($item->reply_type == 2)图片
                                        @elseif($item->reply_type == 3)视频
                                        @elseif($item->reply_type == 4)音频
                                        @elseif($item->reply_type == 5)图文
                                        @elseif($item->reply_type == 6)文本
                                        @else
                                        @endif</td>
                                    <td >{{ $item->content }}</td>
                                    <td>
                                        <a href="/admin/wechat/reply/custom/{{ $item->id }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i>&nbsp;编辑</a>
                                        <a href="javascript:;" class="btn btn-danger btn-xs" hw_remove data-id="{{ $item->id }}">
                                            <i class="fa fa-trash">&nbsp;删除</i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-12">
                                {{--{!! $replys->links() !!}--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
    @include('admin.wechat.js')
    <script>
        $(document).ready(function () {
            bootbox.setLocale("zh_CN");
            $('[hw_remove]').click(function () {
                current = $(this);
                bootbox.confirm("确定要删除吗？", function (result) {
                    if (result) {
                        $.ajax({
                            "url": "/admin/wechat/reply/custom/"+current.attr("data-id"),
                            "dataType": "json",
                            "type": "delete",
                            success: function (data) {
                                if (data.error_code == 0) {
                                    current.parents('.gradeA').remove();
                                    noty({
                                        text: '删除成功',
                                        type: 'success',
                                        timeout: 2000
                                    });
                                } else {
                                    noty({
                                        text: '删除失败，请重试',
                                        type: 'error',
                                        timeout: 2000
                                    });
                                }
                            }, error: function (data) {
                                noty({
                                    text: '删除失败，请重试',
                                    type: 'error',
                                    timeout: 2000
                                });
                            }
                        });

                    }

                });
            });

        });
    </script>
@endsection
