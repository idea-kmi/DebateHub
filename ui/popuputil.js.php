<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2015 - 2025 The Open University UK                            *
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
header('Content-Type: text/javascript;');
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
?>

/**
 * Display the form hint for the given field type.
 * Returns true if the hint was found and displayed else false.
 */
function showFormHint(type, evt, panelName, extra) {

 	var event = evt || window.event;

	$("resourceMessage").innerHTML="";

	// Issue Forms
	if (type == "IssueSummary") {
		$("resourceMessage").insert('<?php echo $LNG->ISSUE_SUMMARY_FORM_HINT; ?>');
	} else if (type == "IssueDesc") {
		$("resourceMessage").insert('<?php echo $LNG->ISSUE_DESC_FORM_HINT; ?>');
 	} else if (type == "IssuePhoto") {
		$("resourceMessage").insert('<?php echo $LNG->ISSUE_PHOTO_FORM_HINT; ?>');
	} else if (type == "IssueDates") {
		$("resourceMessage").insert('<?php echo $LNG->FORM_LABEL_DEBATE_DATES_HINT; ?>');
 	} else if (type == "IssueDiscussionDates") {
		$("resourceMessage").insert('<?php echo $LNG->FORM_LABEL_DISCUSSION_DATES_HINT; ?>');
 	} else if (type == "IssueReducingDates") {
		$("resourceMessage").insert('<?php echo $LNG->FORM_LABEL_LEMONING_DATES_HINT; ?>');
 	} else if (type == "IssueVotingDates") {
		$("resourceMessage").insert('<?php echo $LNG->FORM_LABEL_VOTING_DATES_HINT; ?>');

	// Group Forms
 	} else if (type == "GroupSummary") {
		$("resourceMessage").insert('<?php echo $LNG->GROUP_NAME_FORM_HINT; ?>');
 	} else if (type == "GroupDesc") {
		$("resourceMessage").insert('<?php echo $LNG->GROUP_DESC_FORM_HINT; ?>');
 	} else if (type == "GroupWebsite") {
		$("resourceMessage").insert('<?php echo $LNG->GROUP_WEBSITE_FORM_HINT; ?>');
 	} else if (type == "GroupPhoto") {
		$("resourceMessage").insert('<?php echo $LNG->GROUP_PHOTO_FORM_HINT; ?>');

	} else {
		return false;
	}

	showHint(event, panelName, 10, -10);

	return true;
}

/**
 * Remove the given multiple for the given type at the given index
 */
function removeMultiple(key, i) {
	var answer = confirm("<?php echo $LNG->FORM_REMOVE_MULTI; ?>");
    if(answer){
		if ($(key+'form') && $(key+'field'+i)) {
		    if(	$(key+'form').childElements()[0].nodeName.toUpperCase() != "HR"){
			    $(key+'field'+i).remove();
			    try {
			        $(key+'hr'+ i).remove();
			    } catch (err) {
			        // do nowt
			    }
			    if($(key+'form').childElements()[0] && $(key+'form').childElements()[0].nodeName.toUpperCase() == "HR"){
			        $(key+'form').childElements()[0].remove();
			    }
		    }
		}
    }
    return;
}

/**
 * Add another idea block - used in Split form.
 */
function addIdea(noIdeas) {

	var newitem = '<div id="ideafield'+noIdeas+'" class="formrow">';

	newitem += '<div class="formrowsm">';
	newitem += '<input placeholder="<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>" class="forminput hgrwide" id="ideaname-'+noIdeas+'" name="ideanamearray[]" value="">';
	newitem += '</div>';

	newitem += '<div class="formrowsm">';
	newitem += '<textarea rows="3" placeholder="<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>" class="forminput hgrwide" id="ideadesc-'+noIdeas+'" name="ideadescarray[]"></textarea>';
	newitem += '</div>';

	newitem += '<a id="idearemovebutton-'+noIdeas+'" href="javascript:void(0)" onclick="javascript:removeMultiple(\'idea\', \''+noIdeas+'\')" class="form" style="clear:both;float:right"><?php echo $LNG->FORM_BUTTON_REMOVE; ?></a><br>';
	newitem += '</div>';

	$('ideaformdiv').insert(newitem);

	noIdeas++;
	return noIdeas;
}

