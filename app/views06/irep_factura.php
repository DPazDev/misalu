<?php
include ("../../lib/jfunciones.php");
sesion();

$fechaini=$_REQUEST[dateField1];
$fechafin=$_REQUEST[dateField2];
$forma_pago=$_REQUEST[forma_pago];
$sucursal=$_REQUEST[sucursal];
$servicio=$_REQUEST[servicios];
$num_cheque=$_REQUEST[num_cheque];
$valijacre=$_REQUEST[crvalija];
$carterv = strtoupper($_REQUEST[caractclav]);

$direccvalija = $_REQUEST[carposi];


if(!empty($carterv)){

   if($direccvalija == '1'){

	   $andclave = "and tbl_procesos_claves.no_clave like('$carterv%')"; 

   }else{

        if($direccvalija == '2'){

            $andclave = "and tbl_procesos_claves.no_clave like('%$carterv')"; 

        }else{

            $andclave = "and tbl_procesos_claves.no_clave like('%$carterv%')"; 

        }  

    }

}

$elano = date("Y");
$hora=date("H:i:s");
$fecha=date("Y-m-d");
if ($num_cheque>0){
    $andnumcheque="and tbl_facturas.numero_cheque='$num_cheque' ";
    }
    else
    {
         $andnumcheque="";
        }

list($ente,$nom_ente)=explode("@",$_REQUEST['ente']);
list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);
if  ($servicio==0){
	$servicios="and gastos_t_b.id_servicio<>12";
    $serviciosp="and g.id_servicio<>12";
	$servicios1="servicios.id_servicio<>12";
	}
	else
	{
		$servicios="and gastos_t_b.id_servicio=$servicio";
        $serviciosp="and g.id_servicio=$servicio";
		$servicios1="servicios.id_servicio=$servicio";
		}
        
if ($forma_pago==0){
	$tipo_pago="and tbl_facturas.id_estado_factura>=0";
	}
if ($forma_pago=='*'){
	$tipo_pago="and tbl_facturas.id_estado_factura>=1 and tbl_facturas.id_estado_factura<=2";
	}
	if ($forma_pago>0){
	$tipo_pago="and tbl_facturas.id_estado_factura=$forma_pago";
	}
if ($ente==0)
{
	$elente="and entes.id_ente>=$ente";
    $elentep="e.id_ente>=$ente and";
	}
	else
	{
		$elente="and entes.id_ente=$ente";
        $elentep="e.id_ente=$ente and";
          
		}
          if ($ente=='*')
{
    $elente="and entes.id_ente!=53";

    }
    
    
    
if ($forma_pago==1)
{
	$descripcion="Pagadas";
	}
	
if ($forma_pago==2)
{
	$descripcion="Por Cobrar";
	}
	
	
if ($forma_pago==3)
{
	$descripcion="Anuladas";
	}
		if ($forma_pago=='*')
{
	$descripcion="Por Cobrar y Pagadas";
	}
    
    		

/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);




if ($sucursal==0)
{
	$id_serie="and tbl_facturas.id_serie>0";
    $id_seriep="and ts.id_serie>0";
	$serie="Todas Las Series";
}
else
{
	$id_serie="and tbl_facturas.id_serie=$sucursal";
    $id_seriep="and ts.id_serie=$sucursal";
	$q_serie=("select  * from tbl_series where id_serie=$sucursal");
	$r_serie=ejecutar($q_serie);
	$f_serie=asignar_a($r_serie);
	
}


//Guardamos en la tabla tbl_valija la creacion
//primero tenemos que crear el codigo de la valija

