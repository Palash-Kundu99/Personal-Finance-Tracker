<?php
include("session.php");

// Fetch data for charts and new features
$exp_category_query = mysqli_query($con, "SELECT expensecategory, SUM(expense) AS total_expense FROM expenses WHERE user_id = '$userid' GROUP BY expensecategory");

// Fetching daily expenses
$exp_date_query = mysqli_query($con, "SELECT DATE(expensedate) as expensedate, SUM(expense) AS total_expense FROM expenses WHERE user_id = '$userid' GROUP BY DATE(expensedate)");

$categories = [];
$category_expenses = [];
while ($row = mysqli_fetch_assoc($exp_category_query)) {
    $categories[] = $row['expensecategory'];
    $category_expenses[] = $row['total_expense'];
}

$dates = [];
$date_expenses = [];
while ($row = mysqli_fetch_assoc($exp_date_query)) {
    $dates[] = $row['expensedate'];
    $date_expenses[] = $row['total_expense'];
}
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
    .card a {
      color: #000;
      font-weight: 500;
    }
    .card a:hover {
      color: #28a745;
      text-decoration: dotted;
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
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
      <nav class="navbar navbar-expand-lg navbar-light border-bottom">
        <button class="toggler" type="button" id="menu-toggle" aria-expanded="false">
          <span data-feather="menu"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

        </div>
      </nav>
      <div class="container-fluid">
        <h3 class="mt-4">Dashboard</h3>
        <div class="row">
          <div class="col-md">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col text-center">
                    <a href="add_expense.php"><img src="icon/rupee.png" height = "70px" width="70px" />
                      <p>Add Expenses</p>
                    </a>
                  </div>
                  <div class="col text-center">
                    <a href="manage_expense.php"><img src="icon/ME.png" height = "70px" width="70px" />
                      <p>Manage Expenses</p>
                    </a>
                  </div>
                  <div class="col text-center">
                    <a href="profile.php"><img src="icon/DP.png" height = "70px" width="70px"/>
                      <p>User Profile</p>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <h3 class="mt-4">Full-Expense Report</h3>
        <div class="row">
          <div class="col-md">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title text-center">Daily Expenses</h5>
              </div>
              <div class="card-body">
                <canvas id="expense_line" height="150"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title text-center">Expense Category</h5>
              </div>
              <div class="card-body">
                <canvas id="expense_category_pie" height="150"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /#page-content-wrapper -->
  </div>
  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
  
  <script src="js/bootstrap.min.js"></script>
  <script src="js/Chart.min.js"></script>
  <!-- Menu Toggle Script -->
  <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
  </script>
  <script>
    feather.replace();
  </script>
  <script>
    const ctxPie = document.getElementById('expense_category_pie').getContext('2d');
    new Chart(ctxPie, {
      type: 'pie',
      data: {
        labels: <?php echo json_encode($categories); ?>,
        datasets: [{
          label: 'Expense by Category',
          data: <?php echo json_encode($category_expenses); ?>,
          backgroundColor: [
            '#6f42c1',
            '#dc3545',
            '#28a745',
            '#007bff',
            '#ffc107',
            '#20c997',
            '#17a2b8',
            '#fd7e14',
            '#e83e8c',
            '#6610f2'
          ],
          borderWidth: 1
        }]
      }
    });

    const ctxLine = document.getElementById('expense_line').getContext('2d');
    new Chart(ctxLine, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($dates); ?>,
        datasets: [{
          label: 'Expense by Day',
          data: <?php echo json_encode($date_expenses); ?>,
          borderColor: '#adb5bd',
          backgroundColor: 'rgba(109, 117, 125, 0.2)',
          fill: true,
          borderWidth: 2
        }]
      }
    });
  </script>
  <!-- Footer -->
<footer class="bg-dark text-white mt-4">
  <div class="container py-4">
    <div class="row">
      <div class="col-md-4">
        <h5>𝐄𝐱𝐩𝐞𝐧𝐬𝐞 𝐌𝐚𝐧𝐚𝐠𝐞𝐫</h5>
        <p>Your trusted tool for managing finances efficiently. Keep track of your expenses, plan your budget, and achieve your financial goals.</p>
      </div>
      <div class="col-md-4">
        <h5>𝐐𝐮𝐢𝐜𝐤 𝐋𝐢𝐧𝐤𝐬</h5>
        <ul class="list-unstyled">
          <li><a href="index.php" class="text-white">Dashboard</a></li>
          <li><a href="add_expense.php" class="text-white">Add Expenses</a></li>
          <li><a href="manage_expense.php" class="text-white">Manage Expenses</a></li>
          <li><a href="budget_planning.php" class="text-white">Budget Planning</a></li>
          <li><a href="financial_goals.php" class="text-white">Financial Goals</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h5>𝐎𝐟𝐟𝐢𝐜𝐞</h5>
        <ul class="list-unstyled">
          <li><span data-feather="map-pin"></span> ANO 717</li>
          <li><span data-feather="phone"></span> Astra Towers, Action Area IIC, New Town, West Bengal 700135</li>
          <li><span data-feather="mail"></span> support@expensemanager.com</li>
        </ul>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col text-center">
        <p class="mb-0">&copy; 2024 Expense Manager by PK. All Rights Reserved.</p>
      </div>
    </div>
  </div>
</footer>

</body>
</html>
