<?php

declare(strict_types=1);

namespace Symplify\Skipper\Tests\Skipper\Skipper;

use Iterator;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\Skipper\Skipper\Skipper;
use Symplify\Skipper\Tests\HttpKernel\SkipperKernel;
use Symplify\Skipper\Tests\Skipper\Skipper\Fixture\Element\FifthElement;
use Symplify\Skipper\Tests\Skipper\Skipper\Fixture\Element\SixthSense;
use Symplify\Skipper\Tests\Skipper\Skipper\Fixture\Element\ThreeMan;
use Symplify\SmartFileSystem\SmartFileInfo;

final class SkipperTest extends AbstractKernelTestCase
{
    private Skipper $skipper;

    protected function setUp(): void
    {
        $this->bootKernelWithConfigs(SkipperKernel::class, [__DIR__ . '/config/config.php']);
        $this->skipper = $this->getService(Skipper::class);
    }

    /**
     * @dataProvider provideDataShouldSkipFileInfo()
     */
    public function testSkipFileInfo(string $filePath, bool $expectedSkip): void
    {
        $smartFileInfo = new SmartFileInfo($filePath);

        $resultSkip = $this->skipper->shouldSkipFileInfo($smartFileInfo);
        $this->assertSame($expectedSkip, $resultSkip);
    }

    /**
     * @return Iterator<string[]|bool[]>
     */
    public function provideDataShouldSkipFileInfo(): Iterator
    {
        yield [__DIR__ . '/Fixture/SomeRandom/file.txt', false];
        yield [__DIR__ . '/Fixture/SomeSkipped/any.txt', true];
    }

    /**
     * @param object|class-string $element
     * @dataProvider provideDataShouldSkipElement()
     */
    public function testSkipElement(string|object $element, bool $expectedSkip): void
    {
        $resultSkip = $this->skipper->shouldSkipElement($element);
        $this->assertSame($expectedSkip, $resultSkip);
    }

    /**
     * @return Iterator<bool[]|class-string<SixthSense>[]|class-string<ThreeMan>[]|FifthElement[]>
     */
    public function provideDataShouldSkipElement(): Iterator
    {
        yield [ThreeMan::class, false];
        yield [SixthSense::class, true];
        yield [new FifthElement(), true];
    }
}
