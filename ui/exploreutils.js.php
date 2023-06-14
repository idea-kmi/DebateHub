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

    include_once("../config.php");
?>

var phasetimer;
var currentphase = '';

var TABS = {"remaining":true, "removed":true};
var DEFAULT_TAB = 'remaining';
var CURRENT_TAB = DEFAULT_TAB;
var DATA_LOADED = {"remaining":false, "removed":false};

var stpRemaining = setTabPushed.bindAsEventListener($('tab-remaining'),'remaining');
var stpRemoved = setTabPushed.bindAsEventListener($('tab-removed'),'removed');

/**
 *	Intial data and mode
 */
Event.observe(window, 'load', function() {
	currentphase = calculateIssuePhase(nodeObj); // set the initial phase.
	NODE_ARGS['currentphase'] = currentphase;
	NODE_ARGS['issueHasLemoning'] = false;

	if (currentphase == DISCUSS_PHASE
		|| currentphase == OPEN_PHASE
		|| currentphase == OPEN_VOTEPENDING_PHASE
		|| currentphase == TIMED_PHASE
		|| currentphase == TIMED_NOVOTE_PHASE
		|| currentphase == TIMED_VOTEPENDING_PHASE
	) {
		NODE_ARGS['issueDiscussing'] = true;
	} else {
		NODE_ARGS['issueDiscussing'] = false;
		NODE_ARGS['issueDebating'] = false;
	}
	if (currentphase == DECIDE_PHASE
		|| currentphase == TIMED_VOTEON_PHASE
		|| currentphase == OPEN_VOTEON_PHASE
		|| currentphase == OPEN_PHASE
		|| currentphase == TIMED_PHASE) {
		NODE_ARGS['issueVoting'] = true;
	} else {
		NODE_ARGS['issueVoting'] = false;
	}

	// hide phase arrows bar for certain phases.
	if (currentphase == CLOSED_PHASE
			|| currentphase == OPEN_PHASE
			|| currentphase == TIMED_PHASE
			|| currentphase == TIMED_NOVOTE_PHASE
			|| currentphase == PENDING_PHASE) {
		if ($('phaseindicator')) { $('phaseindicator').style.display = 'none'; }
	} else {
		if ($('phaseindicator')) { $('phaseindicator').style.display = 'block'; }
	}

	var lemonstart = 0;
	if (nodeObj.properties.lemoningstart) {
		lemonstart = parseInt(nodeObj.properties.lemoningstart);
	}
	var lemonend = 0;
	if (nodeObj.properties.lemoningend) {
		lemonend = parseInt(nodeObj.properties.lemoningend);
	}
	if (lemonstart > 0 && lemonend > 0) {
		NODE_ARGS['issueHasLemoning'] = true;
	}

	if (currentphase != CLOSED_PHASE) {
		var votestart = 0;
		if (nodeObj.properties.votingstart) {
			votestart = parseInt(nodeObj.properties.votingstart);
		}

		if (NODE_ARGS['issueDiscussing']) {
			if (currentphase == DISCUSS_PHASE
					|| currentphase == TIMED_VOTEPENDING_PHASE
					|| currentphase == OPEN_VOTEPENDING_PHASE) {
				if ($('discuss1')) { $('discuss1').className = 'step current'; }
				if ($('discusshelp')) { $('discusshelp').style.display = 'block'; }
			}
			if ($('addnewideaarea')) { $('addnewideaarea').style.display = 'block'; }
			if ($('moderatoralerts')) { $('moderatoralerts').style.display = 'block'; }
			if ($('useralerts')) { $('useralerts').style.display = 'block'; }
			if ($('moderatebutton')) { $('moderatebutton').style.display = 'block'; }
		}
		if (lemonstart > 0 && lemonend > 0) {
			if (currentphase == REDUCE_PHASE) {
				if ($('discuss1')) { $('discuss1').className = 'step'; }
				if ($('reduce1')) { $('reduce1').className = 'step current'; }
				if ($('reducehelp')) {$('reducehelp').style.display = 'block'; }
				if ($('lemonbasket')) {	$('lemonbasket').style.display = 'block'; }
				if ($('dashboardbutton')) { $('dashboardbutton').style.display = 'none'; }
				if ($('healthindicatorsdiv')) { $('healthindicatorsdiv').style.display = 'none'; }
			}
		} else {
			if ($('reduce1')) { $('reduce1').style.display = 'none'; }
		}
		if (votestart > 0) {
			if (currentphase == DECIDE_PHASE || currentphase == TIMED_VOTEON_PHASE
					|| currentphase == OPEN_VOTEON_PHASE) {
				if ($('discuss1')) { $('discuss1').className = 'step'; }
				if($('decide1')) { $('decide1').className = 'step current'; }
				if($('decidehelp')) { $('decidehelp').style.display = 'block'; }
			}
		} else {
			if ($('decide1')) { $('decide1').style.display = 'none'; }
		}
	}

	refreshMainIssue();

	if ((currentphase == DECIDE_PHASE || currentphase == CLOSED_PHASE) && NODE_ARGS['issueHasLemoning']) {
		$('tabber').style.display = 'block';
		Event.observe('tab-remaining','click', stpRemaining);
		Event.observe('tab-removed','click', stpRemoved);

		setTabPushed($('tab-'+DEFAULT_TAB),DEFAULT_TAB);

		refreshSolutions();
	} else {
		if ($('tabber')) {
			$('tabber').style.display = 'none';
		}
		refreshSolutions();
	}

	if (currentphase != REDUCE_PHASE) {
		if ($('health-viewing')) {
			loadViewingStats();
		}
		if ($('health-debate')) {
			loadContributionStats();
		}
	}

	if ($('moderatoralerts') && $('moderatoralerts').style.display == 'block') {
		loadModeratorAlertsData($('moderatoralerts-issue-div'), $('moderatoralerts-user-div'), $('moderatoralerts-messagearea'), nodeObj.nodeid);
	}

	if (USER && USER != "" && $('useralerts-issue-div') && $('useralerts').style.display == 'block') {
		loadUserAlertsData($('useralerts-issue-div'), $('useralerts-user-div'), $('useralerts-messagearea'), nodeObj.nodeid);
	}

	// if this is some sort of phased debate where the phase can change,
	// set up a timer to refresh the page when phases change
	if (currentphase != CLOSED_PHASE && currentphase != OPEN_PHASE && currentphase != OPEN_VOTEON_PHASE) {
		phasetimer = setInterval(refreshIssueWhenPhaseChanges, 1000); // check every half a minute
	}
});

/**
 *	switch between tabs
 */
function setTabPushed(e) {

	var data = $A(arguments);
	var tab = data[1];

	// Check tab is know else default to default
	if (!TABS.hasOwnProperty(tab)) {
		tab = DEFAULT_TAB;
	}
	for (i in TABS){
		if ($("tab-"+i)) {
			if(tab == i){
				if($("tab-"+i)) {
					$("tab-"+i).removeClassName("unselected");
					$("tab-"+i).addClassName("current");
					if ($("tab-content-"+i+"-div")) {
						$("tab-content-"+i+"-div").show();
					}
				}
			} else {
				if($("tab-"+i)) {
					$("tab-"+i).removeClassName("current");
					$("tab-"+i).addClassName("unselected");
					if ($("tab-content-"+i+"-div")) {
						$("tab-content-"+i+"-div").hide();
					}
				}
			}
		}
	}

	CURRENT_TAB = tab;
	if (tab == "remaining") {
		$('tab-remaining').setAttribute("href",'#remaining');
		Event.stopObserving('tab-remaining','click');
		Event.observe('tab-remaining','click', stpRemaining);
		if(!DATA_LOADED.remaining) {
			loadsolutions(CONTEXT,NODE_ARGS);
			loadremovedsolutions(CONTEXT,NODE_ARGS);
		}
	} else if (tab == "removed") {
		$('tab-removed').setAttribute("href",'#removed');
		Event.stopObserving('tab-removed','click');
		Event.observe('tab-removed','click', stpRemoved);
	}
}


function refreshIssueWhenPhaseChanges() {
	var thisphase = calculateIssuePhase(nodeObj);

	if (thisphase == CLOSED_PHASE) {
		currentphase = '';
		NODE_ARGS['currentphase'] = "";
		clearInterval(phasetimer);
		window.location.reload(true);
	} else if (thisphase != currentphase) {
		NODE_ARGS['currentphase'] = thisphase;
		currentphase = thisphase;
		window.location.reload(true);
	}
}

function refreshMainIssue() {
	var itemobj = renderIssueNode("760","", nodeObj, 'mainnode', nodeObj.role, true, 'active', false, false, false, true, true);
	$('mainnodediv').update(itemobj);
}

function refreshStats() {
	if ($('health-debate')) {
		loadContributionStats();
	}
	if ($('health-participation')) {
		loadParticipationStats();
	}
}

function hasClass(obj, className) {
    var classNames = obj.className.split(' ');
    for (var i = 0; i < classNames.length; i++) {
        if (classNames[i].toLowerCase() == className.toLowerCase()) {
            return true;
        }
    }
    return false;
}

