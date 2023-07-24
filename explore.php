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

include_once("config.php");

$me = substr($_SERVER["PHP_SELF"], 1); // remove initial '/'
if ($HUB_FLM->hasCustomVersion($me)) {
	$path = $HUB_FLM->getCodeDirPath($me);
	include_once($path);
	die;
}

global $USER;

$ref = "http" . ((!empty($_SERVER["HTTPS"])) ? "s" : "") . "://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

// default parameters
$start = optional_param("start",0,PARAM_INT);
$max = optional_param("max",20,PARAM_INT);
$orderby = optional_param("orderby","",PARAM_ALPHA);
$sort = optional_param("sort","DESC",PARAM_ALPHA);

$nodeid = required_param("id",PARAM_ALPHANUMEXT);
$focusid = optional_param("focusid","",PARAM_ALPHANUMEXT);
if ($focusid != "") {
	$selectednodeid = $focusid;
} else {
	$selectednodeid = $nodeid;
}

$searchid = optional_param("sid","",PARAM_ALPHANUMEXT);
if ($searchid != "" && isset($USER->userid)) {
	auditSearchResult($searchid, $USER->userid, $nodeid, 'N');
}

$node = getNode($nodeid, 'long');
if($node instanceof Error){
	echo "<h1>".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
	include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
	die;
} else {
	$userid = "";
	if (isset($USER->userid)) {
		$userid = $USER->userid;
	}
	auditView($userid, $nodeid, 'explore');
}

if (isset($USER->userid) &&
		isset($node->properties['lemoningon']) && $node->properties['lemoningon'] == 'Y') {

	$lemonset = getMyLemonsForIssue($nodeid);
	$lemonnodes = $lemonset->nodes;
	$lemoncount = count($lemonnodes);
	$lemonsleft = 10-$lemoncount;
}

$nodetype = $node->role->name;
if ($nodetype != "Issue") {
	//get the Issue for this node.
	if ($nodetype == "Solution") {
		$selectednodeid = $nodeid;
		$connSet = getConnectionsByNode($node->nodeid,0,1,'date','ASC', 'all','','Issue');
		$con = $connSet->connections[0];
		$node = $con->to;
		if($node instanceof Error){
			echo "<h1>".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
			include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
			die;
		} else {
			$nodeid = $node->nodeid;
			$node = getNode($nodeid);
			if($node instanceof Error){
				echo "<h1>".$LNG->ISSUE_NAME." ".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
				include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
				die;
			}
		}
	} else if ($nodetype == "Pro" || $nodetype == "Con" || $nodetype == "Comment"){
		$selectednodeid = $nodeid;
		$conSetSol = getConnectionsByNode($node->nodeid,0,1,'date','ASC', 'all', '', 'Solution');
		$consol = $conSetSol->connections[0];
		$nodesol = $consol->to;
		$consSet = getConnectionsByNode($nodesol->nodeid,0,1,'date','ASC', 'all', '', 'Issue');
		$con = $consSet->connections[0];
		$localnode = $con->to;
		if($localnode instanceof Error){
			echo "<h1>".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
			include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
			die;
		} else {
			$nodeid = $localnode->nodeid;
			$node = getNode($nodeid);
			if($node instanceof Error){
				echo "<h1>".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
				include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
				die;
			}
		}
	}
}

$groupid = optional_param("groupid", "", PARAM_ALPHANUMEXT);

// try and get the groupid from the node
if ($groupid == "") {
	if (isset($node->groups)) {
		$groups = $node->groups;
		// there should only be one group per node.
		if (count($groups) > 0) {
			$groupid = $groups[0]->groupid;
		}
	}
}

if (isset($groupid) && $groupid != "") {
	$group = getGroup($groupid);
	//getGroup does not return group properties apart from its members
	if($group instanceof Error){
		echo "<h1>Group not found</h1>";
		include_once("includes/footer.php");
		die;
	} else {
		$userset = $group->members;
		$members = $userset->users;
		$memberscount = sizeof($members);
	}
}

$errors = array();

