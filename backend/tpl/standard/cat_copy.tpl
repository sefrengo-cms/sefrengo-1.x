<!-- Anfang side_copy.tpl -->
<div id="main">
    <h5>{AREA_TITLE}</h5>
    <!-- BEGIN ERROR_BLOCK -->
    <p class="errormsg">{ERR_MSG}</p><!-- END ERROR_BLOCK -->

<form name="editform" method="post" action="{FORM_ACTION}">
<input type="hidden" name="action" value="save" />

<table class="config" cellspacing="1">
  <tr>
    <td rowspan="4" class="head">
      <p>{CON_CATCONFIG}</p>
    </td>
    <td class="headre" colspan="3">

            <!-- Ueberschrift Titel -->
            {CAT_TITLE_DESC}

    </td>
  </tr>
  <tr>
    <td colspan="3" >
            <!-- Eingabe Titel -->
            <input type="text" name="title" value="{CAT_TITLE}" size="30" maxlength="255" style="width: 350px;" />
    </td>
  </tr>
  
 <td class="headre" colspan="3">

            <!-- Kopiere nach Titel -->
            {CAT_COPY_TO_DESC}

    </td>
  </tr>
  <tr>
    <td colspan="3">
            <!-- Eingabe Titel -->
            {CAT_SELECT}
    </td>

  <tr>
    <td class="content7" colspan="4" style="text-align:right">
			<input name="sf_safe" type="submit" value="{BUTTON_SUBMIT_VALUE}" title="{BUTTON_SUBMIT_TEXT}" class="sf_buttonAction"/>
			<input name="sf_apply" type="submit" value="{BUTTON_APPLY_VALUE}" title="{BUTTON_APPLY_TEXT}" class="sf_buttonAction"/>
			<input name="sf_cancel" type="button" value="{BUTTON_CANCEL_VALUE}" title="{BUTTON_CANCEL_TEXT}" class="sf_buttonActionCancel" onclick="window.location='{BUTTON_CANCEL_URL}'"/>
	</td>
  </tr>
</table>
</form>
</div>
<div class="footer">{FOOTER_LICENSE}</div>
</body>
</html>
