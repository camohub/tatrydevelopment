$(function() {

	$('body').removeClass('init-color');

	$('form.no-ajax, .btn.no-ajax').netteAjaxOff();

	$.nette.ext('name', {
		before: function () {

		},
		start: function()
		{
			$('#loader').css('display', 'block');
		},
		complete: function () {
			$('#loader').css('display', 'none');
		}
	});

	$.nette.init();

});