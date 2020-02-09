import { isUndefined } from 'lodash'

import './style.scss'

const { __ } = wp.i18n

const source = (setAttributes) => {
  const select = data => () => setAttributes(data)

  return <div class="source-selector">
    <button class="data-source" onClick={select({ post: 1 })}>
      <span class="data-source-icon">
        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false">
          <path d="M4 4h7V2H4c-1.1 0-2 .9-2 2v7h2V4zm6 9l-4 5h12l-3-4-2.03 2.71L10 13zm7-4.5c0-.83-.67-1.5-1.5-1.5S14 7.67 14 8.5s.67 1.5 1.5 1.5S17 9.33 17 8.5zM20 2h-7v2h7v7h2V4c0-1.1-.9-2-2-2zm0 18h-7v2h7c1.1 0 2-.9 2-2v-7h-2v7zM4 13H2v7c0 1.1.9 2 2 2h7v-2H4v-7z"></path>
          <path d="M0 0h24v24H0z" fill="none"></path>
        </svg>
      </span>
      <span class="data-source-label">{ __( 'Post', 'podlove-web-player' ) }</span>
    </button>
    <button class="data-source" onClick={select({ publisher: null })}>
      <span class="data-source-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false">
          <path d="M0,0h24v24H0V0z" fill="none"></path>
          <path d="m12 3l0.01 10.55c-0.59-0.34-1.27-0.55-2-0.55-2.22 0-4.01 1.79-4.01 4s1.79 4 4.01 4 3.99-1.79 3.99-4v-10h4v-4h-6zm-1.99 16c-1.1 0-2-0.9-2-2s0.9-2 2-2 2 0.9 2 2-0.9 2-2 2z"></path>
        </svg>
      </span>
      <span class="data-source-label">{ __( 'Publisher', 'podlove-web-player' ) }</span>
    </button>
    <button class="data-source" onClick={select({ data: {} })}>
      <span class="data-source-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false">
          <path d="M0,0h24v24H0V0z" fill="none"></path><path d="M12,2l-5.5,9h11L12,2z M12,5.84L13.93,9h-3.87L12,5.84z"></path>
          <path d="m17.5 13c-2.49 0-4.5 2.01-4.5 4.5s2.01 4.5 4.5 4.5 4.5-2.01 4.5-4.5-2.01-4.5-4.5-4.5zm0 7c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path>
          <path d="m3 21.5h8v-8h-8v8zm2-6h4v4h-4v-4z"></path>
        </svg>
      </span>
      <span class="data-source-label">{ __( 'Custom', 'podlove-web-player' ) }</span>
    </button>
  </div>
}

const post = (attribute, setAttributes) => {
  return <p>POST</p>
}

const publisher = (attribute, setAttributes) => {
  return <p>Publisher</p>
}

const custom = (attribute, setAttributes) => {
  return <p>Custom</p>
}

export default ({ attributes, setAttributes }) => {
  console.log(attributes)
  let edit

  switch (true) {
    case isUndefined(attributes.post) === false:
      edit = post(attributes.post, setAttributes)
    break
    case isUndefined(attributes.publisher) === false:
      edit = publisher(attributes.publisher, setAttributes)
    break
    case isUndefined(attributes.data) === false:
      edit = custom(attributes.data, setAttributes)
    break
    default:
      edit = source(setAttributes)
  }


  return (
    <div class="podlove-web-player-block">
      <div class="block-wrapper">
        { edit }
      </div>
      <div class="block-footer">
        <span class="brand">{ __( 'Podlove Web Player', 'podlove-web-player' ) }</span>
      </div>
    </div>
  );
}
