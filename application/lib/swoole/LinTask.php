<?php
/**
 * Created by PhpStorm.
 * User: hkj
 * Date: 2019/5/28
 * Time: 21:49
 */

namespace app\lib\swoole;

use app\lib\exception\push\SwooleException;

/**
 * Class LinTask
 * @method \app\lib\swoole\Task sendEmail(array $to, string $title, string $content) static 内置异步发送邮件
 */
class LinTask
{
    public static function __callStatic($method, $args)
    {
        $class = self::getClass($method, $args);
        $data = [
            'class' => $class,
            'method' => $method,
            'data' => $args
        ];
        Pclient::getInstance()->sSend(json_encode($data));
    }

    /**
     * @param string $method 方法名
     * @param array $args 参数
     * @throws \app\lib\exception\push\SwooleException
     * @return string $class 类的路径
     */
    public static function getClass($method, &$args)
    {
        $class = 'app\common\async\Task';
        $params = [];
        if (class_exists($class) && method_exists($class, $method)) {
            if (!empty($args)) {    //将传入的多个参数，装入索引数组，在task回调事件中可用...$data形式分别传递参数
                foreach ($args as $val) {
                    $params[] = $val;
                }
            }
        } else {
            if (count($args)>0 && is_string($args[0]) && !empty($args[0])) {
                $class = 'app\\common\\async\\'.ucfirst($args[0]);
                if (!class_exists($class)) {
                    throw new SwooleException(['code'=>400, 'msg'=>'自定义类不存在：'.$class, 'error_code'=>50004]);
                } else if (!method_exists($class, $method)) {
                    throw new SwooleException(['code'=>400, 'msg'=>'自定义方法不存在：'.$method, 'error_code'=>50005]);
                } else {
                    foreach ($args as $k=>$v) {
                        $k != 0 && $params[] = $v;
                    }
                }
            } else {
                throw new SwooleException(['code'=>400, 'msg'=>'请传入正确的类路径', 'error_code'=>50003]);
            }
        }
        $args = $params;
        return $class;
    }
}