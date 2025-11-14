<?php
include ("../../lib/jfunciones.php");
sesion();
$lasubdivi=("select subdivisiones.id_subdivision,subdivisiones.subdivision from subdivisiones order by subdivision;");
$laspartidas=("select tbl_partidas.id_partida,tbl_partidas.tipo_partida from tbl_partidas order by tipo_partida;");
$loparentesco=("select parentesco.id_parentesco,parentesco.parentesco 
       from parentesco 
       where (id_parentesco<>17 and id_parentesco<>18) order by parentesco;");
$ceduclien=$_REQUEST['lacedula'];
$datbasicos="select
    clientes.id_cliente,
    clientes.nombres,
    clientes.apellidos,
    clientes.fecha_nacimiento,    
    clientes.sexo,
    clientes.direccion_hab,
    clientes.telefono_hab,
    clientes.telefono_otro,
    clientes.celular,
    clientes.cedula,
    clientes.comentarios,
    clientes.email
  from 
    clientes 
  where
    clientes.cedula='$ceduclien';";
$repdatbasico=ejecutar($datbasicos);
$cuanbasico=num_filas($repdatbasico);
$lobasicos=assoc_a($repdatbasico);
$idcliente=$lobasicos['id_cliente'];
if($cuanbasico>=1){
	
	$datatitular=("select titulares.id_titular,entes.nombre,estados_clientes.estado_cliente,titulares.codigo_empleado,titulares.tipo_partida 
                   from
                        titulares,entes,estados_t_b,estados_clientes,clientes
                   where
						clientes.id_cliente=titulares.id_cliente and
						titulares.id_ente=entes.id_ente and
						titulares.id_titular=estados_t_b.id_titular and
						estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
						estados_t_b.id_beneficiario=0 and
						clientes.id_cliente=$idcliente;");
	$reptitular=ejecutar($datatitular);	
	$cuantostitular=num_filas($reptitular);			
	$databeneficiario=("select beneficiarios.id_beneficiario,beneficiarios.id_titular,parentesco.parentesco,
	                    estados_clientes.estado_cliente,entes.nombre 
                      from 
                          clientes,beneficiarios,titulares,estados_t_b,estados_clientes,parentesco,entes
                      where
						  clientes.id_cliente=beneficiarios.id_cliente and
						  beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
						  estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
						  beneficiarios.id_titular=titulares.id_titular and
						  titulares.id_ente=entes.id_ente and
						  beneficiarios.id_parentesco=parentesco.id_parentesco and
						  beneficiarios.id_cliente=$idcliente;");
	$repdatabeneficiario=ejecutar($databeneficiario);	
	$cuantosbeneficiario=num_filas($repdatabeneficiario);				  
}
if($cuanbasico==0){?>
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion"><?echo "No existe informaci&oacute;n con la cédula No.$ceduclien";?></td>  
     </tr>
</table>	 

<?}else{?>
	 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         
         <td colspan=4 class="titulo_seccion">Datos actuales del cliente</td>  
     </tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
  <tr>
    <td class="tdtitulos" colspan="1">Cédula:</td>
    <td class="tdcampos"  colspan="1"><input type="text" class="campos" id="cedclien" value="<?echo $lobasicos['cedula'];?>"> </td>  
    
    <td class="tdtitulos" colspan="1">Genero:</td>
    <td class="tdcampos"  colspan="1">
      <select name="cliengenero1" id="cliengenero1" class="campos" style="width: 100px;">
        <option value="0" <?php echo $lobasicos[sexo] == 0 ? "selected" : "" ?> >Femenino</option>
        <option value="1" <?php echo $lobasicos[sexo] == 1 ? "selected" : "" ?> >Masculino</option>
      </select>
    </td>
  </tr>
  <tr>
    <input type="hidden" id="idclien" value="<?echo $lobasicos['id_cliente'];?>">   
  </tr>
  <tr>
    <td><br></td>
  </tr>
  <tr>
    <td class="tdtitulos" colspan="1">Nombre:</td>
    <td class="tdcampos"  colspan="1">
      <input type="text" id="cliennombre" class="campos" size="30" value="<? echo $lobasicos[nombres];?>">
    </td>
    <td class="tdtitulos" colspan="1">Apellido:</td>
    <td class="tdcampos" colspan="1">
      <input type="text" id="clienapellido"  class="campos" size="30" value="<? echo $lobasicos[apellidos];?>">
    </td>
  </tr>   
  <tr>
    <td class="tdtitulos" colspan="1">Email:</td>
    <td class="tdcampos"  colspan="1">
      <input type="text" id="cliencorreo"  class="campos" size="30" value="<? echo $lobasicos[email];?>">
    </td>

    <td class="tdtitulos" colspan="1">Fecha Nacimiento:</td>
    <td class="tdcampos" colspan="1">
      <input  type="text" size="10" id="fnaci1" class="campos" maxlength="10" value="<?echo $lobasicos[fecha_nacimiento];?>">
      <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fnaci1', 'yyyy-mm-dd')" title="Ver calendario">
      <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
    </td>
	 </tr> 
  <tr>
    <td class="tdtitulos" colspan="1">Teléfono:</td>
    <td class="tdcampos"  colspan="1">
      <input type="text" id="telefonohab" class="campos" size="30" value="<? echo $lobasicos[telefono_hab];?>">
    </td>
    <td class="tdtitulos" colspan="1">Celular:</td>
    <td class="tdcampos" colspan="1">
      <input type="text" id="telecelular"  class="campos" size="30" value="<? echo $lobasicos[celular];?>">
    </td>
  </tr>
  <tr>
    <td><br></td>
  </tr>
	  <tr> 
	   <td class="tdtitulos" colspan="1">Dirección:</td>
       <td class="tdcampos" colspan="3"><TEXTAREA COLS=60 ROWS=2 id="cliendirr1" class="campos"><?echo $lobasicos[direccion_hab]?></TEXTAREA></td>
	</tr>  
  <tr> 
	  <td class="tdtitulos" colspan="1">Comentario:</td>
       <td class="tdcampos" colspan="3"><TEXTAREA COLS=60 ROWS=2 id="cliencoment" class="campos"><?echo $lobasicos[comentarios]?></TEXTAREA></td>

       <!-- <td class="tdcampos" colspan="1">
         <a href="#" class="boton" onclick="datosLaborales('2025-5893')">Datos laborales</a>
       </td> -->
  </tr>
 
 </table>
       <?if($cuantostitular>=1){?>
           <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
           <tr> 
            <td colspan=4 class="titulo_seccion">Datos como titular</td>  
           </tr>
          </table>
          <table class="tabla_citas"  cellpadding=0 cellspacing=0>
          <?$codigo=1;
          
            while($datocomotitu=asignar_a($reptitular,NULL,PGSQL_ASSOC)){
			$campotitu="eltitular$codigo";
			$subdivi="subdivision$codigo";	
			$lapartida="partida$codigo";
			$tituid="idtitular$codigo";
		  ?>
            <tr>
	            <td class="tdtitulos" colspan="1">Ente:</td>
                <td class="tdcampos"  colspan="1"><? echo $datocomotitu[nombre];?></td>
            </tr>
	        <tr>
	            <td class="tdtitulos" colspan="1">Estatus:</td>
                <td class="tdcampos" colspan="1"><? echo $datocomotitu[estado_cliente];?></td>
	        </tr> 
	        <tr>
	            <td class="tdtitulos" colspan="1">Codigo:</td>
                <td class="tdcampos" colspan="1"><input type="text" id="<?echo $campotitu?>" class="campos" size="30" value="<? echo $datocomotitu[codigo_empleado];?>"></td>
                <input type="hidden" id="<?echo $tituid?>" value="<? echo $datocomotitu[id_titular]?>" > 
                <td class="tdtitulos" colspan="1">Subdivision:</td>
                <?
                $ertitular=$datocomotitu['id_titular'];
                $qsubdivision=("select subdivisiones.id_subdivision,subdivisiones.subdivision,titulares.tipo_partida 
                              from 
                               subdivisiones,titulares,titulares_subdivisiones 
                              where 
                              titulares.id_titular=titulares_subdivisiones.id_titular and
                              titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision and
                              titulares.id_titular=$ertitular;");
                $repsubvision=ejecutar($qsubdivision);   
                $cuantasubdi=num_filas($repsubvision);           
                if($cuantasubdi>=1){
					$ladatasubdi=assoc_a($repsubvision);
					$numsubdi=$ladatasubdi['id_subdivision'];
					$nomcosubdi=$ladatasubdi['subdivision'];
					$partidaes=$ladatasubdi['tipo_partida'];
					$busidpartida=("select tbl_partidas.tipo_partida from tbl_partidas where id_partida=$partidaes;");
					$repbuspartida=ejecutar($busidpartida);
					$cuantapartida=num_filas($repbuspartida);
					if($cuantapartida>=1){
					 $datpartida=assoc_a($repbuspartida);
					 $nombrepartida=$datpartida[tipo_partida];
					}else{
						$nombrepartida=0;
						$partidaes=7;
						} 
					
				}
                ?>
        <td class="tdcampos"  colspan="1">
                       <select id="<?echo $subdivi?>" class="campos" style="width: 180px;">
                         	<option value="<?echo $numsubdi?>"><?echo $nomcosubdi?></option>
                           <?  $contador="$contador$codigo";
                               $contador=ejecutar($lasubdivi);
                               while($lassub=asignar_a($contador,NULL,PGSQL_ASSOC)){?>
							     <option value="<?echo $lassub[id_subdivision]?>"><?echo $lassub[subdivision]?></option>
							<?}?>
						</select>	                    
		
        </td>
            </tr>
            <tr>
	             <td class="tdtitulos" colspan="1">Partida:</td>
	             <td class="tdcampos"  colspan="1">
                       <select id="<?echo $lapartida?>" class="campos" style="width: 180px;">
                         	<option value="<?echo $partidaes?>"><?echo $nombrepartida?></option>
                           <?  $conpartida="$conpartida$codigo";
                               $conpartida=ejecutar($laspartidas);
                               while($partida=asignar_a($conpartida,NULL,PGSQL_ASSOC)){?>
							     <option value="<?echo $partida[id_partida]?>"><?echo $partida[tipo_partida]?></option>
							<?}?>
						</select>	                    
		
        </td>
	        </tr>
            <tr>
	            <td class="tdtitulos" colspan="6"><HR></td>
	        </tr>
          <?$codigo++;
            }?>
          </table>
	   <?}//fin del if que Si existe la persona como titular?>  
	   <?if($cuantosbeneficiario>=1){?>
	         <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
           <tr> 
            <td colspan=4 class="titulo_seccion">Datos como beneficiarios</td>  
           </tr>
          </table> 
		     <table class="tabla_citas"  cellpadding=0 cellspacing=0>
          <?$codiben=1;
            while($datocomobenef=asignar_a($repdatabeneficiario,NULL,PGSQL_ASSOC)){
            $benfiid=$datocomobenef[id_beneficiario];
            $eseltitu=$datocomobenef[id_titular];
 			$campobenif="elbenef$codiben";	
 			$benefid="idbenefi$codiben";
 			$busparentesco=("select parentesco.id_parentesco,parentesco.parentesco 
                 from 
                    parentesco,beneficiarios
                where
                   beneficiarios.id_parentesco=parentesco.id_parentesco and
                   beneficiarios.id_beneficiario=$benfiid;"); 
            $repbusparent=ejecutar($busparentesco);       
            $elparenes=assoc_a($repbusparent);
            $numidparen=$elparenes[id_parentesco];
            $parenes=$elparenes[parentesco];
            $quiestitu=("select clientes.nombres,clientes.apellidos 
                         from
                          clientes,titulares
                         where
                          clientes.id_cliente=titulares.id_cliente and
                          titulares.id_titular=$eseltitu");
            $repdeltitu=ejecutar($quiestitu);               
            $datvertitu=assoc_a($repdeltitu);
            $nombdeltitu="$datvertitu[nombres] $datvertitu[apellidos]";
		  ?>
	        <tr>
	            <td class="tdtitulos" colspan="1">Ente:</td>
                <td class="tdcampos" colspan="1"><?echo $datocomobenef['nombre']?></td>
                <td class="tdtitulos" colspan="1">Estatus:</td>
                <td class="tdcampos" colspan="1"><?echo $datocomobenef['estado_cliente']?></td>
            </tr>
            <tr>
	            <td class="tdtitulos" colspan="1">Titular:</td>
                <td class="tdcampos" colspan="1"><?echo $nombdeltitu?></td>
                                <td class="tdtitulos" colspan="1">Parentesco:</td>
                <td class="tdcampos"  colspan="1">
                       <select  id="<?echo $campobenif?>" class="campos" style="width: 180px;">
                         	<option value="<?echo $numidparen?>"><?echo $parenes?></option>
                           <?$repdparentesco="$repdparentesco$codiben";
                             $repdparentesco=ejecutar($loparentesco); 
                                while($losparen=asignar_a($repdparentesco,NULL,PGSQL_ASSOC)){?>
				<option value="<?echo $losparen[id_parentesco]?>"><?echo $losparen[parentesco]?></option>
							<?}?>
						</select>	                    
		
               </td>
                <input type="hidden" id="<?echo $benefid?>" value="<? echo $benfiid?>" >
            </tr>
	   <?$codiben++;
             }?>
	      </table>
	   <?}//fin del if de que Si existe como beneficiario?>
	<?}//fin del else que si existe informacion!
?>
   <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
   <div id='datosactualizados'></div>  
   <input type="hidden" id="sevetitu" value="<? echo $cuantostitular?>" >  
   <input type="hidden" id="sevebene" value="<? echo $cuantosbeneficiario?>" >  
   <input type="hidden" id="elcliente" value="<?echo $idcliente?>" >  
   
   <br>
    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td class="tdcampos" title="Guardar Cambios"><label class="boton" style="cursor:pointer" onclick="CambiosGeneral()" >Guardar</label>
     </tr>     
   </table>  
