import edit from './edit'

const { __ } = wp.i18n
const { registerBlockType } = wp.blocks

registerBlockType( 'podlove-web-player/shortcode', {
	title: __( 'Podlove Web Player', 'podlove-web-player' ),
	icon: 'format-audio',
	category: 'embed',

  attributes: {
    source: {
      type: 'string',
      source: 'attribute'
    },

    post: {
      type: 'number',
      source: 'attribute'
    },

    publisher: {
      type: 'number',
      source: 'attribute'
    },

    data: {
      type: 'object',
      source: 'attribute'
    }
  },

	edit: edit,

	save( props ) {
		return (
			<p className={ props.className }>Hello World! — from the frontend (02 Basic Block ESNext).</p>
		);
	},
} );
