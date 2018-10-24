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
                $.ajax({
                    url: "<?php echo RELATIVE_DIR; ?>/controllers/user.php",
                    type: "GET",
                    cache: false,
                    success: printData
                });
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

                    row.id = "r" + user.id;

                    row.insertCell(-1).innerText = user.id;
                    row.insertCell(-1).innerText = user.firstName;
                    row.insertCell(-1).innerText = user.lastName;
                    row.insertCell(-1).innerText = user.email;
                    
                    var buttonCell = row.insertCell(-1);
                    var separatorSpan = document.createElement("span");
                    var editButton = document.createElement("a");
                    var deleteButton = document.createElement("a");
                    
                    separatorSpan.innerText = " | ";

                    editButton.innerText = "Edit";
                    editButton.href = "javascript:void(0)";
                    editButton.addEventListener("click", editUser);
                    editButton.setAttribute("targetId", id);
                    
                    deleteButton.innerText = "Delete";
                    deleteButton.href = "javascript:void(0)";
                    deleteButton.addEventListener("click", deleteUser);
                    deleteButton.setAttribute("targetId", id);

                    buttonCell.appendChild(editButton);
                    buttonCell.appendChild(separatorSpan);
                    buttonCell.appendChild(deleteButton);
                }
            }

            function editUser() {
                var id = this.getAttribute("targetId");
                var targetRow = document.getElementById("r" + id);

                if(isNull(targetRow)) {
                    alert("Invalid selection!");
                    return;
                }
                
                var table = document.getElementById("userTable");
                var editRow = table.insertRow(targetRow.rowIndex);

                editRow.insertCell(-1).innerText = id;

                const ids = ["fn", "ln", "em"];
                const cells = [];

                for(key in targetRow.cells) {
                    cells.push(targetRow.cells[key]);
                }

                for(var i = 0; i < 3; i++) {
                    var textBox = document.createElement("input");
                    textBox.id = ids[i] + id;
                    textBox.type = "text";
                    textBox.value = cells[i + 1].innerText;

                    editRow.insertCell(-1).appendChild(textBox);
                }

                var buttonCell = editRow.insertCell(-1);
                var separatorSpan = document.createElement("span");
                var saveButton = document.createElement("a");
                var cancelButton = document.createElement("a");
                
                separatorSpan.innerText = " | ";

                saveButton.innerText = "Save";
                saveButton.href = "javascript:void(0)";
                saveButton.addEventListener("click", submitEditUser);
                saveButton.setAttribute("targetId", id);
                    
                cancelButton.innerText = "Cancel";
                cancelButton.href = "javascript:void(0)";
                cancelButton.addEventListener("click", cancelEditUser);
                cancelButton.setAttribute("targetId", id);

                buttonCell.appendChild(saveButton);
                buttonCell.appendChild(separatorSpan);
                buttonCell.appendChild(cancelButton);

                targetRow.style.display = "none";                
            }

            function cancelEditUser() {

            }

            function submitEditUser() {
                //TODO: Additional data.
                $.post("<?php echo RELATIVE_DIR; ?>/controllers/user.php", { editId: this.getAttribute("targetId") }, function(data) {
                    if(typeof data === "string") {
                        try {
                            data = JSON.decode(data);
                        }
                        catch(error) {
                            alert("ERROR: Invalid response received from server!");
                            return;
                        }
                    }

                    if(!isNull(data.error)) {
                        alert(data.error);
                        return;
                    }

                    if(!isNull(data.success) && data.success === true) {
                        window.location.reload(true);
                    }
                    else {
                        alert("An unknown error occurred!");
                    }
                });
            }

            function deleteUser() {
                $.post("<?php echo RELATIVE_DIR; ?>/controllers/user.php", { deleteId: this.getAttribute("targetId") }, function(data) {
                    if(typeof data === "string") {
                        try {
                            data = JSON.decode(data);
                        }
                        catch(error) {
                            alert("ERROR: Invalid response received from server!");
                            return;
                        }
                    }

                    if(!isNull(data.error)) {
                        alert(data.error);
                        return;
                    }

                    if(!isNull(data.success) && data.success === true) {
                        window.location.reload(true);
                    }
                    else {
                        alert("An unknown error occurred!");
                    }
                });
            }
        </script>
<?php
    require_once(dirname(__DIR__) . "/templates/global-bottom.php");
?>