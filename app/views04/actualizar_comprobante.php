<?php 
include ("../../lib/jfunciones.php");

$q_comprobante="select actividades_pro.id_act_pro,facturas_procesos.id_factura_proceso,
facturas_procesos.id_proveedor,facturas_procesos.tipo_proveedor from actividades_pro,facturas_procesos,personas_proveedores where
 personas_proveedores.id_persona_proveedor=facturas_procesos.id_proveedor and 
facturas_procesos.tipo_proveedor=1 and personas_proveedores.id_act_pro=actividades_pro.id_act_pro;
";
$r_comprobante=ejecutar($q_comprobante);

while($f_comprobante=asignar_a($r_comprobante,NULL,PGSQL_ASSOC)){
$i++;

$mod_fpro="update facturas_procesos set id_act_pro=$f_comprobante[id_act_pro] where  facturas_procesos.id_factura_proceso='$f_comprobante[id_factura_proceso]'";
$fmod_fpro=ejecutar($mod_fpro);

}
echo $i;
?>