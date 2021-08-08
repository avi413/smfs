<!DOCTYPE html>
<?php 

	require 'validator.php';
	require_once 'admin/conn.php';
	require_once 'function.php';
	require_once 'environment.php';
?>
<html lang = "he" dir="rtl">
	<head>
		<title>ניהול סריקות תביעות</title>
		<meta charset = "utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel = "stylesheet" type = "text/css" href = "admin/css/bootstrap.css" />
		<link rel = "stylesheet" type = "text/css" href = "admin/css/jquery.dataTables.css" />
		<link rel = "stylesheet" type = "text/css" href = "admin/css/style.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="pdf.js"></script>
		<script src="pdf.worker.js"></script>
		<script src="pdfobject.min.js"></script>
		<script src="pdfobject.js"></script>
		pdfobject.min
		<style type="text/css">

#upload-button {
	width: 150px;
	display: block;
	margin: 20px auto;
}

#file-to-upload {
	display: none;
}

#pdf-main-container {

	margin: 20px auto;
}

#pdf-loader {
	display: none;
	text-align: center;
	color: #999999;
	font-size: 13px;
	line-height: 100px;
	height: 100px;
}

#pdf-contents {
	display: none;
}

#pdf-meta {
	overflow: hidden;
	margin: 0 0 20px 0;
}

#pdf-buttons {
	float: left;
}

#page-count-container {
	float: right;
}

#pdf-current-page {
	display: inline;
}

#pdf-total-pages {
	display: inline;
}

#pdf-canvas {
	border: 1px solid rgba(0,0,0,0.2);
	box-sizing: border-box;
}

#page-loader {
	height: 100px;
	line-height: 100px;
	text-align: center;
	display: none;
	color: #999999;
	font-size: 13px;
}
input[type=text], select {
  width: 100%;
  padding: 4px 10px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

#rcorners2 {
  width: 100%;
  padding: 4px 10px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  
}



</style>
	</head>
<body >
	
	<nav class="navbar navbar-default navbar-fixed-top" style="background-color:<?php 	echo $_ENV["ENV_COLOR"];	?>;">
		<div class="container-fluid">
			<label class="navbar-brand" id="title">ניהול קבצים</label>
		</div>
	</nav>
					<!-- insert in the document body -->


