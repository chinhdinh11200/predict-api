<?php

namespace App\Log;

use DateTimeZone;
use Monolog\Formatter\NormalizerFormatter;
use Monolog\Utils;

class TelegramFormatter extends NormalizerFormatter
{
    /**
     * Add Title
     *
     * @param string $levelName
     * @param $datetime
     * @return string
     */
    protected function addTitle(string $levelName, $datetime): string
    {
        $levelName = $this->formatStr($levelName);
        $datetime = $datetime->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));

        return '<b>[' . $levelName . '] ' . $this->formatDate($datetime) . '</b>';
    }

    /**
     * Formats a log record.
     *
     * @param array $record
     * @return string The formatted record
     */
    public function format(array $record): string
    {
        $output = $this->addTitle($record['level_name'], $record['datetime']);
        $output .= "\n";
        $output .= $record['message'];

        if (!empty($record['context'])) {
            $output .= "\n";
            $output .= $this->formatContext($record['context']);
        }//end if

        return $output;
    }

    /**
     * Format Context
     *
     * @param $context
     * @return string
     */
    protected function formatContext($context): string
    {
        $output = [];
        foreach ($context as $key => $value) {
            $output[] = $this->formatStr($key) . ': ' . $this->convertToString($value);
        }//end foreach

        return implode("\n", $output);
    }

    /**
     * Format str
     *
     * @param $str
     * @return string
     */
    protected function formatStr($str): string
    {
        return htmlspecialchars((string)$str, ENT_NOQUOTES, 'UTF-8');
    }

    /**
     * Convert To String
     *
     * @param $data
     * @return string
     */
    protected function convertToString($data): string
    {
        if (null === $data || is_scalar($data)) {
            return (string)$data;
        }//end if

        $data = $this->normalize($data);

        return Utils::jsonEncode($data, JSON_PRETTY_PRINT | Utils::DEFAULT_JSON_FLAGS, true);
    }
}
