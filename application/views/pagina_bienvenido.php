<?php
    require("head.php");
    require("menu_navegador.php");
?>  
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <img src="../img/logo.png" style="margin-top: 15%;" class="img-responsive" alt="Responsive image">
        </div>
    </div>
</div>
<?php
    if( $this->session->userdata("inicio_sesion")['rol'] === 'SO' ){
?>
<script>
	window.fwSettings={
	'widget_id':12000000466
	};
	!function(){if("function"!=typeof window.FreshworksWidget){var n=function(){n.q.push(arguments)};n.q=[],window.FreshworksWidget=n}}() 
</script>
<script type='text/javascript' src='https://widget.freshworks.com/widgets/12000000466.js' async defer></script>
<?php
    }
    require("footer.php");
?>
