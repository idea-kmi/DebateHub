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

$LNG->PAGE_HELP_TITLE = "Help";

$LNG->PAGE_HELP_BODY = '<p>... TO BE WRITTEN ...';

/*$LNG->PAGE_HELP_BODY = '<p>LiteMap consists of a series of building blocks that represents the main types of information that can be added to the Website. ';
$LNG->PAGE_HELP_BODY .= '<br />These are: ';
if ($CFG->HAS_CHALLENGE) {
	$LNG->PAGE_HELP_BODY .= $LNG->CHALLENGES_NAME.', ';
}
$LNG->PAGE_HELP_BODY .= $LNG->ISSUES_NAME.', ';
if ($CFG->HAS_SOLUTION) {
	$LNG->PAGE_HELP_BODY .= $LNG->SOLUTIONS_NAME.', ';
}
if ($CFG->HAS_CLAIM) {
	$LNG->PAGE_HELP_BODY .= $LNG->CLAIMS_NAME.', ';
}
$LNG->PAGE_HELP_BODY .=  $LNG->RESOURCES_NAME.', '.$LNG->PROJECTS_NAME.', '.$LNG->ORGS_NAME.'.</p>';

$LNG->PAGE_HELP_BODY .= '<p>In the following you\'ll see a list of coloured boxes describing each of these categories of information. If you click on a box a panel will drop down wind help information on a category.</p>';

$LNG->PAGE_HELP_BODY .= '<p>The bottom right box represents a different type of information called "Stories". A story is a chain of connected ';
if ($CFG->HAS_CHALLENGE) {
	$LNG->PAGE_HELP_BODY .= $LNG->CHALLENGES_NAME.'&gt;';
}
$LNG->PAGE_HELP_BODY .= $LNG->ISSUES_NAME.'&gt;';
if ($CFG->HAS_SOLUTION) {
	$LNG->PAGE_HELP_BODY .= $LNG->SOLUTIONS_NAME;
}
if ($CFG->HAS_SOLUTION && $CFG->HAS_CLAIM) {
	$LNG->PAGE_HELP_BODY .= '/';
}
if ($CFG->HAS_CLAIM) {
	$LNG->PAGE_HELP_BODY .= $LNG->CLAIMS_NAME;
}
$LNG->PAGE_HELP_BODY .= '&gt;'.$LNG->EVIDENCES_NAME.'&gt;'.$LNG->RESOURCES_NAME;
$LNG->PAGE_HELP_BODY .= ' that can be added in one go to LiteMap by using a Wizard interface. ';
$LNG->PAGE_HELP_BODY .= 'Click on the "Stories" box for further information and links to the Wizard form to add a new story.';


$LNG->PAGE_HELP_BODY .= '</p>';

$LNG->PAGE_HELP_BODY .= '<div style="float:left;width:99%;padding-top:10px;margin-bottom:20px;">';

if ($CFG->HAS_CHALLENGE) {
	$LNG->PAGE_HELP_BODY .= '<table cellspacing="10" style="margin: 0 auto;border-spacing:10px 0px;width: 100%;">';
	$LNG->PAGE_HELP_BODY .= '<tr>';
	$LNG->PAGE_HELP_BODY .= '<td width="33%">&nbsp;</td>';
	$LNG->PAGE_HELP_BODY .= '<td width="33%">';
	$LNG->PAGE_HELP_BODY .= '<div id="challengehomebutton" style="height:174px; padding:5px; font-weight:bold;" class="plainbackgradient plainborder curvedBorder homebutton1" onmouseover="this.className=\'challengebackgradient challengeborder curvedBorder homebutton1\';" onmouseout="if ($(\'homebuttonmessagediv3\').style.display == \'none\' || $(\'homebuttonmessagelasttype3\').value != \'challenge\') {this.className=\'plainbackgradient plainborder curvedBorder homebutton1\';}" title="Click to view/hide additional information" onclick="showHomeButtonText3(event, \'challenge\');">';
	$LNG->PAGE_HELP_BODY .= '<div style="margin: 0 auto; width:64px;margin-bottom:5px;">';
	$LNG->PAGE_HELP_BODY .= '<img src="'.$HUB_FLM->getImagePath('nodetypes/Default/challenge64px.png').'" border="0" />';
	$LNG->PAGE_HELP_BODY .= '</div>';
	$LNG->PAGE_HELP_BODY .= '<div style="margin: 0 auto;padding:0px;"><center><h2>'.$LNG->CHALLENGES_NAME.'</h2></center><br>Click in this box to read more.</div>';
	$LNG->PAGE_HELP_BODY .= '</div>';
	$LNG->PAGE_HELP_BODY .= '</td>';
	$LNG->PAGE_HELP_BODY .= '<td width="33%">&nbsp;</td>';
	$LNG->PAGE_HELP_BODY .= '</tr>';
	$LNG->PAGE_HELP_BODY .= '</table>';

	$LNG->PAGE_HELP_BODY .= '<table style="margin: 0 auto;padding-right:15px;width:100%;margin-bottom:10px;">';
	$LNG->PAGE_HELP_BODY .= '<tr>';
	$LNG->PAGE_HELP_BODY .= '<td>';
	$LNG->PAGE_HELP_BODY .= '<div id="homebuttonmessagediv3" class="plainborder curvedBorder" style="clear:both;float:left;width:99%;display:none;margin-left:10px;">';
	$LNG->PAGE_HELP_BODY .= '<input type="hidden" id="homebuttonmessagelasttype3" value="" />';
	$LNG->PAGE_HELP_BODY .= '<div style="clear:both;float:left;padding:10px;padding-top:0px;" id="homebuttonmessage3"></div>';
	$LNG->PAGE_HELP_BODY .= '</div>';
	$LNG->PAGE_HELP_BODY .= '</td>';
	$LNG->PAGE_HELP_BODY .= '</tr>';
	$LNG->PAGE_HELP_BODY .= '</table>';
}

$LNG->PAGE_HELP_BODY .= '<table cellspacing="10" style="margin: 0 auto;border-spacing:10px 0px;width: 100%;">';
$LNG->PAGE_HELP_BODY .= '<tr>';
$LNG->PAGE_HELP_BODY .= '<td width="33%">';
$LNG->PAGE_HELP_BODY .= '<div id="issuehomebutton" style="height:174px; padding:5px; font-weight:bold;" class="plainbackgradient plainborder curvedBorder homebutton1" onmouseover="this.className=\'issuebackgradient issueborder curvedBorder homebutton1\';" onmouseout="if ($(\'homebuttonmessagediv\').style.display == \'none\' || $(\'homebuttonmessagelasttype\').value != \'issue\') {this.className=\'plainbackgradient plainborder curvedBorder homebutton1\';}" title="Click to view/hide additional information" onclick="showHomeButtonText(event, \'issue\');">';
$LNG->PAGE_HELP_BODY .= '<div style="margin: 0 auto; width:64px;margin-bottom:5px;">';
$LNG->PAGE_HELP_BODY .= '<img src="'.$HUB_FLM->getImagePath('nodetypes/Default/issue64px.png').'" border="0" />';
$LNG->PAGE_HELP_BODY .= '</div>';

$LNG->PAGE_HELP_BODY .= '<div style="padding:0px;"><center><h2>'.$LNG->ISSUES_NAME.'</h2></center>';
$LNG->PAGE_HELP_BODY .= 'Click in this box to read more.';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</td>';

$LNG->PAGE_HELP_BODY .= '<td width="33%">';
$LNG->PAGE_HELP_BODY .= '<div id="solutionhomebutton" style="height:174px; padding:5px; font-weight:bold;" class="plainbackgradient plainborder curvedBorder homebutton1" onmouseover="this.className=\'solutionbackgradient solutionborder curvedBorder homebutton1\';" onmouseout="if ($(\'homebuttonmessagediv\').style.display == \'none\' || $(\'homebuttonmessagelasttype\').value != \'solutin\') {this.className=\'plainbackgradient plainborder curvedBorder homebutton1\';}" title="Click to view/hide additional information" onclick="showHomeButtonText(event, \'solution\');">';
if ($CFG->HAS_SOLUTION && $CFG->HAS_CLAIM) {
	$LNG->PAGE_HELP_BODY .= '<div style="margin: 0 auto; width:140px;margin-bottom:5px;">';
} else {
	$LNG->PAGE_HELP_BODY .= '<div style="margin: 0 auto; width:64px;margin-bottom:5px;">';
}
if ($CFG->HAS_SOLUTION) {
	$LNG->PAGE_HELP_BODY .= '<img src="'.$HUB_FLM->getImagePath('nodetypes/Default/solution64px.png').'" border="0" />';
}
if ($CFG->HAS_CLAIM) {
	$LNG->PAGE_HELP_BODY .= '<img src="'.$HUB_FLM->getImagePath('nodetypes/Default/claim64px.png').'" border="0" />';
}
$LNG->PAGE_HELP_BODY .= '</div>';

$LNG->PAGE_HELP_BODY .= '<div style="padding:0px;width:100%"> ';
$LNG->PAGE_HELP_BODY .= '<center><h2>';
if ($CFG->HAS_SOLUTION) {
	$LNG->PAGE_HELP_BODY .= $LNG->SOLUTIONS_NAME;
}
if ($CFG->HAS_SOLUTION  && $CFG->HAS_CLAIM) {
	$LNG->PAGE_HELP_BODY .= ' and ';

}
if ($CFG->HAS_CLAIM) {
	$LNG->PAGE_HELP_BODY .= $LNG->CLAIMS_NAME;
}
$LNG->PAGE_HELP_BODY .= '</h2></center>';
$LNG->PAGE_HELP_BODY .= 'Click in this box to read more.';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</td>';


$LNG->PAGE_HELP_BODY .= '<td width="33%">';
$LNG->PAGE_HELP_BODY .= '<div id="evidencehomebutton" style="height:174px; padding:5px; font-weight:bold;" class="plainbackgradient plainborder curvedBorder homebutton1" onmouseover="this.className=\'evidencebackgradient evidenceborder curvedBorder homebutton1\';" onmouseout="if ($(\'homebuttonmessagediv\').style.display == \'none\' || $(\'homebuttonmessagelasttype\').value != \'evidence\') {this.className=\'plainbackgradient plainborder curvedBorder homebutton1\'}" title="Click to view/hide additional information" onclick="showHomeButtonText(event, \'evidence\');">';
$LNG->PAGE_HELP_BODY .= '<div style="margin: 0 auto; width:64px;margin-bottom:5px;">';
$LNG->PAGE_HELP_BODY .= '<img src="'.$HUB_FLM->getImagePath('nodetypes/Default/literature-analysis64px.png').'" border="0" />';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '<div style="padding:0px;">';
$LNG->PAGE_HELP_BODY .= '<center><h2>'.$LNG->EVIDENCES_NAME.'</h2></center>';
$LNG->PAGE_HELP_BODY .= 'Click in this box to read more.';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</td>';

$LNG->PAGE_HELP_BODY .= '</tr>';

$LNG->PAGE_HELP_BODY .= '</table>';

$LNG->PAGE_HELP_BODY .= '<table style="margin: 0 auto;padding-right:15px;width:100%;margin-bottom:10px;width: 100%;">';
$LNG->PAGE_HELP_BODY .= '<tr>';
$LNG->PAGE_HELP_BODY .= '<td>';
$LNG->PAGE_HELP_BODY .= '<div id="homebuttonmessagediv" class="plainborder curvedBorder" style="clear:both;float:left;width:99%;display:none;margin-left:10px;">';
$LNG->PAGE_HELP_BODY .= '<input type="hidden" id="homebuttonmessagelasttype" value="" />';
$LNG->PAGE_HELP_BODY .= '<div style="clear:both;float:left;padding:10px;padding-top:0px;" id="homebuttonmessage"></div>';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</td>';
$LNG->PAGE_HELP_BODY .= '</tr>';
$LNG->PAGE_HELP_BODY .= '</table>';

$LNG->PAGE_HELP_BODY .= '<table cellspacing="10" style="margin: 0 auto;border-spacing:10px 0px;width:100%">';
$LNG->PAGE_HELP_BODY .= '<tr>';

$LNG->PAGE_HELP_BODY .= '<td width="33%">';
$LNG->PAGE_HELP_BODY .= '<div id="resourcehomebutton" style="height:174px; padding:5px; font-weight:bold;" class="plainbackgradient plainborder curvedBorder homebutton1" onmouseover="this.className=\'resourcebackgradient resourceborder curvedBorder homebutton1\';" onmouseout="if ($(\'homebuttonmessagediv2\').style.display == \'none\' || $(\'homebuttonmessagelasttype2\').value != \'resource\') {this.className=\'plainbackgradient plainborder curvedBorder homebutton1\';}" title="Click to view/hide additional information" onclick="showHomeButtonText2(event, \'resource\');">';
$LNG->PAGE_HELP_BODY .= '<div style="margin: 0 auto; width:64px;margin-bottom:5px;">';
$LNG->PAGE_HELP_BODY .= '<img src="'.$HUB_FLM->getImagePath('nodetypes/Default/reference64px.png').'" border="0" />';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '<div style="padding:0px;"><center><h2>'.$LNG->RESOURCES_NAME.'</h2></center>';
$LNG->PAGE_HELP_BODY .= 'Click in this box to read more.';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</td>';

$LNG->PAGE_HELP_BODY .= '<td width="33%">';
$LNG->PAGE_HELP_BODY .= '<div id="orghomebutton" style="height:174px; padding:5px; font-weight:bold;" class="plainbackgradient plainborder curvedBorder homebutton1" onmouseover="this.className=\'orgbackgradient orgborder curvedBorder homebutton2\';" onmouseout="if ($(\'homebuttonmessagediv2\').style.display == \'none\' || $(\'homebuttonmessagelasttype2\').value != \'org\') {this.className=\'plainbackgradient plainborder curvedBorder homebutton1\';}" title="Click to view/hide additional information" onclick="showHomeButtonText2(event, \'org\');">';
$LNG->PAGE_HELP_BODY .= '<div style="margin: 0 auto; width:64px;margin-bottom:5px;">';
$LNG->PAGE_HELP_BODY .= '<img src="'.$HUB_FLM->getImagePath('nodetypes/Default/country64px.png').'" border="0" />';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '<div style="padding:0px;"><center><h2>'.$LNG->ORGS_NAME.' and '.$LNG->PROJECTS_NAME.'</h2></center>';
$LNG->PAGE_HELP_BODY .= 'Click in this box to read more.';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</td>';

$LNG->PAGE_HELP_BODY .= '<td width="33%">';
$LNG->PAGE_HELP_BODY .= '<div id="storyhomebutton" style="height:174px; padding:5px; font-weight:bold;" class="plainbackgradient plainborder curvedBorder homebutton1" onmouseover="this.className=\'themebackgradient themeborder curvedBorder homebutton1\';" onmouseout="if ($(\'homebuttonmessagediv2\').style.display == \'none\' || $(\'homebuttonmessagelasttype2\').value != \'story\') {this.className=\'plainbackgradient plainborder curvedBorder homebutton1\';}" title="Click to view/hide additional information" onclick="showHomeButtonText2(event, \'story\');">';
$LNG->PAGE_HELP_BODY .= '<div style="margin: 0 auto; width:64px;margin-bottom:5px;">';
$LNG->PAGE_HELP_BODY .= '<img src="'.$HUB_FLM->getImagePath('nodetypes/Default/story64px.png').'" width="64" height="64" border="0" />';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '<div style="padding:0px;"><center><h2>Stories</h2></center>';
$LNG->PAGE_HELP_BODY .= 'Click in this box to read more.';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</td>';

$LNG->PAGE_HELP_BODY .= '</tr>';
$LNG->PAGE_HELP_BODY .= '</table>';

$LNG->PAGE_HELP_BODY .= '<table style="margin: 0 auto;padding-right:15px;width:100%;">';
$LNG->PAGE_HELP_BODY .= '<tr>';
$LNG->PAGE_HELP_BODY .= '<td>';
$LNG->PAGE_HELP_BODY .= '<div id="homebuttonmessagediv2" class="plainborder curvedBorder" style="clear:both;float:left;width:99%;display:none;margin-left:10px;">';
$LNG->PAGE_HELP_BODY .= '<input type="hidden" id="homebuttonmessagelasttype2" value="" />';
$LNG->PAGE_HELP_BODY .= '<div style="clear:both;float:left;padding:10px;padding-top:0px;" id="homebuttonmessage2"></div>';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</td>';
$LNG->PAGE_HELP_BODY .= '</tr>';
$LNG->PAGE_HELP_BODY .= '</table>';
$LNG->PAGE_HELP_BODY .= '</div>';


$LNG->PAGE_HELP_BODY .= '<h2>How the Categories Connect</h2>';

$LNG->PAGE_HELP_BODY .= '<div style="clear:both;float:left;height:60px;width:100%;padding:0px;margin-bottom:10px;background:white;" >';

if ($CFG->HAS_CHALLENGE) {
	$LNG->PAGE_HELP_BODY .= '<div class="navnetworkiconfirst">';
	$LNG->PAGE_HELP_BODY .= '<a class="navnetworklink" href="javascript:void(0)" onclick="showHomeNavText(\'ChallengesHome\'); return false;" title="'.$LNG->HOME_ADDITIONAL_INFO_TOGGLE_HINT.'" onkeypress="enterKeyPressed(event)">';
	$LNG->PAGE_HELP_BODY .= '<center>';
	$LNG->PAGE_HELP_BODY .= '<img class="navnetworkimage" src="'.$CFG->challengeicon.'" width="20" height="20" border="0" />';
	$LNG->PAGE_HELP_BODY .= '<br><span class="';
	$LNG->PAGE_HELP_BODY .= "navnetworktext";
	$LNG->PAGE_HELP_BODY .= '">'.$LNG->CHALLENGE_NAME.'</span>';
	$LNG->PAGE_HELP_BODY .= '</center>';
	$LNG->PAGE_HELP_BODY .= '</a>';
	$LNG->PAGE_HELP_BODY .= '</div>';
	$LNG->PAGE_HELP_BODY .= '<div class="navnetworkarrow">';
	$LNG->PAGE_HELP_BODY .= '<img src="'.$HUB_FLM->getImagePath('arrow-orange-right.jpg').'" border="0" />';
	$LNG->PAGE_HELP_BODY .= '</div>';
}
if ($CFG->HAS_CHALLENGE) {
	$LNG->PAGE_HELP_BODY .= '<div class="navnetworkicon">';
} else {
	$LNG->PAGE_HELP_BODY .= '<div class="navnetworkiconfirst">';
}
$LNG->PAGE_HELP_BODY .= '<a class="navnetworklink" href="javascript:void(0)" onclick="showHomeNavText(\'IssuesHome\'); return false;" title="'.$LNG->HOME_ADDITIONAL_INFO_TOGGLE_HINT.'" onkeypress="enterKeyPressed(event)">';
$LNG->PAGE_HELP_BODY .= '<center>';
$LNG->PAGE_HELP_BODY .= '<img class="navnetworkimage" src="'.$CFG->issueicon.'" width="20" height="20" border="0" />';
$LNG->PAGE_HELP_BODY .= '<br><span class="navnetworktext">'.$LNG->ISSUE_NAME.'</span>';
$LNG->PAGE_HELP_BODY .= '</center>';
$LNG->PAGE_HELP_BODY .= '</a>';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '<div class="navnetworkarrow">';
$LNG->PAGE_HELP_BODY .= '<img src="'.$HUB_FLM->getImagePath('arrow-orange-right.jpg').'" border="0" />';
$LNG->PAGE_HELP_BODY .= '</div>';

if ($CFG->HAS_SOLUTION) {
	$LNG->PAGE_HELP_BODY .= '<div class="navnetworkicon">';
	$LNG->PAGE_HELP_BODY .= '<a class="navnetworklink" href="javascript:void(0)" onclick="showHomeNavText(\'SolutionsHome\'); return false;" title="'.$LNG->HOME_ADDITIONAL_INFO_TOGGLE_HINT.'" onkeypress="enterKeyPressed(event)">';
	$LNG->PAGE_HELP_BODY .= '<center>';
	$LNG->PAGE_HELP_BODY .= '<img class="navnetworkimage" src="'.$CFG->solutionicon.'" width="20" height="20" border="0" />';
	$LNG->PAGE_HELP_BODY .= '<br><span class="navnetworktext">'.$LNG->SOLUTION_NAME.'</span>';
	$LNG->PAGE_HELP_BODY .= '</center>';
	$LNG->PAGE_HELP_BODY .= '</a>';
	$LNG->PAGE_HELP_BODY .= '</div>';
}

if ($CFG->HAS_SOLUTION && $CFG->HAS_CLAIM) {
$LNG->PAGE_HELP_BODY .= '<div class="navnetworkdivider">';
$LNG->PAGE_HELP_BODY .= '<span style="font-weight:bold;clear:both">/</span>';
$LNG->PAGE_HELP_BODY .= '</div>';
}

if ($CFG->HAS_CLAIM) {
	$LNG->PAGE_HELP_BODY .= '<div class="navnetworkicon">';
	$LNG->PAGE_HELP_BODY .= '<a class="navnetworklink" href="javascript:void(0)" onclick="showHomeNavText(\'ClaimsHome\'); return false;" title="'.$LNG->HOME_ADDITIONAL_INFO_TOGGLE_HINT.'" onkeypress="enterKeyPressed(event)">';
	$LNG->PAGE_HELP_BODY .= '<center>';
	$LNG->PAGE_HELP_BODY .= '<img class="navnetworkimage" src="'.$CFG->claimicon.'" width="20" height="20" border="0" />';
	$LNG->PAGE_HELP_BODY .= '<br><span class="navnetworktext">'.$LNG->CLAIM_NAME.'</span>';
	$LNG->PAGE_HELP_BODY .= '</center>';
	$LNG->PAGE_HELP_BODY .= '</a>';
	$LNG->PAGE_HELP_BODY .= '</div>';
}
$LNG->PAGE_HELP_BODY .= '<div class="navnetworkarrow">';
$LNG->PAGE_HELP_BODY .= '<img src="'.$HUB_FLM->getImagePath('arrow-orange-right.jpg').'" border="0" />';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '<div class="navnetworkicon">';
$LNG->PAGE_HELP_BODY .= '<a class="navnetworklink" href="javascript:void(0)" onclick="showHomeNavText(\'EvidenceHome\'); return false;" title="'.$LNG->HOME_ADDITIONAL_INFO_TOGGLE_HINT.'" onkeypress="enterKeyPressed(event)">';
$LNG->PAGE_HELP_BODY .= '<center>';
$LNG->PAGE_HELP_BODY .= '<img class="navnetworkimage" src="'.$CFG->evidenceicon.'" width="20" height="20" border="0" />';
$LNG->PAGE_HELP_BODY .= '<br><span class="navnetworktext">'.$LNG->EVIDENCE_NAME.'</span>';
$LNG->PAGE_HELP_BODY .= '</center>';
$LNG->PAGE_HELP_BODY .= '</a>';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '<div class="navnetworkarrow">';
$LNG->PAGE_HELP_BODY .= '<img src="'.$HUB_FLM->getImagePath('arrow-orange-right.jpg').'" border="0" />';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '<div class="navnetworkicon">';
$LNG->PAGE_HELP_BODY .= '<a class="navnetworklink" href="javascript:void(0)" onclick="showHomeNavText(\'ResourcesHome\'); return false;" title="'.$LNG->HOME_ADDITIONAL_INFO_TOGGLE_HINT.'" onkeypress="enterKeyPressed(event)">';
$LNG->PAGE_HELP_BODY .= '<center>';
$LNG->PAGE_HELP_BODY .= '<img class="navnetworkimage" src="'.$CFG->resourceicon.'" width="20" height="20" border="0" />';
$LNG->PAGE_HELP_BODY .= '<br><span class="navnetworktext">'.$LNG->RESOURCE_NAME.'</span>';
$LNG->PAGE_HELP_BODY .= '</center>';
$LNG->PAGE_HELP_BODY .= '</a>';
$LNG->PAGE_HELP_BODY .= '</div>';
$LNG->PAGE_HELP_BODY .= '</div>';

$LNG->PAGE_HELP_BODY .= '<div id="helpmessagediv" class="plainborder curvedBorder" style="clear:both;float:left;width:99%;display:none;margin-left:10px;margin-right:20px;margin-bottom:20px;">';
$LNG->PAGE_HELP_BODY .= '<input type="hidden" id="helpmessagelasttype" value="" />';
$LNG->PAGE_HELP_BODY .= '<div style="clear:both;float:left;padding-left:10px;padding-right:10px" id="helpMessage"></div>';
$LNG->PAGE_HELP_BODY .= '</div>';

$LNG->PAGE_HELP_BODY .= '<br>';

$LNG->PAGE_HELP_BODY .= '<h2>Explore '.$LNG->THEMES_NAME.'</h2>';
$LNG->PAGE_HELP_BODY .= '<ul style="padding-left:20px;margin-left:0px">';



$LNG->PAGE_HELP_BODY .= '<li>LiteMap has a grouping systems which allows users to cluster information under the same '.$LNG->THEME_NAME.'.</li>';
$LNG->PAGE_HELP_BODY .= '<li>'.$LNG->THEMES_NAME.' are shown in the homepage as a list of coloured boxes . If you click on a theme you will see all the information (';
if ($CFG->HAS_CHALLENGE) {
	$LNG->PAGE_HELP_BODY .= $LNG->CHALLENGES_NAME.', ';
}

$LNG->PAGE_HELP_BODY .= $LNG->ISSUES_NAME.', ';
if ($CFG->HAS_SOLUTION) {
	$LNG->PAGE_HELP_BODY .= $LNG->SOLUTIONS_NAME.', ';
}
if ($CFG->HAS_CLAIM) {
	$LNG->PAGE_HELP_BODY .= $LNG->CLAIMS_NAME.', ';
}
$LNG->PAGE_HELP_BODY .=  $LNG->RESOURCES_NAME.', '.$LNG->PROJECTS_NAME.', '.$LNG->ORGS_NAME.' ) etc. have that same that have been given that theme.</li>';

$LNG->PAGE_HELP_BODY .= '<li>The list of '.$LNG->THEMES_NAME.' is decided by the administrator of LiteMap.</li>';
$LNG->PAGE_HELP_BODY .= '<li>'.$LNG->THEMES_NAME.' aim to represent the higher level topics of interest for the community, while for lower level categorisation users can add tags, which are also displayed in LiteMap\'s homepage as tag cloud.</li>';

$LNG->PAGE_HELP_BODY .= '</ul>';

$LNG->PAGE_HELP_BODY .= '<h2>Follow Your Interests</h2>';
$LNG->PAGE_HELP_BODY .= '<ul style="padding-left:20px;margin-left:0px">';
$LNG->PAGE_HELP_BODY .= '<li>Interested in something specific? You can follow any people, '.$LNG->ORGS_NAME.', '.$LNG->PROJECTS_NAME.', '.$LNG->THEMES_NAME.', ';
if ($CFG->HAS_CHALLENGE) {
	$LNG->PAGE_HELP_BODY .= $LNG->CHALLENGES_NAME.', ';
}

$LNG->PAGE_HELP_BODY .= $LNG->ISSUES_NAME.', ';
if ($CFG->HAS_SOLUTION) {
	$LNG->PAGE_HELP_BODY .= $LNG->SOLUTIONS_NAME.', ';
}
if ($CFG->HAS_CLAIM) {
	$LNG->PAGE_HELP_BODY .= $LNG->CLAIMS_NAME.', ';
}
$LNG->PAGE_HELP_BODY .=  $LNG->EVIDENCES_NAME.' etc. in LiteMap.</li>';

$LNG->PAGE_HELP_BODY .= '<li>You can follow any node in the hub by clicking on the follow icon <img border="0" src="'.$HUB_FLM->getImagePath('follow.png').'"/>.</li>';
$LNG->PAGE_HELP_BODY .= '<li>When you start following a node the activities related to that node will be listed in your Homepage.</li>';
$LNG->PAGE_HELP_BODY .= '<li>You can also choose to receive a daily/weekly/monthly email digest of all activities related to your followed items and people.</li>';
$LNG->PAGE_HELP_BODY .= '<li>By following an item in LiteMap you will also be added to the list of followers shown in the explore page of that item.</li>';
$LNG->PAGE_HELP_BODY .= '</ul>';
*/
?>