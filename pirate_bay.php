<?php

date_default_timezone_set('Europe/Warsaw');

require './config.php';
require './lib/cURL.php';
require './lib/Colors.php';
require './lib/phpQuery/phpQuery/phpQuery.php';

$index = 0;
$max_index = 100;
$curl = new cURL(false);
$colors = new Colors();

$results = array();

if (file_exists('./movies.txt')) { rename('./movies.txt', './archive/movies.' . date('Y-m-d-H-i-s') . '.txt'); }

$fp = fopen('./movies.txt', 'w');

while($index < $max_index) {
	$html = $curl->get(str_replace(
		array('%index%'),
		array($index),
		$url_pattern
	));
	phpQuery::newDocumentHTML($html);
	echo ($index % 10 == 0 ? "\n" : null) . '.'; 
	foreach(pq('table#searchResult > tr') as $tr) {
		if (!pq($tr)->find('td:first')->attr('colspan')) {
			$seeds = pq(pq($tr)->find('td')->get(2))->html();
			$user = pq(pq($tr)->find('td')->get(1))->find('a[href^="/user/"]')->length();
			if ($seeds > 9 && $user > 1) {
				$name = pq(pq($tr)->find('td')->get(1))->find('div.detName a')->html();
				$url = pq(pq($tr)->find('td')->get(1))->find('a[title="Download this torrent using magnet"]')->attr('href');
				$pirateurl = pq(pq($tr)->find('td')->get(1))->find('div.detName a')->attr('href');
				$results[] = array(
					'name' => $name,
					'url' => $url,
					'seeds' => $seeds,
					'pirateurl' => $pirateurl,
				);
				fwrite($fp, $seeds."\t".
					$name."\t".
					$url."\t".
					$pirateurl."\n");
			}
		}
	}
	$index++;
}

fclose($fp);

echo 'done @ '.date('Y-m-d H:i:s') . "\n";
echo '<a href="./">back</a>';

?>