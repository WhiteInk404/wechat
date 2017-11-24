@extends('layouts.app')

@section('title', '公众号设置')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            公众号设置
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 主页</a></li>
            <li><a href="#">公众号管理</a></li>
            <li class="active">公众号设置</li>
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
                    <form class="form-horizontal">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label for="mobile" class="col-sm-2 control-label">微信号</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{ isset($wxConfig) ? $wxConfig->wechat_id : '' }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">原始ID</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{ isset($wxConfig) ? $wxConfig->source_id : '' }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">公众号名称</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{ isset($wxConfig) ? $wxConfig->name : '' }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="realname" class="col-sm-2 control-label">AppID</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{ isset($wxConfig) ? $wxConfig->appid : '' }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sex" class="col-sm-2 control-label">AppSecret</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{ isset($wxConfig) ? $wxConfig->app_secret : '' }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-2 control-label">TOKEN</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{ isset($wxConfig) ? $wxConfig->token : '' }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="province" class="col-sm-2 control-label">消息加解密密钥</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{ isset($wxConfig) ? $wxConfig->aes_key : '' }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="province" class="col-sm-2 control-label">支付商户号</label>
                            <div class="col-sm-8">
                                <p class="form-control-static"> {{ isset($wxConfig) ? $wxConfig->mch_id : '' }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city" class="col-sm-2 control-label">支付签名key</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{ isset($wxConfig) ? $wxConfig->sign_key : '' }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city" class="col-sm-2 control-label">公众号接入url</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{ route('api.wechat.callback') }}</p>
                            </div>
                        </div>
                        {{--<div class="form-group">
                            <label for="city" class="col-sm-2 control-label">公众号接入url</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{ route('api.wechat.callback') }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city" class="col-sm-2 control-label">支付回调url</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{ route('api.wechat.notify') }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city" class="col-sm-2 control-label">退款通知url</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{ route('api.wechat.refundNotify') }}</p>
                            </div>
                        </div>--}}
                    </div>
                    </form>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{{ route('admin.wechat.wxConfig.edit') }}" class="btn btn-info pull-right">编辑</a>
                    </div>
                    <!-- /.box-footer -->
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
