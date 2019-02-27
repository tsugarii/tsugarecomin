<!DOCTYPE html>
<html lang="ja">
    <head>
    <meta charset="UTF-8">
    @php $epoch_time = time(); @endphp
    <title>ツガレコミン</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <script src="/js/app.js"></script>
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

        <div class="col-12 col-md-6">
        <div class="card">
        <div class="card-body">
            <h4 class="card-title">Upvote:{{$upvoted}}</h4>
        </div>
        </div>
        @foreach($thread_datas as $thread_data)
        <div class="card" id="{{$thread_data->thread_key}}">
        <div class="card-header">
            <p class="float-right">Posted by <a href="../../user/{{$thread_data->user_id}}">u/{{$thread_data->user_id}}</a> {{$thread_data->created_at}}</p>
        </div>
        <div class="card-body">
            <h4 class="card-title"><a href="../../../r/{{$thread_data->board_key}}/{{$thread_data->thread_key}}/">{!!$thread_data->title!!}</a></h4>
        </div>
        <div class="card-footer"><p>{{$thread_data->res}} コメント 勢い:{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}} 最終更新:{{$thread_data->updated_at}}</p></div>
        </div>
        @endforeach
        {{$thread_datas->links()}}
        </div>
        <div class="col-12 col-md-6">
        <div class="card">
        <div class="card-body">
            <h4 class="card-title">いいね:{{$liked}}</h4>
        </div>
        </div>
        @foreach($reses as $res)
        <div class="card" id="{{$res->comment_key}}">
          <div class="card-header">{{$res->res_number}} 名前:{{$res->name}} Posted by <a href="../../../user/{{$res->user_id}}">u/{{$res->user_id}}</a> </div>
          <div class="card-body">
          <p class="card-text">{!!$res->message!!}</p>
          </div>
          <div class="card-footer">
          投稿日:{{$res->time}} <a href="id/{{$res->id}}" name="{{$res->id}}">ID</a>:{{$res->id}}
          </div>
        </div>
        @endforeach
        {{$reses->links()}}
        </div>

        </div>
        </div>
    </body>
</html>