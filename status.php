<?php
class status {
	private $volume;
	private $repeat;
	private $random;
	private $single;
	private $consume;
	private $playlist;
	private $playlistLength;
	private $state;
	private $song;
	private $songID;
	private $nextSong;
	private $nextSongID;
	private $time;
	private $elapsed;
	private $duration;
	private $bitrate;
	private $xfade;
	private $mixRampDB;
	private $mixRampDelay;
	private $audio;
	private $updatingDB;
	private $error;

	private function setValue($value) {
		if (strcmp("", $value) == 0) return;

		$key = explode(':', $value)[0];
		$val = explode(' ', $value)[1];

		switch ($key) {
			case 'volume':         $this->volume =$val; break;
			case 'repeat':         $this->repeat =$val; break;
			case 'random':         $this->random =$val; break;
			case 'single':         $this->single =$val; break;
			case 'consume':        $this->consume =$val; break;
			case 'playlist':       $this->playlist =$val; break;
			case 'playlistlength': $this->playlistLength =$val; break;
			case 'state':          $this->state =$val; break;
			case 'song':           $this->song =$val; break;
			case 'songid':         $this->songID =$val; break;
			case 'nextsong':       $this->nextSong =$val; break;
			case 'nextsongid':     $this->nextSongID =$val; break;
			case 'time':           $this->time =$val; break;
			case 'elapsed':        $this->elapsed =$val; break;
			case 'duration':       $this->duration =$val; break;
			case 'bitrate':        $this->bitrate =$val; break;
			case 'xfade':          $this->xfade =$val; break;
			case 'mixrampdb':      $this->mixRampDB =$val; break;
			case 'audio':          $this->audio =$val; break;
			case 'updatingdb':     $this->updatingDB =$val; break;
			case 'error':          $this->error =$val; break;
			default: break;
		}
	}

	function __construct($statusString) {
		$elements = explode(chr(10), $statusString);
		foreach ($elements AS $value) {
			$this->setValue($value);
		}
	}

	function getVolume() {
		return $this->volume;
	}

	function getRepeat() {
		return $this->repeat;
	}

	function getRandom() {
		return $this->random;
	}

	function getSingle() {
		return $this->single;
	}

	function getConsume() {
		return $this->consume;
	}

	function getPlaylist() {
		return $this->playlist;
	}

	function getPlaylistLength() {
		return $this->playlistLength;
	}

	function getState() {
		return $this->state;
	}

	function getSong() {
		return $this->song;
	}

	function getSongID() {
		return $this->songID;
	}

	function getNextSong() {
		return $this->nextSong;
	}

	function getNextSongID() {
		return $this->nextSongID;
	}

	function getTime() {
		return $this->time;
	}

	function getElapsed() {
		return $this->elapsed;
	}

	function getDuration() {
		return $this->duration;
	}

	function getBitrate() {
		return $this->bitrate;
	}

	function getXfade() {
		return $this->xfade;
	}

	function getMixRampDB() {
		return $this->mixRampDB;
	}

	function getMixRampDelay() {
		return $this->mixRampDelay;
	}

	function getAudio() {
		return $this->audio;
	}

	function getUpdatingDB() {
		return $this->updatingDB;
	}

	function getError() {
		return $this->error;
	}
}

?>