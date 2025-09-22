<?php
	include 'config.php';
	if (isset($_SESSION['pesan'])) {
		?>
			<script>alert(<?= $_SESSION['pesan'] ?>)</script>
		<?php
	}
?>
<html>
	<head>
		<title>JawaPeleker</title>
		<link rel="stylesheet" href="view/css/bootstrap.min.css">
		<link rel="stylesheet" href="view/core.css">
		<script type="text/javascript" src="view/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="FurtexUtility.js"></script>
	</head>
	
    <body>
		<?php
			include 'view/navbar.php'
		?>
		<div class="container wow">
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
				
				if (isset($_GET['ajax'])) {
					if (file_exists("page/$p.php")) {
						include "page/$p.php";
					} else {
						include "page/404.php";
					}
				}
				if (file_exists("page/$p.php")) {
					include "page/$p.php";
				} else {
					include "page/404.php";
				}
			?>
		</div>
		<?php
			include 'view/footer.php'
		?>
    </body>
	<script>
		function replayAnimation(el, anim) {
			el.style.animation = 'none'; // Stop the animation
			setTimeout(() => {
			  el.style.animation = ''; // Reset to original animation
			}, 10); // Small delay to ensure reset
		}
		document.querySelectorAll('a[data-page]').forEach(link => {
		  link.addEventListener('click', e => {
			e.preventDefault();
			let page = link.getAttribute('data-page');
			FurtexUtil.runCleanups();

			// Load page content without full reload
			fetch(`/JawaPeleket/page/${page}.php?ajax=1`)
			  .then(res => res.text())
			  .then(html => {
				replayAnimation(document.querySelector('.container'), 'wow');
				document.querySelector('.container').innerHTML = html;
				history.pushState({ page }, '', `/JawaPeleket/?p=${page}`);
			  })
			  .catch(err => console.error('Error loading page:', err));
		  });
		});

		// Handle browser back/forward
		window.addEventListener('popstate', e => {
		  if (e.state && e.state.page) {
			FurtexUtil.runCleanups();
			fetch(`/JawaPeleket/page/${e.state.page}.php?ajax=1`)
			  .then(res => res.text())
			  .then(html => {
				replayAnimation(document.querySelector('.container'), 'wow');
				document.querySelector('.container').innerHTML = html;
				
				// Run any scripts inside the new content
				const scripts = document.querySelector('.container').querySelectorAll("script");
				scripts.forEach(oldScript => {
				  const newScript = document.createElement("script");
				  if (oldScript.src) {
					newScript.src = oldScript.src; // external JS
				  } else {
					newScript.textContent = oldScript.textContent; // inline JS
				  }
				  document.body.appendChild(newScript);
				  oldScript.remove();
				});
			  });
		  }
		});
	</script>
</html>