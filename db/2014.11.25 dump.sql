<!DOCTYPE html>
<html lang="en" dir="ltr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="robots" content="noindex">
<title>Export: banking - Adminer</title>
<link rel="stylesheet" type="text/css" href="?file=default.css&amp;version=4.1.0&amp;driver=mysql">
<script type="text/javascript" src="?file=functions.js&amp;version=4.1.0&amp;driver=mysql"></script>
<link rel="shortcut icon" type="image/x-icon" href="?file=favicon.ico&amp;version=4.1.0&amp;driver=mysql">
<link rel="apple-touch-icon" href="?file=favicon.ico&amp;version=4.1.0&amp;driver=mysql">
<link rel="stylesheet" type="text/css" href="adminer.css">

<body class="ltr nojs" onkeydown="bodyKeydown(event);" onclick="bodyClick(event);">
<script type="text/javascript">
document.body.className = document.body.className.replace(/ nojs/, ' js');
</script>

<div id="help" class="jush-sql jsonly hidden" onmouseover="helpOpen = 1;" onmouseout="helpMouseout(this, event);"></div>

<div id="content">
<p id="breadcrumb"><a href=".">MySQL</a> &raquo; <a href='?username=root' accesskey='1' title='Alt+Shift+1'>Server</a> &raquo; <a href="?username=root&amp;db=banking">banking</a> &raquo; Export
<h2>Export: banking</h2>

<form action="" method="post">
<table cellspacing="0">
<tr><th>Output<td><label><input type='radio' name='output' value='text' checked>open</label><label><input type='radio' name='output' value='file'>save</label><label><input type='radio' name='output' value='gz'>gzip</label>
<tr><th>Format<td><label><input type='radio' name='format' value='sql' checked>SQL</label><label><input type='radio' name='format' value='csv'>CSV,</label><label><input type='radio' name='format' value='csv;'>CSV;</label><label><input type='radio' name='format' value='tsv'>TSV</label>
<tr><th>Database<td><select name='db_style'><option><option>USE<option selected>DROP+CREATE<option>CREATE</select><label><input type='checkbox' name='routines' value='1' checked>Routines</label><label><input type='checkbox' name='events' value='1' checked>Events</label><tr><th>Tables<td><select name='table_style'><option><option selected>DROP+CREATE<option>CREATE</select><label><input type='checkbox' name='auto_increment' value='1' checked>Auto Increment</label><label><input type='checkbox' name='triggers' value='1' checked>Triggers</label><tr><th>Data<td><select name='data_style'><option><option selected>TRUNCATE+INSERT<option>INSERT<option>INSERT+UPDATE</select></table>
<p><input type="submit" value="Export">
<input type="hidden" name="token" value="973059:525274">

<table cellspacing="0">
<thead><tr><th style='text-align: left;'><label class='block'><input type='checkbox' id='check-tables' onclick='formCheck(this, /^tables\[/);'>Tables</label><th style='text-align: right;'><label class='block'>Data<input type='checkbox' id='check-data' onclick='formCheck(this, /^data\[/);'></label></thead>
<tr><td><label class='block'><input type='checkbox' name='tables[]' value='accounts' onclick="checkboxClick(event, this); formUncheck(&#039;check-tables&#039;);">accounts</label><td align='right'><label class='block'><span id='Rows-accounts'></span><input type='checkbox' name='data[]' value='accounts' onclick="checkboxClick(event, this); formUncheck(&#039;check-data&#039;);"></label>
<tr><td><label class='block'><input type='checkbox' name='tables[]' value='debug' onclick="checkboxClick(event, this); formUncheck(&#039;check-tables&#039;);">debug</label><td align='right'><label class='block'><span id='Rows-debug'></span><input type='checkbox' name='data[]' value='debug' onclick="checkboxClick(event, this); formUncheck(&#039;check-data&#039;);"></label>
<tr><td><label class='block'><input type='checkbox' name='tables[]' value='transactions' onclick="checkboxClick(event, this); formUncheck(&#039;check-tables&#039;);">transactions</label><td align='right'><label class='block'><span id='Rows-transactions'></span><input type='checkbox' name='data[]' value='transactions' onclick="checkboxClick(event, this); formUncheck(&#039;check-data&#039;);"></label>
<tr><td><label class='block'><input type='checkbox' name='tables[]' value='transaction_codes' onclick="checkboxClick(event, this); formUncheck(&#039;check-tables&#039;);">transaction_codes</label><td align='right'><label class='block'><span id='Rows-transaction_codes'></span><input type='checkbox' name='data[]' value='transaction_codes' onclick="checkboxClick(event, this); formUncheck(&#039;check-data&#039;);"></label>
<tr><td><label class='block'><input type='checkbox' name='tables[]' value='users' checked onclick="checkboxClick(event, this); formUncheck(&#039;check-tables&#039;);">users</label><td align='right'><label class='block'><span id='Rows-users'></span><input type='checkbox' name='data[]' value='users' checked onclick="checkboxClick(event, this); formUncheck(&#039;check-data&#039;);"></label>
<script type='text/javascript'>ajaxSetHtml('?username=root&db=banking&script=db');</script>
</table>
</form>
</div>