function showIndicatorDetails(type) {
	if (type != 'debate') {
		$("health-debate-details").style.display = 'none';
		$('health-debate').auditid = '';
	}
	if (type != 'participation') {
		$("health-participation-details").style.display = 'none';
		$('health-participation').auditid = '';
	}
	if (type != 'viewing') {
		$("health-viewing-details").style.display = 'none';
		$('health-viewing').auditid = '';
	}

	var details = $("health-"+type+"-details");
	if (details.style.display == 'none') {
		details.style.display = 'block';

		var id = new Date().getTime();
		if (type == 'debate') {
			$('health-debate').auditid = id;
			auditDebateStats(id);
		} else if (type == 'viewing') {
			$('health-viewing').auditid = id;
			auditViewingStats(id);
		} else if (type == 'participation') {
			$('health-participation').auditid = id;
			auditParticipationStats(id);
		}
	} else {
		details.style.display = 'none';

		if (type == 'debate') {
			$('health-debate').auditid = '';
		} else if (type == 'viewing') {
			$('health-viewing').auditid = '';
		} else if (type == 'participation') {
			$('health-participation').auditid = '';
		}
	}
}

/************************* STATS AUDITING ***********************************/

function auditExploreVisButtonClick(nodeid, testelementid, testevent, link) {

	<?php
	$nowtime = time();
	if ($nowtime >= $CFG->TEST_TRIAL_START && $nowtime < $CFG->TEST_TRIAL_END) { ?>
		var handler = function() {
			 location.href=link;
		}
		auditTesting(nodeid, testelementid, testevent, link, handler);
	<?php } else { ?>
		 location.href=link;
	<?php } ?>

	return true;
}

function auditParticipationStats(parentid) {
	<?php
	$nowtime = time();
	if ($nowtime >= $CFG->TEST_TRIAL_START && $nowtime < $CFG->TEST_TRIAL_END) { ?>

	var colour = $('health-participation').trafficlight;

	var state = '<parent>'+parentid+'</parent>';
	state += '<meta1>'+colour+'</meta1>';
	state += '<meta2>'+$('health-participation-count').innerHTML+'</meta2>';
	state += '<meta3></meta3>';

	auditTesting(NODE_ARGS['nodeid'], 'participationIndicatorV1', 'clickTraficLight', state);

	<?php } ?>
}

function auditParticipationStatsLink(link) {

	<?php
	$nowtime = time();
	if ($nowtime >= $CFG->TEST_TRIAL_START && $nowtime < $CFG->TEST_TRIAL_END) { ?>
		var handler = function() {
			 location.href=link;
		}
		var parentid = $('health-participation').auditid;
		var colour = $('health-participation').trafficlight;

		var state = '<parent>'+parentid+'</parent>';
		state += '<meta1>'+colour+'</meta1>';
		state += '<meta2>'+$('health-participation-count').innerHTML+'</meta2>';
		state += '<meta3><![CDATA['+link+']]></meta3>';

		auditTesting(NODE_ARGS['nodeid'], 'participationIndicatorV1', 'clickTraficLightLink', state, handler);
	<?php } else { ?>
		 location.href=link;
	<?php } ?>
	return true;
}

function auditViewingStats(parentid) {
	<?php
	$nowtime = time();
	if ($nowtime >= $CFG->TEST_TRIAL_START && $nowtime < $CFG->TEST_TRIAL_END) { ?>

	var colour = $('health-viewing').trafficlight;

	var state = '<parent>'+parentid+'</parent>';
	state += '<meta1>'+colour+'</meta1>';
	state += '<meta2>'+$('health-viewingpeople-count').innerHTML+"/"+$('health-viewinggroup-count').innerHTML+'</meta2>';
	state += '<meta3></meta3>';

	auditTesting(NODE_ARGS['nodeid'], 'viewingActivityIndicatorV1', 'clickTraficLight', state);

	<?php } ?>
}

function auditViewingStatsLink(link) {
	<?php
	$nowtime = time();
	if ($nowtime >= $CFG->TEST_TRIAL_START && $nowtime < $CFG->TEST_TRIAL_END) { ?>
		var handler = function() {
			 location.href=link;
		}

		var parentid = $('health-viewing').auditid;
		var colour = $('health-viewing').trafficlight;

		var state = '<parent>'+parentid+'</parent>';
		state += '<meta1>'+colour+'</meta1>';
		state += '<meta2>'+$('health-viewingpeople-count').innerHTML+"/"+$('health-viewinggroup-count').innerHTML+'</meta2>';
		state += '<meta3><![CDATA['+link+']]></meta3>';

		auditTesting(NODE_ARGS['nodeid'], 'viewingActivityIndicatorV1', 'clickTraficLightLink', state, handler);
	<?php } else { ?>
		 location.href=link;
	<?php } ?>

	return true;
}

function auditDebateStats(parentid) {
	<?php
	$nowtime = time();
	if ($nowtime >= $CFG->TEST_TRIAL_START && $nowtime < $CFG->TEST_TRIAL_END) { ?>

	var colour = $('health-debate').trafficlight;

	var state = '<parent>'+parentid+'</parent>';
	state += '<meta1>'+colour+'</meta1>';
	state += '<meta2>';
	state += $('health-debate').ideacount+" ideas,";
	state += $('health-debate').procount+" pros,";
	state += $('health-debate').concount+" cons,";
	state += $('health-debate').totalvotes+" votes,";
	state += $('health-debate').contributioncount+" total";
	state += '</meta2>';
	state += '<meta3></meta3>';

	auditTesting(NODE_ARGS['nodeid'], 'debateTypeIndicatorV1', 'clickTraficLight', state);

	<?php } ?>
}

function auditDebateStatsLink(link) {
	<?php
	$nowtime = time();
	if ($nowtime >= $CFG->TEST_TRIAL_START && $nowtime < $CFG->TEST_TRIAL_END) { ?>
		var handler = function() {
			 location.href=link;
		}

		var parentid = $('health-debate').auditid;

		var colour = $('health-debate').trafficlight;
		var state = '<parent>'+parentid+'</parent>';
		state += '<meta1>'+colour+'</meta1>';
		state += '<meta2>';
		state += $('health-debate').ideacount+" ideas,";
		state += $('health-debate').procount+" pros,";
		state += $('health-debate').concount+" cons,";
		state += $('health-debate').totalvotes+" votes,";
		state += $('health-debate').contributioncount+" total";
		state += '</meta2>';
		state += '<meta3><![CDATA['+link+']]></meta3>';

		auditTesting(NODE_ARGS['nodeid'], 'debateTypeIndicatorV1', 'clickTraficLightLink', state, handler);
	<?php } else { ?>
		 location.href=link;
	<?php } ?>

	return true;
}

/******************** STATS DRAWING *****************************/
/**
 * Draw the participation health indicator
 */
function loadParticipationStats() {
	var nodeid = nodeObj.nodeid;

	var args = {}; //must be an empty object to send down the url, or all the Array functions get sent too.
	args["nodeid"] = nodeid;
    args['style'] = "long";

	var reqUrl = SERVICE_ROOT + "&method=getdebateparticipationstats&" + Object.toQueryString(args);
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var stats = json.debateparticipationstats[0];
			var peoplecount = parseInt(stats.peoplecount);

			if ($('health-participation')) {
				$('health-participation-count').update(peoplecount);
				var person = peoplecount == 1 ? '<?php echo $LNG->STATS_OVERVIEW_PERSON; ?>' :'<?php echo $LNG->STATS_OVERVIEW_PEOPLE; ?>';
				$('health-participation-message').update(person+' '+'<?php echo $LNG->STATS_OVERVIEW_HEALTH_CONTRIBUTORS; ?>');
				if (peoplecount < 3) {
					$('health-participation').trafficlight = 'red';
					$('health-participation-red').className = 'trafficlightredon';
					$('health-participation-recomendation').update('<?php echo $LNG->STATS_OVERVIEW_HEALTH_PROBLEM; ?>');
				} else if (peoplecount >= 3 && peoplecount <= 5) {
					$('health-participation').trafficlight = 'orange';
					$('health-participation-orange').className = 'trafficlightorangeon';
					$('health-participation-recomendation').update('<?php echo $LNG->STATS_OVERVIEW_HEALTH_MAYBE_PROBLEM; ?>');
				} else if (peoplecount > 5) {
					$('health-participation').trafficlight = 'green';
					$('health-participation-green').className = 'trafficlightgreenon';
					$('health-participation-recomendation').update('<?php echo $LNG->STATS_OVERVIEW_HEALTH_NO_PROBLEM; ?>');
				}
				$('health-participation').style.display = 'block';
			}

			$('debatestatspeople'+nodeid).update(peoplecount);
		}
	});
}

/**
 * load and draw the Contribution health indicator
 */
