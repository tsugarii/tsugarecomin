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
        @if(Auth::check())
        <div class="container fluid">
        <div class="row">

        <div class="col-12 col-md-3"></div>
        <div class="col-12 col-md-6">
        <div class="card">
        <div class="card-header">User Settings</div>
        <div class="card-body">
        <div class="card-text">
        <p>あなたの名前は「{{Auth::user()->name}}」です。</p>
        <p>変更する場合は以下のフォームに入力してください。</p>
        @if($errors->has('name'))
            <p>エラー: {{$errors->first('name')}}</p>
        @endif
        <form method="post" action="settings/update" class="form-inline">
        <div class="form-group">
        <label for="name">名前:</label>
        <input type="text" class="form-control" name="name" value="{{Auth::user()->name}}">
        <button type="submit" class="btn btn-primary">変更</button>
        </div>
        </form>

        </div>
        </div>
        </div>
        <div class="col-12 col-md-3"></div>

        </div>
        </div>
        @endif
    </body>
</html>