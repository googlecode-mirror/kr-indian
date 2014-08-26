<upgrade>
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
			<php_view_code><![CDATA[$oServicefeventBrowse = Phpfox::getService('fevent.browse');
$iTimeDisplay = Phpfox::getLib('phpfox.date')->mktime(0, 0, 0, Phpfox::getTime('m'), Phpfox::getTime('d'), Phpfox::getTime('Y'));
$aConditions = array();
$aConditions[] = 'm.view_id = 0 AND m.module_id = \\\\\\\\'fevent\\\\\\\\' AND m.item_id = 0';
$aConditions[] = 'AND m.start_time >= \\\\\\\\'' . $iTimeDisplay . '\\\\\\\\'';
$oServicefeventBrowse->condition($aConditions)
	->page(0)
	->full(true)
	->size(Phpfox::getParam('rss.total_rss_display'))
	->execute();
$aRows = $oServicefeventBrowse->get();	
foreach ($aRows as $iKey => $aRow)
{
	$aRows[$iKey]['link'] = Phpfox::getLib('phpfox.url')->makeUrl('fevent.view', $aRow['title_url']);
	$aRows[$iKey]['creator'] = $aRow['full_name'];
}]]></php_view_code>
		</feed>
	</phpfox_update_rss>
</upgrade>