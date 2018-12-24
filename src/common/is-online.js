//

import { check, watch } from 'is-offline';
import { connect } from 'react-redux'

class isOnline extends React.Component {
 
  static getDerivedStateFromProps(props, state) {   // Called when component: is created; receives new props
    
    if(typeof window !== 'undefined'){
      const { watch } = require('../../../client/is-online')

      return {
        onlineStatusWatcher: watch
      }
    }
  }
}

const mapStateToProps = state => {
  return {
    online: state.todos[0]
  }
}

const mapDispatchToProps = dispatch => {
  return {
    destroyTodo: (bool) =>
      dispatch({
        type: 'boilerplate/App/IS_ONLINE',
        online: !bool
      })
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(isOnline)
// Check if currently offline
//check().then(foobar);

// Setup a "watcher" to respond to all online/offline changes
//export default watch(onlineStatusChange);