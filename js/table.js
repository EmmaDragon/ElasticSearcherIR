const table = document.getElementById("resultOfSearch");
const body = document.getElementById("myBody");
let result = [];

//body.onload = (ev) => editTable();

function editTable()
{
    result = JSON.parse(sessionStorage.getItem("result"));
    
    var innerHTML="";
    for(var i=0;i<result.length;i++)
    {
            var book = result[i];
            innerHTML += "<tr><th scope='row'>"+(i+1)+"</th>";
            innerHTML +="<td>"+book.id+"</td>";
            innerHTML +="<td>"+book.title+"</td>";
            innerHTML +="<td>"+book.dateModified+"</td>";
            innerHTML +="<td><button class='btn login_btn' name='download' id="+book.id+">Download</button></td>";
            innerHTML +="<td>"+book.rank+"</td>";
            innerHTML +="</tr>";
         
             
    }
 
    table.innerHTML = innerHTML;
    addEvents();
  
}
function addEvents()
{
  let buttons=document.querySelectorAll("button[name='download']");
  buttons.forEach(d => 
  {
      d.onclick=(ev)=>downloadFile(d.id);
  });
}
function downloadFile(x)
{
    
    const formData = new FormData();
    formData.append("query",x);
    formData.append("typeOfSearch","_id");
    formData.append("downloadFile","yes");
    const fetchData =
    {
        method:"POST",
        body: formData
    }
    fetch("../php/index.php",fetchData)
        .then(response =>
        {
            if(!response.ok)
                throw new Error(response.statusText);
            else
                return response.json();

        }).then((result) => saveFile(result))
    
        .catch(error => console.log(error));
   
}
function saveFile(res)
{
   blob = new Blob([res.content], {type: "text/plain;charset=utf-8"});
   saveAs(blob, res.title);
   $("#myModal").modal('show');

}