<?php  

/*
Функции для вывода рассчитанного графика платежей в разные форматы:
   Platezh_to_html - возвращает html-код для отображения во всплывающем окне
   PDF             - формирует файл Grafik.pdf
   XLS             - формирует файл Grafik.xls
*/


//---------------------------------------------------------------------
// Формирование html-кода для графика платежей
//---------------------------------------------------------------------
function Platezh_to_html($str_beg_date, $sum_kred, $col_month, $proc, $arr_all_platezh) 
{
	$type_platezh = $arr_all_platezh[0]['type_platezh'];
	
	$str_out = "<h3>График платежей по кредиту</h3><p>Вид платежа: ";
	if ($type_platezh == 'annuit') 
		$str_out .= "<strong>Аннуитетный платеж</strong></p>";
	elseif ($type_platezh == 'differ') 
		$str_out .= "<strong>Дифференцированный платеж</strong></p>";
	elseif ($type_platezh == 'flex') 
		$str_out .= "<strong>Гибкий платеж</strong></p>";
	$str_sum_kred = number_format($sum_kred, 2, '.', ' ');
	$str_proc = number_format($proc, 2, '.', ' ');
	$str_out .= "<p>Сумма кредита: <strong>$str_sum_kred</strong> руб.<br>";
	$str_out .= "Процентная ставка: <strong>$str_proc</strong> %<br>";
	$str_out .= "Срок кредита (мес): <strong>$col_month</strong> </p>";
	
	$str_out .= "<table class=\"platezh_table\">\n";
	$str_out .= "<tr class=\"platezh_table_header\"><th>N</th><th>Дата</th><th>Сумма платежа</th><th>Погашение основного долга</th><th>Погашение процентов</th><th>Остаток основного долга</th></tr>";
	
	$total_platezh = $total_platezh_main_dolg = $total_platezh_proc = 0;
	
	foreach ($arr_all_platezh as $arr_platezh) {
		$str_platezh = number_format($arr_platezh['platezh'], 2, '.', ' ');
		$str_platezh_main_dolg = number_format($arr_platezh['platezh_main_dolg'], 2, '.', ' ');
		$str_platezh_proc = number_format($arr_platezh['platezh_proc'], 2, '.', ' ');
		$str_ostatok = number_format($arr_platezh['ostatok'], 2, '.', ' ');
		$str_out .= "<tr><td>".$arr_platezh['nomer']."</td><td>".$arr_platezh['date']."</td><td>".$str_platezh."</td>";
		$str_out .= "<td>".$str_platezh_main_dolg."</td><td>".$str_platezh_proc."</td><td>".$str_ostatok."</td></tr>";
		$total_platezh += $arr_platezh['platezh'];
		$total_platezh_main_dolg += $arr_platezh['platezh_main_dolg'];
		$total_platezh_proc += $arr_platezh['platezh_proc'];
	}
	$str_total_platezh = number_format($total_platezh, 2, '.', ' ');
	$str_total_platezh_main_dolg = number_format($total_platezh_main_dolg, 2, '.', ' ');
	$str_total_platezh_proc = number_format($total_platezh_proc, 2, '.', ' ');
	$str_out .= "<tr class=\"platezh_table_footer\"><td></td><td>Итого</td><td>$str_total_platezh</td><td>$str_total_platezh_main_dolg</td>";
	$str_out .= "<td>$str_total_platezh_proc</td><td></td></tr>";
	$str_out .= "</table>\n";


	// Форма для печати в pdf и сохранения в Excel
    $str_out .= "<form  target='_blank' method='post' action='out_platezh_schedule.php'>";
	$str_out .= "<input type='hidden' name='type_platezh' value=$type_platezh>";
	$str_out .= "<input type='hidden' name='sum_kred' value=$sum_kred>";
	$str_out .= "<input type='hidden' name='str_beg_date' value=$str_beg_date>";
	$str_out .= "<input type='hidden' name='col_month' value=$col_month>";
	$str_out .= "<input type='hidden' name='proc' value=$proc>";

	if ($type_platezh == 'flex') {
		foreach($_POST['flex_payment_schedule'] as $flex_payment) {
		    $str_out .= "<input type='hidden' name='flex_payment_schedule[]' value=$flex_payment>";	
		}
	}	
	$str_out .= '<input class="btn btn-link" type="submit" id="btnOutToPDF" name="pdf" value="Вывести в pdf">';
	$str_out .= '<input class="btn btn-link" type="submit" id="btnSaveToXLS" name="xls" value="Сохранить в xls">';
	$str_out .= '</form>';

	return $str_out;
}

