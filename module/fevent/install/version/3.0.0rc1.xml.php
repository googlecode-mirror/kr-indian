<upgrade>
	<phrases>
		<phrase>
			<module_id>fevent</module_id>
			<version_id>3.0.0rc1</version_id>
			<var_name>responded</var_name>
			<added>1320238329</added>
			<value>Responded</value>
		</phrase>
		<phrase>
			<module_id>fevent</module_id>
			<version_id>3.0.0rc1</version_id>
			<var_name>invited</var_name>
			<added>1320238336</added>
			<value>Invited</value>
		</phrase>
	</phrases>
	<hooks>
		<hook>
			<module_id>fevent</module_id>
			<hook_type>component</hook_type>
			<module>fevent</module>
			<call_name>fevent.component_block_attending_clean</call_name>
			<added>1319729453</added>
			<version_id>3.0.0rc1</version_id>
			<value />
		</hook>
		<hook>
			<module_id>fevent</module_id>
			<hook_type>component</hook_type>
			<module>fevent</module>
			<call_name>fevent.component_block_browse_clean</call_name>
			<added>1319729453</added>
			<version_id>3.0.0rc1</version_id>
			<value />
		</hook>
		<hook>
			<module_id>fevent</module_id>
			<hook_type>component</hook_type>
			<module>fevent</module>
			<call_name>fevent.component_block_featured_clean</call_name>
			<added>1319729453</added>
			<version_id>3.0.0rc1</version_id>
			<value />
		</hook>
		<hook>
			<module_id>fevent</module_id>
			<hook_type>component</hook_type>
			<module>fevent</module>
			<call_name>fevent.component_block_invite_clean</call_name>
			<added>1319729453</added>
			<version_id>3.0.0rc1</version_id>
			<value />
		</hook>
		<hook>
			<module_id>fevent</module_id>
			<hook_type>component</hook_type>
			<module>fevent</module>
			<call_name>fevent.component_block_rsvp_entry_clean</call_name>
			<added>1319729453</added>
			<version_id>3.0.0rc1</version_id>
			<value />
		</hook>
		<hook>
			<module_id>fevent</module_id>
			<hook_type>controller</hook_type>
			<module>fevent</module>
			<call_name>fevent.component_controller_profile_clean</call_name>
			<added>1319729453</added>
			<version_id>3.0.0rc1</version_id>
			<value />
		</hook>
	</hooks>
	<phpfox_update_rss>
		<feed>
			<module_id>fevent</module_id>
			<group_id>2</group_id>
			<title_var>fevent.rss_title_3</title_var>
			<description_var>fevent.rss_description_3</description_var>
			<feed_link>fevent</feed_link>
			<is_active>1</is_active>
			<is_site_wide>1</is_site_wide>
			<php_group_code />
			<php_view_code><![CDATA[$aRows = Phpfox::getService('fevent')->getForRssFeed();]]></php_view_code>
		</feed>
	</phpfox_update_rss>
</upgrade>