<?php

declare(strict_types=1);

/*
 * This file is part of eelly package.
 *
 * (c) eelly.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tencent\Im;

use GuzzleHttp\Client as HttpClient;
use Phalcon\Di\Injectable;
use Phalcon\Text;
use Tencent\TLSSigAPI;

/**
 * Tim Client v4.0.
 */
class Client extends Injectable
{
    /**
     * 默认签名有效时间.
     */
    private const SIG_DEFAULT_TTL = 15552000;

    /**
     * App id.
     *
     * @var int
     */
    private $appId;

    private $tLSSigAPI;

    private $httpClient;

    private $adminIdentifier;

    /**
     * Client constructor.
     * @param int $appId Appid
     * @param string $privateKeyPath  私钥路径
     * @param string $adminIdentifier 管理员帐号
     */
    public function __construct(int $appId, string $privateKeyPath, string $adminIdentifier = 'admin')
    {
        $this->appId = $appId;
        $this->tLSSigAPI = new TLSSigAPI();
        $this->tLSSigAPI->setAppid($this->appId);
        $this->tLSSigAPI->setPrivateKey(file_get_contents($privateKeyPath));
        $this->adminIdentifier = $adminIdentifier;
        $this->httpClient = new HttpClient([
            'base_uri' => 'https://console.tim.qq.com/v4/',
        ]);
    }

    /**
     * @param string $servicename
     * @param string $command
     * @param array  $data
     *
     * @throws \Exception
     *
     * @return ReturnObject
     */
    public function execute(string $servicename, string $command, array $data)
    {
        static $sigArr;
        $now = time();
        if (null == $sigArr || $sigArr['expire'] < $now) {
            $sigArr['userSig'] = $this->genSig($this->adminIdentifier, self::SIG_DEFAULT_TTL);
            $sigArr['expire'] = $now + self::SIG_DEFAULT_TTL - 10;
        }
        $random = Text::random(Text::RANDOM_NUMERIC, 32);
        $response = $this->httpClient->post($servicename.'/'.$command, [
            'query' => [
                'sdkappid'    => $this->appId,
                'identifier'  => $this->adminIdentifier,
                'usersig'     => $sigArr['userSig'],
                'random'      => $random,
                'contenttype' => 'json',
            ],
            'body' => json_encode($data),
        ]);

        return new ReturnObject(\GuzzleHttp\json_decode($response->getBody(), true));
    }

    /**
     * 生成usersig.
     *
     * @param string $identifier
     * @param int    $expire
     *
     * @throws \Exception
     *
     * @return string
     */
    public function genSig(string $identifier, int $expire = self::SIG_DEFAULT_TTL)
    {
        return $this->tLSSigAPI->genSig($identifier, $expire);
    }
}
