<?php
/**
 * Author:Shaun·Yang
 * Date:2020/6/12
 * Time:下午5:30
 * Description:
 */

namespace wlhywebservice;

use SoapClient;
use SoapHeader;
use SoapVar;

class WccyClient
{
    private string $userId = ''; //用户交换码
    private string $password = ''; //密码
    private string $resource = ''; //资源ID
    private string $toUserId = ''; //数据接受方交换码
    private string $msgUrl = '';  //报文地址

    public function __construct($config = [])
    {
        $this->userId   = $config['userId'] ?? '';
        $this->password = $config['password'] ?? '';
        $this->resource = $config['resource'] ?? '';
        $this->toUserId = $config['toUserId'] ?? '';
        $this->msgUrl   = $config['msgUrl'] ?? '';
    }

    /**
     * 获取token
     * @return mixed|string
     * token的有效期目前为2个小时，只有该token在2小时未被使用才会失效，如果持续使用，
     * 会自动延期，不会失效，所有不需要每次调用都申请token，只有在收到失效的异常代码或
     * 网络异常时，再进行调用即可，强烈建议采用共享变量使用，便于服务接口调用的高效性；
     */
    public function getToken()
    {
        $info = HttpTools::get_curl('https://ssl.logink.cn/authapi/rest/auth/apply?userid=' . $this->userId . '&password=' . $this->password . '&resource=' . $this->resource);
        $info = json_decode($info, true);
        if (isset($info['resultCode']) && $info['resultCode'] == '100000') {
            return $info['token'];
        }
        return '';
    }

    /**
     * 发送报文
     * @param string $xml xml格式的报文体
     * @param string $type 报文类型
     * @param string $token 令牌
     * @return bool
     * @throws WlhyException
     */
    public function send(string $xml, string $type, string $token): bool
    {
        @header("Content-Type: text/html; charset=utf-8");
        try {
            $client = new SoapClient($this->msgUrl, [
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(
                    [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        ]
                    ]
                )
            ]);

            //获取报文体
            $data = $this->getSendData($xml, $this->toUserId, $type);
            //封装报文头
            $header = $this->getSendHeader($this->userId, $token);

            $client->__setSoapHeaders($header);

            $res = $client->__soapCall('send', $data);

            if (!$res) {
                throw new WlhyException('未获取响应报文',9999);
            }

            if (!$res->SendResult) {
                $code = isset($res->GenericFault)?($res->GenericFault->Code ?? 9999):9999;
                $msg = isset($res->GenericFault)?($res->GenericFault->ErrorMessage ?? '未知异常'):'未知异常';
                //失败了 看失败的原因是什么
                throw new WlhyException($msg,$code);
            }

            return true;
        } catch (\SoapFault $e) {
            throw new WlhyException('上报异常',9999);
        }
    }

    /**
     * 组装报文头
     * @param $userId
     * @param $token
     * @return SoapHeader
     */
    public function getSendHeader($userId, $token): SOAPHeader
    {
        $ns          = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
        $userNameVar = new SoapVar($userId, XSD_STRING, 'wsse', $ns, null, $ns);
        $tokenVar    = new SoapVar('ticket:' . $token, XSD_STRING, null, $ns, null, $ns);
        $tokenObj    = new cTokenInfo($userNameVar, $tokenVar);
        $securityVar = new SoapVar($tokenObj, SOAP_ENC_OBJECT, null, $ns, 'UsernameToken', $ns);
        $utObj       = new cUsernameToken($securityVar);
        $utVar       = new SoapVar($utObj, SOAP_ENC_OBJECT, null, $ns, 'UsernameToken', $ns);
        return new SOAPHeader($ns, 'Security', $utVar, false);
    }

    /**
     * 组装报文体
     * @param $xml
     * @param string $toUserId
     * @param string $type
     * @return array[]
     */
    private function getSendData($xml, string $toUserId = '', string $type = ''): array
    {
        $xml     = base64_encode($xml);
        $eventId = MsgBase::getGuid();
        return [
            'SendRequest' => [
                'ToAddress' => $toUserId,
                'ExchangeEvent' => [
                    'EventID' => $eventId,
                    'ActionType' => $type,
                    'ExchangeDataPackage' => [
                        'packageID' => $eventId,
                        'transactionID' => $eventId, // string
                        'createTime' => date('Y-m-d'), // datetime
                        'title' => $type,
                        'ExchangeDataPackageUnit' => [
                            'groupID' => MsgBase::getGuid(),
                            'groupSize' => '1',
                            'sequenceInGroup' => '1',
                            'unitID' => MsgBase::getGuid(),
                            'source' => 'source',
                            'createDate' => date('Y-m-d'),//dateTime
                            'DataFile' => [
                                'dataFileID' => '2',//NCName
                                'fileName' => 'file.xml',
                                'dataFileFormat' => 'xml',
                                'Base64EncodedData' => $xml,//base64Binary
                                'AttachmentData' => '',//base64Binary
                            ],
                        ],
                    ],

                ],
            ]
        ];
    }
}
