<?php
    require 'login_header.php';
?>
<html>
<body>
    <div class="main">
        <p class="sign" align="center">Sign in</p>
        <form class="form1" action="login.php" method="post">
          <input class="un " type="text" align="center" placeholder="Username" name="user_name">
          <input class="pass" type="password" align="center" placeholder="Password" name="pass_word">
          <!-- <button class="submit" align="center" type="submit" name="login_form">Sign in</button> -->
          <input class="submit" type="submit" name="submit" value="Sign in">
          <br>
          <br>
          <a class="submit" align="center" href="signup.php">Sign up</a>
          <p class="forgot" align="center"><a href="#">Forgot Password?</p>
        </form>                
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>