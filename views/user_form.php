<?php
    $DOCUMENT_TITLE = "Register";
    require_once("templates/global-top.php");
?>
        <form action="../controllers/user.php" method="POST" onsubmit="onSubmit()">
            <input type="text" name="firstName" placeholder="First name"/><br/>
            <input type="text" name="lastName" placeholder="Last name"/><br/>
            <input type="email" name="email" placeholder="E-mail"/><br/>
            <input type="password" id="password" name="password" placeholder="Password"/><br/>
            <input type="password" id="repeatPassword" placeholder="Repeat password"/><br/>
            <input type="submit" value="Register"/>
        </form>

        <script type="text/javascript">
            function onSubmit(event) {
                if(document.getElementById("password").value !== document.getElementById("repeatPassword").value) {
                    event.preventDefault();
                    alert("Passwords doesn't match!");
                }
            }
        </script>
<?php
    require_once("templates/global-bottom.php");
?>