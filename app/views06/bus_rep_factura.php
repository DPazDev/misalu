<?php
include ("../../lib/jfunciones.php");
sesion();

$admin= $_SESSION['id_usuario_'.empresa];

$fechaini=$_REQUEST[dateField1];
$fechafin=$_REQUEST[dateField2];
$forma_pago=$_REQUEST[forma_pago];
$sucursal=$_REQUEST[sucursal];
$servicio=$_REQUEST[servicios];
$num_cheque=$_REQUEST[num_cheque];
$lavalijaes=$_REQUEST[datavalija];
$crearvalija=0;
$carterv = strtoupper($_REQUEST[clavcar]);

$direccvalija = $_REQUEST[direcvalija];



if(!empty($carterv)){

   if($direccvalija == '1'){

	    $andclave = "and tbl_procesos_claves.no_clave like('$carterv%')"; 

    }else{

        if($direccvalija == '2'){

            $andclave = "and tbl_procesos_claves.no_clave like('%$carterv')"; 

            } else {

                $andclave = "and tbl_procesos_claves.no_clave like('%$carterv%')"; 

			}  
	   }
}

	/* **** verifico de tiene activado el permiso de Modificar facturas de edo por cobrar a pagadas **** */
$q_mod_edo=("select * from permisos where permisos.id_admin='$admin' and permisos.id_modulo=12");
$r_mod_edo=ejecutar($q_mod_edo);
$f_mod_edo=asignar_a($r_mod_edo);

if ($num_cheque>0){
    $andnumcheque="and tbl_facturas.numero_cheque='$num_cheque' ";
} else {
    $andnumcheque="";
}


list($ente,$nom_ente)=explode("@",$_REQUEST['ente']);
list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);

if  ($servicio==0){
	$servicios="and gastos_t_b.id_servicio<>12";
    $serviciosp="and g.id_servicio<>12";
	$servicios1="servicios.id_servicio<>12";
} else {
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
} else {
    $elente="and entes.id_ente=$ente";
    $elentep="e.id_ente=$ente and";
}
        
if ($ente=='*')
{
    $elente="and entes.id_ente!=53";
}
       

if ($tipo_ente==0)
{
	$eltipo_ente="tbl_tipos_entes.id_tipo_ente>=$tipo_ente"; 
} else {
    $eltipo_ente="tbl_tipos_entes.id_tipo_ente=$tipo_ente";
}


if ($sucursal==0) {
	$id_serie="and tbl_facturas.id_serie>0";
    $id_seriep="and ts.id_serie>0";
} else {
    $id_serie="and tbl_facturas.id_serie=$sucursal";
    $id_seriep="and ts.id_serie=$sucursal";
}

    
if(($sucursal>0) && ($ente>0) && ($lavalijaes>0)){
    $crearvalija=1;
}
?>





<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>


<tr>

    <?php 
    $r_ente=pg_query("select  * from entes where id_ente=$ente");
    ($f_ente=pg_fetch_array($r_ente, NULL, PGSQL_ASSOC))
    ?> 

</tr>
     
<tr>
    <td height="21" colspan="12" class="titulo_seccion"><div align="center"><strong>Relacion de facturas de <?php echo $f_ente[nombre]?>, Atendidos por consultas, Laboratorios, Radiologia, Estudios especiales y Servicio de emergencias.</strong></div></td>
</tr>
    <?
    /* **** Comparo si el tipo de empresa o ente a consultar es medicina prepagada individual **** */
