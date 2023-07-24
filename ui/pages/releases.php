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
include_once("../../config.php");

include_once($HUB_FLM->getCodeDirPath("ui/header.php"));
?>

<div style="float:left;padding:10px;padding-top:0px;padding-botton:0px;">
<center><h1><?php echo $CFG->SITE_TITLE; ?> Releases</h1></center>

<h2>0.01 Alpha - October 2013 - 18th January 2015</h2>
<p>Initial development:
<ol>
<li>Groups for user and data clustering.</li>
<li>Inline discussion of ideas, pros and cons.</li>
<li>New list design based on panels for each item with images on Groups and Issues.</li>
<li>Dashboard of visualisation at System, Groups and Issue levels.</li>
<li>New restful API returning CIF formatted jsonld.</li>
<li>Timed debates with separate voting phase if required.</li>
<li>Moderator features - splitting and merging.</li>
<li>New Random sort order for Idea lists.</li>
<li>Testing system for automatic A/B grouping and test specific logging.</li>
</ol>
</p>

<h2>0.1 Alpha - 19th January 2015</h2>
<p>Speed improvements:
<ol>
<li>Implementation of Caching of key datamodel classes to enhance performance.</li>
<li>Implement memcache for Restful API.</li>
</ol>
</p>

<h2>0.2 Alpha - 8th April 2015</h2>
<p>
<ol>
<li>User obfuscation: Separation of private user data from main data on Restful API calls including a security key system.</li>
<li>New group joining management system.</li>
<li>'My Groups' tab in user area to show groups I manage and groups I am in.</li>
</ol>
</p>

<h2>0.3 Alpha - 22nd April 2015</h2>
<p>
<ol>
<li>New homepage design showing you your Groups and Issues when logged in.</li>
<li>New scrolling lists on homepage.</li>
<li>Traffic light statistic on the Debate page.</li>
<li>Small interface tweaks.</li>
</ol>
</p>

<h2>0.4 Alpha - 18th June 2015</h2>
<p>
<ol>
<li>A new Moderators concept has been added to Debte Hub to allow Debate owners and group Admins to moderate Debates.
	<br>This exposes two existing 'moderator' features: splitting an idea into more than one idea, and merging several ideas into one.
	<br>These features where formerly only seen by system admins.</li>
<li>New Debate sidebar section to list Debate moderators.</li>
<li>New section under Ideas to list Moderator comments on an idea or its arguments.</li>
<li>New Debate sideber section to show Moderator Alerts relevant for the moderators of the Debate.</li>
<li>New Debate sideber section to show User Alerts relevant for the logged in user.</li>
<li>Interface changes to move the 'Add Idea' form into the main section of the page to make adding Ideas more obvious.
	<br>Also a small plus button has been added infront of the Arguments link on Ideas to show more obviously where you go to add arguments.</li>
<li>Group admins have now been highlighted to the group by putting a border around thier picture in the Groups members list.</li>
<li>Small interface tweaks.</li>
</ol>
</p>

<h2>0.5 Alpha - 16th October 2015</h2>
<p>
We have added the concept of phasing a debate through three timed sections. Users can choose to set time boundaries on a debate, as before, but now also time phases of the debate. There are now three phases to a phased debate:
	<ol>
		<li>Discuss Phase: where users add ideas and arguments to the debate;</li>
		<li>Reduce Phase: (NEW) where users can allocate up to 10 lemons to the ideas they least like.
		These are then used to reduce to final idea list down by up to 60%;</li>
		<li>Decide Phase: where users vote on the remianing ideas to determine the most popular top 3 ideas.</li>
	</ol>
</p>

<h2>1.0 - 19th November 2015</h2>
<p>
	<ol>
		<li>User Debate Issues list shows as Debate boxes, not text list.</li>
		<li>User list added to the Admin area.</li>
		<li>Help movies added.</li>
		<li>Bug fixes.</li>
	</ol>
</p>

</div>
<?php
include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
?>