<?php

namespace addons\softlib\library;

use think\response\Json;

/**
 *  Ip信息
 */
class IpInfo
{
    protected const  SUCCESS = 200;
    protected const ERROR = 201;
    protected string $ip;
    protected array $methods = [
        'getMethod_1',
        'getMethod_2',
        'getMethod_4',
        'getMethod_5',
    ];

    /**
     * @title 初始化
     * @param $ip string
     */
    public function __construct(string $ip)
    {
        $this->ip = $ip;
    }


    /**
     * @title 转换为json
     * @return Json
     */
    function toJson(): Json
    {
        $inFo = $this->getInfo();
        $code = $inFo->getCode();
        $msg = $inFo->getMsg();
        $ipInfo = $inFo->getIpInfo()->toArray();
        return json(['code' => $code, 'msg' => $msg, 'data' => $ipInfo], $code);
    }

    /**
     * @title 获取ip信息
     * @return ResponseModel
     */
    public function getInfo(): ResponseModel
    {
        // 过滤空数据
        if (!$this->ip) {
            return new ResponseModel(self::ERROR, '未传入ip地址', new IpInfoModel());
        }
        // 验证ipv4地址合法性
        if (!filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return new ResponseModel(self::ERROR, '这不是一个正确的ip地址', new IpInfoModel());
        }
        //发起请求
        foreach ($this->methods as $method) {
            //调用方法
            $res = $this->$method($this->ip);
            //请求成功
            if ($res->getCode() == self::SUCCESS) {
                return $res;
            }
        }
        //请求失败
        return new ResponseModel(self::ERROR, '请求失败~可能是接口失效了', new IpInfoModel());
    }

    /**
     * @title 【接口一】 https://ipchaxun.com/{ip}/
     * @param $ip
     * @return ResponseModel
     */
    private function getMethod_1($ip): ResponseModel
    {
        try {
            // 中国34个省级行政区域
            $provinces = [
                "北京",
                "天津",
                "河北",
                "山西",
                "内蒙古",
                "辽宁",
                "吉林",
                "黑龙江",
                "上海",
                "江苏",
                "浙江",
                "安徽",
                "福建",
                "江西",
                "山东",
                "河南",
                "湖北",
                "湖南",
                "广东",
                "广西",
                "海南",
                "重庆",
                "四川",
                "贵州",
                "云南",
                "西藏",
                "陕西",
                "甘肃",
                "青海",
                "宁夏",
                "新疆",
                "香港",
                "澳门",
                "台湾"
            ];
            $response = $this->cUrlGetIP('https://ipchaxun.com/' . $ip . '/');
            $str1 = substr($response, strripos($response, "归属地") + 15);
            $str2 = substr($str1, 0, strrpos($str1, '<div id="cidr">'));
            $province = '';
            // 提取省份
            foreach ($provinces as $province_) {
                if (str_contains($str2, $province_)) {
                    $province = $province_;
                    break;
                }
            }

            // 提取国家
            $str3 = substr($str2, 0, strrpos($str2, $province));
            $country = preg_replace('/[^\x{4e00}-\x{9fa5}]+/u', '', $str3);

            // 提取城市
            $str4 = substr($str2, strripos($str2, "nofollow") + 10);
            $city = substr($str4, 0, strrpos($str4, "</a>"));

            // 提取县区
            $str6 = substr($str2, strripos($str2, "</a>") + 4);
            //去除所有空格、换行符、制表符
            $str6 = preg_replace("/\s/", "", $str6);

            //提取县区
            $district = substr($str6, 0, strrpos($str6, "</span></label><label>"));
            //去除多余字符
            if (str_contains($district, '<spanclass="value">')) {
                $district = substr($district, 0, strrpos($district, '<span class="value">'));
            }

            //提取运营商
            $str7 = substr($str2, strripos($str2, "运营商"));
            $str8 = substr($str7, 0, strrpos($str7, "</span></label>"));
            if ($str8) {
                $operator = substr($str8, strripos($str8, "value") + 7);
            } else {
                $operator = '';
            }

            // 判断是否获取成功
            if ($country || $province || $city || $district) {
                $ipInfoModel = new IpInfoModel($country, $province, $city, $district, $operator, $ip);
                return new ResponseModel(self::SUCCESS, '获取成功', $ipInfoModel);
            } else {
                return new ResponseModel(self::ERROR, '获取失败', new IpInfoModel());
            }
        } catch (\Exception $e) {
            return new ResponseModel(self::ERROR, '发生错误：' . $e->getMessage(), new IpInfoModel());
        }
    }

