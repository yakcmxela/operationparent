(function($){

	var bounds, infoWindow, map, marker, markers, windowScrollPosition = -1;

	// IE Mobile fix
	if ( "-ms-user-select" in document.documentElement.style && navigator.userAgent.match(/IEMobile\/10\.0/) ) {
		var a = document.createElement("style");
		a.appendChild(document.createTextNode("@-ms-viewport{width:auto!important}"));
		document.getElementsByTagName("head")[0].appendChild(a);
	}

	// Add dashboard link for logged-in admin users
	var addDashboardLinkIfNeeded = function(){
		if( 1 == MSWObject.show_dashboard_link ){
			$( '<a href="' + MSWObject.home_url + '/wp-admin" id="msw-dashboard" title="Dashboard" rel="nofollow" target="_blank"><img src="' + MSWObject.site_url + '/wp-admin/images/w-logo-white.png" height="24" width="24" alt="Dashboard"></a>' ).appendTo( 'body' );
		}
	}; 

	var createGoogleMap = function(){
		if( MSWObject.google_map_data ){
			var markers = JSON.parse( MSWObject.google_map_data );
			var singleMarker = [];
			var locationId;
			var markerMatchesLocationId = function(marker){
				return marker.location_id == locationId;
			};

			if( document.getElementById( 'gmap' ) ){
				//Check that markers exist
				if( markers.length == 0 ){
					return;
				}
				
				//Check if single-location or multiple locations	
				if(document.querySelector('[data-gmapSingleLocation]') ){
					locationId = document.querySelector('[data-gmapSingleLocation]').getAttribute('data-gmapSingleLocation');
					singleMarker.push(markers.find(markerMatchesLocationId));
					createGoogleMapInit( singleMarker );
				} else {
					createGoogleMapInit( markers );
				}
			}
		}
	};

	var createGoogleMapInit = function( markers ){
		var maxZoom = document.getElementById('gmap').getAttribute('data-maxZoom');
		var minZoom = document.getElementById('gmap').getAttribute('data-minZoom');
		var styles = document.getElementById('gmap').getAttribute('data-styles');
		map = new google.maps.Map(document.getElementById('gmap'), {
			center: {lat: -38.216996, lng: -85.575},
			disableDoubleClickZoom: true,
			draggable: false,
			mapTypeControl: false,
			scrollwheel: false,
			styles: styles || [],
			minZoom: minZoom || 1,
			maxZoom: maxZoom || 18
		});
		bounds = new google.maps.LatLngBounds();
		infoWindow = new google.maps.InfoWindow();
		var infoWindowContent = [];
		for( var i = 0; i < markers.length; i++ ){
			var position = new google.maps.LatLng( markers[i].lat, markers[i].lng );
			bounds.extend( position );
			marker = new google.maps.Marker({
				icon: markers[i].marker,
				position: position,
				map: map,
			});
			map.fitBounds( bounds );

			var locationContent = '<a href="';
				locationContent += markers[i].location_url;
				locationContent += '" class="button">';
				locationContent += markers[i].location_name;
				locationContent += '</a>';
			infoWindowContent.push( locationContent );

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					infoWindow.setContent(infoWindowContent[ i ]);
					infoWindow.open(map, marker);
				};
			})(marker, i));
			google.maps.event.addListener(marker, 'closeclick', (function(marker, i) {
				return function() {
					map.fitBounds( bounds );
				};
			})(marker, i));
		}
		google.maps.event.addDomListener(window, 'resize', function() {
			map.fitBounds( bounds );
		});
	};

	// Toggle "scrolled" body class when site is scrolled or at the top
	var isSiteScrolled = function(){
		var top = window.pageYOffset;
		if( windowScrollPosition != top ){
			windowScrollPosition = top;
			if( 0 < top ){
				$( 'body' ).addClass( 'scrolled' );
			} else {
				$( 'body' ).removeClass( 'scrolled' );
			}
		}
	};

	// Add nav menu functionality if using one of the built-in menus
	var shouldConstructNavMenu = function(){
		if( !$( 'body' ).hasClass( 'nav-custom' ) ){

			// Add sub-menu functionality when clicked.
			// If a link is just a hash, toggle it on click.
			$( 'body' ).on( 'click', '.menu-item-has-children', function(ev){
				var isTrigger = /#/.test(this.href);
				var thisParent = $( this ).parentsUntil('ul');
				$( this ).toggleClass('menu-item-open')
				$( this ).siblings( '.menu-item-has-children ').not( $(this) ).removeClass('menu-item-open');
				if( isTrigger ){
					ev.preventDefault();
					$( thisParent ).toggleClass( 'menu-item-open' );
				}
				thisParent.siblings( '.menu-item-has-children' ).not( $( thisParent ) ).removeClass( 'menu-item-open' );
			});

			// Click arrow to open sub-menu
			$( 'body' ).on( 'click', '.menu-item-has-children', function(ev){
				var thisParent = $(this).parentsUntil('ul');
				$( thisParent ).toggleClass( 'menu-item-open' );
				thisParent.siblings( '.menu-item-has-children' ).not( $( thisParent ) ).removeClass( 'menu-item-open' );
			});

			// If using the built-in offcanvas nav, add the overlay at the end of the document
			if( $( 'body' ).hasClass( 'nav-ocn' ) ){
				$( '<a href="javascript:void(0)" class="nav-toggle" id="ocn-overlay"></a>' ).appendTo( 'body' );
			}

			// Anything with the .nav-toggle class should toggle the menu class.
			$( 'body' ).on( 'click', '.nav-toggle', function(ev){
				ev.preventDefault();
				$( 'body' ).toggleClass( 'nav-open' );
			});
			$( '.wrapper' ).on( 'click', function() {
				$( 'body' ).removeClass( 'nav-open' );
			});
		}
	};
	
	
	var filterDropdown = function(){
		$('.filter-dropdown').on('click', function(e){
			e.stopPropagation();
			$(this).toggleClass('active');
			$('.filter-dropdown').not(this).removeClass('active');
		});

		$(document).on('click.closefilter',':not(.filter-dropdown)', function(){
			$('.filter-dropdown').removeClass('active');
		});
	};
	
	var popUpImg = function(){
		$('[data-action="popup-img"]').magnificPopup({
			type: 'image'
		});
	};
	
	var popUpGallery = function(){
		$('[data-action="popup-gallery"]').magnificPopup({
			delegate: 'a',
			type: 'image',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1]
			}
		});
	};

	$(document).ready(function(){
		shouldConstructNavMenu();
		isSiteScrolled();
		addDashboardLinkIfNeeded();
		filterDropdown();
		popUpImg();
		popUpGallery();

		optimizedScroll.add( isSiteScrolled );
	});

	$(window).load(function() {
		createGoogleMap();
	});
	
})(jQuery);