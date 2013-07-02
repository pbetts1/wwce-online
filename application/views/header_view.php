<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>What Works in Character Education</title>

	<?= link_tag(base_url().'css/bootstrap.min.css'); ?>
	<?= link_tag(base_url().'css/bootstrap-responsive.min.css'); ?>
	<?= link_tag(base_url().'css/custom.css'); ?>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	
	 <style>
  #accordion-resizer {
    padding: 10px;
    width: 350px;
    height: 220px;
  }
  </style>
  <script>
  $(function() {
    $( "#accordion" ).accordion({
      heightStyle: "fill",
       collapsible: true
      
    });
  });
  $(function() {
    $( "#accordion-resizer" ).resizable({
      minHeight: 140,
      minWidth: 200,
      resize: function() {
        $( "#accordion" ).accordion( "refresh" );
      }
    });
  });
  </script>
	
	<style>
		
		.color0 { #fff; }
        .color1 { #ccc; }
		
	</style>
	
</head>
<body>
<body>
		<div class="container-fluid">
			<nav class="navbar">
				<div class="navbar-inner">
					<a class="brand" href="#">WWCE Online Database</a>
					<ul class="nav">
						<li>
							<?=  anchor('main_controller/index/','Home') ?>
						</li>
						<li>
							<?=  anchor('advanced/index/','Advanced Search') ?>
						</li>
						<li>
							<?= anchor('main_controller/search/','View All Interventions'); ?>
						</li>
						<li>
							<a href="http://www.characteredworks.org">CERP Clearinghouse</a>
						</li>
					</ul>
				</div>
			</nav>

			<div class="jumbotron subhead cherp-header">
				<div class="container">
					<h1>WWCE</h1>
					<p>
						What Works in Character Education
					</p>
				</div>
			</div>