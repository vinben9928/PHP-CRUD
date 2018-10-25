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

                //Iterate the data.
                for(var i = 0; i < count; i++) {
                    //Insert a new row for the user.
                    var user = data[i];
                    var id = user.id;
                    var row = table.insertRow(-1);

                    row.id = "r" + user.id;

                    //Insert cells for the user content.
                    row.insertCell(-1).innerText = user.id;
                    row.insertCell(-1).innerText = user.firstName;
                    row.insertCell(-1).innerText = user.lastName;
                    row.insertCell(-1).innerText = user.email;
                    
                    //Create Edit and Delete links.
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

                    //Append links to final cell.
                    buttonCell.appendChild(editButton);
                    buttonCell.appendChild(separatorSpan);
                    buttonCell.appendChild(deleteButton);
                }
            }

            function editUser() {
                //Get the row to be edited.
                var id = this.getAttribute("targetId");
                var targetRow = document.getElementById("r" + id);

                if(isNull(targetRow)) {
                    alert("Invalid selection!");
                    return;
                }
                
                //Insert a new row with text boxes.
                var table = document.getElementById("userTable");
                var editRow = table.insertRow(targetRow.rowIndex);

                editRow.id = "e" + id;

                //Set the first cell to the user ID (not changeable).
                editRow.insertCell(-1).innerText = id;

                const ids = ["fn", "ln", "em"];
                const cells = [];

                //Add the original row's cells' to an array.
                for(key in targetRow.cells) {
                    cells.push(targetRow.cells[key]);
                }

                //Create the text boxes for each column and set their texts to that of the original row's cells.
                for(var i = 0; i < 3; i++) {
                    var textBox = document.createElement("input");
                    textBox.id = ids[i] + id;
                    textBox.type = (ids[i] === "em" ? "email" : "text");
                    textBox.value = cells[i + 1].innerText;

                    //textBox.style.width = cells[i + 1].offsetWidth + "px";
                    textBox.style.display = "inline";
                    textBox.style.boxSizing = "border-box";

                    textBox.required = true;

                    editRow.insertCell(-1).appendChild(textBox);
                }

                //Create Save and Cancel links.
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

                //Hide the original row.
                targetRow.style.display = "none";                
            }

            function cancelEditUser() {
                var id = this.getAttribute("targetId");
                var table = document.getElementById("userTable");
                var targetRow = document.getElementById("r" + id);
                var editRow = document.getElementById("e" + id);

                table.deleteRow(editRow.rowIndex);
                targetRow.style.display = "";
            }

            function submitEditUser() {
                //Get the input elements.
                var id = this.getAttribute("targetId");
                var fnTextBox = document.getElementById("fn" + id);
                var lnTextBox = document.getElementById("ln" + id);
                var emTextBox = document.getElementById("em" + id);

                //Make sure they exist.
                if(isNull(fnTextBox) || isNull(lnTextBox) || isNull(emTextBox)) { alert("Page error!"); return; }
                
                //If any of the boxes are disabled a post is already in progress. Do not continue.
                if(fnTextBox.disabled == true || lnTextBox.disabled == true || emTextBox.disabled == true) {
                    return;
                }

                //Get the inputted values.
                var firstName = fnTextBox.value;
                var lastName = lnTextBox.value;
                var email = emTextBox.value;

                //Make sure they're set.
                if(isNull(fnTextBox) || isNull(lastName) || isNull(email) ||
                    firstName.length <= 0 || lastName.length <= 0 || email.length <= 0) {
                    
                    alert("Please fill in all the required fields!");
                    return;
                }

                //Disable the edit controls while posting.
                fnTextBox.disabled = true;
                lnTextBox.disabled = true;
                emTextBox.disabled = true;

                //Post edit data.
                $.post("<?php echo RELATIVE_DIR; ?>/controllers/user.php", {
                    editId: id,
                    firstName: firstName,
                    lastName: lastName,
                    email: email
                }, function(data) {
                    if(typeof data === "string") {
                        try {
                            data = JSON.decode(data);
                        }
                        catch(error) {
                            alert("ERROR: Invalid response received from server!");
                            
                            //Re-enable edit controls.
                            fnTextBox.disabled = false;
                            lnTextBox.disabled = false;
                            emTextBox.disabled = false;

                            return;
                        }
                    }

                    if(!isNull(data.error)) {
                        alert(data.error);

                        //Re-enable edit controls.
                        fnTextBox.disabled = false;
                        lnTextBox.disabled = false;
                        emTextBox.disabled = false;

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
                var id = this.getAttribute("targetId");
                $.post("<?php echo RELATIVE_DIR; ?>/controllers/user.php", { deleteId: id }, function(data) {
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
                        //Remove the row for the deleted user.
                        var table = document.getElementById("userTable");
                        var targetRow = document.getElementById("r" + id);
                        table.deleteRow(targetRow.rowIndex);
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