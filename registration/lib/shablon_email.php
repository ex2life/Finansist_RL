<?php
/*
 * Генерация письма для подтверждения почты
 */
function gener_email_html($user)
{
// текст письма
$html = "
<html lang='ru'>
<head>
<title>Подтверждение регистрации</title>
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width'>
</head>
<body style='margin: 0; padding: 0;'>

<!-- HEADER -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff'>
            <div align='center' style='padding: 0px 15px 0px 15px;'>
                <table border='0' cellpadding='0' cellspacing='0' width='500' class='wrapper'>
                    <!-- LOGO/PREHEADER TEXT -->
                    <tr>
                        <td style='padding: 20px 0px 30px 0px;' class='logo'>
                            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                <tr> 
								    <!-- —сылку на сайт нужно добавить -->
                                    <td bgcolor='#ffffff' width='100' align='left'><a href='' target='_blank'><img alt='Logo' src='http://i104.fastpic.ru/big/2018/0615/f6/f4d3bca596b581cdefd85a6fe09be8f6.png' width='214' height='43' style='display: block; font-family: Helvetica, Arial, sans-serif; color: #666666; font-size: 16px;' border='0'></a></td>
                                    <td bgcolor='#ffffff' width='400' align='right' class='mobile-hide'>
                                        <table border='0' cellpadding='0' cellspacing='0'>
                                            <tr>
                                                <td align='right' style='padding: 0 0 5px 0; font-size: 14px; font-family: Arial, sans-serif; color: #666666; text-decoration: none;'><span style='color: #666666; text-decoration: none;'>Фининсист - это то, что нужно.<br>Ваш помощник в финансах.</span></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

<!-- ONE COLUMN SECTION -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff' align='center' style='padding: 70px 15px 20px 15px;' class='section-padding'>
            <table border='0' cellpadding='0' cellspacing='0' width='500' class='responsive-table'>
                <tr>
                    <td>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td>
                                    <!-- COPY -->
                                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                        <tr>
                                            <td align='center' style='font-size: 40px; font-family: Helvetica, Arial, sans-serif; color: #333333; line-height: 40px; padding-top: 30px;' class='padding-copy'>Cпасибо за регистрацию на сайте, ".$user['fullname']."!</td>
                                        </tr>
										<tr>
                                            <td align='center' style='font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;' class='padding-copy'>Осталось подтвердить вашу учетную запись.</td>
                                        </tr>
                                        <tr>
                                            <td align='center' style='padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;' class='padding-copy'>Просто нажмите на кнопку, чтобы мы были уверены в правильности указанной почты.</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <!-- BULLETPROOF BUTTON -->
                                    <table width='100%' border='0' cellspacing='0' cellpadding='0' class='mobile-button-container'>
                                        <tr>
                                            <td align='center' style='padding: 25px 0 0 0;' class='padding-copy'>
                                                <table border='0' cellspacing='0' cellpadding='0' class='responsive-table'>
                                                    <tr>
                                                        <td align='center'><a href='".$user['link']."' target='_blank' style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #5D9CEC; border-top: 15px solid #5D9CEC; border-bottom: 15px solid #5D9CEC; border-left: 25px solid #5D9CEC; border-right: 25px solid #5D9CEC; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;' class='mobile-button'>Подтвердить &rarr;</a></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Проблемы регистрации -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#f8f8f8' align='center' style='padding: 20px 15px 20px 15px;' class='section-padding-bottom-image'>
            <table border='0' cellpadding='0' cellspacing='0' width='500' class='responsive-table'>
                <tr>
                    <td>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td align='center' style='padding: 0 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;' class='padding-copy'>Если не получается, перейдите по этой ссылке или скопируйте и вставьте её в браузер</td>
                            </tr>
							<tr>
								<td valign='top'>
								  <table width='585' align='left' cellpadding='0' cellspacing='0' border='0' bgcolor='transparent' style='table-layout:fixed'>
									<tbody><tr>
									  <td valign='top' style='font-size:0;line-height:0' width='17' bgcolor='transparent'>
										<div style='overflow:hidden;font-size:0;line-height:0;background-color:transparent;width:17px'><font size='0' style='font-size:0px;line-height:0px;display:block;background-color:transparent'><span style='font-size:0px;line-height:0px;display:block;background-color:transparent'></span></font></div>
									  </td>
									  <td valign='top'><img src='http://i98.fastpic.ru/big/2017/1126/d4/46ebc0ceafba20e566b13fda469441d4.png' width='27' height='27' align='left' class='CToWUd'>
									  </td>
									  <td valign='top' width='537' style='word-wrap:break-word;word-break:break-all;word-break:break-word'><a href='".$user['link']."' style='text-decoration:none' target='_blank' ><span style='font-family:Tahoma,Arial,Helvetica,FreeSans,sans-serif;font-size:16px;line-height:20px;font-weight:normal;color:#00a5ff;text-decoration:underline'>".$user['link']."</span></a>
									  </td>
									</tr>
								  </tbody></table>
								</td>
							</tr>
							 <tr>
                                <td align='center' style='padding: 0 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;' class='padding-copy'>Если вы не понимаете, что происходит, проигнорируйте это письмо</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


<!-- FOOTER -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff' align='center'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>
                <tr>
                    <td style='padding: 20px 0px 20px 0px;'>
                        <!-- UNSUBSCRIBE COPY -->
                        <table width='500' border='0' cellspacing='0' cellpadding='0' align='center' class='responsive-table'>
                            <tr>
                                <td align='center' style='padding: 0 0 0 0; font-size: 14px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;' class='padding-copy'>По всем вопросам пишите на <a href='mailto:finansistslepakov@yandex.ru'>finansistslepakov@yandex.ru</a><br></td>
                            </tr>  
							<tr>
							<td align='center' valign='middle' style='font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;'>
                                    <span class='appleFooter' style='color:#666666;'>Вы получили это письмо, поскольку при регистрации на сайте Finansist был указан адрес ".$user['email']."</span>
                                </td>
                            </tr><tr>
							<td align='center' valign='middle' style='font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;'>
                                    <span class='appleFooter' style='color:#666666;'>2018 Finansist</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>
";

	return $html;
}


