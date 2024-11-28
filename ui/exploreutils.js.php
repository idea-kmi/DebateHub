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

    include_once("../config.php");
?>

var phasetimer;
var currentphase = '';

const TABS = {"remaining":true, "removed":true};
const DEFAULT_TAB = 'remaining';
const CURRENT_TAB = DEFAULT_TAB;
const DATA_LOADED = {"remaining":false, "removed":false};

const tab-remaining = document.getElmenetById('tab-remaining');
var stpRemaining = function(event) { setTabPushed.call(tab-remaining, event, 'remaining'); };
const tab-removed = document.getElmenetById('tab-removed');
var stpRemoved = function(event) { setTabPushed.call(tab-removed, event, 'removed'); };

/**
 *	Intial data and mode
 */
window.addEventListener('load', function() {
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
	const phaseindicator = document.getElmenetById('phaseindicator');
	if (currentphase == CLOSED_PHASE
			|| currentphase == OPEN_PHASE
			|| currentphase == TIMED_PHASE
			|| currentphase == TIMED_NOVOTE_PHASE
			|| currentphase == PENDING_PHASE) {
		if (phaseindicator) { phaseindicator.style.display = 'none'; }
	} else {
		if (phaseindicator) { phaseindicator.style.display = 'block'; }
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

		const discuss1 = document.getElmenetById('discuss1');
		const discusshelp = document.getElmenetById('discusshelp');
		const reduce1 = document.getElmenetById('reduce1');
		const decide1 = document.getElmenetById('decide1');

		if (NODE_ARGS['issueDiscussing']) {
			if (currentphase == DISCUSS_PHASE
					|| currentphase == TIMED_VOTEPENDING_PHASE
					|| currentphase == OPEN_VOTEPENDING_PHASE) {
				if (discuss1) { discuss1.className = 'step current'; }
				if (discusshelp) { discusshelp.style.display = 'block'; }
			}
			const addnewideaarea = document.getElmenetById('addnewidea');
			const moderatoralerts = document.getElmenetById('moderatoralerts');
			const useralerts = document.getElmenetById('useralerts');
			const moderatebutton = document.getElmenetById('moderatebutton');

			if (addnewideaarea) { addnewideaarea.style.display = 'block'; }
			if (moderatoralerts) { moderatoralerts.style.display = 'block'; }
			if (useralerts) { useralerts.style.display = 'block'; }
			if (moderatebutton) { moderatebutton.style.display = 'block'; }
		}
		if (lemonstart > 0 && lemonend > 0) {
			if (currentphase == REDUCE_PHASE) {
				const reducehelp = document.getElmenetById('reducehelp');
				const lemonbasket = document.getElmenetById('lemonbasket');
				const dashboardbutton = document.getElmenetById('dashboardbutton');
				const healthindicatorsdiv = document.getElmenetById('healthindicatorsdiv');
				const reducehelp = document.getElmenetById('reducehelp);

				if (discuss1) { discuss1.className = 'step'; }
				if (reduce1) { reduce1.className = 'step current'; }
				if (reducehelp) {reducehelp.style.display = 'block'; }
				if (lemonbasket) {	lemonbasket.style.display = 'block'; }
				if (dashboardbutton) { dashboardbutton.style.display = 'none'; }
				if (healthindicatorsdiv) { healthindicatorsdiv.style.display = 'none'; }
			}
		} else {
			if (reduce1) { reduce1.style.display = 'none'; }
		}
		if (votestart > 0) {
			if (currentphase == DECIDE_PHASE || currentphase == TIMED_VOTEON_PHASE
					|| currentphase == OPEN_VOTEON_PHASE) {

				const decidehelp = document.getElmenetById('decidehelp');

				if (discuss1) { discuss1.className = 'step'; }
				if(decide1) { decide1.className = 'step current'; }
				if(decidehelp) { decidehelp.style.display = 'block'; }
			}
		} else {
			if ($decide1) { decide1.style.display = 'none'; }
		}
	}

	refreshMainIssue();

	const tabber = document.getElmenetById('tabber');	

	if ((currentphase == DECIDE_PHASE || currentphase == CLOSED_PHASE) && NODE_ARGS['issueHasLemoning']) {
		if (tabber) {
			tabber.style.display = 'block';
		}
		document.getElementById('tab-remaining').addEventListener('click', stpRemaining);
		document.getElementById('tab-removed').addEventListener('click', stpRemoved);

		const tabpushed = document.getElmenetById('tab-'+DEFAULT_TAB);
		setTabPushed(tabpushed,DEFAULT_TAB);

		refreshSolutions();
	} else {
		if (tabber) {
			tabber.style.display = 'none';
		}
		refreshSolutions();
	}

	if (currentphase != REDUCE_PHASE) {
		const healthviewing = document.getElmenetById('health-viewing');
		if (healthviewing) {
			loadViewingStats();
		}
		const healthdebate = document.getElmenetById('health-debate');
		if (healthdebate) {
			loadContributionStats();
		}
	}

	const moderatoralerts = document.getElmenetById('moderatoralerts');
	const moderatoralertsissue = document.getElmenetById('moderatoralerts-issue-div');
	const moderatoralertsuser = document.getElmenetById('moderatoralerts-user-div');
	const moderatoralertsmessagearea = document.getElmenetById('moderatoralerts-messagearea');
	if (moderatoralerts) && moderatoralerts.style.display == 'block') {
		loadModeratorAlertsData(moderatoralertsissue, moderatoralertsuser, moderatoralertsmessagearea, nodeObj.nodeid);
	}

	const useralerts = document.getElmenetById('useralerts');
	const useralertsissue = document.getElmenetById('useralerts-issue-div');
	const useralertsuser = document.getElmenetById('useralerts-user-div');
	const useralertsmessagearea = document.getElmenetById('useralerts-messagearea');

	if (USER && USER != "" && useralertsissue && useralerts.style.display == 'block') {
		loadUserAlertsData(useralertsissue, useralertsuser, useralertsmessagearea, nodeObj.nodeid);
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
function setTabPushed(e, tab) {

	// Check tab is know else default to default
	if (!TABS.hasOwnProperty(tab)) {
		tab = DEFAULT_TAB;
	}
	for (i in TABS){
		const tabi = document.getElmenetById('tab-'+i);
		const tabcontent = document.getElmenetById('tab-content-'+i+'-div');

		if (tabi) {
			if(tab == i){
				tabi.classList.remove("unselected");
				tabi.classList.add("current");
				if (tabcontent) {
					tabcontent.style.display = "block";
				}
			} else {
				tabi.classList.remove("current");
				tabi.classList.add("unselected");
				if (tabcontent) {
					tabcontent.style.display = "none";
				}
			}
		}
	}

	CURRENT_TAB = tab;
	if (tab == "remaining") {
		const tabremaining = document.getElmenetById('tab-remaining');
		tabremaining.setAttribute("href",'#remaining');
		document.getElementById('tab-remaining').onclick = stpRemaining;
		if(!DATA_LOADED.remaining) {
			loadsolutions(CONTEXT,NODE_ARGS);
			loadremovedsolutions(CONTEXT,NODE_ARGS);
		}
	} else if (tab == "removed") {
		const tabremoved = document.getElmenetById('tab-removed');
		tabremoved.setAttribute("href",'#removed');
		document.getElementById('tab-removed').onclick = stpRemoved;
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
	const mainnodediv = document.getElmenetById('mainnodediv');
	mainnodediv.innerHTML = "";
	mainnodediv.appendChild(itemobj);
}

function refreshStats() {
	const healthdebate = document.getElmenetById('health-debate');
	const healthparticipation = document.getElmenetById('health-participation');
	if (healthdebate) {
		loadContributionStats();
	}
	if (healthparticipation) {
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
		const healthdebate = document.getElmenetById('health-debate');
		const healthdebateDetails = document.getElmenetById('health-debate-details');
		healthdebateDetails.style.display = 'none';
		healthdebate.auditid = '';
	}
	if (type != 'participation') {
		const healthparticipation = document.getElmenetById('health-participation');
		const healthparticipationDetails = document.getElmenetById('health-participation-details');
		healthparticipationDetails.style.display = 'none';
		healthparticipation.auditid = '';
	}
	if (type != 'viewing') {
		const healthviewing = document.getElmenetById('health-viewing');
		const healthviewingDetails = document.getElmenetById('health-viewing-details');
		healthviewingDetails.style.display = 'none';
		healthviewing.auditid = '';
	}

	const details = document.getElmenetById('health-'+type+'-details');
	const healthdebate = document.getElmenetById('health-debate');
	const healthviewing = document.getElmenetById('health-viewing');
	const healthparticipation = document.getElmenetById('health-participation');

	if (details.style.display == 'none') {
		details.style.display = 'block';

		var id = new Date().getTime();
		if (type == 'debate') {			
			healthdebate.auditid = id;
			auditDebateStats(id);
		} else if (type == 'viewing') {
			healthviewing.auditid = id;
			auditViewingStats(id);
		} else if (type == 'participation') {
			healthparticipation.auditid = id;
			auditParticipationStats(id);
		}
	} else {
		details.style.display = 'none';

		if (type == 'debate') {
			healthdebate.auditid = '';
		} else if (type == 'viewing') {
			healthviewing.auditid = '';
		} else if (type == 'participation') {
			healthparticipation.auditid = '';
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

	const healthparticipation = document.getElmenetById('health-participation');
	var colour = healthparticipation.trafficlight;

	var state = '<parent>'+parentid+'</parent>';
	state += '<meta1>'+colour+'</meta1>';
	const healthparticipationcount = document.getElmenetById('health-participation-count');
	state += '<meta2>'+healthparticipationcount.innerHTML+'</meta2>';
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
		const healthparticipation = document.getElmenetById('health-participation');
		var parentid = healthparticipation.auditid;
		var colour = healthparticipation.trafficlight;

		var state = '<parent>'+parentid+'</parent>';
		state += '<meta1>'+colour+'</meta1>';
		const healthparticipationcount = document.getElmenetById('health-participation-count');
		state += '<meta2>'+healthparticipationcount.innerHTML+'</meta2>';
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

	const healthviewing = document.getElmenetById('health-viewing');
	var colour = healthviewing.trafficlight;

	var state = '<parent>'+parentid+'</parent>';
	state += '<meta1>'+colour+'</meta1>';
	const healthviewingpeoplecount = document.getElmenetById('health-viewingpeople-count');
	const healthviewinggroupcount = document.getElmenetById('health-viewinggroup-count');
	state += '<meta2>'+healthviewingpeoplecount.innerHTML+"/"+healthviewinggroupcount.innerHTML+'</meta2>';
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

		const healthviewing = document.getElmenetById('health-viewing');
		var parentid = healthviewing.auditid;
		var colour = healthviewing.trafficlight;

		var state = '<parent>'+parentid+'</parent>';
		state += '<meta1>'+colour+'</meta1>';
		const healthviewingpeoplecount = document.getElmenetById('health-viewingpeople-count');
		const healthviewinggroupcount = document.getElmenetById('health-viewinggroup-count');
		state += '<meta2>'+healthviewingpeoplecount.innerHTML+"/"+healthviewinggroupcount.innerHTML+'</meta2>';
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

	const healthdebate = document.getElmenetById('health-debate');
	var colour = healthdebate.trafficlight;

	var state = '<parent>'+parentid+'</parent>';
	state += '<meta1>'+colour+'</meta1>';
	state += '<meta2>';
	state += healthdebate.ideacount+" ideas,";
	state += healthdebate.procount+" pros,";
	state += healthdebate.concount+" cons,";
	state += healthdebate.totalvotes+" votes,";
	state += healthdebate.contributioncount+" total";
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

		const healthdebate = document.getElmenetById('health-debate');
		var parentid = healthdebate.auditid;

		var colour = healthdebate.trafficlight;
		var state = '<parent>'+parentid+'</parent>';
		state += '<meta1>'+colour+'</meta1>';
		state += '<meta2>';
		state += healthdebate.ideacount+" ideas,";
		state += healthdebate.procount+" pros,";
		state += healthdebate.concount+" cons,";
		state += healthdebate.totalvotes+" votes,";
		state += healthdebate.contributioncount+" total";
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
async function loadParticipationStats() {
	var nodeid = nodeObj.nodeid;

	var args = {}; //must be an empty object to send down the url, or all the Array functions get sent too.
	args["nodeid"] = nodeid;
    args['style'] = "long";

	var reqUrl = SERVICE_ROOT + "&method=getdebateparticipationstats&" + Object.toQueryString(args);

	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}

		var stats = json.debateparticipationstats[0];
		var peoplecount = parseInt(stats.peoplecount);

		const healthparticipation = document.getElmenetById('health-participation');
		if (healthparticipation) {
			const healthparticipationcount = document.getElmenetById('health-participation-count');
			healthparticipationcount.innerHTML = peoplecount;
			var person = peoplecount == 1 ? '<?php echo $LNG->STATS_OVERVIEW_PERSON; ?>' :'<?php echo $LNG->STATS_OVERVIEW_PEOPLE; ?>';
			const healthparticipationmessage = document.getElmenetById('health-participation-message');
			healthparticipationmessage.innerHTML = person+' '+'<?php echo $LNG->STATS_OVERVIEW_HEALTH_CONTRIBUTORS; ?>';
			const healthparticipationrecomendation = document.getElmenetById('health-participation-recomendation');

			if (peoplecount < 3) {
				healthparticipation.trafficlight = 'red';
				const healthparticipationred = document.getElmenetById('health-participation-red');
				healthparticipationred.className = 'trafficlightredon';
				healthparticipationrecomendation.innerHTML = '<?php echo $LNG->STATS_OVERVIEW_HEALTH_PROBLEM; ?>';
			} else if (peoplecount >= 3 && peoplecount <= 5) {
				healthparticipation.trafficlight = 'orange';
				const healthparticipationorange = document.getElmenetById('health-participation-orange');
				healthparticipationorange.className = 'trafficlightorangeon';
				healthparticipationrecomendation.innerHTML = '<?php echo $LNG->STATS_OVERVIEW_HEALTH_MAYBE_PROBLEM; ?>';
			} else if (peoplecount > 5) {
				healthparticipation.trafficlight = 'green';
				const healthparticipationgreen = document.getElmenetById('health-participation-green');
				healthparticipationgreen.className = 'trafficlightgreenon';
				healthparticipationrecomendation.innerHTML = '<?php echo $LNG->STATS_OVERVIEW_HEALTH_NO_PROBLEM; ?>';
			}
			healthparticipation.style.display = 'block';
		}

		const debateparticipationcount = document.getElmenetById('debate-participation-count');
		debateparticipationcount.innerHTML = peoplecount;

	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

/**
 * load and draw the Contribution health indicator
 */
async function loadContributionStats() {

	var nodeid = nodeObj.nodeid;

	var args = {}; //must be an empty object to send down the url, or all the Array functions get sent too.
	args["nodeid"] = nodeid;
    args['style'] = "long";

	var reqUrl = SERVICE_ROOT + "&method=getdebatecontributionstats&" + Object.toQueryString(args);

	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
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

		const healthdebate = document.getElmenetById('health-debate');
		healthdebate.procount = procount;
		healthdebate.concount = concount;
		healthdebate.ideacount = ideacount;
		healthdebate.totalvotes = totalvotes;
		healthdebate.contributioncount = contributioncount;

		var ideaRatio = ideacount/contributioncount;
		var proRatio = procount/contributioncount;
		var conRatio = concount/contributioncount;
		var votingRatio = totalvotes/contributioncount;

		//If one of the four ratios <= 0.1 then red traffic light
		//If all of the four ratios are > 0.1 but one of them is <=0.2 then yellow traffic light.

		const healthdebateRecomendation = document.getElmenetById('health-debate-recomendation');
		const healthdebateMessage = document.getElmenetById('health-debate-message');

		if (ideacount == 0 || procount == 0 || concount == 0 || totalvotes == 0
				|| ideaRatio <= 0.1 || votingRatio <= 0.1 || proRatio <= 0.1 || conRatio <= 0.1) {
					
			healthdebate.trafficlight = 'red';

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

			healthdebateMessage.innerHTML = message;
			healthdebateRecomendation.innerHTML = '<?php echo $LNG->STATS_OVERVIEW_HEALTH_PROBLEM; ?>';
			const healthdebateRed = document.getElmenetById('health-debate-red');
			healthdebateRed.className = "trafficlightredon";
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

			healthdebate.trafficlight = 'orange';
			healthdebateMessage.innerHTML = message;
			healthdebateRecomendation.innerHTML = '<?php echo $LNG->STATS_OVERVIEW_HEALTH_MAYBE_PROBLEM; ?>';
			const healthdebateorange = document.getElmenetById('health-debate-orange');
			healthdebateorange.className = "trafficlightorangeon";
		} else {
			healthdebate.trafficlight = 'green';
			healthdebateMessage.innerHTML = '<?php echo $LNG->STATS_DEBATE_CONTRIBUTION_GREEN; ?>';
			healthdebateRecomendation.innerHTML = '<?php echo $LNG->STATS_OVERVIEW_HEALTH_NO_PROBLEM; ?>';
			const healthdebategreen = document.getElmenetById('health-debate-green');
			healthdebategreen.className = "trafficlightgreenon";
		}

		healthdebate.style.display = 'block';

	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

/**
 * load and draw the Viewing health indicator
 */
async function loadViewingStats() {
	if (NODE_ARGS["groupid"] && NODE_ARGS["groupid"] != "") {

		var nodeid = nodeObj.nodeid;
		var args = {}; //must be an empty object to send down the url, or all the Array functions get sent too.
		args["nodeid"] = nodeid;
		args["groupid"] = NODE_ARGS["groupid"];

		var reqUrl = SERVICE_ROOT + "&method=getdebateviewingstats&" + Object.toQueryString(args);
		try {
			const json = await makeAPICall(reqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}

			var stats = json.debateviewingstats[0];

			var groupmembercount = parseInt(stats.groupmembercount);
			var viewingmembercount = parseInt(stats.viewingmembercount);

			var ratio = viewingmembercount/groupmembercount;

			const healthviewing = document.getElmenetById('health-viewing');
			const healthviewingpeoplecount = document.getElmenetById('health-viewingpeople-count');
			const healthviewinggroupcount = document.getElmenetById('health-viewinggroup-count');

			healthviewingpeoplecount.innerHTML = viewingmembercount;
			healthviewinggroupcount.innerHTML = groupmembercount;

			var person = viewingmembercount == 1 ? '<?php echo $LNG->STATS_OVERVIEW_PERSON; ?>' :'<?php echo $LNG->STATS_OVERVIEW_PEOPLE; ?>';

			const healthviewingmessage = document.getElmenetById('health-viewing-message');
			const healthviewingmessagepart2 = document.getElmenetById('health-viewing-message-part2');
			const healthviewingrecomendation = document.getElmenetById('health-viewing-recomendation');

			healthviewingmessage.innerHTML = person+' '+'<?php echo $LNG->STATS_DEBATE_VIEWING_MESSAGE_PART1; ?>';
			healthviewingmessagepart2.innerHTML = '<?php echo $LNG->STATS_DEBATE_VIEWING_MESSAGE_PART2; ?>';

			if (ratio >= 0.5) {
				healthviewing.trafficlight = 'green';
				const healthviewinggreen = document.getElmenetById('health-viewing-green');
				healthviewinggreen.className = "trafficlightgreenon";
				healthviewingrecomendation.innerHTML = '<?php echo $LNG->STATS_OVERVIEW_HEALTH_NO_PROBLEM; ?>';
			} else if (ratio < 0.5 && ratio >= 0.2) {
				healthviewing.trafficlight = 'orange';
				const healthviewingorange = document.getElmenetById('health-viewing-orange');
				healthviewingorange.className = "trafficlightorangeon";
				healthviewingrecomendation.innerHTML = '<?php echo $LNG->STATS_OVERVIEW_HEALTH_MAYBE_PROBLEM; ?>';
			} else {
				healthviewing.trafficlight = 'red';
				const healthviewingred = document.getElmenetById('health-viewing-red');
				healthviewingred.className = "trafficlightredon";
				healthviewingrecomendation.innerHTML = '<?php echo $LNG->STATS_OVERVIEW_HEALTH_PROBLEM; ?>';
			}

			healthviewing.style.display = 'block';

		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	}
}

function insertArgumentLink(uniQ, type) {
	const argumentLinkDiv = document.getElmenetById('linksdiv'+type+uniQ);
	var count = parseInt(argumentLinkDiv.linkcount);
	count = count+1;
	argumentLinkDiv.linkcount = count;

	var weblink = document.createElement("input");
	weblink.className = 'form-control mt-2';
	weblink.placeholder = '<?php echo $LNG->FORM_LINK_LABEL; ?>';
	weblink.id = 'argumentlink'+type+uniQ+count;
	weblink.name = 'argumentlink'+type+uniQ+'[]';
	weblink.value = '';

	argumentLinkDiv.appendChild(weblink);
	weblink.focus();
}

function insertIdeaLink(uniQ, type) {
	const argumentLinkDiv = document.getElmenetById('linksdiv'+type+uniQ);
	var count = parseInt(argumentLinkDiv.linkcount);
	count = count + 1;
	argumentLinkDiv.linkcount = count;

	var weblink = document.createElement("input");
	weblink.className = 'form-control mt-2';
	weblink.placeholder = '<?php echo $LNG->FORM_LINK_LABEL; ?>';
	weblink.id = 'argumentlink'+type+uniQ+count;
	weblink.name = 'argumentlink'+type+uniQ+'[]';
	weblink.value = '';

	argumentLinkDiv.appendChild(weblink);
	weblink.focus();
}

function removeArgumentLink(uniQ, type) {
	const linkItemDiv = document.getElmenetById('linkitemdiv'+type+uniQ);
	linkItemDiv.remove();
}

function toggleOrganizeMode(obj, mode) {

	if (NODE_ARGS['mode'] == mode && mode == 'Organize' ) {
		obj = null;
		mode = "Gather";
	}
	NODE_ARGS['mode'] = mode;

	const maininner = document.getElmenetById('maininner');
	maininner.className='p-3';

	const moderatebutton = document.getElmenetById('moderatebutton');
	if (moderatebutton) {
		moderatebutton.className = "btn btn-moderator d-grid gap-2 m-2";
	}

	if (obj != null) {
		if (mode == "Gather") {
			obj.className = "groupbutton modeback3 modeborder3pressed";
		} else if (mode == "Organize") {
			obj.className = "btn btn-moderator d-grid gap-2 m-2 active";
			const maininner = document.getElmenetById('maininner');
			maininner.className = "p-3 modebackinner1";
		}
	}

	setMode(mode);
}

function setMode(mode) {

	if ((currentphase != DECIDE_PHASE && currentphase != CLOSED_PHASE)) {
		const tabcontent = document.getElmenetById('tab-content-idea-list');
		var nodes = tabcontent.select('[class="nodecheck"]');
		nodes.each(function(name, index) {
			nodes[index].checked = false
		});
	}

	// New idea and merge idea forms.
	if (mode == "Organize") {
		const addnewideaarea = document.getElmenetById('addnewideaarea');
		if (addnewideaarea) {
			addnewideaarea.style.display = "none";
		}
	} else if (mode == "Gather") {
		const mergeideadiv = document.getElmenetById('mergeideadiv');
		if (mergeideadiv) {
			mergeideadiv.style.display = 'none';
		}

		const mergeideaform = document.getElmenetById('mergeideaform');
		if (mergeideaform) {
			mergeideaform.style.display = "none";
		}

		if (currentphase == DISCUSS_PHASE
					|| currentphase == TIMED_VOTEPENDING_PHASE
					|| currentphase == OPEN_VOTEPENDING_PHASE) {	
			const addnewideaarea = document.getElmenetById('addnewideaarea');	
			if (addnewideaarea) {
				addnewideaarea.style.display = "block";
			}
		}
	}

	const mergeideaform = document.getElmenetById('mergeideaform');
	if (mode == "Organize" && mergeideaform) {
		mergeideaform.style.display = "block";
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
	const addformdividea = document.getElmenetById('addformdividea');
	const newideadivbutton = document.getElmenetById('newideadivbutton');
	if (addformdividea.style.display == 'none') {
		addformdividea.style.display='block';
		newideadivbutton.src='<?php echo $HUB_FLM->getImagePath("arrow-up2.png"); ?>';
	} else {
		addformdividea.style.display='none';
		newideadivbutton.src='<?php echo $HUB_FLM->getImagePath("arrow-down2.png"); ?>';
	}
}

function toggleMergeIdeas() {
	const mergeideadiv = document.getElmenetById('mergeideadiv');
	if (mergeideadiv.style.display == 'none') {
		const tabcontentidealist = document.getElmenetById('tab-content-idea-list');
		var toAdd = getSelectedNodeIDs(tabcontentidealist);
		if(toAdd.length < 2){
			alert("<?php echo $LNG->FORM_IDEA_MERGE_MUST_SELECT; ?>");
			return;
		} else {
			mergeideadiv.style.display='block';
		}
	} else {
		mergeideadiv.style.display='none';
	}
}


function editInline(objno, type){
	cancelAllEdits(type);

	const editformdiv = document.getElmenetById('editformdiv'+type+objno);
	if (editformdiv) {
		editformdiv.style.display = "block";
	}
	const textdiv = document.getElmenetById('textdiv'+type+objno);
	if (textdiv) {
		textdiv.style.display = "none";
	}

	const editformvotediv = document.getElmenetById('editformvotediv'+type+objno);
	if (editformvotediv) {
		editformvotediv.style.display = "none";
	}
	const editformuserdiv = document.getElmenetById('editformuserdiv'+type+objno);
	if (editformuserdiv) {
		editformuserdiv.style.display = "none";
	}
}

function hideAddForm(objno, type) {
	// hide add form
	if (NODE_ARGS['mode'] == 'Gather') {
		const addformdiv = document.getElmenetById('addformdiv'+type+objno);
		if (addformdiv) {
			addformdiv.style.display = "none";
		}
	}
}

function showAddForm(objno, type) {
	if (NODE_ARGS['mode'] == 'Gather') {
		const addformdiv = document.getElmenetById('addformdiv'+type+objno);
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
	const editformdiv = document.getElmenetById('editformdiv'+type+objno);
	if (editformdiv) {
		editformdiv.style.display = "none";
	}
	const textdiv = document.getElmenetById('textdiv'+type+objno);
	if (textdiv) {
		textdiv.style.display = "block";
	}
	const editformvotediv = document.getElmenetById('editformvotediv'+type+objno);
	if (editformvotediv) {
		editformvotediv.style.display = "block";
	}
	const editformuserdiv = document.getElmenetById('editformuserdiv'+type+objno);
	if (editformuserdiv) {
		editformuserdiv.style.display = "block";
	}

	if (type == "argument") {

	}
}

function checkIdeaAddForm() {
	const checkname = document.getElmenetById('idea').value.trim();
	if (checkname == ""){
	   alert("<?php echo $LNG->FORM_SOLUTION_ENTER_SUMMARY_ERROR; ?>");
	   return false;
    }

	const ideaform = document.getElmenetById('ideaform');
    ideaform.style.cursor = 'wait';

	return true;
}

function checkCommentAddForm(nodeid) {
	const checkname = document.getElmenetById('commenttitle'+nodeid).value.trim();
	if (checkname == ""){
	   alert("<?php echo $LNG->FORM_COMMENT_ENTER_SUMMARY_ERROR; ?>");
	   return false;
    }
	const commentform = document.getElmenetById('commentform'+nodeid);
    commentform.style.cursor = 'wait';

	return true;
}

function checkProAddForm(nodeid) {
	const checkname = document.getElmenetById('proarg'+nodeid).value.trim();
	if (checkname == ""){
	   alert("<?php echo $LNG->FORM_PRO_ENTER_SUMMARY_ERROR; ?>");
	   return false;
    }
	const proform = document.getElmenetById('proform'+nodeid);
    proform.style.cursor = 'wait';

	return true;
}

function checkConAddForm(nodeid) {
	const checkname = document.getElmenetById('conarg'+nodeid).value.trim();
	if (checkname == ""){
	   alert("<?php echo $LNG->FORM_CON_ENTER_SUMMARY_ERROR; ?>");
	   return false;
    }
	const conform = document.getElmenetById('conform'+nodeid);
    conform.style.cursor = 'wait';

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
async function loadsolutions(context,args){

	var focalnodeid = args['nodeid'];
	var title = "<?php echo $LNG->SOLUTIONS_NAME; ?>";

	var reqUrl = SERVICE_ROOT;
	const container = document.getElmenetById('tab-content-idea-list');
	if ((currentphase == DECIDE_PHASE || currentphase == CLOSED_PHASE) && NODE_ARGS['issueHasLemoning']) {
		reqUrl = reqUrl + "&method=getdebateideaconnectionswithlemoning&style=long";
		container = document.getElmenetById('tab-content-remaining-div');
	} else {
		reqUrl = reqUrl + "&method=getdebateideaconnections&style=long";
	}
	if (NODE_ARGS['currentphase'] == CLOSED_PHASE) {
		args['orderby'] = 'ideavote';
		args['sort'] = 'DESC';
	}
	reqUrl += "&orderby="+args['orderby']+"&sort="+args['sort']+"&issueid="+focalnodeid;

	container.innerHTML = getLoading("<?php echo $LNG->LOADING_SOLUTIONS; ?>");

	//alert(reqUrl);

	//var time = Math.round(+new Date()/1000);
	try {
		const json = await makeAPICall(reqUrl, 'POST');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}

		var conns = json.connectionset[0].connections;
		//alert("conns="+conns.length);

		if (conns.length == 0) {
			container.innerHTML = "<?php echo $LNG->WIDGET_NONE_FOUND_PART1; ?> "+title+" <?php echo $LNG->WIDGET_NONE_FOUND_PART2; ?>";
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
				const remainingcount = document.getElmenetById('remaining-count');
				if (remainingcount) {
					remainingcount.innerHTML = '('+nodes.length+')';
					const removedcount = document.getElmenetById('removed-count');
					removedcount.innerHTML = '('+(parseInt(json.connectionset[0].totalno)-nodes.length)+')';
				}

				// Audit ideas viewed
				nodeids = nodeids.substr(1); // remove first comma
				var innerreqUrl = SERVICE_ROOT + "&method=auditnodeviewmulti&nodeids="+nodeids+"&viewtype=list";
				const innerjson = await makeAPICall(innerreqUrl, 'POST');
				if (innerjson.error) {
					//alert(innerjson.error[0].message);
					return;
				}

				// clear list
				container.innerHTML = "";

				if (NODE_ARGS['currentphase'] != CLOSED_PHASE && NODE_ARGS['currentphase'] != DECIDE_PHASE) {
					var tb3 = document.createElement("div");
					tb3.className = 'toolbarrow';
					var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', fromname: '<?php echo $LNG->SORT_TITLE; ?>', random:'<?php echo $LNG->SORT_RANDOM; ?>'};
					//sortOpts.vote = '<?php echo $LNG->SORT_VOTES; ?>';
					tb3.appendChild(displaySortForm(sortOpts,args,'solution',reorderSolutions));

					container.appendChild(tb3);
				}

				displayIdeaList(container,nodes,parseInt(0), true, 'explore');

				// Set Idea count on Issue
				const debatestatsideas = document.getElmenetById('debatestatsideas'+focalnodeid);
				debatestatsideas.innerHTML = json.connectionset[0].totalno;
				const debatestatsideasnow = document.getElmenetById('debatestatsideasnow'+focalnodeid);
				if (debatestatsideasnow) {
					debatestatsideasnow.innerHTML = nodes.length;
				}
			} else {
				container.innerHTML = "<?php echo $LNG->WIDGET_NONE_FOUND_PART1; ?> "+title+" <?php echo $LNG->WIDGET_NONE_FOUND_PART2; ?>";
			}

			// It also updates the Issue participants count. So do it here.
			loadParticipationStats();

			if (NODE_ARGS['mode'] == 'Organize' ) {
				setMode(NODE_ARGS['mode']);
			}
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}

	DATA_LOADED.remaining = true;
}

/**
 *	load removed solutions
 */
async function loadremovedsolutions(context,args){

	const container = document.getElmenetById('tab-content-removed-div');
	container.innerHTML = getLoading("<?php echo $LNG->LOADING_SOLUTIONS; ?>");

	var focalnodeid = args['nodeid'];
	var title = "<?php echo $LNG->SOLUTIONS_NAME; ?>";
	var reqUrl = SERVICE_ROOT + "&method=getdebateideaconnectionsremoved&style=long&issueid="+focalnodeid;

	//alert(reqUrl);
	try {
		const json = await makeAPICall(reqUrl, 'POST');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}	
		var conns = json.connectionset[0].connections;
		//alert("conns="+conns.length);

		if (conns.length == 0) {
			container.innerHTML = "<?php echo $LNG->WIDGET_NONE_FOUND_PART1; ?> "+title+" <?php echo $LNG->WIDGET_NONE_FOUND_PART2; ?>";
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
				const removedcount = document.getElmenetById('removed-count');
				removedcount.innerHTML = '('+nodes.length+')';

				// Audit ideas viewed
				nodeids = nodeids.substr(1); // remove first comma
				var innerreqUrl = SERVICE_ROOT + "&method=auditnodeviewmulti&nodeids="+nodeids+"&viewtype=list";
				try {
					const innerjson = await makeAPICall(innerreqUrl, 'POST');
					if (innerjson.error) {
						//alert(innerjson.error[0].message);
						return;
					}
				} catch (err) {
					//alert("There was an error: "+err.message);
					console.log(err)
				}

				container.innerHTML = "";
				displayRemovedIdeaList(container,nodes,parseInt(0), true, 'explore-removed');
			} else {
				container.innerHTML = "<?php echo $LNG->WIDGET_NONE_FOUND_PART1; ?> "+title+" <?php echo $LNG->WIDGET_NONE_FOUND_PART2; ?>";
			}
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}

	DATA_LOADED.removed = true;
}

function editArgumentNode(node, uniQ, type, nodetype, actiontype, includeUser, status) {

	const nodeid = document.getElmenetById('edit'+type+'id'+uniQ).value;
	const nodetypeid = document.getElmenetById('edit'+type+'nodetypeid'+uniQ).value;
	const name = document.getElmenetById('edit'+type+'name'+uniQ).value.trim();
	const desc = document.getElmenetById('edit'+type+'desc'+uniQ).value;

	// check form has title at least
	if(name == ""){
		if (nodetype == 'Con') {
	   		alert("<?php echo $LNG->FORM_CON_ENTER_SUMMARY_ERROR; ?>");
	   	} else {
	   		alert("<?php echo $LNG->FORM_PRO_ENTER_SUMMARY_ERROR; ?>");
	   	}
		return;
	} else {
		const editformdiv = document.getElmenetById('editformdiv'+type+uniQ);
	    editformdiv.style.cursor = 'wait';
		editExploreNode(node, nodeid, nodetypeid, name, desc, type, uniQ, actiontype, includeUser, status);
	}
}

function editCommentNode(node, uniQ, type, actiontype, includeUser, status) {

	const nodeid = document.getElmenetById('edit'+type+'id'+uniQ).value;
	const nodetypeid = document.getElmenetById('edit'+type+'nodetypeid'+uniQ).value;
	const name = document.getElmenetById('edit'+type+'name'+uniQ).value.trim();
	const desc = document.getElmenetById('edit'+type+'desc'+uniQ).value;

	// check form has title at least
	if(name == ""){
		alert("<?php echo $LNG->FORM_COMMENT_ENTER_SUMMARY_ERROR; ?>");
		return;
	} else {
		const editformdiv = document.getElmenetById('editformdiv'+type+uniQ);
	    editformdiv.style.cursor = 'wait';
		editExploreNode(node, nodeid, nodetypeid, name, desc, type, uniQ, actiontype, includeUser, status);
	}
}


function editIdeaNode(orinode, uniQ, type, actiontype, includeUser, status) {

	const nodeid = document.getElmenetById('edit'+type+'id'+uniQ).value;
	const nodetypeid = document.getElmenetById('edit'+type+'nodetypeid'+uniQ).value;
	const name = document.getElmenetById('edit'+type+'name'+uniQ).value.trim();
	const desc = document.getElmenetById('edit'+type+'desc'+uniQ).value;

	// check form has title at least
	if(name == ""){
		alert("<?php echo $LNG->FORM_SOLUTION_ENTER_SUMMARY_ERROR; ?>");
		return;
	} else {
		const editformdiv = document.getElmenetById('editformdiv'+type+uniQ);
	    editformdiv.style.cursor = 'wait';
		editExploreNode(orinode, nodeid, nodetypeid, name, desc, type, uniQ, actiontype, includeUser, status);
	}
}

async function editExploreNode(orinode, nodeid, nodetypeid, name, desc, type, uniQ, actiontype, includeUser, status) {

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

	try {
		const json = await makeAPICall(innerreqUrl, 'POST');

		const editformdiv = document.getElmenetById('editformdiv'+type+uniQ);
		editformdiv.style.cursor = 'pointer';

		if (json.error) {
			alert(json.error[0].message);
			return;
		}

		var node = json.cnode[0];
		try {
			clearSelections();

			orinode.name = name;
			orinode.description = desc;
			orinode.urls = node.urls;

			document.getElmenetById('edit'+type+'name'+uniQ).value = name;
			document.getElmenetById('edit'+type+'desc'+uniQ).value = desc;
			editformdiv.style.display = "none";

			if (type == 'idea') {
				const blobNode = renderIdeaList(orinode, uniQ, orinode.role[0].role, includeUser, actiontype, status);
				const ideablobdiv = document.getElmenetById('ideablobdiv'+uniQ);
				ideablobdiv.innerHTML = "";
				ideablobdiv.appendChild(blobNode);
			} else if (type == 'comment') {
				NODE_ARGS['selectednodeid'] = orinode.nodeid;
				const blobNode = renderCommentNode(orinode, uniQ, orinode.role[0].role, includeUser, actiontype, status);
				const commentblobdiv = document.getElmenetById('commentblobdiv'+uniQ);
				commentblobdiv.innerHTMNL = "";
				commentblobdiv.appendChild(blobNode);
			} else if (type == 'argument') {
				NODE_ARGS['selectednodeid'] = orinode.nodeid;
				const blobNode = renderArgumentNode(orinode, uniQ, orinode.role[0].role, includeUser, actiontype, status);
				const argumentblobdiv = document.getElmenetById('argumentblobdiv'+uniQ);
				argumentblobdiv.innerHTML = "";
				argumentblobdiv.appendChild(blobNode);
			}
		} catch(err) {
			//do nothing
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

<?php if (isset($_SESSION['HUB_CANADD']) && $_SESSION['HUB_CANADD']){ ?>

function promptForArgument(node, uniQ, type, nodetype, actiontype, includeUser, status) {
	const prompttext = document.getElmenetById('prompttext');
	prompttext.innerHTML="";
	prompttext.style.width = "320px";
	prompttext.style.height = "200px";

	var viewportHeight = getWindowHeight();
	var viewportWidth = getWindowWidth();
	var x = (viewportWidth-320)/2;
	var y = (viewportHeight-200)/2;
	if (GECKO || NS) {
		prompttext.style.left = x+window.pageXOffset+"px";
		prompttext.style.top = y+window.pageYOffset+"px";
	}
	else if (IE || IE5) {
		prompttext.style.left = x+ document.documentElement.scrollLeft+"px";
		prompttext.style.top = y+ document.documentElement.scrollTop+"px";
	}

	var heading = document.createElement('h2');
	if (type == "pro") {
		heading.innerHTML += '<?php echo $LNG->DEBATE_VOTE_ARGUMENT_MESSAGE_PRO; ?>';
	} else {
		heading.innreHTML += '<?php echo $LNG->DEBATE_VOTE_ARGUMENT_MESSAGE_CON; ?>';
	}

	var heading2 = document.createElement('h2');
	heading2.style = 'font-size:11pt;margin:0px;padding-bottom:0px';
	heading2.innerHTML += '<?php echo $LNG->DEBATE_VOTE_ARGUMENT_PLACEHOLDER; ?>';

	var textarea1 = document.createElement('textarea');
	textarea1.id = 'messagetextarea';
	textarea1.rows = '6';
	textarea.style = 'color: black; width:300px; border: 1px solid gray; padding: 3px; padding-top:0px;overflow:hidden;z-index:200;margin-top:0px;';

	var buttonOK = document.createElement('input');
	buttonOK.className = 'btn btn-secondary text-dark fw-bold mx-3 mt-2 float-end';
	buttonOK.type = 'button';
	buttonOK.value = '<?php echo $LNG->FORM_BUTTON_SAVE; ?>';
	buttonOK.onclick = function() {
		var name = textarea1.value;
		if (name != "") {
			addArgumentNodeFromVote(name, node, uniQ, type, nodetype, actiontype, includeUser, status);
		}
		prompttext.style.display = "none";
		prompttext.innerHTML = "";
	};

	var buttonCancel = document.createElement('input');
	buttonCancel.classNamne = 'btn btn-secondary mx-3 mt-2 float-end';
	buttonCancel.type = 'button';
	buttonCancel.value = '<?php echo $LNG->FORM_BUTTON_CANCEL; ?>';
	buttonCancel.onclick = function() {
		prompttext.style.display = "none";
		prompttext.innerHTML = "";
	};

	var buttonDiv = document.createElement('div');
	buttonDic.className = 'col-auto';
	buttonDiv.appendChild(buttonOK);
	buttonDiv.appendChild(buttonCancel);

	prompttext.appendChild(heading);
	prompttext.appendChild(heading2);
	prompttext.appendChild(textarea1);
	prompttext.appendChild(buttonDiv);
	prompttext.style.display = "block";

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

	const name = document.getElmenetById('add'+type+'name'+uniQ).value.trim();
	const desc = document.getElmenetById('add'+type+'desc'+uniQ).value;
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
		const addformdiv = document.getElmenetById('addformdiv'+type+uniQ);
	    addformdiv.style.cursor = 'wait';
		addExploreNode(parentnode, nodetypename, linktypename, name, desc, type, uniQ, actiontype, includeUser, status);
	}
}

function addCommentNode(parentnode, uniQ, type, actiontype, includeUser, status) {

	var nodetypename = 'Comment';
	const name = document.getElmenetById('add'+type+'name'+uniQ).value.trim();
	const desc = document.getElmenetById('add'+type+'desc'+uniQ).value;
	var linktypename = '<?php echo $CFG->LINK_COMMENT_NODE; ?>';

	// check form has title at least
	if(name == ""){
		alert("<?php echo $LNG->FORM_COMMENT_ENTER_SUMMARY_ERROR; ?>");
		return;
	} else {
		const addformdiv = document.getElmenetById('addformdiv'+type+uniQ);
	    addformdiv.style.cursor = 'wait';
		addExploreNode(parentnode, nodetypename, linktypename, name, desc, type, uniQ, actiontype, includeUser, status);
	}
}

function addIdeaNode(parentnode, uniQ, type, actiontype, includeUser, status) {

	const name = document.getElmenetById('add'+type+'name'+uniQ).value.trim();
	const desc = document.getElmenetById('add'+type+'desc'+uniQ).value;

	var nodetypename = 'Solution';
	var linktypename = '<?php echo $CFG->LINK_SOLUTION_ISSUE; ?>';

	// check form has title at least
	if(name == ""){
		alert("<?php echo $LNG->FORM_SOLUTION_ENTER_SUMMARY_ERROR; ?>");
		return;
	} else {
		const addformdiv = document.getElmenetById('addformdiv'+type+uniQ);
	    addformdiv.style.cursor = 'wait';
		addExploreNode(parentnode, nodetypename, linktypename, name, desc, type, uniQ, actiontype, includeUser, status);
	}
}

async function addExploreNode(parentnode, nodetypename, linktypename, name, desc, type, uniQ, actiontype, includeUser, status) {

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

	try {
		const json = await makeAPICall(innerreqUrl, 'POST');

		const addformdiv = document.getElmenetById('addformdiv'+type+uniQ);
		addformdiv.style.cursor = 'pointer';

		if (json.error) {
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

			document.getElementById('add'+type+'name'+uniQ).value = "";
			document.getElementById('add'+type+'desc'+uniQ).value = "";

			if (type == 'idea') {
				//document.getElementById('newideaform').style.display = "none";
				refreshSolutions();
			} else if (type == 'comment') {
				document.getElementById('commentslist'+uniQ).loaded = 'false';
				const commentcount = document.getElementById('count-comment'+uniQ);
				loadChildComments('commentslist'+uniQ, parentnode.nodeid, '<?php echo $LNG->COMMENTS_NAME; ?>', linktypename, nodetypename, parentnode.parentid, parentnode.groupid, uniQ, commentcount, actiontype, status);
				recalculatePeople();
			} else if (type == 'con') {
				document.getElementById('counterkidsdiv'+uniQ).loaded = 'false';
				const commentcounter = document.getElementById('count-counter'+uniQ);
				const votebardiv = document.getElementById('votebardiv'+uniQ);
				loadChildArguments('counterkidsdiv'+uniQ, parentnode.nodeid, '<?php echo $LNG->CONS_NAME; ?>', linktypename, nodetypename, parentnode.parentid, parentnode.groupid, uniQ, commentcounter, actiontype, status, votebardiv);
				recalculatePeople();
			} else if (type == 'pro') {
				document.getElementById('supportkidsdiv'+uniQ).loaded = 'false';
				const countsupport = document.getElementById('count-support'+uniQ);
				const votebardiv = document.getElementById('votebardiv'+uniQ);
				loadChildArguments('supportkidsdiv'+uniQ, parentnode.nodeid, '<?php echo $LNG->PROS_NAME; ?>', linktypename, nodetypename, parentnode.parentid, parentnode.groupid, uniQ, countsupport, actiontype, status, votebardiv);
				recalculatePeople();
			}

			const healthdebate = document.getElementById('health-debate');
			if (healthdebate) {
				loadContributionStats();
			}

		} catch(err) {
			//do nothing
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}	
}
<?php } ?>

async function addCurrentUserAsGroupMember() {

	if (NODE_ARGS['groupid'] && NODE_ARGS['groupid'] != "" && USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=addgroupmember";
		reqUrl += "&groupid="+ encodeURIComponent(NODE_ARGS['groupid']);
		reqUrl += "&userid="+ encodeURIComponent(USER);

		try {
			const json = await makeAPICall(innerreqUrl, 'POST');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}		

			window.location.reload(true);
		
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
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
async function mergeSelectedNodes(){

	const tabcontet = document.getElmenetById('tab-content-idea-list');
	const toAdd = getSelectedNodeIDs(tabcontet);
	if(toAdd.length < 2){
		alert("<?php echo $LNG->FORM_IDEA_MERGE_MUST_SELECT; ?>");
		return;
	}

	// check form has title at least
	const newtitle = document.getElmenetById('mergeidea').value.trim();
	if(newtitle == ""){
		alert("<?php echo $LNG->FORM_IDEA_MERGE_NO_TITLE; ?>");
		return;
	}

	const newdesc = document.getElmenetById('mergeideadesc').value;
	const nodeid = NODE_ARGS['nodeid'];
	const groupid = NODE_ARGS['groupid'];

	let reqUrl = SERVICE_ROOT + "&method=mergeselectednodes&ids=" + encodeURIComponent(toAdd.join(","));
	reqUrl += "&groupid="+ encodeURIComponent(groupid);
	reqUrl += "&issuenodeid="+ encodeURIComponent(nodeid);
	reqUrl += "&title="+ encodeURIComponent(newtitle);
	reqUrl += "&desc="+ encodeURIComponent(newdesc);

	try {
		const json = await makeAPICall(innerreqUrl, 'POST');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}		

		var node = json.cnode[0];
		try {
			// Clear and close the form
			document.getElementById = ('mergeidea').value = "";
			document.getElementById = ('mergeideadesc').value = "";
			toggleMergeIdeas();

			NODE_ARGS['selectednodeid'] = node.nodeid;
			refreshSolutions();
		} catch(err) {
			//do nothing
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

/**
 *	Reorder the solutions tab
 */
function reorderSolutions(){
	// change the sort and orderby ARG values
	NODE_ARGS['start'] = 0;
	const selectsortsolution = document.getElmenetById('select-sort-solution');
	NODE_ARGS['sort'] = selectsortsolution.options[selectsortsolution.selectedIndex].value;
	const selectorderbysolution = document.getElmenetById('select-orderby-solution');
	NODE_ARGS['orderby'] = selectorderbysolution.options[selectorderbysolution.selectedIndex].value;

	loadsolutions(CONTEXT,NODE_ARGS);
}


/**
 *	Reorder the solutions tab
 */
function reorderRemovedSolutions(){
	// change the sort and orderby ARG values
	NODE_ARGS['start'] = 0;
	const selectsortremoved = document.getElmenetById('select-sort-removed');
	NODE_ARGS['sort'] = selectsortremoved.options[selectsortremoved.selectedIndex].value;
	const selectorderbyremoved = document.getElmenetById('select-orderby-removed');
	NODE_ARGS['orderby'] = selectorderbyremoved.options[selectorderbyremoved.selectedIndex].value;
	DATA_LOADED.removed = false;

	loadremovedsolutions(CONTEXT,NODE_ARGS);
}

/**
 *	Filter the solutions by search criteria
 */
async function filterSearchSolutions() {
	NODE_ARGS['q'] = document.getElementById('qsolution').value;
	var scope = 'all';
	const scopesolutionmy = document.getElementById('scopesolutionmy');
	if (scopesolutionmy && scopesolutionmy.selected) {
		scope = 'my';
	}
	NODE_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&type=solution&format=text&q="+NODE_ARGS['q'];
		try {
			const json = await makeAPICall(innerreqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}			
			var searchid = transport.responseText;
			if (searchid != "") {
				NODE_ARGS['searchid'] = searchid;
			}
			DATA_LOADED.solution = false;
			const tabsolutionlistobj = document.getElementById('tab-solution-list-obj');
			setTabPushed(tabsolutionlistobj,'solution-list');
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	} else {
		DATA_LOADED.solution = false;
		const tabsolutionlistobj = document.getElementById('tab-solution-list-obj');
		setTabPushed(tabsolutionlistobj,'solution-list');
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

	var nav = document.createElement ("div");
	nav.id = 'page-nav';
	nav.className = 'toolbarrow pb-3';

	var header = createNavCounter(total, start, count, type);
	nav.appendChild(header);

	if (total > parseInt( argArray["max"] )) {
		//previous
	    var prevSpan = document.createElement("span");
		prevSpan.id = "nav-previous";
		prevSpan.className = "page-nav page-chevron";
	    if(start > 0){
			prevSpan.innerHTML = "<i class=\"fas fa-chevron-left fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_PREVIOUS_HINT; ?></span>";
	        prevSpan.classList.add("active");
			prevSpan.onclick = function() {
	            var newArr = argArray;
	            newArr["start"] = parseInt(start) - newArr["max"];
	            eval("load"+type+"(context,newArr)");
	        };
	    } else {
			prevSpan.innerHTML = "<i disabled class=\"fas fa-chevron-left fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NO_PREVIOUS_HINT; ?></span>";
	        prevSpan.classList.add("inactive");
	    }

	    //pages
	    var pageSpan = document.createElement("span");
		pageSpan.id = "nav-pages";
		pageSpan.className = "page-nav";
	    var totalPages = Math.ceil(total/argArray["max"]);
	    var currentPage = (start/argArray["max"]) + 1;
	    for (var i = 1; i < totalPages+1; i++){
	    	var page = document.createElement("span");
			page.className = "nav-page";
			page.innerHTML = i;
	    	if(i != currentPage){
		    	page.classList.add("active");
		    	var newArr = { ...argArray };
		    	newArr["start"] = newArr["max"] * (i-1) ;
				page.onclick = function(event) {
    				Pages.next.call(Pages, type, context, newArr, event);
				};
	    	} else {
	    		page.classList.add("currentpage");
	    	}
	    	pageSpan.appendChild(page);
	    }

	    //next
	    var nextSpan = document.createElement("span");
		nextSpan.id = "nav-next";
		nextSpan.className = "page-nav page-chevron";
	    if(parseInt(start)+parseInt(count) < parseInt(total)){
			nextSpan.innerHTML = "<i class=\"fas fa-chevron-right fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NEXT_HINT; ?></span>";
	        nextSpan.classList.add("active");
	        nextSpan.onclick = function(){
	            var newArr = argArray;
	            newArr["start"] = parseInt(start) + parseInt(newArr["max"]);
	            eval("load"+type+"(context, newArr)");
	        };
	    } else {
			nextSpan.innerHTML = "<i class=\"fas fa-chevron-right fa-lg\" aria-hidden=\"true\" disabled></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NO_NEXT_HINT; ?></span>";
	        nextSpan.classList.add("inactive");
	    }

	    if( start>0 || (parseInt(start)+parseInt(count) < parseInt(total))){
	    	nav.appendChild(prevSpan);
			prevSpan.appendChild(pageSpan);
			pageSpan.appendChild(nextSpan);
	    }
	}

	return nav;
}

/**
 * display nav header
 */
function createNavCounter(total, start, count, type){

    if(count != 0){
    	var objH = document.createElement("span");
		objH.className = 'nav';
    	var s1 = parseInt(start)+1;
    	var s2 = parseInt(start)+parseInt(count);
        objH.innerHTML = "<b>" + s1 + " <?php echo $LNG->LIST_NAV_TO; ?> " + s2 + " (" + total + ")</b>";
    } else {
    	var objH = document.createElement("span");
     	objH.innerHTML = "<p><b><?php echo $LNG->LIST_NAV_NO_SOLUTION; ?></b></p>";
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
	const selectsortsolution = document.getElmenetById('select-sort-solution');
 	NODE_ARGS['sort'] = selectsortsolution.options[selectsortsolution.selectedIndex].value;
	const selectorderbysolution = document.getElmenetById('select-orderby-solution');
 	NODE_ARGS['orderby'] = selectorderbysolution.options[selectorderbysolution.selectedIndex].value;

	loadsolutions(CONTEXT,NODE_ARGS);
}

/**
 * show the sort form
 */
function displaySortForm(sortOpts,args,tab,handler){

	var sbTool = document.createElement("span");
	sbTool.className = 'sortback toolbar2 col-auto';
    sbTool.innerHTML = "<?php echo $LNG->SORT_BY; ?> ";

    var selOrd = document.createElement("select");
 	selOrd.onchange = handler;
    selOrd.id = "select-orderby-"+tab;
    selOrd.className = "toolbar form-select";
    selOrd.name = "orderby";
    selOrd.setAttribute("aria-label","Sort by");
    sbTool.appendChild(selOrd);
    for(var key in sortOpts){
        var opt = document.createElement("option");
        opt.value=key;
        opt.innerHTML += sortOpts[key].valueOf();
        selOrd.appendChild(opt);
        if(args.orderby == key){
        	opt.selected = true;
        }
    }
    var sortBys = {ASC: '<?php echo $LNG->SORT_ASC; ?>', DESC: '<?php echo $LNG->SORT_DESC; ?>'};
    var sortBy = document.createElement("select");
 	sortBy.onchange = handler;
    sortBy.id = "select-sort-"+tab;
    sortBy.className = "toolbar form-select";
    sortBy.name = "sort";
    sortBy.setAttribute("aria-label","Order by");
    sbTool.appendChild(sortBy);
    for(var key in sortBys){
        var opt = document.createElement("option");
        opt.value=key;
        opt.innerHTML += sortBys[key];
        sortBy.appendChild(opt);
        if(args.sort == key){
        	opt.selected = true;
        }
    }

    return sbTool;
}

/** LEMON BASKET DRAG AND DROP **/

function lemondragstart(e) {
	const lemonbasketcount = document.getElementById('lemonbasketcount');
	if (parseInt(lemonbasketcount.innerHTML) <= 0) {
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
			const lemoncount = parseInt(document.getElementById('lemoncount'+nodeid).innerHTML);
			if (lemoncount == 1) {
				document.getElementById('lemondiv'+nodeid).style.display = 'none';
			}
			document.getElmenetById('lemoncount'+nodeid).innerHTML = lemoncount - 1;
			document.getElementById('lemonbasketcount').innerHTML = parseInt(document.getElementById('lemonbasketcount').innerHTML) - 1;
			document.body.style.cursor = 'default';
		}

		unlemonNode(nodeid, NODE_ARGS['nodeid'], callback);
	}
	return true;
}
