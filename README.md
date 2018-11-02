# APRS-dashboard

This page is a very simple and small Dashboard which shows the vitality of one or more stations in the aprs.fi database.
The stations data are retrieved from the aprs.fi database using API, and the vitailty is determined by the time of the last heard packet.

# INSTALLATION

You must have php libraries and a web server installed.
Copy the content from Github to your web server folder, then edit config.php file with the following informations:
1) stations to observe
2) aprs.fi APIkey (you must have an account or aprs.fi)
3) timeout (default is 30min)

# NOTES
This script may have bugs and it's written without all the best programming rules. But it works for me.

# AUTHOR
Alfredo IZ7BOJ, iz7boj[--at--]gmail.com

# LICENSE
You can modify this program, but please give a credit to original author. Program is free for non-commercial use only.

Version: 0.1beta

