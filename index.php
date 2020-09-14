<!DOCTYPE html>
<html>
    <head>
		<meta name="robots" content="noindex, nofollow"/>
		<meta charset="utf-8" />
		<title>Тестовое задание</title>

		<link rel="SHORTCUT ICON" href="/favicon.ico" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style type="text/css">
			.form {margin: auto;width: 430px;margin-top: 10%;border: 1px solid #ccc;padding: 30px 40px;position: relative;}
            .check {display: flex;font-size: 22px;}
            .check * {font-size: 22px;}
			input {padding: 6px 12px;border: 1px solid #ccc;border-radius: 6px 0 0 6px;}
			input:active, input:focus {border: 1px solid #ddd;}
            button {background: #eee;padding: 6px 12px;border: 1px solid #ccc;border-radius: 0 6px 6px 0;}
            #error {color: red;font-size: 14px;display: none;position: absolute;bottom: 8px;left: 45px;}
            .green {color: green;}}
		</style>
    </head>
    <body>
        <div class="form">
            <form id="form" action="" method="post">
                <div class="check">
                    <div class="check-item">
        			    <input type="text" id="inn" placeholder="введите инн" value="3664069397" />
        			</div>
                    <div class="check-item">
        			    <button type="submit">Проверить</button>
        			</div>
        		</div>
            </form>
            <div id="error">asd0 asd0asd</div>
        </div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="/js.js?<?php echo time();?>"></script>
	</body>
</html>
