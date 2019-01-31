import React, {Component, Fragment} from 'react'
import Axios from 'axios';

/**
 * Internal dependancies
 */
import Main from '../../../components/admin/Main';
import SocialNetworkForm from '../../../components/admin/SocialNetworkForm';

/**
 * Will display social network setting and allow the user to update the settings
 */
class SocialNetworkSettings extends Component {
	constructor(props) {
		super(props);

		const initialState = {
			socialNetworks: [],
			activeShareLinks: [],
			activeProfileLinks: [],

		}

		this.state = initialState;
		this.updateSocialNetwork = this.updateSocialNetwork.bind(this);
		
	}
	
	/**
	 * TODO: Change this page so that it just shows a list of social networks that will then allow the user to choose which social network they would like to update
	 * 
	 * Doing the above would mean that we only need to request a list of registered social
	 * networks initially then load the specific detail when it is selected
	 */
	componentWillMount() {
		// Load the details of the social networks that are available and add them to state
		Axios.get(wpApiSettings.root + 'relative-sharer/v1/get-sharing-data', { headers: {'X-WP-Nonce': wpApiSettings.nonce} }).then(
			(res) => {
				this.setState(res.data)
			}
		)
	}

	/**
	 * Will update the details for an individual social network and will also activate/deactivate for both the
	 * share and profile context.
	 * 
	 * TODO: the network requests that are made in this function need work. we should look at the options pages and look to seperate updating the detail of the social network from it's active status
	 * 
	 * @param {object} socialNetwork 
	 * @param {bool} activeProfileLinks 
	 * @param {bool} activeShareLinks 
	 */
  	updateSocialNetwork(socialNetwork, activeProfileLinks, activeShareLinks) {
		// here we ned to make a request to a wp endpoint that will update the social network information
		const {id} = socialNetwork;
		const {root, nonce} = wpApiSettings;
		// We are making requests that require the user to be logged in so set the header of each request
		// so wordpress knows that the user is allowed to make it.
		const nonceHeader = {
			headers: {'X-WP-Nonce': nonce}
		}

		const newNetworkData = {
			id,
			data: JSON.stringify(socialNetwork)
		}

		// Send the request to update the social network details
		Axios.post(`${root}relative-sharer/v1/update-social-network`, newNetworkData, nonceHeader)
		.then(
			(res) => {
				// once we've done that send the request to activate/deactive the social networks in context
				// TODO: We should handle whether or not the request actually needs to be send. The user may not have amended the visibility settings but we're still updating regardless
				const visibilityData = {
					id,
					activeInContext: {
						profile: activeProfileLinks,
						share: activeShareLinks
					}
				}

				Axios.post(`${root}relative-sharer/v1/set-network-visibility`, visibilityData, nonceHeader).then(res => {
					this.setState({activeProfileLinks, activeShareLinks});
				})
			}
		).catch(err => console.log(err))
	}

	render() {
		return (	
			<Fragment>
				<Main title="Registered Social Network Settings">
					{/* // TODO: When we are just showing one social network at a time we won't need to map anymore */}
					{ this.state.socialNetworks.map( (sN) => <SocialNetworkForm key={sN['id']} socialNetwork={sN} activeProfile={!! this.state.activeProfileLinks[sN['id']] || false} activeShare={!! this.state.activeShareLinks[sN['id']] || false} onSubmit={this.updateSocialNetwork} /> ) }
				</Main>
			</Fragment>
	  	)
	}  
}

export default SocialNetworkSettings
