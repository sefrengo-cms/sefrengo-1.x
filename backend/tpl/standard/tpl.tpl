<!-- Anfang tpl.tpl -->
<div id="main">
<div class="forms">
		{NEW_TEMPLATE}
</div>
 <h5>{AREA}</h5>
	<!-- BEGIN ERROR -->
	<p class="errormsg">{ERRORMESSAGE}</p>
	<!-- END ERROR -->
    <table class="uber">
      <tr>
        <th align="left">{TPL_TEMPLATENAME}</th>
        <th width="100%">{TPL_DESCRIPTION}</th>
        <th class="center">{TPL_ACTION}</th>
      </tr>
<!-- BEGIN ENTRY -->
      <tr onmouseover="this.style['background']='{OVERENTRY_BGCOLOR}';" onmouseout="this.style['background']='{ENTRY_BGCOLOR}';" bgcolor="{ENTRY_BGCOLOR}">
        <td class="entry nowrap">{ENTRY_ICON} {ENTRY_NAME}</td>
        <td class="entry">{ENTRY_DESCRIPTION}</td>
        <td class="entry buttons">{ENTRY_STARTTPL} {ENTRY_EDIT} {ENTRY_DUPLICATE} {ENTRY_DELBUT}</td>
      </tr>
<!-- END ENTRY -->
<!-- BEGIN NOENTRY -->
      <tr>
        <td class="entry" colspan="3" style="background:#fff">{TPL_NOTEMPLATES}</td>
      </tr>
<!-- END NOENTRY -->
    </table>
</div>
<div class="footer">{FOOTER_LICENSE}</div>
</body>
</html>