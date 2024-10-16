 <?php 
 include ("../../lib/jfunciones.php");
sesion();
 $_s = "098675647,3.,2";
        
        if( preg_match("/^([0-9,\.])([^a-zA-Z])+$/", $_s) )
        {
            echo 'Correcto';   
        }
        
        ?>