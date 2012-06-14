<!--

<div style="font-size: large; position: relative;">

  <b><?= $semester_name ?></b>
  <hr />


  <div style="
    left: 822px;
    position: absolute;
    border: 1pt;
    border-style: solid;
    padding: 10pt;
  ">

   <form name="inst_list" action="" method="get">

    &#160;<b>Semester:</b><br />

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

  </div>

<? if ($show_lectures == 0) :?>

  <? $first_letter = '#'; ?>

  <? foreach ($inst_list as $rec) :?>

    <? if (strtoupper(substr($rec['Name'], 0, 1)) != $first_letter) :?>
     <? $first_letter = strtoupper(substr($rec['Name'], 0, 1)); ?>

      <br /><b><?= $first_letter ?></b><br />

    <? endif ?>

    <span style="font-size: small;"> &gt; </span>
    <a href="<? URL_PLUGIN ?>?inst_id=<?= $rec['Institut_id'] ?>&inst_name=<?= $rec['Name'] ?>
      &semester_start=<?= $semester_start ?>&show_lectures=1">
      <?= $rec['Name'] ?>
    </a>
    <br />

  <? endforeach ?>

<? else: ?>

  <style type="text/css">
    div.border {
      width: 1000px;
      border-top-width: 1pt;
      border-left-width: 1pt;
      border-right-width: 1pt;
      border-bottom-width: 0pt;
      border-style: solid;
      padding: 10pt;
      position: relative;
    }
    
    div.left {
      text-align: left;
      width: 70%;
      position: relative;
      top: 0pt;
      left: 0pt;
    }

    div.right {
      text-align: right;
      width: 30%;
      position: absolute;
      top: 10pt;
      left: 70%;
    }

  </style>

  <br /><b><?= $inst_name ?></b>
  <br /><?= $semester_name ?>
  &#160; &#160; &#160;
  &#160; &#160; &#160;
  [ <a href="<?= $url_plugin ?>?semester_start=<?= $semester_start ?>">zur&uuml;ck zur Liste der Institute</a> ]

  <span style="font-size: 12p; font-weight: bold;">
  <?= $structure_data['inst_id'][$inst_id] ?> &#160; &#160;
  </span>
  <br /><br />

  <? if (count($lecture_list) == 0) :?>
    <br />
    Keine Veranstaltungen f&uuml;r dieses Semester vorhanden.<br /><br />
  <? endif ?>

  <? foreach ($lecture_list as $rec) :?>
    <div class="border">
      <div class="left">
        <?= $rec['VeranstaltungsNummer'] ?>&#160;
        <?= $rec['Name'] ?>

        <? if ($rec['art'] != '') :?>
          &#160; (<?= $rec['art'] ?>)
        <? endif ?>
        <br />
        <i style="font-size: small;">
           ( <?= $rec['belegt'] ?><?= $rec['max_stud'] ?><?= $rec['free'] ?> )
        </i><br /><br />

        <? foreach ($rec['terms'] as $term) :?>
          &#160; &#160; &#160; &bull;&#160; <?= $term ?><br />
        <? endforeach ?>

        <? for ($i = 1; $i < count($rec['lecturers']) - count($rec['terms']); $i++) :?>
        <br />
        <? endfor ?>

      </div>

      <div class="right">
        <? foreach ($rec['lecturers'] as $lecturer) :?>
          <?= $lecturer ?>&#160; &#160;<br />
        <? endforeach ?>
      </div>

    </div>

  <? endforeach ?>

  <div class="border" style="padding: 0pt; width: 1025px;" />

<? endif ?>

</div>

-->

