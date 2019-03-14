<!DOCTYPE html>
<html lang="ja">
    <head>
    <meta charset="UTF-8">
    <title>{{$board_info->board_name}}:{{$thread_title}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/js/app.js"></script>
    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');

                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if(form.checkValidity() == false) {
                            event.preventDefalut();
                            event.stopPropagetion();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        @foreach($reses as $res)
        $(function () {
          $('#liked-btn-{{$res->comment_key}}').click(function () {
          $.ajax({
            url: "../../../like/{{$board_info->board_url}}/{{$res->thread_key}}/{{$res->comment_key}}",
            type: "POST",
            }).done(function(data) {
              $('#liked-{{$res->comment_key}}').text({{$res->liked}}+1);
              $('#liked-btn-{{$res->comment_key}}').attr('class', 'btn btn-danger float-right');
            });
          });
        });
        @endforeach

        @foreach($reses as $res)
        $(function () {
          $('#dis-liked-btn-{{$res->comment_key}}').click(function () {
          $.ajax({
            url: "../../../like/{{$board_info->board_url}}/{{$res->thread_key}}/{{$res->comment_key}}",
            type: "POST",
            }).done(function(data) {
              $('#dis-liked-{{$res->comment_key}}').text({{$res->liked}}-1);
              $('#dis-liked-btn-{{$res->comment_key}}').attr('class', 'btn btn-secondary float-right');
            });
          });
        });
        @endforeach
    </script>
    </head>
    <body>
    <nav class="navbar navbar-expand-md navbar-light">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ __('ツガレコミン') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto"></ul>
                    <ul class="navbar-nav ml-auto">
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/user/{{Auth::user()->user_id}}">
                                        {{__('My Profile')}}
                                    </a>
                                    <a class="dropdown-item" href="/settings">
                                        {{__('User Settings')}}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

      <div class="container fluid">

        <div class="row">
        <div class="col-12 col-md-7">

        <div class="card">
        <div class="card-header">スレッドタイトル</div>
        <div class="card-body">
        <h1 class="card-text">{{$thread_title}}</h1>
        </div>
        </div>

        @foreach($reses as $res)
        @if($loop->first)
          <div class="card" id="{{$res->comment_key}}">
          <div class="card-header">スレッド作成者 名前:{{$res->name}} Posted by <a href="../../../user/{{$res->user_id}}">u/{{$res->user_id}}</a> </div>
          <div class="card-body">
          <p class="card-text">{!!$res->message!!}</p>
          </div>
          <div class="card-footer">
            投稿日:{{$res->time}} <a href="id/{{$res->id}}" name="{{$res->id}}">ID</a>:{{$res->id}}
              @if(Auth::check())
              @if(Cache::has('_' . Auth::user()->user_id . '_' . $res->board_key . '_' . $res->thread_key . '_' . $res->comment_key . '_liked'))
              <button type="button" class="btn btn-danger float-right" id="dis-liked-btn-{{$res->comment_key}}"> いいね <span class="badge badge-light" id="dis-liked-{{$res->comment_key}}">{{$res->liked}}</span></button>
              @else
              <button type="button" class="btn btn-secondary float-right" id="liked-btn-{{$res->comment_key}}"> いいね <span class="badge badge-light" id="liked-{{$res->comment_key}}">{{$res->liked}}</span></button>
              @endif
              @endif
          </div>
          </div>
        @continue
        @endif

        <div class="card" id="{{$res->comment_key}}">
          <div class="card-header">{{$res->res_number}} 名前:{{$res->name}} Posted by <a href="../../../user/{{$res->user_id}}">u/{{$res->user_id}}</a> </div>
          <div class="card-body">
          <p class="card-text">{!!$res->message!!}</p>
          </div>
          <div class="card-footer">
          投稿日:{{$res->time}} <a href="id/{{$res->id}}" name="{{$res->id}}">ID</a>:{{$res->id}}
          @if(Auth::check())
          @if(Cache::has('_' . Auth::user()->user_id . '_' . $res->board_key . '_' . $res->thread_key . '_' . $res->comment_key . '_liked'))
            <button type="button" class="btn btn-danger float-right" id="dis-liked-btn-{{$res->comment_key}}"> いいね <span class="badge badge-light" id="dis-liked-{{$res->comment_key}}">{{$res->liked}}</span></button>
          @else
            <button type="button" class="btn btn-secondary float-right" id="liked-btn-{{$res->comment_key}}"> いいね <span class="badge badge-light" id="liked-{{$res->comment_key}}">{{$res->liked}}</span></button>
          @endif
          @endif
          </div>
        </div>

        @endforeach

        <form method="POST" action="../../../../test/bbs.cgi">
          <input type="hidden" name="bbs" value="{{$board_info->board_url}}">
          <input type="hidden" name="key" value="{{$key}}">
          <div class="form-row">
            <div class="form-group col-6">
              <label for="name">名前:</label>
                @if ($errors->has('FROM'))
                  ERROR:{{$errors->first('FROM')}}
                @endif
              <input type="text" class="form-control" name="FROM" placeholder="名前を入力(省略可)">
            </div>
          </div>
        <input type="hidden" name="mail" value="">
        <div class="form-row">
            <div class="form-group col-12">
                <label for="textarea">コメント内容:</label>
                @if ($errors->has('MESSAGE'))
                  ERROR:{{$errors->first('MESSAGE')}}
                @endif
                <textarea class="form-control" name="MESSAGE" id="textarea" rows="4" required></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">書き込み</button>
        </form>

        </div>
        <div class="col-12 col-md-5">

        <div class="card">
        <div class="card-header"><h1><a href="../../../../r/{{$board_info->board_url}}">{{$board_info->board_name}}</a></h1></div>
        <div class="card-body"><p>{!!$board_info->board_explain!!}</p></div>

        <div class="card">
        <div class="card-header"><h2>ルール</h2></div>
        <div class="card-body"><p>{!!$board_info->board_rule!!}</p></div>
        </div>

        </div>

        </div>

      </div>
    </body>
</html>