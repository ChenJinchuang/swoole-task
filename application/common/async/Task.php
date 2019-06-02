<?php
/**
 * Created by PhpStorm.
 * User: hkj
 * Date: 2019/5/31
 * Time: 22:36
 */

namespace app\common\async;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use think\facade\Config;
use app\lib\exception\push\PushException;

/**
 * Class Task
 * @package app\common\async
 */
class Task
{
    /**
     * 发送邮件
     * @param array $to 接收人的邮箱地址，数组
     * @param string $title 邮件标题
     * @param string $content   邮件内容
     * @throws app\lib\exception\push\PushException
     * @return boolean
     */
    public function sendEmail(array $to, $title='', $content='')
    {
        if (empty($to))
            return '';
        $config = Config::pull('email');
        try{
            $mail = new PHPMailer($config['debug']);
            $mail->isSMTP();
            $mail->CharSet=$config['char_set'];
            $mail->Host = $config['host'];
            $mail->SMTPAuth = true;
            $mail -> Username = $config['username'];
            $mail -> Password =$config['password'];
            $mail->SMTPSecure = $config['smtp_secure'];
            $mail -> Port = $config['port'];
            $mail -> setFrom($config['username'],$config['send_name']);
            foreach($to as $v)
            {
                $mail->addAddress($v,'');
            }
            $mail->addReplyTo($config['username'],'info');
            $mail -> isHTML($config['is_html']);
            $mail -> Subject = $title;
            $mail -> Body = $content;
            $mail -> AltBody = $config['alt_body'];
            // $mail->WordWrap = 50;                                 //多少字换行
//            $mail->addAttachment('/data/wwwroot/PHP7.3.pdf');         // Add attachments 添加附件
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name 发送附件并重命名
            $mail -> send();
            return true;
        }catch(Exception $e)
        {
            //或日志记录
            throw new PushException([
                'code'=>'500',
                'msg'=>'邮件发送失败：',$mail->ErrorInfo
            ]);

        }
    }

}