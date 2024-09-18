<?php
include("session.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        $goal_id = $_POST['delete'];
        $query = "DELETE FROM financial_goals WHERE id = '$goal_id' AND user_id = '$userid'";
        mysqli_query($con, $query);
    } elseif (isset($_POST['add_goal'])) {
        $goal_name = $_POST['goal_name'];
        $target_amount = $_POST['target_amount'];
        $current_amount = $_POST['current_amount'];

        $query = "INSERT INTO financial_goals (user_id, goal_name, target_amount, current_amount) VALUES ('$userid', '$goal_name', '$target_amount', '$current_amount')";
        mysqli_query($con, $query);
    }
}

// Fetch existing financial goals
$goals_query = mysqli_query($con, "SELECT id, goal_name, target_amount, current_amount FROM financial_goals WHERE user_id = '$userid'");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Expense Manager - Dashboard</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Feather JS for Icons -->
    <script src="js/feather.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }

        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card-header {
            background-color: #343a40;
            color: #fff;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-title {
            margin-bottom: 0;
            font-size: 1.25rem;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 50px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-danger {
            border-radius: 50px;
            padding: 5px 15px;
        }

        .form-control {
            border-radius: 50px;
            padding: 10px 20px;
        }

        .table {
            margin-top: 20px;
        }

        .table th {
            border-top: none;
            font-weight: bold;
        }

        .table td {
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f1f3f5;
        }
    </style>
</head>

<body>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="border-right" id="sidebar-wrapper">
            <div class="user">
                <img class="img img-fluid rounded-circle" src="<?php echo $userprofile ?>" width="120">
                <h5><?php echo $username ?></h5>
                <p><?php echo $useremail ?></p>
            </div>
            <div class="sidebar-heading">Management</div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action"><span data-feather="home"></span> Dashboard</a>
                <a href="add_expense.php" class="list-group-item list-group-item-action sidebar-active"><span data-feather="plus-square"></span> Add Expenses</a>
                <a href="manage_expense.php" class="list-group-item list-group-item-action"><span data-feather="dollar-sign"></span> Manage Expenses</a>
            </div>
            <div class="sidebar-heading">Reports</div>
      <div class="list-group list-group-flush">
        <a href="budget_planning.php" class="list-group-item list-group-item-action "><span data-feather="calendar"></span> Budget Planning</a>
        <a href="financial_goals.php" class="list-group-item list-group-item-action "><span data-feather="target"></span> Financial Goals</a>
      </div>
            <div class="sidebar-heading">Settings </div>
            <div class="list-group list-group-flush">
                <a href="profile.php" class="list-group-item list-group-item-action "><span data-feather="user"></span> Profile</a>
                <a href="logout.php" class="list-group-item list-group-item-action "><span data-feather="power"></span> Logout</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Financial Goals</h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label for="goal_name">Goal Name:</label>
                        <input type="text" id="goal_name" name="goal_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="target_amount">Target Amount:</label>
                        <input type="number" id="target_amount" name="target_amount" class="form-control" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="current_amount">Current Amount:</label>
                        <input type="number" id="current_amount" name="current_amount" class="form-control" step="0.01" required>
                    </div>
                    <button type="submit" name="add_goal" class="btn btn-success">Add Goal</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">Existing Goals</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Goal Name</th>
                            <th>Target Amount</th>
                            <th>Current Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($goals_query)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['goal_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['target_amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['current_amount']); ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="delete" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
