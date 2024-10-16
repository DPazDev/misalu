<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $ceduclien=$_REQUEST["cedulaclien"];      
  $partcedula=substr($ceduclien,1,3);
  $esnumero=is_numeric($partcedula);
  if ($esnumero==1){ //if para ver si el dato entrante en la variable es una cedula, si no lo es es un nombre
  $queryCedula=("select * from clientes where cedula='$ceduclien';");
  $resuCedula=ejecutar($queryCedula);
  $numfilas=num_filas($resuCedula);
     if ($numfilas<=0){
       echo"<table class=\"tabla_cabecera3\" cellpadding=0 cellspacing=0>
         <tr>
	   <td colspan=4 class=\"titulo_seccion\">No existe al menos una persona con la c&eacute;dula: $ceduclien</td>
         </tr>
       </table>";
  }else{	 
    $elidcliente=assoc_a($resuCedula);
    $vercliente=$elidcliente['id_cliente'];
    //busco a ver si es un titular
    $queryTitular=("select * from titulares where id_cliente=$vercliente;");
    $resuTitular=ejecutar($queryTitular);
    $numfTitular=num_filas($resuTitular);
       if ($numfTitular>0){
             $elidTitular=assoc_a($resuTitular);
             $veridTitular=$elidTitular['id_titular'];
	     //ver a que ente pertenece y ver su estatus
	       $queryEnte=("
select entes.nombre, estados_clientes.estado_cliente,titulares.id_titular,titulares.codigo_empleado,
tbl_tipos_entes.tipo_ente,entes.id_tipo_ente
 from
   entes,estados_clientes,titulares,estados_t_b,clientes,tbl_tipos_entes
 where 
titulares.id_ente=entes.id_ente and titulares.id_titular=estados_t_b.id_titular and 
estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and titulares.id_cliente=clientes.id_cliente and 
clientes.cedula='$ceduclien' and estados_t_b.id_beneficiario=0 and
entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente order by estado_cliente;");
	       $resuEnte=ejecutar($queryEnte);
              //busco a ver si tiene beneficiarios y su entes y su estatus
	      $queryBenf=("select 
    entes.nombre, estados_clientes.estado_cliente, beneficiarios.id_beneficiario 
from
    entes,titulares,beneficiarios,clientes,estados_t_b,estados_clientes
where           
   titulares.id_titular=beneficiarios.id_titular and
   titulares.id_cliente=clientes.id_cliente and
   titulares.id_ente=entes.id_ente         and 	  
   clientes.cedula='$ceduclien'              and
   beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
   estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente;");
	      $resuBenf=ejecutar($queryBenf);
	      $numfBenf=num_filas($resuBenf);
	      //ver si el titular esta como beneficiario de otro titular;
	      $queryTcomoB=("select entes.nombre, estados_clientes.estado_cliente, titulares.id_titular   
from 
   entes,titulares,estados_t_b,estados_clientes,beneficiarios,clientes
where    
	                     titulares.id_ente=entes.id_ente and   
		             titulares.id_titular=estados_t_b.id_titular and 
			     estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
			     beneficiarios.id_cliente=clientes.id_cliente 
			     and clientes.cedula='$ceduclien' 
			     and titulares.id_titular=beneficiarios.id_titular 
			     and estados_t_b.id_beneficiario=0 order by estado_cliente;");
	     $resuTcomoB=ejecutar($queryTcomoB);		     
	     $numTcomoB=num_filas($resuTcomoB);

      }else{
           //para ver si es un Beneficiario 
          $queryesBenf=("select clientes.*,beneficiarios.id_beneficiario,beneficiarios.id_titular 
from beneficiarios,clientes
where beneficiarios.id_cliente=clientes.id_cliente 
	                  and clientes.cedula='$ceduclien';");
	  $resuesBenf=ejecutar($queryesBenf);
	  $daBenf=assoc_a($resuesBenf);
	  $cuanBenf=num_filas($resuesBenf);
	  $elBysT=$daBenf['id_titular'];
	  //ver a que ente pertenece y ver su estatus
          $queryEnteB=("select entes.nombre, estados_clientes.estado_cliente, titulares.id_titular, beneficiarios.id_beneficiario,tbl_tipos_entes.tipo_ente,entes.id_tipo_ente
 from
       entes,estados_clientes,titulares,beneficiarios,estados_t_b,clientes,tbl_tipos_entes
 where    
	                 titulares.id_ente=entes.id_ente and 
                         entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
		         titulares.id_titular=estados_t_b.id_titular and               
			 estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
			 beneficiarios.id_cliente=clientes.id_cliente and            
			 titulares.id_titular=beneficiarios.id_titular and
			 clientes.cedula='$ceduclien' and estados_t_b.id_beneficiario=beneficiarios.id_beneficiario and 
		         titulares.id_titular=beneficiarios.id_titular group by entes.nombre,estados_clientes.estado_cliente,titulares.id_titular,beneficiarios.id_beneficiario,tbl_tipos_entes.tipo_ente,entes.id_tipo_ente order by  
		         estado_cliente;");
	  $resuEnteB=ejecutar($queryEnteB);
	  $resuEntB1=("select entes.nombre, estados_clientes.estado_cliente, titulares.id_titular, beneficiarios.id_beneficiario 
from
         entes,estados_clientes,titulares,estados_t_b,clientes,beneficiarios
where    
	               titulares.id_ente=entes.id_ente and 
		       titulares.id_titular=estados_t_b.id_titular and               
		       estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
		       beneficiarios.id_cliente=clientes.id_cliente and            
		       titulares.id_titular=beneficiarios.id_titular and
		      clientes.cedula='$ceduclien' and estados_t_b.id_beneficiario=beneficiarios.id_beneficiario and 
		     titulares.id_titular=beneficiarios.id_titular group by entes.nombre,estados_clientes.estado_cliente,titulares.id_titular,beneficiarios.id_beneficiario order by  
		     estado_cliente;");
          $resuETB1=ejecutar($resuEntB1);		     
          $nufEBf=num_filas($resuEnteB);
      }
    }  ?>
    
    <form method="get" onsubmit="return false;" name="dattitu" id="dattitu">
    <?
   if ($numfTitular>0){
      $esti='Titular';
      echo" <input  TYPE=\"hidden\"  id=\"estt\" VALUE=$esti>";
      ?>
        <table class="tabla_cabecera3" cellpadding=0 cellspacing=0>  
              <tr>
	          <td colspan=4 class="titulo_seccion">Datos personales del <?php echo $esti;?></td>
	      </tr>
        </table>
        <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
           <tr>
             <td class="tdtitulos"></td>
             <td class="tdcampos"></td>
	     <td class="tdtitulos">Fecha Inclusi&oacute;n al sistema:</td>
             <td class="tdcampos"><?php echo $elidcliente['fecha_creado'];?></td>
	  </tr>
	      <tr>
	        <td class="tdtitulos">C&eacute;dula:</td>
            <td class="tdcampos"><?php echo $elidcliente['cedula'];?></td>
	        <td class="tdtitulos">Nombres y Apellidos:</td> 
            <td class="tdcampos" ><?php echo "$elidcliente[nombres] $elidcliente[apellidos]"; ?></td>
          </tr>
	      
	      <tr>
	         <td class="tdtitulos">Fecha Nacimiento:</td>
		 <td class="tdcampos" ><?php echo camfecha($elidcliente['fecha_nacimiento']); ?></td>
                 <td class="tdtitulos">Edad:</td>
                 <td class="tdcampos" ><label style="color: #ff0000"> <?php echo calcular_edad($elidcliente['fecha_nacimiento']); ?> a&ntilde;os</label></td>
                 <td class="tdtitulos">Sexo:</td>
                 <td class="tdcampos" ><?php $elidcliente['sexo'];
		           if($elidcliente['sexo']==0){
				echo "FEMENINO";
			  }else{
	         	       echo "MASCULINO";}?></td>
             </tr>    			
	     <tr>
	        <td class="tdtitulos">Tel&eacute;fono:</td>
		<td class="tdcampos" ><?php echo $elidcliente['telefono_hab']; echo " / "; echo $elidcliente['telefono_otro'];  ?></td>
	        <td class="tdtitulos">Celular:</td>
	        <td class="tdcampos" ><?php echo $elidcliente['celular']; ?></td>
	    </tr>
            <tr>
	        <td colspan=1 class="tdtitulos">Direcci&oacute;n:</td>
		<td colspan=3 class="tdcampos" ><?php echo $elidcliente['direccion_hab']; ?></td>
	    </tr>
            <tr>
                <td colspan=1 class="tdtitulos">Comentarios:</td>
                <td colspan=3 class="tdcampos" ><label style="color: #ff0000"><?php echo "$elidcliente[comentarios]"; ?></label></td>
            </tr>
	      <?php 
	    $ti=1;
	    $tv=1;
	    while ($estaente=asignar_a($resuEnte)){ 
	    $elttl1=$estaente[id_titular];
	    $elttlar=$elttl1;
		$url="'views01/isolicitudmedicamento.php?id_titular=$elttlar&id_beneficiario=0'";
	     echo "
	       <tr>
	         <td class=\"tdtitulos\">El ente al cual pertenece:</td>
	         <td class=\"tdcampos\" >$estaente[nombre]</td>";
	      
                 if ( ($estaente['estado_cliente']=='ACTIVO')){
	          $querydiv=("select * from subdivisiones,titulares_subdivisiones where titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision 
and titulares_subdivisiones.id_titular=$elttl1");
                  $resuldiv=ejecutar($querydiv);
		  $datadiv=assoc_a($resuldiv);
                  $decsalud=("select declaracion_t.id_declaracion from declaracion_t where declaracion_t.id_titular=$elttl1 limit 1");
                  $repuesta=ejecutar($decsalud);
                  $cuantdesa=num_filas($repuesta);
                      echo"
                        <td class=\"tdtitulos\">Estatus:</td>
	                    <td  class=\"tdtitulos\" ><label style=\"color: #ff0000\"> $estaente[estado_cliente]</label></td>
                      <td class=\"tdtitulos\">C&oacute;digo:</td>
                    <td  class=\"tdtitulos\" ><label style=\"color: #ff0000\"> $estaente[codigo_empleado]</label></td>
		 </tr>
        <tr>
		        <td class=\"tdtitulos\">Tipo ente:</td>
	            <td  class=\"tdtitulos\" ><label style=\"color: #ff0000\"> $estaente[tipo_ente]</label></td>
		 </tr>
          </tr>
		 <tr>
		        <td class=\"tdtitulos\">Sub-division:</td>
	                <td class=\"tdcampos\" >$datadiv[subdivision]</td>
		 </tr>
		 <tr>"; ?>   
           <?
	          echo"<input  TYPE=\"hidden\" name=\"idcott\" id=\"idcott_$ti\" VALUE=$elttlar > ";
              if(($estaente[id_tipo_ente]<>4) and ($estaente[id_tipo_ente]<>6)){
              echo "<td class=\"tdtitulos\"><input type=\"radio\" id=\"group_$ti\" value=$elttlar onClick=\"Effect.Grow('titcober');VerCob1()\" style=\"cursor:pointer\" title=\"Ver Cobertura\">Ver cobertura</td> ";
         }else{
           echo"<td class=\"tdtitulos\"></td>";?>
            <div id="toggleText" style="display: none"
               <?echo "<td class=\"tdtitulos\"><input type=\"radio\" id=\"group_$ti\" value=$elttlar onClick=\"Effect.Grow('titcober');VerCob1()\" style=\"cursor:pointer\" title=\"Ver Cobertura\">Ver cobertura</td> ";?>
               </div>
            <? }
echo "<td class=\"tdtitulos\">
<a href=\"javascript: imprimir($url);\" class=\"boton\" title=\"Imprimir Planilla de Solicitud de Medicamento\"> Solicitud Medicamento </a></td>";
if($cuantdesa>=1){
    echo "<td class=\"tdtitulos\">

               <a href=\"views01/mostdeclaracion.php?titular=$elttl1-T\" title=\"Declaraci&oacute;n de Salud\" class=\"boton\" onclick=\"Modalbox.show(this.href, {title: this.title, width:900,height:400, overlayClose: false}); return false;\">Declaraci&oacute;n Salud</a>
            </td>";
         }
echo"</tr>
<tr>
		        <td class=\"tdtitulos\" colspan=7><hr></td>	      

</tr>";
		 $ti++;
		 $tv++;
		 }else{
                     echo"
                           <td class=\"tdtitulos\">Estatus:</td>
		           <td class=\"tdcampos\" > $estaente[estado_cliente]</td>
		</tr>";
               }
	     }
		 //Buscar si ese titular tambien esta como un beneficiario
        $buscoticomoben=("select clientes.nombres,clientes.apellidos,beneficiarios.id_beneficiario,estados_clientes.estado_cliente,
       entes.nombre,beneficiarios.id_titular 
      from 
           clientes,beneficiarios,estados_clientes,titulares,estados_t_b,entes
         where
      clientes.cedula='$ceduclien' and 
      clientes.id_cliente=beneficiarios.id_cliente and
      beneficiarios.id_titular=titulares.id_titular and 
      titulares.id_ente=entes.id_ente and
      beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
      estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
      estados_t_b.id_estado_cliente=4"); 
	    $repbuscomotibenf=ejecutar($buscoticomoben);  
		$cuantitucombenf=num_filas($repbuscomotibenf);
		if($cuantitucombenf>=1){
                echo"<tr><td colspan=7><hr></td></tr>
                    "; 
		while ($TitBenf=asignar_a($repbuscomotibenf)){
			$iddeltiencar=$TitBenf['id_titular'];
			$iddelbenfcomot=$TitBenf['id_beneficiario'];
				$url="'views01/isolicitudmedicamento.php?id_titular=$iddeltiencar&id_beneficiario=$iddelbenfcomot'";
			$buscotiencar=("select clientes.nombres,clientes.apellidos,entes.nombre from clientes,entes,titulares where clientes.id_cliente=titulares.id_cliente and titulares.id_titular=$iddeltiencar and titulares.id_ente=entes.id_ente;");
			$repbuscotiencar=ejecutar($buscotiencar);
			$dataticonbenf=assoc_a($repbuscotiencar);
			$nomcpletotinuevo="$dataticonbenf[nombres] $dataticonbenf[apellidos] ($dataticonbenf[nombre])";
		 echo"
          <tr>
                <td class=\"tdtitulos\" ><input type=\"radio\" name=\"groutb\" onClick=\"VerCobTcB($iddelbenfcomot)\">Ver cobertura</td>

                <td class=\"tdcampos\" colspan=3>Cobertura como beneficiario del titular $nomcpletotinuevo</td>
</tr>
<tr>
<td class=\"tdtitulos\">
<a href=\"javascript: imprimir($url);\" class=\"boton\" title=\"Imprimir Planilla de Solicitud de Medicamento\"> Solicitud Medicamento </a></td>
		 </tr>";
		 } 
		} 
	     echo "</table>";
	     echo" <input  TYPE=\"hidden\" name=\"vfi\" id=\"vfi\" VALUE=$tv>"; 
	     echo" </talbe>";
             $ti=0;
         if (($numfBenf>0)){
	     echo"<table class=\"tabla_cabecera3\" cellpadding=0 cellspacing=0>
	               <tr>
		           <td colspan=4 class=\"titulo_seccion\">Beneficiarios asignados:</td>
		        </tr>
		  </table>
		<table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
		   <TR>
		        <TH>C&eacute;dula</TH>
		        <TH>Nombres y Apellidos</TH>
		        <TH>Parentesco</TH>
			<TH>Ente</TH>
			<TH>Estatus</TH>
		  </TR> ";
							       
	  while ($dapBenfi=asignar_a($resuBenf)){   
	    $elidBf= $dapBenfi['id_beneficiario'];
	    $queryDBf=("select clientes.nombres,clientes.apellidos,clientes.cedula,parentesco.parentesco 
from
       clientes,beneficiarios,parentesco
where 
	                  clientes.id_cliente=beneficiarios.id_cliente and
			  beneficiarios.id_beneficiario=$elidBf and
			  beneficiarios.id_parentesco=parentesco.id_parentesco;");
	   $resuDBf=ejecutar($queryDBf);		  
	   $ladatBF=assoc_a($resuDBf); 
           $lacedut=$ladatBF['cedula'];  
           echo "
	        <tr>
	              <td class=\"tdcampos\" onclick=\"hola('$lacedut')\" style=\"cursor:pointer\" title=\"Ver data\"> $ladatBF[cedula]</td>
		      <td class=\"tdcampos\">$ladatBF[nombres] $ladatBF[apellidos] </td>
                      <td class=\"tdcampos\">$ladatBF[parentesco]</td>
		      <td class=\"tdcampos\">$dapBenfi[nombre]</td>";
		       if ( ($dapBenfi['estado_cliente']=='ACTIVO')){
		      echo" <td  class=\"tdtitulos\" ><label style=\"color: #ff0000\"> $dapBenfi[estado_cliente]</label></td>";
		        }else{
                      echo" <td class=\"tdcampos\" > $dapBenfi[estado_cliente]</td>";
		        }	 
                echo"</tr>";
	  }
	  echo"</table>";
	 }
         $lacedut=""; 
         ?>
	<div id="titcober"></div>
	 
	<?
        if ($numTcomoB>0){
	echo "<table class=\"tabla_cabecera3\" cellpadding=0 cellspacing=0>
	       <tr>
	          <td colspan=4 class=\"titulo_seccion\">Titular asignado como beneficiario a:</td>
	       </tr>
	      </table>
	      <table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
	        <TR>
	           <TH>C&eacute;dula</TH>
		   <TH>Nombres y Apellidos</TH>
		   <TH>Ente</TH>
		   <TH>Estatus</TH>			
		 </TR> ";
		 while ($dapTcB=asignar_a($resuTcomoB)){   
		   $elidTB= $dapTcB['id_titular'];
		   $queryTcB=("select clientes.nombres,clientes.apellidos,clientes.cedula 
                   from
                            clientes,titulares
                   where 
		               clientes.id_cliente=titulares.id_cliente and
			       titulares.id_titular=$elidTB;");
		   $resuTcB=ejecutar($queryTcB);		  
		   $ladatBF=assoc_a($resuTcB);
		echo " <tr>
		     <td class=\"tdcampos\" onclick=\"hola($ladatBF[cedula])\" style=\"cursor:pointer\" title=\"Ver data\"> $ladatBF[cedula]</td>
		     <td class=\"tdcampos\" >$ladatBF[nombres] $ladatBF[apellidos] </td>
		     <td class=\"tdcampos\">$dapTcB[nombre]</td>";
		      if ( ($dapTcB['estado_cliente']=='ACTIVO')){
		            echo" <td  class=\"tdtitulos\" ><label style=\"color: #ff0000\"> $dapTcB[estado_cliente]</label></td>";
		      }else{
		           echo" <td class=\"tdcampos\" >$dapTcB[estado_cliente]</td>";
		      }	 
		echo"</tr>";
	      }
	echo"</table>";
	}//fin de los datos de titular asignado como un beneficiario
     }//Fin de los datos principales del titular	 
     else{//Debe ser un benficiario se muestran los datos 
         if (($cuanBenf>0) and ($numfilas>0)){
	  $esti='Beneficiario';
	  echo" <input  TYPE=\"hidden\"  id=\"estt\" VALUE=$esti>";

	 ?>
           <table class="tabla_cabecera3" cellpadding=0 cellspacing=0>  
             <tr>
	        <td colspan=4 class="titulo_seccion">Datos personales del Beneficiario</td>
	     </tr>
	   </table>
	   <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
              <tr>
            <td class="tdtitulos"></td>
            <td class="tdcampos"></td>
	        <td class="tdtitulos">Fecha Inclusi&oacute;n al sistema:</td>
            <td class="tdcampos"><?php echo $elidcliente['fecha_creado'];?></td>
	      </tr>
	      <tr>
	         <td class="tdtitulos">C&eacute;dula:</td>
		 <td class="tdcampos"><?php echo $daBenf['cedula'];?></td>
	         <td class="tdtitulos">Nombres y Apellidos:</td> 
	         <td class="tdcampos" ><?php echo "$daBenf[nombres] $daBenf[apellidos]"; ?></td>
	      </tr>
	      <tr>
	         <td class="tdtitulos">Fecha Nacimiento:</td>
		 <td class="tdcampos" ><?php echo camfecha($daBenf['fecha_nacimiento']); ?></td>
                 <td class="tdtitulos">Edad:</td>
                 <td class="tdcampos" ><label style="color: #ff0000"> <?php echo calcular_edad($daBenf['fecha_nacimiento']); ?> a&ntilde;os</label></td>
		 <td class="tdtitulos">Sexo:</td>
		 <td class="tdcampos" ><?php $daBenf['sexo'];
		    if($elidcliente['sexo']==0){
		       echo "FEMENINO";
		    }else{
		       echo "MASCULINO";}?></td>
	      </tr>    			
	      <tr>
	           <td class="tdtitulos">Tel&eacute;fono:</td>
	           <td class="tdcampos" ><?php echo $daBenf['telefono_hab']; echo " / "; echo $daBenf['telefono_otro']; ?></td>
		   <td class="tdtitulos">Celular:</td>
		   <td class="tdcampos" ><?php echo $daBenf['celular']; ?></td>
	     </tr>
	     <tr>
	          <td colspan=1 class="tdtitulos">Direcci&oacute;n:</td>
		  <td colspan=3 class="tdcampos" ><?php echo $daBenf['direccion_hab']; ?></td>
	     </tr>
	     <tr>
	         <td colspan=1 class="tdtitulos">Comentarios:</td>
	         <td colspan=3 class="tdtitulos" ><label style="color: #ff0000"><?php echo $daBenf['comentarios']; ?></label></td>
	    </tr>
                 <?php
		 $ti=1;
		 $tv=1;
                      while ($estaente=asignar_a($resuEnteB)){
                       $elttl1=$estaente[id_beneficiario];
		               $elttlar=$elttl1;
					$url="'views01/isolicitudmedicamento.php?id_titular=$estaente[id_titular]&id_beneficiario=$elttl1'";   
			        if($ti>1){
						 echo"<tr><td colspan=7><hr></td></tr>";
						 $eltitulperten=$estaente['id_titular']; 
						 $buscartitupe=("select clientes.nombres,clientes.apellidos from clientes,titulares
                                                    where titulares.id_cliente=clientes.id_cliente and 
													titulares.id_titular=$eltitulperten");
						  $repbuscartitupe=ejecutar($buscartitupe);
						  $datatitpertenece=assoc_a($repbuscartitupe);
						  $nombcompletoti="<br>$datatitpertenece[nombres]  $datatitpertenece[apellidos]";  
						  $mensajetitupert="( Beneficiario registrado al titular $nombcompletoti )";  
						}   
						 
	               echo "
		             <tr>
			         <td class=\"tdtitulos\">El ente al cual pertenece:</td>
			         <td class=\"tdcampos\" >$estaente[nombre] $mensajetitupert</td>";
                                   if ( ($estaente['estado_cliente']=='ACTIVO')){
                                  $decsalud=("select declaracion_t.id_declaracion from declaracion_t where declaracion_t.id_beneficiario=$elttl1 limit 1");
                                  $repuesta=ejecutar($decsalud);
                                  $cuantdesa=num_filas($repuesta);
		                    echo"
		                          <td class=\"tdtitulos\">Estatus:</td>
		                          <td  class=\"tdtitulos\" ><label style=\"color: #ff0000\"> $estaente[estado_cliente]</label></td>
			            </tr>		  
                        <tr>
		                        <td class=\"tdtitulos\">Tipo ente:</td>
	                             <td  class=\"tdtitulos\" ><label style=\"color: #ff0000\"> $estaente[tipo_ente]</label></td>
		              </tr>
                      
	                     <tr>
                                <input  TYPE=\"hidden\" name=\"idcott\" id=\"idcott_$ti\" VALUE=$elttlar > ";
                                if(($estaente[id_tipo_ente]<>4) and ($estaente[id_tipo_ente]<>6)){
                                echo"<td class=\"tdtitulos\"><input type=\"radio\" id=\"group_$ti\" value=$elttlar onClick=\"Effect.Grow('titcober');VerCob1()\" style=\"cursor:pointer\" title=\"Ver Cobertura\">Ver cobertura</td>";
                                }else{
                                  echo"<td class=\"tdtitulos\"></td>";?>
                          <div id="toggleText" style="display: none"
                          <?echo "<td class=\"tdtitulos\"><input type=\"radio\" id=\"group_$ti\" value=$elttlar onClick=\"Effect.Grow('titcober');VerCob1()\" style=\"cursor:pointer\" title=\"Ver Cobertura\">Ver cobertura</td> ";?>
                         </div>
                       <? }
echo "<td class=\"tdtitulos\"><a href=\"javascript: imprimir($url);\" class=\"boton\" title=\"Imprimir Planilla de Solicitud de Medicamento\"> Solicitud Medicamento </a></td>";
         if($cuantdesa>=1){
    echo "<td class=\"tdtitulos\">
               <a href=\"views01/mostdeclaracion.php?titular=$elttl1-B\" title=\"Declaraci&oacute;n de Salud\" class=\"boton\" onclick=\"Modalbox.show(this.href, {title: this.title, width:900,height:400, overlayClose: false}); return false;\">Declaraci&oacute;n Salud</a>
            </td>";
         }
		             echo"</tr>";
			     $ti++;
			     $tv++;
		             }else{
		               echo" <tr>
		                       <td class=\"tdtitulos\">Estatus:</td>
			               <td class=\"tdcampos\" > $estaente[estado_cliente]</td>
			            </tr>";
	                    }
		    }
	
                echo"</table>
		   <input  TYPE=\"hidden\" name=\"vfi\" id=\"vfi\" VALUE=$tv>";
		   ?>
                   <div id="titcober"></div> 
		
		<?php 
		echo"<table class=\"tabla_cabecera3\" cellpadding=0 cellspacing=0>
	                <tr>
		            <td colspan=4 class=\"titulo_seccion\">Beneficiario asignado a el(los) titular(es):</td>
		        </tr>
		     </table>
                     <table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
                     <TR>
		         <TH>C&eacute;dula</TH>
		         <TH>Nombres y Apellidos</TH>
		         <TH>Parentesco</TH>	 
		         <TH>Ente</TH>
		         <TH>Estatus</TH>
                       </TR>";
	
                           while ($daTyB=asignar_a($resuETB1)){
                           $elideTyB= $daTyB['id_titular'];
			   $elidsoloB=$daTyB['id_beneficiario'];
		           $queryTcB=("select clientes.nombres,clientes.apellidos,clientes.cedula,entes.nombre,
       estados_clientes.estado_cliente,parentesco.parentesco 
from
       clientes,estados_clientes,parentesco,titulares,beneficiarios,estados_t_b,entes
where 
  titulares.id_titular=beneficiarios.id_titular and titulares.id_titular=$elideTyB and 
  titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente 
 and titulares.id_titular=beneficiarios.id_titular and estados_t_b.id_beneficiario=beneficiarios.id_beneficiario and beneficiarios.id_parentesco=parentesco.id_parentesco and 
 beneficiarios.id_beneficiario=$elidsoloB and
 estados_clientes.id_estado_cliente=estados_t_b.id_estado_cliente 
 group by 
   clientes.nombres,clientes.apellidos,clientes.cedula,entes.nombre,
       estados_clientes.estado_cliente,parentesco.parentesco
order by estado_cliente;");
			   $resuTyB=ejecutar($queryTcB);
			   $ladatByF=assoc_a($resuTyB);
	               echo " <tr>
			       <td class=\"tdcampos\" onclick=\"hola($ladatByF[cedula])\" style=\"cursor:pointer\" title=\"Ver data\"> $ladatByF[cedula]</td>
			       <td class=\"tdcampos\" >$ladatByF[nombres] $ladatByF[apellidos] </td>
			       <td class=\"tdcampos\">$ladatByF[parentesco]</td>  
			       <td class=\"tdcampos\">$ladatByF[nombre]</td>";
			          if ( ($ladatByF['estado_cliente']=='ACTIVO')){
				     echo" <td  class=\"tdtitulos\" ><label style=\"color: #ff0000\"> $ladatByF[estado_cliente]</label></td>";
				  }else{
				     echo" <td class=\"tdcampos\" >$ladatByF[estado_cliente]</td>";
				 }
			 
			 echo"</tr>";
			}
		echo"</table>";

           ?>
	    
    <?  }//fin de la comparacion de si hay beneficiarios o no    
      }//fin de los datos principales de los beneficiarios
 }else{//fin de la comparacion de la variable cedula para ver si era una cedula o no
       // variable que esta entrado es es $ceduclien  
    $nombuscar=strtoupper($ceduclien);
    $querynombre=("select nombres,apellidos,cedula from clientes where nombres like('%$nombuscar%');");
    $ejecutanom=ejecutar($querynombre);
    $numfnom=num_filas($ejecutanom);
         if ($numfnom>=1){
	    echo"<table class=\"tabla_cabecera3\" cellpadding=0 cellspacing=0>
	            <tr>
		     <td colspan=4 class=\"titulo_seccion\">Usuarios(as) asociados con el nombre $nombuscar</td>
		    </tr>
	         </table>
		 <table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
		    <TR>
		         <TH>Nombres y Apellidos</TH>
		         <TH>C&eacute;dula</TH>
			 <th>Ver data</th>
		    </TR> ";
	     $ti=1;	    
	     $tv=1;
	     while ($ladanomb=asignar_a($ejecutanom)){
	       echo " <tr>
	                <td class=\"tdcampos\"> $ladanomb[nombres] $ladanomb[apellidos]</td>
		        <td class=\"tdcampos\" >$ladanomb[cedula] </td>
                        <td class=\"tdtitulos\" align=\"right\"><input type=\"radio\" id=\"grupo_$ti\" value=$ladanomb[cedula] onClick=\"Effect.SlideDown('clientes');VerCob1a()\" style=\"cursor:pointer\"></td>
		      </tr>";	
	     $ti++;
	     $tv++;
             }  

	     echo"</table>";
	 }
	   echo"<input  TYPE=\"hidden\"  id=\"vfno\" VALUE=$tv>";
}
    ?>
   </form>
     
