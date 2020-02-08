const { __ } = wp.i18n

const source = (attribute, setAttributes) => {
  const onChange = event => setAttributes({ source: event.target.value })

  return <div>
    <select value={ attribute } onChange={ onChange } placeholder={ __( 'Select Source' ) }>
      <option value="post">{ __( 'Post', 'podlove-web-player' ) }</option>
      <option value="publisher">{ __( 'Publisher', 'podlove-web-player' ) }</option>
      <option value="custom">{ __( 'Custom', 'podlove-web-player' ) }</option>
    </select>
  </div>
}


export default ({ attributes, setAttributes }) => {
  return (
    <div>
      <div>{ __( 'Data Source', 'podlove-web-player' ) }</div>
      { source(attributes.source, setAttributes) }
    </div>
  );
}
