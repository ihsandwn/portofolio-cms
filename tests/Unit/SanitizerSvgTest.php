<?php

namespace Tests\Unit;

use App\Helpers\Sanitizer;
use PHPUnit\Framework\TestCase;

class SanitizerSvgTest extends TestCase
{
    public function testKeepsPlainIconMarkup(): void
    {
        $icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.5" d="M4 4h16"/></svg>';

        $clean = Sanitizer::svg($icon);

        $this->assertStringContainsString('<svg', $clean);
        $this->assertStringContainsString('viewBox="0 0 24 24"', $clean);
        $this->assertStringContainsString('d="M4 4h16"', $clean);
    }

    public function testDropsEventHandlersAndScripts(): void
    {
        $icon = '<svg viewBox="0 0 24 24" onload="alert(1)"><script>alert(2)</script><path d="M0 0" onclick="alert(3)"/></svg>';

        $clean = Sanitizer::svg($icon);

        $this->assertStringNotContainsString('onload', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('script', $clean);
        $this->assertStringNotContainsString('alert', $clean);
    }

    public function testDropsForeignElementsAndReferences(): void
    {
        $icon = '<svg viewBox="0 0 24 24"><foreignObject><iframe src="https://evil.test"></iframe></foreignObject><use href="https://evil.test/x.svg#a"/><path d="M0 0"/></svg>';

        $clean = Sanitizer::svg($icon);

        $this->assertStringNotContainsString('foreignObject', $clean);
        $this->assertStringNotContainsString('iframe', $clean);
        $this->assertStringNotContainsString('evil.test', $clean);
        $this->assertStringContainsString('<path', $clean);
    }

    public function testRejectsNonSvgPayload(): void
    {
        $this->assertSame('', Sanitizer::svg('<div>not an icon</div>'));
        $this->assertSame('', Sanitizer::svg('<svg><path d="M0 0"/></svg><script>alert(1)</script>'));
        $this->assertSame('', Sanitizer::svg(''));
    }
}
