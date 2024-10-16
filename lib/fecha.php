    <?php
 //      echo "<br>";
$datetime = new DateTime('NOW');
//  echo "<br> fecha actual";
$datetime->format('Y-m-d H:i:s') . "\n";
$la_time = new DateTimeZone('America/Caracas');
$datetime->setTimezone($la_time);
$FechaServer=$datetime->format('Y-m-d');
$HoraServer=$datetime->format('h:i:s');
/*echo "<br>";
echo "FECHA SERVIDOR:",$FechaServer;
echo "<br>";
echo "FECHA SERVIDOR:",$HoraServer;

  echo "<br>";
echo 'TimeZonePHP default: ', date_default_timezone_get();   
*/
    ?>