function loadContributionStats() {

	var nodeid = nodeObj.nodeid;

	var args = {}; //must be an empty object to send down the url, or all the Array functions get sent too.
	args["nodeid"] = nodeid;
    args['style'] = "long";

	var reqUrl = SERVICE_ROOT + "&method=getdebatecontributionstats&" + Object.toQueryString(args);
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var stats = json.debatecontributionstats[0];

			var totalvotes = parseInt(stats.totalvotes);
			var positivevotes = parseInt(stats.positivevotes);
			var negativevotes = parseInt(stats.negativevotes);
			var ideacount = parseInt(stats.ideacount);
			var procount = parseInt(stats.procount);
			var concount = parseInt(stats.concount);

			var contributioncount = totalvotes+ideacount+procount+concount;

			$('health-debate').procount = procount;
			$('health-debate').concount = concount;
			$('health-debate').ideacount = ideacount;
			$('health-debate').totalvotes = totalvotes;
			$('health-debate').contributioncount = contributioncount;

			var ideaRatio = ideacount/contributioncount;
			var proRatio = procount/contributioncount;
			var conRatio = concount/contributioncount;
			var votingRatio = totalvotes/contributioncount;

			//If one of the four ratios <= 0.1 then red traffic light
			//If all of the four ratios are > 0.1 but one of them is <=0.2 then yellow traffic light.

			if (ideacount == 0 || procount == 0 || concount == 0 || totalvotes == 0
					|| ideaRatio <= 0.1 || votingRatio <= 0.1 || proRatio <= 0.1 || conRatio <= 0.1) {
				$('health-debate').trafficlight = 'red';

				var message = '<?php echo $LNG->STATS_DEBATE_CONTRIBUTION_MESSAGE; ?>';
				var needsAnd = false;
				if (ideacount == 0 || ideaRatio <= 0.1) {
					message += ' <?php echo $LNG->SOLUTIONS_NAME; ?>';
					needsAnd = true;
				}
				if (procount == 0 || proRatio <= 0.1) {
					if (needsAnd) {
						message += ' <?php echo $LNG->STATS_DEBATE_AND; ?>';
					}
					message += ' <?php echo $LNG->PROS_NAME; ?>';
					needsAnd = true;
				}
				if (concount == 0 || conRatio <= 0.1) {
					if (needsAnd) {
						message += ' <?php echo $LNG->STATS_DEBATE_AND; ?>';
					}
					message += ' <?php echo $LNG->CONS_NAME; ?>';
					needsAnd = true;
				}
				if (totalvotes == 0 || votingRatio <= 0.1) {
					if (needsAnd) {
						message += ' <?php echo $LNG->STATS_DEBATE_AND; ?>';
					}
					message += ' <?php echo $LNG->VOTES_NAME; ?>';
				}

				$('health-debate-message').update(message);
				$('health-debate-recomendation').update('<?php echo $LNG->STATS_OVERVIEW_HEALTH_PROBLEM; ?>');
				$('health-debate-red').className = "trafficlightredon";
			} else if (ideaRatio > 0.1 && votingRatio > 0.1 && proRatio > 0.1 && conRatio > 0.1
					&& (ideaRatio <= 0.2 || votingRatio <= 0.2 || proRatio <= 0.2 || conRatio <= 0.2)) {

				var message = '<?php echo $LNG->STATS_DEBATE_CONTRIBUTION_MESSAGE; ?>';
				var needsAnd = false;
				if (ideaRatio <= 0.2) {
					message += ' <?php echo $LNG->SOLUTIONS_NAME; ?>';
					needsAnd = true;
				}
				if (proRatio <= 0.2) {
					if (needsAnd) {
						message += ' <?php echo $LNG->STATS_DEBATE_AND; ?>';
					}
					message += ' <?php echo $LNG->PROS_NAME; ?>';
					needsAnd = true;
				}
				if (conRatio <= 0.2) {
					if (needsAnd) {
						message += ' <?php echo $LNG->STATS_DEBATE_AND; ?>';
					}
					message += ' <?php echo $LNG->CONS_NAME; ?>';
					needsAnd = true;
				}
				if (votingRatio <= 0.2) {
					if (needsAnd) {
						message += ' <?php echo $LNG->STATS_DEBATE_AND; ?>';
					}
					message += ' <?php echo $LNG->VOTES_NAME; ?>';
				}

				$('health-debate').trafficlight = 'orange';
				$('health-debate-message').update(message);
				$('health-debate-recomendation').update('<?php echo $LNG->STATS_OVERVIEW_HEALTH_MAYBE_PROBLEM; ?>');
				$('health-debate-orange').className = "trafficlightorangeon";
			} else {
				$('health-debate').trafficlight = 'green';
				$('health-debate-message').update('<?php echo $LNG->STATS_DEBATE_CONTRIBUTION_GREEN; ?>');
				$('health-debate-recomendation').update('<?php echo $LNG->STATS_OVERVIEW_HEALTH_NO_PROBLEM; ?>');
				$('health-debate-green').className = "trafficlightgreenon";
			}

			$('health-debate').style.display = 'block';
		}
	});
}

/**
 * load and draw the Viewing health indicator
 */
function loadViewingStats() {
	if (NODE_ARGS["groupid"] && NODE_ARGS["groupid"] != "") {

		var nodeid = nodeObj.nodeid;
		var args = {}; //must be an empty object to send down the url, or all the Array functions get sent too.
		args["nodeid"] = nodeid;
		args["groupid"] = NODE_ARGS["groupid"];

		var reqUrl = SERVICE_ROOT + "&method=getdebateviewingstats&" + Object.toQueryString(args);
		new Ajax.Request(reqUrl, { method:'get',
			onSuccess: function(transport){
				var json = transport.responseText.evalJSON();
				if(json.error){
					alert(json.error[0].message);
					return;
				}

				var stats = json.debateviewingstats[0];

				var groupmembercount = parseInt(stats.groupmembercount);
				var viewingmembercount = parseInt(stats.viewingmembercount);

				var ratio = viewingmembercount/groupmembercount;

				$('health-viewingpeople-count').update(viewingmembercount);
				$('health-viewinggroup-count').update(groupmembercount);

				var person = viewingmembercount == 1 ? '<?php echo $LNG->STATS_OVERVIEW_PERSON; ?>' :'<?php echo $LNG->STATS_OVERVIEW_PEOPLE; ?>';

				$('health-viewing-message').update(person+' '+'<?php echo $LNG->STATS_DEBATE_VIEWING_MESSAGE_PART1; ?>');
				$('health-viewing-message-part2').update('<?php echo $LNG->STATS_DEBATE_VIEWING_MESSAGE_PART2; ?>');


				if (ratio >= 0.5) {
					$('health-viewing').trafficlight = 'green';
					$('health-viewing-green').className = "trafficlightgreenon";
					$('health-viewing-recomendation').update('<?php echo $LNG->STATS_OVERVIEW_HEALTH_NO_PROBLEM; ?>');
				} else if (ratio < 0.5 && ratio >= 0.2) {
					$('health-viewing').trafficlight = 'orange';
					$('health-viewing-orange').className = "trafficlightorangeon";
					$('health-viewing-recomendation').update('<?php echo $LNG->STATS_OVERVIEW_HEALTH_MAYBE_PROBLEM; ?>');
				} else {
					$('health-viewing').trafficlight = 'red';
					$('health-viewing-red').className = "trafficlightredon";
					$('health-viewing-recomendation').update('<?php echo $LNG->STATS_OVERVIEW_HEALTH_PROBLEM; ?>');
				}

				$('health-viewing').style.display = 'block';
			}
		});
	}
}

function insertArgumentLink(uniQ, type) {
	var argumentLinkDiv = $('linksdiv'+type+uniQ);
	var count = parseInt(argumentLinkDiv.linkcount);
	count = count+1;
	argumentLinkDiv.linkcount = count;

	var weblink = new Element("input", {
		'class':'form-control mt-2',
		'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
		'id':'argumentlink'+type+uniQ+count,
		'name':'argumentlink'+type+uniQ+'[]',
		'value':''
	});

	argumentLinkDiv.insert(weblink);
	weblink.focus();
}

function insertIdeaLink(uniQ, type) {
	var argumentLinkDiv = $('linksdiv'+type+uniQ);
	var count = parseInt(argumentLinkDiv.linkcount);
	count = count + 1;
	argumentLinkDiv.linkcount = count;

	var weblink = new Element("input", {
		'class':'form-control mt-2',
		'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
		'id':'argumentlink'+type+uniQ+count,
		'name':'argumentlink'+type+uniQ+'[]',
		'value':''
	});

	argumentLinkDiv.insert(weblink);
	weblink.focus();
}

function removeArgumentLink(uniQ, type) {
	var linkItemDiv = $('linkitemdiv'+type+uniQ);
	linkItemDiv.remove();
}

function toggleOrganizeMode(obj, mode) {

	if (NODE_ARGS['mode'] == mode && mode == 'Organize' ) {
		obj = null;
		mode = "Gather";
	}
	NODE_ARGS['mode'] = mode;

	$('maininner').className='p-3';

	if ($('moderatebutton')) {
		$('moderatebutton').className = "btn btn-moderator d-grid gap-2 m-2";
	}

	if (obj != null) {
		if (mode == "Gather") {
			obj.className = "groupbutton modeback3 modeborder3pressed";
		} else if (mode == "Organize") {
			obj.className = "btn btn-moderator d-grid gap-2 m-2 active";
			$('maininner').className = "p-3 modebackinner1";
		}
	}

	setMode(mode);
}

