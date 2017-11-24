@extends('layouts.app')
@section('title', '素材库-分类')
@section('css')
    @include('admin.wechat.css')
@endsection
@section('content')
<section id="main-content" class="content">
        <ul id="myTab" class="nav nav-tabs">
            @if ($type == 'article')
                <li><a href="/admin/wechat/media/article">文章</a></li>
            @elseif ($type == 'image')
                <li><a href="/admin/wechat/media/image">图片</a></li>
            @elseif ($type == 'voice')
                <li><a href="">音频</a></li>
            @elseif ($type == 'news')
                <li><a href="/admin/wechat/media/news">图文</a></li>
            @endif
            <li class="active"><a href="#image_cate" data-toggle="tab">分类</a></li>
        </ul>
        {{--分类--}}
        <div class="tab-pane" id="image_cate">
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="row">
                                <div class="col-lg-2">
                                    <h4>分类列表</h4>
                                </div>
                                <div class="col-lg-2" style="float:right">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <a href="/admin/wechat/media/{{ $type }}/cate/create" style="float:right">
                                                <span class="btn btn-default"><i class="fa fa-plus"
                                                                                 style="margin-right:5px;"></i>添加分类</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </header>
                        <div class="panel-body">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th style="width:150px;">分类ID</th>
                                    <th>分类名称</th>
                                    <th style="width:200px;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($cates as $item)
                                    <tr class="delete">
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            <a href="/admin/wechat/media/{{ $item->id }}/cate/{{ $type }}/edit"
                                               class="btn btn-primary btn-xs">
                                                <i class="fa fa-pencil">&nbsp;编辑</i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-xs" data-id="{{ $item->id }}"
                                               hw_remove>
                                                <i class="fa fa-trash-o">&nbsp;删除</i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
                        "url": "/admin/wechat/media/{{ $type }}/cate/"+current.attr("data-id"),
                        "dataType": "json",
                        "type": "delete",
                        success: function (data) {
                            if (data.error_code == 0) {
                                current.parents('.delete').remove();
                                noty({
                                    text: '删除成功',
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
                                text: data.error_message,
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
