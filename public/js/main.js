(function($) {
	$(document).ready(function(){
		$(".toggle").click(function() {
			var toToggle = $(this).parent().next();
			if (toToggle.is(":visible")) {
				toToggle.slideUp();
			}
			else {
				toToggle.slideDown();
			}
			return false;
		});
	});
})(jQuery);
