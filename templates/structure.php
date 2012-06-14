<div style="font-size: large; position: relative;">

  <b><?= $semester_name ?></b><br />

  Vorlesungszeit:
  <?= $sem_rec['vorles_beginn'] ?> bis <?= $sem_rec['vorles_ende'] ?>
  &#160; (<?= $sem_rec['first_sem_week'] ?>. bis <?= $sem_rec['last_sem_week'] ?>. Kalenderwoche)

  <br />
  <br />

<? if ($show_lectures == 0) :?>

  In dieser Liste erscheinen nur Institute, die im ausgew&auml;hlten
  Semester<br />
  Veranstaltungen f&uuml;r Gasth&ouml;rende anbieten.<br /><br /><hr />


  <? if ($found == 0) :?>
    <br />

    <span style="color: red;"><b>
      Keine Veranstaltungen f&uuml;r Gasth&ouml;rende 
      in diesem Semester vorhanden.<br /><br />
    </b></span>

  <? endif ?>

  <? foreach ($inst_list as $rec_fac) :?>

    <? if ($rec_fac['active'] == 1) :?>
      <br />

      <b><?= $rec_fac['name'] ?></b><br />

      <ul style="font-size: Large;">
      <? foreach ($rec_fac['departments'] as $rec) :?>

        <? if ($rec['active'] == 0) :?>
<!--
            <i style="color: red;"><?= $rec['name'] ?></i>
-->
        <? else :?>

          <li>

            <a href="<?= $controller->url_for('main'); ?><?
                ?>?inst_id=<?= $rec['institut_id'] ?><?
                ?>&inst_name=<?= $rec['name'] ?><?
                ?>&semester_start=<?= $semester_start ?><?
                ?>&show_lectures=1">
              <?= $rec['name'] ?>

           </a>

          </li>

        <? endif ?>

      <? endforeach ?>
      </ul>

    <? endif ?>

  <? endforeach ?>

<? else: ?>

  <style type="text/css">
    div.border {
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

  <? if ($inst_id != '%') :?>
    <br /><b><?= $inst_name ?></b><br />
  <? endif ?>

  <? if ($found == 0) :?>
    <br />

    <span style="color: red;"><b>
      Keine Veranstaltungen f&uuml;r Gasth&ouml;rende 
      in diesem Semester vorhanden.<br /><br />
    </b></span>

  <? endif ?>

  <? if ($search != '%') :?>
    Suchfilter: <b style="color: red;"><?= $search ?></b>&#160; &#160;
    [ <a href="<?= $controller->url_for('main'); 
      ?>?semester_start=<?= $semester_start ?><?
      ?>&inst_id=<?= $inst_id ?><?
      ?>&inst_name=<?= $inst_name ?><?
      ?>&show_lectures=<?= $show_lectures ?>
      ">
      Suchfilter l&ouml;schen</a> ]<br />

    <? if ($inst_id == '%') :?>
      Suche &uuml;ber alle Institute
    <? endif ?>

  <? endif ?>

  <span style="font-size: 12p; font-weight: bold;">
  <?= $structure_data['inst_id'][$inst_id] ?> &#160; &#160;
  </span>
  <br /><br />

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

       <? if (strval($rec['max_stud']) != '#') :?>
          erwartete TeilnehmerInnen: <?= $rec['max_stud'] ?>
       <? endif ?>

       <? if ($rec['both'] == 1) :?>
          &#160; / &#160;
       <? endif ?>

       <? if (strval($rec['free']) != '#') :?>
          freie Pl&auml;tze: <?= $rec['free'] ?>
       <? endif ?>

       <? if (strval($rec['free']) == '0') :?>
         <br />In dieser Veranstaltungen k&ouml;nnen Gasth&ouml;rende u.U.
            dennoch aufgenommen werden. Melden Sie sich bitte<br />
            per E-Mail an <b><a style="font-family: courier; font-size: large;"
            href="mailto:studium.generale@uni-oldenburg.de"> 
            studium.generale@uni-oldenburg.de</a></b> 
            oder unter Tel. 0441/798-2275 oder -2276.

      <? endif ?>

        </i><br /><br />

        <? foreach ($rec['terms'] as $term) :?>
          &#160; &#160; &#160; &bull; &#160; <?= $term['time'] ?><br />
          &#160; &#160; &#160; &#160; &#160; Veranstaltungsort: <?= $term['location'] ?>
          <div style="line-height: 2pt;"><br /></div>

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

  <div class="border" style="padding: 0pt;" />

<? endif ?>

</div>
