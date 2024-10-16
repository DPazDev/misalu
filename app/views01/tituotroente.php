<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $cedclien=$_POST["laceduc"];  
  $elidties=("select id_cliente from clientes where cedula='$cedclien';");
  $respelidtes=ejecutar($elidties) ;
  $datdelclient=assoc_a($respelidtes);  
  $elidclientes= $datdelclient[id_cliente];
  $losestatus=("select estados_clientes.id_estado_cliente,estados_clientes.estado_cliente from estados_clientes order by estado_cliente;");
  $resestatus=ejecutar($losestatus);
   $lossubdiv=("select subdivisiones.id_subdivision,subdivisiones.subdivision from subdivisiones order by subdivision");
   $ressubdiv=ejecutar($lossubdiv);  
  $buscarcliente=("select * from clientes where cedula='$cedclien';");
  $resultacliente=ejecutar($buscarcliente);
  $loscargos=("select cargos.id_cargo,cargos.cargo from cargos order by cargo");
  $resloscargo=ejecutar($loscargos);
  $admin= $_SESSION['id_usuario_'.empresa];
  $q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$hoy=date("Y-m-d");
 $cuantpermiso=0;
$buscarpermiso=("select permisos.permiso from permisos where permisos.id_admin=$f_admin[id_admin] and permisos.id_modulo=7;");
$repbuscarpermis=ejecutar($buscarpermiso);
$cuantpermiso=asignar_a($repbuscarpermis);
$cuantpermiso=$cuantpermiso[permiso];

if($cuantpermiso==1){
$losentes=("select entes.id_ente,entes.nombre,tbl_tipos_entes.tipo_ente from entes,tbl_tipos_entes where entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente order by entes.nombre");
$reslosentes=ejecutar($losentes);

}else{
    
  $losentes=("select entes.id_ente,entes.nombre,tbl_tipos_entes.tipo_ente from entes,tbl_tipos_entes where (entes.id_tipo_ente=4 or entes.id_tipo_ente=6 or entes.id_tipo_ente=8) and  entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente  order by entes.nombre");
  $reslosentes=ejecutar($losentes);
}
  

  $daclient=assoc_a($resultacliente);
  $nomcompleto=$daclient[nombres];
  $apellcompleto=$daclient[apellidos];  
  echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
		 <br>
           <tr>  
		       <td colspan=8 class=\"titulo_seccion\">Titular $nomcompleto  $apellcompleto sera registrado a otro ente</td>   
		   </tr> 
          </table>";    
   echo "<input type=\"hidden\" id=\"eltitucliente\" value=\"$elidclientes\">";   	  
?>
<input type="hidden" id="cliennombre" value="<? echo $nomcompleto?>" >
<input type="hidden" id="clienapellido" value="<? echo $apellcompleto?>" >
<input type="hidden" id="cliencedula" value="<? echo $cedclien?>" >
<input type="hidden" id="idclienteid" value="<? echo $elidclientes?>" >
 <div id='noesparticular'>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
      <td class="tdtitulos">C&eacute;dula:</td>
      <td class="tdcampos"><? echo "$cedclien"?></td>
	  <td class="tdtitulos">C&oacute;digo:</td>
      <td class="tdcampos"><input type="text" id="clientcodig" class="campos" size="15"></td>  
	  <input type="hidden" id="codgcliente" >  
      
     </tr>
	   <tr>
	   <td class="tdtitulos" colspan="1">Ente:</td>
       <td class="tdcampos"  colspan="1"><select id="clienente" class="campos" onChange="verpoliza(),busente()" style="width: 260px;">
                              <option value=""></option>
			   <?php  while($verente=asignar_a($reslosentes,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $verente[id_ente]?>"> <?php echo "$verente[nombre] -- $verente[tipo_ente]"?></option>
			   <?php
			  }
			  ?>
			 </select> 
            </td> 
	   <td class="tdtitulos" colspan="1">Fecha de ingreso a la empresa:</td>
       <td class="tdcampos"  colspan="1"><input readonly type="text" size="10" id="inempre" class="campos" maxlength="10" value="<?echo $hoy?>">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'inempre', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>  
	 </tr> 
         <div id='yaesta'></div>
	 <tr>
	   <td class="tdtitulos" colspan="1">Fecha de inclusi&oacute;n:</td>
       <td class="tdcampos"  colspan="1"><input readonly type="text" size="10" id="feinc" class="campos" maxlength="10" value="<?echo date("Y-m-d")?>">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'feinc', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>  
	   <td class="tdtitulos" colspan="1">Cargo:</td>
       <td class="tdcampos"  colspan="1"><select id="cliencarg" class="campos" style="width: 260px;">
                              <option value="82">HABILITADO</option>
			   <?php  while($vercargo=asignar_a($resloscargo,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $vercargo[id_cargo]?>"> <?php echo "$vercargo[cargo]"?></option>
			   <?php
			  }
			  ?>
			 </select>  
             <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />
             </td> 			 
	 </tr>   
	<tr>
	   <td class="tdtitulos" colspan="1">Sub-divisi&oacute;n:</td>
       <td class="tdcampos"  colspan="1"><select id="cliensubd" class="campos" style="width: 260px;">
                              <option value=""></option>
			   <?php  while($versubdi=asignar_a($ressubdiv,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $versubdi[id_subdivision]?>"> <?php echo "$versubdi[subdivision]"?></option>
			   <?php
			  }
			  ?>
			 </select>  </td>
	   <td class="tdtitulos" colspan="1">Estado del Cliente:</td>
       <td class="tdcampos"  colspan="1"><select id="clienestatu" class="campos" style="width: 160px;">
                              <option value="4">ACTIVO</option>
			   <?php  while($verestatu=asignar_a($resestatus,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $verestatu[id_estado_cliente]?>"> <?php echo "$verestatu[estado_cliente]"?></option>
			   <?php
			  }
			  ?>
			 </select>  </td> 			 
	 </tr>    
</table>	 
 <div id='losente'></div>
<br>
          <table class="tabla_citas"  cellpadding=0 cellspacing=0>
	     <tr>
	       <td title="Guardar cliente"><label class="boton" style="cursor:pointer" onclick="validotrotiente()" >Guardar </label>
	      </tr>
          </table>	  
</div>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />
<div id='siesparticular'></div>          
