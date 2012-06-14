<form name="inst_list" action="<?=$controller->url_for('main')?>" method="get">

 <input type="hidden" name="inst_id"       value="<?= $inst_id ?>" />
 <input type="hidden" name="show_lectures" value="<?= $show_lectures ?>" />
 <input type="hidden" name="inst_name"     value="<?= $inst_name ?>" />

  <b>Semesterauswahl</b><div style="line-height: 2pt;"><br /></div>

  <select name="semester_start" onchange="submit()">

    <? foreach ($semester_list as $rec) :?>
      <option value="<?= $rec['beginn'] ?>"<?= $rec['selected'] ?>>
        <?= $rec['name'] ?></option>
    <? endforeach ?>
  </select>

</form>

<br />

