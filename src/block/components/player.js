import './player.scss'
import { get, isEqual, debounce } from 'lodash'
import { type, loadScript } from './utils'
import logo from './logo'

const { Component, createRef } = wp.element
const { compose } = wp.compose
const { withSpokenMessages } = wp.components
const { __ } = wp.i18n

class Player extends Component {
  constructor(props) {
    super(...arguments)
    this.props = props
    this.playerRef = createRef()
    this.plugin = null
    this.type = null
    this.mountIframe = debounce(({ node, episode, config, template }) => {
      // clear existing dom
      while (node.firstChild) {
        node.removeChild(node.firstChild);
      }

      node.innerHtml = template
      window.podlovePlayer(node, episode, config)
    }, 600)
  }

  renderPlayer() {
    const { attributes } = this.props
    const node = this.playerRef.current
    const routes = window.PODLOVE.embed

    let episode
    const config = [routes.config, attributes.config, 'theme', attributes.theme].join('/')
    const template = get(this.plugin, ['templates', attributes.template], '')

    switch (this.type) {
      case 'post':
        episode = [routes.post, attributes.post].join('/')
        break;

      case 'custom':
        episode = attributes.data
        break;
    }

    this.mountIframe({ node, config, episode, template })
  }


  async componentDidMount() {
    const plugin = await fetch(window.PODLOVE.api.bootstrap).then(result => result.json())
    const base = get(get(plugin, 'settings.source.items'), get(plugin, 'settings.source.selected'))
    await loadScript(base + 'embed.js', 'podlovePlayer')

    this.type = type(this.props.attributes)
    this.plugin = plugin
    this.renderPlayer(this.props.attributes)
  }

  shouldComponentUpdate() {
    return !this.plugin
  }

  componentWillReceiveProps(props) {
    if (isEqual(props.attributes, this.props.attributes)) {
      return
    }

    this.props = props
    this.renderPlayer()
  }

  render() {
    return <div className="podlove-web-player--preview">
      <div className="logo">
        { logo(50) }
        <span class="title">{ __('Podlove Web Player', 'podlove-web-player') }</span>
      </div>
      <div className="player" ref={this.playerRef}></div>
    </div>
  }
}

export default compose([ withSpokenMessages ])(Player)
