<?php
include ("../../lib/jfunciones.php");
sesion();
$id_admin= $_SESSION['id_usuario_'.empresa];
$lafacvalija = $_REQUEST[fcadena];
$arreglovalija = explode(",",$lafacvalija);
$cuantfacvalija = count($arreglovalija);
$valijacoment = strtoupper($_REQUEST[fcomentario]);
$estavalija = $_REQUEST[festvalifact];
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$f=1;
$paso=1;
if ($estavalija == 4){
	     $loscometfac = $_REQUEST[fcometfa];
	     $arreglocoment = explode(",",$loscometfac);
	     
		for($i = 0; $i <=$cuantfacvalija; $i++){
			if(!empty($arreglocoment[$f])){
			  $comentfact[$f] = strtoupper($arreglocoment[$f]);
			}
			
	     list($nfactu[$f],$laidserie[$f]) = explode("@",$arreglovalija[$i]);
		if(!empty($nfactu[$f])){
			if($paso == 1){
				$eliddlavalija = $laidserie[$f];
				$cambiaesta="numero_factura='$nfactu[$f]'";
				$cambiarestadfac =  "update tbl_valija_factura set estado_factura=5,comentario='$comentfact[$f]' where ($cambiaesta) and id_valija=$eliddlavalija";
                //comenzamos a guardar los cambios de las facturas en las valijas
                $estafacvalija = ejecutar($cambiarestadfac);
				
			}else{
				  $cambiaesta="numero_factura='$nfactu[$f]'";
				  $cambiarestadfac =  "update tbl_valija_factura set estado_factura=5,comentario='$comentfact[$f]' where ($cambiaesta) and id_valija=$eliddlavalija";
                //comenzamos a guardar los cambios de las facturas en las valijas
                $estafacvalija = ejecutar($cambiarestadfac);
				}
		$paso++;
		$f++;
		}
	}
   
}else{	
	
	for($i = 0; $i <=$cuantfacvalija; $i++){
		list($nfactu[$f],$laidserie[$f]) = explode("@",$arreglovalija[$i]);
		if(!empty($nfactu[$f])){
			if($paso == 1){
				$eliddlavalija = $laidserie[$f];
				$cambiaesta="numero_factura='$nfactu[$f]'";
			}else{
				  $cambiaesta="$cambiaesta or numero_factura='$nfactu[$f]'";
				}
		$paso++;
		$f++;
		}
	}
}
if ($estavalija == 1){
$mensavalija= "Enviada";	
$cambiarestadfac =  "update tbl_valija_factura set estado_factura=2 where ($cambiaesta) and id_valija=$eliddlavalija";
//comenzamos a guardar los cambios de las facturas en las valijas
$estafacvalija = ejecutar($cambiarestadfac);
$historialvalija = ("insert into tbl_valija_historial(id_valija,id_admin_crea,id_admin_edit,comentario,estado_factura) 
                        values($eliddlavalija,$id_admin,0,'$valijacoment',2);");
$rephistorialvalija = ejecutar($historialvalija); 
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha cambiado exitosamente el estado de la valija a Enviada, con id_valija=$eliddlavalija";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$id_admin','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
}
if ($estavalija == 2){
$mensavalija= "Recibida";	
$pesrecibe = strtoupper($_REQUEST[fqrecibe]);	
$cambiarestadfac =  "update tbl_valija_factura set estado_factura=3 where ($cambiaesta) and id_valija=$eliddlavalija";
//comenzamos a guardar los cambios de las facturas en las valijas
$estafacvalija = ejecutar($cambiarestadfac);
$historialvalija = ("insert into tbl_valija_historial(id_valija,id_admin_crea,id_admin_edit,comentario,estado_factura,recibevalija) 
                        values($eliddlavalija,$id_admin,0,'$valijacoment',3,'$pesrecibe');");
$rephistorialvalija = ejecutar($historialvalija); 
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha cambiado exitosamente el estado de la valija a Recibida, con id_valija=$eliddlavalija";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$id_admin','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
}
if ($estavalija == 3){
$mensavalija= "Entregada";	
$pesrecibe = strtoupper($_REQUEST[fqrecibe]);	
$pesentrega = strtoupper($_REQUEST[fqentrega]);	
$cambiarestadfac =  "update tbl_valija_factura set estado_factura=4 where ($cambiaesta) and id_valija=$eliddlavalija";
//comenzamos a guardar los cambios de las facturas en las valijas
$estafacvalija = ejecutar($cambiarestadfac);
$historialvalija = ("insert into tbl_valija_historial(id_valija,id_admin_crea,id_admin_edit,comentario,estado_factura,recibeexterno,recibevalija) 
                        values($eliddlavalija,$id_admin,0,'$valijacoment',4,'$pesrecibe','$pesentrega');");
$rephistorialvalija = ejecutar($historialvalija); 
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha cambiado exitosamente el estado de la valija a Entregada, con id_valija=$eliddlavalija";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$id_admin','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
}
if ($estavalija == 4){
$mensavalija= "Devuelta";	

$historialvalija = ("insert into tbl_valija_historial(id_valija,id_admin_crea,id_admin_edit,comentario,estado_factura) 
                        values($eliddlavalija,$id_admin,0,'$valijacoment',5);");
$rephistorialvalija = ejecutar($historialvalija); 
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha cambiado exitosamente el estado de la valija a Devuelta, con id_valija=$eliddlavalija";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$id_admin','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
}
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Se ha cambiado Exitosamente el Estado de la Valija a (<?php echo $mensavalija?>)</td>
     </tr>
</table>  
