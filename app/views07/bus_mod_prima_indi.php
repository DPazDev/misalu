<?php
include ("../../lib/jfunciones.php");
sesion();
$id_admin= $_SESSION['id_usuario_'.empresa];
$cedula = $_POST['cedula'];
$numero_contrato = $_POST['numero_contrato'];
$id_prima = $_POST['id_prima'];
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

if ($id_prima>0)
{
	$monto_prima = $_POST['monto_prima'];
	$id_caract_recibo = $_POST['id_caract_recibo'];
	
	$mod_prima="update 
								tbl_caract_recibo_prima
							set  
								monto_prima='$monto_prima'                            
							where 
								tbl_caract_recibo_prima.id_caract_recibo='$id_caract_recibo'	and
								tbl_caract_recibo_prima.id_prima='$id_prima'
";
	$fmod_prima=ejecutar($mod_prima);	
	
	$log="ACTUALIZO LA PRIMA ID_PRIMA $id_prima MONTO_PRIMA $monto_prima ID_CARACT_RECIBO $id_caract_recibo ";
logs($log,$ip,$id_admin);
}


    /* **** fin registrar el pago del recibo**** */
if (!empty($cedula))
{
	$q_busc = "select 
                                    tbl_contratos_entes.*,
                                    entes.*,
                                    tbl_recibo_contrato.*,
                                    polizas.porcentaje,
                                    polizas.cuota
                            from 
                                    tbl_contratos_entes,
                                    entes ,
                                    tbl_recibo_contrato,
                                    polizas,
                                    polizas_entes
                            where 
                                    entes.rif='$cedula' and 
                                    tbl_contratos_entes.id_ente=entes.id_ente and
                                    tbl_contratos_entes.estado_contrato=1 and
                                    tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and
                                    entes.id_ente=polizas_entes.id_ente and
                                    polizas_entes.id_poliza=polizas.id_poliza and
                                    polizas.maternidad=0";
	$r_busc = ejecutar($q_busc);
	?>
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Contratos</td>	</tr>	
	
	<?php
	while($f_busc=asignar_a($r_busc,NULL,PGSQL_ASSOC))
                {
					
					/* **** busco los contratos para verificar si tienen alguna deuda **** */
					$q_buscon = "select 
                                    tbl_contratos_entes.*,
                                    entes.*,
                                    tbl_recibo_contrato.*,
                                    polizas.porcentaje,
                                    polizas.cuota
                            from 
                                    tbl_contratos_entes,
                                    entes ,
                                    tbl_recibo_contrato,
                                    polizas,
                                    polizas_entes
                            where 
                                    tbl_recibo_contrato.num_recibo_prima='$f_busc[id_recibo_contrato]' and 
                                    tbl_contratos_entes.id_ente=entes.id_ente and
                                    tbl_contratos_entes.estado_contrato=1 and
                                    tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and
                                    entes.id_ente=polizas_entes.id_ente and
                                    polizas_entes.id_poliza=polizas.id_poliza and
                                    polizas.maternidad=0";
									
									$r_buscon = ejecutar($q_buscon);
									$f_buscon = asignar_a($r_buscon);
    
    /* **** sumo el monto total de la prima**** */
	
	$q_buscon_caract= "select 
                                        sum(monto_prima) 
                                from 
                                        tbl_caract_recibo_prima 
                                where 
                                       tbl_caract_recibo_prima.id_recibo_contrato=  $f_busc[id_recibo_contrato]";
    $r_buscon_caract = ejecutar($q_buscon_caract);
    $f_buscon_caract = asignar_a($r_buscon_caract);
	
	    /* **** sumo el monto total de los pagos de los recibo de prima**** */
    
    $q_buscon_pagos= "select 
                                        sum(monto) 
                                from 
                                        tbl_recibo_pago 
                                where 
                                       tbl_recibo_pago.id_recibo_contrato=  $f_busc[id_recibo_contrato]";
    $r_buscon_pagos = ejecutar($q_buscon_pagos);
    $f_buscon_pagos = asignar_a($r_buscon_pagos);
				?>
				<tr>
		<td class="tdtitulos">Numero de Contrato</td>
		<td class="tdcampos"><a href="#" OnClick="bus_mod_prima_indic(<?php echo 	"'$f_busc[num_recibo_prima]'"?>);" class="boton" title="Ir al Contrato <?php echo 	"$f_busc[numero_contrato] Cuadro Recibo de Prima $f_busc[num_recibo_prima]" ;?>"><?php echo 	"$f_busc[numero_contrato] Cuadro Recibo de Prima $f_busc[num_recibo_prima]";?></a>
		</td>

<td colspan=1 class="tdtitulos">Deuda
</td>
<td colspan=1 class="tdcamposr"><?php echo number_format($f_buscon_caract[sum] - $f_buscon_pagos[sum]  ,2,',','');?>
</td>
		</tr>
<?php
			}
?>
</table>
<?php
									
}
 
        if (!empty($numero_contrato))
        {
            $q_bus = "select 
                                    tbl_contratos_entes.*,
                                    entes.*,
                                    tbl_recibo_contrato.*,
                                    polizas.porcentaje,
                                    polizas.cuota,
									polizas.id_poliza
                            from 
                                    tbl_contratos_entes,
                                    entes ,
                                    tbl_recibo_contrato,
                                    polizas,
                                    polizas_entes
                            where 
                                   tbl_recibo_contrato.num_recibo_prima='$numero_contrato' and 
                                    tbl_contratos_entes.id_ente=entes.id_ente and
                                    tbl_contratos_entes.estado_contrato=1 and
                                    tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and
                                    entes.id_ente=polizas_entes.id_ente and
                                    polizas_entes.id_poliza=polizas.id_poliza and
                                    polizas.maternidad=0";
        }
            else
            {
                    
            }

    $r_bus = ejecutar($q_bus);
    $f_bus = asignar_a($r_bus);
    
     $q_bus_caract= "select 
										tbl_caract_recibo_prima.id_caract_recibo,
                                        tbl_caract_recibo_prima.id_prima,
										tbl_caract_recibo_prima.monto_prima,
										tbl_caract_recibo_prima.id_beneficiario,
										polizas.nombre_poliza,
										polizas.id_poliza
                                from 
                                        tbl_caract_recibo_prima,
										primas,
										polizas
                                where 
										tbl_caract_recibo_prima.id_recibo_contrato=  $f_bus[id_recibo_contrato] and 
										tbl_caract_recibo_prima.id_prima=primas.id_prima and 
										primas.id_poliza=polizas.id_poliza";
    $r_bus_caract = ejecutar($q_bus_caract);
    
 
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Datos del Contratante</td>	</tr>	
	<tr>
		<td class="tdtitulos">Usuario:</td>
		<td class="tdcampos"><?php echo $f_bus[nombre]?></td>
		<td colspan=1 class="tdtitulos">Numero de Contrato         
        </td>
