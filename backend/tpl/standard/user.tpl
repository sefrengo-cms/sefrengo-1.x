<!-- Anfang user.tpl -->
<div id="main">
  <div class="forms">
     <form name="navi" method="get" action="{FORM_URL}">
      <input type="submit" id="usub" value="" />
      <input type="text" name="searchterm" value="{SEARCHTERM}" />
      <select name="area" size="1" onchange="navi.submit()">
<!-- BEGIN SELECT_ACTIONLIST -->
      <option value="{ACTIONLIST_VALUE}"{ACTIONLIST_SELECTED}>{ACTIONLIST_ENTRY}</option>
<!-- END SELECT_ACTIONLIST -->
      </select>

      <select name="idgroup" size="1" onchange="navi.submit()">
<!-- BEGIN SELECT_SHOWLIST -->
      <option value="{SHOWLIST_VALUE}"{SHOWLIST_SELECTED}{SHOWLIST_STYLE}>{SHOWLIST_ENTRY}</option>
<!-- END SELECT_SHOWLIST -->
      </select>
     <input type="hidden" name="order" value="{ORDER}" />
      <input type="hidden" name="ascdesc" value="{ASCDESC}" />
      <input type="hidden" name="page" value="{PAGE}" />
      <input type="hidden" name="{SESS_NAME}" value="{SESS_ID}" />
  </div>
  <h5>{AREA}</h5>
<!-- BEGIN ERROR -->
  <p class="errormsg">{ERRORMESSAGE}</p>
<!-- END ERROR -->
  <table class="uber">
    <tr>
      <td colspan="5" class="entryuser">
        <p class="seite">
          Seite
          <input type="text" name="changepage1" value="{CHANGEPAGE_CURRENT}" /> von {CHANGEPAGE_MAX}
        </p>
        <p class="midd">{SEARCHRESET}
        </p>
        <p class="zahl">{PAGER_LINKS}
        </p>
      </td>
    </tr>
    <tr>
      <th width="20%">{LANG_LOGINNAME}</th>
      <th width="25%">{LANG_NAME}/{LANG_SURNAME}</th>
      <th width="25%">{LANG_EMAIL}</th>
      <th width="30%">{LANG_FIRM}</th>
      <th class="center">{LANG_ACTIONS}</th>
    </tr>
<!-- BEGIN ENTRY -->
    <tr valign="top" onmouseover="this.style['background']='#fff7ce';" onmouseout="this.style['background']='#ffffff';" bgcolor="#ffffff">
      <td class="entry nowrap">{USERICON}
      {LOGINNAME}</td>
      <td class="entry">{NAME}{SURNAME}</td>
      <td class="entry">{EMAIL}</td>
      <td class="entry">{FIRM}</td>
      <td class="entry buttons">{BUTTON_SENDMAIL} {BUTTON_EDIT} {BUTTON_AKTIVE} {BUTTON_DELETE}</td>
    </tr>
<!-- END ENTRY --> 
<!-- BEGIN EMPTY -->
    <tr>
      <td colspan="5" class="entryuser">{LANG_NOUSERS}</td>
    </tr>
<!-- END EMPTY -->
    <tr>
      <td colspan="5" class="entryuser">
        <p class="seite">
          Seite
          <input type="text" name="changepage2" value="{CHANGEPAGE_CURRENT}" /> von {CHANGEPAGE_MAX}
        </p>
        <p class="zahl">{PAGER_LINKS}
        </p>
      </td>
    </tr>
  </table>
  </form>
</div>
<div class="footer">{FOOTER_LICENSE}</div>
</body>
</html>
