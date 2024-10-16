<?
/* Nombre del Archivo: r_cliente_x_ente.php
   Descripción: Solicitar los datos para Reporte de Impresión: Relación de los Clientes Totales, de un determinado Ente */

    include ("../../lib/jfunciones.php");
    sesion();
$admin= $_SESSION['id_usuario_'.empresa];
/* Seleccionar la información en la base de datos, para utilizar las variables en el formulario */
   $q_tipo_ente=("select * from tbl_tipos_entes order by tipo_ente");
   $r_tipo_ente = ejecutar($q_tipo_ente);

    $q_estado=("select estados_clientes.id_estado_cliente,estados_clientes.estado_cliente from estados_clientes order by estados_clientes.estado_cliente");
    $r_estado=ejecutar($q_estado);

    $q_subdivision=("select subdivisiones.id_subdivision, subdivisiones.subdivision from subdivisiones order by subdivisiones.subdivision");
    $r_subdivision=ejecutar($q_subdivision);




?>

 <form method="POST" name="r_clientexente" id="r_clientexente">

    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Clientes Totales, de un determinado Ente.</td>
	</tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr>
	      

	       <td class="tdtitulos" colspan="1">&nbsp; &nbsp;Seleccione Estado del Titular:</td>
	       <td class="tdcampos"  colspan="1"><select name="estadot" class="campos"  style="width: 210px;" >
                                     <option value="0@Todos los Estados">Todos los Estado</option>
				     <option value="-01@ACTIVO CON BENEFICIARIO">ACTIVO CON BENEFICIARIO</option>
				    <option value="-02@ACTIVO SIN BENEFICIARIO">ACTIVO SIN BENEFICIARIO</option>
      				     <?php  while($f_estado=asignar_a($r_estado,NULL,PGSQL_ASSOC)){
$value="$f_estado[id_estado_cliente]@$f_estado[estado_cliente]";
					?>

				     <option value="<?php echo $value?>"> <?php echo "$f_estado[estado_cliente]"?></option>
				    <?php
				    }?> 
		</td>

	
	       <td class="tdtitulos" colspan="1">&nbsp; &nbsp;Seleccione Estado del Beneficiario:</td>
	       <td class="tdcampos"  colspan="1"><select name="estadob" class="campos"  style="width: 210px;" >
                                     <option value="0@Todos los Estados">Todos los Estado</option>
				     <option value="-01@NINGUNO">NINGUNO</option>
				     <option value="-02@ACTIVO + LAPSO ESPERA">ACTIVO + LAPSO ESPERA</option>
				     <?php  
					pg_result_seek($r_estado,0);			
						while($f_estado=asignar_a($r_estado,NULL,PGSQL_ASSOC)){
$value="$f_estado[id_estado_cliente]@$f_estado[estado_cliente]";
?>
				     <option value="<?php echo $value?>"> <?php echo "$f_estado[estado_cliente]"?></option>
				    <?php
				    }?> 
		</td>
		</tr>

<tr> <td colspan=4>&nbsp;</td></tr>


	<tr>
		<td class="tdtitulos" colspan="1">&nbsp; &nbsp;Seleccione la Subdivisión:</td>
	        <td class="tdcampos"  colspan="1"><select name="subdivi" class="campos"  style="width: 210px;" >
                                     <option value="0@Todos">Subdivision</option>
				     <?php  while($f_subdivision=asignar_a($r_subdivision,NULL,PGSQL_ASSOC)){?>
				     <option value="<?php echo $f_subdivision['id_subdivision']?>"> <?php echo "$f_subdivision[subdivision]"?></option>
				    <?php
				    }?> 
		</td>	

	<td class="tdtitulos" colspan="1">&nbsp; &nbsp;Seleccione Tipo de Ente:</td>
		 <td class="tdcampos"  colspan="1">
         <select class="campos"  style="width: 200px;"  id="tipo_ente" name="tipo_ente" onchange="bus_ent(4)" >
		<option value="">--Seleccione un Tipo de Ente--</option>
		<option value="0@TODOS LOS TIPOS">TODOS LOS TIPOS</option>
		<?php
		while($f_tipo_ente = asignar_a($r_tipo_ente)){
		echo "<option value=\"$f_tipo_ente[id_tipo_ente]@$f_tipo_ente[tipo_ente]\">$f_tipo_ente[tipo_ente]</option>";
		}
		?>
		</select> </td>
		</tr>

<tr><td>&nbsp;</td></tr>
</table>

<div id="bus_ent"></div>

	</tr>

    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr> <td colspan=4>&nbsp;</td></tr>
	<tr>
		<td colspan=4 class="tdcamposcc">
<a href="#" OnClick="reporte_cliente_x_ente();" class="boton">Buscar</a> 
<a href="#" OnClick="reporte_cliente_x_ente_titular();" class="boton">Solo Titulares</a>  
<a href="#" OnClick="imp_cliente_x_ente();" class="boton">Imprimir</a>
<a href="#" title="BUSQUEDA GENERAL" OnClick="exc_cliente_x_ente();">  <img border="0" src="../public/images/excel.jpg"></a>
<a href="#" title="NÚMEROS TELEFÓNICOS" OnClick="exc_cliente_x_ente_telefonos();">  <img border="0" src="../public/images/excel.jpg"></a>
<a href="#" OnClick="leyenda();" class="boton">Leyenda</a> 
<a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>

    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


	<tr> <td colspan=4>&nbsp;</td></tr>
<tr> <td class="tdtitulos" colspan=4> &nbsp; &nbsp;&nbsp; &nbsp; PARA REALIZAR LA BUSQUEDA DE TITULARES SIN BENEFICIARIOS O TITULARES CON BENEFICIARIOS SE DEBE SELECCIONAR LA FECHA<td></tr>

	<tr> <td colspan=4>&nbsp;</td></tr>

	<tr>
		<td colspan=2 class="tdtitulos">* Seleccione Fecha Inicio:&nbsp;
		<input readonly type="text" size="10" id="dateField1" name="fecha1" class="campos" maxlength="10" >
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>

		<td colspan=2 class="tdtitulos">* Seleccione Fecha Final:
		<input readonly type="text" size="10" id="dateField2" name="fecha2" class="campos" maxlength="10">
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
		</td>
	</tr>

	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr>
		<td colspan=4 class="tdcamposcc">


<a href="#" OnClick="reporte_cliente_x_ente_sinben();" class="boton">Titulares sin Beneficiarios</a>  
<a href="#" OnClick="reporte_cliente_x_ente_conben();" class="boton">Titulares con Beneficiarios</a>  
<a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>




	<tr> <td colspan=4>&nbsp;</td></tr>
</table>
<div id="reporte_cliente_x_ente"></div>
</from>

