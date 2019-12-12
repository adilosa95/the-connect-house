<?php
//error_reporting( E_ALL );
//ini_set( 'display_errors' , true );
//ini_set( 'display_startup_errors' , true );
/*
    -------------------------------------
    Archivo de: Alejandro Díez
    GitHub: @adilosa95
    Proyecto: the-connect-house
    Nombre del archivo: piso.php
    -------------------------------------
*/
	require_once(__DIR__."/includes/header.php" );
	require_once(__DIR__."/includes/constantes.php" );
	require_once(__DIR__."/includes/carrusel-de-img.php");
	require_once(__DIR__."/includes/sesion.php");
    //
    //Acceso a datos
    require_once(__DIR__."/bd/bd_usuario.php");
    require_once(__DIR__."/bd/bd_pisos.php");
    require_once(__DIR__."/bd/bd_imagenespiso.php");
    require_once(__DIR__."/bd/bd_secciones.php");
	require_once(__DIR__."/bd/bd_ocupado.php");
	//
    //Configuramos los estilos que necesitamos
    $estilos = array(
		 ESTILOS_WIDGETS ,
		 ESTILOS_MAIN ,
		 INCLUD_SLIDE
	);
    //
    //Generamos la cabecera
	cabecera( TITULO_LOGIN , $estilos , true );
	//
    //Respuesta del GET
    if( $_GET )
    {
        //Decodificamos la URL
        $urldecode = base64_decode( $_SERVER['REQUEST_URI'] );
        //Sacar el valor de la ID
        $get = explode( 'idPiso=', $urldecode );
        $idPisoHabitacion = $get[1];
    }
    else
    {
        //
        //Si intentamos entrar sin una petición get nos redirecciona
        header( "location:/the-connect-house/perfil.php" );
        return;
    }

    //
    //Accedemos a los datos del piso o Habitacion
    $oDbPisoHabitacion = new Pisos();
    $aDatosPisosHabitaciones  = $oDbPisoHabitacion->getById($idPisoHabitacion);
    //
    //Accedemos al 1 que son las comodidades
    $odbComodidades = new Secciones(1);
    //Sacamos todos los registros
    $oComodidades = $odbComodidades->getById($idPisoHabitacion);
    //Sacamos las normas
    $odbNormas = new Secciones(2);
    //Sacamos todos los registros
    $oNormas = $odbNormas->getById($idPisoHabitacion);
    //
    //Sacamos el perfil del Vendedor

?>
<style>
    /*Para maquetar las caracteristicas del piso o habitacion*/
    .contenedor-izquierdo
    {
        height: 971px;
    }
    .contenedor-centro
    {
        margin: 0% 10% 0% 36%;
    }
</style>
<body>
<?php
    //
    //Formamos un array con las imagenes del piso
	$oDbImagenes = new Imagenes();
	$aImagenesPiso = $oDbImagenes->getByIdPiso($idPisoHabitacion);
	//
    //Pasamos las imagenes para el carrusel
	foreach( $aImagenesPiso as $aImagenePiso )
	{
		 getCarrusel( $aImagenePiso->Url ) ;
	}
