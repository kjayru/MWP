<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>

	<form action="/contacto" method="POST" role="form">
		  {{ csrf_field() }}
		<input type="text" name="firstname" placeholder="Nombre">
		<input type="email" name="email" placeholder="Email">
		<textarea name="message"></textarea>
		<input type="submit" value="Enviar">
	</form>

</body>
</html>