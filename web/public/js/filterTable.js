/**
 * Created by Siebe on 1/06/2016.
 */
(function ($) {
	$(function () {
		var stripeTable = function ($table) { //stripe the table (jQuery selector)
			$table.find('tr').removeClass('striped').filter(':visible:even').addClass('striped');
		};

		var $table = $("table#users-table");
		$table.filterTable({
			minRows: 3,
			placeholder: "Zoek in deze tabel",
			callback: function (term, table) {
				stripeTable(table);
			}
		});

		stripeTable($table);
	});
})(jQuery);