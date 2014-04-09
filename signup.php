#!/usr/local/bin/php

<html>
 <head>
  <title>
   Signup
  </title>
 </head>
 <body>
   <form name='form' method='post' action='welcome.php'>
  First Name: <input type="Text" value="" name="fn" id="fn"><br />
   <font size="1">Your first name</font><br /><br />
   <form name='form' method='post' action='welcome.php'>
  Last Name: <input type="Text" value="" name="ln" id="ln"><br />
   <font size="1">Your last name</font><br /><br /> 	
   <form name='form' method='post' action='welcome.php'>
  Username: <input type="Text" value="" name="un" id="un"><br />
   <font size="1">Select a Username</font><br /><br />
  Password: &nbsp;<input type="password" value="" name="pw" id="pw"><br />
   <font size="1">Select Password</font><br /><br />
  Password: &nbsp;<input type="password" value="" name="vpw" id="vpw"><br />
   <font size="1">Again</font><br /><br />
  E-mail: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="email" value="" name="em" id="em">
   <input type="Submit" value="Submit"><br />
   <font size="1">Input your Email</font>
  </form>
    
 </body>
</html>
