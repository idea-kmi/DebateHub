<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2013-2023 The Open University UK                              *
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
    include_once("../../config.php");

    $me = substr($_SERVER["PHP_SELF"], 1); // remove initial '/'
    if ($HUB_FLM->hasCustomVersion($me)) {
    	$path = $HUB_FLM->getCodeDirPath($me);
    	include_once($path);
		die;
	}

    checkLogin();

    include_once($HUB_FLM->getCodeDirPath("ui/headeradmin.php"));

    if($USER == null || $USER->getIsAdmin() == "N"){
         echo "<div class='errors'>.".$LNG->ADMIN_NOT_ADMINISTRATOR_MESSAGE."</div>";
        include_once($HUB_FLM->getCodeDirPath("ui/footeradmin.php"));
        die;
	}

    $errors = array();

	function encodeURIComponent($str) {
		$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
		return strtr(rawurlencode($str), $revert);
	}

	function loadChildNodes($node, $status) {
		global $CFG;
		
		$nodetype = $node->role->name;
		$children = [];

		// If the node being archived is a Debate Node
		if ($nodetype == "Issue") {

			//get the Ideas for this Debate.
			$connSetSolutions = getConnectionsByNode($node->nodeid, 0, -1, 'date', 'ASC', 'all', 'responds to', 'Solution', 'long', $status);

			if (isset($connSetSolutions->connections[0])) {
				$count = 0;
				if (is_countable($connSetSolutions->connections)) {
					$count = count($connSetSolutions->connections);
				}
				for ($i=0; $i<$count; $i++) {
					$con = $connSetSolutions->connections[$i];
					// connection connect from the child to the parent so get the from end of the connection
					if (isset($con->from)) {
						$solutionnode = $con->from;
						if(!$solutionnode instanceof Hub_Error){								
							$solutionnode->children = loadChildNodes($solutionnode, $status);
							array_push($children, $solutionnode);
						}
					} 
				}
			}
		} else if ($nodetype == "Solution") {

			// get any pros, cons and moderator comments for a given Idea
			$connSetArguments = getConnectionsByNode($node->nodeid,0,-1,'date','ASC', 'all','','Pro,Con,Comment', 'long', $status);
			if (isset($connSetArguments->connections[0])) {
				$count = 0;
				if (is_countable($connSetArguments->connections)) {
					$count = count($connSetArguments->connections);
				}
				for ($j=0; $j<$count; $j++) {
					$con = $connSetArguments->connections[$j];
					// connections connect from the child to the parent so get the from end of the connection
					if (isset($con->from)) {
						$argumentnode = $con->from;
						if(!$argumentnode instanceof Hub_Error){								
							array_push($children, $argumentnode); // has no children
						}
					}
				}
			}
		} else if ($nodetype == "Pro" || $nodetype == "Con" || $nodetype == "Comment"){
			//nothing more to do! status already changed above for the node.
		}

		return $children;
	}


    if(isset($_POST["deletenode"])){
		$nodeid = optional_param("nodeid","",PARAM_ALPHANUMEXT);
    	if ($nodeid != "") {
    		$node = new CNode($nodeid);
	   		$node = $node->delete();
    	} else {
            array_push($errors,$LNG->SPAM_ADMIN_ID_ERROR);
    	}
	} else if(isset($_POST["archivenode"])){
		$nodeid = optional_param("nodeid","",PARAM_ALPHANUMEXT);

    	if ($nodeid != "") {
    		$node = new CNode($nodeid);
			
			// to get the connections - set archived at the end
			$node = $node->updateStatus($CFG->STATUS_ACTIVE);

			// marks all child nodes as also archived, if parent archived.

			$nodetype = $node->role->name;

			// If the node being archived is a Debate Node
			if ($nodetype == "Issue") {

				//get the Ideas for this Debate.
				$connSetSolutions = getConnectionsByNode($node->nodeid, 0, -1, 'date', 'ASC', 'all', 'responds to', 'Solution', 'short', $CFG->STATUS_ACTIVE);
				if (isset($connSetSolutions->connections[0])) {
					$count = 0;
					if (is_countable($connSetSolutions->connections)) {
						$count = count($connSetSolutions->connections);
					}
					for ($i=0; $i<$count; $i++) {
						$con = $connSetSolutions->connections[$i];
						$con->updateStatus($CFG->STATUS_ARCHIVED);

						// connection connect from the child to the parent so get the from end of the connection
						if (isset($con->from)) {
							$solutionnode = $con->from;
							if(!$solutionnode instanceof Hub_Error){								
								$solutionnode->updateStatus($CFG->STATUS_ARCHIVED);

								// get any pros, cons and moderator comments for a given Idea
								$connSetArguments = getConnectionsByNode($solutionnodeid->nodeid, 0, 1, 'date', 'ASC', 'all', '', 'Pro,Con,Comment', 'short', $CFG->STATUS_ACTIVE);
								if (isset($connSetArguments->connections[0])) {
									$count2 = 0;
									if (is_countable($connSetArguments->connections)) {
										$count2 = count($connSetArguments->connections);
									}
									for ($j=0; $j<$count2; $j++) {
										$con2 = $connSetArguments->connections[$j];
										$con2->updateStatus($CFG->STATUS_ARCHIVED);

										// connection connect from the child to the parent so get the from end of the connection
										if (isset($con2->from)) {
											$argumentnode = $con2->from;
											if(!$argumentnode instanceof Hub_Error){								
												$argumentnode->updateStatus($CFG->STATUS_ARCHIVED);
											}
										}
									}
								}
							}
						} 
					}
				}
			} else if ($nodetype == "Solution") {

				// get any pros, cons and moderator comments for a given Idea
				$connSetArguments = getConnectionsByNode($node->nodeid,0,1,'date','ASC', 'all','','Pro,Con,Comment', 'short', $CFG->STATUS_ACTIVE);
				if (isset($connSetArguments->connections[0])) {
					$count = 0;
					if (is_countable($connSetArguments->connections)) {
						$count = count($connSetArguments->connections);
					}
					for ($j=0; $j<$count; $j++) {
						$con = $connSetArguments->connections[$j];
						$con->updateStatus($CFG->STATUS_ARCHIVED);

						// connection connect from the child to the parent so get the from end of the connection
						if (isset($con->from)) {
							$argumentnode = $con->from;
							if(!$argumentnode instanceof Hub_Error){								
								$argumentnode->updateStatus($CFG->STATUS_ARCHIVED);
							}
						}
					}
				}
			} else if ($nodetype == "Pro" || $nodetype == "Con" || $nodetype == "Comment"){
				//nothing more to do! status already changed above for the node.
			}

			$node = $node->updateStatus($CFG->STATUS_ARCHIVED);
		}
    } else if(isset($_POST["restorenode"])){
		$nodeid = optional_param("nodeid","",PARAM_ALPHANUMEXT);

    	if ($nodeid != "") {
    		$node = new CNode($nodeid);

			$originalStatus = $node->status;

			// only need to restore child nodes if we are dealing with an archived item
			if ($originalStatus == $CFG->STATUS_ARCHIVED) {

				// marks all child nodes as also archived, if parent archived.
				$nodetype = $node->role->name;

				// If the node being archived is a Debate Node
				if ($nodetype == "Issue") {

					//get the Ideas for this Debate.
					$connSetSolutions = getConnectionsByNode($node->nodeid,0,1,'date','ASC', 'all','','Solution', 'short',  $CFG->STATUS_ARCHIVED);
					if (isset($connSetSolutions->connections[0])) {
						$count = 0;
						if (is_countable($connSetSolutions->connections)) {
							$count = count($connSetSolutions->connections);
						}
						for ($i=0; $i<$count; $i++) {
							$con = $connSetSolutions->connections[$i];
							$con->updateStatus($CFG->STATUS_ACTIVE);

							// connection connect from the child to the parent so get the from end of the connection
							if (isset($con->from)) {
								$solutionnode = $con->from;
								if(!$solutionnode instanceof Hub_Error){								
									$solutionnode->updateStatus($CFG->STATUS_ACTIVE);

									// get any pros, cons and moderator comments for a given Idea
									$connSetArguments = getConnectionsByNode($solutionnode->nodeid,0,1,'date','ASC', 'all','','Pro,Con,Comment', 'short', $CFG->STATUS_ARCHIVED);
									if (isset($connSetArguments->connections[0])) {
										$count2 = 0;
										if (is_countable($connSetArguments->connections)) {
											$count2 = count($connSetArguments->connections);
										}
										for ($j=0; $j<$count2; $j++) {
											$con2 = $connSetArguments->connections[$j];
											$con2->updateStatus($CFG->STATUS_ACTIVE);

											// connection connect from the child to the parent so get the from end of the connection
											if (isset($con2->from)) {
												$argumentnode = $con2->from;
												if(!$argumentnode instanceof Hub_Error){								
													$argumentnode->updateStatus($CFG->STATUS_ACTIVE);
												}
											}
										}
									}
								}
							} 
						}
					}
				} else if ($nodetype == "Solution") {

					// get any pros, cons and moderator comments for a given Idea
					$connSetArguments = getConnectionsByNode($node->nodeid,0,1,'date','ASC', 'all','','Pro,Con,Comment', 'short',  $CFG->STATUS_ARCHIVED);
					if (isset($connSetArguments->connections[0])) {
						$count = 0;
						if (is_countable($connSetArguments->connections)) {
							$count = count($connSetArguments->connections);
						}
						for ($j=0; $j<$count; $j++) {
							$con = $connSetArguments->connections[$j];
							$con->updateStatus($CFG->STATUS_ACTIVE);

							// connection connect from the child to the parent so get the from end of the connection
							if (isset($con->from)) {
								$argumentnode = $con->from;
								if(!$argumentnode instanceof Hub_Error){								
									$argumentnode->updateStatus($CFG->STATUS_ACTIVE);
								}
							}
						}
					}
				} else if ($nodetype == "Pro" || $nodetype == "Con" || $nodetype == "Comment"){
					//nothing more to do! status already changed above for the node.
				}
			}

			$node = $node->updateStatus($CFG->STATUS_ACTIVE);
		} else {
            array_push($errors,$LNG->SPAM_ADMIN_ID_ERROR);
    	}
    }

	$allNodes = array();

	$ns = getNodesByStatus($CFG->STATUS_REPORTED, 0,-1,'name','ASC','long');
    $nodes = $ns->nodes;

	$count = 0;
	if (is_countable($nodes)) {
		$count = count($nodes);
	}
    for ($i=0; $i<$count;$i++) {
    	$node = $nodes[$i];
		$node->children = loadChildNodes($node, $CFG->STATUS_ACTIVE);
	   	$reporterid = getSpamReporter($node->nodeid);
    	if ($reporterid != false) {
    		$reporter = new User($reporterid);
    		$reporter = $reporter->load();
    		$node->reporter = $reporter;
			$node->istop = true;	// only top if it was the reported item
    	}
		$allNodes[$node->nodeid] = $node;
    }

	$ns2 = getNodesByStatus($CFG->STATUS_ARCHIVED, 0,-1,'name','ASC','long');
    $nodesarchivedinitial = $ns2->nodes;

	$count2 = 0;
	if (is_countable($nodesarchivedinitial)) {
		$count2 = count($nodesarchivedinitial);
	}
	
	$nodesarchived = [];
    for ($i=0; $i<$count2;$i++) {
    	$node = $nodesarchivedinitial[$i];
   		$reporterid = getSpamReporter($node->nodeid);
		//only hold top level nodes - rest shown in tree
    	if ($reporterid != false) {
    		$reporter = new User($reporterid);
    		$reporter = $reporter->load();
    		$node->reporter = $reporter;
			$node->children = loadChildNodes($node, $CFG->STATUS_ARCHIVED);
			$node->istop = true; // only top if it was the reported item
			array_push($nodesarchived, $node);
    	}
 		$allNodes[$node->nodeid] = $node;
    }
