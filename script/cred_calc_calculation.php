<?php
//функции для расчета графика платежей по разным схемам


//---------------------------------------------------------------------
// Добавление одного или нескольких календарных месяцев к TIMESTAMP
//---------------------------------------------------------------------
function Add_month($time, $num=1) {
    $d=date('j',$time);  // день
    $m=date('n',$time);  // месяц
    $y=date('Y',$time);  // год
 
    // Прибавить месяц(ы)
    $m+=$num;
    if ($m>12) {
        $y+=floor($m/12);
        $m=($m%12);
        // Дополнительная проверка на декабрь
        if (!$m) {
            $m=12;
            $y--;
        }
    }
    // Это последний день месяца?
    if ($d==date('t',$time)) {
        $d=31;
    }
    // Открутить дату, пока она не станет корректной
    while(true) {
        if (checkdate($m,$d,$y)){
            break;
        }
        $d--;
    }
    // Вернуть новую дату в TIMESTAMP
    return mktime(0,0,0,$m,$d,$y);
}


//---------------------------------------------------------------------
// Расчет графика платежей по разным схемам.
// Возвращает массив с типом платежа, датами и суммами платежей.
//---------------------------------------------------------------------
function Payment_Schedule($type_platezh, $str_beg_date, $sum_kred, $col_month, $proc, $arr_payments) 
{
	/*
	$type_platezh : тип платежей по кредитам {'annuit', 'differ', 'flex'}
	$str_beg_date : дата выдачи кредита в формате 01.ММ.ГГГГ (гасить начинаем в СЛЕДУЮЩЕМ месяце)
	$sum_kred : сумма кредита (=начальная сумма основного долга)
	$col_month : срок кредита в месяцах
	$proc : годовой процент
	$arr_payments : массив платежей при гибких платежах
	*/
	
	$sum_kred = round((float)$sum_kred, 2);
	$proc = round((float)$proc, 2);
	$col_month = (int)($col_month);
	
	$arr_all_platezh = array();
	
	$beg_date = strtotime($str_beg_date);
	
	# Платить начинаем в следующем месяце после взятия кредита
	$platezh_next_date = Add_month($beg_date);
	$ostatok_dolg = $sum_kred;
	$month_proc = ($proc/100)/12; #месячный процент
	
	// Определяем ежемесячный платеж для аннуитета и дифееренцированной схемы
	if ($type_platezh == 'annuit') {
		$platezh = $sum_kred*($month_proc + $month_proc /(pow(1+$month_proc, $col_month)-1));
		$platezh = round($platezh,2); #ежемесячный платеж
	}
	elseif ($type_platezh == 'differ') {
		$platezh_main_dolg = $sum_kred/$col_month; 
		$platezh_main_dolg = round((float)$platezh_main_dolg, 2); #ежемесячное погашение основного долга
		$platezh = $sum_kred*($month_proc + $month_proc /(pow(1+$month_proc, $col_month)-1));
		$platezh = round($platezh,2); #ежемесячный платеж
	}
	
	/*Рассчитываем в цикле платежи для каждого месяца. 
	Для текущего месяца заполняется массив $arr_platezh, затем этот массив добавляется в качестве элемента
	в массив $arr_all_platezh. */
	for ($nomer_platezh=1; $nomer_platezh<=$col_month; $nomer_platezh++) {
		$ostatok_dolg_0 = $ostatok_dolg; #остаток основного долга до очередного погашения
		$platezh_proc = $ostatok_dolg*$month_proc;
		$platezh_proc = round($platezh_proc,2); #погашение процентов
		
		if ($type_platezh == 'annuit') {
			$platezh_main_dolg = $platezh - $platezh_proc;
		}
		elseif ($type_platezh == 'differ') {
			$platezh = $platezh_main_dolg + $platezh_proc;
		}
		elseif ($type_platezh == 'flex') {
			$platezh_main_dolg = $arr_payments[$nomer_platezh-1];
			$platezh = $platezh_main_dolg + $platezh_proc;
		}
		$ostatok_dolg = $ostatok_dolg - $platezh_main_dolg; #остаток основного долга после очередного погашения
		
		$platezh_date = $platezh_next_date;
		/* Сначала была полная дата 
		$str_platezh_date = date("d.m.Y", $platezh_date);
		*/
		$str_platezh_date = date("m.Y", $platezh_date);
		$platezh_next_date = Add_month($platezh_date);
		
		# Корректировка последнего платежа
		if ($nomer_platezh == $col_month) {
			if ($ostatok_dolg != 0) {
				$platezh_proc = $ostatok_dolg_0*$month_proc;
				$platezh_proc = round($platezh_proc,2); #погашение процентов
				$platezh_main_dolg = $ostatok_dolg_0;
				$platezh = $platezh_proc + $platezh_main_dolg;
				$ostatok_dolg = 0.00;
			}
		}
		
		// Формируем массив с параметрами платежа для текущего месяца	
		$arr_platezh = array('type_platezh' => $type_platezh,
						'nomer' => $nomer_platezh,
						'date' => $str_platezh_date,
						'ostatok_0' => $ostatok_dolg_0,
						'platezh' => $platezh,
						'platezh_proc' => $platezh_proc,
						'platezh_main_dolg' => $platezh_main_dolg,
						'ostatok' => $ostatok_dolg);
		/*Добавляем массив с параметрами платежа в текущем месяце 
		в общий массив с платежами за каждый месяц
		*/
		$arr_all_platezh[] = $arr_platezh;
	}

	return $arr_all_platezh;
}


 ?>