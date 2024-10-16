<?php
include ("../../lib/jfunciones.php");



$variable = <<< EOF

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="clientes">
<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Registrar datos del Cliente</td>	</tr>	
	<tr>
		<td class="tdtitulos">C&eacute;dula</td>
		<td class="tdcampos"><input class="campos" type="text" name="nombres" maxlength=128 size=20 value=""></td>
		<td class="tdtitulos">Como titular</td>
		<td class="tdtitulos">Como Beneficiario</td>
		
	</tr>

	<tr>
		<td class="tdtitulos">Cédula</td>
		<td class="tdtitulos"><b>14106265</b></td>
	
			<td class="tdtitulos">Fecha de inclusión</td>
		<td class="tdcampos">
        	<select name="dia_in" class="campos">
	                <option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option selected value="29">29</option><option value="30">30</option><option value="31">31</option>

	        </select>
        	<select name="mes_in" class="campos">
	                <option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option selected value="10">10</option><option value="11">11</option><option value="12">12</option>

	        </select>
        	<select name="ano_in" class="campos">
	                <option value=""></option><option value="1900">1900</option><option value="1901">1901</option><option value="1902">1902</option><option value="1903">1903</option><option value="1904">1904</option><option value="1905">1905</option><option value="1906">1906</option><option value="1907">1907</option><option value="1908">1908</option><option value="1909">1909</option><option value="1910">1910</option><option value="1911">1911</option><option value="1912">1912</option><option value="1913">1913</option><option value="1914">1914</option><option value="1915">1915</option><option value="1916">1916</option><option value="1917">1917</option><option value="1918">1918</option><option value="1919">1919</option><option value="1920">1920</option><option value="1921">1921</option><option value="1922">1922</option><option value="1923">1923</option><option value="1924">1924</option><option value="1925">1925</option><option value="1926">1926</option><option value="1927">1927</option><option value="1928">1928</option><option value="1929">1929</option><option value="1930">1930</option><option value="1931">1931</option><option value="1932">1932</option><option value="1933">1933</option><option value="1934">1934</option><option value="1935">1935</option><option value="1936">1936</option><option value="1937">1937</option><option value="1938">1938</option><option value="1939">1939</option><option value="1940">1940</option><option value="1941">1941</option><option value="1942">1942</option><option value="1943">1943</option><option value="1944">1944</option><option value="1945">1945</option><option value="1946">1946</option><option value="1947">1947</option><option value="1948">1948</option><option value="1949">1949</option><option value="1950">1950</option><option value="1951">1951</option><option value="1952">1952</option><option value="1953">1953</option><option value="1954">1954</option><option value="1955">1955</option><option value="1956">1956</option><option value="1957">1957</option><option value="1958">1958</option><option value="1959">1959</option><option value="1960">1960</option><option value="1961">1961</option><option value="1962">1962</option><option value="1963">1963</option><option value="1964">1964</option><option value="1965">1965</option><option value="1966">1966</option><option value="1967">1967</option><option value="1968">1968</option><option value="1969">1969</option><option value="1970">1970</option><option value="1971">1971</option><option value="1972">1972</option><option value="1973">1973</option><option value="1974">1974</option><option value="1975">1975</option><option value="1976">1976</option><option value="1977">1977</option><option value="1978">1978</option><option value="1979">1979</option><option value="1980">1980</option><option value="1981">1981</option><option value="1982">1982</option><option value="1983">1983</option><option value="1984">1984</option><option value="1985">1985</option><option value="1986">1986</option><option value="1987">1987</option><option value="1988">1988</option><option value="1989">1989</option><option value="1990">1990</option><option value="1991">1991</option><option value="1992">1992</option><option value="1993">1993</option><option value="1994">1994</option><option value="1995">1995</option><option value="1996">1996</option><option value="1997">1997</option><option value="1998">1998</option><option value="1999">1999</option><option value="2000">2000</option><option value="2001">2001</option><option value="2002">2002</option><option value="2003">2003</option><option value="2004">2004</option><option value="2005">2005</option><option value="2006">2006</option><option value="2007">2007</option><option selected value="2008">2008</option><option value="2009">2009</option><option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option>

	        </select></td>
	</tr>	

