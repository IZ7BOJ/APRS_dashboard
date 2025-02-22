<?php
/******************************************************************************************
This page is a very simple and small Dashboard which shows the vitality of one or more stations in the aprs.fi database.
The stations data are retrieved from the aprs.fi database using API, and the vitailty is determined by the time of the last heard packet.

IMPORTANT: you have to edit config.php in order to set:
1) stations to observe
2) aprs.fi APIkey (you must have an account or aprs.fi)
3) timeout (default is 30min)

This code may have bugs and it's written without all the best programming rules. But it works for me.
Author: Alfredo IZ7BOJ, iz7boj[--at--]gmail.com
You can modify this program, but please give a credit to original author. Program is free for non-commercial use only.

Version: 0.1beta
*******************************************************************************************/

include 'config.php'; //takes timeout,stations to observe and apikey from config file

$nowtime = time();
date_default_timezone_set('Europe/Rome');

function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
}

$sysop_arr = explode (",", $sysop);
$stations_arr = explode (",", $stationsquery);
if (count($stations_arr)!==count($sysop_arr)){
	echo '<font color="red" size="6"><b>Error! Number of stations differ from number of sysops declared in config.php!</b></font>';
        echo '<br><br>Please check carefully config.php';
        echo '<br><br><b>Pointless to continue.</b>';
        die();
        }
$i=0;

?>

<html lang="en">
<body bgcolor="#606060">
<br><br><br><br>
<center>

<TABLE BGCOLOR= "silver" WIDTH="50%" BORDER=5 RULES="all" CELLPADDING=5 CELLSPACING=4 >
	<TR><TH colspan="6" align="center" bgcolor="#ffd700">APRS DIGI AND I-GATE DASHBOARD</TH></TR>
	<TR><TH colspan="6" align="center">Last update:<?php echo(date('m/d/Y h:i:s a', time()))?></TH></TR>
	<TR>
		<TD BGCOLOR= "silver" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><B><I>Digi/I-gate Call</B></I></FONT> </TD>
		<TD BGCOLOR= "silver" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><B><I>Time since Last Heard</B></I></FONT> </TD>
		<TD BGCOLOR= "silver" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><B><I>Status</B></I></FONT> </TD>
		<TD BGCOLOR= "silver" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><B><I>Source</B></I></FONT> </TD>
		<TD BGCOLOR= "silver" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><B><I>SYSOP</B></I></FONT> </TD>
	</TR>
	<?php
	//added on 19/10/2021 to fix openssl error. https://stackoverflow.com/questions/26148701/file-get-contents-ssl-operation-failed-with-code-1-failed-to-enable-crypto
	$arrContextOptions=array(
	    "ssl"=>array(
        	"verify_peer"=>false,
	        "verify_peer_name"=>false,
    		),
	);

	$stations=[];

	for ($n=0; $n<ceil(count($stations_arr)/20);$n++) {
		$stations=implode(",",array_slice($stations_arr,20*$n,20*(1+$n)));
		$json_url = "https://api.aprs.fi/api/get?name=".$stationsquery."&what=loc&apikey=".$apikey."&format=json";
		//$json = file_get_contents( $json_url, 0, null, null );
		$json = file_get_contents( $json_url, false, stream_context_create($arrContextOptions));
		$json_output = json_decode( $json, true);
		$station_array = $json_output[ 'entries' ];
		foreach ( $station_array as $station ) {
			if (strtoupper($stations_arr[$i])!==$station['name']) {
				$i++; //station not present in aprs.fi database. Jump to next syso array element to avoid misalignment
			}
			($nowtime-$station['lasttime']>$timeout) ? $color="red" : $color="green";
	?>

	<TR>
                <TD BGCOLOR= "white" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><I><a target="_blank" href="https://aprs.fi/?call=<?php echo $station['name']?>"</a><?php echo $station['name']?></I></FONT> </TD>
                <TD BGCOLOR= "white" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><I> <?php echo secondsToTime($nowtime-$station['lasttime'])?></I></FONT> </TD>
		<TD BGCOLOR= "white" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="<?php echo $color?>"><B><I> <?php
		echo ($nowtime-$station['lasttime']<$timeout) ? "ALIVE" : "DEAD!";
		?></B></I></FONT> </TD>
		<TD BGCOLOR= "white" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"><B><I>
		<?php
                echo (strpos($station['path'], 'qAC') !== false) ? "TCP-IP" : "RF";
		?></B></I></FONT> </TD>
		<TD BGCOLOR= "white" ALIGN=CENTER> <FONT FACE="verdana" SIZE="2" COLOR="black"> <?php echo strtoupper($sysop_arr[$i]) ?></FONT> </TD>
		</B></I></FONT> </TD>
	</TR>
		<?php
			$i++;
		} //closes foreach

	} //closes for

	?>
	</CENTER>
	</TABLE>
<br><br>

<iframe src="https://aprs-map.info/?snamelist=<?php echo strtoupper($stationsquery) ?>"width="800" height="400">Alternative text for browsers that do not understand IFrames.</iframe>

<hr>
APRS Vitality Dashboard V0.1 Beta by IZ7BOJ Alfredo<br>
Email contact: iz7boj [at] gmail.com<br>
<!-- Get your own at:-->
<a href="credits.html">Credits</a><br>
<a href="help.html">Help</a><br>
<br><br>
</body>
</html>
