<html>

    <head>
        <script type="text/javascript">
            function setServerStatus(status) {
console.log(status);
}
function checkServerStatus()
{
    setServerStatus("unknown");
	var img2 = document.createElement("img");
	img2.hidden = true;
	var img = document.body.appendChild(img2);
    img.onload = function()
    {
        setServerStatus("online");
    };
    img.onerror = function()
    {
        setServerStatus("offline");
    };
    img.src = "https://telegram.org/img/t_logo.png";
}
         </script>
    </head>

    <body>
         <input type="button" onclick="checkServerStatus()" value="Доступен?"/>
    </body>

</html>