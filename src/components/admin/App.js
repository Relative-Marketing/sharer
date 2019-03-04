import React from 'react'
/**
 * HashRouter gets around the problem with the wordpress page url being a query string
 * 
 * see: https://wp-community-uk.slack.com/archives/C04DTD9AK/p1549036527252900
 */
import {HashRouter as Router, Route, Link} from "react-router-dom"

import Header from './Header'
import SocialNetworkSettings from '../../pages/admin/SocialNetworkSettings';
import WelcomePage from '../../pages/admin/Welcome';
import GlobalSettings from '../../pages/admin/GlobalSettings';

const App = () => (	
	<Router>
		<div>
			<Header title="Relative Sharer - Options" tag="h1" className="relative-sharer-header--main" desc="Welcome to the Relative Sharer plugin options page" />
			<div className="relative-sharer-main-container">

				<div className="relative-sharer-sidebar">
					<h3>Menu</h3>
					<ul>
						<li><Link to="/">Welcome</Link></li>
						<li><Link to="global-settings">Global Settings</Link></li>
						<li><Link to="social-network-settings">Social Network Settings</Link></li>
					</ul>
				</div>

				<Route exact path="/" component={WelcomePage} />
				<Route path="/social-network-settings" component={SocialNetworkSettings} />
				<Route path="/global-settings" component={GlobalSettings} />
			</div>
		</div>
	</Router>
)

export default App
