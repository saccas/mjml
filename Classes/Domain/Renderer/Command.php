<?php
namespace Saccas\Mjml\Domain\Renderer;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class Command implements RendererInterface
{
    public function getHtmlFromMjml($mjml): string
    {
        $conf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('mjml');

        $temporaryMjmlFileWithPath = GeneralUtility::tempnam('mjml_', '.mjml');

        GeneralUtility::writeFileToTypo3tempDir($temporaryMjmlFileWithPath, $mjml);

        $mjmlExtPath = ExtensionManagementUtility::extPath('mjml');

        // see https://mjml.io/download and https://www.npmjs.com/package/mjml-cli
        $cmd = $conf['nodeBinaryPath'] . ' ' . $mjmlExtPath . $conf['mjmlBinaryPath'] . $conf['mjmlBinary'];
        $args = $temporaryMjmlFileWithPath . ' ' . $conf['mjmlParams'];

        $result = [];
        $returnValue = '';

        CommandUtility::exec($this->getEscapedCommand($cmd, $args), $result, $returnValue);

        GeneralUtility::unlink_tempfile($temporaryMjmlFileWithPath);

        return implode('', $result);
    }

    /**
     * @param string $cmd
     * @param string $args
     * @return string
     */
    protected function getEscapedCommand(string $cmd, string $args): string
    {
        $escapedCmd = escapeshellcmd($cmd);

        $argsArray = explode(' ', $args);
        $escapedArgsArray = CommandUtility::escapeShellArguments($argsArray);
        $escapedArgs = implode(' ', $escapedArgsArray);

        return $escapedCmd . ' ' . $escapedArgs;
    }
}
