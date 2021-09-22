<?php
/**
 * Author:Shaun·Yang
 * Date:2021/9/18
 * Time:上午11:38
 * Description:
 */

namespace wlhywebservice;

interface MsgInterface
{
    public function getMsgType();

    public function getMsg();

    public function setHeader($key, $val);

    public function setBody($key, $val);

}