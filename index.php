<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8' />
	<style>
	body{
		font-family: Calibri, Helvetica, sans-serif;
		font-size: 1em;
		color: #333;
		background-color: #CCC;
	}
	#container{
		max-width: 960px;
		margin: 0 auto;
		background-color: #EEE;
		padding: 0 0.5em 0.5em 0.5em;
		box-sizing: border-box;
	}
	#link{
		margin: 10px;
	}
	</style>
	<title>URL Shortener</title>
</head>
	<body>
		<div id='container'>
			<h1>URL Shortener</h1>
			<p>Enter a URL below to shorten it.</p>
			<form action='' method='GET' name='short' id='short'>
				<input type='text' name='url' id='url' />
				<input type='button' name='submit' id='submit' value='Shorten' />
			</form>
			<div id='link'></div>
		</div>
	</body>
	<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js'></script>
	<script type='text/javascript'>
	jQuery(document).ready(function(){
		jQuery('#short').submit(function(e){
			e.preventDefault();
			get_link();
		});
		jQuery('#submit').click(get_link);
	});
	function get_link(){
		var input = jQuery('#url').val();
		jQuery.ajax({
			type:"GET",
			url:'handler.php',
			data:{url:input}
		}).done(function(msg){
			jQuery('#link').html(msg);
		});
	}
	</script>
</html>