<?php
include ("../../lib/jfunciones.php");
sesion();
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);



?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="ccita">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Consultar  Citas del Usuario</td>	</tr>	
		<tr>
		<td colspan=1 class="tdtitulos">* Seleccione la Fecha Inicio Cita:    </td>
        <td colspan=1 class="tdtitulos">
 <input readonly type="text" size="10" id="dateField1" name="fechainicio" class="campos" maxlength="10" value=<?php echo $f_cita1[fecha_cita]; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a> </td>
<td colspan=1 class="tdtitulos">* Seleccione la Fecha final Cita:   </td>
<td colspan=1 class="tdtitulos"> 
 <input readonly type="text" size="10" id="dateField2" name="fechafin" class="campos" maxlength="10" value=<?php echo $f_cita1[fecha_cita]; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
		</tr>
	
		<tr>
		<td colspan=1 class="tdtitulos">* Seleccione  Otros Proveedores</td>
<td colspan=1 class="tdcampos">
		  <select style="width: 200px;" OnChange="verificarproc(this);" id="id_proveedorc" name="id_proveedorc" class="campos">
                <?php $q_pc=("select clinicas_proveedores.*,proveedores.id_proveedor 
                        from clinicas_proveedores,proveedores 
                        where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and
                                    clinicas_proveedores.prov_compra=0
                order by clinicas_proveedores.nombre");
                $r_pc=ejecutar($q_pc);
                if ($f_admin[id_ente]>0){
                ?>
                <option   value="0"> Sin Proveedor</option> 
                <option value="*"> Todos </option>
                
                 <?php
                 }
                 else
                 {
                     ?>
                <option   value="0"> Sin Proveedor</option> 
                <option value="*"> Todos </option>
                     <?php 
                while($f_pc=asignar_a($r_pc,NULL,PGSQL_ASSOC)){

                ?>  
                <option  value="<?php echo $f_pc[id_proveedor]?>"> <?php echo "$f_pc[nombre] $f_pc[direccion]"?></option>
                <?php
                }
                }
                ?>
                </select>
            o </td>
	
       
		<td colspan=1 class="tdtitulos">* Seleccione  Doctor(a).</td>
<td colspan=1 class="tdcampos">
		<select style="width: 200px;" OnChange="verificarprop(this);" id="id_proveedorp" name="id_proveedorp" class="campos" >
		<?php $q_p=("select sucursales.sucursal,especialidades_medicas.especialidad_medica,proveedores.id_proveedor,
		personas_proveedores.*,s_p_proveedores.* from especialidades_medicas,personas_proveedores,
		s_p_proveedores,proveedores,sucursales where proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and 
		s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and s_p_proveedores.nomina=1 		
		and s_p_proveedores.id_sucursal=sucursales.id_sucursal and 
		especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad 
		order by personas_proveedores.nombres_prov");
		$r_p=ejecutar($q_p);
		?>
		<option   value="0"> Sin Proveedor</option> 
		<option value="*"> Todos Los Medicos</option>
		<?php		
		while($f_p=asignar_a($r_p,NULL,PGSQL_ASSOC)){
		?>
		<option  <?php if ($f_p[id_sucursal]==3 || $f_p[id_sucursal]>=8) {?> class="option" <?php } ?>
<?php if ($f_p[id_sucursal]==2) {?> class="option2" <?php } ?>
<?php if ($f_p[id_sucursal]==7) {?> class="option1" <?php } ?>

value="<?php echo $f_p[id_proveedor]?>"> <?php echo " $f_p[nombres_prov] $f_p[apellidos_prov]  $f_p[especialidad_medica] $f_p[sucursal] 
		 $f_p[id_proveedor]"?></option>
		<?php
		}
		?>
		</select>
		</td>
		
	
        </tr>
        	<tr>
		<td colspan=1  class="tdtitulos">* Numero de Cedula</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="cedula" maxlength=128 size=20 value=""   ></td>
		<td colspan=2 class="tdcampos"><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""> <a href="#" OnClick="buscarcitas();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		</tr>
</table>
<div id="buscarcitas"></div>

</form>
