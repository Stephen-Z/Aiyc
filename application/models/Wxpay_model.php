<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wxpay_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
}


class WxPayConfig
{
    //=======【基本信息设置】=====================================
    //
    /**
     * TODO: 修改这里配置为您自己申请的商户信息
     * 微信公众号信息配置
     *
     * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
     *
     * MCHID：商户号（必须配置，开户邮件中可查看）
     *
     * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
     * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
     *
     * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
     * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN
     * @var string
     */
    const APPID = 'wxa517ab08104c3746';
    const MCHID = '1396199502';
    const KEY = '369161556dad8a73392ffc629f4a2430';
    const APPSECRET = '369161556dad8a73392ffc629f4a2429';

    const NOTIFY_URL = 'http://omg.bukson.com/wx/notify';

    //=======【证书路径设置】=====================================
    /**
     * TODO：设置商户证书路径
     * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
     * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
     * @var path
     */
    const SSLCERT_PATH = '../cert/apiclient_cert.pem';
    const SSLKEY_PATH = '../cert/apiclient_key.pem';

    //=======【curl代理设置】===================================
    /**
     * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
     * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
     * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
     * @var unknown_type
     */
    const CURL_PROXY_HOST = "0.0.0.0";//"10.152.18.220";
    const CURL_PROXY_PORT = 0;//8080;

    //=======【上报信息配置】===================================
    /**
     * TODO：接口调用上报等级，默认紧错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】，
     * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少
     * 开启错误上报。
     * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
     * @var int
     */
    const REPORT_LEVENL = 1;
}


class WxPayDataBase
{
    protected $values = array();

    /**
     * 设置签名，详见签名生成算法
     * @param string $value
     **/
    public function SetSign()
    {
        $sign = $this->MakeSign();
        $this->values['sign'] = $sign;
        return $sign;
    }

    /**
     * 获取签名，详见签名生成算法的值
     * @return 值
     **/
    public function GetSign()
    {
        return $this->values['sign'];
    }

    /**
     * 判断签名，详见签名生成算法是否存在
     * @return true 或 false
     **/
    public function IsSignSet()
    {
        return array_key_exists('sign', $this->values);
    }

