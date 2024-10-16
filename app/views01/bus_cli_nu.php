<?php
include ("../../lib/jfunciones.php");
sesion();

$cedula=$_REQUEST['cedula'];

/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** busco si es titular **** */
$q_cliente=("select 
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								estados_clientes.estado_cliente,
								titulares.id_titular,
								titulares.id_ente,
								entes.nombre,
								tbl_tipos_entes.tipo_ente,
								entes.fecha_inicio_contrato,
								entes.fecha_renovacion_contrato,
								entes.fecha_inicio_contratob,
								entes.fecha_renovacion_contratob 	 	 
						from 
								clientes,
								titulares,
								estados_t_b,
								estados_clientes,
								entes,
								tbl_tipos_entes
						where 
								clientes.cedula='$cedula' and 
								clientes.id_cliente=titulares.id_cliente and 
								titulares.id_titular=estados_t_b.id_titular and 
								estados_t_b.id_beneficiario=0 and 
								estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
								titulares.id_ente=entes.id_ente and 
								entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
								estados_clientes.id_estado_cliente=4");
$r_cliente=ejecutar($q_cliente) or mensaje(ERROR_BD);
$num_filas=num_filas($r_cliente);
$titular=1;

/* **** busco si es beneficiario **** */
if ($num_filas == 0) { 
$q_clienteb=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,estados_clientes.estado_cliente,
beneficiarios.id_beneficiario,beneficiarios.id_titular,entes.nombre 
from clientes,estados_clientes,beneficiarios,entes,estados_t_b,titulares  where 
clientes.cedula='$cedula' and clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=estados_t_b.id_beneficiario 
and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and beneficiarios.id_titular=titulares.id_titular 
and titulares.id_ente=entes.id_ente and estados_clientes.id_estado_cliente=4");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);
$titular=0;
}

if ($num_filas==0 and $num_filasb==0){

?>
<linkHREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<tr> <td colspan=4 class="titulo_seccion">El Numero de cedula no existe Si es particular debe dirigirse a administracion a cancelar la consulta si es afiliado y no a parece preguntar en dpto de nomina o operacion o verificarlo en la opcion buscar cliente que se encuentra en la parte superior derecha </td>
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
coberturas_t_b.id_titular='$f_cliente[id_titular]' and coberturas_t_b.id_organo<=1 order by propiedades_poliza.cualidad");
$r_coberturat=ejecutar($q_coberturat) or mensaje(ERROR_BD);

?>


<tr>
		<td class="tdtitulos">Nombres y Apellidos del Titular</td>
		<td class="tdcampos"><?php echo $f_cliente[nombres]?> <?php echo $f_cliente[apellidos]?></td>
		<td class="tdtitulos">Ente</td>
                <td class="tdcampos"><?php echo $f_cliente[nombre]?></td>
	</tr>	
			
	<tr> 
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr">
		<?php echo $f_cliente[estado_cliente]?></td>
		 <td class="tdtitulos">Tipo Ente</td>
                <td class="tdcamposr">
		<?php echo $f_cliente[tipo_ente]?></td>
		
	</tr>
	


<tr>
                <td colspan=1 class="tdtitulos">Seleccione la Cobertura</td>
<td colspan=3 class="tdcampos">
                <select style="width: 400px;" id="cobertura_<?php echo $i ?>" name="cobertura" class="campos" Onchange="varcober();">
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
                        ?> 
                </td>

	
        </tr>
	
		<tr>
		<td colspan=4><hr></td>
		</tr>
<?php
 }

$q_clienteb=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,estados_clientes.estado_cliente,
beneficiarios.id_beneficiario,beneficiarios.id_titular,entes.nombre
from clientes,estados_clientes,beneficiarios,entes,estados_t_b,titulares  where
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
<tr> <td colspan=4 class="titulo_seccion">  <?php echo "Este Cliente Tambi&eacute;n es Beneficiario "?></td></tr>
<?php
while($f_clienteb=asignar_a($r_clienteb,NULL,PGSQL_ASSOC)){
$i++;
$q_clientet=("select 
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								estados_clientes.estado_cliente,
								titulares.id_titular,
								titulares.id_ente,
								entes.nombre,
								tbl_tipos_entes.tipo_ente
						from 
								clientes,
								titulares,
								estados_t_b,
								estados_clientes,
								entes,
								tbl_tipos_entes
						where 
								titulares.id_titular=$f_clienteb[id_titular] and 
								clientes.id_cliente=titulares.id_cliente and
								titulares.id_titular=estados_t_b.id_titular and 
								estados_t_b.id_beneficiario=0 and
								estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
								titulares.id_ente=entes.id_ente and
								entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente");

$r_clientet=ejecutar($q_clientet) or mensaje(ERROR_BD);
$f_clientet=asignar_a($r_clientet);

$q_coberturabe=("select coberturas_t_b.id_cobertura_t_b,propiedades_poliza.cualidad,coberturas_t_b.id_propiedad_poliza,
coberturas_t_b.monto_actual,coberturas_t_b.id_organo
from propiedades_poliza,coberturas_t_b where
coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza
and coberturas_t_b.id_beneficiario='$f_clienteb[id_beneficiario]' and
coberturas_t_b.id_titular='$f_clientet[id_titular]'  and coberturas_t_b.id_organo<=1 order by propiedades_poliza.cualidad");
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
         <td class="tdtitulos">Tipo Ente</td>
                <td class="tdcamposr"><?php echo $f_clientet[tipo_ente]?></td>
                <td class="tdtitulos"></td>
                <td class="campos1"></td>
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
                <select style="width: 400px;" id="cobertura_<?php echo $i ?>" name="cobertura" class="campos" Onchange="asigcober1();">
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
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdcampos"></td>
		<td colspan=2 class="tdcampos"><a href="#" OnClick="gua_act_cliord();" class="boton">Actualizar</a></td>

		
</tr> 
<tr>
                <td colspan=1 class="tdtitulos"><input type="hidden" value="<?php echo $i ?>" name="contador"></td>
        </tr>




<?php
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
coberturas_t_b.id_titular='$f_clientet[id_titular]' and coberturas_t_b.id_organo<=1 order by propiedades_poliza.cualidad");
$r_coberturab=ejecutar($q_coberturab) or mensaje(ERROR_BD);

?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos del Cliente como Beneficiario"?></td></tr>

<tr>
                <td class="tdtitulos">Nombres y Apellidos del titular
    

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
         <td class="tdtitulos">Tipo Ente</td>
                <td class="tdcampos"><?php echo $f_clientet[tipo_ente]?></td>
                <td class="tdtitulos"></td>
                <td class="tdcamposr"></td>
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
                <select style="width: 400px;" id="cobertura_<?php echo $i ?>" name="cobertura" class="campos">
                  <option value=""> Seleccione La Cobertura</option>
                <?php
                while($f_coberturab=asignar_a($r_coberturab,NULL,PGSQL_ASSOC)){
		$q_organo=("select * from organos where organos.id_organo=$f_coberturab[id_organo]");
		$r_organo=ejecutar($q_organo);
		$f_organo=asignar_a($r_organo);
                ?>
				
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
<tr>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdcampos"></td>
		<td colspan=2 class="tdcampos"><a href="#" OnClick="gua_act_cliord();" class="boton">Actualizar</a></td>

		
</tr> 
		
</table>
<div id="gua_act_cliord"></div>

<?php
}
}
?>

<?php
}
?>
<div id="gua_act_cliord"></div>
