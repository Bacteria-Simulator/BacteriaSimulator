<nav role="navigation" class="topnav">

  <!-- Collection of nav links and other content for toggling -->
  <div id="header" class="topnav">
    <script>
      function myFunction()
      {
        var x = document.getElementById("header");
        if (x.className === "topnav")
        {
          x.className += " responsive";
        }

        else
        {
          x.className = "topnav";
        }
      }
    </script>
    <ul class="topnav">
      <!-- if the user isn't logged in show the login button, otherwise show the logout button-->
      <li>
        <li>
          @if (Auth::guest())
          
          <a class="navbar-brand" href="/">Home</a>
          <a class="navbar-brand" href="/simulations">Simulations</a>
          <a class="navbar-brand" href="/register">Create Account</a>
          <a class="navbar-brand" href="/login">Login</a>
          <a href="javascript:void(0);" class="icon" onclick="myFunction()">&#9776;</a>
          @else
          <?php $user = Auth::user()?>
          
          <a class="navbar-brand" href="/">Home</a>
          <a class="navbar-brand" href="/simulations">Simulations</a>
          <a class="navbar-brand" href="/saved_simulations">Saved Simulations</a>
          <a href="javascript:void(0);" class="icon" onclick="myFunction()">&#9776;</a>
          @if ($user->user_level == 2 || $user->user_level == 1)
          <a class="navbar-brand" href="/admin_controls">Administrator Controls</a>
          @endif
          <a class="navbar-brand" href="/account">Account</a>
          <a class="navbar-brand" href="/edit_account">Edit Account</a>
          <a class="navbar-brand" href="/logout">Logout</a>
          @endif
        </li>
      </li>
    </ul>
  </div>
</nav>