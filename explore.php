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

if($node instanceof Hub_Error){
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

	$lemoncount = 0;
	if (is_countable($lemonnodes)) {
		$lemoncount = count($lemonnodes);
	}
	$lemonsleft = 10-$lemoncount;
}

$nodetype = $node->role->name;
if ($nodetype != "Issue") {
	//get the Issue for this node.
	if ($nodetype == "Solution") {
		$selectednodeid = $nodeid;
		$connSet = getConnectionsByNode($node->nodeid,0,1,'date','ASC', 'all','','Issue');
		if (isset($connSet->connections[0])) {
			$con = $connSet->connections[0];
			if (isset($con->to)) {
				$node = $con->to;
				if($node instanceof Hub_Error){
					echo "<h1>".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
					include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
					die;
				} else {
					$nodeid = $node->nodeid;
					$node = getNode($nodeid);
					if($node instanceof Hub_Error){
						echo "<h1>".$LNG->ISSUE_NAME." ".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
						include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
						die;
					}
				}
			}
		}
	} else if ($nodetype == "Pro" || $nodetype == "Con" || $nodetype == "Comment"){
		$selectednodeid = $nodeid;
		$conSetSol = getConnectionsByNode($node->nodeid,0,1,'date','ASC', 'all', '', 'Solution');
		$consol = $conSetSol->connections[0];
		$nodesol = $consol->to;
		$consSet = getConnectionsByNode($nodesol->nodeid,0,1,'date','ASC', 'all', '', 'Issue');
		if (isset($connSet->connections[0])) {
			$con = $consSet->connections[0];
			if (isset($con->to)) {
				$localnode = $con->to;
				if($localnode instanceof Hub_Error){
					echo "<h1>".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
					include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
					die;
				} else {
					$nodeid = $localnode->nodeid;
					$node = getNode($nodeid);
					if($node instanceof Hub_Error){
						echo "<h1>".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
						include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
						die;
					}
				}
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
		$groupcount = 0;
		if (is_countable($groups)) {
			$groupcount = count($groups);
		}
		if ($groupcount > 0) {
			$groupid = $groups[0]->groupid;
		}
	}
}

if (isset($groupid) && $groupid != "") {
	$group = getGroup($groupid);
	//getGroup does not return group properties apart from its members
	if($group instanceof Hub_Error){
		echo "<h1>Group not found</h1>";
		include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
		die;
	} else {
		$userset = $group->members;
		$members = $userset->users;

		$memberscount = 0;
		if (is_countable($members)) {
			$memberscount = count($members);
		}
	}
}

$errors = array();

if (isset($_POST["joingroup"])) {
	if (isset($USER->userid) && $USER->userid != "" && isset($group)) {
		if ($group->isopenjoining == 'Y') {
			$group->addmember($USER->userid);
			$userset = $group->members;
			$memberscount = 0;
			if (is_countable($members)) {
				$memberscount = count($members);
			}
		} else if ($group->isopenjoining == 'N') {
			$reply = $group->joinrequest($USER->userid);
			if (!$reply instanceof Hub_Error) {
				// loop through group members and send all admins and email about the join request.
				$userset = $group->members;
				$members = $userset->users;
				$count = 0;
				if (is_countable($members)) {
					$count = count($members);
				}
				for($i=0; $i<$count; $i++) {
					$member = $members[$i];
					if ($group->isgroupadmin($member->userid)) {
						$paramArray = array ($group->name,$CFG->homeAddress,$USER->userid,$USER->name,$CFG->homeAddress,$groupid);
						sendMail("groupjoinrequest",$LNG->VALIDATE_GROUP_JOIN_SUBJECT,$member->getEmail(),$paramArray);
					}
				}
				$userset = $group->members;
				$memberscount = 0;
				if (is_countable($members)) {
					$memberscount = count($members);
				}
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
		|| (isset($group) && $group != "" && isGroupMember($groupid,$USER->userid) && $group->isgroupadmin($USER->userid))))
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

$keycount = 0;
if (is_countable($keys)) {
	$keycount = count($keys);
}

for($i=0;$i< $keycount; $i++){
	$argsStr .= '"'.$keys[$i].'":"'.$args[$keys[$i]].'"';
	if ($i != ($keycount-1)){
		$argsStr .= ',';
	}
}
$argsStr .= "}";

array_push($HEADER,'<script src="'.$HUB_FLM->getCodeWebPath('ui/exploreutils.js.php').'" type="text/javascript"></script>');
//array_push($HEADER,'<script src="'.$HUB_FLM->getCodeWebPath('margot.js.php').'" type="text/javascript"></script>');

//checkLogin();

include_once($HUB_FLM->getCodeDirPath("ui/header.php"));
include_once($HUB_FLM->getCodeDirPath("ui/popuplib.php"));

try {
	$jsonnode = json_encode($node, JSON_INVALID_UTF8_IGNORE);
} catch (Exception $e) {
	echo('Caught exception: '.$e->getMessage());
}

echo "<script type='text/javascript'>";
echo "var CONTEXT = '".$CONTEXT."';";
echo "var NODE_ARGS = ".$argsStr.";";
echo "var nodeObj = ";
echo $jsonnode;
echo ";";
echo "</script>";
?>

<div class="container-fluid">
	<div class="row">		
		<!-- LEFT COLUMN -->
		<div class="col-md-12">
			<?php if (isset($groupid) && $groupid != "") { ?>
				<h3 class="mt-2 px-3 pt-2"><a href="<?php echo $CFG->homeAddress.'group.php?groupid='.$group->groupid;?>" ><?php echo $group->name; ?></a></h3>
			<?php } ?>
		</div>

		<div class="col-md-12 col-lg-9">
			<div>
				<div id="mainnodediv" class="mainnodediv"></div>

				<div id="phaseindicator" style="display:none;">
					<div class="p-3 pb-0">
						<h3>
							<div id="start" class="phasearrow">
								<div id="start1" class="step"><span><?php echo $LNG->ISSUE_PHASE_START; ?></span></div>
								<div id="discuss1" class="step current"><span><?php echo $LNG->ISSUE_PHASE_DISCUSS; ?></span></div>
								<div id="reduce1" class="step"><span><?php echo $LNG->ISSUE_PHASE_REDUCE; ?></span></div>
								<div id="decide1" class="step"><span><?php echo $LNG->ISSUE_PHASE_DECIDE; ?></span></div>
								<div id="end1" class="step"><span><?php echo $LNG->ISSUE_PHASE_END; ?></span></div>
							</div>
						</h3>
					</div>

					<div id="discusshelp" class="ps-3" style="display:none"><?php echo $LNG->ISSUE_PHASE_DISCUSS_HELP; ?></div>
					<div id="reducehelp" class="ps-3" style="display:none"><?php echo $LNG->ISSUE_PHASE_REDUCE_HELP; ?></div>
					<div id="decidehelp" class="ps-3" style="display:none"><?php echo $LNG->ISSUE_PHASE_DECIDE_HELP; ?></div>
				</div>

				<div id="maininner" class="p-3">
					<div id="addnewideaarea" style="display:none;" class="boxshadowsquaregreen">
						<?php 
							if (isset($USER->userid)) {
								if ( ($groupid != "" && isGroupMember($groupid,$USER->userid)) || $groupid == "") { 
									if(!empty($errors)){
										echo "<div class='alert alert-danger'>".$LNG->FORM_ERROR_MESSAGE.":<ul>";
										foreach ($errors as $error){
											echo "<li>".$error."</li>";
										}
										echo "</ul></div>";
									} else {
										$idea = "";
										$ideadesc = "";
									} ?>
									<div id="newideaform" class="p-3 newideaform">
										<h3><?php echo $LNG->FORM_IDEA_NEW_TITLE; ?></h3>
										<div id="addformdividea">
											<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
											<input type="hidden" id="nodeid" name="nodeid" value="<?php echo $nodeid; ?>">
											<div class="row mb-3">
												<label class="d-none" for="addideaname"><?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?></label>
												<input <?php if (!$hasAddPermissions){ echo 'disabled'; } ?> class="form-control" placeholder="<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>" id="addideaname" name="idea" value="" />
											</div>
											<div class="mb-3 row">
												<label class="d-none" for="addideadesc"><?php echo $LNG->FORM_IDEA_LABEL_DESC; ?></label>
												<textarea <?php if (!$hasAddPermissions){ echo 'disabled'; } ?> class="form-control" rows="3" placeholder="<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>" id="addideadesc" name="ideadesc" value=""></textarea>
											</div>
											<div class="mb-3 row" id="linksdividea">
												<label class="d-none" for="argumentlinkidea0"><?php echo $LNG->FORM_LINK_LABEL; ?></label>
												<input <?php if (!$hasAddPermissions){ echo 'disabled'; } ?> class="form-control" placeholder="<?php echo $LNG->FORM_LINK_LABEL; ?>" id="argumentlinkidea0" name="argumentlinkidea[]" value="" />
											</div>
											<div class="mb-3 row">
												<span <?php if (!$hasAddPermissions){ echo 'disabled'; } ?> onclick="insertIdeaLink('', 'idea')" class="active add-another-url"><?php echo $LNG->FORM_MORE_LINKS_BUTTONS; ?></span>
											</div>
											<div class="mb-3 row">
												<div class="d-grid gap-2 d-md-flex justify-content-md-center mb-3">
													<button <?php if (!$hasAddPermissions){ echo 'disabled'; } ?> class="btn btn-primary" id="addidea" name="addidea" onclick="addIdeaNode(nodeObj, '', 'idea', 'active', true, <?php echo $CFG->STATUS_ACTIVE; ?>)"><?php echo $LNG->FORM_BUTTON_SUBMIT; ?></button>
												</div>
											</div>
										</div>
									</div>
								<?php } else if (isGroupPendingMember($groupid,$USER->userid)) { ?>
									<span>
										<?php echo $LNG->GROUP_JOIN_PENDING_MESSAGE; ?>
										<span onMouseOver="showGlobalHint('PendingMember', event, 'hgrhint'); return false;" onfocus="showGlobalHint('PendingMember', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)" class="m-2 help-hint" aria-label="More information">
											<i class="fas fa-question-circle fa-lg" title="Search Info"></i>
										</span>
									</span>
								<?php } else if (isset($USER->userid) && $groupid != "" && !isGroupMember($groupid,$USER->userid)) { ?>
								<div>
									<?php if ($group->isopenjoining == 'Y') {?>
										<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
											<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
											<input class="mainfont active submitleft" style="font-size:12pt; border: none; background: transparent;" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP; ?>" id="joingroup" name="joingroup"></input>
											<?php echo $LNG->GROUP_JOIN_GROUP; ?>
										</form>
									<?php } else if ($group->isopenjoining == 'N' && !isGroupRejectedMember($groupid,$USER->userid) && !isGroupReportedMember($groupid,$USER->userid)) { ?>
										<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
											<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
											<input class="mainfont active submitleft" style="font-size:12pt; border: none; background: transparent;" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP_CLOSED; ?>" id="joingroup" name="joingroup"></input>
											<?php echo $LNG->GROUP_JOIN_GROUP; ?>
										</form>
									<?php } ?>
								</div>
							<?php }
						} else { ?>
							<div style="bottom-top:20px;">
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
						<div id="mergeideaform" style="display:none;" class="mb-3">
							<div id="pageseparatorbar" class="modeback1 pageseparatorbar"></div>

							<button onclick="toggleMergeIdeas();return false;" class="btn btn-light border"><h2 class="m-0 fw-bold"><?php echo $LNG->FORM_IDEA_MERGE_TITLE; ?></h2></button>
							
							<span class="active">
								<i class="far fa-question-circle fa-lg me-2" aria-hidden="true" title="<?php echo $LNG->FORM_IDEA_MERGE_HINT; ?>"></i> 
								<span class="sr-only"><?php echo $LNG->FORM_IDEA_MERGE_HINT; ?></span>
							</span>

							<div id="mergeideadiv" style="display:none;clear:both;width:742px" class="boxshadowsquare">
								<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
								<input type="hidden" id="nodeid" name="nodeid" value="<?php echo $nodeid; ?>">
								<div class="formrowsm">									
									<label class="d-none" for="mergeidea"><?php echo $LNG->FORM_IDEA_MERGE_LABEL_TITLE; ?></label>
									<input <?php if (!isset($USER->userid) || (isset($USER->userid) && isset($groupid) && $groupid != "" && !isGroupMember($groupid,$USER->userid))){ echo 'disabled'; } ?> class="forminput hgrwide" placeholder="<?php echo $LNG->FORM_IDEA_MERGE_LABEL_TITLE; ?>" id="mergeidea" name="mergeidea" value="" />
									<button <?php if (!isset($USER->userid) || (isset($USER->userid) && isset($groupid) && $groupid != "" && !isGroupMember($groupid,$USER->userid))){ echo 'disabled'; } ?> class="submitleft" id="mergeidea" name="mergeidea" onclick="mergeSelectedNodes()"><?php echo $LNG->FORM_BUTTON_SUBMIT; ?></button>
								</div>
								<div class="formrowsm">									
									<label class="d-none" for="mergeideadesc"><?php echo $LNG->FORM_IDEA_MERGE_LABEL_DESC; ?></label>
									<textarea <?php if (!isset($USER->userid) || (isset($USER->userid) && isset($groupid) && $groupid != "" && !isGroupMember($groupid,$USER->userid))){ echo 'disabled'; } ?> rows="3" class="forminput hgrwide" placeholder="<?php echo $LNG->FORM_IDEA_MERGE_LABEL_DESC; ?>" id="mergeideadesc" name="mergeideadesc" value=""></textarea>
								</div>
							</div>
						</div>
					<?php } ?>

					<div id="tabber" style="display:none;">
						<ul id="tabs" class="nav nav-tabs">
							<li class="nav-item"><a class="nav-link active fs-6" id="tab-remaining" data-bs-toggle="tab" data-bs-target="#remaining"><span class="tab tabsolution">Remaining <span id="remaining-count"></span></a></li>
							<li class="nav-item"><a class="nav-link fs-6" id="tab-removed" data-bs-toggle="tab" data-bs-target="#removed"><span class="tab tabsolution">Removed <span id="removed-count"></span></a></li>
						</ul>
						<div id="tabs-content" class="tab-content border border-top-0">
							<div id='tab-content-remaining-div' class='tab-pane fade show active' aria-label="remaining-tab"></div>
							<div id='tab-content-removed-div' class='tab-pane fade active show' aria-label="removed-tab"></div>
						</div>
					</div>

					<div id='content-ideas-div'>
						<div id='tab-content-idea-list' class='tabcontentinner'></div>
					</div>
				</div>
			</div>
		</div>		

		<!-- RIGHT COLUMN -->
		<div class="col-md-12 col-lg-3">
			<fieldset class="border border-2 mx-2 my-4 p-3">
				<legend><h3><?php echo $LNG->DEBATE_MODERATOR_SECTION_TITLE; ?></h3></legend>
				<div class="d-flex flex-row flex-wrap">
					<?php $user = $node->users[0]; ?>
					<a class="m-1" href='user.php?userid=<?php echo $user->userid; ?>' title='<?php echo $user->name; ?>'>
						<img class="p-1" src='<?php echo $user->thumb; ?>' alt='<?php echo $user->name; ?> profile picture' />
					</a>
					<?php 
						if (isset($group) && isset($members)) {
							foreach($members as $u){
								if ($group->isgroupadmin($user->userid) && $user->userid != $user->userid) { ?>
									<a class="m-1" href='user.php?userid=<?php echo $user->userid; ?>' title='<?php echo $user->name; ?>'>
										<img src='<?php echo $user->thumb; ?>' alt='<?php echo $user->name; ?> profile picture' />
									</a>										
								<?php }
							}
						}
					?>
				</div>
			</fieldset>

			<?php if ($_SESSION['IS_MODERATOR']){ ?>
				<div id="moderatebutton" class="btn btn-moderator gap-2 m-2" style="display: none;" onclick="toggleOrganizeMode(this, 'Organize')">
					<span class="fw-bold"><?php echo $LNG->DEBATE_MODE_BUTTON_ORGANIZE; ?></span>
				</div>
			<?php } ?>

			<div id="dashboardbutton" class="d-grid gap-2 m-2">
				<button class="btn btn-secondary text-dark fw-bold" onclick="return auditDashboardButton(NODE_ARGS['nodeid'], this.innerHTML, '<?php echo $CFG->homeAddress; ?>ui/stats/debates/index.php?nodeid=<?php echo $nodeid; ?>', 'issue', 'dashboardButton_Issue_V1')">
					<?php echo $LNG->PAGE_BUTTON_DASHBOARD; ?>
				</button>
			</div>

			<div id="healthindicatorsdiv" class="d-grid gap-2 m-2">
				<div id="health-indicators" class="border border-2 p-3">
					<div>
						<p class="fw-bold mb-0"><?php echo $LNG->STATS_DEBATE_HEALTH_TITLE; ?></p>
						<p class="fst-italic"><?php echo $LNG->STATS_DEBATE_HEALTH_MESSAGE; ?></p>
					</div>

					<div id='health-participation' style="display:none;">
						<div class="d-flex flex-rows">
							<div id='health-participation-trafficlight' class="trafficlightacross active" onclick="showIndicatorDetails('participation')">
								<span id='health-participation-red' class="trafficlightred"></span>
								<span id='health-participation-orange' class="trafficlightorange"></span>
								<span id='health-participation-green' class="trafficlightgreen"></span>
							</div>
							<div class="pt-1 ms-2">
								<p class="mb-0"><?php echo $LNG->STATS_DEBATE_PARTICIPATION_TITLE; ?></p>
							</div>
						</div>
						<div id="health-participation-details" style="display:none">
							<div class="my-2">
								<p><span id="health-participation-count" class="fw-bold"></span> <span id='health-participation-message'></span></p>			
								<p>
									<span onMouseOver="showGlobalHint('StatsOverviewParticipation', event, 'hgrhint'); return false;" onfocus="showGlobalHint('StatsOverviewParticipation', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)" class="m-2 help-hint">
										<i class="fas fa-info-circle fa-lg" title="Info"></i>
									</span>
									<span id="health-participation-recomendation"></span>
								</p>
							</div>
							<div class="d-flex flex-rows">
								<div id="health-participation-explore" class="me-2">
									<p class="fst-italic"><?php echo $LNG->STATS_DEBATE_HEALTH_EXPLORE; ?></p>
								</div>
								<div id="health-participation-links">
									<a class="active" onclick="return auditParticipationStatsLink('<?php echo $CFG->homeAddress;?>ui/stats/debates/useractivityanalysis.php?page=useractivityfilter&nodeid=<?php echo $nodeid; ?>');">
										<?php echo $LNG->STATS_TAB_USER_ACTIVITY_ANALYSIS; ?>
									</a>
								</div>
							</div>
						</div>
					</div>

					<div id='health-viewing' style="display:none;">
						<div class="d-flex flex-rows">
							<div id='health-viewing-trafficlight' class="trafficlightacross active" onclick="showIndicatorDetails('viewing')">
								<span id='health-viewing-red' class="trafficlightred"></span>
								<span id='health-viewing-orange' class="trafficlightorange"></span>
								<span id='health-viewing-green' class="trafficlightgreen"></span>
							</div>
							<div class="pt-1 ms-2">
								<p class="mb-0"><?php echo $LNG->STATS_DEBATE_VIEWING_TITLE; ?></p>
							</div>
						</div>
						<div id="health-viewing-details" style="display:none">
							<div class="my-2">
								<p>
									<span id="health-viewingpeople-count" class="fw-bold"></span> <span id='health-viewing-message'></span>
									<span id="health-viewinggroup-count" class="fw-bold"></span> <span id='health-viewing-message-part2'></span>
								</p>
								<p>
									<span onMouseOver="showGlobalHint('StatsDebateViewing', event, 'hgrhint'); return false;" onfocus="showGlobalHint('StatsDebateViewing', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)" class="m-2 help-hint">
										<i class="fas fa-info-circle fa-lg" title="Info"></i>
									</span>
									<span id="health-viewing-recomendation"></span>
								</p>
							</div>
							<div class="d-flex flex-rows">
								<div id="health-viewing-explore" class="me-2">
									<p class="fst-italic"><?php echo $LNG->STATS_DEBATE_HEALTH_EXPLORE; ?></p>
								</div>
								<div id="health-participation-links">
									<a class="active" onclick="return auditViewingStatsLink('<?php echo $CFG->homeAddress;?>ui/stats/debates/useractivityanalysis.php?page=useractivityfilter&nodeid=<?php echo $nodeid; ?>');">
										<?php echo $LNG->STATS_TAB_ACTIVITY_ANALYSIS; ?>
									</a>
								</div>
							</div>
						</div>
					</div>

					<div id='health-debate' style="display:none;">
						<div class="d-flex flex-rows">
							<div id='health-debate-trafficlight' class="trafficlightacross active" onclick="showIndicatorDetails('debate')">
								<span id='health-debate-red' class="trafficlightred"></span>
								<span id='health-debate-orange' class="trafficlightorange"></span>
								<span id='health-debate-green' class="trafficlightgreen"></span>
							</div>
							<div class="pt-1 ms-2">
								<p class="mb-0"><?php echo $LNG->STATS_DEBATE_CONTRIBUTION_TITLE; ?></p>
							</div>
						</div>
						<div id="health-debate-details" style="display:none;">
							<div class="my-2">
								<p><span id="health-debate-message"></span></p>
								<p>
									<span onMouseOver="showGlobalHint('StatsDebateContribution', event, 'hgrhint'); return false;" onfocus="showGlobalHint('StatsDebateContribution', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)" class="m-2 help-hint">
										<i class="fas fa-info-circle fa-lg" title="Info"></i>
									</span>
									<span id="health-debate-recomendation"></span>
								</p>
							</div>
							<div class="d-flex flex-rows">
								<div id="health-debate-explore" class="me-2">
									<p class="fst-italic"><?php echo $LNG->STATS_DEBATE_HEALTH_EXPLORE; ?></p>
								</div>
								<div id="health-debate-links">
									<a class="active" onclick="return auditDebateStatsLink('<?php echo $CFG->homeAddress;?>ui/stats/debates/circlepacking.php?page=circlepacking&nodeid=<?php echo $nodeid; ?>');">
										<?php echo $LNG->STATS_TAB_CIRCLEPACKING; ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- BAG OF LEMONS -->
			<?php if (isset($USER->userid)) {?>
				<div id="lemonbasket" style="display:none;position:fixed; top: 660px;clear:both; margin-top:10px;">
					<img id="addlemon" draggable="true" ondragstart="lemondragstart(event)" ondragover="lemonbasketdragover(event)" ondragenter="lemonbasketdragenter" ondrop="lemonbasketdrop(event)" src="<?php echo $HUB_FLM->getImagePath('basket-120-lemons.png'); ?>" style="vertical-align:bottom;" alt="add lemon" />
					<div style="clear:both;"><?php echo $LNG->LEMONING_COUNT_LEFT; ?> <span id="lemonbasketcount" style="font-size:12pt; font-weight:bold"><?php if (isset($lemonsleft)) { echo $lemonsleft; } ?></span></div>
				</div>
			<?php } ?>

			<?php if ($_SESSION['HUB_CANADD']){ ?>
				<!-- div id="useralerts" style="float:left;display:none;margin-top:7px;">
					<div class="boxshadowsquare" style="width:190px;">
						<h2 style="padding-top:0px;margin-top:0px;margin-bottom:0px;padding-bottom:0px;"><?php echo $LNG->ALERTS_BOX_TITLE_MINE; ?></h2>
						<div id="useralerts-messagearea" style="width:100%;padding:0px;margin:0px;"></div>
						<div id="useralerts-div" style="float:left;max-height:200px;overflow:auto">
							<div id="useralerts-issue-div" style=""></div>
							<div id="useralerts-user-div" style=""></div>
						</div>
					</div>
				</div -->
			<?php } ?>

			<!-- MARGOT PROCESSING -->
			<!-- div id="margotinput" style="float:left;margin-top:7px;">
				<div class="boxshadowsquare" style="width:190px;">
					<h3 style="padding-top:0px;margin-top:0px;margin-bottom:0px;padding-bottom:0px;">Margo Data - found:<span style="padding-left:2px; font-weight:normal;" id="margotcount">0</span></h3>
					<textarea id="margotdata" style="height:160px; width:185px"></textarea>
					<button style="clear:both" onclick="processMargotResults(document.getElementById('margotdata').value)" id="margotdatabutton">Process</button -->
					<!-- button style="clear:both;float:right" onclick="clearMargotResults()" id="margotdatabuttonclear">Clear</button -->
				<!--/div>
			</div -->

		</div>
	</div>
</div>

<?php
    include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
?>
