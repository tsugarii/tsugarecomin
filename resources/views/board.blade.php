<!DOCTYPE html>
<html lang="ja">
    <head>
    <meta charset="UTF-8">
    @php $epoch_time = time(); @endphp
    <title>{{$board_info->board_name}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
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
        @if(isset($thread_datas))
        @foreach($thread_datas as $thread_data)
        $(function () {
          $('#upvoted-btn-{{$thread_data->thread_key}}').click(function () {
          $.ajax({
            url: "../../upvote/{{$board_info->board_url}}/{{$thread_data->thread_key}}",
            type: "POST",
            }).done(function(data) {
              $('#upvoted-{{$thread_data->thread_key}}').text({{$thread_data->upvote}}+1);
              $('#upvoted-btn-{{$thread_data->thread_key}}').attr('class', 'btn btn-danger float-left');
            });
          });
        });
        @endforeach

        @foreach($thread_datas as $thread_data)
        $(function () {
          $('#dis-upvoted-btn-{{$thread_data->thread_key}}').click(function () {
          $.ajax({
            url: "../../upvote/{{$board_info->board_url}}/{{$thread_data->thread_key}}",
            type: "POST",
            }).done(function(data) {
              $('#dis-upvoted-{{$thread_data->thread_key}}').text({{$thread_data->upvote}}-1);
              $('#dis-upvoted-btn-{{$thread_data->thread_key}}').attr('class', 'btn btn-secondary float-left');
            });
          });
        });
        @endforeach
        @endif
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

        <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="sort_menu" data-toggle="dropdown" aria-haspoppup="true" aria-expended="false">ソート</button>
        <div class="dropdown-menu" aria-labelledby="sort_menu">
        <a class="dropdown-item" href="../../r/{{$board_info->board_url}}/hot">HOT</a>
        <a class="dropdown-item" href="../../r/{{$board_info->board_url}}/new">NEW</a>
        <a class="dropdown-item" href="../../r/{{$board_info->board_url}}/top">TOP</a>
        </div>
        </div>
        
        @if(isset($sticky_thread_datas))
        @foreach($sticky_thread_datas as $thread_data)
        <div class="row">
        <div class="col-12">
        <div class="card" id="{{$thread_data->thread_key}}">
            <div class="card-header">
            @if(Auth::check())
            <div class="form-group float-left">
                <form method="POST" action="../../upvote/{{$board_info->board_url}}/{{$thread_data->thread_key}}">
                <button type="submit" class="btn btn-danger">Upvote <span class="badge badge-light">{{$thread_data->upvote}}</span></button>
                </form>
            </div>
            @endif
            <p class="float-right">Posted by <a href="../../user/{{$thread_data->user_id}}">u/{{$thread_data->user_id}}</a></p>
            </div>
            <div class="card-body">
                <h4 class="card-title"><a href="../../../r/{{$board_info->board_url}}/{{$thread_data->thread_key}}/all">{!!$thread_data->title!!}</a></h4>
            </div>
            <div class="card-footer"><p>{{$thread_data->res}} コメント 勢い:{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}} 最終更新:{{$thread_data->updated_at}}</p></div>
        </div>
        </div>
        </div>
        @endforeach
        @endif

        @if(isset($thread_datas_3times))
        @foreach($thread_datas_3times as $thread_data)
        <div class="row">
        <div class="col-12">
        <div class="card" id="{{$thread_data->thread_key}}">
        <div class="card-header">
            @if(Auth::check())
            <div class="form-group float-left">
                <form method="POST" action="../../upvote/{{$board_info->board_url}}/{{$thread_data->thread_key}}">
                <button type="submit" class="btn btn-danger">Upvote <span class="badge badge-light">{{$thread_data->upvote}}</span></button>
                </form>
            </div>
            @endif
            <p class="float-right">Posted by <a href="../../user/{{$thread_data->user_id}}">u/{{$thread_data->user_id}}</a></p>
            </div>
            <div class="card-body">
                <h4 class="card-title"><a href="../../../r/{{$board_info->board_url}}/{{$thread_data->thread_key}}/all">{!!$thread_data->title!!}</a></h4>
            </div>
            <div class="card-footer"><p>{{$thread_data->res}} コメント 勢い:{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}} 最終更新:{{$thread_data->updated_at}}</p></div>
        </div>
        </div>
        </div>
        @endforeach
        @endif
        
        @if(isset($thread_datas_6times))
        @foreach($thread_datas_6times as $thread_data)
        <div class="row">
        <div class="col-12">
        <div class="card" id="{{$thread_data->thread_key}}">
        <div class="card-header">
            @if(Auth::check())
            <div class="form-group float-left">
                <form method="POST" action="../../upvote/{{$board_info->board_url}}/{{$thread_data->thread_key}}">
                <button type="submit" class="btn btn-danger">Upvote <span class="badge badge-light">{{$thread_data->upvote}}</span></button>
                </form>
            </div>
            @endif
            <p class="float-right">Posted by <a href="../../user/{{$thread_data->user_id}}">u/{{$thread_data->user_id}}</a></p>
            </div>
            <div class="card-body">
                <h4 class="card-title"><a href="../../../r/{{$board_info->board_url}}/{{$thread_data->thread_key}}/all">{!!$thread_data->title!!}</a></h4>
            </div>
            <div class="card-footer"><p>{{$thread_data->res}} コメント 勢い:{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}} 最終更新:{{$thread_data->updated_at}}</p></div>
        </div>
        </div>
        </div>
        @endforeach
        @endif

        @if(isset($thread_datas_12times))
        @foreach($thread_datas_12times as $thread_data)
        <div class="row">
        <div class="col-12">
        <div class="card" id="{{$thread_data->thread_key}}">
        <div class="card-header">
            @if(Auth::check())
            <div class="form-group float-left">
                <form method="POST" action="../../upvote/{{$board_info->board_url}}/{{$thread_data->thread_key}}">
                <button type="submit" class="btn btn-danger">Upvote <span class="badge badge-light">{{$thread_data->upvote}}</span></button>
                </form>
            </div>
            @endif
            <p class="float-right">Posted by <a href="../../user/{{$thread_data->user_id}}">u/{{$thread_data->user_id}}</a></p>
            </div>
            <div class="card-body">
                <h4 class="card-title"><a href="../../../r/{{$board_info->board_url}}/{{$thread_data->thread_key}}/all">{!!$thread_data->title!!}</a></h4>
            </div>
            <div class="card-footer"><p>{{$thread_data->res}} コメント 勢い:{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}} 最終更新:{{$thread_data->updated_at}}</p></div>
        </div>
        </div>
        </div>
        @endforeach
        @endif

        @if(isset($thread_datas_24times))
        @foreach($thread_datas_24times as $thread_data)
        <div class="row">
        <div class="col-12">
        <div class="card" id="{{$thread_data->thread_key}}">
        <div class="card-header">
            @if(Auth::check())
            <div class="form-group float-left">
                <form method="POST" action="../../upvote/{{$board_info->board_url}}/{{$thread_data->thread_key}}">
                <button type="submit" class="btn btn-danger">Upvote <span class="badge badge-light">{{$thread_data->upvote}}</span></button>
                </form>
            </div>
            @endif
            <p class="float-right">Posted by <a href="../../user/{{$thread_data->user_id}}">u/{{$thread_data->user_id}}</a></p>
            </div>
            <div class="card-body">
                <h4 class="card-title"><a href="../../../r/{{$board_info->board_url}}/{{$thread_data->thread_key}}/all">{!!$thread_data->title!!}</a></h4>
            </div>
            <div class="card-footer"><p>{{$thread_data->res}} コメント 勢い:{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}} 最終更新:{{$thread_data->updated_at}}</p></div>
        </div>
        </div>
        </div>
        @endforeach
        @endif

        @if(isset($thread_datas_24times_before))
        @foreach($thread_datas_24times_before as $thread_data)
        <div class="row">
        <div class="col-12">
        <div class="card" id="{{$thread_data->thread_key}}">
        <div class="card-header">
            @if(Auth::check())
            <div class="form-group float-left">
                <form method="POST" action="../../upvote/{{$board_info->board_url}}/{{$thread_data->thread_key}}">
                <button type="submit" class="btn btn-danger">Upvote <span class="badge badge-light">{{$thread_data->upvote}}</span></button>
                </form>
            </div>
            @endif
            <p class="float-right">Posted by <a href="../../user/{{$thread_data->user_id}}">u/{{$thread_data->user_id}}</a></p>
            </div>
            <div class="card-body">
                <h4 class="card-title"><a href="../../../r/{{$board_info->board_url}}/{{$thread_data->thread_key}}/all">{!!$thread_data->title!!}</a></h4>
            </div>
            <div class="card-footer"><p>{{$thread_data->res}} コメント 勢い:{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}} 最終更新:{{$thread_data->updated_at}}</p></div>
        </div>
        </div>
        </div>
        @endforeach
        {{$thread_datas_24times_before->links()}}
        @endif

        @if(isset($thread_datas))
        @foreach($thread_datas as $thread_data)
        <div class="row">
        <div class="col-12">
        <div class="card">
        <div class="card-header">
            @if(Auth::check())
            @if(Cache::has('_' . Auth::user()->user_id . '_' . $thread_data->board_key . '_' . $thread_data->thread_key . '_upvoted'))
            <button type="button" class="btn btn-danger floart-left" id="dis-upvoted-btn-{{$thread_data->thread_key}}">Upvote <span class="badge badge-light" id="dis-upvoted-{{$thread_data->thread_key}}">{{$thread_data->upvote}}</span></button>
            @else
            <button type="button" class="btn btn-secondary floart-left" id="upvoted-btn-{{$thread_data->thread_key}}">Upvote <span class="badge badge-light" id="upvoted-{{$thread_data->thread_key}}">{{$thread_data->upvote}}</span></button>
            @endif
            @endif
            <p class="float-right">Posted by <a href="../../user/{{$thread_data->user_id}}">u/{{$thread_data->user_id}}</a></p>
            </div>
            <div class="card-body">
                <h4 class="card-title"><a href="../../../r/{{$board_info->board_url}}/{{$thread_data->thread_key}}/all">{!!$thread_data->title!!}</a></h4>
            </div>
            <div class="card-footer"><p>{{$thread_data->res}} コメント 勢い:{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}} 最終更新:{{$thread_data->updated_at}}</p></div>
        </div>
        </div>
        </div>
        @endforeach
        {{$thread_datas->links()}}
        @endif
        </table>
        </div>

        <div class="col-12 col-md-5">

        <p><a class="btn btn-primary" data-toggle="collapse" href="#new_thread" role="button" aria-expanded="false" area-controls="new_thread">スレッド新規作成</a></p>
        <div class="collapse" id="new_thread">
        <form method="POST" action="../../test/bbs.cgi">
        <input type="hidden" name="bbs" value="{{$board_info->board_url}}">
        <div class="form-row">
            <div class="form-group col-12">
                <label for="title">タイトル:</label>
                @if ($errors->has('subject'))
                    ERROR:{{$errors->first('subject')}}
                @endif
                <input type="text" class="form-control" name="subject" placeholder="スレッドのタイトルを入力" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                <label for="name">名前:</label>
                @if ($errors->has('FROM'))
                    ERROR:{{$errors->first('FROM')}}
                @endif
                <input type="text" class="form-control" name="FROM" placeholder="名前を入力(省略可)">
            </div>
        </div>
        <input type="hidden" name="mail">
        <div class="form-row">
            <div class="form-group col-12">
                <label for="textarea">コメント内容:</label>
                @if ($errors->has('MESSAGE'))
                    ERROR:{{$errors->first('MESSAGE')}}
                @endif
                <textarea class="form-control" name="MESSAGE" id="textarea" rows="5" required></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">書き込み</button>
        </form>
        </div>

        <div class="card">
        <div class="card-header"><h1>{{$board_info->board_name}}</h1></div>
        <div class="card-body"><p>{!!$board_info->board_explain!!}</p></div>
        </div>

        <div class="card">
        <div class="card-header"><h2>ルール</h2></div>
        <div class="card-body"><p>{!!$board_info->board_rule!!}</p></div>
        </div>

        </div>

        </div>
    </body>
</html>