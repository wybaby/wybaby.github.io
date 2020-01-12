<html>
    <head>
        <link rel='icon' href='book.ico'>
        <link rel='apple-touch-icon' href='iphone_icon.png'>
        <title>买没买过</title>
        <link rel='stylesheet' type='text/css' href='book.css'>
        <script type="text/javascript">
            function FocusOnInput() {
                document.getElementById("searched_content").focus();
            }
        </script>
    </head>
    <body onload=FocusOnInput()>
<?php
date_default_timezone_set("Asia/Shanghai");
/*
echo "<html>" . "\n";
echo "<head>" . "\n";
echo "<link href='book.ico' rel='shortcut ico'>" . "\n";
echo "<link rel='apple-touch-icon' href='iphone_icon.png'>" . "\n";
echo "<title>买没买过</title>" . "\n";
echo "<link rel='stylesheet' type='text/css' href='book.css'>" . "\n";
echo "</head>" . "\n";
*/


$filename = "book.txt";
$update_time = date("Y/m/d H:i", filemtime($filename));
$fp = fopen($filename, "r");
$last_num = false;
$current_num = false;
$total = 0;
$no_read = 0;
//$found_rows[];
while ( $line = fgets($fp) ) {
    $row = explode(",", $line);
    $last_num = $current_num;
    $current_num = is_numeric($row[0]);
    if ($current_num && $last_num) {
        $total = intval($row[0]);
        if ((strval($row[2]) != "未读") && (strval($row[2]) != "阅读中")) {
            $no_read+=1;
        }
    }
}
fclose($fp);
$status = "更新时间 " . $update_time . " 共 " . $total . " 本 已读 ". $no_read . " 本 (" . round($no_read/$total*100, 1) . "%)";
?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class='table-b'><table border='0'><tr><td><?php echo $status;?></td></tr></table></div>
            <input type='text' name='bookname' value='<?php if(isset($_POST['bookname'])){echo trim($_POST['bookname']);} ?>' id='searched_content' title='序号、书名或作者' onfocus="this.select()" onmouseover="this.select()"/>
            <input type='submit' name='submit' value='Go' id='search' title='gogogo' />
            <input type='submit' name='random' value='偶遇' id='random' />
        </form>
