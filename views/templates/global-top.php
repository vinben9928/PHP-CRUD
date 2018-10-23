<?php
    require_once(dirname(dirname(__DIR__)) . "/lib/settings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title><?php echo (isset($DOCUMENT_TITLE) && strlen($DOCUMENT_TITLE) > 0 ? $DOCUMENT_TITLE : "(Untitled)"); ?></title>
        <script type="text/javascript" src="<?php echo RELATIVE_DIR; ?>/static/scripts/jquery-3.3.1.min.js"></script>
    </head>
    <body>
