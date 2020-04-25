import './inspector.scss'
import { keys } from 'lodash'

const { Component } = wp.element
const { compose } = wp.compose
const { withSpokenMessages, SelectControl, PanelBody, PanelRow } = wp.components
const { InspectorControls } = wp.blockEditor
const { __ } = wp.i18n

class Settings extends Component {
  constructor(props) {
		super(...arguments);
    this.props = props;
    this.state = {
      configs: [],
      themes: [],
      templates: []
    }
  }

  async componentDidMount() {
    const data = await fetch(window.PODLOVE_WEB_PLAYER.api.bootstrap).then(result => result.json())

    this.setState({
      configs: keys(data.configs).map(data => ({ value: data, label: data })),
      themes: keys(data.themes).map(data => ({ value: data, label: data })),
      templates: keys(data.templates).map(data => ({ value: data, label: data }))
    })
  }

  render() {
		const {
      setAttributes,
      attributes,
    } = this.props

    const select = prop => value => setAttributes({ [prop]: value })

    return <InspectorControls>
      <PanelBody title={ __( 'Settings', 'podlove-web-player' ) } >
        <PanelRow className="podlove-web-player--row">
          <SelectControl
            label={__('Config', 'podlove-web-player')}
            value={ attributes.config }
            options={ this.state.configs }
            onChange={ select('config') }
          />
        </PanelRow>
        <PanelRow className="podlove-web-player--row">
          <SelectControl
            label={__('Theme', 'podlove-web-player')}
            value={ attributes.theme }
            options={ this.state.themes }
            onChange={ select('theme') }
          />
        </PanelRow>
        <PanelRow className="podlove-web-player--row">
        <SelectControl
            label={__('Templates', 'podlove-web-player')}
            value={ attributes.template }
            options={ this.state.templates }
            onChange={ select('template') }
          />
        </PanelRow>
      </PanelBody>
    </InspectorControls>
  }
}

export default compose([
	withSpokenMessages
])(Settings)
