<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $losadmin=("select nombres,apellidos from admin order by nombres;");
   $tmparreglo=array();
          $i=0;  		    
          while ($columnas=pg_fetch_assoc($losadmin)){
				  $a="$columnas[nombres] $columnas[apellidos]";
			      $tmparreglo[$i]=$a;
				  $i++;  
			}   
			
	
			
	$aUsers=$tmparreglo;		
	
	
	
	$input = strtolower( $_GET['input'] );
	$len = strlen($input);
	$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 0;
	
	
	$aResults = array();
	$count = 0;
	
	if ($len)
	{
		for ($i=0;$i<count($aUsers);$i++)
		{
			// had to use utf_decode, here
			// not necessary if the results are coming from mysql
			//
			if (strtolower(substr(utf8_decode($aUsers[$i]),0,$len)) == $input)
			{
				$count++;
				$aResults[] = array( "value"=>htmlspecialchars($aUsers[$i]), "info"=>htmlspecialchars($aCantidad[$i]), "canti"=>htmlspecialchars($aInfo[$i]) );
			}
			
			if ($limit && $count==$limit)
				break;
		}
	}
	
	
	
	
	
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header ("Pragma: no-cache"); // HTTP/1.0
	
	sleep(2);
	
	if (isset($_REQUEST['json']))
	{
		header("Content-Type: application/json");
	
		echo "{\"results\": [";
		$arr = array();
		for ($i=0;$i<count($aResults);$i++)
		{
			$arr[] = "{\"value\": \"".$aResults[$i]['value']."\", \"info\":\"".$aResults[$i]['info']."\",\"canti\":\"".$aResults[$i]['canti']."\"}";
		}
		echo implode(", ", $arr);
		echo "]}";
	}
	
?>