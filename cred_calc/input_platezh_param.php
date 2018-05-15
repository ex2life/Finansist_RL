<!DOCTYPE html>

<html>
<head>
	<title>Финансист онлайн</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link rel="stylesheet" href="../css/bootstrap.min.css"/> 
	<link rel="stylesheet" href="../css/jumbotron-narrow.css">
	<link rel="stylesheet" href="../css/magnific-popup.css">
	<link rel="stylesheet" href="../css/style.css"/> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
	
<body>
	<div class="container">
		<div class="header">
			<?php
			if ($_REQUEST['type_platezh'] == 'annuit')
				echo '<h2 class="text-center">АННУИТЕТНЫЙ <br>ПЛАТЕЖ</h2>';
			elseif ($_REQUEST['type_platezh'] == 'differ')
				echo '<h2 class="text-center">ДИФФЕРЕНЦИРОВАННЫЙ <br>ПЛАТЕЖ</h2>';
			elseif ($_REQUEST['type_platezh'] == 'flex')
				echo '<h2 class="text-center">ГИБКИЙ ГРАФИК<br>ПОГАШЕНИЯ</h2>';
			?>
		</div>
		<div class="jumbotron">
			<form method="post" id="frmPlatezhParam" action="out_platezh_schedule.php">
				<?php
				if ($_REQUEST['type_platezh'] == 'annuit')
					echo '<input type="hidden" name="type_platezh" id="type_platezh" value="annuit">';
				elseif ($_REQUEST['type_platezh'] == 'differ')
					echo '<input type="hidden" name="type_platezh" id="type_platezh" value="differ">';
				elseif ($_REQUEST['type_platezh'] == 'flex')
					echo '<input type="hidden" name="type_platezh" id="type_platezh" value="flex">';
				?>
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="str_beg_date" class="control-label text-left">Дата получения кредита (ММ.ГГГГ) </label>
							<!-- Сначала дату вводили в полном формате
							<input type="date" name="str_beg_date" value="<?=date('d.m.Y')?>" maxlength="10" autofocus required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01])\.(0[1-9]|1[012])\.[0-9]{4}"> -->
							<input type="text" class="form-control" name="str_beg_date" value="<?=date('m.Y')?>" id="str_beg_date" maxlength="7" autofocus required pattern="(0[1-9]|1[012])\.[0-9]{4}">
						</div>
						<div class="form-group">
							<label for="sum_kred" class="control-label">Сумма кредита </label>
							<input type="text" class="form-control" name="sum_kred" id="sum_kred" required pattern="^\d+(\.\d{1,2})?$">
						</div>
					</div>
					<div class="col-sm-6">	
						<div class="form-group">
							<label for="col_month" class="control-label">Срок кредита в месяцах </label>
							<input type="number" class="form-control" min="1" name="col_month" id="col_month" value="1" required>
						</div>
						<div class="form-group">
							<label for="proc" class="control-label">Процентная ставка в год </label>
							<input type="text" class="form-control" name="proc" id="proc" required pattern="^\d+(\.\d{1,2})?$">
						</div>
						<!-- <a href="#text-popup" class="popup-content">Вызвать окно с текстом</a>  -->
					</div>
					<div class="col-sm-6">
						<?php
						if ($_REQUEST['type_platezh'] == 'flex') {
							echo '<div class="input_payment_schedule" id="input_payment_schedule">';
							echo  '<div></div>';
							echo '</div>';
							echo '<p></p>';
						}
						?>
					</div>	
					<div class="clearfix"></div>
					<div class="form-group">
						<input class="col-xs-6 btn btn-primary" type="submit" id="btnShowPaymentSchedule" value="Рассчитать график">
						<a class="btn btn-warning col-xs-6" href="./calc.php">Другой тип платежа</a>
					</div>
				</div>	<!-- Конец row -->
			</form>
		</div> <!-- Конец jumbotron -->

		<!-- Контейнер для вывода графика платежей в magnific-popup -->
		<div id="text-popup" class="white-popup mfp-hide">
		</div>
	</div>  <!-- Конец container -->
	<script type="text/javascript" src="../js/jquery-1.12.2.min.js"></script>
	<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="../js/additional-methods.min.js"></script>
	<script type="text/javascript" src="../js/magnific-popup.js"></script>
	<script type="text/javascript" src="../js/my.js"></script>
</body>
</html>