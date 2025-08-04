<style>
  nav {
    background-color: var(--blue-light)
  }

  .kf-logo {
    font-weight: bolder;
    font-style: italic;
  }
</style>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand kf-logo" href="#" onclick="popup.showModal()">JawaPeleket</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>
<script>
	const popup = createPopup(
	  "Login",
	  "Login",
	  "Cancel",
	  [
		["line", "Insert Username and Password"],
		["textbox", "Username", "Text"],
		["textbox", "Password", "Pass"],
		["checkbox", "Remember Me"]
	  ],
	  "POST",
	  "login.php"
	);
</script>