<?
include ("../../lib/jfunciones.php");
sesion();
$cecliendona=$_POST['cedudona'];
$buscacliendona=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.celular,clientes.telefono_hab from clientes where clientes.cedula='$cecliendona';");
$esben=0;
$repbuscacliendona=ejecutar($buscacliendona);
$cuantoscliendona=num_filas($repbuscacliendona);
if ($cuantoscliendona>=1){
	$datacliendona=assoc_a($repbuscacliendona);
	$idcliendona=$datacliendona['id_cliente'];
       
	$nomcliendona=$datacliendona['nombres'];
	$apellicliendona=$datacliendona['apellidos'];
        $telf1=$datacliendona['telefono_hab'];  
        $telf2=$datacliendona['celular'];
        $versiesti=("select titulares.id_titular from titulares where titulares.id_cliente=$idcliendona");
        $repversti=ejecutar($versiesti);
        $cuantosti=num_filas($repversti);
        if($cuantosti>=1){
	$vertituoben=("select entes.nombre,estados_clientes.estado_cliente,titulares.id_titular 
                            from 
                                  entes,estados_clientes,titulares,estados_t_b 
                           where titulares.id_cliente=$idcliendona and titulares.id_ente=entes.id_ente and 
                                     titulares.id_titular=estados_t_b.id_titular and 
                                    estados_t_b.id_estado_cliente=4 and 
                                    estados_t_b.id_beneficiario=0 and 
                                    estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente;");
	$repvertioben=ejecutar($vertituoben);
	}else{
         
           $esben=1;
           $vertituoben=("select clientes.nombres,clientes.apellidos,entes.nombre,estados_clientes.estado_cliente,beneficiarios.id_beneficiario 
                    from 
                          entes,estados_clientes,titulares,clientes,beneficiarios,estados_t_b
                   where beneficiarios.id_cliente=$idcliendona and beneficiarios.id_titular=titulares.id_titular and titulares.id_ente=entes.id_ente and beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and estados_t_b.id_estado_cliente=4 and  estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and titulares.id_cliente=clientes.id_cliente;");
	$repvertioben=ejecutar($vertituoben);
        }
	echo"<div class=\"rbroundbox\">
	<div class=\"rbtop\"><div></div></div>
		<div class=\"rbcontent\">
			";

	echo"<table   cellpadding=0 cellspacing=0>
                 <tr>
                     <td align=\"left\">Nombres: </td>
                     <td align=\"left\" width=\"15%\"> $nomcliendona</td>
                     <td  align=\"right\">Apellidos: </td>
                     <td align=\"left\" width=\"15%\"> $apellicliendona</td>
                     <td align=\"left\">Telf Hab: </td>
                     <td align=\"left\" width=\"10%\">$telf1</td>
                     <td  align=\"right\">Telf Cel: </td>
                     <td align=\"left\" width=\"10%\">$telf2</td> 
				 </tr> 
                  ";
				while($lostitularesdona=asignar_a($repvertioben,NULL,PGSQL_ASSOC)){
                             $eltituobene=$lostitularesdona[id_titular];
                             if(empty($eltituobene)){
                               $eltituobene="B@$lostitularesdona[id_beneficiario]";
                             }else{
                                $eltituobene="T@$lostitularesdona[id_titular]";
                               }  
                             $nomt=$lostitularesdona[nombres];
                             $apet=$lostitularesdona[apellidos];  
			  echo"
                              <tr>
                               <td align=\"left\">Ente:</td>
                               <td align=\"left\" width=\"20%\"> $lostitularesdona[nombre]</td>
                               <td align=\"right\">Estatus:</td>
                               <td align=\"left\" width=\"20%\"> $lostitularesdona[estado_cliente]</td>
                              </tr>
                            ";
                          }     
                              if($esben==1){
                                   echo"
                               <tr>
                               <td align=\"left\" colspan=8><hr></td>
                               </tr> 
                              <tr>
                               <td align=\"left\">Nombre del titular:</td>
                               <td align=\"left\" width=\"20%\">$nomt</td>
                               <td align=\"right\">Apellidos:</td>
                               <td align=\"left\" width=\"20%\">$apet</td>
                              </tr>
                            ";
                                }
				
	echo"</table>";			
	echo"
		</div>
	<div class=\"rbbot\"><div></div></div>
</div>";
}else{
  echo "<label style=\"color:#DF0101\" >El cliente no existe!!!!</label><label class=\"boton\" style=\"cursor:pointer\" onclick=\"reg_clientes(); return false;\" >Registrar</label>";
 }
?>
<input type='hidden' id='esun' value='<?echo $eltituobene?>'>
