<?php

require '../vendor/autoload.php';

use vogello\HotTorrents as HT;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Hot Torrents</title>
    <link rel="stylesheet" href="assets/css/screen.css" type="text/css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
          type="text/css">
    <script type="text/javascript" src="assets/js/jquery/jquery.js"></script>
</head>
<body>

<?php

$rows = array();
if (file_exists("../data/movies.json")) {
    $data = file_get_contents("../data/movies.json");
    $decoded = json_decode($data);
    foreach ($decoded as $item) {
        $name = HT\Movies::movieName($item->name);
        $rows[$name] = array(
            'seeds' => $item->seeds,
            'name' => $item->name,
            'torrent_url' => $item->url,
            'url' => $item->tpb_url,

            'title' => HT\Movies::movieName($item->name),
            'year' => HT\Movies::movieYear($item->name),
            'type' => HT\Movies::movieType($item->name),
        );
    }
}

if (isset($_REQUEST['sortBy'])) {
    $rows = HT\Sort::sort($rows, $_REQUEST['sortBy']);
}

?>

<table>
    <tr class="header">
        <th>
            <a href="?sortBy=title">Title <span class="fa fa-fw fa-sort"></span></a>
        </th>
        <th>
            <a href="?sortBy=type">Type <span class="fa fa-fw fa-sort"></span></a>
        </th>
        <th>
            <a href="?sortBy=year">Movie <span class="fa fa-fw fa-sort"></span></a>
        </th>
        <th>
            <a href="?sortBy=seeds">Seeds <span class="fa fa-fw fa-sort"></span></a>
        </th>
        <th colspan="2">
            Download
        </th>
    </tr>
    <?php
    foreach ($rows as $row) {
        $url = 'http://www.imdb.com/find?s=tt&q=';
        ?>
        <tr>
            <td>
                <a href="<?= $url . urlencode($row['title']) ?>" target="_blank"><?= $row['name'] ?></a>
            </td>
            <td class="type class_<?= ($row['type'] != '???' ? $row['type'] : 'unknown') ?>">
                <?= $row['type'] ?>
            </td>
            <td>
                <a href="<?= $url . urlencode($row['title'] . ($row['year'] ? ' ' . $row['year'] : null)) ?>"
                   target="_blank">
                    <?= ($row['year'] ? $row['year'] : '???') ?>
                </a>
            </td>
            <td class="popularity-<?= HT\Movies::getPopularity($row['seeds']) ?>">
                <?= $row['seeds'] ?>
            </td>
            <td>
                <a href="<?= $row['torrent_url'] ?>">
                    <span class="fa fa-fw fa-magnet fa-rotate-180"></span>
                </a>
                <a href="<?= HT\Config::TPB_URL . $row['url'] ?>" target="_blank"
                   data-title="<?= htmlspecialchars($row['name']) ?>">
                    <span class="fa fa-fw fa-download"></span>
                </a>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
</body>
</html>