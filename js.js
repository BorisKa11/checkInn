(function(test, $, undefined) {
    "use strict";
    test.store = test.store || {
        urlAjax: '/a/o',
        id: 0,
        lat: 0,
		lon: 0,
		mymap: '',
		name: '',
    };
    test.helper = test.helper || {

    };
    test.controller = test.controller || {
        check: function() {
            $.ajax({
				type: "POST",
				url: "/ajax.php?check",
				data: {inn: $('#inn').val()},
				dataType: 'JSON',
				success: function(data) {
					if(data.status == 1) {
                        $('#error').stop().text(data.ret).addClass('green').slideDown(150).delay(3500).slideUp(150);
					} else {
						$('#error').stop().text(data.ret).removeClass('green').slideDown(150).delay(3500).slideUp(150);
					}
				}
			});
		}
    };
	test.view = test.view || {
		init: function() {
			$('#form').on('submit', function(e) {
				e.preventDefault();
				test.controller.check();
			});
		}
	};
    $(document).ready(function() {
        test.view.init();
    });
})(window.test = window.test || {}, jQuery);
