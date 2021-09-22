<?php
/**
 * Author:Shaun·Yang
 * Date:2021/9/7
 * Time:上午9:34
 * Description:
 */

namespace wlhywebservice;

class WccyOrder extends MsgBase implements MsgInterface
{
    public $body = [
        //必填，上游企业委托运输单号。
        'OriginalDocumentNumber' => '',
        //必填，运单号
        'ShippingNoteNumber' => '',
        //运输总车辆数
        'VehicleAmount' => 1,
        //分段分单号
        'SerialNumber' => '0000',
        //运输组织类型代码
        'TransportTypeCode' => 1,
        //网络货运经营者名称
        'Carrier' => '',
        //统一社会信用代码
        'UnifiedSocialCreditIdentifier' => '',
        //网络货运经营者的道路运输经营许可证编号
        'PermitNumber' => '',
        //网络货运经营者信息系统正式成交生成运单的日期时间。YYYYMMDDhhmmss
        'ConsignmentDateTime' => '',
        //业务类型代码
        'BusinessTypeCode' => '',
        //发货日期时间
        'DespatchActualDateTime' => '',
        //收货日期时间
        'GoodsReceiptDateTime' => '',
        //托运人信息
        'ConsignorInfo' => [
            //托运人名称
            'Consignor' => '',
            //托运人统一社会信用代码或个人证件号
            'ConsignorID' => '',
            //装货地址
            'PlaceOfLoading' => '',
            //装货地点的国家行政区划代码或国别代码
            'CountrySubdivisionCode' => '',
        ],
        //收货方信息
        'ConsigneeInfo' => [
            //收货方名称
            'Consignee' => '',
            //收货方统一社会信用代码或个人证件号码
            'ConsigneeID' => '',
            //收货地址
            'GoodsReceiptPlace' => '',
            //收货地点的国家行政区划代码或国别代码
            'CountrySubdivisionCode' => '',
        ],
        //运费金额
        'TotalMonetaryAmount' => '',
        //车辆信息
        'VehicleInfo' => [
            //车牌号
            'VehicleNumber' => '',
            //车牌颜色代码
            'VehiclePlateColorCode' => '',
            //驾驶员
            'Driver' => [],
            //货物信息
            'GoodsInfo' => []
        ],
        //实际承运人信息
        'ActualCarrierInfo' => [
            //实际承运人名称
            'ActualCarrierName' => '',
            //实际承运人道路运输经营许可证号
            'ActualCarrierBusinessLicense' => '',
            //实际承运人统一社会信用代码或证件号码
            'ActualCarrierID' => '',
        ],
        //保险信息
        'InsuranceInformation' => [
            //保险单号
            'PolicyNumber' => 'none',
            //保险公司代码
            'InsuranceCompanyCode' => 'none'
        ]
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

    /**
     * 添加司机
     * @param $name
     * @param $driverLicense
     */
    public function setDriver($name, $driverLicense)
    {
        $this->body['VehicleInfo']['Driver'][] = [
            'DriverName' => $name,
            'DrivingLicense' => $driverLicense
        ];
    }

    /**
     * 添加货物信息
     * @param $name
     * @param $cateCode
     * @param int|float $weight 货物重量 单位吨 保留三位小数
     */
    public function setGoodsInfo($name, $cateCode, $weight)
    {
        $this->body['VehicleInfo']['GoodsInfo'][] = [
            'DescriptionOfGoods' => $name,
            'CargoTypeClassificationCode' => $cateCode,
            'GoodsItemGrossWeight' => number_format($weight,3,'.',''),
        ];
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
        return 'LOGINK_CN_FREIGHTBROKER_WAYBILL';
    }
}