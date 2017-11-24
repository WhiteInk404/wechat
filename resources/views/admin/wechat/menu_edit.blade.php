@extends('layouts.app')
@section('title', '普通菜单')

@section('css')
    @include('admin.wechat.css')
    <link rel="stylesheet" type="text/css" href="/assets/admin/wechat/css/proMenu.css">
@endsection

@section('content')
    <section class="content">
        <div class="mainContnetRight row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>微信菜单 <small>普通菜单</small></h5>
                    </div>
                    <div class="ibox-content">
                        <div class="proText" style="width: 802px">
                            <span class="text-primary">可创建最多3个一级菜单，每个一级菜单下可创建最多5个二级菜单<br>编辑中的菜单不能直接在用户手机上生效，你需要进行发布，发布菜单后24小时内所有的用户都讲更新到新菜单。</span>
                        </div>
                        <div id="menuBotton">
                            <menu-button :button.sync="button"  :options="options"></menu-button>
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
    var type = {{ $type }};
    @if($wxMenu)
        var id = {{ $wxMenu->id }};
        var menuId = '{{ $wxMenu->menu_id }}';
        var button = {!! json_encode($wxMenu->button) !!};
    @else
        var id = 0;
        var menuId = '';
        var button = [];
    @endif
</script>
<script src="/assets/admin/wechat/js/wx_menu_edit_build.js"></script>
@endsection
