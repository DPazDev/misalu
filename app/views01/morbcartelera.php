<?php
include ("../../lib/jfunciones.php");
sesion();
$fechaviso = date("Y-m-d");
$fechafin = strtotime ( '+1 day' , strtotime ( $fechaviso ) ) ;
$fechafin = date ( 'Y-m-d' , $fechafin );

$vercitas = ("select gastos_t_b.fecha_cita,gastos_t_b.id_proveedor,personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov 
from
procesos,gastos_t_b,proveedores,s_p_proveedores,personas_proveedores
where
procesos.id_proceso = gastos_t_b.id_proceso and
procesos.id_estado_proceso =  2 and
gastos_t_b.fecha_cita between '$fechaviso' and '$fechafin' and
gastos_t_b.id_proveedor = proveedores.id_proveedor and
proveedores.id_s_p_proveedor = s_p_proveedores.id_s_p_proveedor and
(s_p_proveedores.id_sucursal=3 or s_p_proveedores.id_sucursal=8 or s_p_proveedores.id_sucursal=9 or s_p_proveedores.id_sucursal=10 or s_p_proveedores.id_sucursal=12 or s_p_proveedores.id_sucursal=13) and
s_p_proveedores.id_persona_proveedor = personas_proveedores.id_persona_proveedor
group by 
gastos_t_b.fecha_cita,gastos_t_b.id_proveedor,personas_proveedores.nombres_prov,
personas_proveedores.apellidos_prov order by gastos_t_b.id_proveedor,gastos_t_b.fecha_cita;");
$repvercitas = ejecutar($vercitas);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
          <tr> 
             <td colspan=3 class="titulo_seccion">Morbilidad del d&iacute;a (<?php echo $fechaviso?>) al d&iacute;a (<?php echo $fechafin?>)</td>            
	      </tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Fecha.</th>
                 <th class="tdtitulos">Doctor/a.</th>
                 <th class="tdtitulos">Opci&oacute;n.</th>
            </tr>
           <?
        $i=1;
        while($lamorbili=asignar_a($repvercitas,NULL,PGSQL_ASSOC)){?>
            <tr>
                    
                    <td class="tdcampos"><?echo $lamorbili['fecha_cita'];?></td>
                    <td class="tdcampos"><?echo "$lamorbili[nombres_prov] $lamorbili[apellidos_prov]";?></td>
                    <td class="tdcampos"><label title="Ver pacientes citados"class="boton" style="cursor:pointer" onclick="VerPacMorbi('<?php echo $lamorbili[fecha_cita]?>',<?php echo $lamorbili['id_proveedor']?>, '<?php echo "$lamorbili[nombres_prov] $lamorbili[apellidos_prov]"?>')" >Ver</label></td>
            </tr> 
            
      <?  
        $i++;
        } 
      ?>     
</table>            
