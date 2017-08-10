<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>支持团队</title>
    <link rel="stylesheet" href="/frontend/css/support-team.css">
</head>

<body style="background:#EFEFF4">
    <div class="p10 fz17 tac white medium orange-bg">感谢您对本团队的支持！</div>
    <img class="w100" src="{{$activity->full_pic_url}}" alt="">
    <div class="container container-info">
        <div class="box">
            <h2 class="fz17 color-medium">活动信息</h2>
            <p class="color-light">{{$activity->description}}</p>
            <p class="number artbrush fz18 orange-bg white">No. {{$sort}}</p>
        </div>

        <div class="box">
            <h2 class="fz17 color-medium">团队信息</h2>
            <p class="color-light">{{$team->name}}</p>
        </div>

        @if($team->participants->isEmpty())
        <div class="box">
            <h2>暂无支持者</h2>
        </div>
        @else
        <div class="box avatars">
            <p class="imgs">
                @foreach($team->participants()->take(16)->orderBy('id','desc')->get() as $participant)
                <img src="{{ $participant->user->wechatUser->avatar_url }}" alt="">
                @endforeach
            </p>
            <p class="fz12 tar color-gray"><a href="#">查看全部支持者（{{$team->participants()->count()}}）<i class="icon icon-arrow-right"></i></a></p>
        </div>
        @endif

        <a href="javascript:;" class="btn block">帮助分享，提升团队人气</a>
    </div>

    <script src="//res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
  wx.config({!! EasyWeChat::js()->config(['onMenuShareTimeline','onMenuShareAppMessage']) !!});

  wx.error(function(){
    console.log('wx.error');
  });
  wx.ready(function () {
    console.log('wx.ready');
    var share_title = "我支持了 {{$team->name}}，你也来支持一下吧！";
    var share_link = window.location.href;
    var share_img_url = "{{$activity->full_pic_url}}";
    var share_desc = "{{$activity->description}}";
    
    wx.onMenuShareTimeline({
    title: share_title, // 分享标题
    link: share_link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
    imgUrl: share_img_url, // 分享图标
    success: function () {
      // 用户确认分享后执行的回调函数
    },
    cancel: function () {
      // 用户取消分享后执行的回调函数
    }
    });
    
    wx.onMenuShareAppMessage({
    title: share_title, // 分享标题
    desc: share_desc, // 分享描述
    link: share_link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
    imgUrl: share_img_url, // 分享图标
    success: function () {
      // 用户确认分享后执行的回调函数
    },
    cancel: function () {
      // 用户取消分享后执行的回调函数
    }
    });
  });
</script>
</body>
</html>