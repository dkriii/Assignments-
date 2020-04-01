<?php

session_start();
if(!isset($_SESSION["person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
$sessionid = $_SESSION['person_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body style="background-color: lightblue !important";>
    <div class="container">

		<div class="row">
			<h3>Volunteers</h3>
		</div>
		<div class="row">
			<p>Each shift is 4 hours.</p>
			<p>
				<?php if($_SESSION['person_title']=='Administrator')
					echo '<a href="per_create.php" class="btn btn-primary">Add Volunteer</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<a href="persons.php">Volunteers</a> &nbsp;
				<a href="events.php">Events</a> &nbsp;
				<a href="assignments.php">AllShifts</a>&nbsp;
				<a href="assignments.php?id=<?php echo $sessionid; ?>">MyShifts</a>&nbsp;
			</p>
				
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'database.php';
						$pdo = Database::connect();
						$sql = 'SELECT `persons`.*, COUNT(`assignments`.assign_per_id) AS countAssigns FROM `persons` LEFT OUTER JOIN `assignments` ON (`persons`.id=`assignments`.assign_per_id) GROUP BY `persons`.id ORDER BY `persons`.lname ASC, `persons`.fname ASC';
						//$sql = 'SELECT * FROM persons ORDER BY `persons`.lname ASC, `persons`.fname ASC';
						foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							if ($row['countAssigns'] == 0)
								echo '<td>'. trim($row['lname']) . ', ' . trim($row['fname']) . ' (' . substr($row['title'], 0, 1) . ') '.' - UNASSIGNED</td>';
							else
								echo '<td>'. trim($row['lname']) . ', ' . trim($row['fname']) . ' (' . substr($row['title'], 0, 1) . ') - '.$row['countAssigns']. ' events</td>';
							echo '<td>'. $row['email'] . '</td>';
							echo '<td>'. $row['mobile'] . '</td>';
							echo '<td width=250>';
							# always allow read
							echo '<a class="btn" href="per_read.php?id='.$row['id'].'">Details</a>&nbsp;';
							# person can update own record
							if ($_SESSION['person_title']=='Administrator'
								|| $_SESSION['person_id']==$row['id'])
								echo '<a class="btn btn-success" href="per_update.php?id='.$row['id'].'">Update</a>&nbsp;';
							# only admins can delete
							if ($_SESSION['person_title']=='Administrator' 
								&& $row['countAssigns']==0)
								echo '<a class="btn btn-danger" href="per_delete.php?id='.$row['id'].'">Delete</a>';
							if($_SESSION["person_id"] == $row['id']) 
								echo " &nbsp;&nbsp;Me";
							echo '</td>';
							echo '</tr>';
						}
						Database::disconnect();
					?>
				</tbody>
			</table>
			
    	</div>
    </div> <!-- /container -->
  </body>
</html>