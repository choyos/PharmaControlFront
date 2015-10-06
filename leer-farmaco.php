<?
$myfile = fopen("datos-farmaco1.txt", "r") or die("Unable to open file!");
while (($line = fgets($myfile)) !== false) {
  $line = explode(" ", $line);
  $line[1] = str_replace("\n", "", $line[1]);
  $line[1] = str_replace("-", "", $line[1]);
  $sql = "INSERT INTO trastuzumab ('cantidad', 'fecha') VALUES ('".$line[1]."', '".$line[0]."')";
  echo $sql;
  die();
}
fclose($myfile);
?>