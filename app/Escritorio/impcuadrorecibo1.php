<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();        
$idtitular=$_REQUEST['titular'];
$idcontrato=$_REQUEST['contrato'];
$idrecibo=$_REQUEST['recibo'];
$lapoliza=$_REQUEST['lapoliza'];
$textos=$_REQUEST['texo'];
$bucarpoliza=("select polizas.id_poliza,polizas.nombre_poliza 
                       from polizas where id_poliza=$lapoliza order by nombre_poliza;");
$reppoliza=ejecutar($bucarpoliza);
$anos=date("Y");
$mes=date("m");
$eldia=date("d");
$lafechahoy="$eldia-$mes-$anos";
$buscodatitula=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.direccion_hab,
                             clientes.telefono_hab,clientes.celular,ciudad.ciudad,estados.estado,clientes.fecha_nacimiento 	
                           from
                             clientes,titulares,ciudad,estados
                        where
                           clientes.id_cliente=titulares.id_cliente and
                           clientes.id_ciudad=ciudad.id_ciudad and
                           ciudad.id_estado=estados.id_estado and
                           titulares.id_titular=$idtitular;");
$repdatitula=ejecutar($buscodatitula);     
$datatitula=assoc_a($repdatitula);
$nombrecompleto="$datatitula[nombres] $datatitula[apellidos]";
//para ver los posibles beneficiarios del titular
$verbeni=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.edad,clientes.sexo,
                     parentesco.parentesco 
                 from
                    clientes,beneficiarios,parentesco,estados_t_b
                   where
                     beneficiarios.id_titular=$idtitular and
                     beneficiarios.id_cliente=clientes.id_cliente and
                     beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
                     estados_t_b.id_estado_cliente=4 and
                     beneficiarios.id_parentesco=parentesco.id_parentesco;");
$repbeni=ejecutar($verbeni);                     
$idente=$_REQUEST['elente'];

$busdatente=("select entes.fecha_inicio_contrato,entes.fecha_renovacion_contrato,sucursales.sucursal from
entes,sucursales
where entes.id_ente=$idente and
entes.id_sucursal=sucursales.id_sucursal;");
$repbusente=ejecutar($busdatente);
$dataente=assoc_a($repbusente);
$verpolizas=("select polizas.nombre_poliza,titulares_polizas.id_titular 
from titulares_polizas,polizas 
where 
titulares_polizas.id_titular=$idtitular and
titulares_polizas.id_poliza=polizas.id_poliza");
$repvepoliza=ejecutar($verpolizas);
$veidrecibo=("select tbl_recibo_contrato.id_recibo_contrato,tbl_recibo_contrato.id_comisionado 
from
       tbl_recibo_contrato
where
   tbl_recibo_contrato.num_recibo_prima='$idrecibo';");
$repvercibo=ejecutar($veidrecibo);
$datnumrecibo=assoc_a($repvercibo);
$eliddelrecibo=$datnumrecibo[id_recibo_contrato];
$elcomisionado=$datnumrecibo[id_comisionado];
$cualcomisionado=("select comisionados.nombres,comisionados.apellidos,comisionados.codigo 
                                from comisionados where comisionados.id_comisionado=$elcomisionado;");
$repcualcomision=ejecutar($cualcomisionado);                                
$datacomision=assoc_a($repcualcomision);
$nombrcomisionado="$datacomision[nombres] $datacomision[apellidos]";
$elcodigcomisionado=$datacomision[codigo];
?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo">
</td>
<td colspan=1 class="titulo2">
<strong></strong> 
</td>
<td colspan=1 class="titulo2">
<strong>NRO. DE CONTRATO:<BR><?echo $idcontrato?><BR><HR size="2" width="30%" align="left"/><BR>
NRO. DE RECIBO:<BR><?echo $idrecibo?></strong> 
</td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=2 class="titulo">

</td>
</tr>
<tr>
	<tr>
    <br>
    <br>
		<td colspan=6 align="center" class="titulo3"><strong>CUADRO RECIBO</strong></td>
	</tr>
	<tr>
		<td colspan=4 class="tdcamposc">&nbsp;</td>
	</tr>
</table>
<table   class="tabla_citas"  cellpadding=0 cellspacing=0 border=1>
          </tr>
		   <td colspan=12 align="center" class="titulo3"><strong>DATOS PERSONALES</strong></td>
	 </tr>
         <tr>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt">Usuario titular.</label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt">C&eacute;dula de identidad.</label></td>                  
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt">Fecha nacimiento.</label></td>                  
         </tr>
         <tr>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?echo "$datatitula[nombres] $datatitula[apellidos]"?></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?echo "$datatitula[cedula]"?></label></td>                  
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?list($anot,$mest,$diat)=explode("-",$datatitula[fecha_nacimiento]);
                                                                                                                            echo "$diat-$mest-$anot";?></label></td>                  
         </tr>
         <tr>
                  <td colspan=12 class="tdtituloss"><label style="font-size: 8pt">Direcci&oacute;n de cobro.</label></td>         
         </tr>
         <tr>
                  <td colspan=12 class="tdtituloss"><label style="font-size: 8pt"><?echo "$datatitula[direccion_hab]"?></label></td>         
         </tr>
         <tr>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt">Estado.</label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt">Ciudad.</label></td>                  
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt">Tel&eacute;fono.</label></td>                  
         </tr>
         <tr>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?echo "$datatitula[estado]"?></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?echo "$datatitula[ciudad]"?></label></td>                  
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?echo "$datatitula[telefono_hab] / $datatitula[celular]"?></label></td>                  
         </tr>
         </tr>
		   <td colspan=12 align="center" class="titulo3"><strong>Vigencia del Contrato</strong></td>
	    </tr>
        <tr>
                  <td colspan=10 class="tdtituloss"><label style="font-size: 8pt"><?list($anocon,$mescon,$diacon)=explode("-",$dataente[fecha_inicio_contrato]);
                                                                                                                              echo "Desde: $diacon-$mescon-$anocon      a las 12:00 M";?></label></td>
                  <td class="tdtituloss"><label style="font-size: 8pt"><?list($anocon,$mescon,$diacon)=explode("-",$dataente[fecha_renovacion_contrato]);
                                                                                                                              echo "Hasta: $diacon-$mescon-$anocon      a las 12:00 M";?></label></td>                  
         </tr>
         </tr>
		   <td colspan=12 align="center" class="titulo3"><strong>Vigencia del recibo</strong></td>
	    </tr>
        <tr>
                  <td colspan=10 class="tdtituloss"><label style="font-size: 8pt"><?list($anocon,$mescon,$diacon)=explode("-",$dataente[fecha_inicio_contrato]);
                                                                                                                              echo "Desde: $diacon-$mescon-$anocon      a las 12:00 M";?></label></td>
                  <td class="tdtituloss"><label style="font-size: 8pt"><?list($anocon,$mescon,$diacon)=explode("-",$dataente[fecha_renovacion_contrato]);
                                                                                                                              echo "Hasta: $diacon-$mescon-$anocon      a las 12:00 M";?></label></td>                  
         </tr>
         <tr>
                  <td colspan=10 class="tdtituloss"><label style="font-size: 8pt">Sucursal de emisi&oacute;n.</label></td>    
                  <td class="tdtituloss"><label style="font-size: 8pt">Sucursal de cobro.</label></td>                  
         </tr>
         <tr>
                  <td colspan=10 class="tdtituloss"><label style="font-size: 8pt"><?echo "$dataente[sucursal]"?></label></td>
                  <td class="tdtituloss"><label style="font-size: 8pt"><?echo "$dataente[sucursal]"?></label></td>                  
         </tr>
          <?$filas=1; 
            while($laspoliza=asignar_a($reppoliza,NULL,PGSQL_ASSOC)){
                $idpoliza=$laspoliza[id_poliza];
                $propiedpoliza=("select propiedades_poliza.cualidad,propiedades_poliza.descripcion,propiedades_poliza.monto 
                                            from propiedades_poliza
                                            where id_poliza=$idpoliza");
                $repdapoliza=ejecutar($propiedpoliza);
                ?>        
         </tr>
                <td colspan=12 align="center" class="titulo3"><strong> <?echo $laspoliza[nombre_poliza]?></strong></td>
         </tr>
            <?if($filas==1){?>
                <tr>
                  <td colspan=6 class="tdtituloss"><label style="font-size: 8pt">Cobertura.</label></td>
                  <td colspan=6 class="tdtituloss"><label style="font-size: 8pt">COBERTURA AMPARADA .</label></td>                  
                </tr>
            <?}
              while($ppoli=asignar_a($repdapoliza,NULL,PGSQL_ASSOC)){?>
             <tr>
                  <td rowspan=1 colspan=6><?echo $ppoli[descripcion]?></td>
                  <td rowspan=1 colspan=6><?echo $ppoli[monto]?></td>         
             </tr>
         <?  
            }
            $filas++;
         }
         ?>
         </tr>
		   <td colspan=12 align="center" class="titulo3"><strong>BENEFICIARIOS</strong></td>
	    </tr>
        <tr>
                  <td colspan=2 class="tdtituloss"><label style="font-size: 8pt">Parentesco.</label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt">Apellidos y Nombres.</label></td>                  
                  <td colspan=2 class="tdtituloss"><label style="font-size: 8pt">C&eacute;dula.</label></td>                  
                  <td colspan=2 class="tdtituloss"><label style="font-size: 8pt">Edad.</label></td>                  
                  <td colspan=2 class="tdtituloss"><label style="font-size: 8pt">Genero.</label></td>                  
         </tr>
         <?  while($losbeni=asignar_a($repbeni,NULL,PGSQL_ASSOC)){?>
         <tr>
              <td colspan=2class="tdtituloss"><label style="font-size: 8pt"><?echo $losbeni[parentesco]?></label></td>
              <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?echo "$losbeni[apellidos] $losbeni[nombres]"?></label></td>                  
              <td colspan=2 class="tdtituloss"><label style="font-size: 8pt"><?echo $losbeni[cedula]?></label></td>                  
              <td colspan=2 class="tdtituloss"><label style="font-size: 8pt"><?echo $losbeni[edad]?></label></td>     
              <td colspan=2 class="tdtituloss"><label style="font-size: 8pt"><?if($losbeni[sexo]==0){
                                                                                                                           echo "F";
                                                                                                                         }else{ echo "M";}?></label></td>     
         </tr>
         <?}?>
         </tr>
		   <td colspan=12 align="center" class="titulo3"><strong>Coberturas</strong></td>
	    </tr>
        <tr>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt">Usuario.</label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt">Plan.</label></td>                  
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt">Prima Neta.</label></td>                  
        </tr>
          <? 
              
                $buscolagente=("select tbl_caract_recibo_prima.id_titular,tbl_caract_recibo_prima.id_beneficiario,
                                           tbl_caract_recibo_prima.monto_prima,tbl_caract_recibo_prima.id_prima
                                   from
                                             tbl_caract_recibo_prima
                                    where 
                                     tbl_caract_recibo_prima.id_recibo_contrato=$eliddelrecibo and
                                    tbl_caract_recibo_prima.id_titular=$idtitular order by 
                                     tbl_caract_recibo_prima.id_beneficiario;");
                 $resplagente=ejecutar($buscolagente);                    
                while($lomotbeni=asignar_a($resplagente,NULL,PGSQL_ASSOC)){
                    $esuntitu=$lomotbeni[id_titular];
                    $esunbenf=$lomotbeni[id_beneficiario];
                    $lapimaes=$lomotbeni[id_prima];
                    $cualespla=("select polizas.nombre_poliza from
                                polizas,primas
                                where
                                   primas.id_prima=$lapimaes and
                                   primas.id_poliza=polizas.id_poliza;");
                     $repcualpla=ejecutar($cualespla); 
                     $infocualplan=assoc_a($repcualpla);
                     $elnomplane=$infocualplan[nombre_poliza];
                    if($esunbenf==0){
                       $datatitula=("select clientes.nombres,clientes.apellidos from
                                            clientes,titulares
                                            where
                                            titulares.id_titular=$esuntitu and
                                            titulares.id_cliente=clientes.id_cliente");
                       $redatitula=ejecutar($datatitula);   
                       $datreptitula=assoc_a($redatitula);
                       $nombrpersona="$datreptitula[nombres] $datreptitula[apellidos]";
                    }else{
                       $datatitula1=("select clientes.nombres,clientes.apellidos from
                                            clientes,beneficiarios
                                            where
                                            beneficiarios.id_beneficiario=$esunbenf and
                                             beneficiarios.id_cliente=clientes.id_cliente");
                       $redatitula1=ejecutar($datatitula1);   
                       $datreptitula1=assoc_a($redatitula1);
                       $nombrpersona="$datreptitula1[nombres] $datreptitula1[apellidos]";
                    }
            ?> 
                <tr>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?echo $nombrpersona?></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?echo "$elnomplane"?></label></td>                  
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?echo "$lomotbeni[monto_prima] Bs.S"?></label></td>                  
              </tr>
          <?
              $nombrpersona="";
              }
                  
         
          $totalmonto=("select sum(tbl_caract_recibo_prima.monto_prima) as totalprima
                       from
                            tbl_caract_recibo_prima
                      where 
                            tbl_caract_recibo_prima.id_recibo_contrato=$eliddelrecibo and
                            tbl_caract_recibo_prima.id_titular=$idtitular;");
          $reptotalmonto=ejecutar($totalmonto);       
          $elmontotal=assoc_a($reptotalmonto);
          $montobol=$elmontotal[totalprima];
        ?>
        <tr>
                  <td colspan=8 class="tdtituloss" align='right'><label style="font-size: 8pt"><strong>TOTAL&nbsp;&nbsp;&nbsp;&nbsp;</strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><?echo  "$montobol Bs.S"?></strong></label></td>                  
        </tr>
        <tr>
                  <td colspan=8 class="tdtituloss" align='right'><label style="font-size: 8pt"><strong>TOTAL  PRIMA NETA ANUAL&nbsp;&nbsp;&nbsp;&nbsp;</strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><?echo  "$montobol Bs.S"?></strong></label></td>                  
        </tr>
        <tr>
                  <td colspan=8 class="tdtituloss" align='right'><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong></strong></label></td>                  
        </tr>
        <tr>
                  <td colspan=4 class="tdtituloss" align='right'><label style="font-size: 8pt"><strong>Comisionado.</strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong>C&oacute;digo.</strong></label></td>                  
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong>Referencia.</strong></label></td>   
        </tr>
         <tr>
                  <td colspan=4 class="tdtituloss" align='right'><label style="font-size: 8pt"><strong><?echo $nombrcomisionado?></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><?echo $elcodigcomisionado?></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><?$refer=number_format($montobol*(10/100), 2, '.', '');
echo "00000$refer"?></strong></label></td>
        </tr>
          <tr>
                  <td colspan=12 class="tdtituloss"><label style="font-size: 7pt"><strong>Yo <?echo $nombrecompleto;?> 
                    obrando en nombre propio y de manera voluntaria declaro bajo f&eacute; de juramento que el dinero utilizado 
                    para el pago del plan de salud No tienen relaci&oacute;n alguna con dinero, capitales, 
                     bienes, haberes o t&iacute;tulos valores producto de las actividades o acciones a que se refiere el articulo 35 de la Ley Org&aacute;nica 
                    Contra la Delincuencia Organizada y Financiamiento al Terrorismo y el articulo 209 de la Ley Org&aacute;nica Contra el 
                    Trafico Il&iacute;cito y el Consumo de Sustancias Estupefacientes y Psicotr&oacute;picas; todo ello de conformidad con 
                    lo establecido en el articulo 40 de la Providencia Administrativa N° 514 de fecha 18 de febrero de 2011 emanada 
                    por la Superintendencia de la Actividad Aseguradora, 
                    publicada en la Gaceta Oficial de la Rep&uacute;blica Bolivariana de Venezuela N° 39.621 de fecha 22 de febrero de 2011, 
                    reformada a trav&eacute;s de la Providencia Administrativa N° SAA-001495 de fecha 27 de mayo de 2011, 
                    publicada en la Gaceta Oficial de la Rep&uacute;blica Bolivariana de Venezuela Nº 39.694 de fecha 13 de junio de 2011</strong></label></td>
        </tr>
 </table>        
     <table   class="tabla_citas"  cellpadding=0 cellspacing=0>
        <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>                  
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>                  
        </tr>
        <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>                  
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>                  
        </tr>
        <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong>Firma Titular</strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>                  
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong>Firma Operador</strong></label></td>                  
        </tr>
         <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>                  
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>                  
        </tr> <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>                  
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>                  
        </tr> 
        <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong>Pulgar mano derecha</strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>                  
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>                  
        </tr>
        <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>
        </tr>
         <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>
        </tr>
        <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>
        </tr>
        <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>
        </tr>

         <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><IMG SRC="firmant.jpg" width="80" height="80"></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong></strong></label></td>
        </tr> 
        <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong>Antonio Jose Guerrero Quintero</strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong></strong></label></td>
        </tr> 
        <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong>Presidente. Clinisalud Medicina Prepagada</strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong></strong></label></td>
        </tr> 
         <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong><br></strong></label></td>
        </tr> 
        <tr>
                  <td colspan=4 class="tdtituloss" ><label style="font-size: 12pt"><strong><?echo $textos?></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong></strong></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong></strong></label></td>
        </tr> 

</table>
