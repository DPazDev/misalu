<?php
include ("../../lib/jfunciones.php");
sesion();
$ClasifiaProveedor=$_REQUEST['clasificaproveedor'];
 if($ClasifiaProveedor=='0' || $ClasifiaProveedor=='1') {
 	//filtrar por clasificacion si son intramurales o extramurales
 $q_pc=("select clinicas_proveedores.nombre,proveedores.id_proveedor
                    from clinicas_proveedores,proveedores
                    where
                     clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                     and clinicas_proveedores.activar=1
                     and proveedores.tipo_proveedor=$ClasifiaProveedor
                     and clinicas_proveedores.prov_compra =0
                     order by clinicas_proveedores.nombre;");
  }else {  //todos
                $q_pc=("select clinicas_proveedores.nombre,proveedores.id_proveedor from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                and clinicas_proveedores.activar=1 order by clinicas_proveedores.nombre");}

$repbuscaproveedores=ejecutar($q_pc);
  ?>
<select id='idprovee' class='campos'  style='width: 230px;' >
    <?php
    if($opera1=='externa'){?>
              <option value="698">FARMA EXPRESS</option>
                       <?php }?>
                          <option value=""></option>
              <?php while($losproveedores=asignar_a($repbuscaproveedores,NULL,PGSQL_ASSOC)){
              echo"<option value=\"$losproveedores[id_proveedor]\">
              $losproveedores[nombre]
              </option>";
              }
              echo"</select>";

 ?>
