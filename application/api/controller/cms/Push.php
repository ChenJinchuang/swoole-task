<?php
/*
* Created by DevilKing
* Author:DevilKing
* Date: 2019- 05-23
*Time: 16:19:
*/
namespace app\api\controller\cms;
use app\lib\swoole\LinTask;
use think\Request;
use think\Controller;
/*
 * 发送邮件接口
 * */
class Push extends Controller
{
    /**
     * @auth('发送邮件功能','推送功能')
     * @param Request $request
     * @validate('SendForm')
     * @return json
     * @throws \think\exception
     */
    public function email(Request $request)
    {
        $data = $request -> post();
        //进行发送数据
        LinTask::sendEmail($data['to'], $data['title'], $data['content']);
        //发送成功
        return writeJson(201,'','ok',0);
    }
}