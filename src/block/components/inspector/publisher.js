const { Component } = wp.element
const { compose } = wp.compose
const { withSpokenMessages, Button, Dashicon } = wp.components
const { __ } = wp.i18n

class Publisher extends Component {
  render() {
		const {
			setAttributes,
    } = this.props;

    return <div className="podlove-web-player--publisher">
      Publisher!
    </div>
  }
}

export default compose([ withSpokenMessages ])(Publisher)
