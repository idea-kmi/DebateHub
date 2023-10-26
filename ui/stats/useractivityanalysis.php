<?php
	/********************************************************************************
	 *                                                                              *
	 *  (c) Copyright 2015-2023 The Open University UK                              *
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
	/** Author: Michelle Bachler, KMi, The Open University **/

	include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
	checkDashboardAccess('GLOBAL');
	require_once($HUB_FLM->getCodeDirPath("core/io/catalyst/analyticservices.php"));

	$sdt = trim(optional_param("startdate","",PARAM_TEXT));
	$edt = trim(optional_param("enddate","",PARAM_TEXT));

	$data = array();
	$usersCheck = array();
	$users = array();
	$nodes = array();

	if ($sdt != "" && $edt != "") {

		$start = strtotime($sdt);
		$end = strtotime($edt);
	
		//$style = 'shortactivity:'.$start.":".$end;

		$nodeSet = getNodesByGlobal(0,-1,'date','ASC', 'Issue,Solution,Pro,Con', 'mini');
		$nodes = $nodeSet->nodes;

		$count = 0;
		if (is_countable($nodes)) {
			$count = count($nodes);
		}

		$countUsers = 1;

		for ($i=0; $i<$count; $i++) {
			$node = $nodes[$i];
			$activitieSet = getAllNodeActivity($node->nodeid,$start,$end);
			$activities = $activitieSet->activities;
			foreach($activities as $activity) {
				if (isset($activity)) {
					$date = $activity->modificationdate;
					$type = $activity->type;
					$changetype = $activity->changetype;
					if (($type == "Node" && $changetype == 'add') || $type == "Vote") {
						$role = $node->role->name;
						if ($type == "Vote") {
							$role = $LNG->STATS_ACTIVITY_VOTE;
						}

						$userid = $node->users[0]->userid;
						if (!in_array($userid, $usersCheck)) {
							$users[$userid] = $LNG->STATS_ACTIVITY_USER_ANONYMOUS.$countUsers;
							array_push($usersCheck, $userid);

							$userid = $users[$userid];
							$username = $node->users[0]->name;
							$countUsers++;
						} else {
							$userid = $users[$userid];
							$username = $node->users[0]->name;
						}

						$nexttopic = array(
							"date" => $date,
							"userid" => $userid,
							"username" => $username,
							"nodeid" => $node->nodeid,
							"title" => $node->name,
							"nodetype" => $role,
						);
						array_push($data, (object)$nexttopic);
					}
				}
			}
		}
	}

	// sort by date
	function datesort($datekeyA, $datekeyB) {
		$a = $datekeyA->date;
		$b = $datekeyB->date;
		if ($a == $b) $r = 0;
		else $r = ($a > $b) ? 1: -1;
		return $r;
	}

	// get the activity date range for the given set of nodes.
	$nodeString = "";
	$count1 = 0;
	if (is_countable($nodes)) {
		$countl = count($nodes);
	}
	for ($l=0; $l<$count1; $l++) {
		$node = $nodes[$l];
		if ($l == 0) {
			$nodeString .= "'".$node->nodeid."'";
		} else {
			$nodeString .= ",'".$node->nodeid."'";
		}
	}
	$dateRange = getActivityDateRange($nodeString);

	include_once($HUB_FLM->getCodeDirPath("ui/headerstats.php"));
?>

<script type='text/javascript'>
	var NODE_ARGS = new Array();

	Event.observe(window, 'load', function() {
		NODE_ARGS['data'] = <?php echo json_encode($data, JSON_INVALID_UTF8_IGNORE); ?>;

		const data = NODE_ARGS['data'];
		if (data.length > 0) {
			document.getElementById("allStatsArea").style.visibility = "visible";
			$('messagearea').update(getLoadingLine("<?php echo $LNG->LOADING_DATA; ?>"));
			displayUserActivityCrossFilterD3Vis(data, 980);
		} else {
			document.getElementById("allStatsArea").style.visibility = "hidden";
			<?php if ($sdt != "" && $edt != "") {?>
				$('messagearea').innerHTML="<?php echo $LNG->NETWORKMAPS_NO_RESULTS_MESSAGE; ?>";
			<?php } ?>
		}		
	});

	function checkForm() {
		//check dates
		var startdate = ($('startdate').value).trim();
		var enddate = ($('enddate').value).trim();

		if (startdate == "") {
			alert("<?php echo $LNG->STATS_START_DATE_ERROR; ?>");
			return false;
		}
		if (enddate == "") {
			alert("<?php echo $LNG->STATS_END_DATE_ERROR; ?>");
			return false;
		}

		if (startdate != "" && enddate != "") {
			var sdate = Date.parse(startdate);
			var edate = Date.parse(enddate);

			if (sdate >= edate) {
				alert("<?php echo $LNG->STATS_START_END_DATE_ERROR; ?>");
				return false;
			}
		}

		$('messagearea').update(getLoadingLine("<?php echo $LNG->LOADING_DATA; ?>"));

		return true;
	}	
