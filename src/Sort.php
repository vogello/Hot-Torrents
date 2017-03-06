<?php

namespace vogello\HotTorrents;

class Sort
{
    public static function sort($rows, $sortBy)
    {
        switch ($sortBy) {
            case 'title':
                $sortBy = 'Title';
                break;
            case 'year':
                $sortBy = 'Year';
                break;
            case 'type':
                $sortBy = 'Type';
                break;
            case 'seeds':
                $sortBy = 'Seeds';
                break;
            default:
                $sortBy = 'Title';
        }

        usort($rows, 'self::by' . $sortBy);

        return $rows;
    }

    public static function byTitle(array $a, array $b)
    {
        return $a['title'] > $b['title'];
    }

    public static function byYear(array $a, array $b)
    {
        return $a['year'] < $b['year'];
    }

    public static function byType(array $a, array $b)
    {
        $values = array(

            'DVDrip' => 95,
            'BDRip' => 90,
            'DVDScr' => 85,
            'R5' => 80,

            'TVHD' => 50,
            'TV' => 45,

            'Screener' => 30,
            'TELESYNC' => 25,
            'TS' => 20,
            'CAM' => 15,

        );

        $_a = isset($values[$a['type']]) ? $values[$a['type']] : 0;
        $_b = isset($values[$b['type']]) ? $values[$b['type']] : 0;

        return $_a < $_b;
    }

    public static function bySeeds(array $a, array $b)
    {
        return $a['seeds'] < $b['seeds'];
    }
}