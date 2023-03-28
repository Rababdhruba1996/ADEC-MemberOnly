function openPopupView() {
    var xmlhttp = new XMLHttpRequest();
    var popupViewLink = 'http://153.127.59.35/同意/';
    
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var popupViewName = 'Popup View';
        var width = 600;
        var height = 400;
        var left = (screen.width/2)-(width/2);
        var top = (screen.height/2)-(height/2);
        var popupViewContent = this.responseText;
        
        var popupViewWindow = window.open('', popupViewName, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+width+', height='+height+', top='+top+', left='+left);
        popupViewWindow.document.body.innerHTML = popupViewContent;
      }
    };
    
    xmlhttp.open("GET", popupViewLink, true);
    xmlhttp.send();
  }
  