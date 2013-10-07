$jqsf(document).ready(function()
{
	// doing the heavy init action and load all plugins if used
	SF.Plugin.loadAll();
	
	$jqsf(document)
		// submitting the form on changing the actionbox
		.on('change', 'select.actionbox', function() {
			var onchange_confirm = $(this).data('onchange-confirm');
			onchange_confirm = onchange_confirm.replace("{selection}", this.options[this.selectedIndex].text);
			if( this.options[this.selectedIndex].value != '' &&
				(onchange_confirm == '' || (onchange_confirm != '' && confirm( onchange_confirm ) == true))
			  )
			{
				$(this).parents('form').submit();
			}
		});
});

// ----------------------------------------------------------------- //

SF.Plugin.create(
	'SortableSelectList',
	['select.sortableselect'],
	[], // no files
	function()
	{
		var $select = $jqsf('select.sortableselect'),
			select_len = $select.length;
			
		if(select_len > 0)
		{
			for (var i = 0; i < select_len; i++)
			{
				var $this = $($select[i]).hide(),
					html = '<div class="sortableselect" id="'+$this.attr('name')+'"><ul>',
					$option = $jqsf('option', $this),
					option_len = $option.length;
				
				for(var k = 0; k < option_len; k++)
				{
					if(k == 0)
					{
						html += '<li rel="'+$option[k].value+'" class="'+(($option[k].selected == true) ? 'ui-selected' : '')+'">'+$option[k].text+'</li>';
					}
					else if($option[k].selected == true && $option[k].value != '0')
					{
						html += '<li rel="'+$option[k].value+'" class="'+(($option[k].selected == true) ? 'ui-selected' : '')+'">'+$option[k].text+'</li>';
					}
					else
					{
						html += '<li rel="'+$option[k].value+'" class="'+(($option[k].selected == true) ? 'ui-selected' : '')+'">'+$option[k].text+'</li>';
					}
				};
				
				html += '</ul></div>';
				$this.parent().prepend(html);
				
				$jqsf('div.sortableselect ul', $this.parent())
					.sortable({ 
						handle: '.handle',
						axis: 'y',
						stop: function() {
							var $ul = $jqsf(this),
								$select = $ul.parent().parent().find('select'),
								options = {},
								$lis = $jqsf('li', $ul),
								$li = {};
								
							$select.html('');
							for(var i = 0, len = $lis.length; i < len; i++)
							{
								$li = $jqsf( $lis[i] );
								options +='<option value="'+$li.attr('rel')+'"';
								options += ($li.hasClass('ui-selected') == true) ? ' selected="selected"' : '';
								options += '>'+$li.text()+'</option>';
							};
							$select.html(options);
						}
					})
					.find('li').prepend('<div class="handle"><span></span></div>');
			};
			
			var lis = $jqsf('div.sortableselect ul li'),
				lastChecked = null,
				isShiftPressed = false,
				isCtrlPressed = false;
			
			$jqsf(document)
				// disable selection of a table
				.on('selection.disable', 'div.sortableselect ul', function() {
					$jqsf(this)
						.attr('unselectable', 'on')
						.addClass('disableselection')
						.on('selectstart', function() { return false; });
				})
				// enable selection of a table
				.on('selection.enable', 'div.sortableselect ul', function() {
					$jqsf(this)
						.removeAttr('unselectable')
						.removeClass('disableselection')
						.off('selectstart');
				})
				// highlight table rows by clicking only the td
				.on('click', 'div.sortableselect ul', function(event) {
					if($jqsf(event.target).is('li') == true)
					{
						var $li = $jqsf(event.target),
							$select = $li.parent().parent().siblings('select'),
							$option_selected = $jqsf('option:eq(' + $li.index()  + ')', $select);
						
						$li.toggleClass('ui-selected');
						
						if($li.hasClass('ui-selected'))
						{
							$option_selected.attr('selected', 'selected')
						}
						else
						{
							$option_selected.removeAttr('selected');
						}
						
						isShiftPressed = false; // assume that no shift key is pressed
						isCtrlPressed = false; // assume that no ctrl key is pressed
						
						// select multiple rows with shift key
						if(!lastChecked)
						{
							lastChecked = $li[0];
							return;
						}
						
						if(event.shiftKey == true)
						{
							var $lis = $li.parent().find('li'),
								start = $lis.index($li[0]),
								end = $lis.index(lastChecked),
								$options = $jqsf('option', $select),
								toSelect = $jqsf(lastChecked).hasClass('ui-selected');
							
							$lis.slice(Math.min(start,end), Math.max(start,end) + 1).toggleClass('ui-selected', toSelect);
							
							if(toSelect == true)
							{
								$options.slice(Math.min(start,end), Math.max(start,end) + 1).attr('selected', 'selected')
							}
							else
							{
								$options.slice(Math.min(start,end), Math.max(start,end) + 1).removeAttr('selected');
							}
							
							// enable selection
							$jqsf('div.sortableselect ul').trigger('selection.enable');
							
							// reset and indicate that shift key was pressed
							lastChecked = null;
							isShiftPressed = true;
							return;
						}
						else if(event.ctrlKey == true)
						{
							lastChecked = null;
							isCtrlPressed = true;
							return;
						}
						
						lastChecked = $li[0];
					}
				})
				.on('keydown', function(event) {
					if(isShiftPressed == false && event.shiftKey == true && lastChecked != null)
					{
						isShiftPressed = true;
						$jqsf('div.sortableselect ul').trigger('selection.disable');
					}
					else if(isCtrlPressed == false && event.ctrlKey == true && lastChecked != null)
					{
						isCtrlPressed = true;
						$jqsf('div.sortableselect ul').trigger('selection.disable');
					}
				})
				.on('keyup', function(event) {
					if(isShiftPressed == true && lastChecked != null)
					{
						isShiftPressed = false;
						$jqsf('div.sortableselect ul').trigger('selection.enable');
					}
					else if(isCtrlPressed == true && lastChecked != null)
					{
						isCtrlPressed = false;
						$jqsf('div.sortableselect ul').trigger('selection.enable');
					}
				});
		}
	}
);

