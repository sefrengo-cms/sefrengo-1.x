<!-- Anfang css_edit.tpl -->
<div id="main">
		<div class="forms">{SUB_NAV_RIGHT}{QNAV}</div>
		<h5>{AREA_TITLE}</h5>
<!-- BEGIN ERROR -->
  <p class="errormsg">{ERR_MSG}</p>
<!-- END ERROR -->   {CSS_WARN}

			<form name="editstyle" id="editstyle" class="editor" action="{FORM_ACTION}" method="post">
					<input type="hidden" name="action" value="editrule" />
					<input type="hidden" name="area" value="css_edit" />
					<input type="hidden" name="idcss" value="{IDCSS}" />
					<input type="hidden" name="idexpand" value="{IDEXPAND}" />
					<input type="hidden" name="idclient" value="{IDCLIENT}" />
				<!-- BEGIN CSSERRORS -->
							<p class="errormsg">{CSSWARNING_DESC}<br />{CSSWARNINGS}<input type="hidden" name="{CSSWARNING_NAME}" value="{CSSWARNING_VALUE}" /></p>
				<!-- END CSSERRORS -->
				<table class="config" cellspacing="1">
					<tr>
						<td class="head" width="100"><p>{EDIT_TYPE_DESC}</p></td>
						<td><select name="{EDIT_TYPE_NAME}">
				<!-- BEGIN TYPELIST -->
								<option value="{EDIT_TYPE_VALUE}"{EDIT_TYPE_SELECTED}>{EDIT_TYPE_TEXT}</option>
				<!-- END TYPELIST -->
							</select>
						</td>
      </tr>
					<tr>
						<td class="head">{EDIT_CSSSELECTOR_DESC}</td>
						<td><input class="w800" type="text" maxlength="255" id="{EDIT_CSSSELECTOR_NAME}" name="{EDIT_CSSSELECTOR_NAME}" size="120" value="{EDIT_CSSSELECTOR_VALUE}" />
						</td>
					</tr>
					<tr>
						<td class="head">{EDIT_CSSRULE_DESC}</td>
						<td><input class="w800" type="text" maxlength="255" id="{EDIT_CSSRULE_NAME}" name="{EDIT_CSSRULE_NAME}" value="{EDIT_CSSRULE_VALUE}" />
						</td>
					</tr>
					<tr>
					<td class="head">&nbsp;</td>
						<td>{EDIT_CSSFILE_TEXT}<input type="hidden" name="{EDIT_CSSFILE_NAME}" value="{EDIT_CSSFILE_VALUE}" />
						</td>
					</tr>
					<tr>
						<td class="head">{EDIT_ELEMENT_TEXT}</td>
						<td>
							<table id="cssedit">
								<tr>
									<td>
										<textarea id="styles" name="{EDIT_ELEMENT_NAME}" cols="30" rows="10" onBlur="{EDIT_ELEMENT_ONBLUR}">{EDIT_ELEMENT_RULES}</textarea>
									</td>
									<td align="right">
										<select name="{EDIT_ELEMENTS_NAME}" onchange="{EDIT_ELEMENTS_ONCHANGE}" size="9" disabled="disabled">
											<option value="">{EDIT_ELEMENT_SELECTION}</option>
											<optgroup label="Font">
												<option value="font-family">font-family</option>
												<option value="font-size">font-size</option>
												<option value="font-size-adjust">font-size-adjust</option>
												<option value="font-stretch">font-stretch</option>
												<option value="font-style">font-style</option>
												<option value="font-variant">font-variant</option>
												<option value="font-weight">font-weight</option>
												<option value="color">color</option>
											</optgroup>
											<optgroup label="Text">
												<option value="letter-spacing">letter-spacing</option>
												<option value="word-spacing">word-spacing</option>
												<option value="line-height">line-height</option>
												<option value="text-align">text-align</option>
												<option value="vertical-align">vertical-align</option>
												<option value="text-decoration">text-decoration</option>
												<option value="text-indent">text-indent</option>
												<option value="text-shadow">text-shadow</option>
												<option value="text-transform">text-transform</option>
											</optgroup>
											<optgroup label="Background and Color">
												<option value="background-color">background-color</option>
												<option value="background-image">background-image</option>
												<option value="background-repeat">background-repeat</option>
												<option value="background-attachment">background-attachment</option>
												<option value="background-position">background-position</option>
											</optgroup>
											<optgroup label="Box">
												<option value="width">width</option>
												<option value="height">height</option>
												<option value="clear">clear</option>
												<option value="float">float</option>
												<option value="display">display</option>
												<option value="white-space">white-space</option>
											</optgroup>
											<optgroup label="Border">
												<option value="border">border</option>
												<option value="border-color">border-color</option>
												<option value="border-style">border-style</option>
												<option value="border-width">border-width</option>
												<option value="border-left">border-left</option>
												<option value="border-left-width">border-left-width</option>
												<option value="border-right">border-right</option>
												<option value="border-right-width">border-right-width</option>
												<option value="border-top">border-top</option>
												<option value="border-top-width">border-top-width</option>
												<option value="border-bottom">border-bottom</option>
												<option value="border-bottom-width">border-bottom-width</option>
											</optgroup>
											<optgroup label="Padding">
												<option value="padding">padding</option>
												<option value="padding-bottom">padding-bottom</option>
												<option value="padding-left">padding-left</option>
												<option value="padding-right">padding-right</option>
												<option value="padding-top">padding-top</option>
											</optgroup>
											<optgroup label="Margin">
												<option value="margin">margin</option>
												<option value="margin-bottom">margin-bottom</option>
												<option value="margin-left">margin-left</option>
												<option value="margin-right">margin-right</option>
												<option value="margin-top">margin-top</option>
											</optgroup>
											<optgroup label="List">
												<option value="list-style-type">list-style-type</option>
												<option value="list-style-image">list-style-image</option>
												<option value="list-style-position">list-style-position</option>
											</optgroup>
											<optgroup label="Position">
												<option value="position">position</option>
												<option value="left">left</option>
												<option value="right">right</option>
												<option value="top">top</option>
												<option value="bottom">bottom</option>
												<option value="width">width</option>
												<option value="height">height</option>
												<option value="z-Index">z-Index</option>
												<option value="visibility">visibility</option>
											</optgroup>
										</select><br />
										<select name="{EDIT_UNITS_NAME}" onchange="{EDIT_UNITS_CHANGE}" disabled="disabled">
											<option value="{EDIT_UNITS_DEFAULT_VALUE}">{EDIT_UNITS_DEFAULT_TEXT}</option>
										</select>
									</td>
						<td>
							{COLORPCIKER}
						</td>
        </tr>
								<tr>
									<td colspan="2">
										<input style="width:540px" type="text" maxlength="255" name="{EDIT_ELEMENTVALUE_NAME}" size="30" onBlur="{EDIT_ELEMENTVALUE_ONBLUR}" disabled="disabled" /><br/><br/>
          <div class="forms">
										<a href="javascript:void(0)" onclick="{BUTTON_CSSCLR_URL}"><img src="{BUTTON_CSSCLR_PICT}" width="16" height="16" alt="{BUTTON_CSSCLR_TEXT}" title="{BUTTON_CSSCLR_TEXT}" /></a>
										<a href="javascript:void(0)" onclick="{BUTTON_CSSADD_URL}"><img src="{BUTTON_CSSADD_PICT}" width="16" height="16" alt="{BUTTON_CSSADD_TEXT}" title="{BUTTON_CSSADD_TEXT}" /></a>
										<a href="javascript:void(0)" onclick="{BUTTON_CSSREM_URL}"><img src="{BUTTON_CSSREM_PICT}" width="16" height="16" alt="{BUTTON_CSSREM_TEXT}" title="{BUTTON_CSSREM_TEXT}" /></a>
										<!--a href="javascript:void(0)" onclick="{BUTTON_CSSHLP_URL}"><img src="{BUTTON_CSSHLP_PICT}" width="16" height="16" alt="{BUTTON_CSSHLP_TEXT}" title="{BUTTON_CSSHLP_TEXT}" /></a-->
										</div>
          <input type="checkbox" name="checkstyle" id="checkstyle" value="1" onclick="{EDITOR_RULE_CHECK}"{CHECKRULE_CHECKED} /> <label for="checkstyle">{CHECKRULE_OPTION}</label>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="head" rowspan="2">{PREVIEWTYPE_TEXT}</td>
						<td><select name="{PREVIEW_NAME}" onchange="{PREVIEW_CHANGE}">
				<!-- BEGIN OPTIONLIST -->
								<option value="{OPTION_VALUE}">{OPTION_TEXT}</option>
				<!-- END OPTIONLIST -->
							</select>
							<a href="javascript:void(0)" onclick="{PREVIEW_CLICK}">
							<img src="{PREVIEW_PICT}" width="16" height="16" alt="{PREVIEWHINT_TEXT}" title="{PREVIEWHINT_TEXT}" /></a>
						</td>
					</tr>
					<tr>
						<td colspan="2"><iframe height="100" width="502" id="previewarea" name="previewarea" scrolling="auto" src="about:blank"></iframe></td>
					</tr>
				<!-- BEGIN CSS_MANAGEMENT_INFO -->
					<tr>
						<td class="head">{CSS_INFO_TOPIC}</td>
						<td>{CSS_INFO_CONTENT}</td>
					</tr>
				<!-- END CSS_MANAGEMENT_INFO -->
					<tr>
						<td class="content7" colspan="3" style="text-align:right">
						<input name="sf_safe" type="submit" value="{BUTTON_SUBMIT_VALUE}" title="{BUTTON_SUBMIT_TEXT}" class="sf_buttonAction" onmouseover="this.className='sf_buttonActionOver'" onmouseout="this.className='sf_buttonAction'" />
						<input name="sf_apply" type="submit" value="{BUTTON_APPLY_VALUE}" title="{BUTTON_APPLY_TEXT}" class="sf_buttonAction" onmouseover="this.className='sf_buttonActionOver'" onmouseout="this.className='sf_buttonAction'" />
						<input name="sf_cancel" type="button" value="{BUTTON_CANCEL_VALUE}" title="{BUTTON_CANCEL_TEXT}" class="sf_buttonAction" onclick="window.location='{BUTTON_CANCEL_URL}'" onmouseover="this.className='sf_buttonActionCancelOver'" onmouseout="this.className='sf_buttonAction'" />
						</td>
					</tr>
				</table>
			</form>
			<script src="{EDITOR_JS}" type="text/javascript"></script>
			<script type="text/javascript">
				function ddx_initBuilder() {
					var ddx_cssconfig = new Array();
					ddx_cssconfig.ddx_css_preview_baseadr = "{EDITOR_PREVIEW_BASE}";
					ddx_cssconfig.ddx_previewname         = "{EDITOR_PREVIEWNAME}";
					ddx_cssconfig.ddx_picker              = "{EDITOR_PICKER}";
					ddx_cssconfig.ddx_visible             = "{EDITOR_VISIBLE}";
					ddx_cssconfig.ddx_hidden              = "{EDITOR_HIDDEN}";
					ddx_cssconfig.ddx_nopreview           = "{EDITOR_NOPREVIEW}";
					ddx_cssconfig.ddx_choosepreview       = "{EDITOR_CHOOSEPREVIEW}";
					ddx_cssconfig.ddx_chosseelement       = "{EDITOR_CHOSSEELEMENT}";
					ddx_cssconfig.ddx_entervalue0         = "{EDITOR_ENTERVALUE0}";
					ddx_cssconfig.ddx_entervalue1         = "{EDITOR_ENTERVALUE1}";
					ddx_cssconfig.ddx_confirmvalue        = "{EDITOR_CONFIRMVALUE}";
					cssbuilder = new csseditor( {EDITOR_INIT}, ddx_cssconfig );
				}
				setTimeout("ddx_initBuilder()", 50);
			</script>
</div>
<div class="footer">{FOOTER_LICENSE}</div>
</body>
</html>