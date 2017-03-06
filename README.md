Hot-Torrents
============

PHP App to retrieve newest torrents from Pirate Bay

It scans 100 pages from "Movies" category, filtered by VIP and Trusted uploaders only. It recognizes video rip type (in example: DVD, Blue Ray, Screener itp.), number of seeds, number of similiar torrents, adds link to IMDB page & magnet link to torrent. It archives older PirateBay scans in Archive folder.

Requirements:
- PHP 5.2.x with cURL
- No database required
- A web server to analyze results

Running:
- `@php Initialise.php`
- or on Windows, run `Initialise.bat`

Results are saved in `data/movies.txt` file. Archives ara in `data/archive` dir.
To list results you need web server and then run `public/index.php` in your web browser.