// ----------------------------------------------------------------- //

SF.Plugin.create(
	'Tooltip',
	['.uber a[title]',
	 '.config img',
	 '.toolinfo',
	 'input[title]',
	 '.buttons img[title]',
	 '.forms img[title]',
	 '.forms button[title]',
	 '.toolbar img[title]',
	 '.toolbar button[title]',
	 '.toolbar a[title]'],
	['jquery.qtip.css',
	 'jquery/lib/jquery.qtip.min.js'],
	function(scope)
	{
		scope = (typeof(scope) === 'undefined') ? document : scope;
		
		// tooltip init / binds
		if($jqsf(document).qtip)
		{
			var options = {
					position: {
						my: 'bottom center',
						at: 'top center',
						viewport: $jqsf(document),
						adjust: {
							method: 'shift flipinvert'
						}
					},
					show: {
						delay: 300
					},
					hide: {
						//fixed: true // for testing purpose
						delay: 0
					},
					style: {
						classes: 'sfTooltip',
						width: 'auto'
					}
				},
				$toolinfos = $jqsf('.toolinfo', scope),
				$toolinfo = null;
			
			$jqsf('.uber a[title], .config img, input[title], .buttons img[title], .forms img[title], .forms button[title], .toolbar img[title], .toolbar button[title], .toolbar a[title]', scope).qtip(options);
			
			if($jqsf('.rightpane')[0])
			{
				options.position.viewport = $jqsf('.rightpane');
			}
			options.style.classes += ' sfToolinfo';
			options.content = { text: '' }; // text is replaced in loop
			
			for(var i = 0, len = $toolinfos.length; i < len; i++)
			{
				$toolinfo = $jqsf($toolinfos[i]);
				options.content.text = $toolinfo.siblings('span.toolinfo');
				$toolinfo.qtip(options);
			};
		}
	}
);

// ----------------------------------------------------------------- //

