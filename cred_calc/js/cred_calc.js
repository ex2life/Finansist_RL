//**********************************************************************************************
//Функция для добавления дополнительных input в форму для ввода сумм погашения при гибком платеже
//**********************************************************************************************
function Make_flex_payment_schedule() {
	var col_month = $.trim($("#col_month").val());
	    col_month = parseInt(col_month, 10);
	var str_date  = $.trim($("#str_beg_date").val()),
	    str_month = str_date.substr(0,2),
	    str_year  = str_date.substr(3,4);
	var month     = parseInt(str_month, 10),
	    year      = parseInt(str_year, 10);
		
	//Начинаем платить со следующего месяца после взятия кредита
	if (month == 12) {
		month = 1;
		year++;
	}
	else
		month++;
	
	var str_html = "<div></div>";
	for (var i = 1; i <= col_month; i++) {
		str_month  = (month < 10) ? "0" + month : month.toString();
		str_date   = str_month + '.' + year;
		str_html  += '<div><label class="control-label">Гашение ' + str_date;
		//Проверку значения через pattern сделать не получилось, все время ругается на неправильный формат
		//str_html += ' <input type="text" name="flex_payment_schedule[]" value="0" pattern="^\d+(\.\d{1,2})?$"/>';
		str_html += ' <input type="text" class="form-control" name="flex_payment_schedule[]" value="0"/>';
		str_html += '</label></div>';
		//$(str_html).fadeIn('slow').appendTo('.input_payment_schedule');
		
		if (month == 12) {
			month = 1;
			year++;
		}
		else
			month++;
	}
	$("#input_payment_schedule").html(str_html);

}

function ValidFormPlatezhParam() {
	// Количество платежей по гибкой схеме
	var col_flex_payments = $('input[name="flex_payment_schedule[]"]').length;
	if (col_flex_payments > 0) {
		var sum_flex_payments = 0;
		$('input[name="flex_payment_schedule[]"]').each(function(){
			sum_flex_payments += parseFloat($(this).val());
		});
		var sum_kred = parseFloat($('#sum_kred').val());
		if (sum_flex_payments > sum_kred) {
			alert('Ошибка ввода - сумма платежей по кредиту (' + sum_flex_payments +' руб) больше суммы кредита (' + sum_kred + ' руб)!');
			return false;
		};

	};
	return $('#frmPlatezhParam').valid();
}

//**********************************************************************************************
//Код выполняется после загрузки всего содержимого документа
//**********************************************************************************************
$(document).ready(function() {
	
	//Настройка проверок значений элементов ввода в форме
    $("#frmPlatezhParam").validate({
        rules: {
            sum_kred: {
                required: true,
                pattern : "^\\d+(\\.\\d{1,2})?$"
            },
            str_beg_date: {
                required: true,
                pattern : "(0[1-9]|1[012])\\.[0-9]{4}"
            },
			col_month: {
				required: true,
				number  : true,
				min     : 1
			},
            proc: {
                required: true,
                pattern : "^\\d+(\\.\\d{1,2})?$"
            }
        },
        messages: {
            sum_kred: {
                required: "Укажите сумму кредита в рублях.",
                pattern : "Неправильный формат числа. Сумма должно быть в рублях (100) или рублях и копейках (100.25)"
            },
            str_beg_date: {
                required: "Укажите дату получения кредита (месяц и год).",
                pattern : "Неправильный формат даты. Укажите месяц и год получения кредита (например, 01.2010)"
            },
			col_month: {
				required: "Укажите срок кредита.",
				number  : "Неправильный формат числа. Срок кредита - целое число",
				min     : "Минимальный срок кредита - 1 месяц"
			},
            proc: {
                required: "Укажите годовую процентную ставку кредита.",
                pattern : "Неправильный формат числа. Процент - число с двумя знаками после запятой"
            }
        }
    });

	
	//Если гибкий платеж, то сразу добавляем дополнительные поля ввода для частичных погашений
	if ($.trim($("#type_platezh").val()) == "flex")
		Make_flex_payment_schedule();
	
	//После изменения даты выдачи строим заново поля ввода для частичных погашений
	$('#str_beg_date').change(function() {
		if ($.trim($("#type_platezh").val()) == "flex")
			Make_flex_payment_schedule();
	});

	//После изменения срока кредита строим заново поля ввода для частичных погашений
	$('#col_month').change(function() {
		if ($.trim($("#type_platezh").val()) == "flex")
			Make_flex_payment_schedule();
	});

	
	//Нажимаем на кнопку в форме
	$('#btnShowPaymentSchedule').click(function(){
		//Проверяем корректность значений в полях формы
		if (ValidFormPlatezhParam()) {
			var data = $("#frmPlatezhParam :input").serialize();
			$.post($("#frmPlatezhParam").attr('action'), data, function(html_reply){
				$('#text-popup').html(html_reply);
				$.magnificPopup.open({
					items: {
						src : "#text-popup",
						type: "inline"
					}
				});
			}
			)
		}
	});
	
	//Submit в форме не используется
	$('#frmPlatezhParam').submit(function() {
		return false;
	}
	);
	
});