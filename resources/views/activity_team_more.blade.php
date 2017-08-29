<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>淘老外</title>
    <link rel="stylesheet" href="/frontend/css/support-team.css">

</head>

<body style="background:#EFEFF4">
@if($exists)
<div class="box share-box">
    <a href="javascript:;" class="fz17 block btn share-btn no-radius">帮助分享，提升团队人气 <i class="icon icon-share"></i></a>
</div>
@else
<div class="box share-box">
    <a href="{{ route('team_up',['activity_id'=>$activity->id,'team_id'=>$team->id]) }}" class="fz17 block btn no-radius join-btn">我要支持</a>
</div>
@endif
    <div class="container-avatars">
        <p class="fz17 color-light">团队全部支持者</p>
        <div class="imgs">
            @foreach($participants as $participant)
            <img src="{{$participant->user->wechatUser->avatar_url}}" alt="">
            @endforeach
        </div>
    </div>

    <div class="share-tip fixed-container fz17 medium white tac">
        点击右上角，分享到朋友圈 <i class="icon icon-share"></i>
    </div>
    <script src="//res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script type="text/javascript">
        var shareBtn = document.getElementsByClassName('share-btn')[0];
        var shareTip = document.getElementsByClassName('share-tip')[0];

        shareBtn.onclick = function () {
          shareTip.style.display = 'block';
        };

        shareTip.onclick = function () {
          shareTip.style.display = 'none';
        };

        wx.config({!! EasyWeChat::js()->config(['onMenuShareTimeline', 'onMenuShareAppMessage']) !!});

        wx.error(function () {
          console.log('wx.error');
        });
        wx.ready(function () {
          console.log('wx.ready');
          var share_title = "我支持了 {{$team->name}}，你也来支持一下吧！";
          var share_link = "{{ route('activity_team',['activity_id'=>$activity->id,'team_id'=>$team->id]) }}";
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
