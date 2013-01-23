<!-- Anfang plug_settings.tpl -->
<!-- BEGIN TABLE -->
  <tr valign="top">
    <th align="left" colspan="2">{SET_HEADNAME}</th>
    <th width="10"></td>
  </tr>
<!-- BEGIN ENTRY -->
  <tr valign="top" onmouseover="this.style['background']='{OVERENTRY_BGCOLOR}';" onmouseout="this.style['background']='{ENTRY_BGCOLOR}';" bgcolor="{ENTRY_BGCOLOR}">{FORM_START}
    <td class="entry nowrap">{ENTRY_DESC} {ENTRY_CONFIRM_DELETE}</td>
    <!--td class="entry">:</td-->
    <td class="entry" bgcolor="white" width="100%">{ENTRY_VALUE}</td>
    <td class="entry buttons">{ENTRY_ACTIONS}</td>
  </tr>{FORM_END}
<!-- END ENTRY -->
<!-- END TABLE -->