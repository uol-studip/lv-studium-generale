<?php

class structure
{
#----------

public  $inst_list;
public  $lecture_list;
private $seminar_id_list;
public  $inst_active;
public  $found;

#==========

public function __construct ()
{

} # __construct

#==========

public function expand_semester_list ($semester_list, $semester_start)
{
  foreach ($semester_list as $index => $rec)
  {
    $semester_list[$index]['selected']
      = ($rec['beginn'] == $semester_start ? ' selected="selected"' : '');

  } #  foreach semester_list

  return($semester_list);

} # expand_semester_list

#==========

public function format_date ($timestamp)
{
  $date = getdate($timestamp);

  $day   = sprintf('%02d', $date['mday']);
  $month = sprintf('%02d', $date['mon']);
  $year  = $date['year'];

  return($day.'.'.$month.'.'.$year);

} # format_date

#==========

public function get_inst_list ()
{
  $query = "
    select
      i.Name,
      i.fakultaets_id
        from
          Institute i
            where i.institut_id = i.fakultaets_id
              order by i.name;
  ";

  $query_result = DBManager::get()->query($query)->fetchAll(PDO::FETCH_ASSOC);

  $this->inst_list = array();
  $this->found = 0;

  foreach ($query_result as $rec)
  {
    $this->inst_list[$rec['fakultaets_id']]
      = array('name' => $rec['Name'], 'departments' => array(), 'active' => 0);

  } # foreach query_result

  #----------

  $query = "
    select
      i.Institut_id,
      i.Name,
      i.fakultaets_id
        from
          Institute i
            where i.institut_id != i.fakultaets_id
              and i.institut_id != '0000'
                order by i.name;
  ";

  $query_result = DBManager::get()->query($query)->fetchAll(PDO::FETCH_ASSOC);

  foreach ($query_result as $rec)
  {
    $item = array('name' => $rec['Name'], 'institut_id' => $rec['Institut_id'], 
      'active' => 0);

    if (isset($this->inst_active[$rec['Institut_id']]))
    {
      $item['active'] = 1;
      $this->inst_list[$rec['fakultaets_id']]['active'] = 1;
      $this->found = 1;

    } # if active

    $this->inst_list[$rec['fakultaets_id']]['departments'][] = $item;

  } # foreach query_result

} # get_inst_list

#==========

public function get_lecture_list ($semester_start, $inst_id, $search)
{
  global $SEM_TYPE;
  $this->inst_active = array();

  $weekday_table = array('', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So');

  $this->get_lecturer_list($search);

  $query = "
    select
      s.seminar_id,
      s.institut_id,
      s.VeranstaltungsNummer,
      s.Name,
      s.status,
      s.start_time,
      s.ort,
      s.admission_type,
      s.admission_turnout
        from
          seminar_sem_tree sst,
          seminare s
            where s.seminar_id = sst.seminar_id
              and sst.sem_tree_id = '18ca85409be550468d39fc2c0c8313c6'
              and s.Institut_id like '".$inst_id."'
              and s.start_time = ".$semester_start."
              and s.visible > 0
              and (
                     s.Name like '%".$search."%'
                       or
                     s.VeranstaltungsNummer like '%".$search."%'
                       or
                     s.seminar_id in ('".join("' , '", $this->seminar_id_list)."')
                   )
                     order by s.VeranstaltungsNummer;
  ";

/*

*/

  $this->lecture_list = DBManager::get()->query($query)->fetchAll(PDO::FETCH_ASSOC);

###  $code_type = array(0 => '', 1 => 'nur &uuml;ber Anmeldeverfahren / ',
###    2 => 'nur &uuml;ber Anmeldeverfahren / ',
###    3 => 'keine Anmeldung m&ouml;glich');

  foreach ($this->lecture_list as $index => $rec)
  {
    $this->inst_active[$rec['institut_id']] = '#';

    if ($rec['ort'] == '')
    {
      $rec['ort'] = 'ohne Raumangabe';

    } # if ! ort

    $this->lecture_list[$index]['art'] = $SEM_TYPE[$rec['status']]['name'];

    $this->lecture_list[$index]['max_stud'] = '#';
    $this->lecture_list[$index]['free']     = '#';
    $this->lecture_list[$index]['both']     = '#';

    if ($rec['admission_turnout'] > 0)
    {
      $this->lecture_list[$index]['max_stud'] = $rec['admission_turnout'];

    } # if turnout

    if ($rec['admission_type'] > 0)
    {
      $query = "
        select
          count(su.user_id) as count_stud
            from
              seminar_user su
                where su.Seminar_id = '".$rec['seminar_id']."'
                  and su.status = 'autor';
      ";

      $query_result = DBManager::get()->query($query)->fetch(PDO::FETCH_ASSOC);

      $this->lecture_list[$index]['free']
        = $rec['admission_turnout'] - $query_result['count_stud'];

      if ($this->lecture_list[$index]['free'] <= 0)
      {
        $this->lecture_list[$index]['free'] = 0;

      } # if <= 0

      if ($rec['admission_turnout'] > 0)
      {
        $this->lecture_list[$index]['both'] = 1;

      } # if both

    } # if ! keine Anmeldung

    $query = "
      select
        au.Vorname,
        au.Nachname
          from
            seminar_user su,
            auth_user_md5 au
              where su.Seminar_id = '".$rec['seminar_id']."'
                and su.status = 'dozent'
                and su.user_id = au.user_id
    ";

    $lecturers = DBManager::get()->query($query)->fetchAll(PDO::FETCH_ASSOC);

    $this->lecture_list[$index]['lecturers'] = array();

    foreach ($lecturers as $rec_lecturer)
    {
      $this->lecture_list[$index]['lecturers'][]
        = $rec_lecturer['Vorname'].' '.$rec_lecturer['Nachname'];

    } # foreach lecturers

    #----------

    $query = "
      select distinct
        scd.weekday,
        scd.start_time,
        scd.end_time,
        scd.metadate_id
          from
            seminar_cycle_dates scd
              where scd.seminar_id = '".$rec['seminar_id']."'
                order by scd.weekday, scd.start_time;
    ";

    $cycle = DBManager::get()->query($query)->fetchAll(PDO::FETCH_ASSOC);

    $this->lecture_list[$index]['terms'] = array();

    foreach ($cycle as $rec_cycle)
    {
      $query_text = "
        select
          t.raum
            from
              termine t
                where t.metadate_id = '".$rec_cycle['metadate_id']."';
      ";

      $terms_text = DBManager::get()->query($query_text)->fetchAll(PDO::FETCH_ASSOC);

      $query_assign = "
        select
          ro.name
            from
              termine t,
              resources_assign ra,
              resources_objects ro
                where t.metadate_id = '".$rec_cycle['metadate_id']."'
                  and t.termin_id = ra.assign_user_id
                  and ra.resource_id = ro.resource_id;
      ";

      $terms_assign = DBManager::get()->query($query_assign)->fetchAll(PDO::FETCH_ASSOC);

      $room_list = array('raum' => array(), 'name' => array());

      foreach ($terms_text as $rec_terms)
      {
        if ($rec_terms['raum'] != '')
        {
          $room_list['raum'][$rec_terms['raum']] = '#';

        } # if room

      } # foreach terms_text

      foreach ($terms_assign as $rec_terms)
      {
        if ($rec_terms['name'] != '')
        {
          $room_list['name'][$rec_terms['name']] = '#';

        } # if room

      } # foreach terms

      $room_list_found = array();

      if (count($room_list['name']) > 0)
      {
        $room_list_found = $room_list['name'];

      }
      elseif (count($room_list['raum']) > 0)
      {
        $room_list_found = $room_list['raum'];

      }
      else
      {
        $room_list_found = array($rec['ort'] => '#');

      } # else raum
      
      ksort($room_list_found);

      $term_data = array();

      $term_data['time'] =
        $weekday_table[$rec_cycle['weekday']]
        .', '.substr($rec_cycle['start_time'], 0, -3).' - '
        .' '.substr($rec_cycle['end_time'], 0, -3);

      $term_data['location'] = join(' / ', array_keys($room_list_found));

      $this->lecture_list[$index]['terms'][] = $term_data;

    } # foreach metadate

  } # foreach lecture_list

  #----------

  $this->mark_search($search);

} # get_lecture_list


#==========

public function get_lecturer_list ($search)
{
  $query = "
    select distinct
      su.Seminar_id
        from
          auth_user_md5 a,
          seminar_user su
            where su.status = 'dozent'
              and (
                 a.Vorname  like '%".$search."%'
                   or
                 a.Nachname like '%".$search."%'
               )
               and a.user_id = su.user_id;
  ";

  $query_result = DBManager::get()->query($query)->fetchAll(PDO::FETCH_ASSOC);

  $this->seminar_id_list = array();

  foreach ($query_result as $rec)
  {
    $this->seminar_id_list[] = $rec['Seminar_id'];

  } # foreach query_result

} # get_lecturer_list

#==========

private function mark_search ($search)
{
  foreach ($this->lecture_list as $index => $rec)
  {
    $pattern = '/('.$search.')/i';
    $replace = '<font color="red"><b>$1</b></font>';

    $this->lecture_list[$index]['VeranstaltungsNummer']
      = preg_replace($pattern, $replace, $rec['VeranstaltungsNummer']);

    $this->lecture_list[$index]['Name']
      = preg_replace($pattern, $replace, $rec['Name']);

    foreach ($rec['lecturers'] as $pos => $lecturer)
    {
      $this->lecture_list[$index]['lecturers'][$pos]
        = preg_replace($pattern, $replace, $lecturer);

    } # foreach lecturers

  } # foreach lecture_list

} # mark_search

#==========

} # class structure

#?>
