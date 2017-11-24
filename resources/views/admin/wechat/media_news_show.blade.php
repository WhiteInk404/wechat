<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>文章预览</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="/assets/admin/wechat/css/setting/article.css" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
    <link href="/assets/admin/wechat/css/setting/article_ie.css" rel="stylesheet" type="text/css" />
    />
    <![endif]-->
</head>
<body>
<div class="rich_media">
    <div class="rich_media_inner">
        <h2 class="rich_media_title">{{ isset($article->content[$key]->title)?$article->content[$key]->title:'' }}</h2>
        <div class="rich_media_meta_list">
            <em id="post-date" class="rich_media_meta text">{{ isset($article->updated_at)?$article->updated_at:'' }}</em>
            <a class="rich_media_meta link nickname">V3</a>
        </div>
        <div id="page-content" class="rich_media_content_wrp">
            <div id="img-content">
                {{--<div class="rich_media_thumb" id="media"></div>--}}
                <div class="rich_media_content">
                    <p>↗↗↗↗↗</p>
                    <p>点击上方蓝色字关注</p>
                    {!! $article->content[$key]->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>