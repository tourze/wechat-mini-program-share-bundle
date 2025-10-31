<?php

declare(strict_types=1);

namespace WechatMiniProgramShareBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use WechatMiniProgramShareBundle\WechatMiniProgramShareBundle;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramShareBundle::class)]
#[RunTestsInSeparateProcesses]
final class WechatMiniProgramShareBundleTest extends AbstractBundleTestCase
{
}
