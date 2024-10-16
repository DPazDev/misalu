<?
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$tablatemp="nusuar$elid";
$tablatemp1="caranusuar$elid";
$eli1="DROP TABLE IF EXISTS $tablatemp";
$reli1=ejecutar($eli1);
$eli2="DROP TABLE IF EXISTS $tablatemp1";
$reli1=ejecutar($eli2);
$tipousu=("select tipos_admin.id_tipo_admin from tipos_admin,admin
where
admin.id_tipo_admin=tipos_admin.id_tipo_admin and
admin.id_admin=$elid");
$reptipousu=ejecutar($tipousu);
$datatipousu=assoc_a($reptipousu);
$elusues=$datatipousu[id_tipo_admin];
$quepoli="";
if($elusues==18){
	$quepoli="and polizas.intermediario=1";
}
$buspoliza=("select polizas.id_poliza,polizas.nombre_poliza from polizas where polizas.particular=1 and
                      polizas.maternidad=0 and polizas.activar=1 $quepoli order by nombre_poliza;");
$repbupoliza=ejecutar($buspoliza);
$buspolizamater=("select polizas.id_poliza,polizas.nombre_poliza from polizas where polizas.particular=1
                                and polizas.maternidad=1 and polizas.activar=1 $quepoli order by nombre_poliza;");
$reppolimater=ejecutar($buspolizamater);
$cedulaclien=$_REQUEST[lacedula];
$buscaclien=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.telefono_hab,clientes.edad,
clientes.email,clientes.fecha_nacimiento from clientes where clientes.cedula='$cedulaclien' and clientes.cedula<>'';");
 $repbusclien=ejecutar($buscaclien);
 $databusclien=assoc_a($repbusclien);
 $laceduclien=$databusclien[cedula];
 $lanomclien=$databusclien[nombres];
 $laapellclien=$databusclien[apellidos];
 $laedaclien=calcular_edad($databusclien[fecha_nacimiento]);
 if($laedaclien>100){
	 $laedaclien=0;
 }
 $latelefclien=$databusclien[telefono_hab];
 $lacorreoclien=$databusclien[email];
?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=4 class="titulo_seccion">Cotizaci&oacute;n de planes</td>
     </tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
       <td class="tdtitulos">RIF/C&eacute;dula:</td>
       <td class="tdcampos" ><input type="text" id="cliencedula" class="campos"  onblur="Quepaso(this.value)" value="<?echo $laceduclien?>"> <div id="siexiste"></div> </td>
     </tr>
	 <tr>
	     <td class="tdtitulos">Nombre:</td>
       <td class="tdcampos" ><input type="text" id="cliennombre" class="campos" size="30" value="<?echo $lanomclien?>">  </td>
       <td class="tdtitulos">Apellido:</td>
       <td class="tdcampos" ><input type="text" id="clienapellido" class="campos" size="30" value="<?echo $laapellclien?>">  </td>
	 </tr>
     <tr>
       <td class="tdtitulos">Genero:</td>
       <td class="tdcampos" >
          <select id="cliengenero" class="campos" style="width: 160px;">
                              <option value=""></option>
                              <option value="0">Femenino</option>
                              <option value="1">Masculino</option>
         </select>
       </td>
	   <td class="tdtitulos">Edad:</td>
       <td class="tdcampos" ><input type="text" id="clienedad" class="campos"   onkeypress="return isNumberKey(event)"  size="10" value="<?echo $laedaclien?>">  </td>

	 </tr>
     <tr>
        <td class="tdtitulos">Tel&eacute;fono:</td>
       <td class="tdcampos" ><input type="text" id="clientelefono" class="campos" size="25" value="<?echo $latelefclien?>">  </td>
       <td class="tdtitulos">Correo:</td>
       <td class="tdcampos" ><input type="text" id="cliencorreo" class="campos"  size="30" value="<?echo $lacorreoclien?>">  </td>

    </tr>
     <tr>
       <td class="tdtitulos">Tipo p&oacute;liza:</td>
       <td class="tdcampos" >
          <select id="clienpoliza" class="campos" style="width: 160px;" onchange="CaractPoliza(this.value);poliza_rangos_edad(this.value);">
                              <option value=""></option>
			   <?php  while($verpoli=asignar_a($repbupoliza,NULL,PGSQL_ASSOC)){
                                  $sumcarpoli=("select sum(cast(propiedades_poliza.monto as double precision)) from propiedades_poliza where id_poliza=$verpoli[id_poliza]");
				  $repsumcarpoli=ejecutar($sumcarpoli);
				  $datasumpoli=assoc_a($repsumcarpoli);
                           ?>
                              <option value="<?php echo $verpoli[id_poliza]?>"> <?php echo "$verpoli[nombre_poliza]---(Bs.S.$datasumpoli[sum])"?></option>
			   <?php
			  }
			  ?>
			 </select>
       <br>
      <div id="caractpoliza"></div>
       </td>
        <td class="tdtitulos">Maternidad:</td>
       <td class="tdcampos" >
          <select id="clienmaterni" class="campos" style="width: 160px;">
                              <option value=""></option>
			   <?php  while($verpolimate=asignar_a($reppolimater,NULL,PGSQL_ASSOC)){
                                  $sumcarpoli1=("select sum(cast(propiedades_poliza.monto as double precision)) from propiedades_poliza where id_poliza=$verpolimate[id_poliza]");
				   $repsumcarpoli1=ejecutar($sumcarpoli1);
				   $datasumpoli1=assoc_a($repsumcarpoli1);
                               ?>
                              <option value="<?php echo $verpolimate[id_poliza]?>"> <?php echo "$verpolimate[nombre_poliza]---(Bs.S.$datasumpoli1[sum])"?></option>
			   <?php
			  }
			  ?>
			 </select>
       </td>
     </tr>
     <tr>
         <td class="tdtitulos">% Inicial:</td>
        <td class="tdcampos" ><input type="text" id="clieninicial" class="campos" size="8" value="40">  </td>
        <td class="tdtitulos">No. Cuotas:</td>
        <td class="tdcampos" ><input type="text" id="clienincuotas" class="campos"   size="8" value="6">  </td>
     </tr>
      <tr>
         <td class="tdtitulos">Tipo de cliente:</td>
         <td class="tdcampos" >
           <input type="radio" name="group1" id="clititula" value="1"> Titular<br>
           <input type="radio" name="group1" id="clittoma"  value="0">Tomador</td>
     </tr>
     </table>
      <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=4 class="titulo_seccion">Carga familiar</td>
     </tr>
    </table>


		<div id='rango_edades'>
			<style>
			#tb_rangos td,th{ height:30px; border: 10px; border-top: 5px red;}"
			</style>
    <table class="tabla_citas colortable" id="tb_rangos" cellpadding=0 cellspacing=0 >
              <tr>
			     <th class="tdtitulos">Edades.</th>
                 <th class="tdtitulos">Hombre.</th>
                 <th class="tdtitulos">Mujer.</th>
             </tr>
              <tr>
								<td class="tdcampos" >
									<input type="hidden" id="edadr1" class="campos"  style="width: 70px;" value="0-9" readonly><span style="font-size:20px">0 - 9</span>
								</td>
                   <td class="tdcampos" >
											<input type="number"  id="edadh1" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
									 </td>
			             <td class="tdcampos" >
			                <input type="number"  id="edadm1" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
						       </td>
              </tr>


              <tr>
								<td class="tdcampos" >
								 <input type="hidden" id="edadr2" class="campos"  style="width: 70px;" value="10-19" readonly><span style="font-size:20px">10-19</span>
							 </td>
									 <td class="tdcampos" >
											<input type="number"  id="edadh2" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
									 </td>
			             <td class="tdcampos" >
			                <input type="number"  id="edadm2" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
						       </td>

              </tr>

              <tr>
									 <td class="tdcampos" >
									  	<input type="hidden" id="edadr3" class="campos"  style="width: 70px;" value="20-29" readonly><span style="font-size:20px">20-29</span>
								   </td>
									 <td class="tdcampos" >
											<input type="number"  id="edadh3" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
									 </td>
			             <td class="tdcampos" >
			                <input type="number"  id="edadm3" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
						       </td>
              </tr>

              <tr>
									 <td class="tdcampos" >
										 	<input type="hidden" id="edadr4" class="campos"  style="width: 70px;" value="30-39" readonly><span style="font-size:20px">30-39</span>
									 </td>
									 <td class="tdcampos" >
											<input type="number"  id="edadh4" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
									 </td>
			             <td class="tdcampos" >
			                <input type="number"  id="edadm4" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
						       </td>
              </tr>

							<tr>
                   <td class="tdcampos" >
										 <input type="hidden" id="edadR5" class="campos"  style="width: 70px;" value="40-49"  readonly><span style="font-size:20px">40-49</span>
									 </td>
									 <td class="tdcampos" >
											<input type="number"  id="edadh5" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
									 </td>
			             <td class="tdcampos" >
			                <input type="number"  id="edadm5" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
						       </td>
              </tr>

              <tr>
									 <td class="tdcampos" >
											<input type="hidden" id="edadr6" class="campos"  style="width: 70px;" value="50-54" readonly><span style="font-size:20px">50-54</span>
									 </td>
									 <td class="tdcampos" >
											<input type="number"  id="edadh6" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
									 </td>
			             <td class="tdcampos" >
			                <input type="number"  id="edadm6" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
						       </td>
              </tr>

              <tr>
									 <td class="tdcampos" >
											 <input type="hidden" id="edadr7" class="campos"  style="width: 70px;" value="55-59" readonly><span style="font-size:20px">55-59</span>
									 </td>
									 <td class="tdcampos" >
											<input type="number"  id="edadh7" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
									 </td>
			             <td class="tdcampos" >
			                <input type="number"  id="edadm7" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
						       </td>
              </tr>

              <tr>
									 <td class="tdcampos" >
											 <input type="hidden" id="edadr8" class="campos"  style="width: 70px;" value="60-69" readonly><span style="font-size:20px">60-69</span>
									 </td>
									 <td class="tdcampos" >
											<input type="number"  id="edadh8" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
									 </td>
			             <td class="tdcampos" >
			                <input type="number"  id="edadm8" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
						       </td>
              </tr>

              <tr>
									 <td class="tdcampos" >
										 	<input type="hidden" id="edadr9" class="campos"  style="width: 70px;" value="70-79" readonly><span style="font-size:20px">70-79</span>
								   </td>
									 <td class="tdcampos" >
											<input type="number"  id="edadh9" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
									 </td>
			             <td class="tdcampos" >
			                <input type="number"  id="edadm9" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
						       </td>
              </tr>

              <tr>
									 <td class="tdcampos" >
												<input type="hidden" id="edadr8" class="campos"  style="width: 70px;" value="80-120" readonly><span style="font-size:20px">80-120</span>
									 </td>
									 <td class="tdcampos" >
											<input type="number"  id="edadh10" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
									 </td>
			             <td class="tdcampos" >
			                <input type="number"  id="edadm10" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
						       </td>

              </tr>
              <tr><tb><input type="hidden" id="cantidadrango" value="10"><tb><tr>
    </table>
		</div>
		<table class="tabla_citas colortable"  cellpadding=0 cellspacing=0 >
					<tr style="text-align: center;">
                <td title="Calcular cotizaci&oacute;n individual" ><br><label class="boton" style="cursor:pointer" onclick="recalcotizindi()" >Calcular</label></td>
                <td title="Guardar cotizaci&oacute;n individual"><br><label class="boton" style="cursor:pointer" onclick="guarcotizindi()" >Guardar</label></td>
                <td title="Guardar cotizaci&oacute;n individual"><br><label class="boton" style="cursor:pointer" onclick="cliente_individuales()" >Nueva Cotizaci&oacute;n</label></td>
           </tr>
   </table>

   <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />
   <div id="cotiindi"></div>
