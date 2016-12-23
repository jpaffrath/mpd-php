<?php
class stats {
	private $artists;
	private $albums;
	private $songs;
	private $uptime;
	private $db_playtime;
	private $db_uptime;
	private $playtime;

	private function setValue($value) {
		if (strcmp("", $value) == 0) return;

		$key = explode(':', $value)[0];
		$val = explode(' ', $value)[1];

		switch ($key) {
			case 'artists':     $this->artists =$val; break;
			case 'albums':      $this->albums =$val; break;
			case 'songs':       $this->songs =$val; break;
			case 'uptime':      $this->uptime =$val; break;
			case 'db_playtime': $this->db_playtime =$val; break;
			case 'db_uptime':   $this->db_uptime =$val; break;
			case 'playtime':    $this->playtime =$val; break;
			default: break;
		}
	}

	function __construct($statusString) {
		echo "Status: $statusString<br>";
		$elements = explode(chr(10), $statusString);
		foreach ($elements AS $value) {
			$this->setValue($value);
		}
	}

	function getArtists() {
		return $this->artists;
	}

	function getAlbums() {
		return $this->albums;
	}

	function getSongs() {
		return $this->songs;
	}

	function getUptime() {
		return $this->uptime;
	}

	function getDBPlaytime() {
		return $this->db_playtime;
	}

	function getDBUptime() {
		return $this->db_uptime;
	}

	function getPlaytime() {
		return $this->playtime;
	}
}

?>