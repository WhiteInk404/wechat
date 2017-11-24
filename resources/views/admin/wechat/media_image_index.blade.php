@extends('layouts.app')

@section('title', '素材库-图文')

@section('css')
    @include('admin.wechat.css')
@endsection

@section('content')
<!--main content start-->
<section id="main-content" class="content">
    <input  id="_token" type="hidden" name="_token" value="{{ csrf_token() }}">
    <ul id="myTab" class="nav nav-tabs">
        <li class="active"><a href="#image_list">图片</a></li>
        <li><a href="/admin/wechat/media/{{ $type }}/cate">分类</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane in active panel" id="image_list">
            <header class="panel-heading">
                <div class="row">
                    <div class="col-md-2">
                        <select class="form-control has-success" select_search data-type="{{ $type }}">
                            <option value="0" @if(!$mcid) selected="selected"@endif>全部分类</option>
                            @foreach($cates as $item)
                                <option value="{{ $item->id }}"
                                        @if ($mcid == $item->id) selected="selected"@endif>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" type="text" id="keyword" name="keyword" value="{{ $keyword?$keyword:'' }}" required/>
                    </div>
                    <div class="col-md-2">
                        <a href="#" class="btn btn-primary" btn_search>搜索</a>
                    </div>
                    <div class="col-md-2 col-md-offset-4">
                        <div id="picker"><i class="fa fa-plus">传图</i></div>
                    </div>
                </div>

            </header>
            <div class="panel-body">
                <div class="row">
                    @foreach ($images as $item)
                        <div class="col-lg-3  delete" style="width: 220px;float:left;">
                            <div class="thumbnail">
                                <a href="{{ $item->content->url }}" target="_blank">
                                    <img src="{{ $item->content->url }}" style="height: 148px" alt="">
                                </a>
                                <div class="caption">
                                    <h3 class="text-overflow">{{ $item->name }}</h3>
                                    <p>文件大小:{{ $item->content->size }}k</p>
                                    <p>
                                        <a href="javascript:;" class="btn btn-primary btn-xs" hw_edit
                                           data-id="{{ $item->id }}"
                                           data-name="{{ $item->name }}" data-mcid="{{ $item->mcid }}">
                                            <i class="fa fa-pencil"></i>&nbsp;编辑
                                        </a>
                                        <a href="javascript:;" class="btn btn-danger btn-xs" hw_remove
                                           data-id="{{ $item->id }}" data-type="{{ $type }}">
                                            <i class="fa fa-trash"></i>&nbsp;删除
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="row">
                        <div class="col-sm-11">
                            {!!$images->appends($request)->render()!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/template" id="one-image-edit-tpl">
    <form class="cmxform form-horizontal tasi-form" id="one_image_edit_form" method="post" action="#"
          enctype="multipart/form-data">
        <input type="hidden" name="id" id="id" value="">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="form-group" id="name-form-group">
            <label for="name" class="control-label col-lg-2">文件名</label>
            <div class="col-lg-6">
                <input type="text" name="name" id="name" value="" class="form-control" required>
            </div>
        </div>
        <div class="form-group" id="name-form-group">
            <label for="mcid" class="control-label col-lg-2">分类</label>
            <div class="col-lg-6">
                <select class="form-control has-success" name="mcid" id="mcid">
                    <option value="0">全部分类</option>
                    @foreach($cates as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</script>
@endsection

@section('scripts')
    @include('admin.wechat.js')
    <script src="/assets/admin/wechat/js/media_image.js"></script>
    <script>
        $(document).ready(function () {
            var options = {};
            AppMediaImage.init(options);
        });
    </script>
@endsection
