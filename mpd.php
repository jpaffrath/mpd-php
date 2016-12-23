<?php

include 'status.php';
include 'stats.php';

class mpd {
	private $host = '';
	private $port = '';
	private $sock;

	private $error = FALSE;
	private $errorDescription = '';
	private $connected = FALSE;

	private $protocolVersion = '';

	function __construct($host, $port) {
		$this->host = $host;
		$this->port = $port;
	}

	function isConnected() {
		return $this->connected;
	}

	function getErrorDescription() {
		return $this->errorDescription;
	}

	function getProtocolVersion() {
		return $this->protocolVersion;
	}

	/*
	* Connects to a mpd server given in $host at port $port.
	* Returns TRUE if the connection was successfull, otherwise FALSE
	*/
	function connect() {
		if (!$this->connected) {
			$this->sock = fsockopen($this->host, $this->port, $errno, $errstr, 10);

			if (!$this->sock) {
				$this->errorDescription = $errstr;
				return FALSE;
			}

			while (!feof($this->sock)) {
				$response = fgets($this->sock, 1024);

				if (strncmp('OK', $response, 2) == 0) {
					$this->protocolVersion = explode('MPD ', $response)[1];
					$this->connected = TRUE;
					return TRUE;
				}

				return FALSE;
			}

			return FALSE;
		}

		return TRUE;
	}

	/*
	* Closes the current connection to the mpd server.
	*/
	function disconnect() {
		if ($this->connected) {
			fclose($this->sock);
			$this->connected = FALSE;

			return TRUE;
		}

		return FALSE;
	}

	/*
	* Sends the command given in $command to the mpd server.
	*/
	private function sendCommand($command) {
		if (!$this->connected) {
			echo 'Not connected!';
			return NULL;
		}

		fputs($this->sock, "$command\n");
		$resp = "";

		// only set to false if no error occured
		$this->error = TRUE;

		while (!feof($this->sock)) {
			$response = fgets($this->sock, 1024);

			if (strncmp('OK', $response, 2) == 0) {
				$this->error = FALSE;
				break;
			}

			if (strncmp('ACK', $response, 3) == 0) {
				$this->error = TRUE;
				return NULL;
			}

			$resp .= $response;
		}

		return $resp;
	}

	/*
	* Returns an array containing the playlists stored in /var/lib/mpd/playlists
	*/
	function listPlaylists() {
		$response = $this->sendCommand('listplaylists');
		if ($this->error) return NULL;

		$playlists = explode('playlist', $response);

		array_shift($playlists);

		for ($i = 0; $i < count($playlists); $i++) {
			$tempStr = $playlists[$i];

			$tempStr = substr($tempStr, 2);
			$tempStr = strtok($tempStr, chr(10));

			$playlists[$i] = $tempStr;
		}

		return $playlists;
	}

	/*
	* Returns an array containing the songs in the given playlist.
	*/
	function listPlaylist($playlist) {
		$response = $this->sendCommand("listplaylist $playlist");
		if ($this->error) return NULL;

		$songs = explode('file: ', $response);
		array_shift($songs);

		for ($i = 0; $i < count($songs); $i++) {
			$song = $songs[$i];
			$songs[$i] = substr($song, 0, strlen($song)-1);
		}

		return $songs;
	}

	/*
	* Loads the playlist given in $playlist.
	*/
	function loadPlaylist($playlist) {
		$this->sendCommand("load $playlist");
	}

	/*
	* Begins playing the playlist at song number $nr.
	*/
	function play($nr) {
		$this->sendCommand("play $nr");
	}

	function isPlaying() {
		$state = $this->getStatus()->getState();
		if (strcmp($state, 'play') == 0) return TRUE;

		return FALSE;
	}

	/*
	* Plays the next song in the playlist.
	*/
	function next() {
		$this->sendCommand('next');
	}

	function previous() {
		$this->sendCommand('previous');
	}

	function pause() {
		$this->sendCommand('pause 1');
	}

	function resume() {
		$this->sendCommand('pause 0');
	}

	/*
	* Stops playing.
	*/
	function stop() {
		$this->sendCommand('stop');
	}

	/*
	* Clears the current playlist
	*/
	function clear() {
		$this->sendCommand('clear');
	}

	/*
	* Sets the volume of mpd given in $vol.
	*/
	function setVolume($vol) {
		if ($vol < 0 || $vol > 100) return false;

		$this->sendCommand("setvol $vol");
		return true;
	}

	/*
	* Returns a status object out of the status response
	*/
	function getStatus() {
		$response = $this->sendCommand('status');
		$status = new status($response);

		return $status;
	}

	/*
	* Returns a stats object out of the stats response
	*/
	function getStats() {
		$response = $this->sendCommand('stats');
		$stats = new stats($response);

		return $stats;
	}

	/*
	* Returns the current song
	*/
	function getCurrentSong() {
		$response = $this->sendCommand('currentsong');
		if ($this->error) return NULL;

		/*
		* Parsing sometimes is wrong for web radio stations
		*/

		$indexStart = strpos($response, "Title:") + strlen("Title:");
		$indexLenght = strpos($response, "Album:") - $indexStart;

		$song = substr($response, $indexStart, $indexLenght);

		return $song;
	}

	/*
	* Returns all artist names
	*/
	function getArtistnames() {
		$response = $this->sendCommand('list artist');
		if ($this->error) return NULL;

		$artists = explode('Artist: ', $response);
		
		for ($i = 0; $i < count($artists); $i++) {
			$tempStr = $artists[$i];
			$tempStr = substr($tempStr, 0, -1);

			$artists[$i] = $tempStr;
		}

		return $artists;
	}

	/*
	* Returns all album names for a given artist
	*/
	function getAlbumnamesFromArtist($artist) {
		$response = $this->sendCommand('list album "'.$artist.'"');
		if ($this->error) return NULL;

		$albums = explode('Album: ', $response);

		for ($i = 0; $i < count($albums); $i++) {
			$tempStr = $albums[$i];
			$tempStr = substr($tempStr, 0, -1);

			$albums[$i] = $tempStr;
		}

		return $albums;
	}
}

?>