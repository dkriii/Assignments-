<?php 

session_start();
if(!isset($_SESSION["person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}

require 'database.php';
require 'functions.php';

$id = $_GET['id'];

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM persons where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($id));
$data = $q->fetch(PDO::FETCH_ASSOC);
Database::disconnect();

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link   href="css/bootstrap.min.css" rel="stylesheet">
		<script src="js/bootstrap.min.js"></script>
		<link rel="icon" href="cardinal_logo.png" type="image/png" />
	</head>

	<body>
		<div class="container">

			<div class="row">
				<h3>View Volunteer Details</h3>
			</div>
			 
			<div class="form-horizontal" >
				
				<div class="control-group col-md-6">
				
					<label class="control-label">First Name</label>
					<div class="controls ">
						<label class="checkbox">
							<?php echo $data['fname'];?> 
						</label>
					</div>
					
					<label class="control-label">Last Name</label>
					<div class="controls ">
						<label class="checkbox">
							<?php echo $data['lname'];?> 
						</label>
					</div>
					
					<label class="control-label">Email</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['email'];?>
						</label>
					</div>
					
					<label class="control-label">Mobile</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['mobile'];?>
						</label>
					</div>     
					
					<label class="control-label">Title</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['title'];?>
						</label>
					</div>   
					
					<!-- password omitted on Read/View -->
					
					<div class="form-actions">
						<a class="btn" href="persons.php">Back</a>
					</div>
					
				</div>
	
				
				<div class="row">
					<h4>Events for which this Volunteer has been assigned</h4>
				</div>
				
				<?php
					$pdo = Database::connect();
					$sql = "SELECT * FROM assignments, events WHERE assign_event_id = events.id AND assign_per_id = " . $id . " ORDER BY event_date ASC, event_time ASC";
					$countrows = 0;
					foreach ($pdo->query($sql) as $row) {
						echo Functions::dayMonthDate($row['event_date']) . ': ' . Functions::timeAmPm($row['event_time']) . ' - ' . $row['event_location'] . ' - ' . $row['event_description'] . '<br />';
						$countrows++;
					}
					if ($countrows == 0) echo 'none.';
				?>
				
			</div>  <!-- end div: class="form-horizontal" -->

		</div> <!-- end div: class="container" -->
		
	</body> 
	
</html>