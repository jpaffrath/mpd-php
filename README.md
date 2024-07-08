# mpd-php
mpd-php is a php library for controlling a mpd server.

# Usage
Import _mpd.php_ in your web project:
```PHP
include 'mpd.php';
```

Configure the connection settings and connect to a mpd server:
```PHP
$mpd = new mpd('127.0.0.1', 6600);

if ($mpd->connect()) {
    alert("Connected!");
}
```

Control server playback:
```PHP
$mpd->play($songnr);
$mpd->stop();
$mpd->resume();

$mpd->next();
$mpd->previous();

$mpd->clear();
```

Get database informations:
```PHP
$mpd->getArtistnames();
$mpd->getAlbumnamesFromArtist($artist);

$mpd->isPlaying();
```

Control playlists:
```PHP
$mpd->listPlaylists();
$mpd->listPlaylist($playlist);

$mpd->getCurrentSong();
$mpd->loadPlaylist($playlist)();
```

Disconnect from server:
```PHP
$mpd->disconnect();
```
