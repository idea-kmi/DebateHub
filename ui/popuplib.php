<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2015 The Open University UK                                   *
 *                                                                              *
 *  This software is freely distributed in accordance with                      *
 *  the GNU Lesser General Public (LGPL) license, version 3 or later            *
 *  as published by the Free Software Foundation.                               *
 *  For details see LGPL: http://www.fsf.org/licensing/licenses/lgpl.html       *
 *               and GPL: http://www.fsf.org/licensing/licenses/gpl-3.0.html    *
 *                                                                              *
 *  This software is provided by the copyright holders and contributors "as is" *
 *  and any express or implied warranties, including, but not limited to, the   *
 *  implied warranties of merchantability and fitness for a particular purpose  *
 *  are disclaimed. In no event shall the copyright owner or contributors be    *
 *  liable for any direct, indirect, incidental, special, exemplary, or         *
 *  consequential damages (including, but not limited to, procurement of        *
 *  substitute goods or services; loss of use, data, or profits; or business    *
 *  interruption) however caused and on any theory of liability, whether in     *
 *  contract, strict liability, or tort (including negligence or otherwise)     *
 *  arising in any way out of the use of this software, even if advised of the  *
 *  possibility of such damage.                                                 *
 *                                                                              *
 ********************************************************************************/

/**
 * Insert an Idea block - used in Split form
 */
function insertIdeas() {
	global $ideanamearray, $ideadescarray, $CFG, $LNG, $HUB_FLM;
?>
   <div class="hgrformrow" id="ideadiv">
		<div style="display: block; float:left">
	        <div class="formrow" id="ideaformdiv">
	  			<?php
					$count = 0;
					if (is_countable($ideanamearray)) {
						$count = count($ideanamearray);
					}
	                for($i=0; $i<$count; $i++){
	            		?>
						<div id="ideafield<?php echo $i; ?>" class="formrow">
							<div class="formrowsm">
								<input class="subforminput hgrwide" placeholder="<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>" id="ideaname-'+noIdeas+'" name="ideanamearray[]"  value="<?php echo $ideanamearray[$i]; ?>" />
							</div>
							<div class="formrowsm">
								<textarea rows="3" class="subforminput hgrwide" placeholder="<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>" id="ideadesc-'+noIdeas+'" name="ideadescarray[]"><?php echo $ideadescarray[$i]; ?></textarea>
							</div>
						</div>
						<?php if ($i > 1) { ?>
						< a href="javascript:removeMultiple('idea', <?php echo $i; ?>)" class="form" style="float:right;margin-right: 5px;"><?php echo $LNG->FORM_BUTTON_REMOVE; ?></a><br>
						<?php } ?>
		         <?php } ?>
			</div>
	        <div class="formrow">
	    		<span class="formsubmit form active" style="margin-left: 10px;" onclick="noIdeas = addIdea(noIdeas);"><?php echo $LNG->FORM_BUTTON_ADD_ANOTHER." ".$LNG->SOLUTION_NAME; ?></span>
	    	</div>
		</div>
	</div>
<?php }


function insertFormHeaderMessage() {
	global $LNG; ?>
	<p style="clear:both;margin-left: 10px;"><?php echo $LNG->FORM_HEADER_MESSAGE; ?>
	<br><?php echo $LNG->FORM_REQUIRED_FIELDS_MESSAGE_PART1; ?> <span style="font-size:14pt;margin-top:3px;vertical-align:top; font-weight:bold;color:red;">*</span> <?php echo $LNG->FORM_REQUIRED_FIELDS_MESSAGE_PART2; ?><?php echo $LNG->FORM_REQUIRED_FIELDS_MESSAGE_PART3; ?>
	</p>
<?php }

function insertSummary($hintname, $title = "") {
	global $summary, $CFG, $LNG, $HUB_FLM;
	if ($title == "") {
		$title = $LNG->FORM_LABEL_SUMMARY;
	}
	?>
   <div class="hgrformrow" id="summarydiv">
		<label  class="formlabelbig" for="summary"><span style="vertical-align:top"><?php echo $title; ?></span>
			<span class="active" onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)"><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="margin-top: 2px; margin-left: 5px; margin-right: 2px;" /></span>
			<span style="font-size:14pt;margin-top:3px;vertical-align:middle;color:red;">*</span>
		</label>
		<input class="forminputmust hgrinput hgrwide" id="summary" name="summary" value="<?php echo( $summary ); ?>" />
	</div>
