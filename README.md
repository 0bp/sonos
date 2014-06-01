sonos
=====

PHP classes to control Sonos speakers

Inspired by [DjMomo/sonos](https://github.com/DjMomo/sonos) and [phil-lavin/sonos](https://github.com/phil-lavin/sonos)


Classes
-------
Three classes are available:
* Network - Provides static methods to locate speakers/controllers on the current network
* Speaker - Provides a read-only interface to individual speakers
* Controller - Allows interactive with the groups of speakers. Although sometimes a Controller is synonymous with a Speaker, when speakers are grouped together only the coordinator can receive events (play/pause/etc)


Network Class
-------------
All of these methods are static
* getSpeakers() - Returns an array of Speaker instances for all speakers on the network
* getSpeakersByRoom(string $room) - Returns an array of Speaker instances for all speakers with the specified room name
* getSpeakerByRoom(string $room) - Returns a Speaker instance for the first speaker with the specified room name
* getControllers() - Returns an array of Controller instances, one instance per group of speakers
* getControllerByRoom(string $room) - Returns a Controller instance for the speaker assigned as coordinator of the specified room name


Speaker Class
-------------
All of these properties are public
* ip - The IP address of the speaker
* name - The "Friendly" name reported by the speaker
* room - The room name assigned to this speaker
There are also the folllwing public methods
* isCoordinator() - Returns true if this speaker is the coordinator of it's current group
* getVolume() - Get the current volume of this speaker as an integer between 0 and 100
* setVolume(int $volume) - Set the current volume of this speaker
* adjustVolme(int $adjust) - Adjust the volume of this speaker by a relative amount between -100 and 100


Controller Class
----------------
The Controller class extends the Speaker class, so all the public properties/methods listed above are available, in addition to the following public methods
* getState() - Returns the current state of the group of speakers using the Controller class constants:
STATE_STOPPED
STATE_PLAYING
STATE_PAUSED
STATE_TRANSITIONING
STATE_UNKNOWN
* getStateName() - Returns the current state of the group of speakers as the string reported by sonos: PLAYING, PAUSED_PLAYBACK, etc
* getStateDetails() - Returns an array of attributes about the currently active track in the queue
* play() - Start playing the active music for this group
* pause() - Pause the group
* next() - Skip to the next track in the current queue
* previous() - Skip back to the previous track in the current queue
* getSpeakers() - Returns an array of Speaker instances that are in the group of this Controller
* addSpeaker(Speaker $speaker) - Adds the specified speaker to the group of this Controller
* removeSpeaker(Speaker $speaker) - Removes the specified speaker from the group of this Controller
* setVolume(int $volume) - Set the current volume of all the speakers controlled by this Controller
* adjustVolme(int $adjust) - Adjust the volume of all the speakers controlled by this Controller by a relative amount between -100 and 100


Examples
--------

Get all the speakers on the network
```
$speakers = \Sonos\Network::getSpeakers();
foreach($speakers as $speaker) {
    echo $speaker->ip . "\n";
    echo "\t" . $speaker->name . " (" . $speaker->room . ")\n";
}
```

Start all groups playing music
```
$controllers = \Sonos\Network::getControllers();
foreach($controllers as $controller) {
    echo $controller->name . " (" . $controller->room . ")\n";
    echo "\tState: " . $controller->getState() . "\n";
    $controller->play();
}
```

Control what is currently playing in the Living Room, even if it is not the coordinator of it's current group
```
$controller = \Sonos\Network::getControllerByRoom("Living Room");
echo $controller->room . "\n";
$controller->pause();
```
