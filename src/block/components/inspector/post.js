import { isUndefined, pickBy } from 'lodash'

const { Component } = wp.element
const { compose } = wp.compose
const { withSelect } = wp.data
const { withSpokenMessages, SelectControl, PanelBody, PanelRow } = wp.components
const { InspectorControls } = wp.blockEditor
const { __ } = wp.i18n

class Post extends Component {
  render() {
    const { posts, setAttributes, attributes } = this.props

    const select = post => setAttributes({ post })
    const options = [{ id: null, title: { label: __('Select', 'podlove-web-player') } }]
      .concat(posts || [])
      .map(({ id, title }) => ({ value: id, label: title.rendered }))

    return <InspectorControls>
        <PanelBody title={__('Post', 'podlove-web-player')}>
          <PanelRow>
            <SelectControl
              value={attributes.post}
              options={options}
              onChange={select}
            />
          </PanelRow>
        </PanelBody>
      </InspectorControls>
  }
}

export default compose([
  withSelect(select => {
    const { getEntityRecords } = select('core')

    return {
      posts: getEntityRecords('postType', 'post', {
        per_page: -1
      }),
    }
  }),
  withSpokenMessages,
])(Post)
