<?php
include ("../../lib/jfunciones.php");
include_once ("../../lib/Excel/reader.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$formapago         =  $_REQUEST['elformapago'];
$formtiptarjeta    =  $_REQUEST['eltipotarjeta'];
$formfechapago     =  $_REQUEST['elfechpago'];
$formbanco         =  $_REQUEST['elnbanco'];
$formelcheque      =  $_REQUEST['elnumcheque'];
$formelestado      =  $_REQUEST['elestadfact'];
$nombredarchivo    =  $_REQUEST['elnombrearchivo'];
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();
// Set output Encoding.
$data->setOutputEncoding('CP1251');
$filename = "$nombredarchivo";
$error=0;
if (file_exists("../../files/$filename")) {
    $data->read("../../files/$filename");
   
} else { 
	$error=1;
	?>
	<br>
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">No existe el Archivo: <?php echo "($filename)"?></td>  
     </tr>
</table>
<?php }?>
<?php
   if($error == 0){
       for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 2; $j <= $data->sheets[0]['numCols']; $j++) {		
		        $nfactura=$data->sheets[0]['cells'][$i][1];	
		        $chetrafer=$data->sheets[0]['cells'][$i][2];
                $fechapago=$data->sheets[0]['cells'][$i][3];
                list($eldia,$elmes,$elano) = explode("/",$fechapago);
                $eldia = substr($eldia,0,2);
                $elmes = substr($elmes,0,2);
                $elano = substr($elano,0,2);
                $diadpago = "20$elano/$elmes/$eldia";
                $tippago=$data->sheets[0]['cells'][$i][4];
                $montopagar=$data->sheets[0]['cells'][$i][5];
                $impslr=$data->sheets[0]['cells'][$i][6];
                $montoneto=$data->sheets[0]['cells'][$i][7];
                $monto= str_replace(".",'.',$montoneto);
                $seriefact= strtoupper($data->sheets[0]['cells'][$i][8]);	
                
	}
	if(!empty($nfactura)){
	  $buscaridfactura =  ("select tbl_series.id_serie from tbl_series where nomenclatura = '$seriefact'");
      $rbuscarid       =  ejecutar($buscaridfactura);
      $dataidfactura   =  assoc_a($rbuscarid);
      $laiddefactura =  $dataidfactura[id_serie];
      $mod_factura="update 
									tbl_facturas 
							set  
									id_estado_factura='$formelestado',
									id_banco='$formbanco',
									numero_cheque='$formelcheque',
									condicion_pago='$formapago'
							where 
									tbl_facturas.id_serie 	='$laiddefactura' and
									tbl_facturas.numero_factura 	 = '$nfactura'";
									
	$fmod_factura=ejecutar($mod_factura);
  
   }
  }
  //Guardar los datos en la tabla logs;
$mensaje="El usuario $elus con id_admin=$elid actualizo las facturas por lote con el archivo nombre de archivo ($filename)";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Se ha actualizado exitosamente las facturas por lote del Archivo: <?php echo "($filename)"?></td>  
     </tr>
   </table>
<?php }?>

