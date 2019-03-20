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

use Phalcon\Text;

/**
 * Class ReturnObject.
 */
class ReturnObject
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function __call($name, $arguments)
    {
        if (Text::startsWith($name, 'get')) {
            return $this->get(substr($name, 3));
        } else {
            throw new \InvalidArgumentException('Not found method: '.$name);
        }
    }

    /**
     * @return string
     */
    public function getActionStatus()
    {
        return $this->data['ActionStatus'];
    }

    /**
     * @return string
     */
    public function getErrorInfo()
    {
        return $this->data['ErrorInfo'];
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->data['ErrorCode'];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        if ($key && isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            throw new \InvalidArgumentException('Error key: '.$key);
        }
    }
}
