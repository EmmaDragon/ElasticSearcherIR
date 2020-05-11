const queryString = document.getElementById("queryString");
const searchType = document.getElementById("searchByValue");
const btnSearch = document.getElementById("searchDocuments");
const linkIndexFile = document.getElementById("file-input");

btnSearch.onclick = (ev) => singleFiledSearch(ev);

function readAsText(file, done, doneContext){

    var fileReader = new FileReader;
    var c = doneContext || this;
    fileReader.onload = function(){
     done.call(c, fileReader.result);
     
    }

    fileReader.readAsText(file);

}
linkIndexFile.onchange = function(event) {
    
    $("#loadModal").modal("show");
    var nameOfFile=this.files[0].name;
    var size=this.files[0].size;
    size =size/1024;
    size=size.toFixed(2);
    size=size.toString()+"KB";
    var time= new Date(this.files[0].lastModified);
    var new_time=time.toLocaleString('en-GB', { timeZone: 'UTC' })
   
    readAsText(this.files[0], function(res){
    const formData = new FormData();
    formData.append("indexFile",true);
    formData.append("link",nameOfFile);
    formData.append("body",res);
    formData.append("size",size);
    formData.append("time",new_time);
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
   
                }).then((res) => {showResponse(res)})
   
                .catch(error => console.log(error));
           
      
       });

 }


function showResponse(response)
{
   $("#loadModal").modal("hide");
   $("#myModal").modal("show");   
}



function singleFiledSearch(ev)
{
    $("#loadModal").modal('show');
    const formData = new FormData();
    formData.append("query",queryString.value);
    formData.append("typeOfSearch",searchType.value);
    formData.append("SingleFieldSearch",true);
   
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

        }).then((result) => showAsTable(result))
    
        .catch(error => console.log(error));
}


function showAsTable(result)
{
    if (typeof Storage !== "undefined") 
    { 
        sessionStorage.setItem("result", JSON.stringify(result));
    }
    $("#loadModal").modal('hide');
    window.location="table.html";
}
