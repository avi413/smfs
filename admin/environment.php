<?php
$pathInPieces = explode('\\', getcwd());
$_ENV["MYENV"]=$pathInPieces[3];

?>