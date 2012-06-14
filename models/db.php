<?php

#----------

class db
{
#==========

public function connect ()
{
  define('DB_PASSWD', '<studip!>');

  $host = "elearningsrv05.uni-oldenburg.de:3306";
  $user = "studip";

  define('DBH', mysql_connect($host, $user, DB_PASSWD));

} # __construct

#==========

function database ($database)
{
  mysql_select_db($database, DBH) or die ('no connection');

} # database

#==========

function read ($query)
{
  $data = array();

  $cursor = mysql_query($query, DBH) or die ('query error');

  while ($data[] = mysql_fetch_array($cursor, MYSQL_ASSOC))
  { } # while

  array_pop($data);
  # delete empty last element

  return $data;

} # read

#==========

function write ($query)
{
  mysql_query($query, DBH) or die ('query error');

} # write

#==========

} # class db_studip

#----------

#?>
