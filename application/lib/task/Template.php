<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/6/7
 * Time: 0:46
 */

namespace app\lib\task;


interface Template
{
    public function run($arguments);
}