function setMode(mode) {

	if ((currentphase != DECIDE_PHASE && currentphase != CLOSED_PHASE)) {
		var nodes = $("tab-content-idea-list").select('[class="nodecheck"]');
		nodes.each(function(name, index) {
			nodes[index].checked = false
		});
	}

	// New idea and merge idea forms.
	if (mode == "Organize") {
		if ($('addnewideaarea')) {
			$('addnewideaarea').style.display = "none";
		}
	} else if (mode == "Gather") {
		if ($('mergeideadiv')) {
			$('mergeideadiv').style.display = 'none';
		}
		if ($('mergeideaform')) {
			$('mergeideaform').style.display = "none";
		}
		if (currentphase == DISCUSS_PHASE
					|| currentphase == TIMED_VOTEPENDING_PHASE
					|| currentphase == OPEN_VOTEPENDING_PHASE) {		
			if ($('addnewideaarea')) {
				$('addnewideaarea').style.display = "block";
			}
		}
	}

	if (mode == "Organize" && $('mergeideaform')) {
		$('mergeideaform').style.display = "block";
	}

	// votebars
	var votebardiv = document.getElementsByName('votebardiv');
	if (votebardiv && votebardiv.length > 0) {
		var count = votebardiv.length;
		for (var i=0; i < count; i++) {
			var bar = votebardiv[i];
			bar.style.display = "none";
			if (mode == "Gather") {
				bar.style.display = "inline";
			}
		}
	}

	// checkboxes
	var checkboxes = document.getElementsByName('nodecheckcell');
	if (checkboxes.length > 0) {
		var count = checkboxes.length;
		for (var i=0; i < count; i++) {
			var boxitem = checkboxes[i];
			boxitem.style.display = "none";
			if (mode == "Organize") {
				if (IS_USER_ADMIN) { //boxitem.userid == USER &&
					boxitem.style.display = "block";
					boxitem.firstChild.disabled = false;
				} else {
					boxitem.style.display = "block";
					boxitem.firstChild.disabled = true;
				}
			}
		}
	}

	// splitbuttons
	var ideasplitbuttons = document.getElementsByName('ideasplitbutton');
	if (ideasplitbuttons.length > 0) {
		var count = ideasplitbuttons.length;
		for (var i=0; i < count; i++) {
			var item = ideasplitbuttons[i];
			item.style.display = "none";
			var uniQ = item.uniQ;
			if (mode == "Organize") {
				if (IS_USER_ADMIN && item.hasChildren == 'N') { //item.userid == USER &&
					item.style.display = "inline";
				}
			}
		}
	}

	// adjust arguments
	if (NODE_ARGS['issueVoting'] && NODE_ARGS['currentphase'] != DECIDE_PHASE) {
		var argumentvoteDiv = document.getElementsByName('argumentvotediv');
		if (argumentvoteDiv.length > 0) {
			var count = argumentvoteDiv.length;
			for (var i=0; i < count; i++) {
				var argumentdiv = argumentvoteDiv[i];
				argumentdiv.style.display = "table-cell";
			}
		}
	} else {
		var argumentvoteDiv = document.getElementsByName('argumentvotediv');
		if (argumentvoteDiv.length > 0) {
			var count = argumentvoteDiv.length;
			for (var i=0; i < count; i++) {
				var argumentdiv = argumentvoteDiv[i];
				argumentdiv.style.display = "none";
			}
		}
	}

	// this just makes the voting visible and not active.
	if (NODE_ARGS['issueVoting'] || NODE_ARGS["currentphase"] == CLOSED_PHASE) {
		var ideavoteDiv = document.getElementsByName('ideavotediv');
		if (ideavoteDiv.length > 0) {
			var count = ideavoteDiv.length;
			for (var i=0; i < count; i++) {
				var votediv = ideavoteDiv[i];
				votediv.style.display = "table-cell";
			}
		}
	} else {
		var ideavoteDiv = document.getElementsByName('ideavotediv');
		if (ideavoteDiv.length > 0) {
			var count = ideavoteDiv.length;
			for (var i=0; i < count; i++) {
				var votediv = ideavoteDiv[i];
				votediv.style.display = "none";
			}
		}
	}
}

function toggleNewIdea() {
	if ($('addformdividea').style.display == 'none') {
		$('addformdividea').style.display='block';
		$('newideadivbutton').src='<?php echo $HUB_FLM->getImagePath("arrow-up2.png"); ?>';
	} else {
		$('addformdividea').style.display='none';
		$('newideadivbutton').src='<?php echo $HUB_FLM->getImagePath("arrow-down2.png"); ?>';
	}
}

function toggleMergeIdeas() {
	if ($('mergeideadiv').style.display == 'none') {
		var toAdd = getSelectedNodeIDs($('tab-content-idea-list'));
		if(toAdd.length < 2){
			alert("<?php echo $LNG->FORM_IDEA_MERGE_MUST_SELECT; ?>");
			return;
		} else {
			$('mergeideadiv').style.display='block';
		}
	} else {
		$('mergeideadiv').style.display='none';
	}
}


function editInline(objno, type){
	cancelAllEdits(type);

	if ($('editformdiv'+type+objno)) {
		$('editformdiv'+type+objno).show();
	}
	if ($('textdiv'+type+objno)) {
		$('textdiv'+type+objno).hide();
	}

	if ($('editformvotediv'+type+objno)) {
		$('editformvotediv'+type+objno).hide();
	}
	if ($('editformuserdiv'+type+objno)) {
		$('editformuserdiv'+type+objno).hide();
	}
}

function hideAddForm(objno, type) {
	// hide add form
	if (NODE_ARGS['mode'] == 'Gather') {
		var addformdiv = $('addformdiv'+type+objno);
		if (addformdiv) {
			addformdiv.style.display = "none";
		}
	}
}

function showAddForm(objno, type) {
	if (NODE_ARGS['mode'] == 'Gather') {
		var addformdiv = $('addformdiv'+type+objno);
		if (addformdiv) {
			addformdiv.style.display = "block";
		}
	}
}

function cancelAllEdits(type) {
	var array = document.getElementsByTagName('div');
	for(var i=0;i < array.length;i++) {
		if (array[i].id.startsWith('editform'+type)) {
			var objno = array[i].id.substring(12);
			cancelEditAction(objno, type);
		}
	}
}

function cancelEditAction(objno, type){
	if ($('editformdiv'+type+objno)) {
		$('editformdiv'+type+objno).hide();
	}
	if ($('textdiv'+type+objno)) {
		$('textdiv'+type+objno).show();
	}
	if ($('editformvotediv'+type+objno)) {
		$('editformvotediv'+type+objno).show();
	}
	if ($('editformuserdiv'+type+objno)) {
		$('editformuserdiv'+type+objno).show();
	}

	if (type == "argument") {

	}
}

function checkIdeaAddForm() {
	var checkname = ($('idea').value).trim();
	if (checkname == ""){
	   alert("<?php echo $LNG->FORM_SOLUTION_ENTER_SUMMARY_ERROR; ?>");
	   return false;
    }

    $('ideaform').style.cursor = 'wait';

	return true;
}

function checkCommentAddForm(nodeid) {
	var checkname = ($('commenttitle'+nodeid).value).trim();
	if (checkname == ""){
	   alert("<?php echo $LNG->FORM_COMMENT_ENTER_SUMMARY_ERROR; ?>");
	   return false;
    }
    $('commentform'+nodeid).style.cursor = 'wait';

	return true;
}

function checkProAddForm(nodeid) {
	var checkname = ($('proarg'+nodeid).value).trim();
	if (checkname == ""){
	   alert("<?php echo $LNG->FORM_PRO_ENTER_SUMMARY_ERROR; ?>");
	   return false;
    }
    $('proform'+nodeid).style.cursor = 'wait';

	return true;
}

function checkConAddForm(nodeid) {
	var checkname = ($('conarg'+nodeid).value).trim();
	if (checkname == ""){
	   alert("<?php echo $LNG->FORM_CON_ENTER_SUMMARY_ERROR; ?>");
	   return false;
    }
    $('conform'+nodeid).style.cursor = 'wait';

	return true;
}

/**
 *	Called by forms to refresh the solution view
 */
function refreshSolutions() {
	loadsolutions(CONTEXT,NODE_ARGS);
}

/**
 *	load next/previous set of nodes
 */
