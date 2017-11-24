<li class="header">功能导航</li>
<li class="treeview {{ request()->is('admin/activities*') ? 'active' : '' }}">
  <a href="#"><span>活动管理</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
    <li class="{{ request()->route()->getName() == 'admin.activities.index' ? 'active' : '' }}"><a href="{{ route('admin.activities.index') }}">活动列表</a></li>
    <li class="{{ request()->route()->getName() == 'admin.activities.create' ? 'active' : '' }}"><a href="{{ route('admin.activities.create') }}">添加活动</a></li>
  </ul>
</li>
<li class="treeview {{ request()->is('admin/wordbook*') ? 'active' : '' }}">
<a href="#"><span>单词本管理</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
    <li class="{{ request()->route()->getName() == 'admin.wordbook.index' ? 'active' : '' }}"><a href="{{ route('admin.wordbook.index') }}">单词本列表</a></li>
    <li class="{{ request()->route()->getName() == 'admin.wordbook.create' ? 'active' : '' }}"><a href="{{ route('admin.wordbook.create') }}">上传单词本</a></li>
  </ul>
</li>
<li class="treeview {{ request()->is('admin/wechat*') ? 'active' : '' }}">
  <a href="#"><span>公众号管理</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
    <li class="{{ request()->route()->getName() == 'admin.wechat.wxConfig.index' ? 'active' : '' }}"><a href="{{ route('admin.wechat.wxConfig.index') }}">公众号设置</a></li>
    <li class="{{ request()->route()->getName() == 'admin.wechat.subscribe.index' ? 'active' : '' }}"><a href="{{ route('admin.wechat.subscribe.index') }}">关注回复</a></li>
    <li class="{{ request()->route()->getName() == 'admin.wechat.nomatch.index' ? 'active' : '' }}"><a href="{{ route('admin.wechat.nomatch.index') }}">默认回复</a></li>
    <li class="{{ request()->route()->getName() == 'admin.wechat.custom.index' ? 'active' : '' }}"><a href="{{ route('admin.wechat.custom.index') }}">自定义回复</a></li>
    <li class="{{ request()->route()->getName() == 'admin.wechat.default.index' ? 'active' : '' }}"><a href="{{ route('admin.wechat.default.index') }}">自定义菜单</a></li>
    <li class="{{ request()->route()->getName() == 'admin.wechat.image.index' ? 'active' : '' }}"><a href="{{ route('admin.wechat.image.index') }}">图片</a></li>
    <li class="{{ request()->route()->getName() == 'admin.wechat.article.index' ? 'active' : '' }}"><a href="{{ route('admin.wechat.article.index') }}">文章</a></li>
    <li class="{{ request()->route()->getName() == 'admin.wechat.news.index' ? 'active' : '' }}"><a href="{{ route('admin.wechat.news.index') }}">图文</a></li>
  </ul>
</li>