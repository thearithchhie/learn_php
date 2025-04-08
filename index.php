<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./public/style.css">
    
</head>
<body>
    <div class="container">
        <h3 class="container-title">Add New User</h3>
        <form>
            <div class="frm-control">
                <div class="frm-input-control">
                    <div class="input-username">
                        <input type="text" class="frm-username" placeholder="Please enter your username" required/>
                    </div>
                    <div class="input-phone">
                        <input type="text" class="frm-phone" placeholder="Please enter your phone" required/>
                    </div>
                </div>
                <div class="btn-submit">
                    <input type="submit" value="submit" class="frm-btn-submit"/>
                </div>
            </div>
        </form>
        <h3 class="container-title">List All Users</h3>
        <?php
            include_once "./Database/Database.php";
            $db = new Database();
            $conn = $db->getConnection();

            $sql = "SELECT * FROM users";
            $result = mysqli_query($conn, $sql);

            echo "<table>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th>ID</th>";
                    echo "<th>Username</th>";
                    echo "<th>Phone</th>";
                    echo "<th>CreatedAt</th>";
                    echo "<th>Action</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            if (mysqli_num_rows($result) > 0) {
                while($row  = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                            echo "<td>".$row["id"]."</td>";
                            echo "<td>".$row["username"]."</td>";
                            echo "<td>".$row["phone"]."</td>";
                            echo "<td>".($row["created_at"] ? date("n/j/Y, g:i:s A", strtotime($row["created_at"])): "")."</td>";
                            // actions
                            echo "<td>";
                                echo "<div class='action-control'>";
                                echo "<a class='frm-view' data-id='".$row["id"]."'>"."<button>view</button>"."</a>";
                                echo "<a>"."<button class='frm-edit' data-id=".$row["id"].">edit</button>"."</a>";
                                echo "<a>"."<button class='btn-delete' data-id=".$row["id"].">delete</button>"."</a>";
                                echo "</div>";
                            echo "</td>";
                        echo "</tr>";
                    
                }
            }else {
                echo "<tr><td colspan='5' class='not-found'>User not found</td></tr>";
            }
            echo "</tbody>";
            echo "</table>";
        ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $(".frm-btn-submit").click(function(e) {
                e.preventDefault();
                let username = $(".frm-username").val();
                let phone = $(".frm-phone").val();
               $.ajax({
                    url: "curd.php?action=create",
                    type: "POST",
                    data: {
                        username: username,
                        phone: phone
                    },
                    success: function(res) {
                        let data = JSON.parse(res);
                        if (data && data.success) {
                            const createdAt = new Date(data.user.created_at); 
                            const formatDate = createdAt.toLocaleString();
                            const newRow = `
                                <tr>
                                    <td>${data.user.id}</td>
                                    <td>${data.user.username}</td>
                                    <td>${data.user.phone}</td>
                                    <td>${formatDate}</td>
                                    <td>
                                         <div class="action-control">
                                           <a href="crud.php?action=view&id=${data.user.id}" class='frm-view'><button>view</button></a>
                                           <a href=""><button>edit</button></a>
                                           <a href=""><button>delete</button></a>
                                         </div>
                                    </td>
                                </tr>
                            `;
                            $("table tbody").append(newRow);

                            // clear input
                            $(".frm-username").val("");
                            $(".frm-phone").val("");
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
               })
            });

        // view 
        $(document).on("click", ".frm-view", function (e) {
            e.preventDefault();
            const id = $(this).data("id");
            $.ajax({
                url: `curd.php?action=view&id=${id}`,
                type: "GET",
                success: function (response, textStatus) {
                   try{
                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                       if (response.success) {
                        const modalHTML = `
                            <div class="model">
                            <div class="model-content">
                                <h3>User Details</h3>
                                <hr/>
                                <p>ID: ${data.user.id}</p>
                                <p>Username: ${data.user.username}</p>
                                <p>Phone: ${data.user.phone}</p>
                                <p>CreatedAt: ${data?.user?.created_at ? new Date(data.user.created_at).toLocaleString(): ""}</p>
                                <button class="btn-close">Close</button>
                                </div>
                            </div>
                    `;
                    // Append modal to body
                    $("body").append(modalHTML);

                    //close model dialog
                    $(".btn-close").click(function(e) {
                        $(".model").remove();
                    })
                    
                      // Also close when clicking outside the modal content
                      $(".model").click(function(e) {
                        if (e.target === this) {
                            $(".model").remove();
                        }
                      });

                    }
                   }catch(e) {
                    console.error(e);
                   }
                },
                error: function (err) {
                    console.log({err})
                }
            })
        });

        //Update user
        $(".frm-edit").click(function(e) {
            const id = $(this).data('id');
            const username = $(this).data("username");
            $.ajax({
                url: `curd.php?action=view&id=${id}`,
                type: "GET",
                success: function(response) {
                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                    try {
                        const editHTML = `
                        <div class="edit-model">
                            <div class="edit-content">
                                <h3>Update user</h3>
                                <hr/>
                                <form class="edit-frm-control" method="POST" id="edit-frm">
                                    <input type="hidden" id="edit-id" value="${data.user.id}" />
                                    <label>Username</label>
                                    <input type="text" id="edit-username" value="${data.user.username}" /><br/>
                                    <label>Phone</label>
                                    <input type="text" id="edit-phone" value="${data.user.phone}" />
                                    <button id="edit-cancel">Cancel</button>
                                    <button id="edit-submit">Submit</button>
                                </form>
                            </div>
                        </div>
                    `;
                    $("body").append(editHTML);

                    
                    $("#edit-cancel").click(function(e) {
                       $(".edit-model").remove();
                    });

                    // case modify data 
                    $("#edit-frm").submit(function(e) {
                        e.preventDefault();
                        idEdit = $("#edit-id").val();
                        usernameEdit = $("#edit-username").val();
                        phoneEdit = $("#edit-phone").val();

                        if (usernameEdit == "" || phoneEdit == "") {
                            alert("Please full fill in all filed");
                            return;
                        }

                        $.ajax({
                            url: "curd.php?action=update",
                            method: "POST",
                            data: {
                                id: idEdit,
                                username: usernameEdit,
                                phone: phoneEdit
                            },
                            success: function (response) {
                                console.log(response);
                            },
                            error: function(e) {
                                console.error(e);
                            }
                        });

                        $("#edit-submit").click(function(e) {
                       $(".edit-model").remove();
                    });


                    });
                    } catch(e) {
                        console.error(e);
                    }
                },
                error: function(e) {
                    console.error(e);
                }
            });
        });

        // Delete
        $(".btn-delete").click(function(e) {
            const id = $(this).data("id");
            if(id) {
                deleteHTML = `
                    <div class="delete-modal">
                        <div class="delete-content">
                            <p class="delete-title">Are you want to delete ?</p>
                            <div class="delete-frm-control">
                                <button class="delete-btn-cancel">Cancel</button><br/>
                                <button class="delete-btn-delete">Delete</button>
                            </div>
                        </div>
                    </div>
                `;
                $("body").append(deleteHTML);
                $(".delete-btn-cancel").click(function(e) {
                    $(".delete-modal").remove();
                });
            }
        })

        });
    </script>
</body>
</html>