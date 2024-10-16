<?php
/* Nombre del Archivo: r_cliente_x_ente_Empresas.php
  Descripción: Solicitar los datos para Reporte de Impresión: Relación de los Clientes Totales, de un determinado Ente para los Usuarios autorizados  en las distintas Empresas a las cuales les prestamos el servicio */

    include ("../../lib/jfunciones.php");
    sesion();

/*seleccionar el admin asignado al usuario autorizado de la empresa especifica que desea realizar la consulta*/

$admin= $_SESSION['id_usuario_'.empresa];



/* Seleccionar la información en la base de datos, para utilizar las variables en el formulario */

   $q_ente=("select entes.id_ente, entes.nombre from entes,admin where entes.id_ente=admin.id_ente and admin.id_admin='$admin'");
   $r_ente = ejecutar($q_ente);
   $f_ente=asignar_a($r_ente);



    $q_estado=("select estados_clientes.id_estado_cliente,estados_clientes.estado_cliente from estados_clientes order by estados_clientes.estado_cliente");
    $r_estado=ejecutar($q_estado);


/*	echo $admin;*/
?>

 <form method="POST" name="r_clientexente" id="r_clientexente">

    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Clientes Totales, de un determinado Ente.</td>
	</tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr>
	      				  
	       <td class="tdtitulos" colspan="1">&nbsp; &nbsp;Seleccione Estado del Titular:</td>
	       <td class="tdcampos"  colspan="1"><select id="estadot" class="campos"  style="width: 210px;" >
                                     <option value="0@Todos los Estados">Todos los Estado</option>
      				     <?php  while($f_estado=asignar_a($r_estado,NULL,PGSQL_ASSOC)){
				   echo " <option value=\"$f_estado[id_estado_cliente]@$f_estado[estado_cliente]\"> $f_estado[estado_cliente]</option>";

				    }?> 
		</td>

	
	       <td class="tdtitulos" colspan="1">&nbsp; &nbsp;Seleccione Estado del Beneficiario:</td>
	       <td class="tdcampos"  colspan="1"><select id="estadob" class="campos"  style="width: 210px;" >
                                     <option value="0@Todos los Estados">Todos los Estado</option>
				     <option value="-01@NINGUNO">NINGUNO</option>
				     <option value="-02@ACTIVO + LAPSO ESPERA">ACTIVO + LAPSO ESPERA</option>
				     <?php  
					pg_result_seek($r_estado,0);			
						while($f_estado=asignar_a($r_estado,NULL,PGSQL_ASSOC)){
				echo "<option value=\"$f_estado[id_estado_cliente]@$f_estado[estado_cliente]\"> $f_estado[estado_cliente]</option>";
				    }?> 
		</td>
		</tr>

<tr> <td colspan=4>&nbsp;</td></tr>


	<tr>

	<td class="tdtitulos" colspan="1">&nbsp; &nbsp; Ente a Consultar:</td>

<td colspan=2 class="tdcampos"><input class="campos" type="text" id="ente" maxlength=150 size=50 value="<?php echo $f_ente[nombre];?>"></td>		
			<input type="hidden" id="id_ente" value="<?php echo $f_ente['id_ente'];?>">



		</tr>

<tr><td>&nbsp;</td></tr>


<tr> <td colspan=4>&nbsp;</td></tr>
	<tr>
		<td colspan=4 class="tdcamposcc">
<a href="#" OnClick="reporte_cliente_x_ente_empresas();" class="boton">Buscar</a> 


	</tr>

	<tr> <td colspan=4>&nbsp;</td></tr>
</table>
<div id="reporte_cliente_x_ente_empresas"></div>
</from>

