@extends('layouts.app')

@section('title', '编辑公众号菜单')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            编辑公众号菜单
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 主页</a></li>
            <li><a href="#">公众号管理</a></li>
            <li class="active">编辑公众号菜单</li>
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
                    <form class="form-horizontal" action="{{ route('admin.wechat.wxMenu.store') }}" method="post" enctype="multipart/form-data">
                        {{ method_field('post') }}
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="mobile" class="col-sm-2 control-label">菜单代码</label>
                                <div class="col-sm-8">
                                    <textarea id="menus" name="menus" class="form-control" required="" aria-required="true" rows="30">{{ isset($wxMenu) ? $wxMenu->button : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <a href="{{ route('admin.wechat.wxMenu.index') }}" class="btn btn-default">返回</a>
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
