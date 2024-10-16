<?php
include ("../../lib/jfunciones.php");
sesion();
$admin= $_SESSION['id_usuario_'.empresa];


$monto          = $_REQUEST['monto_fac']; 
$forma_pago     = $_REQUEST['forma_pago'];
$tipo_moneda    = $_REQUEST['tipo_moneda'];
$admin          = $_SESSION['id_usuario_'.empresa];
$factura        = $_REQUEST['factura'];
$id_factura     = $_REQUEST['id_factura'];
?>
<input type="hidden" id="monto" name="monto"  size=20 value="<?php echo $monto?>">
<form name="factura">

<?php


   //**************************************************************************************** EFECTIVO
     if ($forma_pago==1){
	?>
	<input readonly type="hidden" size="10" id="dateField5" name="fechac" class="campos" maxlength="10" value=""> 
	<input readonly type="hidden" size="10" id="nom_tarjeta" name="nom_tarjeta" class="campos" maxlength="10" value="0"> 
	<input readonly type="hidden" size="10" id="banco" name="banco" class="campos" maxlength="10" value="0"> 
	<input readonly type="hidden" id="no_cheque" name="no_cheque" class="campos" size=20 value="0">
	
	<input readonly type="hidden" id="nombre_p_pago" name="nombre_p_pago" maxlength="20" class="campos" size=20 >
    <input readonly type="hidden" id="cedula_p_pago" name="nombre_p_pago" maxlength="20" class="campos" size=20 >
	<input readonly type="hidden" id="telf_p_pago" name="telf_p_pago" maxlength="20" class="campos" size=20 >
	<input readonly type="hidden" id="correo_p_pago" name="correo_p_pago" maxlength="20" class="campos" size=20 >

	<?php
        }

   //*************************************************************************************** CREDITO
    
     if ($forma_pago==2){
     ?>
    <fieldset class="tipos-pagos2">
    <div>	
    <label  class="tdtituloss">*  Fecha de Final de Credito   (A&ntilde;o-Mes-D&iacute;a)</label>
    </div>
    <div><input type="text" size="10" id="dateField5" name="fechac" class="campos" maxlength="10" value="" title="Fecha en formato AAAAA/MM/DD"required> 
    </div>

		<input readonly type="hidden" size="10" id="nom_tarjeta" name="nom_tarjeta" class="campos" maxlength="10" value="0"> 
		<input readonly type="hidden" size="10" id="banco" name="banco" class="campos" maxlength="10" value="0"> 
		<input type="hidden" id="no_cheque" name="no_cheque" class="campos" size=20 value="0">

		<input readonly type="hidden" id="nombre_p_pago" name="nombre_p_pago" maxlength="20" class="campos" size=20 >
	    <input readonly type="hidden" id="cedula_p_pago" name="nombre_p_pago" maxlength="20" class="campos" size=20 >
		<input readonly type="hidden" id="telf_p_pago" name="telf_p_pago" maxlength="20" class="campos" size=20 >
		<input readonly type="hidden" id="correo_p_pago" name="correo_p_pago" maxlength="20" class="campos" size=20 >
    </div>
    </fieldset>
   <?php 
        }

   //************************************************************************************ TARJETA DE CREDITO

