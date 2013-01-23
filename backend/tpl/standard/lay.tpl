<!-- Anfang lay.tpl -->
<div id="main">
<div class="forms">{NEW_LAYOUT}{IMPORT_LAYOUT}{BACK}</div>
 <h5>{AREA}</h5>
	<!-- BEGIN ERROR -->
	<p class="errormsg">{ERRORMESSAGE}</p>
	<!-- END ERROR -->
    <table class="uber">
      <tr valign="top">
        <th align="left">{LAY_LAYOUTNAME}</th>
        <th width="100%">{LAY_DESCRIPTION}</th>
        <th class="center">{LAY_ACTION}</th>
      </tr>
<!-- BEGIN ENTRY -->
      <tr valign="top" onmouseover="this.style['background']='{OVERENTRY_BGCOLOR}';" onmouseout="this.style['background']='{ENTRY_BGCOLOR}';" bgcolor="{ENTRY_BGCOLOR}">
        <td class="entry nowrap">{ENTRY_ICON}{ENTRY_NAME}</td>
        <td class="entry">{ENTRY_DESCRIPTION}</td>
        <td class="entry buttons">{ENTRY_EDIT} {ENTRY_IMPORT} {ENTRY_EXPORT} {ENTRY_DUPLICATE} {ENTRY_DELBUT}</td>
      </tr>
<!-- END ENTRY -->
<!-- BEGIN NOENTRY -->
      <tr>
        <td class="entry" colspan="2">{LAY_NOLAYOUTS}<td>
      </tr>
<!-- END NOENTRY -->
    </table>

</div>
<div class="footer">{FOOTER_LICENSE}</div>
</body>
</html>