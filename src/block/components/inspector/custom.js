import './inspector.scss'
import { get, set, cloneDeep } from 'lodash'

const { Component } = wp.element
const { compose } = wp.compose
const {
  withSpokenMessages,
  Button,
  SelectControl,
  PanelBody,
  PanelRow,
  TextControl,
  Dashicon,
  TextareaControl,
} = wp.components
const { MediaUpload, MediaUploadCheck, InspectorControls } = wp.blockEditor
const { __ } = wp.i18n

class Custom extends Component {
  render() {
    const { attributes, setAttributes } = this.props

    const select = prop => value => {
      const episode = cloneDeep(attributes.episode)
      set(episode, prop, value)
      setAttributes({ episode })
    }

    const value = attr => get(attributes, ['episode'].concat(attr))
    const event = data => get(event, 'target.value', data)
    const url = media => media.url

    return (
      <InspectorControls>
        {/* Audio */}
        <PanelBody title={__('Audio', 'podlove-web-player')}>
          <PanelRow className="podlove-web-player--row">
            <TextControl
              label={__('Title', 'podlove-web-player')}
              value={value(['audio', 0, 'title'])}
              onChange={compose(select(['audio', 0, 'title']), event)}
            />
          </PanelRow>
          <PanelRow className="podlove-web-player--row">
            <TextControl
              label={__('File', 'podlove-web-player')}
              value={value(['audio', 0, 'url'])}
              onChange={compose(select(['audio', 0, 'url']), event)}
              type="url"
            />
            <MediaUploadCheck>
              <MediaUpload
                onSelect={compose(select(['audio', 0, 'url']), url)}
                allowedTypes={['audio']}
                render={({ open }) => (
                  <Button onClick={open} className="upload-button">
                    <Dashicon icon="upload" />
                  </Button>
                )}
              />
            </MediaUploadCheck>
          </PanelRow>
          <PanelRow className="podlove-web-player--row">
            <SelectControl
              label={__('Type', 'podlove-web-player')}
              value={value(['audio', 0, 'mimeType'])}
              options={[
                { value: null, label: null },
                { value: 'audio/mpeg', label: 'mp3' },
                { value: 'audio/mp4', label: 'mp4' },
                { value: 'audio/ogg', label: 'ogg' },
                { value: 'audio/opus', label: 'opus' },
                { value: 'audio/wav', label: 'wav' },
              ]}
              onChange={compose(select(['audio', 0, 'mimeType']), event)}
            />
          </PanelRow>
          <PanelRow className="podlove-web-player--row">
            <TextControl
              label={__('Size', 'podlove-web-player')}
              value={value(['audio', 0, 'size'])}
              onChange={compose(select(['audio', 0, 'size']), event)}
              type="number"
            />
          </PanelRow>
        </PanelBody>

        {/* Episode */}
        <PanelBody title={__('Episode', 'podlove-web-player')}>
          <PanelRow className="podlove-web-player--row">
            <TextControl
              label={__('Title', 'podlove-web-player')}
              value={value('title')}
              onChange={compose(select('title'), event)}
            />
          </PanelRow>
          <PanelRow className="podlove-web-player--row">
            <TextControl
              label={__('Subtitle', 'podlove-web-player')}
              value={value('subtitle')}
              onChange={compose(select('subtitle'), event)}
            />
          </PanelRow>
          <PanelRow className="podlove-web-player--row">
            <TextControl
              label={__('Duration', 'podlove-web-player')}
              value={value('duration')}
              onChange={compose(select('duration'), event)}
            />
          </PanelRow>
          <PanelRow className="podlove-web-player--row">
            <TextControl
              label={__('Poster', 'podlove-web-player')}
              value={value('poster')}
              onChange={compose(select('poster'), event)}
              type="url"
            />
            <MediaUploadCheck>
              <MediaUpload
                onSelect={compose(select('poster'), url)}
                allowedTypes={['image']}
                render={({ open }) => (
                  <Button onClick={open} className="upload-button">
                    <Dashicon icon="upload" />
                  </Button>
                )}
              />
            </MediaUploadCheck>
          </PanelRow>
          <PanelRow className="podlove-web-player--row">
            <TextareaControl
              label={__('Summary', 'podlove-web-player')}
              value={value('summary')}
              onChange={compose(select('summary'), event)}
            />
          </PanelRow>
        </PanelBody>

        {/* Show */}
        <PanelBody title={__('Show', 'podlove-web-player')}>
          <PanelRow className="podlove-web-player--row">
            <TextControl
              label={__('Title', 'podlove-web-player')}
              value={value(['show', 'title'])}
              onChange={compose(select(['show', 'title']), event)}
            />
          </PanelRow>
          <PanelRow className="podlove-web-player--row">
            <TextControl
              label={__('Subtitle', 'podlove-web-player')}
              value={value(['show', 'subtitle'])}
              onChange={compose(select(['show', 'subtitle']), event)}
            />
          </PanelRow>
          <PanelRow className="podlove-web-player--row">
            <TextControl
              label={__('Poster', 'podlove-web-player')}
              value={value(['show', 'poster'])}
              onChange={compose(select(['show', 'poster']), event)}
              type="url"
            />
            <MediaUploadCheck>
              <MediaUpload
                onSelect={compose(select(['show', 'poster']), url)}
                allowedTypes={['image']}
                render={({ open }) => (
                  <Button onClick={open} className="upload-button">
                    <Dashicon icon="upload" />
                  </Button>
                )}
              />
            </MediaUploadCheck>
          </PanelRow>
          <PanelRow className="podlove-web-player--row">
            <TextareaControl
              label={__('Summary', 'podlove-web-player')}
              value={value(['show', 'summary'])}
              onChange={compose(select(['show', 'summary']), event)}
            />
          </PanelRow>
        </PanelBody>
      </InspectorControls>
    )
  }
}

export default compose([withSpokenMessages])(Custom)
