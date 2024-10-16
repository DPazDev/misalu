<?php
include ("../../lib/jfunciones.php");
sesion();
$eldonativoes=$_REQUEST['idordendona'];
$buscardatosdona=("select tbl_ordenes_donativos.fecha_donativo,tbl_ordenes_donativos.id_titular,
tbl_ordenes_donativos.id_beneficiario,tbl_ordenes_donativos.comentarios from tbl_ordenes_donativos where tbl_ordenes_donativos.id_orden_donativo=$eldonativoes;");
$repbuscardatosdona=ejecutar($buscardatosdona);
$infodatdona=assoc_a($repbuscardatosdona);
$estitular=$infodatdona[id_titular];
$esbenefi=$infodatdona[id_beneficiario];
$escomenta=$infodatdona[comentarios];
$esfechadona=$infodatdona[fecha_donativo];
if(($estitular>0)&&($esbenefi==0)){
   $datacliente=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,titulares where 
                  clientes.id_cliente=titulares.id_cliente and titulares.id_titular=$estitular");
   $repdatacliente=ejecutar($datacliente);
   $datpriclient=assoc_a($repdatacliente);
   $nomclie=$datpriclient[nombres];
   $apellid=$datpriclient[apellidos];
   $cedclie=$datpriclient[cedula];
   $eltitulaid=$elidtob;
   $elidbenef=0;
}else{
       $datacliente=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento,titulares.id_titular from clientes,titulares,beneficiarios 
                   where 
                  clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$esbenefi and
                  beneficiarios.id_titular=titulares.id_titular");
       $repdatacliente=ejecutar($datacliente);
       $datpriclient=assoc_a($repdatacliente);
       $nomclie=$datpriclient[nombres];
       $apellid=$datpriclient[apellidos];
       $cedclie=$datpriclient[cedula];
       $eltitulaid=$datpriclient[id_titular];
       $fechanaci=$datpriclient[fecha_nacimiento];
       $elidbenef=$elidtob; 
       $edad=calcular_edad($fechanaci); 
        if($edad<18){
           $datacliente=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,titulares where 
                  clientes.id_cliente=titulares.id_cliente and titulares.id_titular=$eltitulaid");
          $repdatacliente=ejecutar($datacliente);
          $datpriclient=assoc_a($repdatacliente);
          $nomclie=$datpriclient[nombres];
          $apellid=$datpriclient[apellidos];
          $cedclie=$datpriclient[cedula];
        }
       
     }
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
   <td colspan=2  class="logo">
      <img src="../../public/images/head.png">
   </td>
   <td  colspan=2 class="titulo"></td>
   <td  colspan=2 class="titulo"></td>
   <td  colspan=1 class="titulo">
       <img src="../../public/images/lainternacional.gif">Rif: J-00338202-7
   </td>
</tr>

</table>

<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo">

</td>
</tr>
<tr>
</tr>
<tr>
</tr>
<tr>
<br>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo1">
<?php 
$areglofechimpr=explode("-",$esfechadona);
$anos=$areglofechimpr[0];
$meses=$areglofechimpr[1];
$dias=$areglofechimpr[2];
$fechaimp="$dias-$meses-$anos";
echo "$f_admin[sucursal] $fechaimp"?>
</td>
</tr>

<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> SOLICTUD DE DONATIVO 

</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>

<tr>
<td colspan=4 class="datos_cliente">
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">

</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">

</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">

</td>
</tr>


<tr>
<td colspan=4 class="datos_cliente1" style="text-align: justify">
Dr. Antonio Guerrero Presidente de CliniSalud c.a. Me es grato dirigirme a usted con la finalidad de saludarle y desarle &eacute;xitos en sus funciones generales y de manifestarle un sincero agradecimiento en virtud a la labor social que usted ha venido realizando y a su vez solicitarle muy respetuosamente su valiosa colaboraci&oacute;n para  <?php echo $escomenta ?> ,tal como lo describe el informe. 


</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4  class="datos_cliente">
Sin m&aacute;s a que hacer referencia se despide atentamente de usted  <?php echo "$nomclie $apellid" ?>, Venezolano portador de la C&eacute;dula de identidad n&uacute;mero <?php echo cedula($cedclie);?>.
<br>
</br>
</br>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=1 class="titulo3">
__________________
</td>
<td colspan=1  class="titulo3">
__________________
</td>
<td colspan=2 class="titulo3">
__________________
</td>
</tr>

<tr>
<td colspan=1 class="titulo3">
ELABORADO POR:
<?php echo "$f_admin[nombres] $f_admin[apellidos]" ?>
</td>
<td colspan=1 class="titulo3">
REVISADO POR:
</td>
<td colspan=2 class="titulo3">
<? echo "$nomclie $apellid C.I. $cedclie";?>
</td>
</tr>	
<tr>
<td>
<br>
</br>
</td>
</tr>
<tr>
<td align="right" colspan=4>
"Gesti&oacute;n con Sentido Social..."
<br>
</br>
</td>
</tr>
<tr>
<td>
<br>
</br>
<br>
</br><br>
</br>
<br>
</br>
</td>
</tr>

		



<tr>
<td colspan=4 class="datos_cliente1">
Domicilio Fiscal: Avenida Las Americas, Centro Comercial Mayeya, Nivel Mezzanina, locales 16,17 y 24, M&eacute;rida Edo. M&eacute;rida.
</td>

</tr>

</table>


