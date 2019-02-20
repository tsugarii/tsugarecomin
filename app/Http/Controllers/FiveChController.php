<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;
use App\Http\Requests\FiveChRequest;

use App\thread;
use App\comment;

class FiveChController extends Controller
{
    public function write_ready() {
        return view('write_ready');
    }

    public function read_cgi_board($board_key) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            return redirect('r/' . $board_key);
        } else {
            return "指定された板は存在しません。";
        }
    }

    public function read_cgi_thread($board_key,$thread_key) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            if(preg_match('/^[0-9]{10}$/',$thread_key) == 1) {
                $thread = new thread;
                if($thread::where('time', $thread_key)->exists()) {
                    $thread_key = $thread::where('time', $thread_key)->value('thread_key');
                    return redirect('r/' . $board_key. '/' . $thread_key);
                } else {
                    return "指定されたスレッドは存在しません。";
                }
            }
            return "指定された板は存在しません。";
        }
        return "スレッドキーがおかしいです。";
    }

    public function read_cgi_res($board_key,$thread_key,$res_number) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            if(preg_match('/^[0-9]{10}$/', $thread_key) == 1) {
                if(preg_match('/^[0-9]{1,4}$/', $res_number) == 1) {
                    $thread = new thread;
                    if($thread::where('time', $thread_key)->exists()) {
                        $thread_key = $thread::where('time', $thread_key)->value('thread_key');
                        return redirect('r/' . $board_key. '/' . $thread_key . '/' . $res_number);
                    }else {
                        return "スレッドが見つかりません。";
                    }
                } else {
                    return "レス番号の指定がおかしいです。";
                }
            }
            return "板キーがおかしいです。";
        }
        return "スレッドキーがおかしいです。";
    }

    public function write(Request $request,FiveChRequest $reqest) {

        //時間帯を日本に設定
        date_default_timezone_set('Asia/Tokyo');

        $thread = new thread;
        $comment = new comment;
        
        $user = Auth::user();

        $ip = $request->server('REMOTE_ADDR');

        //特殊文字<>&"改行を置換
         $this->escape($request);

        //代入する。
         $form = $request;

        //改行の数を確認して20以上ならエラーを出す。
        $br_count = mb_substr_count($form->MESSAGE, "\n");
        if($br_count > 20) {
            return "ERROR:Too much new line!!";
        }

        //トリップの処理をする。
        $trip_key = $this->trip($form);

        //コマンド欄の処理をする。
        $command_str = $this->command($form,$ip);

        //掲示板の情報を取り出す。
        $path = public_path();
        $board_info_json = file::get($path . '/' . $form->bbs . '/board_info.json');
        $board_info = json_decode($board_info_json);

        //スレッドの新規作成かスレへの書き込みかを判定。
        //スレ立ての場合。
        if(isset($form->subject) && !isset($form->key)) {

            //IPから連投を制限
            if(Cache::has('_' . $ip . '_thread')) {
                return "PLESE WAIT 30 SECONDS";
            } else {
                cache::put('_' . $ip . '_thread', 1, now()->addSeconds(30));
            }

            //既に投稿されてないか確認。
            if($thread::where('board_key', $form->bbs)->where('time', $form->key)->exists()) {
                return "ERROR:DO NOT POST SAME TIME!!";
            }

            //スレッドにアカウントが必須か確認し、必要ならチェックする
            if($board_info->account_required) {
                if(Auth::check()) {
                    return "アカウントが認証されています。";
                } else {
                    return "この板はスレ立てにアカウント登録が必要です。";
                }
            }

            //スレッドキーの生成
            $thread_key = $this->key_generator($thread,$comment,$form,6,NULL);

            //コメントキーの生成
            $comment_key = $this->key_generator($thread,$comment,$form,7,$thread_key);

            //スレッドMysqlに保存
            $this->thread_save($thread,$thread_key,$form);

            //コメントmysqlに保存。
            $this->comment_save($comment,$thread_key,$comment_key,1,$form,$board_info->default_name,$ip);

            // スレッドをsubject.txtに書き足す
            $this->subject_txt_save($thread,$form);

            //スレッドのdatファイルに書き込み
            $this->thread_dat_save($thread,$comment,$form,$command_str,$trip_key);

            return redirect('../r/' . $form->bbs . '/' . $thread_key);
            
        //コメントの場合。
        }else if(!isset($form->subject) && isset($form->key)) {

            //スレッドを検索
            if(!$thread::where('board_key', $form->bbs)->where('time', $form->key)->exists()) {
                return "ERROR:thread was not found!";
            }

            //スレッドが半年経過しているか確認
            $epoch_time_now = time();

            if(($epoch_time_now - $form->key) > 15552000) {

                $this->thread_subject_delete($thread,$form);

                return "ERROR:thread is dead";
            }

            //スレッドが1000を越えているか確認
            if($thread::where('time', $form->key)->value('res') === 999) {

                $this->thread_subject_delete($thread,$form);

                return "ERROR:thread is 1000 over!";
            }
            
            //datファイルが512kbを越えているか確認
            $path = public_path();
            if((file::size($path. '/' . $form->bbs . '/dat/' . $form->key . '.dat')) > 512000) {

                $this->thread_subject_delete($thread,$form);

                return "ERROR:thread size 512kb OVER!";
            }

            //IPから連投を制限
            if(Cache::has('_' . $ip . '_res')) {
                return "PLESE WAIT 7 SECONDS";
            } else {
                cache::put('_' . $ip . '_res', 1, now()->addSeconds(7));
            }

            //スレッドキーを取得する。
            $thread_key = $thread::where('time', $form->key)->value('thread_key');

            //コメントキーの生成。
            $comment_key = $this->key_generator($thread,$comment,$form,7,$thread_key);

            //スレッドMysqlを更新してレスの数を返す。
            $res_count = $this->thread_update($thread,$form);

            //レス番号を足しておく。
            $res_count++;

            //コメントmysqlに保存。
            $this->comment_save($comment,$thread_key,$comment_key,$res_count,$form,$board_info->default_name,$ip);

            //レス番号を引いておく。
            $res_count--;

            //スレッドsubject.txtを更新する。
            $this->subject_txt_update($thread,$form,$res_count);

            //スレッドのdatファイルに書き込み。
            $this->thread_dat_update($comment,$form,$command_str,$res_count,$trip_key);

            return redirect('../r/' . $form->bbs . '/' . $thread_key);

        }else {
            return "ERROR!!";
        }
    }

    private function thread_save($thread,$thread_key,$form) {
        $thread->board_key = $form->bbs;
        $thread->thread_key = $thread_key;
        $thread->title = $form->subject;
        $thread->time = time();
        $thread->created_at = time();
        $thread->updated_at = time();
        $thread->save();
    }

    private function thread_update($thread,$form) {
        $res_count = $thread::where('time', $form->key)->value('res');
        $thread::where('time', $form->key)->update(['res' => ($res_count+1)]);

        return $res_count;
    }

    private function comment_save($comment,$thread_key,$comment_key,$res_number,$form,$default_name,$ip) {

        $comment->board_key = $form->bbs;
        $comment->thread_key = $thread_key;
        $comment->comment_key = $comment_key;
        $comment->res_number = $res_number;

        if($form->FROM != NULL) {
            $comment->name = $form->FROM;
        }else {
            $comment->name = $default_name;
            $form->FROM = $default_name;
        }

        if($form->mail != NULL) {
            $comment->command = $form->mail;
        } else {
            $comment->command = '';
        }

        $comment->epoch_time = time();
        $comment->created_at = time();
        $comment->updated_at = time();


        $comment->ip = $ip;
        $comment->MESSAGE = $form->MESSAGE;

        $timestamp_1 = date('Y/m/d');
        $timestamp_2 = date('H:i:s');

        $secret = 'secret'; 
        $id_hash = hash_hmac("sha1", $form->bbs . $timestamp_1 . substr($ip, 0, 8), $secret);
        $id_base64 = base64_encode($id_hash);
        $id =  substr($id_base64, 0, 8);

        $week = array('日', '月', '火', '水', '木', '金', '土');
        $w = date('w');

        $comment->id = $id;

        $comment->time = $timestamp_1 . ' (' . $week[$w] . ') ' . $timestamp_2;

        $comment->save();
    }

    private function subject_txt_save($thread,$form) {
        $path = public_path();

        $handle = fopen($path.'/' . $form->bbs . '/subject.txt', 'a');

        $string_thread = $thread->time . '.dat<>' . $form->subject . '  (1)' . "\n";
        $string_thread = mb_convert_encoding($string_thread, 'SJIS-win', 'utf-8');

        fwrite($handle, $string_thread);

        fclose($handle);
    }

    private function subject_txt_update($thread,$form,$res_count) {
        $thread_subject = $thread::where('time', $form->key)->value('title');
        $serch_str = $form->key . '.dat<>' . $thread_subject . '  (' . $res_count . ')' . "\n";
        $replace_str = $form->key . '.dat<>' . $thread_subject . '  (' . ($res_count+1) . ')' . "\n";

        $path = public_path();

        $subject_txt = File::get($path. '/' . $form->bbs . '/' . 'subject.txt');
        $subject_txt = mb_convert_encoding($subject_txt, 'utf-8', 'SJIS-win');
        $subject_txt = str_replace($serch_str, $replace_str, $subject_txt);

        $handle = fopen($path.'/'. $form->bbs .'/subject.txt', 'w');
        $subject_txt = mb_convert_encoding($subject_txt, 'SJIS-win', 'utf-8');
        fwrite($handle, $subject_txt);

        fclose($handle);
    }

    private function thread_subject_delete($thread,$form) {
        $thread_subject = $thread::where('time', $form->key)->value('title');
        $res_count = $thread::where('time', $form->key)->value('res');

        $serch_str = $form->key . '.dat<>' . $thread_subject . '  (' . $res_count . ')' . "\n";

        $path = public_path();

        $subject_txt = File::get($path. '/' . $form->bbs . '/' . 'subject.txt');
        $subject_txt = mb_convert_encoding($subject_txt, 'utf-8', 'SJIS-win');
        $subject_txt = str_replace($serch_str, '', $subject_txt);

        $handle = fopen($path.'/'. $form->bbs .'/subject.txt', 'w');
        $subject_txt = mb_convert_encoding($subject_txt, 'SJIS-win', 'utf-8');
        fwrite($handle, $subject_txt);

        fclose($handle);
    }

    private function thread_dat_save($thread,$comment,$form,$command_str,$trip_key) {
        $path = public_path();
        $handle = fopen($path.'/'. $form->bbs . '/dat/'. $thread->time . '.dat', 'w');

        if($command_str != '' && $trip_key != '') {
            $string_res = $form->FROM . '</b>◆' . $trip_key . ' (' . $command_str . ') <b> <><>' . $comment->time . ' ID:' . $comment->id . '<>' . $form->MESSAGE . '<>' . $form->subject . "\n";
        } else if($command_str != '' && $trip_key == '') {
            $string_res = $form->FROM . '</b> (' . $command_str . ') <b> <><>' . $comment->time . ' ID:' . $comment->id . '<>' . $form->MESSAGE . '<>' . $form->subject . "\n";
        } else if($command_str == '' && $trip_key != '') {
            $string_res = $form->FROM . '</b>◆' . $trip_key . '<b> <><>' . $comment->time . ' ID:' . $comment->id . '<>' . $form->MESSAGE . '<>' . $form->subject . "\n";
         } else {
            $string_res = $form->FROM . '<><>' . $comment->time . ' ID:' . $comment->id . '<>' . $form->MESSAGE . '<>' . $form->subject . "\n";
        }
        
        $string_res = mb_convert_encoding($string_res, 'SJIS-win', 'utf-8');
        fwrite($handle, $string_res);

        fclose($handle);
    }

    private function thread_dat_update($comment,$form,$command_str,$res_count,$trip_key) {
        $path = public_path();

        $handle = fopen($path.'/'. $form->bbs . '/dat/'. $form->key . '.dat', 'a');

        if($command_str != '' && $trip_key != '') {
            $string_res = $form->FROM . '</b>◆' . $trip_key . ' (' . $command_str . ') <b> <><>' . $comment->time . ' ID:' . $comment->id . '<>' . $form->MESSAGE . '<>' . "\n";
        } else if($command_str != '' && $trip_key == '') {
            $string_res = $form->FROM . '</b> (' . $command_str . ') <b> <><>' . $comment->time . ' ID:' . $comment->id . '<>' . $form->MESSAGE . '<>' . "\n";
        }else if($command_str == '' && $trip_key != '') {
            $string_res = $form->FROM . '</b>◆' . $trip_key . '<b> <><>' . $comment->time . ' ID:' . $comment->id . '<>' . $form->MESSAGE . '<>' . "\n";
        } else {
            $string_res = $form->FROM . $command_str . '<><>' . $comment->time . ' ID:' . $comment->id . '<>' . $form->MESSAGE . '<>' . "\n";
        }

        $string_res = mb_convert_encoding($string_res, 'SJIS-win', 'utf-8');
        fwrite($handle, $string_res);

        fclose($handle);

        if($res_count === 999) {

            $thousand = "このスレッドは1000を越えました<br />新しいスレッドを立ててください。<br />";
            $tenone = "ツガレコミンは完全無料完全無広告で頑張って運営しています。<br />安定した運営への協力をお願いします。<br />";

            $handle = fopen($path.'/'. $form->bbs . '/dat/'. $form->key . '.dat', 'a');
            $string_res = '1000<><>1000 OVER Thread<>' . $thousand . '<>' . "\n";
            $string_res = mb_convert_encoding($string_res, 'SJIS-win', 'utf-8');
            fwrite($handle, $string_res);
            fclose($handle);

            $handle = fopen($path.'/'. $form->bbs . '/dat/'. $form->key . '.dat', 'a');
            $string_res = '1001<><>1000 OVER Thread<>' . $tenone . '<>' . "\n";
            $string_res = mb_convert_encoding($string_res, 'SJIS-win', 'utf-8');
            fwrite($handle, $string_res);
            fclose($handle);
        }
    }

    private function trip($form) {

        $form->FROM = str_replace('◆', '◇', $form->FROM);

        if(preg_match('/#/', $form->FROM) == 1) {
            $name_trip = explode('#', $form->FROM, 2);
            $form->FROM = $name_trip[0];

            return $trip_key = str_replace('+', '.', substr(base64_encode(sha1($name_trip[1], true)), 0, 12));
        }

        return '';
    }

    private function command($form,$ip) {
        if($form->mail != NULL) {
            if(preg_match('/^ip$/i', $form->mail) == 1) {
                return $ip;
            }
        }

        return '';
    }

    private function escape($request) {
        if(isset($request->subject)) {
            $request->subject = str_replace('&','&amp;',$request->subject);
            $request->subject = str_replace('<','&lt;',$request->subject);
            $request->subject = str_replace('>','&gt;',$request->subject);
            $request->subject = str_replace('"','&quot;',$request->subject);
            $request->subject = str_replace("\n",'',$request->subject);
        }

        $request->FROM = str_replace('&','&amp;',$request->FROM);
        $request->FROM = str_replace('<','&lt;',$request->FROM);
        $request->FROM = str_replace('>','&gt;',$request->FROM);
        $request->FROM = str_replace('"','&quot;',$request->FROM);
        $request->FROM = str_replace("\n",'',$request->FROM);
        
        $request->mail = str_replace('&','&amp;',$request->mail);
        $request->mail = str_replace('<','&lt;',$request->mail);
        $request->mail = str_replace('>','&gt;',$request->mail);
        $request->mail = str_replace('"','&quot;',$request->mail);
        $request->mail = str_replace("\n",'',$request->mail);

        $request->MESSAGE = str_replace('&','&amp;',$request->MESSAGE);
        $request->MESSAGE = str_replace('<','&lt;',$request->MESSAGE);
        $request->MESSAGE = str_replace('>','&gt;',$request->MESSAGE);
        $request->MESSAGE = str_replace('"','&quot;',$request->MESSAGE);
        $request->MESSAGE = str_replace("\n", '<br />', $request->MESSAGE);
    }

    private function key_generator($thread,$comment,$form,$length,$thread_key) {
        $flag = true;

        while($flag) {
            $str_random = str_random($length);

           if($length == 6) {
               if(!$thread::where('board_key', $form->bbs)
               ->where('thread_key', $str_random)->exists()) {
                    $flag = false;
               }
           }

           if($length == 7) {
                if(!$comment::where('board_key', $form->bbs)
                ->where('thread_key', $thread_key)
                ->where('comment_key', $str_random)->exists()) {
                    $flag = false;
                }
           }
        }
        
        return $str_random;
    }
}
