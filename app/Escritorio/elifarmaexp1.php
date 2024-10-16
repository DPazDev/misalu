<?
include ("../../lib/jfunciones.php");
sesion();
$elprocesoes=$_POST['procesoid'];
$operacion=$_POST['elopera'];

$buscolosdatos=("select procesos.id_titular,procesos.id_beneficiario,procesos.fecha_creado,
                                procesos.comentarios 
                                  from procesos where procesos.id_proceso=$elprocesoes;");
$repbuscardatos=ejecutar($buscolosdatos);
$buscoengastotb=("select * from gastos_t_b where gastos_t_b.id_proceso=$elprocesoes");
$repbuscoengastb=ejecutar($buscoengastotb);
$buscoengastotbprov=("select clinicas_proveedores.nombre 
                          from clinicas_proveedores,gastos_t_b,proveedores 
                    where gastos_t_b.id_proceso=$elprocesoes and gastos_t_b.id_proveedor=proveedores.id_proveedor and 
                      proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor limit 1");
$repbuscoengastbprov=ejecutar($buscoengastotbprov);
$datagastotbprov=assoc_a($repbuscoengastbprov);
$nombreprovee=$datagastotbprov['nombre'];
$databuscpro=assoc_a($repbuscardatos);
$elidtitular=$databuscpro['id_titular'];
$elidbenef=$databuscpro['id_beneficiario'];
$fechacreadopro=$databuscpro['fecha_creado'];
$elcomentario=$databuscpro['comentarios'];
list($ano,$mes,$dia)=split("-",$fechacreadopro,3);
$esunbeni=0;
if($elidbenef>0){
	$buscarnbeni=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,beneficiarios where 
                              clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$elidbenef");
	$repbuscarbeni=ejecutar($buscarnbeni);						  
	$databeni=assoc_a($repbuscarbeni);
	$nombrecompletobeni="$databeni[nombres]  $databeni[apellidos]";
	$cedulabeni=$databeni['cedula'];
	$buscoeltitu=("select clientes.nombres,clientes.apellidos,clientes.cedula,entes.nombre 
                            from clientes,titulares,entes where 
                              clientes.id_cliente=titulares.id_cliente and 
                         titulares.id_titular=$elidtitular and titulares.id_ente=entes.id_ente");
	$repbuscoeltitu=ejecutar($buscoeltitu);		
	$datatitul=assoc_a($repbuscoeltitu);
	$nombrecompletotitu="$datatitul[nombres]  $datatitul[apellidos]";
	$cedulatitul=$datatitul['cedula'];
	$entetitu=$datatitul['nombre'];
	$esunbeni=1;
	}else{
	$buscoeltitu=("select clientes.nombres,clientes.apellidos,clientes.cedula,entes.nombre 
                                from clientes,titulares,entes where 
                              clientes.id_cliente=titulares.id_cliente and titulares.id_titular=$elidtitular and titulares.id_ente=entes.id_ente");
	$repbuscoeltitu=ejecutar($buscoeltitu);		
	$datatitul=assoc_a($repbuscoeltitu);
	$nombrecompletotitu="$datatitul[nombres]  $datatitul[apellidos]";
	$cedulatitul=$datatitul['cedula'];
	$entetitu=$datatitul['nombre'];
	}
?>
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="tdtitulos">

</td>
<td colspan=1 class="tdtitulos">

</td>
</tr>
<tr>
<td colspan=1 class="tdtitulos">
Rif: J-31180863-9
</td>
<td colspan=2 class="tdtitulos">

</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="tdcampos">
<?php echo "ORDEN DE MEDICAMENTO  Num. $elprocesoes" ?>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="tdtitulos">
DATOS DEL CLIENTE
</td>
</tr>
<tr>
<td colspan=1 class="tdtitulos">
TITULAR
</td>
<td colspan=1 class="tdcampos">
<?php echo "$nombrecompletotitu;" ?>

</td>
<td colspan=1 class="tdtitulos">
CEDULA
</td>
<td colspan=1 class="tdcampos">
<?php echo "$cedulatitul" ?>

</td>
</tr>
<?php 
if ($esunbeni==1 )
{?>
<tr>
<td colspan=1 class="tdtitulos">
BENEFICIARIO
</td>
<td colspan=1 class="tdcampos">
<?php echo "$nombrecompletobeni" ?>

</td>
<td colspan=1 class="tdtitulos">
CEDULA
</td>
<td colspan=1 class="tdcampos">
<?php echo "$cedulabeni" ?>

</td>
</tr>
<?php
}
?>
<tr>
<td colspan=1 class="tdtitulos">
ENTE
</td>
<td colspan=3 class="tdcampos">
<?php echo "$entetitu" ?>

</td>

</tr>

<tr>
<td colspan=4 class="tdtitulos">
DATOS DE LA ORDEN
</td>
</tr>
<tr>
<td colspan=1 class="tdtitulos">
FECHA EMISION
</td>
<td colspan=1 class="tdcampos">
<?php echo " $dia-$mes-$ano" ?>

</td>
</tr>


<tr>
<td colspan=1  class="tdtitulos">
PROVEEDOR:
</td>
<td colspan=1 class="tdcampos">
<?php echo "$nombreprovee"?>
</td>
</tr>

<tr>
<td colspan=4 class="tdtitulos">
MEDICAMENTOS CARGADOS
</td>
</tr>
<tr>

   <?
      if($operacion=='emhp'){?>
        <th class="tdtitulos">Especialidad.</th>
        <th class="tdtitulos">Nombre del art&iacute;culo.</th>
        <th class="tdtitulos">Cantidad.</th>

   <?}else{?> 
    <th class="tdtitulos">Especialidad.</th>
    <th class="tdtitulos">Nombre del art&iacute;culo.</th>
    <th class="tdtitulos">Tratamiento C.</th>
    <th class="tdtitulos">Cantidad.</th>
    <th class="tdtitulos">Fecha Final Tratamiento.</th>
   <?}?>
</tr>
<?
    while($losmedicar=asignar_a($repbuscoengastb,NULL,PGSQL_ASSOC)){
               $idservico=$losmedicar['id_servicio'];
              $elidinsumo=$losmedicar['id_insumo'];
               $buscarlab=("select tbl_laboratorios.laboratorio from 
                                tbl_laboratorios,tbl_insumos where tbl_insumos.id_insumo=$elidinsumo and 
                                tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio");
                $repbusclab=ejecutar($buscarlab);
                $datolab=assoc_a($repbusclab);
                $nombrela=$datolab[laboratorio];
              if(($idservico==6) or ($idservico==9)){
                   $especiali=$losmedicar['descripcion'];
		   $producto="$losmedicar[nombre] -- $nombrela";
                   $tratamiento=$losmedicar['unidades'];  
              }else{
                 $especiali=$losmedicar['nombre'];
		 $producto=$losmedicar['descripcion'];
		 $tratamiento=$losmedicar['continuo']; 
		 $cantidades=$losmedicar['unidades']; 
		$fechatratcon=$losmedicar['fecha_continuo']; 
		
		if($tratamiento=='on'){
			
			  $tratamiento='Si';
			}else{$tratamiento='No';}
		if(!empty($fechatratcon)){
			list($ano1,$mes1,$dia1)=split("-",$fechatratcon,3); 
			$fechatratcon="$dia1-$mes1-$ano1"; 
			}	else{$fechatratcon=''; }
               }
	echo"
            <tr>
               <td colspan=1  class=\"tdcampos\">$especiali</td>
			   <td colspan=1 class=\"tdcampos\">$producto</td>
               <td colspan=1  class=\"tdcampos\">$tratamiento</td>
			   <td colspan=1 class=\"tdcampos\">$cantidades</td>
               <td colspan=1 class=\"tdcampos\">$fechatratcon</td>
            </tr>
           ";		
	}
?>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=1 class="tdtitulos">
__________________
</td>
<td colspan=1  class="tdtitulos">
__________________
</td>
<td colspan=2 class="tdtitulos">
__________________
</td>
</tr>
<tr>
<td colspan=1 class="tdtitulos">
ELABORADO POR:
</td>
<td colspan=1 class="tdtitulos">
REVISADO POR:
</td>
<td colspan=2 class="tdtitulos">
<? if($esunbeni==1){
	    echo "$nombrecompletobeni";
	  }else{
	     echo "$nombrecompletotitu";
	  } 
 ?>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="tdtitulos">
NOTA:  CONSIGNAR ESTE FORMATO JUNTO CON EL INFORME MEDICO Y FACTURAS ANEXAS A LA SIGUIENTE DIRECCION.
</td>

</tr>
<tr>
<td colspan=4 class="tdtitulos">
<?php echo DIRECCION_FISCAL; ?>
</td>

</tr>
<tr>
<td colspan=4 class="tdtitulos">
<?php echo DIRECCION_VIGIA;?><br>
<?php echo  DIRECCION_QUIROFANO;?>
</td>
</tr>
<tr>
<td colspan=4 class="tdtitulos">
COMENTARIO:  <? echo $elcomentario?>
</td>

</tr>

</table>
