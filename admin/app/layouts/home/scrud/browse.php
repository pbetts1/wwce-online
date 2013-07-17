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

        <script src="<?php echo __MEDIA_PATH__; ?>bootstrap/js/jquery-1.7.1.min.js"></script>
        <script src="<?php echo __MEDIA_PATH__; ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo __MEDIA_PATH__; ?>tiny_mce/tiny_mce.js"></script>
        <script src="<?php echo __MEDIA_PATH__; ?>datepicker/js/bootstrapdatepicker.js"></script>

        <style>
            body {
                padding-top: 40px;
                font-size: 13px !important;
            }
            .list thead tr th .arrow {
                display: inline;
                float: right;
                width: 7px;
                height: 4px;
                margin-top: 7px;
                margin-right: 3px;
            }
            .pagination{
                margin: 5px 0 !important;
            }
            .table{
                margin-bottom:5px;
            }
            .list thead tr th .desc {
                background: url("<?php echo __MEDIA_PATH__; ?>icons/arrow_down_black.png") no-repeat right center;
            }

            .list thead tr th .asc {
                background: url("<?php echo __MEDIA_PATH__; ?>icons/arrow_up_black.png") no-repeat right center;
            }
            input,select{
                margin-bottom: 0px !important;
            }
            .control-group {
                margin-bottom: 0px !important;
            }
            .search_form th{
                text-align:center; 
                color:#333333;
                text-shadow: 0 1px 0 #FFFFFF;  
                background-color: #e6e6e6;                                     
            }
            .form-horizontal .control-label{
                text-align: left !important;
            }
            .navbar-inner{
                height: 30px;
                min-height: 30px;
            }
            .navbar .nav > li > a{
                padding: 5px 8px;
                font-size: 13px;
            }
            .navbar .brand {
                padding: 3px 10px;
            }
            .navbar .divider-vertical{
                height: 29px;
            }
            legend{
                font-size: 18px;
                line-height: 30px;
            }
            select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
                font-size: 13px;
                height: 18px;
                line-height: 18px;
                margin-bottom: 5px;
                padding: 3px 5px;
            }

            label {
                font-size: 13px;
                margin-bottom: 0px;
            }
            .form-horizontal .control-label {
                padding-top: 0px;
            }

            select, input[type="file"] {
                height: 28px;
                line-height: 28px;
                font-size: 13px;
            }

            .control-group {
                margin-bottom: 5px;
            }
            .table-condensed td {
                padding: 2px 5px;
            }   
            .pagination ul > li > a, .pagination ul > li > span {
                padding: 3px 8px;
            }
            .input-append .add-on, .input-prepend .add-on {
                padding: 2px 5px;
            }
            .input-append, .input-prepend {
                margin-bottom: 0px;
            }
        </style>

    </head>

    <body>
        <?php $this->com->load('main_menu'); ?>
        <?php $this->com->load('main_content'); ?>
    </body>
</html>
