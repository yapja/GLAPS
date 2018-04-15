    <?php 
	$page_title = "Audit Logs";
    include_once('../../includes/header_it.php');
	$account_ID = $_SESSION['account_ID'];

    $sql_user = "SELECT u.account_ID, a.username, u.session_start, u.session_end FROM user_log u
    INNER JOIN account a ON u.account_ID = a.account_ID ORDER BY user_log_ID DESC";
    $result_user = $con->query($sql_user);

    $sql_system = "SELECT s.account_ID, a.username, s.action, s.timestamp FROM system_log s
    INNER JOIN account a ON s.account_ID = a.account_ID ORDER BY system_log_ID DESC";
    $result_system = $con->query($sql_system);

?>
<form method="POST" class="form-horizontal">
	<div class="col-lg-12">
        <div class="col-lg-6">
            <h3>User Log<?php 
                    echo "
                        <a href='../../reports/userauditlogs.php?pid=$account_ID' class='btn btn-danger' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
                        ";
                    ?></h3>
            <table id="tblUsers" class="table table-hover">
                <thead>
                    <th>Username</th>
                    <th>Session in</th>
                    <th>Session out</th>
                </thead>
                <tbody>
                    <?php
                        while ($row = mysqli_fetch_array($result_user))
                        {
                            $username = $row['username'];
                            $session_start = $row['session_start'];
                            $session_end = $row['session_end'];

                            echo "
                                <tr>
                                    <td>$username</td>
                                    <td>$session_start</td>
                                    <td>$session_end</td>
                                </tr>
                            ";
                        }

                    ?>
                </tbody>
            </table>
            <script>
                $(document).ready( function() {
                    $('#tblUsers').dataTable({
                        "order": []
                    });
                });
            </script>
        </div>
        <div class="col-lg-6"> 
            <h3>System Log<?php 
                    echo "
                        <a href='../../reports/systemauditlogs.php?pid=$account_ID' class='btn btn-danger' target='_blank'><i class='fa fa-file-pdf-o'></i></a>
                        ";
                    ?></h3>
            <table id="tblUsers2" class="table table-hover">
                <thead>
                    <th>Username</th>
                    <th>Timestamp</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php
                        while ($row = mysqli_fetch_array($result_system))
                        {
                            $username = $row['username'];
                            $timestamp = $row['timestamp'];
                            $action = $row['action'];

                            echo "
                                <tr>
                                    <td>$username</td>
                                    <td>$timestamp</td>
                                    <td>$action</td>
                                </tr>
                            ";
                        }

                    ?>
                </tbody>
            </table>
            <script>
                $(document).ready( function() {
                    $('#tblUsers2').dataTable({
                        "order": []
                    });
                });
            </script>
        </div>
	</div>
</form>

<?php
	include_once('../../includes/footer.php');