?>

<script type="text/javascript">

	const allnodes = <?php echo json_encode($allNodes); ?>;

	function getParentWindowHeight(){
		var viewportHeight = 900;
		if (window.opener.innerHeight) {
			viewportHeight = window.opener.innerHeight;
		} else if (window.opener.document.documentElement && document.documentElement.clientHeight) {
			viewportHeight = window.opener.document.documentElement.clientHeight;
		} else if (window.opener.document.body)  {
			viewportHeight = window.opener.document.body.clientHeight;
		}
		return viewportHeight;
	}

	function getParentWindowWidth(){
		var viewportWidth = 700;
		if (window.opener.innerHeight) {
			viewportWidth = window.opener.innerWidth;
		} else if (window.opener.document.documentElement && document.documentElement.clientHeight) {
			viewportWidth = window.opener.document.documentElement.clientWidth;
		} else if (window.opener.document.body)  {
			viewportWidth = window.opener.document.body.clientWidth;
		}
		return viewportWidth;
	}

	function viewSpamUserDetails(userid) {
		var width = getParentWindowWidth()-20;
		var height = getParentWindowHeight()-20;

		loadDialog('user', URL_ROOT+"user.php?userid="+userid, width, height);
	}

	function viewSpamItemDetails(nodeid, nodetype) {
		var width = getParentWindowWidth()-20;
		var height = getParentWindowHeight()-20;

		loadDialog('details', URL_ROOT+"explore.php?id="+nodeid, width, height);
	}

	function checkFormRestore(name) {
		var ans = confirm("<?php echo $LNG->SPAM_ADMIN_RESTORE_CHECK_MESSAGE; ?>\n\n"+name+"\n\n");
		if (ans){
			return true;
		} else {
			return false;
		}
	}

	function checkFormArchive(name) {
		var ans = confirm("<?php echo $LNG->SPAM_ADMIN_ARCHIVE_CHECK_MESSAGE; ?>\n\n"+name+"\n\n");
		if (ans){
			return true;
		} else {
			return false;
		}
	}

	function checkFormDelete(name) {
		var ans = confirm("<?php echo $LNG->SPAM_ADMIN_DELETE_CHECK_MESSAGE; ?>\n\n"+name+"\n\n");
		if (ans){
			return true;
		} else {
			return false;
		}
	}

	function viewItemTree(nodeid, nodetype, containerid) {
		var node = allnodes[nodeid];
		console.log(node);

		const containerObj = document.getElementById(containerid);
		if (containerObj.style.display == 'block') {
			containerObj.style.display = 'none';
		} else {
			containerObj.style.display = 'block';
		}
		
		if (containerObj.innerHTML == "&nbsp;") {
			containerObj.innerHTML = "";
			displayConnectionNodes(containerObj, [node], parseInt(0), true, nodeid+"tree");
		}
	}
