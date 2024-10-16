 <?php
include ("../../lib/jfunciones.php");
$buspolizas=("select polizas.id_poliza,polizas.nombre_poliza from polizas order by nombre_poliza;");
$rebuspoliza=ejecutar($buspolizas);
sesion();
$ejecute=0;
if($_REQUEST['polizaid']){
  $idpoliza=$_REQUEST['polizaid'];
  echo "<h1>$idpoliza</h1>";
  if($idpoliza!=0 || $idpoliza!='' || $idpoliza!=NULL){
      $ejecute=1;
      unset($_REQUEST['polizaid']);
  }
}


?>

 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=3 class="titulo_seccion">Control de primas</td>
	</tr>
  </table>
   <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
         <br>
           <tr>
             <td class="tdtitulos" colspan="1">Seleccione la P&oacute;liza:</td>
            <td class="tdcampos"  colspan="1">
            <select id="lapoliz" class="campos" onChange="buslapoliza()" style="width: 260px;">
              <option value="" ></option>
			     <?php  while($laspoliza=asignar_a($rebuspoliza,NULL,PGSQL_ASSOC)){
                          if($idpoliza==$laspoliza[id_poliza]){$selec="selected";}else{$selec='';}
             ?>
                              <option value="<?php echo $laspoliza[id_poliza]?>" <?php echo$selec;?> > <?php echo "$laspoliza[nombre_poliza]"?></option>
			     <?php
			  }
			  ?>
			 </select>
             </td>
            </tr>
  </table>

  <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />
  <div id='primapoliza'></div>
