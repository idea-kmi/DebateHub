<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2015 - 2024 The Open University UK                            *
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
			<span class="active" onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)"><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" style="margin-top: 2px; margin-left: 5px; margin-right: 2px;" /></span>
			<span style="font-size:14pt;margin-top:3px;vertical-align:middle;color:red;">*</span>
		</label>
		<input class="forminputmust hgrinput hgrwide" id="summary" name="summary" value="<?php echo( $summary ); ?>" />
	</div>
<?php }

function insertDescription($hintname) {
	global $desc, $CFG, $LNG, $HUB_FLM; ?>
	<div class="mb-3 row" id="descdiv">
		<label for="desc" class="col-sm-3 col-form-label">
			<?php echo $LNG->FORM_LABEL_DESC; ?>
			<a class="active" onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)">
				<i class="far fa-question-circle fa-lg me-2" aria-hidden="true" ></i> 
				<span class="sr-only">More info</span>
			</a>
			<span class="required">*</span>
			<br />		
			<a id="editortogglebutton" href="javascript:void(0)" onclick="switchCKEditorMode(this, 'textareadiv', 'desc')" title="<?php echo $LNG->FORM_DESC_HTML_TEXT_HINT; ?>"><?php echo $LNG->FORM_DESC_HTML_TEXT_LINK; ?></a>
		</label>
		<div class="col-sm-9" id="textareadiv">
			<?php if (isProbablyHTML($desc)) { ?>
				<textarea rows="4" class="ckeditor form-control" id="desc" name="desc"><?php echo( $desc ); ?></textarea>
			<?php } else { ?>
				<textarea rows="4" class="form-control" id="desc" name="desc"><?php echo( $desc ); ?></textarea>
			<?php } ?>
		</div>
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

	<div class="mb-3 row border border-1 p-3">
		<h3><?php echo $LNG->ISSUE_OPEN_TITLE; ?></h3>
		<p><?php echo $LNG->ISSUE_OPEN_HELP ;?></p>
	</div>

	<div class="mb-3 row border border-1 p-3">
		<h3><?php echo $LNG->ISSUE_TIMING_TITLE; ?></h3>
		<p><?php echo $LNG->ISSUE_TIMING_HELP ;?></p>

		<h3>
			<div id="start" class="phasearrow">
				<div id="start2" class="step current"><span><?php echo $LNG->ISSUE_PHASE_START; ?></span></div>
				<div id="discuss2" class="step"><span>&nbsp;</span></div>
				<div id="reduce2" class="step"><span>&nbsp;</span></div>
				<div id="decide2" class="step"><span>&nbsp;</span></div>
				<div id="end2" class="step current"><span><?php echo $LNG->ISSUE_PHASE_END; ?></span></div>
			</div>
		</h3>
		<?php insertIssueDates('IssueDates'); ?>
	</div>

	<div class="mb-3 row border border-1 p-3">
		<h3><?php echo $LNG->ISSUE_PHASING_TITLE; ?></h3>
		<p><?php echo $LNG->ISSUE_PHASING_HELP; ?></p>
		
		<h3>
			<div id="start" class="phasearrow">
				<div id="start2" class="step"><span><?php echo $LNG->ISSUE_PHASE_START; ?></span></div>
				<div id="discuss2" class="step current"><span><?php echo $LNG->ISSUE_PHASE_DISCUSS; ?></span></div>
				<div id="reduce2" class="step"><span><?php echo $LNG->ISSUE_PHASE_REDUCE; ?></span></div>
				<div id="decide2" class="step"><span><?php echo $LNG->ISSUE_PHASE_DECIDE; ?></span></div>
				<div id="end2" class="step"><span><?php echo $LNG->ISSUE_PHASE_END; ?></span></div>
			</div>
		</h3>
		<?php insertDiscussionDates('IssueDiscussionDates'); ?>

		<h3>
			<div class="phasearrow">
				<div id="start3" class="step"><span><?php echo $LNG->ISSUE_PHASE_START; ?></span></div>
				<div id="discuss3" class="step"><span><?php echo $LNG->ISSUE_PHASE_DISCUSS; ?></span></div>
				<div id="reduce3" class="step current"><span><?php echo $LNG->ISSUE_PHASE_REDUCE; ?></span></div>
				<div id="decide3" class="step"><span><?php echo $LNG->ISSUE_PHASE_DECIDE; ?></span></div>
				<div id="end3" class="step"><span><?php echo $LNG->ISSUE_PHASE_END; ?></span></div>
			</div>
		</h3>
		<?php insertLemoningDates('IssueReducingDates'); ?>

		<h3>
			<div class="phasearrow">
				<div id="start4" class="step"><span><?php echo $LNG->ISSUE_PHASE_START; ?></span></div>
				<div id="discuss4" class="step"><span><?php echo $LNG->ISSUE_PHASE_DISCUSS; ?></span></div>
				<div id="reduce4" class="step"><span><?php echo $LNG->ISSUE_PHASE_REDUCE; ?></span></div>
				<div id="decide4" class="step current"><span><?php echo $LNG->ISSUE_PHASE_DECIDE; ?></span></div>
				<div id="end4" class="step"><span><?php echo $LNG->ISSUE_PHASE_END; ?></span></div>
			</div>
		</h3>
		<?php insertVotingDates('IssueVotingDates'); ?>
	</div>
