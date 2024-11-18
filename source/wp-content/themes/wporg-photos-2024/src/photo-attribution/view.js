/**
 * WordPress dependencies
 */
import { getContext, getElement, store, withScope } from '@wordpress/interactivity';

// List of tabs in order, used for arrow key navigation.
const TABLIST = [ 'rtf', 'html', 'txt' ];

const { state } = store( 'wporg/photos/photo-attribution', {
	state: {
		get isCurrentTab() {
			const { tab } = getContext();
			const { attributes } = getElement();
			return tab === attributes[ 'data-tab' ];
		},
		get buttonLabel() {
			const { i18n } = getContext();
			return state.copied ? i18n.copySuccess : i18n.copyDefault;
		},
		get tabIndex() {
			const { tab } = getContext();
			const { attributes } = getElement();
			return tab === attributes[ 'data-tab' ] ? 0 : -1;
		},
		isLoaded: true,
		copied: false,
	},
	actions: {
		openTab: () => {
			const context = getContext();
			const { attributes } = getElement();
			context.tab = attributes[ 'data-tab' ];
			state.copied = false;
		},
		onKeyDown( event ) {
			const context = getContext();
			const { ref } = getElement();
			// Cycle through the tab list with arrow keys.
			const current = TABLIST.indexOf( context.tab );
			const max = TABLIST.length;
			if ( 'ArrowLeft' === event.code ) {
				const i = current === 0 ? max - 1 : current - 1;
				context.tab = TABLIST[ i ];
			} else if ( 'ArrowRight' === event.code ) {
				const i = current === max - 1 ? 0 : current + 1;
				context.tab = TABLIST[ i ];
			} else {
				return;
			}
			// Move the tab focus to the just-selected button.
			const container = ref.closest( '.wp-block-wporg-photo-attribution' );
			const button = container.querySelector( `.wporg-photo-attribution__tab[data-tab="${ context.tab }"]` );
			if ( button ) {
				button.focus();
			}
		},
		copyText: () => {
			document.execCommand( 'copy' );
		},
	},
	callbacks: {
		copyListener: ( event ) => {
			event.preventDefault();
			const { tab } = getContext();
			const { ref } = getElement();
			const container = ref.closest( '.wp-block-wporg-photo-attribution' );

			// Get the active tab.
			const attribution = container.querySelector( '.wporg-photo-attribution__tabpanel:not([hidden])' );
			let content = attribution.innerHTML.trim();
			if ( 'html' === tab ) {
				// Decode HTML entities.
				const textarea = document.createElement( 'textarea' );
				textarea.innerHTML = content;
				content = textarea.value;
			}

			if ( 'rtf' === tab ) {
				event.clipboardData.setData( 'text/html', content );
			}

			event.clipboardData.setData( 'text/plain', content );

			state.copied = true;
			setTimeout(
				withScope( () => ( state.copied = false ) ),
				10000
			);
		},
	},
} );
