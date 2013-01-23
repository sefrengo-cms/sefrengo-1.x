<!-- Anfang settings.tpl -->
<table class="uber">
<!-- BEGIN TABLE -->
      <tr valign="top">
	<th align="left" colspan="2">{SET_HEADNAME}</th>
	<th width="10"></th>
      </tr>
	<!-- BEGIN ENTRY -->
	      <tr valign="top" onmouseover="this.style['background']='{OVERENTRY_BGCOLOR}';" onmouseout="this.style['background']='{ENTRY_BGCOLOR}';" bgcolor="{ENTRY_BGCOLOR}">{FORM_START}
		<td class="entry" width="225">{ENTRY_ICON} {ENTRY_DESC} {ENTRY_CONFIRM_DELETE}</td>
		<td class="entry" >{ENTRY_VALUE}</td>
		<td class="entry nowrap">{ENTRY_ACTIONS}</td>
	      </tr>{FORM_END}
	<!-- END ENTRY -->

<!-- END TABLE -->
    </table>
<!-- BEGIN NOENTRY -->
      <tr>
        <td class="entry" colspan="3">{LAY_NOLAYOUTS}<td>
      </tr>
<!-- END NOENTRY -->
</div>
<div class="footer">{FOOTER_LICENSE}</div>
</body>
</html>