// Added by SA 20171006
//---------------------------------------------------------------------
// Сохранение графика платежей в pdf
//---------------------------------------------------------------------
function PDF($str_beg_date, $sum_kred, $col_month, $proc, $arr_all_platezh) 
{
 
	require_once('../libs/tcpdf/tcpdf.php');

	class MYPDF extends TCPDF 
	{ 

		//Заголовок страницы
		function Header()
		{
		    //Логотип
		    //$this->Image('logo_pb.png',10,8,33);
		    //шрифт dejavusans, жирный, размер 15
		    $this->SetFont('dejavusans','B',15);
		    //Название
		    $this->Cell(60,10,'Финансист-онлайн',1,0,'C');
			$this->Ln();
			$this->Cell(194,0,'',1,0,'C');
		    //Разрыв строки
		    $this->Ln();
		}
 
		//Подвал страницы
		function Footer()
		{
		    //Позиция на 1,5 cm от нижнего края страницы
		    $this->SetY(-15);
		    //Шрифт dejavusans, курсив, размер 8
		    $this->SetFont('dejavusans','I',8);
		    //Номер страницы
		    $this->Cell(0,10,'Стр. '.$this->PageNo(),0,0,'C');
		}

		//Цветная таблица
		function FancyTable($header,$data)
		{
		    //Цвета, ширина линии и жирный шрифт
		    $this->SetFillColor(205, 92, 92);
		    $this->SetTextColor(255);
		    $this->SetDrawColor(128,0,0);
		    $this->SetLineWidth(.3);
		    $this->SetFont('dejavusans','10');
		    //Заголовок
		    $w=array(12,22,40,40,40,40);
		    for($i=0;$i<count($header);$i++)
			   $this->MultiCell($w[$i], 20, $header[$i], 1, 'C', 1, 0, '', '', true);
		       //$this->multiCell($w[$i],18,$header[$i],1,0,'C',true);
		    $this->Ln();
		    //Восстановление цвета и шрифта
		    $this->SetFillColor(224,235,255);
		    $this->SetTextColor(0);
		    $this->SetFont('dejavusans','',10);
		    //Данные
		    $fill=false;
			foreach ($data as $arr_platezh) 
			{
				$str_platezh = number_format($arr_platezh['platezh'], 2, '.', ' ');
				$str_platezh_main_dolg = number_format($arr_platezh['platezh_main_dolg'], 2, '.', ' ');
				$str_platezh_proc = number_format($arr_platezh['platezh_proc'], 2, '.', ' ');
				$str_ostatok = number_format($arr_platezh['ostatok'], 2, '.', ' ');
				$this->Cell($w[0],6,$arr_platezh['nomer'],'LR',0,'L',$fill);
				$this->Cell($w[1],6,$arr_platezh['date'],'LR',0,'C',$fill);
				$this->Cell($w[2],6,$str_platezh,'LR',0,'R',$fill);
				$this->Cell($w[3],6,$str_platezh_main_dolg,'LR',0,'R',$fill);
				$this->Cell($w[4],6,$str_platezh_proc,'LR',0,'R',$fill);
				$this->Cell($w[5],6,$str_ostatok,'LR',0,'R',$fill);
				$this->Ln();
		        $fill=!$fill;
				$total_platezh += $arr_platezh['platezh'];
				$total_platezh_main_dolg += $arr_platezh['platezh_main_dolg'];
				$total_platezh_proc += $arr_platezh['platezh_proc'];
			}
			$str_total_platezh = number_format($total_platezh, 2, '.', ' ');
			$str_total_platezh_main_dolg = number_format($total_platezh_main_dolg, 2, '.', ' ');
			$str_total_platezh_proc = number_format($total_platezh_proc, 2, '.', ' ');
			$this->SetFillColor(205, 92, 92);
		    $this->SetTextColor(255);
		    $this->SetDrawColor(128,0,0);
		    $this->SetLineWidth(.3);
		    $this->SetFont('dejavusans','10');
		    $this->Cell($w[0]+$w[1],6,'Итого:','LR',0,'R',true);
		    $this->Cell($w[2],6,$str_total_platezh,'LR',0,'R',true);
			$this->Cell($w[3],6,$str_total_platezh_main_dolg,'LR',0,'R',true);
			$this->Cell($w[4],6,$str_total_platezh_proc,'LR',0,'R',true);
			$this->Cell($w[5],6,'','LR',0,'R',true);
		}
	} //end of class MYPDF extends TCPDF 
 
	$pdf=new MYPDF();
	//Заголовки столбцов
	$header = array('N', 'Дата', 'Сумма платежа', 'Погашение основного долга', 'Погашение процентов', 'Остаток основного долга');
	//Загрузка данных
	$data=$arr_all_platezh;
	$pdf->SetFont('dejavusans','',14);
	$pdf->AddPage();
	$type_platezh = $arr_all_platezh[0]['type_platezh'];
	
	$str_out = "<p>Вид платежа: ";
	if ($type_platezh == 'annuit') 
		$str_out .= "<strong>Аннуитетный платеж</strong></p>";
	elseif ($type_platezh == 'differ') 
		$str_out .= "<strong>Дифференцированный платеж</strong></p>";
	elseif ($type_platezh == 'flex') 
		$str_out .= "<strong>Гибкий платеж</strong></p>";
	$str_sum_kred = number_format($sum_kred, 2, '.', ' ');
	$str_proc = number_format($proc, 2, '.', ' ');
	$str_out .= "<p>Сумма кредита: <strong>$str_sum_kred</strong> руб.<br>";
	$str_out .= "Процентная ставка: <strong>$str_proc</strong> %<br>";
	$str_out .= "Срок кредита (мес): <strong>$col_month</strong> </p>";
	
	$str_out .= "<table class=\"platezh_table\">\n";
	//$str_out .= "<tr class=\"platezh_table_header\"><th>N</th><th>Дата</th><th>Сумма платежа</th><th>Погашение основного долга</th><th>Погашение процентов</th><th>Остаток основного долга</th></tr>";
	
	$pdf->writeHTMLCell(194, 0, '', '', $str_out, 1, 1, 0, true, '', true);	
	$pdf->FancyTable($header,$data);
	$pdf->Output('Grafik.pdf', 'I');

}

