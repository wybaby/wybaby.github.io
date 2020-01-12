<?php
$a = "Codex Seraphinianus";
$pos = (strpos(strtolower($a), strtolower("codex")));
var_dump($pos);
if ($pos === false) {
    echo "false";
}
var_dump(strtolower($a));
?>
