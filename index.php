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
		
		async function loadPage(page) {
		  const container = document.querySelector('.container');
		  if (!container) return;

		  // prepare ids
		  const oldId = FurtexUtil.pageId;
		  const newId = (oldId || 0) + 1;
		  FurtexUtil.pageId = newId; // set BEFORE injecting so new registrations belong to new page

		  try {
			const res = await fetch(`/JawaPeleket/page/${page}.php?ajax=1`);
			if (!res.ok) throw new Error("HTTP " + res.status);
			const html = await res.text();

			// run cleanup for old page (remove popups/listeners created by previous page)
			FurtexUtil.runCleanupsFor(oldId);

			// inject HTML
			container.innerHTML = html;

			// execute scripts found in the injected HTML sequentially
			// (this ensures external scripts register cleanups under newId)
			const scripts = Array.from(container.querySelectorAll('script'));
			for (const oldScript of scripts) {
			  const newScript = document.createElement('script');

			  if (oldScript.src) {
				// external script: wait for load to finish before moving on
				await new Promise((resolve, reject) => {
				  newScript.src = oldScript.src;
				  newScript.async = false; // keep execution order
				  newScript.onload = () => resolve();
				  newScript.onerror = () => {
					console.error("Failed to load", oldScript.src);
					resolve(); // resolve anyway so navigation continues
				  };
				  // Track the script for cleanup
				  FurtexUtil.trackScript(newScript);
				  document.body.appendChild(newScript);
				});
			  } else {
				// inline script: run immediately
				newScript.textContent = oldScript.textContent;
				// Track the script for cleanup
				FurtexUtil.trackScript(newScript);
				document.body.appendChild(newScript);
				
				// Execute the script in the context of the current page
				try {
				  const scriptContent = oldScript.textContent;
				  // This ensures the script runs in the global scope but with access to the current pageId
				  eval(scriptContent);
				} catch (err) {
				  console.error('Error executing inline script:', err);
				}
			  }
			  oldScript.remove();
			}

			// push history
			history.pushState({ page }, '', `/JawaPeleket/?p=${page}`);
		  } catch (err) {
			console.error('Failed to load page', err);
		  }
		}
		
		function runScripts(container) {
		  const scripts = container.querySelectorAll("script");
		  scripts.forEach(oldScript => {
			const newScript = document.createElement("script");
			if (oldScript.src) {
			  newScript.src = oldScript.src;
			} else {
			  newScript.textContent = oldScript.textContent;
			}
			document.body.appendChild(newScript);
			oldScript.remove();
		  });
		}


		document.querySelectorAll('a[data-page]').forEach(link => {
		  link.addEventListener('click', e => {
			e.preventDefault();
			const page = link.dataset.page;
			loadPage(page);
		  });
		});

		// back/forward
		window.addEventListener('popstate', e => {
		  const page = (e.state && e.state.page) || 'home';
		  loadPage(page);
		});
	</script>
</html>