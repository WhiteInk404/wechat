@extends('layouts.app')
@section('title', '素材库-文章')
@section('content')
    <section id="main-content" class="content">
            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#image_list">文章</a></li>
                <li><a href="/admin/wechat/media/{{ $type }}/cate">分类</a></li>
            </ul>
            <div class="tab-pane" id="article_list">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            {{--图片列表--}}
                            <header class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <select class="form-control has-success" select_search>
                                            <option value="0" @if(empty($request['mcid'])) selected="selected"@endif>全部分类</option>
                                            @foreach($cates as $item)
                                                <option value="{{ $item->id }}"
                                                        @if (!empty($request['mcid']) && $request['mcid'] == $item->id) selected="selected"@endif>{{ $item->name }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-lg-2" style="float:right">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <a href="/admin/wechat/media/article/create" style="float:right">
                                                <span class="btn btn-default"><i class="fa fa-plus"
                                                                                 style="margin-right:5px;"></i>添加文章</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </header>
                            <div class="panel-body">
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <th style="width:150px;">文章ID</th>
                                        <th>标题</th>
                                        <th style="width:200px;">发布时间</th>
                                        <th style="width:200px;">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($articles as $item)
                                        <tr class="delete">
                                            <td>{{ $item->id }}</td>
                                            <td>
                                                <a href="/admin/wechat/media/article/{{ $item->id }}" target="_black">{{ $item->name }}</a>
                                            </td>
                                            <td>{{ $item->created_at }}</td>

                                            <td>
                                                <a href="/admin/wechat/media/article/{{ $item->id }}/edit"
                                                   class="btn btn-info btn-xs" title="编辑">
                                                    <i class="fa fa-pencil"></i> 编辑
                                                </a>
                                                <a href="javascript:" class="btn btn-danger btn-xs" data-id="{{ $item->id }}" hw_remove title="删除">
                                                    <i class="fa fa-trash"></i> 删除
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">暂无数据</td>
                                        <tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                {!!$articles->appends($request)->render()!!}
                            </div>
                        </section>
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
                            "url": "/admin/wechat/media/{{ $type }}/article/"+current.attr("data-id"),
                            "dataType": "json",
                            "type": "delete",
                            success: function (data) {
                                if (data.error_code == 0) {
                                    current.parents('.delete').remove();
                                    noty({
                                        text: data.data,
                                        type: 'success',
                                        timeout: 2000
                                    });
                                } else {
                                    noty({
                                        text: data.error_message,
                                        type: 'error',
                                        timeout: 2000
                                    });
                                }
                            }, error: function (data) {
                                noty({
                                    text: '网络异常，请稍后重试',
                                    type: 'error',
                                    timeout: 2000
                                });
                            }
                        });

                    }
                });
            });

            $('[select_search]').change(function () {
                var mcid = $(this).val();
                window.location.href = '/admin/wechat/media/{{ $type }}?mcid=' + mcid;
            })
        })
    </script>
@endsection