function loadsolutions(context,args){

	var focalnodeid = args['nodeid'];
	var title = "<?php echo $LNG->SOLUTIONS_NAME; ?>";

	var reqUrl = SERVICE_ROOT;
	var container = $('tab-content-idea-list');
	if ((currentphase == DECIDE_PHASE || currentphase == CLOSED_PHASE) && NODE_ARGS['issueHasLemoning']) {
		reqUrl = reqUrl + "&method=getdebateideaconnectionswithlemoning&style=long";
		container = $('tab-content-remaining-div');
	} else {
		reqUrl = reqUrl + "&method=getdebateideaconnections&style=long";
	}
	if (NODE_ARGS['currentphase'] == CLOSED_PHASE) {
		args['orderby'] = 'ideavote';
		args['sort'] = 'DESC';
	}
	reqUrl += "&orderby="+args['orderby']+"&sort="+args['sort']+"&issueid="+focalnodeid;

	container.update(getLoading("<?php echo $LNG->LOADING_SOLUTIONS; ?>"));

	//alert(reqUrl);

	//var time = Math.round(+new Date()/1000);
	new Ajax.Request(reqUrl, { method:'post',
  		onSuccess: function(transport){
  			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var conns = json.connectionset[0].connections;
			//alert("conns="+conns.length);

			if (conns.length == 0) {
				container.update("<?php echo $LNG->WIDGET_NONE_FOUND_PART1; ?> "+title+" <?php echo $LNG->WIDGET_NONE_FOUND_PART2; ?>");
			} else {
				var nodes = new Array();
				var nodeids = "";
				for(var i=0; i <  conns.length; i++){
					var c = conns[i].connection;
					var fN = c.from[0].cnode;
					var tN = c.to[0].cnode;

					if (fN.nodeid != focalnodeid) {
						if (fN.name != "") {
							var next = c.from[0];
							if (next.cnode.status != 2) {
								next.cnode['connection'] = c;
								next.cnode['parentid'] = focalnodeid;
								next.cnode['handler'] = '';
								if (args['searchid'] && args['searchid'] != "") {
									next.cnode.searchid = args['searchid'];
								}
								if (args['groupid'] && args['groupid'] != "") {
									next.cnode.groupid = args['groupid'];
								} else {
									next.cnode.groupid = "";
								}

								nodes.push(next);
								nodeids  = nodeids+","+next.cnode.nodeid;
							}
						}
					} else if (tN.nodeid != focalnodeid) {
						if (tN.name != "") {
							var next = c.to[0];
							if (next.cnode.status != 2) {
								next.cnode['connection'] = c;
								next.cnode['parentid'] = focalnodeid;
								next.cnode['handler'] = '';
								if (args['searchid'] && args['searchid'] != "") {
									next.cnode.searchid = args['searchid'];
								}
								if (args['groupid'] && args['groupid'] != "") {
									next.cnode.groupid = args['groupid'];
								}
								nodes.push(next);
								nodeids  = nodeids+","+next.cnode.nodeid;
							}
						}
					}
				}
				if (nodes.length > 0) {
					if ($('remaining-count')) {
						$('remaining-count').update('('+nodes.length+')');
						$('removed-count').update('('+(parseInt(json.connectionset[0].totalno)-nodes.length)+')');
					}

					// Audit ideas viewed
					nodeids = nodeids.substr(1); // remove first comma
					var reqUrl = SERVICE_ROOT + "&method=auditnodeviewmulti&nodeids="+nodeids+"&viewtype=list";
					new Ajax.Request(reqUrl, { method:'post',
						onSuccess: function(transport){
							var json = transport.responseText.evalJSON();
							if(json.error){
								//alert(json.error[0].message);
							}
						}
					});

					// clear list
					container.update("");

					if (NODE_ARGS['currentphase'] != CLOSED_PHASE && NODE_ARGS['currentphase'] != DECIDE_PHASE) {
						var tb3 = new Element("div", {'class':'toolbarrow'});
						var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', fromname: '<?php echo $LNG->SORT_TITLE; ?>', random:'<?php echo $LNG->SORT_RANDOM; ?>'};
						//sortOpts.vote = '<?php echo $LNG->SORT_VOTES; ?>';
						tb3.insert(displaySortForm(sortOpts,args,'solution',reorderSolutions));

						container.insert(tb3);
					}

					displayIdeaList(container,nodes,parseInt(0), true, 'explore');

					// Set Idea count on Issue
					$('debatestatsideas'+focalnodeid).update(json.connectionset[0].totalno);
					if ($('debatestatsideasnow'+focalnodeid)) {
						$('debatestatsideasnow'+focalnodeid).update(nodes.length);
					}
				} else {
					container.update("<?php echo $LNG->WIDGET_NONE_FOUND_PART1; ?> "+title+" <?php echo $LNG->WIDGET_NONE_FOUND_PART2; ?>");
				}

				// It also updates the Issue participants count. So do it here.
				loadParticipationStats();

				if (NODE_ARGS['mode'] == 'Organize' ) {
					setMode(NODE_ARGS['mode']);
				}
			}
		}
	});

	DATA_LOADED.remaining = true;
}

/**
 *	load removed solutions
 */
function loadremovedsolutions(context,args){

	var container = $('tab-content-removed-div');
	container.update(getLoading("<?php echo $LNG->LOADING_SOLUTIONS; ?>"));

	var focalnodeid = args['nodeid'];
	var title = "<?php echo $LNG->SOLUTIONS_NAME; ?>";
	var reqUrl = SERVICE_ROOT + "&method=getdebateideaconnectionsremoved&style=long&issueid="+focalnodeid;

	//alert(reqUrl);
	new Ajax.Request(reqUrl, { method:'post',
  		onSuccess: function(transport){
  			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var conns = json.connectionset[0].connections;
			//alert("conns="+conns.length);

			if (conns.length == 0) {
				container.update("<?php echo $LNG->WIDGET_NONE_FOUND_PART1; ?> "+title+" <?php echo $LNG->WIDGET_NONE_FOUND_PART2; ?>");
			} else {
				var nodes = new Array();
				var nodeids = "";
				for(var i=0; i <  conns.length; i++){
					var c = conns[i].connection;
					var fN = c.from[0].cnode;
					var tN = c.to[0].cnode;
					if (fN.nodeid != focalnodeid) {
						if (fN.name != "") {
							var next = c.from[0];
							if (next.cnode.status != 2) {
								next.cnode['connection'] = c;
								next.cnode['parentid'] = focalnodeid;
								next.cnode['handler'] = '';
								if (args['searchid'] && args['searchid'] != "") {
									next.cnode.searchid = args['searchid'];
								}
								if (args['groupid'] && args['groupid'] != "") {
									next.cnode.groupid = args['groupid'];
								} else {
									next.cnode.groupid = "";
								}


								nodes.push(next);
								nodeids  = nodeids+","+next.cnode.nodeid;
							}
						}
					} else if (tN.nodeid != focalnodeid) {
						if (tN.name != "") {
							var next = c.to[0];
							if (next.cnode.status != 2) {
								next.cnode['connection'] = c;
								next.cnode['parentid'] = focalnodeid;
								next.cnode['handler'] = '';
								if (args['searchid'] && args['searchid'] != "") {
									next.cnode.searchid = args['searchid'];
								}
								if (args['groupid'] && args['groupid'] != "") {
									next.cnode.groupid = args['groupid'];
								}
								nodes.push(next);
								nodeids  = nodeids+","+next.cnode.nodeid;
							}
						}
					}
				}
				if (nodes.length > 0) {
					$('removed-count').update('('+nodes.length+')');

					// Audit ideas viewed
					nodeids = nodeids.substr(1); // remove first comma
					var reqUrl = SERVICE_ROOT + "&method=auditnodeviewmulti&nodeids="+nodeids+"&viewtype=list";
					new Ajax.Request(reqUrl, { method:'post',
						onSuccess: function(transport){
							var json = transport.responseText.evalJSON();
							if(json.error){
								//alert(json.error[0].message);
							}
						}
					});

					container.update("");
					displayRemovedIdeaList(container,nodes,parseInt(0), true, 'explore-removed');
				} else {
					container.update("<?php echo $LNG->WIDGET_NONE_FOUND_PART1; ?> "+title+" <?php echo $LNG->WIDGET_NONE_FOUND_PART2; ?>");
				}
			}
		}
	});

	DATA_LOADED.removed = true;
}

function editArgumentNode(node, uniQ, type, nodetype, actiontype, includeUser, status) {

	var nodeid = $('edit'+type+'id'+uniQ).value;
	var nodetypeid = $('edit'+type+'nodetypeid'+uniQ).value;
	var name = ($('edit'+type+'name'+uniQ).value).trim();
	var desc = $('edit'+type+'desc'+uniQ).value;

	// check form has title at least
	if(name == ""){
		if (nodetype == 'Con') {
	   		alert("<?php echo $LNG->FORM_CON_ENTER_SUMMARY_ERROR; ?>");
	   	} else {
	   		alert("<?php echo $LNG->FORM_PRO_ENTER_SUMMARY_ERROR; ?>");
	   	}
		return;
	} else {
	    $('editformdiv'+type+uniQ).style.cursor = 'wait';
		editExploreNode(node, nodeid, nodetypeid, name, desc, type, uniQ, actiontype, includeUser, status);
	}
}

function editCommentNode(node, uniQ, type, actiontype, includeUser, status) {

	var nodeid = $('edit'+type+'id'+uniQ).value;
	var nodetypeid = $('edit'+type+'nodetypeid'+uniQ).value;
	var name = ($('edit'+type+'name'+uniQ).value).trim();
	var desc = $('edit'+type+'desc'+uniQ).value;

	// check form has title at least
	if(name == ""){
		alert("<?php echo $LNG->FORM_COMMENT_ENTER_SUMMARY_ERROR; ?>");
		return;
	} else {
	    $('editformdiv'+type+uniQ).style.cursor = 'wait';
		editExploreNode(node, nodeid, nodetypeid, name, desc, type, uniQ, actiontype, includeUser, status);
	}
}


function editIdeaNode(orinode, uniQ, type, actiontype, includeUser, status) {

	var nodeid = $('edit'+type+'id'+uniQ).value;
	var nodetypeid = $('edit'+type+'nodetypeid'+uniQ).value;
	var name = ($('edit'+type+'name'+uniQ).value).trim();
	var desc = $('edit'+type+'desc'+uniQ).value;

	// check form has title at least
	if(name == ""){
		alert("<?php echo $LNG->FORM_SOLUTION_ENTER_SUMMARY_ERROR; ?>");
		return;
	} else {
	    $('editformdiv'+type+uniQ).style.cursor = 'wait';
		editExploreNode(orinode, nodeid, nodetypeid, name, desc, type, uniQ, actiontype, includeUser, status);
	}
}

