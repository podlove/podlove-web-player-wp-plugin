import './inspector.scss'
import { keys } from 'lodash'

const { Component } = wp.element
const { compose } = wp.compose
const { withSpokenMessages, SelectControl, PanelBody, PanelRow } = wp.components
const { InspectorControls } = wp.blockEditor
const { __ } = wp.i18n

class Shows extends Component {
  constructor(props) {
		super(...arguments);
    this.props = props;
    this.state = {
      shows: []
    }
  }

  async componentDidMount() {
    const shows = await fetch(window.PODLOVE_WEB_PLAYER.api.shows).then(result => result.json())
    console.log(shows)
    this.setState({
      shows: shows || []
    })
  }

  render() {
		const {
      setAttributes,
      attributes
    } = this.props

    const { shows } = this.state

    if (shows.concat.length === 0) {
      return null
    }

    const select = show => console.log(show) || setAttributes({ show })
    const options = [{ id: null, name: __('Select', 'podlove-web-player') }]
      .concat(shows || [])
      .map(({ slug, name }) => ({ value: slug, label: name }))

    return <InspectorControls>
    <PanelBody title={__('Shows', 'podlove-web-player')}>
      <PanelRow>
        <SelectControl
          value={attributes.show}
          options={options}
          onChange={select}
        />
      </PanelRow>
    </PanelBody>
  </InspectorControls>
  }
}

export default compose([
	withSpokenMessages
])(Shows)
