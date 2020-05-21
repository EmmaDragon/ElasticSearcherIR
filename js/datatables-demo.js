// Call the dataTables jQuery plugin
$(document).ready(function() {
    var data=[];
    result = JSON.parse(sessionStorage.getItem("result"));
    for(var i=0;i<result.length;i++)
    {
      var book = result[i];
      var obj=
      {
           "#": (i+1).toString(),
           "Id": book.id,
           "Naziv" : book.title,
           "Datum Modifikacije" :book.dateModified,
		   "Datum Upload-a" :book.dateUploaded,
           "Rank":book.rank, 
           "Veličina": book.size,
           "Preuzmi Fajl" : "<button class='btn btn-primary' name='download' id="+book.id+">Download</button>"
           
      };
      data.push(obj);
    }
    

     $('#dataTable').DataTable(
      {
       
        "lengthMenu": [
          [ 5, 10, 25, 50, -1 ],
          [ '5 rows', '10 rows', '25 rows', '50 rows', 'Show all' ]
          ],
        "pageLength": -1,
        "ordering":false,
        "columns":[
            {"data":"#"},
            {"data":"Id"},
            {"data":"Naziv"},
            {"data":"Datum Modifikacije"},
			{"data":"Datum Upload-a"},
            {"data":"Rank"},
            {"data":"Veličina"},
            {"data":"Preuzmi Fajl"}
 
        ],
        "data": data
      });
      addEvents();
      $('#dataTable').DataTable().page.len(5).draw();
      
});

