<!DOCTYPE html>
<html lang="ja">
    <head>
    <meta charset="UTF-8">
    <title>ID抽出</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/js/app.js"></script>
    </head>
    <body>
    <div class="container fluid">
    <div class="row">
    <div class="col-12 col-md-3"></div>
    <div class="col-12 col-md-6">
        @foreach($reses as $res)
        <div class="card" id="{{$res->comment_key}}">
        <div class="card-header">{{$res->res_number}} 名前:{{$res->name}} Posted by <a href="../../user/{{$res->user_id}}">u/{{$res->user_id}}</a> </div>
        <div class="card-body">
        <p class="card-text">{!!$res->message!!}</p>
        </div>
        <div class="card-footer">
        投稿日:{{$res->time}} <a href="{{$res->thread_key}}/id/{{$res->id}}" name="{{$res->id}}">ID</a>:{{$res->id}}
        <button type="button" class="btn btn-danger float-right"> いいね <span class="badge badge-light">{{$res->liked}}</span></button>
        </div>
    </div>
    @endforeach
    </div>
    <div class="col-12 col-md-3"></div>
    </div>
    </body>
</html>