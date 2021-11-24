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
 * Tabber library
 * Formats the output tab view in the main section of the site
 */

/**
 * Displays the tabber
 *
 * @param string $context the context to display
 * @param string $args the url arguments
 */
function display_tabber($context,$args, $wasEmpty){
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
	echo "var GROUP_ARGS = ".$argsStr2.";";
	echo "var ISSUE_ARGS = ".$argsStr2.";";

	echo "</script>";
    ?>

    <div id="tabber" style="clear:both;float:left; width: 100%;">
		<div style="height:1px;clear:both;float:left;width:100%;margin:0px;background:#E8E8E8"></div>
        <ul id="tabs" class="tab" class="issueborder">
			<li class="tab"><a class="tab" id="tab-home" href="<?php echo $CFG->homeAddress; ?>index.php#home-list"><span class="tab tabissue"><?php echo $LNG->TAB_HOME; ?></span></a></li>
			<li class="tab"><a class="tab" id="tab-group" href="<?php echo $CFG->homeAddress; ?>index.php#group-list"><span class="tab tabissue"><?php echo $LNG->TAB_GROUP; ?></span></a></li>
			<li class="tab"><a class="tab" id="tab-issue" href="<?php echo $CFG->homeAddress; ?>index.php#issue-list"><span class="tab tabissue"><?php echo $LNG->TAB_ISSUE; ?></span></a></li>
        </ul>

        <div id="tabs-content" style="float: left; width: 100%;">

			<!-- HOME TAB PAGES -->
            <div id='tab-content-home-div' class='tabcontent' style="padding:0px;">
	  			<div id="tab-content-home-title" style="background:#E8E8E8;height:2px;clear:both;float:left;width:100%;margin:0px;"></div>
	            <div id='tab-content-home'>
					<?php include($HUB_FLM->getCodeDirPath("ui/homepage.php")); ?>
	            </div>
			</div>

			<!-- ISSUE TAB PAGE -->
            <div id='tab-content-issue-div' class='tabcontent' style="display:none;padding:0px;">
	  			<div id="tab-content-issue-title" style="background:#E8E8E8;height:2px;clear:both;float:left;width:100%;margin:0px;"></div>
            	<div id='tab-content-issue-search' style='margin:5px;padding-top:5px;clear:both; float:left;'>

					<?php if ($CFG->issueCreationPublic || (!$CFG->issueCreationPublic && $USER->getIsAdmin() == "Y" )) { ?>
						<?php if(isset($USER->userid)){ ?>
						<span class="toolbar" style="margin-top:0px;">
							<span class="active" style="font-size: 11pt" onclick="javascript:loadDialog('createissue','<?php echo $CFG->homeAddress; ?>ui/popups/issueadd.php', 760,600);" title='<?php echo $LNG->TAB_ADD_ISSUE_HINT; ?>'><img style="vertical-align:bottom" src="<?php echo $HUB_FLM->getImagePath('add.png'); ?>" border="0" style="margin:0px;margin-left: 5px;padding:0px" /> <?php echo $LNG->TAB_ADD_ISSUE_LINK; ?></span>
						</span>
						<?php } ?>
					<?php } ?>
					<div id="searchissue" style="float:left;margin-left: 10px;">
						<label for="q" style="float: left; margin-right: 3px; margin-top: 3px;"><?php echo $LNG->TAB_SEARCH_ISSUE_LABEL; ?></label>

						<?php
							// if search term is present in URL then show in search box
							$q = stripslashes(optional_param("q","",PARAM_TEXT));
						?>

						<div style="float: left;">
							<input type="text" style="margin-right:3px; width:250px" onkeyup="if (checkKeyPressed(event)) { $('issue-go-button').onclick();}" id="qissue" name="q" value="<?php print( htmlspecialchars($q) ); ?>"/>
							<div style="clear: both;"></div>
						<div id="q_choices" class="autocomplete" style="border-color: white;"></div>
						</div>
						<div style="float:left;">
							<img id="issue-go-button" src="<?php echo $HUB_FLM->getImagePath('search.png'); ?>" class="active" width="20" height="20" onclick="javascript: filterSearchIssues();" title="<?php echo $LNG->TAB_SEARCH_GO_BUTTON; ?>" alt="<?php echo $LNG->TAB_SEARCH_GO_BUTTON; ?>" />
						</div>
						<div style="float:left;margin-left: 10px;">
							<img src="<?php echo $HUB_FLM->getImagePath('search-clear.png'); ?>" class="active" width="20" height="20" onclick="javascript: ISSUE_ARGS['q'] = ''; ISSUE_ARGS['scope'] = 'all'; $('qissue').value='';if ($('scopechallangeall'))  $('scopechallangeall').checked=true; refreshIssues();" title="<?php echo $LNG->TAB_SEARCH_CLEAR_SEARCH_BUTTON; ?>" alt="<?php echo $LNG->TAB_SEARCH_CLEAR_SEARCH_BUTTON; ?>" />
						</div>
					 </div>
            	</div>

            	<div id='tab-content-toolbar-issue' style='clear:both; float:left; width: 100%; height: 100%'>
						<div id="tab-content-issue" class="tabcontentouter">
          					<div id='tab-content-issue-list' class='tabcontentinner'></div>
						</div>
            	</div>
			</div>

			<!-- GROUP TAB PAGE -->
            <div id='tab-content-group-div' class='tabcontent' style="display:none;padding:0px;">
	  			<div id="tab-content-group-title" style="background:#E8E8E8;height:2px;clear:both;float:left;width:100%;margin:0px;"></div>
            	<div id='tab-content-group-search' style='margin:5px; padding-top: 5px;clear:both; float:left;'>

					<?php if ($CFG->groupCreationPublic || (!$CFG->groupCreationPublic && $USER->getIsAdmin() == "Y" )) { ?>
						<?php if(isset($USER->userid)){ ?>
						<span class="toolbar" style="margin-top:0px;">
							<span class="active" style="font-size: 11pt" onclick="javascript:loadDialog('creategroup','<?php echo $CFG->homeAddress; ?>ui/popups/groupadd.php', 720,700);" title='<?php echo $LNG->TAB_ADD_GROUP_HINT; ?>'><img style="vertical-align:bottom" src="<?php echo $HUB_FLM->getImagePath('add.png'); ?>" border="0" style="margin:0px;margin-left: 5px;padding:0px" /> <?php echo $LNG->TAB_ADD_GROUP_LINK; ?></span>
						</span>
						<?php } ?>
					<?php } ?>

					<div id="searchuser" style="float:left;margin-left:10px;">

						<label for="q" style="float: left; margin-right: 3px; margin-top: 3px;"><?php echo $LNG->TAB_SEARCH_USER_LABEL; ?></label>

						<?php
							// if search term is present in URL then show in search box
							$q = stripslashes(optional_param("q","",PARAM_TEXT));
						?>

						<div style="float: left;">
							<input type="text" style="margin-right:3px; width:250px" onkeyup="if (checkKeyPressed(event)) { $('group-go-button').onclick();}" id="qgroup" name="q" value="<?php print( htmlspecialchars($q) ); ?>"/>
							<div style="clear: both;">
							</div>
							<div id="q_choices" class="autocomplete" style="border-color: white;"></div>
						</div>
						<div style="float:left;">
							<img id="group-go-button" src="<?php echo $HUB_FLM->getImagePath('search.png'); ?>" class="active" width="20" height="20" onclick="javascript: filterSearchGroups();" title="<?php echo $LNG->TAB_SEARCH_GO_BUTTON; ?>" alt="<?php echo $LNG->TAB_SEARCH_GO_BUTTON; ?>" />
						</div>
						<div style="float:left;margin-left: 10px;">
							<img src="<?php echo $HUB_FLM->getImagePath('search-clear.png'); ?>" class="active" width="20" height="20" onclick="javascript: GROUP_ARGS['q'] = ''; GROUP_ARGS['scope'] = 'all';$('qgroup').value=''; refreshGroups();" title="<?php echo $LNG->TAB_SEARCH_CLEAR_SEARCH_BUTTON; ?>" alt="<?php echo $LNG->TAB_SEARCH_CLEAR_SEARCH_BUTTON; ?>" />
						</div>
					 </div>
            	</div>

            	<div id='tab-content-toolbar-group' style='clear:both; float:left; width: 100%; height: 100%'>
					<div id="tab-content-group" class="tabcontentouter">
           				<div id='tab-content-group-list' class='tabcontentinner'></div>
					</div>
				</div>
            </div>
        </div>
    </div>

<?php } ?>