if ($forma_pago==5){
                //busco los tipos de pagos.
                $q_forma_pago="select * from tbl_nombre_tarjetas where tbl_nombre_tarjetas.id_tipo_pago=$forma_pago order by tbl_nombre_tarjetas.nombre_tar";
                $r_forma_pago=ejecutar($q_forma_pago);
?>
	<fieldset class="tipos-pagos2">
     <div>
	  <label class="tdtitulos">Seleccione el Tipo de Tarjeta</label>
    </div>
	<div class="item">
	  <select id="nom_tarjeta" name="nom_tarjeta" class="campos">
                        <?php 
                        while($f_forma_pago=asignar_a($r_forma_pago)){
			?>
                                <option value="<?php echo $f_forma_pago[id_nom_tarjeta]?>"><?php echo $f_forma_pago[nombre_tar]?></option>";
                        <?php
			}
			?>

         </select>
	 </div>
    </fieldset>
<?php
}
else
{?>
    <input type="hidden" id="nom_tarjeta" name="nom_tarjeta" class="campos" size=20 value="0" >
<?php
}
	
	//********************************************************************************* CHEQUE O DEBITO

     if (($forma_pago>=3 && $forma_pago<=5)) {
		//busco los bancos.
		$q_bancos="select * from tbl_bancos where tbl_bancos.id_ban<>7 order by tbl_bancos.nombanco";
		$r_bancos=ejecutar($q_bancos);
	?>	
	<fieldset class="tipos-pagos2">

	<div>
	<label class="tdtitulos">Banco</label>
	<select id="banco" name="banco" class="campos" style="width:170px">
	<?php	while($f_banco=asignar_a($r_bancos)){
				echo "<option value=\"$f_banco[id_ban]\">$f_banco[nombanco]</option>";
			}
	?>
	</select>
    </div>

    <div>
	<label class="tdtitulos">Referencia</label>
    <input  type="text" id="no_cheque" name="no_cheque" maxlength="15" style="width:130px" class="campos" size=15 title="Num Cheque/ Ultimos 4 digitos de tarjeta/ Referencia" >
    
      <input readonly type="hidden" size="10" id="dateField5" name="fechac" class="campos" maxlength="10" value=""> 
	  <input readonly type="hidden" id="nombre_p_pago" name="nombre_p_pago" maxlength="20" class="campos" size=20 >
      <input readonly type="hidden" id="cedula_p_pago" name="nombre_p_pago" maxlength="20" class="campos" size=20 >
	  <input readonly type="hidden" id="telf_p_pago" name="telf_p_pago" maxlength="20" class="campos" size=20 >
	  <input readonly type="hidden" id="correo_p_pago" name="correo_p_pago" maxlength="20" class="campos" size=20 >
 	  <input readonly type="hidden" size="10" id="nom_tarjeta" name="nom_tarjeta" class="campos" maxlength="10" value="0"> 

    </div>
	</fieldset>

<?php }


   //************************************************************************************** PAGO MOVIL  y TRANSFERENCIAS / DEPOSITOS

