import './source.scss'
import { type } from './utils'

const { Component, Fragment } = wp.element
const { compose } = wp.compose
const { withSpokenMessages, Button, Dashicon } = wp.components
const { __ } = wp.i18n

class Source extends Component {
  render() {
		const {
      attributes,
			setAttributes,
    } = this.props;

    const select = data => () => setAttributes(data)

    if (type(attributes)) {
      return <Fragment />
    }

    return <div className="podlove-web-player--source">
      <Button onClick={select({ post: null })} className="source-select">
        <Dashicon icon="media-document" size="40"/>
        <span className="source-name">{ __( 'Post', 'podlove-web-player' ) }</span>
      </Button>
      <Button onClick={select({ publisher: null })} className="source-select">
        <Dashicon icon="media-audio" size="40"/>
        <span className="source-name">{ __( 'Publisher', 'podlove-web-player' ) }</span>
      </Button>
      <Button onClick={select({ episode: {} })} className="source-select">
        <Dashicon icon="media-code" size="40"/>
        <span className="source-name">{ __( 'Custom', 'podlove-web-player' ) }</span>
      </Button>
    </div>
  }
}

export default compose([ withSpokenMessages ])(Source)
