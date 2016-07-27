$(document).ready(function() {

	$(':input:first').focus();

	// autocomplete ajax request options for querying the webservice with a autocomplete request
	var options = {
		minLength: 3,
		source: function(request, response) {
			$.ajax({
				type: 'GET',
				url: document.URL.replace('index.php', '') + 'webservice.php',
				dataType: 'json',
				data: {
					requesttype: 'autocomplete',
					query: request.term
				},
				success: function(data) {
					response($.map(data['predictions'], function(el) {
						return {label: el.description, value: el.description}
					}));
				}
			});
		}
	};

	$('#search-box').autocomplete(options);

	// preserve bootstrap look while using jQuery UI's autocomplete widget
	$('span.ui-helper-hidden-accessible').remove();
	$('ul.ui-autocomplete').addClass('dropdown-menu');

	// ajax submit call for querying the webservice with a textsearch request
	$('form').on('submit', function(e) {
		e.preventDefault();

		$.ajax({
			type: 'GET',
			url: document.URL.replace('index.php', '') + 'webservice.php',
			dataType: 'json',
			data: {
				requesttype: 'textsearch',
				query: $('#search-box').val()
			},
			success: function(data, status, jqXHR) {
				$('#json-data').remove();
				$('#response').append('<div id="json-data">' + jqXHR.responseText + '</div>');
			}
		})
	});

});