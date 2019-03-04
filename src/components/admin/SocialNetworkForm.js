import React, {Component} from 'react'
import Axios from 'axios';

class SocialNetworkForm extends Component {
	constructor(props) {
		super(props);

		// ({socialNetwork, activeProfile, activeShare, onUpdateField:updateField, onSubmit: updateSocialNetwork})
		this.submit = this.submit.bind(this);
		this.updateSocialNetwork = this.props.onSubmit;
		this.updateIconType = this.updateIconType.bind(this);

		this.ICON_TYPE_IMG = 'iconTypeImg';
		this.ICON_TYPE_FA = 'iconTypeFA'; 

		this.state = {
			iconType: this.props.socialNetwork.icon_type === 'fa' ? this.ICON_TYPE_FA : this.ICON_TYPE_IMG
		}
	}

	submit(e) {
		e.preventDefault();

		// Get all the inputs of the form, will return a node list
		const inputNodeList = e.target.querySelectorAll('input[type=text]');
		let updatedNetwork = {id: this.props.socialNetwork.id};
		
		// Populate the updatedNetwork object with the latest saved values
		Array
			.from(inputNodeList)
			.map((el) => updatedNetwork[el.name] = el.value )
		
		this.updateSocialNetwork(updatedNetwork, e.target.active_profile.checked, e.target.active_share.checked);
	}

	updateIconType(e) {
		//update-icon-type
		const {root, nonce} = wpApiSettings;
		// We are making requests that require the user to be logged in so set the header of each request
		// so wordpress knows that the user is allowed to make it.
		const nonceHeader = {
			headers: {'X-WP-Nonce': nonce}
		}

		const updatedIconData = {
			id: this.props.socialNetwork.id,
			type: 'img'
		}

		// Send the request to update the social network details
		Axios.post(`${root}relative-sharer/v1/update-icon-type`, updatedIconData, nonceHeader)
		.then(
			(res) => {
				console.log(this.props.socialNetwork.icon_type);
			}
		).catch(err => console.log(err))
		// TODO: Make a call to an endpoint to update the type of icons being used
		this.setState({iconType: e.target.id});
	}

	render() {
		return (
			<div className="relative-sharer-social-network-form">
				<form onSubmit={this.submit}>
					<div className="d-flex flex-column">
						<div className="social-network-form-field d-flex flex-column">
							<label htmlFor="nice_name">Nicename</label>
							<input type="text" name="nice_name" id="nice-name" defaultValue={this.props.socialNetwork.nice_name || ''} />
						</div>
						
						<div className="social-network-form-field d-flex flex-column">
							<label htmlFor="social-network-link">Profile Link</label>
							<p>The link to your page on the social network</p>
							<input type="text" name="social_network_link" id="profile-social-network-link" defaultValue={this.props.socialNetwork.social_network_link || ''}/>
						</div>

						<div className="social-network-form-field d-flex flex-column">
							<h3>Icon type to use</h3>
							<p>Choose what type of icon you'd like to use for display</p>
							<div className="social-network-form-radio d-flex flex-row">
								<div className="social-network-form-select-input">
									<input type="radio" name="icon_type" id={this.ICON_TYPE_FA} value="Font awesome" onChange={this.updateIconType} checked={this.state.iconType === this.ICON_TYPE_FA} />
									<label htmlFor={this.ICON_TYPE_FA}>Font awesome</label>
								</div>
								<div className="social-network-form-select-input">
									<input type="radio" name="icon_type" id={this.ICON_TYPE_IMG} value="Image" onChange={this.updateIconType} checked={this.state.iconType === this.ICON_TYPE_IMG} />
									<label htmlFor={this.ICON_TYPE_IMG}>Image</label>
								</div>
							</div>
						</div>
						
						{this.state.iconType === this.ICON_TYPE_FA && 
							<div className="social-network-form-field d-flex flex-column">
								<label htmlFor="faIcon">Font Awesome Brands Icon</label>
								<p>The name (e.g 'facebook') of the font awesome brand you would like to use see <a href="https://fontawesome.com/icons?d=gallery&s=brands" target="_BLANK">Font awesome brands</a></p>
								<input type="text" name="fa_icon" id="fa-icon" defaultValue={this.props.socialNetwork.fa_icon || ''} />
							</div>
						}
						{this.state.iconType === this.ICON_TYPE_IMG && 
							<div className="social-network-form-field d-flex flex-column">
								<label htmlFor="imgIcon">Icon image URL</label>
								<p>Add the URL of the image that you would like to use</p>
								<input type="text" name="img_icon" id="imgIcon" defaultValue={this.props.socialNetwork.img_icon || 'not set'} />
							</div>
						}
					</div>
					
					<div className="d-flex flex-row">
						<div className="social-network-form-field width-50">
							<label htmlFor="faIcon">Show in profile links</label>
							<p>Should we show this social network when displaying links to your social profile?</p>
							<input type="checkbox" name="active_profile" id="active_profile" defaultChecked={this.props.activeProfile} />
						</div>
						<div className="social-network-form-field width-50">
							<label htmlFor="faIcon">Show in share links</label>
							<p>Should we show this social network when giving the option to share a post?</p>
							<input type="checkbox" name="active_share" id="active_share" defaultChecked={this.props.activeShare} />
						</div>
					</div>
					{/* // TODO: give an indication to the user that the options are being saved when the user hits save */}
					<button type="submit" className="btn btn--submit">Save</button>
				</form>
			</div>
		)
	}
}

export default SocialNetworkForm
