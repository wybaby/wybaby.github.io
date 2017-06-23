var res = {};

function loading() {
Papa.parse("https://wybaby.github.io/book/book.txt", {
	download: true,
	complete: function(results) {
		//console.log(results);
		var $tblSts = "<table border='0'>";
		var $no = 0;
		var $update = "";
		for (var i=0; i<results.data.length; ++i) {
			var b = results.data[i];
			if (b[0].length>0) {
				$no = b[0];
			};
			if (b[0].length==0 && (typeof(b[1])!="undefined") && b[1].indexOf("2017")>=0) {
				$update = b[1];
			};
		}
		$tblSts += "<tr><td>更新日期 " + $update + "</td><td></td><td>" + "共 " + $no + " 本" + "</td></tr>";
		$tblSts += "</table>";
		$("#stsTbl").html($tblSts);
		res = results;
	}
});

}

function bquery() {
    var $searchname = trimStr(document.getElementById('searched_content').value);
    if ($searchname.length<=0) {
        return;
    }
/*
Papa.parse("https://wybaby.github.io/book/book.txt", {
	download: true,
	complete: function(results) {
		*/
		//console.log(res);
        var $found = 0;
        var $tblStr = "";//<table border='0'>";
        for (var i=0; i<res.data.length; ++i) {
            var b = res.data[i];
            if (b.length<20) {
                continue;
            }
//            console.log(b[3]);
            if (b[3].indexOf($searchname)>=0) {
                $found++;
				$tblStr += "<tr><td class='table-x'>" + "序号" + "</td><td>" + b[0] + "</td></tr>";
                $tblStr += "<tr><td class='table-x'>" + "编号" + "</td><td>" + b[1] + "</td></tr>";
                $tblStr += "<tr><td class='table-x'>" + "书名" + "</td><td>" + b[3] 				
				if (b[4].length>0) {
					$tblStr += (" - " + b[4]);
				};
				$tblStr += "</td></tr>";
                $tblStr += "<tr><td class='table-x'>" + "作者" + "</td><td>" + b[5] + "</td></tr>";
                $tblStr += "<tr><td class='table-x'>" + "ISBN" + "</td><td>" + "<a target='_blank' href='https://book.douban.com/subject_search?search_text=" +b[13] + "'>" + b[13] + "</a>" + "</td></tr>";
                $tblStr += "<tr><td class='table-x'>" + "状态" + "</td><td>" + b[2] + "</td></tr>";
                $tblStr += "<tr><td class='table-x'>" + "出版日期" + "</td><td>" + b[7] + "</td></tr>";
                $tblStr += "<tr><td class='table-x'>" + "购买日期" + "</td><td>" + b[16] + "</td></tr>";
		if (b[17].length>0) {
			$tblStr += "<tr><td class='table-x'>" + "阅读日期" + "</td><td>" + b[17] + "</td></tr>";
		};
		if (b[18].length>0) {
			$tblStr += "<tr><td class='table-x'>" + "" + "</td><td>" + b[18] + "</td></tr>";
		};
		if (b[19].length>0) {
			$tblStr += "<tr><td class='table-x'>" + "打分" + "</td><td>" + b[19] + "</td></tr>";
		};
		if (b[20].length>0) {
			$tblStr += "<tr><td class='table-x'>" + "简评" + "</td><td>" + b[20] + "</td></tr>";
		};
		if (b[21].length>0) {
			$tblStr += "<tr><td class='table-x'>" + "江湖地位" + "</td><td>" + b[21] + "</td></tr>";
		};
                $tblStr += "<tr><td>----------</td></tr>";
            }
        }
		$tblStr += "</table>";
        if ($found==0) {
            $("#resTbl").html("<table><tr></tr><tr><td>没买过</td></tr></table>");
        } else {
			$tblStr = "<table border='0'>" + "<tr><td>" + "【找到 " + $found + " 本】" + "</td></tr>" + $tblStr;
            $("#resTbl").html($tblStr);
        }
//	}
//});

}

function trimStr(str){return str.replace(/(^\s*)|(\s*$)/g,"");}
