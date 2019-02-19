<!DOCTYPE html>
<html lang="ja">
    <head>
    <meta charset="Shift_JIS">
    <title>ツガレコミン:書き込み画面:テスト用</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <style>
    form {
  margin: 0 auto;
  width: 400px;
  padding: 1em;
  border: 1px solid #CCC;
  border-radius: 1em;
}

form div + div {
  margin-top: 1em;
}

label {
  display: inline-block;
  width: 90px;
  text-align: right;
}

input, textarea {
  font: 1em sans-serif;

  width: 300px;
  box-sizing: border-box;

  border: 1px solid #999;
}

input:focus, textarea:focus {
  border-color: #000;
}

textarea {
  vertical-align: top;
  height: 5em;
}

.button {
  padding-left: 90px; 
}

button {
  margin-left: .5em;
}
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="/js/app.js"></script>
    </head>
    <body>
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
      @else
        <form method="POST" action="bbs.cgi">
        <label>スレ立て</label><br />
        <label>板キー:</label>
        <input type="text" name="bbs">
        <label>スレタイ:</label>
        <input type="text" name="subject">
        <label>名前:</label>
        <input type="text" name="FROM">
        <label>コマンド:</label>
        <input type="text" name="mail">
        <label>コメント内容:</label>
        <textarea type="text" name="MESSAGE"></textarea>
        <div class="button">
        <button type="submit">書き込み</button>
        </div>
        </form>

        <form method="POST" action="bbs.cgi">
        <label>書き込み</label><br />
        <label>板キー:</label>
        <input type="text" name="bbs">
        <label>スレッドキー:</label>
        <input type="text" name="key">
        <label>名前:</label>
        <input type="text" name="FROM">
        <label>コマンド:</label>
        <input type="text" name="mail">
        <label>コメント内容:</label>
        <textarea type="text" name="MESSAGE"></textarea>
        <div class="button">
            <button type="submit">書き込み</button>
        </div>
       </form>
    @endif
    </body>
</html>