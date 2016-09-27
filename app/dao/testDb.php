<?php
$sql = mysql_connect("localhost", "root", "!root");
mysql_select_db("q_db", $sql);
mysql_query('SET NAMES utf8', $sql ); // ←これ

$query = "SELECT * FROM T_QUESTION_HEAD";
$result = mysql_query($query);

header('Content-type: text/plain; charset=UTF-8'); 

while ($row = mysql_fetch_array($result)) {
	 echo $row["Q_TITLE"] . "\n";
}
?>