<?php }

function insertDescription($hintname) {
	global $desc, $CFG, $LNG, $HUB_FLM; ?>
    <div class="hgrformrow" id="descdiv">
		<label  class="formlabelbig" for="desc">
			<span style="vertical-align:top"><?php echo $LNG->FORM_LABEL_DESC; ?>
			<a id="editortogglebutton" href="javascript:void(0)" style="vertical-align:top" onclick="switchCKEditorMode(this, 'textareadiv', 'desc')" title="<?php echo $LNG->FORM_DESC_HTML_TEXT_HINT; ?>"><?php echo $LNG->FORM_DESC_HTML_TEXT_LINK; ?></a>
			</span>
			<span class="active" onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)"><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="margin-top: 2px; margin-left: 5px; margin-right: 2px;" /></span>
			<span style="font-size:14pt;margin-top:3px;vertical-align:middle;color:white;">*</span>
		</label>
		<?php if (isProbablyHTML($desc)) { ?>
			<div id="textareadiv" style="clear:both;float:left;">
				<textarea rows="4" class="ckeditor forminput hgrwide" id="desc" name="desc"><?php echo( $desc ); ?></textarea>
			</div>
		<?php } else { ?>
			<div id="textareadiv" style="clear:none;float:left;">
				<textarea rows="4" class="forminput hgrwide" id="desc" name="desc"><?php echo( $desc ); ?></textarea>
			</div>
		<?php } ?>
	</div>
<?php }


/**
 * Insert the project dates form fields
 * @param hintname the string representing the key to call the rollover hint for the field.
 */
function insertIssuePhases() {
	global $CFG,$LNG,$HUB_FLM,$sdt,$edt,
		$discussionend,$discuissionon,
		$lemoningstart,$lemoningend,$lemoningon,
		$votingstart,$votingend,$votingon; ?>

	<div class="boxshadowsquare" style="margin-top:10px;margin-left:5px;clear:both;float:left;width:675px">
		<h2><?php echo $LNG->ISSUE_OPEN_TITLE; ?></h2>
		<span><?php echo $LNG->ISSUE_OPEN_HELP ;?></span>
	</div>

	<div class="boxshadowsquare" style="margin-top:10px;margin-left:5px;clear:both;float:left;width:675px">
		<h2><?php echo $LNG->ISSUE_TIMING_TITLE; ?></h2>
		<span><?php echo $LNG->ISSUE_TIMING_HELP ;?></span>
		<h2>
			<ul id="start" class="phasearrow" style="padding:0px;">
				<li id="start2" class="current"><span><?php echo $LNG->ISSUE_PHASE_START; ?></span></li>
				<li id="discuss2"><span>&nbsp;</span></li>
				<li id="reduce2"><span>&nbsp;</span></li>
				<li id="decide2"><span>&nbsp;</span></li>
				<li id="end2" class="current"><span><?php echo $LNG->ISSUE_PHASE_END; ?></span></li>
			</ul>
		</h2>
		<?php insertIssueDates('IssueDates'); ?>
	</div>

	<div class="boxshadowsquare" style="margin-top:10px;margin-left:5px;clear:both;float:left;width:675px">
		<h2><?php echo $LNG->ISSUE_PHASING_TITLE; ?></h2>

		<span><?php echo $LNG->ISSUE_PHASING_HELP; ?></span>

		<div style="clear:both"></div>

		<h2>
			<ul id="start" class="phasearrow" style="padding:0px;">
				<li id="start2"><span><?php echo $LNG->ISSUE_PHASE_START; ?></span></li>
				<li id="discuss2" class="current"><span><?php echo $LNG->ISSUE_PHASE_DISCUSS; ?></span></li>
				<li id="reduce2"><span><?php echo $LNG->ISSUE_PHASE_REDUCE; ?></span></li>
				<li id="decide2"><span><?php echo $LNG->ISSUE_PHASE_DECIDE; ?></span></li>
				<li id="end2"><span><?php echo $LNG->ISSUE_PHASE_END; ?></span></li>
			</ul>
		</h2>
		<?php insertDiscussionDates('IssueDiscussionDates'); ?>

		<h2 style="padding-top:10px;">
			<ul class="phasearrow" style="padding:0px;">
				<li id="start3"><span><?php echo $LNG->ISSUE_PHASE_START; ?></span></li>
				<li id="discuss3"><span><?php echo $LNG->ISSUE_PHASE_DISCUSS; ?></span></li>
				<li id="reduce3" class="current"><span><?php echo $LNG->ISSUE_PHASE_REDUCE; ?></span></li>
				<li id="decide3"><span><?php echo $LNG->ISSUE_PHASE_DECIDE; ?></span></li>
				<li id="end3"><span><?php echo $LNG->ISSUE_PHASE_END; ?></span></li>
			</ul>
		</h2>
		<?php insertLemoningDates('IssueReducingDates'); ?>

		<h2 style="padding-top:10px;">
			<ul class="phasearrow" style="padding:0px;">
				<li id="start4"><span><?php echo $LNG->ISSUE_PHASE_START; ?></span></li>
				<li id="discuss4"><span><?php echo $LNG->ISSUE_PHASE_DISCUSS; ?></span></li>
				<li id="reduce4"><span><?php echo $LNG->ISSUE_PHASE_REDUCE; ?></span></li>
				<li id="decide4" class="current"><span><?php echo $LNG->ISSUE_PHASE_DECIDE; ?></span></li>
				<li id="end4"><span><?php echo $LNG->ISSUE_PHASE_END; ?></span></li>
			</ul>
		</h2>
		<?php insertVotingDates('IssueVotingDates'); ?>
	</div>
<?php }