SF.Plugin.create(
	'ModrewriteCheckbox',
	['#rewrite_use_automatic'],
	[], // no files
	function()
	{
		// set & bind mod_rewrite input field to mod_rewrite automatic-checkbox
		if ($jqsf('#rewrite_use_automatic').attr('checked'))
		{
			$jqsf('#rewrite_alias').addClass('disabled-field-bg');
			$jqsf('#rewrite_url').addClass('disabled-field-bg');
		}
	
		$jqsf(document).on('click', '#rewrite_use_automatic', function()
		{
			var isChecked = $jqsf('#rewrite_use_automatic').is(':checked');
			$jqsf('#rewrite_alias')
				.toggleClass('disabled-field-bg', isChecked)
				.attr('disabled', isChecked);
			$jqsf('#rewrite_url')
				.toggleClass('disabled-field-bg', isChecked)
				.attr('disabled', isChecked);
		});
	}
);

// ----------------------------------------------------------------- //

SF.Plugin.create(
	'CheckAllNoneInvert',
	['a.chk_select_all', 'a.chk_select_none', 'a.chk_select_invert'],
	[], // no files
	function()
	{
		$jqsf(document)
			// link to select all checkboxes; example: <a href="#" class="chk_select_all" rel="mychk">All</a>
			.on('click', 'a.chk_select_all', function() {
				var rel = $jqsf(this).attr('rel').split(','),
					chk = {};
				for(var i = k = len2 = 0, len = rel.length; i < len; i++)
				{
					chk = $jqsf(":checkbox[name='" + rel[i] + "']");

					for(k = 0, len2 = chk.length; k < len2; k++)
					{
						chk[k].checked = true;
						$jqsf(chk).closest('tr').addClass('tblrbgcolorover');
					}
				}
				return false;
			})
			// link to select no checkboxes; example: <a href="#" class="chk_select_none" rel="mychk">None</a>
			.on('click', 'a.chk_select_none', function() {
				var rel = $jqsf(this).attr('rel').split(','),
					chk = {};
				for(var i = k = len2 = 0, len = rel.length; i < len; i++)
				{
					chk = $jqsf(":checkbox[name='" + rel[i] + "']");

					for(k = 0, len2 = chk.length; k < len2; k++)
					{
						chk[k].checked = false;
						$jqsf(chk).closest('tr').removeClass('tblrbgcolorover');
					}
				}
				return false;
			})
			// link to invert selection for checkboxes; example: <a href="#" class="chk_select_invert" rel="mychk">Invert</a>
			.on('click', 'a.chk_select_invert', function() {
				var rel = $jqsf(this).attr('rel').split(','),
					chk = {};
				for(var i = k = len2 = 0, len = rel.length; i < len; i++)
				{
					chk = $jqsf(":checkbox[name='" + rel[i] + "']");

					for(k = 0, len2 = chk.length; k < len2; k++)
					{
						chk[k].checked = !chk[k].checked;
						$jqsf(chk).closest('tr').toggleClass('tblrbgcolorover');
					}
				}
				return false;
			})
		
	}
);

// ----------------------------------------------------------------- //

SF.Plugin.create(
	'SelectTableRow',
	['table.sfSelectTableRow'],
	[], // no files
	function()
	{
		var chks = $jqsf('table.sfSelectTableRow :checkbox'),
			lastChecked = null,
			isShiftPressed = false;
		
		$jqsf(document)
			// reset default after ajax loaded
			.on('ajaxdeeplink.loaded', function() {
				chks = $jqsf('table.sfSelectTableRow :checkbox');
				lastChecked = null;
			})
			// disable selection of a table
			.on('selection.disable', 'table.sfSelectTableRow', function() {
				$jqsf(this)
					.attr('unselectable', 'on')
					.addClass('disableselection')
					.on('selectstart', function() { return false; });
			})
			// enable selection of a table
			.on('selection.enable', 'table.sfSelectTableRow', function() {
				$jqsf(this)
					.removeAttr('unselectable')
					.removeClass('disableselection')
					.off('selectstart');
			})
			// highlight table rows by clicking only the td
			.on('click', 'table.sfSelectTableRow tr', function(event) {
				if($jqsf(event.target).is('td') == true)
				{
					var $tr = $jqsf(this),
						$chk = $tr.find(':checkbox');

					if($chk[0])
					{
						$chk[0].checked = !$chk[0].checked;
						$tr.toggleClass('tblrbgcolorover', $chk.checked);
						isShiftPressed = false; // assume that no shift key is pressed
						
						// select multiple rows with shift key
						if(!lastChecked) {
							lastChecked = $chk[0];
							return;
						}
						
						if(event.shiftKey == true) {
							var $chks = $jqsf(chks),
								start = $chks.index($chk[0]),
								end = $chks.index(lastChecked);
							$chks.slice(Math.min(start,end), Math.max(start,end)+ 1).attr('checked', lastChecked.checked).trigger('change');
							// enable selection
							$tr.parents('table').trigger('selection.enable');
							// reset and indicate that shift key was pressed
							lastChecked = null;
							isShiftPressed = true;
							return;
						}
						
						lastChecked = $chk[0];
					}
				}
			})
			.on('keydown', function(event) {
				if(isShiftPressed == false && event.shiftKey == true && lastChecked != null)
				{
					isShiftPressed = true;
					$jqsf('table.sfSelectTableRow').trigger('selection.disable');
				}
			})
			.on('keyup', function(event) {
				if(isShiftPressed == true && lastChecked != null)
				{
					isShiftPressed = false;
					$jqsf('table.sfSelectTableRow').trigger('selection.enable');
				}
			})
			// on change the state of the checkbox, highlight the surrounding table row
			.on('change', 'table.sfSelectTableRow :checkbox', function() {
				$(this).closest('tr').toggleClass('tblrbgcolorover', this.checked);
			});
			
		// highlight all table rows by default
		for(i = 0, len = chks.length; i < len; i++)
		{
			$jqsf(chks[i]).closest('tr').toggleClass('tblrbgcolorover', chks[i].checked);
		}
		
	}
);
		
