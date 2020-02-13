import Edit from './components/edit';

const { __ } = wp.i18n
const { registerBlockType } = wp.blocks

registerBlockType('podlove-web-player/shortcode', {
	title: __( 'Podlove Web Player', 'podlove-web-player' ),
	icon: 'format-audio',
	category: 'embed',

  attributes: {
    post: {
      type: 'string'
    },

    publisher: {
      type: 'string'
    },

    data: {
      type: 'object'
    },

    config: {
      type: 'string',
      default: 'default'
    },

    theme: {
      type: 'string',
      default: 'default'
    },

    theme: {
      type: 'string',
      default: 'default'
    }
  },

	edit(props) {
    return <div className={props.className}>
      <Edit { ...props }/>
    </div>
  },

	save() {
		return null
	},
});