?>
    <div class="content">
        <div class="atras" onclick="javascript:window.history.back()">
            <div class="flecha">&#8592; Atrás</div>
        </div>
        <div class="likein">
            <?php echo '<div class="likeinpiso">'.file_get_contents("img/iconos-materiales/like.svg").'</div>'; ?>
        </div>
                <?php
                    //Incializamos estas varbles, necesitaremos la latitud y longitud fuera del foreach, para darselo al mapa
                $lt = 0.00;
                $lg = 0.00;
                foreach ( $aDatosPisosHabitaciones as $aDatosPisosHabitacion)
                {
                    //Asignamos datos
                    $lt = $aDatosPisosHabitacion->Latitud;
                    $lg = $aDatosPisosHabitacion->Longitud;
                    //Mostramos el usuario
                    $oDbUsuarios = new Usuario();
                    //Accedemos al usuario del piso
                    $aDbUsuarios = $oDbUsuarios->getById( $aDatosPisosHabitacion->idUsuario );
                    //
                    //Permiso de edición
	                $Permiso = false;
                    if($aDatosPisosHabitacion->idUsuario == $_SESSION['idUsuario'])
                    {
	                    $Permiso = true;
                    }
                    //
                    //Recorremos sus datos
                    foreach( $aDbUsuarios as $aDbUsuario)
                    {
	                    $Html = ' <div class="contenedor-izquierdo">';
	                    $Html .=    '<div id="perfil">';
	                    $Html .=        '<img id="user" src="'.$aDbUsuario->Imgperfil.'" alt="">';
	                    $Html .=        '<h3>'.$aDbUsuario->Nombre.'</h3>';
	                    $Html .=        '<h2>'.$aDatosPisosHabitacion->Precio.' €/Mes</h2>';
	                    $Html .=        '<button class="button">Me interesa</button>';
	                    //
                        //Si es true podemos editar el piso
	                    if( $Permiso == true )
                        {
	                        $Html1 .= '<div class="editar-piso"><i class="fas fa-pen"></i> Editar</div>';
                        }
	                    $Html .=    '</div>';
	                    $Html .= '</div>';
                    }
	                //
                    //Datelles del piso
                    $Html .= '<div class="contenedor-centro">';
                        $Html .= '<div class="into-centro">';
                        $Html .= '<h1>'.$aDatosPisosHabitacion->Calle.'</h1>';
                        $Html .= '<p><i class="fas fa-map-marker-alt"></i> '.$aDatosPisosHabitacion->Calle.','.$aDatosPisosHabitacion->Ciudad.'</p><br>';
                        $Html .= '<div class="caracteristicas">';
                        $Html .= '<h3> Características </h3>';
                        $Html .= '<p><i class="fas fa-bath"></i>  '.$aDatosPisosHabitacion->NBanos.' Baños</p>';
                        $Html .= '<p><i class="fas fa-bed"></i> '.$aDatosPisosHabitacion->NHabitaciones.'  Habitaciones</p>';
                        //
                        //Si es una habitacion
                        if( $aDatosPisosHabitacion->Tipo == 2)
                        {
                            //Accedemos a lagente que tiene en el piso
                            $odbOcupado = new Ocupado();
                            $aOcupados = $odbOcupado->getById( $aDatosPisosHabitacion->idPiso );
                            foreach( $aOcupados as $aOcupado)
                            {
                                $sSexo = '';
                                if( $aOcupado->Num == 'M' )
                                {
                                    $sSexo = 'Chicos';
                                }
                                else
                                {
                                    $sSexo = 'Chicas';
                                }
                                //
                                //Muestra la gente
                                $Html .= '<p><i class="fas fa-child"></i>'.$sSexo.' : '.$aOcupado->Num.'</p>';
                            }
                        }
                        //
                        $Html .= '</div>';
                        $Html .= '<br>';
                        $Html .= '<h3 class="title">Descripción</h3>';
                        $Html .= '<p>'.$aDatosPisosHabitacion->Descripcion.'</p>';
                        //
                        //Si hay comodidades asignadas
                        if($oComodidades != null)
                        {
                            $Html .= '<h3 class="title">Comodidades</h3>';
                            $Html .= '<div class="comodidad">';
                            foreach ($oComodidades as $oComodidad)
                            {
                                $Html .= '<div class="comodidades">';
                                $Html .= '<img src="'.$oComodidad->Imagen.'">';
                                $Html .= '<p>'.$oComodidad->Nombre.'</p>';
                                $Html .= '</div>';
                            }
                            $Html .= '</div>';
                        }
                        //
                        //Si hay normas asignadas
                        if($oNormas != null)
                        {
                            $Html .= '<h3 class="title">Normas</h3>';
                            $Html .= '<div class="norma">';
                            foreach ($oNormas as $oNorma)
                            {
                                $Html .= '<div class="normas">';
                                $Html .= '<img src="'.$oNorma->Imagen.'">';
                                $Html .= '<p>'.$oNorma->Nombre.'</p>';
                                $Html .= '</div>';
                            }
                            $Html .= '</div>';
                        }
                        //
                        //Asignamos el mapa
                        $Html .= '<div id="mapid"></div>';
                        //
                        $Html .= '</div>';
	                $Html .= '</div>';
	                //
                    //Mostramos en pantalla los datos
                    echo $Html;
                }
                ?>
    </div>
<?php  require_once(__DIR__."/includes/footer.php"); ?>
</body>
<!-- Script necesarios -->
<script src="<?php echo get_root_uri() ?>/the-connect-house/js/slider.js"></script>
<script src="<?php echo get_root_uri() ?>/the-connect-house/js/mapa.js"></script>
<script>
    //Quitamos el scroll del mapa
    mymap.scrollWheelZoom.disable();
    //Quitamos el doble click del mapa
    mymap.doubleClickZoom.disable();
    //Quitamos el arrastrar del mapa
    mymap.dragging.disable();
    //Quitamos el clikc en el mapa para que no agrege marcas
    mymap.off('click');
    //Visualizamos esas coordenadas en el mapa
    mymap.panTo([<?php echo $lt ?> , <?php echo $lg ?>]);
    //Ponemos una marca en el mapa con las coordenadas
    L.marker([<?php echo $lt ?> , <?php echo $lg ?>]).addTo(mymap);
    //
    //Scroll del vendedOr
    $(document).scroll(function()
    {
        //Cuando el contenedor llega al footer queda quieto para no sobrepasarlo
        if($('#perfil').offset().top + $('#perfil').height() > $('footer').offset().top )
        {
            $('#perfil').css('position', 'absolute');
            $('#perfil').css('left', '110px');
            $('#perfil').css('top', '110%');
        }
        //Si la altura de la pantalla mas el scroll es menos que el footer el perfil y el presio se moverá
        if($(document).scrollTop() + window.innerHeight < $('footer').offset().top)
        {
            $('#perfil').css('position', 'fixed');
            $('#perfil').css('left', '110px');
            $('#perfil').css('top', '');
        }
        var height = $(window).height();
        $('#div2').height(height);
    });
</script>
</html>