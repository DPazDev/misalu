<?php
include ("../../lib/jfunciones.php");
sesion();
?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=2 >
                <select style="width: 200px;" name="proveedor" class="campos">
                <?php $q_p=("select especialidades_medicas.especialidad_medica,proveedores.id_proveedor,
                personas_proveedores.*,s_p_proveedores.* from especialidades_medicas,personas_proveedores,
                s_p_proveedores,proveedores where proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
                s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor  
                and
                especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad
                order by personas_proveedores.nombres_prov");
                $r_p=ejecutar($q_p);
                ?>
                <option   value=""> Seleccione el Dr(a).</option>
                <?php
                while($f_p=asignar_a($r_p,NULL,PGSQL_ASSOC)){

                ?>  
                <option  value="<?php echo $f_p[id_proveedor]?>"> <?php echo "$f_p[nombres_prov] $f_p[apellidos_prov] $f_p[direccion_prov]"?></option>
                <?php
                }
                ?>
                </select>
 </td>

	<td colspan=2 class="tdtitulos"><a href="#" OnClick="guardaroac();" class="boton">Guardar</a></td>

</tr>
</table>            
