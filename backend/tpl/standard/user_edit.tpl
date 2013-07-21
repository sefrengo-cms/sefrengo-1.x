<!-- Anfang settings.tpl -->
<div id="main">
    <h5>{AREA}</h5>
    <!-- BEGIN ERROR -->
    <p class="errormsg">{ERRORMESSAGE}</p><!-- END ERROR -->
    <form name="user" method="post" action="{FORM_URL}" autocomplete="off">
    <input type="hidden" name="area" value="user_edit" />
    <input type="hidden" name="action" value="save" />
    <input type="hidden" name="iduser" value="{IDUSER}" />
    <input type="hidden" name="idgroup" value="{IDGROUP}" />
    <input type="hidden" name="order" value="{ORDER}" />
    <input type="hidden" name="ascdesc" value="{ASCDESC}" />
    <input type="hidden" name="page" value="{PAGE}" />
    <input type="hidden" name="searchterm" value="{SEARCHTERM}" />
    <input type="hidden" name="oldusername" value="{FORM_OLDUSERNAME}" />
    <input type="hidden" name="deletable" value="{FORM_DELETABLE}" />
<table class="config" cellspacing="1">
      <tr>
        <td class="headre" colspan=2>Anmelde-Daten des Benutzers</td>
      </tr>
      <tr>
        <td class="head"><p>{LANG_USERNAME}</p></td>
        <td width="100%"><input class="w800" type="text" name="username" value="{FORM_USERNAME}" autocomplete="off"  size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_NEWPASSWORD}</td>
        <td><input class="w800" type="password" name="password" autocomplete="off" value="{FORM_PASSWORD}" maxlength="60" size="90" /></td>
      </tr>
      <tr>
        <td class="head nowrap">{LANG_NEWPASSWORDAGAIN}</td>
        <td><input class="w800" type="password" name="password_validate" autocomplete="off" value="{FORM_PASSWORDVALIDATE}" maxlength="60" size="90" /></td>
      </tr>
      <tr>
        <td class="head" valign="top">{LANG_COMMENT}</td>
        <td><textarea class="w800" name="comment" cols="45" rows="3">{FORM_COMMENT}</textarea></td>
      </tr>
      <tr>
        <td class="head" valign="top">{LANG_GROUP}</td>
        <td><select name="group[]" size="8" multiple="multiple">
