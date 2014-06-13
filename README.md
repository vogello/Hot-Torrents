Hot-Torrents
============

PHP App to retrive newest torrents from Pirate Bay

It scans 100 pages from "Movies" category, filtered by VIP and Trusted uploaders only. It recognizes video rip type (in example: DVD, Blue Ray, Screener itp.), number of seeds, number of similiar torrents, adds link to IMDB page & magnet link to torrent. It archives older PirateBay scans in Archive folder.

Requirements:
- PHP 5.2.x with cURL
- no database required
- webserver to analyze results

Running:
- @php pirate_bay.php
- or just on Windows: pirate.bat

Results are saved in movies.txt file. Archives ara in /archive dir.
To anlyze results you need webserver and run analyze.php in your webbrowser.