var initialising = false;

function initialisePhaseDates() {
	initialising = true;

	// Debate dates
	var time = $('utcstarttime').value;
	if (time > 0) {
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		$('startdate').value = fomatedDate;
	}

	if (time > 0) {
		var time = $('utcendtime').value;
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		$('enddate').value = fomatedDate;
	}

	if ($('utcstarttime').value != 0 && $('utcendtime').value != 0) {
		turnOnPhasing();
	}

	//Discussion Phase date
	var time = $('utcdiscussionendtime').value;
	if (time > 0) {
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		$('discussionend').value = fomatedDate;
	}

	//Lemoning Phase dates
	var time = $('utclemoningstarttime').value;
	if (time > 0) {
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		$('lemoningstart').value = fomatedDate;
	}

	if (time > 0) {
		var time = $('utclemoningendtime').value;
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		$('lemoningend').value = fomatedDate;
	}

	//Voting Phase dates
	var time = $('utcvotingstarttime').value;
	if (time > 0) {
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		$('votingstart').value = fomatedDate;
	}

	if (time > 0) {
		var time = $('utcvotingendtime').value;
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		$('votingend').value = fomatedDate;
	}

	initialising = false;
}

function issueStartDateChanged(obj) {

	if (initialising) {return;}

	clearIssuePhaseBackgrounds();

	var issuestartdate = Date.parse($('startdate').value);
	var issueenddate = Date.parse($('enddate').value);
	var discussionenddate = Date.parse($('discussionend').value);
	var lemoningstartdate = Date.parse($('lemoningstart').value);
	var lemoningenddate = Date.parse($('lemoningend').value);
	var votingstartdate = Date.parse($('votingstart').value);

	if (
		($('startdate').value != "" && $('enddate').value != ""
			&& issueenddate < issuestartdate)
	) {
		alert('<?php echo $LNG->FORM_ISSUE_START_END_DATE_ERROR; ?>');
		$('startdate').value = "";
		$('utcstarttime').value = 0;
		$('startdate').style.background = 'LightYellow ';
		setTimeout((function() { $('startdate').focus() }), 0);
	} else {
		var utctime = 0;
		if ($('startdate').value.trim() == "") {
			$('utcstarttime').value = 0;
		} else {
			var localtime = Date.parse(obj.value);
			var newDate = new Date(localtime);
			utctime = convertLocalDateToUTCTime(newDate);
			$('utcstarttime').value = utctime;
		}

		if ($('startdate').value.trim() != "" && ($('enddate').value).trim() != "") {
			turnOnPhasing();

			if (issuestartdate > discussionenddate) {
				$('discussionend').value = "";
				$('utcdiscussionendtime').value = 0;
			}
			if (issuestartdate > lemoningstartdate) {
				$('lemoningstart').value = "";
				$('utclemoningstarttime').value = 0;
			}
			if (issuestartdate > lemoningenddate) {
				$('lemoningend').value = "";
				$('utclemoningendtime').value = 0;
			}
			if (issuestartdate > votingstartdate) {
				$('votingstart').value = "";
				$('utcvotingstarttime').value = 0;
			}
		} else {
			turnOffPhasing();

			// highlight next field
			if ($('enddate').value == "") {
				$('enddate').style.background = 'LightYellow ';
				setTimeout((function() { $('enddate').focus() }), 0);
			}
		}
	}
}

