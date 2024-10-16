/* **** facturacion **** */
function buscar_procesosf(){
	
	if ((Field.present('proceso')==false) && (Field.present('clave')==false) && (Field.present('planilla')==false) && (Field.present('entes')==false))
	{
		alert ("Campos Vacios Haga la Busqueda llenando uno de los Campos si la Factura es por Ente debe Seleccionar las Fechas");
		}
		else
		{
new Ajax.Request("views04/reg_facturas1.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&proceso='+$F('proceso')+'&clave='+$F('clave')+'&planilla='+$F('planilla')+'&tipo_ente='+$F('tipo_ente')+'&entes='+$F('entes')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios')+'&cua_rec_prim='+$F('cua_rec_prim')+'&partidas='+$F('partidas'),
	onComplete: mos_bus_prof
	  
    });
	var1 = document.getElementById('buscar_procesosf'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_prof(req){
	
  $('buscar_procesosf').innerHTML= req.responseText;

}
}



function buscar_tarjetas(i){
modulo=i;
new Ajax.Request("views04/buscar_tarjetas.php",
    {
       method:'post',
       asynchronous: true,
       postBody:


                'forma_pago='+$F('forma_pago')+'&modulo='+modulo+'&controlfactura='+$F('controlfactura')+'&serie='+$F('serie')+'&ult_controlfactura='+$F('ult_controlfactura'),
        onComplete: mos_bus_tarjeta

    });
        var1 = document.getElementById('buscar_tarjetas');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_tarjeta(req){

  $('buscar_tarjetas').innerHTML= req.responseText;

}


/*FUNCIONES PARA ENVIAR INFO. TIPOS DE PAGOS ING PATRICIA 19-10-22*/
function mostrar_div_forma_pago(iddiv){
			document.getElementById(iddiv).style.display='block';
			document.getElementById('boton-'+iddiv).style.display='none';

}

function datoscliente_tp() {
	  document.getElementById("nombre_p_pago").value =$F('nombre_titular');
	  document.getElementById("cedula_p_pago").value =$F('cedula_titular');
	  document.getElementById("telf_p_pago").value   =$F('telf_titular');
}

function datoscliente_tp1() {

	  document.getElementById("nombre_p_pago1").value =$F('nombre_titular');
	  document.getElementById("cedula_p_pago1").value =$F('cedula_titular');
	  document.getElementById("telf_p_pago1").value =$F('telf_titular');
	}

function datoscliente_tp2() {

	  document.getElementById("nombre_p_pago2").value =$F('nombre_titular');
	  document.getElementById("cedula_p_pago2").value =$F('cedula_titular');
	  document.getElementById("telf_p_pago2").value =$F('telf_titular');
	}

	function datoscliente_tp3() {

	  document.getElementById("nombre_p_pago3").value =$F('nombre_titular');
	  document.getElementById("cedula_p_pago3").value =$F('cedula_titular');
	  document.getElementById("telf_p_pago3").value =$F('telf_titular');
	}
	function datoscliente_tp4() {

	  document.getElementById("nombre_p_pago4").value =$F('nombre_titular');
	  document.getElementById("cedula_p_pago4").value =$F('cedula_titular');
	  document.getElementById("telf_p_pago4").value =$F('telf_titular');
	}
/*FIN*/







function mos_bus_tarjeta(req){

  $('buscar_tarjetas').innerHTML= req.responseText;

}


function guardar_factura()
{ Procesar=true;

	if($F('ult_controlfactura')>=$F('controlfactura') || $F('controlfactura')=='')
	{Procesar=false;
		alert('Verificar Nummero de Control');}

	if ((Field.present('controlfactura')==false) || (Field.present('dateField3')==false && $F('forma_pago')==1)
|| (Field.present('dateField3')==false && $F('forma_pago')==2 && Field.present('dateField5')==false)
|| ( $F('forma_pago')==2 && Field.present('dateField5')==false)
|| (Field.present('dateField3')==false && $F('forma_pago')==3 && Field.present('no_cheque')==false)
|| ( $F('forma_pago')==3 && Field.present('no_cheque')==false)
|| ( $F('forma_pago')==5 && Field.present('no_cheque')==false)
|| ( $F('forma_pago')==4 && Field.present('no_cheque')==false)
|| ( $F('forma_pago')==10 && Field.present('cedula_p_pago')==false)
|| ( $F('forma_pago')==11 && Field.present('correo_p_pago')==false)
|| ( $F('forma_pago')==0)  )
	{Procesar=false;
	alert ("Debe llenar los campos Obligatorios");
	}
	else
	{
		if(Procesar==true){
				new Ajax.Request("views04/reg_facturas2.php",
				{		method:'post',
						asynchronous: true,
						postBody:
						'dateField1='+$F('dateField1')+'&nombre_p_pago='+$F('nombre_p_pago')+'&cedula_p_pago='+$F('cedula_p_pago')+'&telf_p_pago='+$F('telf_p_pago')+'&correo_p_pago='+$F('correo_p_pago')+'&dateField2='+$F('dateField2')+'&proceso='+$F('proceso')+'&clave='+$F('clave')+'&planilla='+$F('planilla')+'&tipo_ente='+$F('tipo_ente')+'&entes='+$F('entes')+'&dateField3='+$F('dateField3')+'&dateField5='+$F('dateField5')+'&forma_pago='+$F('forma_pago')+'&banco='+$F('banco')+'&no_cheque='+$F('no_cheque')+'&concepto='+$F('concepto')+'&factura='+$F('factura')+'&serie='+$F('serie')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios')+'&nom_tarjeta='+$F('nom_tarjeta')+'&controlfactura='+$F('controlfactura')+'&monto='+$F('monto')+'&descuento='+$F('descuento')+'&partidas='+$F('partidas')+'&codigosap='+$F('codigosap')+'&tipo_moneda='+$F('tipo_moneda'),
						onComplete: mos_gua_fact
				});
				var1 = document.getElementById('buscar_procesosf');
				var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span class="titulos">Cargando...</span>';
		}
	}

	function mos_gua_fact(req)
	{

	$('buscar_procesosf').innerHTML= req.responseText;

	}
}


function guardar_factura2()
{

	if ((Field.present('controlfactura')==false) || (Field.present('dateField3')==false ))
	{
	alert ("Campo Numero de Control o  Campo Fecha Emision o Campo Fecha Credito o Campo Numero de Cheque debito tarjeta credito  Vacio debe Seleccionar la Fecha o campo forma de campo vacio");
	}
	else
	{
		
					
		new Ajax.Request("views04/reg_facturas2.php",
		{
		method:'post',
		asynchronous: true,
		postBody:
	
		'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&proceso='+$F('proceso')+'&clave='+$F('clave')+'&planilla='+$F('planilla')+'&tipo_ente='+$F('tipo_ente')+'&entes='+$F('entes')+'&dateField3='+$F('dateField3')+'&dateField5='+$F('dateField5')+'&forma_pago='+$F('forma_pago')+'&forma_pago1='+$F('forma_pago1')+'&banco1='+$F('banco1')+'&no_cheque1='+$F('no_cheque1')+'&tipo_moneda1='+$F('tipo_moneda1')+'&nombre_p_pago1='+$F('nombre_p_pago1')+'&cedula_p_pago1='+$F('cedula_p_pago1')+'&telf_p_pago1='+$F('telf_p_pago1')+'&correo_p_pago1='+$F('correo_p_pago1')+'&forma_pago2='+$F('forma_pago2')+'&banco2='+$F('banco2')+'&no_cheque2='+$F('no_cheque2')+'&tipo_moneda2='+$F('tipo_moneda2')+'&nombre_p_pago2='+$F('nombre_p_pago2')+'&cedula_p_pago2='+$F('cedula_p_pago2')+'&telf_p_pago2='+$F('telf_p_pago2')+'&correo_p_pago2='+$F('correo_p_pago2')+'&forma_pago3='+$F('forma_pago3')+'&banco3='+$F('banco3')+'&no_cheque3='+$F('no_cheque3')+'&tipo_moneda3='+$F('tipo_moneda3')+'&nombre_p_pago3='+$F('nombre_p_pago3')+'&cedula_p_pago3='+$F('cedula_p_pago3')+'&telf_p_pago3='+$F('telf_p_pago3')+'&correo_p_pago3='+$F('correo_p_pago3')+'&forma_pago4='+$F('forma_pago4')+'&banco4='+$F('banco4')+'&no_cheque4='+$F('no_cheque4')+'&tipo_moneda4='+$F('tipo_moneda4')+'&nombre_p_pago4='+$F('nombre_p_pago4')+'&cedula_p_pago4='+$F('cedula_p_pago4')+'&telf_p_pago4='+$F('telf_p_pago4')+'&correo_p_pago4='+$F('correo_p_pago4')+'&concepto='+$F('concepto')+'&factura='+$F('factura')+'&serie='+$F('serie')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios')+'&nom_tarjeta1='+$F('nom_tarjeta1')+'&nom_tarjeta2='+$F('nom_tarjeta2')+'&nom_tarjeta3='+$F('nom_tarjeta3')+'&nom_tarjeta4='+$F('nom_tarjeta4')+'&controlfactura='+$F('controlfactura')+'&monto1='+$F('monto1')+'&monto2='+$F('monto2')+'&monto3='+$F('monto3')+'&monto4='+$F('monto4')+'&monto='+$F('monto')+'&descuento='+$F('descuento')+'&partidas='+$F('partidas')+'&codigosap='+$F('codigosap'),
	onComplete: mos_gua_fact2
	  
		});
		var1 = document.getElementById('buscar_procesosf2'); 
		var1.innerHTML = '<img 						src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	}

	function mos_gua_fact2(req)
	{
	
	$('buscar_procesosf').innerHTML= req.responseText;

	}
}







function guar_fact_rec_pri()
{
registrar='1';
	
	if ((Field.present('controlfactura')==false) || (Field.present('concepto')==false) )
	{
	alert ("Campo Numero de Control o  Campo Concepto  Vacio debe Seleccionar la Fecha o campo forma de campo vacio");
	}
	else
	{
		
			
		new Ajax.Request("views04/reg_facturas2.php",
		{
		method:'post',
		asynchronous: true,
		postBody:
		'id_recibo_contrato='+$F('id_recibo_contrato')+'&dateField3='+$F('dateField3')+'&concepto='+$F('concepto')+'&factura='+$F('factura')+'&serie='+$F('serie')+'&controlfactura='+$F('controlfactura')+'&tprima='+$F('tprima')+'&registrar='+registrar,
	onComplete: mos_guar_fact_rec_pri
	  
		});
		var1 = document.getElementById('buscar_procesosf'); 
		var1.innerHTML = '<img 						src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	}

	function mos_guar_fact_rec_pri(req)
	{
	
	$('buscar_procesosf').innerHTML= req.responseText;

	}
}



function buscarfactura(){
	
	if ((Field.present('factura')==false))
	{
		alert ("Campos Numero de Factura Vacio");
		}
		else
		{
new Ajax.Request("views04/ver_facturas2.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'&factura='+$F('factura'),
	onComplete: mos_bus_fact
	  
    });
	var1 = document.getElementById('buscarfactura'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_fact(req){
	
  $('buscarfactura').innerHTML= req.responseText;

}
}

/*FORMAS DE PAGO ING PATRICIA 19-10-22*/
function modifpago() {

  new Ajax.Request("views04/mod_datos_pago.php",
    {
        method: 'post',
	asynchronous: true,
	postBody: 'factura='+$F('factura'),
	onComplete:mod_datos_pago
    });

  function mod_datos_pago(req){
	
  $('datos_pago').innerHTML= req.responseText;

}
}

function modifpago2(){
new Ajax.Request("views04/mod_datos_pago2.php",
    {
       method:'post',
       asynchronous: true,
       postBody:'forma_pago='+$F('forma_pago')+'&tipo_moneda='+$F('tipo_moneda')+'&monto_fac='+$F('monto_fac')+'&factura='+$F('factura')+'&id_factura='+$F('id_factura')+'&telf_titular='+$F('telf_titular')+'&cedula_titular='+$F('cedula_titular')+'&nombre_titular='+$F('nombre_titular'),
        onComplete: modif_datos_pago_fac

    });
         function modif_datos_pago_fac(req){
	
  $('modif_datos_pago_fac').innerHTML= req.responseText;

 }
}

function actualizar_pago(){

	if (    ($F('forma_pago')==2 && Field.present('dateField5')==false) 
          || ($F('forma_pago')==3 && Field.present('no_cheque')==false) 
          || ( $F('forma_pago')==5 && Field.present('no_cheque')==false)
          || ( $F('forma_pago')==4 && Field.present('no_cheque')==false)
          || ( $F('forma_pago')==10 && Field.present('cedula_p_pago')==false)
          || ( $F('forma_pago')==11 && Field.present('correo_p_pago')==false)
          || ( $F('forma_pago')==0)  )
	{
	alert ("Debe llenar los campos Obligatorios");
	}
	else
	{		


new Ajax.Request("views04/mod_datos_pago3.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'factura='+$F('factura')+'&monto_fac='+$F('monto_fac')+'&id_factura='+$F('id_factura')+'&forma_pago='+$F('forma_pago')+'&tipo_moneda='+$F('tipo_moneda')+'&nombre_p_pago='+$F('nombre_p_pago')+'&cedula_p_pago='+$F('cedula_p_pago')+'&telf_p_pago='+$F('telf_p_pago')+'&correo_p_pago='+$F('correo_p_pago')+'&dateField5='+$F('dateField5')+'&banco='+$F('banco')+'&no_cheque='+$F('no_cheque')+'&nom_tarjeta='+$F('nom_tarjeta'),
        onComplete: act_pago

    });
         function act_pago(req){
	
  $('final_datos_pago').innerHTML= req.responseText;

     }
 }
}

function actualizar_pago2(){


new Ajax.Request("views04/mod_datos_pago3.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'factura='+$F('factura')+'&id_factura='+$F('id_factura')+'&forma_pago='+$F('forma_pago')+'&forma_pago1='+$F('forma_pago1')+'&banco1='+$F('banco1')+'&nom_tarjeta1='+$F('nom_tarjeta1')+'&no_cheque1='+$F('no_cheque1')+'&tipo_moneda1='+$F('tipo_moneda1')+'&monto1='+$F('monto1')+'&nombre_p_pago1='+$F('nombre_p_pago1')+'&cedula_p_pago1='+$F('cedula_p_pago1')+'&telf_p_pago1='+$F('telf_p_pago1')+'&correo_p_pago1='+$F('correo_p_pago1')
       +'&forma_pago2='+$F('forma_pago2')+'&banco2='+$F('banco2')+'&nom_tarjeta2='+$F('nom_tarjeta2')+'&no_cheque2='+$F('no_cheque2')+'&tipo_moneda2='+$F('tipo_moneda2')+'&monto2='+$F('monto2')+'&nombre_p_pago2='+$F('nombre_p_pago2')+'&cedula_p_pago2='+$F('cedula_p_pago2')+'&telf_p_pago2='+$F('telf_p_pago2')+'&correo_p_pago2='+$F('correo_p_pago2')
       +'&forma_pago3='+$F('forma_pago3')+'&banco3='+$F('banco3')+'&nom_tarjeta3='+$F('nom_tarjeta3')+'&no_cheque3='+$F('no_cheque3')+'&tipo_moneda3='+$F('tipo_moneda3')+'&monto3='+$F('monto3')+'&nombre_p_pago3='+$F('nombre_p_pago3')+'&cedula_p_pago3='+$F('cedula_p_pago3')+'&telf_p_pago3='+$F('telf_p_pago3')+'&correo_p_pago3='+$F('correo_p_pago3')
       +'&forma_pago4='+$F('forma_pago4')+'&banco4='+$F('banco4')+'&nom_tarjeta4='+$F('nom_tarjeta4')+'&no_cheque4='+$F('no_cheque4')+'&tipo_moneda4='+$F('tipo_moneda4')+'&monto4='+$F('monto4')+'&nombre_p_pago4='+$F('nombre_p_pago4')+'&cedula_p_pago4='+$F('cedula_p_pago4')+'&telf_p_pago4='+$F('telf_p_pago4')+'&correo_p_pago4='+$F('correo_p_pago4'),
       
       onComplete: act_pago
    })
  
  function act_pago(req){	
  $('final_datos_pago').innerHTML= req.responseText;

     }
 }

/**/






function buscar_procesosmf(){
	if ((Field.present('proceso')==false) && (Field.present('clave')==false) && (Field.present('planilla')==false) && (Field.present('entes')==false))
	{
		alert ("Campos Vacios Haga la Busqueda llenando uno de los Campos si la Factura es por Ente debe Seleccionar las Fechas");
		}
		else
		{

new Ajax.Request("views04/ver_facturas3.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&proceso='+$F('proceso')+'&clave='+$F('clave')+'&planilla='+$F('planilla')+'&tipo_ente='+$F('tipo_ente')+'&entes='+$F('entes')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios')+'&cua_rec_prim='+$F('cua_rec_prim')+'&partidas='+$F('partidas'),
	onComplete: mos_bus_promf
	  
    });
	var1 = document.getElementById('buscar_procesosmf'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_promf(req){
	
  $('buscar_procesosmf').innerHTML= req.responseText;

}
}

function actualizar_factura()
{
		
	if ((Field.present('controlfactura')==false) || (Field.present('dateField3')==false))
	{
	alert ("Los campos No. de Control  y Fecha Emision deben estar llenos");
	}
	else
	{
		
			
		new Ajax.Request("views04/act_facturas.php",
		{
		method:'post',
		asynchronous: true,
		postBody:
		'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&proceso='+$F('proceso')+'&clave='+$F('clave')+'&planilla='+$F('planilla')+'&tipo_ente='+$F('tipo_ente')+'&entes='+$F('entes')+'&dateField3='+$F('dateField3')+'&concepto='+$F('concepto')+'&factura='+$F('factura')+'&serie='+$F('serie')+'&estado_fac='+$F('estado_fac')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios')+'&controlfactura='+$F('controlfactura')+'&cua_rec_prim='+$F('cua_rec_prim')+'&partidas='+$F('partidas')+'&codigosap='+$F('codigosap'),
	onComplete: mos_act_fact
	  
		});
		var1 = document.getElementById('buscarfactura'); 
		var1.innerHTML = '<img 						src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	}

	function mos_act_fact(req)
	{
	
	$('buscarfactura').innerHTML= req.responseText;

	}
}

///funcion para Modificar los datos de la factura-Edi 19-10-22 ING PATRICIA
function mod_edo_fact()
{error=0;
	Controlfactura=$F('controlfactura').trim();
	comentarioFactura=$F('comen_fact').trim();
		if(Controlfactura.length<6)
		{	error++;
			errorcampo('controlfactura');
			alert('El Numero de Control no debe de estar vacio o contener menos de 6 caracteres');
		}

		if(comentarioFactura.length<20)
		{	error++;
			errorcampo('comen_fact');
			alert('El comentario debe de ser mayor a 20 caracteres');
		}

		if(error==0){
				new Ajax.Request("views04/act_facturas1.php",
				{
				method:'post',
				asynchronous: true,
				postBody:
				'factura='+$F('factura')+'&serie='+$F('serie')+'&estado_fac='+$F('estado_fac')+'&comen_fact='+comentarioFactura+'&codigosap='+$F('codigosap')+'&controlfactura='+Controlfactura,
			onComplete: mos_mod_edo_fact
				});
				var1 = document.getElementById('buscar_procesosf');
				var1.innerHTML = '<img 						src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	}
		function mos_mod_edo_fact(req)
		{		$('buscarfactura').innerHTML= req.responseText;	}

}
///fin funcion para Modificar los datos de la factura
function buscarfacturaserie(){
	
	if ((Field.present('factura')==false))
	{
		alert ("Campos Numero de Factura Vacio");
		}
		else
		{
new Ajax.Request("views04/ver_edo_facturas2.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
		'factura='+$F('factura')+'&serie='+$F('serie'),
	onComplete: mos_buscarfacturaserie
	  
    });
	var1 = document.getElementById('buscarfacturaserie'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_buscarfacturaserie(req){
	
  $('buscarfacturaserie').innerHTML= req.responseText;

}
}
function mod_edo_fact1()
{
		
			
		new Ajax.Request("views04/act_facturas1.php",
		{
		method:'post',
		asynchronous: true,
		postBody:
		'factura='+$F('factura')+'&serie='+$F('serie')+'&estado_fac='+$F('estado_fac')+'&comen_fact='+$F('comen_fact')+'&controlfactura='+$F('controlfactura'),
	onComplete: mos_mod_edo_fact
	  
		});
		var1 = document.getElementById('buscarfacturaserie'); 
		var1.innerHTML = '<img 						src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	

	function mos_mod_edo_fact(req)
	{
	
	$('buscarfacturaserie').innerHTML= req.responseText;

	}
}
/* **** Verificar numero de de control de una factura   **** */

function verificar_numcontrol()
{
new Ajax.Request("views04/verificar_numcontrol.php",
    {
	
       method:'post',
       asynchronous: true,
       postBody:
	   
	'controlfactura='+$F('controlfactura')+'&serie='+$F('serie')+'&ult_controlfactura='+$F('ult_controlfactura'),
	onComplete: mos_verificar_numcontrol
	  
    });
	var1 = document.getElementById('bus_verificar_numcontrol'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_verificar_numcontrol(req){
	
  $('bus_verificar_numcontrol').innerHTML= req.responseText;
}

/* *** fin de verificar numero de de control de una factura *** */
/* **** fin de facturacion **** */


/* **** facturacion gobernacion**** */
function buscar_procesosfgob(){
	
	if (Field.present('tipo_ente')==false)
	{
		alert ("Campos Vacios Haga la Busqueda llenando uno de los Campos si la Factura es por Ente debe Seleccionar las Fechas");
		}
		else
		{

new Ajax.Request("views04/reg_facturas_gob1.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&tipo_ente='+$F('tipo_ente')+'&entes='+$F('ente')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios'),
	onComplete: mos_bus_profgob
	  
    });
	var1 = document.getElementById('buscar_procesosfgob'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_profgob(req){
	
  $('buscar_procesosfgob').innerHTML= req.responseText;

}
}

function guardar_facturagob()
{
	
	if ((Field.present('controlfactura')==false) || (Field.present('dateField3')==false && $F('forma_pago')==1) 
|| (Field.present('dateField3')==false && $F('forma_pago')==2 && Field.present('dateField5')==false) 
|| ( $F('forma_pago')==2 && Field.present('dateField5')==false) 
|| (Field.present('dateField3')==false && $F('forma_pago')==3 && Field.present('no_cheque')==false) 
|| ( $F('forma_pago')==3 && Field.present('no_cheque')==false) 
|| ( $F('forma_pago')==5 && Field.present('no_cheque')==false)
|| ( $F('forma_pago')==4 && Field.present('no_cheque')==false)
|| ( $F('forma_pago')==0)  )
	{
	alert ("Campo Numero de Control o  Campo Fecha Emision o Campo Fecha Credito o Campo Numero de Cheque debito tarjeta credito  Vacio debe Seleccionar la Fecha o campo forma de campo vacio");
	}
	else
	{
		
			
		new Ajax.Request("views04/reg_facturas_gob2.php",
		{
		method:'post',
		asynchronous: true,
		postBody:
		'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&tipo_ente='+$F('tipo_ente')+'&entes='+$F('ente')+'&dateField3='+$F('dateField3')+'&dateField5='+$F('dateField5')+'&forma_pago='+$F('forma_pago')+'&banco='+$F('banco')+'&no_cheque='+$F('no_cheque')+'&concepto='+$F('concepto')+'&factura='+$F('factura')+'&serie='+$F('serie')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios')+'&nom_tarjeta='+$F('nom_tarjeta')+'&controlfactura='+$F('controlfactura')+'&monto='+$F('monto'),
	onComplete: mos_gua_gob_fact
	  
		});
		var1 = document.getElementById('buscar_procesosfgob'); 
		var1.innerHTML = '<img 						src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	}

	function mos_gua_gob_fact(req)
	{
	
	$('buscar_procesosfgob').innerHTML= req.responseText;

	}
}



function guardar_facturagob2()
{

	if ((Field.present('controlfactura')==false) || (Field.present('dateField3')==false ))
	{
	alert ("Campo Numero de Control o  Campo Fecha Emision o Campo Fecha Credito o Campo Numero de Cheque debito tarjeta credito  Vacio debe Seleccionar la Fecha o campo forma de campo vacio");
	}
	else
	{
		
					
		new Ajax.Request("views04/reg_facturas_gob2.php",
		{
		method:'post',
		asynchronous: true,
		postBody:
	
		'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&tipo_ente='+$F('tipo_ente')+'&entes='+$F('ente')+'&dateField3='+$F('dateField3')+'&dateField5='+$F('dateField5')+'&forma_pago='+$F('forma_pago')+'&forma_pago1='+$F('forma_pago1')+'&banco1='+$F('banco1')+'&no_cheque1='+$F('no_cheque1')+'&forma_pago2='+$F('forma_pago2')+'&banco2='+$F('banco2')+'&no_cheque2='+$F('no_cheque2')+'&forma_pago3='+$F('forma_pago3')+'&banco3='+$F('banco3')+'&no_cheque3='+$F('no_cheque3')+'&forma_pago4='+$F('forma_pago4')+'&banco4='+$F('banco4')+'&no_cheque4='+$F('no_cheque4')+'&concepto='+$F('concepto')+'&factura='+$F('factura')+'&serie='+$F('serie')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios')+'&nom_tarjeta1='+$F('nom_tarjeta1')+'&nom_tarjeta2='+$F('nom_tarjeta2')+'&nom_tarjeta3='+$F('nom_tarjeta3')+'&nom_tarjeta4='+$F('nom_tarjeta4')+'&controlfactura='+$F('controlfactura')+'&monto1='+$F('monto1')+'&monto2='+$F('monto2')+'&monto3='+$F('monto3')+'&monto4='+$F('monto4')+'&monto='+$F('monto'),
	onComplete: mos_gua_gob_fact2
	  
		});
		var1 = document.getElementById('buscar_procesosf2'); 
		var1.innerHTML = '<img 						src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	}

	function mos_gua_gob_fact2(req)
	{
	
	$('buscar_procesosf').innerHTML= req.responseText;

	}
}
/* **** fin  facturacion gobernacion**** */

/* **** Notas de Creditos **** */


function buscarfacturacre(){
	
	if ((Field.present('factura')==false))
	{
		alert ("Campos Numero de Factura Vacio");
		}
		else
		{
new Ajax.Request("views04/reg_notacredito2.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'&factura='+$F('factura'),
	onComplete: mos_bus_fact_cre
	  
    });
	var1 = document.getElementById('buscarfacturacre'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_fact_cre(req){
	
  $('buscarfacturacre').innerHTML= req.responseText;

}
}

// GUARDAR NOTA CREDITO modificacion franklin monsalve 03-04-2023
function guardar_notacredito()
{
	id_factura=$F('id_factura');
  id_serie=$F('serie');
  ControlNotaC=$F('controlfactura').trim();//control del numero nota de debito
  UltimoControlFactura=$F('UltimoControlFactura').trim();//control del numero factura de la serie
  NumNotaCredito=$F('num_notacredito').trim();
  FechaNotaC=$F('dateField3').trim();
  concepto=$F('concepto').trim();
  montoTotalFactura=Number($F('TotalFacturado'));//monto facturado( monotFactura+NotaDebito-NotaCredito )
  montoFactura=Number($F('monto'));//monto en bs
  montoTotalCredi=Number($F('MontoTotal'));//trasformacion a bs y total a pagar
  TipoNotaCredito=$F('estado_cre').trim();
	montoFactura=Number(montoFactura.toFixed(2));//trasforamr a 2 decimales
	montoTotalCredi=Number(montoTotalCredi.toFixed(2));
	///validar si al restar el mono de credito no quede en negativo
	DeudaTotal=montoTotalFactura-montoTotalCredi;
	///validar datos
	  //datos no vacios
	  msj1='';
	  msj2='';
	  msj3='';
	  msj4="";
	  if ((Field.present('controlfactura')==false))
		  { Validarcrt=false;
	      msj1+="\n Campo Numero de Control Estan Vacio";
	    }
	    else{Validarcrt=true;
	      //validar que el numero de control sea mayor al ultimo de la series
	      UltimoCrt=Number(UltimoControlFactura);
	      CrtNuevo=Number(ControlNotaC);
	      if(CrtNuevo <= UltimoCrt)
	      {Validarcrt=false;
	        msj1+="\n El numero de control debe ser mayor al ultimo";
	      }
	    }

		if($F('concepto').length<10)
		  { Validarc=false;
	      msj1+="\n Campo concepto debe tener un minimo de 10 caracteres";
	    }
	    else{Validarc=true;}
	  //validar fecha
	  if (validarMesAn(FechaNotaC)==false)
	    {Validarf=false;
	      msj1+="\n La fecha no es valida, verifique e intente de nuevo";
	    }
	    else{Validarf=true;}
	  //validar monto mayor a 0
	  if (montoTotalCredi<=0 || isNaN(montoTotalCredi))
	    {Validarm=false;
	    msj1+="\n El monto no es valido, verifique e intente de nuevo";
	    }else
	      {Validarm=true; }
	  //validar Que el mono no exeda la factura
	  if (montoFactura<montoTotalCredi)
	    {Validarm2=false;
	    msj1+="\n El monto total no puede ser mayor al facturado, verifique e intente de nuevo";
	    }else
	      {Validarm2=true; }
	  //validar Que la Deuda no este en negativo
	  if (DeudaTotal<0)
	    {Validarm2=false; alert('Total Deuda:'+montoTotalFactura+' - '+montoTotalCredi+' = '+DeudaTotal)
	    msj1+="\n El monto de la nota es superior a la deuda, verifique e intente de nuevo";
	    }else
	      {Validarm2=true; }
	    if(Validarcrt==false || Validarc==false || Validarf==false || Validarm==false || Validarm2==false){
	            alert("Error:"+msj1+"");
	            Validar=false;
	        }else{Validar=true;}

	if(Validar==true){
		new Ajax.Request("views04/reg_notacredito3.php",
		{
				method:'post',
				asynchronous: true,
				postBody:
				'id_factura='+id_factura+'&dateField3='+FechaNotaC+'&concepto='+concepto+'&controlfactura='+ControlNotaC+'&num_notacredito='+NumNotaCredito+'&monto='+montoTotalCredi+'&estado_cre='+TipoNotaCredito+'&idserie='+id_serie,
				onComplete: mos_gua_nota_credito
		});
		var1 = document.getElementById('buscarfacturacre');
		var1.innerHTML = '<img 						src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	}

	function mos_gua_nota_credito(req)
	{
		$('buscarfacturacre').innerHTML= req.responseText;
	}

}
// FIN GUARDAR NOTA CREDITO modificacion franklin monsalve 03-04-2023
// FIN GUARDAR NOTA CREDITO modificacion franklin monsalve 03-04-2023
function buscarnotafactura(){
		TipoNota=$F('TipoNota');
		NumNota=$F('NunNota');
		if ((Field.present('NunNota')==false))
			{
				alert ("Campo Numero de Nota de Credito Vacio");
			}
			else
			{
				new Ajax.Request("views04/ver_notafactura2.php",
	    {  method:'post',
	       asynchronous: true,
	       postBody:
				 '&numnota='+NumNota+'&tiponota='+TipoNota,
				 onComplete: mos_bus_nota_factura
	    });
			var1 = document.getElementById('buscarnota');
			var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		}

		function mos_bus_nota_factura(req){
		  $('buscarnota').innerHTML= req.responseText;
		}
}
//franklin monsalve 10-04-2023

function buscarfacturanc(){
	
	if ((Field.present('factura')==false))
	{
		alert ("Campo Numero de Nota de Credito Vacio");
		}
		else
		{
new Ajax.Request("views04/ver_notacredito3.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'&facturanueva='+$F('facturanueva'),
	onComplete: mos_bus_f_nota_credito
	  
    });
	var1 = document.getElementById('buscarfacturanc'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_f_nota_credito(req){
	
  $('buscarfacturanc').innerHTML= req.responseText;

}
}


function actualizar_notacredito()
{
		
	if ((Field.present('controlfactura')==false))
	{
	alert ("Campo Numero Control de la Nota de Credito esta Vacio");
	}
	else
	{
		
			
		new Ajax.Request("views04/act_notacredito.php",
		{
		method:'post',
		asynchronous: true,
		postBody:
		'id_notacredito='+$F('id_notacredito')+'&controlfactura='+$F('controlfactura')+'&dateField3='+$F('dateField3')+'&estado_fac='+$F('estado_fac')+'&concepto='+$F('concepto')+'&id_fact_nueva='+$F('id_fact_nueva')+'&monto='+$F('monto')+'&factura='+$F('factura'),
	onComplete: mos_act_notacredito
	  
		});
		var1 = document.getElementById('buscarfacturacre'); 
		var1.innerHTML = '<img 						src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	}

	function mos_act_notacredito(req)
	{
	
	$('buscarnotacredito').innerHTML= req.responseText;

	}
}


/* **** fin de notas de credito **** */
///LISTA DE NOTAS DE FACTURA PARAMETROS franklin Monsalve
function listanotafactura(){
        var contenedor;
	contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
	ajax.open("GET", "views04/listanotafactura.php",true);
	ajax.onreadystatechange=function() {
	   if (ajax.readyState==4){
	      contenedor.innerHTML = ajax.responseText
	      }
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}
///LISTA DE NOTAS DE FACTURA
function listanotafactura2()
{

	if ((Field.present('dateField1')==false) || (Field.present('dateField2')==false))
	{
		alert ("Campo Numero Fecha de la Nota esta Vacio");
	}
	else
	{	fechini=$F('dateField1');
		fechfin=$F('dateField2');
		tiponota=$F('TipoNota');

		new Ajax.Request("views04/listanotafactura2.php",
		{
		method:'post',
		asynchronous: true,
		postBody:
		'fechaIni='+fechini+'&fechaFin='+fechfin+'&tiponota='+tiponota,
	onComplete: mos_listanota

		});
		var1 = document.getElementById('listaNotasFactura');
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	}

	function mos_listanota(req)
	{

	$('listaNotasFactura').innerHTML= req.responseText;

	}
}
////MOSTRAR NOTA FACTURA DESDE EL LISTADO
function verNotaFacturaLista(tpNotaF,NotaFact){
		TipoNota=tpNotaF;
		NumNota=NotaFact;
		if (NumNota=='' || NumNota==0)
			{
				alert ("Campo Numero de Nota de Credito Vacio");
			}
			else
			{
				new Ajax.Request("views04/ver_notafactura.php",
	    {  method:'post',
	       asynchronous: true,
	       postBody:
				 '&numnota='+NumNota+'&tiponota='+TipoNota,
				 onComplete: mos_ver_nota_factura
	    });
			var1 = document.getElementById('buscarnota');
			var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		}

		function mos_ver_nota_factura(req){
		  $('clientes').innerHTML= req.responseText;
		}
}
//franklin monsalve 10-04-2023


/* **** fin de notas de credito **** */

/* **** realizacion de cheques reembolso **** */
function bus_che_reem()
{
	
	if (Field.present('cedula')==false)
	{
		alert ("El Numero de Cedula es Obligatorio");
		}
		else
		{
new Ajax.Request("views04/bus_che_reem.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'cedula='+$F('cedula')+'&ente='+$F('ente'),
	onComplete: mos_bus_reem
	  
    });
	var1 = document.getElementById('bus_che_reem'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_reem(req){
	
  $('bus_che_reem').innerHTML= req.responseText;

}
}


function gua_che_reem()
{
	if (Field.present('cedula')==false || Field.present('monto')==false || ($F('banco')!=8 &&  Field.present('numcheque')==false))
	{
		alert ("El Numero de Cedula o el Monto esta Vacio o Seleciono 	algun Banco y no Coloco el Numero de Cheque");
	}
	else
	{
			
			
			proceso1="";
			honorarios1="";
			servicio1="";
			factura1="";
			j=0;
			z=0;
			
			contador=$F('conexa');
			for(var i=0; i<contador; i++) 
				
			{
				j++;
				formexa = document.getElementById("check_"+[j]);
				if(formexa.checked) 
					
				{
					z++;		
				proceso=document.getElementById("proceso_"+[j]);
				honorarios=document.getElementById("honorarios_"+[j]);
				servicio=document.getElementById("servicio_"+[j]);
				factura=document.getElementById("factura_"+[j]);
							
				proceso1 +="@"+proceso.value;
				honorarios1 +="@"+honorarios.value;
				servicio1 +="@"+servicio.value;
				factura1 +="@"+factura.value;
			
				
				}
			}
			if (z>0)
			{

new Ajax.Request("views04/gua_che_reem.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	'cedula='+$F('nombre')+'&conexa='+$F('conexa')+'&proceso1='+proceso1+'&honorarios1='+honorarios1+'&banco='+$F('banco')+'&servicio1='+servicio1+'&numcheque='+$F('numcheque')+'&factura1='+factura1+'&ente='+$F('ente'),
	onComplete: mos_gua_che_reem
	
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		
		}
		else
		{
		alert ("Debes Seleccionar algun proceso");
		}
	}
	function mos_gua_che_reem(req)
{

  $('clientes').innerHTML= req.responseText;
			
}

}

/* **** fin de realizacion de cheques reembolso**** */



/* **** realizacion de cheques proveedores **** */
function bus_provp(i)
{
new Ajax.Request("views04/provp.php",
    {
	
       method:'post',
       asynchronous: true,
       postBody:
	   
	'prov='+$F('prov')+'&codigomas='+$F('codigomas')+'&boton='+i,
	onComplete: mos_bus_che_prov
	  
    });
	var1 = document.getElementById('bus_che_pro'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_che_prov(req){
	
  $('bus_che_pro').innerHTML= req.responseText;
}

/* **** realizacion de cheques proveedores **** */
function bus_provp(i)
{
new Ajax.Request("views04/provp.php",
    {

       method:'post',
       asynchronous: true,
       postBody:

	'prov='+$F('prov')+'&codigomas='+$F('codigomas')+'&boton='+i,
	onComplete: mos_bus_che_prov

    });
	var1 = document.getElementById('bus_che_pro');
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_che_prov(req){

  $('bus_che_pro').innerHTML= req.responseText;
}


function bus_che_prov()
{

	if (Field.present('dateField1')==false || Field.present('dateField2')==false )
		{
			alert ("Los Campos Estan Vacios Debe Seleccionarlos");
		}
	else
		{
			new Ajax.Request("views04/bus_che_prov.php",
    {
       method:'post',
       asynchronous: true,
       postBody:


		'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&proveedor='+$F('proveedor')+'&prov='+$F('prov')+'&des_gasto='+$F('des_gasto'),
	onComplete: mos_bus_che_prov1

    });
	var1 = document.getElementById('bus_che_prov');
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_che_prov1(req){

  $('bus_che_prov').innerHTML= req.responseText;

}
}

///AGREGAR OTROS ITESN PARA FACTURAS CHEQUES PROVEDORESE
function bus_che_prov_AgregarFacturas()
{ Cuantos=$F(conexa);
	Cunt=parseInt(Cuantos);
	Cunt++;
	$(conexa).value=Cunt;
if(Cuantos<5)
{alert('pendiente')}
else{
	new Ajax.Updater('masCampos', 'views04/agregar_fact_prov.php', {
	               method: 'post',
								 parameters: { cuantos: Cuantos },
	               insertion: Insertion.Bottom
	            });

	}

}


function gua_che_prov()
{
	if (Field.present('monto')==false || ($F('banco')!=8 &&  Field.present('numcheque')==false) || ($F('banco')!=8 && $F('banco')!=13 &&  $F('numcheque')==0))
	{
		alert ("El Monto esta Vacio o Selecciono 	algun Banco y no Coloco el Numero de Cheque");
	}
	else
	{
		
		
			honorarios1="";
			factura1="";
			idordcom1="";
			montoexento1="";
			baseimponible1="";
			iva_fact1="";
			iva_ret1="";
			confactura1="";
			fecha_emision1="";
			j=0;
			z=0;
			
			contador=$F('conexa');
			for(var i=0; i<contador; i++) 
				
			{
				j++;
				formexa = document.getElementById("check_"+[j]);
				if(formexa.checked) 
					
				{
					z++;	
				
				honorarios=document.getElementById("honorarios_"+[j]);
				factura=document.getElementById("factura_"+[j]);
				idordcom=document.getElementById("idordcom_"+[j]);		
				montoexento=document.getElementById("montoexento_"+[j]);
				baseimponible=document.getElementById("baseimponible_"+[j]);
				iva_fact=document.getElementById("iva_fact_"+[j]);		
				iva_ret=document.getElementById("iva_ret_"+[j]);	
				confactura=document.getElementById("confactura_"+[j]);		
				fecha_emision=document.getElementById("fecha_emision_"+[j]);	
			
				honorarios1 +="@"+honorarios.value;
				factura1 +="@"+factura.value;
				idordcom1 +="@"+idordcom.value;
				montoexento1 +="@"+montoexento.value;
				baseimponible1 +="@"+baseimponible.value;
				iva_fact1 +="@"+iva_fact.value;
				iva_ret1 +="@"+iva_ret.value;
				confactura1 +="@"+confactura.value;
				fecha_emision1 +="@"+fecha_emision.value;
				
				}
			}
			if (z>0)
			{
					
new Ajax.Request("views04/gua_che_prov.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	'proveedor='+$F('proveedor')+'&conexa='+$F('conexa')+'&honorarios1='+honorarios1+'&banco='+$F('banco')+'&numcheque='+$F('numcheque')+'&factura1='+factura1+'&idordcom1='+idordcom1+'&rif='+$F('rif')+'&montoexento1='+montoexento1+'&baseimponible1='+baseimponible1+'&iva_fact1='+iva_fact1+'&iva_ret1='+iva_ret1+'&prov='+$F('prov')+'&nombreprov='+$F('nombreprov')+'&confactura1='+confactura1+'&fecha_emision1='+fecha_emision1+'&direccionprov='+$F('direccionprov')+'&anombrede='+$F('anombrede')+'&cedularif='+$F('cedularif')+'&motivo='+$F('motivo')+'&tipocuenta='+$F('tipocuenta')+'&iva_rettt='+$F('iva_rettt')+'&codigomas='+$F('codigomas'),
	onComplete: mos_gua_che_prov
	
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		
		}
		else
		{
		alert ("Debes Seleccionar alguna Factura Para Cancelar");
		}
	}
	function mos_gua_che_prov(req)
{

  $('clientes').innerHTML= req.responseText;
			
}

}

function gua_che_prov_cli()
{
	if (Field.present('monto')==false || ($F('banco')!=8 &&  Field.present('numcheque')==false) || ($F('banco')!=8 && $F('banco')!=13 &&  $F('numcheque')==0))
	{
		alert ("El Monto esta vacio o selecciono algun Banco y no Coloco el Numero de Cheque");
	}
	else
	{   
       	motivo_ret=$F('motivo').trim();

		if(motivo_ret.length<15)
		   { alert('Debe llenar el motivo');}
			
			else{
			procesos1="";
			factura1="";
			controlfactura1="";
			idservicios1="";
			honorarios_medicos1="";
			gastos_clinicos1="";
			ret1="";
			iva1="";
			retiva1="";
			tipo_documento1="";
			fac_afectada1="";
			
			fecha_emision1="";
			j=0;
			z=0;
			checkact=false;

			contador=$F('conexa');
			for(var i=0; i<contador; i++) 
				
			{
				j++;
				formexa = document.getElementById("check_"+[j]);
				if(formexa.checked) 
					
				{
					z++;	
				    

				procesos=document.getElementById("proceso_"+[j]);
				factura=document.getElementById("factura_"+[j]);
				controlfactura=document.getElementById("controlfactura_"+[j]);
				idservicios=document.getElementById("idservicio_"+[j]);		
				honorarios_medicos=document.getElementById("honorarios_medicos_"+[j]);
				gastos_clinicos=document.getElementById("gastos_clinicos_"+[j]);
				ret=document.getElementById("ret_"+[j]);		
				iva=document.getElementById("iva_"+[j]);		
				retiva=document.getElementById("retiva_"+[j]);	
				fecha_emision=document.getElementById("fecha_emision_"+[j]);	
				tipo_documento=document.getElementById("tipo_documento_"+[j]);	
				fac_afectada=document.getElementById("fac_afectada_"+[j]);
			    
			  
				  if(factura.value.length<4||factura.value.length==0){
			    	checkact=true;
			    	alert("Revisar campo Factura");
			    }else{

				      if(controlfactura.value.length<=5||controlfactura.value.length==0){
				    	checkact=true;
				    	alert("Revisar campo N° de Control");}
				     else{

					      if(fecha_emision.value.length==0){
					    	checkact=true;
					    	alert("Revisar campo Fecha de emisión");}
			          }
			    
			    }


				procesos1 +="@"+procesos.value;
				factura1 +="@"+factura.value;
				controlfactura1 +="@"+controlfactura.value;
				idservicios1 +="@"+idservicios.value;
				honorarios_medicos1 +="@"+honorarios_medicos.value;
				gastos_clinicos1 +="@"+gastos_clinicos.value;
				ret1 +="@"+ret.value;
				iva1 +="@"+iva.value;
				retiva1 +="@"+retiva.value;
				fecha_emision1 +="@"+fecha_emision.value;
				tipo_documento1 +="@"+tipo_documento.value;
				fac_afectada1 +="@"+fac_afectada.value;




				}
			}
			if(z>0 && checkact==false)
			{  

new Ajax.Request("views04/gua_che_prov_cli.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	'proveedor='+$F('proveedor')+'&conexa='+$F('conexa')+'&ret_indi='+$F('ret_indi')+'&procesos1='+procesos1+'&banco='+$F('banco')+'&numcheque='+$F('numcheque')+'&factura1='+factura1+'&controlfactura1='+controlfactura1+'&idservicios1='+idservicios1+'&rif='+$F('rif')+'&honorarios_medicos1='+honorarios_medicos1+'&gastos_clinicos1='+gastos_clinicos1+'&ret1='+ret1+'&iva1='+iva1+'&retiva1='+retiva1+'&tipo_documento1='+tipo_documento1+'&fac_afectada1='+fac_afectada1+'&prov='+$F('prov')+'&nombreprov='+$F('nombreprov')+'&fecha_emision1='+fecha_emision1+'&direccionprov='+$F('direccionprov')+'&total_iva='+$F('total_iva')+'&anombrede='+$F('anombrede')+'&cedularif='+$F('cedularif')+'&personaprov='+$F('personaprov')+'&total_ret='+$F('total_ret')+'&motivo='+$F('motivo')+'&tipocuenta='+$F('tipocuenta')+'&codigomas='+$F('codigomas')+'&id_variable='+$F('id_variable'),
	onComplete: mos_gua_che_prov_cli

	
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		
		}
		else
		{
		alert ("Debes Seleccionar alguna Factura Para Cancelar");
		}
	}
	function mos_gua_che_prov_cli(req)
{

  $('clientes').innerHTML= req.responseText;
			
   }
  }
}


/* **** registrar pagos proveedores otros **** */
function reg_pagos2(){
	
	
new Ajax.Request("views04/reg_pago2.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'id_proveedor='+$F('id_proveedor'),
	onComplete: mos_reg_pagos
	  
    });
	var1 = document.getElementById('reg_pagos2'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';


function mos_reg_pagos(req){
	
  $('reg_pagos2').innerHTML= req.responseText;

}
}




/* **** cambiar variable retencion iva **** */
function act_var_glo()
{
new Ajax.Request("views04/act_var_glo.php",
    {
	
       method:'post',
       asynchronous: true,
       postBody:
	   
	'variable='+$F('variableretiva')+'&id_variable='+$F('id_variableretiva'),
	onComplete: mos_act_var_glo
	  
    });
	var1 = document.getElementById('act_var_glo'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_act_var_glo(req){
	
  $('act_var_glo').innerHTML= req.responseText;
}

/* **** fin de cambiar variable retencion iva**** */

/* **** cambiar variable iva **** */
function act_var_iva()
{
new Ajax.Request("views04/act_var_iva.php",
    {
	
       method:'post',
       asynchronous: true,
       postBody:
	   
	'variable='+$F('variva')+'&id_variable='+$F('id_variva'),
	onComplete: mos_act_var_iva
	  
    });
	var1 = document.getElementById('act_var_iva'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_act_var_iva(req){
	
  $('act_var_iva').innerHTML= req.responseText;
}

/* **** fin de cambiar variable iva**** */


/* **** fin cheques proveedores**** */

/* **** buscar cheques o recibos**** */
function bus_che_rec()
{
	
	if (Field.present('cheque')==false)
	{
		alert ("El Numero de Cheque es Obligatorio");
		}
		else
		{
new Ajax.Request("views04/bus_che_rec1.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'cheque='+$F('cheque')+'&banco='+$F('banco')+'&tipo_cheque='+$F('prov')+'&proveedor='+$F('proveedor'),
	onComplete: mos_bus_rec
	  
    });
	var1 = document.getElementById('bus_che_rec'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_rec(req){
	
  $('bus_che_rec').innerHTML= req.responseText;

}
}



function anu_che_reem()
{
	
	if (Field.present('cheque')==false || $F('motivo')==false)
	{
		alert ("El Numero de Cheque es Obligatorio y el motivo");
		}
		else
		{
new Ajax.Request("views04/anu_che_reem.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'codigo='+$F('codigo')+'&motivo='+$F('motivo'),
	onComplete: mos_bus_anu_rec
	  
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_anu_rec(req){
	
  $('clientes').innerHTML= req.responseText;

}
}


function anu_che_prov()
{
	
	if (Field.present('cheque')==false || $F('motivo')==false)
	{
		alert ("El Numero de Cheque es Obligatorio y el motivo");
		}
		else
		{
new Ajax.Request("views04/anu_che_prov.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'codigo='+$F('codigo')+'&motivo='+$F('motivo')+'&numerocheque='+$F('numerocheque')+'&id_banco='+$F('id_banco'),
	onComplete: mos_bus_anu_chepro
	  
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_anu_chepro(req){
	
  $('clientes').innerHTML= req.responseText;

}
}


function anu_che_prov1()
{
	
	if (Field.present('cheque')==false || $F('motivo')==false)
	{
		alert ("El Numero de Cheque es Obligatorio y el motivo");
		}
		else
		{
new Ajax.Request("views04/anu_che_prov1.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'codigo='+$F('codigo')+'&motivo='+$F('motivo')+'&numerocheque='+$F('numerocheque')+'&id_banco='+$F('id_banco'),
	onComplete: mos_bus_anu_chepro1
	  
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_anu_chepro1(req){
	
  $('clientes').innerHTML= req.responseText;

}
}

function anu_che_prov2(i)
{
	
	if (Field.present('numcheque_'+i)==false || $F('motivo_'+i)==false)
	{
		alert ("El Numero de Cheque es Obligatorio y el motivo");
		}
		else
		{
new Ajax.Request("views04/anu_che_prov2.php",
    {
       method:'post',
       asynchronous: true,
       postBody:

	
		'codigo='+$F('codigo_'+i)+'&motivo='+$F('motivo_'+i),
	onComplete: mos_bus_anu_chepro2
	  
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_anu_chepro2(req){
	
  $('clientes').innerHTML= req.responseText;

}
}


function act_num_recche()
{
	
	if (Field.present('cheque')==false)
	{
		alert ("El Numero de Cheque es Obligatorio y el motivo");
		}
		else
		{
new Ajax.Request("views04/act_num_recche.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'codigo='+$F('codigo')+'&actnumche='+$F('actnumche'),
	onComplete: mos_bus_actnumche
	  
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_actnumche(req){
	
  $('clientes').innerHTML= req.responseText;

}
}

/* **** fin de buscar cheques o recibos**** */


/* **** buscar codigo para eliminarle un gasto siempre y cuando no tenga cheque generado**** */




function bus_codigo1()
{
	
	if (Field.present('codigo')==false)
	{
		alert ("El Numero de Codigo Obligatorio y el motivo");
		}
		else
		{
new Ajax.Request("views04/bus_codigo1.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'codigo='+$F('codigo'),
	onComplete: mos_bus_codigo
	  
    });
	var1 = document.getElementById('bus_codigo1'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_codigo(req){
	
  $('bus_codigo1').innerHTML= req.responseText;

}
}

function quitar_gasto3(i)
{
	eliminar='1';
	if (Field.present('factura_'+i)==false || $F('codigo')==false)
	{
		alert ("El Numero de Codigo es Obligatorio y el motivo");
		}
		else
		{
new Ajax.Request("views04/bus_codigo1.php",
    {
       method:'post',
       asynchronous: true,
       postBody:

	
		'codigo='+$F('codigo')+'&factura='+$F('factura_'+i)+'&proceso='+$F('proceso_'+i)+'&eliminar='+eliminar,
	onComplete: mos_bus_factura2
	  
    });
	var1 = document.getElementById('bus_codigo1'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_factura2(req){
	
  $('bus_codigo1').innerHTML= req.responseText;

}
}

function mod_fec_emi(i)
{
modificar='1';
	if ($F('codigo')==false)
	{
		alert ("El Numero de Codigo es Obligatorio y el motivo");
		}
		else
		{
new Ajax.Request("views04/bus_codigo1.php",
    {
       method:'post',
       asynchronous: true,
       postBody:

	
		'codigo='+$F('codigo')+'&factura='+$F('factura_'+i)+'&fechaemi='+$F('fechaemi_'+i)+'&modificar='+modificar,
	onComplete: mos_bus_mod_fec_emi
	  
    });
	var1 = document.getElementById('bus_codigo1'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_mod_fec_emi(req){
	
  $('bus_codigo1').innerHTML= req.responseText;

}
}

/* **** buscar proceso de donativo para actualizar el tipo de donativo**** */

function act_orden_donativo()
{
	
	if (Field.present('proceso')==false)
	{
		alert ("El Numero de proceso Obligatorio y el motivo");
		}
		else
		{
new Ajax.Request("views01/act_orden_donativo.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'proceso='+$F('proceso')+'&donativo='+$F('donativo'),
	onComplete: mos_act_orden_donativo
	  
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_act_orden_donativo(req){
	
  $('clientes').innerHTML= req.responseText;

}
}
/* **** buscar proceso de donativo para actualizar el tipo de donativo**** */

/* **** guardar cheque a varios codigos de control de comprobante **** */
function guar_varios_che()
{
	if (Field.present('numchequeasig')==false || Field.present('motivoasig')==false)
	{
		alert ("El Numero de Cheque o el Motivo esta Vacio");
	}
	else
	{
						
			codigo1="";
			
			j=0;
			z=0;
			
			contador=$F('conche');
			for(var i=0; i<contador; i++) 
				
			{
		
				j++;
				formexa = document.getElementById("check_"+[j]);
				if(formexa.checked) 
					
				{
					z++;		 
				codigo=document.getElementById("codigo_"+[j]);
											
				codigo1 +="@"+codigo.value;
				
			
				
				}
			}
			if (z>0)
			{

new Ajax.Request("views04/gua_che_varios.php",
    {

       method:'post',
       asynchronous: true,
       postBody:
	'numchequeasig='+$F('numchequeasig')+'&bancoasig='+$F('bancoasig')+'&tipocuentaasig='+$F('tipocuentaasig')+'&motivoasig='+$F('motivoasig')+'&conche='+$F('conche')+'&proveedor='+$F('proveedor')+'&codigo1='+codigo1,
	onComplete: mos_varios_che
	
    });
	var1 = document.getElementById('bus_che_rec'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		
		}
		else
		{
		alert ("Debes Seleccionar algun Pago del Proveedor");
		}
	}
	function mos_varios_che(req)
{

  $('bus_che_rec').innerHTML= req.responseText;
			
}

}

/* **** fin de guardar cheque a varios codigos de control de comprobante **** */



/* **** Imprimir cheques de proveedores compras en estado cheques por generar**** */

function imp_che_ge(i){
	codigo=$F('codigo_'+i);
	banco=$F('banco_'+i);
	motivo=$F('motivo_'+i);
	numcheque=$F('numcheque_'+i);
	nombreprov=$F('nombreprov_'+i);
	tipocuenta=$F('tipocuenta_'+i);
	id_proveedor=$F('id_proveedor_'+i);
	cedula=$F('cedula_'+i);
	fechaemision=$F('fechaemision_'+i);
	prov=3;
	mod=1;
	
url='views04/icheque_prov.php?codigo='+codigo+'&banco='+banco+'&prov='+prov+'&cedula='+cedula+'&id_proveedor='+id_proveedor+'&nombreprov='+nombreprov+'&mod='+mod+'&numcheque='+numcheque+'&motivo='+motivo+'&tipocuenta='+tipocuenta+'&fechaemision='+fechaemision;
  	imprimir(url);
}

/* **** fin  de Imprimir cheques en estado cheques por generar **** */

/* **** Imprimir cheques de proveedores medicos clinicas otros en estado cheques por generar**** */

function imp_che_gemco(i){
	


	codigo=$F('codigo_'+i);
	banco=$F('banco_'+i);
	motivo=$F('motivo_'+i);
	numcheque=$F('numcheque_'+i);
	id_proveedor=$F('id_proveedor_'+i);
	tipocuenta=$F('tipocuenta_'+i);
	personaprov=$F('personaprov_'+i);

	cedula=$F('cedula_'+i);
	fechaemision=$F('fechaemision_'+i);
	comproretiva=$F('comproretiva_'+i);

	prov=$F('prov');
	mod=1;
	
url='views04/icheque_prov_islr.php?codigo='+codigo+'&banco='+banco+'&prov='+prov+'&cedula='+cedula+'&id_proveedor='+id_proveedor+'&mod='+mod+'&numcheque='+numcheque+'&motivo='+motivo+'&tipocuenta='+tipocuenta+'&personaprov='+personaprov+'&fechaemision='+fechaemision+'&comproretiva='+comproretiva;
  	imprimir(url);
}

/* **** fin  de Imprimir cheques proveedores medicos clinicas y otros en estado cheques por generar **** */











/* **** trasladar permisos de un usuario a otro **** */




function dup_permisos2()
{
	
	if (Field.present('usuario1')==false || Field.present('usuario2')==false)
	{
		alert ("Debe Seleccionar los dos Usuarios");
		}
		else
		{
new Ajax.Request("views07/duplicar_permisos2.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'usuario1='+$F('usuario1')+'&usuario2='+$F('usuario2'),
	onComplete: mos_bus_dupli_permisos
	  
    });
	var1 = document.getElementById('dupli_permisos'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}
function mos_bus_dupli_permisos(req){
	
  $('dupli_permisos').innerHTML= req.responseText;

}
}

/* **** fin de trasladar permisos de un usuario a otro **** */



/* **** registrar fechas para control de dias no laborables en citas medicas  **** */
function reg_fechas1(){
	var paso;
	paso="1";
	if ((Field.present('mesini')==false) && (Field.present('mesfinal')==false) && (Field.present('diaini')==false) && (Field.present('diafinal')==false))
	{
		alert ("Campos Vacios Todos son Obligatorios llenando uno de los Campos");
		}
		else
		{
		
new Ajax.Request("views07/reg_fechas.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	'mesini='+$F('mesini')+'&mesfinal='+$F('mesfinal')+'&diaini='+$F('diaini')+'&diafinal='+$F('diafinal')+'&tipo_fecha='+$F('tipo_fecha')+'&medico='+$F('medico')+'&comentario='+$F('comentario')+'&paso='+(paso),
	onComplete: reg_con_fec
	  
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}
function reg_con_fec(req){
	
  $('clientes').innerHTML= req.responseText;

}
}


function eli_fechas(){
	var paso;
	paso="2";
	
	if (Field.present('eliminar')==false)
	{
		alert ("Campo Vacio Para Eliminar un Fecha debe colocar un id_fecha ");
		}
		else
		{
		
new Ajax.Request("views07/reg_fechas.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	'eliminar='+$F('eliminar')+'&paso='+(paso),
	onComplete: reg_con_fec
	  
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}
function reg_con_fec(req){
	
  $('clientes').innerHTML= req.responseText;

}
}



/* **** fin de control de fechas **** */



/* **** buscar el cliente modulo ordenes particular **** */

function reg_orden_part1(i){
condicion=i;
new Ajax.Request("views01/reg_orden_part1.php",
    {
       method:'post',
       asynchronous: true,
       postBody:


                'cedula='+$F('cedula')+'&condicion='+condicion,
        onComplete: mos_bus_reg_orden_part

    });
        var1 = document.getElementById('reg_orden_part');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_reg_orden_part(req){

  $('reg_orden_part').innerHTML= req.responseText;

}

/* **** fin de buscar el cliente modulo ordenes particular **** */

/* **** registrar el titular cliente modulo ordenes particular **** */

function reg_orden_part2(i){
condicion=i;
new Ajax.Request("views01/reg_orden_part1.php",
    {
       method:'post',
       asynchronous: true,
       postBody:


                'cedula='+$F('cedula')+'&id_cliente='+$F('id_cliente')+'&nombret='+$F('nombret')+'&apellidot='+$F('apellidot')+'&telefonot='+$F('telefonot')+'&celulart='+$F('celulart')+'&direcciont='+$F('direcciont')+'&condicion='+condicion,
        onComplete: mos_bus_reg_orden_part2

    });
        var1 = document.getElementById('reg_orden_part');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_reg_orden_part2(req){

  $('reg_orden_part').innerHTML= req.responseText;

}

/* **** fin de registrar titular el cliente modulo ordenes particular **** */

/* **** registrar el beneficiario cliente modulo ordenes particular **** */

function reg_orden_part3(i){
if ((Field.present('cedulab')==false) || (Field.present('nombreb')==false) || (Field.present('apellidob')==false))
	{
		alert ("Campos Vacios Todos son Obligatorios");
		}
		else
		{
condicion=i;
document.forms['oa']['cedula'].value=document.forms['oa']['cedulab'].value; 
new Ajax.Request("views01/reg_orden_part1.php",
    {
       method:'post',
       asynchronous: true,
       postBody:


                'cedula='+$F('cedulab')+'&id_titular='+$F('id_titular')+'&id_cliente='+$F('id_cliente')+'&nombreb='+$F('nombreb')+'&apellidob='+$F('apellidob')+'&direcciont='+$F('direcciont')+'&celulart='+$F('celulart')+'&condicion='+condicion,
        onComplete: mos_bus_reg_orden_part3

    });
        var1 = document.getElementById('reg_orden_part');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}
}

function mos_bus_reg_orden_part3(req){

  $('reg_orden_part').innerHTML= req.responseText;

}

function verificar_bene(){

new Ajax.Request("views01/verificar_bene.php",
    {
       method:'post',
       asynchronous: true,
       postBody:


                'cedula='+$F('cedulab')+'&id_titular='+$F('id_titular'),
        onComplete: mos_bus_verificar_bene

    });
        var1 = document.getElementById('verificar_bene');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_verificar_bene(req){

  $('verificar_bene').innerHTML= req.responseText;

}



/* **** fin de registrar beneficiario el cliente modulo ordenes particular **** */

/* **** buscar examenes cuartivas o estudios especiales para registrar orden particular **** */

function bus_exa_orden_par(){

new Ajax.Request("views01/bus_exa_orden_par.php",
    {
       method:'post',
       asynchronous: true,
       postBody:


                'examenes='+$F('examenes'),
        onComplete: mos_bus_exa_orden_par

    });
        var1 = document.getElementById('buscar_procesosf');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_exa_orden_par(req){

  $('buscar_procesosf').innerHTML= req.responseText;

}

/* **** fin de buscar examenes cuartivas o estudios especiales para registrar orden particular **** */


/* **** guardar orden particular metodo rapido**** */
function gua_ord_par_rap()
{
	if (Field.present('monto')==false)
	{
		alert ("El Monto esta Vacio");
	}
	else
	{
		
			

			examen1="";

			idexamen1="";

			honorarios1= "";

			coment1= "";
			j=0;
			z=0;
			
			contador=$F('conexa');
			for(var i=0; i<contador; i++) 
				
			{
				j++;
				formexa = document.getElementById("check_"+[j]);
				if(formexa.checked) 
					
				{
					z++;	
				
				idexamen=document.getElementById("idexamen_"+[j]);

		 		examen=document.getElementById("examen_"+[j]);

				honorarios=document.getElementById("honorarios_"+[j]);

				coment=document.getElementById("coment_"+[j]);

				idexamen1 +="@"+idexamen.value;

				examen1 +="@"+examen.value;

				honorarios1 +="@"+honorarios.value;

				coment1 +="@"+coment.value;



				}
			}
			if (z>0)
			{
					
new Ajax.Request("views01/gua_ord_par_rap.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	'proveedor='+$F('proveedor')+'&conexa='+$F('conexa')+'&id_titular='+$F('id_titular')+'&beneficiario='+$F('beneficiario')+'&examenes='+$F('examenes')+'&id_cobertura='+$F('id_cobertura')+'&idexamen1='+idexamen1+'&examen1='+examen1+'&honorarios1='+honorarios1+'&coment1='+coment1,
	onComplete: mos_gua_ord_par_rap

	
    });
	var1 = document.getElementById('buscar_procesosf'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		
		}
		else
		{
		alert ("Debes Seleccionar algun Gasto");
		}
	}
	function mos_gua_ord_par_rap(req)
{

  $('buscar_procesosf').innerHTML= req.responseText;
			
}

}



/* **** facturacion **** */
function reg_fac_ord_par(){
	
	if (Field.present('proceso')==false)
	{
		alert ("Campos Vacios Haga la Busqueda llenando uno de los Campos si la Factura es por Ente debe Seleccionar las Fechas");
		}
		else
		{
new Ajax.Request("views04/reg_facturas1.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	
	
		'proceso='+$F('proceso'),
	onComplete: mos_bus_reg_fac_ord_par
    });
	var1 = document.getElementById('reg_fac_ord_par'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_reg_fac_ord_par(req){
	
  $('reg_fac_ord_par').innerHTML= req.responseText;

}
}


/* **** fin de guardar orden particular metodo rapido **** */


/* **** registrar plan para polizas **** */

function bus_caract_poliza1(){

new Ajax.Request("views02/bus_caract_poliza.php",
    {
       method:'post',
       asynchronous: true,
       postBody:


                'id_poliza='+$F('id_poliza')+'&id_tipo_caract='+$F('id_tipo_caract'),
        onComplete: mos_bus_caract_poliza

    });
        
}

function mos_bus_caract_poliza(req){

  $('bus_caract_poliza').innerHTML= req.responseText;

}

/* **** registrar plan para polizas **** */


function reg_caract_poliza2(){
control='1';
new Ajax.Request("views02/reg_caract_poliza2.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'id_poliza='+$F('id_poliza'),
        onComplete: mos_bus_reg_caract_poliza

    });
        var1 = document.getElementById('bus_reg_caract_poliza');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_reg_caract_poliza(req){

  $('bus_reg_caract_poliza').innerHTML= req.responseText;

}


function reg_caract_poliza3(){
control='1';
new Ajax.Request("views02/bus_caract_poliza.php",
    {
       method:'post',
       asynchronous: true,
       postBody:'id_poliza='+$F('id_poliza')+'&caract='+$F('caract')+'&noorden='+$F('no_orden')+'&id_tipo_caract='+$F('id_tipo_caract')+'&control='+control,
        onComplete: mos_bus_reg_caract_poliza3

    });
        var1 = document.getElementById('bus_caract_poliza');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_reg_caract_poliza3(req){

  $('bus_caract_poliza').innerHTML= req.responseText;

}

function act_caract_poliza()
{

			control='2';
			caracteristica1="";
			idcaractpoliza1="";
			noorden="0";
			j=0;
			z=0;
			contador=$F('conexa');
			
			for(var i=0; i<contador; i++) 
			{
				j++;
				formexa = document.getElementById("check_"+[j]);
				
				if(formexa.checked) 
				{
					z++;	
				caracteristica=document.getElementById("caracteristica_"+[j]);
				idcaractpoliza=document.getElementById("id_caract_poliza_"+[j]);
		 		no_orden=document.getElementById("no_orden_"+[j]);
		 		caracteristica1 +="@"+caracteristica.value;
		 		idcaractpoliza1 +="@"+idcaractpoliza.value;
				noorden +="@"+no_orden.value;
			
				}
			}
			if (z>0)
			{ 
new Ajax.Request("views02/bus_caract_poliza.php",
    {
       method:'post',
       asynchronous: true,
       postBody:'id_poliza='+$F('id_poliza')+'&id_tipo_caract='+$F('id_tipo_caract')+'&conexa='+$F('conexa')+'&caracteristica1='+caracteristica1+'&idcaractpoliza1='+idcaractpoliza1+'&noorden='+noorden+'&control='+control,
	onComplete: mos_act_caract_poliza
    });
	var1 = document.getElementById('bus_caract_poliza'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		
		}
		else
		{
		alert ("Debes Seleccionar algun Gasto");
		}
	}
	function mos_act_caract_poliza(req)
{

  $('bus_caract_poliza').innerHTML= req.responseText;
			
}

/* **** fin d registrar plan para polizas **** */

/* **** registrar recibos de pagos de cuadro de primas **** */

function bus_reg_rec_pago(){

new Ajax.Request("views04/bus_regrec_pago.php",
    {

       method:'post',
       asynchronous: true,
       postBody:

              'cedula='+$F('cedula')+'&numero_contrato='+$F('numero_contrato'),
        onComplete: mos_bus_reg_rec_pago

    });
        var1 = document.getElementById('bus_regrec_pago');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_reg_rec_pago(req){

  $('bus_regrec_pago').innerHTML= req.responseText;

}


function bus_reg_rec_pagocon(numc){

document.getElementById("numero_contrato").value=numc;

new Ajax.Request("views04/bus_regrec_pago.php",
    {

       method:'post',
       asynchronous: true,
       postBody:

              'cedula='+$F('cedula')+'&numero_contrato='+numc,
        onComplete: mos_bus_reg_rec_pagocon

    });
        var1 = document.getElementById('bus_regrec_pago');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_reg_rec_pagocon(req){

  $('bus_regrec_pago').innerHTML= req.responseText;

}



function bus_reg_rec_pago2(){

new Ajax.Request("views04/reg_recibo_pago2.php",
    {
       method:'post',
       asynchronous: true,
       postBody:

              'cedula='+$F('cedula')+'&numero_contrato='+$F('numero_contrato'),
        onComplete: mos_bus_reg_rec_pago2

    });
        var1 = document.getElementById('bus_regrec_pago2');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_reg_rec_pago2(req){

  $('bus_regrec_pago2').innerHTML= req.responseText;

}


function guardar_recibo_pago()
{
monto=$F('monto');
cuota1=$F('cuota');
cuota=Number(cuota1);
contado1=$F('contado');
contado=Number(contado1);
registrar='1';
	
	if ((Field.present('dateField3')==false && $F('forma_pago')==1) 
|| (Field.present('dateField3')==false && $F('forma_pago')==2 && Field.present('dateField5')==false) 
|| ( $F('forma_pago')==2 && Field.present('dateField5')==false) 
|| (Field.present('dateField3')==false && $F('forma_pago')==3 && Field.present('no_cheque')==false) 
|| ( $F('forma_pago')==3 && Field.present('no_cheque')==false) 
|| ( $F('forma_pago')==5 && Field.present('no_cheque')==false)
|| ( $F('forma_pago')==4 && Field.present('no_cheque')==false)
|| ( $F('forma_pago')==0) 
|| Field.present('monto')==false )
	{
	alert ("Campo Fecha Emision o Campo Fecha Credito o Campo Numero de Cheque debito tarjeta credito  Vacio debe Seleccionar la Fecha o campo forma de campo vacio");
	}
	else
	{
if (monto<cuota || monto>contado)

{
alert ("EL Monto a Cancelar no Puede ser Menor a su Cuota o Mayor al Total Faltante");
}
else
{
		
			
		new Ajax.Request("views04/bus_regrec_pago.php",
		{
		method:'post',
		asynchronous: true,
		postBody:
		'id_recibo_contrato='+$F('id_recibo_contrato')+'&dateField3='+$F('dateField3')+'&dateField5='+$F('dateField5')+'&forma_pago='+$F('forma_pago')+'&banco='+$F('banco')+'&no_cheque='+$F('no_cheque')+'&concepto='+$F('concepto')+'&factura='+$F('num_rec_pago')+'&serie='+$F('serie')+'&nom_tarjeta='+$F('nom_tarjeta')+'&monto='+$F('monto')+'&cedula='+$F('cedula1')+'&numero_contrato='+$F('numero_contrato1')+'&contado='+$F('contado')+'&tprima='+$F('tprima')+'&registrar='+registrar+'&fecha_emision='+$F('fecha_emision')+'&cuotacon='+$F('cuotacon')+'&fechaefectivap='+$F('fechaefectivap'),
	onComplete: mos_guardar_recibo_pago
	  
		});
		var1 = document.getElementById('bus_regrec_pago'); 
		var1.innerHTML = '<img 	src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	}
}

	function mos_guardar_recibo_pago(req)
	{
	
	$('bus_regrec_pago').innerHTML= req.responseText;

	}
}




function guardar_recibo_pago2()
{
monto=$F('monto');
cuota1=$F('cuota');
cuota=Number(cuota1);
contado1=$F('contado');
contado=Number(contado1);
registrar='1';

	if ((Field.present('concepto')==false) || (Field.present('dateField3')==false ))
	{
	alert ("Campo Conpcepto o  Campo Fecha Emision o Campo Fecha Credito o Campo Numero de Cheque debito tarjeta credito  Vacio debe Seleccionar la Fecha o campo forma de campo vacio");
	}
	else
	{
	
if (monto<cuota || monto>contado)

{
alert ("EL Monto a Cancelar no Puede ser Menor a su Cuota o Mayor al Total Faltante");
}
else
{	
					
		new Ajax.Request("views04/bus_regrec_pago.php",
		{
		method:'post',
		asynchronous: true,
		postBody:
	
		'id_recibo_contrato='+$F('id_recibo_contrato')+'&dateField3='+$F('dateField3')+'&dateField5='+$F('dateField5')+'&forma_pago='+$F('forma_pago')+'&forma_pago1='+$F('forma_pago1')+'&banco1='+$F('banco1')+'&no_cheque1='+$F('no_cheque1')+'&forma_pago2='+$F('forma_pago2')+'&banco2='+$F('banco2')+'&no_cheque2='+$F('no_cheque2')+'&forma_pago3='+$F('forma_pago3')+'&banco3='+$F('banco3')+'&no_cheque3='+$F('no_cheque3')+'&forma_pago4='+$F('forma_pago4')+'&banco4='+$F('banco4')+'&no_cheque4='+$F('no_cheque4')+'&concepto='+$F('concepto')+'&factura='+$F('num_rec_pago')+'&serie='+$F('serie')+'&nom_tarjeta1='+$F('nom_tarjeta1')+'&nom_tarjeta2='+$F('nom_tarjeta2')+'&nom_tarjeta3='+$F('nom_tarjeta3')+'&nom_tarjeta4='+$F('nom_tarjeta4')+'&monto1='+$F('monto1')+'&monto2='+$F('monto2')+'&monto3='+$F('monto3')+'&monto4='+$F('monto4')+'&monto='+$F('monto')+'&cedula='+$F('cedula1')+'&numero_contrato='+$F('numero_contrato1')+'&contado='+$F('contado')+'&tprima='+$F('tprima')+'&registrar='+registrar+'&fecha_emision='+$F('fecha_emision')+'&cuotacon='+$F('cuotacon')+'&fechaefectivap='+$F('fechaefectivap'),
	onComplete: mos_guardar_recibo_pago2
	  
		});
		var1 = document.getElementById('bus_regrec_pago'); 
		var1.innerHTML = '<img 						src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	}
}
	function mos_guardar_recibo_pago2(req)
	{
	
	$('bus_regrec_pago').innerHTML= req.responseText;

	}
}

/* **** cambiar inicial y cuota que selecciono el cliente antes de realizar el primer pago **** */
function act_ini_cuo()
{
actinicuo='1';
new Ajax.Request("views04/bus_regrec_pago.php",
    {
	
       method:'post',
       asynchronous: true,
       postBody:
	   
	'id_recibo_contrato='+$F('id_recibo_contrato')+'&cuotacon='+$F('cuotacon')+'&cedula='+$F('cedula1')+'&numero_contrato='+$F('numero_contrato1')+'&actinicuo='+actinicuo+'&fecha_emision='+$F('fecha_emision'),
	onComplete: mos_act_ini_cuo
	  
    });
	var1 = document.getElementById('bus_regrec_pago'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_act_ini_cuo(req){
	
  $('bus_regrec_pago').innerHTML= req.responseText;
}

/* **** fin de cambiar inicial y cuota que selecciono el cliente antes de realizar el primer pago**** */


/* **** anular un recibo de  pago  de poliza **** */
function anu_rec_pago(id_rec_pago,concepto)
{
id_rec_pago=id_rec_pago;
concepto=concepto;
anular='1';
new Ajax.Request("views04/bus_regrec_pago.php",
    {
	
       method:'post',
       asynchronous: true,
       postBody:
	   
	'id_recibo_pago='+id_rec_pago+'&anular='+anular+'&concepto='+concepto+'&cedula='+$F('cedula')+'&numero_contrato='+$F('numero_contrato'),
	onComplete: mos_anu_rec_pago
	  
    });
	var1 = document.getElementById('bus_regrec_pago'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_anu_rec_pago(req){
	
  $('bus_regrec_pago').innerHTML= req.responseText;
}


/* **** fin de anu un recibo de  pago  de poliza **** */
/* **** anular un recibo de  pago  de poliza **** */
function mod_fec_pago(id_rec_pago,i)
{
id_rec_pago=id_rec_pago;
num=i;
fechaefectivam='fechaefectivam'+num;
modificar='1';
new Ajax.Request("views04/bus_regrec_pago.php",
    {
	
       method:'post',
       asynchronous: true,
       postBody:
	   
	'id_recibo_pago='+id_rec_pago+'&modificar='+modificar+'&fechaefectivam='+$F(fechaefectivam)+'&cedula='+$F('cedula')+'&numero_contrato='+$F('numero_contrato'),
	onComplete: mos_mod_fec_pago
	  
    });
	var1 = document.getElementById('bus_regrec_pago'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_mod_fec_pago(req){
	
  $('bus_regrec_pago').innerHTML= req.responseText;
}


/* **** fin de anu un recibo de  pago  de poliza **** */


function imprimir_recibo_pago(nombres,rifs,numeros_contrato,numeros_rec_prima,conceptos,montos,saldos_favor,saldos_deudor,fechas_pago,fechas_proxima_pago,series,numeros_recibo,total_prima,cuota,id_recibo_contrato){
nombre=nombres;
rif=rifs;
numero_contrato=numeros_contrato;
numero_rec_prima=numeros_rec_prima;
concepto=conceptos;
monto=montos;
saldo_favor=saldos_favor;
saldo_deudor=saldos_deudor;
fecha_pago=fechas_pago;
fecha_proxima_pago=fechas_proxima_pago;
serie=series;
numero_recibo=numeros_recibo;
total_prima=total_prima;
cuota=cuota;
id_recibo_contrato=id_recibo_contrato;
	
url='views04/irecibo_pago.php?concepto='+conceptos+'&monto='+monto+'&saldo_favor='+saldo_favor+'&saldo_deudor='+saldo_deudor+'&fecha_pago='+fecha_pago+'&fecha_proxima_pago='+fecha_proxima_pago+'&serie='+serie+'&numero_recibo='+numero_recibo+'&nombre='+nombre+'&rif='+rif+'&numero_contrato='+numero_contrato+'&numero_rec_prima='+numero_rec_prima+'&total_prima='+total_prima+'&cuota='+cuota+'&id_recibo_contrato='+id_recibo_contrato;
  	imprimir(url);
}


/* ****   **** */

/* **** Verificar numero de planilla o presupuesto para mostrar quien lo tiene asignado   **** */
function verificar_planilla()
	{
			numpre=$F('numpre');
			url='views01/verificar_planilla.php?numpre='+numpre;
 		 	Modalbox.show(this.url, {title: this.title, width: 800, height: 400, overlayClose: false});

	
	}

/* *** fin de verificar planilla*** */
/* **** Verificar numero de planilla o presupuesto para mostrar quien lo tiene asignado  y permita modificar el numero de planilla  **** */

function verificar_planilla2()
{
new Ajax.Request("views01/verificar_planilla2.php",
    {
	
       method:'post',
       asynchronous: true,
       postBody:
	   
	'numpre='+$F('numpre'),
	onComplete: mos_verificar_planilla2
	  
    });
	var1 = document.getElementById('verificar_planilla2'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_verificar_planilla2(req){
	
  $('verificar_planilla2').innerHTML= req.responseText;
}

/* *** fin de verificar planilla y permita modificar el numero de planilla *** */

/* **** Verificar numero de planilla o presupuesto para mostrar quien lo tiene asignado  y permita modificar el numero de planilla  **** */

function verificar_clave2()
{
new Ajax.Request("views01/verificar_clave2.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	'numpre='+$F('numpre')+'&clave='+$F('clave')+'&comentario='+$F('comentario')+'&cuadro_m='+$F('cuadro_m')+'&dateFieldfe='+$F('dateFieldfe'),
	onComplete: mos_verificar_clave2
    });
	var1 = document.getElementById('verificar_clave2'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_verificar_clave2(req){
  $('verificar_clave2').innerHTML= req.responseText;
}

/* *** fin de verificar planilla y permita modificar el numero de planilla *** */



/* **** Verificar numero de clave o presupuesto para mostrar quien lo tiene asignado   **** */
function verificar_clave()
	{
			clave=$F('clave');
			url='views01/verificar_clave.php?clave='+clave;
 		 	Modalbox.show(this.url, {title: this.title, width: 800, height: 400, overlayClose: false});

	
	}

/* *** fin de verificar clave*** */




/* **** Cambiar y Actualizar numero de planilla o presupuesto  de un numero anterior **** */

function act_num_planilla()
{
new Ajax.Request("views01/gua_act_num_plan.php",
    {
	
       method:'post',
       asynchronous: true,
       postBody:
	   
	'nu_planilla='+$F('numpre2')+'&ant_planilla='+$F('numpre'),
	onComplete: mos_act_num_planilla
	  
    });
	var1 = document.getElementById('verificar_planilla2'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_act_num_planilla(req){
	
  $('verificar_planilla2').innerHTML= req.responseText;
}

/* *** fin de Cambiar y Actualizar numero de planilla o presupuesto  de un numero anterior *** */


/* ***  actualizar egreso de una emergencia *** */
function act_egreso_eme(i)
{
	
	if ($F('id_proceso_'+i)==false)
	{
		alert ("El Numero de poceso y fecha es obligatorio");
		}
		else
		{

new Ajax.Request("views01/act_egreso_eme.php",
    {
       method:'post',
       asynchronous: true,
       postBody:

	
		'proceso='+$F('id_proceso_'+i),
	onComplete: mos_bus_act_egreso_eme
	  
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_act_egreso_eme(req){
	
  $('clientes').innerHTML= req.responseText;

}
}

/* *** fin de actualizar egreso de una emergencia *** */


/* ***  buscar proceso para crear informe medico *** */
function bus_crea_info(i)
{
	
	if ($F('id_proceso_'+i)==false)
	{
		alert ("El Numero de poceso ");
		}
		else
		{

new Ajax.Request("views01/inf_medico1.php",
    {
       method:'post',
       asynchronous: true,
       postBody:

	
		'elproceso='+$F('id_proceso_'+i),
	onComplete: mos_bus_crea_info
	  
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_crea_info(req){
	
  $('clientes').innerHTML= req.responseText;

}
}
/* *** fin den  buscar proceso para crear informe medico *** */


/* **** Auditar Coberturas  **** */

function auditar_cob1()
{

new Ajax.Request("views07/auditar_cobertura1.php",
    {
	
       method:'post',
       asynchronous: true,
       postBody:
	   
	'ente='+$F('ente')+'&tipo_ente='+$F('tipo_ente'),
	onComplete: mos_auditar_cobertura1
	  
    });
	var1 = document.getElementById('bus_entes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}
function mos_auditar_cobertura1(req){
  $('bus_entes').innerHTML= req.responseText;
}

/* *** fin de Auditar Coberturas  *** */



/* **** Modificar Facturas de Estado Por Cobrar a Estado Pagada por Relacion **** */
function mod_edo_fact_rel()
{

	if (Field.present('monto')==false)
	{
		alert ("El Monto esta Vacio");
	}
	else
	{

	
			idfactura1="";
			honorarios1= "";
			j=0;
			z=0;
			
			contador=$F('conexa');
			for(var i=0; i<contador; i++) 
				
			{
				j++;
				formexa = document.getElementById("check_"+[j]);
				if(formexa.checked) 
					
				{
					z++;	
		
				idfactura=document.getElementById("idfactura_"+[j]);
		 		honorarios=document.getElementById("honorarios_"+[j]);
				idfactura1 +="@"+idfactura.value;
				honorarios1 +="@"+honorarios.value;

				
				}
			}
			if (z>0)
			{
					
new Ajax.Request("views06/gua_edo_fact_rel.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	'forma_pago='+$F('forma_pago1')+'&conexa='+$F('conexa')+'&nom_tarjeta='+$F('nom_tarjeta')+'&banco='+$F('banco')+'&no_cheque='+$F('no_cheque')+'&estado_fac='+$F('estado_fac')+'&idfactura1='+idfactura1+'&honorarios1='+honorarios1,
	onComplete: mos_mod_edo_fact_rel

	
    });
	var1 = document.getElementById('bus_rep_factura'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		
		}
		else
		{
		alert ("Debes Seleccionar algun Gasto");
		}
	}
	function mos_mod_edo_fact_rel(req)
{

  $('bus_rep_factura').innerHTML= req.responseText;
			
}

}

/* **** fin de Modificar Facturas de Estado Por Cobrar a Estado Pagada por Relacion **** */

/* *** Registrar nombre de Baremos de Precios *** */
function gua_nombre_bar(variable){
variable1=variable;

new Ajax.Request("views07/reg_baremo_precio.php",
    {
       method:'post',
       asynchronous: true,
       postBody:


                'nombre_bar='+$F('nombre_bar')+'&id_baremo='+$F('id_baremo')+'&id_ente='+$F('id_ente')+'&variable1='+variable1,
        onComplete: mos_gua_nombre_bar

    });
        var1 = document.getElementById('clientes');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_gua_nombre_bar(req){

  $('clientes').innerHTML= req.responseText;

}


/* *** Fin Registrar nombre de Baremos de Precios *** */


/* **** Modificar Precios de Baremos **** */
function act_baremo_precio(variable)
{
			variable1=variable;		
			examen_bl_1="";
			precio_1= "";
			j=0;
			z=0;
		
			contador=$F('conexa');
			for(var i=0; i<contador; i++) 
				
			{
				j++;
				formexa = document.getElementById("check_"+[j]);
				if(formexa.checked) 
					
				{
					z++;	
		
				examen_bl=document.getElementById("examen_bl_"+[j]);
		 		precio=document.getElementById("precio_"+[j]);
				examen_bl_1 +="@"+examen_bl.value;
				precio_1 +="@"+precio.value;
				}
			}
			if (z>0)
			{
					
new Ajax.Request("views07/reg_baremo_precio.php",
    {
       method:'post',
       asynchronous: true,
       postBody:
	'id_baremo='+$F('id_baremo')+'&conexa='+$F('conexa')+'&examen_bl_1='+examen_bl_1+'&precio_1='+precio_1+'&variable1='+variable1,
	onComplete: mos_act_baremo_precio

	
    });
	var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		
		}
		else
		{
		alert ("Debes Seleccionar algun Gasto");
		}
}

	function mos_act_baremo_precio(req)
{

  $('clientes').innerHTML= req.responseText;
			
}



/* **** fin de Modificar Precios de Baremos **** */


/* **** Modificar Primas de un cuadro de recibo de primas **** */

function bus_mod_prima_indi(){

new Ajax.Request("views07/bus_mod_prima_indi.php",
    {

       method:'post',
       asynchronous: true,
       postBody:

              'cedula='+$F('cedula')+'&numero_contrato='+$F('numero_contrato'),
        onComplete: mos_bus_mod_prima_indi

    });
        var1 = document.getElementById('mod_prima_indi');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_mod_prima_indi(req){

  $('mod_prima_indi').innerHTML= req.responseText;

}

function bus_mod_prima_indic(numc){

document.getElementById("numero_contrato").value=numc;

new Ajax.Request("views07/bus_mod_prima_indi.php",
    {

       method:'post',
       asynchronous: true,
       postBody:

              'cedula='+$F('cedula')+'&numero_contrato='+numc,
        onComplete: mos_mod_prima_indic

    });
        var1 = document.getElementById('mod_prima_indi');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_mod_prima_indic(req){

  $('mod_prima_indi').innerHTML= req.responseText;


}


function bus_mod_prima_indi2(numc,id_caract_recibo,id_prima,i){

document.getElementById("numero_contrato").value=numc;
new Ajax.Request("views07/bus_mod_prima_indi.php",
    {

       method:'post',
       asynchronous: true,
       postBody:

              'monto_prima='+$F("tprima_"+[i])+'&id_caract_recibo='+id_caract_recibo+'&numero_contrato='+numc+'&id_prima='+id_prima,
        onComplete: mos_mod_prima_indi2

    });
        var1 = document.getElementById('mod_prima_indi');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_mod_prima_indi2(req){

  $('mod_prima_indi').innerHTML= req.responseText;


}


/* ****FIn Modificar Primas de un cuadro de recibo de primas **** */

/* **** Registrar o modificar Paragrafos**** */

function bus_paragrafos(){
new Ajax.Request("views02/bus_paragrafos.php",
    {

       method:'post',
       asynchronous: true,
       postBody:

              'id_tparagrafo='+$F('id_tparagrafo'),
        onComplete: mos_bus_paragrafos

    });
        var1 = document.getElementById('tipo_paragrafo');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_paragrafos(req){

  $('tipo_paragrafo').innerHTML= req.responseText;
}



function reg_rparagrafo(regparagrafo){
new Ajax.Request("views02/bus_paragrafos.php",
    {

       method:'post',
       asynchronous: true,
       postBody:

              'rparagrafo='+$F('rparagrafo')+'&regparagrafo='+regparagrafo+'&id_tparagrafo='+$F('id_tparagrafo'),
        onComplete: mos_reg_rparagrafo

    });
        var1 = document.getElementById('tipo_paragrafo');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_reg_rparagrafo(req){

  $('tipo_paragrafo').innerHTML= req.responseText;


}




function bus_mod_paragrafo(id_paragrafo,i){

new Ajax.Request("views02/bus_paragrafos.php",
    {

       method:'post',
       asynchronous: true,
       postBody:

              'paragrafo='+$F("paragrafo_"+[i])+'&id_paragrafo='+id_paragrafo+'&id_tparagrafo='+$F('id_tparagrafo'),
        onComplete: mos_bus_mod_paragrafo

    });
        var1 = document.getElementById('tipo_paragrafo');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_mod_paragrafo(req){

  $('tipo_paragrafo').innerHTML= req.responseText;


}


/* **** fin de Registrar o modificar Paragrafos**** */


/* *** reportes carlos ivan gastos entes *** */

/* **** reporte de gastos por entes**** */
function bus_ent(imprimir){
var imprimir;
imprimir=imprimir;
new Ajax.Request("views06/bus_ent.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'tipo_ente='+$F('tipo_ente')+'&imprimir='+(imprimir),
       onComplete: mos_bus_ent
    });
}

function mos_bus_ent(req){
  $('bus_ent').innerHTML= req.responseText;
}

function bus_entes(){

	
new Ajax.Request("views06/bus_rep_ent.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&sucursal='+$F('sucursal')+'&servicio='+$F('servicio')+'&tipo_ente='+$F('tipo_ente')+'&ente='+$F('ente')+'&tipo_cliente='+$F('tipo_cliente')+'&tipo_proveedor='+$F('tipo_proveedor'),
	onComplete: mos_bus_entes
	  
    });
	var1 = document.getElementById('bus_entes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_entes(req){
	
  $('bus_entes').innerHTML= req.responseText;

}

/* **** Imprimir gastos Entes para calculo de siniestralidad **** */

function imp_bus_rep_gas_ent(){
dateField1=$F('dateField1');
dateField2=$F('dateField2');
sucursal=$F('sucursal');
servicio=$F('servicio');
tipo_ente=$F('tipo_ente');
ente=$F('ente');
tipo_cliente=$F('tipo_cliente');
tipo_proveedor=$F('tipo_proveedor');
	
	
url='views06/igastoente.php?dateField1='+dateField1+'&dateField2='+dateField2+'&sucursal='+sucursal+'&servicio='+servicio+'&tipo_ente='+tipo_ente+'&ente='+ente+'&tipo_cliente='+tipo_cliente+'&tipo_proveedor='+tipo_proveedor;
  	imprimir(url);
}

/* **** fin  de Imprimir gastos Entes para calculo de siniestralidad **** */

/* **** reporte de servicios por entes**** */


function bus_entes_ser(){

new Ajax.Request("views06/bus_rep_ent_ser.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&sucursal='+$F('sucursal')+'&servicio='+$F('servicio')+'&tipo_ente='+$F('tipo_ente')+'&ente='+$F('ente')+'&tipo_cliente='+$F('tipo_cliente')+'&tipo_proveedor='+$F('tipo_proveedor'),
	onComplete: mos_bus_entes_ser
	  
    });
	var1 = document.getElementById('bus_entes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_entes_ser(req){
	
  $('bus_entes').innerHTML= req.responseText;

}

/* **** Imprimir gastos Entes para calculo de siniestralidad **** */

function imp_rep_gas_ent_ser(){
dateField1=$F('dateField1');
dateField2=$F('dateField2');
sucursal=$F('sucursal');
servicio=$F('servicio');
tipo_ente=$F('tipo_ente');
ente=$F('ente');
tipo_cliente=$F('tipo_cliente');
tipo_proveedor=$F('tipo_proveedor');
	
	
url='views06/igastoserente.php?dateField1='+dateField1+'&dateField2='+dateField2+'&sucursal='+sucursal+'&servicio='+servicio+'&tipo_ente='+tipo_ente+'&ente='+ente+'&tipo_cliente='+tipo_cliente+'&tipo_proveedor='+tipo_proveedor;
  	imprimir(url);
}

/* **** fin  de Imprimir gastos Entes para calculo de siniestralidad **** */


/* **** reporte de servicios por entes**** */


function bus_cli_tot_ent(){

new Ajax.Request("views06/reportenuestrosclientes_totales.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'tipo_ente='+$F('tipo_ente')+'&ente='+$F('ente'),
	onComplete: mos_bus_cli_tot_ent
	  
    });
	var1 = document.getElementById('bus_entes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_cli_tot_ent(req){
	
  $('bus_entes').innerHTML= req.responseText;

}






function bus_invs(){

new Ajax.Request("views06/rep_invs2.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'dependencia='+$F('dependencia')+'&tipo_insumo='+$F('tipo_insumo')+'&laboratorio='+$F('laboratorio')+'&letra='+$F('letra')+'&monto='+$F('monto')+'&signo='+$F('signo')+'&cantidad='+$F('cantidad'),
	onComplete: mos_bus_invs
	  
    });
	var1 = document.getElementById('bus_invs'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_invs(req){
	
  $('bus_invs').innerHTML= req.responseText;

}


function bus_inv_xcorte(){

new Ajax.Request("views06/rep_inv_corte2.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'dependencia='+$F('dependencia')+'&tipo_insumo='+$F('tipo_insumo')+'&dateField1='+$F('dateField1')+'&letra='+$F('letra')+'&dateField2='+$F('dateField2'),
	onComplete: mos_bus_inv_xcorte
	  
    });
	var1 = document.getElementById('bus_inv_xcorte'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_inv_xcorte(req){
	
  $('bus_inv_xcorte').innerHTML= req.responseText;

}

/* **** reporte responsabilidad social**** */

function bus_rep_ressocial(){
new Ajax.Request("views06/bus_rep_ressocial.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&procesado='+$F('procesado')+'&donativo='+$F('donativo'),
       onComplete: mos_bus_rep_ressocial
    });
	var1 = document.getElementById('bus_rep_ressocial'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_rep_ressocial(req){
  $('bus_rep_ressocial').innerHTML= req.responseText;
}

/* **** fin de reporte responsabilidad social**** */

/* **** reporte relacion de facturas**** */

function bus_rep_factura(){
 qvalija = $('oa').getInputs('radio','valija').find(function(radio) { return radio.checked; }).value; 
 var fcarvalija = $F('caraclave');
 var fdireclave = $('oa').getInputs('radio','cladire').find(function(radio) { return radio.checked; }).value; 

new Ajax.Request("views06/bus_rep_factura.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&tipo_ente='+$F('tipo_ente')+'&ente='+$F('ente')+'&forma_pago='+$F('forma_pago')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios')+'&num_cheque='+$F('num_cheque')+'&datavalija='+qvalija+qvalija+'&clavcar='+fcarvalija+'&direcvalija='+fdireclave,
       onComplete: mos_bus_rep_factura
    });
	var1 = document.getElementById('bus_rep_factura'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_rep_factura(req){
  $('bus_rep_factura').innerHTML= req.responseText;
}

/* **** fin de reporte relacion factura**** */


/* **** reporte relacion de facturas detallado sin auditar**** */

function bus_rep_factura_sin_auditar(){
var fcarvalija = $F('caraclave');
var fdireclave = $('oa').getInputs('radio','cladire').find(function(radio) { return radio.checked; }).value; 

new Ajax.Request("views06/bus_rep_factura_sin_auditar.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&tipo_ente='+$F('tipo_ente')+'&ente='+$F('ente')+'&forma_pago='+$F('forma_pago')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios')+'&num_cheque='+$F('num_cheque')+'&clavcar='+fcarvalija+'&direcvalija='+fdireclave,
       onComplete: mos_bus_rep_factura_sin_auditar
    });
	var1 = document.getElementById('bus_rep_factura'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_rep_factura_sin_auditar(req){
  $('bus_rep_factura').innerHTML= req.responseText;
}

/* **** fin de reporte relacion factura detallado sin auditar **** */

/* **** reporte relacion de facturas general sin auditar**** */

function bus_rep_sin_audi_factura(){
new Ajax.Request("views06/bus_rep_gen_factura.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&tipo_ente='+$F('tipo_ente')+'&ente='+$F('ente')+'&forma_pago='+$F('forma_pago')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios')+'&num_cheque='+$F('num_cheque'),
       onComplete: mos_bus_rep_sin_audi_factura
    });
	var1 = document.getElementById('bus_rep_factura'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_rep_sin_audi_factura(req){
  $('bus_rep_factura').innerHTML= req.responseText;
}

/* **** fin de reporte relacion facturas general sin auditar**** */

/*Busqueda general con montos no aprobados sin auditoria */
function bus_monto_noaprobado(){
new Ajax.Request("views06/bus_monto_noaprob.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&tipo_ente='+$F('tipo_ente')+'&ente='+$F('ente')+'&forma_pago='+$F('forma_pago')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios')+'&num_cheque='+$F('num_cheque'),
       onComplete: mos_bus_rep_sin_audi_factura
    });
	var1 = document.getElementById('bus_rep_factura'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function bus_fac_monto_noaprobado(){
new Ajax.Request("views06/bus_fac_monto_noaprobado.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&tipo_ente='+$F('tipo_ente')+'&ente='+$F('ente')+'&forma_pago='+$F('forma_pago')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios')+'&num_cheque='+$F('num_cheque'),
       onComplete: mos_bus_rep_sin_audi_factura
    });
	var1 = document.getElementById('bus_rep_factura'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

/*fin */
/* **** reporte relacion estado de cuentas de facturas general sin auditar**** */

function bus_rep_edo_cue_factura(){
new Ajax.Request("views06/bus_rep_edo_cue_factura.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&tipo_ente='+$F('tipo_ente')+'&ente='+$F('ente')+'&forma_pago='+$F('forma_pago')+'&sucursal='+$F('sucursal')+'&servicios='+$F('servicios')+'&num_cheque='+$F('num_cheque'),
       onComplete: mos_bus_rep_edo_cue_factura
    });
	var1 = document.getElementById('bus_rep_factura'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_rep_edo_cue_factura(req){
  $('bus_rep_factura').innerHTML= req.responseText;
}

/* **** fin de reporte relacion estado de cuentas facturas general sin auditar**** */

/* **** reporte relacion de nota de credito**** */

function bus_rep_nc(){
new Ajax.Request("views06/bus_rep_nc.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&ente='+$F('ente')+'&forma_pago='+$F('forma_pago')+'&sucursal='+$F('sucursal'),
       onComplete: mos_bus_rep_nc
    });
	var1 = document.getElementById('bus_rep_nc'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_rep_nc(req){
  $('bus_rep_nc').innerHTML= req.responseText;
}

/* **** fin de reporte relacion nota credito**** */



/* **** reporte relacion de cheques o recibos**** */

function buscarrecibos(){
new Ajax.Request("views06/rep_rel_rec1.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&sucursal='+$F('sucursal')+'&recibo_che='+$F('recibo_che')+'&tipo_cheque='+$F('prov')+'&banco='+$F('banco')+'&proveedor='+$F('proveedor'),
       onComplete: mos_bus_recibo
    });
	var1 = document.getElementById('buscarrecibos'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_recibo(req){
  $('buscarrecibos').innerHTML= req.responseText;
}

/* **** fin de reporte relacion recibos**** */

/* **** reporte relacion de reembolsos en estado en espera **** */

function bus_rep_reem_esp(){
		if ((Field.present('monto')==false) || (Field.present('dateField1')==false) || (Field.present('dateField2')==false))
	{
		alert ("Campos Vacios Todos son Obligatorios");
		}
		else
		{
new Ajax.Request("views06/rep_reem_esp1.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'monto='+$F('monto')+'&dateField1='+$F('dateField1')+'&dateField2='+$F('dateField2')+'&igualdad='+$F('igualdad')+'&estapro='+$F('estapro'),
       onComplete: mos_bus_rep_reem_esp
    });
	var1 = document.getElementById('bus_rep_reem_esp'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}
}
function mos_bus_rep_reem_esp(req){
  $('bus_rep_reem_esp').innerHTML= req.responseText;

}

/* **** fin de reporte relacion reembolsos en estado en espera **** */

/* **** Reportes de Excel**** */

/* **** reporte de relacion de facturas **** */

function bus_rep_excel_factura(i){
dateField1=$F('dateField1');
dateField2=$F('dateField2');
ente=$F('ente');
forma_pago=$F('forma_pago');
sucursal=$F('sucursal');
tipo_ente=$F('tipo_ente');
servicios=$F('servicios');
num_cheque=$F('num_cheque');
var fcarvalija = $F('caraclave');
var fdireclave = $('oa').getInputs('radio','cladire').find(function(radio) { return radio.checked; }).value; 

if (i==1){
url='views06/irep_excel_factura.php?dateField1='+dateField1+'&dateField2='+dateField2+'&sucursal='+sucursal+'&forma_pago='+forma_pago+'&ente='+ente+'&tipo_ente='+tipo_ente+'&servicios='+servicios+'&num_cheque='+num_cheque+'&clavcar='+fcarvalija+'&direcvalija='+fdireclave;
}
if (i==2){
url='views06/irep_excel_factura3.php?dateField1='+dateField1+'&dateField2='+dateField2+'&sucursal='+sucursal+'&forma_pago='+forma_pago+'&ente='+ente+'&tipo_ente='+tipo_ente+'&servicios='+servicios+'&num_cheque='+num_cheque+'&clavcar='+fcarvalija+'&direcvalija='+fdireclave;
}
  imprimir(url);
}


/* **** fin reporte de relacion de facturas **** */
/* **** reporte de relacion de facturas con mas campos**** */

function bus_rep_excel_factura2(){
dateField1=$F('dateField1');
dateField2=$F('dateField2');
ente=$F('ente');
forma_pago=$F('forma_pago');
sucursal=$F('sucursal');
tipo_ente=$F('tipo_ente');
servicios=$F('servicios');
num_cheque=$F('num_cheque');
url='views06/irep_excel_factura2.php?dateField1='+dateField1+'&dateField2='+dateField2+'&sucursal='+sucursal+'&forma_pago='+forma_pago+'&ente='+ente+'&tipo_ente='+tipo_ente+'&servicios='+servicios+'&num_cheque='+num_cheque;
  imprimir(url);
}


/* **** fin reporte de relacion de facturas **** */
/* **** reporte de relacion de facturas contable jp**** */

function bus_rep_excel_contablejp(){
dateField1=$F('dateField1');
dateField2=$F('dateField2');
ente=$F('ente');
forma_pago=$F('forma_pago');
sucursal=$F('sucursal');
tipo_ente=$F('tipo_ente');
servicios=$F('servicios');
num_cheque=$F('num_cheque');
url='views06/irep_excel_contable.php?dateField1='+dateField1+'&dateField2='+dateField2+'&sucursal='+sucursal+'&forma_pago='+forma_pago+'&ente='+ente+'&tipo_ente='+tipo_ente+'&servicios='+servicios+'&num_cheque='+num_cheque;
  imprimir(url);
}


/* **** fin de relacion de facturas contable jp**** */
/* **** reporte de relacion de facturas **** */

function bus_rep_excel_edo_cue_factura(){
dateField1=$F('dateField1');
dateField2=$F('dateField2');
ente=$F('ente');
forma_pago=$F('forma_pago');
sucursal=$F('sucursal');
tipo_ente=$F('tipo_ente');
servicios=$F('servicios');

num_cheque=$F('num_cheque');
url='views06/irep_excel_edo_cue_factura.php?dateField1='+dateField1+'&dateField2='+dateField2+'&sucursal='+sucursal+'&forma_pago='+forma_pago+'&ente='+ente+'&tipo_ente='+tipo_ente+'&servicios='+servicios+'&num_cheque='+num_cheque;
  imprimir(url);
}


/* **** fin reporte de relacion de facturas **** */

/* **** reporte de relacion de facturas contable xml**** */

function bus_rep_excel_contablejpxm(){
dateField1=$F('dateField1');
dateField2=$F('dateField2');
ente=$F('ente');
forma_pago=$F('forma_pago');
sucursal=$F('sucursal');
tipo_ente=$F('tipo_ente');
servicios=$F('servicios');
num_cheque=$F('num_cheque');
url='views06/irep_excel_conxml.php?dateField1='+dateField1+'&dateField2='+dateField2+'&sucursal='+sucursal+'&forma_pago='+forma_pago+'&ente='+ente+'&tipo_ente='+tipo_ente+'&servicios='+servicios+'&num_cheque='+num_cheque;
  imprimir(url);
}
/* **** fin de relacion de facturas contable xml**** */

/* **** reporte Busca solo los Procesos Recibidos en una Determinada Fecha Relacion Para dpto Actuarial **** */

function bus_rep_sol_pro(){
dateField1=$F('dateField1');
dateField2=$F('dateField2');
ente=$F('ente');
forma_pago=$F('forma_pago');
sucursal=$F('sucursal');
tipo_ente=$F('tipo_ente');
servicios=$F('servicios');
num_cheque=$F('num_cheque');

url='views06/irep_sol_pro.php?dateField1='+dateField1+'&dateField2='+dateField2+'&sucursal='+sucursal+'&forma_pago='+forma_pago+'&ente='+ente+'&tipo_ente='+tipo_ente+'&servicios='+servicios+'&num_cheque='+num_cheque;

  imprimir(url);
}


/* **** fin reporte Busca solo los Procesos Recibidos en una Determinada Fecha Relacion Para dpto Actuarialbus_rep_sol_pro **** */

/* **** reporte de relacion NC **** */

function bus_rep_excel_notacredito(){
dateField1=$F('dateField1');
dateField2=$F('dateField2');
ente=$F('ente');
forma_pago=$F('forma_pago');
sucursal=$F('sucursal');

url='views06/irep_excel_notacredito.php?dateField1='+dateField1+'&dateField2='+dateField2+'&sucursal='+sucursal+'&forma_pago='+forma_pago+'&ente='+ente;
  imprimir(url);
}


/* **** fin reporte de relacion NC **** */
/* **** reporte de relacion de recibo islr **** */

function bus_rep_excel_islr(){
dateField1=$F('dateField1');
dateField2=$F('dateField2');
sucursal=$F('sucursal');
tipo_cheque=$F('prov');
proveedor=$F('proveedor');
recibo_che=$F('recibo_che');
banco=$F('banco');

url='views06/irep_excel_islr.php?fechainicio='+dateField1+'&fechafin='+dateField2+'&sucursal='+sucursal+'&tipo_cheque='+tipo_cheque+'&recibo_che='+recibo_che+'&banco='+banco+'&proveedor='+proveedor;
  imprimir(url);
}


/* **** fin reporte relacion de recibo islr **** */
/* **** reporte de relacion de recibo iva **** */

function bus_rep_excel_iva(){
dateField1=$F('dateField1');
dateField2=$F('dateField2');
sucursal=$F('sucursal');
tipo_cheque=$F('prov');
proveedor=$F('proveedor');
recibo_che=$F('recibo_che');
banco=$F('banco');

url='views06/irep_excel_iva.php?fechainicio='+dateField1+'&fechafin='+dateField2+'&sucursal='+sucursal+'&tipo_cheque='+tipo_cheque+'&recibo_che='+recibo_che+'&banco='+banco+'&proveedor='+proveedor;
  imprimir(url);
}


/* **** fin reporte relacion de recibo iva **** */

/* **** reporte de relacion de cotizacion **** */

function bus_rel_coti(){

	if ((Field.present('dateField1')==false) || (Field.present('dateField2')==false))
	{

		alert ("Campos Vacios Todos son Obligatorios");
		}
		else
		{
dateField1=$F('dateField1');
dateField2=$F('dateField2');
sucursal=$F('sucursal');
url='views06/rep_rel_cotiz1.php?dateField1='+dateField1+'&dateField2='+dateField2+'&sucursal='+sucursal;
  imprimir(url);
}
}


/* **** fin reporte relacion de cotizacion **** */

/* **** reporte de codificacion de servicios y baremos **** */

function rep_baremo1(){
new Ajax.Request("views06/rep_baremo1.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'tbaremo='+$F('tbaremo'),
       onComplete: mos_bus_rep_baremo1
    });
	var1 = document.getElementById('bus_rep_baremo1'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_rep_baremo1(req){
  $('bus_rep_baremo1').innerHTML= req.responseText;
}

function rep_baremo2(){
new Ajax.Request("views06/bus_rep_baremo_exa.php",
    {
       method:'post',
       asynchronous: true,
       postBody: 'tbaremo='+$F('tbaremo')+'&baremo1='+$F('baremo1'),
       onComplete: mos_bus_rep_baremo2
    });
	var1 = document.getElementById('bus_rep_baremo2'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function mos_bus_rep_baremo2(req){
  $('bus_rep_baremo2').innerHTML= req.responseText;
}

function bus_rep_excel_baremo(){
tbaremo=$F('tbaremo');
baremo1=$F('baremo1');

url='views06/irep_excel_baremo_exa.php?tbaremo='+tbaremo+'&baremo1='+baremo1;
  imprimir(url);
}

/* **** fin de reporte de codificacion de servicios y baremos **** */

/* **** reporte Exportar a Excel la Morbilidad Para Auditar el Proceso **** */

function bus_rep_excel_morbi(){
dateField1=$F('dateField1');
dateField2=$F('dateField2');
id_proveedorc=$F('id_proveedorc');
id_proveedorp=$F('id_proveedorp');
url='views01/irep_excel_morbi.php?fechainicio='+dateField1+'&fechafin='+dateField2+'&proveedorc='+id_proveedorc+'&proveedor='+id_proveedorp;
  imprimir(url);
}


/* **** fin reporte Exportar a Excel la Morbilidad Para Auditar el Proceso **** */
