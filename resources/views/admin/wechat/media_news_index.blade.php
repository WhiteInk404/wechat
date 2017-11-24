@extends('layouts.app')
@section('title', '素材库-图文列表')

@section('css')
    @include('admin.wechat.css')
    <link href="/assets/admin/wechat/css/media_news.css" rel="stylesheet">
@endsection

@section('content')
<section id="main-content" class="content">
    <ul id="myTab" class="nav nav-tabs">
        <li class="active"><a href="#image_list">图文</a></li>
        <li><a href="/admin/wechat/media/{{ $type }}/cate">分类</a></li>
    </ul>
    <div class="tab-pane" id="news_list">
        <input  id="_token" type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="row">
                            <div class="col-lg-2">
                                <select class="form-control has-success" select_search>
                                    <option value="0" @if(empty($request['mcid'])) selected="selected"@endif>全部分类
                                    </option>
                                    @foreach($cates as $item)
                                        <option value="{{ $item->id }}"
                                                @if (!empty($request['mcid']) && $request['mcid'] == $item->id) selected="selected"@endif>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2" style="float:right">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <a href="/admin/wechat/media/news/create" style="float:right">
                                        <span class="btn btn-default"><i class="fa fa-plus"
                                                                         style="margin-right:5px;"></i>新增图文</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </header>
                    <div class="panel-body">
                        <div class="news_container">
                            @forelse($news as $item)
                            <div class="news_clomn  delete">
                                <div class="news_clomn_btn">
                                    {{ $item->updated_at }}
                                    <hr>
                                </div>
                                <ul class="media-ul">
                                    {{--@php ($arr = (array)$item->content)
                                    @foreach($arr['articles'] as $v)--}}
                                    @foreach(((array)$item->content)['articles'] as $v)
                                    <li class="media-li">
                                        <div class="picture_bottom">
                                            <img class="picture_bottom_img" id="cell_image" src="{{ $v->image_url }}">
                                            <span><h4>名称：{{ $v->title }}</h4></span>
                                        </div>
                                    @endforeach
                                    </li>
                                </ul>
                                <div class="picture_btn">
                                    <span class="btn btn-w-m btn-link"><a href="/admin/wechat/media/news/{{ $item->id }}/edit"><i class="fa fa-pencil"></i></a></span>
                                    <span class="btn btn-outline btn-default" hw_remove data-id="{{ $item->id }}"><i class="fa fa-trash"></i></span>
                                </div>
                            </div>
                            @empty
                                <h3>
                                    暂无数据<br>
                                </h3>
                            @endforelse
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                {!!$news->appends($request)->render()!!}
                            </div>
                        </div>
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
            self = $(this);
            bootbox.confirm("确定要删除吗？", function (result) {
                if (result) {
                    $.ajax({
                        "url": "/admin/wechat/media/{{ $type }}/news" + self.data("id"),
                        "dataType": "json",
                        "type": "delete",
                        success: function (data) {
                            if (data.error_code == 0) {
                                self.parents('.delete').remove();
                                noty({
                                    text: '删除文章成功',
                                    type: 'success',
                                    timeout: 2000
                                });
                            } else {
                                noty({
                                    text: '删除文章失败，请重试',
                                    type: 'error',
                                    timeout: 2000
                                });
                            }
                        }, error: function (data) {
                            noty({
                                text: '删除文章失败，请重试',
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
