<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: August 23, 2014, 6:25 am */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: share.html.php 7099 2014-02-10 19:39:10Z Fern $
 */
 
 

?>
				<script type="text/javascript">
					oTranslations['<?php echo $this->_aVars['sPhraseKey']; ?>'] = '<?php echo $this->_aVars['sPhraseValue']; ?>';
				</script>

				<div class="global_attachment_holder_section" id="global_attachment_poll">	
					<div class="table">
						<div class="table_left">
<?php echo Phpfox::getPhrase('poll.question'); ?>:
						</div>
						<div class="table_right">
							<input type="text" name="val[poll_question]" value="" style="width:90%;" onchange="if (empty(this.value)) { $bButtonSubmitActive = false; $('.activity_feed_form_button .button').addClass('button_not_active'); } else { $bButtonSubmitActive = true; $('.activity_feed_form_button .button').removeClass('button_not_active'); }" />
						</div>
					</div>
					<div class="table">
						<div class="table_left">
<?php echo Phpfox::getPhrase('poll.answers'); ?>:
						</div>
						<div class="table_right">
							<ol id="js_poll_feed_answer" class="js_poll_feed_answer poll_feed_answer">
<?php for ($this->_aVars['i'] = 1; $this->_aVars['i'] <= 2; $this->_aVars['i']++): ?>
							<li>
								<input type="text" name="val[answer][][answer]" value="" size="30" class="js_feed_poll_answer v_middle" />
							</li>
<?php endfor; ?>
							</ol>
							<a href="#" onclick="return $Core.addNewPollOption(<?php echo $this->_aVars['iMaxAnswers']; ?>);" class="poll_feed_answer_add"><?php echo Phpfox::getPhrase('poll.add_another_answer'); ?></a>
						</div>
					</div>					
				</div>

