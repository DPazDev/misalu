<?php
include ("../../lib/jfunciones.php");
sesion();
$numfactura = $_REQUEST[ffactura];
$numserie   = $_REQUEST[fserie];
$verestadfactu = ("select tbl_valija_factura.estado_factura,tbl_valija_factura.id_valija  
from 
tbl_valija_factura,tbl_valija
where
tbl_valija_factura.numero_factura='$numfactura' and
tbl_valija_factura.id_valija = tbl_valija.id_valija and
tbl_valija.id_serie = $numserie");
$repverestafact = ejecutar($verestadfactu);
$dataestafact = assoc_a($repverestafact);
$estafacvalija = $dataestafact[estado_factura];
$laidvalija    = $dataestafact[id_valija];

$datosfactura = ("select tbl_facturas.fecha_emision,tbl_facturas.numero_factura,tbl_facturas.numcontrol,
admin.nombres,admin.apellidos,tbl_series.nomenclatura,tbl_series.nombre as serienombre,entes.nombre,
procesos.id_titular,procesos.id_beneficiario,sum(tbl_procesos_claves.monto)
from
tbl_facturas,admin,tbl_series,tbl_procesos_claves,entes,titulares,procesos
where
tbl_facturas.numero_factura='$numfactura' and
tbl_facturas.id_serie=$numserie and
tbl_facturas.id_serie = tbl_series.id_serie and
tbl_facturas.id_admin = admin.id_admin and
tbl_facturas.id_factura = tbl_procesos_claves.id_factura and
tbl_procesos_claves.id_proceso = procesos.id_proceso and
procesos.id_titular = titulares.id_titular and
titulares.id_ente = entes.id_ente
group by 
tbl_facturas.fecha_emision,tbl_facturas.numero_factura,tbl_facturas.numcontrol,
admin.nombres,admin.apellidos,tbl_series.nomenclatura,tbl_series.nombre,entes.nombre,procesos.id_titular,procesos.id_beneficiario");
$repdatosfactura = ejecutar($datosfactura);
$datosgenerfactura = assoc_a($repdatosfactura);
$facttitular = $datosgenerfactura[id_titular];
$factbenefic = $datosgenerfactura[id_beneficiario];

$datostitular = ("select clientes.nombres , clientes.apellidos, clientes.cedula 
from
titulares,clientes
where
titulares.id_titular = $facttitular and
titulares.id_cliente = clientes.id_cliente ");
$repdatostitular = ejecutar($datostitular);
$dattostitular = assoc_a($repdatostitular);
$nomcompletotitu = "$dattostitular[nombres] $dattostitular[apellidos]";
$cedultitu       = $dattostitular[cedula];

if($factbenefic>0){
   $datosbenif = ("select clientes.nombres , clientes.apellidos, clientes.cedula 
from
beneficiarios,clientes
where
beneficiarios.id_beneficiario = $factbenefic and
beneficiarios.id_cliente = clientes.id_cliente ");
$repdatosbenif = ejecutar($datosbenif);
$dattosbenefi = assoc_a($repdatosbenif);
$nomcompletobenefi = "$dattosbenefi[nombres] $dattosbenefi[apellidos]";
$cedulbenefi       = $dattosbenefi[cedula];
}

$buscotofactvalija = ("select tbl_valija_historial.comentario, tbl_valija_historial.estado_factura,
tbl_valija_historial.fecha_creada,tbl_valija_historial.recibevalija as usuinter,
tbl_valija_historial.recibeexterno as usuexter, admin.nombres,admin.apellidos,tbl_valija.serialvalija
from 
tbl_valija_historial,admin,tbl_valija
where 
tbl_valija_historial.id_valija=$laidvalija and 
tbl_valija_historial.estado_factura <=$estafacvalija and
tbl_valija_historial.id_admin_crea = admin.id_admin and
tbl_valija_historial.id_valija = tbl_valija.id_valija
order by 
tbl_valija_historial.estado_factura");
$repbuscotofactvalija = ejecutar($buscotofactvalija);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Datos Basicos de la Factura</td>
     </tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
    <tr>
        <td class="tdtitulos">N&uacute;mero de Factura</td>      				    
        <td class="tdcampos"><?php echo $datosgenerfactura['numero_factura'];?></td>      				             				    
   </tr>
   <tr>
        <td class="tdtitulos">N&uacute;mero de Control</td>      				    
        <td class="tdcampos"><?php echo $datosgenerfactura['numcontrol'];?></td>      				             				    
   </tr>
   <tr>
        <td class="tdtitulos">Serie de Factura </td>      				    
        <td class="tdcampos"><?php echo " ($datosgenerfactura[nomenclatura]) - $datosgenerfactura[serienombre] ";?></td>      				             				    
   </tr>
   <tr>
        <td class="tdtitulos">Fecha Emisi&oacute;n</td>      				    
        <td class="tdcampos"><?php echo $datosgenerfactura[fecha_emision];?></td>      				             				    
   </tr>
   <tr>
        <td class="tdtitulos">Factura Generada por:</td>      				    
        <td class="tdcampos"><?php echo " $datosgenerfactura[nombres] $datosgenerfactura[apellidos] ";?></td>      				             				    
   </tr>
   <tr>
        <td class="tdtitulos">Monto Factura:</td>      				    
        <td class="tdcamposr"><?php echo montos_print($datosgenerfactura[sum]);?></td>      				             				    
   </tr>
   <tr>
        <td class="tdtitulos">Titular:</td>      				    
        <td class="tdcampos"><?php echo $nomcompletotitu;?></td>      				             				    
   </tr>
   <tr>
        <td class="tdtitulos">C&eacute;dula:</td>      				    
        <td class="tdcampos"><?php echo $cedultitu;?></td>      				             				    
   </tr>
   <?php if($factbenefic>0){?>
   <tr>
        <td class="tdtitulos">Beneficiario:</td>      				    
        <td class="tdcampos"><?php echo $nomcompletobenefi;?></td>      				             				    
   </tr>
   <tr>
        <td class="tdtitulos">C&eacute;dula:</td>      				    
        <td class="tdcampos"><?php echo $cedulbenefi;?></td>      				             				    
   </tr>
   <?php }?>
   <tr>
        <td class="tdtitulos">Ente:</td>      				    
        <td class="tdcampos"><?php echo $datosgenerfactura['nombre'];?></td>      				             				    
   </tr>
</table>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Historial Factura en Valija</td>
     </tr>
</table>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Serial Valija.</th>
                 <th class="tdtitulos">Fecha Creada.</th>
                 <th class="tdtitulos">Estado Valija.</th>
                 <th class="tdtitulos">Realizado por.</th>  
                 <th class="tdtitulos">Comentario.</th>
                 <th class="tdtitulos">Usuario Interno.</th>
                 <th class="tdtitulos">Usuario Externo.</th>
              </tr>
<?php 

       while($lasvali = asignar_a($repbuscotofactvalija,NULL,PGSQL_ASSOC)){
	  $estavalija = $lasvali[estado_factura];	   
	   if ($estavalija == 1){
		$estadfac ="Creada";   
      }else{
	    if($estavalija == 2){
		 $estadfac ="Enviada";
		 }else{
			   if($estavalija == 3){
				   $estadfac ="Recibida";
				}else{
					  if($estavalija == 4){
						  $estadfac ="Entregada";
					  }else{
						  $estadfac ="Devuelta";
						  }
					}
			 }
	  }	   
 ?>	  
  <tr>
        <td class="tdcampos"><?php echo $lasvali[serialvalija];?></td>  
        <td class="tdcampos"><?php echo $lasvali[fecha_creada];?></td>      				    
        <td class="tdcampos"><?php echo $estadfac;?></td>      				    
        <td class="tdcampos"><?php echo "$lasvali[nombres] $lasvali[apellidos]";?></td>      				    
        <td class="tdcampos"><?php echo $lasvali[comentario];?></td>      				    
        <td class="tdcampos"><?php echo $lasvali[usuinter];?></td>  
        <td class="tdcampos"><?php echo $lasvali[usuexter];?></td>       				    
   </tr>
   <tr>
        <td class="tdcampos"><HR></td>          
        <td class="tdcampos"><HR></td>          
        <td class="tdcampos"><HR></td>          
        <td class="tdcampos"><HR></td>          
        <td class="tdcampos"><HR></td>          
        <td class="tdcampos"><HR></td>          
        <td class="tdcampos"><HR></td>          
   </tr>
   <?php }?>
</table> 
<?php 
  if($estafacvalija == 5){
  $buscomenfactura = ("select tbl_valija_factura.comentario,tbl_valija_historial.fecha_creada,admin.nombres,
                              admin.apellidos,tbl_valija.serialvalija 
from
admin,tbl_valija_factura,tbl_valija_historial,tbl_valija
where
tbl_valija_historial.estado_factura = $estafacvalija and
tbl_valija_historial.estado_factura = tbl_valija_factura.estado_factura and
tbl_valija_historial.id_valija = tbl_valija_factura.id_valija and
tbl_valija_factura.id_valija = tbl_valija.id_valija and
tbl_valija_historial.id_admin_crea = admin.id_admin and
tbl_valija_factura.numero_factura= '$numfactura' ");	
 $repbuscomenfactura = ejecutar($buscomenfactura);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Historial Factura Devuelta</td>
     </tr>
</table>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Serial Valija.</th>
                 <th class="tdtitulos">Fecha Creada.</th>
                 <th class="tdtitulos">Realizado por.</th>  
                 <th class="tdtitulos">Comentario.</th>
              </tr>
   
<?php 

       while($lacomend = asignar_a($repbuscomenfactura,NULL,PGSQL_ASSOC)){
?>
       <tr>
        <td class="tdcampos"><?php echo $lacomend[serialvalija];?></td>  
        <td class="tdcampos"><?php echo $lacomend[fecha_creada];?></td>      				    
        <td class="tdcampos"><?php echo "$lacomend[nombres] $lacomend[apellidos]";?></td>      				    
        <td class="tdcampos"><?php echo $lacomend[comentario];?></td>      				    
       </tr>
       <?php }?>	
</table>        	             
<?php }?>