    /**
     * @title  Http请求封装
     * @param $url
     * @return bool|string
     */
    private function cUrlGetIP($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $header[] = 'user-agent: ' . $this->getRandomUserAgent();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        return curl_exec($ch);
    }

    /**
     * @title 获取随机User-Agent
     * @return string User-Agent
     */
    private function getRandomUserAgent(): string
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36',
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36",
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:95.0) Gecko/20100101 Firefox/95.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Safari/605.1.15',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69',
            'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:95.0) Gecko/20100101 Firefox/95.0 Chrome/97.0.4692.99 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 OPR/83.0.4254.27',
            'Mozilla/5.0 (Android 13; Mobile; LG-M700) Gecko/90.0 Firefox/90.0',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Linux; Android 10; Pixel 3 XL Build/QP1A.190711.020) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Mobile Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Safari/605.1.15 Edg/97.0.1072.69'
        ];

        // 随机选择一个 User-Agent
        return $userAgents[array_rand($userAgents)];
    }

    /**
     * @title 【接口二】 https://webapi-pc.meitu.com/common/ip_location?ip={ip}
     * @param $ip
     * @return ResponseModel
     */
    private function getMethod_2($ip): ResponseModel
    {
        try {
            $url = 'https://webapi-pc.meitu.com/common/ip_location?ip=' . $ip;
            $response = $this->cUrlGetIP($url);
            $response = json_decode($response, true);
            if ($response['code'] == 0) {
                $data = $response['data'][$ip];
                $ipInfoModel = new IpInfoModel($data['nation'], $data['province'], $data['city'], $data['district'] = '', $data['isp'], $ip);
                return new ResponseModel(self::SUCCESS, '获取成功', $ipInfoModel);
            } else {
                return new ResponseModel(self::ERROR, '获取失败', new IpInfoModel());
            }
        } catch (\Exception $e) {
            return new ResponseModel(self::ERROR, '发生错误：' . $e->getMessage(), new IpInfoModel());
        }
    }


//    /**
//     * @title 【接口三 国外接口】 http://demo.ip-api.com/json/{ip}?fields=66842623&lang=zh-CN
//     * @param $ip
//     * @return ResponseModel
//     */
//    private function getMethod_3($ip): ResponseModel
//    {
//        $url = 'http://demo.ip-api.com/json/' . $ip . '?fields=66842623&lang=zh-CN';
//        $response = $this->cUrlGetIP($url);
//        $response = json_decode($response, true);
//        if ($response['status'] == 'success') {
//            $ipInfoModel = new IpInfoModel($response['country'], $response['regionName'], $response['city'], $response['district'] = '', $response['isp'], $ip);
//            return new ResponseModel(self::SUCCESS, '获取成功', $ipInfoModel);
//        } else {
//            return new ResponseModel(self::ERROR, '获取失败', new IpInfoModel());
//        }
//    }

    /**
     * @title 【接口四】https://api.qjqq.cn/api/district?ip={ip}
     * @param $ip
     * @return ResponseModel
     */
    private function getMethod_4($ip): ResponseModel
    {
        try {
            $url = 'https://api.qjqq.cn/api/district?ip=' . $ip;
            $response = $this->cUrlGetIP($url);
            $response = json_decode($response, true);
            if ($response['code'] == 200) {
                $response = $response['data'];
                $ipInfoModel = new IpInfoModel($response['country'], $response['prov'], $response['city'], $response['district'], $response['isp'], $ip);
                return new ResponseModel(self::SUCCESS, '获取成功', $ipInfoModel);
            } else {
                return new ResponseModel(self::ERROR, '获取失败', new IpInfoModel());
            }
        } catch (\Exception $e) {
            return new ResponseModel(self::ERROR, '发生错误：' . $e->getMessage(), new IpInfoModel());
        }
    }

    /**
     * @title 【接口五】https://mesh.if.iqiyi.com/aid/ip/info?version=1.1.1&ip={ip}
     * @param $ip
     * @return ResponseModel
     */
    private function getMethod_5($ip): ResponseModel
    {
        try {
            $url = 'https://mesh.if.iqiyi.com/aid/ip/info?version=1.1.1&ip=' . $ip;
            $response = $this->cUrlGetIP($url);
            $response = json_decode($response, true);
            if ($response['code'] == 0) {
                $response = $response['data'];
                $ipInfoModel = new IpInfoModel($response['countryCN'], $response['provinceCN'], $response['cityCN'], $response['countyCN'], $response['ispCN'], $ip);
                return new ResponseModel(self::SUCCESS, '获取成功', $ipInfoModel);
            } else {
                return new ResponseModel(self::ERROR, '获取失败', new IpInfoModel());
            }
        } catch (\Exception $e) {
            return new ResponseModel(self::ERROR, '发生错误：' . $e->getMessage(), new IpInfoModel());
        }
    }
    ///
}

