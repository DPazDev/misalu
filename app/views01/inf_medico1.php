<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $proceson=$_REQUEST["elproceso"]; 
  $busproce=("select procesos.id_proceso,procesos.id_estado_proceso,procesos.id_titular,
                     procesos.id_beneficiario,procesos.comentarios,procesos.nu_planilla 
  from procesos where procesos.id_proceso=$proceson");
  $repbuspro=ejecutar($busproce);
  $cuantospro=num_filas($repbuspro);
  if($cuantospro>=1){
	  $datapaci=assoc_a($repbuspro);
	  $eltitu=$datapaci['id_titular'];
	  $elbenif=$datapaci['id_beneficiario'];
	  $elcomenta=$datapaci['comentarios'];
	  $laplanillaes=$datapaci['nu_planilla'];
	  $estadproceso=$datapaci['id_estado_proceso'];
	  if($estadproceso==2){
	  if($elbenif==0){
	    $busdattitu=("select clientes.nombres,clientes.apellidos,clientes.sexo,clientes.fecha_nacimiento,clientes.cedula,entes.nombre 
	                   from 
	                     clientes,titulares,entes 
	                   where
							clientes.id_cliente=titulares.id_cliente and
							titulares.id_ente=entes.id_ente and titulares.id_titular=$eltitu");
	    $repdatatitu=ejecutar($busdattitu);						
	    $dataprintitu=assoc_a($repdatatitu);
	    $nombretitu=$dataprintitu['nombres'];
	    $apellittitu=$dataprintitu['apellidos'];
	    $nomcompletotitu="$nombretitu $apellittitu";
	    $genertitu=$dataprintitu['sexo'];
	    if($genertitu==0){
			$lgenertitu="Femenino";
		 }else{
			 $lgenertitu="Masculino";
			 }
	    $fechanaci=$dataprintitu['fecha_nacimiento'];
	    list($elant,$elmt,$eldt)=explode("-",$fechanaci);
	    $edadnai=calcular_edad($fechanaci);
	    $cedulatitu=$dataprintitu['cedula'];
	    $entetitu=$dataprintitu['nombre'];
	    $histinforme=("select tbl_informedico.id_proceso,procesos.id_titular,tbl_informedico.fechacreado 
	                 from procesos,tbl_informedico where
                     tbl_informedico.id_proceso=procesos.id_proceso and
                     procesos.id_titular=$eltitu order by tbl_informedico.fechacreado desc;");
      $rephistinform=ejecutar($histinforme);
      $cuanthis=num_filas($rephistinform);  
	  }else{
		$busdattitu=("select clientes.nombres,clientes.apellidos,clientes.sexo,clientes.fecha_nacimiento,clientes.cedula,entes.nombre 
	                   from 
	                     clientes,titulares,entes 
	                   where
							clientes.id_cliente=titulares.id_cliente and
							titulares.id_ente=entes.id_ente and titulares.id_titular=$eltitu");
	    $repdatatitu=ejecutar($busdattitu);						
	    $dataprintitu=assoc_a($repdatatitu);
	    $nombretitu=$dataprintitu['nombres'];
	    $apellittitu=$dataprintitu['apellidos'];
	    $nomcompletotitu="$nombretitu $apellittitu";
	    $genertitu=$dataprintitu['sexo'];
	    if($genertitu==0){
			$lgenertitu="Femenino";
		 }else{
			 $lgenertitu="Masculino";
			 }
	    $fechanaci=$dataprintitu['fecha_nacimiento'];
	    list($elant,$elmt,$eldt)=explode("-",$fechanaci);
	    $edadnai=calcular_edad($fechanaci);
	    $cedulatitu=$dataprintitu['cedula'];  
	    $busdatbeni=("select clientes.nombres,clientes.apellidos,clientes.sexo,clientes.fecha_nacimiento,clientes.cedula,entes.nombre,parentesco.parentesco 
	from 
	   clientes,titulares,entes,beneficiarios,parentesco 
	where
	clientes.id_cliente=beneficiarios.id_cliente and
        beneficiarios.id_titular=titulares.id_titular and
        beneficiarios.id_parentesco=parentesco.id_parentesco and
	titulares.id_ente=entes.id_ente and beneficiarios.id_beneficiario=$elbenif");
	   $repdatbeni=ejecutar($busdatbeni);
	   $ladatbeni=assoc_a($repdatbeni);
	   $nomcompbeni="$ladatbeni[nombres] $ladatbeni[apellidos]";
	   $cedubenif=$ladatbeni['cedula'];
	   $fechabeni=$ladatbeni['fecha_nacimiento'];
	   list($anoben,$mesben,$diaben)=explode("-",$fechabeni);
	   $edadbeni=calcular_edad($fechabeni);
	   $sexobeni=$ladatbeni['sexo'];
	   $parenteco=$ladatbeni['parentesco'];
	   if($sexobeni==0){
		   $generbeni="Femenino";
		}else{
			$generbeni="Masculino";
			}
	   $entebeni=$ladatbeni['nombre'];
	  $histinforme=("select tbl_informedico.id_proceso,procesos.id_beneficiario,tbl_informedico.fechacreado 
	                 from procesos,tbl_informedico where
                     tbl_informedico.id_proceso=procesos.id_proceso and
                     procesos.id_beneficiario=$elbenif order by tbl_informedico.fechacreado desc;");
      $rephistinform=ejecutar($histinforme);
      $cuanthis=num_filas($rephistinform);               
	  }
	  if($elbenif==0){?>
	  <input type="hidden" id="estitu" value="<?echo $eltitu?>">
	  <input type="hidden" id="esbeni" value="<?echo $elbenif?>">
	  <input type="hidden" id="esplanilla" value="<?echo $laplanillaes?>">
	     <table class="tabla_cabecera3" cellpadding=0 cellspacing=0>  
              <tr>
	          <td colspan=4 class="titulo_seccion">Datos personales</td>
	      </tr>
        </table>
        <table class="tabla_citas"  cellpadding=0 cellspacing=0>
           <tr>
                <td  class="tdtitulos">Paciente (Titular):</td>
                <td  class="tdcampos" ><?php echo "$nomcompletotitu"; ?></td>
                <td  class="tdtitulos">No. C&eacute;dula:</td>
                <td  class="tdcampos" ><?php echo "$cedulatitu"; ?></td>
                <td  class="tdtitulos">Ente:</td>
                <td  class="tdcampos" ><?php echo "$entetitu"; ?></td>
            </tr>
           <tr>
                <td class="tdtitulos">Genero:</td>
                <td class="tdcampos" ><?php echo "$lgenertitu"; ?></td>
                <td class="tdtitulos">Fecha de Nacimiento:</td>
                <td class="tdcampos" ><?php echo "$eldt-$elmt-$elant"; ?></td>
                <td class="tdtitulos">Edad:</td>
                <td class="tdcampos" ><?php echo "$edadnai"; ?></td>
            </tr>
            <tr>
                <td class="tdtitulos">Comentario:</td>
                <td colspan=5 class="tdcampos" ><?php echo "$elcomenta"; ?></td>
            </tr>
        </table>   
	   <?}else{?>
		   <table class="tabla_cabecera3" cellpadding=0 cellspacing=0>  
              <tr>
	          <td colspan=4 class="titulo_seccion">Datos personales</td>
	      </tr>
        </table>
        <table class="tabla_citas"  cellpadding=0 cellspacing=0>
           <tr>
                <td  class="tdtitulos">Paciente (Beneficiario):</td>
                <td  class="tdcampos" ><?php echo "$nomcompbeni"; ?></td>
                <td  class="tdtitulos">No. C&eacute;dula:</td>
                <td  class="tdcampos" ><?php echo "$cedubenif"; ?></td>
                <td  class="tdtitulos">Ente:</td>
                <td  class="tdcampos" ><?php echo "$entebeni"; ?></td>
            </tr>
           <tr>
                <td class="tdtitulos">Genero:</td>
                <td class="tdcampos" ><?php echo "$generbeni"; ?></td>
                <td class="tdtitulos">Fecha de Nacimiento:</td>
                <td class="tdcampos" ><?php echo "$diaben-$mesben-$anoben"; ?></td>
                <td class="tdtitulos">Edad:</td>
                <td class="tdcampos" ><?php echo "$edadbeni"; ?></td>
            </tr>
            <tr>
                <td class="tdtitulos">Comentario:</td>
                <td colspan=5 class="tdcampos" ><?php echo "$elcomenta"; ?></td>
            </tr>
            <tr>
                <td colspan=6 class="tdcampos" ><hr></td>
            </tr>
            <tr>
                <td  class="tdtitulos">Titular:</td>
                <td  class="tdcampos" ><?php echo "$nomcompletotitu"; ?></td>
                <td  class="tdtitulos">No. C&eacute;dula:</td>
                <td  class="tdcampos" ><?php echo "$cedulatitu"; ?></td>
                <td  class="tdtitulos">Parentesco:</td>
                <td  class="tdcampos" ><?php echo "$parenteco"; ?></td>                
            </tr>
           <tr>
                <td class="tdtitulos">Genero:</td>
                <td class="tdcampos" ><?php echo "$lgenertitu"; ?></td>
                <td class="tdtitulos">Fecha de Nacimiento:</td>
                <td class="tdcampos" ><?php echo "$eldt-$elmt-$elant"; ?></td>
                <td class="tdtitulos">Edad:</td>
                <td class="tdcampos" ><?php echo "$edadnai"; ?></td>
            </tr>
        </table>     
	    <?}?>
	     <input type="hidden" id="eltitu" value="<? echo $eltitu?>" >  
	    <input type="hidden" id="elbeni" value="<? echo $elbenif?>" >  
	    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <br>
        <tr>  
          <td colspan=8 class="titulo_seccion">Informe <?if($cuanthis>=1){?><label class="boton" style="cursor:pointer" onclick="Historial_Informe('<?echo "$eltitu@$elbenif"?>')" >Historial</label><?}?></td>   
       </tr> 
       </table>
	   <table class="tabla_citas"  cellpadding=0 cellspacing=0>
           
           <tr>
                <td  class="tdtitulos">Paciente Presenta:</td>
                <td  class="tdcampos" ><TEXTAREA COLS=60 ROWS=2 id="presenta" class="campos"></TEXTAREA></td>
           </tr>
           <tr>
                <td  class="tdtitulos">Diagn&oacute;stico:</td>
                <td  class="tdcampos" ><TEXTAREA COLS=60 ROWS=2 id="diagnos" class="campos"></TEXTAREA></td>
           </tr>
           <tr><td><label style="color: #ff0000">Indicandole.</label></td></tr>
           
           <tr>
                <td  class="tdtitulos">Laboratorio:</td>
                <td  class="tdcampos" ><TEXTAREA COLS=60 ROWS=2 id="labora" class="campos"></TEXTAREA></td>
           </tr>
           <tr>
                <td  class="tdtitulos">Ultrasonido:</td>
                <td  class="tdcampos" ><TEXTAREA COLS=60 ROWS=2 id="ultras" class="campos"></TEXTAREA></td>
           </tr>
           <tr>
                <td  class="tdtitulos">Radiolog&iacute;a:</td>
                <td  class="tdcampos" ><TEXTAREA COLS=60 ROWS=2 id="radiolog" class="campos"></TEXTAREA></td>
           </tr>
           <tr>
                <td  class="tdtitulos">Estudios Especiales:</td>
                <td  class="tdcampos" ><TEXTAREA COLS=60 ROWS=2 id="estdespe" class="campos"></TEXTAREA></td>
           </tr>
            <tr>
                <td  class="tdtitulos">Tratamiento:</td>
                <td  class="tdcampos" ><TEXTAREA COLS=60 ROWS=2 id="indica" class="campos"></TEXTAREA></td>
           </tr>
           <tr>
                        <td  title="Guardar e Imprimir Informe"><br><br><label class="boton" style="cursor:pointer" onclick="Guardar_Informe()" >Guardar / Imprimir</label></td>
          </tr>
        </table>
        <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
        <div id='infofinal'></div>

 <? }else{?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>
 <tr>  
     <td class="titulo_seccion">El n&uacute;mero de proceso no esta en el estado Aprobado - Operador!!!</td>
   </tr> 
</table>
 
<? }
}else{?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>
 <tr>  
     <td class="titulo_seccion">El n&uacute;mero de proceso no existe!!!</td>
   </tr> 
</table>
<?}?>
