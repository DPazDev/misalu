<?php
include ("../../lib/jfunciones.php");
sesion();

$cedula=$_REQUEST['cedula'];
$tiposerv=$_REQUEST['tiposerv'];
$servicio=$_REQUEST['servicio'];
$fechacreado=date("Y-m-d");
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** busco si es titular **** */
$q_cliente=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,estados_clientes.estado_cliente,titulares.id_titular,titulares.id_ente,entes.nombre,entes.fecha_inicio_contrato,entes.fecha_renovacion_contrato,entes.fecha_inicio_contratob,entes.fecha_renovacion_contratob 	 	 from clientes,titulares,estados_t_b,estados_clientes,entes
                where clientes.cedula='$cedula' and clientes.id_cliente=titulares.id_cliente and titulares.id_titular=estados_t_b.id_titular and estados_t_b.id_beneficiario=0 and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and titulares.id_ente=entes.id_ente and estados_clientes.id_estado_cliente=4");
$r_cliente=ejecutar($q_cliente) or mensaje(ERROR_BD);
$num_filas=num_filas($r_cliente);
$titular=1;

/* **** busco si es beneficiario **** */
if ($num_filas == 0) { 
$q_clienteb=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,estados_clientes.estado_cliente,
beneficiarios.id_beneficiario,beneficiarios.id_titular,entes.nombre 
from clientes,estados_clientes,beneficiarios,entes  where 
clientes.cedula='$cedula' and clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=estados_t_b.id_beneficiario 
and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and beneficiarios.id_titular=titulares.id_titular 
and titulares.id_ente=entes.id_ente and estados_clientes.id_estado_cliente=4");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);
$titular=0;
}

