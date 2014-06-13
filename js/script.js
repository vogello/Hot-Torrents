$(document).ready(function() {
	var types = { '???': 0 };
	$('table tr td.type').each(function() {
		var val = $(this).text();
		if (val && types[val] == undefined)
		{
			types[val] = 1;
		}
		else if (val && types[val] > 0) 
		{
			types[val]++;
		} 
		else 
		{
			types['???']++;
		}
	});

	var defaults = Array('DVDrip', 'BRRip', 'HDrip', 'DVDScr', 'R5');

	$('#filter').empty().append('<li style="list-style: none;"><label><input type="checkbox" checked="checked" class="all" /></label></li>');
	for(var i in types)
	{
		$('#filter').append('<li style="list-style: none;"><label><input type="checkbox" value="'+ i + '"' + (defaults.indexOf(i) > -1 ? ' checked="checked"' : '') + ' /> ' + i + ' (' + types[i] + ')</label></li>');
	}

	var _filter_change = function() {

		$('table tr:not(.header)').hide();

		$('#filter input:checked').each(function() {
			var val = $(this).val() != '???' ? $(this).val() : 'unknown';
			$('table tr td.type.class_' + val).parent().show();
		});
	}

	$('#filter input.all').change(function() {
		var _checked = $(this).attr('checked');
		$('#filter input:not(.all)').attr('checked', _checked == 'checked');
		_filter_change();
	});

	$('#filter input:not(.all)').change(_filter_change);

	_filter_change();
});