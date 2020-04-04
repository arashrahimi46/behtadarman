jQuery(window).on('elementor:init', function () {

	lozad('.lozad', {
		load: function (el) {
			el.src = el.dataset.src;
			el.onload = function () {
				el.classList.add('loaded')
			}
		}
	}).observe();

	var ethemeIconControlItem = elementor.modules.controls.BaseData.extend({
		onReady: function () {
			var self = this;

			setTimeout(function () {

				jQuery('.elementor-control-field .etheme-icons-wrap li input').on('click', function(event) {
					let checked = jQuery(this).data('name');
					let path = jQuery(this).data('path');
					jQuery('.etheme-icon-selected').prop('src',path + checked);
					self.setValue(checked);
				});
            }, 100);

		},
	});

	elementor.addControlView('etheme-icon-control', ethemeIconControlItem);
});
