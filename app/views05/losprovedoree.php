<?php
include ("../../lib/jfunciones.php");
sesion();
$elvalorprove=$_REQUEST['paraprovee'];
if($elvalorprove==1){
      $elproveedor=("select proveedores.id_proveedor,s_p_proveedores.id_s_p_proveedor,s_p_proveedores.direccion_prov,
                         personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov 
                      from
                            proveedores,s_p_proveedores,personas_proveedores
                      where
                           personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and
                           s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
                           s_p_proveedores.activar=1
                       order by
                          personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov;");
        $repelproveedor=ejecutar($elproveedor);                  
    }else{
            $elproveedor=("select proveedores.id_proveedor,clinicas_proveedores.nombre 
  from
    proveedores,clinicas_proveedores
  where
    clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and
    clinicas_proveedores.activar=1 and
    clinicas_proveedores.prov_compra=1
  order by
    clinicas_proveedores.nombre;");
        $repelproveedor=ejecutar($elproveedor);  
            }
?>
 <select id="tipoprovee" class="campos" style="width: 210px;" >
      <option value=""></option>
    <? while($losprovee=asignar_a($repelproveedor,NULL,PGSQL_ASSOC)){
         if($elvalorprove==1){
               $datoproveedor="$losprovee[nombres_prov] $losprovee[apellidos_prov] -- $losprovee[direccion_prov]";
             }else{
                 $datoproveedor="$losprovee[nombre]";
                 }
       ?>
           <option value="<?echo  $losprovee[id_proveedor]?>"><?echo  $datoproveedor?></option>
    <?}?>                          
</select>