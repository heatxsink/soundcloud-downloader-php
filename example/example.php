<?php

$stream_url = false;
if(array_key_exists('soundcloud-url', $_POST)) {
	$url = $_POST['soundcloud-url'];
	$stream_data = SoundCloud::getStreamData($url);
	$mp3_data = SoundCloud::getMusic($stream_data["stream_url"]);
	$filename = $stream_data["stream_title"] . ".mp3";
	header("Content-Type: audio/mpeg");
	header("Content-Disposition: attachment; filename=$filename");
	return $mp3_data;
} else {
	return "There has been an error, please try again.";
}
