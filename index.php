<?php
    $errors = "";

    $db = mysqli_connect("localhost", "root", "", "todo");

    if(isset($_POST['submit'])){
        $task = $_POST['task'];
        if(empty($task)){
            $errors = "You must fill in the task";
        }else{
            mysqli_query($db, "INSERT INTO tasks (task) VALUES ('$task')");
            header('location: index.php');
        }
    }

    // delete task
    if(isset($_GET['del_task'])){
        $id = $_GET['del_task'];
        mysqli_query($db, "DELETE FROM tasks WHERE id=$id");
        header('location: index.php');
    }
    
    // edit task - show edit form
    $update = false;
    $task_id = 0;
    $task_text = "";
    
    if(isset($_GET['edit_task'])){
        $update = true;
        $task_id = $_GET['edit_task'];
        $result = mysqli_query($db, "SELECT * FROM tasks WHERE id=$task_id");
        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_array($result);
            $task_text = $row['task'];
        }
    }
    
    // update task
    if(isset($_POST['update'])){
        $id = $_POST['task_id'];
        $task = $_POST['task'];
        if(empty($task)){
            $errors = "You must fill in the task";
        }else{
            mysqli_query($db, "UPDATE tasks SET task='$task' WHERE id=$id");
            header('location: index.php');
        }
    }

    $tasks = mysqli_query($db, "SELECT * FROM tasks");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List Application</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="heading">
        <h2>My Todo List</h2>
    </div>

    <form method="POST" action="index.php">
        <?php if(isset($errors)){ ?>
            <p><?php echo $errors; ?></p>
        <?php } ?>
        <input type="text" name="task" class="task_input" placeholder="Enter a new task..." value="<?php echo $task_text; ?>">
        <?php if($update == true): ?>
            <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
            <button type="submit" class="update_btn" name="update"><i class="fas fa-edit"></i> Update Task</button>
        <?php else: ?>
            <button type="submit" class="add_btn" name="submit"><i class="fas fa-plus"></i> Add Task</button>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Task</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php $i = 1; while($row = mysqli_fetch_array($tasks)){ ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td class="task"><?php echo $row['task']; ?></td>
                    <td class="actions">
                        <a href="index.php?edit_task=<?php echo $row['id']; ?>" class="edit" title="Edit task"><i class="fas fa-edit"></i></a>
                        <a href="index.php?del_task=<?php echo $row['id']; ?>" class="delete" title="Delete task"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
            <?php $i++; } ?>
        </tbody>
    </table>
</body>
</html>
