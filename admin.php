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
			font-family: Inter, ui-sans-serif, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
			background:
				radial-gradient(circle at top, rgba(37, 99, 235, 0.10), transparent 34%),
				#070b12;
			color: #dbe4f0;
			display: flex;
			justify-content: center;
			align-items: center;
			-webkit-font-smoothing: antialiased;
		}

		.login-page{
			width: 100%;
			padding: 28px;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-direction: column;
		}

		.login-card{
			background: linear-gradient(180deg, #111827 0%, #0d1420 100%);
			border: 1px solid #263449;
			border-radius: 12px;
			padding: 34px;
			box-shadow: 0 24px 70px rgba(0, 0, 0, 0.42), inset 0 1px 0 rgba(255, 255, 255, 0.025);
			max-width: 420px;
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
			font-weight: 700;
			font-size: 18px;
			margin-bottom: 20px;
		}

		.login-card h1{
			margin: 0;
			font-size: 22px;
			font-weight: 650;
			letter-spacing: -0.02em;
			color: #f8fafc;
		}

		.login-card p{
			margin-top: 8px;
			margin-bottom: 26px;
			font-size: 13px;
			line-height: 1.6;
			color: #8794a8;
		}

		.form-group{
			margin-bottom: 16px;
		}

		.form-group label{
			display: block;
			margin-bottom: 7px;
			font-size: 12px;
			font-weight: 650;
			letter-spacing: 0.025em;
			color: #b8c4d4;
		}

		.form-group input{
			width: 100%;
			height: 42px;
			padding: 0 12px;
			background: #080e18;
			color: #eef4fb;
			border: 1px solid #2a3950;
			border-radius: 7px;
			font-size: 14px;
			outline: none;
			transition: border-color 120ms ease, box-shadow 120ms ease, background 120ms ease;
		}

		.form-group input::placeholder{
			color: #526075;
		}

		.form-group input:hover{
			border-color: #3a4b64;
		}

		.form-group input:focus{
			background: #0a111d;
			border-color: #3b82f6;
			box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
		}

		.login-button{
			width: 100%;
			height: 42px;
			margin-top: 4px;
			padding: 0 14px;
			background: #2563eb;
			color: white;
			border: 1px solid #3572ef;
			border-radius: 7px;
			font-size: 13px;
			font-weight: 650;
			cursor: pointer;
			box-shadow: 0 6px 18px rgba(37, 99, 235, 0.18);
			transition: background 120ms ease, border-color 120ms ease, transform 120ms ease;
		}

		.login-button:hover{
			background: #2f6df0;
			border-color: #4b82f3;
		}

		.login-button:active{
			transform: translateY(1px);
		}

		.footer-text{
			margin-top: 18px;
			text-align: center;
			font-size: 11px;
			line-height: 1.7;
			color: #586579;
		}

		.footer-text a{
			color: #8294ad;
			text-decoration: none;
		}

		.footer-text a:hover{
			color: #a9b8cb;
			text-decoration: none;
		}

		@media (max-width: 520px){
			.login-page{
				padding: 18px;
			}

			.login-card{
				padding: 26px 22px;
			}
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