if (($forma_pago==7 || $forma_pago==10)) {
		//busco los bancos.
		$q_bancos="select * from tbl_bancos where tbl_bancos.id_ban<>7 order by tbl_bancos.nombanco";
		$r_bancos=ejecutar($q_bancos);
	?>	
	<fieldset class="tipos-pagos2">

	<div>
	<label class="tdtitulos">Banco</label>
	<select id="banco" name="banco" class="campos" style="width:170px">
	<?php	while($f_banco=asignar_a($r_bancos)){
				echo "<option value=\"$f_banco[id_ban]\">$f_banco[nombanco]</option>";
			}
	?>
	</select>
    </div>

    <div>
	<label class="tdtitulos">Referencia</label>
    <input  type="text" id="no_cheque" name="no_cheque" maxlength="15" style="width:130px" class="campos" size=15 title="Num Cheque/ Ultimos 4 digitos de tarjeta/ Referencia" >
  </fieldset>
    
    <fieldset class="tipos-pagos2">
      <div>	
      <label class="tdtitulos">Nombre y Apellido</label>
	 <input type="text" id="nombre_p_pago" name="nombre_p_pago" maxlength="60" class="campos" style="width:140px" size=60 >
     </div>

     <div>
     <div><label class="tdtitulos">Cédula</label></div>
	 <input type="text" id="cedula_p_pago" name="cedula_p_pago" maxlength="12" class="campos" size=12 >	
     </div>

     <div>
     <div><label class="tdtitulos">Tel&eacute;fono</label></div>
	 <input type="text" id="telf_p_pago" name="telf_p_pago" maxlength="15" class="campos" size=15  onkeypress="return SoloNumeros(event)" title="Solo números">	
       	<input readonly type="hidden" size="10" id="nom_tarjeta" name="nom_tarjeta" class="campos" maxlength="10" value="0"> 
        <input readonly type="hidden" size="10" id="dateField5" name="fechac" class="campos" maxlength="10" value="">
        <input readonly type="hidden" id="correo_p_pago" name="correo_p_pago" maxlength="20" class="campos" size=20 > 

	   <br><br>
	<div>
	<label class="button" id="datoscliente_tp" style="cursor:pointer" onclick="datoscliente_tp()" >Datos Cliente</label></span>
	</div>
 

     </div> 
    </fieldset> 

     <?php 
       } 

    //******************************************************************************************** ZELLE
     if($forma_pago==11){
    ?>
    <fieldset class="tipos-pagos2">
     <div>
	<label class="tdtitulos">Referencia</label>
    <input  type="text" id="no_cheque" name="no_cheque" style="width:120px" maxlength="15" class="campos" title="Num Cheque/ Ultimos 4 digitos de tarjeta/ Referencia" >
     </div>	
    
     <div>	
     <label class="tdtitulos">Nombre y Apellido</label>
	 <input type="text" id="nombre_p_pago" name="nombre_p_pago" maxlength="60" class="campos" style="width:140px" size=60 >
     </div>
    
     <div>
     <div><label class="tdtitulos">Tel&eacute;fono</label></div>
	 <input type="text" id="telf_p_pago" name="telf_p_pago" maxlength="15" class="campos" size=15  onkeypress="return SoloNumeros(event)" title="Solo números">
	 </div>	

     <div>
     <label class="tdtitulos">Correo</label>
	 <input type="email" id="correo_p_pago" placeholder="ejemplo@gmail.com"  style="width:180px" name="correo_p_pago" maxlength="40" class="campos" >
   

	<input readonly type="hidden" size="10" id="dateField5" name="fechac" class="campos" maxlength="10" value=""> 
	<input readonly type="hidden" size="10" id="nom_tarjeta" name="nom_tarjeta" class="campos" maxlength="10" value="0"> 
	<input readonly type="hidden" size="10" id="banco" name="banco" class="campos" maxlength="10" value="0"> 
    <input readonly type="hidden" id="cedula_p_pago" name="nombre_p_pago" maxlength="20" class="campos" size=20 >
   
    <br><br>
	<div>
	<label class="button" id="datoscliente_tp" style="cursor:pointer" onclick="datoscliente_tp()" >Datos Cliente</label></span>
	</div>
 
        </fieldset> 

   <?php
     }
	
		//*****     OPERACIONES MULTIPLES     *****

	 if ($forma_pago==6){
				//*************************************************** PAGO 1

           //busco los tipos de pagos.
           $q_tipo_pago="select * from tbl_tipos_pagos where id_tipo_pago<>6 and id_tipo_pago<> 2 order by tbl_tipos_pagos.tipo_pago";
           $r_tipo_pago=ejecutar($q_tipo_pago);
	  ?>
	
	 <fieldset class="tipos-pagos3">

	 <div>
	 <label class="tdtituloss">Forma de pago</label>
	   <select id="forma_pago1" name="forma_pago1" style="width:170px"  OnChange="sumarom();" class="campos">
        <option value="0">Seleccione la Forma de Pago</option>       
         <?php 
                        while($f_tipo_pago=asignar_a($r_tipo_pago)){
			?>
                                <option value="<?php echo $f_tipo_pago[id_tipo_pago]?>">
         <?php echo $f_tipo_pago[tipo_pago]?></option>";
          <?php
			}
			?>
        </select>
		</div>  

		<?php	
		//busco los bancos.
		$q_bancos="select * from tbl_bancos where tbl_bancos.id_ban<>7 order by tbl_bancos.nombanco";
		$r_bancos=ejecutar($q_bancos);
		//busco llos nombres de tarjeta.
                $q_forma_pago="select * from tbl_nombre_tarjetas  order by tbl_nombre_tarjetas.nombre_tar";
                $r_forma_pago=ejecutar($q_forma_pago);
		?>
		  <div>
		   <div class="tdtituloss" id="servicio-descripcion1" ></div>
            
            <input type="text" id="correo_p_pago1" style="visibility:hidden; width: 150px;" placeholder="ejemplo@gmail.com" name="correo_p_pago1" maxlength="40" class="campos" size=40 >

		  	<select id="banco1" name="banco1"  style="width:170px; visibility: hidden; " class="campos">
			<option value="0">Seleccione El Banco</option>
			<?
			while($f_banco=asignar_a($r_bancos)){
				echo "<option value=\"$f_banco[id_ban]\">$f_banco[nombanco]</option>";
			}
		?>
		</select>
		<select id="nom_tarjeta1" style="visibility:hidden;" name="nom_tarjeta1" class="campos">
                        <?php 
                        while($f_forma_pago=asignar_a($r_forma_pago)){
			?>
                                <option value="<?php echo $f_forma_pago[id_nom_tarjeta]?>"><?php echo $f_forma_pago[nombre_tar]?></option>";
                        <?php
			}
			?>
         </select> 
	  	</div> 
	 </div>
	
		<div>
			<label class="tdtituloss">Referencia</label>
			 <input  type="text" id="no_cheque1" style="visibility:hidden;" name="no_cheque1" maxlength="15"  class="campos" size=15 title="Num Cheque/ Ultimos 4 digitos de tarjeta/ Referencia" >
		</div> 

		<div>
			<label class="tdtituloss">Moneda</label>
			 <?php
                //busco los tipos de monedas
                $q_tipo_moneda="select * from tbl_monedas order by tbl_monedas.id_moneda";
                $r_tipo_moneda=ejecutar($q_tipo_moneda);
               ?>
            <div>
             <select id="tipo_moneda1" name="tipo_moneda1" class="campos" style="visibility:hidden;">
		     <?php
		       while($moneda=asignar_a($r_tipo_moneda,NULL,PGSQL_ASSOC)){
		        if($moneda[id_moneda]==1){?>
		          <option value="<?php echo $moneda[id_moneda]?>"select="select"><?php echo $moneda[moneda]?></option>";
		       <?php }
		        else{?>
		         <option value="<?php echo $moneda[id_moneda]?>"><?php echo $moneda[moneda]?></option>";
		       
		      <?php  }
		       }
		     ?>
		   </select> 
		  </div>
		</div>

		 <div>
         <label class="tdtituloss">Monto</label>
	     <input type="text" id="monto1" name="monto1" style="visibility:hidden" class="campos" size=10 value="0">
	     </div> 

		 <div id="servicio-nombre1" style="visibility:hidden">	
	     <div><label class="tdtituloss">Nombre y Apellido</label></div>
		 <input type="text" id="nombre_p_pago1" style="visibility:hidden; width:110px" name="nombre_p_pago1" maxlength="60" class="campos" size=60 >
	     </div>
          
           <div id="servicio-telefono1" style="visibility:hidden">
	     <label class="tdtituloss">Tel&eacute;fono</label>
		 <div><input type="text" id="telf_p_pago1" style="visibility:hidden" name="telf_p_pago1" maxlength="15" class="campos" size=15  onkeypress="return SoloNumeros(event)" title="Solo números"></div>
	     </div> 

	     <div id="servicio-cedula1" style="visibility:hidden">
	     <label class="tdtituloss">Cédula</label>
		 <div><input type="text" id="cedula_p_pago1" style="visibility:hidden" name="cedula_p_pago1" maxlength="12" class="campos" size=12 ></div>	
	     </div>

	     <div>
	     <div id="servicio-descripcion1" class="tdtituloss" style="visibility:hidden">		   	
	     <input type="text" id="correo_p_pago1" style="visibility:hidden;" placeholder="ejemplo@gmail.com" name="correo_p_pago1" maxlength="40" class="campos">
         </div></div>

          <br><br>
	<div>
		<span class="button" id="datoscliente_tp1" style="cursor:pointer; visibility: hidden;" onclick="datoscliente_tp1()" >Datos Cliente</label></span>
	</div>


	
        <br>
       <div >
		<span id='boton-div_forma_pago2' onclick="mostrar_div_forma_pago('div_forma_pago2')"><img src="../public/images/add_16.png" align="right" class="cp_img" title="Agregue un nuevo pago"></span>	
	  </div>

      </fieldset>







      	<?php  //****************************************** PAGO 2

              //busco los tipos de pagos.
           $q_tipo_pago="select * from tbl_tipos_pagos where id_tipo_pago<>6 and id_tipo_pago<> 2 order by tbl_tipos_pagos.tipo_pago";
           $r_tipo_pago=ejecutar($q_tipo_pago);
      	?>


   <div id="div_forma_pago2" style="display: none;">
     <fieldset class="tipos-pagos3">

      <!----------------------------------------------------------------------------------------------------------->
       <div>
	 		<label class="tdtituloss">Forma de pago</label>
	   			<select id="forma_pago2" name="forma_pago2" style="width:170px"  OnChange="sumarom();" class="campos">
        			<option value="0">Seleccione la Forma de Pago</option>       
         				<?php 
                        	while($f_tipo_pago=asignar_a($r_tipo_pago)){
							?>
		                                <option value="<?php echo $f_tipo_pago[id_tipo_pago]?>">
		         <?php echo $f_tipo_pago[tipo_pago]?></option>";
		          <?php
	 				}
					?>
		        </select>
	 	 </div>
    <!----------------------------------------------------------------------------------------------------------->
      
		    <?php	
				//busco los bancos.
			$q_bancos="select * from tbl_bancos where tbl_bancos.id_ban<>7 order by tbl_bancos.nombanco";
			$r_bancos=ejecutar($q_bancos);
			//busco llos nombres de tarjeta.
	                $q_forma_pago="select * from tbl_nombre_tarjetas  order by tbl_nombre_tarjetas.nombre_tar";
	                $r_forma_pago=ejecutar($q_forma_pago);
			?>
	     <div>
		   <div class="tdtituloss" id="servicio-descripcion2" ></div>
            
            <input type="text" id="correo_p_pago2" style="visibility:hidden; width: 150px;" placeholder="ejemplo@gmail.com" name="correo_p_pago1" maxlength="40" class="campos" size=40 >

		  	<select id="banco2" name="banco2"  style="width:170px; visibility: hidden; " class="campos">
			<option value="0">Seleccione El Banco</option>
			<?
			while($f_banco=asignar_a($r_bancos)){
				echo "<option value=\"$f_banco[id_ban]\">$f_banco[nombanco]</option>";
			}
		?>
		</select>
		<select id="nom_tarjeta2" style="visibility:hidden;" name="nom_tarjeta2" class="campos">
                        <?php 
                        while($f_forma_pago=asignar_a($r_forma_pago)){
			?>
                                <option value="<?php echo $f_forma_pago[id_nom_tarjeta]?>"><?php echo $f_forma_pago[nombre_tar]?></option>";
                        <?php
			}
			?>
         </select> 
        </div>
   <!----------------------------------------------------------------------------------------------------------->



            <div>
			<label class="tdtituloss"  id="servicio-referencia2" >Referencia</label>
			<input  type="text" id="no_cheque2" style="visibility:hidden;" name="no_cheque2" maxlength="15"  class="campos" size=15 title="Num Cheque/ Ultimos 4 digitos de tarjeta/ Referencia" >
            </div>
<!----------------------------------------------------------------------------------------------------------->

       <div>
			 <div><label class="tdtituloss" id="servicio-tipomoneda2" >Moneda</label></div>
			 <?php
                //busco los tipos de monedas
                $q_tipo_moneda="select * from tbl_monedas order by tbl_monedas.id_moneda";
                $r_tipo_moneda=ejecutar($q_tipo_moneda);
               ?>
             <select id="tipo_moneda2" name="tipo_moneda2" class="campos" style="visibility:hidden;">
		     <?php
		       while($moneda=asignar_a($r_tipo_moneda,NULL,PGSQL_ASSOC)){
		        if($moneda[id_moneda]==1){?>
		          <option value="<?php echo $moneda[id_moneda]?>"select="select"><?php echo $moneda[moneda]?></option>";
		       <?php }
		        else{?>
		         <option value="<?php echo $moneda[id_moneda]?>"><?php echo $moneda[moneda]?></option>";
		       
		      <?php  }
		       }
		     ?>
		         </select> 
      </div>


<!----------------------------------------------------------------------------------------------------------->

       <div>
       <label class="tdtituloss" id="servicio-monto2" type="hidden">Monto</label>
	     <input type="text" id="monto2" name="monto2" style="visibility:hidden" class="campos" size=10 value="0">
	     </div> 
<!----------------------------------------------------------------------------------------------------------->

		  <div id="servicio-nombre2" style="visibility:hidden">	
	    <div><label class="tdtituloss">Nombre y Apellido</label></div>
		  <input type="text" id="nombre_p_pago2" style="visibility:hidden; width:110px" name="nombre_p_pago2" maxlength="60" class="campos" size=60 >
	    </div>
<!----------------------------------------------------------------------------------------------------------->
          
      <div id="servicio-telefono2" style="visibility:hidden">
	    <label class="tdtituloss">Tel&eacute;fono</label>
		  <div><input type="text" id="telf_p_pago2" style="visibility:hidden" name="telf_p_pago2" maxlength="15" class="campos" size=15  onkeypress="return SoloNumeros(event)" title="Solo números"></div>
	    </div> 
<!----------------------------------------------------------------------------------------------------------->

	   <div id="servicio-cedula2" style="visibility:hidden">
	   <label class="tdtituloss">Cédula</label>
		 <div><input type="text" id="cedula_p_pago2" style="visibility:hidden" name="cedula_p_pago2" maxlength="12" class="campos" size=12 ></div>	
	   </div>
<!----------------------------------------------------------------------------------------------------------->

	     <div>
	     <div id="servicio-descripcion2" class="tdtituloss" style="visibility:hidden">		   	
	     <input type="text" id="correo_p_pago2" style="visibility:hidden;" placeholder="ejemplo@gmail.com" name="correo_p_pago2" maxlength="40" class="campos">
       </div>
       </div>

                <br><br>
		  <div>
			<span class="button" id="datoscliente_tp2" style="cursor:pointer; visibility: hidden;" onclick="datoscliente_tp2()" >Datos Cliente</label></span>
	    </div>


    <div>
		<span id='boton-div_forma_pago3' onclick="mostrar_div_forma_pago('div_forma_pago3')"><img src="../public/images/add_16.png"  class="cp_img" align="right" alt="Agregue un nuevo pago"></span>	
	  </div>

		
      </fieldset>
  </div>

  





      	<?php  //****************************************** PAGO 3

              //busco los tipos de pagos.
           $q_tipo_pago="select * from tbl_tipos_pagos where id_tipo_pago<>6 and id_tipo_pago<> 2 order by tbl_tipos_pagos.tipo_pago";
           $r_tipo_pago=ejecutar($q_tipo_pago);
      	?>


   <div id="div_forma_pago3" style="display: none;">
     <fieldset class="tipos-pagos3">

      <!----------------------------------------------------------------------------------------------------------->
       <div>
	 		<label class="tdtituloss">Forma de pago</label>
	   			<select id="forma_pago3" name="forma_pago3" style="width:170px"  OnChange="sumarom();" class="campos">
        			<option value="0">Seleccione la Forma de Pago</option>       
         				<?php 
                        	while($f_tipo_pago=asignar_a($r_tipo_pago)){
							?>
		                                <option value="<?php echo $f_tipo_pago[id_tipo_pago]?>">
		         <?php echo $f_tipo_pago[tipo_pago]?></option>";
		          <?php
	 				}
					?>
		        </select>
	 	 </div>
    <!----------------------------------------------------------------------------------------------------------->
      
		    <?php	
				//busco los bancos.
			$q_bancos="select * from tbl_bancos where tbl_bancos.id_ban<>7 order by tbl_bancos.nombanco";
			$r_bancos=ejecutar($q_bancos);
			//busco llos nombres de tarjeta.
	                $q_forma_pago="select * from tbl_nombre_tarjetas  order by tbl_nombre_tarjetas.nombre_tar";
	                $r_forma_pago=ejecutar($q_forma_pago);
			?>
	     <div>
		   <div class="tdtituloss" id="servicio-descripcion3" ></div>
            
            <input type="text" id="correo_p_pago3" style="visibility:hidden; width: 150px;" placeholder="ejemplo@gmail.com" name="correo_p_pago3" maxlength="40" class="campos" size=40 >

		  	<select id="banco3" name="banco3"  style="width:170px; visibility: hidden; " class="campos">
			<option value="0">Seleccione El Banco</option>
			<?
			while($f_banco=asignar_a($r_bancos)){
				echo "<option value=\"$f_banco[id_ban]\">$f_banco[nombanco]</option>";
			}
		?>
		</select>
		<select id="nom_tarjeta3" style="visibility:hidden;" name="nom_tarjeta3" class="campos">
                        <?php 
                        while($f_forma_pago=asignar_a($r_forma_pago)){
			?>
                                <option value="<?php echo $f_forma_pago[id_nom_tarjeta]?>"><?php echo $f_forma_pago[nombre_tar]?></option>";
                        <?php
			}
			?>
         </select> 
        </div>
   <!----------------------------------------------------------------------------------------------------------->



            <div>
			<label class="tdtituloss"  id="servicio-referencia3" >Referencia</label>
			<input  type="text" id="no_cheque3" style="visibility:hidden;" name="no_cheque3" maxlength="15"  class="campos" size=15 title="Num Cheque/ Ultimos 4 digitos de tarjeta/ Referencia" >
            </div>
<!----------------------------------------------------------------------------------------------------------->

       <div>
			 <div><label class="tdtituloss" id="servicio-tipomoneda3" >Moneda</label></div>
			 <?php
                //busco los tipos de monedas
                $q_tipo_moneda="select * from tbl_monedas order by tbl_monedas.id_moneda";
                $r_tipo_moneda=ejecutar($q_tipo_moneda);
               ?>
            
             <select id="tipo_moneda3" name="tipo_moneda3" class="campos" style="visibility:hidden;">
		     <?php
		       while($moneda=asignar_a($r_tipo_moneda,NULL,PGSQL_ASSOC)){
		        if($moneda[id_moneda]==1){?>
		          <option value="<?php echo $moneda[id_moneda]?>"select="select"><?php echo $moneda[moneda]?></option>";
		       <?php }
		        else{?>
		         <option value="<?php echo $moneda[id_moneda]?>"><?php echo $moneda[moneda]?></option>";
		       
		      <?php  }
		       }
		     ?>
		         </select> 
       </div>

<!----------------------------------------------------------------------------------------------------------->


         <div>
         <label class="tdtituloss" id="servicio-monto3" type="hidden">Monto</label>
	     <input type="text" id="monto3" name="monto3" style="visibility:hidden" class="campos" size=10 value="0">
	     </div> 
<!----------------------------------------------------------------------------------------------------------->

		 <div id="servicio-nombre3" style="visibility:hidden">	
	     <div><label class="tdtituloss">Nombre y Apellido</label></div>
		 <input type="text" id="nombre_p_pago3" style="visibility:hidden; width:110px" name="nombre_p_pago3" maxlength="60" class="campos" size=60 >
	     </div>
<!----------------------------------------------------------------------------------------------------------->
          
           <div id="servicio-telefono3" style="visibility:hidden">
	     <label class="tdtituloss">Tel&eacute;fono</label>
		 <div><input type="text" id="telf_p_pago3" style="visibility:hidden" name="telf_p_pago3" maxlength="15" class="campos" size=15  onkeypress="return SoloNumeros(event)" title="Solo números"></div>
	     </div> 
<!----------------------------------------------------------------------------------------------------------->

	     <div id="servicio-cedula3" style="visibility:hidden">
	     <label class="tdtituloss">Cédula</label>
		 <div><input type="text" id="cedula_p_pago3" style="visibility:hidden" name="cedula_p_pago3" maxlength="12" class="campos" size=12 ></div>	
	     </div>
<!----------------------------------------------------------------------------------------------------------->

	     <div>
	     <div id="servicio-descripcion3" class="tdtituloss" style="visibility:hidden">		   	
	     <input type="text" id="correo_p_pago3" style="visibility:hidden;" placeholder="ejemplo@gmail.com" name="correo_p_pago3" maxlength="40" class="campos">
         </div></div>

                <br><br>
	<div>
		<span  class="button" id="datoscliente_tp3" style="cursor:pointer; visibility: hidden;" onclick="datoscliente_tp3()" >Datos Cliente</label></span>
	</div>


       <div>
		<span id='boton-div_forma_pago4' onclick="mostrar_div_forma_pago('div_forma_pago4')"><img src="../public/images/add_16.png" class="cp_img" alt="Agregue un nuevo pago"></span>	
	  </div>

		
      </fieldset>
  </div>




      	<?php  //****************************************** PAGO 4

              //busco los tipos de pagos.
           $q_tipo_pago="select * from tbl_tipos_pagos where id_tipo_pago<>6 and id_tipo_pago<> 2 order by tbl_tipos_pagos.tipo_pago";
           $r_tipo_pago=ejecutar($q_tipo_pago);
      	?>


   <div id="div_forma_pago4" style="display: none;">
     <fieldset class="tipos-pagos1">

      <!----------------------------------------------------------------------------------------------------------->
       <div>
	 		<label class="tdtituloss">Forma de pago</label>
	   			<select id="forma_pago4" name="forma_pago4" style="width:170px"  OnChange="sumarom();" class="campos">
        			<option value="0">Seleccione la Forma de Pago</option>       
         				<?php 
                        	while($f_tipo_pago=asignar_a($r_tipo_pago)){
							?>
		                                <option value="<?php echo $f_tipo_pago[id_tipo_pago]?>">
		         <?php echo $f_tipo_pago[tipo_pago]?></option>";
		          <?php
	 				}
					?>
		        </select>
	 	 </div>
    <!----------------------------------------------------------------------------------------------------------->
      
		    <?php	
				//busco los bancos.
			$q_bancos="select * from tbl_bancos where tbl_bancos.id_ban<>7 order by tbl_bancos.nombanco";
			$r_bancos=ejecutar($q_bancos);
			//busco llos nombres de tarjeta.
	                $q_forma_pago="select * from tbl_nombre_tarjetas  order by tbl_nombre_tarjetas.nombre_tar";
	                $r_forma_pago=ejecutar($q_forma_pago);
			?>
	     <div>
		   <div class="tdtituloss" id="servicio-descripcion4" ></div>
            
            <input type="text" id="correo_p_pago4" style="visibility:hidden; width: 150px;" placeholder="ejemplo@gmail.com" name="correo_p_pago4" maxlength="40" class="campos" size=40 >

		  	<select id="banco4" name="banco4"  style="width:170px; visibility: hidden; " class="campos">
			<option value="0">Seleccione El Banco</option>
			<?
			while($f_banco=asignar_a($r_bancos)){
				echo "<option value=\"$f_banco[id_ban]\">$f_banco[nombanco]</option>";
			}
		?>
		</select>
		<select id="nom_tarjeta4" style="visibility:hidden;" name="nom_tarjeta4" class="campos">
                        <?php 
                        while($f_forma_pago=asignar_a($r_forma_pago)){
			?>
                                <option value="<?php echo $f_forma_pago[id_nom_tarjeta]?>"><?php echo $f_forma_pago[nombre_tar]?></option>";
                        <?php
			}
			?>
         </select> 
        </div>
   <!----------------------------------------------------------------------------------------------------------->



            <div>
			<label class="tdtituloss"  id="servicio-referencia4" >Referencia</label>
			<input  type="text" id="no_cheque4" style="visibility:hidden;" name="no_cheque3" maxlength="15"  class="campos" size=15 title="Num Cheque/ Ultimos 4 digitos de tarjeta/ Referencia" >
            </div>
<!----------------------------------------------------------------------------------------------------------->

       <div>
			 <div><label class="tdtituloss" id="servicio-tipomoneda4" >Moneda</label></div>
			 <?php
                //busco los tipos de monedas
                $q_tipo_moneda="select * from tbl_monedas order by tbl_monedas.id_moneda";
                $r_tipo_moneda=ejecutar($q_tipo_moneda);
               ?>
            
             <select id="tipo_moneda4" name="tipo_moneda4" class="campos" style="visibility:hidden;">
		     <?php
		       while($moneda=asignar_a($r_tipo_moneda,NULL,PGSQL_ASSOC)){
		        if($moneda[id_moneda]==1){?>
		          <option value="<?php echo $moneda[id_moneda]?>"select="select"><?php echo $moneda[moneda]?></option>";
		       <?php }
		        else{?>
		         <option value="<?php echo $moneda[id_moneda]?>"><?php echo $moneda[moneda]?></option>";
		       
		      <?php  }
		       }
		     ?>
		        </select> 
       </div>

<!----------------------------------------------------------------------------------------------------------->


         <div>
         <label class="tdtituloss" id="servicio-monto4" type="hidden">Monto</label>
	     <input type="text" id="monto4" name="monto4" style="visibility:hidden" class="campos" size=10 value="0">
	     </div> 
<!----------------------------------------------------------------------------------------------------------->

		 <div id="servicio-nombre4" style="visibility:hidden">	
	     <div><label class="tdtituloss">Nombre y Apellido</label></div>
		 <input type="text" id="nombre_p_pago4" style="visibility:hidden; width:110px" name="nombre_p_pago4" maxlength="60" class="campos" size=60 >
	     </div>
<!----------------------------------------------------------------------------------------------------------->
          
         <div id="servicio-telefono4" style="visibility:hidden">
	     <label class="tdtituloss">Tel&eacute;fono</label>
		 <div><input type="text" id="telf_p_pago4" style="visibility:hidden" name="telf_p_pago3" maxlength="15" class="campos" size=15  onkeypress="return SoloNumeros(event)" title="Solo números"></div>
	     </div> 
<!----------------------------------------------------------------------------------------------------------->

	     <div id="servicio-cedula4" style="visibility:hidden">
	     <label class="tdtituloss">Cédula</label>
		 <div><input type="text" id="cedula_p_pago4" style="visibility:hidden" name="cedula_p_pago4" maxlength="12" class="campos" size=12 ></div>	
	     </div>
<!----------------------------------------------------------------------------------------------------------->

	     <div>
	     <div id="servicio-descripcion4" class="tdtituloss" style="visibility:hidden">		   	
	     <input type="text" id="correo_p_pago4" style="visibility:hidden;" placeholder="ejemplo@gmail.com" name="correo_p_pago4" maxlength="40" class="campos">
         </div></div>

                <br><br>
	<div>
		<span class="button" id="datoscliente_tp4" style="cursor:pointer; visibility: hidden;" onclick="datoscliente_tp4()" >Datos Cliente</span>
	</div>

		
      </fieldset>
  </div>




      
	<fieldset class="tipos-pagos2">
    <div align="item">
	<span OnClick="sumarom1();" class="button">Calcular</span>
    </div>
	<div><input type="text" id="calculo" name="calculo" class="campos" size=10 maxlength="10" value="0"></div>
    </fieldset>

</form>

<?php
}?>

<div id="final_datos_pago"></div>
<?php 

		if ($forma_pago<>6){
?>
<div>
<span class="button" type="hidden" name="vacio" maxlength=128 size=20  OnClick="actualizar_pago();" >Guardar</span>
</div>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 

<?php 
}
else
{
?>

<div>	
<span class="button" type="hidden" name="vacio" maxlength=128 size=20 OnClick="actualizar_pago2();" style="visibility:hidden" id="guardar" name="guardar" class="boton">Guardar</span>
</div>
<?php
}