function issueEndDateChanged(obj) {
	if (initialising) {return;}

	clearIssuePhaseBackgrounds();

	var issuestartdate = Date.parse($('startdate').value);
	var issueenddate = Date.parse($('enddate').value);
	var discussionenddate = Date.parse($('discussionend').value);
	var lemoningstartdate = Date.parse($('lemoningstart').value);
	var lemoningenddate = Date.parse($('lemoningend').value);
	var votingstartdate = Date.parse($('votingstart').value);
	if (
		($('startdate').value != "" && $('enddate').value != ""
			&& issueenddate < issuestartdate)
	) {
		alert('<?php echo $LNG->FORM_ISSUE_START_END_DATE_ERROR; ?>');
		$('enddate').value = "";
		$('utcendtime').value = 0;
		$('enddate').style.background = 'LightYellow ';
		setTimeout((function() { $('enddate').focus() }), 0);
	} else {
		var utctime = 0;
		if ($('enddate').value.trim() == "") {
			$('utcendtime').value = 0;
		} else {
			var localtime = Date.parse(obj.value);
			var newDate = new Date(localtime);
			utctime = convertLocalDateToUTCTime(newDate);
			$('utcendtime').value = utctime;
		}

		if ($('startdate').value.trim() != "" && ($('enddate').value).trim() != "") {
			turnOnPhasing();
			if ($('votingon').checked) {
				$('votingend').value = $('enddate').value;
				$('utcvotingendtime').value = utctime;
			}
		} else {
			turnOffPhasing();
			if ( ($('startdate').value).trim() == "") {
				$('startdate').style.background = 'LightYellow ';
				setTimeout((function() { $('startdate').focus() }), 0);
			}
		}

		if (issueenddate < discussionenddate) {
			$('discussionend').value = "";
			$('utcdiscussionendtime').value = 0;
		}
		if (issueenddate < lemoningstartdate) {
			$('lemoningstart').value = "";
			$('utclemoningstarttime').value = 0;
		}
		if (issueenddate < lemoningenddate) {
			$('lemoningend').value = "";
			$('utclemoningendtime').value = 0;
		}
		if (issueenddate < votingstartdate) {
			$('votingstart').value = "";
			$('utcvotingstarttime').value = 0;
		}
	}
}

function discussionEndDateChanged(obj) {
	if (initialising) {return;}

	clearIssuePhaseBackgrounds();

	var startdate = Date.parse($('startdate').value);
	var discussionenddate = Date.parse($('discussionend').value);
	var issueenddate = Date.parse($('enddate').value);
	var lemoningenddate = Date.parse($('lemoningend').value);
	if (
		($('discussionend').value != "" && discussionenddate <= startdate) ||
		($('enddate').value != "" && discussionenddate > issueenddate)
	) {
		alert('<?php echo $LNG->FORM_ISSUE_DISCUSSION_END_DATE_ERROR; ?>');
		$('discussionend').value = "";
		$('utcdiscussionendtime').value = 0;
		$('discussionend').style.background = 'LightYellow ';
		setTimeout((function() { $('discussionend').focus() }), 0);
	} else {
		var localtime = Date.parse(obj.value);
		var newDate = new Date(localtime);
		var utctime = convertLocalDateToUTCTime(newDate);
		$('utcdiscussionendtime').value = utctime;

		if ($('lemoningon').checked) {
			$('lemoningstart').value = $('discussionend').value;
			$('utclemoningstarttime').value = $('utcdiscussionendtime').value;

			if (lemoningenddate <= discussionenddate) {
				$('lemoningend').value = "";
				$('utclemoningendtime').value = 0;
			}

			// highlight next field if empty
			if ($('lemoningend').value == ""){
				$('lemoningend').style.background = 'LightYellow ';
				setTimeout((function() { $('lemoningend').focus() }), 0);
			}
		} else if ($('votingon').checked) {
			$('votingstart').value = $('discussionend').value;
			$('utcvotingstarttime').value = $('utcdiscussionendtime').value;
		}
	}
}

function lemoningEndDateChanged(obj) {
	if (initialising) {return;}

	clearIssuePhaseBackgrounds();

	var lemonstartdate = Date.parse($('lemoningstart').value);
	var lemonenddate = Date.parse($('lemoningend').value);
	var discussionenddate = Date.parse($('discussionend').value);
	var issueenddate = Date.parse($('enddate').value);
	var votingstartdate = Date.parse($('votingstart').value);

	if ($(
		('lemoningstart').value != "" && $('lemoningend').value != "" && lemonenddate <= lemonstartdate ) ||
		($('enddate').value != "" && $('discussionend').value != "" && lemonenddate <= discussionenddate) ||
		($('enddate').value != "" && $('lemoningend').value != "" && lemonenddate > issueenddate)
		) {
		alert('<?php echo $LNG->FORM_ISSUE_LEMONING_END_DATE_ERROR; ?>');
		$('lemoningend').value = "";
		$('utclemoningendtime').value = 0;
		$('lemoningend').style.background = 'LightYellow ';
		setTimeout((function() { $('lemoningend').focus() }), 0);
	} else {
		var localtime = Date.parse(obj.value);
		var newDate = new Date(localtime);
		var utctime = convertLocalDateToUTCTime(newDate);
		$('utclemoningendtime').value = utctime;

		if ($('votingon').checked) {
			$('votingstart').value = $('lemoningend').value;
			$('utcvotingstarttime').value = $('utclemoningendtime').value;
		}
	}
}

