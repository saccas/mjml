<?php
namespace Saccas\Mjml\Domain\Renderer;

use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class Command implements RendererInterface
{
    public function getHtmlFromMjml($mjml)
    {
        $configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mjml']);

        $temporaryMjmlFileWithPath = GeneralUtility::tempnam('mjml_', '.mjml');

        GeneralUtility::writeFileToTypo3tempDir($temporaryMjmlFileWithPath, $mjml);

        // see https://mjml.io/download and https://www.npmjs.com/package/mjml-cli
        $cmd = $configuration['nodeBinaryPath'] . ' ' . ExtensionManagementUtility::extPath('mjml') . $configuration['mjmlBinaryPath'] . $configuration['mjmlBinary'];
        $args = $temporaryMjmlFileWithPath . ' ' . $configuration['mjmlParams'];

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
    protected function getEscapedCommand(string $cmd, string $args)
    {
        $escapedCmd = escapeshellcmd($cmd);

        $argsArray = explode(' ', $args);
        $escapedArgsArray = CommandUtility::escapeShellArguments($argsArray);
        $escapedArgs = implode(' ', $escapedArgsArray);

        return $escapedCmd . ' ' . $escapedArgs;
    }
}
