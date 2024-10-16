<?php
include ("../../lib/jfunciones.php");
sesion();
$enteid=$_POST['elidelente'];
$buscartpente=("select tbl_tipos_entes.id_tipo_ente,tbl_tipos_entes.tipo_ente from tbl_tipos_entes order by tbl_tipos_entes.tipo_ente;");
$repbusctpente=ejecutar($buscartpente);
$dataente=("select entes.*,ciudad.ciudad,ciudad.id_ciudad,sucursales.sucursal,sucursales.id_sucursal 
from
    entes,ciudad,sucursales
where
    entes.id_ciudad=ciudad.id_ciudad and
    entes.id_sucursal=sucursales.id_sucursal and
    entes.id_ente=$enteid;");
$repdatente=ejecutar($dataente);
$datadelente=assoc_a($repdatente);
//dato comisionado
$datcomision=("select entes_comisionados.descuento_recargo,entes_comisionados.porcentaje_comision,
                        comisionados.nombres,comisionados.apellidos,comisionados.id_comisionado 
                       from 
                          entes_comisionados,comisionados
                       where
                         entes_comisionados.id_ente=$enteid and
                        entes_comisionados.id_comisionado=comisionados.id_comisionado;");
 $repdatcomisio=ejecutar($datcomision);                       
 $datcomisio=assoc_a($repdatcomisio);
 $elcomision="$datcomisio[nombres] $datcomisio[apellidos]";
 $elcomisid=$datcomisio['id_comisionado'];
 $descuento=$datcomisio['descuento_recargo'];
 $porcentaj=$datcomisio['porcentaje_comision'];
//fin comisionado
$nomente=$datadelente['nombre'];
$telefonos=$datadelente['telefonos'];
$direccion=$datadelente['direccion'];
$correo=$datadelente['email'];
$rifente=$datadelente['rif'];
$nomcontac=$datadelente['nombre_contacto'];
$telefcontac=$datadelente['telefonos_contacto'];
$corrcontac=$datadelente['email_contacto'];
$faxente=$datadelente['fax'];
$eltpente=$datadelente['id_tipo_ente'];
$eltipoentees=("select tbl_tipos_entes.tipo_ente,tbl_tipos_entes.id_tipo_ente 
                          from tbl_tipos_entes where tbl_tipos_entes.id_tipo_ente=$eltpente;");
$reptipoentees=ejecutar($eltipoentees);      
$dataentes=assoc_a($reptipoentees);
$elnomentees=$dataentes[tipo_ente];
$elidentees=$dataentes[id_tipo_ente];
if($eltpente==1){
       $tpente="Natural";
    }else{
            if($eltpente==2){
                $tpente="Juridica";
            }else{
                  if($eltpente==3){
                    $tpente="Gubernamental";
                  }else{
                         if($eltpente==4){
                           $tpente="Privado";
                        }else{
                              $tpente="Sindicatos";
                            }
                      }
                }
        }
$laciudad=$datadelente['ciudad'];
$laciduid=$datadelente['id_ciudad'];
$fechaincont=$datadelente['fecha_inicio_contrato'];
$fechafincont=$datadelente['fecha_renovacion_contrato'];
$lasursal=$datadelente['sucursal'];
$lasucuid=$datadelente['id_sucursal'];
$formpago=$datadelente['forma_pago'];
$fechainconbe=$datadelente['fecha_inicio_contratob'];
$fechafinconbe=$datadelente['fecha_renovacion_contratob'];
$sucursales=("select sucursales.id_sucursal,sucursales.sucursal from sucursales order by sucursales.sucursal;");
$repsucursales=ejecutar($sucursales);
$comisionados=("select comisionados.id_comisionado,comisionados.nombres,comisionados.apellidos from comisionados order by comisionados.nombres;");
$repcomisionados=ejecutar($comisionados);
$ciudades=("select ciudad.id_ciudad,ciudad.ciudad from ciudad group by ciudad.ciudad,ciudad.id_ciudad order by ciudad.ciudad;");
$repciudades=ejecutar($ciudades);
$polizashay=("select polizas.id_poliza,polizas.nombre_poliza,polizas.descripcion from polizas order by polizas.nombre_poliza;");
$reppolizas=ejecutar($polizashay);
?>
  <input type="hidden" id="iddelente" value="<?echo $enteid?>">
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Modificar Ente</td>  
        
	</tr>
  </table>
  <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
         <br>
           <tr>
            
             <td class="tdtitulos">Nombre del ente:</td>
               <td class="tdcampos"><input type="text" name="nomente" id="nomente" class="campos" size="35" value="<?echo $nomente?>"> </td>            
             
              <td class="tdtitulos">Tipo de ente:</td>  
                <td class="tdtitulos">  
                        <select class="campos" id="tipo_ente" style="width: 230px">
                           <option value="<?echo $elidentees?>"><?echo $elnomentees?></option>
                           <?php  
			                 while($losentesons=asignar_a($repbusctpente,NULL,PGSQL_ASSOC)){
				           ?>
					        <option value="<?php echo $losentesons[id_tipo_ente]?>"> <?php echo "$losentesons[tipo_ente]"?></option>
			             <?}?>
		        </select>  
              </td>       
             </tr>   
			 <tr>
             <td class="tdtitulos">RIF:</td>
             <td class="tdcampos"><input type="text" id="rifente" class="campos" size="35" value="<?echo $rifente?>"></td>
              <td class="tdtitulos">Correo:</td>  
	          <td class="tdtitulos"><input type="text" id="corrente" class="campos" size="35" value="<?echo $correo?>"></td>           
             </tr>  
             <tr>
             <td class="tdtitulos">Tel&eacute;fono:</td>
             <td class="tdcampos"><input type="text" id="entetelef" class="campos" size="35" value="<?echo $telefonos?>"></td>
              <td class="tdtitulos">Fax:</td>  
	          <td class="tdtitulos"><input type="text" id="entefax" class="campos" size="35" value="<?echo $faxente?>"></td>           
             </tr>   
               <tr>
             <td class="tdtitulos">Direcci&oacute;n:</td>
             <td class="tdcampos"><TEXTAREA COLS=45 ROWS=3 id="direccente" class="campos"><?echo $direccion?></TEXTAREA>
             </td>  
             <td class="tdtitulos">Ciudad:</td>
                <td class="tdcampos">
                   <select id="ciudad" class="campos"  style="width: 230px;" >
			       <option value="<?echo $laciduid?>"><?echo $laciudad?></option>
                    <?php  
			         while($lasciudades=asignar_a($repciudades,NULL,PGSQL_ASSOC)){
				    ?>
					<option value="<?php echo $lasciudades[id_ciudad]?>"> <?php echo "$lasciudades[ciudad]"?></option>
			      <?}?>
		        </select>  
                </td>
             </tr>   
		     <tr>
             <td class="tdtitulos" colspan=4><hr></td>
             </tr>
              <tr>
                <td class="tdtitulos">Sucursal de contrato:</td>
                <td class="tdcampos">
                   <select id="sucursal" class="campos"  style="width: 230px;" >
			       <option value="<?echo $lasucuid?>"><?echo $lasursal?></option>
                    <?php  
			         while($lassucur=asignar_a($repsucursales,NULL,PGSQL_ASSOC)){
				    ?>
					<option value="<?php echo $lassucur[id_sucursal]?>"> <?php echo "$lassucur[sucursal]"?></option>
			      <?}?>
		        </select>  
                </td>
                <td class="tdtitulos">Comisionado:</td>  
	            <td class="tdtitulos">
                     <select id="comisionado" class="campos"  style="width: 230px;" >
			        <option value="<?echo $elcomisid?>"><?echo $elcomision?></option>
                    <?php  
			             while($loscomi=asignar_a($repcomisionados,NULL,PGSQL_ASSOC)){
				      ?>
					    <option value="<?php echo $loscomi[id_comisionado]?>"> <?php echo "$loscomi[nombres] $loscomi[apellidos]"?></option>
			      <?}?>
		        </select>  
                </td>           
             </tr> 
             <tr>
             <td class="tdtitulos">Inicio de contrato titular:</td>
             <td class="tdcampos"><input readonly type="text" size="10" id="Fini" class="campos" maxlength="10" value="<?echo $fechaincont?>">
	<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fini', 'yyyy-mm-dd')" title="Ver calendario">
	<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
              <td class="tdtitulos">Fin de contrato titular:</td>  
	          <td class="tdtitulos"><input readonly type="text" size="10" id="Fifi" class="campos" maxlength="10" value="<?echo $fechafincont?>">
	<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fifi', 'yyyy-mm-dd')" title="Ver calendario">
	<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>           
             </tr>   
             
             <tr>
             <td class="tdtitulos">Inicio de contrato beneficiario:</td>
             <td class="tdcampos"><input readonly type="text" size="10" id="Finiben" class="campos" maxlength="10" value="<?echo $fechainconbe?>">
	<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Finiben', 'yyyy-mm-dd')" title="Ver calendario">
	<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
              <td class="tdtitulos">Fin de contrato beneficiario:</td>  
	          <td class="tdtitulos"><input readonly type="text" size="10" id="Fifiben" class="campos" maxlength="10" value="<?echo $fechafinconbe?>">
	<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fifiben', 'yyyy-mm-dd')" title="Ver calendario">
	<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>           
             </tr>   
             
              <tr>
             <td class="tdtitulos">Monto contrato:</td>
             <td class="tdtitulos"><input type="text" id="montcontrato" class="campos" onKeyUp="validadnume(this.value)" size="20" >Bs.S</td>
              <td class="tdtitulos">Porcentaje Comisi&oacute;n:</td>  
	          <td class="tdtitulos"><input type="text" id="porcomision" class="campos" onKeyUp="validadnume(this.value)" size="20" value="<?echo $porcentaj?>">%</td>           
             </tr>  
              <tr>
             <td class="tdtitulos">Tipo de descuento:</td>
             <td class="tdtitulos">
                     <select class="campos" id="descuento">
                          <option value="<?echo  $descuento?>"><?echo  $descuento?></option>
                          <option value="PRIMERA RENOVACION">Primera Renovaci&oacute;n</option>
                          <option value="SEGUNDA RENOVACION">Segunda Renovaci&oacute;n</option>
                          <option value="CONTRATO DIRECTO">Contrato Directo</option>
                         <option value="EMPLEADO">Empleado</option>
                         <option value="5% RECARGO">5% Recargo</option>
                         <option value="10% RECARGO">10% Recargo</option>
                </select>
             </td>
              <td class="tdtitulos">Forma de pago:</td>  
	          <td class="tdtitulos">
                      <select class="campos" id="forma_pago">
                        <option value="<?echo $formpago?>"><?echo $formpago?></option>
                        <option value="ANUAL">Anual</option>
                        <option value="SEMESTRAL">Semestral</option>
                        <option value="TRIMESTRAL">Trimestral</option>
                        <option value="MENSUAL">Mensual</option>
                        </select>
              </td>           
             </tr>
             <tr>
             <td class="tdtitulos" colspan=4><hr></td>
             </tr>
              <tr>
             <td class="tdtitulos">Nombre del contacto:</td>
             <td class="tdtitulos"><input type="text" id="nombrcontacto" class="campos"  size="30" value="<?echo $nomcontac?>"></td>
              <td class="tdtitulos">Tel&eacute;fono del contacto:</td>  
	          <td class="tdtitulos"><input type="text" id="telecontacto" class="campos"  size="30" value="<?echo $telefcontac?>"></td>           
             </tr>  
               <tr>
             <td class="tdtitulos">Correo del contacto:</td>
             <td class="tdtitulos"><input type="text" id="correocontacto" class="campos"  size="30" value="<?echo $corrcontac?>"></td>
              <td class="tdtitulos">Direcci&oacute;n del contacto:</td>  
	          <td class="tdtitulos"><TEXTAREA COLS=37 ROWS=3 id="direcccontacto" class="campos"><?echo $direccion?></TEXTAREA></td>           
             </tr>
             <tr>
             <td class="tdtitulos" colspan=4><hr></td>
             </tr>
			<tr>
             <td class="tdtitulos"></td>
             <td class="tdcampos"></td>
            </tr>
             <input type="hidden" id="tocajas" value="<?echo $i;?>">
       <tr>
              <td><label title="Registrar cambio en ente" id="enteguardado1" class="boton" style="cursor:pointer" onclick="modiffinelennte()" >Guardar</label></td>  
        </tr>
        </table>   
	  
	  <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
	  <div id='enteguardado'></div>  
