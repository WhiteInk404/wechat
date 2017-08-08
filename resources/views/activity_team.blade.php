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
                @foreach($team->participants()->take(32)->get() as $participant)
                <img src="{{ $participant->user->wechatUser->avatar_url }}" alt="">
                @endforeach
            </p>
            <p class="fz12 tar color-gray"><a href="#">查看全部支持者（{{$team->participants()->count()}}）<i class="icon icon-arrow-right"></i></a></p>
        </div>
        @endif

        <a href="javascript:;" class="btn block">帮助分享，提升团队人气</a>
    </div>
</body>

</html>
