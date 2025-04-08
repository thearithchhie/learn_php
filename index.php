<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos Form</title>
    <link rel="stylesheet" href="./public/css/style.css">
</head>
<body>
   <div class="container">
   <form method="POST" action="curd_todo.php">
       <label>Title</label>
        <input type="text" name="title" placeholder="Please enter title" required /> <br/><br/>
        <input type="checkbox" name="is_completed" id="completed-checkbox" />
        <label for="completed-checkbox">Completed</label> <br/><br/>
        <button type="submit" class="btn-submit">Add new todo</button>
    </form>
    <h3>TODOS List</h3>

    <?php 
        include_once "./database/Database.php";
        $db = new Database();
        $conn = $db->getConnection();

        $sql = "SELECT 
            t.id,
            t.title,
            t.is_completed,
            t.created_at,
            u.username
         FROM todos AS t
            INNER JOIN users AS u ON t.user_id = u.id
            WHERE deleted_at IS NULL
            ORDER BY t.id DESC
        ";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
               $is_completed = $row["is_completed"] ? "completed" : "";
               echo "<li class={$is_completed} style='text-decoration: line-through;'>";
                echo "<div class='container-frm'>";
                            $checked = $is_completed ? "checked" : "";
                        echo $row["id"]. "<input type='checkbox' {$checked} />";
                        echo "<div>";
                            echo "<span class='todo-title'>" . htmlspecialchars($row['title']) . "</span>";
                            echo "<span class=''>" .date("M d, Y h:i A", strtotime($row['created_at'])). "</span>";
                            echo "<span class=''>". " CreatedBy= "." ".$row["username"]. "</span>";
                        echo "</div>";
                    // Actions
                    echo "<div class='action-button'>"; 
                        echo "<a href='curd_todo.php?method=GET&action=view&id=".$row['id']."'>" .'<button>view</button>' ."</a>";
                        echo "<a href='curd_todo.php?method=GET&action=edit&id=".$row['id']."'>". "<button>edit</button>" ."</a>";
                        echo "<a href='curd_todo.php?method=GET&action=delete&id=".$row['id']."' onclick=\"return confirm('Are u want to delete ?')\">". "<button>delete</button>" ."</a>";
                    echo "</div>";
                echo "</div>";
               echo "</li>"; 
            }
            echo "</ul>";
        } else {
            echo "TODOS not found";
        }
    ?>
   </div>
</body>

<script>
    function viewTodo(id) {
       
     }
     
</script>
</html>