if (isset($_POST["joingroup"])) {
	if (isset($USER->userid) && $USER->userid != "" && isset($group)) {
		if ($group->isopenjoining == 'Y') {
			$group->addmember($USER->userid);
			$userset = $group->members;
			$memberscount = count($members);
		} else if ($group->isopenjoining == 'N') {
			$reply = $group->joinrequest($USER->userid);
			if (!$reply instanceof error) {
				// loop through group members and send all admins and email about the join request.
				$userset = $group->members;
				$members = $userset->users;
				$count = count($members);
				for($i=0; $i<$count; $i++) {
					$member = $members[$i];
					if ($group->isgroupadmin($member->userid)) {
						$paramArray = array ($group->name,$CFG->homeAddress,$USER->userid,$USER->name,$CFG->homeAddress,$groupid);
						sendMail("groupjoinrequest",$LNG->VALIDATE_GROUP_JOIN_SUBJECT,$member->getEmail(),$paramArray);
					}
				}
				$userset = $group->members;
				$memberscount = count($members);
			}
		}
	}
}

unset($_SESSION['HUB_CANADD']);
unset($_SESSION['IS_MODERATOR']);

$hasAddPermissions = false;
if (isset($USER->userid) && isset($groupid) && isGroupMember($groupid,$USER->userid)) {
	$hasAddPermissions = true;
} else if (isset($USER->userid) && (!isset($groupid) || $groupid == "")) {
	$hasAddPermissions = true;
}
$can_moderate = false;
if (isset($USER->userid) &&
	($node->users[0]->userid == $USER->userid
		/*|| $USER->getIsAdmin() == "Y"*/
		|| ($group != "" && isGroupMember($groupid,$USER->userid) && $group->isgroupadmin($USER->userid))))
{
	$can_moderate = true;
}

$_SESSION['HUB_CANADD'] = $hasAddPermissions;
$_SESSION['IS_MODERATOR'] = $can_moderate;

$mode = optional_param("mode","Gather",PARAM_ALPHA); // Gather/Organize(moderate)

$args = array();
$args["nodeid"] = $nodeid;
$args["selectednodeid"] = $selectednodeid;
$args["groupid"] = $groupid;
$args["nodetype"] = $nodetype;
$args["title"] = $node->name;
$args["start"] = $start;
$args["max"] = $max;
$args["mode"] = $mode;
$args["ref"] = $ref;

$wasEmpty = false;
if ($orderby == "") {
	$args["orderby"] = 'random';
	$wasEmpty = true;
} else {
	$args["orderby"] = $orderby;
}
$args["sort"] = $sort;

$CONTEXT = $CFG->NODE_CONTEXT;

// now trigger the js to load data
$argsStr = "{";
$keys = array_keys($args);
for($i=0;$i< sizeof($keys); $i++){
	$argsStr .= '"'.$keys[$i].'":"'.$args[$keys[$i]].'"';
	if ($i != (sizeof($keys)-1)){
		$argsStr .= ',';
	}
}
$argsStr .= "}";

array_push($HEADER,'<script src="'.$HUB_FLM->getCodeWebPath('ui/exploreutils.js.php').'" type="text/javascript"></script>');

//checkLogin();

include_once($HUB_FLM->getCodeDirPath("ui/header.php"));
include_once($HUB_FLM->getCodeDirPath("ui/popuplib.php"));

try {
	$jsonnode = json_encode($node);
} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "<br>";
}

echo "<script type='text/javascript'>";
echo "var CONTEXT = '".$CONTEXT."';";
echo "var NODE_ARGS = ".$argsStr.";";
echo "var nodeObj = ";
echo $jsonnode;
echo ";";
echo "</script>";
?>

<div style="clear:both;border-top:1px solid #E8E8E8;"></div>

