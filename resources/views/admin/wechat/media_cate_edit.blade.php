@extends('layouts.app')

@section('title', '素材库-分类')
@section('css')
    @include('admin.wechat.css')
    @endsection
@section('content')
<section id="main-content" class="content">
@include('partials.messages')
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <div class="ibox-title">
                        @if ($action == 'add')添加@else编辑@endif分类
                    </div>
                    <div class="ibox-content">
                        <div class=" form">
                            <form class="cmxform form-horizontal tasi-form" id="form" method="post"  enctype="multipart/form-data">
                                <input type="hidden" name="id" value="{{ $id }}">
                                <input type="hidden" name="action" value="{{ $action }}">
                                <input type="hidden" name="type" value="{{ $type }}">
                                <input  id="_token" type="hidden" name="_token" value="{{ csrf_token() }}">
                                @include('admin.wechat.form_base')
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <p class="btn btn-primary" id="saveBtn">保存</p>
                                        <button class="btn btn-default" type="reset">重置</button>
                                    </div>
                                </div>
                            </form>
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
        $(document).ready(function () {
            $('#saveBtn').on('click', function() {
                var postData = $('#form').serialize();
                $.ajax({
                    "url": "/admin/wechat/media/{{ $type }}/cate",
                    "dataType": "json",
                    "data": postData,
                    "type": "post",
                    success: function (data) {
                        if (data.error_code == 0) {
                            location.href = '/admin/wechat/media/{{ $type }}/cate';
                        } else {
                            noty({
                                text: data.error_message,
                                type: 'error',
                                timeout: 2000,
                            });
                        }
                    }, error: function (data) {
                        noty({
                            text: data.error_message,
                            type: 'error',
                            timeout: 2000,
                        });
                    }
                });
            });
        });
    </script>
@endsection