<form action='' method='post'>
<div id='lang'>Language: <select name='lang' onchange="this.form.submit();"><option value="en" selected>English<option value="ar">العربية<option value="bn">বাংলা<option value="ca">Català<option value="cs">Čeština<option value="de">Deutsch<option value="es">Español<option value="et">Eesti<option value="fa">فارسی<option value="fr">Français<option value="hu">Magyar<option value="id">Bahasa Indonesia<option value="it">Italiano<option value="ja">日本語<option value="ko">한국어<option value="lt">Lietuvių<option value="nl">Nederlands<option value="no">Norsk<option value="pl">Polski<option value="pt">Português<option value="pt-br">Português (Brazil)<option value="ro">Limba Română<option value="ru">Русский язык<option value="sk">Slovenčina<option value="sl">Slovenski<option value="sr">Српски<option value="ta">த‌மிழ்<option value="th">ภาษาไทย<option value="tr">Türkçe<option value="uk">Українська<option value="vi">Tiếng Việt<option value="zh">简体中文<option value="zh-tw">繁體中文</select> <input type='submit' value='Use' class='hidden'>
<input type='hidden' name='token' value='28684:436949'>
</div>
</form>
<form action="" method="post">
<p class="logout">
<input type="submit" name="logout" value="Logout" id="logout">
<input type="hidden" name="token" value="973059:525274">
</p>
</form>
<div id="menu">
<h1>
<a href='http://www.adminer.org/' target='_blank' id='h1'>Adminer</a> <span class="version">4.1.0</span>
<a href="http://www.adminer.org/#download" target="_blank" id="version"></a>
</h1>
<script type="text/javascript" src="?file=jush.js&amp;version=4.1.0&amp;driver=mysql"></script>
<script type="text/javascript">
var jushLinks = { sql: [ '?username=root&db=banking&table=$&', /\b(accounts|debug|transactions|transaction_codes|users)\b/g ] };
jushLinks.bac = jushLinks.sql;
jushLinks.bra = jushLinks.sql;
jushLinks.sqlite_quo = jushLinks.sql;
jushLinks.mssql_bra = jushLinks.sql;
bodyLoad('5.5');
</script>
<form action="">
<p id="dbs">
<input type="hidden" name="username" value="root"><span title='database'>DB</span>: <select name='db' onmousedown='dbMouseDown(event, this);' onchange='dbChange(this);'><option value=""><option>information_schema<option selected>banking<option>dvwa<option>mysql<option>owasp10<option>performance_schema<option>samurai_dojo_basic<option>samurai_dojo_scavenger<option>test</select><input type='submit' value='Use' class='hidden'>
<input type="hidden" name="dump" value=""></p></form>
<p class='links'><a href='?username=root&amp;db=banking&amp;sql='>SQL command</a>
<a href='?username=root&amp;db=banking&amp;import='>Import</a>
<a href='?username=root&amp;db=banking&amp;dump=' id='dump' class='active '>Dump</a>
<a href="?username=root&amp;db=banking&amp;create=">Create table</a>
<p id='tables' onmouseover='menuOver(this, event);' onmouseout='menuOut(this);'>
<a href="?username=root&amp;db=banking&amp;select=accounts">select</a> <a href="?username=root&amp;db=banking&amp;table=accounts" title='Show structure'>accounts</a><br>
<a href="?username=root&amp;db=banking&amp;select=debug">select</a> <a href="?username=root&amp;db=banking&amp;table=debug" title='Show structure'>debug</a><br>
<a href="?username=root&amp;db=banking&amp;select=transactions">select</a> <a href="?username=root&amp;db=banking&amp;table=transactions" title='Show structure'>transactions</a><br>
<a href="?username=root&amp;db=banking&amp;select=transaction_codes">select</a> <a href="?username=root&amp;db=banking&amp;table=transaction_codes" title='Show structure'>transaction_codes</a><br>
<a href="?username=root&amp;db=banking&amp;select=users">select</a> <a href="?username=root&amp;db=banking&amp;table=users" title='Show structure'>users</a><br>
</div>
<script type="text/javascript">setupSubmitHighlight(document);</script>
