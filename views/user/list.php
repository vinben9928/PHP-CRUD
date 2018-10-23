<?php
    $DOCUMENT_TITLE = "List of users";
    require_once(dirname(__DIR__) . "/templates/global-top.php");
?>
        <table id="userTable" style="border-spacing: 0">
            <tbody>
                <tr>
                    <th>ID</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>E-mail</th>
                    <th></th>
                </tr>
            </tbody>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {
                $.get("<?php echo RELATIVE_DIR; ?>/controllers/user.php", printData);
            });

            $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
                alert("An error occurred: " + thrownError);
            }); 

            function printData(data) {
                var table = document.getElementById("userTable");
                
                var count = data.length;
                for(var i = 0; i < count; i++) {
                    var user = data[i];
                    var id = user.id;
                    var row = table.insertRow(-1);

                    row.insertCell(-1).innerText = user.id;
                    row.insertCell(-1).innerText = user.firstName;
                    row.insertCell(-1).innerText = user.lastName;
                    row.insertCell(-1).innerText = user.email;
                    
                    var deleteButtonCell = row.insertCell(-1);
                    var deleteButton = document.createElement("button");
                    
                    deleteButton.innerText = "Delete";
                    deleteButton.addEventListener("click", function() { deleteUser(id); });

                    deleteButtonCell.appendChild(deleteButton);
                }
            }

            function deleteUser(id) {
                $.post("<?php echo RELATIVE_DIR; ?>/controllers/user.php", { deleteId: id }, function(data) {
                    console.log(data);
                });
            }
        </script>
<?php
    require_once(dirname(__DIR__) . "/templates/global-bottom.php");
?>