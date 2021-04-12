<?php
namespace Saccas\Mjml\View;

use Saccas\Mjml\Domain\Renderer\RendererInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class MjmlBasedView extends StandaloneView
{
    protected ?RendererInterface $renderer = null;

    public function __construct(ContentObjectRenderer $contentObject = null, RendererInterface $renderer = null)
    {
        parent::__construct($contentObject);

        $this->renderer = $renderer;
        if ($this->renderer === null) {
            $this->renderer = $this->objectManager->get(RendererInterface::class);
        }
    }

    public function render($actionName = null): string
    {
        return $this->renderer->getHtmlFromMjml(parent::render($actionName));
    }
}
