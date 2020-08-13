<?php
/**
 * Copyright (c) 2018.  Alexander Weber <weber@exotec.de> - exotec - TYPO3 Services
 *
 * All rights reserved
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 *
 */

namespace EXOTEC\Cardealer\Utilities;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;


class TaskLibrary
{


    /**
     * @param $email
     * @param $baseURL
     * @throws \TYPO3\CMS\Core\Exception
     */
    public static function sendInfoMail ($email, $baseURL, $subject, $errorMessage)
    {

        try {
            /** @var $mailer \TYPO3\CMS\Core\Mail\MailMessage */
            $mailer = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
            $mailer->setFrom([$email => 'TYPO3 Scheduler']);
            $mailer->setReplyTo([$email => 'typo3@'.$baseURL]);
            $mailer->setSubject($subject.' (' . $baseURL . ')');
            $mailer->html('<html><head>
				   <style type="text/css"></style>
				   </head><body>
				   <h1>'.$subject.'</h1>
				   <div><b>Message:</b> '.$errorMessage->getMessage().'</div><hr />
				   <div><b>File:</b> '.$errorMessage->getFile().'</div><hr />
				   <div><b>Line:</b> '.$errorMessage->getLine().'</div>
				   </body></html>', 'text/html' //Mark the content-type as HTML
            );
            $mailer->setTo($email);
            $mailsSend = $mailer->send();
            $success = $mailsSend > 0;

            $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
            $flashMessageService = $objectManager->get(FlashMessageService::class);
            $messageQueue = $flashMessageService->getMessageQueueByIdentifier();

            if ($success) {
                $message = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                    'Eine Mail wurde an "' . $email . '" gesendet', '', FlashMessage::OK, true);
//                $messageQueue->addMessage($message);
            } else {
                $message = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                    'Mail senden an "' . $email . '" fehlgeschlagen.', '', FlashMessage::ERROR, true);
//                $messageQueue->addMessage($message);
            }

        } catch (\Exception $e) {
            throw new \TYPO3\CMS\Core\Exception($e->getMessage());
        }
    }
    
    /**
     * @param $table
     * @param $values
     * @return void
     */
    public static function updateTable($table, $field, $value, $uid)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $result = $queryBuilder->update($table)
            ->where( $queryBuilder->expr()->eq('uid', $uid))
            ->set($field, $value)
            ->execute();
        return $result;

    }


    /**
     * @param $table
     * @param $values
     * @return int
     */
    public static function insertInto($table, $values) : int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $queryBuilder->insert($table)->values($values)->execute();

        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $databaseConnectionForPages = $connectionPool->getConnectionForTable($table);
