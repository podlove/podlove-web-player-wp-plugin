const { Component } = wp.element
const { compose } = wp.compose
const { withSelect } = wp.data
const { withSpokenMessages, SelectControl, PanelBody, PanelRow } = wp.components
const { InspectorControls } = wp.blockEditor
const { __ } = wp.i18n

class Publisher extends Component {
  render() {
    const { episodes, setAttributes, attributes } = this.props

    const select = publisher => setAttributes({ publisher })
    const options = [{ id: null, title: { rendered: __('Select', 'podlove-web-player') } }]
      .concat(episodes || [])
      .map(({ id, title }) => ({ value: id, label: title.rendered }))

    return <InspectorControls>
        <PanelBody title={__('Publisher', 'podlove-web-player')}>
          <PanelRow>
            <SelectControl
              value={attributes.publisher}
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
      episodes: getEntityRecords('postType', 'podcast', {
        per_page: -1,
      }),
    }
  }),
  withSpokenMessages,
])(Publisher)
