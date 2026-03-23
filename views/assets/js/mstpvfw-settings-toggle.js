document.addEventListener('DOMContentLoaded', function () {
	if (typeof jQuery === 'undefined' || typeof mstpvfwSettings === 'undefined') {
		return;
	}

	(function ($) {
		function toggleCustomRows() {
			var mode = $('#mstpvfw_mode').val();

			if (mode === mstpvfwSettings.hidePriceAndShowText) {
				$('#mstpvfw_custom_text_row').show();
			} else {
				$('#mstpvfw_custom_text_row').hide();
			}

			if (mode === mstpvfwSettings.hidePriceAndShowFormRequest) {
				$('#mstpvfw_custom_form_text_row').show();
			} else {
				$('#mstpvfw_custom_form_text_row').hide();
			}
		}

		$(document).ready(function () {
			toggleCustomRows();
			$('#mstpvfw_mode').on('change', toggleCustomRows);
		});
	})(jQuery);
});