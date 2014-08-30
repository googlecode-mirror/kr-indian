<?php 
    /**
    * [PHPFOX_HEADER]
    * 
    * @copyright		[PHPFOX_COPYRIGHT]
    * @author  		Raymond_Benc
    * @package 		Phpfox
    * @version 		$Id: attending.html.php 3342 2011-10-21 12:59:32Z Raymond_Benc $
    */

    defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="div_parent_attending">
    {if count($aInvites)}
    <div>
        {foreach from=$aInvites name=invites item=aInvite}    
        <div class="fevevtAttending">{img user=$aInvite suffix='_50_square' max_width=32 max_height=32 class='v_middle'}</div>
        {/foreach}
    </div>
    <div style="clear: both;"></div>
    <div id="Attendingline"></div>
    {/if}

    <div id="attendingInvites">
        <div>
            <label id="attendingGust">GUEST</label>
        </div>
        <div>
            <div id="attendingAttending" class="attendingpoup">
                <div class="attendtextcount">{$aInvites|count}</div>
                <div class="attendtext" >Attending</div>
            </div>
            <div id="attendingMaybeAttending" class="attendingpoup">
                <div class="attendtextcount">{$iMaybeCnt}</div>
                <div class="attendtext">Maybe Attending</div>
            </div>
            <div id="attendingNotAttending" class="attendingpoup">
                <div class="attendtextcount">{$iNotAttendingCnt}</div>
                <div class="attendtext">Not Attending</div>
            </div>
        </div> 
        <div style="clear: both;"></div>
    </div>
</div>
<script type="text/javascript">
var sfeventId = {$afevent.fevent_id};
{literal}
$Behavior.onClickfeventGuestList = function()
{
    if ($Core.exists('#attendingInvites')){
        $('#attendingAttending').click(function()
        {
            $Core.box('fevent.browseList', '400', 'id=' + sfeventId+'&rsvp=1');
            return false;
        });  
        $('#attendingMaybeAttending').click(function()
        {
            $Core.box('fevent.browseList', '400', 'id=' + sfeventId+'&rsvp=2');
            return false;
        });
        $('#attendingNotAttending').click(function()
        {
            $Core.box('fevent.browseList', '400', 'id=' + sfeventId+'&rsvp=3');
            return false;
        });      
    }
}
{/literal}
</script>
