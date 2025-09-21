<html>
    <head>
        <?php $this->load->view('email/css_email'); ?>
    </head>
    <body>
        <div class="container">

            <img class="photo" src="<?php echo base_url() ?>assets/img/logo.png">

            <br>

            <div class="header">
                <h1>Recuperación de cuenta</h1>
            </div>

            <p class="description">Hola estimado, </p>
            <p class="description">Hemos recibido una solicitud para recuperar el acceso a tu cuenta</p>
            <p class="description">Haz clic en el siguiente enlace:</p>
            <p class="description"><a href="<?php echo base_url() . $link ?>"><?php echo base_url() . $link ?></a></p>

        </div>
    </body>
</html>