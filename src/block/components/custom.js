import './custom.scss'

const { Component } = wp.element
const { compose } = wp.compose
const { withSpokenMessages, Button, Dashicon, BaseControl } = wp.components
const { __ } = wp.i18n

const section = text => <BaseControl>
  <span className="section-label">{ text }</span>
</BaseControl>

const input = label => <div className="input">
  <BaseControl>
    <label className="input-label">{ label }</label>
    <input className="input-control" />
  </BaseControl>
</div>

class Custom extends Component {
  render() {
		const {
			setAttributes,
    } = this.props;

    return <div className="podlove-web-player--custom">
      { section(__( 'Episode', 'podlove-web-player' )) }
      { input(__( 'Title', 'podlove-web-player' )) }
      { input(__( 'Duration', 'podlove-web-player' )) }

      { section(__( 'Show', 'podlove-web-player' )) }
      { input(__( 'Title', 'podlove-web-player' )) }
      { input(__( 'Duration', 'podlove-web-player' )) }
    </div>
  }
}

export default compose([ withSpokenMessages ])(Custom)