function lemoningCheckedbox() {
	clearIssuePhaseBackgrounds();
	if ($('lemoningon').checked) {
		$('lemoningstart').disabled = false;
		$('lemoningend').disabled = false;

		if ($('discussionend').value != "" && $('discussionend').value != $('enddate').value) {
			$('lemoningstart').value = $('discussionend').value;
			$('utclemoningstarttime').value = $('utcdiscussionendtime').value;
		} else {
			$('discussionend').value = "";
			$('utcdiscussionendtime').value = 0;
			$('discussionend').style.background = 'LightYellow ';
			setTimeout((function() { $('discussionend').focus() }), 0);
		}
	} else {
		$('lemoningstart').value = "";
		$('lemoningend').value = "";
		$('utclemoningstarttime').value = 0;
		$('utclemoningendtime').value = 0;
		$('lemoningstart').style.background = null;
		$('lemoningend').style.background = null;
		$('lemoningstart').disabled = true;
		$('lemoningend').disabled = true;

		if ($('votingon').checked) {
			$('votingstart').value = $('discussionend').value;
			$('utcvotingstarttime').value = $('utcdiscussionendtime').value;
		}
	}
}

function votingCheckedbox() {
	clearIssuePhaseBackgrounds();

	if ($('votingon').checked) {
		$('votingend').value = $('enddate').value;
		$('utcvotingendtime').value = $('utcendtime').value;
		if ($('lemoningon').checked && $('lemoningend').value != "") {
			$('votingstart').value = $('lemoningend').value;
			$('utcvotingstarttime').value = $('utclemoningendtime').value;
		} else if ($('discussionend').value != "") {
			$('votingstart').value = $('discussionend').value;
			$('utcvotingstarttime').value = $('utcdiscussionendtime').value;
		}
	} else {
		$('votingstart').value = "";
		$('votingend').value = "";
		$('utcvotingstarttime').value = 0;
		$('utcvotingendtime').value = 0;
	}
}

function clearIssuePhaseBackgrounds() {
	$('startdate').style.background = 'white';
	$('enddate').style.background = 'white';
	if ($('discussionend').disabled == false) {
		$('discussionend').style.background = 'white';
	}
	if ($('lemoningend').disabled == false) {
		$('lemoningend').style.background = 'white';
	}
}

function showCalendar(url, field, format, type, time, hours) {
	if ($('utcendtime').value != 0 && $('utcstarttime').value != 0) {
		NewCssCal(url, field, format, type, time, hours);
	}
}

function turnOnPhasing() {
 	$('discussionend').disabled = false;
 	$('lemoningon').disabled = false;
 	$('votingon').disabled = false;
 	return;
}

function turnOffPhasing() {
 	$('discussionend').disabled = true;

	$('discussionend').value = "";
	$('utcdiscussionendtime').value = 0;

	// clear and turn off lemongin
 	$('lemoningon').checked = false;
 	$('lemoningon').disabled = true;
 	$('lemoningend').disabled = true;

	$('lemoningstart').value = "";
	$('lemoningend').value = "";
	$('utclemoningstarttime').value = 0;
	$('utclemoningendtime').value = 0;

	// clear and turn off voting phase
 	$('votingon').checked = false;
 	$('votingon').disabled = true;

	$('votingstart').value = "";
	$('votingend').value = "";
	$('utcvotingstarttime').value = 0;
	$('utcvotingendtime').value = 0;
}