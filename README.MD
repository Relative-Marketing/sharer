# Relative Social Sharer wordpress plugin

This plugin will:

- Register a widget that will display a list of icons that when clicked will navigate to the relevant social network.
- Provide the functionality to add social sharing links to your website.

## Data

### Context

Definition: Within this plugin a context is a way to indicate what data is needed and the use of that data.

There are two contexts available:

1. `profile` - If you need links to a users pages you would request the profile context.
2. `share` - If you are going to do something involving sharing a post/page.

### `registered_profiles` (array)

The `registered_profiles` array contains the currently registered social profiles.

Each key of the `registered_profiles` array should be a unique id for a social profile containing an array of data about that social profile.

For example:

```php
$registered_profiles = array(
	'facebook' => array(
		'name' => 'Facebook',
		'icon' => 'https://example.com/facebook-icon.png',
		// Font awesome icon
		'fa_icon' => 'facebook',
		'profile_link' => 'https://facebook.com/your-page-link',
		// Valid sprintf format - expect args = name, link, media, referrer 
		'share_link_format' => 'https://www.facebook.com/sharer/sharer.php?u=%2$s',
	),
	// ...other registered profiles
);
```

### `active_page_profiles` (array)

The `active_page_profiles` array will hold a list of profile id's that are active in the context of `page`. For example, there may be 6 registered profiles in the `registered_profiles` array but there may only be 3 profiles that are active in the context of page. The reasoning behind this is that the user may only want to show links to three of their social pages but still wants to give the option to share to all 6 registered profiles.

```php
$active_page_profiles = array('facebook', 'twitter');
```

### `active_share_profiles` (array)

The same as `active_page_profiles` except `active_share_profiles` contains id's of profiles active in the `share` context.

```php
$active_share_profiles = array('pinterest', 'linkedin', 'twitter');
```

### `display_settings` (array)
// TODO: Add display settings

## Functions

All functions exist in the `RelativeMarketing\RelativeSharer` namespace make sure you include the namespace before using any of the below functions.

```php
use RelativeMarketing\Sharer;
```

### Getting and checking data
---
#### `get_registered_profiles( string $id )` 
Returns all currently registered profiles.

`return array` - An array of registered profiles or an empty array if no profiles are registered

---
#### `is_profile_registered( string $id )` 
Checks if a profile with a given id is registered.

`return boolean` - True if the profile is registered or false

---
#### `is_profile_active( string $id, string $content )` 
Checks if a profile is active in a given context.

`return boolean` - True if the profile is active or false

---
#### `get_active_profile_ids(string $context)`

Returns all active profile ids for a given content

`return array` - If no active profiles exist an empty array will be returned

---
#### `get_active_profiles(string $context)`

Returns all active profiles for a given content

`return array` - If no active profiles exist an empty array will be returned

---

### Updating data

---
#### `register_profile(array $profile)`

Registers a profile. The profile array may contain the following:

- `id` - The id of the profile
- `name` - The nicename for the profile
- `link_to_profile` - The link to the users dedicated page on the social network you are adding
- `icon` - A custom icon for the social network
- `fa_icon` - The font awesome icon name to use for this profile e.g 'facebook'
- `share_link_format` - A valid sprintf string that will be used to contruct the share url. Arguments for this string will be name, link, media, referrer in that order.

`return boolean|WP_Error` - True if the profile was registered or WP_Error on failure

---

#### `update_profile($id, $data)`

Works in a similar fashion to `register_profile` but expects the given `$id` to exists. `$data` should be any valid profile data you would like to update.

`return boolean|WP_Error` - True if the profile was updated or WP_Error on failure

---

#### `activate_profile($id, $context)`

Will activate the given profile in the given content

`return boolean|WP_Error` - `true` on activation (or if the profile is already active), WP_Error if the profile doesn't exist

---

#### `deactivate_profile($id, $context)`

Will deactivate the given profile in the given content

`return boolean|WP_Error` - `true` on deactivation (or if the profile is already deactive), WP_Error if the profile doesn't exist

---

### `register_and_activate_profile(array $profile, array $contexts)`

Will register and activate a given profile and activate it for the given contents

`return boolean|WP_Error` - true on success, error on failure

---

### `delete_profile($id)`

Will delete a profile from the `registered_profiles` array.

`return boolean|WP_Error` - true on sucess, error on failure.

---

### Outputting contexts

---

#### `get_page_share_icons()`

Will return HTML output for all `active_page_profiles`

#### `render_page_share_icons()`

A wrapper for `get_page_share_icons()` will echo instead of returning

---

#### `get_share_icons()`

Will return HTML output for `active_share_profiles`

---

#### `render_share_icons()`

A wrapper for `get_share_icons()` will echo instead of returning

---

@TODO: functions for widget, shortcode, block registration

@TODO: functions/docs for user options

@TODO: functions styling

@TODO: actions and filters docs