<div class="col-md-12">
	<?php
			$query = mysqli_query($conn, "SELECT * FROM `student` WHERE `stud_id` = '$_SESSION[student]'") or die(mysqli_error());
			$fetch = mysqli_fetch_array($query);

	?>

	


	<div class="col-md-6" style="margin-top:60px;">
		<div id="pdf-main-container">
			<div id="pdf-loader">טוען קובץ...</div>
			<div id="pdf-contents">
				<div id="pdf-meta">
					<div id="pdf-buttons">
						<button id="pdf-prev">הקודם</button>
						<button id="pdf-next">הבא</button>
					</div>
					<div id="page-count-container">עמוד <div id="pdf-current-page"></div> מתוך <div id="pdf-total-pages"></div></div>
				</div>
				<canvas id="pdf-canvas" width="850"></canvas>


				<div id="page-loader">טוען קובץ...</div>
			</div>
		</div>
	</div>	
	
	<div class="col-md-6" style="margin-top:60px;" dir="rtl" >
		<h4 ><label class="pull-right"><?php echo "שלום ". $fetch['firstname']." ".$fetch['lastname'];?></label><font bold color='red'><?php if (isset($_GET['arg1'])){echo "</br>         ".$_GET['arg1'];} ?></font>.</h4>
		

		<button class="btn btn-danger pull-right" data-toggle="modal" data-target="#modal_confirm"><span class="glyphicon glyphicon-log-out"></span> התנתקות</button>
		<div class="col-md-12" >

			<div class="panel panel-default">

				<div class="panel-body">
					<table id="table" class="table table-bordered" >
						<thead>
							<tr>
								<th align="right">שם הקובץ</th>
								
								<th>סטטוס</th>
								<th>להשלמה</th>
								<th>סוג קובץ</th>
								<th>מספר תביעה</th>
								<th>הערות</th>
								<th align="right">פעולה</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$err = "";
								$stud_no = $fetch['stud_no'];
								$query = mysqli_query($conn, "SELECT * FROM `storage` WHERE `stud_no` = '$stud_no' and(Status = N'חדש' or Status = N'השלמות')" ) or die(mysqli_error());
								while($fetch = mysqli_fetch_array($query)){
							?>
							<tr class="del_file<?php echo $fetch['store_id']?>">

								<td><?php echo substr($fetch['filename'], 0 ,30)?> <div><b>ת.סריקה: </b><?php echo date("m/d/Y  H:i:s", filemtime("c:/inetpub/wwwroot/SFMS_DEV/files/avid_DEV/fax.pdf")); ?></div><div><b>ת.העלאה: </b><?php echo $fetch['date_uploaded']?></div></td>

								<td><?php echo $fetch['Status'] ?></td>
								<td>
									<select id = 'stud_number' name='stud_number<?php echo $fetch['store_id']?>' class="mdb-select md-form" class="custom-select">
									<?php 
										$result = $conn->query("select * from Student");
										echo'<option name="stud_no_Selected" value=""></option>';
										while ($row = $result->fetch_assoc()) {

												  unset($id, $name);
												  $rep_stud_no = $row['stud_no'];
												  $name = $row['firstname']." " .$row['lastname']; 
												  echo "<option name='stud_no_".$rep_stud_no." value='".$rep_stud_no."'>".$rep_stud_no."</option>";
												
										}
									?>
									</select>
									
									<center><button class="btn btn-primary btn-sm btn_completion" type="button" id="<?php echo $fetch['store_id']?>"><span class="glyphicon glyphicon glyphicon-file"></span> העברה להשלמה</button>
								</td>
								<form name="call_siebel" method="POST" enctype="multipart/form-data" action="call_siebel.php" accept-charset="utf-8">
								<td>
								<select style="width: 130px;" id = 'doctype' name='doctype' class="mdb-select md-form" class="custom-select">
									<option name='doctype' value=''></option>
									<option name='doctype' value='קבלה על תשלום'>קבלה על תשלום</option>
									<option name='doctype' value='מייל'>מייל</option>
									<option name='doctype' value='אי הגשה'>אי הגשה</option>
									<option name='doctype' value='הודעה'>הודעה</option>
									</select>
									
									
								</td>
								<td>
										<?php  $filepath = dirname(getcwd(),1)."/".$_ENV['MYENV']."/files/".$stud_no."/".$fetch['filename'];  ?>					
										<input id="rcorners1" type="text" name="claim_num" value="" size="12" required />
										<?php $insetfilename = explode(".",$fetch['filename']); ?>
										<input name="filename" class="form-control" type="hidden"  value="<?php echo "$insetfilename[0]";  ?>" />
										<input name="filetype" class="form-control" type="hidden" value=<?php echo $insetfilename[1]  ?> />
										<input name="store_id" class="form-control" type="hidden" value=<?php echo $fetch['store_id'] ?> />
										<input name="stud_no" class="form-control" type="hidden" value=<?php echo $stud_no ?> />
										<input name="OriginalSendingDate" class="form-control" type="hidden"  value=<?php echo  date("m/d/Y H:i:s", filemtime($filepath)); ?> />
										<center><button class="btn btn-success btn-sm" name="save"><span class="glyphicon glyphicon-plus"></span>שיוך לתביעה</button>
									
								</td>
								</form>
								<td>
									<p id="rcorners2">
									<?php echo $fetch['note']?>
									</p>
									
									<button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit_modal<?php echo $fetch['store_id']?>"><span class="glyphicon glyphicon-edit"></span> עריכה</button>
															
									<div class="modal fade" id="edit_modal<?php echo $fetch['store_id']?>" aria-hidden="true">										
										<div class="modal-dialog modal-dialog-centered">
											<div class="modal-content">
												<form method="POST" action="edit_note.php">	
													<div class="modal-header">
														<h4 class="modal-title">הוספת הערה</h4>
													</div>
													<div class="modal-body">
														<div class="col-md-3"></div>
														<div class="col-md-6">
															<div class="form-group">
																<label>הערה</label>
																<select id = 'note' name='note' class="mdb-select md-form" class="custom-select">
																<?php 
																	$result = $conn->query("select * from parameters where name = 'note'");
																	echo'<option name="note_Selected" value=""></option>';
																	while ($row = $result->fetch_assoc()) {

																			  
																			  $value = $row['value'];
																			  
																			  echo "<option name='note' value='".$value."' required='required'>".$value."</option>";
																			
																	}
																?>
																</select>
																<input type="hidden" name="store_id" value="<?php echo $fetch['store_id']?>"/>
																
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
								</td>	
								<td>
									<form name="showpdf" method="POST" enctype="multipart/form-data" action="showpdf.php" accept-charset="utf-8">
										<input name="filename" class="form-control" type="hidden" value=<?php echo $fetch['filename']  ?> />
										<input name="stud_no" class="form-control" type="hidden" value=<?php echo $stud_no ?> />
										<button class="btn btn-show btn-sm" type="button" id="<?php echo $fetch['filename']?>"><span class="glyphicon glyphicon glyphicon-file"></span> הצג קובץ</button>								
									</form>
									</br>
									<a href="download.php?store_id=<?php echo $fetch['store_id']?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-download"></span> הורדה</a> 
																		
								</td>
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
	<label class = "footer-title"  dir="rtl">&copy;  ניהול קבצים אבי דלל  <?php echo date("Y", strtotime("+8 HOURS"))?></label>
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
	
	<div class="modal fade" id="modal_confirm" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">מערכת</h3>
				</div>
				<div class="modal-body">
					<center><h4 class="text-danger">האם להתנתק?</h4></center>
				</div>
				<div class="modal-footer">
					<a type="button" class="btn btn-success" data-dismiss="modal">ביטול</a>
					<a href="logout.php" class="btn btn-danger">התנתקות</a>
				</div>
			</div>
		</div>
	</div>

</div>

	<div class="modal fade" id="modal_remove" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">System</h3>
				</div>
				<div class="modal-body">
					<center><h4 class="text-danger">Are you sure you want to remove this file?</h4></center>
				</div>
				<div class="modal-footer">
					<a type="button" class="btn btn-success" data-dismiss="modal">No</a>
					<button type="button" class="btn btn-danger" id="btn_yes">Yes</button>
				</div>
			</div>
		</div>
	</div>
	
	
<?php include 'script.php'?>
<script type="text/javascript">


function getclaimnum() {
  return document.getElementById("ClaimNum").value ;
}


$('.btn-show').on('click', function(){
		var stud_no= $('[name="stud_no"]').val();
		var file_name=  $(this).attr('id');
		$.ajax({
			type: "POST",
			url: "showpdf.php",
			data:{
				stud_no: stud_no,
				file_name:  file_name

			},
			success: function(data){
			showPDF(data);
			}
		});
	})
	

	
$(document).ready(function(){
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
				$(".del_file" + id).html("<td colspan='4'><center class='text-danger'>Deleting...</center></td>");
				setTimeout(function(){
					$(".del_file" + id).fadeOut('slow');
				}, 1000);
			}
		});
	});
});

	$(document).ready(function(){
	$('.btn_completion').on('click', function(){
		var store_id = $(this).attr('id');
		$("#modal_completion").modal('show');
		$('#btn_yes_compl').attr('name', store_id);
	});
	
	$('#btn_yes_compl').on('click', function(){
		var id = $(this).attr('name');
		var stud_no = $('select[name=stud_number'+id+']').val(); 
		if(stud_no == "") {
		$("#modal_completion").modal('hide');
		alert("נא למלא שדה להשלמה");
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

var __PDF_DOC,
	__CURRENT_PAGE,
	__TOTAL_PAGES,
	__PAGE_RENDERING_IN_PROGRESS = 0,
	__CANVAS = $('#pdf-canvas').get(0),
	__CANVAS_CTX = __CANVAS.getContext('2d');

function showPDF(pdf_url) {
	if(pdf_url =="false")
	{
		alert("אין אפשרות להציג קובץ שהוא אינו PDF");
		return
	}
	$("#pdf-loader").show();

	PDFJS.getDocument({ url: pdf_url }).then(function(pdf_doc) {
		__PDF_DOC = pdf_doc;
		__TOTAL_PAGES = __PDF_DOC.numPages;
		
		// Hide the pdf loader and show pdf container in HTML
		$("#pdf-loader").hide();
		$("#pdf-contents").show();
		$("#pdf-total-pages").text(__TOTAL_PAGES);

		// Show the first page
		showPage(1);
	}).catch(function(error) {
		// If error re-show the upload button
		$("#pdf-loader").hide();
		$("#upload-button").show();
		
		alert(error.message);
	});;
}

function showPage(page_no) {
	__PAGE_RENDERING_IN_PROGRESS = 1;
	__CURRENT_PAGE = page_no;

	// Disable Prev & Next buttons while page is being loaded
	$("#pdf-next, #pdf-prev").attr('disabled', 'disabled');

	// While page is being rendered hide the canvas and show a loading message
	$("#pdf-canvas").hide();
	$("#page-loader").show();

	// Update current page in HTML
	$("#pdf-current-page").text(page_no);
	
	// Fetch the page
	__PDF_DOC.getPage(page_no).then(function(page) {
		// As the canvas is of a fixed width we need to set the scale of the viewport accordingly
		var scale_required = __CANVAS.width / page.getViewport(1).width;

		// Get viewport of the page at required scale
		var viewport = page.getViewport(scale_required);

		// Set canvas height
		__CANVAS.height = viewport.height;

		var renderContext = {
			canvasContext: __CANVAS_CTX,
			viewport: viewport
		};
		
		// Render the page contents in the canvas
		page.render(renderContext).then(function() {
			__PAGE_RENDERING_IN_PROGRESS = 0;

			// Re-enable Prev & Next buttons
			$("#pdf-next, #pdf-prev").removeAttr('disabled');

			// Show the canvas and hide the page loader
			$("#pdf-canvas").show();
			$("#page-loader").hide();
		});
	});
}

// Upon click this should should trigger click on the #file-to-upload file input element
// This is better than showing the not-good-looking file input element
$("#upload-button").on('click', function() {
	$("#file-to-upload").trigger('click');
});

// When user chooses a PDF file
$("#file-to-upload").on('change', function() {
	// Validate whether PDF
    if(['application/pdf'].indexOf($("#file-to-upload").get(0).files[0].type) == -1) {
        alert('Error : Not a PDF');
        return;
    }

	$("#upload-button").hide();

	// Send the object url of the pdf
	showPDF(URL.createObjectURL($("#file-to-upload").get(0).files[0]));
});

// Previous page of the PDF
$("#pdf-prev").on('click', function() {
	if(__CURRENT_PAGE != 1)
		showPage(--__CURRENT_PAGE);
});

// Next page of the PDF
$("#pdf-next").on('click', function() {
	if(__CURRENT_PAGE != __TOTAL_PAGES)
		showPage(++__CURRENT_PAGE);
});


</script>	
</body>
</html>