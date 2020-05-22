import './source.scss'
import { type } from './utils'

const { Component, Fragment } = wp.element
const { withSelect } = wp.data
const { compose } = wp.compose
const { withSpokenMessages, Button, Dashicon } = wp.components
const { __ } = wp.i18n

class Source extends Component {
  render() {
    const { attributes, setAttributes, publisher, posts } = this.props

    const select = data => () => setAttributes(data)

    if (type(attributes)) {
      return <Fragment />
    }

    return (
      <div className="podlove-web-player--source">
        {posts && posts.length > 0 && (
          <Button onClick={select({ post: null })} className="source-select">
            <Dashicon icon="media-document" size="40" />
            <span className="source-name">{__('Post', 'podlove-web-player')}</span>
          </Button>
        )}
        {publisher && publisher.length > 0 && (
          <Button onClick={select({ publisher: null })} className="source-select">
            <Dashicon icon="media-audio" size="40" />
            <span className="source-name">{__('Publisher', 'podlove-web-player')}</span>
          </Button>
        )}
        <Button onClick={select({ episode: {} })} className="source-select">
          <Dashicon icon="media-code" size="40" />
          <span className="source-name">{__('Custom', 'podlove-web-player')}</span>
        </Button>
      </div>
    )
  }
}

export default compose([
  withSelect(select => {
    const { getEntityRecords } = select('core')

    return {
      publisher: getEntityRecords('postType', 'podcast', {
        per_page: 1,
      }),
      posts: getEntityRecords('postType', 'post', {
        per_page: 1,
      })
    }
  }),
  withSpokenMessages,
])(Source)
