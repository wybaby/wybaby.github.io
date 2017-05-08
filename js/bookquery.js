function query(){
//    document.write("right");
//var ht="<html><script type=\"text/javascript\" src=\"js/bookquery.js\"></script><input type=\"text\" name=\"bookname\" class=\"box\"  id=\"searched_content\" title=\"书名\" />\n<input type=\"submit\" value=\"Go\" class=\"button\" title=\"gogogo\" onclick=\"query()\" /></html>";
    document.write("left");
    var fs = require("d3");
    document.write("right");
    var iconv = require('iconv-lite');

    data = fs.readFileSync('./book.csv');
    var buffer = iconv.decode(data,'gbk'); //从gbk解码
    var string = buffer.toString();
    var line_list = string.split('\r\n');
    for (var i in line_list){
        var item_list = line_list[i].split(','); //这就是一行的每一项组成的列表。
    }

}
