(function ($) {
	$(function () {

		$(document.getElementById('btn_new_cat_feature')).colorbox({
			srollable: false,
			innerWidth: 600,
			innerHeight: 450,
			href: SITE_URL + 'admin/products/features/create_cat_feature_ajax',
			onComplete: function () {
				$.colorbox.resize();
				var $form_categories = $('form#cat_feature');
				$form_categories.removeAttr('action');
				$form_categories.live('submit', function (e) {
					var form_data = $(this).serialize();
					$.ajax({
						url: SITE_URL + 'admin/products/features/create_cat_feature_ajax',
						type: "POST",
						data: form_data,
						success: function (obj) {

							if (obj.status == 'ok') {
								//succesfull db insert do this stuff
								var $select = $('select[name=cat_feature_id]');
								//append to dropdown the new option
								$select.append('<option value="' + obj.category_id + '" selected="selected">' + obj.name + '</option>');
								$select.trigger("liszt:updated");
								// TODO work this out? //uniform workaround
								//$(document.getElementById('blog-options-tab')).find('li').first().find('span').html(obj.title);
								//close the colorbox
								$.colorbox.close();
							} else {
								//no dice
								//append the message to the dom
								var $cboxLoadedContent = $(document.getElementById('cboxLoadedContent'));
								$cboxLoadedContent.html(obj.message + obj.form);
								$cboxLoadedContent.find('p').first().addClass('notification error').show();
							}
						}
					});
					return false;
					e.preventDefault();
				});

			}
		});
	});
})(jQuery);