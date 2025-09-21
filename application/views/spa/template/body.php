<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo APP_NAME . ' | ' . APP_DESCRIPTION ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/blog/custom.css">
        <script src="<?php echo base_url() ?>assets/js/blog/vue/vue.js"></script>
        <script src="<?php echo base_url() ?>assets/js/blog/vue/vue-router.js"></script>

        <?php
        meta_tags($title, $desc, $imgurl, $url);
        ?>

        <script>
            var BASE_URL = "<?php echo base_url() ?>";
        </script>

    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <?php $this->load->view("blog/template/header"); ?>
        <section class="container">
            <div id="post_search"></div>
          

            <div id="app">

                <nav>
                    <ul>
                        <li>
                        <router-link to="/">Home</router-link>
                        </li>
                        <li>
                        <router-link to="/about">About</router-link>
                        </li>
                    </ul>
                </nav>

                <router-view></router-view>
            </div>

        </section>

        <?php $this->load->view("blog/template/footer"); ?>

        <script src="<?php echo base_url() ?>assets/js/jquery-3.3.1.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery.toaster.js"></script>

        <script src="<?php echo base_url() ?>assets/js/blog/jquery-comments.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/blog/jquery-comments.css">

        <script src="<?php echo base_url() ?>assets/js/blog/vue/component/CategoryPost.js"></script>
        <script src="<?php echo base_url() ?>assets/js/blog/vue/component/SocialLinks.js"></script>
        <script src="<?php echo base_url() ?>assets/js/blog/vue/component/ImagePost.js"></script>
        <script src="<?php echo base_url() ?>assets/js/blog/vue/component/ListPost.js"></script>
        <script src="<?php echo base_url() ?>assets/js/blog/vue/component/DetailPost.js"></script>
        <script src="<?php echo base_url() ?>assets/js/blog/main.js"></script>
        <script src="<?php echo base_url() ?>assets/js/blog/vue/app.js"></script>
        <?php if ($this->session->flashdata("text") != null): ?>
            <script>
                $.toaster({
                    priority: '<?php echo $this->session->flashdata("type") ?>',
                    title: '<?php echo $this->session->flashdata("text") ?>',
                    message: ''});
            </script>
        <?php endif; ?>
    </body>
</html>
