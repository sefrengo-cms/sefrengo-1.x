// Startup variables

//Einzelnen Toolbaricons farblich formatieren
//Arrayindex: 0=Fett,2=Kursiv, 4=Unterstrichen, 6=Linksbuendig, 8=Zentriert, 10=Rechtsbuendig, 12=Blocksatz
function colored_dbltag()
{
	this.tagnr = new Array();
	this.tagnr[0] = 0;
	this.tagnr[2] = 0;
	this.tagnr[4] = 0;
	this.tagnr[6] = 0;
	this.tagnr[8] = 0;
	this.tagnr[10] = 0;
	this.tagnr[12] = 0;
	this.tagnr[14] = 0;

	return this;
}

function dbltag_val()
{
	this.tagnr = new Array();
	this.tagnr[0] = '<B>';
	this.tagnr[1] = '</B>';
	this.tagnr[2] = '<I>';
	this.tagnr[3] = '</I>';
	this.tagnr[4] = '<U>';
	this.tagnr[5] = '</U>';
	this.tagnr[6] = '<DIV ALIGN = "LEFT">';
	this.tagnr[7] = '</DIV>';
	this.tagnr[8] = '<DIV ALIGN = "CENTER">';
	this.tagnr[9] = '</DIV>';
	this.tagnr[10] = '<DIV ALIGN = "RIGHT">';
	this.tagnr[11] = '</DIV>';
	this.tagnr[12] = '<DIV ALIGN = "JUSTIFY">';
	this.tagnr[13] = '</DIV>';
	this.tagnr[14] = '<BLOCKQUOTE dir=ltr style="MARGIN-RIGHT: 0px">';
	this.tagnr[15] = '</BLOCKQUOTE>';

	return this;
}

bgColor_dbltag = new Array();
val_dbltag = new dbltag_val;
theSelection = false;

search_string_count   = 0;
search_string_temp   = "";


/*
//-------------------------------------------------------
//INSERT IT INTO THE TEXTFIELDS FUNCTIONS
//-------------------------------------------------------
*/

function doubletag(dbltag_number, unique_nr)
{
	if(error_panic_bol){
		error_panic();
		return;
	}


	donotinsert = false;
	theSelection = false;

	// Alle geoeffneten Tags schliessen
	if (dbltag_number == -1) {
		all_tags_content = "";
		for(i=0; i < val_dbltag.tagnr.length;i = i+2)
		{
			if(bgColor_dbltag[unique_nr].tagnr[i]  == "1"){
				bgColor_dbltag[unique_nr].tagnr[i] = "0";
				document.getElementById("number"+ i + "-" + unique_nr).style.borderColor = "threedface";
				all_tags_content += val_dbltag.tagnr[i + 1];
			}
		}
		singletag(all_tags_content, unique_nr);
		document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].focus();
		return;
	}

	if ((browser.clientVer >= 4) && browser.is_ie && browser.is_win)
		theSelection = document.selection.createRange().text; // Get text selection

	// Tags um die Auswahl herum anordnen
	if (theSelection) {
		undo_store(unique_nr);
		document.selection.createRange().text = val_dbltag.tagnr[dbltag_number] + theSelection + val_dbltag.tagnr[dbltag_number+1];
		document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].focus();
		theSelection = '';
		return;
	}


	//Falls Tag bereits geoffnet schliessen
	if (bgColor_dbltag[unique_nr].tagnr[dbltag_number] == "1") {
		donotinsert = true;
	}

	//Offenen Tag schliessen
	if (donotinsert) {
		singletag(val_dbltag.tagnr[dbltag_number+1], unique_nr);
		bgColor_dbltag[unique_nr].tagnr[dbltag_number] = 0;
		document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].focus();
		return;
	}
	else {
		// Tag oeffnen
		singletag(val_dbltag.tagnr[dbltag_number], unique_nr);
		bgColor_dbltag[unique_nr].tagnr[dbltag_number] = 1;
		document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].focus();
		return;
	}
	//storeCaret(document.gp_editor.gp_content);



}


function singletag(text, unique_nr)
{
	if(error_panic_bol){
		error_panic();
		return;
	}

	if(text != ""){
		if (document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].createTextRange && document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].caretPos) {
			undo_store(unique_nr);
			var caretPos = document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].caretPos;
			caretPos.text = text ;
    		caretPos.select();
			document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].focus();
		} else
		{
			document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].value  += text;
			document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].focus();

		}
	}
}


