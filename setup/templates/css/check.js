window.onload=function sf_EnableButtons() {
    var element = document.getElementById("submit");
        if (element.type == "button" || element.type == "submit") { 
            element.disabled=false; 
}
}