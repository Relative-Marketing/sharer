import React from 'react'
import classnames from 'classnames'

const Header = props => {
	const {tag:Tag, title, className, desc} = props;

	// For more than one class as a prop use object syntax
	const classes = classnames(className, 'relative-sharer-header')

  return (
	<div className={classes}>
	  <Tag>{title}</Tag>
		{!! desc && <p>{desc}</p>}
	</div>
  )
}

export default Header
