<html>
    <head>
        <title>Vali Admin - Free Bootstrap 4 Admin Template</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">

        <!-- Para twitter -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:description" content="Las bebidas más frías del mundo" />
        <meta name="twitter:title" content="Neveras inteligentes para hostelería" />
        <meta name="twitter:image" content="https://lh3.googleusercontent.com/mAkLK_IkvOxzNxr3ILPS-5nAUpJWiPoIWAEYWd8oqlqjR4MErwXNRbid_EwZ_PaITnXdIB4f4cV8OJPtHFiJ5i1cgMdM3uYyBsqP=w1440-l80-sg-rp" />
        <!-- Para twitter opcional -->
        <meta property="twitter:site" content="@pratikborsadiya">
        <meta property="twitter:creator" content="@pratikborsadiya">



        <!-- Open Graph Meta general facebook, SMS -->
        <meta property="og:locale" content="es_ES" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Neveras inteligentes para hostelería" />
        <meta property="og:site_name" content="Wondercool | Neveras inteligentes" />
        <meta property="og:description" content="Hasta 150 caracteres " />
        <meta property="og:url" content="https://wondercool.eu/" />
        <meta property="og:image" content="http://pratikborsadiya.in/blog/vali-admin/hero-social.png">

        <!-- Revisar --> 
        <!-- Para facebook, SMS -->
        <meta property="og:image" content="https://wondercool.eu/wp-content/uploads/2019/01/logo-wondercool-redes-sociales-3-lineas-original-1200x630.png" />
        <meta property="og:image:secure_url" content="https://wondercool.eu/wp-content/uploads/2019/01/logo-wondercool-redes-sociales-3-lineas-original-1200x630.png" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Neveras para hostelería, Wondercool" />
        <!-- Para whatsapp -->
        <meta property="og:image" content="http://wondercool.eu/wp-content/uploads/2019/01/logo-wondercool-redes-sociales-3-lineas-original-300x300.png" />
        <meta property="og:image:secure_url" content="https://wondercool.eu/wp-content/uploads/2019/01/logo-wondercool-redes-sociales-3-lineas-original-300x300.png" />
        <meta property="og:image:type" content="image/png" />
        <meta property="og:image:width" content="300" />
        <meta property="og:image:height" content="300" />



        <!-- Main CSS-->
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <!-- Font-icon css-->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
</head>
<body>
    <form>
        <table>
            <tr>
                <td><input id="input1" class="myText" type="text" placeholder="Row 1" /></td>
                <td><button type="button" onclick="toggleEnable('input1')"> Enable/Disable </button></td>
            </tr>
            <tr>
                <td><input id="input2" class="myText" type="text" placeholder="Row 2" /></td>
                <td><button type="button" onclick="toggleEnable('input2')"> Enable/Disable </button></td>
            </tr>
        </table>
    </form>

    <div>
        <?php
        /*  $token = getTokenPayPal();
          dep($token);
          $rutapp = "https://api.sandbox.paypal.com/v2/checkout/orders/3VY149469P480882D";
          $contentType = "application/json";
          $requestApi = curlConectionGet($rutapp, $contentType, $token);
          dep($requestApi); */
        ?>

        <?php
        //dep($_SESSION['userData']); 
//Mediante $_SERVER[‘HTTP_CLIENT_IP’] verificamos si la IP es una conexión compartida.
//Mediante $_SERVER[‘HTTP_X_FORWARDED_FOR’] verificamos si la IP pasa por un proxy.
//Mediante $_SERVER[‘REMOTE_ADDR’] obtenemos la dirección IP desde la cual está viendo la página actual el usuario.

        dep('REMOTE_ADDR : ' . $_SERVER['REMOTE_ADDR']);
        $ipcli = $_SERVER['REMOTE_ADDR'];

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            dep('HTTP_CLIENT_IP : ' . $_SERVER['HTTP_CLIENT_IP']);
            $ipcli = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            dep('HTTP_X_FORWARDED_FOR : ' . $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ipcli = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
            dep('HTTP_X_FORWARDED : ' . $_SERVER['HTTP_X_FORWARDED']);
            $ipcli = $_SERVER['HTTP_X_FORWARDED'];
        }
        if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            dep('HTTP_FORWARDED_FOR : ' . $_SERVER['HTTP_FORWARDED_FOR']);
            $ipcli = $_SERVER['HTTP_FORWARDED_FOR'];
        }
        if (!empty($_SERVER['HTTP_FORWARDED'])) {
            dep('HTTP_FORWARDED : ' . $_SERVER['HTTP_FORWARDED']);
            $ipcli = $_SERVER['HTTP_FORWARDED'];
        }

        $meta = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR']));
        $latitud = $meta['geoplugin_latitude'];
        $longitud = $meta['geoplugin_longitude'];
        $ciudad = $meta['geoplugin_city'];
        dep($meta);

        //Uso de getenv()
        $ip = getenv('REMOTE_ADDR');
        dep($ip);

        echo 'IP del servidor: ' . $_SERVER['SERVER_ADDR'] . "<br/>"; //Imprime la IP del servidor
        echo 'nombre del servidor: ' . $_SERVER['SERVER_NAME'] . "<br/>"; //Imprime el nombre del servidor
        echo 'S.O y navegador del cliente: ' . $_SERVER['HTTP_USER_AGENT'] . "<br/>"; /* Imprime la información de S.O y navegador del cliente */


//$bdid = 9;
        //dep($data);
        //dep($data['info_empresa']); 
        dep($_SESSION);
        ?>
    </div>
</body>
<script type="text/javascript">

    function toggleEnable(id) {
        var textbox = document.getElementById(id);

        if (textbox.disabled) {
            // If disabled, do this 
            document.getElementById(id).disabled = false;
        } else {
            // Enter code here
            document.getElementById(id).disabled = true;
        }
    }
</script>



<!-- Google analytics script-->
<script type="text/javascript">
    if (document.location.hostname == 'pratikborsadiya.in') {
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
        ga('create', 'UA-72504830-1', 'auto');
        ga('send', 'pageview');
    }
</script>
</html>



