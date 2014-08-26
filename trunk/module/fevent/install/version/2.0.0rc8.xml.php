<upgrade>
	<phrases>
		<phrase>
			<module_id>fevent</module_id>
			<version_id>2.0.0rc8</version_id>
			<var_name>unable_to_find_the_fevent_you_want_to_approve</var_name>
			<added>1258472809</added>
			<value>Unable to find the fevent you want to approve.</value>
		</phrase>
		<phrase>
			<module_id>fevent</module_id>
			<version_id>2.0.0rc8</version_id>
			<var_name>you_do_not_have_sufficient_permission_to_modify_this_fevent</var_name>
			<added>1258472832</added>
			<value>You do not have sufficient permission to modify this fevent.</value>
		</phrase>
		<phrase>
			<module_id>fevent</module_id>
			<version_id>2.0.0rc8</version_id>
			<var_name>unable_to_find_the_fevent</var_name>
			<added>1258472853</added>
			<value>Unable to find the fevent.</value>
		</phrase>
		<phrase>
			<module_id>fevent</module_id>
			<version_id>2.0.0rc8</version_id>
			<var_name>unable_to_find_the_fevent_you_want_to_delete</var_name>
			<added>1258472870</added>
			<value>Unable to find the fevent you want to delete.</value>
		</phrase>
		<phrase>
			<module_id>fevent</module_id>
			<version_id>2.0.0rc8</version_id>
			<var_name>you_do_not_have_sufficient_permission_to_delete_this_listing</var_name>
			<added>1258472878</added>
			<value>You do not have sufficient permission to delete this listing.</value>
		</phrase>
		<phrase>
			<module_id>fevent</module_id>
			<version_id>2.0.0rc8</version_id>
			<var_name>your_fevent_has_been_approved_on_site_title</var_name>
			<added>1258472903</added>
			<value>Your fevent has been approved on {site_title}.</value>
		</phrase>
		<phrase>
			<module_id>fevent</module_id>
			<version_id>2.0.0rc8</version_id>
			<var_name>your_fevent_has_been_approved_on_site_title_link</var_name>
			<added>1258472958</added>
			<value><![CDATA[Your fevent has been approved on {site_title}.

To view this fevent, follow the link below:
<a href="{link}">{link}</a>]]></value>
		</phrase>
		<phrase>
			<module_id>fevent</module_id>
			<version_id>2.0.0rc8</version_id>
			<var_name>notice_this_is_a_newsletter_sent_from_the_fevent</var_name>
			<added>1258472989</added>
			<value>Notice: This is a newsletter sent from the fevent</value>
		</phrase>
	</phrases>
	<hooks>
		<hook>
			<module_id>fevent</module_id>
			<hook_type>component</hook_type>
			<module>fevent</module>
			<call_name>fevent.component_block_filter_clean</call_name>
			<added>1258389334</added>
			<version_id>2.0.0rc8</version_id>
			<value />
		</hook>
	</hooks>
	<update_templates>
		<file type="controller">view.html.php</file>
	</update_templates>
</upgrade>