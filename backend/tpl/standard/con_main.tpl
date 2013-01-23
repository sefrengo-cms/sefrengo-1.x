<!-- Anfang con_main.tpl -->
<div id="main">
<div class="forms" id="formpadd">
<!-- BEGIN FORM_SELECT_ACTIONS -->
  <form name="actionform" method="post" action="{FORM_URL_ACTIONS}">
    <input type="hidden" name="area" value="con" />
    <select name="change_show_tree" size="1" onchange="actionform.submit()">
      <option value="">{LANG_SELECT_ACTIONS}</option><!-- BEGIN SELECT_ACTIONS -->
      <option value="{ACTIONS_VALUE}" {ACTIONS_SELECTED}>{ACTIONS_ENTRY}</option><!-- END SELECT_ACTIONS -->
    </select>
  </form><!-- END FORM_SELECT_ACTIONS --><!-- BEGIN FORM_SELECT_VIEW -->
  <form name="treeform" method="post" action="{FORM_URL_VIEW}">
    <input type="hidden" name="area" value="con" />
    <select name="change_show_tree" size="1" onchange="treeform.submit()">
      <option value="">{LANG_SELECT_VIEW}</option><!-- BEGIN SELECT_FOLDERLIST -->
      <option value="{FOLDERLIST_VALUE}" {FOLDERLIST_SELECTED}>{FOLDERLIST_ENTRY}</option><!-- END SELECT_FOLDERLIST -->
    </select>
  </form><!-- END FORM_SELECT_VIEW --><!-- BEGIN FORM_CHANGE_TO -->
  <form name="changetoform" method="post" action="{FORM_URL_CHANGE_TO}">
    <input type="hidden" name="area" value="con" />
    <select name="sort" size="1" onchange="changetoform.submit()">
      <option value="">{LANG_CHANGE_TO}</option><!-- BEGIN SELECT_CHANGE_TO -->
      <option value="{CHANGE_TO_VALUE}" {CHANGE_TO_SELECTED}>{CHANGE_TO_ENTRY}</option><!-- END SELECT_CHANGE_TO -->
    </select>
  </form><!-- END FORM_CHANGE_TO -->
</div>
<h5>{AREA}</h5>
 <script type="text/javascript" src="tpl/standard/js/popupmenu.js"></script>
    <div id="overDiv" style="position:absolute; visibility:hidden;"></div>
<!-- BEGIN ERRORMESSAGE -->
<p class="errormsg">{ERRORMESSAGE} </p>
<!-- END ERRORMESSAGE -->

    <table class="uber">
      <tr>
        <th width="100%">{LANG_STRUCTURE_AND_SIDE}{BUTTON_EXPAND}{BUTTON_MINIMIZE}</th>
        <th class="center">{LANG_ACTIONS}</th>
      </tr><!-- BEGIN TREE -->{EMPTY_ROW}
      <tr onmouseover="this.style['background']='{TABLE_OVERCOLOR}';" onmouseout="this.style['background']='{TABLE_COLOR}';" bgcolor="{TABLE_COLOR}">{EXPAND_ANCHOR}
        <td class="entry" >{SPACES_BEFORE}{BUTTON_CAT_EXPAND}{BUTTON_CAT_CONFIG} {CAT_NAME}</td>
        <td class="entry buttons" >{BUTTON_NEWSIDE} {BUTTON_NEWCAT} {BUTTON_COPYCAT} {BUTTON_LOCK} {BUTTON_PUBLISH} {BUTTON_DELETE} {BUTTON_PREVIEW}{CAT_ACTIONS}</td>
      </tr><!-- BEGIN SIDES -->
      <tr onmouseover="this.style['background']='{TABLE_OVERCOLOR}';" onmouseout="this.style['background']='{TABLE_COLOR}';" bgcolor="{TABLE_COLOR}">
        <td class="entry">{SPACES_BEFORE_SIDENAME}{SIDECONFIG}{SIDENAME}{SIDEANCHOR}</td>
        <td class="entry buttons">{BUTTON_STARTPAGE} {BUTTON_EDIT} {BUTTON_COPY} {BUTTON_LOCK} {BUTTON_PUBLISH} {BUTTON_DELETE} {BUTTON_PREVIEW}{SIDE_ACTIONS}</td>
      </tr><!-- END SIDES --><!-- END TREE --><!-- BEGIN EMPTY -->{EMPTY_ROW}
      <tr>
        <td class="entry">{LANG_NOCATS}<td>
      </tr><!-- END EMPTY -->
    </table>
</div>
<div class="footer">{FOOTER_LICENSE}</div>
</body>
</html>
