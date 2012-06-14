 <form name="inst_list" action="" method="get">

  &#160;<b>Semesterauswahl:</b><div style="line-height: 2pt;"><br /></div>

  <select name="selector" 
    onchange="self.location = this.options[this.selectedIndex].value
      +'&inst_id=<?= $inst_id ?>&show_lectures=<?= $show_lectures ?>'
      +'&inst_name=<?= $inst_name ?>';">

    <? foreach ($semester_list as $rec) :?>
      <option value="<?= $rec['link'] ?>" <?= $rec['selected'] ?>>
        <?= $rec['name'] ?></option>
    <? endforeach ?>
  </select>

</form>

<? if ($show_lectures == 1) :?>
  <br />
  &#160; <b>&gt;</b> &#160;
  <a href="<?= $url_plugin ?>?semester_start=<?= $semester_start ?>">
  Liste der Institute</a>
<? endif ?>
