<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;

use App\thread;
use App\comment;

class TsugarecominController extends Controller
{
    public function board_read($board_key) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            $thread = new thread;

            $path = public_path();

            $board_info_json = file::get($path . '/' . $board_key . '/board_info.json');
            $board_info = json_decode($board_info_json);

            if(Cache::has('_sticky_' . $board_key)) {
                $sticky_thread_datas =  Cache::get('_sticky_' . $board_key);
                $thread_datas_3times = Cache::get('_3times_' . $board_key);
                $thread_datas_6times = Cache::get('_6times_' . $board_key);
                $thread_datas_12times = Cache::get('_12times_' . $board_key);
                $thread_datas_24times = Cache::get('_24times_' . $board_key);
                $thread_datas_24times_before = Cache::get('_24times_before_' . $board_key);
            } else {
                if($thread::where('board_key', $board_key)->exists()) {

                    $sticky_thread_datas =  $thread::where('board_key', $board_key)
                    ->where('sticky', 1)
                    ->orderby('upvote', 'desc')->get();

                    $thread_datas_3times = $thread::where('board_key', $board_key)
                    ->where('sticky', 0)->where('deleted', 0)
                    ->where('time', '>', (time() - 10800))
                    ->orderby('upvote', 'desc')->get();

                    $thread_datas_6times = $thread::where('board_key', $board_key)
                    ->where('sticky', 0)->where('deleted', 0)
                    ->where('time', '<', (time() - 10800))
                    ->where('time', '>', (time() - 21600))
                    ->orderby('upvote', 'desc')->get();

                    $thread_datas_12times = $thread::where('board_key', $board_key)
                    ->where('sticky', 0)->where('deleted', 0)
                    ->where('time', '<', (time() - 21600))
                    ->where('time', '>', (time() - 43200))
                    ->orderby('upvote', 'desc')->get();

                    $thread_datas_24times = $thread::where('board_key', $board_key)
                    ->where('sticky', 0)->where('deleted', 0)
                    ->where('time', '<', (time() - 43200))
                    ->where('time', '>', (time() - 86400))
                    ->orderby('upvote', 'desc')->get();

                    $thread_datas_24times_before = $thread::where('board_key', $board_key)
                    ->where('sticky', 0)->where('deleted', 0)
                    ->where('time', '<', (time() - 86400))
                    ->orderby('upvote', 'desc')
                    ->simplePaginate(10);

                    cache::put('_sticky_' . $board_key, $sticky_thread_datas, now()->addSeconds(3));
                    cache::put('_3times_' . $board_key, $thread_datas_3times, now()->addSeconds(3));
                    cache::put('_6times_' . $board_key, $thread_datas_6times, now()->addSeconds(3));
                    cache::put('_12times_' . $board_key, $thread_datas_12times, now()->addSeconds(3));
                    cache::put('_24times_' . $board_key, $thread_datas_24times, now()->addSeconds(3));
                    cache::put('_24times_before_' . $board_key, $thread_datas_24times_before, now()->addSeconds(3));
                } else {
                    $sticky_thread_datas = NULL;
                    $thread_datas_3times = NULL;
                    $thread_datas_6times = NULL;
                    $thread_datas_12times = NULL;
                    $thread_datas_24times = NULL;
                    $thread_datas_24times_before = NULL;
                }
            }

