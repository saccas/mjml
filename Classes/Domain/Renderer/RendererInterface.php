<?php
namespace Saccas\Mjml\Domain\Renderer;

/**
 * Defines API of possible renderers.
 */
interface RendererInterface
{
    /**
     * Convert mjml strings into html.
     *
     * @param string $mjml
     * @return string
     */
    public function getHtmlFromMjml($mjml): string;
}
