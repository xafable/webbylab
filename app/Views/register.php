<?php

use App\Utils;
	include(__DIR__."/components/header.php");
?>

<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid w-80">
    <a class="navbar-brand" href="#">WebbyLab</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
 
  </div>
</nav>

<div class="container">

<div class="login-alert" role="alert">
  <?php Utils::displayFlashMessage(); ?>
</div>
<form class="row g-3 needs-validation register-form " novalidate action="/register" method="POST">
       
        <div class="col-md-12">
            <div class="input-group has-validation">
                <span class="input-group-text" >Username</span>
                <input name="username" type="text" class="form-control" id="username"
                    aria-describedby="inputGroupPrepend" required>
                <div class="invalid-feedback">
                    Please enter a username.
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="input-group has-validation">
                <span class="input-group-text">Password</span>
                <input name="password" type="text" class="form-control" id="password"
                    aria-describedby="inputGroupPrepend" required>
                <div class="invalid-feedback">
                    Please enter a password.
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="input-group has-validation">
                <span class="input-group-text">Repeat </span>
                <input name="password" type="text" class="form-control" id="repeatPassword"
                    aria-describedby="inputGroupPrepend" required>
                <div class="invalid-feedback">
                    Passwords don't match
                </div>
            </div>
        </div>
       
        <div class="col-12">
            <button disabled id="registerButton" class="btn btn-outline-success" type="submit" style="width: 100%;">Register</button>
        </div>

        <p><a class="link-opacity-100" href="/login">Already have an account? Login</a></p>

    </form>
</div>
   

     <script src="../../public/js/register.js"></script>

    <?php
	include(__DIR__."/components/footer-data.php");
    ?>
</body>