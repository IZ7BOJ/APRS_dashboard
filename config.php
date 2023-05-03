<?php
/******************************************************************************************
This page is a very simple and small Dashboard which shows the vitality of one or more stations in the aprs.fi database.
The station data are retrieved from the aprs.fi database using API, and thevitailty is determined by the time of the last heard packet.

IMPORTANT: you have to edit config.php in order to set:
1) stations to observe with relative sysops
2) aprs.fi APIkey (you must have an account or aprs.fi
3) timeout (default is 30min)

This script may have a lot of bugs, problems and it's written in very non-efficient way without a lot of good programming rules. But it works for me.
Author: Alfredo IZ7BOJ, iz7boj[--at--]gmail.com
You can modify this program, but please give a credit to original author. Program is free for non-commercial use only.

Version: 0.1beta
*******************************************************************************************/

//timeout in seconds for the last heard packet
$timeout=3600;
//stations to observe. declare with ssid, comma separated and without spaces!Keep double quotes
$stationsquery="Station1,Station2,Station3";
//Sysops of stations previously declared, comma separated and without spaces!Keep double quotes
//You must declare 1 sysop for every station
$sysop="Sysop1,Sysop2,Sysop3";
//declare API Key. You must be registered on aprs.fi
$apikey="put_your_AP_here"

?>
