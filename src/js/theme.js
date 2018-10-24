(function($){

	var openSearch = function(){
		$('.search form img').on('click', function() {
			$('.search').toggleClass('active');
		});
	};

	$(document).ready(function(){
		openSearch();
	});

})(jQuery);