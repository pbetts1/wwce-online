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
        <link href="<?php echo __MEDIA_PATH__; ?>datepicker/css/datepicker.css" rel="stylesheet">
        <link href="<?php echo __MEDIA_PATH__; ?>datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
        <link href="<?php echo __MEDIA_PATH__; ?>select2/select2.css" rel="stylesheet">

        <script src="<?php echo __MEDIA_PATH__; ?>jquery/jquery-1.8.2.min.js"></script>
        <script src="<?php echo __MEDIA_PATH__; ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo __MEDIA_PATH__; ?>ckeditor/ckeditor.js"></script>
        <script src="<?php echo __MEDIA_PATH__; ?>datepicker/js/bootstrapdatepicker.js"></script>
        <script src="<?php echo __MEDIA_PATH__; ?>datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
        <script src="<?php echo __MEDIA_PATH__; ?>select2/select2.js"></script>

        <style>
            body {
                padding-top: 40px;
            }
            .list thead tr th .arrow {
                display: inline;
                float: right;
                width: 7px;
                height: 4px;
                margin-top: 7px;
                margin-right: 3px;
            }
            .list thead tr th .desc {
                background: url("<?php echo __MEDIA_PATH__; ?>icons/arrow_down_black.png") no-repeat right center;
            }

            .list thead tr th .asc {
                background: url("<?php echo __MEDIA_PATH__; ?>icons/arrow_up_black.png") no-repeat right center;
            }
            h2{
            	margin:0px 0px 10px 0px;
            }
        </style>

    </head>

    <body>
        <?php $this->com->load('main_menu'); ?>
        <?php $this->com->load('main_content'); ?>
    </body>
</html>
