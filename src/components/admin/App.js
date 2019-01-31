import React from 'react'

import Header from './Header'
import SocialNetworkSettings from '../../pages/admin/SocialNetworkSettings';

const App = () => (	
	<div>
		<Header title="Relative Sharer - Options" tag="h1" className="relative-sharer-header--main" desc="Welcome to the Relative Sharer plugin options page" />
		{/* // TODO: add a sidebar that holds links to the different settings pages available */}

		{/* // TODO: add react router and make this a page  */}
		<SocialNetworkSettings />
	</div>
)

export default App