//        $lastInsertId = $databaseConnectionForPages->lastInsertId($table);

        try {
            $lastInsertId = $databaseConnectionForPages->lastInsertId($table);

        }

        catch (\Exception $e) {
            $ex = new \TYPO3\CMS\Scheduler\FailedExecutionException('##### Exception #####');
            throw $ex;
        }

        catch (\RuntimeException $e) {
            $ex = new \TYPO3\CMS\Scheduler\FailedExecutionException('##### RuntimeException #####');
            throw $ex;
        }

        catch (\OutOfBoundsException $e) {
            $ex = new \TYPO3\CMS\Scheduler\FailedExecutionException('##### OutOfBoundsException #####');
            throw $ex;
        }

        catch (\UnexpectedValueException $e) {
            $ex = new \TYPO3\CMS\Scheduler\FailedExecutionException('##### UnexpectedValueException #####');
            throw $ex;
        }

        return $lastInsertId;
    }


    /**
     * @return array
     */
    public static function getTyposcriptConf ($pluginName)
    {
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
        $FullTS = $configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $FullTS['plugin.'][$pluginName.'.']['baseURL'] = $FullTS['config.']['baseURL'];
        return $FullTS['plugin.'][$pluginName.'.'];
    }



    /**
     * @param $username
     * @param $password
     * @param string $pageNumber
     * @param string $pageSize
     * @param string $host
     * @param string $getXML
     * @return mixed
     */
    public static function getXmlData($username, $password, $pageNumber = '', $pageSize = '&page.size=100', $host = 'https://services.mobile.de/search-api/search?', $getXML="0")
    {
        $process = curl_init($host . $pageSize . $pageNumber);
        curl_setopt($process, CURLOPT_HTTPHEADER, array('Accept-Language: de', 'Accept: application/xml', 'Host: services.mobile.de'));
        curl_setopt($process, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($process, CURLOPT_USERPWD, '' . $username . ':' . $password . '');
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        $xmlData = curl_exec($process);
        curl_close($process);
        if($getXML == 1) {
            return $xmlData;
        }
        $xmlArr = TaskLibrary::XMLtoArray($xmlData);
        return $xmlArr;
    }



    /**
     * @param $XML
     * @return mixed
     */
    public static function XMLtoArray($XML)
    {
        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, $XML, $vals);
        xml_parser_free($xml_parser);
        // wyznaczamy tablice z powtarzajacymi sie tagami na tym samym poziomie
        $_tmp = '';
        foreach ($vals as $xml_elem) {
            $x_tag = $xml_elem['tag'];
            $x_level = $xml_elem['level'];
            $x_type = $xml_elem['type'];
            if ($x_level != 1 && $x_type == 'close') {
                if (isset($multi_key[$x_tag][$x_level])) {
                    $multi_key[$x_tag][$x_level] = 1;
                } else {
                    $multi_key[$x_tag][$x_level] = 0;
                }
            }
            if ($x_level != 1 && $x_type == 'complete') {
                if ($_tmp == $x_tag) {
                    $multi_key[$x_tag][$x_level] = 1;
                }
                $_tmp = $x_tag;
            }
        }
        // jedziemy po tablicy
        foreach ($vals as $xml_elem) {
            $x_tag = $xml_elem['tag'];
            $x_level = $xml_elem['level'];
            $x_type = $xml_elem['type'];
            if ($x_type == 'open') {
                $level[$x_level] = $x_tag;
            }
            $start_level = 1;
            $php_stmt = '$xml_array';
            if ($x_type == 'close' && $x_level != 1) {
                $multi_key[$x_tag][$x_level]++;
            }
            while ($start_level < $x_level) {
                $php_stmt .= '[$level[' . $start_level . ']]';
                if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level]) {
                    $php_stmt .= '[' . ($multi_key[$level[$start_level]][$start_level] - 1) . ']';
                }
                $start_level++;
            }
            $add = '';
            if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type == 'open' || $x_type == 'complete')) {
                if (!isset($multi_key2[$x_tag][$x_level])) {
                    $multi_key2[$x_tag][$x_level] = 0;
                } else {
                    $multi_key2[$x_tag][$x_level]++;
                }
                $add = '[' . $multi_key2[$x_tag][$x_level] . ']';
            }
            if (isset($xml_elem['value']) && trim($xml_elem['value']) != '' && !array_key_exists('attributes', $xml_elem)) {
                if ($x_type == 'open') {
                    $php_stmt_main = $php_stmt . '[$x_type]' . $add . '[\'content\'] = $xml_elem[\'value\'];';
                } else {
                    $php_stmt_main = $php_stmt . '[$x_tag]' . $add . ' = $xml_elem[\'value\'];';
                }
                eval($php_stmt_main);
            }
            if (array_key_exists('attributes', $xml_elem)) {
                if (isset($xml_elem['value'])) {
                    $php_stmt_main = $php_stmt . '[$x_tag]' . $add . '[\'content\'] = $xml_elem[\'value\'];';
                    eval($php_stmt_main);
                }
                foreach ($xml_elem['attributes'] as $key => $value) {
                    $php_stmt_att = $php_stmt . '[$x_tag]' . $add . '[$key] = $value;';
                    eval($php_stmt_att);
                }
            }
        }
        return $xml_array;
    }



    /**
     * @param $extConf
     * @return array
     */
    public static function collectAddKeys ($configuration, $location): array
    {


        $y = 0;
        foreach ($configuration as $config) {

            $username = $config['username'];
            $password = $config['password'];
            $email = $config['email'];

            $totalArr[$key] = TaskLibrary::getXmlData($username, $password);
            $total = $totalArr[$key]['SEARCH:SEARCH-RESULT']['SEARCH:TOTAL'];
            $steps = ceil($total / 100);
            for ($i = 1; $i <= $steps; $i++) {
                $pageNumber = '&page.number=' . $i . '';
                $xmlArray = TaskLibrary::getXmlData($username, $password, $pageNumber);

                // if 9, we have only one result
                if (count($xmlArray['SEARCH:SEARCH-RESULT']['SEARCH:ADS']['AD:AD']) == 9) {
                    $xmlArray = $xmlArray['SEARCH:SEARCH-RESULT']['SEARCH:ADS'];
                } else {
                    $xmlArray = $xmlArray['SEARCH:SEARCH-RESULT']['SEARCH:ADS']['AD:AD'];
                }

                foreach ($xmlArray as $addVal) {
                    $x = $y++;
                    $addKeys[$x]['key'] = $addVal['KEY'];
                    $addKeys[$x]['location'] = $location;
                    $addKeys[$x]['username'] = $username;
                    $addKeys[$x]['password'] = $password;
                    $addKeys[$x]['email'] = $email;

                }
            }
        }
//        DebuggerUtility::var_dump($addKeys);
//        exit;
        return $addKeys;
    }

}