<module>
	<data>
		<module_id>fevent</module_id>
		<product_id>phpfox</product_id>
		<is_core>0</is_core>
		<is_active>1</is_active>
		<is_menu>1</is_menu>
		<menu><![CDATA[a:2:{s:29:"fevent.admin_menu_add_category";a:1:{s:3:"url";a:2:{i:0;s:5:"fevent";i:1;s:3:"add";}}s:34:"fevent.admin_menu_manage_categories";a:1:{s:3:"url";a:1:{i:0;s:5:"fevent";}}}]]></menu>
		<phrase_var_name>module_fevent</phrase_var_name>
		<writable><![CDATA[a:1:{i:0;s:15:"file/pic/fevent/";}]]></writable>
	</data>
	<menus>
		<menu module_id="fevent" parent_var_name="" m_connection="main" var_name="menu_fevent" ordering="29" url_value="fevent" version_id="2.0.0alpha1" disallow_access="" module="fevent" />
		<menu module_id="fevent" parent_var_name="" m_connection="fevent.index" var_name="menu_create_new_fevent" ordering="62" url_value="fevent.add" version_id="2.0.0alpha4" disallow_access="" module="fevent" />
		<menu module_id="fevent" parent_var_name="" m_connection="mobile" var_name="menu_fevent_fevents_532c28d5412dd75bf975fb951c740a30" ordering="115" url_value="fevent" version_id="3.1.0rc1" disallow_access="" module="fevent" mobile_icon="small_fevents.png" />
	</menus>
	<settings>
		<setting group="time_stamps" module_id="fevent" is_hidden="0" type="string" var_name="fevent_view_time_stamp_profile" phrase_var_name="setting_fevent_view_time_stamp_profile" ordering="1" version_id="2.0.0alpha4">F j, Y</setting>
		<setting group="time_stamps" module_id="fevent" is_hidden="0" type="string" var_name="fevent_browse_time_stamp" phrase_var_name="setting_fevent_browse_time_stamp" ordering="2" version_id="2.0.0alpha4">l, F j</setting>
		<setting group="time_stamps" module_id="fevent" is_hidden="0" type="string" var_name="fevent_basic_information_time" phrase_var_name="setting_fevent_basic_information_time" ordering="3" version_id="2.0.5">l, F j, Y g:i a</setting>
		<setting group="time_stamps" module_id="fevent" is_hidden="0" type="string" var_name="fevent_basic_information_time_short" phrase_var_name="setting_fevent_basic_information_time_short" ordering="4" version_id="2.0.5">g:i a</setting>
		<setting group="cache" module_id="fevent" is_hidden="0" type="boolean" var_name="cache_fevents_per_user" phrase_var_name="setting_cache_fevents_per_user" ordering="1" version_id="3.6.0rc1">0</setting>
		<setting group="cache" module_id="fevent" is_hidden="0" type="integer" var_name="cache_upcoming_fevents_info" phrase_var_name="setting_cache_upcoming_fevents_info" ordering="2" version_id="3.6.0rc1">8</setting>
	</settings>
	<blocks>
		<block type_id="0" m_connection="fevent.view" module_id="fevent" component="info" location="4" is_active="1" ordering="3" disallow_access="" can_move="0">
			<title>fevent Information</title>
			<source_code />
			<source_parsed />
		</block>
		<block type_id="0" m_connection="fevent.view" module_id="fevent" component="rsvp" location="3" is_active="1" ordering="4" disallow_access="" can_move="0">
			<title></title>
			<source_code />
			<source_parsed />
		</block>
		<block type_id="0" m_connection="fevent.index" module_id="fevent" component="category" location="1" is_active="1" ordering="4" disallow_access="" can_move="0">
			<title></title>
			<source_code />
			<source_parsed />
		</block>
		<block type_id="0" m_connection="group.view" module_id="fevent" component="parent" location="0" is_active="1" ordering="1" disallow_access="" can_move="1">
			<title></title>
			<source_code />
			<source_parsed />
		</block>
		<block type_id="0" m_connection="fevent.view" module_id="fevent" component="map" location="1" is_active="1" ordering="5" disallow_access="" can_move="0">
			<title></title>
			<source_code />
			<source_parsed />
		</block>
		<block type_id="0" m_connection="fevent.view" module_id="fevent" component="image" location="1" is_active="1" ordering="5" disallow_access="" can_move="0">
			<title>fevent Photo</title>
			<source_code />
			<source_parsed />
		</block>
		<block type_id="0" m_connection="fevent.index" module_id="fevent" component="sponsored" location="3" is_active="1" ordering="3" disallow_access="" can_move="0">
			<title></title>
			<source_code />
			<source_parsed />
		</block>
		<block type_id="0" m_connection="fevent.view" module_id="fevent" component="attending" location="1" is_active="1" ordering="6" disallow_access="" can_move="0">
			<title>Attending</title>
			<source_code />
			<source_parsed />
		</block>
		<block type_id="0" m_connection="fevent.index" module_id="fevent" component="invite" location="3" is_active="1" ordering="2" disallow_access="" can_move="0">
			<title>fevent Invites</title>
			<source_code />
			<source_parsed />
		</block>
		<block type_id="0" m_connection="fevent.index" module_id="fevent" component="featured" location="3" is_active="1" ordering="5" disallow_access="" can_move="0">
			<title>Featured fevents</title>
			<source_code />
			<source_parsed />
		</block>
	</blocks>
	<hooks>
		<hook module_id="fevent" hook_type="controller" module="fevent" call_name="fevent.component_controller_index_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="controller" module="fevent" call_name="fevent.component_controller_admincp_index_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="controller" module="fevent" call_name="fevent.component_controller_admincp_add_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="controller" module="fevent" call_name="fevent.component_controller_view_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="controller" module="fevent" call_name="fevent.component_controller_add_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="controller" module="fevent" call_name="fevent.component_controller_group_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_image_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_category_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_parent_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_menu_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_info_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_profile_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_rsvp_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_list_clean" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_category_category__call" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_category_process__call" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process__call" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_fevent__call" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_callback__call" added="1240687633" version_id="2.0.0beta1" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_browse__call" added="1242299564" version_id="2.0.0beta2" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_filter_clean" added="1258389334" version_id="2.0.0rc8" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_add__end" added="1260366442" version_id="2.0.0rc11" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_sponsor__end" added="1274286148" version_id="2.0.5dev1" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_sponsored_clean" added="1274286148" version_id="2.0.5dev1" />
		<hook module_id="fevent" hook_type="template" module="fevent" call_name="fevent.template_default_controller_view_extra_info" added="1274286148" version_id="2.0.5dev1" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_callback_getnewsfeed_start" added="1286546859" version_id="2.0.7" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_delete__start" added="1298455495" version_id="2.0.8" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_delete__pre_unlink" added="1298455495" version_id="2.0.8" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_delete__pre_space_update" added="1298455495" version_id="2.0.8" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_delete__pre_deletes" added="1298455495" version_id="2.0.8" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_delete__end" added="1298455495" version_id="2.0.8" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_add__start" added="1298455786" version_id="2.0.8" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_update__start" added="1298455786" version_id="2.0.8" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_massemail__start" added="1298455786" version_id="2.0.8" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_massemail__end" added="1298455786" version_id="2.0.8" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_attending_clean" added="1319729453" version_id="3.0.0rc1" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_browse_clean" added="1319729453" version_id="3.0.0rc1" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_featured_clean" added="1319729453" version_id="3.0.0rc1" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_invite_clean" added="1319729453" version_id="3.0.0rc1" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_rsvp_entry_clean" added="1319729453" version_id="3.0.0rc1" />
		<hook module_id="fevent" hook_type="controller" module="fevent" call_name="fevent.component_controller_profile_clean" added="1319729453" version_id="3.0.0rc1" />
		<hook module_id="fevent" hook_type="controller" module="fevent" call_name="fevent.component_controller_index_set_filter_menu_1" added="1335951260" version_id="3.2.0" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.component_service_callback_getactivityfeed__1" added="1335951260" version_id="3.2.0" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_update__end" added="1335951260" version_id="3.2.0" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_deleteimage__end" added="1335951260" version_id="3.2.0" />
		<hook module_id="fevent" hook_type="service" module="fevent" call_name="fevent.service_process_approve__1" added="1335951260" version_id="3.2.0" />
		<hook module_id="fevent" hook_type="component" module="fevent" call_name="fevent.component_block_mini_clean" added="1372931660" version_id="3.6.0" />
		<hook module_id="fevent" hook_type="controller" module="fevent" call_name="fevent.component_controller_view_process_end" added="1395674818" version_id="3.7.6rc1" />
	</hooks>
	<components>
		<component module_id="fevent" component="menu" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
		<component module_id="fevent" component="image" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
		<component module_id="fevent" component="view" m_connection="fevent.view" module="fevent" is_controller="1" is_block="0" is_active="1" />
		<component module_id="fevent" component="index" m_connection="fevent.index" module="fevent" is_controller="1" is_block="0" is_active="1" />
		<component module_id="fevent" component="rsvp" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
		<component module_id="fevent" component="category" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
		<component module_id="fevent" component="profile" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
		<component module_id="fevent" component="info" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
		<component module_id="fevent" component="parent" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
		<component module_id="fevent" component="filter" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
		<component module_id="fevent" component="sponsored" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
		<component module_id="fevent" component="list" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
		<component module_id="fevent" component="attending" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
		<component module_id="fevent" component="map" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
		<component module_id="fevent" component="invite" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
		<component module_id="fevent" component="profile" m_connection="fevent.profile" module="fevent" is_controller="1" is_block="0" is_active="1" />
		<component module_id="fevent" component="featured" m_connection="" module="fevent" is_controller="0" is_block="1" is_active="1" />
	</components>
	<phrases>
		<phrase module_id="fevent" version_id="2.0.0alpha1" var_name="module_fevent" added="1232964578">fevents</phrase>
		<phrase module_id="fevent" version_id="2.0.0alpha1" var_name="menu_fevent" added="1232964592">fevents</phrase>
		<phrase module_id="fevent" version_id="2.0.0alpha4" var_name="user_setting_can_edit_own_fevent" added="1239708707">Can edit own fevent?</phrase>
		<phrase module_id="fevent" version_id="2.0.0alpha4" var_name="user_setting_can_edit_other_fevent" added="1239708756">Can edit fevents added by other users?</phrase>
		<phrase module_id="fevent" version_id="2.0.0alpha4" var_name="user_setting_can_post_comment_on_fevent" added="1239715876">Can post comments on fevents?</phrase>
		<phrase module_id="fevent" version_id="2.0.0alpha4" var_name="user_setting_can_delete_own_fevent" added="1239716463">Can delete own fevent?</phrase>
		<phrase module_id="fevent" version_id="2.0.0alpha4" var_name="user_setting_can_delete_other_fevent" added="1239716486">Can delete fevents created by other users?</phrase>
		<phrase module_id="fevent" version_id="2.0.0alpha4" var_name="menu_create_new_fevent" added="1239786795">Create New fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.0alpha4" var_name="admin_menu_add_category" added="1239792607">Add Category</phrase>
		<phrase module_id="fevent" version_id="2.0.0alpha4" var_name="admin_menu_manage_categories" added="1239792607">Manage Categories</phrase>
		<phrase module_id="fevent" version_id="2.0.0alpha4" var_name="setting_fevent_view_time_stamp_profile" added="1239794674"><![CDATA[<title>fevent Profile Time Stamp</title><info>Time stamp used when displaying fevents on a users profile.</info>]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0alpha4" var_name="setting_fevent_browse_time_stamp" added="1239801858"><![CDATA[<title>fevent Browsing Time Stamp</title><info>Time stamp displayed when browsing fevents.</info>]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0beta5" var_name="rss_group_name_2" added="1245607788">fevents</phrase>
		<phrase module_id="fevent" version_id="2.0.0beta5" var_name="rss_title_3" added="1245608409">Latest fevents</phrase>
		<phrase module_id="fevent" version_id="2.0.0beta5" var_name="rss_description_3" added="1245608409">List of all the upcoming fevents.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc1" var_name="user_setting_max_upload_size_fevent" added="1250361175"><![CDATA[Max file size for fevent photos in kilobits (kb).
(1000 kb = 1 mb)
For unlimited add "0" without quotes.]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc1" var_name="user_setting_can_view_pirvate_fevents" added="1250490001">Can view private fevents?</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc1" var_name="user_setting_can_approve_fevents" added="1250491324">Can approve fevents?</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc1" var_name="user_setting_can_feature_fevents" added="1250491341">Can feature fevents?</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc1" var_name="user_setting_fevent_must_be_approved" added="1250491615">fevents must be approved first before they are displayed publicly?</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc1" var_name="user_setting_total_mass_emails_per_hour" added="1250502555">Define how long this user group must wait until they are allowed to send out another mass email.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc1" var_name="user_setting_can_mass_mail_own_members" added="1250505060">Can mass email own fevent guests?</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="done" added="1254390083">Done!</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="not_attending" added="1254390105">Not Attending</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="maybe" added="1254390112">Maybe</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="attending" added="1254390119">Attending</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="successfully_deleted_fevent" added="1254390136">Successfully deleted fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="you_are_unable_to_send_out_any_mass_emails_at_the_moment" added="1254390156">You are unable to send out any mass emails at the moment.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="fill_in_both_a_subject_and_text_for_your_mass_email" added="1254390166">Fill in both a subject and text for your mass email.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="you_are_unable_to_send_a_mass_email_for_this_fevent" added="1254390175">You are unable to send a mass email for this fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="email_progress_page_total" added="1254390201">Email Progress: {page}/{total}</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="categories" added="1254390245">Categories</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="sub_categories" added="1254390255">Sub-Categories</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="browse_filter" added="1254390267">Browse Filter</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="basic_information" added="1254390281">Basic Information</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="fevent_guests" added="1254390296">fevent Guests</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="can_t_make_it" added="1254390323"><![CDATA[Can't Make It]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="not_responded" added="1254390333">Not Responded</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="upcoming_fevents" added="1254390347">Upcoming fevents</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="fevents_i_m_attending" added="1254390361"><![CDATA[fevents I'm Attending]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="your_rsvp" added="1254390374">Your RSVP</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="category_successfully_updated" added="1254390394">Category successfully updated.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="category_successfully_added" added="1254390403">Category successfully added.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="edit_a_category" added="1254390411">Edit a Category</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="create_a_new_category" added="1254390420">Create a New Category</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="category_order_successfully_updated" added="1254390441">Category order successfully updated.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="category_successfully_deleted" added="1254390450">Category successfully deleted.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="manage_categories" added="1254390459">Manage Categories</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="provide_a_name_for_this_fevent" added="1254390488">Provide a name for this fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="provide_a_location_for_this_fevent" added="1254390496">Provide a location for this fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="provide_a_country_location_for_this_fevent" added="1254390544">Provide a country location for this fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="provide_a_host_for_this_fevent" added="1254390553">Provide a host for this fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="successfully_invited_guests_to_this_fevent" added="1254390564">Successfully invited guests to this fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="successfully_customized_this_fevent" added="1254390573">Successfully customized this fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="fevent_successfully_updated" added="1254390590">fevent successfully updated.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="fevent_successfully_added" added="1254390606">fevent successfully added.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="note_that_fevents_must_first_be_approved_by_a_site_admin_before_it_is_displayed_publicly" added="1254390617">Note that fevents must first be approved by a site admin before it is displayed publicly.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="edit_fevent" added="1254390634">Edit fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="create_new_fevent" added="1254390661">Create New fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="fevents" added="1254390673">fevents</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="the_fevents_section_is_closed" added="1254390690">The fevents section is closed.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="fevent_successfully_deleted" added="1254390708">fevent successfully deleted.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="date_added" added="1254390726">Date Added</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="name" added="1254390733">Name</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="recent_fevents" added="1254390754">Recent fevents</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="today" added="1254390762">Today</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="tomorrow" added="1254390768">Tomorrow</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="this_week" added="1254390776">This Week</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="this_weekend" added="1254390785">This Weekend</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="pending" added="1254390792">Pending</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="the_fevent_you_are_looking_for_does_not_exist_or_has_been_removed" added="1254390835">The fevent you are looking for does not exist or has been removed.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="this_fevent_is_private" added="1254390844">This fevent is private.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="provide_a_category_name" added="1254390903">Provide a category name.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="user_name_left_you_a_comment_on_site_title" added="1254390985">{user_name} left you a comment on {site_title}.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="user_name_left_you_a_comment_on_your_fevent_title" added="1254393241"><![CDATA[{user_name} left you a comment on your fevent "{title}".

To view this comment, follow the link below:
<a href="{link}">{link}</a>]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="user_name_added_a_new_comment_on_their_own_fevent" added="1254393467"><![CDATA[<a href="{user_link}">{user_name}</a> added a new comment on their own <a href="{title_link}">fevent</a>.]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="user_name_added_a_new_comment_on_your_fevent" added="1254393537"><![CDATA[<a href="{user_link}">{user_name}</a> added a new comment on your <a href="{title_link}">fevent</a>.]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="user_name_added_a_new_comment_on_item_user_name" added="1254393593"><![CDATA[<a href="{user_link}">{user_name}</a> added a new comment on <a href="{item_user_link}">{item_user_name}'s</a> <a href="{title_link}">fevent</a>.]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="owner_full_name_added_a_new_fevent_title" added="1254393693"><![CDATA[<a href="{user_link}">{owner_full_name}</a> added a new fevent "<a href="{title_link}">{title}</a>"]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="create_an_fevent" added="1254393872">Create an fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="manage_fevents" added="1254393882">Manage fevents</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="view_fevents" added="1254393907">View fevents</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="your_fevent_title_has_been_approved" added="1254393926"><![CDATA[Your fevent "{title}" has been approved.]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="full_name_invited_you_to_an_fevent" added="1254393988"><![CDATA[<a href="{user_link}">{full_name}</a> invited you to an fevent.]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="no_fevent_invites" added="1254394032">No fevent invites.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="fevent_invites_total" added="1254394068"><![CDATA[fevent Invites (<span id="js_request_fevent_count_total">{total}</span>)]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="fevent_text" added="1254394108">fevent Text</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="are_you_sure_this_will_delete_all_fevents_that_belong_to_this_category_and_cannot_be_undone" added="1254394191">Are you sure? This will delete all fevents that belong to this category and cannot be undone.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="keywords" added="1254394232">Keywords</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="location" added="1254394242">Location</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="city" added="1254394251">City</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="zip_postal_code" added="1254394258">Zip/Postal Code</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="sort" added="1254394264">Sort</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="submit" added="1254394272">Submit</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="reset" added="1254394278">Reset</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="host" added="1254394553">Host</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="date" added="1254394561">Date</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="remove_this_person_from_the_guest_list" added="1254394593">Remove this person from the guest list.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="report_an_fevent" added="1254394628">Report an fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="report" added="1254394635">Report</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="invite_people_to_come" added="1254394643">Invite People to Come</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="mass_email_guests" added="1254394654">Mass Email Guests</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="manage_guest_list" added="1254394679">Manage Guest List</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="contact_full_name_creator" added="1254394697">Contact {full_name} (Creator)</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="delete_fevent" added="1254394721">Delete fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="no_upcoming_fevents" added="1254397022">No upcoming fevents.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="add_an_fevent" added="1254397054">Add an fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="time_stamp_at_location" added="1254397907">{time_stamp} at {location}</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="maybe_attending" added="1254398353">Maybe Attending</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="update_your_rsvp" added="1254398413">Update Your RSVP</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="submit_your_rsvp" added="1254398422">Submit Your RSVP</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="fevent_category_details" added="1254398464">fevent Category Details</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="parent_category" added="1254398482">Parent Category</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="select" added="1254398490">Select</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="edit" added="1254398508">Edit</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="delete" added="1254398515">Delete</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="update_order" added="1254398529">Update Order</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="view_this_fevent" added="1254398541">View This fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="skip_amp_view_this_fevent" added="1254398553"><![CDATA[Skip &amp; View This fevent]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="step_1" added="1254398566">Step 1</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="fevent_details" added="1254398576">fevent Details</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="step_2" added="1254398589">Step 2</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="customize" added="1254398598">Customize</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="step_3" added="1254398609">Step 3</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="invite_guests" added="1254398621">Invite Guests</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="mass_email" added="1254398639">Mass Email</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="category" added="1254398655">Category</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="tagline" added="1254398671">Tagline</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="description" added="1254398686">Description</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="public_everyone_can_join" added="1254398696">Public (Everyone can join)</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="privacy" added="1254398703">Privacy</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="private_does_not_show_up_anywhere_and_only_invited_users_can_rsvp" added="1254398711">Private (Does not show up anywhere and only invited users can RSVP)</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="start_time" added="1254398721">Start Time</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="end_time" added="1254398728">End Time</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="country" added="1254398736">Country</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="location_venue" added="1254398759">Location/Venue</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="save" added="1254398769">Save</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="create_fevent" added="1254398776">Create fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="fevent_photo" added="1254398789">fevent Photo</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="are_you_sure" added="1254398809">Are you sure?</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="click_here_to_delete_this_image_and_upload_a_new_one_in_its" added="1254399660"><![CDATA[Click <a href="#" onclick="{java_script}">here</a> to delete this image and upload a new one in its place.]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="you_can_upload_a_jpg_gif_or_png_file" added="1254399765">You can upload a JPG, GIF or PNG file.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="the_file_size_limit_is_filesize_if_your_upload_does_not_work_try_uploading_a_smaller_picture" added="1254399852">The file size limit is {filesize}. If your upload does not work, try uploading a smaller picture.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="invite_friends" added="1254399982">Invite Friends</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="invite_people_via_email" added="1254399998">Invite People via Email</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="separate_multiple_emails_with_a_comma" added="1254400018">Separate multiple emails with a comma.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="add_a_personal_message" added="1254400038">Add a Personal Message</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="send_invitations" added="1254402189">Send Invitations</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="new_guest_list" added="1254402198">New Guest List</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="send_out_an_email_to_all_the_guests_that_are_joining_this_fevent" added="1254402208">Send out an email to all the guests that are joining this fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="last_mass_email" added="1254403662">Last Mass Email</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="subject" added="1254403672">Subject</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="text" added="1254403680">Text</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="sending_emails" added="1254403694">Sending Emails</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="send" added="1254403702">Send</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="please_wait_till" added="1254403726">Please wait till</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="you_have_not_been_invited_to_any_fevents_yet" added="1254403749">You have not been invited to any fevents yet.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="browse_fevents" added="1254403767">Browse fevents.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="you_are_not_attending_any_fevents" added="1254403845">You are not attending any fevents.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="no_fevents_found_where_you_might_attend" added="1254403853">No fevents found where you might attend.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="no_fevents_found_where_you_are_not_attending" added="1254403860">No fevents found where you are not attending.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="no_past_fevents_were_found" added="1254403867">No past fevents were found.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="no_fevents_have_been_created" added="1254403874">No fevents have been created.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="be_the_first_to_create_an_fevent" added="1254403881">Be the first to create an fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="there_is_one_fevent_that_is_pending_approval" added="1254403897">There is one fevent that is pending approval.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="there_are_total_fevents_that_are_pending_approval" added="1254403911">There are {total} fevents that are pending approval.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="click_here_to_approve_fevents" added="1254403986"><![CDATA[Click <a href="{link}">here</a> to approve fevents.]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="private" added="1254404038">Private</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="featured" added="1254404047">Featured</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="start_time_at_location" added="1254404250">{start_time} at {location}</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="posted_by_user_name_on_time_stamp" added="1254405622">Posted by {user_name} on {time_stamp}</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="rsvp" added="1254463761">RSVP</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="no_response" added="1254463769">No Response</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="edit_lowercase" added="1254463862">edit</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="approve_this_fevent" added="1254463909">Approve this fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="approve" added="1254463929">Approve</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="approved" added="1254463938">Approved</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="feature_this_fevent" added="1254463951">Feature this fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="feature" added="1254463975">Feature</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="un_feature_this_fevent" added="1254463988">Un-Feature this fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="unfeature" added="1254464001">Unfeature</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="fevent_is_pending_approval" added="1254464118">fevent is pending approval.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc3" var_name="full_name_has_closed_their_favorites_section" added="1254464572"><![CDATA[<a href="{user_link}">{full_name}</a> has closed their favorites section.]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc4" var_name="a_href_user_link_user_name_a_added_a_comment_on_the_fevent_a_href_title_link_title_a" added="1255941112"><![CDATA[<a href="{user_link}">{user_name}</a> added a comment on the fevent "<a href="{title_link}">{title}</a>".]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc6" var_name="your_fevent_is_ending_before_it_starts" added="1256907682">Your fevent is ending before it starts.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc6" var_name="your_fevent_is_starting_in_the_past" added="1256907696">Your fevent is starting in the past.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc7" var_name="full_name_invited_you_to_the_title" added="1257929870"><![CDATA[{full_name} invited you to "{title}".

To check out this fevent, follow the link below:
<a href="{link}">{link}</a>]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc7" var_name="full_name_added_the_following_personal_message" added="1257930058">

{full_name} added the following personal message
</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc7" var_name="full_name_invited_you_to_the_fevent_title" added="1257930189"><![CDATA[{full_name} invited you to the fevent "{title}".]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc7" var_name="provide_a_category_this_fevent_will_belong_to" added="1257930365">Provide a category this fevent will belong to.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc8" var_name="unable_to_find_the_fevent_you_want_to_approve" added="1258472809">Unable to find the fevent you want to approve.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc8" var_name="you_do_not_have_sufficient_permission_to_modify_this_fevent" added="1258472832">You do not have sufficient permission to modify this fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc8" var_name="unable_to_find_the_fevent" added="1258472853">Unable to find the fevent.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc8" var_name="unable_to_find_the_fevent_you_want_to_delete" added="1258472870">Unable to find the fevent you want to delete.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc8" var_name="you_do_not_have_sufficient_permission_to_delete_this_listing" added="1258472878">You do not have sufficient permission to delete this listing.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc8" var_name="your_fevent_has_been_approved_on_site_title" added="1258472903">Your fevent has been approved on {site_title}.</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc8" var_name="your_fevent_has_been_approved_on_site_title_link" added="1258472958"><![CDATA[Your fevent has been approved on {site_title}.

To view this fevent, follow the link below:
<a href="{link}">{link}</a>]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc8" var_name="notice_this_is_a_newsletter_sent_from_the_fevent" added="1258472989">Notice: This is a newsletter sent from the fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc11" var_name="user_setting_can_access_fevent" added="1260286460">Can browse and view the fevent module?</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc11" var_name="user_setting_can_create_fevent" added="1260329621">Can create an fevent?</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc12" var_name="a_href_user_link_full_name_a_liked_a_href_view_user_link_view_full_name_a_s_a_href_link_fevent_a" added="1260455427"><![CDATA[<a href="{user_link}">{full_name}</a> liked <a href="{view_user_link}">{view_full_name}</a>'s <a href="{link}">fevent</a>.]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc12" var_name="a_href_user_link_full_name_a_liked_their_own_a_href_link_fevent_a" added="1260455449"><![CDATA[<a href="{user_link}">{full_name}</a> liked their own <a href="{link}">fevent</a>.]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc12" var_name="a_href_user_link_full_name_a_liked_your_a_href_link_fevent_a" added="1260456261"><![CDATA[<a href="{user_link}">{full_name}</a> liked your <a href="{link}">fevent</a>.]]></phrase>
		<phrase module_id="fevent" version_id="2.0.0rc12" var_name="can_create_fevent" added="1260904019">Create fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.0rc12" var_name="select_a_sub_category" added="1260971268">Select a Sub-Category</phrase>
		<phrase module_id="fevent" version_id="2.0.2" var_name="remove_invite" added="1262117196">Remove Invite</phrase>
		<phrase module_id="fevent" version_id="2.0.4" var_name="no_attendees" added="1266424685">No attendees.</phrase>
		<phrase module_id="fevent" version_id="2.0.4" var_name="no_results" added="1266424785">No results.</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="user_setting_can_sponsor_fevent" added="1270109256">Can members of this user group sponsor their fevents?</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="unsponsor_this_fevent" added="1270109414">Unsponsor this fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="sponsor_this_fevent" added="1270109438">Sponsor this fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="sponsored_fevent" added="1270109451">Sponsored fevent</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="user_setting_can_purchase_sponsor" added="1271077140">Can members of this user group purchase a sponsored ad space?</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="fevent_successfully_un_sponsored" added="1271077259">fevent successfully unsponsored</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="fevent_successfully_sponsored" added="1271077306">fevent successfully sponsored</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="sponsor_help" added="1271149455">To purchase sponsor space for your fevents click on your fevent and then click on Sponsor in the right hand side menu.</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="encourage_sponsor" added="1271149817">Sponsor your fevents</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="user_setting_fevent_fevent_sponsor_price" added="1271932350">How much is the sponsor space worth for fevents?
This works in a CPM basis.</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="user_setting_auto_publish_sponsored_item" added="1272007260">After the user has purchased a sponsored space, should the fevent be published right away?
If set to false, the admin will have to approve each new purchased sponsored fevent space before it is shown in the site.</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="sponsor_error_not_found" added="1272356770">That fevent is no longer available.</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="sponsor_error_privacy" added="1272356851">This fevent is set to private, sponsoring it conflicts with this setting.</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="sponsor_title" added="1272356926">fevent: {sfeventTitle}</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="sponsor_paypal_message" added="1272357027">Sponsor of fevent {sfeventTitle}</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="setting_fevent_basic_information_time" added="1273245992"><![CDATA[<title>fevent Basic Information Time Stamp</title><info>This is the time stamp that is used when viewing an fevent. It can be found in the "Basic Information" block.</info>]]></phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="setting_fevent_basic_information_time_short" added="1273246130"><![CDATA[<title>fevent Basic Information Time Stamp (Short)</title><info>This is the short version of the time stamp that is used when viewing an fevent. It can be found in the "Basic Information" block.</info>]]></phrase>
		<phrase module_id="fevent" version_id="2.0.5dev2" var_name="user_setting_flood_control_fevents" added="1275108355"><![CDATA[How many minutes should a user wait before they can create another fevent?

Note: Setting it to "0" (without quotes) is default and users will not have to wait.]]></phrase>
		<phrase module_id="fevent" version_id="2.0.5dev2" var_name="you_are_creating_an_fevent_a_little_too_soon" added="1275108393">You are creating an fevent a little too soon.</phrase>
		<phrase module_id="fevent" version_id="2.0.5" var_name="user_setting_fevent_sponsor_price" added="1276177435">How much is the sponsor space worth for fevents?
This works in a CPM basis.</phrase>
		<phrase module_id="fevent" version_id="2.0.6" var_name="time_separator" added="1284988672">at</phrase>
		<phrase module_id="fevent" version_id="2.0.7" var_name="updating" added="1288183829">Updating</phrase>
		<phrase module_id="fevent" version_id="2.0.7" var_name="fevent_invite_count" added="1288714155">fevent Invite Count</phrase>
		<phrase module_id="fevent" version_id="2.0.8" var_name="user_setting_can_view_gmap" added="1298476820">Can members of this user group view a Google Map in the fevents section?</phrase>
		<phrase module_id="fevent" version_id="2.0.8" var_name="user_setting_can_add_gmap" added="1298476869">Can members of this user group add a Google Map to their fevents?</phrase>
		<phrase module_id="fevent" version_id="2.0.8" var_name="address" added="1298896463">Address</phrase>
		<phrase module_id="fevent" version_id="2.1.0" var_name="find_on_map" added="1303890231">Find On Map</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta1" var_name="user_setting_points_fevent" added="1304600668">How many points does the user get when they add a new fevent?</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="what_s_the_fevent" added="1319112161"><![CDATA[What's the fevent?]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="where" added="1319112186">Where?</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="and" added="1319112259">and</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="guest_list" added="1319121993">Guest List</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="fevent_has_been_approved" added="1319183730">fevent has been approved.</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="fevent_approved" added="1319183741">fevent Approved</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="unable_to_find_the_fevent_you_are_trying_to_comment_on" added="1319183818">Unable to find the fevent you are trying to comment on.</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="no_fevents_found" added="1319200038">No fevents found.</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="sponsored" added="1319200053">Sponsored</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="actions" added="1319200068">Actions</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="moderate" added="1319200074">Moderate</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="at" added="1319200086">at</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="search_fevents" added="1319200435">Search fevents...</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="latest" added="1319200442">Latest</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="most_viewed" added="1319200453">Most Viewed</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="most_liked" added="1319200462">Most Liked</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="most_discussed" added="1319200470">Most Discussed</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="all_fevents" added="1319200489">All fevents</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="my_fevents" added="1319200498">My fevents</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="friends_fevents" added="1319200523"><![CDATA[Friends' fevents]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="featured_fevents" added="1319200531">Featured fevents</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="pending_fevents" added="1319200542">Pending fevents</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="fevents_i_may_attend" added="1319200564">fevents I May Attend</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="fevents_i_m_not_attending" added="1319200574"><![CDATA[fevents I'm Not Attending]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="fevent_invites" added="1319200582">fevent Invites</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="full_name_s_fevents" added="1319200598"><![CDATA[{full_name}'s fevents]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="what_are_you_planning" added="1319200639">What are you planning?</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="add_end_time" added="1319200648">Add end time</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="add_address_city_zip_country" added="1319200659">Add address/city/zip/country</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="fevent_privacy" added="1319200672">fevent Privacy</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="control_who_can_see_this_fevent" added="1319200679">Control who can see this fevent.</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="share_privacy" added="1319200688">Share Privacy</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="control_who_can_share_on_this_fevent" added="1319200695">Control who can share on this fevent.</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="update" added="1319200706">Update</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="upload_photo" added="1319200722">Upload Photo</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="unable_to_view_this_item_due_to_privacy_settings" added="1319200748">Unable to view this item due to privacy settings.</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="photo" added="1319200760">Photo</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="managing_fevent" added="1319200777">Managing fevent</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="time" added="1319200892">Time</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="view_on_google_maps" added="1319200908">View on Google Maps</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="view_this_on_google_maps" added="1319200920">View this on Google maps</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="created_by" added="1319200928">Created By</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="respond" added="1319200965">Respond</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="awaiting_reply" added="1319200985">Awaiting Reply</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="view_guest_list" added="1319201019">View Guest List</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="full_name_liked_a_comment_you_posted_on_the_fevent_title" added="1319530391"><![CDATA[{full_name} liked a comment you posted on the fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="full_name_liked_your_comment_message_fevent" added="1319530495"><![CDATA[{full_name} liked your comment "<a href="{link}">{content}</a>" that you posted on the fevent "<a href="{item_link}">{title}</a>".
To view this fevent follow the link below:
<a href="{link}">{link}</a>]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="full_name_liked_your_fevent_title" added="1319530561"><![CDATA[{full_name} liked your fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="full_name_liked_your_fevent_message" added="1319530627"><![CDATA[{full_name} liked your fevent "<a href="{link}">{title}</a>"
To view this fevent follow the link below:
<a href="{link}">{link}</a>]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="unable_to_post_a_comment_on_this_item_due_to_privacy_settings" added="1319547123">Unable to post a comment on this item due to privacy settings.</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="full_name_commented_on_a_comment_posted_on_the_fevent_title" added="1319547152"><![CDATA[{full_name} commented on a comment posted on the fevent "{title}".]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="full_name_commented_on_one_of_your_comments_you_posted_on_the_fevent" added="1319547220"><![CDATA[{full_name} commented on one of your comments you posted on the fevent "<a href="{item_link}">{title}</a>".
To see the comment thread, follow the link below:
<a href="{link}">{link}</a>]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="full_name_commented_on_one_of_gender_fevent_comments" added="1319547295">{full_name} commented on one of {gender} fevent comments.</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="full_name_commented_on_one_of_row_full_name_s_fevent_comments" added="1319547348"><![CDATA[{full_name} commented on one of {row_full_name}'s fevent comments.]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="full_name_commented_on_one_of_gender_own_comments_on_the_fevent" added="1319547425"><![CDATA[{full_name} commented on one of {gender} own comments on the fevent "<a href="{item_link}">{title}</a>".
To see the comment thread, follow the link below:
<a href="{link}">{link}</a>]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="full_name_commented_on_one_of_row_full_name_s" added="1319547518"><![CDATA[{full_name} commented on one of {row_full_name}'s comments on the fevent "<a href="{item_link}">{title}</a>".
To see the comment thread, follow the link below:
<a href="{link}">{link}</a>]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_commented_on_span_class_drop_data_user_row_full_name_s_span_comment_on_the_fevent_title" added="1319547635"><![CDATA[{users} commented on <span class="drop_data_user">{row_full_name}'s</span> comment on the fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_commented_on_gender_own_comment_on_the_fevent_title" added="1319547703"><![CDATA[{users} commented on {gender} own comment on the fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_commented_on_one_of_your_comments_on_the_fevent_title" added="1319547746"><![CDATA[{users} commented on one of your comments on the fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_commented_on_one_of_span_class_drop_data_user_row_full_name_s_span_comments_on_the_fevent_title" added="1319547790"><![CDATA[{users} commented on one of <span class="drop_data_user">{row_full_name}'s</span> comments on the fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_commented_on_span_class_drop_data_user_row_full_name_s_span_fevent_title" added="1319547938"><![CDATA[{users} commented on <span class="drop_data_user">{row_full_name}'s</span> fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_commented_on_gender_own_fevent_title" added="1319547985"><![CDATA[{users} commented on {gender} own fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_commented_on_your_fevent_title" added="1319548025"><![CDATA[{users} commented on your fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_liked_span_class_drop_data_user_row_full_name_s_span_comment_on_the_fevent_title" added="1319548826"><![CDATA[{users} liked <span class="drop_data_user">{row_full_name}'s</span> comment on the fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_liked_gender_own_comment_on_the_fevent_title" added="1319548878"><![CDATA[{users} liked {gender} own comment on the fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_liked_one_of_your_comments_on_the_fevent_title" added="1319548913"><![CDATA[{users} liked one of your comments on the fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_liked_one_on_span_class_drop_data_user_row_full_name_s_span_comments_on_the_fevent_title" added="1319548952"><![CDATA[{users} liked one on <span class="drop_data_user">{row_full_name}'s</span> comments on the fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_liked_gender_own_fevent_title" added="1319551547"><![CDATA[{users} liked {gender} own fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_liked_your_fevent_title" added="1319551587"><![CDATA[{users} liked your fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_liked_span_class_drop_data_user_row_full_name_s_span_fevent_title" added="1319551627"><![CDATA[{users} liked <span class="drop_data_user">{row_full_name}'s</span> fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="users_invited_you_to_the_fevent_title" added="1319551700"><![CDATA[{users} invited you to the fevent "{title}"]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="in_the_fevent_a_href_link_title_a" added="1319551749"><![CDATA[In the fevent <a href="{link}">{title}</a>]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="who_can_share_fevents" added="1319551800">Who can share fevents?</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="who_can_view_browse_fevents" added="1319551808">Who can view/browse fevents?</phrase>
		<phrase module_id="fevent" version_id="3.0.0beta5" var_name="fevent" added="1319551818">fevent</phrase>
		<phrase module_id="fevent" version_id="3.0.0rc1" var_name="responded" added="1320238329">Responded</phrase>
		<phrase module_id="fevent" version_id="3.0.0rc1" var_name="invited" added="1320238336">Invited</phrase>
		<phrase module_id="fevent" version_id="3.0.0rc1" var_name="invites" added="1321288957">Invites</phrase>
		<phrase module_id="fevent" version_id="3.0.0rc1" var_name="yes" added="1321288970">Yes</phrase>
		<phrase module_id="fevent" version_id="3.0.0rc1" var_name="no" added="1321288983">No</phrase>
		<phrase module_id="fevent" version_id="3.0.0rc2" var_name="by" added="1321364518">by</phrase>
		<phrase module_id="fevent" version_id="3.0.0" var_name="full_name_wrote_a_comment_on_your_fevent_title" added="1322466493"><![CDATA[{full_name} wrote a comment on your fevent "{title}".]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0" var_name="full_name_wrote_a_comment_on_your_fevent_message" added="1322466576"><![CDATA[{full_name} wrote a comment on your fevent "<a href="{link}">{title}</a>".
To see the comment thread, follow the link below:
<a href="{link}">{link}</a>]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0" var_name="a_href_link_on_name_s_fevent_a" added="1322561554"><![CDATA[<a href="{link}">On {name}'s fevent</a>]]></phrase>
		<phrase module_id="fevent" version_id="3.0.0" var_name="fevent_s_successfully_approved" added="1322739304">fevent(s) successfully approved.</phrase>
		<phrase module_id="fevent" version_id="3.0.0" var_name="fevent_s_successfully_deleted" added="1322739317">fevent(s) successfully deleted.</phrase>
		<phrase module_id="fevent" version_id="3.0.0" var_name="successfully_added_a_photo_to_your_fevent" added="1323087700">Successfully added a photo to your fevent.</phrase>
		<phrase module_id="fevent" version_id="3.1.0beta1" var_name="user_name_tagged_you_in_a_comment_in_an_fevent" added="1331221377">{user_name} tagged you in a comment in an fevent</phrase>
		<phrase module_id="fevent" version_id="3.1.0rc1" var_name="menu_fevent_fevents_532c28d5412dd75bf975fb951c740a30" added="1332257694">fevents</phrase>
		<phrase module_id="fevent" version_id="3.5.0beta1" var_name="item_phrase" added="1352730940">fevent</phrase>
		<phrase module_id="fevent" version_id="3.6.0rc1" var_name="setting_cache_fevents_per_user" added="1371724255"><![CDATA[<title>Profile fevent Count</title><info>Avoids querying for count in fevent.callback getTotalItemCount (called when going to a profile).</info>]]></phrase>
		<phrase module_id="fevent" version_id="3.6.0rc1" var_name="setting_cache_upcoming_fevents_info" added="1371731919"><![CDATA[<title>Cache Upcoming fevents (Hours)</title><info>Cache the upcoming fevent in hours.</info>]]></phrase>
	</phrases>
	<rss_group>
		<group module_id="fevent" group_id="2" name_var="fevent.rss_group_name_2" is_active="1" />
	</rss_group>
	<rss>
		<feed module_id="fevent" group_id="2" title_var="fevent.rss_title_3" description_var="fevent.rss_description_3" feed_link="fevent" is_active="1" is_site_wide="1">
			<php_group_code></php_group_code>
			<php_view_code><![CDATA[$aRows = Phpfox::getService('fevent')->getForRssFeed();]]></php_view_code>
		</feed>
	</rss>
	<user_group_settings>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="1" user="1" guest="0" staff="1" module="fevent" ordering="0">can_edit_own_fevent</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="1" user="0" guest="0" staff="1" module="fevent" ordering="0">can_edit_other_fevent</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="1" user="1" guest="0" staff="1" module="fevent" ordering="0">can_post_comment_on_fevent</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="1" user="1" guest="0" staff="1" module="fevent" ordering="0">can_delete_own_fevent</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="1" user="0" guest="0" staff="1" module="fevent" ordering="0">can_delete_other_fevent</setting>
		<setting is_admin_setting="0" module_id="fevent" type="integer" admin="500" user="500" guest="500" staff="500" module="fevent" ordering="0">max_upload_size_fevent</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="1" user="0" guest="0" staff="1" module="fevent" ordering="0">can_view_pirvate_fevents</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="1" user="0" guest="0" staff="1" module="fevent" ordering="0">can_approve_fevents</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="1" user="0" guest="0" staff="1" module="fevent" ordering="0">can_feature_fevents</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="0" user="0" guest="0" staff="0" module="fevent" ordering="0">fevent_must_be_approved</setting>
		<setting is_admin_setting="0" module_id="fevent" type="integer" admin="0" user="60" guest="60" staff="0" module="fevent" ordering="0">total_mass_emails_per_hour</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="1" user="1" guest="0" staff="1" module="fevent" ordering="0">can_mass_mail_own_members</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="1" user="1" guest="1" staff="1" module="fevent" ordering="0">can_access_fevent</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="1" user="1" guest="0" staff="1" module="fevent" ordering="0">can_create_fevent</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="false" user="false" guest="false" staff="false" module="fevent" ordering="0">can_sponsor_fevent</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="false" user="false" guest="false" staff="false" module="fevent" ordering="0">can_purchase_sponsor</setting>
		<setting is_admin_setting="0" module_id="fevent" type="string" admin="null" user="null" guest="null" staff="null" module="fevent" ordering="0">fevent_sponsor_price</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="true" user="false" guest="false" staff="false" module="fevent" ordering="0">auto_publish_sponsored_item</setting>
		<setting is_admin_setting="0" module_id="fevent" type="integer" admin="0" user="0" guest="0" staff="0" module="fevent" ordering="0">flood_control_fevents</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="true" user="true" guest="false" staff="true" module="fevent" ordering="0">can_view_gmap</setting>
		<setting is_admin_setting="0" module_id="fevent" type="boolean" admin="true" user="true" guest="false" staff="true" module="fevent" ordering="0">can_add_gmap</setting>
		<setting is_admin_setting="0" module_id="fevent" type="integer" admin="1" user="1" guest="0" staff="1" module="fevent" ordering="0">points_fevent</setting>
	</user_group_settings>
	<tables><![CDATA[a:7:{s:12:"phpfox_fevent";a:3:{s:7:"COLUMNS";a:28:{s:8:"fevent_id";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:14:"auto_increment";i:3;s:2:"NO";}s:7:"view_id";a:4:{i:0;s:6:"TINT:1";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:11:"is_featured";a:4:{i:0;s:6:"TINT:1";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:10:"is_sponsor";a:4:{i:0;s:6:"TINT:1";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:7:"privacy";a:4:{i:0;s:6:"TINT:1";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:15:"privacy_comment";a:4:{i:0;s:6:"TINT:1";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:9:"module_id";a:4:{i:0;s:8:"VCHAR:75";i:1;s:5:"fevent";i:2;s:0:"";i:3;s:2:"NO";}s:7:"item_id";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:7:"user_id";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:5:"title";a:4:{i:0;s:9:"VCHAR:255";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:8:"location";a:4:{i:0;s:9:"VCHAR:255";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}s:11:"country_iso";a:4:{i:0;s:6:"CHAR:2";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}s:16:"country_child_id";a:4:{i:0;s:4:"UINT";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:11:"postal_code";a:4:{i:0;s:8:"VCHAR:20";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}s:4:"city";a:4:{i:0;s:9:"VCHAR:255";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}s:10:"time_stamp";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:10:"start_time";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:8:"end_time";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:10:"image_path";a:4:{i:0;s:8:"VCHAR:75";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}s:9:"server_id";a:4:{i:0;s:6:"TINT:1";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:13:"total_comment";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:10:"total_like";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:13:"total_dislike";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:10:"mass_email";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:16:"start_gmt_offset";a:4:{i:0;s:8:"VCHAR:15";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}s:14:"end_gmt_offset";a:4:{i:0;s:8:"VCHAR:15";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}s:4:"gmap";a:4:{i:0;s:9:"VCHAR:255";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}s:7:"address";a:4:{i:0;s:9:"VCHAR:255";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}}s:11:"PRIMARY_KEY";s:8:"fevent_id";s:4:"KEYS";a:7:{s:9:"module_id";a:2:{i:0;s:5:"INDEX";i:1;a:2:{i:0;s:9:"module_id";i:1;s:7:"item_id";}}s:7:"user_id";a:2:{i:0;s:5:"INDEX";i:1;s:7:"user_id";}s:7:"view_id";a:2:{i:0;s:5:"INDEX";i:1;a:4:{i:0;s:7:"view_id";i:1;s:7:"privacy";i:2;s:7:"item_id";i:3;s:10:"start_time";}}s:9:"view_id_2";a:2:{i:0;s:5:"INDEX";i:1;a:5:{i:0;s:7:"view_id";i:1;s:7:"privacy";i:2;s:7:"item_id";i:3;s:7:"user_id";i:4;s:10:"start_time";}}s:9:"view_id_3";a:2:{i:0;s:5:"INDEX";i:1;a:3:{i:0;s:7:"view_id";i:1;s:7:"privacy";i:2;s:7:"user_id";}}s:9:"view_id_4";a:2:{i:0;s:5:"INDEX";i:1;a:4:{i:0;s:7:"view_id";i:1;s:7:"privacy";i:2;s:7:"item_id";i:3;s:5:"title";}}s:9:"view_id_5";a:2:{i:0;s:5:"INDEX";i:1;a:5:{i:0;s:7:"view_id";i:1;s:7:"privacy";i:2;s:9:"module_id";i:3;s:7:"item_id";i:4;s:10:"start_time";}}}}s:21:"phpfox_fevent_category";a:3:{s:7:"COLUMNS";a:8:{s:11:"category_id";a:4:{i:0;s:4:"UINT";i:1;N;i:2;s:14:"auto_increment";i:3;s:2:"NO";}s:9:"parent_id";a:4:{i:0;s:4:"UINT";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:9:"is_active";a:4:{i:0;s:6:"TINT:1";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:4:"name";a:4:{i:0;s:9:"VCHAR:255";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:8:"name_url";a:4:{i:0;s:9:"VCHAR:255";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}s:10:"time_stamp";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:4:"used";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:8:"ordering";a:4:{i:0;s:7:"UINT:11";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}}s:11:"PRIMARY_KEY";s:11:"category_id";s:4:"KEYS";a:2:{s:9:"parent_id";a:2:{i:0;s:5:"INDEX";i:1;a:2:{i:0;s:9:"parent_id";i:1;s:9:"is_active";}}s:9:"is_active";a:2:{i:0;s:5:"INDEX";i:1;a:2:{i:0;s:9:"is_active";i:1;s:8:"name_url";}}}}s:26:"phpfox_fevent_category_data";a:2:{s:7:"COLUMNS";a:2:{s:8:"fevent_id";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:11:"category_id";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}}s:4:"KEYS";a:2:{s:11:"category_id";a:2:{i:0;s:5:"INDEX";i:1;s:11:"category_id";}s:8:"fevent_id";a:2:{i:0;s:5:"INDEX";i:1;s:8:"fevent_id";}}}s:17:"phpfox_fevent_feed";a:3:{s:7:"COLUMNS";a:11:{s:7:"feed_id";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:14:"auto_increment";i:3;s:2:"NO";}s:7:"privacy";a:4:{i:0;s:6:"TINT:1";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:15:"privacy_comment";a:4:{i:0;s:6:"TINT:1";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:7:"type_id";a:4:{i:0;s:8:"VCHAR:75";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:7:"user_id";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:14:"parent_user_id";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:7:"item_id";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:10:"time_stamp";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:14:"parent_feed_id";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:16:"parent_module_id";a:4:{i:0;s:8:"VCHAR:75";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}s:11:"time_update";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}}s:11:"PRIMARY_KEY";s:7:"feed_id";s:4:"KEYS";a:2:{s:14:"parent_user_id";a:2:{i:0;s:5:"INDEX";i:1;s:14:"parent_user_id";}s:11:"time_update";a:2:{i:0;s:5:"INDEX";i:1;s:11:"time_update";}}}s:25:"phpfox_fevent_feed_comment";a:3:{s:7:"COLUMNS";a:9:{s:15:"feed_comment_id";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:14:"auto_increment";i:3;s:2:"NO";}s:7:"user_id";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:14:"parent_user_id";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:7:"privacy";a:4:{i:0;s:6:"TINT:3";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:15:"privacy_comment";a:4:{i:0;s:6:"TINT:3";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:7:"content";a:4:{i:0;s:5:"MTEXT";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}s:10:"time_stamp";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:13:"total_comment";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:10:"total_like";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}}s:11:"PRIMARY_KEY";s:15:"feed_comment_id";s:4:"KEYS";a:1:{s:14:"parent_user_id";a:2:{i:0;s:5:"INDEX";i:1;s:14:"parent_user_id";}}}s:19:"phpfox_fevent_invite";a:3:{s:7:"COLUMNS";a:8:{s:9:"invite_id";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:14:"auto_increment";i:3;s:2:"NO";}s:8:"fevent_id";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:7:"type_id";a:4:{i:0;s:6:"TINT:1";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:7:"rsvp_id";a:4:{i:0;s:6:"TINT:1";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:7:"user_id";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:15:"invited_user_id";a:4:{i:0;s:7:"UINT:10";i:1;s:1:"0";i:2;s:0:"";i:3;s:2:"NO";}s:13:"invited_email";a:4:{i:0;s:9:"VCHAR:100";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}s:10:"time_stamp";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}}s:11:"PRIMARY_KEY";s:9:"invite_id";s:4:"KEYS";a:5:{s:8:"fevent_id";a:2:{i:0;s:5:"INDEX";i:1;s:8:"fevent_id";}s:10:"fevent_id_2";a:2:{i:0;s:5:"INDEX";i:1;a:2:{i:0;s:8:"fevent_id";i:1;s:15:"invited_user_id";}}s:15:"invited_user_id";a:2:{i:0;s:5:"INDEX";i:1;s:15:"invited_user_id";}s:10:"fevent_id_3";a:2:{i:0;s:5:"INDEX";i:1;a:3:{i:0;s:8:"fevent_id";i:1;s:7:"rsvp_id";i:2;s:15:"invited_user_id";}}s:7:"rsvp_id";a:2:{i:0;s:5:"INDEX";i:1;a:2:{i:0;s:7:"rsvp_id";i:1;s:15:"invited_user_id";}}}}s:17:"phpfox_fevent_text";a:2:{s:7:"COLUMNS";a:3:{s:8:"fevent_id";a:4:{i:0;s:7:"UINT:10";i:1;N;i:2;s:0:"";i:3;s:2:"NO";}s:11:"description";a:4:{i:0;s:5:"MTEXT";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}s:18:"description_parsed";a:4:{i:0;s:5:"MTEXT";i:1;N;i:2;s:0:"";i:3;s:3:"YES";}}s:4:"KEYS";a:1:{s:8:"fevent_id";a:2:{i:0;s:5:"INDEX";i:1;s:8:"fevent_id";}}}}]]></tables>
	<install><![CDATA[
		$aCategories = array(
			'Arts',
			'Party',
			'Comedy',			
			'Sports',			
			'Music',
			'TV',
			'Movies',
			'Other'
		);		
		
		$iCategoryOrder = 0;
		foreach ($aCategories as $sCategory)
		{
			$iCategoryOrder++;
			$iCategoryId = $this->database()->insert(Phpfox::getT('fevent_category'), array(					
					'name' => $sCategory,					
					'is_active' => 1,
					'ordering' => $iCategoryOrder			
				)
			);			
		}
	]]></install>
</module>