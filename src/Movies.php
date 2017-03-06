<?php

namespace vogello\HotTorrents;

class Movies
{
    public static function getPopularity($seeds)
    {
        $levels = array(
            0,
            100,
            250,
            500,
            1000
        );

        foreach ($levels as $level => $limit) {
            if ($limit >= $seeds) {
                $limit--;
                break;
            }
        }

        return $level;
    }

    public static function getCountPopularity($seeds)
    {
        $levels = array(
            1,
            5,
            10,
            20,
            30
        );

        foreach ($levels as $level => $limit) {
            if ($limit >= $seeds) {
                $limit--;
                break;
            }
        }

        return $level;
    }

    public static function movieYear($text)
    {
        $text = preg_match('#.*?([12][0-9]{3}).*$#si', $text) ? preg_replace('#.*?([12][0-9]{3}).*$#si', '$1',
            $text) : null;
        return trim($text);
    }

    public static function movieName($text)
    {
        $text = preg_replace('#^[,\.\(\)\[\]\*\-\{\}]+.*? #si', '', $text);
        $text = preg_replace('#[,\.\(\)\[\]\*\-\{\}]#si', ' ', $text);
        $text = preg_replace('#(BDRip|DVDrip|R5|CAM|DVD|Screener|DVDScr|LIMITED) .*$#si', '', $text);
        $text = preg_replace('#(TS) .*$#s', '', $text);
        $text = preg_replace('#20[0-9]{2}.*$#si', '', $text);
        $text = preg_replace('#19[0-9]{2}.*$#si', '', $text);
        return trim($text);
    }

    public static function movieType($text)
    {
        if (preg_match('#(BDRip|BRRip)#si', $text)) {
            $type = 'BRRip';
        } elseif (preg_match('#(HDrip)#si', $text)) {
            $type = 'HDrip';
        } elseif (preg_match('#(DVDrip|DVD rip)#si', $text)) {
            $type = 'DVDrip';
        } elseif (preg_match('#(Screener)#si', $text)) {
            $type = 'Screener';
        } elseif (preg_match('#(DVDScr)#si', $text)) {
            $type = 'DVDScr';
        } elseif (preg_match('#(WEBRip)#si', $text)) {
            $type = 'WEBRip';
        } elseif (preg_match('#(VODRip|DTHRiP|DTHRip)#si', $text)) {
            $type = 'VOD';
        } elseif (preg_match('#(TS)#s', $text)) {
            $type = 'TS';
        } elseif (preg_match('#(RMVB)#s', $text)) {
            $type = 'RMVB';
        } elseif (preg_match('#(TELESYNC|TC)#s', $text)) {
            $type = 'TELESYNC';
        } elseif (preg_match('#(R5|R6)#si', $text)) {
            $type = 'R5';
        } elseif (preg_match('#(CAM)#si', $text)) {
            $type = 'CAM';
        } elseif (preg_match('#(HDTV)#si', $text)) {
            $type = 'TVHD';
        } elseif (preg_match('#(TVRip)#si', $text)) {
            $type = 'TV';
        } elseif (preg_match('#(DVD)#si', $text)) {
            $type = 'DVDrip';
        } elseif (preg_match('#(HQ|x264|H264|H\.264)#si', $text)) {
            $type = 'HQ';
        } elseif (preg_match('#(WORKPRINT|CROPPED)#si', $text)) {
            $type = 'Preview';
        } elseif (preg_match('#(XViD|XviD|XVID|xvid|DivX|DIVX|divx)#s', $text)) {
            $type = 'XVID_DIVX';
        } else {
            $type = '???';
        }

        return $type;
    }
}