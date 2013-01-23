function sf_EnableButtons(check) {
    var element = document.getElementById("submit");
        if (element.type == "button" || element.type == "submit") { 
          if(check==true){
            element.disabled=false; 
          } else {
            element.disabled=true; 
          }
        }
}
