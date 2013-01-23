function bbcode(v)
 {
 if (document.selection)
   {
    var str = document.selection.createRange().text;
    document.forms['editcontent'].elements['text'].focus();
    var sel = document.selection.createRange();
    sel.text = "[" + v + "]" + str + "[/" + v + "]";
    return;
   }
  else if ((typeof document.forms['editcontent'].elements['text'].selectionStart) != 'undefined')
   {
    var txtarea = document.forms['editcontent'].elements['text'];
    var selLength = txtarea.textLength;
    var selStart = txtarea.selectionStart;
    var selEnd = txtarea.selectionEnd;
    var s1 = (txtarea.value).substring(0,selStart);
    var s2 = (txtarea.value).substring(selStart, selEnd)
    var s3 = (txtarea.value).substring(selEnd, selLength);
    txtarea.value = s1 + '[' + v + ']' + s2 + '[/' + v + ']' + s3;
    txtarea.selectionStart = s1.length;
    txtarea.selectionEnd = s1.length + 5 + s2.length + v.length * 2;
    return;
   }
  else input('[' + v + '][/' + v + '] ');
 }

function input(what)
 {
  if (document.forms['editcontent'].elements['text'].createTextRange)
   {
    document.forms['editcontent'].elements['text'].focus();
    document.selection.createRange().duplicate().text = what;
   }
  else if ((typeof document.forms['editcontent'].elements['text'].selectionStart) != 'undefined')
   {
    var tarea = document.forms['editcontent'].elements['text'];
    var selEnd = tarea.selectionEnd;
    var txtLen = tarea.value.length;
    var txtbefore = tarea.value.substring(0,selEnd);
    var txtafter =  tarea.value.substring(selEnd, txtLen);
    tarea.value = txtbefore + what + txtafter;
    tarea.selectionStart = txtbefore.length + what.length;
    tarea.selectionEnd = txtbefore.length + what.length;
   }
  else
   {
    document.forms['editcontent'].elements['text'].value += what;
   }
 }

function insert_link()
 {
 if (document.selection)
   {
    var str = document.selection.createRange().text;
    document.forms['editcontent'].elements['text'].focus();
    var sel = document.selection.createRange();
    sel.text = "[link=" + str + "]Link[/link]";
    return;
   }
  else if ((typeof document.forms['editcontent'].elements['text'].selectionStart) != 'undefined')
   {
    var txtarea = document.forms['editcontent'].elements['text'];
    var selLength = txtarea.textLength;
    var selStart = txtarea.selectionStart;
    var selEnd = txtarea.selectionEnd;
    var s1 = (txtarea.value).substring(0,selStart);
    var s2 = (txtarea.value).substring(selStart, selEnd)
    var s3 = (txtarea.value).substring(selEnd, selLength);
    txtarea.value = s1 + '[link=' + s2 + ']Link[/link]' + s3;
    txtarea.selectionStart = s1.length;
    txtarea.selectionEnd = s1.length + 18 + s2.length;
    return;
   }
  else input('[link=]Link[/link] ');
 }
 
 function insert_mail()
 {
 if (document.selection)
   {
    var str = document.selection.createRange().text;
    document.forms['editcontent'].elements['text'].focus();
    var sel = document.selection.createRange();
    sel.text = "[email=" + str + "]Text[/email]";
    return;
   }
  else if ((typeof document.forms['editcontent'].elements['text'].selectionStart) != 'undefined')
   {
    var txtarea = document.forms['editcontent'].elements['text'];
    var selLength = txtarea.textLength;
    var selStart = txtarea.selectionStart;
    var selEnd = txtarea.selectionEnd;
    var s1 = (txtarea.value).substring(0,selStart);
    var s2 = (txtarea.value).substring(selStart, selEnd)
    var s3 = (txtarea.value).substring(selEnd, selLength);
    txtarea.value = s1 + '[email=' + s2 + ']Text[/email]' + s3;
    txtarea.selectionStart = s1.length;
    txtarea.selectionEnd = s1.length + 18 + s2.length;
    return;
   }
  else input('[email=]Text[/email] ');
 }

function clear()
 {
  document.forms['editcontent'].elements['text'].value = "";
 }