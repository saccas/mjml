<?php
namespace Saccas\Mjml\Tests\Unit\View;

use Saccas\Mjml\Domain\Renderer\RendererInterface;
use Saccas\Mjml\Tests\Unit\AbstractUnitTestCase;
use Saccas\Mjml\View\MjmlBasedView;

class MjmlBasedViewTest extends AbstractUnitTestCase
{
    public const EXAMPLE_MJML_TEMPLATE = '<mjml>
  <mj-body>
    <mj-section>
      <mj-column>
        <mj-image src="/assets/img/easy-and-quick.png" width="112px" />
        <mj-text font-size="20px" color="#595959" align="center">Easy and Quick</mj-text>
      </mj-column>
      <mj-column>
        <mj-image src="/assets/img/responsive.png" width="135px" />
        <mj-text font-size="20px" color="#595959" align="center">Responsive</mj-text>
      </mj-column>
    </mj-section>
    <mj-section>
      <mj-column>
        <mj-button background-color="#F45E43" font-size="15px">Discover</mj-button>
      </mj-column>
    </mj-section>
  </mj-body>
</mjml>
';

    /**
     * @test
     */
    public function viewCallsRendererAndReturnsRenderedHtml(): void
    {
        $expectedHtml = '<h1>Simple HTML</h1>';
        $rendererMock = $this->getMockBuilder(RendererInterface::class)->getMock();
        $rendererMock->expects($this->once())
            ->method('getHtmlFromMjml')
            ->with(static::EXAMPLE_MJML_TEMPLATE)
            ->willReturn($expectedHtml);

        $subject = new MjmlBasedView(null, $rendererMock);
        $subject->setTemplateSource(static::EXAMPLE_MJML_TEMPLATE);
        $result = $subject->render();

        $this->assertSame(
            $expectedHtml,
            $result,
            'Rendering of view did not return expected HTML.'
        );
    }
}
