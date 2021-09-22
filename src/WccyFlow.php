<?php
/**
 * Author:Shaun·Yang
 * Date:2021/9/7
 * Time:上午9:34
 * Description:
 */

namespace wlhywebservice;

class WccyFlow extends MsgBase implements MsgInterface
{
    public array $body = [
        //单证号
        'DocumentNumber' => '',
        //实际承运人名称
        'Carrier' => '',
        //实际承运人统一社会信用代码或证件号码
        'ActualCarrierID' => '',
        //车辆牌照号
        'VehicleNumber' => '',
        //车牌颜色代码
        'VehiclePlateColorCode' => '',
        //运单列表
        'ShippingNoteList' => [],
        //财务列表
        'Financiallist' => [],
        //备注
        'Remark' => '',
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

    public function setHeader($key, $val)
    {
        $this->header[$key] = $val;
    }

    public function setBody($key, $val)
    {
        $this->body[$key] = $val;
    }

    /**
     * 添加运单
     * @param $orderCode
     * @param $orderMoney
     */
    public function setOrder($orderCode, $orderMoney)
    {
        $this->body['ShippingNoteList'][] = [
            'ShippingNoteNumber' => $orderCode,
            'SerialNumber' => '0000',
            'TotalMonetaryAmount' => number_format($orderMoney, 3, '.', '')
        ];
    }

    /**
     * 添加流水
     * @param string $payTypeCode 支付方式
     * @param string $name 账户名称
     * @param string $accountNum 账号
     * @param string $bankCode 银行代码
     * @param string $transactionNo 交易流水号
     * @param int|float|mixed $money 金额
     * @param string $time 时间
     */
    public function setFlow(string $payTypeCode, string $name, string $accountNum, string $bankCode, string $transactionNo, $money, string $time)
    {
        $this->body['Financiallist'][] = [
            'PaymentMeansCode' => $payTypeCode,
            'Recipient' => $name,
            'ReceiptAccount' => $accountNum,
            'BankCode' => $bankCode,
            'SequenceCode' => $transactionNo,
            'MonetaryAmount' => number_format($money, 3, '.', ''),
            'DateTime' => $time
        ];
    }

    public function getMsgType(): string
    {
        return 'LOGINK_CN_FREIGHTCHARGES';
    }
}