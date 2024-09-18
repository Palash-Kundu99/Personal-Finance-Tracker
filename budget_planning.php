<?php
include("session.php");

// Handle deletion of a budget
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $expense_id = mysqli_real_escape_string($con, $_POST['delete']);
    $query = "DELETE FROM budgets WHERE id = '$expense_id' AND user_id = '$userid'";
    if (!mysqli_query($con, $query)) {
        echo "Error deleting record: " . mysqli_error($con);
    }
}

// Handle addition of a new budget
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_budget'])) {
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);

    $query = "INSERT INTO budgets (user_id, category, amount) VALUES ('$userid', '$category', '$amount')";
    if (!mysqli_query($con, $query)) {
        echo "Error adding budget: " . mysqli_error($con);
    }
}

// Fetch existing budgets
$budgets_query = mysqli_query($con, "SELECT id, category, amount FROM budgets WHERE user_id = '$userid'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Budget Planning</title>
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
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
        <a href="index.php" class="list-group-item list-group-item-action sidebar-active"><span data-feather="home"></span> Dashboard</a>
        <a href="add_expense.php" class="list-group-item list-group-item-action "><span data-feather="plus-square"></span> Add Expenses</a>
        <a href="manage_expense.php" class="list-group-item list-group-item-action "><span data-feather="dollar-sign"></span> Manage Expenses</a>
      </div>
      <div class="sidebar-heading">Reports</div>
      <div class="list-group list-group-flush">
        <a href="budget_planning.php" class="list-group-item list-group-item-action "><span data-feather="calendar"></span> Budget Planning</a>
        <a href="financial_goals.php" class="list-group-item list-group-item-action "><span data-feather="target"></span> Financial Goals</a>
      </div>
      <div class="sidebar-heading">Settings</div>
      <div class="list-group list-group-flush">
        <a href="profile.php" class="list-group-item list-group-item-action "><span data-feather="user"></span> Profile</a>
        <a href="logout.php" class="list-group-item list-group-item-action "><span data-feather="power"></span> Logout</a>
      </div>
    </div>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title text-center">Budget Planning</h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" id="category" name="category" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" id="amount" name="amount" class="form-control" step="0.01" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="add_budget" class="btn btn-primary">Add Budget</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-header">
                <h5 class="card-title text-center">Existing Budgets</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Amount</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($budgets_query)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td>₹<?php echo htmlspecialchars(number_format($row['amount'], 2)); ?></td>
                            <td class="text-center">
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
