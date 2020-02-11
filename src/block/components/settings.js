import './post.scss'
import { isUndefined, pickBy } from 'lodash'

const { Component } = wp.element
const { compose } = wp.compose
const { withSelect } = wp.data;
const { withSpokenMessages, SelectControl } = wp.components
const { __ } = wp.i18n

class Post extends Component {
  render() {
		const {
      posts,
      setAttributes,
      attributes,
    } = this.props

    const select = post => console.log({ post }) || setAttributes({ post })
    const options = [{ id: null, title: { label: null } }].concat(posts || []).map(({ id, title }) => ({ value: id, label: title.rendered }))

    return <div className="podlove-web-player--post">
      <SelectControl
        label={ __('Select Episode', 'podlove-web-player') }
        value={ attributes.post }
        options={ options }
        onChange={ select }
      />
    </div>
  }
}

export default compose([
	withSelect(select => {
		const { getEntityRecords } = select( 'core' );

		const postsListQuery = pickBy( {
			per_page: -1,
			exclude: [ select( 'core/editor' ).getCurrentPostId() ],
		}, ( value ) => ! isUndefined( value ) );

		return {
			posts: getEntityRecords( 'postType', 'post', postsListQuery ),
		};
	}),
	withSpokenMessages
])(Post)
