import React from 'react';
import { Tree } from 'antd';

import { FormattedMessage } from 'react-intl';
import messages from './messages';


const { TreeNode } = Tree;

const treeData = [
{
  title: '#leapgest social media hashtag',
  key: '0-0',
  children: [
    { title: 'Leap second year-to-year index', key: '0-0-0' },
    { title: 'Hashtagged keywords capture posts meanwhile', key: '0-0-1' },
    { title: 'Leaps of habits are captured, entangling measurables', key: '0-0-2' },
    { title: 'Posts fly or sink as weighs of measurable data bend lines', key: '0-0-3' },
  ],
}, {
  title: 'Streamline with social media',
  key: '0-1',
  children: [
    { title: 'Import and update data from social media', key: '0-0-1-0' },
    { title: 'Validate data and export checksums to blockchain', key: '0-0-1-1' },
  ],
}, {
  title: 'Text parser component',
  key: '0-2',
  children: [
    { title: 'Flexible: integrates custom parser schemas (in JSON)', key: '0-2-0' },
    { title: 'Will learn when to suggest guided input templates (in-browser)', key: '0-2-1' },
    { title: 'Inserting multiple rows of data faster and with broader overview', key: '0-2-2' },
    { title: 'Can prototype reusable cases in common spaces of social media', key: '0-2-3' },
  ],
}, {
  title: 'Built on Node.js with "react-boilerplate" base',
  key: '0-3',
  children: [
    { title: 'GitHub repository', key: '0-3-0' },
    { title: 'GraphQL open-data complient(Apollo client)', key: '0-3-1' },
    { title: 'Firebase escape hull from backend server hackers (planned)', key: '0-3-2' },
    { title: 'Bootstrap interface desgn components', key: '0-3-3' },
    { title: 'Antd Design React components', key: '0-3-4' },
    { title: 'UnCSS to reduce production load', key: '0-3-4' },
  ],
}];


class Demo extends React.Component {
  state = {
    expandedKeys: ['0-0', '0-1', '0-2', '0-3'],
    autoExpandParent: true,
    checkedKeys: ['0-0'],
    selectedKeys: [],
  }

  onExpand = (expandedKeys) => {
    console.log('onExpand', expandedKeys);
    // if not set autoExpandParent to false, if children expanded, parent can not collapse.
    // or, you can remove all expanded children keys.
    this.setState({
      expandedKeys,
      autoExpandParent: false,
    });
  }

  onCheck = (checkedKeys) => {
    console.log('onCheck', checkedKeys);
    this.setState({ checkedKeys });
  }

  onSelect = (selectedKeys, info) => {
    console.log('onSelect', info);
    this.setState({ selectedKeys });
  }

  renderTreeNodes = data => data.map((item) => {
    if (item.children) {
      return (
        <TreeNode title={item.title} key={item.key} dataRef={item}>
          {this.renderTreeNodes(item.children)}
        </TreeNode>
      );
    }
    return <TreeNode {...item} />;
  })

  render() {
    return (
    	<div>
    		<FormattedMessage {...messages.Introduction} />
	      <Tree
	        checkable
	        onExpand={this.onExpand}
	        expandedKeys={this.state.expandedKeys}
	        autoExpandParent={this.state.autoExpandParent}
	        onCheck={this.onCheck}
	        checkedKeys={this.state.checkedKeys}
	        onSelect={this.onSelect}
	        selectedKeys={this.state.selectedKeys}
	      >
	        {this.renderTreeNodes(treeData)}
	      </Tree>
	    </div>
    );
  }
}


export default Demo;