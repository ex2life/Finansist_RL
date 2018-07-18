<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Список пользователей</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body>
<div class="wrapper form-container">
	<div class="form">
		<div class="error-msg"><?= $message != '' ? htmlspecialchars($message) : 'Неизвестная ошибка' ?></div>
	</div>
</div>
</body>
</html>