<!DOCTYPE html>
<html lang="ja">
    <head>
    <meta charset="UTF-8">
    <title>{{$board_info->board_name}}:{{$thread_title}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <style>
    .res_number {float : left;}
    .res_namae {float : left;}
    .res_name {float : left; color : green; font-weight : bold;}
    .res_post {float : left;}
    .res_id {float : left;}
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/js/app.js"></script>
    </head>
    <body>
        <a href="../{{$board_info->board_url}}">板トップ</a>

        @foreach($reses as $res)
        <div class="res">
            <div class="res_number">{{$res->res_number}}:</div>
            <div class="res_namae">名前:</div>
            <div class="res_name">{{$res->name}}</div>
            <div class="res_post">投稿日:{{$res->time}} </div>
            <div class="res_id"><a href="" name="{{$res->id}}">ID</a>:{{$res->id}}</div><br />
            <div class="res_message">{{$res->message}}</div><br />
        </div>
        @endforeach

        @if ($errors->any())
        <div class="error_post">
        @if ($errors->has('bbs'))
          ERROR:{{$errors->first('bbs')}}<br />
        @endif
        @if ($errors->has('key'))
          ERROR:{{$errors->first('key')}}<br />
        @endif
        @if ($errors->has('subject'))
          ERROR:{{$errors->first('subject')}}<br />
        @endif
        @if ($errors->has('FROM'))
         ERROR:{{$errors->first('FROM')}}<br />
        @endif
        @if ($errors->has('mail'))
         ERROR:{{$errors->first('mail')}}<br />
       @endif
        @if ($errors->has('MESSAGE'))
          ERROR:{{$errors->first('MESSAGE')}}<br />
        </div>
       @endif
       @endif

       <a href="../{{$board_info->board_url}}">板トップ</a>
        <form method="POST" action="../../../test/bbs.cgi">
        <input type="hidden" name="bbs" value="{{$board_info->board_url}}">
        <input type="hidden" name="key" value="{{$key}}">
        <label>名前:</label>
        <input type="text" name="FROM">
        <input type="hidden" name="mail"><br />
        <label>コメント内容:</label>
        <textarea type="text" name="MESSAGE"></textarea>
        <div class="button">
        <button type="submit">書き込み</button>
    </body>
</html>