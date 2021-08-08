<!DOCTYPE html>
<?php 
	require 'validator.php';
	require_once 'conn.php';
	require_once __DIR__ . '\..\environment.php';

?>
<html lang = "en">
	<head>
		<title>ניהול קבצים</title>
		<meta charset = "utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel = "stylesheet" type = "text/css" href = "css/bootstrap.css" />
		<link rel = "stylesheet" type = "text/css" href = "css/jquery.dataTables.css" />
		<link rel = "stylesheet" type = "text/css" href = "css/style.css" />
	</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top" style="background-color:<?php 	echo $_ENV["ENV_COLOR"];	?>;">
		<div class="container-fluid">
			<label class="navbar-brand" id="title">ניהול קבצים</label>
			<?php 
				$query = mysqli_query($conn, "SELECT * FROM `user` WHERE `user_id` = '$_SESSION[user]'") or die(mysqli_error());
				$fetch = mysqli_fetch_array($query);
			?>
			<ul class = "nav navbar-right">	
				<li class = "dropdown">
					<a class = "user dropdown-toggle" data-toggle = "dropdown" href = "#">
						<span class = "glyphicon glyphicon-user"></span>
						<?php 
							echo $fetch['firstname']." ".$fetch['lastname'];
						?>
						<b class = "caret"></b>
					</a>
				<ul class = "dropdown-menu">
					<li>
						<a href = "logout.php"><i class = "glyphicon glyphicon-log-out"></i> Logout</a>
					</li>
				</ul>
				</li>
			</ul>
		</div>
	</nav>
	<?php include 'sidebar.php'?>
	<div id = "content">
		<br /><br /><br />
		<div class="alert alert-info"><h3>פרמטרים</h3></div> 
		<button class="btn btn-success" data-toggle="modal" data-target="#form_modal"><span class="glyphicon glyphicon-plus"></span>הוספת פרמטר</button>
		<br /><br />
		<table id = "table" class="table table-bordered">
			<thead>
				<tr>
					<th>מזהה</th>
					<th>שם</th>
					<th>ערך</th>
					<th>סוג</th>
					<th>פעולה</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$query = mysqli_query($conn, "SELECT * FROM `parameters`") or die(mysqli_error());
					while($fetch = mysqli_fetch_array($query)){
				?>

					<tr class="del_user<?php echo $fetch['id']?>">
						<td><?php echo $fetch['id']?></td>
						<td><?php echo $fetch['name']?></td>
						<td><?php echo $fetch['value']?></td>
						<td><?php echo $fetch['type']?></td>
						<td><center><button class="btn btn-warning" data-toggle="modal" data-target="#edit_modal<?php echo $fetch['id']?>"><span class="glyphicon glyphicon-edit"></span> עריכה</button> 
						
							| <button class="btn btn-danger btn-delete" id="<?php echo $fetch['id']?>" type="button"><span class="glyphicon glyphicon-trash"></span> מחיקה</button></center></td>

					</tr>
					
					<div class="modal fade" id="edit_modal<?php echo $fetch['id']?>" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<form method="POST" action="update_parameter.php">	
									<div class="modal-header">
										<h4 class="modal-title">עדכון פרמטר</h4>
									</div>
									<div class="modal-body">
										<div class="col-md-3"></div>
										<div class="col-md-6">
											<div class="form-group">
												<label>שם</label>
												<input type="hidden" name="id" value="<?php echo $fetch['id']?>"/>
												<input type="text" name="name" value="<?php echo $fetch['name']?>" class="form-control" required="required"/>
											</div>
											<div class="form-group">
												<label>ערך</label>
												<input type="text" name="value" value="<?php echo $fetch['value']?>" class="form-control" required="required"/>
											</div>
											<div class="form-group">
												<label>סוג</label>
												<input type="text" name="type" value="<?php echo $fetch['type']?>" class="form-control" required="required"/>
											</div>
										</div>
									</div>
									<div style="clear:both;"></div>
									<div class="modal-footer">
										<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> סגירה</button>
										<button name="edit" class="btn btn-warning" ><span class="glyphicon glyphicon-save"></span> עדכון</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					
				
				<?php
					}
				?>
			</tbody>
		</table>
	</div>
	<div class="modal fade" id="modal_confirm" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">מערכת</h3>
				</div>
				<div class="modal-body">
					<center><h4 class="text-danger">בטוחים שרוצים למחוק?</h4></center>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">סגירה</button>
					<button type="button" class="btn btn-success" id="btn_yes">כן</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="form_modal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form method="POST" action="save_parameter.php">	
					<div class="modal-header">
						<h4 class="modal-title">הוספת פרמטר</h4>
					</div>
					<div class="modal-body">
						<div class="col-md-3"></div>
						<div class="col-md-6">
							<div class="form-group">
								<label>שם</label>
								<input type="text" name="name" class="form-control" required="required"/>
							</div>
							<div class="form-group">
								<label>ערך</label>
								<input type="text" name="value" class="form-control" required="required"/>
							</div>
							<div class="form-group">
								<label>סוג</label>
								<input type="text" name="type" class="form-control" required="required"/>
							</div>
						</div>
					</div>
					<div style="clear:both;"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> סגירה</button>
						<button name="save" class="btn btn-success" ><span class="glyphicon glyphicon-save"></span> שמירה</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div id = "footer">
		<label class = "footer-title">&copy; ניהול קבצים אבי דלל <?php echo date("Y", strtotime("+8 HOURS"))?></label>
	</div>
<?php include 'script.php'?>
<script type="text/javascript">
$(document).ready(function(){
	$('.btn-delete').on('click', function(){
		var id = $(this).attr('id');
		$("#modal_confirm").modal('show');
		$('#btn_yes').attr('name', id);
	});
	$('#btn_yes').on('click', function(){
		var id = $(this).attr('name');
		$.ajax({
			type: "POST",
			url: "delete_parameter.php",
			data:{
				id: id
			},
			success: function(){
				$("#modal_confirm").modal('hide');
				$(".del_user" + id).empty();
				$(".del_user" + id).html("<td colspan='6'><center class='text-danger'>Deleting...</center></td>");
				setTimeout(function(){
					$(".del_user" + id).fadeOut('slow');
				}, 1000);
			}
		});
	});
});
</script>	
</body>
</html>