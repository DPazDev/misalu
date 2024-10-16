<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();

$idtitular=$_REQUEST['titular'];
$idcontrato=$_REQUEST['contrato'];
$idrecibo=$_REQUEST['recibo'];
$lacotizacion=$_REQUEST['lacotiza'];
$textos=$_REQUEST['texo'];
$idente=$_REQUEST['elente'];

//** BUSCO DATOS DE LA COTIZACION**/
$bus_datos_cotizacion=("select tbl_cliente_cotizacion.cuotas,tbl_cliente_cotizacion.inicial
                  from tbl_caract_cotizacion,
                        tbl_cliente_cotizacion
                  where tbl_caract_cotizacion.id_cliente_cotizacion=$lacotizacion 
                  and tbl_caract_cotizacion.id_cliente_cotizacion=tbl_cliente_cotizacion.id_cliente_cotizacion;");
$b_datos_cotizacion=ejecutar($bus_datos_cotizacion);
$b_d_cot=assoc_a($b_datos_cotizacion);
$lainiciacontra=$b_d_cot[inicial];
$numcuotas=$b_d_cot[cuotas];

//** BUSQUEDA DE LA POLIZA A TRAVES DE LA COTIZACION***//
$bucarpoliza=("select tbl_caract_cotizacion.id_poliza,
                      tbl_caract_cotizacion.montoprima,
                      polizas.nombre_poliza,
                      polizas.deducible
                from  tbl_caract_cotizacion,polizas 
                where id_cliente_cotizacion=$lacotizacion 
                and   tbl_caract_cotizacion.id_poliza=polizas.id_poliza
             group by tbl_caract_cotizacion.id_poliza,
                      tbl_caract_cotizacion.montoprima,
                      nombre_poliza,
                      polizas.deducible;");
$reppoliza=ejecutar($bucarpoliza);

$reppoliza1=ejecutar($bucarpoliza);//EJECUTO DOS VECES PARA TRAER EL ID_POLIZA Y OBTENER LA MOENDA
$reppolizamoned=assoc_a($reppoliza1);
$id_poli=$reppolizamoned[id_poliza];
//**************/

//elmontotalprima
$montoprimatota=("select sum(montoprima) as totalprima from tbl_caract_cotizacion where id_cliente_cotizacion=$lacotizacion;");
$repmontoprima=ejecutar($montoprimatota);   
$datmotprima=assoc_a($repmontoprima);
$elmotesprima=$datmotprima['totalprima'];


$anos=date("Y");
$mes=date("m");
$eldia=date("d");
$lafechahoy="$eldia-$mes-$anos";


/** BUSQUEDA DEL TITULAR**/
$buscodatitula=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.direccion_hab,clientes.sexo,clientes.edad,
                             clientes.telefono_hab,clientes.celular,ciudad.ciudad,estados.estado,clientes.fecha_nacimiento,
                             titulares.tipocliente
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
$tipocl=$datatitula[tipocliente];


//** BUSQUEDA DEL BENEFICIARIO**/
$verbeni=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.edad,clientes.sexo,clientes.fecha_nacimiento,
                     parentesco.parentesco
                 from
                    clientes,beneficiarios,parentesco
                   where
                     beneficiarios.id_titular=$idtitular and
                     beneficiarios.id_cliente=clientes.id_cliente and
                     beneficiarios.id_parentesco=parentesco.id_parentesco;");
$repbeni=ejecutar($verbeni);
$bbenetitu=ejecutar($verbeni);
$benetitu=assoc_a($bbenetitu);



//** BUSQUEDA DEL ENTE **/
$busdatente=("select entes.fecha_inicio_contrato,entes.fecha_renovacion_contrato,entes.fecha_creado,sucursales.sucursal from
entes,sucursales
where entes.id_ente=$idente and
entes.id_sucursal=sucursales.id_sucursal;");
$repbusente=ejecutar($busdatente);
$dataente=assoc_a($repbusente);


$veidrecibo=("select tbl_recibo_contrato.id_recibo_contrato,tbl_recibo_contrato.id_comisionado
                from
                      tbl_recibo_contrato
                where
                  tbl_recibo_contrato.num_recibo_prima='$idrecibo';");
$repvercibo=ejecutar($veidrecibo);
$datnumrecibo=assoc_a($repvercibo);

$eliddelrecibo=$datnumrecibo[id_recibo_contrato];
$elcomisionado=$datnumrecibo[id_comisionado];

//** BUSQUEDA DEL COMISIONADO**/
$cualcomisionado=("select comisionados.nombres,comisionados.apellidos,comisionados.codigo
                    from comisionados 
                    where comisionados.id_comisionado=$elcomisionado;");
$repcualcomision=ejecutar($cualcomisionado);
$datacomision=assoc_a($repcualcomision);
$nombrcomisionado="$datacomision[nombres] $datacomision[apellidos]";
$elcodigcomisionado=$datacomision[codigo];

//////////MONEDA EXPRESIONES////
$SqlMoneda=("select tbl_monedas.id_moneda, tbl_monedas.moneda , tbl_monedas.simbolo  from polizas,tbl_monedas where polizas.id_moneda=tbl_monedas.id_moneda and id_poliza='$id_poli';");
$MonedaEJ=ejecutar($SqlMoneda);
$Moneda=asignar_a($MonedaEJ,NULL,PGSQL_ASSOC);
$moneda=$Moneda['moneda'];

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
<td colspan=1 align=left class="titulo2">

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
		<td colspan=6 align="center" class="titulo3"><strong>CONTRATO DE SERVICIOS DE MEDICINA PREPAGADA<bR>HOSPITALIZACIÓN,CIRUGÍA Y MATERNIDAD<br>CUADRO RECIBO DE SERVICIOS</strong></td>
   <td class="titulo2"> <strong>Contrato Nº:</strong> <?echo $idcontrato?><BR>
          <strong>Fecha: </strong><?echo $lafechahoy?></td>

 </td>
  </tr>
	<tr>
		<td colspan=4 class="tdcamposc">&nbsp;</td>
	</tr>
</table>
<table   class="tabla_citas"  cellpadding=0 cellspacing=0 border=1>
      <tr>
		   <td colspan=12 align="center" class="titulo3"><strong>DATOS DEL CONTRATANTE Y AFILIADO TITULAR</strong></td>
	   </tr>
     
        <tr>
                  <td colspan=7 class="tdtituloss"><label style="font-size: 8pt"><strong>Contratante:</strong> <?echo "$datatitula[nombres] $datatitula[apellidos]"?></label></td>
                  <td colspan=9 class="tdtituloss"><label style="font-size: 8pt"><strong>C&eacute;dula/R.I.F.:</strong><?echo "$datatitula[cedula]"?></label></td>
                  
         </tr>

         <? if($tipocl==1){?>
          <tr>
                  <td colspan=7 class="tdtituloss"><label style="font-size: 8pt"><strong>Titular:</strong> <?echo "$datatitula[nombres] $datatitula[apellidos]"?></label></td>
                  <td colspan=9 class="tdtituloss"><label style="font-size: 8pt"><strong>C&eacute;dula/R.I.F.:</strong><?echo "$datatitula[cedula]"?></label></td>
                  
         </tr>
         <?}else{?>
          <tr>
                  <td colspan=7 class="tdtituloss"><label style="font-size: 8pt"><strong>Titular:</strong> <?echo "$benetitu[apellidos] $benetitu[nombres]"?></label></td>
                  <td colspan=9 class="tdtituloss"><label style="font-size: 8pt"><strong>C&eacute;dula/R.I.F.:</strong> <?echo "$benetitu[cedula]"?></label></td>
            
         </tr>
        <? }?>
       
        
         <tr>
                  <td colspan=12 class="tdtituloss"><label style="font-size: 8pt"><strong>Direcci&oacute;n de cobro:</strong> <?echo "$datatitula[direccion_hab]"?></label></td>
         </tr>
         
         <tr>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong>Estado:</strong> <?echo "$datatitula[estado]"?></label></td></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong>Ciudad:</strong> <?echo "$datatitula[ciudad]"?></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><strong>Tel&eacute;fono:</strong> <?echo "$datatitula[telefono_hab] / $datatitula[celular]"?></label></td>
         </tr>
 
         </tr>
      <tr>   
		   <td colspan=12 align="center" class="titulo3"><strong>DATOS DEL CONTRATO</strong></td>
	    </tr>
        <tr>
                  <td colspan=4 class="tdtituloss" style="font-size: 8pt"><strong>VIGENCIA  Desde</strong>  <?list($anocon,$mescon,$diacon)=explode("-",$dataente[fecha_inicio_contrato]);
                                                                                                                              echo " $diacon-$mescon-$anocon      a las 12:00 M";?></td>
                  <td colspan=3 class="tdtituloss" style="font-size: 8pt"><strong>Hasta</strong> <?list($anocon,$mescon,$diacon)=explode("-",$dataente[fecha_renovacion_contrato]);
                                                                                                                              echo "$diacon-$mescon-$anocon      a las 12:00 M";?></label></td>
                  <td colspan=5 class="tdtituloss"><label style="font-size: 8pt"><strong>Fecha de emisiòn</strong> <?echo "$dataente[fecha_creado]"?></td>
                    
        </tr>
	
         <tr>
                  <td colspan=5 class="tdtituloss" style="font-size: 8pt"><strong>Sucursal / Oficina </strong><?echo "$dataente[sucursal]"?></td>
                  <td colspan=2 class="tdtituloss" style="font-size: 8pt"><strong>Frecuencia de pago </strong><?echo "$numcuotas"?></td>
                  <td colspan=4 class="tdtituloss" style="font-size: 8pt"><strong>Moneda </strong><?echo "$moneda"?></td>     
         </tr>
       
         
        <tr>    
            <td colspan=12 align="center" class="titulo3"><strong>GRUPO AFILIADO</strong></td>
	    </tr>
        <tr>
                  <td colspan=1 class="tdtituloss" style="font-size: 8pt"><strong>Nro.</strong></td>
                  <td colspan=3 class="tdtituloss" style="font-size: 8pt"><strong>Apellidos y Nombres</strong></td>
                  <td colspan=1 class="tdtituloss" style="font-size: 8pt"><strong>C&eacute;dula</strong></td>          
                  <td colspan=1 class="tdtituloss" style="font-size: 8pt"><strong>Fecha de Nac.</strong></td>
                  <td colspan=1 class="tdtituloss" style="font-size: 8pt"><strong>Edad</strong></td>
                  <td colspan=1 class="tdtituloss" style="font-size: 8pt"><strong>Sexo</strong></td>
                  <td colspan=4 class="tdtituloss" style="font-size: 8pt"><strong>Parentesco</strong></td>
         </tr>
                <?   

       if($tipocl==1){?>
  <tr>
    <td colspan=1 class="tdtituloss"><label style="font-size: 8pt"><?echo "Titular"?></label></td>
    <td colspan=3 class="tdtituloss"><label style="font-size: 8pt"><?echo "$nombrecompleto"?></label></td>
    <td colspan=1 class="tdtituloss"><label style="font-size: 8pt"><?echo $datatitula[cedula]?></label></td>
    <td colspan=1 class="tdtituloss"><label style="font-size: 8pt"><?echo $datatitula[fecha_nacimiento]?></label></td>
    <td colspan=1 class="tdtituloss"><label style="font-size: 8pt"><?echo $datatitula[edad]?></label></td>
    <td colspan=1 class="tdtituloss"><label style="font-size: 8pt"><?if($datatitula[sexo]==0){
                                                                                                                           echo "F";
                                                                                                                         }else{ echo "M";}?></label></td>
    </tr>
   
<?php }
        $num=0;
        while($losbeni=asignar_a($repbeni,NULL,PGSQL_ASSOC)){
           

               $num++;   
               
         ?>
            
         <tr>  
              <td colspan=1 class="tdtituloss"><label style="font-size: 8pt"><?echo "$num"?></label></td>
              <td colspan=3 class="tdtituloss"><label style="font-size: 8pt"><?echo "$losbeni[nombres] $losbeni[apellidos] "?></label></td>
              <td colspan=1 class="tdtituloss"><label style="font-size: 8pt"><?echo $losbeni[cedula]?></label></td>
              <td colspan=1 class="tdtituloss"><label style="font-size: 8pt"><?echo $losbeni[fecha_nacimiento]?></label></td>
              <td colspan=1 sclass="tdtituloss"><label style="font-size: 8pt"><?echo $losbeni[edad]?></label></td>
              <td colspan=1 class="tdtituloss"><label style="font-size: 8pt"><?if($losbeni[sexo]==0){
                                                                                                                           echo "F";
                                                                                                                         }else{ echo "M";}?></label></td>
              <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?echo $losbeni[parentesco]?></label></td>
         </tr>
         <?
          
        }
         ?>







         </tr>
                <td colspan=12 align="center" class="titulo3"><strong>COBERTURAS</strong></td>
         </tr>

         <tr>
                  <td colspan=1 class="tdtituloss" style="font-size: 8pt"><strong>Nro.</strong></td>
                  <td colspan=4 class="tdtituloss" style="font-size: 8pt"><strong>Descripción de coberturas</strong></td>
                  <td colspan=1 class="tdtituloss" style="font-size: 8pt"><strong>Límite de <br>Responsabilidad/Moneda</strong></td>
                  <td colspan=1 class="tdtituloss" style="font-size: 8pt"><strong>Deducible/Moneda</strong></td>
                  <td colspan=1 class="tdtituloss" style="font-size: 8pt"><strong>Cuota <br>Anual/Moneda</strong></td>
                  <td colspan=4 class="tdtituloss" style="font-size: 8pt"><strong>Cuota según frecuencia de Pago/Moneda</strong></td>
         </tr>
        
         

         <?php 
            
            $numeroCobertura=0;

           
           while($laspoliza=asignar_a($reppoliza,NULL,PGSQL_ASSOC)){
                       $idpoliza=$laspoliza[id_poliza];
                       $montopri=$laspoliza[montoprima];
          //////////MONEDA EXPRESIONES////
          $SqlMoneda=("select tbl_monedas.id_moneda, tbl_monedas.moneda , tbl_monedas.simbolo  from polizas,tbl_monedas where polizas.id_moneda=tbl_monedas.id_moneda and id_poliza='$idpoliza';");
          $MonedaEJ=ejecutar($SqlMoneda);
          $Moneda=asignar_a($MonedaEJ,NULL,PGSQL_ASSOC);
          $moneda=$Moneda['moneda'];


                  $propiedpoliza=("select propiedades_poliza.cualidad,propiedades_poliza.descripcion,propiedades_poliza.monto
                                    from propiedades_poliza
                                    where id_poliza=$idpoliza");
                  $repdapoliza=ejecutar($propiedpoliza);

                
                  
                  $linea=1;
              while($ppoli=asignar_a($repdapoliza,NULL,PGSQL_ASSOC)){
                if($montopri>0){    
                   $numeroCobertura++; 
                  $multi=$elmotesprima*($lainiciacontra/100) ;                                  
                 ?>
  
                   <td colspan=1><?echo $numeroCobertura?></td>
                   <td colspan=4><?echo $ppoli[descripcion]?></td>
                   <td colspan=1><?echo $ppoli[monto] .$moneda?></td>
                   <?if($linea==1){?>
                        <td rowspan=1 colspan=1><?echo $reppolizamoned[deducible]?></td>
                   <?}else{?>
                         <td rowspan=1 colspan=1></td>
                   <?}?>
            
                   <?if($linea==1){?>
                        <td rowspan=1 colspan=1><?echo $montopri.$moneda?></td>
                   <?}else{?>
                         <td rowspan=1 colspan=1></td>
                   <?}?>

                   <?if($linea==1){?>
                        <td colspan=2 align='center'><label style="font-size: 8pt"><?$resto=(($elmotesprima-$multi)/$numcuotas);
                                                                                                                          echo number_format($resto, 2, ',', '.');
                                                                                                                         echo "  $moneda";?></label></td> 
                   <?}else{?>
                         <td colspan=2></td>
                   <?}?>
                   
              </tr>        
              <?
             }
             $linea++;
            }

            
          }
         ?>
        <tr>
            <td colspan=7 class="tdtituloss" align='right' style="font-size: 8pt"><strong>TOTAL</strong></td>
         
            <td colspan=1 align='center'><?echo $elmotesprima . $moneda?>
            <td colspan=2 align='center'><label style="font-size: 8pt"><?$resto=(($elmotesprima-$multi)/$numcuotas);
                                                                                                                          echo number_format($resto, 2, ',', '.');
                                                                                                                         echo "  $moneda";?></label></td>          
        </tr>



         <tr>
		   <td colspan=12 align="center" class="titulo3"><strong>OBSERVACIONES</strong></td>
	    </tr>
      <tr>
      <td colspan=12 align="center" class="titulo3">&nbsp;<br>&nbsp;&nbsp;<br></td>
      </tr>

      <tr>
		   <td colspan=12 align="center" class="titulo3"><strong>CENTRAL DE ATENCIÓN/ MECANISMOS PARA COMUNICARSE CON CLINISALUD</strong></td>
	    </tr>
      <tr>
      <td colspan=12 class="tdtituloss" align="center"><label style="font-size: 8pt">0274-2521443 / 0424-7083394</label></td>
      </tr>


      <tr>
		   <td colspan=12 align="center" class="titulo3"><strong>INTERMEDIARIOS DE LA ACTIVIDAD ASEGURADORA</strong></td>
	    </tr>
      <tr>
                  <td colspan=4 class="tdtituloss" align="center"><label style="font-size: 8pt"><strong>Intermediario.</strong></label></td>
                  <td colspan=4 class="tdtituloss" align="center"><label style="font-size: 8pt"><strong>C&oacute;digo.</strong></label></td>
                  <td colspan=4 class="tdtituloss" align="center"><label style="font-size: 8pt"><strong>Referencia.</strong></label></td>
      </tr>
      <tr>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?echo $nombrcomisionado?></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?echo $elcodigcomisionado?></label></td>
                  <td colspan=4 class="tdtituloss"><label style="font-size: 8pt"><?$refer=number_format($montobol*(10/100), 2, '.', '');
                  echo "00000$refer"?></strong></label></td>
      </tr>

      <tr>
		   <td colspan=12 align="center" class="titulo3"><strong>DECLARACIÓN DEL CONTRATANTE</strong></td>
	    </tr>
          <tr>
                  <td colspan=12 class="tdtituloss"><label style="font-size: 7pt">Yo <?echo $nombrecompleto;?>, titular de la cédula de indentidad Nº <?echo $datatitula[cedula]?>, 
                  en mi carácter de contratante, certifico que el dinero utilizado para el pago de la cuota proviene de una fuente lícita y que su origen no guarda relación alguna con capitales, bienes, haberes, valores, títulos u operaciones producto de actividades ilícitas, ni proviene de los delitos de Delincuencia Organizada u otras conductas tipificadas en la legislación venezolana.
                    </label></td>
        </tr>
        <tr>
                  <td colspan=6 class="tdtituloss"><label style="font-size: 7pt">
                   El Contratante<br>
                   Nombre y Apellido/Denominación Social(Colocar datos del representante legal si el Contratante es persona Jurídica)<br><br>
                   C.I/R.I.F.:<br><br>
                   Firma:<br>
                  </label></td>
                  <td colspan=6 class="tdtituloss"><label style="font-size: 7pt">
                  Por la empresa de Medicina Prepagada<br>
                  Representante<br>
                  Nombre y Apellido:<br><br>
                  Cargo:<br><br>
                  Firma:<br><br>
                  </label></td>
        </tr>
        <tr>
                  <td colspan=12 class="tdtituloss"><label style="font-size: 7pt">
                En ______________________________ a los _________________ del mes de _________________ del año ___________________</label></td>
        </tr>
        <tr>
                  <td colspan=12 class="tdtituloss"><label style="font-size: 7pt">
                   Este Cuadro Recibo de Servicios será entregado al Contratante conjuntamente con las Condiciones Generales, las Condiciones Particulares
                  y Anexos, si los hubiere, copia de la solicitud de Servicios y demás documentos que formen parte del contrato. En la renovación la obligación procederá
                para los nuevos documentos o para aquellos que hayan sido modificados</label></td>
        </tr>
        <tr>
                  <td colspan=12 class="tdtituloss"><label style="font-size: 7pt">Si el Contratante, Afiliado o Beneficiario siente vulnerados sus derechos y requiere presentar
                  cualquier denuncia, queja, reclamo o solicitud de asesoría, surgida con ocasión de este contrato de seguro, puede acudir a la Oficina de la Defensoria del Asegurado
                  de la Superintendencia de la Actividad Aseguradora o comunicarlo a través de la página web: http://wwww.sudeaseg.gob.ve
                    </label></td>
        </tr>
        <tr>
                  <td colspan=12 class="tdtituloss" align="center"><label style="font-size: 6pt"><strong>CLINISALUD MEDICINA PREPGADA S.A.,  RIF J-311808639</strong></br>
                 Autorizada por la Superintendencia de la Actividad Aseguradora con el Nº 9, según providencia Nº 9 
                    SAA-07-01873-2023 de fecha 23 de Agosto del 2023<br>
                                   DOMICILIO FISCAL: Calle 25 entre Avenidas 7 y 8, Edif. El Cisne 3er Piso, Mérida. Edo. Mérida. Telf.: (0274) 2510092<br>
                                  SEDE QUIROFANO: Zona Industrial Los Curos, Sector Campo Claro, Hospital San Juan de Dios, Telf.: (0274) 2715226<br>
                                    SEDE EL VIGIA: Av. Bolívar, Esquina con Av. 12, Calle 6 Edificio Liegos. El Vigía. Edo Mérida. Telf.: (0275) 8814608
                    </label></td>
        </tr>



 </table>


</table>
