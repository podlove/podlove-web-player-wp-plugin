import Inspector from './components/inspector'
import Source from './components/source'
import Player from './components/player'
import logo from './components/logo'

import { type } from './components/utils'

const { __ } = wp.i18n
const { registerBlockType } = wp.blocks

registerBlockType('podlove-web-player/shortcode', {
  title: __('Podlove Web Player', 'podlove-web-player'),
  description: __('HTML 5 Podcast Player', 'podlove-web-player'),
  icon: logo(),
  category: 'embed',

  attributes: {
    post: {
      type: 'string',
    },

    publisher: {
      type: 'string',
    },

    episode: {
      type: 'object',
    },

    config: {
      type: 'string',
      default: 'default',
    },

    theme: {
      type: 'string',
      default: 'default',
    },

    template: {
      type: 'string',
      default: 'default',
    },
  },

  edit: props => (
    <div className={props.className}>
      <Inspector {...props} />
      <Source {...props} />
      {type(props.attributes) && <Player {...props} />}
    </div>
  ),

  save() {
    return null
  },
})
