<?php
include ("../lib/jfunciones.php");

echo cabecera(sistema);
?>

<link href="../public/stylesheets/auth.css" rel="stylesheet" type="text/css" media="screen" />

<section id="loginPage">

  <!-- formulario de inicio de sesion -->
  <section class="centrar">
  	<form method="POST" action="login.php" name="login" onSubmit="return entrar()">

      <!-- imagen portada -->
      <section class="contenedor_imagen">
        <img src="../public/images/diseño_navidad_basico.gif">
      </section>

		<p class="texto">
			<?php echo $_REQUEST['mensaje']; ?>
		</p>

		<section class="formulario_botones">
			<?php
			for($i=10;$i>=0;$i--) {
				if(!empty($_SESSION['enlace'.$i]) && !empty($_SESSION['boton'.$i])){ 
					echo "
						<a href=\"".$_SESSION['enlace'.$i]."\" class=\"entrar\">".$_SESSION['boton'.$i]."</a>
						";
					unset($_SESSION['enlace'.$i]);
					unset($_SESSION['boton'.$i]);
				}
			}
			?>
	</section>

    </form>
  </section>

</section>