/**
 * Insert the project dates form fields
 * @param hintname the string representing the key to call the rollover hint for the field.
 */
function insertIssueDates($hintname) {
	global $CFG,$LNG,$sdt,$edt,$utcstarttime,$utcendtime,$HUB_FLM; ?>

    <div id="datediv" class="formrow" style="display: block;padding-top:5px;">
		<input type="hidden" id="utcstarttime" name="utcstarttime" value="<?php echo $utcstarttime; ?>">
		<input type="hidden" id="utcendtime" name="utcendtime" value="<?php echo $utcendtime; ?>">

		<label class="formlabelbig" for="startdate"><?php echo $LNG->FORM_LABEL_DEBATE_START_DATE; ?>
			<span onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)"><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="margin-top: 2px; margin-left: 5px; margin-right: 2px;" /></span>
		</label>
		<input class="forminput dateinput" onchange="issueStartDateChanged(this)" id="startdate" name="startdate" value="">
        <img src="<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/cal.gif" onclick="javascript:NewCssCal('<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/','startdate','DDMMMYYYY','dropdown',true,'24')" style="cursor:pointer"/>

		<label style="padding-left:10px;" for="enddate"><b> <?php echo $LNG->FORM_LABEL_DEBATE_END_DATE; ?> </b></label>
		<input class="dateinput" onchange="issueEndDateChanged(this)" id="enddate" name="enddate" value="">
        <img src="<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/cal.gif" onclick="javascript:NewCssCal('<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/','enddate','DDMMMYYYY','dropdown',true,'24')" style="cursor:pointer"/>
	</div>
<?php }

/**
 * Insert the discussion switch on date and end on date form fields
 * @param hintname the string representing the key to call the rollover hint for the field.
 */
function insertDiscussionDates($hintname) {
	global $CFG,$LNG,$utcstarttime,$utcendtime,$discussionend,$discuissionon,$utcdiscussionendtime,$HUB_FLM; ?>

    <div id="datediv" class="formrow" style="display: block;">
		<input type="hidden" id="utcdiscussionendtime" name="utcdiscussionendtime" value="<?php echo $utcdiscussionendtime; ?>">

		<input type="checkbox" style="float:left;" id="discussionon" name="discussionon" value="Y" checked disabled>

		<label style="font-weight:bold"><?php echo $LNG->FORM_LABEL_DISCUSSION_START_DATE; ?></label>

		<label style="padding-left:10px;" for="discussionend"><b> <?php echo $LNG->FORM_LABEL_DISCUSSION_END_DATE; ?> </b>
			<span onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)"><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="margin-top: 2px; margin-left: 5px; margin-right: 2px;" /></span>
		</label>
		<input <?php if ($utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> class="dateinput" onchange="discussionEndDateChanged(this)" id="discussionend" name="discussionend" value="">
        <img id="discussionendcalendar" src="<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/cal.gif" onclick="showCalendar('<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/','discussionend','DDMMMYYYY','dropdown',true,'24')" style="cursor:pointer;"/>
	</div>
<?php }


