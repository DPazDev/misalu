<?php
include ("../../lib/jfunciones.php");
sesion();
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="con_morbis">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Consultar  Morbilidad Medica</td>	</tr>	
		
		<tr>
		<td colspan=1 class="tdtitulos">* Seleccione  Proveedor.</td>
<td colspan=2>
                <select style="width: 400px;" name="proveedor" class="campos">
                <?php $q_pc=("select clinicas_proveedores.*,proveedores.id_proveedor from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                order by clinicas_proveedores.nombre");
                $r_pc=ejecutar($q_pc);
                ?>
                <option   value=""> Seleccione la Clinica</option>
                <?php
                while($f_pc=asignar_a($r_pc,NULL,PGSQL_ASSOC)){

                ?>  
                <option  value="<?php echo $f_pc[id_proveedor]?>"> <?php echo "$f_pc[nombre] $f_pc[direccion]"?></option>
                <?php
                }
                ?>
                </select>
</td>
		 <td class="tdtitulos"><a href="#" OnClick="con_medico3();" class="boton">Consultar</a></td>
	
        </tr>
		
</table>
<div id="con_medico3"></div>

</form>