// ----------------------------------------------------------------- //

SF.Plugin.create(
	'AddCheckboxesToUrl',
	['a.add_chk_to_url'],
	[], // no files
	function()
	{
		$jqsf(document).on('click', 'a.add_chk_to_url', function() {
			return SF.Plugin.get('AddCheckboxesToUrl').append(this);
		});
	}
);

SF.Plugin.get('AddCheckboxesToUrl').append = function(link)
{
	var target = $jqsf(link),
		rel = target.attr('rel').split(','),
		firstchar = (target.attr('href').search(/\?/) === -1) ? '?' : '&',
		params = [],
		chk = {};
		
	for(i = k = len2 = 0, len = rel.length; i < len; i++)
	{
		chk = $jqsf(":checkbox[name='" + rel[i] + "']:checked");
			
		for(k = 0, len2 = chk.length; k < len2; k++)
		{
			params.push(rel[i]+'='+chk[k].value);
		}
	}
	
	location.href = target.attr('href') + firstchar + params.join('&');
	
	return false;
};

// ----------------------------------------------------------------- //

SF.Plugin.create(
	'Tabs',
	['.tab-pane','.tab-pane-vertical'],
	[], // no files
	function(scope)
	{
		scope = (typeof(scope) === 'undefined') ? document : scope;

		// tabs init / binds
		if($jqsf(document).tabs)
		{
			var options = {},
				$tabpanes = $jqsf('.tab-pane,.tab-pane-vertical', scope),
				$tabpane = $h2s = $h2 = {};
			
			// cookie plugin avaliable?
			if($jqsf.cookie) 
				options.cookie = {};
			
			for(var i = k = len2 = 0, len = $tabpanes.length; i < len; i++)
			{
				$tabpane = $( $tabpanes[i] );
				$h2s = $jqsf('h2.tab', $tabpane);
				
				// create link and replace h2 with li
				for(k = 0, len2 = $h2s.length; k < len2; k++)
				{
					$h2 = $( $h2s[k] );
					$h2.wrapInner('<a href="#' + $h2.parent().attr('id') + '" />')
					   .replaceWith('<li class="tab">' + $h2.html() + '</li>');
				};
				
				// move li to ul
				$jqsf('li.tab', $tabpane)
					.removeClass('tab')
					.appendTo( $tabpane.prepend('<ul></ul>').children('ul') );
				
				// init tabs
				$tabpane.tabs(options);
			}
		}
	}
);

// ----------------------------------------------------------------- //

SF.Plugin.create(
	'MainMenu',
	['#mainmenu'],
	[], // no files
	function()
	{
		$('ul#mainmenu').children('li').children('a')
			.click(function() {
				$('li.open', 'ul#mainmenu').removeClass('open');
				$(this).parent().addClass('open');
				return false;
			});
	}
);
