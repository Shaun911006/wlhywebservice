<?php
/**
 * Author:Shaun·Yang
 * Date:2020/6/15
 * Time:下午5:38
 * Description:
 */
require '../vendor/autoload.php';

use wlhywebservice\WccyClient;
////司机
//$msgObj = new \wlhywebservice\WccyDriver();
//$msgObj->setBody('DriverName', '温*龙');
//$msgObj->setBody('DrivingLicense', '4213************75');
//$msgObj->setBody('VehicleClass', '	B2');
//$msgObj->setBody('IssuingOrganizations', '******公安局交通管理局');
//$msgObj->setBody('ValidPeriodFrom', '20****23');
//$msgObj->setBody('ValidPeriodTo', '20****23');
//$msgObj->setBody('QualificationCertificate', '4213************75');
//$msgObj->setBody('Telephone', '132******76');
//车辆
//$msgObj = new \wlhywebservice\WccyTruck();
//$msgObj->setBody('VehicleNumber', '冀E*****');
//$msgObj->setBody('VehiclePlateColorCode', '2');
//$msgObj->setBody('VehicleType', 'Q11');
//$msgObj->setBody('Owner', '*******有限公司');
//$msgObj->setBody('UseCharacter', '货运');
//$msgObj->setBody('VIN', '');
//$msgObj->setBody('IssuingOrganizations', '******公安局交通警察大队');
//$msgObj->setBody('RegisterDate', '20200708');
//$msgObj->setBody('IssueDate', '20200708');
//$msgObj->setBody('VehicleEnergyType', 'B');
//$msgObj->setBody('VehicleTonnage', round(40000/1000,2));  //千克转吨保留两位
//$msgObj->setBody('GrossMass', round(48200/1000,2));   //千克转吨保留两位
//$msgObj->setBody('RoadTransportCertificateNumber', '130******222');
////订单
//$msgObj = new \wlhywebservice\WccyOrder();
//$msgObj->setBody('OriginalDocumentNumber', 'HYB********0025');
//$msgObj->setBody('ShippingNoteNumber', 'N202**********99261');
//$msgObj->setBody('Carrier', '新疆**********有限公司');
//$msgObj->setBody('UnifiedSocialCreditIdentifier', '9165**********Q2F0P');
//$msgObj->setBody('PermitNumber', '伊**********1293');
//$msgObj->setBody('ConsignmentDateTime', '2021********5206');
//$msgObj->setBody('BusinessTypeCode', '1002996');
//$msgObj->setBody('DespatchActualDateTime', '202******938');
//$msgObj->setBody('GoodsReceiptDateTime', '202******1945');
//$msgObj->setBody('ConsignorInfo', [
//    //托运人名称
//    'Consignor' => '***********',
//    //托运人统一社会信用代码或个人证件号
//    'ConsignorID' => '****************',
//    //装货地址
//    'PlaceOfLoading' => '广**************',
//    //装货地点的国家行政区划代码或国别代码
//    'CountrySubdivisionCode' => '******',
//]);
//$msgObj->setBody('ConsigneeInfo', [
//    //收货方名称
//    'Consignee' => '测试',
//    //收货方统一社会信用代码或个人证件号码
//    'ConsigneeID' => '',
//    //收货地址
//    'GoodsReceiptPlace' => '**********',
//    //收货地点的国家行政区划代码或国别代码
//    'CountrySubdivisionCode' => '******',
//]);
//$msgObj->setBody('TotalMonetaryAmount', 100.09);
//$msgObj->setBody('VehicleInfo', [
//    //车牌号
//    'VehicleNumber' => '*******',
//    //车牌颜色代码
//    'VehiclePlateColorCode' => '2',
//]);
//$msgObj->setBody('ActualCarrierInfo', [
//    //实际承运人名称
//    'ActualCarrierName' => '***',
//    //实际承运人道路运输经营许可证号
//    'ActualCarrierBusinessLicense' => '*************',
//    //实际承运人统一社会信用代码或证件号码
//    'ActualCarrierID' => '****************',
//]);
//$msgObj->setDriver('************8','*****************');
//$msgObj->setGoodsInfo('建材','1700',20);

//资金
$msgObj = new \wlhywebservice\WccyFlow();

$msgObj->setBody('DocumentNumber','************');
$msgObj->setBody('Carrier','***');
$msgObj->setBody('ActualCarrierID','************');
$msgObj->setBody('VehicleNumber','****');
$msgObj->setBody('VehiclePlateColorCode','*****');
$msgObj->setOrder('***********','100.09');
$msgObj->setFlow('41','******','***********','*****','**************************','100','***********8');

$xml = $msgObj->getMsg();

$wccy = new WccyClient([
    'userId' => '****',
    'password' => '********',
    'resource' => '******************************',
    'toUserId' => '********',
    'msgUrl' => 'https://exb.logink.cn/cuteinfo/services/ExchangeTransportService?wsdl',
]);

try {
    $token = $wccy->getToken();
    $res   = $wccy->send($xml, $msgObj->getMsgType(), $token);
    var_dump($res);
} catch (\wlhywebservice\WlhyException $e) {
    echo 'code:' . $e->getCode();
    echo PHP_EOL;
    echo 'msg:' . $e->getMessage();
}