<?php }


/**
 * Insert the project dates form fields
 * @param hintname the string representing the key to call the rollover hint for the field.
 */
function insertIssueDates($hintname) {
	global $CFG,$LNG,$sdt,$edt,$utcstarttime,$utcendtime,$HUB_FLM; ?>

    <div id="datediv" class="row">
		<input type="hidden" id="utcstarttime" name="utcstarttime" value="<?php echo $utcstarttime; ?>">
		<input type="hidden" id="utcendtime" name="utcendtime" value="<?php echo $utcendtime; ?>">

		<div class="col">
			<div class="mb-3 me-2 row">
				<label class="col-sm col-form-label text-end" for="startdate">
					<?php echo $LNG->FORM_LABEL_DEBATE_START_DATE; ?>
					<a class="active" onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)">
						<i class="far fa-question-circle fa-lg me-2" aria-hidden="true" ></i> 
						<span class="sr-only">More info</span>
					</a>
				</label>
				<div class="col">
					<div class="input-group">
						<input class="form-control" onchange="issueStartDateChanged(this)" id="startdate" name="startdate" value="" aria-describedby="startdate-addon" />
						<span class="input-group-text" id="startdate-addon"><i class="far fa-calendar-alt" onclick="javascript:NewCssCal('<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/','startdate','DDMMMYYYY','dropdown',true,'24')" style="cursor:pointer"></i></span>
					</div>				
				</div>
			</div>
		</div>

		<div class="col">
			<div class="mb-3 row">
				<label class="col-sm col-form-label text-end" for="enddate">
					<?php echo $LNG->FORM_LABEL_DEBATE_END_DATE; ?>
				</label>
				<div class="col">
					<div class="input-group">
						<input class="form-control" onchange="issueEndDateChanged(this)" id="enddate" name="enddate" value="" aria-describedby="enddate-addon" />
						<span class="input-group-text" id="enddate-addon"><i class="far fa-calendar-alt" onclick="javascript:NewCssCal('<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/','enddate','DDMMMYYYY','dropdown',true,'24')" style="cursor:pointer"></i></span>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }

/**
 * Insert the discussion switch on date and end on date form fields
 * @param hintname the string representing the key to call the rollover hint for the field.
 */
function insertDiscussionDates($hintname) {
	global $CFG,$LNG,$utcstarttime,$utcendtime,$discussionend,$discuissionon,$utcdiscussionendtime,$HUB_FLM; ?>

    <div id="datediv" class="row">
		<input type="hidden" id="utcdiscussionendtime" name="utcdiscussionendtime" value="<?php echo $utcdiscussionendtime; ?>">

		<div class="col-auto">	
			<div class="mt-2 ms-1 row">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" name="discussionon" value="Y" id="discussionon" checked disabled />
					<label class="form-check-label sr-only" for="discussionon">Discussion on</label>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="mb-3 row">
				<label class="col-sm col-form-label text-end" for="discussionstart">
					<?php echo $LNG->FORM_LABEL_DISCUSSION_START_DATE; ?>
				</label>
			</div>
		</div>
		<div class="col">
			<div class="mb-3 row">
				<label class="col-sm col-form-label text-end" for="discussionend">
					<?php echo $LNG->FORM_LABEL_DISCUSSION_END_DATE; ?>
					<a class="active" onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)">
						<i class="far fa-question-circle fa-lg me-2" aria-hidden="true" ></i> 
						<span class="sr-only">More info</span>
					</a>
				</label>
				<div class="col">
					<div class="input-group">
						<input class="form-control" onchange="discussionEndDateChanged(this)" id="discussionend" name="discussionend" value="" aria-describedby="discussionend-addon" <?php if ($utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> />
						<span class="input-group-text" id="discussionend-addon"><i class="far fa-calendar-alt" onclick="javascript:NewCssCal('<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/','discussionend','DDMMMYYYY','dropdown',true,'24')" style="cursor:pointer"></i></span>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }


/**
 * Insert the reducing/lemoning switch on date and end on date form fields
 * @param hintname the string representing the key to call the rollover hint for the field.
 */