function editExploreNode(orinode, nodeid, nodetypeid, name, desc, type, uniQ, actiontype, includeUser, status) {

	var reqUrl = SERVICE_ROOT + "&method=editnode&nodeid=" + encodeURIComponent(nodeid);
	reqUrl += "&name="+ encodeURIComponent(name);
	reqUrl += "&desc="+ encodeURIComponent(desc);
	reqUrl += "&private=N";
	reqUrl += "&nodetypeid="+encodeURIComponent(nodetypeid);

	//does it have any resources?
	var resourcesArray = document.getElementsByName('argumentlinkedit'+uniQ+'[]');
	var count = resourcesArray.length;
	var j=0;
	for(var i=0; i < count; i++) {
		var resource = resourcesArray[i];
		if (resource) {
			var url = resource.value;
			if (url) {
				url = url.trim();
				if (url != "") {
					if (isValidURI(url)) {
						reqUrl += "&resources["+j+"]="+encodeURIComponent(url);
						j++;
					} else {
						alert('<?php echo $LNG->FORM_LINK_INVALID_PART1; ?>'+url+'<?php echo $LNG->FORM_LINK_INVALID_PART2; ?>');
						return;
					}
				} else {
					// to delete existing

					reqUrl += "&resources["+j+"]=";
					resource.remove();
					resourcesArray = document.getElementsByName('argumentlinkedit'+uniQ+'[]');
					count = resourcesArray.length;
					i--;
				}
			} else {
				// to delete existing
				reqUrl += "&resources["+j+"]=";
				resource.remove();
				resourcesArray = document.getElementsByName('argumentlinkedit'+uniQ+'[]');
				count = resourcesArray.length;
				i--;
			}
		}
	}

	//alert("FRED: "+reqUrl);

	new Ajax.Request(reqUrl, { method:'post',
		onSuccess: function(transport){
			//now refresh the page
			$('editformdiv'+type+uniQ).style.cursor = 'pointer';

			// get returned new node so I can get nodeid;
			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			}
			var node = json.cnode[0];
			try {
				clearSelections();

				orinode.name = name;
				orinode.description = desc;
				orinode.urls = node.urls;

				$('edit'+type+'name'+uniQ).value = name;
				$('edit'+type+'desc'+uniQ).value = desc;
				$('editformdiv'+type+uniQ).style.display = "none";

				if (type == 'idea') {
					var blobNode = renderIdeaList(orinode, uniQ, orinode.role[0].role, includeUser, actiontype, status);
					$('ideablobdiv'+uniQ).update(blobNode);
				} else if (type == 'comment') {
					NODE_ARGS['selectednodeid'] = orinode.nodeid;
					var blobNode = renderCommentNode(orinode, uniQ, orinode.role[0].role, includeUser, actiontype, status);
					$('commentblobdiv'+uniQ).update(blobNode);
				} else if (type == 'argument') {
					NODE_ARGS['selectednodeid'] = orinode.nodeid;
					var blobNode = renderArgumentNode(orinode, uniQ, orinode.role[0].role, includeUser, actiontype, status);
					$('argumentblobdiv'+uniQ).update(blobNode);
				}
			} catch(err) {
				//do nothing
			}
   		},
   		onFailure: function(transport) {
   		    $('editformdiv'+type+uniQ).style.cursor = 'pointer';
   			alert("FAILED");
   		}
 	});
}

<?php if (isset($_SESSION['HUB_CANADD']) && $_SESSION['HUB_CANADD']){ ?>

function promptForArgument(node, uniQ, type, nodetype, actiontype, includeUser, status) {
	$('prompttext').innerHTML="";
	$('prompttext').style.width = "320px";
	$('prompttext').style.height = "200px";

	var viewportHeight = getWindowHeight();
	var viewportWidth = getWindowWidth();
	var x = (viewportWidth-320)/2;
	var y = (viewportHeight-200)/2;
	if (GECKO || NS) {
		$('prompttext').style.left = x+window.pageXOffset+"px";
		$('prompttext').style.top = y+window.pageYOffset+"px";
	}
	else if (IE || IE5) {
		$('prompttext').style.left = x+ document.documentElement.scrollLeft+"px";
		$('prompttext').style.top = y+ document.documentElement.scrollTop+"px";
	}

	var heading = new Element('h2', {});
	if (type == "pro") {
		heading.insert('<?php echo $LNG->DEBATE_VOTE_ARGUMENT_MESSAGE_PRO; ?>');
	} else {
		heading.insert('<?php echo $LNG->DEBATE_VOTE_ARGUMENT_MESSAGE_CON; ?>');
	}

	var heading2 = new Element('h2', {'style':'font-size:11pt;margin:0px;padding-bottom:0px'});
	heading2.insert('<?php echo $LNG->DEBATE_VOTE_ARGUMENT_PLACEHOLDER; ?>');

	var textarea1 = new Element('textarea', {'id':'messagetextarea','rows':'6','style':'color: black; width:300px; border: 1px solid gray; padding: 3px; padding-top:0px;overflow:hidden;z-index:200;margin-top:0px;'});

	var buttonOK = new Element('input', { 'class':'btn btn-secondary text-dark fw-bold mx-3 mt-2 float-end', 'type':'button', 'value':'<?php echo $LNG->FORM_BUTTON_SAVE; ?>'});
	Event.observe(buttonOK,'click', function() {
		var name = textarea1.value;
		if (name != "") {
			addArgumentNodeFromVote(name, node, uniQ, type, nodetype, actiontype, includeUser, status);
		}
		$('prompttext').style.display = "none";
		$('prompttext').update("");
	});

	var buttonCancel = new Element('input', { 'class':'btn btn-secondary mx-3 mt-2 float-end', 'type':'button', 'value':'<?php echo $LNG->FORM_BUTTON_CANCEL; ?>'});
	Event.observe(buttonCancel,'click', function() {
		$('prompttext').style.display = "none";
		$('prompttext').update("");
	});

	var buttonDiv = new Element('div', { 'class':'col-auto'});
	buttonDiv.insert(buttonOK);
	buttonDiv.insert(buttonCancel);

	$('prompttext').insert(heading);
	$('prompttext').insert(heading2);
	$('prompttext').insert(textarea1);
	$('prompttext').insert(buttonDiv);
	$('prompttext').style.display = "block";

	textarea1.focus();
}

function addArgumentNodeFromVote(name, parentnode, uniQ, type, nodetypename, actiontype, includeUser, status) {

	var desc = "";
	if (name.length > 100) {
		var tempname = name.substr(0,100);
		desc = name.substr(100);
		name = tempname;
	}

	var linktypename = '<?php echo $CFG->LINK_PRO_SOLUTION; ?>';
	if (nodetypename == 'Con') {
		linktypename = '<?php echo $CFG->LINK_CON_SOLUTION; ?>';
	}

	// check form has title at least
	if(name == ""){
		if (nodetypename == 'Con') {
	   		alert("<?php echo $LNG->FORM_CON_ENTER_SUMMARY_ERROR; ?>");
	   	} else {
	   		alert("<?php echo $LNG->FORM_PRO_ENTER_SUMMARY_ERROR; ?>");
	   	}
		return;
	} else {
		addExploreNode(parentnode, nodetypename, linktypename, name, desc, type, uniQ, actiontype, includeUser, status);
	}
}


function addArgumentNode(parentnode, uniQ, type, nodetypename, actiontype, includeUser, status) {

	var name = ($('add'+type+'name'+uniQ).value).trim();
	var desc = $('add'+type+'desc'+uniQ).value;
	var linktypename = '<?php echo $CFG->LINK_PRO_SOLUTION; ?>';
	if (nodetypename == 'Con') {
		linktypename = '<?php echo $CFG->LINK_CON_SOLUTION; ?>';
	}

	// check form has title at least
	if(name == ""){
		if (nodetypename == 'Con') {
	   		alert("<?php echo $LNG->FORM_CON_ENTER_SUMMARY_ERROR; ?>");
	   	} else {
	   		alert("<?php echo $LNG->FORM_PRO_ENTER_SUMMARY_ERROR; ?>");
	   	}
		return;
	} else {
	    $('addformdiv'+type+uniQ).style.cursor = 'wait';
		addExploreNode(parentnode, nodetypename, linktypename, name, desc, type, uniQ, actiontype, includeUser, status);
	}
}

function addCommentNode(parentnode, uniQ, type, actiontype, includeUser, status) {

	var nodetypename = 'Comment';
	var name = ($('add'+type+'name'+uniQ).value).trim();
	var desc = $('add'+type+'desc'+uniQ).value;
	var linktypename = '<?php echo $CFG->LINK_COMMENT_NODE; ?>';

	// check form has title at least
	if(name == ""){
		alert("<?php echo $LNG->FORM_COMMENT_ENTER_SUMMARY_ERROR; ?>");
		return;
	} else {
	    $('addformdiv'+type+uniQ).style.cursor = 'wait';
		addExploreNode(parentnode, nodetypename, linktypename, name, desc, type, uniQ, actiontype, includeUser, status);
	}
}

function addIdeaNode(parentnode, uniQ, type, actiontype, includeUser, status) {

	var name = ($('add'+type+'name'+uniQ).value).trim();
	var desc = $('add'+type+'desc'+uniQ).value;

	var nodetypename = 'Solution';
	var linktypename = '<?php echo $CFG->LINK_SOLUTION_ISSUE; ?>';

	// check form has title at least
	if(name == ""){
		alert("<?php echo $LNG->FORM_SOLUTION_ENTER_SUMMARY_ERROR; ?>");
		return;
	} else {
	    $('addformdiv'+type+uniQ).style.cursor = 'wait';
		addExploreNode(parentnode, nodetypename, linktypename, name, desc, type, uniQ, actiontype, includeUser, status);
	}
}

function addExploreNode(parentnode, nodetypename, linktypename, name, desc, type, uniQ, actiontype, includeUser, status) {

	var reqUrl = SERVICE_ROOT + "&method=addnodeandconnect";
	reqUrl += "&name="+ encodeURIComponent(name);
	reqUrl += "&desc="+ encodeURIComponent(desc);
	reqUrl += "&nodetypename="+encodeURIComponent(nodetypename);
	reqUrl += "&linktypename="+encodeURIComponent(linktypename);
	reqUrl += "&private=N";
	reqUrl += "&direction=from";
	if (parentnode && parentnode.nodeid != "") {
		reqUrl += "&focalnodeid="+parentnode.nodeid;
	} else {
		alert("Parent node id node found");
		return;
	}
	if (NODE_ARGS['groupid'] && NODE_ARGS['groupid'] != "") {
		reqUrl += "&groupid="+NODE_ARGS['groupid'];
	}

	//does it have any resources?
	var resourcesArray = document.getElementsByName('argumentlink'+type+uniQ+'[]');
	var count = resourcesArray.length;
	var j=0;
	for(var i=0; i < count; i++) {
		var resource = resourcesArray[i];
		if (resource) {
			var url = resource.value;
			if (url) {
				url = url.trim();
				if (url != "") {
					if (isValidURI(url)) {
						reqUrl += "&resources["+j+"]="+encodeURIComponent(url);
						j++;
					} else {
						alert('<?php echo $LNG->FORM_LINK_INVALID_PART1; ?>'+url+'<?php echo $LNG->FORM_LINK_INVALID_PART2; ?>');
						return;
					}
				}
			}
			if (i == 0) {
				resource.value = "";
			} else {
				resource.remove();
				resourcesArray = document.getElementsByName('argumentlink'+type+uniQ+'[]');
				count = resourcesArray.length;
				i--;
			}
		}
	}

	new Ajax.Request(reqUrl, { method:'post',
		onSuccess: function(transport){

			$('addformdiv'+type+uniQ).style.cursor = 'pointer';
			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			}
			var connection = json.connection[0];

			// if they add an idea/pro/con, make usre they follow the issue
			if (nodeObj && nodeObj.role.name == 'Issue' && !nodeObj.userfollow || nodeObj.userfollow == "N") {
				followNode(nodeObj, null, 'refreshMainIssue');
			}

			// change the sort if idea added
			if (type == 'idea') {
				NODE_ARGS['orderby'] = 'date';
				NODE_ARGS['sort'] = 'DESC';
			}

			try {
				clearSelections();

				var fromnode = connection.from[0].cnode;
				fromnode['connection'] = connection;
				fromnode['parentid'] = parentnode.nodeid;
				fromnode['handler'] = '';
				if (NODE_ARGS['searchid'] && NODE_ARGS['searchid'] != "") {
					fromnode.searchid = NODE_ARGS['searchid'];
				}
				if (NODE_ARGS['groupid'] && NODE_ARGS['groupid'] != "") {
					fromnode.groupid = NODE_ARGS['groupid'];
				}

				NODE_ARGS['selectednodeid'] = fromnode.nodeid;

				$('add'+type+'name'+uniQ).value = "";
				$('add'+type+'desc'+uniQ).value = "";

				if (type == 'idea') {
					//$('newideaform').style.display = "none";
					refreshSolutions();
				} else if (type == 'comment') {
					$('commentslist'+uniQ).loaded = 'false';
					loadChildComments('commentslist'+uniQ, parentnode.nodeid, '<?php echo $LNG->COMMENTS_NAME; ?>', linktypename, nodetypename, parentnode.parentid, parentnode.groupid, uniQ, $('count-comment'+uniQ), actiontype, status);
					recalculatePeople();
				} else if (type == 'con') {
					$('counterkidsdiv'+uniQ).loaded = 'false';
					loadChildArguments('counterkidsdiv'+uniQ, parentnode.nodeid, '<?php echo $LNG->CONS_NAME; ?>', linktypename, nodetypename, parentnode.parentid, parentnode.groupid, uniQ, $('count-counter'+uniQ), actiontype, status, $('votebardiv'+uniQ));
					recalculatePeople();
				} else if (type == 'pro') {
					$('supportkidsdiv'+uniQ).loaded = 'false';
					loadChildArguments('supportkidsdiv'+uniQ, parentnode.nodeid, '<?php echo $LNG->PROS_NAME; ?>', linktypename, nodetypename, parentnode.parentid, parentnode.groupid, uniQ, $('count-support'+uniQ), actiontype, status, $('votebardiv'+uniQ));
					recalculatePeople();
				}

				if ($('health-debate')) {
					loadContributionStats();
				}

			} catch(err) {
				//do nothing
			}
   		},
   		onFailure: function(transport) {
   		    $('editformdiv'+type+uniQ).style.cursor = 'pointer';
   			alert("FAILED");
   		}
 	});
}
<?php } ?>

