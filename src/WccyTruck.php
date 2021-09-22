<?php
/**
 * Author:Shaun·Yang
 * Date:2021/9/7
 * Time:上午9:34
 * Description:
 */

namespace wlhywebservice;

class WccyTruck extends MsgBase implements MsgInterface
{
    public array $body = [
        //车牌号
        'VehicleNumber' => '',
        //车牌颜色代码
        'VehiclePlateColorCode' => '',
        //车辆类型代码
        'VehicleType' => '',
        //所有人
        'Owner' => '',
        //使用性质
        'UseCharacter' => '',
        //车辆识别代码
        'VIN' => '',
        //发证机关
        'IssuingOrganizations' => '',
        //注册日期
        'RegisterDate' => '',
        //发证日期
        'IssueDate' => '',
        //能源类型
        'VehicleEnergyType' => '',
        //核定载质量 吨  2位小数
        'VehicleTonnage' => '',
        //总质量 吨 2位小数
        'GrossMass' => '',
        //道路运输证号
        'RoadTransportCertificateNumber' => '',
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
        return 'LOGINK_CN_CREDIT_VEHICLE';
    }
}