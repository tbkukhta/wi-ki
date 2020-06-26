<?php

namespace app\services;

use yii\base\InvalidValueException;

class TransliteratorService
{
    /**
     * Convert russian string into transliterated english string
     *
     * @param string $inputString
     * @return string
     */
    public static function execute(string $inputString): string
    {
        if (!is_string($inputString)) {
            throw new InvalidValueException('Input value must be string.');
        }

        $inputString = strip_tags($inputString);
        $inputString = str_replace(['\n', '\r'], ' ', $inputString);
        $inputString = trim(preg_replace('/\s+/', ' ', $inputString));
        $inputString = function_exists('mb_strtolower') ? mb_strtolower($inputString) : strtolower($inputString);
        $inputString = strtr($inputString, [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
            'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh',
            'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
        ]);
        $inputString = preg_replace('/[^0-9a-z-_ ]/i', '', $inputString);
        $inputString = str_replace(' ', '-', $inputString);

        return $inputString;
    }
}