<?php

#----------

class MainController extends StudipController
{
#----------

private $template_factory;
private $template;
private $semester_data;
private $structure;

#==========

public function before_filter (&$action, &$args)
{
  PageLayout::setTitle('Studium Generale');
  
  init::main();

  $this->template_factory = new Flexi_TemplateFactory
    ($this->dispatcher->controller->getPluginPath().'/templates');

  $this->template = $this->template_factory->open('structure');
  $this->template->set_attribute('controller', $this);

  $this->template->set_layout($GLOBALS['template_factory']
    ->open('layouts/base'));

} # before_filter

#==========

public function index_action()
{
  $show_lectures = (REQUEST::get('show_lectures') > 0 ?
    REQUEST::get('show_lectures') : 0);

  $semester_start = (REQUEST::get('semester_start') != '' ?
    REQUEST::get('semester_start') : time());

  $inst_id = (REQUEST::get('inst_id') != '' ?
    REQUEST::get('inst_id') : '-');

  $this->structure = new structure();
  $this->structure->get_inst_list();

  $this->semester_data = new SemesterData();
  $sem_rec = $this->semester_data->getSemesterDataByDate($semester_start);
  $semester_start = $sem_rec['beginn'];

  $this->make_infobox($semester_start, $show_lectures);

  #----------

  $this->template->set_attribute('inst_id',        REQUEST::get('inst_id'));
  $this->template->set_attribute('inst_name',      REQUEST::get('inst_name'));
  $this->template->set_attribute('show_lectures',  $show_lectures);
  $this->template->set_attribute('semester_start', $semester_start);
  $this->template->set_attribute('semester_name',  $sem_rec['name']);
  $this->template->set_attribute('inst_list',      $this->structure->inst_list);

  if ($show_lectures == 1)
  {
    $this->structure->get_lecture_list($semester_start, $inst_id);

    $this->template->set_attribute
      ('lecture_list', $this->structure->lecture_list);

  } # if show_lectures

  #----------

  print($this->template->render());

} # index_action

#==========

private function make_infobox ($semester_start, $show_lectures)
{
  $sem_list = $this->semester_data->getAllSemesterData();
  krsort($sem_list);

  $sem_list
    = $this->structure->expand_semester_list($sem_list, $semester_start);


  $this->infobox_1 = $this->template_factory->open('infobox_1');

  $this->infobox_1->set_attribute('controller', $this);
  $this->infobox_1->set_attribute('semester_start', $semester_start);
  $this->infobox_1->set_attribute('inst_id',        REQUEST::get('inst_id'));
  $this->infobox_1->set_attribute('inst_name',      REQUEST::get('inst_name'));
  $this->infobox_1->set_attribute('show_lectures',  $show_lectures);
  $this->infobox_1->set_attribute('semester_list',  $sem_list);

  #-----

  $this->infobox_2 = $this->template_factory->open('infobox_2');

  $this->infobox_2->set_attribute('controller', $this);
  $this->infobox_2->set_attribute('semester_start', $semester_start);

  #----------

  $content = array('kategorie' => 'Aktionen:', 'eintrag' => array());

  $content['eintrag'][] = array(
    'icon' => 'icons/16/black/arr_2right.png', 
    'text' => $this->infobox_1->render()
  );

  $infobox = array(
    'picture' => 'infobox/details.jpg',
    'content' => array($content)
  );

  if ($show_lectures == 1)
  {
    $content['eintrag'][] = array(
      'icon' => 'icons/16/black/arr_2right.png', 
      'text' => $this->infobox_2->render()
    );

    $infobox['content'] = array($content);

  } # if lectures

  $this->template->set_attribute('infobox', $infobox);

} # make_infobox

#==========

} # class MainController

#----------

#?>
