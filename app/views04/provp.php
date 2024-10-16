<?php
include ("../../lib/jfunciones.php");
sesion();
$prov=$_REQUEST['prov'];
$codigomas=$_REQUEST['codigomas'];
$boton=$_REQUEST['boton'];

$procesado=0;
/* **** comparo si voy agregar mas gastos a un pago**** */

$q_codigomas="select * from facturas_procesos where facturas_procesos.codigo='$codigomas';";
$r_codigomas=ejecutar($q_codigomas);
if(num_filas($r_codigomas)>0){
$f_codigomas=asignar_a($r_codigomas);
if  ($f_codigomas[id_banco]==13){
$proveedor1= "where personas_proveedores.id_persona_proveedor=$f_codigomas[id_proveedor]";
$proveedor2= "and proveedores.id_proveedor=$f_codigomas[id_proveedor]";
}
else
{
$proveedor1= "where personas_proveedores.id_persona_proveedor=0";
$proveedor2= "and proveedores.id_proveedor=0";
$procesado=1;
    }
}


/* **** fin de comparar si voy agregar mas gastos a un pago**** */
if ($procesado==0){
?>

<?php
if (($prov==0 || $prov==5) and $boton==2){
?>
<input  type="hidden" size="30" id="proveedor" name="proveedor" class="campos" maxlength="50" value="0">                

<?php
}
if ($prov==1){
?>

                <select style="width:150px;" id="proveedor" name="proveedor" class="campos">
                <?php $q_p=("select  * from  personas_proveedores $proveedor1
		                order by personas_proveedores.nombres_prov");
                $r_p=ejecutar($q_p);
                ?>
                <option   value=""> Dr(a).</option>
                <?php
                while($f_p=asignar_a($r_p,NULL,PGSQL_ASSOC)){

                ?>  
                <option  value="<?php echo $f_p[id_persona_proveedor]?>"> <?php echo "$f_p[nombres_prov] $f_p[apellidos_prov] $f_p[direccion_prov] ($f_p[id_persona_proveedor])"?></option>
                <?php
                }
	                ?>
                </select>
                <?php if ($boton==1){
                ?>
		<input class="campos" type="hidden" id="ret_indi"  name="ret_indi" maxlength=128 size=10 value="0" >
				<a href="#" OnClick="bus_che_prov();" class="boton" title="Buscar Procesos">Buscar</a>
<?php 
}
}
if ($prov==2)
{
?>
 <select style="width: 150px;" id="proveedor" name="proveedor" class="campos">
                <?php $q_pc=("select clinicas_proveedores.*,proveedores.id_proveedor from 
                clinicas_proveedores,proveedores where 
                clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                and clinicas_proveedores.prov_compra=0 $proveedor2 order by clinicas_proveedores.nombre");
                $r_pc=ejecutar($q_pc);
                ?>
                <option   value="">La Clinica</option>
                <?php
                while($f_pc=asignar_a($r_pc,NULL,PGSQL_ASSOC)){

                ?>  
                <option  value="<?php echo $f_pc[id_proveedor]?>"> <?php echo "$f_pc[nombre] $f_pc[direccion]"?></option>
                <?php
                }
                ?>
                </select>
		 <?php if ($boton==1){
                ?>
        <input class="campos" type="hidden" id="ret_indi"  name="ret_indi" maxlength=128 size=10 value="0" >
				<a href="#" OnClick="bus_che_prov();" class="boton" title="Buscar Procesos">Buscar</a>
				<?php 
                }
                }
				if ($prov==3)
{
?>
 <select style="width: 150px;" id="proveedor" name="proveedor" class="campos">
                <?php $q_pc=("select clinicas_proveedores.*,proveedores.id_proveedor from
                clinicas_proveedores,proveedores where 
                clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                and clinicas_proveedores.prov_compra=1 $proveedor2 order by clinicas_proveedores.nombre");
                $r_pc=ejecutar($q_pc);
                ?>
                <option   value="">El Proveedor Compra</option>
                <?php
                while($f_pc=asignar_a($r_pc,NULL,PGSQL_ASSOC)){

                ?>  
                <option  value="<?php echo $f_pc[id_proveedor]?>"> <?php echo "$f_pc[nombre] $f_pc[direccion]"?></option>
                <?php
                }
                ?>
                </select>
                 <?php if ($boton==1){
                ?>
				<a href="#" OnClick="bus_che_prov();" class="boton" title="Buscar Procesos">Buscar</a>
				<?php 
                }
                }
				if ($prov==4)
{
?>
 <select style="width: 150px;" id="proveedor" name="proveedor" class="campos"> 
                <?php $q_pc=("select clinicas_proveedores.*,proveedores.id_proveedor from
                clinicas_proveedores,proveedores where
                clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                and clinicas_proveedores.prov_compra>=0 $proveedor2 order by clinicas_proveedores.nombre");
                $r_pc=ejecutar($q_pc);
                ?>
                <option   value="">Otros Proveedores</option>
                <?php
                while($f_pc=asignar_a($r_pc,NULL,PGSQL_ASSOC)){

                ?>  
                <option  value="<?php echo $f_pc[id_proveedor]?>"> <?php echo "$f_pc[nombre] $f_pc[direccion]"?></option>
                <?php
                }
                ?>
                </select>
 <?php if ($boton==1){
                ?>
		<select style="width: 150px;" id="ret_indi" name="ret_indi" class="campos"> 
                <option   value="0">Sin Retencion Individual</option>
		<option   value="1">Con Retencion Individual</option>
                </select>
				
                <a href="#" OnClick="bus_che_prov();" class="boton" title="Buscar Procesos">Buscar</a>
				<?php 
                }
                }
                }
                else 
                {
                    echo "Este Codigo le Pertenece al Cheque Numero $f_codigomas[numero_cheque] o Recibo $f_codigomas[num_recibo] ";
                    }
                

if ($prov==6)
{
?>
 <select style="width: 150px;" id="proveedor" name="proveedor" class="campos">
                <?php $q_pc=("select * from comisionados  order by comisionados.nombres");
                $r_pc=ejecutar($q_pc);
                ?>
                <option   value="">El Comisionado</option>
                <?php
                while($f_pc=asignar_a($r_pc,NULL,PGSQL_ASSOC)){

                ?>  
                <option  value="<?php echo $f_pc[id_comisionado]?>"> <?php echo "$f_pc[nombres] $f_pc[apellidos]"?></option>
                <?php
                }
                ?>
                </select>
                <select style="width: 150px;" id="ret_indi" name="ret_indi" class="campos"> 
                <option   value="0">Sin Retencion Individual</option>
		<option   value="1">Con Retencion Individual</option>
                </select>
                 <?php if ($boton==1){
                ?>
				<a href="#" OnClick="bus_che_prov();" class="boton" title="Buscar Procesos">Buscar</a>
				<?php 
                }
                }
                ?>