if ($num_filas==0 and $num_filasb==0){

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<tr> <td colspan=4 class="titulo_seccion">El Numero de cedula no existe Si es particular debe dirigirse a administracion a cancelar la consulta si es afiliado y no a parece preguntar en dpto de nomina o operacion o verificarlo en la opcion buscar cliente que se encuentra en la parte superior derecha </td>
      </tr>

	<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><a href="#" OnClick="reg_cita();" class="boton">Registrar Citas</a></td>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><a href="#" OnClick="ir_principal();" class="boton">salir</a></td>
		<td class="tdtitulos"></td> 
	</tr>
</table>

<?php
}
else
{
?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<?php if ($titular==1) {
?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de Este Cliente como Titular"?></td></tr>
<?php
/* ***** repita para buscar al titular **** */
$i=0;
while($f_cliente=asignar_a($r_cliente,NULL,PGSQL_ASSOC)){
$i++;
$q_coberturat=("select coberturas_t_b.id_cobertura_t_b,coberturas_t_b.monto_actual,propiedades_poliza.cualidad,coberturas_t_b.id_propiedad_poliza,
coberturas_t_b.monto_actual,coberturas_t_b.id_organo
from propiedades_poliza,coberturas_t_b where
coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza
and coberturas_t_b.id_beneficiario=0 and
coberturas_t_b.id_titular='$f_cliente[id_titular]' order by propiedades_poliza.cualidad");
$r_coberturat=ejecutar($q_coberturat) or mensaje(ERROR_BD);

?>


<tr>
		<td class="tdtitulos">Nombres y Apellidos del Titular
 <input class="campos" type="hidden" name="proceso" 
                                        maxlength=128 size=20 value="0"   >

</td>
		<td class="tdcampos"><?php echo $f_cliente[nombres]?> <?php echo $f_cliente[apellidos]?></td>
		<td class="tdtitulos">Ente</td>
                <td class="tdcampos"><?php echo $f_cliente[nombre]?></td>
	</tr>		
	
	<tr> 
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr">
		<?php echo $f_cliente[estado_cliente]?></td>
		
	</tr>
	


<tr>
                <td colspan=1 class="tdtitulos">Seleccione la Cobertura</td>
<td colspan=3 class="tdcampos">
                <select style="width: 200px;" id="cobertura_<?php echo $i ?>" name="cobertura" class="campos" Onchange="varcober();">
		<option value=""> Seleccione La Cobertura</option>
                <?php
                while($f_coberturat=asignar_a($r_coberturat,NULL,PGSQL_ASSOC)){
                $q_organot=("select * from organos where organos.id_organo=$f_coberturat[id_organo]");
                $r_organot=ejecutar($q_organot);
                $f_organot=asignar_a($r_organot);
                ?>
		  
		 <option value="<?php echo $f_coberturat[id_cobertura_t_b]?>"> <?php echo "$f_coberturat[cualidad] $f_organot[organo]  Cobertura Disponible $f_coberturat[monto_actual] " ?>
                 </option>
                <?php
                }
                ?>
                </select>
				<?php
				    $url="'views01/igastos.php?cedula=$cedula&id_titular=$f_cliente[id_titular]&titular=$titular&fechainicio=$f_cliente[fecha_inicio_contrato]&fechafinal=$f_cliente[fecha_renovacion_contrato]&id_servicio=$servicio'";
                        ?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Consultar Gastos</a>
                </td>

	
        </tr>
	
		<tr>
		<td colspan=4><hr></td>
		</tr>
<?php
 }

$q_clienteb=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,estados_clientes.estado_cliente,
beneficiarios.id_beneficiario,beneficiarios.id_titular,entes.nombre
from clientes,estados_clientes,beneficiarios,entes  where
clientes.cedula='$cedula' and clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=estados_t_b.id_beneficiario
and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and beneficiarios.id_titular=titulares.id_titular
and titulares.id_ente=entes.id_ente and estados_clientes.id_estado_cliente=4");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);

if ($num_filasb==0){
}
else
{
?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo "Este Cliente Tambien es Beneficiario "?></td></tr>
<?php
while($f_clienteb=asignar_a($r_clienteb,NULL,PGSQL_ASSOC)){
$i++;
echo $i;
$q_clientet=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,
estados_clientes.estado_cliente,titulares.id_titular,titulares.id_ente,entes.nombre
from clientes,titulares,estados_t_b,estados_clientes,entes
                where titulares.id_titular=$f_clienteb[id_titular] and clientes.id_cliente=titulares.id_cliente and
titulares.id_titular=estados_t_b.id_titular and estados_t_b.id_beneficiario=0 and
estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and titulares.id_ente=entes.id_ente");

$r_clientet=ejecutar($q_clientet) or mensaje(ERROR_BD);
$f_clientet=asignar_a($r_clientet);

$q_coberturabe=("select coberturas_t_b.id_cobertura_t_b,propiedades_poliza.cualidad,coberturas_t_b.id_propiedad_poliza,
coberturas_t_b.monto_actual,coberturas_t_b.id_organo
from propiedades_poliza,coberturas_t_b where
coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza
and coberturas_t_b.id_beneficiario='$f_clienteb[id_beneficiario]' and
coberturas_t_b.id_titular='$f_clientet[id_titular]' order by propiedades_poliza.cualidad");
$r_coberturabe=ejecutar($q_coberturabe) or mensaje(ERROR_BD);

?>



<tr>
                <td class="tdtitulos">Nombres y Apellidos del titular </td>
                <td class="tdcampos"><?php echo $f_clientet[nombres]?> <?php echo $f_clientet[apellidos]?></td>
                <td class="tdtitulos">Cedula Titular</td>
                <td class="tdcampos"><?php echo $f_clientet[cedula]?></td>
        </tr>


        <tr>
         <td class="tdtitulos">Ente</td>
                <td class="tdcampos"><?php echo $f_clientet[nombre]?></td>
                <td class="tdtitulos">Estado</td>
                <td class="campos1"><?php echo $f_clientet[estado_cliente]?></td>
        </tr>

<tr>
                <td class="tdtitulos">Nombres y Apellidos del Beneficiario</td>
                <td class="tdcampos" ><?php echo $f_clienteb[nombres]?> <?php echo $f_clienteb[apellidos]?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_clienteb[estado_cliente]?></td>
        </tr>

<tr>
                <td colspan=1 class="tdtitulos">Seleccione la Cobertura</td>
<td colspan=3 class="tdcampos">
                <select style="width: 100px;" id="cobertura_<?php echo $i ?>" name="cobertura" class="campos" Onchange="asigcober1();">
                 <option value=""> Seleccione La Cobertura</option>
                <?php
                while($f_coberturabe=asignar_a($r_coberturabe,NULL,PGSQL_ASSOC)){
                $q_organob=("select * from organos where organos.id_organo=$f_coberturabe[id_organo]");
                $r_organob=ejecutar($q_organob);
                $f_organob=asignar_a($r_organob);
				?>
				 
                <option value="<?php echo $f_coberturabe[id_cobertura_t_b]?>"> <?php echo "$f_coberturabe[cualidad] $f_organob[organo] Cobertura Disponible  $f_coberturabe[monto_actual] " ?>
                 </option>
                <?php
                }
                ?>
                </select>
              
  </td>
 

        </tr>
<tr>
<td colspan=4><hr></td>
</tr>
<?php
}
}
?>

<tr>
                <td colspan=1 class="tdtitulos"><input type="hidden" value="<?php echo $i ?>" name="contador"></td>
        </tr>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de la Orden "?></td></tr>
<tr> 
               
		<td  class="tdtitulos">* Fecha de Recepcion:   </td>
		<td>
 <input readonly type="text" size="10" id="dateField1" name="fechar" class="campos" maxlength="10" value=<?php echo $fechacreado; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
		<td class="tdtitulos"> 
	<?php if (($servicio==6) || ($servicio==9) ) 
	{
	?>
	 * Fecha Egreso
	 <?php
	 } 
	else
	 {
	 ?>
	* Fecha de Cita:
	<?php
	}
	?>
		</td>
		<td> 
 <input readonly type="text" size="10" id="dateField2" name="fechac" class="campos" maxlength="10" value="1900-01-01"> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
	</tr>
		<?php 

	if (($tiposerv==9) || ($tiposerv==13)) {
	?>

<tr>
				<td class="tdtitulos">* Cuadro Medico</td>
              	<td class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength=128 size=20 value=""   ></td>
					<td class="tdtitulos">* Descripcion</td>
              	<td class="tdcampos"><input class="campos" type="text" name="decrip" maxlength=128 size=20 value=""   ></td>
				
	</tr>		
		<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=2 class="campos"></textarea></td>
	</tr>	
<tr>
	<td  class="tdtitulos">Nombre 
	</td>
	<td  class="tdtitulos">Descripcion 
	 </td>
	 </td>
	<td  class="tdtitulos">FM
	 </td>
	<td  colspan=1 class="tdtitulos">
	Monto 	
	</td>
		
</tr>

<?php
$i=0;
$ban="";
$q_baremoe=("select * from examenes_bl where examenes_bl.id_tipo_examen_bl=5  order by examenes_bl.examen_bl");
$r_baremoe=ejecutar($q_baremoe);
                while($f_baremoe=asignar_a($r_baremoe,NULL,PGSQL_ASSOC)){
$i++;

?>
<tr>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="nombre_<?php echo $i?>" name="nombre" maxlength=128 size=20 value="<?php echo $f_baremoe[examen_bl]?>"> </td>
		<td  class="tdtitulos">
<select style="width: 100px;" id="tiposerv_<?php echo $i?>" name="tiposerv" class="campos" >
			<?php if ($tiposerv==9){ ?>

			<option value="GASTOS EMERGENCIAS">GASTOS EMERGENCIAS</option>
			<?php 
			}
			else
			{
			?>
			<option value="HOSPITALIZACION AMBULATORIA">HOSPITALIZACION AMBULATORIA</option>
			<?php 
			}			
			$q_especialidad=("select * from especialidades_medicas order by especialidad_medica");
			$r_especialidad=ejecutar($q_especialidad);
	                while($f_especialidad=asignar_a($r_especialidad,NULL,PGSQL_ASSOC)){	
			?>
			
			<option value="<?php echo $f_especialidad[especialidad_medica]?>"> <?php echo $f_especialidad[especialidad_medica]?></option>
			
			<?php
			}
			?>	
		</select>
			<input class="campos" type="text" id="valor_<?php echo $i?>"  name="valor" maxlength=128 size=10 value="<?php echo $f_baremoe[honorarios]?>"   >
		 </td>
<td  class="tdtitulos">
		<select  id="factor_<?php echo $i?>"  name="factor" class="campos" >
		
				<option value="1"> 1</option>
				<option value="2"> 2</option>
				<option value="3"> 3</option>
				<option value="4"> 4</option>
				<option value="5"> 5</option>
				<option value="6"> 6</option>
				<option value="7"> 7</option>
				<option value="8"> 8</option>
				<option value="9"> 9</option>
				<option value="10"> 10</option>
				<option value="11"> 11</option>
				<option value="12"> 12</option>
				

		</select>
		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>" OnChange="multiplicar(this);" name="checkl" maxlength=128 size=20 value=""> 
		
		</td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="honorarios_<?php echo $i?>" maxlength=128 size=10 value="0"   >
	</td>
		
		
</tr>

                <?php
                }
		$p=$i + 1;

	for( $i=$p; $i<$p*2; $i++){

	?>
	<tr>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="nombre_<?php echo $i?>" name="nombre" maxlength=128 size=20 value=""> </td>
		<td  class="tdtitulos">
<select style="width: 100px;" id="tiposerv_<?php echo $i?>" name="tiposerv" class="campos" >
		
				<option value="0"> Seleccione el Tipo de Servicio</option>
				<option value="MEDICAMENTOS"> MEDICAMENTOS</option>
				<option value="INSUMOS"> INSUMOS</option>
			<?php if ($tiposerv==9){ ?>

			<option value="GASTOS EMERGENCIAS">GASTOS EMERGENCIAS</option>
			<?php 
			}
			else
			{
			?>
			<option value="HOSPITALIZACION AMBULATORIA">HOSPITALIZACION AMBULATORIA</option>
			<?php
			}
			?>

		</select>
<input class="campos" type="text" id="valor_<?php echo $i?>"  name="valor" maxlength=128 size=10 value="0"   >
		 </td>
<td  class="tdtitulos">
		<select  id="factor_<?php echo $i?>" name="factor" class="campos" >
		
				<option value="1"> 1</option>
				<option value="2"> 2</option>
				<option value="3"> 3</option>
				<option value="4"> 4</option>
				<option value="5"> 5</option>
				<option value="6"> 6</option>
				<option value="7"> 7</option>
				<option value="8"> 8</option>
				<option value="9"> 9</option>
				<option value="10"> 10</option>
				<option value="11"> 11</option>
				<option value="12"> 12</option>
				

		</select>
		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>" OnChange="multiplicar(this);"  name="checkl" maxlength=128 size=20 value=""> 
		
		</td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="honorarios_<?php echo $i?>" maxlength=128 size=10 value="0"   >
	</td>
		
		
</tr>
	<?php
	}
	echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
	?>
<tr>
<td class="tdtitulos"></td>
<td class="tdtitulos">* Monto Total</td>

              	<td class="tdcampos"><input class="campos" type="text" name="monto" maxlength=128 size=10 value=""   ></td>
				<td colspan=1><a href="javascript: sumar(this);" class="boton">      Calcular Monto</a></td>
</tr>










	<?php	

	}




		
	if ($tiposerv<>6 and $servicio==4) {
	
	?>
   <tr>
		 
				<td class="tdtitulos">Hora Cita</td>
              	<td class="tdcampos"><input class="campos" type="text" name="horac" 
					maxlength=128 size=20 value=""   >
					</td>
				<td class="tdtitulos">* Monto</td>
              	<td class="tdcampos"><input class="campos" type="text" name="monto" maxlength=128 size=20 value=""   ></td>

	</tr>		
	
	<tr>
				<td class="tdtitulos">* Cuadro Medico</td>
              	<td class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength=128 size=20 value=""   ></td>
					<td class="tdtitulos">* Descripcion</td>
              	<td class="tdcampos"><input class="campos" type="text" name="decrip" maxlength=128 size=20 value=""   ></td>
				
	</tr>		
		<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=2 class="campos"></textarea></td>
				


	</tr>		

<?php 
}
if (($tiposerv==6 and $servicio==4) || ($tiposerv==8 and $servicio==6) || ($tiposerv==12 and $servicio==9)) {

?>	
           <tr>
<td colspan=1 class="tdtitulos"><a href="#" OnClick="buscarexal();" class="boton">* Examenes de Laboratorio</a></td>

<td colspan=1 class="tdcampos">
                <select  name="examenes" class="campos">
                <option value="0">Seleccione  los Examenes Especiales</option>
                <?php
				$q_texamen=("select * from tipos_imagenologia_bi  order by tipo_imagenologia_bi");
$r_texamen=ejecutar($q_texamen);
                while($f_texamen=asignar_a($r_texamen,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_texamen[id_tipo_imagenologia_bi]?>"> <?php echo $f_texamen[tipo_imagenologia_bi]  ?>
                 </option>
                <?php
                }
                ?>
                </select>
              
  </td>
			<td colspan=1 class="tdtitulos"><a href="#" OnClick="buscarexae();" class="boton">Buscar </a></td>
			
</tr>

<tr>
<td colspan=4>
<div id="buscarexa"></div>

</td>
</tr>
<?php 
}
?>

	<?php 
		
	if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13 || $servicio==10) ){
	$ban="";
	?>
   <tr>
		 
				<td colspan=1 class="tdtitulos">* Honorarios Medicos</td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="honorarios_1" name="montoh" 
					maxlength=128 size=20 value="0"   ><input class="campos" style="visibility:hidden" type="checkbox"  checked id="check_1"name="checkl" maxlength=128 size=20 value="">
					</td>
				<td colspan=1 class="tdtitulos">* Gastos Clinicos</td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="honorarios_2" name="montog" maxlength=128 size=20 value="0"   ><input class="campos"  style="visibility:hidden" type="checkbox" checked  id="check_2" name="checkl" maxlength=128 size=20 value=""></td>

	</tr>		
	<tr>
		 
				<td colspan=1 class="tdtitulos">* Otros Gastos</td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="honorarios_3"  name="montoo" maxlength=128 size=20 value="0"   ><input class="campos" style="visibility:hidden" type="checkbox"  checked  id="check_3"name="checkl" maxlength=128 size=20 value="">
					</td>
				<td colspan=1><a href="javascript: sumar(this);" class="boton">      Calcular Monto</a></td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="text"   name="monto" maxlength=128 size=20 value="0"   >

	</tr>	
	<tr>
				<td  colspan=1  class="tdtitulos">* Numero de Proforma</td>
              	<td  colspan=3  class="tdcampos"><input class="campos" type="text" name="numpro" maxlength=128 size=20 value=""   ></td>
		</tr>	
	<tr>
				<td  colspan=1  class="tdtitulos">* Cuadro Medico</td>
              	<td  colspan=3  class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength=128 size=63 value=""   ></td>
		</tr>	
	
	<tr>		
				
					<td  colspan=1  class="tdtitulos">* Descripcion</td>
              	<td  colspan=3  class="tdcampos"><textarea name="decrip" cols=72 rows=1 class="campos"></textarea></td>
				
	</tr>		
		<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=1 class="campos"></textarea><td class="tdcampos"><input class="campos" type="hidden" name="horac" 	maxlength=128 size=20 value=""   ></td>
	</tr>		
	<tr>
	<td  class="tdtitulos"><a href="#" OnClick="pespera();" class="boton">Proceso en Espera</a></td>
	</tr>
	<br>
	</br>
	

<?php 
}
else
{
?>	

  <tr>
		 
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="hidden" id="honorarios_1" name="montoh" 
					maxlength=128 size=20 value="0"   ><input class="campos" style="visibility:hidden" type="checkbox"  checked id="check_1"name="checkl" maxlength=128 size=20 value="">
					</td>
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="hidden" id="honorarios_2" name="montog" maxlength=128 size=20 value="0"   ><input class="campos"  style="visibility:hidden" type="checkbox" checked  id="check_2" name="checkl" maxlength=128 size=20 value=""></td>

	</tr>		
	<tr>
		 
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="hidden" id="honorarios_3"  name="montoo" maxlength=128 size=20 value="0"   ><input class="campos" style="visibility:hidden" type="checkbox"  checked  id="check_3"name="checkl" maxlength=128 size=20 value="">
					</td>
					</tr>	
	<tr>
				<td  colspan=1  class="tdtitulos"></td>
              	<td  colspan=3  class="tdcampos"><input class="campos" type="hidden" name="numpro" maxlength=128 size=20 value="0"   ></td>
		</tr>	
		


<?php
}
if ($servicio==1){
?>
<tr>
				<td  colspan=1  class="tdtitulos">* Cuadro Medico</td>
              	<td  colspan=3  class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength=128 size=63 value=""   ></td>
		</tr>	
	<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=1 class="campos"></textarea><td class="tdcampos"><input class="campos" type="hidden" name="horac" 
					maxlength=128 size=20 value=""   ></td>
	</tr>		
	<tr>
		<td  class="tdtitulos">Nombre 
	 </td>
<td  class="tdtitulos">Descripcion 
		 </td>
		<td  class="tdtitulos">Factura 
		 </td>
		
		<td  colspan=1 class="tdtitulos">
		Monto      y      Tipo Servicio		
		</td>
</tr>
	<?php
	$i=4;
$ban="";
	for( $i=4; $i<24; $i++){

	?>
	<tr>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="nombre_<?php echo $i?>" name="nombre" maxlength=128 size=20 value=""> </td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="descri_<?php echo $i?>" name="descri" maxlength=128 size=20 value=""> </td>
	<td  class="tdtitulos">
		<input class="campos" type="text" id="factura_<?php echo $i?>" name="factura" maxlength=128 size=10 value=""> </td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="honorarios" maxlength=128 size=10 value="0"   >
		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>"name="checkl" maxlength=128 size=20 value=""> 
		<select style="width: 100px;" id="tiposerv_<?php echo $i?>" name="tiposerv" class="campos" >
		<?php $q_tservicio=("select * from tipos_servicios  where tipos_servicios.id_servicio=1 order by tipo_servicio");
		$r_tservicio=ejecutar($q_tservicio);
		?>
				<option value=""> Seleccione el Tipo de Servicio</option>
				<?php		
		while($f_tservicio=asignar_a($r_tservicio,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo $f_tservicio[id_tipo_servicio]?>"> <?php echo "$f_tservicio[tipo_servicio]"?>			</option>
		<?php
		}
		?>
		</select>
		
		</td>
</tr>
	<?php
	}
	echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
	?>
<tr>
<td class="tdtitulos"></td>
<td class="tdtitulos">* Monto Total</td>

              	<td class="tdcampos"><input class="campos" type="text" name="monto" maxlength=128 size=10 value=""   ></td>
				<td colspan=1><a href="javascript: sumar(this);" class="boton">      Calcular Monto</a></td>
</tr>
<br>
</br>
<tr>
	<td class="tdtitulos"><input class="campos" type="hidden" name="proveedor" maxlength=128 size=20 value="0"   ></td>
		<td  class="tdtitulos"><a href="#" OnClick="pespera();" class="boton">Proceso en Espera</a></td>
		<td  class="tdtitulos"><a href="#" OnClick="guardarra();" class="boton">Guardar</a></td>
		<td class="tdtitulos"></td>
</tr>


<?php
}
else
{
	if (($tiposerv<>9) || ($tiposerv<>13)){
	?>
<tr>
<td colspan=2 class="tdtitulos"><a href="#" OnClick="buscarprovp();" class="boton">* Seleccione  Proveedor Persona.</a></td>
<td colspan=2 class="tdtitulos"><a href="#" OnClick="buscarprovc();" class="boton">* Seleccione  Proveedor Clinica.</a></td>
</tr>
<tr>
<td colspan=4>
<div id="buscarprovp"></div>



<?php
}
}
}
else
{
if ($titular==0)
{

/* **** repita para beneficiario **** */

while($f_clienteb=asignar_a($r_clienteb,NULL,PGSQL_ASSOC)){
$i++;
$q_clientet=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,
estados_clientes.estado_cliente,titulares.id_titular,titulares.id_ente,entes.nombre 
from clientes,titulares,estados_t_b,estados_clientes,entes
                where titulares.id_titular=$f_clienteb[id_titular] and clientes.id_cliente=titulares.id_cliente and 
titulares.id_titular=estados_t_b.id_titular and estados_t_b.id_beneficiario=0 and 
estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and titulares.id_ente=entes.id_ente");

$r_clientet=ejecutar($q_clientet) or mensaje(ERROR_BD);
$f_clientet=asignar_a($r_clientet);

$q_coberturab=("select coberturas_t_b.id_cobertura_t_b,propiedades_poliza.cualidad,coberturas_t_b.id_propiedad_poliza,
coberturas_t_b.monto_actual,coberturas_t_b.id_organo 
from propiedades_poliza,coberturas_t_b where
coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza
and coberturas_t_b.id_beneficiario='$f_clienteb[id_beneficiario]' and
coberturas_t_b.id_titular='$f_clientet[id_titular]' order by propiedades_poliza.cualidad");
$r_coberturab=ejecutar($q_coberturab) or mensaje(ERROR_BD);

?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos del Cliente como Beneficiario"?></td></tr>

<tr>
                <td class="tdtitulos">Nombres y Apellidos del titular
    <input class="campos" type="hidden" name="proceso"
                                        maxlength=128 size=20 value="0"   >

		</td>
                <td class="tdcampos"><?php echo $f_clientet[nombres]?> <?php echo $f_clientet[apellidos]?></td>
                <td class="tdtitulos">Cedula Titular</td>
                <td class="tdcampos"><?php echo $f_clientet[cedula]?></td>
        </tr>


        <tr>
         <td class="tdtitulos">Ente</td>
                <td class="tdcampos"><?php echo $f_clientet[nombre]?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_clientet[estado_cliente]?></td>
        </tr>

<tr>
                <td class="tdtitulos">Nombres y Apellidos del Beneficiario</td>
                <td class="tdcampos"><?php echo $f_clienteb[nombres]?> <?php echo $f_clienteb[apellidos]?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_clienteb[estado_cliente]?></td>
        </tr>



<tr>
                <td colspan=1 class="tdtitulos">Seleccione la Cobertura</td>
<td colspan=3 class="tdcampos">
                <select id="cobertura_<?php echo $i ?>" name="cobertura" class="campos">
                
                <?php
                while($f_coberturab=asignar_a($r_coberturab,NULL,PGSQL_ASSOC)){
		$q_organo=("select * from organos where organos.id_organo=$f_coberturab[id_organo]");
		$r_organo=ejecutar($q_organo);
		$f_organo=asignar_a($r_organo);
                ?>
				  <option value=""> Seleccione La Cobertura</option>
                <option value="<?php echo $f_coberturab[id_cobertura_t_b]?>"> <?php echo "$f_coberturab[cualidad] $f_organo[organo] Cobertura Disponible $f_coberturab[monto_actual] " ?>
		 </option>
                <?php
                }
                ?>
                </select>
                </td>


        </tr>

<?php
}
?>
<tr>
                <td colspan=1 class="tdtitulos"><input type="hidden" value="<?php echo $i ?>" name="contador"></td>
        </tr>

		<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de la Orden de Atencion"?></td></tr>
<tr> 
               
		<td  class="tdtitulos">* Fecha de Recepcion:   </td>
		<td>
 <input readonly type="text" size="10" id="dateField1" name="fechar" class="campos" maxlength="10" value=<?php echo $fechacreado; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
		<td class="tdtitulos">* Fecha de Cita:</td>
		<td> 
 <input readonly type="text" size="10" id="dateField2" name="fechac" class="campos" maxlength="10" value="1900-01-01"> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
	</tr>
			<?php 
		
	if ($tiposerv<>6 and $servicio==4) {
	
	?>
   <tr>
		 
				<td class="tdtitulos">Hora Cita</td>
              	<td class="tdcampos"><input class="campos" type="text" name="horac" 
					maxlength=128 size=20 value=""   >
					</td>
				<td class="tdtitulos">* Monto</td>
              	<td class="tdcampos"><input class="campos" type="text" name="monto" maxlength=128 size=20 value=""   ></td>

	</tr>		
	
	<tr>
				<td class="tdtitulos">* Cuadro Medico</td>
              	<td class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength=128 size=20 value=""   ></td>
					<td class="tdtitulos">* Descripcion</td>
              	<td class="tdcampos"><input class="campos" type="text" name="decrip" maxlength=128 size=20 value=""   ></td>
				
	</tr>		
		<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=2 class="campos"></textarea></td>
				


	</tr>		

<?php 
}
if ($tiposerv==6 and $servicio==4) {

?>	
           <tr>
<td colspan=1 class="tdtitulos"><a href="#" OnClick="buscarexal();" class="boton">* Examenes de Laboratorio</a></td>

<td colspan=1 class="tdcampos">
                <select  name="examenes" class="campos">
                <option value="0">Seleccione  los Examenes Especiales</option>
                <?php
				$q_texamen=("select * from tipos_imagenologia_bi  order by tipo_imagenologia_bi");
$r_texamen=ejecutar($q_texamen);
                while($f_texamen=asignar_a($r_texamen,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_texamen[id_tipo_imagenologia_bi]?>"> <?php echo $f_texamen[tipo_imagenologia_bi]  ?>
                 </option>
                <?php
                }
                ?>
                </select>
              
  </td>
			<td colspan=1 class="tdtitulos"><a href="#" OnClick="buscarexae();" class="boton">Buscar </a></td>
			
</tr>

<tr>
<td colspan=4>
<div id="buscarexa"></div>

</td>
</tr>
<?php 
}
?>

		<?php 
		
	if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13 || $servicio==10) ){
	$ban="";
	?>
   <tr>
		 
				<td colspan=1 class="tdtitulos">* Honorarios Medicos</td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="honorarios_1" name="montoh" 
					maxlength=128 size=20 value="0"   ><input class="campos" style="visibility:hidden" type="checkbox"  checked id="check_1"name="checkl" maxlength=128 size=20 value="">
					</td>
				<td colspan=1 class="tdtitulos">* Gastos Clinicos</td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="honorarios_2" name="montog" maxlength=128 size=20 value="0"   ><input class="campos"  style="visibility:hidden" type="checkbox" checked  id="check_2" name="checkl" maxlength=128 size=20 value=""></td>

	</tr>		
	<tr>
		 
				<td colspan=1 class="tdtitulos">* Otros Gastos</td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="honorarios_3"  name="montoo" maxlength=128 size=20 value="0"   ><input class="campos" style="visibility:hidden" type="checkbox"  checked  id="check_3"name="checkl" maxlength=128 size=20 value="">
					</td>
				<td colspan=1><a href="javascript: sumar(this);" class="boton">      Calcular Monto</a></td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="text"   name="monto" maxlength=128 size=20 value="0"   >

	</tr>	
	<tr>
				<td  colspan=1  class="tdtitulos">* Numero de Proforma</td>
              	<td  colspan=3  class="tdcampos"><input class="campos" type="text" name="numpro" maxlength=128 size=20 value=""   ></td>
		</tr>	
	<tr>
				<td  colspan=1  class="tdtitulos">* Cuadro Medico</td>
              	<td  colspan=3  class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength=128 size=63 value=""   ></td>
		</tr>	
	
	<tr>		
				
					<td  colspan=1  class="tdtitulos">* Descripcion</td>
              	<td  colspan=3  class="tdcampos"><textarea name="decrip" cols=72 rows=1 class="campos"></textarea></td>
				
	</tr>		
		<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=1 class="campos"></textarea><td class="tdcampos"><input class="campos" type="hidden" name="horac" 	maxlength=128 size=20 value=""   ></td>
	</tr>		
	<tr>
	<td  class="tdtitulos"><a href="#" OnClick="pespera();" class="boton">Proceso en Espera</a></td>
	</tr>
	<br>
	</br>
	

<?php 
}
else
{
?>	

  <tr>
		 
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="hidden" id="honorarios_1" name="montoh" 
					maxlength=128 size=20 value="0"   ><input class="campos" style="visibility:hidden" type="checkbox"  checked id="check_1"name="checkl" maxlength=128 size=20 value="">
					</td>
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="hidden" id="honorarios_2" name="montog" maxlength=128 size=20 value="0"   ><input class="campos"  style="visibility:hidden" type="checkbox" checked  id="check_2" name="checkl" maxlength=128 size=20 value=""></td>

	</tr>		
	<tr>
		 
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="hidden" id="honorarios_3"  name="montoo" maxlength=128 size=20 value="0"   ><input class="campos" style="visibility:hidden" type="checkbox"  checked  id="check_3"name="checkl" maxlength=128 size=20 value="">
					</td>
					</tr>	
	<tr>
				<td  colspan=1  class="tdtitulos"></td>
              	<td  colspan=3  class="tdcampos"><input class="campos" type="hidden" name="numpro" maxlength=128 size=20 value="0"   ></td>
		</tr>	
		


<?php
}
if ($servicio==1){
?>
<tr>
				<td  colspan=1  class="tdtitulos">* Cuadro Medico</td>
              	<td  colspan=3  class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength=128 size=63 value=""   ></td>
		</tr>	
	<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=1 class="campos"></textarea><td class="tdcampos"><input class="campos" type="hidden" name="horac" 
					maxlength=128 size=20 value=""   ></td>
	</tr>		
	<tr>
		<td  class="tdtitulos">Nombre 
	 </td>
<td  class="tdtitulos">Descripcion 
		 </td>
		<td  class="tdtitulos">Factura 
		 </td>
		
		<td  colspan=1 class="tdtitulos">
		Monto      y      Tipo Servicio		
		</td>
</tr>
	<?php
	$i=4;
$ban="";
	for( $i=4; $i<24; $i++){

	?>
	<tr>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="nombre_<?php echo $i?>" name="nombre" maxlength=128 size=20 value=""> </td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="descri_<?php echo $i?>" name="descri" maxlength=128 size=20 value=""> </td>
	<td  class="tdtitulos">
		<input class="campos" type="text" id="factura_<?php echo $i?>" name="factura" maxlength=128 size=10 value=""> </td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="honorarios" maxlength=128 size=10 value="0"   >
		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>"name="checkl" maxlength=128 size=20 value=""> 
		<select style="width: 100px;" id="tiposerv_<?php echo $i?>" name="tiposerv" class="campos" >
		<?php $q_tservicio=("select * from tipos_servicios  where tipos_servicios.id_servicio=1 order by tipo_servicio");
		$r_tservicio=ejecutar($q_tservicio);
		?>
				<option value=""> Seleccione el Tipo de Servicio</option>
				<?php		
		while($f_tservicio=asignar_a($r_tservicio,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo $f_tservicio[id_tipo_servicio]?>"> <?php echo "$f_tservicio[tipo_servicio]"?>			</option>
		<?php
		}
		?>
		</select>
		
		</td>
</tr>
	<?php
	}
	echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
	?>
<tr>
<td class="tdtitulos"></td>
<td class="tdtitulos">* Monto Total</td>

              	<td class="tdcampos"><input class="campos" type="text" name="monto" maxlength=128 size=10 value=""   ></td>
				<td colspan=1><a href="javascript: sumar(this);" class="boton">      Calcular Monto</a></td>
</tr>
<br>
</br>
<tr>
	<td class="tdtitulos"><input class="campos" type="hidden" name="proveedor" maxlength=128 size=20 value="0"   ></td>
		<td  class="tdtitulos"><a href="#" OnClick="pespera();" class="boton">Proceso en Espera</a></td>
		<td  class="tdtitulos"><a href="#" OnClick="guardarra();" class="boton">Guardar</a></td>
		<td class="tdtitulos"></td>
</tr>


<?php
}
else
{
	?>
<tr>
<td colspan=2 class="tdtitulos"><a href="#" OnClick="buscarprovp();" class="boton">* Seleccione  Proveedor Persona.</a></td>
<td colspan=2 class="tdtitulos"><a href="#" OnClick="buscarprovc();" class="boton">* Seleccione  Proveedor Clinica.</a></td>
</tr>
<tr>
<td colspan=4>
<div id="buscarprovp"></div>

<?php
}

?>
</table>


<?php
}
}
?>

<?php
}
?>

