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
 * @method \app\lib\swoole\Task email(array $data) static 内置异步发送邮件
 * @method \app\lib\swoole\Task test() static 异步测试写入文件
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
     * @param string|array $args 参数
     * @throws \app\lib\exception\push\SwooleException
     * @return object
     */
    public static function getClass($method, &$args)
    {
        $class = 'app\lib\swoole\Task';
        if (class_exists($class) && method_exists($class, $method)) {
            $args = $args[0] ?? [];
        } else {
            if (is_array($args) && isset($args[0]) && !empty($args[0])) {
                if (!class_exists($args[0])) {
                    throw new SwooleException(['code'=>400, 'message'=>'自定义类不存在：'.$args[0], 'error_code'=>50004]);
                } else if (!method_exists($args[0], $method)) {
                    throw new SwooleException(['code'=>400, 'message'=>'自定义方法不存在：'.$method, 'error_code'=>50005]);
                } else {
                    $class = $args[0];
                    $args = $args[1] ?? [];
                }
            } else {
                throw new SwooleException(['code'=>400, 'message'=>'参数错误', 'error_code'=>50003]);
            }
        }
        return $class;
    }
}