function addCurrentUserAsGroupMember() {

	if (NODE_ARGS['groupid'] && NODE_ARGS['groupid'] != "" && USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=addgroupmember";
		reqUrl += "&groupid="+ encodeURIComponent(NODE_ARGS['groupid']);
		reqUrl += "&userid="+ encodeURIComponent(USER);
		new Ajax.Request(reqUrl, { method:'post',
			onSuccess: function(transport){

				var json = transport.responseText.evalJSON();
				if(json.error){
					alert(json.error[0].message);
					return;
				}
				try {
					window.location.reload(true);
				} catch(err) {
					//do nothing
				}
			},
			onFailure: function(transport) {
				alert("FAILED");
			}
		});
	}
}

function clearSelections() {
	var items = document.getElementsByName('idearowitem');
	var count = items.length;
	for (var i=0; i < count; i++) {
		var item = items[i];
		item.className = "";
		item.style.background = "transparent";
	}
	items = document.getElementsByName('argumentrowitem');
	count = items.length;
	for (var i=0; i < count; i++) {
		var item = items[i];
		item.className = "";
		item.style.background = "transparent";
	}
	items = document.getElementsByName('commentrowitem');
	count = items.length;
	for (var i=0; i < count; i++) {
		var item = items[i];
		item.className = "";
		item.style.background = "transparent";
	}
}

/**
 *	merge the selected nodes into the new node (details in merge form)
 */
function mergeSelectedNodes(){

	var toAdd = getSelectedNodeIDs($('tab-content-idea-list'));
	if(toAdd.length < 2){
		alert("<?php echo $LNG->FORM_IDEA_MERGE_MUST_SELECT; ?>");
		return;
	}

	// check form has title at least
	var newtitle = ($('mergeidea').value).trim();
	if(newtitle == ""){
		alert("<?php echo $LNG->FORM_IDEA_MERGE_NO_TITLE; ?>");
		return;
	}

	var newdesc = $('mergeideadesc').value;
	var nodeid = NODE_ARGS['nodeid'];
	var groupid = NODE_ARGS['groupid'];

	var reqUrl = SERVICE_ROOT + "&method=mergeselectednodes&ids=" + encodeURIComponent(toAdd.join(","));
	reqUrl += "&groupid="+ encodeURIComponent(groupid);
	reqUrl += "&issuenodeid="+ encodeURIComponent(nodeid);
	reqUrl += "&title="+ encodeURIComponent(newtitle);
	reqUrl += "&desc="+ encodeURIComponent(newdesc);

	//alert("FRED: "+reqUrl);

	new Ajax.Request(reqUrl, { method:'post',
		onSuccess: function(transport){
			//now refresh the page
			// get returned new node so I can get nodeid;
			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			}
			var node = json.cnode[0];
			try {
				// Clear and close the form
				$('mergeideadesc').value = "";
				$('mergeidea').value = "";
				toggleMergeIdeas();

				NODE_ARGS['selectednodeid'] = node.nodeid;
				refreshSolutions();
			} catch(err) {
				//do nothing
			}
   		},
   		onFailure: function(transport) {
   			alert("FAILED");
   		}
 	});
}

/**
 *	Reorder the solutions tab
 */
function reorderSolutions(){
	// change the sort and orderby ARG values
	NODE_ARGS['start'] = 0;
	NODE_ARGS['sort'] = $('select-sort-solution').options[$('select-sort-solution').selectedIndex].value;
	NODE_ARGS['orderby'] = $('select-orderby-solution').options[$('select-orderby-solution').selectedIndex].value;

	loadsolutions(CONTEXT,NODE_ARGS);
}


/**
 *	Reorder the solutions tab
 */
function reorderRemovedSolutions(){
	// change the sort and orderby ARG values
	NODE_ARGS['start'] = 0;
	NODE_ARGS['sort'] = $('select-sort-removed').options[$('select-sort-removed').selectedIndex].value;
	NODE_ARGS['orderby'] = $('select-orderby-removed').options[$('select-orderby-removed').selectedIndex].value;
	DATA_LOADED.removed = false;

	loadremovedsolutions(CONTEXT,NODE_ARGS);
}

/**
 *	Filter the solutions by search criteria
 */
function filterSearchSolutions() {
	NODE_ARGS['q'] = $('qsolution').value;
	var scope = 'all';
	if ($('scopesolutionmy') && $('scopesolutionmy').selected) {
		scope = 'my';
	}
	NODE_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&type=solution&format=text&q="+NODE_ARGS['q'];
		new Ajax.Request(reqUrl, { method:'get',
			onError: function(error) {
				alert(error);
			},
	  		onSuccess: function(transport){
				var searchid = transport.responseText;
				if (searchid != "") {
					NODE_ARGS['searchid'] = searchid;
				}
				DATA_LOADED.solution = false;
				setTabPushed($('tab-solution-list-obj'),'solution-list');
			}
		});
	} else {
		DATA_LOADED.solution = false;
		setTabPushed($('tab-solution-list-obj'),'solution-list');
	}
}

