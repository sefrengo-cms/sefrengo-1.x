<!-- Anfang js_edit_file.tpl -->
<div id="main">
		<div class="forms">{SUB_NAV_RIGHT}</div>
		<h5>{AREA_TITLE}</h5>
<!-- BEGIN ERROR -->
  <p class="errormsg">{ERR_MSG} {CSS_WARN}</p>
<!-- END ERROR -->  
			<form name="editjsfile" id="editjsfile" action="{FORM_ACTION}" method="post">
					<input type="hidden" name="action" value="editfile" />
					<input type="hidden" name="area" value="js_edit_file" />
					<input type="hidden" name="idjsfile" value="{IDJS}" />
					<input type="hidden" name="idclient" value="{IDCLIENT}" />
					<input type="hidden" name="jsfiletype" value="js" />
					<input type="hidden" name="jsfiledirname" value="{EDIT_JSFILEDIR_VALUE}" />

				<table class="config" cellspacing="1">
					<tr>
						<td class="head"><p>{EDIT_JSFILENAME}</p></td>
						<td>{EDIT_JSFILE}
						</td>
					</tr>
				<!-- BEGIN JS_RIGHTS -->
					<tr>
						<td class="head">&nbsp;</td>
						<td>{JS_RIGHTS_CONTENT}</td>
					</tr>
				<!-- END JS_RIGHTS -->
					<tr>
						<td class="head">{EDIT_JSFILEDESCNAME}</td>
						<td><input class="w800" type="text" maxlength="255" id="{EDIT_JSFILEDESC_NAME}" name="{EDIT_JSFILEDESC_NAME}" value="{EDIT_JSFILEDESC_VALUE}">
						</td>
					</tr>
					<tr>
						<td class="head">{EDIT_JSCODENAME}</td>
						<td><textarea class="w800" cols="45" rows="15" name="{EDIT_JSCODE_NAME}" id="{EDIT_JSCODE_NAME}" wrap="off">{EDIT_JSCODE}</textarea>
						</td>
					</tr>
				<!-- BEGIN JS_MANAGEMENT -->
					<tr>
						<td class="head">{JS_MANAGEMENT_TOPIC}</td>
						<td>{JS_MANAGEMENT_CONTENT}</td>
					</tr>
				<!-- END JS_MANAGEMENT -->
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