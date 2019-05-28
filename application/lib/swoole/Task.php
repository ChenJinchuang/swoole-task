<?php

namespace app\lib\swoole;
use app\lib\push\Send;
/*
 * 代表 swoole 里面 后续所有的 task异步任务 都放在里面做
 * */
class Task{

    /*
     * 异步发送 邮件
     * @param $data 发送数据
     * */
    public function email($data)
    {
        try{
            $send = new Send();
            $status = $send -> send($data);
        }catch(\Exception $e){
            return false;
        }
        return true;
    }

    public function test()
    {
        $file = env('ROOT_PATH').'public/test.txt';
        $fp = fopen($file, 'a+');
        for ($i = 0; $i < 10; $i++) {
            fwrite($fp, $i . "=>" . "task-test\n");
            sleep(1);
        }
        fclose($fp);
        return '';
    }

}
