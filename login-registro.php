<?php
    //error_reporting( E_ALL );
    //ini_set( 'display_errors' , true );
    //ini_set( 'display_startup_errors' , true );
    /*
        -------------------------------------
        Archivo de: Alejandro Díez
        GitHub: @adilosa95
        Proyecto: the-connect-house
        Nombre del archivo: login-registro.php
        -------------------------------------
    */
    session_start();
    require_once(__DIR__."/includes/header.php" );
    require_once(__DIR__."/includes/constantes.php" );
    //
    //Si estamos logueados nos redirige index
   if(isset( $_SESSION['idUsuario'] ))
    {
        //
        // logueado
        header( "location:/perfil.php" );
        return;
    }
    //
    //Configuramos los estilos que necesitamos
    $estilos = array(
        ESTILOS_WIDGETS,
        ESTILOS_LOGIN,
        ESTILOS_MENU
    );
    //
    //Generamos la cabecera
    cabecera(TITULO_LOGIN , $estilos ,false);
?>
    <body>
    <!--Menu-->
    <?php require_once(__DIR__ . '/includes/menu.php'); ?>
     <div class="content">
         <div class="contenedor-centro">
             <img src="img/img-modelo.jpg" alt="Habitacion" srcset="">
         </div>
         <div class="contenedor-izquierdo">
             <div class="into-contenedor">
                 <?php echo '<div id="marca">'.file_get_contents("img/marca/banner.svg").'</div>';
                 if(isset($_GET['Sesion']) == true)
                 {
                     echo '<p class="spanred">Por favor regístrate o inicia sesión para ir más allá</p>';
                 }
                 ?>
                 <!--Login-->
                 <form  method="post" id="login">
                     <label for="email">Correo electrónico</label><br>
                     <input type="email" id="email" name="email" class="credential" placeholder="Correo electronico"><br>
                     <label for="pass">Contraseña</label><br>
                     <input type="password" id="pass" name="pass" class="credential" placeholder="Contraseña"><br><br>
                     <div id="acciones">
                         <input type="button" id="bnLogin" value="Entrar" class="button" onclick="validarCamposLogin()">
                         <span id="registrarse">Resgistrarse</span>
                     </div>
                 </form>
                 <!--Registro-->
                 <form action="" method="post" id="registro">
                     <label for="nombre">Nombre <span class="spanred">*</span></label><br>
                     <input type="text" name="nombre" id="nombre" class="credential" placeholder="Nombre"><br>
                     <label for="apellidos">Apellidos <span class="spanred">*</span></label>
                     <input type="text" name="apellidos" id="apellidos" class="credential" placeholder="Apellidos"><br>
                     <label for="email">Correo electrónico <span class="spanred">*</span></label><br>
                     <input type="email" name="email" id="email" class="credential" placeholder="Correo electronico"><br>
                     <label for="sexo">Sexo <span class="spanred">*</span></label>
                     <select name="sexo" id="selector">
                         <option value="null">Seleccionar <span class="spanred">*</span></option>
                         <option value="m">Mujer</option>
                         <option value="h">Hombre</option>
                     </select><br>
                     <label for="ciudad">¿De donde eres?<span class="spanred">*</span></label>
                     <select name="ciudad" id="selectorciudad">
                         <option value="null">Seleccionar <span class="spanred">*</span></option>
                         <option value="Leon">León</option>
                         <option value="Ponferrada">Ponferrada</option>
                     </select>
                     <label for="pass">Contraseña  <span class="spanred">*</span></label><br>
                     <input type="password" name="password" id="password" class="credential" placeholder="Contraseña"><br>
                     <label for="pass">Repetir contraseña <span class="spanred">*</span></label><br>
                     <input type="password" name="password2" id="password2" class="credential" placeholder="Contraseña"><br><br>
                     <div id="acciones">
                         <span id="atras">Atrás</span>
                         <input type="button" id="bnRegistro" value="Registrarse" class="button" onclick="validarCamposRegistro()">
                     </div>
                 </form>
             </div>
         </div>
     </div>
    <?php  require_once(__DIR__."/includes/footer.php" );?>
    </body>
    <script src="<?php echo get_root_uri() ?>/js/validaciones/login-registro.js"></script>
    <script src="<?php echo get_root_uri() ?>/js/menu.js"></script>
    <script>
        //Nicializamos la pantalla de login
        $("#registro").css("display", "none");
        $('#registrarse').click(function ()
        {
            $("#login").fadeOut(100);
            $("#registro").delay(100).fadeIn(100);
        });
        $('#atras').click(function ()
        {
            $("#registro").fadeOut(100);
            $("#login").delay(100).fadeIn(100);
        });
    </script>
    </html>