<form name="search" action="<?=$controller->url_for('main')?>" method="get">

<form name="search" action="">
<input type="hidden" name="show_lectures"  value="1" />
<input type="hidden" name="semester_start" value="<?= $semester_start ?>" />
<input type="hidden" name="inst_id"        value="<?= $inst_id ?>" />
<input type="hidden" name="inst_name"      value="<?= $inst_name ?>" />

<b>Suche</b><br />
<input type="text" name="search" size="16" value="<?= $search ?>" 
  style="background-color: AntiqueWhite; font-family: courier; font-size: large" />
<br />
<input type="submit" name="submit_search" value="Suche starten" />

<br /><br />
</form>
