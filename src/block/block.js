import edit from './edit'

const { __ } = wp.i18n
const { registerBlockType } = wp.blocks

registerBlockType( 'podlove-web-player/shortcode', {
	title: __( 'Podlove Web Player', 'podlove-web-player' ),
	icon: 'format-audio',
	category: 'embed',

  attributes: {
    post: {
      type: 'number'
    },

    publisher: {
      type: 'number'
    },

    data: {
      type: 'object'
    }
  },

	edit: edit,

	save() {
		return null
	},
} );
