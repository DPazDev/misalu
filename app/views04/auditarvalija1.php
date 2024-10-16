<?
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$estavalija = $_REQUEST[festadovalija];
if ($estavalija == 1){
   $estadfac ="Creada";
   $mensfec = "Fecha Creada";
}else{
	 if($estavalija == 2){
		 $estadfac ="Enviada";
		 $mensfec = "Fecha Enviada";
		 }else{
			   if($estavalija == 3){
				   $estadfac ="Recibida";
				   $mensfec = "Fecha Recibida";
				}else{
					  if($estavalija == 4){
						  $estadfac ="Entregada";
						  $mensfec = "Fecha Entregada";
					  }else{
						  $estadfac ="Devuelta";
						  $mensfec = "Fecha Devuelta";
						  }
					}
			 }
	}
$buscalasvalijas = ("select tbl_valija_historial.id_valija,tbl_valija_historial.fecha_creada,tbl_valija.id_serie,
tbl_valija.serialvalija,entes.id_ente,entes.nombre,tbl_series.nombre as oficina,tbl_series.nomenclatura,admin.nombres,admin.apellidos 
from
tbl_valija_historial,tbl_valija,entes,tbl_series,admin
where
tbl_valija_historial.estado_factura = $estavalija and
tbl_valija_historial.id_valija = tbl_valija.id_valija and
tbl_valija.id_ente =  entes.id_ente and
tbl_valija.id_serie = tbl_series.id_serie and
tbl_valija.id_admin = admin.id_admin and
tbl_valija.estatus = 0
order by 
tbl_valija.fechacreada");
echo $buscalasvalijas;
$repbuscalasvalijas = ejecutar($buscalasvalijas);
$cuantasvalija = num_filas($repbuscalasvalijas);
if($cuantasvalija == 0){
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">No existen Valijas en el estado (<?php echo $estadfac?>)</td>
     </tr>
</table>     
<?php }
else{ ?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Valija en el Estado (<?php echo $estadfac?>) </td>
     </tr>
</table>  
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Creada por.</th>
                 <th class="tdtitulos"><?php echo $mensfec?></th>
                 <th class="tdtitulos">Serial Valija.</th>  
                 <th class="tdtitulos">Serie.</th>
                 <th class="tdtitulos">Ente.</th>
                 <th class="tdtitulos">Opci&oacute;n.</th>
              </tr>
  
 <?php 

       while($lasvali = asignar_a($repbuscalasvalijas,NULL,PGSQL_ASSOC)){
	   if ($estavalija == 1){
		$verestadofinal = ("select tbl_valija_historial.estado_factura from tbl_valija_historial where 
		tbl_valija_historial.id_valija=$lasvali[id_valija] order by tbl_valija_historial.estado_factura desc limit 1");
		$repverestado = ejecutar($verestadofinal);
		$dataestado = assoc_a($repverestado);
		$elestadoes = $dataestado[estado_factura];
		if($elestadoes == 1){
			$anularboton = "<label title=\"Anular Valija\" class=\"boton\" style=\"cursor:pointer\" onclick=\"AnularValija($lasvali[id_valija], '$lasvali[serialvalija]', '$lasvali[nombre]')\" >Anular</label>";
		}
        $botonera ="<label title=\"Procesar Valija\" class=\"boton\" style=\"cursor:pointer\" onclick=\"ProcesarValija($lasvali[id_valija], $estavalija, $lasvali[id_serie], '$lasvali[serialvalija]' )\" >Procesar</label> $anularboton";
      }else{
	    if($estavalija == 2){
		 $estadfac ="Enviada";
		 $botonera ="<label title=\"Procesar Valija\" class=\"boton\" style=\"cursor:pointer\" onclick=\"ProcesarValija($lasvali[id_valija], $estavalija, $lasvali[id_serie], '$lasvali[serialvalija]' )\" >Procesar</label>";
		 }else{
			   if($estavalija == 3){
				   $estadfac ="Recibida";
				   $botonera ="<label title=\"Procesar Valija\" class=\"boton\" style=\"cursor:pointer\" onclick=\"ProcesarValija($lasvali[id_valija], $estavalija, $lasvali[id_serie], '$lasvali[serialvalija]' )\" >Procesar</label>";
				}else{
					  if($estavalija == 4){
						  $estadfac ="Entregada";
						  $botonera ="<label title=\"Procesar Valija\" class=\"boton\" style=\"cursor:pointer\" onclick=\"ProcesarValija($lasvali[id_valija], $estavalija, $lasvali[id_serie], '$lasvali[serialvalija]' )\" >Procesar</label>";
					  }else{
						  $estadfac ="Devuelta";
						  }
					}
			 }
	  }	   
 ?>	  
 <tr>
        <td class="tdcampos"><?php echo "$lasvali[nombres] $lasvali[apellidos]";?></td>      				    
        <td class="tdcampos"><?php echo $lasvali['fecha_creada'];?></td>      				    
        <td class="tdcampos"><?php echo $lasvali[serialvalija];?></td>      				    
        <td class="tdcampos"><?php echo "$lasvali[nomenclatura] - $lasvali[oficina]";?></td>      				    
        <td class="tdcampos"><?php echo $lasvali[nombre];?></td>  
        <td class="tdcampos"><?php echo $botonera;?></td>       				    
   </tr>
   <?php }?>
</table> 
<div align=center> 
    <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
</div>
<?php }?>
