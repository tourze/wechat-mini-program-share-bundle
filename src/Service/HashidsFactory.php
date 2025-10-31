<?php

namespace WechatMiniProgramShareBundle\Service;

use Hashids\Hashids;

class HashidsFactory
{
    public static function createHashids(): Hashids
    {
        $salt = $_ENV['HASHID_SALT'] ?? '';
        if (!is_string($salt)) {
            $salt = '';
        }

        return new Hashids($salt, 10);
    }
}
