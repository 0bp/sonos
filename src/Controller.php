<?php

namespace Sonos;

class Controller extends Speaker {

    const STATE_STOPPED         =   201;
    const STATE_PLAYING         =   202;
    const STATE_PAUSED          =   203;
    const STATE_TRANSITIONING   =   204;
    const STATE_UNKNOWN         =   205;


    public function __construct(Speaker $speaker) {

        $this->ip = $speaker->ip;

        $this->name = $speaker->name;
        $this->room = $speaker->room;
        $this->group = $speaker->getGroup();
        $this->uuid = $speaker->getUuid();

    }


    public function isCoordinator() {
        return true;
    }


    public function getStateName() {
        $data = $this->soap("AVTransport","GetTransportInfo");
        return $data["CurrentTransportState"];
    }


    public function getState() {
        $name = $this->getStateName();
        switch($name) {
            case "STOPPED":
                $state = self::STATE_STOPPED;
                break;
            case "PLAYING":
                $state = self::STATE_PLAYING;
                break;
            case "PAUSED_PLAYBACK":
                $state = self::STATE_PAUSED;
                break;
            case "TRANSITIONING":
                $state = self::STATE_TRANSITIONING;
                break;
            default:
                $state = self::STATE_UNKNOWN;
                break;
        }
        return $state;
    }


    public function play() {
        return $this->soap("AVTransport","Play",[
            "Speed"         =>  1,
        ]);
    }


    public function pause() {
        return $this->soap("AVTransport","Pause");
    }


    public function next() {
        return $this->soap("AVTransport","Next");
    }


    public function previous() {
        return $this->soap("AVTransport","Previous");
    }


    public function getSpeakers() {
        $group = [];
        $speakers = Network::getSpeakers();
        foreach($speakers as $speaker) {
            if($speaker->getGroup() == $this->getGroup()) {
                $group[] = $speaker;
            }
        }
        return $group;
    }


    public function addSpeaker(Speaker $speaker) {
        if($speaker->getUuid() == $this->getUuid()) {
            return;
        }
        $speaker->soap("AVTransport","SetAVTransportURI",array(
            "CurrentURI"            =>  "x-rincon:" . $this->getUuid(),
            "CurrentURIMetaData"    =>  "",
        ));
    }


    public function removeSpeaker(Speaker $speaker) {
        if($speaker->isCoordinator()) {
            throw new \Exception("You cannot remove the coordinator from it's group");
        }
        $speaker->soap("AVTransport","SetAVTransportURI",array(
            "CurrentURI"            =>  "",
            "CurrentURIMetaData"    =>  "",
        ));
    }


}