//---------------------------------------------------------------------
// Сохранение графика в Excel
//---------------------------------------------------------------------
function XLS($str_beg_date, $sum_kred, $col_month, $proc, $arr_all_platezh) 
{
	// Подключаем класс для работы с excel
	require_once('../libs/PHPExcel/Classes/PHPExcel.php');
	// Подключаем класс для вывода данных в формате excel
	require_once('../libs/PHPExcel/Classes/PHPExcel/Writer/Excel5.php');

	// Создаем объект класса PHPExcel
	$xls = new PHPExcel();
	// Устанавливаем индекс активного листа
	$xls->setActiveSheetIndex(0);
	// Получаем активный лист
	$sheet = $xls->getActiveSheet();
	// Подписываем лист
	$sheet->setTitle('График платежей');
	$type_platezh = $arr_all_platezh[0]['type_platezh'];
		
	$str_out = "Вид платежа: ";
	if ($type_platezh == 'annuit') 
		$str_out .= "Аннуитетный платеж";
	elseif ($type_platezh == 'differ') 
		$str_out .= "Дифференцированный платеж";
	elseif ($type_platezh == 'flex') 
		$str_out .= "Гибкий платеж";
	// Вставляем текст в ячейку A1
	$sheet->setCellValue("A1", $str_out);
	// Объединяем ячейки
	$sheet->mergeCells('A1:F1');
	$str_out="Сумма кредита: ";
	$str_sum_kred = number_format($sum_kred, 2, '.', ' ');
	$str_out .= $str_sum_kred;
	// Вставляем текст в ячейку A2
	$sheet->setCellValue("A2", $str_out);
	// Объединяем ячейки
	$sheet->mergeCells('A2:F2');
	$str_out="Процентная ставка: ";
	$str_proc = number_format($proc, 2, '.', ' ');
	$str_out .= $str_proc;
	// Вставляем текст в ячейку A3
	$sheet->setCellValue("A3", $str_out);
	// Объединяем ячейки
	$sheet->mergeCells('A3:F3');
	$str_out="Срок кредита: ";
	$str_out .= $col_month;
	$str_out .=" мес.";
	// Вставляем текст в ячейку A4
	$sheet->setCellValue("A4", $str_out);
	// Объединяем ячейки
	$sheet->mergeCells('A4:F4');

	
	//$str_out .= "Процентная ставка: <strong>$str_proc</strong> %<br>";
	//$str_out .= "Срок кредита (мес): <strong>$col_month</strong> </p>";
	$sheet->getStyle('A1')->getFill()->setFillType(
	    PHPExcel_Style_Fill::FILL_SOLID);
	$sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');

	// Объединяем ячейки
	$sheet->mergeCells('A1:F1');

	// Выравнивание текста
	//$sheet->getStyle('A1')->getAlignment()->setHorizontal(
	   // PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	//Выводим график
	$stroka=6;
	$sheet->setCellValueByColumnAndRow(0, $stroka-1, "N");
	$sheet->setCellValueByColumnAndRow(1, $stroka-1, "Дата");
	$sheet->setCellValueByColumnAndRow(2, $stroka-1, "Сумма платежа");
	$sheet->setCellValueByColumnAndRow(3, $stroka-1, "Погашение основного долга");
	$sheet->setCellValueByColumnAndRow(4, $stroka-1, "Погашение процентов");
	$sheet->setCellValueByColumnAndRow(5, $stroka-1, "Остаток основного долга");
	//$sheet->getRowDimension($stroka-1)->setRowHeight(-1);
	//Автовысота шапки таблицы
	$max_col = $sheet->getHighestColumn();
	for ($col = 'A'; $col <= $max_col; $col++) {
	   $stroka1=$stroka-1;
	  // $sheet->getStyle($col.$stroka1)->getAlignment()->setWrapText(true);
	  // $sheet->getColumnDimension($col)->setAutoSize(true);
	}

	foreach ($arr_all_platezh as $arr_platezh) {
		$sheet->setCellValueByColumnAndRow(0, $stroka, $arr_platezh['nomer']);
		$sheet->setCellValueByColumnAndRow(1, $stroka, " " . $arr_platezh['date']);
		$sheet->setCellValueByColumnAndRow(2, $stroka, $arr_platezh['platezh']);
		$sheet->setCellValueByColumnAndRow(3, $stroka, $arr_platezh['platezh_main_dolg']);
		$sheet->setCellValueByColumnAndRow(4, $stroka, $arr_platezh['platezh_proc']);
		$sheet->setCellValueByColumnAndRow(5, $stroka, $arr_platezh['ostatok']);
		$total_platezh += $arr_platezh['platezh'];
		$total_platezh_main_dolg += $arr_platezh['platezh_main_dolg'];
		$total_platezh_proc += $arr_platezh['platezh_proc'];
		$stroka++;
	}
	$sheet->setCellValueByColumnAndRow(1, $stroka, "Итого:");
	$sheet->setCellValueByColumnAndRow(2, $stroka, $total_platezh);
	$sheet->setCellValueByColumnAndRow(3, $stroka, $total_platezh_main_dolg);
	$sheet->setCellValueByColumnAndRow(4, $stroka, $total_platezh_proc);
	// определение максимальных размеров документа
	$max_col = $sheet->getHighestColumn();
	/* автоширина */
	for ($col = 'A'; $col <= $max_col; $col++) {
	   $sheet->getColumnDimension($col)->setAutoSize(true);
	}
	// Выводим HTTP-заголовки
	 header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
	 header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
	 header ( "Cache-Control: no-cache, must-revalidate" );
	 header ( "Pragma: no-cache" );
	 header ( "Content-type: application/vnd.ms-excel" );
	 header ( "Content-Disposition: attachment; filename=Grafik.xls" );

	// Выводим содержимое файла
	 $objWriter = new PHPExcel_Writer_Excel5($xls);
	 $objWriter->save('php://output');
}


?>