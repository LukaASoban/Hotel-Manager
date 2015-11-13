<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <style>
    .center {
        text-align:center;
    }

    .emailForm {
    border:1px solid grey;
     border-radius:10px;
     margin-top:20px;
    }

    form {
        padding-top:20px;
        padding-bottom:20px;

    }
    </style>
    </head>

    <body>
        <h1 class="center">Login</h1>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 emailForm">

                    <?php echo $result; ?>

                    <form method="post">
                        <div class="form-group">
                            <label for="name">Your Name:</label>
                            <input type="text" name="name" class="form-control" placeholder="Your Name"
                            value="<?php echo $_POST['name']; ?>" />
                        </div>
                        <div class="form-group">
                            <label for="email">Your Email:</label>
                            <input type="email" name="email" class="form-control" placeholder="Your Email"
                            value="<?php echo $_POST['email']; ?>" />
                        </div>
                        <div class="form-group">
                            <label for="comment">Your Comment:</label>
                            <textarea class="form-control" name="comment"><?php echo $_POST['comment']; ?></textarea>
                        </div>
                        <input type="submit" name="submit" class="btn btn-success btn-lg" value="Submit" />

                    </form>
                </div>
            </div>
        </div>
    </body>

</html>
