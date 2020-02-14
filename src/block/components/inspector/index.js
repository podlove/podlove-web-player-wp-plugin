import { isUndefined } from 'lodash'
import Custom from './custom'
import Post from './post'
import Publisher from './publisher'
import Settings from './settings'

const { compose } = wp.compose
const { Fragment, Component } = wp.element
const { withSpokenMessages } = wp.components

class Inspector extends Component {
  render() {
		const {
			attributes,
    } = this.props;

    if (!isUndefined(attributes.post)) {
      return <Fragment>
        <Settings {...this.props} />
        <Post {...this.props} />
      </Fragment>
    }

    if (!isUndefined(attributes.publisher)) {
      return <Fragment>
        <Settings {...this.props} />
        <Publisher {...this.props} />
      </Fragment>
    }

    if (!isUndefined(attributes.data)) {
      return <Fragment>
        <Settings {...this.props} />
        <Custom {...this.props} />
      </Fragment>
    }

    return <Fragment />
  }
}

export default compose([ withSpokenMessages ])(Inspector)