function singletag_without_focus(text, unique_nr)
{
	if(error_panic_bol){
		error_panic();
		return;
	}

	if(text != ""){
		if (document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].createTextRange && document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].caretPos) {
			undo_store(unique_nr);
			var caretPos = document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].caretPos;
			caretPos.text =  text ;
		} else
		{
			document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].value  += text;
		}
	}
}


function font_choser(before, color, behind, unique_nr)
{
	if(error_panic_bol){
		error_panic();
		return;
	}

	if(color != "NULL"){
			theSelection = false;

		if(color != ""){
			pre = before + '"' + color +'">';
			post = behind;
		}
		else{
			pre = before;
			post = behind;
		}


		if ((browser.clientVer >= 4) && browser.is_ie && browser.is_win){
			theSelection = document.selection.createRange().text; // Get text selection
		}
		// Tags um die Auswahl herum anordnen
		if (theSelection) {
			undo_store(unique_nr);
			document.selection.createRange().text = pre + theSelection + post;
			theSelection = '';
			return;
		}
		else{
		singletag(pre + post, unique_nr);
		}
		//storeCaret(document.gp_editor.gp_content);
	}
}



/*
//-------------------------------------------------------
//PRINT/ PREVIEW FUNCTIONS
//-------------------------------------------------------
*/

function print(unique_nr) {
	window.focus();
	if (document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].value != ""){
		if(conf[unique_nr].print_as == 'source'){
			pr_txt = document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].value.replace(/&/gi, "&amp;");
			pr_txt = pr_txt.replace(/</gi, "&lt;");
			pr_txt = pr_txt.replace(/>/gi, "&gt;");
			if(conf[unique_nr].print_nl2br == '1'){
				pr_txt = pr_txt.replace(/\n/gi, "<br>");
			}
		}
		else{
			pr_txt = document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].value;
			if(conf[unique_nr].print_nl2br == '1'){
				pr_txt = pr_txt.replace(/\n/gi, "<br>");
			}
		}

		pr = window.open("", "Print");
		pr.document.open();
		pr.document.write("<html>\n<head></head>\n\n<body onLoad=\"self.print();\">\n");
		pr.document.write(pr_txt);
		pr.document.write("\n</body>\n</html>");
		pr.document.close();
	}
	else{
		alert('<!--{lan_js_empty_textfield}-->');
	}
}

function preview(unique_nr)
{
	window.focus();
	if (document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].value != "") {
		if(conf[unique_nr].preview_as == 'source'){
			pre_txt = document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].value.replace(/&/gi, "&amp;");
			pre_txt = pre_txt.replace(/</gi, "&lt;");
			pre_txt = pre_txt.replace(/>/gi, "&gt;");

			if(conf[unique_nr].preview_nl2br == '1'){
				pre_txt = pre_txt.replace(/\n/gi, "<br>");
			}
			pre_txt = pre_txt.replace(/\n/gi, "<br>");
		}
		else{
			pre_txt = document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].value;
			if(conf[unique_nr].preview_nl2br == '1'){
				pre_txt = pre_txt.replace(/\n/gi, "<br>");
			}
		}
		prev = window.open("", "Vorschau");
		prev.document.open();
		prev.document.write(pre_txt);
		prev.document.close();
	}
	else {
		alert('<!--{lan_js_empty_textfield}-->');
	}
}


/*
//-------------------------------------------------------
//SEARCH /REPLACE FUNCTIONS
//-------------------------------------------------------
*/

function reset_search_string_count()
{
	search_string_count   = 0;
}

function check_search_string_temp(str)
{
	if(search_string_temp != str){
		search_string_count   = 0;
		search_string_temp = str;
	}
}



function search(str, unique_nr)
{
	txt = document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].createTextRange();
	for (i = 0; i <= search_string_count && (found = txt.findText(str)) != false; i++) {
		txt.moveStart("character", 1);
		txt.moveEnd("textedit");
	}
	if (found) {
		txt.moveStart("character", -1);
		txt.findText(str);
		txt.select();
		txt.scrollIntoView();
		search_string_count++;
	}
	else {
		if (search_string_count > 0) {
			search_string_count = 0;
		}
		alert('"'+ str + '" <!--{lan_js_not_found}-->');
	}
}



