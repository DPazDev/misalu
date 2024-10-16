<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$numpre=$_REQUEST['numpre'];

if  ($numpre==0){
    
    
    }
    else
    {
	

/* **** busco el id_cliente y procesos que tengan este numero de planilla o presupuesto registrado **** */
$q_cliente=("select 
                                procesos.id_proceso,
                                procesos.id_beneficiario,
								procesos.no_clave,
								procesos.fecha_ent_pri,
								procesos.comentarios,
                                clientes.nombres,
                                clientes.apellidos,
                                clientes.cedula,
                                admin.nombres as nomadmin,
                                admin.apellidos as   apeadmin,
                                entes.nombre
                        from 
                                procesos,
                                titulares,
                                clientes,
                                admin,
                                entes
                        where
                                procesos.nu_planilla='$numpre' and 
                                procesos.id_titular=titulares.id_titular and 
                                titulares.id_cliente=clientes.id_cliente and
                                procesos.id_admin=admin.id_admin and 
titulares.id_ente=entes.id_ente");
$r_cliente=ejecutar($q_cliente);
$num_filas=num_filas($r_cliente);

if  ($num_filas==0)

{
    ?>
    
        <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=6 class="titulo_seccion">No esta  Asignado el Numero de Planilla o Presupuesto </td>	</tr>	
    </table>
    
  <?php  }
  else
  {
  $totalmtno=("select sum(cast(gastos_t_b.monto_aceptado as double precision)) from gastos_t_b,procesos where procesos.nu_planilla='$numpre' and 
procesos.id_proceso=gastos_t_b.id_proceso and 
(gastos_t_b.id_tipo_servicio=27 or gastos_t_b.id_tipo_servicio=28)
");
$reptotalmtno=ejecutar($totalmtno);
$datmonto=assoc_a($reptotalmtno);
$montotdecno=$datmonto[sum];

?>
<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=7 class="titulo_seccion">Numero de Planilla o Presupuesto Asignada a los Sig. Procesos. </td>	</tr>	
	<tr>
		<td class="tdtitulos">Proceso</td>
        <td class="tdtitulos">Titular</td>
        <td class="tdtitulos">C&eacute;dula</td>
        <td class="tdtitulos">Beneficiario</td>
        <td class="tdtitulos">C&eacute;dula</td>
        <td class="tdtitulos">Ente</td>
        <td class="tdtitulos">Analista</td>
        </tr>	
        <tr>		<td colspan=7 class="tdtitulos"><hr></hr> </td>	</tr>	
	<tr>
        
        <?php 

		while($f_cliente=asignar_a($r_cliente,NULL,PGSQL_ASSOC)){
            
$q_beneficiario=("select 
                                    * 
                            from 
                                    beneficiarios,
                                    clientes 
                            where 
                                    beneficiarios.id_cliente=clientes.id_cliente and 
                                    beneficiarios.id_beneficiario=$f_cliente[id_beneficiario]");
$r_beneficiario=ejecutar($q_beneficiario);
$f_beneficiario=asignar_a($r_beneficiario);
?>        
 <tr>		<td colspan=7 class="tdtitulos"><hr></hr> </td>	</tr>	
        	<tr>
		<td class="tdtitulos"><?php echo $f_cliente[id_proceso]?></td>
        <td class="tdtitulos"><?php echo "$f_cliente[nombres] $f_cliente[apellidos] "?></td>
        <td class="tdtitulos"><?php echo $f_cliente[cedula]?></td>
        <td class="tdtitulos"><?php echo "$f_beneficiario[nombres] $f_beneficiario[apellidos] "?></td>
        <td class="tdtitulos"><?php echo $f_beneficiario[cedula] ?></td>
        <td class="tdtitulos"><?php echo $f_cliente[nombre] ?></td>
        <td class="tdtitulos"><?php echo "$f_cliente[nomadmin] $f_cliente[apeadmin] "?></td>
        </tr>
		
		<?php
		$q_gastos=("select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_proceso='$f_cliente[id_proceso]' and procesos.id_estado_proceso<>14 and
gastos_t_b.id_tipo_servicio<>27 and gastos_t_b.id_tipo_servicio<>28");
$r_gastos=ejecutar($q_gastos);
$monto=0;
 while($f_gastos=asignar_a($r_gastos,NULL,PGSQL_ASSOC)){
$monto=$monto + $f_gastos[monto_aceptado];
$cuadrom="$f_gastos[enfermedad]";
?>
<tr>
<td class="tdtitulos"></td>
<td class="tdtitulos"></td>
<td colspan=3  class="tdtitulos">
<?php echo " ($f_gastos[nombre] $f_gastos[descripcion])";?>
</td>
<td colspan=1 class="tdtitulos">
<?php echo "$f_gastos[unidades]";?>
</td>
<td colspan=1 class="tdtitulos">
<?php echo montos_print($f_gastos[monto_aceptado]);?>
</td>
</tr>
<?php
}
$montot=$montot + $monto;
?>

<tr>
<td class="tdtitulos"></td>
<td class="tdtitulos"></td>
<td colspan=3  class="tdtitulos">
</td>
<td colspan=1 class="tdtitulos"> Sub Total
</td>
<td colspan=1 class="tdtitulos">
<?php echo montos_print($monto);?>
</td>
</tr>
<tr>
<td colspan=7  class="tdtitulos">
<hr></hr>
</td>
</tr>
        
        <?php
		$nuclave=$f_cliente[no_clave];
		$fechaentpri=$f_cliente[fecha_ent_pri];
		$comentario=$f_cliente[comentarios];
        }
        ?>
		
		<tr>
<td class="tdtitulos"></td>
<td class="tdtitulos"></td>
<td colspan=3  class="tdtitulos">
</td>
<td colspan=1 class="tdtitulos"> Total..
</td>
<td colspan=1 class="tdtitulos">
<?php echo montos_print(abs($montot - $montotdecno));?>
</td>
</tr>
<tr>
<td colspan=7  class="tdtitulos">
<hr></hr>
</td>
</tr>
</table>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=7 class="titulo_seccion">Pasar a Candidato a Pago todos estos Procesos </td>	</tr>	
	<tr>
	<tr>
		<td class="tdtitulos"></td>
		<td class="tdcampos"><input class="campos" type="hidden" id="numpre2" name="numpre2" maxlength=128 size=20 value="<?php echo $numpre?>"   onkeypress="return event.keyCode!=13"></td>
		<td class="tdtitulos"></td>
		<td class="tdcampos"></td>
		</tr>
		
		<tr>		
<td colspan=1  class="tdtitulos">Clave</td>
              	<td colspan=2 class="tdcampos"><input class="campos" type="text" id="clave" name="clave"  maxlength=128 size=20  value="<?php echo $nuclave ?>"   ></td>
			           
		<td class="tdtitulos">Fecha Relacion Ente Privado</td>
              	<td colspan=2 class="tdcampos"><input readonly type="text" size="10" id="dateFieldfe" name="dateFieldfe" class="campos" maxlength="10" value="<?php echo $fechaentpri?>"> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateFieldfe', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
<td colspan=1 class="tdtitulos">
</td>	
</tr>

<tr>		
<td colspan=1  class="tdtitulos">Comentario Operador</td>
              	<td colspan=2 class="tdcampos">
				<input class="campos" type="text" id="comentario" name="comentario"  maxlength=128 size=30  value="<?php echo $comentario?>"   >
			<td colspan=1  class="tdtitulos">Cuadro Medico</td>
              	<td colspan=2 class="tdcampos">
				<input class="campos" type="text" id="cuadro_m" name="cuadro_m"  maxlength=128 size=30  value="<?php echo $cuadrom?>"   >
			           
<td colspan=1 class="tdtitulos">

<a href="#" OnClick="verificar_clave2();" class="boton" title="Pasar a Candidato a Pago">CP</a> </td>	
</tr>

</table>
<div id="verificar_clave2"></div>
<?php

}
}

?>