/**
 * Class IpInfoResponse
 * @package addons\appbg\library\tools
 * @title   IpInfoResponse
 */
class  ResponseModel
{
    private int $code;
    private string $msg;
    private IpInfoModel $ipInfo;

    /**
     * ResponseModel constructor.
     * @param $code int
     * @param $msg string
     * @param $ipInfo IpInfoModel
     */
    public function __construct(int $code, string $msg, IpInfoModel $ipInfo)
    {
        $this->code = $code;
        $this->msg = $msg;
        $this->ipInfo = $ipInfo;
    }

    /**
     * @title 获取code
     * @return int
     */
    public
    function getCode(): int
    {
        return $this->code;
    }

    /**
     * @title 获取msg
     * @return string
     */
    public
    function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @title 获取ipInfo
     * @return IpInfoModel
     */
    public
    function getIpInfo(): IpInfoModel
    {
        return $this->ipInfo;
    }
}


/**
 * Class IpInfoModel
 * @package addons\appbg\library\tools
 * @title   IpInfoModel
 */
class IpInfoModel
{
    // 国家
    private string $country;
    // 省份
    private string $province;
    // 城市
    private string $city;
    // 县区
    private string $district;
    // 运营商
    private string $operator;
    // ip地址
    private string $ip;

    /**
     * IpInfoModel constructor.
     * @param $country string 国家
     * @param $province string 省份
     * @param $city string 城市
     * @param $district string 县区
     * @param $operator string 运营商
     * @param $ip string ip地址
     */
    public function __construct(string $country = '', string $province = '', string $city = '', string $district = '', string $operator = '', string $ip = '')
    {
        $this->country = $country;
        $this->province = $province;
        $this->city = $city;
        $this->district = $district;
        $this->operator = $operator;
        $this->ip = $ip;
    }

    /**
     * @title 获取国家
     * @return string 国家
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @title 获取省份
     * @return string 省份
     */
    public function getProvince(): string
    {
        return $this->province;
    }

    /**
     * @title 获取城市
     * @return string 城市
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @title 获取县区
     * @return string 县区
     */
    public function getDistrict(): string
    {
        return $this->district;
    }

    /**
     * @title 获取运营商
     * @return string 运营商
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @title 获取ip地址
     * @return string ip地址
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @title 转换为json
     * @return Json
     */
    function toJson(): Json
    {
        return json($this->toArray());
    }

    /**
     * @title 转换为IpInfo数组
     * @return array
     */
    public function toArray(): array
    {
        return [
            'country' => $this->country,
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'operator' => $this->operator,
            'ip' => $this->ip,
        ];
    }
}
