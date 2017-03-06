<?php
require './vendor/autoload.php';
require './src/phpQuery/phpQuery.php';

$index = 0;
$max_index = 20;
$curl = curl_init();

$results = array();

if (file_exists('./data/movies.json')) {
    rename('./data/movies.json', './data/archive/movies.' . date('Y-m-d-H-i-s') . '.json');
}

$fp = fopen('./data/movies.json', 'w');

while ($index < $max_index) {
    curl_setopt_array(
        $curl,
        [
            CURLOPT_URL => str_replace(['%index%'], [$index], vogello\HotTorrents\Config::SCAN_URL),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
        ]
    );
    $html = curl_exec($curl);
    \phpQuery::newDocumentHTML($html);
    foreach (pq('table#searchResult > tr') as $tr) {
        if (!pq($tr)->find('td:first')->attr('colspan')) {
            $seeds = pq(pq($tr)->find('td')->get(2))->html();
            $user = pq(pq($tr)->find('td')->get(1))->find('a[href^="/user/"]')->count();
            if ($seeds > 9 && $user > 1) {
                $results[] = array(
                    'name' => pq(pq($tr)->find('td')->get(1))->find('div.detName a')->html(),
                    'url' => pq(pq($tr)->find('td')->get(1))->find('a[title="Download this torrent using magnet"]')->attr('href'),
                    'seeds' => $seeds,
                    'tpb_url' => pq(pq($tr)->find('td')->get(1))->find('div.detName a')->attr('href'),
                );
            }
        }
    }
    $index++;
}
fwrite($fp, json_encode($results));

fclose($fp);

echo 'Completed @ ' . date('Y-m-d H:i:s');