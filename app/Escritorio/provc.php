<?php
include ("../../lib/jfunciones.php");
sesion();
$formp1=$_REQUEST['formp1'];
$monto=$_REQUEST['monto'];
$tiposerv=$_REQUEST['tiposerv'];
$donativo=$_REQUEST['donativo'];

$q_cobertura="select * from entes,titulares,coberturas_t_b where entes.id_ente=titulares.id_ente and titulares.id_titular=coberturas_t_b.id_titular and coberturas_t_b.id_cobertura_t_b='$formp1'";
$r_cobertura=ejecutar($q_cobertura);
$f_cobertura=asignar_a($r_cobertura);

if ($monto>$f_cobertura[monto_actual] && $donativo==0)
{
	?>
	<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=4 class="titulo_seccion" >
El Monto a Cargar Sobrepasa La Cobertura 
             </td> 
</tr>
</table>         
	<?php
	
	}
else
{


?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=2>
                <select style="width: 200px;" name="proveedor" class="campos">
                <?php $q_pc=("select clinicas_proveedores.*,proveedores.id_proveedor from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                and clinicas_proveedores.activar=1 order by clinicas_proveedores.nombre");
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
<?php if (($tiposerv==6) || ($tiposerv==8) || ($tiposerv==12)){
	?>
<td colspan=2 class="tdtitulos"><a href="#" OnClick="guardaroa('clientes');" class="boton">Guardar</a></td>
<?php }
else 
{
	?>
	<td colspan=2 class="tdtitulos"><a href="#" OnClick="guardaroac('clientes');" class="boton">Guardar</a></td>
	<?php
	}
	?>
</tr>
</table>
<?php
}
?>

