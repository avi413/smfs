<?php
$pathInPieces = explode('\\', getcwd());
$_ENV["MYENV"]=$pathInPieces[3];
if($_ENV["MYENV"] =="SFMS_DEV")
$_ENV["ENV_COLOR"]	= "PURPLE";
if($_ENV["MYENV"] =="SFMS")
$_ENV["ENV_COLOR"]	= "BLUE";
?>