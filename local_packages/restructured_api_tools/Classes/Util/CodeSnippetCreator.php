<?php

declare(strict_types=1);
namespace T3docs\RestructuredApiTools\Util;

/*
 * This file is part of the TYPO3 project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use T3docs\RestructuredApiTools\Exceptions\InvalidConfigurationException;


class CodeSnippetCreator
{
    CONST RECURSIVE_PATH = 1;
    CONST FLAT_PATH = 1;

    private static $fileCount = 0;
    private static $configPath = '';

    public function run(array $config, string $configPath)
    {
        self::$configPath =  $configPath;
        $typo3CodeSnippets = new Typo3CodeSnippets();
        self::$fileCount = 0;
        foreach ($config as $entry) {
            if (is_array($entry) && $entry['action']) {
                switch ($entry['action']) {
                    case 'createPhpClassDocsAll':
                        ClassDocsHelper::extractPhpDomainAll($entry);
                        break;
                    case 'createPhpClassDocs':
                        $content = ClassDocsHelper::extractPhpDomain($entry);
                        $this->writeFile($entry,  $content);
                        break;
                    case 'createCodeSnippet':
                        $content = $typo3CodeSnippets->createCodeSnippetFromConfig($entry);
                        $this->writeFile($entry,  $content);
                        break;
                    case 'createPhpArrayCodeSnippet':
                        $content = $typo3CodeSnippets->createPhpArrayCodeSnippetFromConfig($entry);
                        $this->writeFile($entry,  $content);
                        break;
                    default:
                        throw new InvalidConfigurationException('Unkown action: ' . $entry['action']);
                }
            }

        }
        echo self::$fileCount . ' Files created or overridden.' . "\n";
    }

    public static function writeSimpleFile($content, $path) {
        if (!$path) {
            throw new InvalidConfigurationException('No path given.');
        }
        if (!$content) {
            throw new InvalidConfigurationException('No content found for file  ' . $path);
        }
        $filename = self::$configPath . '/' . $path;
        mkdir(dirname($filename), 0755, true);
        \TYPO3\CMS\Core\Utility\GeneralUtility::writeFile($filename,
            $content);
    }

    public static function writeFile(array $entry, String $content, String $rstContent = '', $overwriteRst = false, String $indexContent = '', $overwriteIndex = false) {
        $content = '.. Generated by https://github.com/linawolf/t3docs_restructured_api_tools ' . LF . $content;
        if (!$entry['targetFileName']) {
            throw new InvalidConfigurationException('targetFileName not set for action ' . $entry['action']);
        }
        if (!$content) {
            throw new InvalidConfigurationException('No content found for file  ' . $entry['targetFileName']);
        }
        $filename = self::$configPath . '/' . $entry['targetFileName'];
        if (!file_exists(dirname($filename))) {
            mkdir(dirname($filename), 0755, true);
        }
        \TYPO3\CMS\Core\Utility\GeneralUtility::writeFile($filename,
            $content);

        if ($rstContent && $entry['rstFileName']) {
            $rstFilename = self::$configPath . '/' . $entry['rstFileName'];
            mkdir(dirname($rstFilename), 0755, true);
            \TYPO3\CMS\Core\Utility\GeneralUtility::writeFile($filename,
                $content);
            if ($overwriteRst || !file_exists($rstFilename)) {
                \TYPO3\CMS\Core\Utility\GeneralUtility::writeFile($rstFilename, $rstContent);
            }
            if ($indexContent) {
                $indexFile = dirname($rstFilename) . '/Index.rst';
                if ($overwriteIndex || !file_exists($indexFile)) {

                    \TYPO3\CMS\Core\Utility\GeneralUtility::writeFile($indexFile, $indexContent);
                }

            }

        }
        self::$fileCount++;
    }

}
