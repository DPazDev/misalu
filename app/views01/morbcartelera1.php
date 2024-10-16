<?php
include ("../../lib/jfunciones.php");
sesion();
$fecham = $_REQUEST['lafecha'];
$idprovee = $_REQUEST['elprovee'];
$nombre    = $_REQUEST['eldoctor'];
$vercitas = ("select procesos.id_proceso,procesos.id_titular,procesos.id_beneficiario,procesos.fecha_creado,gastos_t_b.fecha_cita,gastos_t_b.id_proveedor 
from
procesos,gastos_t_b,proveedores,s_p_proveedores
where
procesos.id_proceso = gastos_t_b.id_proceso and
procesos.id_estado_proceso =  2 and
gastos_t_b.fecha_cita='$fecham' and
gastos_t_b.id_proveedor = proveedores.id_proveedor and
gastos_t_b.id_proveedor =  $idprovee and
proveedores.id_s_p_proveedor = s_p_proveedores.id_s_p_proveedor and
(s_p_proveedores.id_sucursal=3 or s_p_proveedores.id_sucursal=8 or s_p_proveedores.id_sucursal=9 or s_p_proveedores.id_sucursal=10 or s_p_proveedores.id_sucursal=12 or s_p_proveedores.id_sucursal=13)
group by 
procesos.id_proceso,procesos.id_titular,procesos.id_beneficiario,procesos.fecha_creado,gastos_t_b.fecha_cita,gastos_t_b.id_proveedor order by gastos_t_b.id_proveedor;;");
$repvercitas = ejecutar($vercitas);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
          <tr> 
             <td colspan=3 class="titulo_seccion">Usuarios citados para el d&iacute;a (<?php echo $fecham?>) - Doctor/a (<?php echo $nombre?>)</td>            
	      </tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Ln.</th>
                 <th class="tdtitulos">Proceso.</th>
                 <th class="tdtitulos">Fecha Creado.</th>
                 <th class="tdtitulos">C&eacute;dula.</th>
                 <th class="tdtitulos">Usuario.</th>
                 <th class="tdtitulos">Ente.</th>
            </tr>
           <?
        $i=1;
        while($lamorbili=asignar_a($repvercitas,NULL,PGSQL_ASSOC)){?>
            <tr>
                    <td class="tdcampos"><?echo $i;?></td>
                    <td class="tdcampos"><?echo $lamorbili['id_proceso'];?></td>
                    <td class="tdcampos"><?echo $lamorbili['fecha_creado'];?></td>
                    <?php
                       $idtitu=$lamorbili[id_titular];
                       $idbene=$lamorbili[id_beneficiario];
                       $bustatitu = ("select clientes.nombres,clientes.apellidos,clientes.cedula,entes.nombre from 
										clientes,entes,titulares
									where
										clientes.id_cliente = titulares.id_cliente and
										titulares.id_titular = $idtitu and
										titulares.id_ente = entes.id_ente");
					   $repdatitu = ejecutar($bustatitu);		
					   $datatitu = assoc_a($repdatitu);		
                       $nombredtitu = "$datatitu[nombres] $datatitu[apellidos]";
                       $nombreente  =  $datatitu[nombre];
                       $cedutitul  =  $datatitu[cedula];
                       if($idbene>0){
                        $busdebenefi = ("select clientes.nombres,clientes.apellidos,clientes.cedula from 
											clientes,beneficiarios
										where
											clientes.id_cliente = beneficiarios.id_cliente and
											beneficiarios.id_beneficiario = $idbene ");
			            $repbusdebenefi = ejecutar($busdebenefi);		
			            $databusdebenefi = assoc_a($repbusdebenefi);	
			            $elnombenefi    =	"$databusdebenefi[nombres] $databusdebenefi[apellidos]";	
			            $cedubenefi     =  $databusdebenefi[cedula]; 			
                       } 
                       if($idbene==0){ ?>
                       <td class="tdcampos"><?echo $cedutitul;?></td>
                        <td class="tdcampos"><?echo $nombredtitu;?></td>
                        
                    <?php }else{?>
                        <td class="tdcampos"><?echo $cedubenefi;?></td>
                        <td class="tdcampos"><?echo $elnombenefi;?></td>
                    <?php }?>
                         <td class="tdcampos"><?echo $nombreente;?></td>
            </tr> 
            
      <?  
        $i++;
        } 
      ?>     
</table>   
