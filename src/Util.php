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

namespace TencentSDK\Im;

/**
 * 工具类.
 */
final class Util
{
    /**
     * List to map.
     *
     * @param array  $arr
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public static function list2map(array $arr, string $key = 'Tag', string $value = 'Value')
    {
        $return = [];
        foreach ($arr as $item) {
            $return[$item[$key]] = $item[$value];
        }

        return $return;
    }
}
