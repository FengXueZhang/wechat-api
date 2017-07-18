<?php

namespace WechatApi\API;

use WechatApi\Api;
/**
 * 微信-通讯录 用户相关接口.
 *
 * @author XueFeng.
 */
class ServiceApi extends BaseApi
{

    /**
     * 创建微信成员登录协议的链接.
     *
     * @author XueFeng
     *
     * @date   2016-04-21
     *
     * @param string $redirectUri 协议的回调地址
     * @param string $state       可携带的参数, 选填.
     * @param string $agentid     授权方的网页应用ID.
     *
     * @return string 协议地址
     */
    public function createOAuthUrl($redirectUri, $state = '', $agentid)
    {
        if (!$redirectUri) {
            $this->setError('参数错误!');

            return false;
        }

        $host = isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] : '';
        $api = 'https://open.work.weixin.qq.com/wwopen/sso/qrConnect';

        $state = $state ? $state = base64_encode($state) : '';

        $url = array();
        $url['appid'] = Api::getCorpId();
        $url['agentid'] = $agentid;
        $url['redirect_uri'] = $host . $redirectUri;
        $url['state'] = $state;

        $url = http_build_query($url);

        $url .= '#wechat_redirect';
        $url = $api . '?' . $url;

        return $url;
    }

    /**
     * 请求.
     *
     * @author XueFeng
     *
     * @date   2016-04-22
     */
    public function request($redirectUri, $state = '')
    {
        $code = I('get.code', false, 'trim');
        if ($code) {
            return;
        }

        $url = $this->createOAuthUrl($redirectUri, $state);
        header('Location:' . $url);
        exit;
    }

    /**
     * 获取登录回调的信息.
     *
     * @author XueFeng
     *
     * @date   2016-04-21
     *
     * @return array 回调信息.
     */
    public function receive()
    {
        return Api::factory("User")->receive();
    }
    
    /**
     * 根据用户ID获取用户信息.
     *
     * @author XueFeng
     *
     * @date   2016-04-21
     *
     * @param string $userId 用户在微信端的userid.
     *
     * @return array 用户信息
     */
    public function getInfoById($userId)
    {
        return Api::factory("User")->getInfoById($userId);
        //return $this->_get($node, $queryStr);
    }
}