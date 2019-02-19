<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

use App\thread;
use App\comment;

class JsonController extends Controller
{
    public function board_read($board_key) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            $thread = new thread;

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
                    ->orderby('upvote', 'asc')->get();

                    $thread_datas_3times = $thread::where('board_key', $board_key)
                    ->where('sticky', 0)
                    ->where('deleted', 0)
                    ->where('time', '>', (time() - 10800))
                    ->orderby('upvote', 'asc')->get();

                    $thread_datas_6times = $thread::where('board_key', $board_key)
                    ->where('sticky', 0)
                    ->where('deleted', 0)
                    ->where('time', '<', (time() - 10800))
                    ->where('time', '>', (time() - 21600))
                    ->orderby('upvote', 'asc')->get();

                    $thread_datas_12times = $thread::where('board_key', $board_key)
                    ->where('sticky', 0)
                    ->where('deleted', 0)
                    ->where('time', '<', (time() - 21600))
                    ->where('time', '>', (time() - 43200))
                    ->orderby('upvote', 'asc')->get();

                    $thread_datas_24times = $thread::where('board_key', $board_key)
                    ->where('sticky', 0)
                    ->where('deleted', 0)
                    ->where('time', '<', (time() - 43200))
                    ->where('time', '>', (time() - 86400))
                    ->orderby('upvote', 'asc')->get();

                    $thread_datas_24times_before = $thread::where('board_key', $board_key)
                    ->where('sticky', 0)
                    ->where('deleted', 0)
                    ->where('time', '<', (time() - 86400))
                    ->orderby('upvote', 'asc')->get();