            return view('board', compact('board_info','sticky_thread_datas',
                                        'thread_datas_3times','thread_datas_6times','thread_datas_12times',
                                        'thread_datas_24times','thread_datas_24times_before'));
        }
    }

    public function board_read_new($board_key) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            $thread = new thread;

            $path = public_path();

            $board_info_json = file::get($path . '/' . $board_key . '/board_info.json');
            $board_info = json_decode($board_info_json);

            if(Cache::has('_' . $board_key . '_new')) {
                $sticky_thread_datas =  Cache::get('_sticky_' . $board_key);
                $thread_datas = Cache::get('_' . $board_key . '_new');
            } else {
                if($thread::where('board_key', $board_key)->exists()) {

                    $sticky_thread_datas =  $thread::where('board_key', $board_key)
                    ->where('sticky', 1)
                    ->orderby('time', 'desc')->get();

                    $thread_datas = $thread::where('board_key', $board_key)
                    ->where('sticky', 0)->where('deleted', 0)
                    ->orderby('time', 'desc')
                    ->simplePaginate(10);

                    cache::put('_sticky_' . $board_key, $sticky_thread_datas, now()->addSeconds(3));
                    cache::put('_' . $board_key . '_new', $thread_datas, now()->addSeconds(3));
                } else {
                    $sticky_thread_datas = NULL;
                    $thread_datas = NULL;
                }
            }

            return view('board', compact('board_info','sticky_thread_datas','thread_datas'));
        }
    }

    public function board_read_top($board_key) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            $thread = new thread;

            $path = public_path();

            $board_info_json = file::get($path . '/' . $board_key . '/board_info.json');
            $board_info = json_decode($board_info_json);

            if(Cache::has('_' . $board_key . '_top')) {
                $sticky_thread_datas =  Cache::get('_sticky_' . $board_key);
                $thread_datas = Cache::get('_' . $board_key . '_top');
            } else {
                if($thread::where('board_key', $board_key)->exists()) {

                    $sticky_thread_datas =  $thread::where('board_key', $board_key)
                    ->where('sticky', 1)
                    ->orderby('upvote', 'desc')->get();

                    $thread_datas = $thread::where('board_key', $board_key)
                    ->where('sticky', 0)->where('deleted', 0)
                    ->orderby('upvote', 'desc')
                    ->simplePaginate(10);

                    cache::put('_sticky_' . $board_key, $sticky_thread_datas, now()->addSeconds(3));
                    cache::put('_' . $board_key . '_top', $thread_datas, now()->addSeconds(3));
                } else {
                    $thread_datas = NULL;
                    $sticky_thread_datas = NULL;
                }
            }

            return view('board', compact('board_info','sticky_thread_datas','thread_datas'));
        }
    }

    public function thread_upvote(Request $request,$board_key,$thread_key) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            if(preg_match('/^[A-Za-z0-9]{6}$/', $thread_key) == 1) {
                if(Auth::check()) {
                    $thread = new thread;

                    if(Cache::has('_' . Auth::user()->user_id . '_' . $board_key . '_' . $thread_key . '_upvoted')) {
                        if($thread::where('board_key', $board_key)->where('thread_key', $thread_key)->exists()){
                            $user = $request->user();
                            $upvote = $thread::where('board_key', $board_key)->where('thread_key', $thread_key)->value('upvote');

                            $user->upvoted = $upvote - 1;
                            $user->save();
                            $thread::where('board_key', $board_key)->where('thread_key', $thread_key)->update(['upvote' => $upvote-1]);

                            Cache::forget('_' . Auth::user()->user_id . '_' . $board_key . '_' . $thread_key . '_upvoted');

                            return redirect('../../r/' . $board_key . '#' . $thread_key);
                        }else {
                            return "該当するスレッドが見つかりません。";
                        }
                        
                    } else {
                        if($thread::where('board_key', $board_key)->where('thread_key', $thread_key)->exists()) {
                            
                            Cache::forever('_' . Auth::user()->user_id . '_' . $board_key . '_' . $thread_key . '_upvoted', 1);

                            $user = $request->user();
                            $upvote = $thread::where('board_key', $board_key)->where('thread_key', $thread_key)->value('upvote');

                            $user->upvoted = $upvote + 1;
                            $user->save();
                            $thread::where('board_key', $board_key)->where('thread_key', $thread_key)->update(['upvote' => $upvote+1]);

                            return redirect('../../r/' . $board_key . '#' . $thread_key);
                        } else {
                            return "該当するスレッドが見つかりません。";
                        }
                        
                    }
                    
                } else {
                    return "ログインしていないとupvoteできません。";
                }
            }
        }
    }

    public function res_like(Request $request,$board_key,$thread_key,$comment_key) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            if(preg_match('/^[A-Za-z0-9]{6}$/', $thread_key) == 1) {
                if(preg_match('/^[a-zA-Z0-9]{7}$/', $comment_key) == 1) {
                    if(Auth::check()) {
                        $comment = new comment;

                        if(Cache::has('_' . Auth::user()->user_id . '_' . $board_key . '_' . $thread_key . '_' . $comment_key . '_liked')){
                            if($comment::where('board_key', $board_key)->where('thread_key', $thread_key)->where('comment_key', $comment_key)->exists()) {

                                $user = $request->user();
                                $liked = $comment::where('board_key', $board_key)->where('thread_key', $thread_key)->where('comment_key', $comment_key)->value('liked');

                                $user->liked = $liked - 1;
                                $user->save();
                                $comment::where('board_key', $board_key)->where('thread_key', $thread_key)->where('comment_key', $comment_key)->update(['liked' => $liked-1]);


                                Cache::forget('_' . Auth::user()->user_id . '_' . $board_key . '_' . $thread_key . '_' . $comment_key . '_liked');

                                return redirect('../../r/' . $board_key . '/' . $thread_key . '#' . $comment_key);
                            } else {
                                return "該当するレスが見つかりません。";
                            }
                        } else {
                            if($comment::where('board_key', $board_key)->where('thread_key', $thread_key)->where('comment_key', $comment_key)->exists()) {

                                Cache::forever('_' . Auth::user()->user_id . '_' . $board_key . '_' . $thread_key . '_' . $comment_key . '_liked',1);

                                $user = $request->user();
                                $liked = $comment::where('board_key', $board_key)->where('thread_key', $thread_key)->where('comment_key', $comment_key)->value('liked');

                                $user->liked = $liked + 1;
                                $user->save();
                                $comment::where('board_key', $board_key)->where('thread_key', $thread_key)->where('comment_key', $comment_key)->update(['liked' => $liked+1]);

                                return redirect('../../r/' . $board_key . '/' . $thread_key . '#' . $comment_key);
                            } else {
                                return "該当するレスが見つかりません。";
                            }
                        }
                    } else {
                        return "ログインしていないといいね出来ません。";
                    }
                }
            }
        }
    }

    public function thread_read($board_key,$thread_key) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            if(preg_match('/^[A-Za-z0-9]{6}$/', $thread_key) == 1) {
                $thread = new thread;
                $comment = new comment;

                $path = public_path();

                $board_info_json = file::get($path . '/' . $board_key . '/board_info.json');
                $board_info = json_decode($board_info_json);

                if($thread::where('board_key', $board_key)
                ->where('thread_key', $thread_key)->exists()) {
                    $thread_title = $thread::where('board_key', $board_key)
                                    ->where('thread_key', $thread_key)->value('title');

                    $key = $thread::where('board_key', $board_key)
                                    ->where('thread_key', $thread_key)->value('time');
                } else {
                    return "スレッドが存在しません。";
                }

                if(Cache::has('_' . $board_key . '_' . $thread_key)) {
                    $reses = Cache::get('_' . $board_key . '_' . $thread_key);

                } else {
                    if($comment::where('board_key', $board_key)
                    ->where('thread_key', $thread_key)->exists()) {

                        $reses = $comment::where('board_key', $board_key)
                        ->where('thread_key', $thread_key)
                        ->orderby('res_number', 'asc')->get();

                        cache::put('_' . $board_key . '_' . $thread_key, $reses, now()->addSeconds(3));
                    } else {
                        $reses = NULL;
                    }
                }

                return view('thread', compact('board_info','thread_title','key','reses'));
            } else {
                return "スレキーがおかしいです。";
            }
        } else {
            return "板キーがおかしいです。";
        }
    }

    public function res_read($board_key,$thread_key,$res_select) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            if(preg_match('/^[A-Za-z0-9]{6}$/', $thread_key) == 1) {
                if(preg_match('/^[0-9]{1,4}$|^[a-zA-Z0-9]{7}$/', $res_select) == 1) {
                    $thread = new thread;
                    $comment = new comment;

                    $path = public_path();

                    $board_info_json = file::get($path . '/' . $board_key . '/board_info.json');
                    $board_info = json_decode($board_info_json);
                    $board_name = $board_info->board_name;

                    if($thread::where('board_key', $board_key)
                    ->where('thread_key', $thread_key)->exists()) {
                        $thread_title = $thread::where('board_key', $board_key)
                                                ->where('thread_key', $thread_key)->value('title');
                    } else {
                        return "スレッドが存在しません。";
                    }

                    if(preg_match('/^[0-9]{1,4}$/', $res_select) == 1) {
                        if(($res = $comment::where('board_key', $board_key)
                        ->where('thread_key', $thread_key)
                        ->where('res_number', $res_select)->first()) != NULL) {
                            if($res['deleted'] == 1) {
                                $res['rank'] = 0;
                                $res['name'] = "DELETED";
                                $res['command'] = "DELETED";
                                $res['id'] = "DELETED";
                                $res['user_id'] = "DELETED";
                                $res['message'] = "DELETED";
                            }

                            $res_title = substr($res['message'], 0, 35);

                            return view('res', compact('board_name','thread_title','res_title','res'));
                        }
                    }

                    if(preg_match('/^[a-zA-Z0-9]{7}$/', $res_select) == 1) {
                        if(($res = $comment::where('board_key', $board_key)
                        ->where('thread_key', $thread_key)
                        ->where('comment_key', $res_select)->first()) != NULL) {
                            if($res['deleted'] == 1) {
                                $res['rank'] = 0;
                                $res['name'] = "DELETED";
                                $res['command'] = "DELETED";
                                 $res['id'] = "DELETED";
                                $res['user_id'] = "DELETED";
                                $res['message'] = "DELETED";
                            }

                            $res_title = substr($res['message'], 0, 35);

                            return view('res', compact('board_name','thread_title','res_title','res'));
                        }
                    }
                }
            }
        }
    }
    
    public function thread_id_read($board_key,$thread_key,$id) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            if(preg_match('/^[A-Za-z0-9]{6}$/', $thread_key) == 1) {
                if(preg_match('/^[A-Za-z0-9]{8}$/', $id) == 1) {
                    $comment = new comment;

                    if($comment::where('board_key', $board_key)->where('thread_key', $thread_key)->where('id', $id)->exists()) {
                        $reses = $comment::where('board_key', $board_key)->where('thread_key', $thread_key)->where('id', $id)
                        ->orderby('res_number', 'asc')->get();

                        return view('thread_id', compact('reses'));
                    } else {
                        return "該当のIDが存在しません。";
                    }
                }
            }
        }
    }
}
