var toolbar = "<div class='toolbar'>" +
"<button onclick=\"cmd(event,'undo');\"><i class='fa fa-undo'></i></button>" + 
"<button onclick=\"cmd(event,'redo');\"><i class='fa fa-repeat'></i></button>" + 
"<button onclick=\"cmd(event,'bold');\"><i class='fa fa-bold'></i></button>" + 
"<button onclick=\"cmd(event,'italic');\"><i class='fa fa-italic'></i></button>" + 
"<button onclick=\"cmd(event,'underline');\"><i class='fa fa-underline'></i></button>" + 
"<button onclick=\"cmd(event,'strikeThrough');\"><i class='fa fa-strikethrough'></i></button>" + 
"<button onclick=\"cmd(event,'indent');\"><i class='fa fa-indent'></i></button>" + 
"<button onclick=\"cmd(event,'outdent');\"><i class='fa fa-outdent'></i></button>" + 
"<button onclick=\"cmd(event,'insertUnorderedList');\"><i class='fa fa-list-ul'></i></button>" + 
"<button onclick=\"cmd(event,'insertOrderedList');\"><i class='fa fa-list-ol'></i></button>" + 
"<button onclick=\"cmd(event,'formatBlock','h1');\">H1</button>" + 
"<button onclick=\"cmd(event,'formatBlock','h2');\">H2</button>" + 
"<button onclick=\"cmd(event,'createlink');\"><i class='fa fa-link'></i></button>" + 
"<button onclick=\"cmd(event,'unlink');\"><i class='fa fa-unlink'></i></button>" + 
"<button onclick=\"cmd(event,'insertimage');\"><i class='fa fa-image'></i></button>" + 
"<button onclick=\"cmd(event,'formatBlock','p');\">P</button>" +
"</div>" +
"<div id='editor' style='min-height:200px; overflow:auto; resize:both; border:1px solid silver;padding:10px;margin:5px' contenteditable></div>";

var d1 = document.getElementById(editorId);
d1.insertAdjacentHTML('afterend', toolbar);
d1.form.setAttribute("onsubmit", "addInsets();");
d1.style.display = "none";

var element = document.createElement('input');
element.setAttribute('type', 'file');
element.setAttribute('id', 'fileInput');
element.setAttribute('style', 'display:none');
document.body.appendChild(element);

document.getElementById('editor').innerHTML = $("<div/>").html(d1.innerHTML).text();

function addInsets() {
   d1.innerHTML = document.getElementById('editor').innerHTML;
}

function cmd(e, sCmd, sValue) {
e.preventDefault();
if (sCmd == 'insertimage') {
	$('#fileInput').click();
	var fileInput = document.getElementById('fileInput');
	fileInput.addEventListener('change', function(e) {
		var file = fileInput.files[0];
		var imageType = /image.*/;
		if (file.type.match(imageType)) {
			var reader = new FileReader();
			reader.onload = function(e) {
				var img = new Image();
				img.src = reader.result;
				var sel, range;
			if (window.getSelection && (sel = window.getSelection()).rangeCount) {
					range = sel.getRangeAt(0);
					range.collapse(true);
					range.insertNode(img);
					range.collapse(true);
					sel.removeAllRanges();
					sel.addRange(range);
				}
			}
			reader.readAsDataURL(file);
		} else {
			alert("File not supported!");
		}
	});
} else if (sCmd == 'createlink') {
	var sLnk = prompt('Write the URL here', 'http:\/\/');
	if (sLnk && sLnk != '' && sLnk != 'http://') {
		document.execCommand(sCmd, false, sLnk);
	}
} else {
	document.execCommand(sCmd, false, sValue);
  }
}