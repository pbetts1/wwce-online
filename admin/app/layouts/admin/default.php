<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="<?php echo __CHARSET__; ?>">
        <title><?php echo __LBL_PROJECT_NAME__; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="<?php echo __MEDIA_PATH__; ?>css/template.css" rel="stylesheet">
        <link href="<?php echo __MEDIA_PATH__; ?>css/style.css" rel="stylesheet">
        <style>
            body {
                padding-top: 40px;
            }
        </style>
        <script src="<?php echo __MEDIA_PATH__; ?>jquery/jquery-1.8.2.min.js"></script>
        <script src="<?php echo __MEDIA_PATH__; ?>bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php $this->com->load('main_menu'); ?>
        <?php $this->com->load('main_content'); ?>
    </body>
</html>
