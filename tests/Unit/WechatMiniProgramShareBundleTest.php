<?php

namespace WechatMiniProgramShareBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramShareBundle\WechatMiniProgramShareBundle;

class WechatMiniProgramShareBundleTest extends TestCase
{
    public function testBundleCanBeInstantiated(): void
    {
        $bundle = new WechatMiniProgramShareBundle();
        $this->assertInstanceOf(WechatMiniProgramShareBundle::class, $bundle);
    }

    public function testParamKeyConstant(): void
    {
        $this->assertSame('_shareFrom', WechatMiniProgramShareBundle::PARAM_KEY);
    }
}