sonos
=====

A PHP class to control Sonos speakers

Inspired by [DjMomo/sonos](https://github.com/DjMomo/sonos) and [phil-lavin/sonos](https://github.com/phil-lavin/sonos)


Static Methods
--------------
* getSpeakers() - Returns an array of Controller instances for all speakers on the network
* getGroups() - As above, but only returns one instance per group of speakers


Public Properties
-----------------
* ip - The IP address of the speaker
* name - The "Friendly" name reported by the speaker
* room - The room name assigned to this speaker


Public Methods
--------------
* getState() - Returns the current state of the speaker (PLAYING, PAUSED_PLAYBACK, etc)
> _This method doesn't return the correct state for a speaker that is part of a group, but not the coordinator of that group._
> _Because of this, recommended use is with getGroups() rather than getSpeakers()_


Examples
--------

```
$speakers = \Sonos\Controller::getSpeakers();
foreach($speakers as $sonos) {
	echo $sonos->ip . "\n";
	echo "\t" . $sonos->name . " (" . $sonos->room . ")\n";
}
```

```
$groups = \Sonos\Controller::getGroups();
foreach($groups as $sonos) {
	echo $sonos->name . " (" . $sonos->room . ")\n";
	echo "\tState: " . $sonos->getState() . "\n";
}
```
