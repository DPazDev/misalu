<?php
include ("../../lib/jfunciones.php");
sesion();

$cedula=$_REQUEST['cedula'];
$servicio=$_REQUEST['servicio'];
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** busco si es titular **** */
$q_cliente=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,estados_clientes.estado_cliente,titulares.id_titular,titulares.id_ente,entes.nombre from clientes,titulares,estados_t_b,estados_clientes,entes
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

/* ***** repita para buscar al titular **** */
$i=0;
while($f_cliente=asignar_a($r_cliente,NULL,PGSQL_ASSOC)){
$i++;
$q_coberturat=("select coberturas_t_b.id_cobertura_t_b,propiedades_poliza.cualidad,coberturas_t_b.id_propiedad_poliza,
coberturas_t_b.monto_actual,coberturas_t_b.id_organo
from propiedades_poliza,coberturas_t_b where
coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza
and coberturas_t_b.id_beneficiario=0 and
coberturas_t_b.id_titular='$f_cliente[id_titular]' order by propiedades_poliza.cualidad");
$r_coberturat=ejecutar($q_coberturat) or mensaje(ERROR_BD);

?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de Este Cliente como Titular"?></td></tr>

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
	</tr>


<tr>
                <td colspan=1 class="tdtitulos">Seleccione la Cobertura</td>
<td colspan=3 class="tdcampos">
                <select id="cobertura_<?php echo $i ?>" name="cobertura" class="campos">
                <option value=""> Seleccione la Cobertura</option>
                <?php
                while($f_coberturat=asignar_a($r_coberturat,NULL,PGSQL_ASSOC)){
                $q_organot=("select * from organos where organos.id_organo=$f_coberturat[id_organo]");
                $r_organot=ejecutar($q_organot);
                $f_organot=asignar_a($r_organot);
                ?>
                <option value="<?php echo $f_coberturat[id_cobertura_t_b]?>"> <?php echo "$f_coberturat[cualidad] $f_organot[organo]  Cobertura Dsiponible $f_coberturat[monto_actual] " ?>
                 </option>
                <?php
                }
                ?>
                </select>
                </td>

	
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
<tr> <td colspan=4 class="titulo_seccion">  <?php echo "Este Cliente Tambien es Beneficiario "?></td></tr>


<tr>
                <td class="tdtitulos">Nombres y Apellidos del titular</td>
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
                <select  id="cobertura_<?php echo $i ?>" name="cobertura" class="campos">
                <option value=""> Seleccione la Cobertura</option>
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


<?php
}
}
?>

<tr>
                <td colspan=1 class="tdtitulos"><input type="hidden" value="<?php echo $i ?>" name="contador"></td>
        </tr>


<tr>
               <tr>
		<td  class="tdtitulos">* Seleccione  el Tipo de Servicio.</td>
<td  class="tdcampos">
		<select name="tiposerv" class="campos" >
		<?php $q_servicio=("select * from tipos_servicios  where tipos_servicios.id_servicio=$servicio order by tipo_servicio");
		$r_servicio=ejecutar($q_servicio);
		?>
		<option value=""> Seleccione el Tipo</option>
		<?php		
		while($f_servicio=asignar_a($r_servicio,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo $f_servicio[id_tipo_servicio]?>"> <?php echo "$f_servicio[tipo_servicio]"?></option>
		<?php
		}
		?>
		</select>
		<a href="#" OnClick="reg_gastonue3();" class="boton">Siguiente</a></td>
</tr>
<tr>
                <td colspan=1 class="tdtitulos">* Seleccione  Doctor(a).</td>
<td colspan=2 class="tdcampos">
                <select name="proveedor" class="campos">
                <?php $q_p=("select sucursales.sucursal,especialidades_medicas.especialidad_medica,proveedores.id_proveedor,
                personas_proveedores.*,s_p_proveedores.* from especialidades_medicas,personas_proveedores,
                s_p_proveedores,proveedores,sucursales where proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
                s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and s_p_proveedores.nomina=1 and
                s_p_proveedores.id_ciudad=ciudad.id_ciudad and ciudad.id_ciudad='$f_admin[id_ciudad]'
                and s_p_proveedores.id_sucursal=sucursales.id_sucursal and
                especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad
                order by personas_proveedores.nombres_prov");
                $r_p=ejecutar($q_p);
                ?>
                <option value=""> Seleccione el Tipo</option>
                <?php
                while($f_p=asignar_a($r_p,NULL,PGSQL_ASSOC)){

                ?>  
                <option value="<?php echo $f_p[id_proveedor]?>"> <?php echo "$f_p[nombres_prov] $f_p[apellidos_prov] $f_p[sucursal]
                $f_p[id_proveedor]"?></option>
                <?php
                }
                ?>
                </select>
                </td>
                <td class="tdtitulos"><a href="#" OnClick="con_medico();" class="boton">Consultar Citas</a></td>
        </tr>
<tr>
<tr>
<td colspan=4>
<div id="con_medico"></div>         

</td>
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
echo $i;
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
                <td class="tdtitulos">Nombres y Apellidos del titular</td>
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
                <option value=""> Seleccione la Cobertura</option>
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
		<tr>
		<td  class="tdtitulos">* Seleccione  el Tipo de Servicio.</td>
<td  class="tdcampos">
		<select name="tiposerv" class="campos" >
		<?php $q_servicio=("select * from tipos_servicios  where tipos_servicios.id_servicio=$servicio order by tipo_servicio");
		$r_servicio=ejecutar($q_servicio);
		?>
		<option value=""> Seleccione el Tipo</option>
		<?php		
		while($f_servicio=asignar_a($r_servicio,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo $f_servicio[id_tipo_servicio]?>"> <?php echo "$f_servicio[tipo_servicio]"?></option>
		<?php
		}
		?>
		</select>
		<a href="#" OnClick="reg_gastonue3();" class="boton">Siguiente</a></td>
		</tr>
        </tr>
<tr>
<td colspan=4>
<div id="con_medico"></div>

</td>
</tr>

</table>


<?php
}
}
?>

<?php
}
?>