<div style="clear:both;float: left; width:100%;height:100%;">

	<!-- LEFT COLUMN -->
	<div style="width 100%;margin-right: 210px;">

	<?php if (isset($groupid) && $groupid != "") { ?>
	<a href="<?php echo $CFG->homeAddress.'group.php?groupid='.$group->groupid;?>" style="float:left;font-weight:bold;font-size:12pt;padding-top:5px;"><?php echo $group->name; ?></a>
	<?php } ?>
		<div style="float:left;width: 99%;font-weight:normal;margin-top:0px;font-size:11pt;">
			<div id="mainnodediv" style="clear:both;float:left;margin:0px;padding:0px;margin-bottom:0px;margin-top:10px;margin-right: 290px;"></div>

			<div id="phaseindicator" style="clear:both;float:left;display:none;">
				<h2 style="float:left;clear:both;padding-top:0px;padding-bottom:0px;margin-bottom:0px;">
					<ul class="phasearrow" style="padding-left:0px;margin-top:5px;margin-bottom:5px;">
						<li style="width:148px;" id="start1"><span><?php echo $LNG->ISSUE_PHASE_START; ?></span></li>
						<li style="width:148px;" id="discuss1"><span><?php echo $LNG->ISSUE_PHASE_DISCUSS; ?></span></li>
						<li style="width:148px;" id="reduce1"><span><?php echo $LNG->ISSUE_PHASE_REDUCE; ?></span></li>
						<li style="width:148px;" id="decide1"><span><?php echo $LNG->ISSUE_PHASE_DECIDE; ?></span></li>
						<li style="width:148px;" id="end1"><span><?php echo $LNG->ISSUE_PHASE_END; ?></span></li>
					</ul>
				</h2>
			</div>

			<div id="discusshelp" style="clear:both;float:left;display:none"><?php echo $LNG->ISSUE_PHASE_DISCUSS_HELP; ?></div>
			<div id="reducehelp" style="clear:both;float:left;display:none"><?php echo $LNG->ISSUE_PHASE_REDUCE_HELP; ?></div>
			<div id="decidehelp" style="clear:both;float:left;display:none"><?php echo $LNG->ISSUE_PHASE_DECIDE_HELP; ?></div>

			<!-- div id="pageseparatorbar" class="modeback1" style="clear:both;height:5px;width:760px;"></div -->

				<div id="maininner" style="clear:both;float:left;padding-top:5px;width:755px;">
				<div id="addnewideaarea" style="display:none;clear:both;float:left;margin-bottom:10px;margin-top:5px;width:100%;padding:3px;" class="boxshadowsquaregreen">
				<?php if (isset($USER->userid)) {
					if ( ($groupid != "" && isGroupMember($groupid,$USER->userid)) || $groupid == "") { ?>
							<?php
							if(!empty($errors)){
								echo "<div class='errors'>".$LNG->FORM_ERROR_MESSAGE.":<ul>";
								foreach ($errors as $error){
									echo "<li>".$error."</li>";
								}
								echo "</ul></div>";
							} else {
								$idea = "";
								$ideadesc = "";
							}
							?>
							<div id="newideaform" style="float:left;width:100%;padding:3px;">
								<h2 style="float:left;margin-bottom:0px;padding-bottom:0px;margin-left:5px;font-size:12pt;"><img src="<?php echo $HUB_FLM->getImagePath('add.png'); ?>" border="0" style="vertical-align:bottom;padding-right:3px;" /><?php echo $LNG->FORM_IDEA_NEW_TITLE; ?></h2>
								<div id="addformdividea" style="float:left;width:100%;display:block">
									<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
									<input type="hidden" id="nodeid" name="nodeid" value="<?php echo $nodeid; ?>">
									<div class="formrowsm">
										<input <?php if (!$hasAddPermissions){ echo 'disabled'; } ?> class="forminput hgrwide" placeholder="<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>" id="addideaname" name="idea" value="" />
										<button <?php if (!$hasAddPermissions){ echo 'disabled'; } ?> class="submitleft" id="addidea" name="addidea" onclick="addIdeaNode(nodeObj, '', 'idea', 'active', true, <?php echo $CFG->STATUS_ACTIVE; ?>)"><?php echo $LNG->FORM_BUTTON_SUBMIT; ?></button>
									</div>
									<div class="formrowsm">
										<textarea <?php if (!$hasAddPermissions){ echo 'disabled'; } ?> class="forminput hgrwide" style="height:16px;" placeholder="<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>" id="addideadesc" name="ideadesc" value=""></textarea>
									</div>
									<div id="linksdividea" class="formrowsm" style="padding-top:15px;">
										<input <?php if (!$hasAddPermissions){ echo 'disabled'; } ?> class="forminput hgrwide" style="height:16px;margin-bottom:2px;" placeholder="<?php echo $LNG->FORM_LINK_LABEL; ?>" id="argumentlinkidea0" name="argumentlinkidea[]" value=""></textarea>
									</div>
									<div class="formrowsm">
										<span <?php if (!$hasAddPermissions){ echo 'disabled'; } ?> onclick="insertIdeaLink('', 'idea')" class="active forminput" style="margin-top:0px;padding-top:0px;font-size:8pt" ><?php echo $LNG->FORM_MORE_LINKS_BUTTONS; ?></span>
									</div>
								</div>
							</div>
					<?php } else if (isGroupPendingMember($groupid,$USER->userid)) { ?>
							<span><?php echo $LNG->GROUP_JOIN_PENDING_MESSAGE; ?><img style="margin-left:7px;" src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" onmouseover="showGlobalHint('PendingMember', event, 'hgrhint')" onmouseout="hideHints()" border="0" /></span>
					<?php } else if (isset($USER->userid) && $groupid != "" && !isGroupMember($groupid,$USER->userid)) { ?>
						<div style="float:left;clear:both;">
							<?php if ($group->isopenjoining == 'Y') {?>
								<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
									<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
									<input class="mainfont active submitleft" style="font-size:12pt; border: none; background: transparent;" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP; ?>" id="joingroup" name="joingroup"></input>
									<?php echo $LNG->GROUP_JOIN_GROUP; ?>
								</form>
							<?php } else if ($group->isopenjoining == 'N' && !isGroupRejectedMember($groupid,$USER->userid) && !isGroupReportedMember($groupid,$USER->userid)) {  ?>
								<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
									<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
									<input class="mainfont active submitleft" style="font-size:12pt; border: none; background: transparent;" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP_CLOSED; ?>" id="joingroup" name="joingroup"></input>
									<?php echo $LNG->GROUP_JOIN_GROUP; ?>
								</form>
							<?php } ?>
						</div>
					<?php }
				 } else { ?>
					<div style="float:left;clear:both;bottom-top:20px;">
					<?php
					if ($CFG->signupstatus == $CFG->SIGNUP_OPEN) {
						echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registeropen.php">'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'</a> '.$LNG->SOLUTION_CREATE_LOGGED_OUT_OPEN;
					} else if ($CFG->signupstatus == $CFG->SIGNUP_REQUEST) {
						echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registerrequest.php">'.$LNG->HEADER_SIGNUP_REQUEST_LINK_TEXT.'</a> '.$LNG->SOLUTION_CREATE_LOGGED_OUT_REQUEST;
					} else {
						echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> '.$LNG->SOLUTION_CREATE_LOGGED_OUT_CLOSED;
					}
					echo '</div>';
				}?>
				</div>

				<?php if ($_SESSION['IS_MODERATOR']){ ?>
				<div id="mergeideaform" style="float:left;display:none;margin-top:10px;">
					<div id="pageseparatorbar" class="modeback1" style="clear:both;height:5px;width:760px;margin-bottom:10px;"></div>

					<button onclick="toggleMergeIdeas();return false;" style="margin-bottom:0px;padding-bottom:0px;float:left;"><h2 style="margin-bottom:0px"><?php echo $LNG->FORM_IDEA_MERGE_TITLE; ?></h2></button>
					<img title="<?php echo $LNG->FORM_IDEA_MERGE_HINT; ?>" src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="vertical-align:top;padding-left:5px;padding-top:3px;">
					<div id="mergeideadiv" style="float:left;display:none;clear:both;width:742px" class="boxshadowsquare">
						<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
						<input type="hidden" id="nodeid" name="nodeid" value="<?php echo $nodeid; ?>">
						<div class="formrowsm">
							<input <?php if (!isset($USER->userid) || (isset($USER->userid) && isset($groupid) && $groupid != "" && !isGroupMember($groupid,$USER->userid))){ echo 'disabled'; } ?> class="forminput hgrwide" placeholder="<?php echo $LNG->FORM_IDEA_MERGE_LABEL_TITLE; ?>" id="mergeidea" name="mergeidea" value="" />
							<button <?php if (!isset($USER->userid) || (isset($USER->userid) && isset($groupid) && $groupid != "" && !isGroupMember($groupid,$USER->userid))){ echo 'disabled'; } ?> class="submitleft" id="mergeidea" name="mergeidea" onclick="mergeSelectedNodes()"><?php echo $LNG->FORM_BUTTON_SUBMIT; ?></button>
						</div>
						<div class="formrowsm">
							<textarea <?php if (!isset($USER->userid) || (isset($USER->userid) && isset($groupid) && $groupid != "" && !isGroupMember($groupid,$USER->userid))){ echo 'disabled'; } ?> rows="3" class="forminput hgrwide" placeholder="<?php echo $LNG->FORM_IDEA_MERGE_LABEL_DESC; ?>" id="mergeideadesc" name="mergeideadesc" value=""></textarea>
						</div>
					</div>
				</div>
				<?php } ?>

				<div id="tabber" style="clear:both;float:left; width:760px;display:none;">
					<ul id="tabs" class="tab">
						<li class="tab"><a class="tab" id="tab-remaining" href="#remaining"><span class="tab tabsolution">Remaining <span id="remaining-count"></span></a></li>
						<li class="tab"><a class="tab" id="tab-removed" href="#removed"><span class="tab tabsolution">Removed <span id="removed-count"></span></a></li>
					</ul>
					<div id="tabs-content" style="clear:both; float:left; width:100%">
						<div id='tab-content-remaining-div' class='tabcontentinner' style="display:none;padding:0px;"></div>
						<div id='tab-content-removed-div' class='tabcontentinner' style="display:none;padding:0px;"></div>
					</div>
				</div>

				<div id='content-ideas-div' style='clear:both; float:left; width:760px;'>
					<div id='tab-content-idea-list' class='tabcontentinner'></div>
				</div>
			</div>
		</div>
	</div>

	<!-- RIGHT COLUMN -->
	<div style="float: right; height:100%;width:210px; margin-left: -210px;margin-top:10px;">
		<div style="width:197px;float:left;">
			<fieldset class="plainborder" style="clear:both;float:left;width:100%;padding:5px;padding-bottom:0px;padding-top:2px;margin:0px;">
			<legend><h2 style="margin-bottom:0px;"><?php echo $LNG->DEBATE_MODERATOR_SECTION_TITLE; ?></h2></legend>
			<div style="clear:both;float:left;width:100%;margin:0px;padding:0px;">
				<?php
					$user = $node->users[0];
					echo "<a href='user.php?userid=".$user->userid."' title='".$user->name."'><img style='padding:5px;' border='0' src='".$user->thumb."'/></a>";
					if (isset($group) && isset($members)) {
						foreach($members as $u){
							if ($group->isgroupadmin($u->userid) && $user->userid != $u->userid) {
								echo "<a href='user.php?userid=".$u->userid."' title='".$u->name."'><img style='padding:5px;' border='0' src='".$u->thumb."'/></a>";
							}
						}
					}
				?>
			</div>
			</fieldset>
		</div>

		<?php if ($_SESSION['IS_MODERATOR']){ ?>
			<div id="moderatoralerts" style="float:left;display:none;margin-top:7px;">
				<div class="boxshadowsquare" style="width:190px;float:left;clear:both;">
					<h2 style="padding-top:0px;margin-top:0px;margin-bottom:0px;padding-bottom:0px;"><?php echo $LNG->ALERTS_BOX_TITLE_MODERATOR; ?></h2>
					<div id="moderatoralerts-messagearea" style="width:100%;float:left;clear:both;padding:0px;margin:0px;"></div>
					<div id="moderatoralerts-div" style="float:left;max-height:200px;overflow:auto">
						<div id="moderatoralerts-issue-div" style="clear:both;float:left;"></div>
						<div id="moderatoralerts-user-div" style="clear:both;float:left;"></div>
					</div>
				</div>
			</div>

			<div id="moderatebutton" class="groupbutton modeback1 modeborder1" style="display:none;margin-left:0px;width:202px;margin-top:5px;" onclick="toggleOrganizeMode(this, 'Organize')">
				<div class="groupbuttoninner"><?php echo $LNG->DEBATE_MODE_BUTTON_ORGANIZE; ?></div>
			</div>
		<?php } ?>

		<div id="dashboardbutton" class="groupbutton modeback3 modeborder3" style="margin-left:0px;margin-top:10px;width:202px;">
			<div class="groupbuttoninner"><span style="color:white" onclick="return auditDashboardButton(NODE_ARGS['nodeid'], this.innerHTML, '<?php echo $CFG->homeAddress; ?>ui/stats/debates/index.php?nodeid=<?php echo $nodeid; ?>', 'issue', 'dashboardButton_Issue_V1')"><?php echo $LNG->PAGE_BUTTON_DASHBOARD; ?></span></div>
		</div>

		<div id="healthindicatorsdiv" style="clear:both;float: right; height:100%;width:210px; padding-right:0px;">
			<div id="health-indicators" class="boxshadowsquare" style="width:190px;float:left;clear:both;margin-top:10px;">
				<div>
					<b><?php echo $LNG->STATS_DEBATE_HEALTH_TITLE; ?></b>
					<span style="float:left;clear:both;font-style:italic" /><?php echo $LNG->STATS_DEBATE_HEALTH_MESSAGE; ?></span>
				</div>

				<div id='health-participation' style="float:left;width:190px;margin-top:10px;display:none;">
					<div id='health-participation-trafficlight' class="trafficlightacross active" onclick="showIndicatorDetails('participation')">
						<span id='health-participation-red' class="trafficlightred"></span>
						<span id='health-participation-orange' class="trafficlightorange"></span>
						<span id='health-participation-green' class="trafficlightgreen"></span>
					</div>
					<div style="float:left;width:115px;margin-top:7px;font-size:8pt">
						<?php echo $LNG->STATS_DEBATE_PARTICIPATION_TITLE; ?>
					</div>
					<div id="health-participation-details" style="clear:both;float:left;width:190px;margin-top:10px;display:none">
						<div style="clear:both;float:left;">
							<b><span id="health-participation-count"></span></b> <span id='health-participation-message'> </span>
							<br>
							<span class="active" onMouseOver="showGlobalHint('StatsOverviewParticipation', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)"><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="margin-right: 5px;" /></span>
							<span id="health-participation-recomendation" style="vertical-align:top"></span>
						</div>
						<div id="health-participation-explore" style="float:left;clear:both;margin-top:5px;font-style:italic;"><?php echo $LNG->STATS_DEBATE_HEALTH_EXPLORE; ?></div>
						<div id="health-participation-links" class='trafficlightbutton'>
							<div style="display:inline-block;"><span class="active" onclick="return auditParticipationStatsLink('<?php echo $CFG->homeAddress;?>ui/stats/debates/useractivityanalysis.php?page=useractivityfilter&nodeid=<?php echo $nodeid; ?>');"><?php echo $LNG->STATS_TAB_USER_ACTIVITY_ANALYSIS; ?></span></div>
						</div>
					</div>
				</div>

				<div id='health-viewing' style="clear:both;float:left;width:190px;margin-top:10px;display:none;">
					<div id='health-viewing-trafficlight' class="trafficlightacross active" onclick="showIndicatorDetails('viewing')">
						<span id='health-viewing-red' class="trafficlightred"></span>
						<span id='health-viewing-orange' class="trafficlightorange"></span>
						<span id='health-viewing-green' class="trafficlightgreen"></span>
					</div>
					<div style="float:left;width:115px;margin-top:7px;font-size:8pt">
						<?php echo $LNG->STATS_DEBATE_VIEWING_TITLE; ?>
					</div>
					<div id="health-viewing-details" style="float:left;margin-top:10px;margin-bottom:10px;display:none">
						<div style="clear:both;float:left;">
							<b><span id="health-viewingpeople-count"></span></b> <span id='health-viewing-message'></span>
							<b> <span id="health-viewinggroup-count"></span></b> <span id='health-viewing-message-part2'> </span>
							<br>
							<span class="active" onMouseOver="showGlobalHint('StatsDebateViewing', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)"><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="margin-right: 5px;" /></span>
							<span id="health-viewing-recomendation" style="vertical-align:top"></span>
						</div>
						<div id="health-viewing-explore" style="float:left;clear:both;margin-top:5px;font-style:italic;"><?php echo $LNG->STATS_DEBATE_HEALTH_EXPLORE; ?></div>
						<div id="health-participation-links" class='trafficlightbutton'>
							<div style="display:inline-block;"><span class="active" onclick="return auditViewingStatsLink('<?php echo $CFG->homeAddress;?>ui/stats/debates/activityanalysis.php?page=activityfilter&nodeid=<?php echo $nodeid; ?>');"><?php echo $LNG->STATS_TAB_ACTIVITY_ANALYSIS; ?></span></div>
						</div>
					</div>
				</div>

				<div id='health-debate' style="clear:both;float:left;width:190px;margin-top:10px;display:none;">
					<div id='health-debate-trafficlight' class="trafficlightacross active" onclick="showIndicatorDetails('debate')">
						<span id='health-debate-red' class="trafficlightred"></span>
						<span id='health-debate-orange' class="trafficlightorange"></span>
						<span id='health-debate-green' class="trafficlightgreen"></span>
					</div>
					<div style="float:left;width:115px;margin-top:7px;font-size:8pt">
						<?php echo $LNG->STATS_DEBATE_CONTRIBUTION_TITLE; ?>
					</div>
					<div id="health-debate-details" style="clear:both;float:left;margin-top:10px;display:none;">
						<div style="clear:both;float:left;">
							<span id="health-debate-message"></span>
							<br>
							<span class="active" onMouseOver="showGlobalHint('StatsDebateContribution', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)"><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="margin-right: 5px;" /></span>
							<span id="health-debate-recomendation" style="vertical-align:top"></span>
						</div>
						<div id="health-debate-explore" style="float:left;clear:both;margin-top:5px;font-style:italic;"><?php echo $LNG->STATS_DEBATE_HEALTH_EXPLORE; ?></div>
						<div id="health-debate-links" class='trafficlightbutton'>
							<div style="display:inline-block;"><span class="active" onclick="return auditDebateStatsLink('<?php echo $CFG->homeAddress;?>ui/stats/debates/circlepacking.php?page=circlepacking&nodeid=<?php echo $nodeid; ?>');"><?php echo $LNG->STATS_TAB_CIRCLEPACKING; ?></span></div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<!-- BAG OF LEMONS -->
		<?php if (isset($USER->userid)) {?>
			<div id="lemonbasket" style="display:none;position:fixed; top: 400px;clear:both; float:left;margin-top:10px;">
				<img id="addlemon" draggable="true" ondragstart="lemondragstart(event)" ondragover="lemonbasketdragover(event)" ondragenter="lemonbasketdragenter" ondrop="lemonbasketdrop(event)" src="<?php echo $HUB_FLM->getImagePath('basket-120-lemons.png'); ?>" border="0" style="vertical-align:bottom;" />
				<div style="clear:both;"><?php echo $LNG->LEMONING_COUNT_LEFT; ?> <span id="lemonbasketcount" style="font-size:12pt; font-weight:bold"><?php if (isset($lemonsleft)) { echo $lemonsleft; } ?></span></div>
			</div>
		<?php } ?>

		<?php if ($_SESSION['HUB_CANADD']){ ?>
			<div id="useralerts" style="float:left;display:none;margin-top:7px;">
				<div class="boxshadowsquare" style="width:190px;float:left;clear:both;">
					<h2 style="padding-top:0px;margin-top:0px;margin-bottom:0px;padding-bottom:0px;"><?php echo $LNG->ALERTS_BOX_TITLE_MINE; ?></h2>
					<div id="useralerts-messagearea" style="width:100%;float:left;clear:both;padding:0px;margin:0px;"></div>
					<div id="useralerts-div" style="float:left;max-height:200px;overflow:auto">
						<div id="useralerts-issue-div" style="clear:both;float:left;"></div>
						<div id="useralerts-user-div" style="clear:both;float:left;"></div>
					</div>
				</div>
			</div>
		<?php } ?>

	</div>
</div>

<?php
    include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
?>