</script>

<div>
	<h1><?php echo $dashboarddata[$pageindex][0]; ?></h1>
	<p><?php echo $dashboarddata[$pageindex][5]; ?></p>
</div>

<form id="dateform" name="dateform" action="" enctype="multipart/form-data" method="post" onsubmit="return checkForm();">
	<div id="datediv" class="formrow" style="display: block;padding-top:5px;">
		<label class="formlabelbig" for="startdate"><?php echo $LNG->STATS_START_DATE; ?></label>
		<input class="dateinput" id="startdate" name="startdate" value="<?php if ($sdt != "") { echo date('d M Y H:i',$start); } ?>">
		<img src="<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/cal.gif" onclick="javascript:NewCssCal('<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/','startdate','DDMMMYYYY','dropdown',true,'24')" style="cursor:pointer"/>

		<label style="padding-left:10px;" for="enddate"><b> <?php echo $LNG->STATS_END_DATE; ?> </b></label>
		<input class="dateinput" id="enddate" name="enddate" value="<?php if ($edt != "") { echo date('d M Y H:i',$end); } ?>">
		<img src="<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/cal.gif" onclick="javascript:NewCssCal('<?php echo $CFG->homeAddress; ?>ui/lib/datetimepicker/images2/','enddate','DDMMMYYYY','dropdown',true,'24')" style="cursor:pointer;"/>

		<span>&nbsp;&nbsp;</span><input type="submit" class="submit" value="<?php echo $LNG->STATS_LOAD_BUTTON; ?>" />
	</div>
	<p><?php echo $LNG->STATS_AVAILABLE_FROM; ?> <b><?php echo date('d M Y H:i',$dateRange->min); ?></b> <?php echo $LNG->STATS_AVAILABLE_TO; ?> <b><?php echo date('d M Y H:i',$dateRange->max); ?></b></p>
	<span><?php echo $LNG->STATS_ACTIVITY_WARNING; ?></span>
</form>

<div class="d-flex flex-column">
	<div id="messagearea"></div>

	<div id="keyarea" class="w-100 text-end keyarea statsgraph"></div>
</div>

<div id="allStatsArea" style="visibility:hidden;">

	<div class="d-flex flex-column">
		<div class="d-flex flex-row">
			<div>
				<div class="title"><?php echo $LNG->STATS_ACTIVITY_FILTER_USERS_TITLE; ?></div>
				<div id="user-chart" class="statsgraph"></div>
			</div>
			<div>
				<div class="title"><?php echo $LNG->STATS_ACTIVITY_FILTER_ACTION_TITLE; ?></div>
				<div id="nodetype-chart" class="statsgraph"></div>
			</div>
		</div>

		<div class="d-flex flex-column mt-5">
			<div id="data-count">
				<span class="filter-count"></span> <?php echo $LNG->STATS_ACTIVITY_SELECTED_COUNT_MESSAGE_PART1; ?> <span class="total-count"></span> <?php echo $LNG->STATS_ACTIVITY_SELECTED_COUNT_MESSAGE_PART2; ?> | <a
					href="javascript:dc.filterAll(); dc.renderAll();"><?php echo $LNG->STATS_ACTIVITY_RESET_ALL_BUTTON; ?></a>
			</div>
			<table id="data-table" class="table table-hover dc-data-table">
				<thead>
					<tr class="header">
						<th><?php echo $LNG->STATS_ACTIVITY_COLUMN_DATE; ?></th>
						<th><?php echo $LNG->STATS_ACTIVITY_COLUMN_ACTION; ?></th>
						<th><?php echo $LNG->STATS_ACTIVITY_COLUMN_TITLE; ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<?php
	include_once($HUB_FLM->getCodeDirPath("ui/footerstats.php"));
?>
