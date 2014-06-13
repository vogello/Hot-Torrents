<?php

class cURL {
	
	protected $headers;
	protected $user_agent;
	protected $compression;
	protected $cookie_file;
	protected $proxy;
	protected $info;
	protected $result;
	protected $url;
	protected $baseurl;
	public $cookie = null;
	
	public function __construct($cookies=TRUE, $cookie='../cache/cookies.txt', $compression='gzip,deflate,sdch', $proxy='') {
		$this->headers[] = 'Accept: application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5';
		$this->headers[] = 'Connection: keep-alive';
		$this->headers[] = 'Content-type: application/x-www-form-urlencoded';
		$this->headers[] = 'Accept-charset: UTF-8,*;q=0.5';
		$this->headers[] = 'Accept-language: en-US,en;q=0.8';
		$this->headers[] = 'Cache-control: max-age=0';
		$this->headers[] = 'Origin: http://localhost';
		$this->user_agent = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_8; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.134 Safari/534.16';
		$this->compression=$compression;
		$this->proxy=$proxy;
		$this->cookies=$cookies;
		if ($this->cookies == TRUE) $this->_cookie($cookie);
	}
	
	protected function _parseUrl() {
		$url_array = parse_url($this->_getUrl());
		
		if (isset($url_array['query'])) {
			$query = array();
			$query_elements = explode('&', $url_array['query']);
			foreach ($query_elements as $element) {
				$elements = explode('=', $element);
				if (isset($elements[0]))
					$query[] = $elements[0].'='.(isset($elements[1]) ? urlencode(urldecode($elements[1])) : '');
			}
			$query = join('&', $query);
		}
		
		$url = 
			(isset($url_array['scheme']) ? $url_array['scheme'].'://' : '').
			(isset($url_array['user']) && isset($url_array['pass']) ? $url_array['user'].':'.$url_array['pass'].'@' : (isset($url_array['user']) ? $url_array['user'].'@' : '')).
			(isset($url_array['host']) ? $url_array['host'] : '').
			(isset($url_array['path']) ? $url_array['path'] : '').
			(isset($query) ? '?'.$query : '').
			(isset($url_array['fragment']) ? '#'.$url_array['fragment'] : '')
		;
		return $url;	
	}
	
	protected function _cookie($cookie_file) {
		if (file_exists($cookie_file)) {
			$this->cookie_file=$cookie_file;
		} else {
			fopen($cookie_file,'w') or $this->_error('The cookie file could not be opened. Make sure this directory has the correct permissions');
			$this->cookie_file = $cookie_file;
			if ($this->cookie_file) @fclose($this->cookie_file);
		}
	}

	protected function _setUrl($url) {
		$this->url = $url;
		$this->_computeBaseUrl();
		return true;
	}
	
	protected function _getUrl() {
		return $this->url;
	}
	
	public function get($url = null) {
		if ($url) $this->_setUrl($url);
		else $url = $this->_getUrl();
		
		$process = curl_init($this->_parseUrl());
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		
		if ($this->cookie) curl_setopt('CURLOPT_COOKIE', $this->cookie);
		curl_setopt($process,CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		if ($this->proxy) curl_setopt($cUrl, CURLOPT_PROXY, 'proxy_ip:proxy_port');
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($process, CURLOPT_COOKIESESSION, 1);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($process, CURLOPT_AUTOREFERER, 1);
		$return = curl_exec($process);
		$this->_setInfo(curl_getinfo($process));
		curl_close($process);
		return $this->_setResult($return);
	}
	
	public function post($url = null, $data) {
		if ($url) $this->_setUrl($url);
		else $url = $this->_getUrl();
		
		$process = curl_init($this->_parseUrl());
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
				
//		if ($this->cookie) curl_setopt($process, CURLOPT_COOKIE, $this->cookie);
		curl_setopt($process, CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_CONNECTTIMEOUT, 30);
		
		if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
		
		curl_setopt($process, CURLOPT_POST, 1);
		curl_setopt($process, CURLOPT_POSTFIELDS, $data);
//		curl_setopt($process, CURLOPT_COOKIESESSION, 1);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($process, CURLOPT_AUTOREFERER, 1);
		
//		curl_setopt($process,    CURLOPT_FRESH_CONNECT, 1);

		curl_setopt($process, CURLOPT_REFERER, "http://localhost/projects/bebiloncrm/trunk/test/register.php");
		$return = curl_exec($process);
		$this->_setInfo(curl_getinfo($process));
		curl_close($process);
		return $this->_setResult($return);
	}

	protected function _setResult($result) {
		return ($this->result = $result);
	}
	
	public function getResult() {
		return $this->result;
	}
	
	protected function _error($error) {
		echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>";
		die;
	}
	
	protected function _setInfo($rs) {
		$this->info = $rs;
		return true;
	}
	
	public function getInfo($id = null) {
		return $id && isset($this->info[$id]) ? $this->info[$id] : false;
	}
	
	public function getLastInfo($id = null) {
		return $this->info;
	}

	protected function _computeBaseUrl() {
		$parsed_url = parse_url($this->_getUrl());
		return $this->baseurl = $parsed_url['scheme'].'://'.$parsed_url['host'].'/';
	}
	
	public function getBaseUrl() {
		return $this->baseurl;
	}
	
	public function getUrl() {
		return $this->_getUrl();
	}
	
	public function getCookie($id) {
		$file = file_get_contents($this->cookie_file);
		preg_match_all('#.*?\t.*?\t/\t.*?\t.*?\t'.$id.'\t(.*)#mi', $file, $extracted);
		return isset($extracted[1]) && isset($extracted[1][0]) ? $extracted[1][0] : null;
	}
	
	public function getHttpCode() {
		return $this->getInfo('http_code');
	}

}

?>