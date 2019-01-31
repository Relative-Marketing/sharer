import React from 'react'
import Header from './Header';

const Main = props => (
	<div className="relative-share-main-page-area">
	  <Header {...props} tag="h2" />
	  {props.children}
	</div>
)

export default Main