/**
 * Sort by node type in reverse alphabetical order by connections node type.
 */
function connectiontypenodesort(a, b) {
	var typeA = a.cnode.role[0].role.name.toLowerCase();
	var connA = a.cnode.connection;
	if (connA) {
		if (a.cnode.nodeid == connA.from[0].cnode.nodeid) {
			typeA = connA.fromrole[0].role.name.toLowerCase();
		} else {
			typeA = connA.torole[0].role.name.toLowerCase();
		}
	}
	var typeB = b.cnode.role[0].role.name.toLowerCase();
	var connB = b.cnode.connection;
	if (connB) {
		if (b.cnode.nodeid == connB.from[0].cnode.nodeid) {
			typeB = connB.fromrole[0].role.name.toLowerCase();
		} else {
			typeB = connB.torole[0].role.name.toLowerCase();
		}
	}
	if (typeA > typeB) {
		return -1;
	}
	if (typeA < typeB) {
		return 1;
	}
	return 0;
}

/**
 * Sort by node name after a sort by connection node type has been done.
 * @see connectiontypenodesort
 */
function connectiontypealphanodesort(a, b) {
	var nameA=a.cnode.name.toLowerCase();
	var nameB=b.cnode.name.toLowerCase();

	var typeA = a.cnode.role[0].role.name.toLowerCase();
	var connA = a.cnode.connection;
	if (connA) {
		if (a.cnode.nodeid == connA.from[0].cnode.nodeid) {
			typeA = connA.fromrole[0].role.name.toLowerCase();
		} else {
			typeA = connA.torole[0].role.name.toLowerCase();
		}
	}
	var typeB = b.cnode.role[0].role.name.toLowerCase();
	var connB = b.cnode.connection;
	if (connB) {
		if (b.cnode.nodeid == connB.from[0].cnode.nodeid) {
			typeB = connB.fromrole[0].role.name.toLowerCase();
		} else {
			typeB = connB.torole[0].role.name.toLowerCase();
		}
	}

	if (typeA == typeB) {
		if (nameA < nameB) {
			return -1;
		} else if (nameA > nameB) {
			return 1;
		}
	}
	return 0;
}

/**
 * display Nav
 */
function createNav(total, start, count, argArray, context, type){

	var nav = new Element ("div",{'id':'page-nav', 'class':'toolbarrow pb-3' });

	var header = createNavCounter(total, start, count, type);
	nav.insert(header);

	if (total > parseInt( argArray["max"] )) {
		//previous
	    var prevSpan = new Element("span", {'id':"nav-previous", "class": "page-nav page-chevron"});
	    if(start > 0){
			prevSpan.update("<i class=\"fas fa-chevron-left fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_PREVIOUS_HINT; ?></span>");
	        prevSpan.addClassName("active");
	        Event.observe(prevSpan,"click", function(){
	            var newArr = argArray;
	            newArr["start"] = parseInt(start) - newArr["max"];
	            eval("load"+type+"(context,newArr)");
	        });
	    } else {
			prevSpan.update("<i disabled class=\"fas fa-chevron-left fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NO_PREVIOUS_HINT; ?></span>");
	        prevSpan.addClassName("inactive");
	    }

	    //pages
	    var pageSpan = new Element("span", {'id':"nav-pages", "class": "page-nav"});
	    var totalPages = Math.ceil(total/argArray["max"]);
	    var currentPage = (start/argArray["max"]) + 1;
	    for (var i = 1; i < totalPages+1; i++){
	    	var page = new Element("span", {'class':"nav-page"}).insert(i);
	    	if(i != currentPage){
		    	page.addClassName("active");
		    	var newArr = Object.clone(argArray);
		    	newArr["start"] = newArr["max"] * (i-1) ;
		    	Event.observe(page,"click", Pages.next.bindAsEventListener(Pages,type,context,newArr));
	    	} else {
	    		page.addClassName("currentpage");
	    	}
	    	pageSpan.insert(page);
	    }

	    //next
	    var nextSpan = new Element("span", {'id':"nav-next", "class": "page-nav page-chevron"});
	    if(parseInt(start)+parseInt(count) < parseInt(total)){
			nextSpan.update("<i class=\"fas fa-chevron-right fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NEXT_HINT; ?></span>");
	        nextSpan.addClassName("active");
	        Event.observe(nextSpan,"click", function(){
	            var newArr = argArray;
	            newArr["start"] = parseInt(start) + parseInt(newArr["max"]);
	            eval("load"+type+"(context, newArr)");
	        });
	    } else {
			nextSpan.update("<i class=\"fas fa-chevron-right fa-lg\" aria-hidden=\"true\" disabled></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NO_NEXT_HINT; ?></span>");
	        nextSpan.addClassName("inactive");
	    }

	    if( start>0 || (parseInt(start)+parseInt(count) < parseInt(total))){
	    	nav.insert(prevSpan).insert(pageSpan).insert(nextSpan);
	    }
	}

	return nav;
}

/**
 * display nav header
 */
function createNavCounter(total, start, count, type){

    if(count != 0){
    	var objH = new Element("span",{'class':'nav'});
    	var s1 = parseInt(start)+1;
    	var s2 = parseInt(start)+parseInt(count);
        objH.insert("<b>" + s1 + " <?php echo $LNG->LIST_NAV_TO; ?> " + s2 + " (" + total + ")</b>");
    } else {
    	var objH = new Element("span");
     	objH.insert("<p><b><?php echo $LNG->LIST_NAV_NO_SOLUTION; ?></b></p>");
    }
    return objH;
}

var Pages = {
	next: function(e){
		var data = $A(arguments);
		eval("load"+data[1]+"(data[2],data[3])");
	}
};

/**
 *	Reorder the solutions list
 */
function reorderSolutions(){
 	// change the sort and orderby ARG values
 	NODE_ARGS['start'] = 0;
 	NODE_ARGS['sort'] = $('select-sort-solution').options[$('select-sort-solution').selectedIndex].value;
 	NODE_ARGS['orderby'] = $('select-orderby-solution').options[$('select-orderby-solution').selectedIndex].value;

	loadsolutions(CONTEXT,NODE_ARGS);
}

/**
 * show the sort form
 */
function displaySortForm(sortOpts,args,tab,handler){

	var sbTool = new Element("span", {'class':'sortback toolbar2 col-auto'});
    sbTool.insert("<?php echo $LNG->SORT_BY; ?> ");

    var selOrd = new Element("select");
 	Event.observe(selOrd,'change',handler);
    selOrd.id = "select-orderby-"+tab;
    selOrd.className = "toolbar form-select";
    selOrd.name = "orderby";
    selOrd.setAttribute("aria-label","Sort by");
    sbTool.insert(selOrd);
    for(var key in sortOpts){
        var opt = new Element("option");
        opt.value=key;
        opt.insert(sortOpts[key].valueOf());
        selOrd.insert(opt);
        if(args.orderby == key){
        	opt.selected = true;
        }
    }
    var sortBys = {ASC: '<?php echo $LNG->SORT_ASC; ?>', DESC: '<?php echo $LNG->SORT_DESC; ?>'};
    var sortBy = new Element("select");
 	Event.observe(sortBy,'change',handler);
    sortBy.id = "select-sort-"+tab;
    sortBy.className = "toolbar form-select";
    sortBy.name = "sort";
    sortBy.setAttribute("aria-label","Order by");
    sbTool.insert(sortBy);
    for(var key in sortBys){
        var opt = new Element("option");
        opt.value=key;
        opt.insert(sortBys[key]);
        sortBy.insert(opt);
        if(args.sort == key){
        	opt.selected = true;
        }
    }

    return sbTool;
}

/** LEMON BASKET DRAG AND DROP **/

function lemondragstart(e) {
	if (parseInt($('lemonbasketcount').innerHTML) <= 0) {
		alert('<?php echo $LNG->LEMONING_COUNT_FINISHED; ?>');
	} else {
		e.dataTransfer.setData("text", e.target.id);
		e.dataTransfer.effectAllowed = "copy";
		var img = document.createElement("img");
		img.src = "<?php echo $HUB_FLM->getImagePath('lemon22.png'); ?>";
		e.dataTransfer.setDragImage(img, 0, 0);
	}
}

function lemonbasketdragover(e) {
	if (e.dataTransfer) {
		e.dataTransfer.dropEffect = 'move';
	}
	e.preventDefault();
	e.stopPropagation();
	return true;
}

function lemonbasketdragenter(e) {
	if (e.dataTransfer) {
		e.dataTransfer.dropEffect = 'move';
	}
	e.preventDefault();
	e.stopPropagation();
	return true;
}

function lemonbasketdrop(e) {
	var nodeid = e.dataTransfer.getData('text');
	e.preventDefault();
	e.stopPropagation();
	if (nodeid != 'addlemon') {
		document.body.style.cursor = 'wait';
		var callback = function () {
			var lemoncount = parseInt($('lemoncount'+nodeid).innerHTML);
			if (lemoncount == 1) {
				$('lemondiv'+nodeid).style.display = 'none';
			}
			$('lemoncount'+nodeid).innerHTML = lemoncount - 1;
			$('lemonbasketcount').innerHTML = parseInt($('lemonbasketcount').innerHTML) + 1;
			document.body.style.cursor = 'default';
		}

		unlemonNode(nodeid, NODE_ARGS['nodeid'], callback);
	}
	return true;
}