/**
 * Insert the reducing/lemoning switch on date and end on date form fields
 * @param hintname the string representing the key to call the rollover hint for the field.
 */
function insertLemoningDates($hintname) {
	global $CFG,$LNG,$utcstarttime,$utcendtime,$lemoningstart, $lemoningend,$lemoningon,$utclemoningstarttime,$utclemoningendtime, $HUB_FLM; ?>

    <div id="datediv" class="formrow" style="display: block;">
		<input type="hidden" id="utclemoningstarttime" name="utclemoningstarttime" value="<?php echo $utclemoningstarttime; ?>">
		<input type="hidden" id="utclemoningendtime" name="utclemoningendtime" value="<?php echo $utclemoningendtime; ?>">

		<input <?php if ($utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> type="checkbox" onchange="lemoningCheckedbox()" style="float:left;" id="lemoningon" name="lemoningon" value="Y" <?php if($lemoningon == 'Y'){echo 'checked';} ?>>

		<label  class="formlabelbig" for="lemoningstart"><?php echo $LNG->FORM_LABEL_LEMONING_START_DATE; ?>
			<span onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)"><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="margin-top: 2px; margin-left: 5px; margin-right: 2px;" /></span>
		</label>
		<input <?php if ($lemoningon == 'N' || $utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> readonly class="forminput dateinput" id="lemoningstart" name="lemoningstart" value="">

		<label style="padding-left:10px;" for="lemoningend"><b> <?php echo $LNG->FORM_LABEL_LEMONING_END_DATE; ?> </b>
			<span onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)"><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="margin-top: 2px; margin-left: 5px; margin-right: 2px;" /></span>
		</label>
		<input <?php if ($lemoningon == 'N' || $utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> class="dateinput" onchange="lemoningEndDateChanged(this)" id="lemoningend" name="lemoningend" value="">
        <img src="<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/cal.gif" onclick="showCalendar('<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/','lemoningend','DDMMMYYYY','dropdown',true,'24')" style="cursor:pointer;"/>
	</div>
<?php }

/**
 * Insert the voting switch on date and end on date form fields
 * @param hintname the string representing the key to call the rollover hint for the field.
 */
function insertVotingDates($hintname) {
	global $CFG,$LNG,$utcstarttime,$utcendtime,$votingstart,$votingend,$votingon,$utcvotingstarttime,$utcvotingendtime,$HUB_FLM; ?>

    <div id="datediv" class="formrow" style="display: block;">
		<input type="hidden" id="utcvotingstarttime" name="utcvotingstarttime" value="<?php echo $utcvotingstarttime; ?>">
		<input type="hidden" id="utcvotingendtime" name="utcvotingendtime" value="<?php echo $utcvotingendtime; ?>">

		<input <?php if ($utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> type="checkbox" onchange="votingCheckedbox()" style="float:left;" id="votingon" name="votingon" value="Y" <?php if( $votingon == 'Y'){ echo 'checked'; } ?>>

		<label  class="formlabelbig" for="votingstart"><?php echo $LNG->FORM_LABEL_VOTING_START_DATE; ?>
			<span onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)"><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="margin-top: 2px; margin-left: 5px; margin-right: 2px;" /></span>
		</label>
		<input <?php if ($votingon == 'N' || $utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> class="forminput dateinput" readonly id="votingstart" name="votingstart" value="">

		<label style="padding-left:10px;" for="votingend"><b> <?php echo $LNG->FORM_LABEL_VOTING_END_DATE; ?> </b>
			<span onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)"><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="margin-top: 2px; margin-left: 5px; margin-right: 2px;" /></span>
		</label>
		<input <?php if ($votingon == 'N' || $utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> class="dateinput" id="votingend" readonly name="votingend" value="">
	</div>
<?php }
?>

