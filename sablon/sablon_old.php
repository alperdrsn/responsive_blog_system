<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Benim Bloğum</title>
		<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
				<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.css">
					<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
						<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
						<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
						<style>
							.row content {height: 1500px};
							.sidenav {
								background-color: #f1f1f1;
								height: 100%;
							}
							.footer {
								background-color: #555;
								color: white;
								padding: 15px;
							}
							@media screen and (max-width: 767px){
								.sidenav {
									height: auto;
									padding: 15px;
								}
								.row content { height: auto; }
							}
							.col-sm-10 div img {
      width: 200px;
      height: 150px;
      border-radius: 50%;
      float: left;
      shape-outside: ellipse();
      display: inline-block;
     }
						</style>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row content">
				<div class="col-sm-2 sidenav">
					<h4>Ad Soyad</h4>
					<ul class="nav nav-pills nav-stacked">
						<li class="active"><a href="index.php">AnaSayfa</a></li>
						<?php
						
						if(isset($uye['durum']) && $uye['durum'] == 1){
							echo '<li><a href="admin.php">Admin Panel</a></li>';
						}elseif(!isset($uye['durum'])){
							echo '<li><a href="giris.php">Giriş Yap</a></li>'
						}
						
						?>
						</ul>
						<br>
							<form action="arama" method="GET">
								<div class="input-group">
									<input type="text" name="ara" class="from-control" placeholder="Blogda Ara..." required>
										<span class="input-group-btn">
											<input type="submit" class="btn btn-default" Value="Ara">
											</span>
								</div>
								</form>
				</div>
				<div class="col-sm-10"><?= $cikti ?></div>
			</div>
		</div>
		<footer class="container-fluid">
			<p>Blog Footer</p>
			</footer>
	</body>
</html>
