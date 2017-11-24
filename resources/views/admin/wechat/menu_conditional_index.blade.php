@extends('layouts.app')
@section('title', '个性化菜单')

@section('css')
    @include('admin.wechat.css')
@endsection

@section('content')
<section id="main-content" class="content">
    <div class="row">
        <div class="col-lg-12">
                <div class="ibox-title">
                    个性化菜单列表
                    <div class="ibox-tools">
                        <span class="tools pull-right">
                            <a href="/admin/wechat/menu/conditional/create" class="btn btn-default">
                                <i class="fa fa-plus"style="margin-right:5px;"></i>添加
                            </a>
                        </span>
                    </div>
                </div>
                <div class="ibox-content">
                    <section>
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                            <tr>
                                <th >ID</th>
                                <th >微信ID</th>
                                <th >名字</th>
                                <th style="width:200px;">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($menus as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->menu_id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <a href="/admin/wechat/menu/conditional/{{ $item->id }}/edit" class="btn btn-primary btn-xs">
                                            <i class="icon-pencil">编辑</i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-xs" hw_remove data-id="{{ $item->id }}">
                                        <i class="icon-trash">删除</i>
                                        </a>
                                        {{--<a href="" class="btn btn-default btn-xs">--}}
                                            {{--<i class="icon-dashboard">报表</i>--}}
                                        {{--</a>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $menus->links() !!}
                    </section>
                </div>
        </div>
    </div>
</section>
@endsection
