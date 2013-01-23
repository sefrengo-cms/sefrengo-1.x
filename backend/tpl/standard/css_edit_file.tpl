<!-- Anfang css_edit_file.tpl -->
<div id="main">
		<div class="forms">{SUB_NAV_RIGHT}{QNAV}</div>
		<h5>{AREA_TITLE}</h5>
<!-- BEGIN ERROR -->
  <p class="errormsg">{ERR_MSG} {CSS_WARN}</p>
<!-- END ERROR -->  

			<form name="editcssfile" id="editcssfile" class="editor" action="{FORM_ACTION}" method="post">
				<input type="hidden" name="action" value="editfile" />
				<input type="hidden" name="area" value="css_edit_file" />
				<input type="hidden" name="idclient" value="{CSS_CLIENT_ID}" />
				<input type="hidden" name="idcssfile" value="{CSS_FILE_ID}" />
				<input type="hidden" name="idcssfilecopy" value="{CSS_COPY_ID}" />
				<input type="hidden" name="idexpand" value="{CSS_EXPAND_ID}" />
				<input type="hidden" name="filedirname" value="{CSS_FILE_DIR}" />
				<input type="hidden" name="filetype" value="css" />
			
			<table class="config" cellspacing="1">
					<tr>
						<td class="head"><p>{CSS_FILE_NAME}</p></td>
						<td>
      <a class="forms" href="javascript:void(0)" onclick="document.editcssfile.reset();"><img src="{BUT_RESET}" alt="{BUT_RESET_TEXT}" title="{BUT_RESET_TEXT}" /></a>
      {CSS_FILE}
						</td>
					</tr>
			<!-- BEGIN CSS_RIGHTS -->
					<tr>
						<td class="head">&nbsp;</td>
						<td>{CSS_RIGHTS_CONTENT}</td>
					</tr>
			<!-- END CSS_RIGHTS -->
					<tr>
						<td class="head">{CSS_FILE_DESC_NAME}</td>
						<td><input class="w800" type="text" maxlength="255" id="filedescription" name="filedescription" value="{CSS_FILE_DESC}" /></td>
					</tr>
			<!-- BEGIN CSSSELECT -->
					<tr>
						<td class="head">{CSS_RULES_SELECT_NAME}</td>
						<td>
							{CSS_RULES_SELECT}
			<!-- BEGIN CSSRULES -->
							<option value="{CSSRULE_VALUE}" {CSSRULE_SELECTED}>{CSSRULE}</option>
			<!-- END CSSRULES -->
							{CSS_RULES_SELECT_END}
						</td>
					</tr>
			<!-- END CSSSELECT -->
			<!-- BEGIN CSSSELECT1 -->
					<tr>
						<td class="head">{CSS_RULES_SELECT_NAME1}</td>
						<td>
							{CSS_RULES_SELECT1}
			<!-- BEGIN CSSRULES1 -->
							<option value="{CSSRULE_VALUE1}" {CSSRULE_SELECTED1}>{CSSRULE1}</option>
			<!-- END CSSRULES1 -->
							{CSS_RULES_SELECT_END1}
						</td>
					</tr>
			<!-- END CSSSELECT1 -->
			<!-- BEGIN CSS_MANAGEMENT -->
				<tr>
					<td class="head">{CSS_MANAGEMENT_TOPIC}</td>
					<td>{CSS_MANAGEMENT_CONTENT}</td>
				</tr>
			<!-- END CSS_MANAGEMENT -->
			<!-- buttons fuer reset, cancel und submit -->

					<tr>
						<td class="content7" colspan="2" style="text-align:right">
						<input name="sf_save" type="submit" value="{BUTTON_SUBMIT_VALUE}" title="{BUTTON_SUBMIT_TEXT}" class="sf_buttonAction" onmouseover="this.className='sf_buttonActionOver'" onmouseout="this.className='sf_buttonAction'" />
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