                    cache::put('_sticky_' . $board_key, $sticky_thread_datas, now()->addSeconds(10));
                    cache::put('_3times_' . $board_key, $thread_datas_3times, now()->addSeconds(10));
                    cache::put('_6times_' . $board_key, $thread_datas_6times, now()->addSeconds(10));
                    cache::put('_12times_' . $board_key, $thread_datas_12times, now()->addSeconds(10));
                    cache::put('_24times_' . $board_key, $thread_datas_24times, now()->addSeconds(10));
                    cache::put('_24times_before_' . $board_key, $thread_datas_24times_before, now()->addSeconds(10));
                } else {
                    $thread_datas = NULL;
                }
            }

            if($sticky_thread_datas != null) {
                header("Content-Type: application/json; charset=urf-8");

                echo '[';

                foreach($sticky_thread_datas as $thread_data) {

                    unset($thread_data['board_key']);
                    unset($thread_data['deleted']);

                    echo json_encode($thread_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

                    echo ',';
                }

                if($thread_datas_3times != NULL) {
                    foreach($thread_datas_3times as $thread_data) {
    
                        unset($thread_data['board_key']);
                        unset($thread_data['deleted']);
    
                        echo json_encode($thread_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    
                        echo ',';
                    }
                }
                
                if($thread_datas_6times != NULL) {
                    foreach($thread_datas_6times as $thread_data) {
    
                        unset($thread_data['board_key']);
                        unset($thread_data['deleted']);
    
                        echo json_encode($thread_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    
                        echo ',';
                    }
                }
                
                if($thread_datas_12times != NULL) {
                    foreach($thread_datas_12times as $thread_data) {
    
                        unset($thread_data['board_key']);
                        unset($thread_data['deleted']);
    
                        echo json_encode($thread_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    
                        echo ',';
                    }
                }
                
                if($thread_datas_24times != NULL) {
                    foreach($thread_datas_24times as $thread_data) {
    
                        unset($thread_data['board_key']);
                        unset($thread_data['deleted']);
    
                        echo json_encode($thread_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    
                        echo ',';
                    }
                }
                if($thread_datas_24times_before != NULL) {

                    $length = count($thread_datas_24times_before);
                    $count = 1;

                    foreach($thread_datas_24times_before as $thread_data) {

                        unset($thread_data['board_key']);
                        unset($thread_data['deleted']);

                        echo json_encode($thread_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

                        if($length > $count) {
                            echo ',';
                        }

                        $count++;
                    }
                }

                echo ']';

            } else {
                return "[]";
            }

        } else {
            return "板キーがおかしいです。";
        }
    }
    
    public function board_read_new($board_key) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            $thread = new thread;

            if(Cache::has('_' . $board_key . '_new')) {
                $thread_datas = Cache::get('_' . $board_key . '_new');
            } else {
                if($thread::where('board_key', $board_key)->exists()) {

                    $thread_datas = $thread::where('board_key', $board_key)
                    ->where('deleted', 0)
                    ->orderby('time', 'desc')->get();

                    cache::put('_' . $board_key . '_new', $thread_datas, now()->addSeconds(10));
                } else {
                    $thread_datas = NULL;
                }
            }

            if($thread_datas != null) {
                header("Content-Type: application/json; charset=urf-8");

                $length = count($thread_datas);
                $count = 1;

                echo '[';
                foreach($thread_datas as $thread_data) {

                    unset($thread_data['board_key']);
                    unset($thread_data['deleted']);

                    echo json_encode($thread_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

                    if($length > $count) {
                        echo ',';
                    }

                    $count++;
                }
                echo ']';

            } else {
                return "[]";
            }

        } else {
            return "板キーがおかしいです。";
        }
    }

    public function board_read_top($board_key) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            $thread = new thread;

            if(Cache::has('_' . $board_key . '_top')) {
                $thread_datas = Cache::get('_' . $board_key . '_top');
            } else {
                if($thread::where('board_key', $board_key)->exists()) {

                    $thread_datas = $thread::where('board_key', $board_key)
                    ->orderby('upvote', 'asc')->get();

                    cache::put('_' . $board_key . '_top', $thread_datas, now()->addSeconds(10));
                } else {
                    $thread_datas = NULL;
                }
            }

            if($thread_datas != null) {
                header("Content-Type: application/json; charset=urf-8");

                $length = count($thread_datas);
                $count = 1;

                echo '[';
                foreach($thread_datas as $thread_data) {
                    if($thread_data['deleted'] == 1) continue;

                    unset($thread_data['board_key']);
                    unset($thread_data['deleted']);

                    echo json_encode($thread_data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

                    if($length > $count) {
                        echo ',';
                    }

                    $count++;
                }
                echo ']';
            } else {
                return "[]";
            }

        } else {
            return "板キーがおかしいです。";
        }
    }

    public function thread_read($board_key,$thread_key) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            if(preg_match('/^[A-Za-z0-9]{6}$/', $thread_key) == 1) {
                $comment = new comment;

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

                if($reses != NULL) {
                    header("Content-Type: application/json; charset=urf-8");

                    $length = count($reses);
                    $count = 1;

                    echo '[';
                    foreach($reses as $res) {
                        if($res['deleted'] == 1) {
                            $res['rank'] = 0;
                            $res['name'] = "DELETED";
                            $res['command'] = "DELETED";
                            $res['id'] = "DELETED";
                            $res['user_id'] = "DELETED";
                            $res['message'] = "DELETED";
                        }

                        unset($res['board_key']);
                        unset($res['thread_key']);
                        unset($res['comment_key']);
                        unset($res['res_number']);
                        unset($res['epoch_time']);
                        unset($res['ip']);
                        unset($res['deleted']);

                        echo json_encode($res, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                        
                        if($length > $count) {
                            echo ',';
                        }

                        $count++;
                    }
                    echo ']';
                } else {
                    return "[]";
                }
            } else {
                return "スレッドキーがおかしいです。";
            }
        } else {
            return "板キーがおかしいです";
        }
    }

    public function res_read($board_key,$thread_key,$res_select) {
        if(preg_match('/^min$|^nnp$/', $board_key) == 1) {
            if(preg_match('/^[A-Za-z0-9]{6}$/', $thread_key) == 1) {
                if(preg_match('/^[0-9]{1,4}$|^[a-zA-Z0-9]{7}$/', $res_select) == 1) {
                    $comment = new comment;

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

                            unset($res['board_key']);
                            unset($res['thread_key']);
                            unset($res['comment_key']);
                            unset($res['res_number']);
                            unset($res['epoch_time']);
                            unset($res['ip']);
                            unset($res['deleted']);

                            header("Content-Type: application/json; charset=urf-8"); 
                            echo json_encode($res, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                        } else {
                            return "指定されたレスがありません。";
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

                            unset($res['board_key']);
                            unset($res['thread_key']);
                            unset($res['comment_key']);
                            unset($res['res_number']);
                            unset($res['epoch_time']);
                            unset($res['ip']);
                            unset($res['deleted']);
                            
                            header("Content-Type: application/json; charset=urf-8"); 
                            echo json_encode($res, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                        }else {
                            return "指定されたレスがありません。";
                         }
                    }
                }
            }
        }
    }
}
