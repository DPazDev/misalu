<?php
 include ("../../lib/jfunciones.php");
 sesion();
 $provet=$_POST['elppe'];
 if ($provet==1){
     $busprper=("select personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov,
                  s_p_proveedores.direccion_prov,proveedores.id_proveedor
               from
                  personas_proveedores,s_p_proveedores,proveedores
               where
                  personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and
                  s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and s_p_proveedores.activar='1' 
                  order by nombres_prov;");
    $cualprper=ejecutar($busprper);
   }else{
    $busclipro=("select clinicas_proveedores.nombre,proveedores.id_proveedor 
                  from clinicas_proveedores,proveedores 
                  where proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor order by nombre");
    $cualprcli=ejecutar($busclipro);
  }
 if ($provet==1){
?>
      <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
           <td class="tdtitulos" >Seleccione El Provedor:</td>
		</tr> 
		<tr>
           <td class="tdtitulos" colspan="2"><select id="propp" class="campos"  style="width: 490px;" >
                                <option value="0"></option>
                                <?php  while($elppr=asignar_a($cualprper,NULL,PGSQL_ASSOC)){?>
                                <option value="<?php echo $elppr[id_proveedor]?>"> <?php echo "$elppr[nombres_prov] $elppr[apellidos_prov] - ($elppr[direccion_prov])"?></option>
                                <?php
                                }?>
      </td>
      </tr> 
      </table>
<?php }else{?>
      <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0> 
      <tr>
         <td class="tdtitulos" >Seleccione El Provedor:</td>
	  </tr>	 
	  <tr> 
         <td class="tdtitulos" colspan="2"><select id="propp" class="campos"  style="width: 490px;" >
                                <option value="0"></option>
                                <?php  while($elppcl=asignar_a( $cualprcli,NULL,PGSQL_ASSOC)){?>
                                 <option value="<?php echo $elppcl[id_proveedor]?>"> <?php echo "$elppcl[nombre] $elppcl[id_proveedor]"?></option>
                                <?php
                                }?>
        </td>
       </tr>	
      </table>
<?php } ?>
