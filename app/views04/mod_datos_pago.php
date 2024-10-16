<?php
include ("../../lib/jfunciones.php");
sesion();

$admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$tipoadmin=$f_admin['id_tipo_admin'];


$q_mod_res=("select * from permisos where permisos.id_admin='$admin' and permisos.id_modulo=21");
$r_mod_res=ejecutar($q_mod_res);
$f_mod_res=asignar_a($r_mod_res);

if ($f_mod_res[permiso]=='1'){
    $enablemr="";
}
else
{
    $enablemr="disabled";
}

$factura    = $_REQUEST['factura'];
$id_factura = $_REQUEST['id_factura'];
 
?>

<fieldset class="tipos-pagos2">
	 <div class="item"> 
       <label class="tdtituloss">Forma de pago</label>
	      <?php
                //busco los tipos de pagos.
                $q_tipo_pago="select * from tbl_tipos_pagos order by tbl_tipos_pagos.tipo_pago";
                $r_tipo_pago=ejecutar($q_tipo_pago);
	       ?>
	  </div>
   <div>
        <select id="forma_pago" name="forma_pago" class="campos" OnChange="modifpago2();">
          <option value="0">Seleccione la Forma de Pago</option>
         <?php 
            while($f_tipo_pago=asignar_a($r_tipo_pago)){
		    	?>
          <option value="<?php echo $f_tipo_pago[id_tipo_pago]?>">
                      <?php echo $f_tipo_pago[tipo_pago]?></option>";
                                              <?php
                      			}
                      			?>
        </select>
   </div>



    <div class="item">
        <label class="tdtituloss">Moneda</label>
         <?php
                //busco los tipos de monedas
                $q_tipo_moneda="select * from tbl_monedas order by tbl_monedas.id_moneda";
                $r_tipo_moneda=ejecutar($q_tipo_moneda);
          ?>
       </div>   
     <div>
        <select id="tipo_moneda" name="tipo_moneda" class="campos" OnChange="modifpago2();">
       <?php
        while($moneda=asignar_a($r_tipo_moneda,NULL,PGSQL_ASSOC)){
        if($moneda[id_moneda]==1){?>
          <option value="<?php echo $moneda[id_moneda]?>" select="select"><?php echo $moneda[moneda]?></option>";
       <?php }
        else{
        ?>
         <option value="<?php echo $moneda[id_moneda]?>"><?php echo $moneda[moneda]?></option>";
        <?php  }
           }
         ?>
       </select>
   </div>

</fieldset>

<br>
	<div id="modif_datos_pago_fac"></div>
