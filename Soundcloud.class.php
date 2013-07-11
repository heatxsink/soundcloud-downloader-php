<?php
class SoundCloud {
	private static function  curl_http_get($url, $ssl = false) {
		$ch = curl_init($url);
		$headers = array(
			"User-Agent: curl/7.16.3 (i686-pc-cygwin) libcurl/7.16.3 OpenSSL/0.9.8h zlib/1.2.3 libssh2/0.15-CVS",
			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
			"Accept-Language: en-us;q=0.5,en;q=0.3",
			"Keep-Alive: 115",
			"Connection: keep-alive"
		);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		if ($ssl) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		}
		$response = curl_exec($ch);
		$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($response_code != 200 && $response_code != 302 && $response_code != 304) {
			$response = false;
		}
		return $response;
	}

	public static function getStreamData($url) {
		$return_value = false;
		$valid_url_pattern = '%\b(?:(?:http|https)://|www\.)[\d.a-z-]+\.[a-z]{2,6}(?::\d{1,5}+)?(?:/[!$\'()*+,._a-z-]++){0,10}(?:/[!$\'()*+,._a-z-]+)?(?:\?[!$&\'()*+,.=_a-z-]*)?%i';
		if(preg_match($valid_url_pattern, $url)) {
			$scheme = parse_url($url, PHP_URL_SCHEME);
			if($scheme == "https") {
				$url = str_replace("https","http", $url);
			}
			$response = self::curl_http_get($url, false);
			$return_value = array();
			if($response !== false) {
				//find anything between streamUrl":" and ",
				if(preg_match('/streamUrl\"\:\"(.*?)\"\,/', $response, $stream)) {
					$return_value["stream_url"] = $stream[1];
				}
				if(preg_match('/title\"\:\"(.*?)\"\,/', $response, $title)) {
					$return_value["stream_title"] = $title[1];
				}
			}
		}
		return $return_value;
	}

	public static function getMusic($stream_url) {
		$mp3_data = self::curl_http_get($stream_url);
		return $mp3_data;
	}
}
