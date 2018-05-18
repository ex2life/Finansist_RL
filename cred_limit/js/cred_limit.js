
function is_valid_INN() 
{
	var INN                = $('#INN').val();
	var correct_INN_Length = $('#OPF option:selected').attr('data-INN_Length');
	return (INN.length == correct_INN_Length);
}

//Проверяем, проработала ли компания 6 полных месяцев, начиная с Date_Begin_Work до Current_Date
function is_Date_Correct_for_Cred_Limit(Date_Begin_Work, Current_Date)
{
	if (Date_Begin_Work.getDate() == 1) 
		//Если деятельность начали с 1-го числа, то начинаем отсчитывать 6 месяцев с этой даты
		Start_Date = new Date(Date_Begin_Work);
	else
		if (Date_Begin_Work.getMonth() < 11)
			//Если начали работать не в декабре, то начинаяем отсчет с 1 числа следующего месяца
			Start_Date = new Date(Date_Begin_Work.getFullYear(), Date_Begin_Work.getMonth()+1, 1);
		else
			//Если начали работать в декабре, то начинаяем отсчет с 1 января  следующего месяца
			Start_Date = new Date(Date_Begin_Work.getFullYear()+1, 0, 1);
	
	var End_Date = new Date(Start_Date);
	End_Date.setMonth(End_Date.getMonth()+6);
	return (End_Date <= Current_Date);

}

// function wait(ms){
// 	var start = new Date().getTime();
// 	var end   = start;
// 	while(end < start + ms) {
// 	  end = new Date().getTime();
//    }
//  }

//**********************************************************************************************
//Код выполняется после загрузки всего содержимого документа
//**********************************************************************************************
$(document).ready(function() {
	
	$('#btnError_message').click(function(){
		$('#error_message_div').hide();
	}
	);
	$('#btnWarning_message').click(function(){
		$('#warning_message_div').hide();
	}
	);

	if ($('#error_message').text() != "NO_ERRORS")
		$('#error_message_div').show();
	if ($('#warning_message').text() != "NO_WARNINGS")
		$('#warning_message_div').show();
	
	$('.validated_company_form').submit(function(event){
		event.preventDefault();

		var INN               = $('#INN').val();
		var OPF               = $('#OPF').val();
		var INN_Length        = $('#OPF option:selected').attr('data-INN_Length');
		var Cred_Limit_Affect = $('#SNO option:selected').attr('data-Cred_Limit_Affect');
		var SNO               = $('#SNO').val();
		var sDate_Registr     = $('#Date_Registr').val();
		var sDate_Begin_Work  = $('#Date_Begin_Work').val();
		if ((sDate_Begin_Work == null) || (sDate_Begin_Work.trim() == '')) 
		{
			$('#Date_Begin_Work').val(sDate_Registr);
			sDate_Begin_Work = sDate_Registr;
		}
		
		var Date_Registr    = new Date(sDate_Registr);
		var Date_Begin_Work = new Date(sDate_Begin_Work);
		if (Date_Begin_Work < Date_Registr) 
		{
			alert('Ошибка: дата начала деятельности меньше даты регистрации!');
			return;
		}
		
		// alertify.alert('Ready!');

		var Current_Date = new Date();
		if (Current_Date < Date_Registr) 
		{
			alert('Ошибка: дата регистрации больше текущей даты!');
			return;
		}

		if (!is_Date_Correct_for_Cred_Limit(Date_Begin_Work, Current_Date))
			alert("Внимание! \n Компания работает менее 6 полных месяцев, в расчете кредитного лимита участвовать не будет.");

		if (Cred_Limit_Affect==0 ) {
			alert('Внимание! \n  Компании, работающие по системе '+SNO+', не учитываются в расчете суммы кредита!');
			// Пробовал сделать magnific-popup с сообщением - не получается, оно сразу закрывается и срабатывает submit
			// message = '<b>Внимание!</b> <br>Компании, работающие по системе '+SNO+', не учитываются в расчете суммы кредита!';
			// $('#text-popup').html(message);
			// $.magnificPopup.open({
			// 	items: {
			// 		src : "#text-popup",
			// 		type: "inline"
			// 	}
			// });
		}
		
		if (!is_valid_INN()) {
			$('#error_message').html('Некорректный ИНН: для <b>'+OPF+'</b> длина должна быть <b>'+INN_Length+'</b>.');
			$('#error_message_div').show();
			return;
		}
		
		$('.validated_company_form').unbind('submit').submit();
	});


});