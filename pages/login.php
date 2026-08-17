<?php
$baseDIRlogin =__DIR__;

require  $baseDIRlogin . '/../functions.php';

//get the login credentials from form

$usernameGet = $_POST['username']??die("<h1>Missing credentials!</h1>");
$passwordGet = $_POST['password']??die("<h1>Missing credentials!</h1>");


//get admin account
$admin = readJson("$baseDIRlogin/../data/info.vast");
$admin = $admin["Admin"];

$username = $admin['username'];
$password = $admin['password'];

if ($usernameGet !== $username || !password_verify($passwordGet, $password)) {	
?>
<!--Login failed back to login-->
		

		<!--login content-->
		<div class="login-card">



			<h1>VastDB Admin</h1>
			<p style="color: red;">The credentials doesnt match!</p>

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

<?php
}else{
 require $baseDIRlogin."/dashboard-content.php";
}
?>