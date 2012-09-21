<?php
include('sanitize.inc.php');

// Used to sanitize arrays. Great for superglobals

function ATsanitize($input)
{
   $user_input = trim($input);
   
   if (get_magic_quotesgpc())
   {
      $input = stripslashes($input);
   }
}
   
   


function DMAsanitize($value, $key, &$sanitized)
{
// context to consider
// General strings
// numbers
// emails

 if (is_string($value))
 {
 // string based values
    $sanitized[$key] = sanitize_html_string($value);
 }
 elseif (is_int($value))
 {
    $sanitized[$key] = (int)($value);
 }
 elseif (is_float($value))
 {
    $sanitized[$key] = (float)($value);
 } 
 elseif ($key == "email")
 {
    $sanitized[$key] = sanitize_html_string($value);
 }
 else
 {
 // everything else
    $sanitized[$key] = sanitize_html_string($value);
 }
print_r($sanitized);
}

//Additonal interface functions if one off variables need to be sanitized.
//DMASanString
function DMASanSQL($query)
{
  if (is_string($query))
  {
    $sanQuery = mysql_real_escape_string($query);
  }
  return $sanQuery;
}
//DMASanHTML
//DMASanInt
//DMASanFloat


function test_print($item2, $key)
{
    echo "$key :: $item2\n";
}

/* Main program 

// -d, --debug -> debug mode
// -t <file>, --test <file> -> test mode <filename>
// dt::

$shortopts = "t::d";
$longopts = array(
      "debug",
      "test::"
   );

$options = getopt($shortopts,$longopts);
var_dump($options);

exit;
*/

if (isset($argv[1]) && $argv[1] == 'test')
{
  print "**********Open test file **********\n";
  if (isset($argv[2]))
  {
    $file = $argv[2];
  }
  else
  {
    $file = 'santest.csv';
  }
  if (file_exists($file))
  {
    $sanitizeTest = array();
    print ">>> Opening $file for testing\n";
    $fhandle = file($file);
    foreach ($fhandle as $line)
    {
      $values = explode(",",$line);
      $sanitizeTest[$values[0]] = $values[1];
    }
  }
  else
  {
    print " >>> File $file does not exist\n";
    exit;
  }
  print "**********Initialize test**********\n";
  array_walk($sanitizeTest,'test_print');
  print "**********Sanitize***********\n";
  print ">>> Sanitize an array of values\n";
  $sanitized = array();
  array_walk($sanitizeTest,'DMAsanitize', &$sanitized);
  //print ">>> Sanitize one off variables\n";
  //DMASanString
  //DMASanSQL
  //DMASanHTML
  //DMASanInt
  //DMASanFloat
  print "**********Final Results**********\n";
  print_r($sanitized);
}
else
{
  fwrite(STDOUT,"Usage: php sanitize test\n");
}
?>
