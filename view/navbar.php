<style>
  .kf-logo {
    font-weight: bolder;
    font-style: italic;
  }
</style>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand kf-logo" href="#">JawaPeleket</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="#" data-page="home">Home</a>
        </li>
		<li class="nav-item">
          <a class="nav-link active" href="#" data-page="pelajaran">Jadwal Pelajaran</a>
        </li>
      </ul>
      <div class="d-flex">
		<?php if (!isUserLoggedIn()) {
			?>
			<a class="nav-link" href="#" onclick="FurtexUtil.showAnimated(login)">Login</a>
			<?php
			} else {
			?>
			<a class="nav-link" href="#" onclick="FurtexUtil.showAnimated(logout)">Log out</a>
			<?php
			}
		?>
      </div>
    </div>
  </div>
  
</nav>
<script>
	const login = FurtexUtil.createPopup(
	  "Login",
	  "Login",
	  "Cancel",
	  [
		["line", "Insert Username and Password"],
		["textbox", "Username", "text", "nama"],
		["textbox", "Password", "password", "pass"],
		["checkbox", "Remember Me"]
	  ],
	  "POST",
	  "act/loginAct.php"
	);
	
	const logout = FurtexUtil.createPopup(
	  "Log out?",
	  "Log out",
	  "Cancel",
	  [
		["line", "Apakah anda yakin ingin log out?"],
	  ],
	  "POST",
	  "act/logoutAct.php"
	);
</script>