( function ( $ ) {
	function moveSearchMobile() {
		hideSearch();
		if ( $( window ).width() < 768 ) {
			$( '#search_modal .search-dropdown__content' )
				.detach()
				.appendTo( '#primary-menu-container' );
		} else {
			$( '#primary-menu-container .search-dropdown__content' )
				.detach()
				.prependTo( '#search_modal' );
		}
	}

	function hideSearch() {
		$( 'header .search-dropdown' ).attr( 'aria-hidden', true ).slideUp();
	}

	function setHeaderHeight() {
		let headerHeight = $( '.site-header' ).outerHeight();
		if ( $( 'body' ).hasClass( 'admin-bar' ) ) {
			headerHeight += $( '#wpadminbar' ).outerHeight();
		}
		const root = document.documentElement;
		root.style.setProperty( '--header-height', headerHeight + 'px' );
	}

	function setPageHeaderHeight() {
		const pageHeader = $( '.wp-block-cover.is-style-page-header' );
		if ( pageHeader.length ) {
			if ( $( window ).width() >= 600 ) {
				const headerGroupHeight = $(
					'.wp-block-group',
					pageHeader
				).outerHeight();
				pageHeader.css( 'paddingBottom', headerGroupHeight / 2 + 'px' );
			} else {
				pageHeader.css( 'paddingBottom', '' );
			}
		}
	}

	//set scrollto location just above the element when coming in from a link
	if ( document.location.hash ) {
		setTimeout( function () {
			window.scrollTo( window.scrollX, window.scrollY - 50 );
		}, 10 );
	}

	// Select all links with hashes
	$( 'a[href*="#"]' )
		// Remove links that don't actually link to anything
		.not( '[href="#"]' )
		.not( '[href="#0"]' )
		.not( '.monitor-station-link' )
		.click( function ( event ) {
			// On-page links
			if (
				location.pathname.replace( /^\//, '' ) ===
					this.pathname.replace( /^\//, '' ) &&
				location.hostname === this.hostname
			) {
				// Figure out element to scroll to
				let target = $( this.hash );
				target = target.length
					? target
					: $( '[name=' + this.hash.slice( 1 ) + ']' );
				// Does a scroll target exist?
				if ( target.length ) {
					// Only prevent default if animation is actually gonna happen
					event.preventDefault();
					$( 'html, body' ).animate(
						{
							scrollTop: target.offset().top - 50,
						},
						1000,
						function () {
							// Callback after animation
							// Must change focus!
							const $target = $( target );
							$target.focus();
							if ( $target.is( ':focus' ) ) {
								// Checking if the target was focused
								return false;
							}
							$target.attr( 'tabindex', '-1' ); // Adding tabindex for elements not focusable
							$target.focus(); // Set focus again
						}
					);
				}
			}
		} );

	$( function () {
		moveSearchMobile();
		setHeaderHeight();
		setPageHeaderHeight();
		$( window ).resize( function () {
			moveSearchMobile();
			setHeaderHeight();
			setPageHeaderHeight();
		} );

		$( '.info-popover' ).gpopover({width: 300});

		$( '.print-this-js' ).on( 'click', function ( e ) {
			e.preventDefault();
			window.print();
		} );

		$( '#primary-menu .search-button a' ).on( 'click', function ( e ) {
			e.preventDefault();
			const hidden = $( 'header .search-dropdown' ).attr( 'aria-hidden' );

			if ( hidden === 'false' ) {
				hideSearch();
			} else {
				const top = $( this ).offset().top + $( this ).outerHeight();
				const right =
					$( window ).width() -
					( $( this ).offset().left + $( this ).outerWidth() );
				$( 'header .search-dropdown' )
					.attr( 'aria-hidden', false )
					.css( { top, right } );
				$( 'header .search-dropdown' ).slideDown( 500, function () {
					$( this ).css( 'display', 'flex' );
				} );
			}
		} );
		//close search modal
		$( document ).on( 'click', '.search-dropdown__close', function () {
			hideSearch();
		} );
	} );
} )( jQuery );
