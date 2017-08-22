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