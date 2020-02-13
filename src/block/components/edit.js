import './edit.scss'

import { isUndefined } from 'lodash'
import Source from './source'
import Custom from './custom'
import Post from './post'
import Publisher from './publisher'
import Settings from './settings'

const { compose } = wp.compose
const { Fragment, Component } = wp.element
const { withSpokenMessages } = wp.components

class Edit extends Component {
  render() {
		const {
			attributes,
    } = this.props;

    let content

    switch (true) {
      case isUndefined(attributes.post) === false:
        content = <Fragment>
            <Settings {...this.props} />
            <Post {...this.props} />
          </Fragment>
      break
      case isUndefined(attributes.publisher) === false:
        content = <Fragment>
          <Settings {...this.props} />
          <Publisher {...this.props} />
        </Fragment>
      break
      case isUndefined(attributes.data) === false:
        content = <Fragment>
          <Settings {...this.props} />
          <Custom {...this.props} />
        </Fragment>
      break
      default:
        content = <Source {...this.props} />
    }

    return <Fragment>
      PLAYER!!!
      { content }
    </Fragment>
  }
}

export default compose([ withSpokenMessages ])(Edit)
