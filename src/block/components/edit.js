import { isUndefined } from 'lodash'
import Source from './source'
import Custom from './custom'
import Post from './post'
import Publisher from './publisher'

const { compose } = wp.compose
const { Fragment, Component } = wp.element
const { withSpokenMessages } = wp.components;

class Edit extends Component {
  render() {
		const {
			isSelected,
			attributes,
			setAttributes,
			postsList,
			className,
    } = this.props;

    let content
    console.log(attributes)
    switch (true) {
      case isUndefined(attributes.post) === false:
        content = <Post {...this.props} />
      break
      case isUndefined(attributes.publisher) === false:
        content = <Publisher {...this.props} />
      break
      case isUndefined(attributes.data) === false:
        content = <Custom {...this.props} />
      break
      default:
        content = <Source {...this.props} />
    }

    return <Fragment>
      { content }
    </Fragment>
  }
}

export default compose([ withSpokenMessages ])(Edit)
