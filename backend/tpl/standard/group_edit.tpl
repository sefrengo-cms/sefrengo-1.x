<!-- Anfang goup_edit.tpl -->
<div id="main">
    <h5>{AREA}</h5>
    <!-- BEGIN ERROR -->
    <p class="errormsg">{ERRORMESSAGE}</p><!-- END ERROR -->
    <form name="user" method="post" action="{FORM_URL}" />

<table class="config" cellspacing="1">
      <tr>
        <td class="head nowrap" width="110">{LANG_NAME}
    <input type="hidden" name="area" value="group_edit" />
    <input type="hidden" name="action" value="save" />
    <input type="hidden" name="idgroup" value="{IDGROUP}" />
    <input type="hidden" name="order" value="{ORDER}" />
    <input type="hidden" name="ascdesc" value="{ASCDESC}" />
    <input type="hidden" name="oldname" value="{FORM_OLDNAME}" />
        </td>
        <td><input class="w800" type="text" name="name" value="{FORM_NAME}" size="90" /></td>
      </tr>
      <tr>
        <td class="head nowrap"><p>{LANG_DESCRIPTION}</p></td>
        <td><input class="w800" type="text" name="description" value="{FORM_DESCRIPTION}" size="90" /></td>
      </tr>
      <tr>
			<td class="content7" colspan="2" style="text-align:right">
			<input name="sf_safe" type="submit" value="{BUTTON_SUBMIT_VALUE}" title="{BUTTON_SUBMIT_TEXT}" class="sf_buttonAction" onmouseover="this.className='sf_buttonActionOver'" onmouseout="this.className='sf_buttonAction'" />
			<input name="sf_apply" type="submit" value="{BUTTON_APPLY_VALUE}" title="{BUTTON_APPLY_TEXT}" class="sf_buttonAction" onmouseover="this.className='sf_buttonActionOver'" onmouseout="this.className='sf_buttonAction'" />
			<input name="sf_cancel" type="button" value="{BUTTON_CANCEL_VALUE}" title="{BUTTON_CANCEL_TEXT}" class="sf_buttonAction" onclick="window.location='{BUTTON_CANCEL_URL}'" onmouseover="this.className='sf_buttonActionCancelOver'" onmouseout="this.className='sf_buttonAction'" />
			</td>
	  </tr>
    </table>
    </form>
</div>
<div class="footer">{FOOTER_LICENSE}</div>
</body>
</html>