if ($tipo_ente==7 || $tipo_ente==2){
    ?>
    <tr>
        <td  width="12">&nbsp;</td>
        <td  class="tdcamposc" >Fecha</td>
        <td  class="tdcamposc" >Factura</td>
        <td  class="tdcamposc" > Recibo Prima </td> 
        <td  class="tdcamposc" >Numero Contrato</td>
        <td  class="tdcamposc" >Nombre Cliente </td>
    
        <td  class="tdcamposc" >Cedula/Rif</td>
        <td  class="tdcamposc" ></td>
        <td  class="tdcamposc" >Monto (Bs.S)</strong></td>
	</tr>
    <?php
    $r_factura=pg_query("select
        tbl_facturas.fecha_emision,
        tbl_facturas.numero_factura,
        tbl_facturas.id_factura,
        tbl_facturas.id_estado_factura,
        tbl_facturas.tipo_ente,
        tbl_recibo_contrato.num_recibo_prima,
        tbl_contratos_entes.numero_contrato,
        entes.nombre,
        entes.rif,
        tbl_procesos_claves.monto
    from 
        tbl_facturas,
        tbl_procesos_claves,
        entes,
        tbl_recibo_contrato,
        tbl_contratos_entes,
        tbl_tipos_entes
    where 
        tbl_facturas.id_factura=tbl_procesos_claves.id_factura  and 
        tbl_facturas.con_ente=entes.id_ente and
        tbl_procesos_claves.id_proceso=0
        $elente and
        entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
        $eltipo_ente 
        $id_serie 
        $tipo_pago and
        tbl_facturas.fecha_emision>='$fechaini' and 
        tbl_facturas.fecha_emision<='$fechafin' and
        tbl_facturas.id_recibo_contrato=tbl_recibo_contrato.id_recibo_contrato and
        tbl_recibo_contrato.id_contrato_ente=tbl_contratos_entes.id_contrato_ente");

    $contador=0;
    while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
    {
        $contador++;
        $monto_prima=$monto_prima + $f_factura[monto];
        ?>
       
        <tr>
            <td  class="tdcamposcc"  ><?php echo $contador ?></td> 
            <td   class="tdcamposcc" ><?php echo $f_factura[fecha_emision] ?></td>
            <td   class="tdcamposcc" ><?php echo "00$f_factura[numero_factura]" ?></td>
            <td   class="tdcamposcc" ><?php echo "$f_factura[num_recibo_prima]"?></td>
            <td   class="tdcamposcc" ><?php echo $f_factura[numero_contrato]?></td>
            <td   class="tdcamposcc" ><?php echo $f_factura[nombre] ?></td>
            <td  class="tdcamposcc"  ><?php echo $f_factura[rif] ?></td>
            <td  class="tdcamposc"    ></td>
            <td  class="tdcamposcc"  ><?php echo $f_factura[monto]?> </strong></td>
        </tr>
	
	    <tr>
            <td  colspan=9 class="tdcamposcc"  ><hr></hr></td>
	    </tr>
       
       <?php
    }

} else
{  /* fin de Comparar si el tipo de empresa o ente a consultar es medicina prepagada individual */
           
    ?>
    <tr>
        <td  width="12">&nbsp;</td>
        <td  class="tdcamposc" >Fecha</td>
        <td   class="tdcamposc"><a <?php if ($f_mod_edo[permiso]=='1' and $forma_pago=='2'); {?> href="javascript: todos(this);"> <?php
        } ?>
        <?php
        if ($forma_pago==4){
            echo "Procesos";
        }
        else {
            echo "Factura";
        }
        ?>
        </a>
        </td> 
        <td   class="tdcamposc" >Clave</td>
        <td   class="tdcamposc" >Subdivision</td>
        <td class="tdcamposc" >Titular</td>
        <td  class="tdcamposc" >Cedula</td>
        <td  class="tdcamposc" >Beneficiario</td>
        <td  class="tdcamposc" >Monto (Bs.S)</strong></td>
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
        $id_seriep 
        $andclave
        and
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
        
        "select
            tbl_facturas.fecha_emision,
            tbl_facturas.numero_factura,
            tbl_facturas.id_factura,
            tbl_facturas.id_estado_factura,
            tbl_facturas.tipo_ente,
            tbl_facturas.condicion_pago,
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
            tbl_facturas.fecha_emision<='$fechafin'  
            tbl_facturas.fecha_emision>='$fechaini' and 
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
            tbl_facturas.numero_factura";

        $r_factura=pg_query("select
            tbl_facturas.fecha_emision,
            tbl_facturas.numero_factura,
            tbl_facturas.id_factura,
            tbl_facturas.id_estado_factura,
            tbl_facturas.tipo_ente,
            tbl_facturas.descuento,
            tbl_facturas.condicion_pago,
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
    $i=0;
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{

    $contador++;

    ?>
    
    <?php 
    if ($forma_pago==4){
        $r_titulares=pg_query("select 
                titulares.id_titular,
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

        $titular= $f_titulares[apellidos];

        $r_beneficiarios=pg_query("select 
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

    }
    else
    {
        $r_titulares=pg_query("select 
                titulares.id_titular,
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

        $titular= $f_titulares[apellidos];

        $r_beneficiarios=pg_query("select 
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

        } else{
            $totalmontnoa = $totalmontnoa + $elmontnoapro;
        }
    }
        
        ?>
        <?php

    $totalmontres=0;
    $totalmontpag=0;
    $totaldeducible=0;
    $i++;

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
            } else {
                
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

    $q_deducible=("select 
            tbl_procesos_claves.fac_deducible
        from 
            tbl_procesos_claves 
        where 
            tbl_procesos_claves.id_factura=$f_factura[id_factura] and tbl_procesos_claves.fac_deducible>0");

    $r_deducible=ejecutar($q_deducible);
    $f_deducible=asignar_a($r_deducible);

    $totaldeducible = $totaldeducible + ($f_deducible['fac_deducible']);
    ?>
    <?php 

    if ($f_factura[descuento] > 0) {

        $cantidadDescuento = $totalmontpag * ($f_factura[descuento] / 100);

        $totalmontpag = $totalmontpag - $cantidadDescuento;

        $totalmontpag1 = $totalmontpag1 - $cantidadDescuento;
    }

    if ($forma_pago==4) {
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

            <td  class="tdcamposcc" ><?php echo $contador ?></td>

            <td   class="tdcamposcc" ><?php echo $f_factura[fecha_emision]; ?></td>
            <td   class="tdcamposcc" >
        <?php


        if ($forma_pago<>'2'){
            
            echo "00$f_factura[numero_factura]";
        }
        else
        {
            echo "00$f_factura[numero_factura]";

            if ($f_mod_edo[permiso]=='1'){
                ?>
                <input class="campos" type="hidden" id="idfactura_<?php echo $i?>" name="idfactura_" maxlength=128 size=20 value="<?php echo $f_factura[id_factura]?>">

                <input class="campos" type="hidden" id="honorarios_<?php echo $i?>" name="honorarios_" maxlength=128 size=20 value="<?php echo $totalmontpag?>">

                <input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>"name="checkl" maxlength=128 size=20 value="" OnClick="sumar(this);"> 
                <?php
            }
        }

        ?>
        </td>
        <td   class="tdcamposcc"> <?php echo $no_clave?></td>
        <?php 

    }

    if ($f_factura[tipo_ente]==3 || $f_factura[tipo_ente]==5) 
    {
        ?>
        <td  class="tdcamposcc"> </td>
        <td   class="tdcamposcc"> GOBERNACION DEL ESTADO MERIDA - PLAN DE SALUD </td>
        <td   class="tdcamposcc">G-20000156-9</td>
        <td     class="tdcamposcc">  </td>
        <td    class="tdcamposcc"> <?php 
        if($totalmontpag <= 0){
            echo montos_print($elmontnoapro);
            
        }else{

            if($totalmontpag == $elmontnoapro){	 
                echo montos_print($elmontnoapro);
            }else{		
                echo montos_print((($totalmontpag - $f_deducible[fac_deducible]) - $elmontnoapro));
            }
        }
        ?>
        </td>
        <?
    } else {
        ?>
        <td  class="tdcamposcc"> <?php  echo $f_titulares[subdivision]?></td>
        <td   class="tdcamposcc"> <?php echo "$f_titulares[apellidos]  $f_titulares[nombres]"?> </td>
        <td   class="tdcamposcc"> <?php echo $f_titulares[cedula]?> </td>
        <td     class="tdcamposcc"> <?php echo $f_beneficiarios[apellidos]?> <?php echo $f_beneficiarios[nombres]?> </td>

        <td    class="tdcamposcc">
            <?php
            if($totalmontpag < 0){
                echo "($totalmontpag < 0)";
                echo montos_print(0);
                $monto_total_cp=0;
                
            } else {

                if($totalmontpag == $elmontnoapro){
                    echo montos_print($totalmontpag);
                    $monto_total_cp=$totalmontpag;

                }else{

                    if(($totalmontpag == 0) && ($elmontnoapro > 0)){	
                        echo montos_print(0);
                        $monto_total_cp=0;

                    }else{
                        echo montos_print((($totalmontpag - $f_deducible[fac_deducible]) - $elmontnoapro));
                        $monto_total_cp=((($totalmontpag - $f_deducible[fac_deducible]) - $elmontnoapro));
                    }
                }
            }?>
        </td>
        <?php
    }   ?>


        </tr>   
    
        <?php 

        //CÄLCULO DE LOS MONTOS TOTALES SEGUN TIPOS DE PAGOS ING.PATRICIA//
        if ($f_factura[id_estado_factura]==3){  //* ID_ESTADO_PROCESO=3 ES ANULADO *//
            $totalmontpag=0;

        }else{

            if($f_factura[condicion_pago]==6) {

                $b_oper_multi=("select
                        tbl_oper_multi.monto,  
                        tbl_oper_multi.condicion_pago
                    from
                        tbl_oper_multi 
                    where
                        tbl_oper_multi.id_factura=$f_factura[id_factura]");

                $r_oper_multi=ejecutar($b_oper_multi);

                while($t_oper_multi=pg_fetch_array($r_oper_multi, NULL, PGSQL_ASSOC)){
                    $monto_oper_multi=$t_oper_multi[monto];

                    switch ($t_oper_multi[condicion_pago]) {

                        case 1:
                        $total_monto_cp_e= $total_monto_cp_e + $monto_oper_multi;
                        break;               
                                    
                        case 2:
                        $total_monto_cp_cre= $total_monto_cp_cre + $monto_oper_multi;
                        break;             

                        case 3:
                        $total_monto_cp_che= $total_monto_cp_che + $monto_oper_multi;
                        break;

                        case 4:
                        $total_monto_cp_d= $total_monto_cp_d + $monto_oper_multi;
                        break;

                        case 5:
                        $total_monto_cp_tcre= $total_monto_cp_tcre + $monto_oper_multi;
                        break;    

                        case 7:
                        $total_monto_cp_td= $total_monto_cp_td + $monto_oper_multi;
                        break;

                        case 8:
                        $total_monto_cp_mp= $total_monto_cp_mp + $monto_oper_multi;
                        break; 

                        case 10:
                        $total_monto_cp_pm= $total_monto_cp_pm + $monto_oper_multi;
                        break;

                        case 11:
                        $total_monto_cp_z= $total_monto_cp_z + $monto_oper_multi;
                        break;  
                    }
                }

            }else{

                switch ($f_factura[condicion_pago]) {

                    case 1:
                        $total_monto_cp_e= $total_monto_cp_e + $monto_total_cp;
                        break;
                            
                    case 2:
                        $total_monto_cp_cre= $total_monto_cp_cre + $monto_total_cp;
                        break;
                            
                    case 3:
                        $total_monto_cp_che= $total_monto_cp_che + $monto_total_cp;
                        break;

                    case 4:
                    $total_monto_cp_d= $total_monto_cp_d + $monto_total_cp;
                        break;

                    case 5:
                        $total_monto_cp_tcre= $total_monto_cp_tcre + $monto_total_cp;
                        break;    

                    case 7:
                        $total_monto_cp_td= $total_monto_cp_td + $monto_total_cp;
                        break;

                    case 8:
                        $total_monto_cp_mp= $total_monto_cp_mp + $monto_total_cp;
                        break;
                    
                    case 10:
                        $total_monto_cp_pm= $total_monto_cp_pm + $monto_total_cp;
                        break;

                    case 11:
                        $total_monto_cp_z= $total_monto_cp_z + $monto_total_cp;
                        break;    

                }
            }
        }
}

    echo "<input type=\"hidden\" id=\"conexa\" name=\"conexa\" value=\"$i\">";

    pg_free_result($r_factura);
    if($contador==1){
        $contador="(Una Factura)";
    }else{
        $contador="($contador Facturas)";
    }

}
?>
<tr>

    <td colspan=8  class="tdcamposc">Total</td>
    <td class="tdcamposc" align="right"><?php 

    $bustipente=("select entes.id_tipo_ente from entes where id_ente=$ente");

    $repbustipente=ejecutar($bustipente);
    $datbustipente=assoc_a($repbustipente);
    $eltipentees=$datbustipente[id_tipo_ente];

    if ($eltipentees==6){
        echo  montos_print((($totalmontpag1 - $totaldeducible) + $monto_prima));

    } else {
        echo  montos_print((($totalmontpag1 - $totaldeducible) + $monto_prima)-$totalmontnoa);
    }?>
</tr>
</tr>

<tr>

    <td class="tdcamposc" colspan="12" ><p><?php echo $contador; ?></p></td>
</tr>

<table>
    <?php 
    if($total_monto_cp_e!=0){
        ?>
        <td  class="cajamontos" >Monto total Efectivo <?php echo montos_print($total_monto_cp_e)?></td> 
        <?php;
    }?>

    <?php 
    if($total_monto_cp_cre!=0){
        ?> 
        <td  class="cajamontos" >Monto total Credito <?php echo montos_print($total_monto_cp_cre)  ?></td>  
        <?php;
    }?>

    <?php 
    if($total_monto_cp_che!=0){
        ?>
        <td  class="cajamontos" >Monto total Cheque <?php echo montos_print($total_monto_cp_che)  ?></td>  
        <?php;
    }?>
    
    <?php
    if($total_monto_cp_d!=0){
        ?>
        <td  class="cajamontos" >Monto total Debito <?php echo montos_print($total_monto_cp_d)?></td>  
        <?php;
    }?>

    <?php
    if($total_monto_cp_z!=0){
        ?>
        <td  class="cajamontos" >Monto total Zelle <?php echo montos_print($total_monto_cp_z)?></td>  
        <?php;
    }?>
    
    <?php
    if($total_monto_cp_tcre!=0){
        ?>
        <td  class="cajamontos" >Monto total Tarjeta de Credito <?php echo montos_print($total_monto_cp_tcre)?></td> 
        <?php;
    }?>

    <?php
    if($total_monto_cp_pm!=0){
        ?>
        <td  class="cajamontos" >Monto total Pago móvil <?php echo montos_print($total_monto_cp_pm)  ?></td>  
        <?php;
    }?>

    <?php
    if($total_monto_cp_td!=0){
        ?>
        <td  class="cajamontos" >Monto total Transferencia o deposito <?php echo montos_print($total_monto_cp_td)  ?></td>  
        <?php;
    }?>
    
    <?php
    if($total_monto_cp_mp!=0){
        ?>
        <td  class="cajamontos" >Monto total Recibos de Medicina <?php echo montos_print($total_monto_cp_mp)?></td>  
        <?php;
    }?>
</table>



<tr>
    <td colspan="12" class="titulo_seccion"> <?php
$url="'views06/irep_factura.php?dateField1=$fechaini&dateField2=$fechafin&sucursal=$sucursal&forma_pago=$forma_pago&sucursal=$_REQUEST[sucursal]
&servicios=$_REQUEST[servicios]&ente=$_REQUEST[ente]&tipo_ente=$_REQUEST[tipo_ente]&num_cheque=$_REQUEST[num_cheque]&crvalija=$crearvalija
&caractclav=$_REQUEST[clavcar]&carposi=$_REQUEST[direcvalija]'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" title="Relacion de Facturas con Todos los Campos de la Busquedad" class="boton"> Imprimir </a>
            <?php
$url="'views06/irep_factura2.php?dateField1=$fechaini&dateField2=$fechafin&sucursal=$sucursal&forma_pago=$forma_pago&sucursal=$_REQUEST[sucursal]
&servicios=$_REQUEST[servicios]&ente=$_REQUEST[ente]&tipo_ente=$_REQUEST[tipo_ente]&num_cheque=$_REQUEST[num_cheque]'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" title="Relacion de facturas solo con los campos fecha emision, facturas, clientes y montos" class="boton"> Imprimir Relacion 2 </a>
            </td>
</tr>









   	 <?php 
		if ($f_mod_edo[permiso]=='1' and $forma_pago=='2'){
		?>
    <tr>
		<td colspan=12 align="center" class="titulo_seccion">Datos Para Actualizar Facturas a Pagadas</td>
	</tr>
	<tr>
	<td colspan=3 class="tdtitulos">Forma de pago</td>
	<td colspan=2 class="tdcampos">

<?php 
$q_tipo_pago=("select * from tbl_tipos_pagos  order by tbl_tipos_pagos.tipo_pago");
$r_tipo_pago=ejecutar($q_tipo_pago);
?>
	<select id="forma_pago1" name="forma_pago1" class="campos" style="width: 200px;"  >
<?php
 while($f_tipo_pago = asignar_a($r_tipo_pago)){
?>
		<option value="<?php echo $f_tipo_pago[id_tipo_pago]?>" <?php if($f_factura['condicion_pago']==$f_tipo_pago[id_tipo_pago]) echo "selected"; ?>>
<?php echo $f_tipo_pago[tipo_pago]?></option>
<?php
}
?>
	</select>
	</td>
	<td colspan=2 class="tdtitulos">
	Tipo de Tarjeta
	</td>
	<td colspan=2 class="tdcampos">

<?php
$q_nom_tarjeta=("select * from tbl_nombre_tarjetas  order by tbl_nombre_tarjetas.nombre_tar");
$r_nom_tarjeta=ejecutar($q_nom_tarjeta);
$q_oper_mult=("select * from tbl_oper_multi where tbl_oper_multi.id_factura=$f_factura[id_factura]");
$r_oper_mult=ejecutar($q_oper_mult);
$f_oper_mult = asignar_a($r_oper_mult);


?>

        <select id="nom_tarjeta" name="nom_tarjeta" class="campos" style="width: 200px;"  >
 <option value="0"  <?php if($f_factura['id_nom_tarjeta']==0) echo "selected"; ?>>Nada</option>

<?php

 while($f_nom_tarjeta = asignar_a($r_nom_tarjeta)){
?>
                <option value="<?php echo $f_nom_tarjeta[id_nom_tarjeta]?>"
 <?php if($f_factura['id_nom_tarjeta']==$f_nom_tarjeta[id_nom_tarjeta]) echo "selected"; ?>>
<?php echo $f_nom_tarjeta[nombre_tar]?></option>
<?php
}
?>
        </select>
	</td>
	</tr>
	<tr>	
	<td  colspan=3 class="tdtitulos">*  Fecha de Final de Credito   </td>
	<td  colspan=2 class="tdtitulos">
 <input readonly type="text" size="10" id="dateField5" name="fechac" class="campos" maxlength="10" value="<?php echo $f_factura[fecha_credito]?>"> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField5', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
	
		
		
		<?php
		//busco los bancos.
		$q_bancos="select * from tbl_bancos order by tbl_bancos.nombanco";
		$r_bancos=ejecutar($q_bancos);
		echo " 
				<td colspan=2 class=\"tdtitulos\">Banco</td>
			<td colspan=2 class=\"tdcampos\">
			<select id=\"banco\" name=\"banco\" class=\"campos\">
			";
			while($f_banco=asignar_a($r_bancos)){
				?><option value="<?php echo $f_banco[id_ban];?>" <?php if(($f_banco[id_ban]==$f_oper_mult[id_banco]) || $f_banco[id_ban]==$f_factura[id_banco] ) echo "selected"; ?>><?php echo $f_banco[nombanco]?></option>
			<?php
			}
		echo "	</select>
			</td>
			</tr>
			<tr>
			<td colspan=3 class=\"tdtitulos\">Num Cheque, tarjeta, Trasnferencia</td>
			<td colspan=2 class=\"tdcampos\"><input type=\"text\" id=\"no_cheque\" name=\"no_cheque\" class=\"campos\" size=20 value=\"$f_factura[numero_cheque]\"></td>
		";
?>
	<td class="tdtitulos"  colspan=2 align="left">Monto Total</td>
	<td class="titulos" colspan=2 align="left">
		
		<input class="campos" type="text" disabled id="monto" name="monto" maxlength=128 size=20 value="0"  OnChange="return validarNumero(this);"  >
		
		</td>
		
	</tr>
	<tr>
	<td class="tdtitulos"  colspan=3 align="left">Estado de Factura</td>
	<td class="titulos" colspan=2 align="left">
		<select id="estado_fac" name="estado_fac" class="campos" style="width: 70px;"  <?php echo $desactivar_2; ?>>
		<option value="1" <?php if($f_factura['id_estado_factura']==1) echo "selected"; ?>>Pagada</option>
	</select>
	</td>
	<td class="titulos" colspan=2 align="left">
        <a href="#"  OnClick="mod_edo_fact_rel();" title="Modifica el Estado de La Factura" class="boton" title="Modifica el Estado de La Factura"> Actualizar
        </a>
		</td>
		
	</tr>
	<?php 
		}
	?>
  </table>

