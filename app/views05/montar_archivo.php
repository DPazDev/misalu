<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <link href="../public/stylesheets/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
       <div id="container">
            <div id="header"><div id="header_left"></div>
            <div id="header_main">Cargar archivo</div><div id="header_right"></div></div>
            <div id="content">
                <form action="/misalu/app/views05/montar_archivo1.php" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
                     <p id="f1_upload_process">Loading...<br/><img src="../public/images/loader.gif" /><br/></p>
                     <p id="f1_upload_form" align="center"><br/>
                         <label>Archivo:  
                              <input name="myfile" type="file" size="30" />
                         </label>
                         <label>
                             <input type="submit" name="submitBtn" class="sbtn" value="Cargar" />
                         </label>
                     </p>
                     
                     <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
                 </form>
             </div>
 </body>     