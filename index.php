<?php
// Require needed files
require_once('scripts/use_stocks.php');

$tables = mysql_query("SHOW tables") or handle_error("there was a problem connecting to the database which contains the information needed to continue", mysql_error());
$columns = mysql_list_fields("stocks","fb") or handle_error("there was a problem connecting to the database which contains the information needed to continue", mysql_error());
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Value investing rules!">
    <meta name="author" content="David H Hagan">



    <title>David's Stock Paradise</title>

    <!-- Bootstrap core CSS -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">



    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Stocks</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </div>


    <div class="container">
      <!-- Example row of columns -->
      <div class="row" style="margin-top:70px">
        <div class="col-lg-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
        </div>
        <div class="col-lg-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
       </div>
        <div class="col-lg-4">
          <h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-default" href="#">View details &raquo;</a></p>
        </div>
      </div>

	  <div class="container" style="margin-top:15px">

		<div class="row">
		<h1>Stock stuff</h1>
		</div><!-- /.row -->

		
		<div class="row">
			<div class="col-md-4">
				<h3>List of Tables</h3>
				<?php
				$i = 1;
				echo "<ul class='pagination'>";
				echo "<li><a href='page'>&laquo;</a></li>";
				$numRows = ceil(mysql_num_rows($tables) / 500);	
				while ($i <= $numRows):
					echo "<li><a href='page-{$i}'>{$i}</a></li>";
					$i++;
				endwhile;
					
				echo "<li><a href='page'>&raquo;</a></li>";
				//echo $numRows;	
				echo "</ul>";
				?>
				<table class="table table-hover">
				<colgroup>
					<col width="50" />
				</colgroup>
					<tr><td>#</td><td>Table Name</td></tr>
					<?php
						$i = 1;
						while ($row = mysql_fetch_array($tables)) {
							$tick = strtoupper($row[0]);
							echo "<tr>";
							echo "<td>{$i}</td>";
							echo "<td>{$tick}</td>";
							echo "</tr>";
							$i++;
							}
					?>
				</table>
			</div>
			
			<div class="col-md-8">
				<h3>Table Contents</h3>

				<table class="table table-hover">
					<colgroup>
						<col width="50" />
					</colgroup>
					<tr><td>#</td><td>Field Name</td></tr>
					<?php
						$j = 0;
						while ($j < mysql_num_fields($columns)){
							$item = mysql_field_name($columns,$j);
							echo "<tr>";
							echo "<td>{$j}</td>";
							echo "<td>{$item}</td>";
							echo "</tr>";
							$j++;
							}
						//echo mysql_field_name($columns,0);
					?>
				</table>
			</div><!-- /.col-med-8 -->
		</div><!-- /.row -->
		

		
	  </div><!-- /.container -->
	  
      <hr>

      <footer>
        <p>&copy; David H Hagan 2013</p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//code.jquery.com/jquery.js"></script>
	<script src="scripts/js/main.js" ></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
  </body>
</html>