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



class CodeSnippetCreator
{

    public function run(array $config, string $configPath)
    {
        $typo3CodeSnippets = new Typo3CodeSnippets();
        $fileCount = 0;
        foreach ($config as $entry) {
            try {
                $content = '.. Generated by https://github.com/linawolf/t3docs_restructured_api_tools ' . LF;
                if (is_array($entry) && $entry['action']) {
                    switch ($entry['action']) {
                        case 'createPhpClassDocs':
                            $content .= ClassDocsHelper::extractPhpDomain($entry);
                            break;
                        case 'createCodeSnippet':
                            $content .= $typo3CodeSnippets->createCodeSnippetFromConfig($entry);
                            break;
                        default:
                            throw new \ErrorException('Unkown action: ' . $entry['action']);
                    }
                }
                $filename = $configPath . '/' . $entry['targetFileName'];
                mkdir(dirname($filename), 0755, true);
                \TYPO3\CMS\Core\Utility\GeneralUtility::writeFile($filename,
                    $content);
                $fileCount++;
            } catch (\Throwable $t) {
                echo $t->getMessage() . "\n";
            }
        }
        echo $fileCount . ' Files or changed.' . "\n";
    }

}
