<?php

declare(strict_types=1);

namespace Symplify\Skipper\Tests\SkipCriteriaResolver\SkippedPathsResolver;

use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\Skipper\SkipCriteriaResolver\SkippedPathsResolver;
use Symplify\Skipper\Tests\HttpKernel\SkipperKernel;

final class SkippedPathsResolverTest extends AbstractKernelTestCase
{
    private SkippedPathsResolver $skippedPathsResolver;

    protected function setUp(): void
    {
        $this->bootKernelWithConfigs(SkipperKernel::class, [__DIR__ . '/config/config.php']);
        $this->skippedPathsResolver = $this->getService(SkippedPathsResolver::class);
    }

    public function test(): void
    {
        $skippedPaths = $this->skippedPathsResolver->resolve();
        $this->assertCount(2, $skippedPaths);

        $this->assertSame('*/Mask/*', $skippedPaths[1]);
    }
}
