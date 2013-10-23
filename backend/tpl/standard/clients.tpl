<!-- Anfang clients.tpl -->
<div id="main">
<!-- BEGIN HEADER -->
<div class="forms">
{SUB_NAV_RIGHT}
</div>
<h5>{AREA_TITLE}</h5>
<!-- BEGIN ERROR -->
<p class="errormsg">{ERR_MSG}</p>
<!-- END ERROR -->
<!-- BEGIN OK -->
<p class="ok">{USER_MSG}</p>
<!-- END OK -->
  <table class="uber">
  <tr>
    <th>{TITLE}</th>
    <th width="100%">{DESCRIPTION}</th>
    <th class="center" width="100">{ACTIONS}</th>
  </tr>
<!-- END HEADER -->          
          

<!-- BEGIN ENTRY -->

	<!-- BEGIN PROJECT -->
                   <tr onmouseover="this.style['background']='#fff7ce';" onmouseout="this.style['background']='{PR_ENTRY_BGCOLOR}';" bgcolor="{PR_ENTRY_BGCOLOR}">
		<!-- BEGIN PROJECTNEW -->
		    {PRN_FORM_START}
		    <td class="entry nowrap">{PRN_ENTRY_TITLEFIELD}</td>
		    <td class="entry label">
		             <label>{PRN_DESC}</label>
		             {PRN_DESCFIELD}
		           <br />
		             <label>{PRN_PATH}</label>
		             {PRN_PATHFIELD}
		           <br />
		             <label>{PRN_URL}</label>
		             {PRN_URLFIELD}
		           <br />
		             <label>{PRN_WITH_DIR}</label>
		             {PRN_WITH_DIRFIELD}
		           <br />
		             <label>{PRN_LANG}</label>
		             {PRN_LANGFIELD}
		           <br />
		             <label>{PRN_LANG_DESC}</label>
		             {PRN_LANG_DESCFIELD}
		           <br />
		             <label>{PRN_LANG_CHARSET}</label>
		             {PRN_CHARSETFIELD}
		    </td>
		    <td class="entry nowrap">{PRN_ENTRY_SUBMIT} {PRN_ENTRY_CANCEL}</td>
	          </tr></form>
		<!-- END PROJECTNEW -->
		
		<!-- BEGIN PROJECTSHOW -->
		    <td class="entry nowrap">{PR_ENTRY_EXPANDER} {PR_ENTRY_ICON} {PR_ENTRY_NAME}</td>
		    <td class="entry">{PR_ENTRY_DESCRIPTION}</td>
		    <td class="entry nowrap">{PR_LANG_NEW} {PR_ENTRY_EDIT} {PR_ENTRY_CONF} {PR_ENTRY_DELETE}</td>
	          </tr>
		<!-- END PROJECTSHOW -->

		<!-- BEGIN PROJECTEDIT -->
		    {PR_FORM_START}
		    <td class="entry nowrap">{PR_ENTRY_EXPANDER} {PR_ENTRY_TITLEFIELD}</td>
		    <td class="entry">{PR_ENTRY_DESCFIELD}</td>
		    <td class="entry nowrap">{PR_ENTRY_RIGHTS} {PR_ENTRY_SUBMIT} {PR_ENTRY_CANCEL}</td>
	          </tr></form>
		<!-- END PROJECTEDIT -->
	<!-- END PROJECT -->


	<!-- BEGIN LANG -->
		   <tr onmouseover="this.style['background']='#fff7ce';" onmouseout="this.style['background']='{ENTRY_BGCOLOR}';" bgcolor="{ENTRY_BGCOLOR}">
		<!-- BEGIN LANGSHOW -->
		     <td class="entry nowrap">{ENTRY_ICON} {ENTRY_NAME}</td>
		     <td class="entry">{ENTRY_DESCRIPTION}</td>
		     <td class="entry nowrap">{ENTRY_STARTLANG} {ENTRY_EDIT} {ENTRY_CONF_LANG} {ENTRY_DELETE}</td>
		   </tr>
		<!-- END LANGSHOW -->

		<!-- BEGIN LANGEDIT -->
		     {FORM_START}
		     <td class="entry">{ENTRY_TITLEFIELD}</td>
		     <td class="entry label">
		              <label for="newdesc">{LANG_DESCFIELD}</label>
		             {ENTRY_DESCFIELD}
		           <br />
		             <label for="charset">{LANG_CHARSET}</label>
		             {ENTRY_CHARSET}
		           <br />
		             <label for="iso_3166">{LANG_ISO_3166}</label>
		             {ENTRY_ISO_3166}
		           <br />
		             <label for="rewrite_key">{LANG_REWRITE_KEY}</label>
		             {ENTRY_REWRITE_KEY}
		           <br />
		             <label for="rewrite_mapping">{LANG_REWRITE_MAPPING}</label>
		             {ENTRY_REWRITE_MAPPING}
		     </td>
		     <td class="entry nowrap">{ENTRY_RIGHTS} {ENTRY_SUBMIT} {ENTRY_CANCEL}</td>
		     
		   </tr></form>
		<!-- END LANGEDIT -->
	<!-- END LANG -->

<!-- END ENTRY -->

        </table>
</div>
<div class="footer">{FOOTER_LICENSE}</div>
</body>
</html>
