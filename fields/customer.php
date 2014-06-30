<?php
/**
 * @package    FrameworkOnFramework
 * @subpackage form
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('_JEXEC') or die;

/**
 * Form Field class for the FOF framework
 * A user selection box / display field
 *
 * @package  FrameworkOnFramework
 * @since    2.0
 */

class FOFFormFieldCustomer extends FOFFormFieldUser
{
	/**
	 * Get the rendering of this field type for a repeatable (grid) display,
	 * e.g. in a view listing many item (typically a "browse" task)
	 *
	 * @since 2.0
	 *
	 * @return  string  The field HTML
	 */
	public function getRepeatable()
	{
		//dump($this->item->bg_customer_id, 'amihere');
		// Initialise
		$show_username 	= true;
		$show_email    	= true;
		$show_name     	= true;
		$show_id       	= true;
		$show_avatar   	= true;
		$show_link     	= false;
		$link_url      	= null;
		$avatar_method 	= 'gravatar';
		$avatar_size   	= 64;
		$class         	= '';
		$show_address  	= true;
		$map_link	   	= false;
		$show_phone		= false;

		// Get the user record
		$user = JFactory::getUser($this->value);

		// Get the field parameters
		if ($this->element['class'])
		{
			$class = ' class="' . (string) $this->element['class'] . '"';
		}

		if ($this->element['show_username'] == 'false')
		{
			$show_username = false;
		}

		if ($this->element['show_email'] == 'false')
		{
			$show_email = false;
		}

		if ($this->element['show_name'] == 'false')
		{
			$show_name = false;
		}

		if ($this->element['show_id'] == 'false')
		{
			$show_id = false;
		}

		if ($this->element['show_avatar'] == 'false')
		{
			$show_avatar = false;
		}

		if ($this->element['avatar_method'])
		{
			$avatar_method = strtolower($this->element['avatar_method']);
		}

		if ($this->element['avatar_size'])
		{
			$avatar_size = $this->element['avatar_size'];
		}

		if ($this->element['show_address'] == 'false')
		{
			$show_address = false;
		}

		if ($this->element['show_phone'] == 'true')
		{
			$show_phone = true;
		}

		if ($this->element['show_link'] == 'true')
		{
			$show_link = true;
		}

		if ($this->element['link_url'])
		{
			$link_url = $this->element['link_url'];
		}
		else
		{
			if (FOFPlatform::getInstance()->isBackend())
			{
				// If no link is defined in the back-end, assume the user edit
				// link in the User Manager component
				$link_url = 'index.php?option=com_users&task=user.edit&id=[USER:ID]';
			}
			else
			{
				// If no link is defined in the front-end, we can't create a
				// default link. Therefore, show no link.
				$show_link = false;
			}
		}

		// Post-process the link URL
		if ($show_link)
		{
			$replacements = array(
				'[USER:ID]'			 => $user->id,
				'[USER:USERNAME]'	 => $user->username,
				'[USER:EMAIL]'		 => $user->email,
				'[USER:NAME]'		 => $user->name,
				'[ITEM:ID]'			 => $this->item->id,
			);

			foreach ($replacements as $key => $value)
			{
				$link_url = str_replace($key, $value, $link_url);
			}
		}

		// Get the avatar image, if necessary
		if ($show_avatar)
		{
			$avatar_url = '';

			if ($avatar_method == 'plugin')
			{
				// Use the user plugins to get an avatar
				FOFPlatform::getInstance()->importPlugin('user');
				$jResponse = FOFPlatform::getInstance()->runPlugins('onUserAvatar', array($user, $avatar_size));

				if (!empty($jResponse))
				{
					foreach ($jResponse as $response)
					{
						if ($response)
						{
							$avatar_url = $response;
						}
					}
				}

				if (empty($avatar_url))
				{
					$show_avatar = false;
				}
			}
			else
			{
				// Fall back to the Gravatar method
				$md5 = md5($user->email);

				if (FOFPlatform::getInstance()->isCli())
				{
					$scheme = 'http';
				}
				else
				{
					$scheme = JURI::getInstance()->getScheme();
				}

				if ($scheme == 'http')
				{
					$avatar_url = 'http://www.gravatar.com/avatar/' . $md5 . '.jpg?s='
						. $avatar_size . '&d=mm';
				}
				else
				{
					$avatar_url = 'https://secure.gravatar.com/avatar/' . $md5 . '.jpg?s='
						. $avatar_size . '&d=mm';
				}
			}
		}

		
		if ($this->element['map_link'] == 'true')
		{
			$map_link = true;
		}

		if($show_address){
			 $gomap = $this->item->address . ', ' . $this->item->zipcode;
			 $isSmall = $map_link ? 'small' : 'delivery-list';
			 	$address = '<div class="' . $isSmall . '">'. $this->item->address;

			 	if($map_link)
			 	{
			 		$address .=' <a href="https://maps.google.com/?q&#61;'. $gomap. '" target="_blank">
                        			<span class="icon-location"></span> 
                        		</a>';
                }
					$address .= '</div>';
		}

		// Generate the HTML
		$html = '<div id="' . $this->id . '" ' . $class . '>';

		if ($show_avatar)
		{
			$html .= '<img src="' . $avatar_url . '" align="left" class="fof-usersfield-avatar" style="padding-right:10px;"/>';
		}

		if ($show_link)
		{
			$html .= '<a href="' . $link_url . '">';
		}

		if ($show_username)
		{
			$html .= '<span class="fof-usersfield-username">' . $user->username
				. '</span><br/>';
		}

		if ($show_id)
		{
			$html .= '<span class="fof-usersfield-id">' . $user->id
				. '</span><br/>';
		}

		if ($show_name)
		{
			$isMuted = $map_link ? '' : 'muted'; // if delivery-list
			$html .= '<span class="' . $isMuted . ' fof-usersfield-name">' . $user->name
				. '</span><br/>';
		}

		if ($show_link)
		{
			$html .= '</a>';
		}

		if ($show_email)
		{
			$html .= ' <span class="icon-mail"></span> <span class="fof-usersfield-email"><a href="mailto:' . $user->email
				. '" target="_top">' .  $user->email	.'</a></span><br/>';
		}

		if ($show_phone)
		{
			$html .= ' <span class="small pull-right fof-usersfield-phone">' . JHtmlTel::tel($this->item->phone, "US")	.'</span><br/>';
		}

		if($show_address)
		{
			$html .= $address;
		}

		$html .= '</div>';

		return $html;
	}
}
