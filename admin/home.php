<!DOCTYPE html>
<?php 
	require 'validator.php';
	require_once 'conn.php';
	require_once __DIR__ . '\..\function.php';
	require_once __DIR__ . '\..\environment.php';
?>
<html lang = "en">
	<head>
		<title>ניהול קבצים</title>
		<meta charset = "utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel = "stylesheet" type = "text/css" href = "css/bootstrap.css" />

	
		<link rel = "stylesheet" type = "text/css" href = "css/style.css" />
		 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

		<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
		 <?php 
		 		$CurDate = date('Y-m-d');

				$query = mysqli_query($conn, "SELECT (select count(*) as new from student st2 left join storage on storage.stud_no = st2.stud_no where Status = 'חדש' and st2.stud_no = st1.stud_no) as new, (select count(*) as new from student st2 left join storage on storage.stud_no = st2.stud_no where Status = 'השלמות' and st2.stud_no = st1.stud_no) as treat, firstname FROM 
					student st1 left join storage on storage.stud_no = st1.stud_no GROUP BY firstname,new,treat ") or die(mysqli_error());
				
			?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['עובדים', 'קבצים חדשים','השלמות'],
          <?php  
          	while($row = mysqli_fetch_array($query))
          	{

          		echo  "['".$row['firstname']."'," . $row['new'].",".$row['treat']."],";
          	}
       	?>
        ]);

        var options = {
          chart: {
            title: 'ביצועי עובדים',
            subtitle: 'טיפול בקבצים',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
    <style type="text/css">
		    	.files input {
		    outline: 2px dashed #92b0b3;
		    outline-offset: -10px;
		    -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
		    transition: outline-offset .15s ease-in-out, background-color .15s linear;
		    padding: 120px 0px 85px 35%;
		    text-align: center !important;
		    margin: 0;
		    width: 100% !important;
		}
		.files input:focus{     outline: 2px dashed #92b0b3;  outline-offset: -10px;
		    -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
		    transition: outline-offset .15s ease-in-out, background-color .15s linear; border:1px solid #92b0b3;
		 }
		.files{ position:relative}
		.files:after {  pointer-events: none;
		    position: absolute;
		    top: 60px;
		    left: 0;
		    width: 50px;
		    right: 0;
		    height: 56px;
		    content: "";
		    background-image: url(https://image.flaticon.com/icons/png/128/109/109612.png);
		    display: block;
		    margin: 0 auto;
		    background-size: 100%;
		    background-repeat: no-repeat;
		}
		.color input{ background-color:#f1f1f1;}
		.files:before {
		    position: absolute;
		    bottom: 10px;
		    left: 0;  pointer-events: none;
		    width: 100%;
		    right: 0;
		    height: 57px;
		    content: " or drag it here. ";
		    display: block;
		    margin: 0 auto;
		    color: #2ea591;
		    font-weight: 600;
		    text-transform: capitalize;
		    text-align: center;
		}
    </style>
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
						<a href = "logout.php"><i class = "glyphicon glyphicon-log-out"></i> התנתקות</a>
					</li>
				</ul>
				</li>
			</ul>
		</div>
	</nav>
	<?php include 'sidebar.php'?>
	<div id = "content">
		<br /><br /><br />
		<div class="alert alert-info"><h3>שיוך קבצים לעובדים</h3>
			<?php 
					$pdfdir = getparamvalue('pdfdirectory');
					if(file_exists($pdfdir))
					{
						//echo "$pdfdir";
						$fi = new FilesystemIterator($pdfdir, FilesystemIterator::SKIP_DOTS);
						$filter = new RegexIterator($fi, '/(msg|pdf)$/');
						printf("מספר הקבצים החדשים :  %d", iterator_count($filter));
					}
					/*
echo  '<br>'; 

$fileList = glob('C:\Users\avid\Documents\pdf/*.pdf');
foreach($fileList as $filename){
    //Use the is_file function to make sure that it is not a directory.
    if(is_file($filename)){
        echo $filename, '<br>'; 
    }   
}*/
			?>
					<form name="frm_upload" method="POST" enctype="multipart/form-data" action="save_file.php" accept-charset="utf-8">
					<?php $result = $conn->query("select * from Student"); ?>
					
					<div class="form-group files">
						<select name='stud_no' class="mdb-select md-form" required>
						<option name="stud_no" value=""></option>
					    <?php 

					    	while ($row = $result->fetch_assoc()) {

					                  unset($id, $name);
					                  $stud_no = $row['stud_no'];
					                  $name = $row['firstname']." " .$row['lastname']; 
					                  echo '<option name="stud_no" value="'.$stud_no.'">'.$name.'</option>';
					                 
							}
						?>
					    </select>
						<input class="form-control" type="file" name="file[]" size="4" style="background-color:#fff;" required="required" multiple accept="application/pdf|msg"/>
						</div>
					<br />
				
					<button class="btn btn-success btn-sm" name="save"><span class="glyphicon glyphicon-plus"></span>הוספת קבצים לעובדים</button>
				</form>

		</div> 


		<div class="alert alert-info"><h3>ביצועים</h3>


			<?php
			  $timezone = "Asia/Jerusalem";
			  date_default_timezone_set($timezone);
			  $today = date("Y-m-d");
			?>
		
				

	
		 <div id="columnchart_material" style="width: 800px; height: 500px;"></div>
		 </div>
		<div class="alert alert-info"><h3>קבצים</h3>

		<div class="panel panel-default">

			<div class="panel-body">
				<table id="table" class="table table-bordered">
					<thead>
						<tr>
							<th align="right">שם הקובץ</th>
							<th>שם עובד</th>
							<th>תאריך העלאה</th>
							<th>סטטוס</th>
							<th>העברה/השלמה</th>
							<th>מספר תביעה</th>
							<th align="right">פעולה</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$query = mysqli_query($conn, "SELECT * FROM storage left join student on storage.stud_no = student.stud_no where Status <> 'טופל'") or die(mysqli_error());
							while($fetch = mysqli_fetch_array($query)){
						?>
							<tr class="del_file<?php echo $fetch['store_id']?>">
								<td><?php echo substr($fetch['filename'], 0 ,30)?></td>
								<td><?php echo $fetch['firstname']." ".$fetch['lastname'] ?></td>
								<td><?php echo $fetch['date_uploaded']?></td>
								<td id="status<?php echo $fetch['store_id']?>" ><?php echo $fetch['Status']?></td>
								<td><select id = 'stud_number' name='stud_number<?php echo $fetch['store_id']?>' class="mdb-select md-form" required>
									<?php 
										$result = $conn->query("select * from Student");
										echo'<option name="stud_no_Selected" value=""></option>';
										while ($row = $result->fetch_assoc()) {

												  unset($id, $name);
												  $stud_no = $row['stud_no'];
												  $name = $row['firstname']." " .$row['lastname']; 
												  echo "<option name='stud_no_".$stud_no." value='".$stud_no."'>".$stud_no."</option>";
												 
												 
										}
									?>
									</select>
								</td>
								<td><?php echo $fetch['integrationId']?></td>
								

								

								<?php $filepath = getparamvalue('pdfdirectory')."/".$fetch['stud_no']."/".$fetch['filename']?>
								<td>
								<button class="btn btn-danger btn_remove" type="button" id="<?php echo $fetch['store_id']?>"><span class="glyphicon glyphicon-trash"></span> הסרה</button> 
								<?php		
									$status = $fetch['Status'];
									if($status != "טופל")
									{
										
										echo " | <a href='download.php?store_id=".$fetch['store_id']."' class='btn btn-success'><span class='glyphicon glyphicon-download'></span> הורדה</a> | ";
										echo "<button class='btn btn-ok btn_completion' type'button' id='".$fetch['store_id']."'><span class='glyphicon glyphicon glyphicon-file'></span>השלמות</button> | ";
										echo "<button class='btn btn-ok btn_move' type='button' id='".$fetch['store_id']."'><span class='glyphicon glyphicon glyphicon-file'></span>העברה</button> ";
									}
									
								?>
									
									
									
							</tr>

						<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
		</div> 
	</div>
	</div>
	<div id = "footer">
		<label class = "footer-title">&copy; ניהול קבצים אבי דלל <?php echo date("Y", strtotime("+8 HOURS"))?></label>
	</div>
		<div class="modal fade" id="modal_remove" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">מערכת</h3>
				</div>
				<div class="modal-body">
					<center><h4 class="text-danger">האם למחוק את הקובץ?</h4></center>
				</div>
				<div class="modal-footer">
					<a type="button" class="btn btn-success" data-dismiss="modal">לא</a>
					<button type="button" class="btn btn-danger" id="btn_yes">כן</button>
				</div>
			</div>
		</div>
	</div>
<div class="modal fade" id="modal_completion" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">מערכת</h3>
				</div>
				<div class="modal-body">
					<center dir="rtl"><h4 class="text-danger">להעביר קובץ להשלמה?</h4></center>
				</div>
				<div class="modal-footer">
					<a type="button" class="btn btn-success" data-dismiss="modal">לא</a>
					<button type="button" class="btn btn-danger" id="btn_yes_compl">כן</button>
				</div>
			</div>
		</div>
	</div>
	
<div class="modal fade" id="modal_move" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">מערכת</h3>
			</div>
			<div class="modal-body">
				<center dir="rtl"><h4 class="text-danger">להעביר את הקובץ?</h4></center>
			</div>
			<div class="modal-footer">
				<a type="button" class="btn btn-success" data-dismiss="modal">לא</a>
				<button type="button" class="btn btn-danger" id="btn_yes_move">כן</button>
			</div>
		</div>
	</div>
</div>
<?php include 'script.php'?>	
<script type="text/javascript">

	$('.btn_remove').on('click', function(){
		var store_id = $(this).attr('id');
		$("#modal_remove").modal('show');
		$('#btn_yes').attr('name', store_id);
	});
	
	$('#btn_yes').on('click', function(){
		var id = $(this).attr('name');
		$.ajax({
			type: "POST",
			url: "remove_file.php",
			data:{
				store_id: id
			},
			success: function(data){
				$("#modal_remove").modal('hide');
				$(".del_file" + id).empty();
				$(".del_file" + id).html("<td colspan='4'><center class='text-danger'>מוחק...</center></td>");
				setTimeout(function(){
					$(".del_file" + id).fadeOut('slow');
				}, 1000);
			}
		});
	});

	$('.btn_completion').on('click', function(){
		var store_id = $(this).attr('id');
		$("#modal_completion").modal('show');
		$('#btn_yes_compl').attr('name', store_id);
	});

	$(document).ready(function(){

	
	$('#btn_yes_compl').on('click', function(){
		var id = $(this).attr('name');
		var stud_no = $('select[name=stud_number'+id+']').val(); 
		var status = document.getElementById('status'+id).innerHTML; 
		//var status  = $('status'+id).val();
		if(status == "טופל") {
		$("#modal_completion").modal('hide');
		alert("לא ניתן להעביר טופס שטופל");
			return false;
		}
		if(stud_no == "") {
		$("#modal_completion").modal('hide');
		alert("נא למלא שדה העברה/השלמה");
			return false;
		}
		
		$.ajax({
			type: "POST",
			url: "completion_file.php",
			data:{
				store_id: id,
				stud_no: stud_no
			},
			success: function(data){
				$("#modal_completion").modal('hide');
				                 console.log('my message' + data);
				var tr = $(".del_file" + id).html();
				$(".del_file" + id).empty();

				$(".del_file" + id).html("<td colspan='4'><center class='text-danger'>מעביר להשלמה...</center></td>");
				setTimeout(function(){
					$(".del_file" + id).fadeOut('slow');
				}, 1000);
				 window.location.reload();
				
			}
		});

	});
});
	$('.btn_move').on('click', function(){
		var store_id = $(this).attr('id');
		$("#modal_move").modal('show');
		$('#btn_yes_move').attr('name', store_id);
	});



	
	$('#btn_yes_move').on('click', function(){
		var id = $(this).attr('name');
		var stud_no = $('select[name=stud_number'+id+']').val(); 
		var status = document.getElementById('status'+id).innerHTML; 
		//var status  = $('status'+id).val();
		if(status == "טופל") {
		$("#modal_completion").modal('hide');
		alert("לא ניתן להעביר טופס שטופל");
			return false;
		}
		if(stud_no == "") {
		$("#modal_completion").modal('hide');
		alert("נא למלא שדה העברה/השלמה");
			return false;
		}
		
		$.ajax({
			type: "POST",
			url: "move_file.php",
			data:{
				store_id: id,
				stud_no: stud_no
			},
			success: function(data){
				$("#modal_move").modal('hide');
				                 console.log('my message' + data);
				var tr = $(".del_file" + id).html();
				$(".del_file" + id).empty();

				$(".del_file" + id).html("<td colspan='4'><center class='text-danger'>מעביר קובץ...</center></td>");
				setTimeout(function(){
					$(".del_file" + id).fadeOut('slow');
				}, 1000);
				 window.location.reload();
				
			}
		});

	});


	
</script>
</body>
</html>