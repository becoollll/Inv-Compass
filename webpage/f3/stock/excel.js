function myexcel(){
    var html = '<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8" /><title>Excel</title>';
    html += '';
    html += document.getElementById('exportTable').innerHTML + '';
    window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
}