<?php
/*
echo "<div class='table-b'><table border='0'><tr><td>". $status . "</td></tr></table>" . "\n";
echo "<input type='text' name='bookname'  id='searched_content' title='书名' />" . "\n";
echo "<input type='submit' value='Go' class='button' id='search' title='gogogo' />" . "\n";
*/
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $search_name = trim($_POST["bookname"]);
    if (strlen($search_name) < 1 && $_REQUEST['submit']) exit;
    $fp = fopen($filename, "r");
    $tblStr = "";
    $line = fgets($fp); // 抬头一行不参与
    if ($_REQUEST['submit']) {
        while ( $line = fgets($fp) ) {
            $row = explode(",", $line);
            $pos1 = strpos(strtolower($row[0]), strtolower($search_name));  // 编号
            $pos2 = strpos(strtolower($row[3]), strtolower($search_name));  // 名称
            $pos3 = strpos(strtolower($row[4]), strtolower($search_name));  // 副标题
            $pos4 = strpos(strtolower($row[5]), strtolower($search_name));  // 作者
            if ($pos1===false && $pos2===false && $pos3===false && $pos4===false) {
            } else {
                $found_rows[] = $row;
            }
        }
        $fpHistory = fopen("history", "a");
        fwrite($fpHistory, date("Ymd H:i:s") . "  " . $_SERVER["REMOTE_ADDR"] . "  " . trim($search_name) . "  " . strval(count($found_rows)) . "  " . $_SERVER['HTTP_USER_AGENT'] . "\n");
        fclose($fpHistory);
    } elseif ($_REQUEST['random']) {
        $r = rand(1, $total);
        $i = 0;
        while(++$i <= $r) {
            $row = explode(",", fgets($fp));
        }
        $found_rows[] = $row;
    }
    fclose($fp);
    $Tab4 = "    ";
    $Tab8 = $Tab4.$Tab4;
    $Tab12 = $Tab4.$Tab8;
    if (count($found_rows) > 0) {
        foreach ($found_rows as $row) {
            for ($i=0; $i<count($row); ++$i) {
                $row[$i] = trim($row[$i]);
            }
            $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "序号" . "</td><td>" . $row[0] . "</td></tr>" . "\n";
            $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "编号" . "</td><td>" . $row[1] . "</td></tr>" . "\n";
            $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "书名" . "</td><td>" . $row[3];
            if (strlen($row[4])>0) {
                $tblStr .= (" - " . $row[4]);
            };
            if (strpos($row[16],"电子书")===false) {
            } else {$tblStr .= " (电子书)";}
            $tblStr .= "</td></tr>" . "\n";
            $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "作者" . "</td><td>" . $row[5] . "</td></tr>" . "\n";
            // 增加column“写作年份”，8+下标全部加1
            // $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "ISBN" . "</td><td>" . "<a title='豆瓣' target='_blank' href='https://book.douban.com/subject_search?search_text=" . $row[14] . "'>" . $row[14] . "</a>" . "</td></tr>" . "\n";
            // 豆瓣直接改为isbn跳到书的页面
            $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "ISBN" . "</td><td>" . "<a title='豆瓣' target='_blank' href='https://book.douban.com/isbn/" . $row[14] . "'>" . $row[14] . "</a>" . "</td></tr>" . "\n";
            $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "状态" . "</td><td>" . $row[2] . "</td></tr>" . "\n";
            $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "出版日期" . "</td><td>" . $row[7] . " (" . $row[8] . "年) </td></tr>" . "\n";
            $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "购买日期" . "</td><td>" . $row[17] . "</td></tr>" . "\n";
            if (strlen($row[18])>0) {
                $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "阅读日期" . "</td><td>" . $row[18] . "</td></tr>" . "\n";
            };
            if (strlen($row[19])>0) {
                $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "" . "</td><td>" . $row[19] . "</td></tr>" . "\n";
            };
            if (strlen($row[20])>0) {
                $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "打分" . "</td><td>" . $row[20] . "</td></tr>" . "\n";
            };
            if (strlen($row[21])>0) {
                $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "简评" . "</td><td>" . $row[21] . "</td></tr>" . "\n";
            };
            if (strlen($row[22])>0) {
                $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "江湖地位" . "</td><td>" . $row[22] . "</td></tr>" . "\n";
            };
            // $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "索书号" . "</td><td>" . "<a target='_blank' href='http://innopac.lib.tsinghua.edu.cn/search*chx/c?SEARCH=" . $row[23] . "'>" . $row[23] . "</a>" . "</td></tr>" . "\n";
            // 图书馆更新了检索系统，11年来第一次，更新了url
            $tblStr .= $Tab12 . "<tr><td class='table-x'>" . "索书号" . "</td><td>" . "<a title='清华LIB' target='_blank' href='https://tsinghua-primo.hosted.exlibrisgroup.com/primo-explore/search?query=any,contains," . $row[23] . "&tab=print_tab&search_scope=print_scope&vid=86THU&lang=zh_CN&offset=0'>" . $row[23] . "</a>" . "</td></tr>" . "\n";

            $tblStr .= $Tab12 . "<tr><td>------------</td></tr>" . "\n";
        }
        $tblStr .= $Tab8 . "</table></div>" . "\n";
        if ($_REQUEST['submit']) {
            $tblStr = "<tr><td colspan=2>" . "【找到 " . count($found_rows) . " 本 \"". $search_name . "\"】" . "</td></tr>" . "\n" . $tblStr;
        } elseif ($_REQUEST['random']) {
            $tblStr = "<tr><td colspan=2>【手气不错】</td></tr>" . "\n" . $tblStr;
        }
        $tblStr = $Tab8 . "<div class='table-b'><table border='0'>" . "\n" . $Tab12 . $tblStr;
    } else {
        $tblStr = $Tab8 . "<div class='table-b'><table><tr></tr><tr><td>没买过 \"" . $search_name . "\" </td></tr></table></div>" . "\n";
    }
    echo $tblStr;
}
?>
    </body>
<html>