<td colspan=1 class="tdcampos">
<?php echo $f_bus[numero_contrato]?>
<input class="campos" type="hidden" id="numero_contrato1" name="numero_contrato1" maxlength=128 size=20 value="<?php echo $f_bus[num_recibo_prima]?>">
	</td>
		</tr>
        <tr>
		<td class="tdtitulos">Cedula / Rif</td>
		<td class="tdcampos"><?php echo $f_bus[rif]?>
        <input class="campos" type="hidden" id="cedula1" name="cedula1" maxlength=128 size=20 value="<?php echo $f_bus[rif]?>">
        </td>
		<td colspan=1 class="tdtitulos">Numero Recibo
        <input class="campos" type="hidden" id="id_recibo_contrato" name="id_recibo_contrato" maxlength=128 size=20 value="<?php echo $f_bus[id_recibo_contrato]?>">
        </td>
<td colspan=1 class="tdcampos">
<?php echo $f_bus[num_recibo_prima]?>
	</td>
		</tr>
          <tr>
		<td class="tdtitulos">Direccion</td>
		<td class="tdcampos"><?php echo $f_bus[direccion]?></td>
		<td colspan=1 class="tdtitulos">Fecha Emision</td>
<td colspan=1 class="tdcampos">
<?php echo "$f_bus[fecha_ini_vigencia] $f_bus[hora_emision] "?>
<input class="campos" type="hidden" id="fecha_emision" name="fecha_emision" maxlength=128 size=20 value="<?php echo $f_bus[fecha_ini_vigencia]?>">
	</td>
		</tr>
               
           <tr>
		<td class="tdtitulos"></td>
		<td class="tdcampos"></td>
		<td colspan=1 class="tdtitulos"><hr></hr></td>
<td colspan=1 class="tdcampos">
<hr></hr>
	</td>
		</tr>
		<?php 
		$i=0;
			while($f_bus_caract=asignar_a($r_bus_caract,NULL,PGSQL_ASSOC))
                {
				$i++;	
					     $q_bene= "select 
													clientes.nombres,
													clientes.apellidos
											from 
													clientes,
													beneficiarios
											where 
													clientes.id_cliente=beneficiarios.id_cliente and
													beneficiarios.id_beneficiario=$f_bus_caract[id_beneficiario]";
						$r_bene = ejecutar($q_bene);
						$f_bene = asignar_a($r_bene);
		?>
        <tr>
		<td class="tdtitulos"></td>
		<td class="tdcampos"><?php if ($f_bus_caract[id_beneficiario]==0){
			echo $f_bus[nombre];
			}
			else
			{		
			echo "$f_bene[nombres] $f_bene[apellidos] ";
			}
		?></td>
		<td colspan=1 class="tdtitulos"><?php echo "$f_bus_caract[nombre_poliza]";?></td>
<td colspan=1 class="tdcampos">
<input class="campos" type="text" id="tprima_<?php echo $i?>" name="tprima_<?php echo $i?>" maxlength=10 size=5 value="<?php 
echo $f_bus_caract[monto_prima];?>" onchange="return ValNumero(this);">   
<?php
if ($f_bus[id_poliza]==$f_bus_caract[id_poliza])
{
?>
<a href="#" OnClick="bus_mod_prima_indi2(<?php echo "'$f_bus[num_recibo_prima]','$f_bus_caract[id_caract_recibo]','$f_bus_caract[id_prima]','$i'"?>);"  class="boton" title=" Modificar Prima">MOD</a>
<?php
}
else
{
	?>
	<a href="#"   class="boton" title=" Informar al Dpto de Sistema para Verificar esta Prima Error de id_poliza">ERROR</a>
	<?php
}
	?>

	</td>
		
  </tr>
<?php
}
?>

</table>


