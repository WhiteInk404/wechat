@extends('layouts.app')

@section('title', '编辑公众号')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            编辑公众号
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 主页</a></li>
            <li><a href="#">公众号管理</a></li>
            <li class="active">编辑公众号</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        @include('partials.messages')
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">&nbsp;</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form class="form-horizontal" action="{{ route('admin.wechat.wxConfig.store', ['id' => isset($wxConfig) ? $wxConfig->id : '']) }}" method="post" enctype="multipart/form-data">
                        {{ method_field('post') }}
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="mobile" class="col-sm-2 control-label">微信号</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="wechat_id" name="wechat_id"
                                           value="{{ isset($wxConfig) ? $wxConfig->wechat_id : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">原始ID</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="source_id" name="source_id"
                                           value="{{ isset($wxConfig) ? $wxConfig->source_id : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">公众号名称</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="{{ isset($wxConfig) ? $wxConfig->name : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="realname" class="col-sm-2 control-label">AppID</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="appid" id="appid"
                                           value="{{ isset($wxConfig) ? $wxConfig->appid : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sex" class="col-sm-2 control-label">AppSecret</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="app_secret" id="app_secret"
                                           value="{{ isset($wxConfig) ? $wxConfig->app_secret : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status" class="col-sm-2 control-label">TOKEN</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="token" id="token"
                                           value="{{ isset($wxConfig) ? $wxConfig->token : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="province" class="col-sm-2 control-label">消息加解密密钥</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="aes_key" id="aes_key"
                                           value="{{ isset($wxConfig) ? $wxConfig->aes_key : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="province" class="col-sm-2 control-label">支付商户号</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="mch_id" id="mch_id"
                                           value="{{ isset($wxConfig) ? $wxConfig->mch_id : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city" class="col-sm-2 control-label">支付签名key</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="sign_key" id="sign_key"
                                           value="{{ isset($wxConfig) ? $wxConfig->sign_key : '' }}">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <a href="{{ route('admin.wechat.wxConfig.index') }}" class="btn btn-default">返回</a>
                            <button type="submit" class="btn btn-info pull-right">保存</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('scripts')

@endsection
