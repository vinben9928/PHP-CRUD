<?php
    $DOCUMENT_TITLE = "List of users";
    require_once(dirname(__DIR__) . "/templates/global-top.php");
?>

        <script type="text/javascript">
            $(document).ready(function() {
                $.get("<?php echo RELATIVE_DIR . "/controllers/user.php"; ?>", printData);
            });

            function printData(data) {
                console.log(data);
            }
        </script>
<?php
    require_once(dirname(__DIR__) . "/templates/global-bottom.php");
?>