<tr>
		<td class="tdtitulos">Nombres</td>
		<td class="tdcampos"><input class="campos" type="text" name="nombres" maxlength=128 size=20 value=""></td>
	
		<td class="tdtitulos">Apellidos</td>

		<td class="tdcampos"><input class="campos" type="text" name="apellidos" maxlength=128 size=20 value=""></td>
	</tr>		
	<tr>
	<td class="tdtitulos">Fecha de nacimiento</td>
		<td class="tdcampos">
        	<select name="dia" class="campos">
	                <option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option>

	        </select>
        	<select name="mes" class="campos">
	                <option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>

	        </select>
        	<select name="ano" class="campos">
			<option value=""></option>
			<option value="1913">1913</option>
			<option value="1914">1914</option>
			<option value="1915">1915</option>
			<option value="1916">1916</option>
			<option value="1917">1917</option>
			<option value="1918">1918</option>
			<option value="1919">1919</option>
			<option value="1920">1920</option>
			<option value="1921">1921</option>
			<option value="1922">1922</option>
			<option value="1923">1923</option>
			<option value="1924">1924</option>
			<option value="1925">1925</option>
			<option value="1926">1926</option>
			<option value="1927">1927</option>
			<option value="1928">1928</option>
			<option value="1929">1929</option>
			<option value="1930">1930</option>
			<option value="1931">1931</option>
			<option value="1932">1932</option>
			<option value="1933">1933</option>
			<option value="1934">1934</option>
			<option value="1935">1935</option>
			<option value="1936">1936</option>
			<option value="1937">1937</option>
			<option value="1938">1938</option>
			<option value="1939">1939</option>
			<option value="1940">1940</option>
			<option value="1941">1941</option>
			<option value="1942">1942</option><option value="1943">1943</option><option value="1944">1944</option><option value="1945">1945</option><option value="1946">1946</option><option value="1947">1947</option><option value="1948">1948</option><option value="1949">1949</option><option value="1950">1950</option><option value="1951">1951</option><option value="1952">1952</option><option value="1953">1953</option><option value="1954">1954</option><option value="1955">1955</option><option value="1956">1956</option><option value="1957">1957</option><option value="1958">1958</option><option value="1959">1959</option><option value="1960">1960</option><option value="1961">1961</option><option value="1962">1962</option><option value="1963">1963</option><option value="1964">1964</option><option value="1965">1965</option><option value="1966">1966</option><option value="1967">1967</option><option value="1968">1968</option><option value="1969">1969</option><option value="1970">1970</option><option value="1971">1971</option><option value="1972">1972</option><option value="1973">1973</option><option value="1974">1974</option><option value="1975">1975</option><option value="1976">1976</option><option value="1977">1977</option><option value="1978">1978</option><option value="1979">1979</option><option value="1980">1980</option><option value="1981">1981</option><option value="1982">1982</option><option value="1983">1983</option><option value="1984">1984</option><option value="1985">1985</option><option value="1986">1986</option><option value="1987">1987</option><option value="1988">1988</option><option value="1989">1989</option><option value="1990">1990</option><option value="1991">1991</option><option value="1992">1992</option><option value="1993">1993</option><option value="1994">1994</option><option value="1995">1995</option><option value="1996">1996</option><option value="1997">1997</option><option value="1998">1998</option><option value="1999">1999</option> <option value="2000">2000</option> <option value="2001">2001</option><option value="2002">2002</option><option value="2003">2003</option><option value="2004">2004</option><option value="2005">2005</option><option value="2006">2006</option><option value="2007">2007</option><option value="2008">2008</option><option value="2009">2009</option><option value="2010">2010</option> <option value="2011">2011</option> <option value="2012">2012</option>

	        </select></td>
	
		<td class="tdtitulos">Sexo</td>

		<td class="tdcampos">
		<select name="sexo" class="campos">
			<option value=1 >Masculino</option>
			<option value=0 selected>Femenino</option>
		</select></td>
	</tr>	
		
	<tr>
		<td class="tdtitulos">Correo Electrónico</td>

		<td class="tdcampos"><input class="campos" type="text" name="email" maxlength=255 size=20 value=""></td>
	
		<td class="tdtitulos">Teléfonos habitación</td>
		<td class="tdcampos"><input class="campos" type="text" name="telefonos" maxlength=64 size=20 value=""></td>
	</tr>
	<tr>
		<td class="tdtitulos">Otros teléfonos</td>

		<td class="tdcampos"><input class="campos" type="text" name="telefonos2" maxlength=64 size=20 value=""></td>
	
		<td class="tdtitulos">Teléfono celular</td>
		<td class="tdcampos"><input class="campos" type="text" name="celular" maxlength=32 size=20 value=""></td>
	</tr>

	<tr>
		<td class="tdtitulos">Estado Civil</td>

		<td class="tdcampos">
		<select name="estado_civil" class="campos">
		<option value="">-- Seleccione un estado Civil --</option>
		<option value="SOLTERO" >Soltero(a)</option>
		<option value="CASADO" >Casado(a)</option>
		<option value="DIVORCIADO" >Divorciado(a)<option>
		</select>

		</td>
	
		<td class="tdtitulos">Estudios</td>
		<td class="tdcampos">
		<select name="estudios" class="campos">
		<option value="">-- Seleccione un nivel educativo --</option>
		<option value="NINGUNO" >Ninguno</option>

		<option value="PRIMARIA" >Primaria</option>
		<option value="SECUNDARIA" >Secundaria</option>
		<option value="UNIVERSITARIOS" >Universitaros<option>
		<option value="POST-UNIVERSITARIOS" >Post-Universitaros<option>
		<option value="OTRO" >Otro<option>
		</select>

		</td>
