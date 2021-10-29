<?php
    require 'register_header.php';
?>
<html>
<body>
    <div class="main">
        <p class="sign" align="center">Sign up</p>
        <form class="form1" action="/home/qtn3/Documents/NewFamJamIT490/Backend/registerProc.php" method="post">
          <input class="un " type="text" align="center" name="sign_up_name" placeholder="Username">
          <input class="pass" type="password" align="center" name="sign_up_pass" placeholder="Password">
          <input class="pass" type="email" align="center" name="sign_up_email" placeholder="Email">
          <input class="submit" type="submit" name="submit" value="Sign up">
          <br>
          <br>
          <a class="submit" align="center" href="login.php">Sign in</a>
        </form>                
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>