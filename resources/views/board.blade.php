<!DOCTYPE html>
<html lang="ja">
    <head>
    <meta charset="UTF-8">
    @php $epoch_time = time(); @endphp
    <title>{{$board_info->board_name}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/js/app.js"></script>
    </head>
    <body>
        <h1>{{$board_info->board_name}}</h1>
        <p>{{$board_info->board_explain}}</p>

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

        <form method="POST" action="../../../test/bbs.cgi">
        <input type="hidden" name="bbs" value="{{$board_info->board_url}}">
        <label>スレタイ:</label>
        <input type="text" name="subject"><br />
        <label>名前:</label>
        <input type="text" name="FROM"><br />
        <input type="hidden" name="mail">
        <label>コメント内容:</label>
        <textarea type="text" name="MESSAGE"></textarea>
        <div class="button">
        <button type="submit">書き込み</button>
        </div>
        </form>

        <div class="sort">ソート:<a href="../../r/{{$board_info->board_url}}/hot"> HOT </a><a href="../../r/{{$board_info->board_url}}/new"> NEW </a><a href="../../r/{{$board_info->board_url}}/top"> TOP</a></div>
        <table>
        <tr><th>Upvote</th><th>タイトル</th><th>勢い</th><th>総レス</th><th>スレ立て時間</th><th>最終書き込み時間</th></tr>
        @if(isset($sticky_thread_datas))
        @foreach($sticky_thread_datas as $thread_data)
            <tr>
                <td>{{$thread_data->upvote}}</td>
                <td><a href="r/{{$board_info->board_url}}/{{$thread_data->thread_key}}">{{$thread_data->title}}</a></td>
                <td>{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}}</td>
                <td>{{$thread_data->res}}</td>
                <td>{{$thread_data->created_at}}</td>
                <td>{{$thread_data->updated_at}}</td>
            </tr>
        @endforeach
        @endif

        @if(isset($thread_datas_3times))
        @foreach($thread_datas_3times as $thread_data)
            <tr>
                <td>{{$thread_data->upvote}}</td>
                <td><a href="../../../r/{{$board_info->board_url}}/{{$thread_data->thread_key}}">{{$thread_data->title}}</a></td>
                <td>{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}}</td>
                <td>{{$thread_data->res}}</td>
                <td>{{$thread_data->created_at}}</td>
                <td>{{$thread_data->updated_at}}</td>
            </tr>
        @endforeach
        @endif

        @if(isset($thread_datas_6times))
        @foreach($thread_datas_6times as $thread_data)
            <tr>
                <td>{{$thread_data->upvote}}</td>
                <td><a href="../../../r/{{$board_info->board_url}}/{{$thread_data->thread_key}}">{{$thread_data->title}}</a></td>
                <td>{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}}</td>
                <td>{{$thread_data->res}}</td>
                <td>{{$thread_data->created_at}}</td>
                <td>{{$thread_data->updated_at}}</td>
            </tr>
        @endforeach
        @endif

        @if(isset($thread_datas_12times))
        @foreach($thread_datas_12times as $thread_data)
            <tr>
                <td>{{$thread_data->upvote}}</td>
                <td><a href="../../../r/{{$board_info->board_url}}/{{$thread_data->thread_key}}">{{$thread_data->title}}</a></td>
                <td>{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}}</td>
                <td>{{$thread_data->res}}</td>
                <td>{{$thread_data->created_at}}</td>
                <td>{{$thread_data->updated_at}}</td>
            </tr>
        @endforeach
        @endif

        @if(isset($thread_datas_24times))
        @foreach($thread_datas_24times as $thread_data)
            <tr>
                <td>{{$thread_data->upvote}}</td>
                <td><a href="../../../r/{{$board_info->board_url}}/{{$thread_data->thread_key}}">{{$thread_data->title}}</a></td>
                <td>{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}}</td>
                <td>{{$thread_data->res}}</td>
                <td>{{$thread_data->created_at}}</td>
                <td>{{$thread_data->updated_at}}</td>
            </tr>
        @endforeach
        @endif

        @if(isset($thread_datas_24times_before))
        @foreach($thread_datas_24times_before as $thread_data)
            <tr>
                <td>{{$thread_data->upvote}}</td>
                <td><a href="../../../r/{{$board_info->board_url}}/{{$thread_data->thread_key}}">{{$thread_data->title}}</a></td>
                <td>{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}}</td>
                <td>{{$thread_data->res}}</td>
                <td>{{$thread_data->created_at}}</td>
                <td>{{$thread_data->updated_at}}</td>
            </tr>
        @endforeach
        @endif

        @if(isset($thread_datas))
        @foreach($thread_datas as $thread_data)
            <tr>
                <td>{{$thread_data->upvote}}</td>
                <td><a href="../../../r/{{$board_info->board_url}}/{{$thread_data->thread_key}}">{{$thread_data->title}}</a></td>
                <td>{{(int)($thread_data->res / (($epoch_time - $thread_data->time)/60) * 60 * 24)}}</td>
                <td>{{$thread_data->res}}</td>
                <td>{{$thread_data->created_at}}</td>
                <td>{{$thread_data->updated_at}}</td>
            </tr>
        @endforeach
        @endif
        </table>
    </body>
</html>