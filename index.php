<html>
	<head>
		<title>JawaPeleker</title>
		<link rel="stylesheet" href="view/css/bootstrap.min.css">
		<link rel="stylesheet" href="view/core.css">
		
		<script src="view/js/bootstrap.min.js"></script>
	</head>
	
    <body>
		<?php
			include 'view/navbar.php'
		?>
		<div class="container">
			<?php if (isset($_GET['error'])): ?>
            <p style="color: red;">
                <?php
                if ($_GET['error'] === 'invalid') echo 'Invalid username or password!';
                if ($_GET['error'] === 'empty') echo 'Please fill in all fields!';
                if ($_GET['error'] === 'exists') echo 'Username already exists!';
                ?>
            </p>
			<?php endif; ?>

			<?php if (isset($_GET['success'])): ?>
				<p style="color: green;">
					<?php if ($_GET['success'] === 'registered') echo 'Account created successfully! Please log in.'; ?>
				</p>
			<?php endif; ?>

			<?php
				$p = isset($_GET['p']) ? htmlspecialchars($_GET['p']) : 'home';
				switch($p) {
					case "home":
						include "page/home.php";
						break;
					default:
						include "page/404.php";
						break;
				}
			?>
		</div>
    </body>
</html>