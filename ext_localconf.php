<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)->registerImplementation(
    \Saccas\Mjml\Domain\Renderer\RendererInterface::class,
    \Saccas\Mjml\Domain\Renderer\Command::class
);
