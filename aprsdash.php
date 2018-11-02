<?php
/******************************************************************************************
This page is a very simple and small Dashboard which shows the vitality of one or more stations in the aprs.fi database.
The stations data are retrieved from the aprs.fi database using API, and the vitailty is determined by the time of the last heard packet.

IMPORTANT: you have to edit config.php in order to set:
1) stations to observe
2) aprs.fi APIkey (you must have an account or aprs.fi)
3) timeout (default is 30min)

This script may have bugs and it's written without all the best programming rules. But it works for me.
Author: Alfredo IZ7BOJ, iz7boj[--at--]gmail.com
You can modify this program, but please give a credit to original author. Program is free for non-commercial use only.

Version: 0.1beta
*******************************************************************************************/

include 'config.php'; //takes timeout,stations to observe and apikey from config file

$nowtime = time();

function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
}

?>
<html>
<body bgcolor="#606060">
<br><br>
<br><br>
<center>
<TABLE BGCOLOR= "silver" WIDTH="50%" BORDER=5 RULES="all" CELLPADDING=4 CELLSPACING=4 >
	<TR><TH colspan="4" align="center" bgcolor="#ffd700">APRS DIGI AND I-GATE DASHBOARD</TH></TR>
	<TR><TH colspan="4" align="center">Last update:
	<?php
	date_default_timezone_set('Europe/Rome');
	$date=date('m/d/Y h:i:s a', time());
	echo $date;
	?>
	</TH></TR>
	<TR>
		<TD BGCOLOR= "silver" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><B><I> Digi/I-gate Call </B></I></FONT> </TD>
		<TD BGCOLOR= "silver" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><B><I> Time since Last Heard</B></I></FONT> </TD>
		<TD BGCOLOR= "silver" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><B><I> Status</B></I></FONT> </TD>
		<TD BGCOLOR= "silver" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><B><I> Source</B></I></FONT> </TD>
	</TR>
<?php
	$json_url = "https://api.aprs.fi/api/get?name=".$stationsquery."&what=loc&apikey=".$apikey."&format=json";
	$json = file_get_contents( $json_url, 0, null, null );
	$json_output = json_decode( $json, true);
	$station_array = $json_output[ 'entries' ];
	foreach ( $station_array as $station ) {
	if ($nowtime-$station['lasttime']>$timeout) {
                $color="red";
                }
		else{
                $color="green";
                }
?>

	<TR>
                <TD BGCOLOR= "white" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><I> <?php echo $station['name']?></I></FONT> </TD>
                <TD BGCOLOR= "white" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><I> <?php echo secondsToTime($nowtime-$station['lasttime'])?></I></FONT> </TD>
		<TD BGCOLOR= "white" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="<?php echo $color?>"><B><I> <?php 
		if ($nowtime-$station['lasttime']<$timeout)	{
		echo "ALIVE";
		}
		else{
		echo "DEAD!";
		}
		?></B></I></FONT> </TD>
		<TD BGCOLOR= "white" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><B><I> <?php
                if (strpos($station['path'], 'qAC') !== false) {
		echo "TCP-IP";
		}
		else{
                echo "RF";
                }
		?></B></I></FONT> </TD>
        </TR>

<?php
}
?>
	</CENTER>
	</TABLE>
<br><br>

<iframe src="https://www.aprsdirect.com/sidlist/1140214,1253491,1286527,1312224,1639742,1667435,82449,840298,1641450/time/60" width="800" height="400">Alternative text for browsers that do not understand IFrames.</iframe>

<hr>
APRS Vitality Dashboard V0.1 Beta by IZ7BOJ Alfredo<br>
Email contact: iz7boj [at] gmail.com<br>
<!-- Get your own at:--> 
<a href="credits.html">Credits</a><br>
<a href="help.html">Help</a>
<br><br>
</body>
</html>