function insertLemoningDates($hintname) {
	global $CFG,$LNG,$utcstarttime,$utcendtime,$lemoningstart, $lemoningend,$lemoningon,$utclemoningstarttime,$utclemoningendtime, $HUB_FLM; ?>

    <div id="datediv" class="row">
		<input type="hidden" id="utclemoningstarttime" name="utclemoningstarttime" value="<?php echo $utclemoningstarttime; ?>">
		<input type="hidden" id="utclemoningendtime" name="utclemoningendtime" value="<?php echo $utclemoningendtime; ?>">

		<div class="col-auto">	
			<div class="mt-2 ms-1 row">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" name="lemoningon" value="Y" id="lemoningon" onchange="lemoningCheckedbox()" <?php if ($utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> <?php if($lemoningon == 'Y'){echo 'checked';} ?> />
					<label class="form-check-label sr-only" for="lemoningon">Lemoning on</label>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="mb-3 row">
				<label class="col-sm col-form-label text-end" for="lemoningstart">
					<?php echo $LNG->FORM_LABEL_LEMONING_START_DATE; ?>
					<a class="active" onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)">
						<i class="far fa-question-circle fa-lg me-2" aria-hidden="true" ></i> 
						<span class="sr-only">More info</span>
					</a>
				</label>
				<div class="col">
					<input class="form-control" id="lemoningstart" name="lemoningstart" value="" <?php if ($lemoningon == 'N' || $utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> readonly  />
				</div>
			</div>
		</div>
		<div class="col">
			<div class="mb-3 row">
				<label class="col-sm col-form-label text-end" for="lemoningend">
					<?php echo $LNG->FORM_LABEL_LEMONING_END_DATE; ?>
					<a class="active" onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)">
						<i class="far fa-question-circle fa-lg me-2" aria-hidden="true" ></i> 
						<span class="sr-only">More info</span>
					</a>
				</label>
				<div class="col">
					<div class="input-group">
						<input class="form-control" onchange="lemoningEndDateChanged(this)" id="lemoningend" name="lemoningend" value="" aria-describedby="lemoningend-addon" <?php if ($lemoningon == 'N' || $utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> />
						<span class="input-group-text" id="lemoningend-addon"><i class="far fa-calendar-alt" onclick="showCalendar('<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/','lemoningend','DDMMMYYYY','dropdown',true,'24')" style="cursor:pointer"></i></span>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }

/**
 * Insert the voting switch on date and end on date form fields
 * @param hintname the string representing the key to call the rollover hint for the field.
 */
function insertVotingDates($hintname) {
	global $CFG,$LNG,$utcstarttime,$utcendtime,$votingstart,$votingend,$votingon,$utcvotingstarttime,$utcvotingendtime,$HUB_FLM; ?>

	<div id="datediv" class="row">
		<input type="hidden" id="utcvotingstarttime" name="utcvotingstarttime" value="<?php echo $utcvotingstarttime; ?>">
		<input type="hidden" id="utcvotingendtime" name="utcvotingendtime" value="<?php echo $utcvotingendtime; ?>">

		<div class="col-auto">	
			<div class="mt-2 ms-1 row">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" name="votingon" value="Y" id="votingon" onchange="votingCheckedbox()" <?php if ($utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> <?php if($votingon == 'Y'){echo 'checked';} ?> />
					<label class="form-check-label sr-only" for="votingon">Voting on</label>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="mb-3 row">
				<label class="col-sm col-form-label text-end" for="votingstart">
					<?php echo $LNG->FORM_LABEL_VOTING_START_DATE; ?>
					<a class="active" onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)">
						<i class="far fa-question-circle fa-lg me-2" aria-hidden="true" ></i> 
						<span class="sr-only">More info</span>
					</a>
				</label>
				<div class="col">
					<input class="form-control" id="votingstart" name="votingstart" value="" <?php if ($votingon == 'N' || $utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> readonly  />
				</div>
			</div>
		</div>
		<div class="col">
			<div class="mb-3 row">
				<label class="col-sm col-form-label text-end" for="votingend">
					<?php echo $LNG->FORM_LABEL_VOTING_END_DATE; ?>
					<a href="javascript:void(0)" onMouseOver="showFormHint('<?php echo $hintname; ?>', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)">
						<i class="far fa-question-circle fa-lg me-2" aria-hidden="true" ></i> 
						<span class="sr-only">More info</span>
					</a>
				</label>
				<div class="col">
					<input class="form-control" id="votingend" name="votingend" value="" readonly <?php if ($votingon == 'N' || $utcstarttime == 0 || $utcendtime == 0){ echo 'disabled'; } ?> />
				</div>
			</div>
		</div>
	</div>
<?php }
?>

