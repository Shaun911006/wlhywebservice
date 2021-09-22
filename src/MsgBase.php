<?php
/**
 * Author:Shaun·Yang
 * Date:2021/9/7
 * Time:上午10:54
 * Description:
 */

namespace wlhywebservice;

class MsgBase
{
    public array $header = [
        'MessageReferenceNumber' => '',
        'DocumentName' => '普通运输运单',
        'DocumentVersionNumber' => 'V2020',
        'SenderCode' => '*****',
        'RecipientCode' => '*******',
        'MessageSendingDateTime' => '',
    ];

    /**
     * 简单原生的数组转为xml
     * @param $arr
     * @param bool $isRoot
     * @return string
     */
    public function arrayToXml($arr, bool $isRoot = true): string
    {
        if ($isRoot) {
            $xml = '<?xml version="1.0" encoding="UTF-8"?><Root>';
        } else {
            $xml = '';
        }
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                //是否是非索引数组
                if ($this->is_assoc($val)) {
                    $xml .= "<" . $key . ">" . $this->arrayToXml($val, false) . "</" . $key . ">";
                } else {
                    foreach ($val as $v) {
                        $xml .= "<" . $key . ">" . $this->arrayToXml($v, false) . "</" . $key . ">";
                    }
                }

            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        if ($isRoot) {
            $xml .= "</Root>";
        }
        return $xml;
    }

    private function is_assoc($array): bool
    {
        if (is_array($array)) {
            $keys = array_keys($array);
            return $keys != array_keys($keys);
        }
        return false;

    }

    public static function getGuid(): string
    {
        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $charId = md5(uniqid(rand(), true));
        return substr($charId, 0, 8) . substr($charId, 8, 4)
            . substr($charId, 12, 4)
            . substr($charId, 16, 4)
            . substr($charId, 20, 12);
    }

}