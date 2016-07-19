<!-- Anfang side_config.tpl -->
<script type="text/javascript">
<!--
function timemanagement() {
  document.editform.online[2].selected = true;
}
//-->
</script>
<script type="text/javascript" src="tpl/{SKIN}/js/dynCalendarBrowserSniffer.js"></script>
<script type="text/javascript" src="tpl/{SKIN}/js/dynCalendar.js"></script>
<script type="text/javascript">

//Callback for Calendar start date
function callback_startdate(date, month, year)
{
	if (String(month).length == 1) {
		month = '0' + month;
	}

	if (String(date).length == 1) {
		date = '0' + date;
	}
	document.editform.startdate.value = date + '.' + month + '.' + year;
	document.editform.online[2].checked=true;
}

//Callback for Calendar stop date
function callback_enddate(date, month, year)
{
	if (String(month).length == 1) {
		month = '0' + month;
	}

	if (String(date).length == 1) {
		date = '0' + date;
	}
	document.editform.enddate.value = date + '.' + month + '.' + year;
	document.editform.online[2].checked=true;
}
</script>

<div id="main" class="siteconf">
<h5>{AREA_TITLE}</h5>
<!-- BEGIN ERROR_BLOCK -->
<p class="errormsg">{ERR_MSG}</p><!-- END ERROR_BLOCK -->
<form name="editform" id="editcontent" method="post" action="{FORM_ACTION}">
<input type="hidden" name="action" value="save" />
<input type="hidden" name="idtplconf" value="{IDTPLCONF}" />
<input type="hidden" name="lastmodified" value="{LASTMODIFIED}" />
<input type="hidden" name="author" value="{AUTHOR}" />
<input type="hidden" name="created" value="{CREATED}" />
<input type="hidden" name="idcatside" value="{IDCATSIDE}" />


<table class="config" cellspacing="1">
  <tr>
    <td class="head">
      <p>{CON_SIDECONFIG}</p>
    </td>
    <!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->
    <td class="nopadd">
    <!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->
        <table class="sitehack" cellspacing="1">
            <tr>
                <td colspan="2" class="headre">
                <!-- Ueberschrift Titel -->
                {SIDE_TITLE_DESC}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <!-- Eingabe Titel -->
                    <input class="w800" type="text" name="title" id="title" value="{SIDE_TITLE}" size="30" maxlength="255" onfocus="if($('#metasocial_title').val()==this.value){$('#metasocial_title').val('')}" onblur="if($('#metasocial_title').val()==''){$('#metasocial_title').val(this.value)}" />
                    <!-- Eingabe Darf Seite sperren -->
                    {SELECT_LOCK_SIDE}
                </td>
            </tr>
            <!-- BEGIN URL_REWRITE -->
            <tr><td class="headre" colspan="2">Seiten URL</td></tr>
            <tr>
                <td colspan="2">
  				  <input type="checkbox" name="rewrite_use_automatic" value="1" id="rewrite_use_automatic" {REWRITE_USE_AUTOMATIC_CHECKED}  onclick="if(document.editform.rewrite_use_automatic.checked==true){document.editform.rewrite_url.disabled=true;document.editform.rewrite_url.style.backgroundColor='#cccccc'}else{document.editform.rewrite_url.disabled=false;document.editform.rewrite_url.style.backgroundColor='#ffffff'}" />
				    <label for="rewrite_use_automatic">URL automatisch vergeben</label>
                </td>
            </tr>
            <tr>
                <td  colspan="2">
                    <input class="w800" type="text" name="rewrite_url" value="{REWRITE_URL}"  {REWRITE_URL_DISABLED} size="90" maxlength="255" style="background-color:{REWRITE_URL_BACKGROUNDCOLOR};" />{REWRITE_ERROR}
                </td>
            </tr>
            <tr><td  colspan="2" ><small>URL dieser Seite: {REWRITE_CURRENT_URL}</small></td></tr>
<!-- END URL_REWRITE -->

<!-- BEGIN USER_RIGHTS -->
            <tr>
                <td colspan="2">{BACKENDRIGHTS}&nbsp;&nbsp;&nbsp;&nbsp;{FRONTENDRIGHTS}</td>
            </tr>
<!-- END USER_RIGHTS -->  

<!-- BEGIN TIMER_BLOCK -->

            <tr>
                <td class="headre" colspan="2">
                <!-- Ueberschrift Sichtbarkeit -->
                {VISBILITY_DESC}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <!-- Beschreibung online, offline, zeitgesteuert -->
                    {LANG_SIDE_IS}:
                    <!-- Eingabe online, offline, ... -->
                    {VISIBILITY}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <!-- Beschreibung startdatum -->
                    {LANG_ONLINE}:
                    <!-- Eingabe startdatum -->
                    {STARTDATE}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <!-- Beschreibung enddatum -->
                    {LANG_OFFLINE}:
                    <!-- Eingabe enddatum -->
                    {ENDDATE}
                </td>
            </tr>

<!-- END TIMER_BLOCK -->

<!-- BEGIN CLONE_AND_NOTICE -->
          <tr>
            <td colspan="2" class="headre">
              <table>
                <tr>
                  <td class="headre" width="325">
                    <!-- Beschreibung verschieben/ klonen -->
                    {LANG_MOVE_SIDE}:
                  </td>
                  <td class="headre" width="325">
                    <!-- Beschreibung Notizen - interne Beschreibung -->
                    {LANG_NOTICES}:
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <table>
                <tr>
                  <td width="380">
                    <!-- Feld verschieben/ klonen -->
                    {SELECT_SIDEMOVE}
                  </td>
                  <td width="380">
                    <!-- Feld Notizen - interne Beschreibung -->
                    <textarea name="summary" rows="7" cols="30" style="height:145px;width:380px">{SUMMARY}</textarea>
                  </td>
                </tr>
                </table>

