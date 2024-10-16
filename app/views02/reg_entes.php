<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$sucursales=("select sucursales.id_sucursal,sucursales.sucursal from sucursales order by sucursales.sucursal;");
$repsucursales=ejecutar($sucursales);
$buscartpente=("select tbl_tipos_entes.id_tipo_ente,tbl_tipos_entes.tipo_ente from tbl_tipos_entes order by tbl_tipos_entes.tipo_ente;");
$repbusctpente=ejecutar($buscartpente);
$comisionados=("select comisionados.id_comisionado,comisionados.nombres,comisionados.apellidos from comisionados order by comisionados.nombres;");
$repcomisionados=ejecutar($comisionados);
$ciudades=("select ciudad.id_ciudad,ciudad.ciudad from ciudad group by ciudad.ciudad,ciudad.id_ciudad order by ciudad.ciudad;");
$repciudades=ejecutar($ciudades);
$polizashay=("select polizas.id_poliza,polizas.nombre_poliza,polizas.descripcion from polizas where polizas.activar=1 order by polizas.nombre_poliza;");
$reppolizas=ejecutar($polizashay);
?>
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Registrar Ente</td>  
        <td colspan=1 class="titulo_seccion" title="Ver ente(s) creados"><label class="boton" style="cursor:pointer" onclick="ver_entes()" >Ver entes</label></td> 
	</tr>
  </table>
  <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
         <br>
           <tr>
            
             <td class="tdtitulos">Nombre del ente:</td>
               <td class="tdcampos"><input type="text" name="nomente" id="nomente" class="campos" size="35" onblur="validarcampo('ente')"><div id='nomdelente'> </div> </td>            
             
              <td class="tdtitulos">Tipo de ente:</td>  
               <td class="tdtitulos">  
                        <select class="campos" id="tipo_ente" style="width: 230px">
                           <option value="">--Seleccione un Tipo de Ente--</option>
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
             <td class="tdcampos"><input type="text" id="rifente" class="campos" size="35" onblur="validarcampo('rif')"><div id='rifdelente'> </div></td>
              <td class="tdtitulos">Correo:</td>  
	          <td class="tdtitulos"><input type="text" id="corrente" class="campos" size="35"></td>           
             </tr>  
             <tr>
             <td class="tdtitulos">Tel&eacute;fono:</td>
             <td class="tdcampos"><input type="text" id="entetelef" class="campos" size="35"></td>
              <td class="tdtitulos">Fax:</td>  
	          <td class="tdtitulos"><input type="text" id="entefax" class="campos" size="35"></td>           
             </tr>   
               <tr>
             <td class="tdtitulos">Direcci&oacute;n:</td>
             <td class="tdcampos"><TEXTAREA COLS=45 ROWS=3 id="direccente" class="campos" onblur="validarcampo('direccion')"></TEXTAREA><div id='dirente'></div>
             </td>  
             <td class="tdtitulos">Ciudad:</td>
                <td class="tdcampos">
                   <select id="ciudad" class="campos"  style="width: 230px;" >
			       <option value=""></option>
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
			       <option value=""></option>
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
			        <option value=""></option>
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
             <td class="tdcampos"><input readonly type="text" size="10" id="Fini" class="campos" maxlength="10">
	<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fini', 'yyyy-mm-dd')" title="Ver calendario">
	<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
              <td class="tdtitulos">Fin de contrato titular:</td>  
	          <td class="tdtitulos"><input readonly type="text" size="10" id="Fifi" class="campos" maxlength="10">
	<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fifi', 'yyyy-mm-dd')" title="Ver calendario">
	<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>           
             </tr>   
             
             <tr>
             <td class="tdtitulos">Inicio de contrato beneficiario:</td>
             <td class="tdcampos"><input readonly type="text" size="10" id="Finiben" class="campos" maxlength="10">
	<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Finiben', 'yyyy-mm-dd')" title="Ver calendario">
	<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
              <td class="tdtitulos">Fin de contrato beneficiario:</td>  
	          <td class="tdtitulos"><input readonly type="text" size="10" id="Fifiben" class="campos" maxlength="10">
	<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fifiben', 'yyyy-mm-dd')" title="Ver calendario">
	<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>           
             </tr>   
             
              <tr>
             <td class="tdtitulos">Monto contrato:</td>
             <td class="tdtitulos"><input type="text" id="montcontrato" class="campos" onKeyUp="validadnume(this.value)" size="20" >Bs.S</td>
              <td class="tdtitulos">Porcentaje Comisi&oacute;n:</td>  
	          <td class="tdtitulos"><input type="text" id="porcomision" class="campos" onKeyUp="validadnume(this.value)" size="20">%</td>           
             </tr>  
              <tr>
             <td class="tdtitulos">Tipo de descuento:</td>
             <td class="tdtitulos">
                     <select class="campos" id="descuento">
                          <option value="">--Seleccione un Descuento--</option>
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
                        <option value="">--Selecione una Forma de Pago--</option>
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
             <td class="tdtitulos"><input type="text" id="nombrcontacto" class="campos"  size="30" ></td>
              <td class="tdtitulos">Tel&eacute;fono del contacto:</td>  
	          <td class="tdtitulos"><input type="text" id="telecontacto" class="campos"  size="30"></td>           
             </tr>  
               <tr>
             <td class="tdtitulos">Correo del contacto:</td>
             <td class="tdtitulos"><input type="text" id="correocontacto" class="campos"  size="30" ></td>
              <td class="tdtitulos">Direcci&oacute;n del contacto:</td>  
	          <td class="tdtitulos"><TEXTAREA COLS=37 ROWS=3 id="direcccontacto" class="campos"></TEXTAREA></td>           
             </tr>
             <tr>
             <td class="tdtitulos" colspan=4><hr></td>
             </tr>
			<tr>
             <td class="tdtitulos"></td>
             <td class="tdcampos"></td>
            </tr>
        </table>   
	   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
          <tr> 
             <td colspan=3 class="titulo_seccion">P&oacute;lizas registradas</td>            
	      </tr>
     </table>
     <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Num.</th>
                 <th class="tdtitulos">P&oacute;liza.</th>
                 <th class="tdtitulos">Descripci&oacute;n.</th>
                 <th class="tdtitulos">Selecci&oacute;n.</th>
            </tr>
            <?
        $i=1;
        while($laspolizason=asignar_a($reppolizas,NULL,PGSQL_ASSOC)){?>
            <tr>
                    <td class="tdcampos"><?echo $i;?></td>
                    <td class="tdcampos"><?echo $laspolizason['nombre_poliza'];?></td>
                    <td class="tdcampos"><?echo $laspolizason['descripcion'];?></td>
                    <td class="tdcampos"><INPUT TYPE=CHECKBOX id="<?echo "polizas$i";?>" value="<? echo $laspolizason[id_poliza]?>"></td>
            <tr> 
            <tr>
             <td class="tdtitulos" colspan=4><hr></td>
             </tr>
      <?  
        $i++;
        } 
      ?>
         <input type="hidden" id="tocajas" value="<?echo $i;?>">
       <tr>
              <td><label title="Registrar ente" id="enteguardado1" class="boton" style="cursor:pointer" onclick="guardarente()" >Guardar</label></td>  
        </tr>
      </table>
	  <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
	  <div id='enteguardado'></div>  
      <div id='losentes'></div>
