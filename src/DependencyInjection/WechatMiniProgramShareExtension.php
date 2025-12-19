<?php

namespace WechatMiniProgramShareBundle\DependencyInjection;

use Tourze\SymfonyDependencyServiceLoader\AutoExtension;

final class WechatMiniProgramShareExtension extends AutoExtension
{
    protected function getConfigDir(): string
    {
        return \dirname(__DIR__) . '/Resources/config';
    }
}
