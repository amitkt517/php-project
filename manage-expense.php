<?php  
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['detsuid'] == 0)) {
    header('location:logout.php');
} else {
    // Code for deletion
    if (isset($_GET['delid'])) {
        $rowid = intval($_GET['delid']);
        $query = mysqli_query($con, "DELETE FROM tblexpense WHERE ID='$rowid'");
        if ($query) {
            echo "<script>alert('Record successfully deleted');</script>";
            echo "<script>window.location.href='manage-expense.php'</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again');</script>";
        }
    }

    // Code for updating expense
    if (isset($_POST['update'])) {
        $expenseId = intval($_POST['id']);
        $expenseItem = mysqli_real_escape_string($con, $_POST['expenseItem']);
        $expenseCost = mysqli_real_escape_string($con, $_POST['expenseCost']);
        $expenseDate = mysqli_real_escape_string($con, $_POST['expenseDate']);

        $query = mysqli_query($con, "UPDATE tblexpense SET ExpenseItem='$expenseItem', ExpenseCost='$expenseCost', ExpenseDate='$expenseDate' WHERE ID='$expenseId'");
        if ($query) {
            echo "<script>alert('Record updated successfully');</script>";
            echo "<script>window.location.href='manage-expense.php'</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again');</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Expense Tracker || Manage Expense</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/datepicker3.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <?php include_once('includes/header.php'); ?>
    <?php include_once('includes/sidebar.php'); ?>

    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="#">
                    <em class="fa fa-home"></em>
                </a></li>
                <li class="active">Expense</li>
            </ol>
        </div><!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Expense</div>
                    <div class="panel-body">
                        <p style="font-size:16px; color:red" align="center">
                            <?php if ($msg) {
                                echo $msg;
                            } ?>
                        </p>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered mg-b-0">
                                    <thead>
                                        <tr>
                                            <th>S.NO</th>
                                            <th>Expense Item</th>
                                            <th>Expense Cost</th>
                                            <th>Expense Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $userid = $_SESSION['detsuid'];
                                    $ret = mysqli_query($con, "SELECT * FROM tblexpense WHERE UserId='$userid'");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($ret)) {
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $row['ExpenseItem']; ?></td>
                                            <td><?php echo $row['ExpenseCost']; ?></td>
                                            <td><?php echo $row['ExpenseDate']; ?></td>
                                            <td>
                                                <a href="manage-expense.php?delid=<?php echo $row['ID']; ?>">Delete</a> | 
                                                <a href="#" data-toggle="modal" data-target="#editModal<?php echo $row['ID']; ?>">Edit</a>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal<?php echo $row['ID']; ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Expense</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?php echo $row['ID']; ?>">
                                                            <div class="form-group">
                                                                <label>Expense Item</label>
                                                                <input type="text" class="form-control" name="expenseItem" value="<?php echo $row['ExpenseItem']; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Expense Cost</label>
                                                                <input type="number" class="form-control" name="expenseCost" value="<?php echo $row['ExpenseCost']; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Expense Date</label>
                                                                <input type="date" class="form-control" name="expenseDate" value="<?php echo $row['ExpenseDate']; ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" name="update" class="btn btn-primary">Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Edit Modal -->
                                    <?php
                                        $cnt = $cnt + 1;
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- /.panel-->
            </div><!-- /.col-->
            <?php include_once('includes/footer.php'); ?>
        </div><!-- /.row -->
    </div><!--/.main-->

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