function search_and_replace(flag, search_str, replace_str, unique_nr)
{
	txt = document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].createTextRange();

	if(flag == "search"){
		txt = document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].createTextRange();
		for (i = 0; i <= search_string_count && (found = txt.findText(search_str)) != false; i++)
		{
			txt.moveStart("character", 1);
			txt.moveEnd("textedit");
		}
		if (found) {
		txt.moveStart("character", -1);
			txt.findText(search_str);
			txt.select();
			txt.scrollIntoView();
			search_string_count++;
		}
		else {
			if (search_string_count > 0) {
				search_string_count = 0;
			}
			alert('"'+ search_str + '" <!--{lan_js_not_found}-->');
		}
	}

	if(flag == "replace"){
		if(search_string_count != "0"){
			one_down = search_string_count -1;
		}
		else{
			one_down = search_string_count;
		}

		for (i = 0; i <= one_down && (found = txt.findText(search_str)) != false; i++)
		{
			txt.moveStart("character", 1);
			txt.moveEnd("textedit");
		}

		if (found) {
			txt.moveStart("character", -1);
			txt.findText(search_str);
			txt.select();
			txt.scrollIntoView();
			singletag_without_focus(replace_str, unique_nr);

			txt.moveStart("character", 1);
			txt.moveEnd("textedit");

			txt.moveStart("character", -1);
			txt.findText(replace_str);
			txt.select();
			txt.scrollIntoView();

			if(search_string_count != "0"){
				search_string_count = search_string_count -1;
			}


		}
		else {
			if (one_down > 0) {
				search_string_count = 0;
			}
		alert('"'+ search_str + '" <!--{lan_js_not_found}-->');
		}

	}

	if(flag == "replace_all"){
		search_string_count = 0;
		while(txt.findText(search_str))
		{
			txt.moveStart("character", 1);
			txt.moveEnd("textedit");

			txt.moveStart("character", -1);
			txt.findText(search_str);
			txt.select();
			txt.scrollIntoView();
			singletag_without_focus(replace_str, unique_nr);
			search_string_count++;

		}


		alert('<!--{lan_js_replace_1}--> "'+ search_string_count + '" <!--{lan_js_replace_2}-->');
		search_string_count = 0;

	}
}



/*
//-------------------------------------------------------
//UNDO/ REDO FUNCTIONS
//-------------------------------------------------------
*/


function undo_date(unique_nr)
{
	undo_date_now = new Date();
	undo_timer_new[unique_nr] = undo_date_now.getTime();
	if(undo_timer_old[unique_nr] != ''){
		undo_timer_diff[unique_nr] = undo_timer_new[unique_nr] - undo_timer_old[unique_nr];

	}
	else{
		undo_timer_old[unique_nr] = undo_timer_new[unique_nr];
		return true;
	}
	undo_timer_old[unique_nr] = undo_timer_new[unique_nr];

	if (undo_timer_diff[unique_nr] > 400){
		return true;
	}
	else{
		return false;
	}
}

function undo(unique_nr)
{

	if(undo_container_index[unique_nr] != undo_start[unique_nr]){

		//get sure, that the user click into the textfield, before click another textformat button
		error_panic_bol = true;
		error_panic_nr = '1';

		//für erstes redo
		if(undo_container_index[unique_nr] == undo_max[unique_nr]){
		undo_container[unique_nr][undo_container_index[unique_nr]] = document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].value ;
		}

		if(undo_container_index[unique_nr] == 0){
			undo_container_index[unique_nr] = undo_limit[unique_nr]+1;
		}

		undo_container_index[unique_nr]--;
		document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].value  = undo_container[unique_nr][undo_container_index[unique_nr]];
    }
    window.focus();
}

function redo(unique_nr)
{

	if(undo_container_index[unique_nr] != undo_max[unique_nr]){

		//get sure, that the user click into the textfield, before click another textformat button
		error_panic_bol = true;
		error_panic_nr = '1';

		if(undo_container_index[unique_nr] == undo_limit[unique_nr]){
			undo_container_index[unique_nr] = -1;
		}

		if(undo_container_index[unique_nr] != undo_max[unique_nr]){
			undo_container_index[unique_nr]++;
			document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].value  = undo_container[unique_nr][undo_container_index[unique_nr]];
		}
	}
	window.focus();
}

