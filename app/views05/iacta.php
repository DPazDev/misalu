<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$idorden=$_REQUEST['iorddona'];
$fechaimpreso=date("d-m-Y");
$dia=date("d");
$mes=date("m");
$ano=date("Y");
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** busco los datos del donativo *** */
$datosdona=("select tbl_ordenes_donativos.id_orden_donativo,tbl_ordenes_donativos.id_titular,tbl_ordenes_donativos.id_beneficiario,
             tbl_ordenes_donativos.no_orden_donativo from tbl_ordenes_donativos where tbl_ordenes_donativos.id_orden_donativo=$idorden;");
$repuestadona=ejecutar($datosdona);
$lainfodeldona=assoc_a($repuestadona);
$titularid=$lainfodeldona[id_titular];
$beneficid=$lainfodeldona[id_beneficiario];
$laordeid=$lainfodeldona[id_orden_donativo];
$numorden=$lainfodeldona[no_orden_donativo];

if($beneficid==0){
   $datacliente=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,titulares where 
                  clientes.id_cliente=titulares.id_cliente and titulares.id_titular=$titularid");
   $repdatacliente=ejecutar($datacliente);
   $datpriclient=assoc_a($repdatacliente);
   $nomclie=$datpriclient[nombres];
   $apellid=$datpriclient[apellidos];
   $cedclie=$datpriclient[cedula];
   $eltitulaid=$elidtob;
   $elidbenef=0;
}else{
       $datacliente=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento,titulares.id_titular from clientes,titulares,beneficiarios 
                   where 
                  clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$beneficid and
                  beneficiarios.id_titular=titulares.id_titular");
       $repdatacliente=ejecutar($datacliente);
       $datpriclient=assoc_a($repdatacliente);
       $nomclie=$datpriclient[nombres];
       $apellid=$datpriclient[apellidos];
       $cedclie=$datpriclient[cedula];
       $eltitulaid=$datpriclient[id_titular];
       $fechanaci=$datpriclient[fecha_nacimiento];
       $elidbenef=$beneficid; 
       $edad=calcular_edad($fechanaci); 
        if($edad<18){
           $datacliente=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,titulares where 
                  clientes.id_cliente=titulares.id_cliente and titulares.id_titular=$eltitulaid");
          $repdatacliente=ejecutar($datacliente);
          $datpriclient=assoc_a($repdatacliente);
          $nomclie=$datpriclient[nombres];
          $apellid=$datpriclient[apellidos];
          $cedclie=$datpriclient[cedula];
        }
       
     } 
 $buscalodonado=("select tbl_insumos_ordenes_donativos.cantidad,tbl_insumos_ordenes_donativos.costo,tbl_insumos.insumo from
                  tbl_insumos_ordenes_donativos,tbl_insumos where
                  tbl_insumos_ordenes_donativos.id_insumo=tbl_insumos.id_insumo and
                  tbl_insumos_ordenes_donativos.id_orden_donativo=$idorden order by 
                  tbl_insumos.insumo;");  
 $repuslosdona=ejecutar($buscalodonado);
 $buscalodonado1=("select tbl_insumos_ordenes_donativos.cantidad,tbl_insumos_ordenes_donativos.costo,tbl_insumos.insumo from
                  tbl_insumos_ordenes_donativos,tbl_insumos where
                  tbl_insumos_ordenes_donativos.id_insumo=tbl_insumos.id_insumo and
                  tbl_insumos_ordenes_donativos.id_orden_donativo=$idorden order by 
                  tbl_insumos.insumo;");  
 $repuslosdona1=ejecutar($buscalodonado1);
?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> ACTA DE ENTREGA <?php echo $ano?>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">

</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">

</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">

</td>
</tr>


<?php
while($f_gastos=asignar_a($repuslosdona,NULL,PGSQL_ASSOC)){
$monto=$monto + $f_gastos[costo];
}

$monto_letra=numeros_a_letras($monto);
?>
<tr>
<td colspan=4 class="datos_cliente2" style="text-align: justify">

En el d&iacute;a de hoy, <?php echo $dia?> de <?php echo $mes?> del <?php echo "$monto_escrito ($fechaimpreso)"?>, la Empresa CLINISALUD M.P.S.A entrega a: <b> el Ciudadano (a):  <b> 
<?php echo utf8_encode("$nomclie $apellid"); ?></b> Venezolano (a), mayor de edad, 
Titular de la Cedula de Identidad Num <b> V-<?php echo cedula($cedclie);?></b> domiciliados en M&eacute;rida, 
se hace entrega formal:<b> 
</td>
</tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
	   <td class="tdtituloss">No.</td>
	   <td class="tdtituloss">Art&iacute;culo.</td>   
	  <td class="tdtituloss">Cantidad</td>   
	</tr>
	<?
	    $i=1;
	    while($replosproducto=asignar_a($repuslosdona1,NULL,PGSQL_ASSOC)){		
            echo"
            <tr> 
	             <td class=\"tdtituloss\">$i</td>
		     <td class=\"tdtituloss\">$replosproducto[insumo]</td>   
	            <td class=\"tdtituloss\">$replosproducto[cantidad]</td>   
	        </tr>";
		$i++;
		}	
	?>
</table>
<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=4 class="datos_cliente2" style="text-align: justify">

<?php echo utf8_encode("$nomclie $apellid"); ?>  
</b>valorado por un monto de <b><?php echo "$monto_letra BOLIVARES"; ?> 
(Bs.S <?php echo formato_montos($monto);?>)</b>  seg&uacute;n vigencia del contrato. 
</td>
</tr>
<tr>
<td colspan=4  class="datos_cliente">

<br>
</br>
</td>
</tr>
</tr>
<tr>
<td colspan=4  class="datos_cliente">

<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente2" style="text-align: justify">
 Conformes firman.
</td>
</tr>
<tr>
<td colspan=4  class="datos_cliente">

<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente2" style="text-align: justify">
REPRESENTANTE      LEGAL DE LA EMPRESA

</td>
</tr>
<tr>
<td colspan=4  class="datos_cliente">

<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente2" style="text-align: justify">
RECIBE CONFORME: 

</td>
</tr>

<tr>
<td colspan=4  class="datos_cliente">

<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente2" style="text-align: justify">
NOMBRE Y APELLIDO: <?php echo utf8_encode("$nomclie $apellid"); ?>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente2" style="text-align: justify">
C.I: <?php echo cedula($cedclie);?>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente2" style="text-align: justify">
CANTIDAD: (Bs.S <?php echo formato_montos($monto);?>)
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente2" style="text-align: justify">
FECHA: <?php echo $fechaimpreso?> 
</td>
</tr>
<tr>
<td colspan=4  class="datos_cliente">
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente2" style="text-align: justify">
<b>COLOCAR HUELLA.</b>
</td>
</tr>
<tr>
<td colspan=4  class="datos_cliente">
<br>
</br>
</br>
<br>
</br>
</td>
</tr>
</table>