if($valijacre>0){

	$buscovalija = ("select tbl_valija.numvalija from tbl_valija where id_serie=$sucursal and id_ente=$ente
	order by id_valija desc limit 1 ");		
	$repbuscovalija = ejecutar($buscovalija);
	$databuscvalija = assoc_a($repbuscovalija);
	$elnumerovalija = $databuscvalija[numvalija];

	if ( $elnumerovalija <= 0 ){
		$valijanumero = 1;
	}else{
		  $valijanumero = $elnumerovalija + 1;
    }

	$serialvalija = "$valijanumero-$elano-$id_admin";

	//guardamos la cabezera de tbl_valija
	$cabezvalija = ("insert into tbl_valija(id_admin,id_serie,serialvalija,numvalija,horacreada,id_ente) values($id_admin,$sucursal,'$serialvalija',$valijanumero,'$hora',$ente)");	
	$repcabezvalija = ejecutar($cabezvalija);
        
	$buscolavalija = ("select tbl_valija.id_valija from tbl_valija where serialvalija='$serialvalija' and id_ente=$ente ");
	$rebuscolavalija = ejecutar($buscolavalija);
	$datavalicreada = assoc_a($rebuscolavalija);
	$elidvalijaes = $datavalicreada[id_valija];
   //guardamos la valija en el historial
    $historialvalija = ("insert into tbl_valija_historial(id_valija,id_admin_crea,id_admin_edit,comentario,estado_factura) 
                            values($elidvalijaes,$id_admin,0,'SE HA CREADO EXITOSAMENTE LA VALIJA',1);");	
    $rephistorialvalija = ejecutar($historialvalija);                     
}

?>

<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=2 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=10 class="titulo">

</td>
</tr>
<tr>
<td colspan=2 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=8 class="titulo">

</td>
<td colspan=4 class="titulo1">
<?php echo "$f_admin[sucursal] $fechaimpreso"?>
</td>
</tr>
<tr>
<td >
<br>
</br>
</td>
</tr>

<tr>
   
    <?php 
    $r_ente=pg_query("select  * from entes where id_ente=$ente");
    ($f_ente=pg_fetch_array($r_ente, NULL, PGSQL_ASSOC))
    ?>
       
</tr>
   
     
<tr>
    <td height="21" colspan="14" class="titulo_seccion"><div align="center"><strong>Relacion de facturas de <?php echo $nom_ente?>, de tipo  <?php echo $nom_tipo_ente?> Atendidos por consultas, Laboratorios, Radiologia, Estudios especiales y Servicio de emergencias.</strong></div></td>
</tr>

<tr>
    <td height="21" colspan="14" class="Estilo3"><div align="right"><strong>Relacion de
        <?php  echo $fechaini ?>
        al <?php echo $fechafin ?></strong></div></td>
</tr>

<tr>
<tr>
    <td height="21" colspan="14" class="Estilo3">Relacion de
        <?php  echo "Serie $serie $f_serie[nomenclatura] Sucursal $f_serie[nombre] Condicion de Pago $descripcion" ?></td>
</tr>

<?php if(!empty($serialvalija)){?>
    <tr>
        <td colspan="14" class="Estilo3"><strong>Valija No. ( <?php echo $serialvalija?> ) </strong></td>
    </tr>
<?}?>
<tr>
    <td >
        <br>
        </br>
    </td>
</tr>
<tr>
    <td >&nbsp;</td>
    <td  class="titulo3" >Fecha</td>
	<td  class="titulo3" ><?php  if ($forma_pago==4){
        echo "Procesos";
        } else {
            echo "Factura";
        }
    ?></td>

    <td  class="titulo3" >Clave</td>
    <td  class="titulo3" >ente</td>
	<td   class="titulo3" >Subdivision</td>
    <td class="titulo3" >Titular</td>
    <td  class="titulo3" >Cedula</td>
    <td  class="titulo3" >Beneficiario</td>
    <td  class="titulo3" >Cedula</td>
	<td  class="titulo3" >Monto (Bs.S.)</strong></td>
	<td  class="titulo3" >Deducible (Bs.S.)</strong></td>
	<td  class="titulo3" >Total (Bs.S.)</strong></td>
</tr>

<?php
if ($forma_pago==4){
    $r_factura=pg_query("select 
            p.id_proceso,
            g.fecha_cita,
            count(g.id_proceso)
        from 
            procesos p,
            titulares t,
            entes e,
            gastos_t_b g,
            admin a,
            tbl_series ts
        where 
            p.id_proceso=g.id_proceso 
            $serviciosp and
            g.fecha_cita>='$fechaini' and 
            g.fecha_cita<='$fechafin' and 
            p.id_titular=t.id_titular and 
            t.id_ente=e.id_ente and 
            $elentep
            e.id_tipo_ente=$tipo_ente  and 
            p.id_admin=a.id_admin and
            a.id_sucursal=ts.id_sucursal
            $andclave
            $id_seriep and
            not exists
                (select
                    * 
                from 
                    tbl_procesos_claves t
                where  
                    p.id_proceso=t.id_proceso)  
                group by  
                    p.id_proceso,
                    g.fecha_cita");
} else {
    $r_factura=pg_query("select
            tbl_facturas.fecha_emision,
            tbl_facturas.numero_factura,
            tbl_facturas.id_factura,
            tbl_facturas.id_estado_factura,
            tbl_facturas.descuento,
            tbl_facturas.tipo_ente,
            count(tbl_procesos_claves.id_factura) 
        from 
            tbl_facturas,
            tbl_procesos_claves,
            titulares,
            procesos,
            entes,
            gastos_t_b
        where 
            tbl_facturas.id_factura=tbl_procesos_claves.id_factura  and 
            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
            procesos.id_titular=titulares.id_titular and titulares.id_ente=entes.id_ente 
            $elente and
            entes.id_tipo_ente=$tipo_ente and 
            tbl_facturas.fecha_emision>='$fechaini' and 
            tbl_facturas.fecha_emision<='$fechafin'  
            $id_serie 
            $andclave
            $tipo_pago
            $andnumcheque and
            procesos.id_proceso=gastos_t_b.id_proceso 
            $servicios
        group by 
            tbl_facturas.fecha_emision,
            tbl_facturas.numero_factura,
            tbl_facturas.id_factura,
            tbl_facturas.tipo_ente,
            tbl_facturas.id_estado_factura 
        order by 
            tbl_facturas.numero_factura");
}
?>


<?php    
$contador=0;
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{
	
	$contador++;
    $guardfactvalija = ("insert into tbl_valija_factura(id_valija,numero_factura,estado_factura) values($elidvalijaes,'$f_factura[numero_factura]',1)");
	$repguardfactvalija = ejecutar($guardfactvalija);
    ?>
   
     
    <?php 

    if ($forma_pago==4){
        $r_titulares=pg_query(" select 
                titulares.id_titular,
                titulares.id_ente,
                clientes.*,
                subdivisiones.subdivision 
            from 
                clientes,
                titulares,
                subdivisiones,
                procesos,
                titulares_subdivisiones 
            where 
                clientes.id_cliente=titulares.id_cliente and 
                titulares.id_titular=procesos.id_titular and 
                procesos.id_proceso=$f_factura[id_proceso] and 
                titulares.id_titular=titulares_subdivisiones.id_titular and 
                titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision");

        ($f_titulares=pg_fetch_array($r_titulares, NULL, PGSQL_ASSOC));

        $ente= $f_titulares[id_ente];
        $titular= $f_titulares[apellidos];

        $r_beneficiarios=pg_query(" select 
                * 
            from 
                clientes,
                beneficiarios,
                procesos
            where 
                clientes.id_cliente=beneficiarios.id_cliente and 
                beneficiarios.id_beneficiario=procesos.id_beneficiario and 
                procesos.id_proceso=$f_factura[id_proceso]");

        ($f_beneficiarios=pg_fetch_array($r_beneficiarios, NULL, PGSQL_ASSOC));
        $beneficiario= $f_beneficiarios[apellidos];


        $r_gastos=pg_query("select 
                gastos_t_b.nombre,
                gastos_t_b.enfermedad,
                gastos_t_b.monto_reserva,
                gastos_t_b.monto_aceptado,
                gastos_t_b.descripcion 
            from 
                gastos_t_b,
                procesos
            where 
                gastos_t_b.id_proceso=procesos.id_proceso and 
                procesos.id_proceso=$f_factura[id_proceso]");
     
    } else {

        $r_titulares=pg_query(" select 
                titulares.id_titular,
                titulares.id_ente,
                clientes.*,
                subdivisiones.subdivision 
            from 
                clientes,
                titulares,
                subdivisiones,
                procesos,
                tbl_procesos_claves,
                titulares_subdivisiones 
            where 
                clientes.id_cliente=titulares.id_cliente and 
                titulares.id_titular=procesos.id_titular and 
                procesos.id_proceso=tbl_procesos_claves.id_proceso and 
                tbl_procesos_claves.id_factura=$f_factura[id_factura] and 
                titulares.id_titular=titulares_subdivisiones.id_titular and 
                titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision");

        ($f_titulares=pg_fetch_array($r_titulares, NULL, PGSQL_ASSOC));

        $ente= $f_titulares[id_ente];
        $titular= $f_titulares[apellidos];

        $r_beneficiarios=pg_query(" select 
                * 
            from 
                clientes,
                beneficiarios,
                procesos,
                tbl_procesos_claves
            where 
                clientes.id_cliente=beneficiarios.id_cliente and 
                beneficiarios.id_beneficiario=procesos.id_beneficiario and 
                procesos.id_proceso=tbl_procesos_claves.id_proceso and
                tbl_procesos_claves.id_factura=$f_factura[id_factura]");

        ($f_beneficiarios=pg_fetch_array($r_beneficiarios, NULL, PGSQL_ASSOC));

        $beneficiario= $f_beneficiarios[apellidos];


        $r_gastos=pg_query("select 
                tbl_procesos_claves.no_clave,
                gastos_t_b.nombre,
                gastos_t_b.enfermedad,
                gastos_t_b.monto_reserva,
                gastos_t_b.monto_aceptado,
                gastos_t_b.descripcion 
            from 
                gastos_t_b,
                tbl_procesos_claves 
            where 
                gastos_t_b.id_proceso=tbl_procesos_claves.id_proceso and 
                tbl_procesos_claves.id_factura=$f_factura[id_factura]");
            
        $busmtnoapro = ("select sum(cast(gastos_t_b.monto_reserva as double precision))
            from 
                gastos_t_b,
                tbl_procesos_claves 
            where 
                gastos_t_b.id_proceso=tbl_procesos_claves.id_proceso and
                tbl_procesos_claves.id_factura=$f_factura[id_factura] and
                gastos_t_b.id_tipo_servicio = 27 ");

        $repbusmtnoapro = ejecutar($busmtnoapro);  
        $datmtnoapro = assoc_a($repbusmtnoapro);
        $elmontnoapro = $datmtnoapro['sum'];


        if($elmontnoapro <=0){
            $elmontnoapro = 0;

        }else{
            $totalmontnoapo = $totalmontnoapo + $elmontnoapro;
        } 
    }
        
    $r_ente=pg_query("select  * from entes where id_ente=$ente");
    ($f_ente=pg_fetch_array($r_ente, NULL, PGSQL_ASSOC))

    ?>

    <?php
    $totalmontres=0;
    $totalmontpag=0;
    while($f_gastos=pg_fetch_array($r_gastos, NULL, PGSQL_ASSOC)){
		
        ?>
        <?php
            
        if ($forma_pago==4){

            $totalmontpag =	$totalmontpag + ($f_gastos['monto_aceptado']);
            $totalmontpag1 =	$totalmontpag1 + ($f_gastos['monto_aceptado']);

        } else {
            //Monto reserva
            if ($f_factura[id_estado_factura]==3){
                $totalmontres=0;

            } else{
                $totalmontres = $totalmontres + ($f_gastos['monto_reserva']);
                
                $totalmontres1 = $totalmontres1 + ($f_gastos['monto_reserva']);
            }

            //Monto Aceptado
            if ($f_factura[id_estado_factura]==3){
                $totalmontpag=0;
            } else {
                $totalmontpag =	$totalmontpag + ($f_gastos['monto_aceptado']);
                $totalmontpag1 =	$totalmontpag1 + ($f_gastos['monto_aceptado']);
            }
        }
        $no_clave= $f_gastos['no_clave'];
            ?>
        
        <?php 
    }

    if ($f_factura[descuento] > 0) {
        $cantidadDescuento = $totalmontpag * ($f_factura[descuento] / 100);
        $totalmontpag = $totalmontpag - $cantidadDescuento;
        $totalmontpag1 = $totalmontpag1 - $cantidadDescuento;
    }

    pg_free_result($r_gastos);

    $q_deducible=("select 
            tbl_procesos_claves.fac_deducible
        from 
            tbl_procesos_claves 
        where 
            tbl_procesos_claves.id_factura=$f_factura[id_factura] and tbl_procesos_claves.fac_deducible>0");
            
        $r_deducible=ejecutar($q_deducible);
        $f_deducible=asignar_a($r_deducible);
        $totaldeducible=	$totaldeducible + ($f_deducible['fac_deducible']);
			
    if ($forma_pago==4){
        ?>
        <tr>

            <td  class="tdcamposcc" ><?php echo $contador ?></td>  
            <td   class="tdcamposcc" ><?php echo $f_factura[fecha_cita]; ?></td>
            <td   class="tdcamposcc" ><?php echo "$f_factura[id_proceso]"?></td>
            <td   class="tdcamposcc"> <?php echo $no_clave?></td>
            <?php

     } else {
        ?>
        <tr>
        <td  class="datos_cliente" ><?php echo $contador?></td>  
        <td   class="datos_cliente" ><?php echo $f_factura[fecha_emision]; ?></td>
        <td   class="datos_cliente" ><?php echo "00$f_factura[numero_factura]"?></td>
        <td   class="datos_cliente"> <?php echo $no_clave?></td>
        <?php 
    }

    if ($f_factura[tipo_ente]==3 || $f_factura[tipo_ente]==5)
    {
        ?>
        <td  class="tdcamposcc"> </td>
        <td   class="tdcamposcc"> GOBERNACION DEL ESTADO MERIDA - PLAN DE SALUD </td>
        <td   class="tdcamposcc">G-20000156-9</td>
        <td     class="tdcamposcc">  </td>
        <td     class="tdcamposcc"> <?php echo $f_beneficiarios[cedula]?> </td>
        <td    class="tdcamposcc"> <?php echo montos_print($totalmontpag )?></td>
        
        <td    class="tdcamposcc"> <?php echo montos_print($f_deducible[fac_deducible])?></td>
        <td    class="tdcamposcc">
            <?php if($totalmontpag == $elmontnoapro){
                echo montos_print($elmontnoapro);

            } else {
                echo montos_print((($totalmontpag - $f_deducible[fac_deducible]) - $elmontnoapro));
            }?>
        </td>	
            <?

    } else {
        ?>
        <td     class="tdcamposcc"> <?php echo $f_ente[nombre]?> </td>
        <td  class="tdcamposcc"> <?php  echo $f_titulares[subdivision]?></td>
        <td   class="tdcamposcc"> <?php echo "$f_titulares[apellidos]  $f_titulares[nombres]"?> </td>
        <td   class="tdcamposcc"> <?php echo $f_titulares[cedula]?> </td>
        <td     class="tdcamposcc"> <?php echo $f_beneficiarios[apellidos]?> <?php echo $f_beneficiarios[nombres]?> </td>
        <td     class="tdcamposcc"> <?php echo $f_beneficiarios[cedula]?> </td>
        <td    class="tdcamposcc"> <?php echo montos_print($totalmontpag )?></td>
        
        <td    class="tdcamposcc"> <?php echo montos_print($f_deducible[fac_deducible])?></td>
        <td    class="tdcamposcc">
            <?php  if($totalmontpag < 0){
                echo "($totalmontpag < 0)";
                echo montos_print(0);
                
            } else {
                if($totalmontpag == $elmontnoapro){
                    echo montos_print($totalmontpag);

                } else {
                    if(($totalmontpag == 0) && ($elmontnoapro > 0)){	
                        echo montos_print(0);
                        
                    }else{
                        echo montos_print((($totalmontpag - $f_deducible[fac_deducible]) - $elmontnoapro));
                    }
                }
            }?></td>	

        <?php
    }   ?>
      </tr>
   
   
   
    <?php 

}


pg_free_result($r_factura);

if ($forma_pago<>4){

    if($contador==1){
        $contador="(Una Factura)";
        
    }else{
        $contador="($contador Facturas)";
    }

} else {
    
    if($contador==1){
        $contador="(Un Proceso)";
    }else{
        $contador="($contador Procesos)";
    }
}


?>
<tr>
    <td  class="titulo3"></td>
    <td   class="titulo3"></td>
    <td   class="titulo3"></td>
    <td   class="titulo3"></td>
    <td  class="titulo3"></td>
    <td   class="titulo3"></td>
    <td  class="titulo3"></td>
    <td  class="titulo3"></td>
    <td  class="titulo3"></td>
    <td   class="titulo3">Total</td>
    <td class="titulo3" ><?php echo  montos_print($totalmontpag1)?></td>

    <td class="titulo3" ><?php echo  montos_print($totaldeducible)?></td>
    <td class="titulo3" ><?php 
    $bustipente=("select entes.id_tipo_ente from entes where id_ente=$ente");

    $repbustipente=ejecutar($bustipente);
    $datbustipente=assoc_a($repbustipente);
    $eltipentees=$datbustipente[id_tipo_ente];

    if ($eltipentees==6){
        echo  montos_print(($totalmontpag1 - $totaldeducible));

    } else {
        echo  montos_print(($totalmontpag1 - $totaldeducible)-$totalmontnoapo);
    }
    ?></td>
</tr>
    
	 <tr>
 
      <td height="21" colspan="14" valign="top"><div align="left" class="Estilo3"><strong>&nbsp;</strong><?php echo $contador; ?></strong></td>
    </tr>
<?php
 if ($forma_pago<>4){
     ?>
 <tr>
      <td height="21" colspan="14" valign="top"><div align="right" class="Estilo3">Firma de <?php echo $f_ente[nombre]?>:_________________</div></td>
     <?php
     }
     ?>
    
      </tr>
    <tr>
      <td height="21" colspan="4" valign="top"><div align="center" class="Estilo3"></div></td>
      <td  height="21" colspan="4" valign="top"><div align="center" class="Estilo3"></div></td>
       <td  height="21" colspan="3" valign="top"><div align="center" class="Estilo3"></div></td>
    
      </tr>
   <tr>
      <td height="21" colspan="4" valign="top"><div align="center" class="Estilo5">Elaborado Por:__________ </div></td>
      <td  height="21" colspan="4" valign="top"><div align="center" class="Estilo3">Aprobado Por:__________ </div></td>
       <td  height="21" colspan="3" valign="top"><div align="center" class="Estilo3">Recibido Por:__________ </div></td>
    </tr>
</table>

