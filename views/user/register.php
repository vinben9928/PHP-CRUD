<?php
    $DOCUMENT_TITLE = "Register";
    require_once(dirname(__DIR__) . "/templates/global-top.php");
?>
        <form action="../controllers/user.php" method="POST" onsubmit="onSubmit(event)">
            <input type="text" name="firstName" placeholder="First name" required/><br/>
            <input type="text" name="lastName" placeholder="Last name" required/><br/>
            <input type="email" name="email" placeholder="E-mail" required/><br/>
            <input type="password" id="password" name="password" placeholder="Password" required/><br/>
            <input type="password" id="repeatPassword" placeholder="Repeat password" required/><br/>
            <input type="submit" value="Register"/>
        </form>

        <script type="text/javascript">
            function onSubmit(event) {
                if(document.getElementById("password").value !== document.getElementById("repeatPassword").value) {
                    event.preventDefault();
                    alert("The passwords doesn't match!");
                }
            }
        </script>
<?php
    require_once(dirname(__DIR__) . "/templates/global-bottom.php");
?>