<!-- BEGIN SELECT_SHOWLIST -->          <option value="{SHOWLIST_VALUE}"{SHOWLIST_SELECTED}{SHOWLIST_STYLE}>{SHOWLIST_ENTRY}</option>
<!-- END SELECT_SHOWLIST -->        </select></td>
      </tr>
      <tr>
        <td class="headre" colspan=2>Daten zur Person des Benutzers</td>
      </tr>
      <tr>
        <td class="head">{LANG_SALUTATION}</td>
        <td><input class="w800" type="text" name="salutation" value="{FORM_SALUTATION}" size="45" maxlength="32" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_TITLE}</td>
        <td><input class="w800" type="text" name="title" value="{FORM_TITLE}" size="45" maxlength="32" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_NAME}</td>
        <td><input class="w800" type="text" name="name" value="{FORM_NAME}" size="90" maxlength="32" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_SURNAME}</td>
        <td><input class="w800" type="text" name="surname" value="{FORM_SURNAME}" maxlength="32" size="90" /></td>
      </tr>
      <tr>
        <td class="headre" colspan=2>Kontaktdaten des Benutzers</td>
      </tr>
      <tr>
        <td class="head">{LANG_STREET}</td>
        <td><input class="w800" type="text" name="street" value="{FORM_STREET}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_STREET_ALT}</td>
        <td><input class="w800" type="text" name="street_alt" value="{FORM_STREET_ALT}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_ZIP}</td>
        <td><input class="w800" type="text" name="zip" value="{FORM_ZIP}" maxlength="10" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_LOCATION}</td>
        <td><input class="w800" type="text" name="location" value="{FORM_LOCATION}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_STATE}</td>
        <td><input class="w800" type="text" name="state" value="{FORM_STATE}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_COUNTRY}</td>
        <td><input class="w800" type="text" name="country" value="{FORM_COUNTRY}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_EMAIL}</td>
        <td><input class="w800" type="text" name="email" value="{FORM_EMAIL}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_PHONE}</td>
        <td><input class="w800" type="text" name="phone" value="{FORM_PHONE}" maxlength="50" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_FAX}</td>
        <td><input class="w800" type="text" name="fax" value="{FORM_FAX}" maxlength="50" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_MOBILE}</td>
        <td><input class="w800" type="text" name="mobile" value="{FORM_MOBILE}" maxlength="50" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_PAGER}</td>
        <td><input class="w800" type="text" name="pager" value="{FORM_PAGER}" maxlength="50" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_HOMEPAGE}</td>
        <td><input class="w800" type="text" name="homepage" value="{FORM_HOMEPAGE}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="headre" colspan=2>Firmendaten des Benutzers</td>
      </tr>
      <tr>
        <td class="head">{LANG_FIRM}</td>
        <td><input class="w800" type="text" name="firm" value="{FORM_FIRM}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_POSITION}</td>
        <td><input class="w800" type="text" name="position" value="{FORM_POSITION}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_FIRM_STREET}</td>
        <td><input class="w800" type="text" name="firm_street" value="{FORM_FIRM_STREET}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_FIRM_STREET_ALT}</td>
        <td><input class="w800" type="text" name="firm_street_alt" value="{FORM_FIRM_STREET_ALT}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_FIRM_ZIP}</td>
        <td><input class="w800" type="text" name="firm_zip" value="{FORM_FIRM_ZIP}" maxlength="10" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_FIRM_LOCATION}</td>
        <td><input class="w800" type="text" name="firm_location" value="{FORM_FIRM_LOCATION}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_FIRM_STATE}</td>
        <td><input class="w800" type="text" name="firm_state" value="{FORM_FIRM_STATE}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_FIRM_COUNTRY}</td>
        <td><input class="w800" type="text" name="firm_country" value="{FORM_FIRM_COUNTRY}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_FIRM_EMAIL}</td>
        <td><input class="w800" type="text" name="firm_email" value="{FORM_FIRM_EMAIL}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_FIRM_PHONE}</td>
        <td><input class="w800" type="text" name="firm_phone" value="{FORM_FIRM_PHONE}" maxlength="50" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_FIRM_FAX}</td>
        <td><input class="w800" type="text" name="firm_fax" value="{FORM_FIRM_FAX}" maxlength="50" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_FIRM_MOBILE}</td>
        <td><input class="w800" type="text" name="firm_mobile" value="{FORM_FIRM_MOBILE}" maxlength="50" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_FIRM_PAGER}</td>
        <td><input class="w800" type="text" name="firm_pager" value="{FORM_FIRM_PAGER}" maxlength="50" size="90" /></td>
      </tr>
      <tr>
        <td class="head">{LANG_FIRM_HOMEPAGE}</td>
        <td><input class="w800" type="text" name="firm_homepage" value="{FORM_FIRM_HOMEPAGE}" maxlength="255" size="90" /></td>
      </tr>
      <tr>
        <td class="headre" colspan=2>Sonstige Daten des Benutzers</td>
      </tr>
      <tr>
        <td class="head">{LANG_BIRTHDAY}</td>
        <td><input class="w800" type="text" name="birthday" value="{FORM_BIRTHDAY}" maxlength="15" size="90" /></td>
      </tr>
      
       <tr>
        <td class="headre" colspan=2>Profilinformationen</td>
      </tr>
      <tr>
        <td class="head">{LANG_LAST_LOGIN}</td>
        <td>{FORM_LAST_LOGIN}</td>
      </tr>          
      <tr>
        <td class="head">{LANG_LAST_LOGIN_FAILED}</td>
        <td>{FORM_LAST_LOGIN_FAILED}</td>
      </tr>          
      <tr>
        <td class="head">{LANG_FAILED_COUNT}</td>
        <td>{FORM_FAILED_COUNT}</td>
      </tr>          
      <tr>
        <td class="head">{LANG_LAST_MODIFIED}</td>
        <td>{FORM_LAST_MODIFIED}</td>
      </tr>           
  
      
      
 		<tr>
			<td class="content7" colspan="2" style="text-align:right">
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