<?php
namespace Saccas\Mjml\Tests\Unit;

use TYPO3\CMS\Core\Cache\Backend\NullBackend;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

abstract class AbstractUnitTestCase extends UnitTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        GeneralUtility::makeInstance(CacheManager::class)
            ->setCacheConfigurations([
                'extbase_object' => [
                    'backend' => NullBackend::class,
                ],
            ]);
    }
}
