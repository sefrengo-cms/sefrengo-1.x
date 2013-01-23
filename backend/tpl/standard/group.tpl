<!-- Anfang group.tpl -->
<div id="main">
  <div class="forms">
    {NEW_GROUP}
  </div>
    <h5>{AREA}</h5>
    <!-- BEGIN ERROR --><p class="errormsg">{ERRORMESSAGE}</p><!-- END ERROR -->
    <table class="uber">
      <tr>
        <th width="150">{LANG_NAME}</th>
        <th width="100%">{LANG_DESCRIPTION}</th>
        <th class="center">{LANG_ACTIONS}</th>
      </tr><!-- BEGIN PREENTRY -->
      <tr valign="top" onmouseover="this.style['background']='#fff7ce';" onmouseout="this.style['background']='{BGCOLOR}';" bgcolor="{BGCOLOR}">
        <td class="entry nowrap">{ENTRY_ICON} {NAME}</td>
        <td class="entry">{DESCRIPTION}</td>
        <td class="entry buttons" align="right">{BUTTON_EDIT} {BUTTON_CONFIG} {BUTTON_AKTIVE} {BUTTON_DELETE}</td>
      </tr><!-- END PREENTRY --><!-- BEGIN ENTRY -->
      <tr valign="top" onmouseover="this.style['background']='#fff7ce';" onmouseout="this.style['background']='{BGCOLOR}';" bgcolor="{BGCOLOR}">
        <td class="entry nowrap">{ENTRY_ICON} {NAME}</td>
        <td class="entry">{DESCRIPTION}</td>
        <td class="entry buttons" align="right">{BUTTON_EDIT} {BUTTON_CONFIG} {BUTTON_AKTIVE} {BUTTON_DELETE}</td>
      </tr><!-- END ENTRY --><!-- BEGIN CONFIG -->
      <tr valign="top" onmouseover="this.style['background']='#fff7ce';" onmouseout="this.style['background']='{BGCOLOR}';" bgcolor="{BGCOLOR}">
        <td class="entry nowrap">{ENTRY_ICON} {NAME}</td>
        <td class="entry">{DESCRIPTION}</td>
        <td class="entry buttons" align="right">{BUTTON_CONFIG} {BUTTON_AKTIVE} {SPACE}</td>
      </tr><!-- END CONFIG --><!-- BEGIN POSTENTRY -->
      <tr valign="top" onmouseover="this.style['background']='#fff7ce';" onmouseout="this.style['background']='{BGCOLOR}';" bgcolor="{BGCOLOR}">
        <td class="entry nowrap">{ENTRY_ICON} {NAME}</td>
        <td class="entry">{DESCRIPTION}</td>
        <td class="entry buttons" align="right">{BUTTON_EDIT} {BUTTON_CONFIG} {BUTTON_AKTIVE} {BUTTON_DELETE}</td>
      </tr><!-- END POSTENTRY --><!-- BEGIN EMPTY -->
      <tr bgcolor="#ffffff">
        <td class="entry" colspan="2">{LANG_NOGROUPS}<td>
      </tr><!-- END EMPTY -->
    </table>
</div>
<div class="footer">{FOOTER_LICENSE}</div>
</body>
</html>