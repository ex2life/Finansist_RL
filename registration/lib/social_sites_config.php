<?php

/* ****************************************************************************
 * Конфигурация скрипта - параметры и токены социальных сетей
 */
/*
 * Имя бота в телеграмм, у которого хранятся токены
 */
if ($_SERVER['HTTP_HOST']=='finansist3261.com')
{
    define('Telegram_bot', 'finansist_authBot'); //настроено для finansist3261.com
}
if ($_SERVER['HTTP_HOST']=='finansist.andpop.ru')
{
    define('Telegram_bot', 'finansistAP_authBot'); //настроено для finansist.andpop.ru
}
if ($_SERVER['HTTP_HOST']=='finansist-online.000webhostapp.com')
{
    define('Telegram_bot', 'finansist000webhostapp_authBot'); //настроено для finansist-online.000webhostapp.com
}
if ($_SERVER['HTTP_HOST']=='finansist.ga')
{
    define('Telegram_bot', 'finansistGA_authBot'); //настроено для finansist.ga
}




/*
 * api приложения Вконтакте
 */
 ?>
<script type="text/javascript" src="//vk.com/js/api/openapi.js?152"></script>
<script type="text/javascript">
  VK.init({apiId: 6394999});
</script>


<?php

/* ********
 * Главный токен гугла
 */
 ?>
<meta name="google-signin-client_id" content="846998585230-oct4e70i9en6bivrhaak2ikremk4ga8q.apps.googleusercontent.com">