/*
 * Генерация письма для смены забытого пароля
 */
function gener_email_html_pass($user)
{
// текст письма
$html = "
<html lang='ru'>
<head>
<title>Изменение забытого пароля</title>
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width'>
</head>
<body style='margin: 0; padding: 0;'>

<!-- HEADER -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff'>
            <div align='center' style='padding: 0px 15px 0px 15px;'>
                <table border='0' cellpadding='0' cellspacing='0' width='500' class='wrapper'>
                    <!-- LOGO/PREHEADER TEXT -->
                    <tr>
                        <td style='padding: 20px 0px 30px 0px;' class='logo'>
                            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                <tr> 
								    <!-- Ссылку на сайт нужно добавить -->
                                    <td bgcolor='#ffffff' width='100' align='left'><a href='' target='_blank'><img alt='Logo' src='http://i104.fastpic.ru/big/2018/0615/f6/f4d3bca596b581cdefd85a6fe09be8f6.png' width='214' height='43' style='display: block; font-family: Helvetica, Arial, sans-serif; color: #666666; font-size: 16px;' border='0'></a></td>
                                    <td bgcolor='#ffffff' width='400' align='right' class='mobile-hide'>
                                        <table border='0' cellpadding='0' cellspacing='0'>
                                            <tr>
                                                <td align='right' style='padding: 0 0 5px 0; font-size: 14px; font-family: Arial, sans-serif; color: #666666; text-decoration: none;'><span style='color: #666666; text-decoration: none;'>Фининсист - это то, что нужно.<br>Ваш помощник в финансах.</span></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

<!-- ONE COLUMN SECTION -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff' align='center' style='padding: 70px 15px 20px 15px;' class='section-padding'>
            <table border='0' cellpadding='0' cellspacing='0' width='500' class='responsive-table'>
                <tr>
                    <td>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td>
                                    <!-- COPY -->
                                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                        <tr>
                                            <td align='center' style='font-size: 40px; font-family: Helvetica, Arial, sans-serif; color: #333333; line-height: 40px; padding-top: 30px;' class='padding-copy'>Здравствуйте, ".$user['fullname'].", кажется вы забыли пароль!</td>
                                        </tr>
										<tr>
                                            <td align='center' style='font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;' class='padding-copy'>Давайте его восстановим.</td>
                                        </tr>
                                        <tr>
                                            <td align='center' style='padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;' class='padding-copy'>Просто нажмите на кнопку, чтобы изменить свой пароль.</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <!-- BULLETPROOF BUTTON -->
                                    <table width='100%' border='0' cellspacing='0' cellpadding='0' class='mobile-button-container'>
                                        <tr>
                                            <td align='center' style='padding: 25px 0 0 0;' class='padding-copy'>
                                                <table border='0' cellspacing='0' cellpadding='0' class='responsive-table'>
                                                    <tr>
                                                        <td align='center'><a href='".$user['link']."' target='_blank' style='font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #5D9CEC; border-top: 15px solid #5D9CEC; border-bottom: 15px solid #5D9CEC; border-left: 25px solid #5D9CEC; border-right: 25px solid #5D9CEC; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;' class='mobile-button'>Изменить пароль &rarr;</a></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Проблемы регистрации -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#f8f8f8' align='center' style='padding: 20px 15px 20px 15px;' class='section-padding-bottom-image'>
            <table border='0' cellpadding='0' cellspacing='0' width='500' class='responsive-table'>
                <tr>
                    <td>
                        <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td align='center' style='padding: 0 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;' class='padding-copy'>Если не получается, перейдите по этой ссылке или скопируйте и вставьте её в браузер</td>
                            </tr>
							<tr>
								<td valign='top'>
								  <table width='585' align='left' cellpadding='0' cellspacing='0' border='0' bgcolor='transparent' style='table-layout:fixed'>
									<tbody><tr>
									  <td valign='top' style='font-size:0;line-height:0' width='17' bgcolor='transparent'>
										<div style='overflow:hidden;font-size:0;line-height:0;background-color:transparent;width:17px'><font size='0' style='font-size:0px;line-height:0px;display:block;background-color:transparent'><span style='font-size:0px;line-height:0px;display:block;background-color:transparent'></span></font></div>
									  </td>
									  <td valign='top'><img src='http://i98.fastpic.ru/big/2017/1126/d4/46ebc0ceafba20e566b13fda469441d4.png' width='27' height='27' align='left' class='CToWUd'>
									  </td>
									  <td valign='top' width='537' style='word-wrap:break-word;word-break:break-all;word-break:break-word'><a href='".$user['link']."' style='text-decoration:none' target='_blank' ><span style='font-family:Tahoma,Arial,Helvetica,FreeSans,sans-serif;font-size:16px;line-height:20px;font-weight:normal;color:#00a5ff;text-decoration:underline'>".$user['link']."</span></a>
									  </td>
									</tr>
								  </tbody></table>
								</td>
							</tr>
							 <tr>
                                <td align='center' style='padding: 0 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;' class='padding-copy'>Если вы не понимаете, что происходит, проигнорируйте это письмо</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


<!-- FOOTER -->
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td bgcolor='#ffffff' align='center'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>
                <tr>
                    <td style='padding: 20px 0px 20px 0px;'>
                        <!-- UNSUBSCRIBE COPY -->
                        <table width='500' border='0' cellspacing='0' cellpadding='0' align='center' class='responsive-table'>
                            <tr>
                                <td align='center' style='padding: 0 0 0 0; font-size: 14px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;' class='padding-copy'>По всем вопросам пишите на <a href='mailto:finansistslepakov@yandex.ru'>finansistslepakov@yandex.ru</a><br></td>
                            </tr>  
							<tr>
							<td align='center' valign='middle' style='font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;'>
                                    <span class='appleFooter' style='color:#666666;'>Вы получили это письмо, поскольку при восстановлении пароля на сайте Finansist был указан адрес ".$user['email'].". Если это были не вы, проигнорируйте это письмо.</span>
                                </td>
                            </tr><tr>
							<td align='center' valign='middle' style='font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;'>
                                    <span class='appleFooter' style='color:#666666;'>2018 Finansist</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>
";

	return $html;
}