    /**
     * 输出xml字符
     * @throws WxPayException
     **/
    public function ToXml()
    {
        if(!is_array($this->values)
            || count($this->values) <= 0)
        {
            throw new WxPayException("数组数据异常！");
        }

        $xml = "<xml>";
        foreach ($this->values as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function FromXml($xml)
    {
        if(!$xml){
            throw new WxPayException("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->values;
    }

    /**
     * 格式化参数格式化成url参数
     */
    public function ToUrlParams()
    {
        $buff = "";
        foreach ($this->values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    public function MakeSign()
    {
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".WxPayConfig::KEY;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 获取设置的值
     */
    public function GetValues()
    {
        return $this->values;
    }
}


class NativePay
{
    /**
     *
     * 生成扫描支付URL,模式一
     * @param BizPayUrlInput $bizUrlInfo
     */
    public function GetPrePayUrl($productId)
    {
        $biz = new WxPayBizPayUrl();
        $biz->SetProduct_id($productId);
        $values = WxpayApi::bizpayurl($biz);
        $url = "weixin://wxpay/bizpayurl?" . $this->ToUrlParams($values);
        return $url;
    }

    /**
     *
     * 参数数组转换为url参数
     * @param array $urlObj
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            $buff .= $k . "=" . $v . "&";
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     *
     * 生成直接支付url，支付url有效期为2小时,模式二
     * @param UnifiedOrderInput $input
     */
    public function GetPayUrl($input)
    {
        if($input->GetTrade_type() == "NATIVE")
        {
            $result = WxPayApi::unifiedOrder($input);
            return $result;
        }
    }


}

class WxPayBizPayUrl extends WxPayDataBase
{
    /**
     * 设置微信分配的公众账号ID
     * @param string $value
     **/
    public function SetAppid($value)
    {
        $this->values['appid'] = $value;
    }
    /**
     * 获取微信分配的公众账号ID的值
     * @return 值
     **/
    public function GetAppid()
    {
        return $this->values['appid'];
    }
    /**
     * 判断微信分配的公众账号ID是否存在
     * @return true 或 false
     **/
    public function IsAppidSet()
    {
        return array_key_exists('appid', $this->values);
    }


    /**
     * 设置微信支付分配的商户号
     * @param string $value
     **/
    public function SetMch_id($value)
    {
        $this->values['mch_id'] = $value;
    }
    /**
     * 获取微信支付分配的商户号的值
     * @return 值
     **/
    public function GetMch_id()
    {
        return $this->values['mch_id'];
    }
    /**
     * 判断微信支付分配的商户号是否存在
     * @return true 或 false
     **/
    public function IsMch_idSet()
    {
        return array_key_exists('mch_id', $this->values);
    }

    /**
     * 设置支付时间戳
     * @param string $value
     **/
    public function SetTime_stamp($value)
    {
        $this->values['time_stamp'] = $value;
    }
    /**
     * 获取支付时间戳的值
     * @return 值
     **/
    public function GetTime_stamp()
    {
        return $this->values['time_stamp'];
    }
    /**
     * 判断支付时间戳是否存在
     * @return true 或 false
     **/
    public function IsTime_stampSet()
    {
        return array_key_exists('time_stamp', $this->values);
    }

    /**
     * 设置随机字符串
     * @param string $value
     **/
    public function SetNonce_str($value)
    {
        $this->values['nonce_str'] = $value;
    }
    /**
     * 获取随机字符串的值
     * @return 值
     **/
    public function GetNonce_str()
    {
        return $this->values['nonce_str'];
    }
    /**
     * 判断随机字符串是否存在
     * @return true 或 false
     **/
    public function IsNonce_strSet()
    {
        return array_key_exists('nonce_str', $this->values);
    }

    /**
     * 设置商品ID
     * @param string $value
     **/
    public function SetProduct_id($value)
    {
        $this->values['product_id'] = $value;
    }
    /**
     * 获取商品ID的值
     * @return 值
     **/
    public function GetProduct_id()
    {
        return $this->values['product_id'];
    }
    /**
     * 判断商品ID是否存在
     * @return true 或 false
     **/
    public function IsProduct_idSet()
    {
        return array_key_exists('product_id', $this->values);
    }
}

class WxPayApi
{
    /**
     *
     * 统一下单，WxPayUnifiedOrder中out_trade_no、body、total_fee、trade_type必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayUnifiedOrder $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function unifiedOrder($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        //检测必填参数
        if(!$inputObj->IsOut_trade_noSet()) {
            throw new WxPayException("缺少统一支付接口必填参数out_trade_no！");
        }else if(!$inputObj->IsBodySet()){
            throw new WxPayException("缺少统一支付接口必填参数body！");
        }else if(!$inputObj->IsTotal_feeSet()) {
            throw new WxPayException("缺少统一支付接口必填参数total_fee！");
        }else if(!$inputObj->IsTrade_typeSet()) {
            throw new WxPayException("缺少统一支付接口必填参数trade_type！");
        }

        //关联参数
        if($inputObj->GetTrade_type() == "JSAPI" && !$inputObj->IsOpenidSet()){
            throw new WxPayException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！");
        }
        if($inputObj->GetTrade_type() == "NATIVE" && !$inputObj->IsProduct_idSet()){
            throw new WxPayException("统一支付接口中，缺少必填参数product_id！trade_type为JSAPI时，product_id为必填参数！");
        }

        //异步通知url未设置，则使用配置文件中的url
        if(!$inputObj->IsNotify_urlSet()){
            $inputObj->SetNotify_url(WxPayConfig::NOTIFY_URL);//异步通知url
        }

        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetSpbill_create_ip($_SERVER['REMOTE_ADDR']);//终端ip
        //$inputObj->SetSpbill_create_ip("1.1.1.1");
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        //签名
        $inputObj->SetSign();
        $xml = $inputObj->ToXml();
        
        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     *
     * 查询订单，WxPayOrderQuery中out_trade_no、transaction_id至少填一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayOrderQuery $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function orderQuery($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/orderquery";
        //检测必填参数
        if(!$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet()) {
            throw new WxPayException("订单查询接口中，out_trade_no、transaction_id至少填一个！");
        }
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign();//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     *
     * 关闭订单，WxPayCloseOrder中out_trade_no必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayCloseOrder $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function closeOrder($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/closeorder";
        //检测必填参数
        if(!$inputObj->IsOut_trade_noSet()) {
            throw new WxPayException("订单查询接口中，out_trade_no必填！");
        }
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign();//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     *
     * 申请退款，WxPayRefund中out_trade_no、transaction_id至少填一个且
     * out_refund_no、total_fee、refund_fee、op_user_id为必填参数
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayRefund $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function refund($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
        //检测必填参数
        if(!$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet()) {
            throw new WxPayException("退款申请接口中，out_trade_no、transaction_id至少填一个！");
        }else if(!$inputObj->IsOut_refund_noSet()){
            throw new WxPayException("退款申请接口中，缺少必填参数out_refund_no！");
        }else if(!$inputObj->IsTotal_feeSet()){
            throw new WxPayException("退款申请接口中，缺少必填参数total_fee！");
        }else if(!$inputObj->IsRefund_feeSet()){
            throw new WxPayException("退款申请接口中，缺少必填参数refund_fee！");
        }else if(!$inputObj->IsOp_user_idSet()){
            throw new WxPayException("退款申请接口中，缺少必填参数op_user_id！");
        }
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign();//签名
        $xml = $inputObj->ToXml();
        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, true, $timeOut);
        $result = WxPayResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     *
     * 查询退款
     * 提交退款申请后，通过调用该接口查询退款状态。退款有一定延时，
     * 用零钱支付的退款20分钟内到账，银行卡支付的退款3个工作日后重新查询退款状态。
     * WxPayRefundQuery中out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayRefundQuery $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function refundQuery($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/refundquery";
        //检测必填参数
        if(!$inputObj->IsOut_refund_noSet() &&
            !$inputObj->IsOut_trade_noSet() &&
            !$inputObj->IsTransaction_idSet() &&
            !$inputObj->IsRefund_idSet()) {
            throw new WxPayException("退款查询接口中，out_refund_no、out_trade_no、transaction_id、refund_id四个参数必填一个！");
        }
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign();//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     * 下载对账单，WxPayDownloadBill中bill_date为必填参数
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayDownloadBill $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function downloadBill($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/pay/downloadbill";
        //检测必填参数
        if(!$inputObj->IsBill_dateSet()) {
            throw new WxPayException("对账单接口中，缺少必填参数bill_date！");
        }
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign();//签名
        $xml = $inputObj->ToXml();

        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        if(substr($response, 0 , 5) == "<xml>"){
            return "";
        }
        return $response;
    }

    /**
     * 提交被扫支付API
     * 收银员使用扫码设备读取微信用户刷卡授权码以后，二维码或条码信息传送至商户收银台，
     * 由商户收银台或者商户后台调用该接口发起支付。
     * WxPayWxPayMicroPay中body、out_trade_no、total_fee、auth_code参数必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayWxPayMicroPay $inputObj
     * @param int $timeOut
     */
    public static function micropay($inputObj, $timeOut = 10)
    {
        $url = "https://api.mch.weixin.qq.com/pay/micropay";
        //检测必填参数
        if(!$inputObj->IsBodySet()) {
            throw new WxPayException("提交被扫支付API接口中，缺少必填参数body！");
        } else if(!$inputObj->IsOut_trade_noSet()) {
            throw new WxPayException("提交被扫支付API接口中，缺少必填参数out_trade_no！");
        } else if(!$inputObj->IsTotal_feeSet()) {
            throw new WxPayException("提交被扫支付API接口中，缺少必填参数total_fee！");
        } else if(!$inputObj->IsAuth_codeSet()) {
            throw new WxPayException("提交被扫支付API接口中，缺少必填参数auth_code！");
        }

        $inputObj->SetSpbill_create_ip($_SERVER['REMOTE_ADDR']);//终端ip
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign();//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     *
     * 撤销订单API接口，WxPayReverse中参数out_trade_no和transaction_id必须填写一个
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayReverse $inputObj
     * @param int $timeOut
     * @throws WxPayException
     */
    public static function reverse($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/secapi/pay/reverse";
        //检测必填参数
        if(!$inputObj->IsOut_trade_noSet() && !$inputObj->IsTransaction_idSet()) {
            throw new WxPayException("撤销订单API接口中，参数out_trade_no和transaction_id必须填写一个！");
        }

        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign();//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, true, $timeOut);
        $result = WxPayResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     *
     * 测速上报，该方法内部封装在report中，使用时请注意异常流程
     * WxPayReport中interface_url、return_code、result_code、user_ip、execute_time_必填
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayReport $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function report($inputObj, $timeOut = 1)
    {
        $url = "https://api.mch.weixin.qq.com/payitil/report";
        //检测必填参数
        if(!$inputObj->IsInterface_urlSet()) {
            throw new WxPayException("接口URL，缺少必填参数interface_url！");
        } if(!$inputObj->IsReturn_codeSet()) {
        throw new WxPayException("返回状态码，缺少必填参数return_code！");
    } if(!$inputObj->IsResult_codeSet()) {
        throw new WxPayException("业务结果，缺少必填参数result_code！");
    } if(!$inputObj->IsUser_ipSet()) {
        throw new WxPayException("访问接口IP，缺少必填参数user_ip！");
    } if(!$inputObj->IsExecute_time_Set()) {
        throw new WxPayException("接口耗时，缺少必填参数execute_time_！");
    }
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetUser_ip($_SERVER['REMOTE_ADDR']);//终端ip
        $inputObj->SetTime(date("YmdHis"));//商户上报时间
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign();//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        return $response;
    }

    /**
     *
     * 生成二维码规则,模式一生成支付二维码
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayBizPayUrl $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function bizpayurl($inputObj, $timeOut = 6)
    {
        if(!$inputObj->IsProduct_idSet()){
            throw new WxPayException("生成二维码，缺少必填参数product_id！");
        }

        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetTime_stamp(time());//时间戳
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign();//签名

        return $inputObj->GetValues();
    }

    /**
     *
     * 转换短链接
     * 该接口主要用于扫码原生支付模式一中的二维码链接转成短链接(weixin://wxpay/s/XXXXXX)，
     * 减小二维码数据量，提升扫描速度和精确度。
     * appid、mchid、spbill_create_ip、nonce_str不需要填入
     * @param WxPayShortUrl $inputObj
     * @param int $timeOut
     * @throws WxPayException
     * @return 成功时返回，其他抛异常
     */
    public static function shorturl($inputObj, $timeOut = 6)
    {
        $url = "https://api.mch.weixin.qq.com/tools/shorturl";
        //检测必填参数
        if(!$inputObj->IsLong_urlSet()) {
            throw new WxPayException("需要转换的URL，签名用原串，传输需URL encode！");
        }
        $inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
        $inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
        $inputObj->SetNonce_str(self::getNonceStr());//随机字符串

        $inputObj->SetSign();//签名
        $xml = $inputObj->ToXml();

        $startTimeStamp = self::getMillisecond();//请求开始时间
        $response = self::postXmlCurl($xml, $url, false, $timeOut);
        $result = WxPayResults::Init($response);
        self::reportCostTime($url, $startTimeStamp, $result);//上报请求花费时间

        return $result;
    }

    /**
     *
     * 支付结果通用通知
     * @param function $callback
     * 直接回调函数使用方法: notify(you_function);
     * 回调类成员函数方法:notify(array($this, you_function));
     * $callback  原型为：function function_name($data){}
     */
    public static function notify($callback, &$msg)
    {
        //获取通知的数据
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        //如果返回成功则验证签名
        try {
            $result = WxPayResults::Init($xml);
        } catch (WxPayException $e){
            $msg = $e->errorMessage();
            return false;
        }

        return call_user_func($callback, $result);
    }

    /**
     *
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    public static function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    /**
     * 直接输出xml
     * @param string $xml
     */
    public static function replyNotify($xml)
    {
        echo $xml;
    }

    /**
     *
     * 上报数据， 上报的时候将屏蔽所有异常流程
     * @param string $usrl
     * @param int $startTimeStamp
     * @param array $data
     */
    private static function reportCostTime($url, $startTimeStamp, $data)
    {
        //如果不需要上报数据
        if(WxPayConfig::REPORT_LEVENL == 0){
            return;
        }
        //如果仅失败上报
        if(WxPayConfig::REPORT_LEVENL == 1 &&
            array_key_exists("return_code", $data) &&
            $data["return_code"] == "SUCCESS" &&
            array_key_exists("result_code", $data) &&
            $data["result_code"] == "SUCCESS")
        {
            return;
        }

        //上报逻辑
        $endTimeStamp = self::getMillisecond();
        $objInput = new WxPayReport();
        $objInput->SetInterface_url($url);
        $objInput->SetExecute_time_($endTimeStamp - $startTimeStamp);
        //返回状态码
        if(array_key_exists("return_code", $data)){
            $objInput->SetReturn_code($data["return_code"]);
        }
        //返回信息
        if(array_key_exists("return_msg", $data)){
            $objInput->SetReturn_msg($data["return_msg"]);
        }
        //业务结果
        if(array_key_exists("result_code", $data)){
            $objInput->SetResult_code($data["result_code"]);
        }
        //错误代码
        if(array_key_exists("err_code", $data)){
            $objInput->SetErr_code($data["err_code"]);
        }
        //错误代码描述
        if(array_key_exists("err_code_des", $data)){
            $objInput->SetErr_code_des($data["err_code_des"]);
        }
        //商户订单号
        if(array_key_exists("out_trade_no", $data)){
            $objInput->SetOut_trade_no($data["out_trade_no"]);
        }
        //设备号
        if(array_key_exists("device_info", $data)){
            $objInput->SetDevice_info($data["device_info"]);
        }

        try{
            self::report($objInput);
        } catch (WxPayException $e){
            //不做任何处理
        }
    }

    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    private static function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //如果有配置代理这里就设置代理
        if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0"
            && WxPayConfig::CURL_PROXY_PORT != 0){
            curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
            curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
        }
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error");
        }
    }

    /**
     * 获取毫秒级别的时间戳
     */
    private static function getMillisecond()
    {
        //获取毫秒的时间戳
        $time = explode ( " ", microtime () );
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode( ".", $time );
        $time = $time2[0];
        return $time;
    }
}

/**
 *
 * 统一下单输入对象
 * @author widyhu
 *
 */
class WxPayUnifiedOrder extends WxPayDataBase
{
    /**
     * 设置微信分配的公众账号ID
     * @param string $value
     **/
    public function SetAppid($value)
    {
        $this->values['appid'] = $value;
    }
    /**
     * 获取微信分配的公众账号ID的值
     * @return 值
     **/
    public function GetAppid()
    {
        return $this->values['appid'];
    }
    /**
     * 判断微信分配的公众账号ID是否存在
     * @return true 或 false
     **/
    public function IsAppidSet()
    {
        return array_key_exists('appid', $this->values);
    }


    /**
     * 设置微信支付分配的商户号
     * @param string $value
     **/
    public function SetMch_id($value)
    {
        $this->values['mch_id'] = $value;
    }
    /**
     * 获取微信支付分配的商户号的值
     * @return 值
     **/
    public function GetMch_id()
    {
        return $this->values['mch_id'];
    }
    /**
     * 判断微信支付分配的商户号是否存在
     * @return true 或 false
     **/
    public function IsMch_idSet()
    {
        return array_key_exists('mch_id', $this->values);
    }


    /**
     * 设置微信支付分配的终端设备号，商户自定义
     * @param string $value
     **/
    public function SetDevice_info($value)
    {
        $this->values['device_info'] = $value;
    }
    /**
     * 获取微信支付分配的终端设备号，商户自定义的值
     * @return 值
     **/
    public function GetDevice_info()
    {
        return $this->values['device_info'];
    }
    /**
     * 判断微信支付分配的终端设备号，商户自定义是否存在
     * @return true 或 false
     **/
    public function IsDevice_infoSet()
    {
        return array_key_exists('device_info', $this->values);
    }


    /**
     * 设置随机字符串，不长于32位。推荐随机数生成算法
     * @param string $value
     **/
    public function SetNonce_str($value)
    {
        $this->values['nonce_str'] = $value;
    }
    /**
     * 获取随机字符串，不长于32位。推荐随机数生成算法的值
     * @return 值
     **/
    public function GetNonce_str()
    {
        return $this->values['nonce_str'];
    }
    /**
     * 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
     * @return true 或 false
     **/
    public function IsNonce_strSet()
    {
        return array_key_exists('nonce_str', $this->values);
    }

    /**
     * 设置商品或支付单简要描述
     * @param string $value
     **/
    public function SetBody($value)
    {
        $this->values['body'] = $value;
    }
    /**
     * 获取商品或支付单简要描述的值
     * @return 值
     **/
    public function GetBody()
    {
        return $this->values['body'];
    }
    /**
     * 判断商品或支付单简要描述是否存在
     * @return true 或 false
     **/
    public function IsBodySet()
    {
        return array_key_exists('body', $this->values);
    }


    /**
     * 设置商品名称明细列表
     * @param string $value
     **/
    public function SetDetail($value)
    {
        $this->values['detail'] = $value;
    }
    /**
     * 获取商品名称明细列表的值
     * @return 值
     **/
    public function GetDetail()
    {
        return $this->values['detail'];
    }
    /**
     * 判断商品名称明细列表是否存在
     * @return true 或 false
     **/
    public function IsDetailSet()
    {
        return array_key_exists('detail', $this->values);
    }


    /**
     * 设置附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
     * @param string $value
     **/
    public function SetAttach($value)
    {
        $this->values['attach'] = $value;
    }
    /**
     * 获取附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据的值
     * @return 值
     **/
    public function GetAttach()
    {
        return $this->values['attach'];
    }
    /**
     * 判断附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据是否存在
     * @return true 或 false
     **/
    public function IsAttachSet()
    {
        return array_key_exists('attach', $this->values);
    }


    /**
     * 设置商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
     * @param string $value
     **/
    public function SetOut_trade_no($value)
    {
        $this->values['out_trade_no'] = $value;
    }
    /**
     * 获取商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号的值
     * @return 值
     **/
    public function GetOut_trade_no()
    {
        return $this->values['out_trade_no'];
    }
    /**
     * 判断商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号是否存在
     * @return true 或 false
     **/
    public function IsOut_trade_noSet()
    {
        return array_key_exists('out_trade_no', $this->values);
    }


    /**
     * 设置符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
     * @param string $value
     **/
    public function SetFee_type($value)
    {
        $this->values['fee_type'] = $value;
    }
    /**
     * 获取符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型的值
     * @return 值
     **/
    public function GetFee_type()
    {
        return $this->values['fee_type'];
    }
    /**
     * 判断符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型是否存在
     * @return true 或 false
     **/
    public function IsFee_typeSet()
    {
        return array_key_exists('fee_type', $this->values);
    }


    /**
     * 设置订单总金额，只能为整数，详见支付金额
     * @param string $value
     **/
    public function SetTotal_fee($value)
    {
        $this->values['total_fee'] = $value;
    }
    /**
     * 获取订单总金额，只能为整数，详见支付金额的值
     * @return 值
     **/
    public function GetTotal_fee()
    {
        return $this->values['total_fee'];
    }
    /**
     * 判断订单总金额，只能为整数，详见支付金额是否存在
     * @return true 或 false
     **/
    public function IsTotal_feeSet()
    {
        return array_key_exists('total_fee', $this->values);
    }


    /**
     * 设置APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。
     * @param string $value
     **/
    public function SetSpbill_create_ip($value)
    {
        $this->values['spbill_create_ip'] = $value;
    }
    /**
     * 获取APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。的值
     * @return 值
     **/
    public function GetSpbill_create_ip()
    {
        return $this->values['spbill_create_ip'];
    }
    /**
     * 判断APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。是否存在
     * @return true 或 false
     **/
    public function IsSpbill_create_ipSet()
    {
        return array_key_exists('spbill_create_ip', $this->values);
    }


    /**
     * 设置订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。其他详见时间规则
     * @param string $value
     **/
    public function SetTime_start($value)
    {
        $this->values['time_start'] = $value;
    }
    /**
     * 获取订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。其他详见时间规则的值
     * @return 值
     **/
    public function GetTime_start()
    {
        return $this->values['time_start'];
    }
    /**
     * 判断订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。其他详见时间规则是否存在
     * @return true 或 false
     **/
    public function IsTime_startSet()
    {
        return array_key_exists('time_start', $this->values);
    }


    /**
     * 设置订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则
     * @param string $value
     **/
    public function SetTime_expire($value)
    {
        $this->values['time_expire'] = $value;
    }
    /**
     * 获取订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则的值
     * @return 值
     **/
    public function GetTime_expire()
    {
        return $this->values['time_expire'];
    }
    /**
     * 判断订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则是否存在
     * @return true 或 false
     **/
    public function IsTime_expireSet()
    {
        return array_key_exists('time_expire', $this->values);
    }


    /**
     * 设置商品标记，代金券或立减优惠功能的参数，说明详见代金券或立减优惠
     * @param string $value
     **/
    public function SetGoods_tag($value)
    {
        $this->values['goods_tag'] = $value;
    }
    /**
     * 获取商品标记，代金券或立减优惠功能的参数，说明详见代金券或立减优惠的值
     * @return 值
     **/
    public function GetGoods_tag()
    {
        return $this->values['goods_tag'];
    }
    /**
     * 判断商品标记，代金券或立减优惠功能的参数，说明详见代金券或立减优惠是否存在
     * @return true 或 false
     **/
    public function IsGoods_tagSet()
    {
        return array_key_exists('goods_tag', $this->values);
    }


    /**
     * 设置接收微信支付异步通知回调地址
     * @param string $value
     **/
    public function SetNotify_url($value)
    {
        $this->values['notify_url'] = $value;
    }
    /**
     * 获取接收微信支付异步通知回调地址的值
     * @return 值
     **/
    public function GetNotify_url()
    {
        return $this->values['notify_url'];
    }
    /**
     * 判断接收微信支付异步通知回调地址是否存在
     * @return true 或 false
     **/
    public function IsNotify_urlSet()
    {
        return array_key_exists('notify_url', $this->values);
    }


    /**
     * 设置取值如下：JSAPI，NATIVE，APP，详细说明见参数规定
     * @param string $value
     **/
    public function SetTrade_type($value)
    {
        $this->values['trade_type'] = $value;
    }
    /**
     * 获取取值如下：JSAPI，NATIVE，APP，详细说明见参数规定的值
     * @return 值
     **/
    public function GetTrade_type()
    {
        return $this->values['trade_type'];
    }
    /**
     * 判断取值如下：JSAPI，NATIVE，APP，详细说明见参数规定是否存在
     * @return true 或 false
     **/
    public function IsTrade_typeSet()
    {
        return array_key_exists('trade_type', $this->values);
    }


    /**
     * 设置trade_type=NATIVE，此参数必传。此id为二维码中包含的商品ID，商户自行定义。
     * @param string $value
     **/
    public function SetProduct_id($value)
    {
        $this->values['product_id'] = $value;
    }
    /**
     * 获取trade_type=NATIVE，此参数必传。此id为二维码中包含的商品ID，商户自行定义。的值
     * @return 值
     **/
    public function GetProduct_id()
    {
        return $this->values['product_id'];
    }
    /**
     * 判断trade_type=NATIVE，此参数必传。此id为二维码中包含的商品ID，商户自行定义。是否存在
     * @return true 或 false
     **/
    public function IsProduct_idSet()
    {
        return array_key_exists('product_id', $this->values);
    }


    /**
     * 设置trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识。下单前需要调用【网页授权获取用户信息】接口获取到用户的Openid。
     * @param string $value
     **/
    public function SetOpenid($value)
    {
        $this->values['openid'] = $value;
    }
    /**
     * 获取trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识。下单前需要调用【网页授权获取用户信息】接口获取到用户的Openid。 的值
     * @return 值
     **/
    public function GetOpenid()
    {
        return $this->values['openid'];
    }
    /**
     * 判断trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识。下单前需要调用【网页授权获取用户信息】接口获取到用户的Openid。 是否存在
     * @return true 或 false
     **/
    public function IsOpenidSet()
    {
        return array_key_exists('openid', $this->values);
    }
}


/**
 *
 * 接口调用结果类
 * @author widyhu
 *
 */
class WxPayResults extends WxPayDataBase
{
    /**
     *
     * 检测签名
     */
    public function CheckSign()
    {
        //fix异常
        if(!$this->IsSignSet()){
            throw new WxPayException("签名错误！");
        }

        $sign = $this->MakeSign();
        if($this->GetSign() == $sign){
            return true;
        }
        throw new WxPayException("签名错误！");
    }

    /**
     *
     * 使用数组初始化
     * @param array $array
     */
    public function FromArray($array)
    {
        $this->values = $array;
    }

    /**
     *
     * 使用数组初始化对象
     * @param array $array
     * @param 是否检测签名 $noCheckSign
     */
    public static function InitFromArray($array, $noCheckSign = false)
    {
        $obj = new self();
        $obj->FromArray($array);
        if($noCheckSign == false){
            $obj->CheckSign();
        }
        return $obj;
    }

    /**
     *
     * 设置参数
     * @param string $key
     * @param string $value
     */
    public function SetData($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public static function Init($xml)
    {
        $obj = new self();
        $obj->FromXml($xml);
        //fix bug 2015-06-29
        if($obj->values['return_code'] != 'SUCCESS'){
            return $obj->GetValues();
        }
        $obj->CheckSign();
        return $obj->GetValues();
    }
}

class Wxpub_model{

    public $data;//接收到的数据，类型为关联数组
    var $returnParameters;//返回参数，类型为关联数组

    /**
     * 	作用：array转xml
     */
    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val))
            {
                $xml.="<".$key.">".$val."</".$key.">";

            }
            else
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }


    /**
     * 	作用：将xml转为array
     */
    public function xmlToArray($xml)
    {
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    /**
     * 生成接口参数xml
     */
    function createXml()
    {
        return $this->arrayToXml($this->returnParameters);
    }

    /**
     * 将xml数据返回微信
     */
    function returnXml()
    {
        $returnXml = $this->createXml();
        return $returnXml;
    }

    /**
     * 设置返回微信的xml数据
     */
    function setReturnParameter($parameter, $parameterValue)
    {
        $this->returnParameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
    }

    function saveData($xml)
    {
        $this->data = $this->xmlToArray($xml);
    }

    function checkSign()
    {
        $tmpData = $this->data;
        unset($tmpData['sign']);
        $sign = $this->getSign($tmpData);//本地签名
        if ($this->data['sign'] == $sign) {
            return TRUE;
        }
        return FALSE;
    }

    function trimString($value)
    {
        $ret = null;
        if (null != $value)
        {
            $ret = $value;
            if (strlen($ret) == 0)
            {
                $ret = null;
            }
        }
        return $ret;
    }

    /**
     * 	作用：生成签名
     */
    public function getSign($Obj)
    {
        foreach ($Obj as $k => $v)
        {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //echo '【string1】'.$String.'</br>';
        //签名步骤二：在string后加入KEY
        $String = $String."&key=".WxPayConfig::KEY;
        //echo "【string2】".$String."</br>";
        //签名步骤三：MD5加密
        $String = md5($String);
        //echo "【string3】 ".$String."</br>";
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        //echo "【result】 ".$result_."</br>";
        return $result_;
    }

    /**
     * 	作用：格式化参数，签名过程需要使用
     */
    function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
                $v = urlencode($v);
            }
            //$buff .= strtolower($k) . "=" . $v . "&";
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar='';
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

}

/**
 *
 * 测速上报输入对象
 * @author widyhu
 *
 */
class WxPayReport extends WxPayDataBase
{
    /**
     * 设置微信分配的公众账号ID
     * @param string $value
     **/
    public function SetAppid($value)
    {
        $this->values['appid'] = $value;
    }
    /**
     * 获取微信分配的公众账号ID的值
     * @return 值
     **/
    public function GetAppid()
    {
        return $this->values['appid'];
    }
    /**
     * 判断微信分配的公众账号ID是否存在
     * @return true 或 false
     **/
    public function IsAppidSet()
    {
        return array_key_exists('appid', $this->values);
    }


    /**
     * 设置微信支付分配的商户号
     * @param string $value
     **/
    public function SetMch_id($value)
    {
        $this->values['mch_id'] = $value;
    }
    /**
     * 获取微信支付分配的商户号的值
     * @return 值
     **/
    public function GetMch_id()
    {
        return $this->values['mch_id'];
    }
    /**
     * 判断微信支付分配的商户号是否存在
     * @return true 或 false
     **/
    public function IsMch_idSet()
    {
        return array_key_exists('mch_id', $this->values);
    }


    /**
     * 设置微信支付分配的终端设备号，商户自定义
     * @param string $value
     **/
    public function SetDevice_info($value)
    {
        $this->values['device_info'] = $value;
    }
    /**
     * 获取微信支付分配的终端设备号，商户自定义的值
     * @return 值
     **/
    public function GetDevice_info()
    {
        return $this->values['device_info'];
    }
    /**
     * 判断微信支付分配的终端设备号，商户自定义是否存在
     * @return true 或 false
     **/
    public function IsDevice_infoSet()
    {
        return array_key_exists('device_info', $this->values);
    }


    /**
     * 设置随机字符串，不长于32位。推荐随机数生成算法
     * @param string $value
     **/
    public function SetNonce_str($value)
    {
        $this->values['nonce_str'] = $value;
    }
    /**
     * 获取随机字符串，不长于32位。推荐随机数生成算法的值
     * @return 值
     **/
    public function GetNonce_str()
    {
        return $this->values['nonce_str'];
    }
    /**
     * 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
     * @return true 或 false
     **/
    public function IsNonce_strSet()
    {
        return array_key_exists('nonce_str', $this->values);
    }


    /**
     * 设置上报对应的接口的完整URL，类似：https://api.mch.weixin.qq.com/pay/unifiedorder对于被扫支付，为更好的和商户共同分析一次业务行为的整体耗时情况，对于两种接入模式，请都在门店侧对一次被扫行为进行一次单独的整体上报，上报URL指定为：https://api.mch.weixin.qq.com/pay/micropay/total关于两种接入模式具体可参考本文档章节：被扫支付商户接入模式其它接口调用仍然按照调用一次，上报一次来进行。
     * @param string $value
     **/
    public function SetInterface_url($value)
    {
        $this->values['interface_url'] = $value;
    }
    /**
     * 获取上报对应的接口的完整URL，类似：https://api.mch.weixin.qq.com/pay/unifiedorder对于被扫支付，为更好的和商户共同分析一次业务行为的整体耗时情况，对于两种接入模式，请都在门店侧对一次被扫行为进行一次单独的整体上报，上报URL指定为：https://api.mch.weixin.qq.com/pay/micropay/total关于两种接入模式具体可参考本文档章节：被扫支付商户接入模式其它接口调用仍然按照调用一次，上报一次来进行。的值
     * @return 值
     **/
    public function GetInterface_url()
    {
        return $this->values['interface_url'];
    }
    /**
     * 判断上报对应的接口的完整URL，类似：https://api.mch.weixin.qq.com/pay/unifiedorder对于被扫支付，为更好的和商户共同分析一次业务行为的整体耗时情况，对于两种接入模式，请都在门店侧对一次被扫行为进行一次单独的整体上报，上报URL指定为：https://api.mch.weixin.qq.com/pay/micropay/total关于两种接入模式具体可参考本文档章节：被扫支付商户接入模式其它接口调用仍然按照调用一次，上报一次来进行。是否存在
     * @return true 或 false
     **/
    public function IsInterface_urlSet()
    {
        return array_key_exists('interface_url', $this->values);
    }


    /**
     * 设置接口耗时情况，单位为毫秒
     * @param string $value
     **/
    public function SetExecute_time_($value)
    {
        $this->values['execute_time_'] = $value;
    }
    /**
     * 获取接口耗时情况，单位为毫秒的值
     * @return 值
     **/
    public function GetExecute_time_()
    {
        return $this->values['execute_time_'];
    }
    /**
     * 判断接口耗时情况，单位为毫秒是否存在
     * @return true 或 false
     **/
    public function IsExecute_time_Set()
    {
        return array_key_exists('execute_time_', $this->values);
    }


    /**
     * 设置SUCCESS/FAIL此字段是通信标识，非交易标识，交易是否成功需要查看trade_state来判断
     * @param string $value
     **/
    public function SetReturn_code($value)
    {
        $this->values['return_code'] = $value;
    }
    /**
     * 获取SUCCESS/FAIL此字段是通信标识，非交易标识，交易是否成功需要查看trade_state来判断的值
     * @return 值
     **/
    public function GetReturn_code()
    {
        return $this->values['return_code'];
    }
    /**
     * 判断SUCCESS/FAIL此字段是通信标识，非交易标识，交易是否成功需要查看trade_state来判断是否存在
     * @return true 或 false
     **/
    public function IsReturn_codeSet()
    {
        return array_key_exists('return_code', $this->values);
    }


    /**
     * 设置返回信息，如非空，为错误原因签名失败参数格式校验错误
     * @param string $value
     **/
    public function SetReturn_msg($value)
    {
        $this->values['return_msg'] = $value;
    }
    /**
     * 获取返回信息，如非空，为错误原因签名失败参数格式校验错误的值
     * @return 值
     **/
    public function GetReturn_msg()
    {
        return $this->values['return_msg'];
    }
    /**
     * 判断返回信息，如非空，为错误原因签名失败参数格式校验错误是否存在
     * @return true 或 false
     **/
    public function IsReturn_msgSet()
    {
        return array_key_exists('return_msg', $this->values);
    }


    /**
     * 设置SUCCESS/FAIL
     * @param string $value
     **/
    public function SetResult_code($value)
    {
        $this->values['result_code'] = $value;
    }
    /**
     * 获取SUCCESS/FAIL的值
     * @return 值
     **/
    public function GetResult_code()
    {
        return $this->values['result_code'];
    }
    /**
     * 判断SUCCESS/FAIL是否存在
     * @return true 或 false
     **/
    public function IsResult_codeSet()
    {
        return array_key_exists('result_code', $this->values);
    }


    /**
     * 设置ORDERNOTEXIST—订单不存在SYSTEMERROR—系统错误
     * @param string $value
     **/
    public function SetErr_code($value)
    {
        $this->values['err_code'] = $value;
    }
    /**
     * 获取ORDERNOTEXIST—订单不存在SYSTEMERROR—系统错误的值
     * @return 值
     **/
    public function GetErr_code()
    {
        return $this->values['err_code'];
    }
    /**
     * 判断ORDERNOTEXIST—订单不存在SYSTEMERROR—系统错误是否存在
     * @return true 或 false
     **/
    public function IsErr_codeSet()
    {
        return array_key_exists('err_code', $this->values);
    }


    /**
     * 设置结果信息描述
     * @param string $value
     **/
    public function SetErr_code_des($value)
    {
        $this->values['err_code_des'] = $value;
    }
    /**
     * 获取结果信息描述的值
     * @return 值
     **/
    public function GetErr_code_des()
    {
        return $this->values['err_code_des'];
    }
    /**
     * 判断结果信息描述是否存在
     * @return true 或 false
     **/
    public function IsErr_code_desSet()
    {
        return array_key_exists('err_code_des', $this->values);
    }


    /**
     * 设置商户系统内部的订单号,商户可以在上报时提供相关商户订单号方便微信支付更好的提高服务质量。
     * @param string $value
     **/
    public function SetOut_trade_no($value)
    {
        $this->values['out_trade_no'] = $value;
    }
    /**
     * 获取商户系统内部的订单号,商户可以在上报时提供相关商户订单号方便微信支付更好的提高服务质量。 的值
     * @return 值
     **/
    public function GetOut_trade_no()
    {
        return $this->values['out_trade_no'];
    }
    /**
     * 判断商户系统内部的订单号,商户可以在上报时提供相关商户订单号方便微信支付更好的提高服务质量。 是否存在
     * @return true 或 false
     **/
    public function IsOut_trade_noSet()
    {
        return array_key_exists('out_trade_no', $this->values);
    }


    /**
     * 设置发起接口调用时的机器IP
     * @param string $value
     **/
    public function SetUser_ip($value)
    {
        $this->values['user_ip'] = $value;
    }
    /**
     * 获取发起接口调用时的机器IP 的值
     * @return 值
     **/
    public function GetUser_ip()
    {
        return $this->values['user_ip'];
    }
    /**
     * 判断发起接口调用时的机器IP 是否存在
     * @return true 或 false
     **/
    public function IsUser_ipSet()
    {
        return array_key_exists('user_ip', $this->values);
    }


    /**
     * 设置系统时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则
     * @param string $value
     **/
    public function SetTime($value)
    {
        $this->values['time'] = $value;
    }
    /**
     * 获取系统时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则的值
     * @return 值
     **/
    public function GetTime()
    {
        return $this->values['time'];
    }
    /**
     * 判断系统时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则是否存在
     * @return true 或 false
     **/
    public function IsTimeSet()
    {
        return array_key_exists('time', $this->values);
    }
}


/**
 *
 * 微信支付API异常类
 * @author widyhu
 *
 */
class WxPayException extends Exception {
    public function errorMessage()
    {
        return $this->getMessage();
    }
}