</script>

<?php
	if(!empty($errors)){
		echo "<div class='errors'>".$LNG->FORM_ERROR_MESSAGE.":<ul>";
		foreach ($errors as $error){
			echo "<li>".$error."</li>";
		}
		echo "</ul></div>";
	}
?>

<div class="container-fluid">
	<div class="row p-4">		
		<div class="col">
			<div class="d-flex flex-wrap w-100 gap-2 border-bottom mb-3 pb-4">
				<a href="<?= $CFG->homeAddress ?>ui/admin/index.php" class="btn btn-admin">Dashboard</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/stats" class="btn btn-admin">Analytics</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/userregistration.php" class="btn btn-admin">Users</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/registrationmanager.php" class="btn btn-admin">Registration requests</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/spammanagergroups.php" class="btn btn-admin">Reported groups</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/spammanager.php" class="btn btn-admin active">Reported items</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/spammanagerusers.php" class="btn btn-admin">Reported users</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/newsmanager.php" class="btn btn-admin">Manage news</a>
			</div>

			<h1 class="mb-3"><?php echo $LNG->SPAM_ADMIN_TITLE; ?></h1>

			<div id="spamdiv">
				<div class="mb-3">
					<h2><?php echo $LNG->SPAM_ADMIN_SPAM_TITLE; ?></h2>
					<div class="formrow">
						<div id="nodes" class="forminput">
							<?php
								$count = 0;
								if (is_countable($nodes)) {
									$count = count($nodes);
								}
								if ($count == 0) {
									echo "<p>".$LNG->SPAM_ADMIN_NONE_MESSAGE."</p>";
								} else {
									echo "<table class='table'>";
									echo "<tr>";
									echo "<th width='40%'>".$LNG->SPAM_ADMIN_TABLE_HEADING1."</th>";
									echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING3."</th>";
									echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING2."</th>";
									echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING2."</th>";
									echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING2."</th>";
									echo "<th width='20%'>".$LNG->SPAM_ADMIN_TABLE_HEADING0."</th>";

									echo "</tr>";
									foreach($nodes as $node){

										echo '<tr>';

										echo '<td>';
										echo $node->name;
										echo '</td>';

										echo '<td>';
										$nodetypename = '';
										if ($node->role->name == 'Issue') {
											$nodetypename = $LNG->DEBATE_NAME; //default for type is Issue - I want to show debate
										} else {
											$nodetypename = getNodeTypeText($node->role->name, false);
										}
										echo $nodetypename;
										echo '</td>';

										echo '<td>';
										echo '<span class="active" onclick="viewItemTree(\''.$node->nodeid.'\', \''.$node->role->name.'\', \''.$node->nodeid.'treediv1\');">'.$LNG->SPAM_ADMIN_VIEW_BUTTON.'</span>';
										echo '</td>';

										echo '<td>';
										echo '<form id="second-'.$node->nodeid.'" action="" enctype="multipart/form-data" method="post" onsubmit="return checkFormRestore(\''.htmlspecialchars($node->name).'\');">';
										echo '<input type="hidden" id="nodeid" name="nodeid" value="'.$node->nodeid.'" />';
										echo '<input type="hidden" id="restorenode" name="restorenode" value="" />';
										echo '<span class="active" onclick="if (checkFormRestore(\''.htmlspecialchars($node->name).'\')){ $(\'second-'.$node->nodeid.'\').submit(); }" id="restorenode" name="restorenode">'.$LNG->SPAM_ADMIN_RESTORE_BUTTON.'</a>';
										echo '</form>';
										echo '</td>';

										echo '<td>';
										echo '<form id="third-'.$node->nodeid.'" action="" enctype="multipart/form-data" method="post" onsubmit="return checkFormArchive(\''.htmlspecialchars($node->name).'\');">';
										echo '<input type="hidden" id="nodeid" name="nodeid" value="'.$node->nodeid.'" />';
										echo '<input type="hidden" id="archivenode" name="archivenode" value="" />';
										echo '<span class="active" onclick="if (checkFormArchive(\''.htmlspecialchars($node->name).'\')) { $(\'third-'.$node->nodeid.'\').submit(); }" id="archivenode" name="archivenode">'.$LNG->SPAM_ADMIN_ARCHIVE_BUTTON.'</a>';
										echo '</form>';
										echo '</td>';

										echo '<td>';
										if (isset($node->reporter)) {
											echo '<span title="'.$LNG->SPAM_USER_ADMIN_VIEW_HINT.'" class="active" onclick="viewSpamUserDetails(\''.$node->reporter->userid.'\');">'.$node->reporter->name.'</span>';
										} else {
											echo $LNG->CORE_UNKNOWN_USER_ERROR;
										}
										echo '</td>';
										echo '</tr>';

										// add the tree display area row
										echo '<tr><td colspan="6">';
										echo '<div id="'.$node->nodeid.'treediv1" style="display:none">&nbsp;</div>';
										echo '</td></tr>';

									}
									echo "</table>";
								}
							?>
						</div>
					</div>
				</div>
				
				<div class="mb-3">
					<h2><?php echo $LNG->SPAM_ADMIN_ARCHIVE_TITLE; ?></h2>
					<div class="formrow">
						<div id="nodesarchived" class="forminput">
							<?php
								$count = 0;
								if (is_countable($nodesarchived)) {
									$count = count($nodesarchived);
								}
								if ($count == 0) {
									echo "<p>".$LNG->SPAM_ADMIN_NONE_ARCHIVED_MESSAGE."</p>";
								} else {
									echo "<table class='table'>";
									echo "<tr>";
									echo "<th width='40%'>".$LNG->SPAM_ADMIN_TABLE_HEADING1."</th>";
									echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING3."</th>";
									echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING2."</th>";
									echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING2."</th>";
									echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING2."</th>";
									echo "<th width='20%'>".$LNG->SPAM_ADMIN_TABLE_HEADING0."</th>";

									echo "</tr>";
									foreach($nodesarchived as $node) {
										echo '<tr>';

										echo '<td>';
										echo $node->name;
										echo '</td>';

										echo '<td>';
										$nodetypename = '';
										if ($node->role->name == 'Issue') {
											$nodetypename = $LNG->DEBATE_NAME; //default for type is Issue - I want to show debate
										} else {
											$nodetypename = getNodeTypeText($node->role->name, false);
										}
										echo $nodetypename;
										echo '</td>';

										echo '<td>';
										echo '<span class="active" onclick="viewItemTree(\''.$node->nodeid.'\', \''.$node->role->name.'\', \''.$node->nodeid.'treediv\');">'.$LNG->SPAM_ADMIN_VIEW_BUTTON.'</span>';
										echo '</td>';

										echo '<td>';
										echo '<form id="second-'.$node->nodeid.'" action="" enctype="multipart/form-data" method="post" onsubmit="return checkFormRestore(\''.htmlspecialchars($node->name).'\');">';
										echo '<input type="hidden" id="nodeid" name="nodeid" value="'.$node->nodeid.'" />';
										echo '<input type="hidden" id="restorenode" name="restorenode" value="" />';
										echo '<span class="active" onclick="if (checkFormRestore(\''.htmlspecialchars($node->name).'\')){ $(\'second-'.$node->nodeid.'\').submit(); }" id="restorenode" name="restorenode">'.$LNG->SPAM_ADMIN_RESTORE_BUTTON.'</a>';
										//echo '<input type="submit" style="font-size:10pt;border:none;padding:0px;background:transparent" class="active" id="restorenode" name="restorenode" value="'.$LNG->SPAM_ADMIN_RESTORE_BUTTON.'"/>';
										echo '</form>';
										echo '</td>';

										echo '<td>';
										echo '<form id="third-'.$node->nodeid.'" action="" enctype="multipart/form-data" method="post" onsubmit="return checkFormDelete(\''.htmlspecialchars($node->name).'\');">';
										echo '<input type="hidden" id="nodeid" name="nodeid" value="'.$node->nodeid.'" />';
										echo '<input type="hidden" id="deletenode" name="deletenode" value="" />';
										echo '<span class="active" onclick="if (checkFormDelete(\''.htmlspecialchars($node->name).'\')) { $(\'third-'.$node->nodeid.'\').submit(); }" id="deletenode" name="deletenode">'.$LNG->SPAM_ADMIN_DELETE_BUTTON.'</a>';
										echo '</form>';
										echo '</td>';

										echo '<td>';
										if (isset($node->reporter)) {
											echo '<span title="'.$LNG->SPAM_USER_ADMIN_VIEW_HINT.'" class="active" onclick="viewSpamUserDetails(\''.$node->reporter->userid.'\');">'.$node->reporter->name.'</span>';
										} else {
											echo $LNG->CORE_UNKNOWN_USER_ERROR;
										}
										echo '</td>';

										echo '</tr>';

										// add the tree display area row
										echo '<tr><td colspan="6">';
										echo '<div id="'.$node->nodeid.'treediv" style="display:none">&nbsp;</div>';
										echo '</td></tr>';

									}
									echo "</table>";
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
    include_once($HUB_FLM->getCodeDirPath("ui/footeradmin.php"));
?>