</tr>
	<tr>

		<td class="tdtitulos">Profesión</td>
		<td class="tdcampos"><input class="campos" type="text" name="profesion" maxlength=255 size=20 value=""></td>
		
		<td class="tdtitulos">País</td>
		<td class="tdcampos">

                <SCRIPT type="text/javascript">
	                with (document) {
				writeln('<SELECT NAME="pais" onChange="cambio_estado(this.form)" CLASS="campos">');
				writeln("<option value=''>Seleccione el Pais</option>");
	                tot = Menu1Options.length;
                	for (i = 0; i < tot; i++){
				writeln(Menu1Options[i]);
	                }
			writeln("</SELECT>");
                }
		</SCRIPT>
		</td>
	
	
	</tr>
		

	<td class="tdtitulos">Estado</td>
		<td class="tdcampos">
                <SCRIPT type="text/javascript">
                                with (document) {
                                writeln('<SELECT NAME="estado" onChange="cambio_ciudad(this.form)" CLASS="campos">');
                                writeln("<option value=''>Seleccione el Estado</option>");
                        for (i = 0; i < maxLength; i++){
                                writeln("<OPTION>"+Menu3Options[i]+"</OPTION>");
                        }
                        writeln("</SELECT>");
                        for (i = maxLength; i > Menu2OriginalLength; i--){
                                forma.estado.options[i] = null;
                        }
                }
		</SCRIPT>

		</td>
		<td class="tdtitulos">Ciudad</td>
		<td class="tdcampos">
                <SCRIPT type="text/javascript">
                        with (document) {
                        writeln('<SELECT NAME="ciudad" CLASS="campos">');
                        writeln("<OPTION value=''>Seleccione la Ciudad</OPTION>");
                        for (i = 0; i < maxLength1; i++){
                                writeln("<OPTION>" +Menu3Options[i] + "</OPTION>");
                        }
                        writeln("</SELECT>");
                        for (i = maxLength1; i > Menu3OriginalLength; i--){
                                forma.ciudad.options[i] = null;
                        }
                }
		</SCRIPT>
		</td>


	
	</tr>	


	<tr>
		<td class="tdtitulos">Direccion habitación</td>
		<td class="tdcampos"><textarea class="campos" name="direccion" cols=30 rows=1></textarea></td>
		<td class="tdtitulos">Comentarios</td>
		<td  class="tdcampos"><textarea class="campos" name="comentarios" cols=30 rows=1></textarea></td>

	</tr>	
		




