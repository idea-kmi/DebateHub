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
 * Displays the user tabs and pages
 *
 * @param string $context the context to display
 * @param string $args the url arguments
 */
function displayUserTabs($context,$args, $wasEmpty){
    global $CFG, $LNG, $USER, $CONTEXTUSER, $HUB_FLM;

	// now trigger the js to load data
	$argsStr = "{";
	$keys = array_keys($args);
	$count = 0;
	if (is_countable($keys)) {
		$count = count($keys);
	}
     for($i=0;$i< $count; $i++){
         $argsStr .= '"'.$keys[$i].'":"'.$args[$keys[$i]].'"';
         if ($i != ($count-1)){
             $argsStr .= ',';
         }
     }
     $argsStr .= "}";

 	if ($wasEmpty) {
     	$args["orderby"] = 'date';
    }
 	$argsStr2 = "{";
 	$keys = array_keys($args);
	$count = 0;
	if (is_countable($keys)) {
		$count = count($keys);
	}
 	for($i=0;$i< $count; $i++){
 		$argsStr2 .= '"'.$keys[$i].'":"'.$args[$keys[$i]].'"';
 		if ($i != ($count-1)){
 			$argsStr2 .= ',';
 		}
 	}
 	$argsStr2 .= "}";

	echo "<script type='text/javascript'>";

	echo "var CONTEXT = '".$context."';";
	echo "var NODE_ARGS = ".$argsStr.";";
	echo "var USER_ARGS = ".$argsStr.";";
	echo "var ISSUE_ARGS = ".$argsStr2.";";
	echo "var SOLUTION_ARGS = ".$argsStr2.";";
	echo "var CON_ARGS = ".$argsStr.";";
	echo "var PRO_ARGS = ".$argsStr.";";
	echo "var RESOURCE_ARGS = ".$argsStr2.";";
	echo "var COMMENT_ARGS = ".$argsStr2.";";
	echo "var GROUP_ARGS = ".$argsStr2.";";

	echo "COMMENT_ARGS['filterlist'] = '".$CFG->LINK_COMMENT_NODE."';";

	echo "</script>";
    ?>

    <div id="tabber" class="tabber-user mt-4">
        <ul id="tabs" class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link active" id="tab-home" href="#home" data-bs-toggle="tab">
					<?php echo $LNG->TAB_USER_HOME; ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="tab-data" href="#data" data-bs-toggle="tab">
					<?php echo $LNG->TAB_USER_DATA; ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="tab-group" href="#group" data-bs-toggle="tab">
					<?php echo $LNG->TAB_USER_GROUP; ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="tab-social" href="#social" data-bs-toggle="tab">
					<?php echo $LNG->TAB_USER_SOCIAL; ?>
				</a>
			</li>
        </ul>

        <div id="tabs-content" class="tab-content p-0">
			<!-- HOME TAB PAGES -->
            <div id='tab-content-home-div' class='tab-pane fade show active'>
	            <div id='tab-content-home' class="row">
	           		<?php include($HUB_FLM->getCodeDirPath("ui/homepageuser.php")); ?>
	            </div>
			</div>

			<!-- DATA TAB PAGE -->
			<div id='tab-content-data-div' class='tab-pane border border-top-0 p-3 pt-1'>
				<div id='tab-content-toolbar-data' class="row">
					<div id="tabber">
						<div class="row">
							<div id="datatabs">
								<ul id="tabs" class="nav nav-tabs p-3 myData-tabs">
									<li class="nav-item">
										<a class="nav-link" id="tab-data-issue" data-bs-toggle="tab" data-bs-target="#data-issue"><span class="tab tabissue"><?php echo $LNG->TAB_USER_ISSUE; ?> <span id="issue-list-count"></span></span></a></li>
									<li class="nav-item">
										<a class="nav-link" id="tab-data-solution" data-bs-toggle="tab" data-bs-target="#data-solution"><span class="tab tabsolution"><?php echo $LNG->TAB_USER_SOLUTION; ?> <span id="solution-list-count"></span></span></a></li>
									<li class="nav-item">
										<a class="nav-link" id="tab-data-pro" data-bs-toggle="tab" data-bs-target="#data-pro"><span class="tab tabpro"><?php echo $LNG->TAB_USER_PRO; ?> <span id="pro-list-count"></span></span></a></li>
									<li class="nav-item">
										<a class="nav-link" id="tab-data-con" data-bs-toggle="tab" data-bs-target="#data-con"><span class="tab tabcon"><?php echo $LNG->TAB_USER_CON; ?> <span id="con-list-count"></span></span></a></li>
									<li class="nav-item">
										<a class="nav-link" id="tab-data-comment" data-bs-toggle="tab" data-bs-target="#data-comment"><span class="tab tabuser"><?php echo $LNG->TAB_USER_COMMENT; ?> <span id="comment-list-count"></span></span></a></li>
								</ul>
							</div>

							<div id="tab-content-data" class="tab-content">

								<div class="tab-pane show active" id="data-issue" role="tabpanel" aria-label="data-issue-tab">
									<div id="tab-content-data-issue-div" class="tabcontentuser peoplebackpale">
										<div id='tab-content-issue-search' class="tabcontentsearchuser row p-2">
												<div id="searchissue" class="toolbarIcons">
													<div class="row">
														<div class="col-lg-4 col-md-12">
															<?php
																// if search term is present in URL then show in search box
																$q = stripslashes(optional_param("q","",PARAM_TEXT));
															?>
															<div class="input-group">
																<input type="text" class="form-control" placeholder="<?php echo $LNG->TAB_SEARCH_ISSUE_LABEL; ?>" aria-label="<?php echo $LNG->TAB_SEARCH_ISSUE_LABEL; ?>" onkeyup="if (checkKeyPressed(event)) { $('issue-go-button').onclick();}" id="qissue" name="q" value="<?php print( htmlspecialchars($q) ); ?>" />
																<div id="q_choices" class="autocomplete"></div>
																<button class="btn btn-outline-dark bg-light" type="button" onclick="filterSearchIssues();"><?php echo $LNG->TAB_SEARCH_GO_BUTTON; ?></button>
																<button class="btn btn-outline-dark bg-light" type="button" onclick="ISSUE_ARGS['q'] = ''; ISSUE_ARGS['scope'] = 'all'; $('qissue').value='';if ($('scopechallangeall'))  $('scopechallangeall').checked=true; refreshIssues();"><?php echo $LNG->TAB_SEARCH_CLEAR_SEARCH_BUTTON; ?></button>
															</div>
														</div>
														<div class="col-lg-4 col-md-12">
															<div id="issuebuttons" class="rss-print-btn">
																<?php if ($CFG->hasRss) { ?>
																	<a class="active me-3" title="<?php echo $LNG->TAB_RSS_ISSUE_HINT; ?>" onclick="getNodesFeed(ISSUE_ARGS);">
																		<i class="fas fa-rss-square fa-lg" aria-hidden="true" ></i> 
																		<span class="sr-only"><?php echo $LNG->TAB_RSS_ALT; ?></span>
																	</a>
																<?php } ?>
																<a class="active" title="<?php echo $LNG->TAB_PRINT_HINT_ISSUE; ?>" onclick="printNodes(ISSUE_ARGS, '<?php echo $LNG->TAB_PRINT_TITLE_ISSUE; ?>');">
																	<i class="fas fa-print fa-lg" aria-hidden="true" ></i> 
																	<span class="sr-only"><?php echo $LNG->TAB_PRINT_ALT; ?></span>
																</a>
															</div>
														</div>
													</div>
												</div>											
											</div>
										</div>
									</div>
									<div id="tab-content-data-issue" class="issueGroups tabcontentinner p-4"></div>
								</div>  

								<div class="tab-pane" id="data-solution" role="tabpanel" aria-label="data-solution-tab">
									<div id='tab-content-data-solution-div' class="tabcontentuser peoplebackpale">
										<div id='tab-content-solution-search' class="tabcontentsearchuser row p-2">
											<div id="searchsolution" class="toolbarIcons">
												<div class="row">
													<div class="col-lg-4 col-md-12">
														<div class="input-group">
															<input type="text" class="form-control" placeholder="<?php echo $LNG->TAB_SEARCH_SOLUTION_LABEL; ?>" aria-label="<?php echo $LNG->TAB_SEARCH_SOLUTION_LABEL; ?>" onkeyup="if (checkKeyPressed(event)) { $('solution-go-button').onclick();}" id="qsolution" name="q" value="<?php print( htmlspecialchars($q) ); ?>" />
															<div id="q_choices" class="autocomplete"></div>
															<button class="btn btn-outline-dark bg-light" type="button" onclick="filterSearchSolutions();"><?php echo $LNG->TAB_SEARCH_GO_BUTTON; ?></button>
															<button class="btn btn-outline-dark bg-light" type="button" onclick="SOLUTION_ARGS['q'] = ''; SOLUTION_ARGS['scope'] = 'all'; $('qsolution').value='';if ($('scopesolutionall'))  $('scopesolutionall').checked=true; refreshSolutions();"><?php echo $LNG->TAB_SEARCH_CLEAR_SEARCH_BUTTON; ?></button>
														</div>
														<?php
															// if search term is present in URL then show in search box
															$q = stripslashes(optional_param("q","",PARAM_TEXT));
														?>
													</div>
													<div class="col-lg-4 col-md-12">
														<div id="solutionbuttons" class="rss-print-btn">
															<?php if ($CFG->hasRss) { ?>
																<a class="active me-3" title="<?php echo $LNG->TAB_RSS_SOLUTION_HINT; ?>" onclick="getNodesFeed(SOLUTION_ARGS);">
																	<i class="fas fa-rss-square fa-lg" aria-hidden="true" ></i> 
																	<span class="sr-only"><?php echo $LNG->TAB_RSS_ALT; ?></span>
																</a>
															<?php } ?>
															<a class="active" title="<?php echo $LNG->TAB_PRINT_HINT_SOLUTION; ?>" onclick="printNodes(SOLUTION_ARGS, '<?php echo $LNG->TAB_PRINT_TITLE_SOLUTION; ?>');">
																<i class="fas fa-print fa-lg" aria-hidden="true" ></i> 
																<span class="sr-only"><?php echo $LNG->TAB_PRINT_ALT; ?></span>
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div id="tab-content-data-solution" class="tabcontentinner p-4"></div>
								</div>

								<div class="tab-pane" id="data-pro" role="tabpanel" aria-label="data-pro-tab">
									<div id='tab-content-data-pro-div' class="tabcontentuser peoplebackpale">
										<div id='tab-content-pro-search' class="tabcontentsearchuser row p-2">
											<div id="searchpro" class="toolbarIcons">
												<div class="row">
													<div class="col-lg-4 col-md-12">
														<div class="input-group">
															<input type="text" class="form-control" placeholder="<?php echo $LNG->TAB_SEARCH_PRO_LABEL; ?>" aria-label="<?php echo $LNG->TAB_SEARCH_PRO_LABEL; ?>" onkeyup="if (checkKeyPressed(event)) { $('pro-go-button').onclick();}" id="qpro" name="q" value="<?php print( htmlspecialchars($q) ); ?>" />
															<div id="q_choices" class="autocomplete"></div>
															<button class="btn btn-outline-dark bg-light" type="button" onclick="filterSearchPros();"><?php echo $LNG->TAB_SEARCH_GO_BUTTON; ?></button>
															<button class="btn btn-outline-dark bg-light" type="button" onclick="PRO_ARGS['q'] = ''; PRO_ARGS['scope'] = 'all'; $('qpro').value='';if ($('scopeproall'))  $('scopeproall').checked=true; refreshPros();"><?php echo $LNG->TAB_SEARCH_CLEAR_SEARCH_BUTTON; ?></button>
														</div>
														<?php
															// if search term is present in URL then show in search box
															$q = stripslashes(optional_param("q","",PARAM_TEXT));
														?>
													</div>
													<div class="col-lg-4 col-md-12">
														<div id="probuttons" class="rss-print-btn">
															<?php if ($CFG->hasRss) { ?>
																<a class="active me-3" title="<?php echo $LNG->TAB_RSS_PRO_HINT; ?>" onclick="getNodesFeed(PRO_ARGS);">
																	<i class="fas fa-rss-square fa-lg" aria-hidden="true" ></i> 
																	<span class="sr-only"><?php echo $LNG->TAB_RSS_ALT; ?></span>
																</a>
															<?php } ?>
															<a class="active" title="<?php echo $LNG->TAB_PRINT_HINT_PRO; ?>" onclick="printNodes(PRO_ARGS, '<?php echo $LNG->TAB_PRINT_TITLE_PRO; ?>');">
																<i class="fas fa-print fa-lg" aria-hidden="true" ></i> 
																<span class="sr-only"><?php echo $LNG->TAB_PRINT_ALT; ?></span>
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div id='tab-content-data-pro' class="tabcontentinner p-4"></div>
								</div>

								<div class="tab-pane" id="data-con" role="tabpanel" aria-label="data-con-tab">
									<div id='tab-content-data-con-div' class="tabcontentuser peoplebackpale">
										<div id='tab-content-con-search' class="tabcontentsearchuser row p-2">
											<div id="searchcon" class="toolbarIcons">
												<div class="row">
													<div class="col-lg-4 col-md-12">
														<div class="input-group">
															<input type="text" class="form-control" placeholder="<?php echo $LNG->TAB_SEARCH_CON_LABEL; ?>" aria-label="<?php echo $LNG->TAB_SEARCH_CON_LABEL; ?>" onkeyup="if (checkKeyPressed(event)) { $('con-go-button').onclick();}" id="qcon" name="q" value="<?php print( htmlspecialchars($q) ); ?>" />
															<div id="q_choices" class="autocomplete"></div>
															<button class="btn btn-outline-dark bg-light" type="button" onclick="filterSearchCons();"><?php echo $LNG->TAB_SEARCH_GO_BUTTON; ?></button>
															<button class="btn btn-outline-dark bg-light" type="button" onclick="CON_ARGS['q'] = ''; CON_ARGS['scope'] = 'all'; $('qcon').value='';if ($('scopeconall'))  $('scopeconall').checked=true; refreshCons();"><?php echo $LNG->TAB_SEARCH_CLEAR_SEARCH_BUTTON; ?></button>
														</div>
														<?php
															// if search term is present in URL then show in search box
															$q = stripslashes(optional_param("q","",PARAM_TEXT));
														?>
													</div>
													<div class="col-lg-4 col-md-12">
														<div id="conbuttons" class="rss-print-btn">
															<?php if ($CFG->hasRss) { ?>
																<a class="active me-3" title="<?php echo $LNG->TAB_RSS_CON_HINT; ?>" onclick="getNodesFeed(CON_ARGS);">
																	<i class="fas fa-rss-square fa-lg" aria-hidden="true" ></i> 
																	<span class="sr-only"><?php echo $LNG->TAB_RSS_ALT; ?></span>
																</a>
															<?php } ?>
															<a class="active" title="<?php echo $LNG->TAB_PRINT_HINT_CON; ?>" onclick="printNodes(CON_ARGS, '<?php echo $LNG->TAB_PRINT_TITLE_CON; ?>');">
																<i class="fas fa-print fa-lg" aria-hidden="true" ></i> 
																<span class="sr-only"><?php echo $LNG->TAB_PRINT_ALT; ?></span>
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div id='tab-content-data-con' class="tabcontentinner p-4"></div>
								</div>

								<div class="tab-pane" id="data-comment" role="tabpanel" aria-label="data-comment-tab">
									<div id='tab-content-data-comment-div' class="tabcontentuser peoplebackpale">
										<div id='tab-content-search-comment' class="tabcontentsearchuser row p-2">
											<div id="searchcomment" class="toolbarIcons">
												<div class="row">
													<div class="col-lg-4 col-md-12">
														<div class="input-group">
															<input type="text" class="form-control" placeholder="<?php echo $LNG->TAB_SEARCH_COMMENT_LABEL; ?>" aria-label="<?php echo $LNG->TAB_SEARCH_COMMENT_LABEL; ?>" onkeyup="if (checkKeyPressed(event)) { $('comment-go-button').onclick();}" id="qcomment" name="q" value="<?php print( htmlspecialchars($q) ); ?>" />
															<div id="q_choices" class="autocomplete"></div>
															<button class="btn btn-outline-dark bg-light" type="button" onclick="filterSearchComments();"><?php echo $LNG->TAB_SEARCH_GO_BUTTON; ?></button>
															<button class="btn btn-outline-dark bg-light" type="button" onclick="COMMENT_ARGS['q'] = ''; COMMENT_ARGS['searchid'] = ''; COMMENT_ARGS['scope'] = 'all'; $('qcomment').value='';if ($('scopeproall'))  $('scopeproall').checked=true; refreshComments();"><?php echo $LNG->TAB_SEARCH_CLEAR_SEARCH_BUTTON; ?></button>
														</div>
														<?php
															// if search term is present in URL then show in search box
															$q = stripslashes(optional_param("q","",PARAM_TEXT));
														?>
													</div>
													<div class="col-lg-4 col-md-12">
														<div id="orgbuttons" class="rss-print-btn">
															<?php if ($CFG->hasRss) { ?>
																<a class="active me-3" title="<?php echo $LNG->TAB_RSS_COMMENT_HINT; ?>" onclick="getNodesFeed(COMMENT_ARGS);">
																	<i class="fas fa-rss-square fa-lg" aria-hidden="true" ></i> 
																	<span class="sr-only"><?php echo $LNG->TAB_RSS_ALT; ?></span>
																</a>
															<?php } ?>
															<a class="active" title="<?php echo $LNG->TAB_PRINT_HINT_COMMENT; ?>" onclick="printNodes(COMMENT_ARGS, '<?php echo $LNG->TAB_PRINT_TITLE_COMMENT; ?>');">
																<i class="fas fa-print fa-lg" aria-hidden="true" ></i> 
																<span class="sr-only"><?php echo $LNG->TAB_PRINT_ALT; ?></span>
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div id='tab-content-data-comment' class="tabcontentinner p-4"></div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- GROUP TAB PAGE -->
            <div id='tab-content-group-div' class='tab-pane border border-top-0 p-3 pt-1'>
            	<div id='tab-content-group-search' class="tabcontentsearchuser row p-2">
					<?php if(isset($USER->userid) && $userid == $USER->userid){ ?>
						<span class="toolbar d-flex flex-row px-3">
							<a onclick="javascript:loadDialog('creategroup','<?php echo $CFG->homeAddress ?>ui/popups/groupadd.php', 800,800);" title='<?php echo $LNG->GROUP_CREATE_TITLE; ?>' class="active my-2 me-4">
								<img src="<?php echo $HUB_FLM->getImagePath('add.png'); ?>" alt="" /> <?php echo $LNG->GROUP_CREATE_TITLE; ?>
							</a>		
							<a onclick="javascript:loadDialog('editgroup','<?php echo $CFG->homeAddress ?>ui/popups/groupedit.php', 900,800);" title='<?php echo $LNG->GROUP_MANAGE_TITLE; ?>' class="active my-2 me-4">
								<?php echo $LNG->GROUP_MANAGE_TITLE; ?>
							</a>
						</span>	
					<?php } ?>
            	</div>
				<div id='tab-content-toolbar-group' class="tabcontentouter row p-3">
					<div id="tab-content-group-admin" style="display:none">
						<h2 class="p-0"><?php echo $LNG->GROUP_MY_ADMIN_GROUPS_TITLE;?></h2>
						<div id='tab-content-group-admin-list' class="tabcontentinner discussionGroups"></div>
					</div>
					<div id="tab-content-group" class="row p-3" style="display:none">
						<h2 class="p-0"><?php echo $LNG->GROUP_MY_MEMBER_GROUPS_TITLE;?></h2>
						<div id='tab-content-group-list' class="tabcontentinner discussionGroups"></div>
					</div>
				</div>
			</div>

			<!-- SOCIAL NETWORK TAB PAGE -->
            <div id='tab-content-social-div' class='tabcontent' style="width: 100%; background: white;">
	  			<div id="tab-content-user-bar" class="peopleback plainborder-bottom" style="width:100%;margin:0px;">
	  				<div class="peopleback tabtitlebar" style="padding:4px;margin:0px;font-size:9pt"></div>
	  			</div>
	            <div id="tab-content-social" style="width:100%;padding:5px;"></div>
			</div>
		</div>
	</div>
<?php } ?>

<script type='text/javascript'>
	function updateUserFollow() {
		$('followupdate').submit()
	}
</script>
