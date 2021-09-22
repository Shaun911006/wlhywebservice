<?php
/**
 * Author:Shaun·Yang
 * Date:2021/9/7
 * Time:上午9:34
 * Description:
 */

namespace wlhywebservice;

class WccyDriver extends MsgBase implements MsgInterface
{
    public array $body = [
        //姓名
        'DriverName' => '',
        //身份证号
        'DrivingLicense' => '',
        //准驾车型
        'VehicleClass' => '',
        //发证机关
        'IssuingOrganizations' => '',
        //驾驶证有效期自
        'ValidPeriodFrom' => '',
        //驾驶证有效期至
        'ValidPeriodTo' => '',
        //从业资格证号
        'QualificationCertificate' => '',
        //手机号码
        'Telephone' => '',
    ];

    public function __construct()
    {
        $this->header['MessageReferenceNumber'] = self::getGuid();
        $this->header['MessageSendingDateTime'] = date('YmdHis');
    }

    public function getMsg(): string
    {
        return $this->arrayToXml(['Header' => $this->header, 'Body' => $this->body]);
    }

    public function setHeader($key,$val)
    {
        $this->header[$key] = $val;
    }

    public function setBody($key,$val)
    {
        $this->body[$key] = $val;
    }

    public function getMsgType(): string
    {
        return 'LOGINK_CN_CREDIT_PERSON';
    }
}