<tr>
		<td class="tdtitulos">Ente</td>

		<td align="left">
                <SCRIPT type="text/javascript">
	                with (document) {
			writeln('<SELECT NAME="ente" onChange="cambio_cargos(this.form)" CLASS="campos">');
	                tot_c = Menu1Options_c.length;
                	for (i = 0; i < tot_c; i++){
				writeln(Menu1Options_c[i]);
	                }
			writeln("</SELECT>");
                }
		</SCRIPT>
		</td>
	
		<td class="tdtitulos">Fecha de ingreso a la empresa</td>
		<td class="tdcampos">
        	<select name="dia_empresa" class="campos">

	                <option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option>

	        </select>
        	<select name="mes_empresa" class="campos">
	                <option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>

	        </select>
		<select name="ano_empresa" class="campos">
		<option value=""></option><option value="1900">1900</option><option value="1901">1901</option><option value="1902">1902</option><option value="1903">1903</option><option value="1904">1904</option><option value="1905">1905</option><option value="1906">1906</option><option value="1907">1907</option><option value="1908">1908</option><option value="1909">1909</option><option value="1910">1910</option><option value="1911">1911</option><option value="1912">1912</option><option value="1913">1913</option><option value="1914">1914</option><option value="1915">1915</option><option value="1916">1916</option><option value="1917">1917</option><option value="1918">1918</option><option value="1919">1919</option><option value="1920">1920</option><option value="1921">1921</option><option value="1922">1922</option><option value="1923">1923</option><option value="1924">1924</option><option value="1925">1925</option><option value="1926">1926</option><option value="1927">1927</option><option value="1928">1928</option><option value="1929">1929</option><option value="1930">1930</option><option value="1931">1931</option><option value="1932">1932</option><option value="1933">1933</option><option value="1934">1934</option><option value="1935">1935</option><option value="1936">1936</option><option value="1937">1937</option><option value="1938">1938</option><option value="1939">1939</option><option value="1940">1940</option><option value="1941">1941</option><option value="1942">1942</option><option value="1943">1943</option><option value="1944">1944</option><option value="1945">1945</option><option value="1946">1946</option><option value="1947">1947</option><option value="1948">1948</option><option value="1949">1949</option><option value="1950">1950</option><option value="1951">1951</option><option value="1952">1952</option><option value="1953">1953</option><option value="1954">1954</option><option value="1955">1955</option><option value="1956">1956</option><option value="1957">1957</option><option value="1958">1958</option><option value="1959">1959</option><option value="1960">1960</option><option value="1961">1961</option><option value="1962">1962</option><option value="1963">1963</option><option value="1964">1964</option><option value="1965">1965</option><option value="1966">1966</option><option value="1967">1967</option><option value="1968">1968</option><option value="1969">1969</option><option value="1970">1970</option><option value="1971">1971</option><option value="1972">1972</option><option value="1973">1973</option><option value="1974">1974</option><option value="1975">1975</option><option value="1976">1976</option><option value="1977">1977</option><option value="1978">1978</option><option value="1979">1979</option><option value="1980">1980</option><option value="1981">1981</option><option value="1982">1982</option><option value="1983">1983</option><option value="1984">1984</option><option value="1985">1985</option><option value="1986">1986</option><option value="1987">1987</option><option value="1988">1988</option><option value="1989">1989</option><option value="1990">1990</option><option value="1991">1991</option><option value="1992">1992</option><option value="1993">1993</option><option value="1994">1994</option><option value="1995">1995</option><option value="1996">1996</option><option value="1997">1997</option><option value="1998">1998</option><option value="1999">1999</option><option value="2000">2000</option><option value="2001">2001</option><option value="2002">2002</option><option value="2003">2003</option><option value="2004">2004</option><option value="2005">2005</option><option value="2006">2006</option><option value="2007">2007</option><option value="2008">2008</option><option value="2009">2009</option><option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option>

	        </select></td>
	</tr>	
	<tr>
	
	
		<td class="tdtitulos">Cargo</td>
		<td class="tdcampos">
                <SCRIPT type="text/javascript">
                                with (document) {
                                writeln('<SELECT NAME="cargo" CLASS="campos">');
                                writeln("<option value=''>Seleccione el cargo</option>");
                        for (i = 0; i < maxLength_c; i++){
                                writeln("<OPTION>"+Menu3Options_c[i]+"</OPTION>");
                       }
                        writeln("</SELECT>");
                        for (i = maxLength_c; i > Menu2OriginalLength_c; i--){
                                forma.cargo.options[i] = null;
                        }
                }
		</SCRIPT>
		</td>
	</tr>	
	<tr>

		<td class="tdtitulos">Sub-divisi&oacute;n</td>
		<td class="tdcampos">
		<select name="subdivision" class="campos">
			<option value="">Seleccione una subdivisi&oacute;n</option>
			<option value="1">ADM BOMBERO</option><option value="2">ADM POLICIA</option><option value="3">ADMINISTRATIVO</option><option value="4">ALUMNO</option><option value="5">ASEMED</option><option value="6">BOMBERO</option><option value="7">BRIGADA MERIDA</option><option value="8">BRIGADA TOVAR</option><option value="9">EMPLEADOS COORDINADOS</option><option value="20">EMPLEADOS COORDINADOS EDUCACION</option><option value="10">EMPLEADOS FIJOS</option><option value="11">ETIBEN</option><option value="12">JUBILADO</option><option value="17">OBRERO CONTRATADO</option><option value="19">OBRERO FIJO</option><option value="13">PERSONAL CONTRATADO</option><option value="14">PERSONAL CONTRATADO COORDINADO</option><option value="21">PERSONAL CONTRATADO COORDINADO ADMINISTRATIVO</option><option value="22">PERSONAL CONTRATADO COORDINADO(INST_Y_FACILIT)</option><option value="15">UNIFORMADO</option><option value="16">UNIMOVIL</option><option value="18">VOLUNTARIO</option>		</select>

		</td>
	
		<td class="tdtitulos">Estado del Cliente</td>
		<td class="tdcampos">
		<select name="estado_cliente" class="campos">
			<option value="1">EN LAPSO DE ESPERA</option><option value="4">ACTIVO</option><option value="5">EXCLUIDO</option><option value="6">JUBILADO</option><option value="7">FALLECIDO</option>		</select>

		</td>
	</tr>	
		<tr><td colspan="2"></td></tr>

	<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos">Registrar</td>
		<td class="tdtitulos">Actualizar</td>
		<td class="tdtitulos"><a href="#" OnClick="ir_principal();">salir</a></td>
		<td class="tdtitulos"></td>
	</tr>
</table>
  </form>


EOF;


  $traduce=array( 'á' => '&aacute;' , 'é' => '&eacute;' , 'í' => '&iacute;' , 'ó' => '&oacute;' , 'ú' => '&uacute;' , 'ñ' => '&ntilde;');
  $descripcionHtmlValida = strtr($variable, $traduce );

echo $descripcionHtmlValida;

?>

