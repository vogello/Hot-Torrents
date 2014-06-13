<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Hot Torents</title>
	<link rel="stylesheet" href="screen.css" type="text/css" media="screen, projection" />
	<script type="text/javascript" src="./js/jquery/jquery.js"></script>
	<script type="text/javascript" src="./js/script.js"></script>
</head>
<body>

<?php

$rows = array();
if (($handle = fopen("movies.txt", "r")) !== FALSE) 
{
    while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) 
	{
		if (isset($data[3]))
		{
			$name = movieName($data[1]);
			if (!isset($rows[$name]))
			{
				$rows[$name] = array(
		        	'count' => 1,

					'seeds' => $data[0],
					'name' => $data[1],
					'torrent_url' => $data[2],
					'url' => $data[3],
					
					'title' => movieName($data[1]),
					'year' => movieYear($data[1]),
					'type' => movieType($data[1]),			
				);
			}
			else
			{
				$rows[$name]['count']++;
				if ($rows[$name]['seeds'] < $data[0])
				{
					$rows[$name]['seeds'] = $data[0];
					$rows[$name]['name'] = $data[1];
					$rows[$name]['torrent_url'] = $data[2];
					$rows[$name]['url'] = $data[3];
					$rows[$name]['title'] = movieName($data[1]);
					$rows[$name]['year'] = movieYear($data[1]);
					$rows[$name]['type'] = movieType($data[1]);
				}
			}
		}
    }
    fclose($handle);
}

function getPopularity($seeds) 
{
	$levels = array(
		0, 100, 250, 500, 1000
	);
	
	foreach($levels as $level => $limit) 
	{
		if ($limit >= $seeds) 
		{
			$limit--;
			break;
		}		
	}
	
	return $level;
}

function getCountPopularity($seeds) 
{
	$levels = array(
		1, 5, 10, 20, 30
	);
	
	foreach($levels as $level => $limit) 
	{
		if ($limit >= $seeds) 
		{
			$limit--;
			break;
		}		
	}
	
	return $level;
}

function movieYear($text) 
{
	$text = preg_match('#.*?([12][0-9]{3}).*$#si', $text) ? preg_replace('#.*?([12][0-9]{3}).*$#si', '$1', $text) : null;
	return trim($text);
}

function movieName($text) 
{
	$text = preg_replace('#^[,\.\(\)\[\]\*\-\{\}]+.*? #si', '', $text);
	$text = preg_replace('#[,\.\(\)\[\]\*\-\{\}]#si', ' ', $text);
	$text = preg_replace('#(BDRip|DVDrip|R5|CAM|DVD|Screener|DVDScr|LIMITED) .*$#si', '', $text);
	$text = preg_replace('#(TS) .*$#s', '', $text);
	$text = preg_replace('#20[0-9]{2}.*$#si', '', $text);
	$text = preg_replace('#19[0-9]{2}.*$#si', '', $text);
	return trim($text);
}

function movieType($text) 
{
	if 		(preg_match('#(BDRip|BRRip)#si', $text)) { $type = 'BRRip'; }
	elseif 	(preg_match('#(HDrip)#si', $text)) { $type = 'HDrip'; }
	elseif 	(preg_match('#(DVDrip|DVD rip)#si', $text)) { $type = 'DVDrip'; }
	elseif 	(preg_match('#(Screener)#si', $text)) { $type = 'Screener'; }
	elseif 	(preg_match('#(DVDScr)#si', $text)) { $type = 'DVDScr'; }
	elseif 	(preg_match('#(WEBRip)#si', $text)) { $type = 'WEBRip'; }
	elseif 	(preg_match('#(VODRip|DTHRiP|DTHRip)#si', $text)) { $type = 'VOD'; }
	elseif 	(preg_match('#(TS)#s', $text)) { $type = 'TS'; }
	elseif 	(preg_match('#(RMVB)#s', $text)) { $type = 'RMVB'; }
	elseif 	(preg_match('#(TELESYNC|TC)#s', $text)) { $type = 'TELESYNC'; }
	elseif 	(preg_match('#(R5|R6)#si', $text)) { $type = 'R5'; }
	elseif 	(preg_match('#(CAM)#si', $text)) { $type = 'CAM'; }
	elseif 	(preg_match('#(HDTV)#si', $text)) { $type = 'TVHD'; }
	elseif 	(preg_match('#(TVRip)#si', $text)) { $type = 'TV'; }
	elseif 	(preg_match('#(DVD)#si', $text)) { $type = 'DVDrip'; }
	elseif 	(preg_match('#(HQ|x264|H264|H\.264)#si', $text)) { $type = 'HQ'; }
	elseif 	(preg_match('#(WORKPRINT|CROPPED)#si', $text)) { $type = 'Preview'; }
	elseif 	(preg_match('#(XViD|XviD|XVID|xvid|DivX|DIVX|divx)#s', $text)) { $type = 'XVID/DIVX'; }
	else 	$type = '???';
	
	return $type;
}

$sortByTitle = function(array $a, array $b) 
{
	return $a['title'] > $b['title'];
};
$sortBySeed = function(array $a, array $b) 
{
	return $a['seeds'] < $b['seeds'];
};
$sortByType = function (array $a, array $b) 
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
};
$sortByYear = function(array $a, array $b)  
{
	return $a['year'] < $b['year'];
};
$sortByCount = function(array $a, array $b)  
{
	return $a['count'] < $b['count'];
};

if (isset($_GET['year']) && !empty($_GET['year'])) {
	usort($rows, $sortByYear);
} elseif (isset($_GET['type']) && !empty($_GET['type'])) {
	usort($rows, $sortByType);
} elseif (isset($_GET['seed']) && !empty($_GET['seed'])) {
	usort($rows, $sortBySeed);
}  elseif (isset($_GET['torrent']) && !empty($_GET['torrent'])) {
	usort($rows, $sortByCount);
} else {
	usort($rows, $sortByTitle);
}

echo '<ul id="filter" style="position: fixed; right: 0; top: 0;">';
echo '</ul>';

echo '<table>';

	echo '<tr class="header">';
	echo '<th><a href="?torrent=1">Torrents</a></th>';
	echo '<th><a href="?seed=1">Seeds</a></th>';
	echo '<th><a href="?type=1">Type</a></th>';
	echo '<th><a href="?year=1">Year</a></th>';
	echo '<th><a href="?title=1">Title</a></th>';
	echo '<th>Torrent</th>';
	echo '</tr>';
	
foreach($rows as $row) {
	$url  = 'http://www.imdb.com/find?s=tt&q=';
	
	echo '<tr>';
	echo '<td class="popularity-' . getCountPopularity($row['count']) . '">' . $row['count'] . '</td>';
	echo '<td class="popularity-' . getPopularity($row['seeds']) . '">' . $row['seeds'] . '</td>';
	echo '<td class="type class_' . ($row['type'] != '???' ? $row['type'] : 'unknown') . '">' . $row['type'] . '</td>';
	echo '<td><a href="' . $url . urlencode($row['title'].($row['year'] ? ' '.$row['year'] : null)) . '" target="_blank">' . ($row['year'] ? $row['year'] : '???') . '</a></td>';
	echo '<td><a href="' . $url . urlencode($row['title']) . '" target="_blank">' . $row['title'] . '</a></td>';
	echo '<td class="grey"><a href="' . $row['torrent_url'] . '">[MAGNET]</a> <a href="http://thepiratebay.com' . $row['url'] . '" target="_blank">' . $row['name'] . '</a></td>';
	echo '</tr>';
}

echo '</table>';

?>

</body>
</html>