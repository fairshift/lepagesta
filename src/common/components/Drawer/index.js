import React from 'react';
import { FormattedMessage } from 'react-intl';
import { Drawer, Button, Radio } from 'antd';

class App extends React.Component {
  state = { visible: false, placement: 'left' };

  // Redux action manipulate drawer: showDrawer, onClose, onChange of placement
  showDrawer = () => {
    this.setState({
      visible: true,
    });
  };

  onClose = () => {
    this.setState({
      visible: false,
    });
  };

  onChange = (e) => {
    this.setState({
      placement: e.target.value,
    });
  }

  render() {
    return (
      <div>
        <Drawer
          title="Basic Drawer"
          placement={this.props.placement}
          closable={false}
          onClose={this.onClose}
          visible={this.props.visible}
        >
          {this.props.content}
        </Drawer>
      </div>
    );
  }
}

export default Drawer;