function undo_timer(evt, unique_nr)
{
	if(browser.is_ie){
		key_code = evt.which ? evt.which : evt.keyCode;

		if( ((key_code < 33 || key_code > 40) && (key_code < 112 || key_code > 123)) && key_code != 45 && key_code != 32 && key_code != 13 && key_code != 27)
		{
			//alert(key_code);
			if(undo_date(unique_nr)){

				undo_store(unique_nr);
			}
		}
	}
}


function undo_store(unique_nr)
{
	if (! browser.is_ie) return;

	insert_if_undo_start = true;
	undo_container[unique_nr][undo_container_index[unique_nr]] = document.forms[conf[unique_nr].form_name].elements[conf[unique_nr].textfield_name].value ;

	if(undo_container_index[unique_nr] == undo_limit[unique_nr]){
		undo_container_index[unique_nr] = 0;
		undo_start[unique_nr] = 1;
		undo_loop[unique_nr] = true;
		insert_if_undo_start = false;
	}
	else{
		undo_container_index[unique_nr] = undo_container_index[unique_nr] + 1;
	}


	if(undo_loop[unique_nr] && insert_if_undo_start){
		if(undo_start[unique_nr] >= undo_limit[unique_nr]){
			undo_start[unique_nr] = 0;
		}
		else{
			undo_start[unique_nr] = undo_container_index[unique_nr] + 1;
			if(undo_start[unique_nr] >= undo_limit[unique_nr]){
				undo_start[unique_nr] = 0;
			}
		}
	}

	undo_max[unique_nr] = undo_container_index[unique_nr];
}


/*
//-------------------------------------------------------
//HANDLE ERRORS FUNCTIONS
//-------------------------------------------------------
*/

error_panic_bol = false;
var error_panic_nr;

function error_panic()
{
	if(error_panic_bol){
		switch(error_panic_nr)
		{
			case "1":
				alert("<!--{lan_js_click_textfield_after_undo}-->");
				break;

		}
	}
}




/*
//-------------------------------------------------------
//MISC FUNCTIONS
//-------------------------------------------------------
*/

function popup(url, name, widthX, heightY, resz)
{
	if(error_panic_bol){
		error_panic();
		return;
	}


	posX = (screen.width) ? (screen.width-widthX)/2 : 0;
	posY = (screen.height) ? (screen.height-heightY)/2 : 0;

	popthis = window.open(url,name,"width="+ widthX +",height="+ heightY +",left="+ posX +",top="+ posY +",resizable="+ resz);
}


function storeCaret(textEl)
{
	if (textEl.createTextRange){
		textEl.caretPos = document.selection.createRange().duplicate();
	}

	error_panic_bol = false;
}


function save_file()
{
	document.gp_editor.gp_editor_action.value = "save_file_js";
  	document.gp_editor.submit();
}

/*
//-------------------------------------------------------
//TOOLBAR HOOVER FUNCTIONS
//-------------------------------------------------------
*/

function button_over(eButton)
{
	eButton.style.backgroundColor = "#B5BDD6";
	eButton.style.borderColor = "darkblue darkblue darkblue darkblue";
}

function button_out(eButton)
{
	eButton.style.backgroundColor = "#EFEFEF";
	eButton.style.borderColor = "threedface";
}

function button_down(eButton)
{
	eButton.style.backgroundColor = "#8494B5";
	eButton.style.borderColor = "darkblue darkblue darkblue darkblue";
}

function button_up(eButton)
{
	eButton.style.backgroundColor = "#B5BDD6";
	eButton.style.borderColor = "darkblue darkblue darkblue darkblue";
	eButton = null;
}


function button_over2(eButton, tagId)
{
	eButton.style.backgroundColor = "#B5BDD6";
	eButton.style.borderColor = "darkblue darkblue darkblue darkblue";
}

function button_out2(eButton, tagId, unique_nr)
{
	eButton.style.backgroundColor = "#EFEFEF";
	if(bgColor_dbltag[unique_nr].tagnr[tagId] == "1"){
		eButton.style.borderColor = "darkblue darkblue darkblue darkblue";
	}
	else{
		eButton.style.borderColor = "threedface";
	}
}

function button_down2(eButton, tagId)
{
	eButton.style.backgroundColor = "#8494B5";
	eButton.style.borderColor = "darkblue darkblue darkblue darkblue";
}

function button_up2(eButton, tagId)
{
	eButton.style.backgroundColor = "#EFEFEF";
	eButton.style.borderColor = "darkblue darkblue darkblue darkblue";
	eButton = null;
}
