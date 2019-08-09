$(document).ready(function() {

	// A la demande de l'expert accessibilitÃ©, suppression des balises dl, dt et dd dans les panneaux d'aide
	var helpPanels = $('.step-page div.help-panel');
	helpPanels.each(function() {
		var helpText = $(this).find('dd').html();
		$(this).find('dl').replaceWith(helpText);
	});

	// A la demande de l'expert accessibilitÃ©, suppression des span.asterisk Ã  la fin des labels
	$(".step-page div.field-container label.control-label > span.asterisk:last-child").remove();
});