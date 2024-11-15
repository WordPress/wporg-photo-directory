/**
 * WordPress dependencies
 */
import { getContext, getElement, store, withScope } from '@wordpress/interactivity';

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
