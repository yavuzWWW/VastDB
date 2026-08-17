<?php
//test
require __DIR__ . '/functions.php';
require __DIR__ . '/db.php';


//check auth key
//no get req for auth key
if (isset($_GET['auth_key'])) {
	$auth_key = $_GET['auth_key'];
}else{
	header('Location: https://vasthosting.cloud');
	exit();
}

$key_hash = readJson("data/info.vast")['Key'];
//verify key
if(!password_verify($auth_key, $key_hash)){
	redirect("https://vasthosting.cloud");
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>VastDB - Admin Login</title>

	<style>
		*{
			box-sizing: border-box;
		}

		body{
			margin: 0;
			min-height: 100vh;
			font-family: Arial, Helvetica, sans-serif;
			background: #0b0f19;
			color: #e5e7eb;
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.login-page{
			width: 100%;
			padding: 20px;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-direction: column;

		}

		.login-card{
			background: #111827;
			border: solid 1px #263244;
			border-radius: 14px;
			padding: 32px;
			box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
			max-width: 430px;
			width: 100%;
		}

		.logo-box{
			width: 48px;
			height: 48px;
			background: #2563eb;
			color: white;
			border-radius: 10px;
			display: flex;
			justify-content: center;
			align-items: center;
			font-weight: bold;
			font-size: 18px;
			margin-bottom: 20px;
		}

		.login-card h1{
			margin: 0;
			font-size: 24px;
			color: #ffffff;
		}

		.login-card p{
			margin-top: 8px;
			margin-bottom: 28px;
			font-size: 14px;
			color: #9ca3af;
		}

		.form-group{
			margin-bottom: 18px;
		}

		.form-group label{
			display: block;
			margin-bottom: 7px;
			font-size: 14px;
			font-weight: bold;
			color: #d1d5db;
		}

		.form-group input{
			width: 100%;
			padding: 12px;
			background: #0b1220;
			color: #ffffff;
			border: solid 1px #334155;
			border-radius: 8px;
			font-size: 15px;
			outline: none;
		}

		.form-group input::placeholder{
			color: #64748b;
		}

		.form-group input:focus{
			border-color: #2563eb;
			box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
		}

		.login-button{
			width: 100%;
			padding: 12px;
			background: #2563eb;
			color: white;
			border: none;
			border-radius: 8px;
			font-size: 15px;
			font-weight: bold;
			cursor: pointer;
		}

		.login-button:hover{
			background: #1d4ed8;
		}

		.footer-text{
			margin-top: 22px;
			text-align: center;
			font-size: 12px;
			line-height: 1.6;
			color: #6b7280;
		}

		.footer-text a{
			color: #60a5fa;
			text-decoration: none;
		}

		.footer-text a:hover{
			text-decoration: underline;
		}

	</style>
	<script src="scripts/lib/htmx.js"></script>
</head>

<body>

	<div class="login-page">

		<div class="login-card">



			<h1>VastDB Admin</h1>
			<p>Sign in to access the admin dashboard.</p>

			<form
				hx-post="pages/login.php"
				hx-target=".login-page"
				hx-swap="innerHTML"
			>

				<div class="form-group">
					<label>Username</label>
					<input type="text" name="username" placeholder="Enter username">
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" name="password" placeholder="Enter password">
				</div>

				<button class="login-button" type="submit">
					Login
				</button>

			</form>

		</div>

		<div class="footer-text">
			VastDB Admin Panel<br>
			Powered by <a href="https://vasthosting.nl" target="_blank">Vast Hosting</a>
		</div>


	</div>

</body>
</html>