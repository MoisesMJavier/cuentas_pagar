<?php

    require("head.php");

?>
    <style>
    html{
        height:100vh;
        width:100%;
        margin:0;
        padding:0;
    }
    body{
        background-image:url(http://cuentas.sisgph.com/img/proveedores.png);
        background-size:cover;
        background-position:center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
    .btn-cpp{
        background-color: #2c3e50;
        color: #ffffff;
    }
    .btn-cpp:hover{
        color: #2c3e50;
        background-color: #ffffff;
        box-shadow: inset 0px 1px 1px #2c3e50;
    }
    #imglogo{
        width:auto;
        height: 10%;
        margin: 1em auto;
        padding: 0;
    }
    form{
        background-color:rgba(255,255,255,0.90);
        padding:1em;
        margin:1em;
        border-radius:3px;
        box-shadow:0px 2px 2px #000000;
        height:auto;
        width:100%;
        overflow:auto;
    }
    #coltxt{
        background-color:rgba(255,255,255,0.90);
        padding:1em;
        margin:1em;
        border-radius:3px;
        box-shadow:0px 2px 2px #000000;
        text-align:justify;
        height:auto;
        width:100%;
        overflow:auto;
    }
    @media only screen and (max-width: 1024px) {
        #imglogo{
            width:80%;
            height:1%;
            margin: 1em auto;
            padding: 0;
        }
        #frmAddProv,#coltxt{
            width:100%;
            margin:0.5em auto;
        }
    }
    </style>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <div id="coltxt">
                <img src="https://www.ciudadmaderas.com/assets/img/logo.png" alt="Ciudad Maderas" class="img-responsive" title="Ciudad Maderas">
                <h4 class="text-center">Clave inválida.</h4>
                <h5 class="text-center">La clave fue utilizada con anterioridad.</h5>
            </div>
        </div>
    </div>
</div>
</body>
</html>