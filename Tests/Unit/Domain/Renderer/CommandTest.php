<?php
namespace Saccas\Mjml\Tests\Functional\Domain\Renderer;

use Saccas\Mjml\Domain\Renderer\Command;
use Saccas\Mjml\Tests\Unit\AbstractUnitTestCase;
use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class CommandTest extends AbstractUnitTestCase
{
    protected ObjectManager $objectManager;

    public function setUp(): void
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    }

    /**
     * @test
     */
    public function htmlIsReturnedForMjml(): void
    {
        // Mock extension to be active, to enable path fetching to call node binary.
        $packageMock = $this->getMockBuilder(Package::class)
            ->disableOriginalConstructor()
            ->getMock();
        $packageMock->expects($this->any())
            ->method('getPackagePath')
            ->willReturn(dirname(__FILE__, 5) . '/');
        $packageManagerMock = $this->getMockBuilder(PackageManager::class)->getMock();
        $packageManagerMock->expects($this->any())
            ->method('isPackageActive')
            ->with('mjml')
            ->willReturn(true);
        $packageManagerMock->expects($this->any())
            ->method('getPackage')
            ->with('mjml')
            ->willReturn($packageMock);
        ExtensionManagementUtility::setPackageManager($packageManagerMock);

        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mjml'] = serialize([
            'nodeBinaryPath' => 'node',
            'mjmlBinaryPath' => 'node_modules/mjml/bin/',
            'mjmlBinary' => 'mjml',
            'mjmlParams' => '-s --config.beautify true --config.minify true',
        ]);

        $subject = $this->objectManager->get(Command::class);
        $mjml = file_get_contents(__DIR__ . '/CommandTestFixture/Basic.mjml');
        $html = $subject->getHtmlFromMjml($mjml);

        // remove comment rendered by the outputToConsole https://github.com/mjmlio/mjml/blob/50b08513b7a651c234829abfde254f106a62c859/packages/mjml-cli/src/commands/outputToConsole.js#L4
        $html = preg_replace('/<!-- FILE: (.*)-->/Uis', '', $html);

        $this->assertStringEqualsFile(
            __DIR__ . '/CommandTestFixture/Expected.html',
            $html,
            'Command renderer did not return expected HTML.'
        );
    }
}
