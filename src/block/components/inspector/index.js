import Custom from './custom'
import Post from './post'
import Publisher from './publisher'
import Settings from './settings'
import { type } from '../utils'

const { compose } = wp.compose
const { Fragment, Component } = wp.element
const { withSpokenMessages } = wp.components

class Inspector extends Component {
  render() {
    const { attributes } = this.props

    switch (type(attributes)) {
      case 'post':
        return (
          <Fragment>
            <Settings {...this.props} />
            <Post {...this.props} />
          </Fragment>
        )

      case 'publisher':
        return (
          <Fragment>
            <Settings {...this.props} />
            <Publisher {...this.props} />
          </Fragment>
        )

      case 'custom':
        return (
          <Fragment>
            <Settings {...this.props} />
            <Custom {...this.props} />
          </Fragment>
        )

      default:
        return <Fragment />
    }
  }
}

export default compose([withSpokenMessages])(Inspector)
