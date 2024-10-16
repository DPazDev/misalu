<?
include ("../../lib/jfunciones.php");
sesion();
$verlosmedicamentos=("select procesos.id_proceso,count(gastos_t_b.id_proceso),gastos_t_b.descripcion 
   from procesos,gastos_t_b 
where 
   procesos.id_proceso=gastos_t_b.id_proceso and
   procesos.id_admin=admin.id_admin and
   admin.id_sucursal=4 and
   procesos.fecha_creado>='2011-01-01' and 
   gastos_t_b.id_servicio=5
group by
       procesos.id_proceso,gastos_t_b.descripcion;");
$repverlosmedi=ejecutar($verlosmedicamentos);

$i=0;
while($losmedi=asignar_a($repverlosmedi,NULL,PGSQL_ASSOC)){
        $nombremedi=$losmedi['descripcion'];
        $elproceso=$losmedi['id_proceso'];
        $buscarelid=("select tbl_insumos.id_insumo from tbl_insumos where insumo='$nombremedi' and tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and tbl_insumos_almacen.id_dependencia=37;");
        $repbuscarelid=ejecutar($buscarelid);
        $datainsumo=assoc_a($repbuscarelid);
        $elidinsumo=$datainsumo['id_insumo'];
        if($elidinsumo>0){
           $actualizogtb=("update gastos_t_b set id_insumo=$elidinsumo where id_proceso=$elproceso and id_servicio=5 and descripcion='$nombremedi';");
           $repactualizogtb=ejecutar($actualizogtb);
          echo "----->actualizando $i<--------el insumo id: $elidinsumo<br>";
          $i++;
        }
}
echo "Se ha terminado la actualizacion";
?>
