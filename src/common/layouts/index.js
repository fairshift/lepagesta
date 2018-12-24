import React from 'react';

import { WithSideMenu } from './SideMenu'


const LayoutSelect = (props) => {

	if( typeof props.SideMenu !== 'undefined' ){

		return (
			<WithSideMenu {...props}>
				{props.children}
			</WithSideMenu>
		)
	}

}

const Layout = (props) => {

	return (
		<LayoutSelect {...props} />
	)
}

export default Layout