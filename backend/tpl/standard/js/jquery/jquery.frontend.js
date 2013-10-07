var $jqsf = jQuery;

$jqsf(document).ready(function()
{
	SF.Plugin.loadAll();
});

// ----------------------------------------------------------------- //

SF.Plugin.create(
	'Tooltip',
	['.sf_hmenu_wrapper'],
	['jquery.qtip.css',
	 'lib/jquery.qtip.min.js'],
	function(scope)
	{
		scope = (typeof(scope) === 'undefined') ? document : scope;
		
		// tooltip init / binds
		if($jqsf(document).qtip)
		{
			var options = {
					content: {
						text: '' // replaced in loop
					},
					position: {
						my: 'top left',
						at: 'bottom center',
						viewport: $jqsf(window),
						adjust: {
							method: 'shift flip',
							x: -4
						}
					},
					show: {
						delay: 0
					},
					hide: {
						fixed: true,
						delay: 300
					}
				},
				$wrapper = $jqsf('.sf_hmenu_wrapper', scope);
			
			for(var i = 0, len = $wrapper.length; i < len; i++)
			{
				options.content.text = $jqsf('.sf_hovermenu', $wrapper[i]);
				$jqsf('.sf_hmenu_trigger', $wrapper[i]).qtip(options);
			}
		}
	}
);

