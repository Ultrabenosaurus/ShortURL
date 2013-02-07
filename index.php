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
	.light-rounded {
		border-radius: 3px;
	}
	.depth {
		display: inline-block;
		border: 1px solid rgba(255,255,255,0.6);
		background: -webkit-linear-gradient(#eee, #fff);
		background: -moz-linear-gradient(#eee, #fff);
		background: -o-linear-gradient(#eee, #fff);
		background: -ms-linear-gradient(#eee, #fff);
		background: linear-gradient(#eee, #fff);
		box-shadow: 
			inset 0 1px 4px rgba(0,0,0,0.4);
		padding: 5px;
		color: #555;
	}
	.depth:focus {
		outline: none;
		background-position: 0 -1.7em;
	}
	.modern {
		display: inline-block;
		margin: 10px;
		padding: 8px 15px;
		background: #555A66;
		color: #EEE;
		border: 1px solid rgba(0,0,0,0.15);
		border-radius: 4px;
		transition: all 0.3s ease-out;
		box-shadow:
			inset 0 1px 0 rgba(255,255,255,0.5),
			0 2px 2px rgba(0,0,0,0.3),
			0 0 4px 1px rgba(0,0,0,0.2);
		text-decoration: none;
		text-shadow: 0 1px rgba(255,255,255,0.7);
	}
	.modern:hover  { background: #363941; }
	</style>
	<title>URL Shortener</title>
</head>
	<body>
		<div id='container' class='light-rounded'>
			<h1>URL Shortener</h1>
			<p>Enter a URL below to shorten it.</p>
			<form action='' method='GET' name='short' id='short'>
				<input type='text' name='url' id='url' size='50' class='depth' />
				<input type='button' name='submit' id='submit' value='Shorten' class='modern' />
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