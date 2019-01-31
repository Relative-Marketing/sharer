import React from 'react'

const SocialNetworkForm = ({socialNetwork, activeProfile, activeShare, onUpdateField:updateField, onSubmit: updateSocialNetwork}) => {
	function submit(e) {
		e.preventDefault();

		// Get all the inputs of the form, will return a node list
		const inputNodeList = e.target.querySelectorAll('input[type=text]');
		let updatedNetwork = {id: socialNetwork.id};
		
		// Populate the updatedNetwork object with the latest saved values
		Array
			.from(inputNodeList)
			.map((el) => updatedNetwork[el.name] = el.value )
		
		updateSocialNetwork(updatedNetwork, e.target.active_profile.checked, e.target.active_share.checked);
	}

	return (
		<div className="relative-sharer-social-network-form">
			<form onSubmit={submit}>
				<div className="d-flex flex-column">
					<div className="social-network-form-field d-flex flex-column">
						<label htmlFor="nice_name">Nicename</label>
						<input type="text" name="nice_name" id="nice-name" defaultValue={socialNetwork.nice_name || ''} />
					</div>
					
					<div className="social-network-form-field d-flex flex-column">
						<label htmlFor="social-network-link">Profile Link</label>
						<p>The link to your page on the social network</p>
						<input type="text" name="social_network_link" id="profile-social-network-link" defaultValue={socialNetwork.social_network_link || ''}/>
					</div>
					
					<div className="social-network-form-field d-flex flex-column">
						<label htmlFor="faIcon">Font Awesome Brands Icon</label>
						<p>The name (e.g 'facebook') of the font awesome brand you would like to use see <a href="https://fontawesome.com/icons?d=gallery&s=brands" target="_BLANK">Font awesome brands</a></p>
						<input type="text" name="fa_icon" id="fa-icon" defaultValue={socialNetwork.fa_icon || ''} />
					</div>
				</div>
				
				<div className="d-flex flex-row">
					<div className="social-network-form-field width-50">
						<label htmlFor="faIcon">Show in profile links</label>
						<p>Should we show this social network when displaying links to your social profile?</p>
						<input type="checkbox" name="active_profile" id="active_profile" defaultChecked={activeProfile} />
					</div>
					<div className="social-network-form-field width-50">
						<label htmlFor="faIcon">Show in share links</label>
						<p>Should we show this social network when giving the option to share a post?</p>
						<input type="checkbox" name="active_share" id="active_share" defaultChecked={activeShare} />
					</div>
				</div>
				{/* // TODO: give an indication to the user that the options are being saved when the user hits save */}
				<button type="submit" className="btn btn--submit">Save</button>
			</form>
		</div>
	)
}

export default SocialNetworkForm
