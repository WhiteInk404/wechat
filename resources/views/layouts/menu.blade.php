<li class="header">功能导航</li>
<li class="treeview {{ request()->is('admin/roles*') ? 'active' : '' }}">
  <a href="#"><span>权限设置</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
    <li class="{{ request()->route()->getName() == 'admin.roles.index' ? 'active' : '' }}"><a href="{{ route('admin.roles.index') }}">权限列表</a></li>
    <li class="{{ request()->route()->getName() == 'admin.roles.create' ? 'active' : '' }}"><a href="{{ route('admin.roles.create') }}">添加权限</a></li>
  </ul>
</li>
<li class="treeview {{ request()->is('admin/users*') ? 'active' : '' }}">
  <a href="#"><span>用户管理</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
    <li class="{{ request()->route()->getName() == 'admin.users.index' ? 'active' : '' }}"><a href="{{ route('admin.users.index') }}">用户列表</a></li>
    <li class="{{ request()->route()->getName() == 'admin.users.verification.index' ? 'active' : '' }}"><a href="{{ route('admin.users.verification.index') }}">认证审核</a></li>
  </ul>
</li>
<li class="treeview {{ request()->is('admin/stories*') ? 'active' : '' }}">
  <a href="#"><span>故事集管理</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
    <li class="{{ request()->route()->getName() == 'admin.stories.index' ? 'active' : '' }}"><a href="{{ route('admin.stories.index') }}">故事集列表</a></li>
    <li class="{{ request()->route()->getName() == 'admin.stories.create' ? 'active' : '' }}"><a href="{{ route('admin.stories.create') }}">创建故事集</a></li>
    <li class="{{ request()->route()->getName() == 'admin.stories_comments.index' ? 'active' : '' }}"><a href="{{ route('admin.stories_comments.index') }}">故事集评论列表</a></li>
  </ul>
</li>
<li class="treeview {{ request()->is('admin/activities*') ? 'active' : '' }}">
  <a href="#"><span>活动管理</span> <i class="fa fa-angle-left pull-right"></i></a>
  <ul class="treeview-menu">
    <li class="{{ request()->route()->getName() == 'admin.activities.index' ? 'active' : '' }}"><a href="{{ route('admin.activities.index') }}">活动列表</a></li>
    <li class="{{ request()->route()->getName() == 'admin.activities.create' ? 'active' : '' }}"><a href="{{ route('admin.activities.create') }}">创建活动</a></li>
    <li class="{{ request()->route()->getName() == 'admin.activities_orders.index' ? 'active' : '' }}"><a href="{{ route('admin.activities_orders.index') }}">活动报名列表</a></li>
  </ul>
</li>