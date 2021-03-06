<!DOCTYPE html>
<html lang="ja">
    <head>
    <meta charset="UTF-8">
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
        <div class="col-12 col-md-3">

        <div class="card">
            <div class="card-header">管理人のブログ</div>
            <div class="card-body">
                <div class="card-text">
                <p>管理人のブログのURLです。</p>
                <p><a href="https://www.tsugareco.net/">https://www.tsugareco.net/</a></p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">管理人のTwitter</div>
            <div class="card-body">
                <div class="card-text">
                <a class="twitter-timeline" data-height="500" href="https://twitter.com/tsugarii"></a>
                <script async="async" src="//platform.twitter.com/widgets.js" charset="utf-8" ></script>
                </div>
            </div>
        </div>

        </div>
        <div class="col-12 col-md-6">

        <div class="card">
            <div class="card-header">ツガレコミンへようこそ</div>
            <div class="card-body">
                <div class="card-text">
                <p>ツガレコミンは雑談やニュースを語り合う掲示板です。</p>
                </div>
            </div>
        </div>

        <img src="tsugarecologo.png" class="img-fluid" alt="ツガレコミンロゴ画像">

        <div class="card">
        <div class="card-header"><a href="r/min">ツガレコミン</a></div>
            <div class="card-body">
                <div class="card-text">
                <p>雑談を中心とした自由な掲示板です。</p>
                </div>
            </div>
        </div>
        
        <div class="card">
        <div class="card-header"><a href="r/nnp">ニュー速ノーモラル嫌儲</a></div>
            <div class="card-body">
                <div class="card-text">
                <p>ニュース専用の掲示板です。<br />スレ立てにはアカウント登録が必須です。</p>
                </div>
            </div>
        </div>

        </div>

        <div class="col-12 col-md-3">
        <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">NEWS</div>
            <div class="card-body">
                <div class="card-text">
                <p>・ツガレコミンVer.1.00リリース</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">削除依頼</div>
            <div class="card-body">
                <div class="card-text">
                <p>スレッド、レスの削除依頼は<br />こちらへどうぞ。</p>
                <p><a href="https://www.tsugareco.net/p/blog-page_23.html">削除依頼URLページ</a></p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">5ch専用ブラウザで登録する方法</div>
            <div class="card-body">
                <div class="card-text">
                <p>5ch専用ブラウザに登録する方法。</p>
                <p><a href="https://www.tsugareco.net/p/blog-page_62.html">登録方法URLページ</a></p>
                </div>
            </div>
        </div>

        </div>
        </div>

        </div>
        </div>
        </div>
    </body>
</html>