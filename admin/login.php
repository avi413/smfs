<div class="col-md-4"></div>
<div class="col-md-3">
	<div class="panel panel-primary" id="panel-margin">
		<div class="panel-heading">
			<center><h1 class="panel-title">פאנל ניהול</h1></center>
		</div>
		<div class="panel-body">
			<form method="POST">
				<div class="form-group">
					<label for="username">שם משתמש</label>
					<input class="form-control" name="username" placeholder="Username" type="text" required="required" >
				</div>
				<div class="form-group">
					<label for="password">סיסמה</label>
					<input class="form-control" name="password" placeholder="Password" type="password" required="required" >
				</div>
				<?php include 'login_query.php' ?>
				<div class="form-group">
					<button class="btn btn-block btn-primary"  name="login"><span class="glyphicon glyphicon-log-in"></span> התחברות</button>
				</div>
			</form>
		</div>
	</div>
</div>	