<!-- END CLONE_AND_NOTICE -->


<!-- BEGIN NOTICE -->
  <tr>
    <td class="headre" colspan="2">
      <table>
        <tr>
          <td">
            <!-- Beschreibung Notizen -->
            {LANG_NOTICES}:
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <table>
        <tr>
          <td">
            <!-- Feld Notizen - interne Beschreibung -->
            <textarea name="summary" rows="5" cols="30" style="width:638px">{SUMMARY}</textarea>
            {HIDDEN_CLONES}
          </td>
        </tr>
      </table>
    </td>
  </tr>

<!-- END NOTICE -->

</table>
    <!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->
    {BUTTONS_TOP}


    </table>
    <!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->
 
 <!-- BEGIN META -->
<table class="config" id="siteconfhack1" cellspacing="1">
  <tr>
    <td class="head nowrap" rowspan="12" width="110">
      <p>{LANG_CON_METACONFIG}</p>
    </td>
    <td class="headre" colspan="3">
            <!-- Ueberschrift meta Title -->
            {LANG_META_TITLE}
    </td>
  </tr>
  
  <tr>
    <td colspan="3">
            <!-- Feld meta Title -->
            <input class="w800" type="text" name="meta_title" value="{META_TITLE}" />
    </td>
  </tr>
  <tr>
    <td class="headre" colspan="3">
            <!-- Ueberschrift meta description -->
            {LANG_META_DESC}
    </td>
  </tr>
  <tr>
    <td colspan="3">
            <!-- Feld meta description -->
            <textarea class="w800"  onfocus="if($('#metasocial_description').val()==this.value){$('#metasocial_description').val('')}" onblur="if($('#metasocial_description').val()==''){$('#metasocial_description').val(this.value)}" name="meta_description" rows="2" cols="50">{META_DESC}</textarea>
    </td>
  </tr>
  <tr>
    <td class="headre" colspan="3">
            <!-- Ueberschrift meta keywords -->
            {LANG_META_KEYWORDS}
    </td>
  </tr>
  <tr>
    <td colspan="3">
            <!-- Feld meta keywords -->
            <input class="w800" type="text" name="meta_keywords" value="{META_KEYWORDS}" />
    </td>
  </tr>
  <tr>
    <td class="headre" style="padding:0;" colspan="3">
      <table>
        <tr>
          <td class="headre" width="380">
          {LANG_META_AUTHOR}
          </td>
          <td class="headre" style="padding-left:0;" width="380">
          {LANG_META_ROBOTS}
          </td>
        </tr>
      </table>
   </td>
   </tr>
   <tr>
     <td colspan="3">
      <table>
      <tr>
      <td width="380">
          <!-- Feld Author -->
          <input type="text" name="meta_author" style="width:318px" value="{META_AUTHOR}" />
      </td>
      <td width="380">
          <!-- Feld Robots -->
          {META_ROBOTS}
      </td>
      </tr>
      </table>
    </td>
  </tr>
  
  <tr>
    <td class="headre" colspan="3">
            <!-- Ueberschrift meta OTHER -->
            {LANG_META_OTHER}
    </td>
  </tr>
  <tr>
    <td colspan="3">
            <textarea class="w800" id="meta_other"  name="meta_other" rows="5" cols="50">{META_OTHER}</textarea>
    </td>
  </tr>
  
  <tr>
    <td class="headre" colspan="3">
            <!-- Ueberschrift Weiterleitung -->
            {LANG_META_REDIRECT}
    </td>
  </tr>
  <tr>
    <td colspan="3">
            <!-- Feld Weiterleitung -->
            <input type="checkbox" name="meta_redirect" value="1" {META_REDIRECT} />
            <input type="text" name="meta_redirect_url" value="{META_REDIRECT_URL}" size="50" maxlength="255" />
    </td>
  </tr>
  <tr>
    <td class="head nowrap" rowspan="8">
            <!-- Ueberschrift Social -->
            {LANG_META_SOCIAL}
    </td>
    <td class="headre" colspan="3">{LANG_META_SOCIAL_TITLE}</td>
  </tr>
  <tr>
    <td colspan="3"><input type="text" name="metasocial_title" id="metasocial_title" style="width:318px" value="{META_SOCIAL_TITLE}" /></td>
  </tr>
  
  <tr>
    <td class="headre" colspan="3">{LANG_META_SOCIAL_IMAGE}</td>
  </tr>
  <tr>
    <td colspan="3">{META_SOCIAL_IMAGE}</td>
  </tr>
  
  <tr>
    <td class="headre" colspan="3">{LANG_META_SOCIAL_DESCRIPTION}</td>
  </tr>
  <tr>
    <td colspan="3"><input type="text" name="metasocial_description" id="metasocial_description" style="width:318px" value="{META_SOCIAL_DESCRIPTION}" /></td>
  </tr>
  
  <tr>
    <td class="headre" colspan="3">{LANG_META_AUTHOR}</td>
  </tr>
  <tr>
    <td colspan="3"><input type="text" name="metasocial_author" style="width:318px" value="{META_SOCIAL_AUTHOR}" /></td>
  </tr>
  
  
<!-- END META -->


<!-- END RIGHTS -->


<!-- BEGIN HIDDEN_FIELDS -->
{HIDDEN_FIELDS}
<!-- END HIDDEN_FIELDS -->

{TPL_CONF}

<table class="config" id="siteconfhack2" cellspacing="1">
{BUTTONS_BOTTOM}
</table>
</form>
</div> 
<div class="footer">{FOOTER_LICENSE}</div>
</body>
</html>
