<?php 
include ("../../lib/jfunciones.php");
sesion();
?>
<form id="my_form" name="form" action="views02/exclusionl3.php" method="POST" enctype="multipart/form-data" >
  <input class="tdtitulos" name="my_files" id="my_file" size="27" type="file" />
  <input type="button" name="action" value="Upload" onclick="redirect()"/> <br>
  <iframe id='my_iframe' name='my_iframe' width=300 height=27><font size="2" color="blue"></font></iframe>
 
</form>
