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
 * languageui.php
 *
 * Michelle Bachler (KMi)
 *
 * This should eventually be broken up into separate files and become part of the internationalization of the Debate Hub
 */

/** HEADERS **/
$LNG->HEADER_LOGO_HINT = 'Go to the '.$CFG->SITE_TITLE.' home page';
$LNG->HEADER_LOGO_ALT = $CFG->SITE_TITLE.' Logo';
$LNG->HEADER_HOME_ICON_HINT = 'Go back to Home Page';
$LNG->HEADER_HOME_ICON_ALT = 'Home icon';
$LNG->HEADER_RSS_FEED_ICON_HINT = 'Get an RSS feed for the '.$CFG->SITE_TITLE.'. Note: each '.$LNG->ISSUE_NAME.' has own feed too.';
$LNG->HEADER_RSS_FEED_ICON_ALT = 'RSS Icon';
$LNG->HEADER_USER_HOME_LINK_HINT = 'Go to your home page';
$LNG->HEADER_EDIT_PROFILE_LINK_TEXT = 'Edit Profile';
$LNG->HEADER_EDIT_PROFILE_LINK_HINT = 'Edit your profile information';
$LNG->HEADER_SIGN_OUT_LINK_TEXT = 'Sign Out';
$LNG->HEADER_SIGN_OUT_LINK_HINT = 'Sign Out';
$LNG->HEADER_SIGN_IN_LINK_TEXT = 'Sign In';
$LNG->HEADER_SIGN_IN_LINK_HINT = 'Sign In';
$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT = 'Sign Up';
$LNG->HEADER_SIGNUP_OPEN_LINK_HINT = 'Register now instantly, so you can sign in and add data';
$LNG->HEADER_SIGNUP_REQUEST_LINK_TEXT = 'Sign Up';
$LNG->HEADER_SIGNUP_REQUEST_LINK_HINT = 'Request an account, so you can sign in and add data';
$LNG->HEADER_HELP_PAGE_LINK_TEXT = 'Help';
$LNG->HEADER_HELP_PAGE_LINK_HINT = 'Go to the Help page';
$LNG->HEADER_ABOUT_PAGE_LINK_TEXT = 'About';
$LNG->HEADER_ABOUT_PAGE_LINK_HINT = 'Go to the About page';
$LNG->HEADER_ADMIN_PAGE_LINK_TEXT = 'Admin';
$LNG->HEADER_ADMIN_PAGE_LINK_HINT = 'Go to the Admin page';
$LNG->HEADER_MY_HUB_LINK = 'My Hub';

$LNG->HEADER_SEARCH_RUN_ICON_HINT = 'Run Search';
$LNG->HEADER_SEARCH_RUN_ICON_ALT = 'Run';

$LNG->HEADER_SEARCH_INFO_HINT = "<div  style='padding:10px;'>The default search will separate words using the spaces and perform an OR search, e.g. <b>'school system'</b> will search for the words <b>'school' OR 'system'</b> in either the item title, item description or any associated web clip texts.";
$LNG->HEADER_SEARCH_INFO_HINT .= "<br><br>Use a '+' between words is you wish to perform an AND search, e.g. <b>'school+system'</b> will search for both the words <b>'school' and 'system'</b> somewhere in either the item title, item description or any associated web clip texts.";
$LNG->HEADER_SEARCH_INFO_HINT .= "<br><br>Use double quotes around the search string to perform a phrase search, e.g. <b>\"school system\"</b> will search for the <b>exact phrase 'school system'</b> in either the item title, item description or any associated web clip texts.</div>";

/** FOOTER **/
$LNG->FOOTER_TERMS_LINK = 'Terms of Use';
$LNG->FOOTER_PRIVACY_LINK = 'Privacy';
$LNG->FOOTER_CONTACT_LINK = 'Contact';
$LNG->FOOTER_COOKIES_LINK = 'Cookies';
$LNG->FOOTER_ACCESSIBILITY = 'Accessibility';

/** REPORT FOOTER **/
$LNG->FOOTER_REPORT_PRINTED_ON = 'Report printed on:';

$LNG->HOME_MOST_POPULAR_GROUPS_TITLE = 'Most Popular '.$LNG->GROUPS_NAME;
$LNG->HOME_MOST_RECENT_GROUPS_TITLE = 'Newest '.$LNG->GROUPS_NAME;
$LNG->HOME_MOST_RECENT_DEBATES_TITLE = 'Newest '.$LNG->DEBATES_NAME;
$LNG->HOME_MY_GROUPS_TITLE = 'My '.$LNG->GROUPS_NAME;
$LNG->HOME_MY_GROUPS_AREA_LINK = 'View my '.$LNG->GROUPS_NAME.' area';
$LNG->HOME_MY_DEBATES_TITLE = 'My '.$LNG->DEBATES_NAME;
$LNG->HOME_MY_DEBATES_AREA_LINK = 'View my '.$LNG->DEBATES_NAME.' area';

/** HOME PAGE **/
$LNG->HOMEPAGE_VIEW_ALL = "View All";
$LNG->HOMEPAGE_NEWS_TITLE = "Recent News";

$LNG->DEBATE_CREATE_LOGGED_OUT_OPEN = "to Create a New ".$LNG->DEBATE_NAME;
$LNG->DEBATE_CREATE_LOGGED_OUT_REQUEST = "to Create a New ".$LNG->DEBATE_NAME;
$LNG->DEBATE_CREATE_LOGGED_OUT_CLOSED = "to Create a New ".$LNG->DEBATE_NAME;

$LNG->SOLUTION_CREATE_LOGGED_OUT_OPEN = "to contribute to this ".$LNG->DEBATE_NAME;
$LNG->SOLUTION_CREATE_LOGGED_OUT_REQUEST = "to contribute to this ".$LNG->DEBATE_NAME;
$LNG->SOLUTION_CREATE_LOGGED_OUT_CLOSED = "to contribute to this ".$LNG->DEBATE_NAME;

/** GROUPS **/
$LNG->FORM_BUTTON_DELETE_GROUP = 'Delete '.$LNG->GROUP_NAME;
$LNG->FORM_BUTTON_JOIN_GROUP = 'Join '.$LNG->GROUP_NAME;
$LNG->FORM_BUTTON_JOIN_GROUP_CLOSED = 'Request to join '.$LNG->GROUP_NAME;

$LNG->ERROR_GROUP_NOT_FOUND_MESSAGE = "The required Group could not be found";
$LNG->ERROR_GROUP_USER_LAST_ADMIN = "You cannot remove that user as an admin, as then the group will have no admins";
$LNG->ERROR_GROUP_EXISTS_MESSAGE = "A group with this name already exists";
$LNG->ERROR_GROUP_USER_NOT_MEMBER = "The current user is not a member of the required Group.";

$LNG->GROUP_CREATE_TITLE = 'Create New '.$LNG->GROUP_NAME;
$LNG->GROUP_MANAGE_TITLE = 'Manage '.$LNG->GROUPS_NAME;
$LNG->GROUP_MANAGE_SINGLE_TITLE = 'Manage '.$LNG->GROUP_NAME;

$LNG->GROUP_CREATE_LOGGED_OUT_OPEN = "to Create a New ".$LNG->GROUP_NAME;
$LNG->GROUP_CREATE_LOGGED_OUT_REQUEST = "to Create a New ".$LNG->GROUP_NAME;
$LNG->GROUP_CREATE_LOGGED_OUT_CLOSED = "to Create a New ".$LNG->GROUP_NAME;

$LNG->GROUP_JOIN_GROUP = " to Create a New ".$LNG->ISSUE_NAME;

$LNG->ISSUE_PHOTO_FORM_HINT = "(optional) - Please add an image to represent this ".$LNG->DEBATE_NAME;

$LNG->GROUP_PHOTO_FORM_HINT = "(optional) - Please add an image to represent this ".$LNG->GROUP_NAME;
$LNG->GROUP_NAME_FORM_HINT = "(compulsory) - The Name of this ".$LNG->GROUP_NAME;
$LNG->GROUP_DESC_FORM_HINT = "(optional) - A description of the purpose of this ".$LNG->GROUP_NAME;
$LNG->GROUP_WEBSITE_FORM_HINT = "(optional) - Add an associated website for this ".$LNG->GROUP_NAME;

$LNG->GROUP_FORM_NAME = "Name:";
$LNG->GROUP_FORM_DESC = "Description:";
$LNG->GROUP_FORM_WEBSITE = "Website:";
$LNG->GROUP_FORM_MEMBERS_CURRENT = "Current Members:";

$LNG->GROUP_FORM_SELECT = "Select a ".$LNG->GROUP_NAME;
$LNG->GROUP_FORM_NO_MEMBERS = 'This '.$LNG->GROUP_NAME.' has no members.';
$LNG->GROUP_FORM_NO_PENDING = 'This '.$LNG->GROUP_NAME.' has no pending member requests.';
$LNG->GROUP_FORM_MEMBERS_PENDING = "Member Join Requests:";
$LNG->GROUP_FORM_NAME_LABEL = "Name";
$LNG->GROUP_FORM_DESC_LABEL = "Description";
$LNG->GROUP_FORM_ISADMIN_LABEL = "Admin";
$LNG->GROUP_FORM_REMOVE_LABEL = "Remove";
$LNG->GROUP_FORM_APPROVE_LABEL = "Approve";
$LNG->GROUP_FORM_REJECT_LABEL = "Reject";
$LNG->GROUP_FORM_REMOVE_MESSAGE_PART1 = 'Are you sure you want to remove';
$LNG->GROUP_FORM_REMOVE_MESSAGE_PART2 = 'from this '.$LNG->GROUP_NAME.'?';
$LNG->GROUP_FORM_REJECT_MESSAGE_PART1 = 'Are you sure you want to reject';
$LNG->GROUP_FORM_REJECT_MESSAGE_PART2 = 'as a member of this '.$LNG->GROUP_NAME.'?';
$LNG->GROUP_FORM_APPROVE_MESSAGE_PART1 = 'Are you sure you want to approve';
$LNG->GROUP_FORM_APPROVE_MESSAGE_PART2 = 'to be a member of this '.$LNG->GROUP_NAME.'?';
$LNG->GROUP_FORM_IS_JOINING_OPEN_LABEL = 'Is '.$LNG->GROUP_NAME.' joining open?';
$LNG->GROUP_FORM_IS_JOINING_OPEN_HELP = 'Select the checkbox if you want people to decide to join the group themselves.<br>Leave the checkbox unselected if you wish to moderate group join requests and therefore control who can join the group.';

$LNG->GROUP_JOIN_REQUEST_MESSAGE = 'Your request to join this '.$LNG->GROUP_NAME.' has been logged and is waiting to be approved. You will recieve and email when you request has been processed.<br><br>Thank you for your interest in this '.$LNG->GROUP_NAME;
$LNG->GROUP_JOIN_PENDING_MESSAGE = 'Membership Pending';
$LNG->GROUP_MY_ADMIN_GROUPS_TITLE = $LNG->GROUPS_NAME.' I manage:';
$LNG->GROUP_MY_MEMBER_GROUPS_TITLE = $LNG->GROUPS_NAME.' I am a member of:';

$LNG->GROUP_FORM_SELECT = "Select a ".$LNG->GROUP_NAME;
$LNG->GROUP_FORM_NO_MEMBERS = 'This '.$LNG->GROUP_NAME.' has no members.';
$LNG->GROUP_FORM_NO_PENDING = 'This '.$LNG->GROUP_NAME.' has no pending member requests.';
$LNG->GROUP_FORM_MEMBERS_PENDING = "Member Join Requests:";
$LNG->GROUP_FORM_NAME_LABEL = "Name";
$LNG->GROUP_FORM_DESC_LABEL = "Description";
$LNG->GROUP_FORM_ISADMIN_LABEL = "Admin";
$LNG->GROUP_FORM_REMOVE_LABEL = "Remove";
$LNG->GROUP_FORM_APPROVE_LABEL = "Approve";
$LNG->GROUP_FORM_REJECT_LABEL = "Reject";
$LNG->GROUP_FORM_REMOVE_MESSAGE_PART1 = 'Are you sure you want to remove';
$LNG->GROUP_FORM_REMOVE_MESSAGE_PART2 = 'from this '.$LNG->GROUP_NAME.'?';
$LNG->GROUP_FORM_REJECT_MESSAGE_PART1 = 'Are you sure you want to reject';
$LNG->GROUP_FORM_REJECT_MESSAGE_PART2 = 'as a member of this '.$LNG->GROUP_NAME.'?';
$LNG->GROUP_FORM_APPROVE_MESSAGE_PART1 = 'Are you sure you want to approve';
$LNG->GROUP_FORM_APPROVE_MESSAGE_PART2 = 'to be a member of this '.$LNG->GROUP_NAME.'?';
$LNG->GROUP_JOIN_REQUEST_MESSAGE = 'Your request to join this '.$LNG->GROUP_NAME.' has been logged and is waiting to be approved. You will recieve and email when you request has been processed.<br><br>Thank you for your interest in this '.$LNG->GROUP_NAME;
$LNG->GROUP_JOIN_PENDING_MESSAGE = 'Membership Pending';

$LNG->GROUP_FORM_MEMBERS = "Add Members:<br/>(comma separated)";
$LNG->GROUP_FORM_MEMBERS_HELP = "Please enter the email address of all those people you would like to join this ".$LNG->GROUP_NAME.", all of these people will be sent an email notifying them of the group membership and any users who don't already have accounts will be invited to join.";
$LNG->GROUP_FORM_NAME_ERROR = 'You must enter a name for the '.$LNG->GROUP_NAME;
$LNG->GROUP_FORM_NOT_GROUP_ADMIN = 'You are not an administrator for this '.$LNG->GROUP_NAME;
$LNG->GROUP_FORM_NOT_GROUP_ADMIN_ANY = 'You are not an administrator for any '.$LNG->GROUPS_NAME;
$LNG->GROUP_FORM_LOCATION = 'Location: (town/city)';
$LNG->GROUP_FORM_PHOTO = 'Photo';
$LNG->GROUP_FORM_PHOTO_HELP = '(minimum size 150px w x 100px h. Larger images will be scaled/cropped to this size)';

$LNG->GROUP_FORM_IS_MEMBER = "is a current account holder and has been added to the ".$LNG->GROUP_NAME.".";
$LNG->GROUP_FORM_NOT_MEMBER = "is not a current account holder and has been invited to join.";

$LNG->GROUP_BLOCK_STATS_PEOPLE = 'Members:';
$LNG->GROUP_BLOCK_STATS_ISSUES = $LNG->ISSUES_NAME.':';
$LNG->GROUP_BLOCK_STATS_VOTES = $LNG->VOTES_NAME.':';

$LNG->DEBATE_BLOCK_STATS_VIEWS = 'Views:';
$LNG->DEBATE_BLOCK_STATS_PEOPLE = 'Participants:';
$LNG->DEBATE_BLOCK_STATS_ISSUES = $LNG->SOLUTIONS_NAME.':';
$LNG->DEBATE_BLOCK_STATS_ISSUES_ALL = 'All '.$LNG->SOLUTIONS_NAME.':';
$LNG->DEBATE_BLOCK_STATS_ISSUES_REMAINING = 'Remaining '.$LNG->SOLUTIONS_NAME.':';
$LNG->DEBATE_BLOCK_STATS_VOTES = $LNG->VOTES_NAME.':';

$LNG->DEBATE_BLOCK_STATS_LINK_HINT = "Click to go to a Dashboard of analytics and visualisations on this ".$LNG->ISSUE_NAME.".";

$LNG->GROUP_MEMBERS_LABEL = "Group Members";

$LNG->GROUP_DEBATE_CREATE_BUTTON = 'Create New '.$LNG->DEBATE_NAME;

/** END GROUP **/

$LNG->DEBATE_CONTRIBUTE_LINK_TEXT = "Contribute";
$LNG->DEBATE_CONTRIBUTE_LINK_HINT = "Contribute an ".$LNG->ARGUMENT_NAME." to this ".$LNG->SOLUTION_NAME;

$LNG->ALERT_NO_RESULTS = 'There are no Alerts at this time';
$LNG->ALERT_CLICK_HIGHLIGHT = 'Click to highlight in the '.$LNG->ISSUE_NAME;
$LNG->ALERT_SHOW_ALL = 'show all...';
$LNG->ALERT_SHOW_LESS = 'show less...';

$LNG->FORM_IDEA_NEW_TITLE = "Add Your ".$LNG->SOLUTION_NAME;
$LNG->FORM_IDEA_LABEL_TITLE = $LNG->SOLUTION_NAME." Title...";
$LNG->FORM_IDEA_LABEL_DESC = $LNG->SOLUTION_NAME." Description...";

$LNG->FORM_IDEA_MERGE_TITLE = "Merge Selected ".$LNG->SOLUTIONS_NAME;
$LNG->FORM_IDEA_MERGE_LABEL_TITLE = "Merged ".$LNG->SOLUTIONS_NAME." Title...";
$LNG->FORM_IDEA_MERGE_LABEL_DESC = "Merged ".$LNG->SOLUTIONS_NAME." Description...";
$LNG->FORM_IDEA_MERGE_HINT = "Create a new Idea representing the Selected ideas. Connect any Comments and Arguments on the Selected Ideas to this new Idea. Then retire the Selected Ideas.";
$LNG->FORM_IDEA_MERGE_MUST_SELECT = 'You must first select at least 2 ideas to merge.';
$LNG->FORM_IDEA_MERGE_NO_TITLE = "You must enter a title for the new merged ".$LNG->SOLUTION_NAME;

$LNG->FORM_SOLUTION_ENTER_SUMMARY_ERROR = 'Please enter a '.$LNG->SOLUTION_NAME.' before trying to publish';

$LNG->FORM_BUTTON_SUBMIT = 'Submit';
$LNG->FORM_BUTTON_SAVE = 'Save';
$LNG->FORM_BUTTON_SPLIT = 'Split';
$LNG->FORM_BUTTON_SPLIT_HINT = 'Split this '.$LNG->SOLUTION_NAME.' into two or more '.$LNG->SOLUTIONS_NAME;

$LNG->FORM_REMOVE_MULTI = "Are you sure you want to remove this item? This action cannot be undone!";
$LNG->FORM_SPLIT_IDEA_ERROR = "You must enter a title for the first two ideas";

$LNG->NODE_ADDED_BY = 'Added by:';
$LNG->NODE_CHILDREN_EVIDENCE_PRO = 'For';
$LNG->NODE_CHILDREN_EVIDENCE_CON = 'Against';
$LNG->FORM_PRO_ENTER_SUMMARY_ERROR = 'Please enter a title for your '.$LNG->PRO_NAME.' before trying to submit';
$LNG->FORM_CON_ENTER_SUMMARY_ERROR = 'Please enter a title for your '.$LNG->CON_NAME.' before trying to submit';

$LNG->FORM_PRO_LABEL_TITLE = $LNG->PRO_NAME." Title...";
$LNG->FORM_PRO_LABEL_DESC = $LNG->PRO_NAME." Description...";
$LNG->FORM_CON_LABEL_TITLE = $LNG->CON_NAME." Title...";
$LNG->FORM_CON_LABEL_DESC = $LNG->CON_NAME." Description...";
$LNG->FORM_LINK_LABEL = "Paste ".$LNG->RESOURCE_NAME."...";
$LNG->FORM_MORE_LINKS_BUTTONS = "Add Another ".$LNG->RESOURCE_NAME;
$LNG->FORM_DELETE_LINKS_BUTTONS = "Delete";
$LNG->FORM_LINK_INVALID_PART1 = "The url: ";
$LNG->FORM_LINK_INVALID_PART2 = ", is not a valid url. Make sure it starts with http:// or another valid web protocol";
$LNG->EXPLORE_EDITING_ARGUMENT_TITLE = "Editing";

$LNG->STATS_PRO_HINT_TEXT = "support";
$LNG->STATS_CON_HINT_TEXT = "opposition";

$LNG->DEBATE_IMAGE_LABEL = $LNG->DEBATE_NAME.' photo:';
$LNG->ISSUE_IMAGE_LABEL = $LNG->ISSUE_NAME.' Image:';
$LNG->BUILTFROM_DIALOG_TITLE=" was Built From:";
$LNG->DEBATE_MODE_BUTTON_ORGANIZE = 'Moderate';
$LNG->PAGE_BUTTON_SHARE = 'Share';
$LNG->PAGE_BUTTON_DASHBOARD = 'Dashboard';
$LNG->DEBATE_MODERATOR_SECTION_TITLE = 'Moderators';

/** DEBATE PHASING **/
$LNG->ISSUE_PHASE_CURRENT = 'Current Phase';

$LNG->ISSUE_PHASE_START = 'Start';
$LNG->ISSUE_PHASE_DISCUSS = 'Discuss';
$LNG->ISSUE_PHASE_REDUCE = 'Reduce';
$LNG->ISSUE_PHASE_DECIDE= 'Decide';
$LNG->ISSUE_PHASE_END = 'End';

$LNG->ISSUE_PHASE_DISCUSS_HELP = 'This phase is designed to discuss the current '.$LNG->ISSUE_NAME.'. Add '.$LNG->SOLUTIONS_NAME.', '.$LNG->PROS_NAME.' and '.$LNG->CONS_NAME.' using the embedded forms below. Click the '.$LNG->ARGUMENTS_NAME.' link on '.$LNG->SOLUTIONS_NAME.' to add/view '.$LNG->ARGUMENTS_NAME.'.';
$LNG->ISSUE_PHASE_REDUCE_HELP = 'This phase is designed to reduce the list of ideas. Drag lemons from the basket onto the ideas you like the least. You have 10 lemons to allocate. You can allocate more than one lemon to an idea.';
$LNG->ISSUE_PHASE_DECIDE_HELP = 'This phase is designed to make a final decision on the most supported ideas. You have one possible vote for or against each idea but you cannot vote on your own ideas.';

$LNG->ISSUE_OPEN_TITLE = 'Continuous '.$LNG->DEBATE_NAME.'&nbsp;&nbsp; (default)';
$LNG->ISSUE_OPEN_HELP = 'By default, '.$LNG->DEBATES_NAME.' are always open and ongoing with discussion contributions and voting always available.';

$LNG->ISSUE_TIMING_TITLE = 'Timed '.$LNG->DEBATE_NAME.'&nbsp;&nbsp; (optional)';
$LNG->ISSUE_TIMING_HELP = 'Here you can optionally set the dates on which the '.$LNG->DEBATE_NAME.' starts and ends.<br>By default, discussion contributions and voting will be always available, unless you choose to <b>Phase</b> the debate below.';
$LNG->ISSUE_PHASING_TITLE = 'Timed '.$LNG->DEBATE_NAME.' with Phasing&nbsp;&nbsp; (optional)';
$LNG->ISSUE_PHASING_HELP = 'Here you can optionally phase a Timed '.$LNG->DEBATE_NAME.'. There is always a compulsory phase of discussion where '.$LNG->SOLUTIONS_NAME.', '.$LNG->PROS_NAME.' and '.$LNG->CONS_NAME.' are added to the '.$LNG->DEBATE_NAME.'. There are then two optional phases. The \'Reduce\' phase allows people to allocate lemons to their least liked '.$LNG->SOLUTIONS_NAME.'. These lemon votes are then used to reduce the list of '.$LNG->SOLUTIONS_NAME.'. The \'Decide\' phase allows open voting for and against the final list of '.$LNG->SOLUTIONS_NAME.'.';
$LNG->ISSUE_PHASING_ON = 'Phase this '.$LNG->DEBATE_NAME.'?';

$LNG->FORM_ISSUE_START_END_DATE_ERROR = "The Start On date must be earlier than the End On date";
$LNG->FORM_LABEL_DEBATE_DATES_HINT = "(optional) - Dates from and to which the ".$LNG->DEBATE_NAME." should be open to new contributions. Date formats allowed e.g.: \'14 May 2008\' or \'14-05-2008\'";
$LNG->FORM_LABEL_DEBATE_DATES_TITLE = "Overall Debate Dates";
$LNG->FORM_LABEL_DEBATE_START_DATE = $LNG->DEBATE_NAME." starts on:";
$LNG->FORM_LABEL_DEBATE_END_DATE = "ends on:";

$LNG->FORM_ISSUE_DISCUSSION_START_DATE_ERROR = "The Discussion Start date must lie before the Discussion End date and also between the ".$LNG->DEBATE_NAME." Start On and End On dates, and before the Lemoning and Voting dates, if these are set.";
$LNG->FORM_ISSUE_DISCUSSION_END_DATE_ERROR = "The Discussion end date must be greater than after the ".$LNG->DEBATE_NAME." start date and less than or equal to ".$LNG->DEBATE_NAME." end date, and before the Lemoning and Voting end dates, if these are set.";
$LNG->FORM_LABEL_DISCUSSION_DATES_HINT = "The date until which the ".$LNG->DEBATE_NAME." should be open to new ".$LNG->SOLUTIONS_NAME." and ".$LNG->ARGUMENTS_NAME.". Date formats allowed e.g.: \'14 May 2008\' or \'14-05-2008\'";
$LNG->FORM_LABEL_DISCUSSION_DATES_TITLE = "Discussion Phase";
$LNG->FORM_LABEL_DISCUSSION_START_DATE = "Discussion starts when the ".$LNG->DEBATE_NAME." starts";
$LNG->FORM_LABEL_DISCUSSION_END_DATE = "Discussion ends on:";

$LNG->FORM_ISSUE_LEMONING_START_DATE_ERROR = "The Lemoning Start date must lie between the ".$LNG->DEBATE_NAME." Start On and End On dates, after the Discussion dates, and before the Voting start date, if these are set.";
$LNG->FORM_ISSUE_LEMONING_END_DATE_ERROR = "The Lemoning End date must lie after the Leoming Start Date and between the ".$LNG->DEBATE_NAME." Start On and End On dates, after the Discussion dates, and before the Voting date, if these are set.";
$LNG->FORM_LABEL_LEMONING_DATES_HINT = "(optional) - Dates from which and to which ".$LNG->SOLUTION_NAME." reducing with lemons should be available. Formats allowed e.g.: \'14 May 2008\' or \'14-05-2008\'";
$LNG->FORM_LABEL_LEMONING_DATES_TITLE = "Reduce Idea list with Lemoning";
$LNG->FORM_LABEL_LEMONING_START_DATE = "Idea Reduction starts on:";
$LNG->FORM_LABEL_LEMONING_END_DATE = "ends on:";
$LNG->LEMONING_COUNT_LEFT = 'Lemons left:';
$LNG->LEMONING_CURRENT_CONUNT_LABEL = 'Lemon votes';
$LNG->LEMONING_COUNT_FINISHED = 'Sorry you are out of lemons.';

$LNG->FORM_ISSUE_VOTE_START_DATE_ERROR = "The Voting Start date must lie between the ".$LNG->DEBATE_NAME." Start On and End On dates, before the discussion dates and after the Lemoning dates, if these are set.";
$LNG->FORM_ISSUE_VOTE_START_END_ERROR = "The Voting End date must lie after the Voting Start Date and between the ".$LNG->DEBATE_NAME." Start On and End On dates, before the discussion dates and after the Lemoning dates, if these are set.";
$LNG->FORM_LABEL_VOTING_DATES_HINT = "(optional) - Dates from and to which Voting should be available. The End date is taken from the Issue End date. Formats allowed e.g.: \'14 May 2008\' or \'14-05-2008\'";
$LNG->FORM_LABEL_VOTING_DATES_TITLE = "Voting Phase";
$LNG->FORM_LABEL_VOTING_START_DATE = "Voting starts on:";
$LNG->FORM_LABEL_VOTING_END_DATE = "ends on:";

/** COUNTDOWNS **/
$LNG->NODE_VOTE_COUNTDOWN_START = "Voting On In:";
$LNG->NODE_VOTE_COUNTDOWN_OPEN= "Voting On";

$LNG->NODE_COUNTDOWN_START = "Starts In:";
$LNG->NODE_COUNTDOWN_END = "Ends In:";
$LNG->NODE_COUNTDOWN_CLOSED = "Closed";
$LNG->NODE_COUNTDOWN_OPEN= "Always Open";
$LNG->NODE_COUNTDOWN_DAY = "day";
$LNG->NODE_COUNTDOWN_DAYS = "days";
$LNG->NODE_COUNTDOWN_HOUR = "hr";
$LNG->NODE_COUNTDOWN_HOURS = "hrs";
$LNG->NODE_COUNTDOWN_MINUTE = "min";
$LNG->NODE_COUNTDOWN_MINUTES = "mins";
$LNG->NODE_COUNTDOWN_SECOND = "sec";
$LNG->NODE_COUNTDOWN_SECONDS = "secs";
$LNG->NODE_COUNTDOWN_DISCUSSION_END = "Discussion ends in:";
$LNG->NODE_COUNTDOWN_REDUCING_END = "Reduction ends in:";
$LNG->NODE_COUNTDOWN_DECIDING_END = "Decision making ends in:";

/** VOTE AUTO ADD **/
$LNG->DEBATE_VOTE_ARGUMENT_MESSAGE_PRO = "Why do you think this is a good ".$LNG->SOLUTION_NAME."?";
$LNG->DEBATE_VOTE_ARGUMENT_MESSAGE_CON = "Why do you think this is a bad ".$LNG->SOLUTION_NAME."?";
$LNG->DEBATE_VOTE_ARGUMENT_PLACEHOLDER = "Because:";

/** LOADING MESSAGES **/
$LNG->LOADING_ITEMS = 'Loading items';
$LNG->LOADING_MESSAGE_PRINT_NODE = 'This may take a minute or so depending on the length of the list you are viewing';
$LNG->LOADING_ISSUES = '(Loading '.$LNG->DEBATES_NAME.'...)';
$LNG->LOADING_SOLUTIONS = '(Loading '.$LNG->SOLUTIONS_NAME.'...)';
$LNG->LOADING_PROS = '(Loading '.$LNG->PROS_NAME.'...)';
$LNG->LOADING_CONS = '(Loading '.$LNG->CONS_NAME.'...)';
$LNG->LOADING_RESOURCES = '(Loading '.$LNG->RESOURCES_NAME.'...)';
$LNG->LOADING_DATA = '(Loading data...)';
$LNG->LOADING_COMMENTS = '(Loading '.$LNG->COMMENTS_NAME.'...)';
$LNG->LOADING_USERS = '(Loading '.$LNG->USERS_NAME.'...)';
$LNG->LOADING_GROUPS = '(Loading '.$LNG->GROUPS_NAME.'...)';
$LNG->LOADING_MESSAGE = '(Loading...)';

$LNG->IDEA_ARGUMENTS_LINK = $LNG->ARGUMENTS_NAME;
$LNG->IDEA_ARGUMENTS_HINT = 'View and add '.$LNG->ARGUMENTS_NAME.' on this '.$LNG->SOLUTION_NAME;

$LNG->IDEA_COMMENTS_LINK = $LNG->COMMENTS_NAME;
$LNG->IDEA_COMMENTS_HINT = 'View and add '.$LNG->COMMENTS_NAME.' on this '.$LNG->SOLUTION_NAME;
$LNG->IDEA_COMMENTS_CHILDREN_TITLE = $LNG->COMMENTS_NAME;
$LNG->IDEA_COMMENT_LABEL_TITLE = $LNG->COMMENT_NAME." Title...";
$LNG->IDEA_COMMENT_LABEL_DESC = $LNG->COMMENT_NAME." Description...";
$LNG->FORM_COMMENT_ENTER_SUMMARY_ERROR = 'Please enter a title for your '.$LNG->COMMENT_NAME.' before trying to submit';

$LNG->NODE_EDIT_SOLUTION_ICON_HINT = 'Edit this '.$LNG->SOLUTION_NAME;

/** LIST NAV **/
$LNG->LIST_NAV_PREVIOUS_HINT = 'Previous';
$LNG->LIST_NAV_NO_PREVIOUS_HINT = 'No Previous';
$LNG->LIST_NAV_NEXT_HINT = 'Next';
$LNG->LIST_NAV_NO_NEXT_HINT = 'No Next';
$LNG->LIST_NAV_NO_ITEMS = "You haven't added any yet.";
$LNG->LIST_NAV_TO = 'to';
$LNG->LIST_NAV_NO_SOLUTION = 'There are no '.$LNG->SOLUTIONS_NAME.' to display';
$LNG->LIST_NAV_NO_ITEMS = 'There are no items to display';

$LNG->LIST_NAV_USER_NO_CON = "No ".$LNG->CONS_NAME.' found';
$LNG->LIST_NAV_USER_NO_PRO = "No ".$LNG->PROS_NAME.' found';
$LNG->LIST_NAV_USER_NO_ISSUE = "No ".$LNG->ISSUES_NAME.' found';
$LNG->LIST_NAV_USER_NO_SOLUTION = "No ".$LNG->SOLUTIONS_NAME.' found';
$LNG->LIST_NAV_USER_NO_EVIDENCE = "No ".$LNG->ARGUMENTS_NAME.' found';
$LNG->LIST_NAV_USER_NO_RESOURCE = "No ".$LNG->RESOURCES_NAME.' found';
$LNG->LIST_NAV_USER_NO_COMMENT = "No ".$LNG->COMMENTS_NAME.' found';

$LNG->TAB_RSS_ALT = 'RSS feed';
$LNG->TAB_PRINT_ALT = 'Print';
$LNG->TAB_PRINT_HINT_ISSUE = 'Print '.$LNG->DEBATES_NAME.' list';
$LNG->TAB_PRINT_HINT_SOLUTION = 'Print '.$LNG->SOLUTIONS_NAME.' list';
$LNG->TAB_PRINT_HINT_PRO = 'Print '.$LNG->PROS_NAME.' list';
$LNG->TAB_PRINT_HINT_CON = 'Print '.$LNG->CONS_NAME.' list';
$LNG->TAB_PRINT_HINT_COMMENT = 'Print '.$LNG->COMMENTS_NAME.' list';

$LNG->TAB_PRINT_TITLE_ISSUE = 'Debate Hub: '.$LNG->DEBATES_NAME.' Listing';
$LNG->TAB_PRINT_TITLE_SOLUTION = 'Debate Hub: '.$LNG->SOLUTIONS_NAME.' Listing';
$LNG->TAB_PRINT_TITLE_PRO = 'Debate Hub: '.$LNG->PRO_NAME.' Listing';
$LNG->TAB_PRINT_TITLE_CON = 'Debate Hub: '.$LNG->CON_NAME.' Listing';
$LNG->TAB_PRINT_TITLE_COMMENT = 'Debate Hub: '.$LNG->COMMENTS_NAME.' Listing';

$LNG->TAB_RSS_ISSUE_HINT = 'Get an RSS feed for '.$LNG->ISSUES_NAME;
$LNG->TAB_RSS_SOLUTION_HINT = 'Get an RSS feed for '.$LNG->SOLUTIONS_NAME;
$LNG->TAB_RSS_PRO_HINT = 'Get an RSS feed for '.$LNG->PROS_NAME;
$LNG->TAB_RSS_CON_HINT = 'Get an RSS feed for '.$LNG->CONS_NAME;
$LNG->TAB_RSS_COMMENT_HINT = 'Get an RSS feed for '.$LNG->COMMENTS_NAME;

$LNG->TAB_SEARCH_ISSUE_LABEL = 'Search';
$LNG->TAB_SEARCH_SOLUTION_LABEL = 'Search';
$LNG->TAB_SEARCH_CON_LABEL = 'Search';
$LNG->TAB_SEARCH_PRO_LABEL = 'Search ';
$LNG->TAB_SEARCH_USER_LABEL = 'Search';
$LNG->TAB_SEARCH_COMMENT_LABEL = 'Search';

$LNG->TAB_SEARCH_GO_BUTTON = 'Go';
$LNG->TAB_SEARCH_CLEAR_SEARCH_BUTTON = 'Clear Current Search';

//user
$LNG->TAB_USER_HOME = 'My Home';
$LNG->TAB_USER_ISSUE = 'My '.$LNG->ISSUES_NAME;
$LNG->TAB_USER_SOLUTION = 'My '.$LNG->SOLUTIONS_NAME;
$LNG->TAB_USER_PRO = 'My '.$LNG->PROS_NAME;
$LNG->TAB_USER_CON = 'My '.$LNG->CONS_NAME;
$LNG->TAB_USER_COMMENT = 'My '.$LNG->COMMENTS_NAME;

/** CHANGE PASSWORD PAGE **/
$LNG->CHANGE_PASSWORD_TITLE = 'Change Password';
$LNG->CHANGE_PASSWORD_CURRENT_PASSWORD_ERROR = 'Please enter your current password.';
$LNG->CHANGE_PASSWORD_NEW_PASSWORD_ERROR = 'Please enter your new password.';
$LNG->CHANGE_PASSWORD_CONFIRM_PASSWORD_ERROR = 'Please confirm your new password.';
$LNG->CHANGE_PASSWORD_PASSWORD_INCORRECT_ERROR = 'Your current password is incorrect. Please try again.';
$LNG->CHANGE_PASSWORD_CONFIRM_MISSMATCH_ERROR = "The password and password confirmation don't match.";
$LNG->CHANGE_PASSWORD_PROVIDE_PASSWORD_ERROR = 'You must provide a password.';
$LNG->CHANGE_PASSWORD_SUCCESSFUL_UPDATE = 'Password successfully updated';
$LNG->CHANGE_PASSWORD_BACK_TO_PROFILE = 'Go To My Profile';
$LNG->CHANGE_PASSWORD_GO_TO_MY_HOME = 'Go To My Home Page';
$LNG->CHANGE_PASSWORD_CURRENT_PASSWORD_LABEL = 'Current Password:';
$LNG->CHANGE_PASSWORD_NEW_PASSWORD_LABEL = 'New Password:';
$LNG->CHANGE_PASSWORD_CONFIRM_PASSWORD_LABEL = 'Confirm Password:';
$LNG->CHANGE_PASSWORD_UPDATE_BUTTON = 'Update';

/** FORGOT PASSWORD PAGE **/
$LNG->FORGOT_PASSWORD_TITLE = 'Forgotten password?';
$LNG->FORGOT_PASSWORD_HEADER_MESSAGE = "Please enter your email address and we'll send you a link where you can reset your password.";
$LNG->FORGOT_PASSWORD_EMAIL_NOT_FOUND_ERROR = 'Email address not found';
$LNG->FORGOT_PASSWORD_EMAIL_SUMMARY = 'Reset Debate Hub password';
$LNG->FORGOT_PASSWORD_EMAIL_SENT_MESSAGE = 'An email has been sent for you to reset your password.';
$LNG->FORGOT_PASSWORD_EMAIL_LABEL = 'Email:';
$LNG->FORGOT_PASSWORD_SUBMIT_BUTTON = 'Submit';

/** LOGIN PAGE **/
$LNG->LOGIN_TITLE = 'Sign In to the '.$CFG->SITE_TITLE;
$LNG->LOGIN_INVALID_ERROR = 'Invalid Sign In, please try again.';
$LNG->LOGIN_NOT_REGISTERED_MESSAGE = 'Not yet registered?';
$LNG->LOGIN_INVITIATION_ONLY_MESSAGE = 'Registration for this site is currently by invitation only.';
$LNG->LOGIN_SIGNUP_OPEN_LINK = 'Sign Up!';
$LNG->LOGIN_SIGNUP_REGISTER_LINK = 'Sign Up!';
$LNG->LOGIN_USERNAME_LABEL = 'Email:';
$LNG->LOGIN_PASSWORD_LABEL = 'Password:';
$LNG->LOGIN_LOGIN_BUTTON = 'Login';
$LNG->LOGIN_FORGOT_PASSWORD_LINK = 'Forgotten password?';
$LNG->LOGIN_FORGOT_PASSWORD_MESSAGE_PART1 = 'Forgotten password? Please';
$LNG->LOGIN_FORGOT_PASSWORD_MESSAGE_PART2 = 'Contact Us';
$LNG->LOGIN_PASSWORD_LENGTH = 'Your password must be at least 8 characters long.';
$LNG->LOGIN_PASSWORD_LENGTH_UPDATE = 'For added security we now enforce a minimum password length of 8 characters on new accounts.<br>We recommend to existing account holders with passwords under 8 characters in length that they reset their passwords now.<br>Thank you for your co-operation in increasing security on this site.';
$LNG->LOGIN_SOCIAL_SIGNON = 'Or use another service';

/** PROFILE PAGE **/
$LNG->PROFILE_TITLE = 'Edit Profile';
$LNG->PROFILE_CHANGE_PASSWORD_LINK = '(Change Password)';
$LNG->PROFILE_INVALID_EMAIL_ERROR = "Please enter a valid email address.";
$LNG->PROFILE_EMAIL_IN_USE_ERROR = "That email address is already in use, please select another one.";
$LNG->PROFILE_FULL_NAME_ERROR = "Please enter your full name.";
$LNG->PROFILE_HOMEPAGE_URL_ERROR = "Please enter a full valid URL (including 'http://') for your homepage.";
$LNG->PROFILE_UPDATE_BUTTON = 'Update';
$LNG->PROFILE_DESC_LABEL = 'Description:';
$LNG->PROFILE_PHOTO_CURRENT_LABEL = 'Current photo:';
$LNG->PROFILE_PHOTO_REPLACE_LABEL = 'Replace photo with:';
$LNG->PROFILE_PHOTO_LABEL = 'Photo:';
$LNG->PROFILE_LOCATION = 'Location: (town/city)';
$LNG->PROFILE_COUNTRY = 'Country...';
$LNG->PROFILE_HOMEPAGE = 'Homepage:';
$LNG->PROFILE_EMAIL_VALIDATE_TEXT = 'Validate Email Address';
$LNG->PROFILE_EMAIL_VALIDATE_HINT = 'Your email address has not been validated. If you want to use Social Sign On you will need to validate you own this email address.';
$LNG->PROFILE_EMAIL_VALIDATE_MESSAGE = 'You have been sent an email to validate that you own the email address on this user account.';
$LNG->PROFILE_EMAIL_VALIDATE_COMPLETE = 'This email address has been validated.';
$LNG->PROFILE_EMAIL_CHANGE_CONFIRM = 'You have changed your email address.\nThis new email address will need to be verified.\n\nYour user account will now be locked, you will be logged out and you will be sent a new account validation email.\nPlease click on the link in the email to complete the change of email address.\n\nAre you sure you want to proceed?';

/*******************************************************************************************************/

/** ODD **/
$LNG->RESET_INVALID_MESSAGE = 'Invalid password reset code';
$LNG->POPUPS_BLOCK = 'You appear to have blocked popup windows.\n\n Please alter your browser settings to allow this site to open popup windows.';

/** TABS **/
//main
$LNG->TAB_HOME = 'Home';
$LNG->TAB_ISSUE = $LNG->DEBATES_NAME;
$LNG->TAB_GROUP = $LNG->GROUPS_NAME;
$LNG->TAB_USER = $LNG->USERS_NAME;

/** ERROR MESSAGES */
$LNG->DATABASE_CONNECTION_ERROR = 'Could not connect to database - please check the server configuration.';
$LNG->ITEM_NOT_FOUND_ERROR = 'Item not found';

/** BUTTONS AND LINK HINTS **/
$LNG->EDIT_BUTTON_TEXT = 'Edit';
$LNG->EDIT_BUTTON_HINT_ISSUE = 'Edit this '.$LNG->ISSUE_NAME;
$LNG->DELETE_BUTTON_ALT = 'Delete';
$LNG->DELETE_BUTTON_HINT = 'Delete this item';
$LNG->NO_DELETE_BUTTON_ALT = 'Delete unavailable';
$LNG->NO_DELETE_BUTTON_HINT = 'You cannot delete this item. Someone else has connected to it';

/** USER PAGE **/
$LNG->USER_FOLLOW_HINT = 'Follow this person...';
$LNG->USER_FOLLOW_BUTTON = 'Follow';
$LNG->USER_UNFOLLOW_HINT = 'Unfollow this person...';
$LNG->USER_UNFOLLOW_BUTTON = 'Unfollow';

$LNG->USER_RSS_HINT = 'Get an RSS feed for ';
$LNG->USER_RSS_BUTTON = 'RSS Feed';

/** SORTS **/
$LNG->SORT_BY = 'Sort by';
$LNG->SORT_ASC = 'Ascending';
$LNG->SORT_DESC = 'Descending';
$LNG->SORT_CREATIONDATE = 'Creation Date';
$LNG->SORT_MODDATE = 'Modification Date';
$LNG->SORT_MEMBERS = 'Member Count';
$LNG->SORT_TITLE = 'Title';
$LNG->SORT_NAME = 'Name';
$LNG->SORT_CONNECTIONS = 'Connections';
$LNG->SORT_VOTES = 'Votes';
$LNG->SORT_RANDOM = "Random";

/** SEARCH RESULTS PAGE **/
$LNG->SEARCH_TITLE_ERROR = 'Search Results';
$LNG->SEARCH_ERROR_EMPTY = 'You must enter something to search for.';
$LNG->SEARCH_TITLE = 'Search results for: ';
$LNG->SEARCH_BACKTOTOP = 'back to top';
$LNG->SEARCH_BACKTOTOP_IMG_ALT = 'Up';

/** OVERVIEW TITLES **/
$LNG->OVERVIEW_RESOURCE_MOSTCONNECTED_TITLE = 'Most Connected '.$LNG->RESOURCES_NAME;

/** FORM LABELS, BUTTONS AND TEXT **/

$LNG->CONDITIONS_REGISTER_FORM_TITLE = 'Terms and Conditions of use';
$LNG->CONDITIONS_REGISTER_FORM_MESSAGE = 'By registering to be a member of this Hub you agree to the Terms and Conditions of this Hub as written in our <a href="'.$CFG->homeAddress.'ui/pages/conditionsofuse.php">Terms of Use</a>.';
$LNG->CONDITIONS_AGREE_FORM_REGISTER_MESSAGE = 'I agree to the terms and conditions of use of this Hub';
$LNG->CONDITIONS_AGREE_FAILED_MESSAGE = 'You must agree to the terms and conditions of use of this Hub before you can register.';

/** OTHER FORMS **/
$LNG->FORM_REGISTER_OPEN_TITLE = 'Register';
$LNG->FORM_REGISTER_REQUEST_TITLE = 'Registration Request';
$LNG->FORM_REGISTER_ADMIN_TITLE = 'Register a New User';
$LNG->FORM_REGISTER_EMAIL = 'Email:';
$LNG->FORM_REGISTER_DESC = 'Description:';
$LNG->FORM_REGISTER_PASSWORD = 'Password:';
$LNG->FORM_REGISTER_PASSWORD_CONFIRM = 'Confirm Password:';
$LNG->FORM_REGISTER_NAME = 'Full name:';
$LNG->FORM_REGISTER_INTEREST = 'Interest in this Debate Hub:';
$LNG->FORM_REGISTER_LOCATION = 'Location: (town/city)';
$LNG->FORM_REGISTER_COUNTRY = 'Country...';
$LNG->FORM_REGISTER_HOMEPAGE = 'Homepage:';
$LNG->FORM_REGISTER_NEWSLETTER = 'Newsletter:';
$LNG->FORM_REGISTER_CAPTCHA = 'Are you human?';
$LNG->FORM_REGISTER_SUBMIT_BUTTON = 'Register';
$LNG->FORM_REGISTER_REQUEST_DESC = 'Personal Description:';
$LNG->FORM_REGISTER_IMAGE_ERROR = "Please edit your profile and upload a different image once you complete your registration.";

$LNG->REGISTRATION_SUCCESSFUL_TITLE = 'Registration Successful';
$LNG->REGISTRATION_SUCCESSFUL_MESSAGE = 'You will shortly receive an email. You must click on the link inside it to validate your email address and complete your Hub Registration.';
$LNG->REGISTRATION_COMPLETE_TITLE = 'Registration Complete';
$LNG->REGISTRATION_FAILED = 'Your registration could not be completed. Please try registering again';
$LNG->REGISTRATION_FAILED_INVALID = 'Your registration could not be completed as the Registration key was invalid for the given user. Please try registering again';
$LNG->REGISTRATION_SUCCESSFUL_LOGIN = "You can now <a href='".$CFG->homeAddress."ui/pages/login.php'>log in</a>";

$LNG->REGISTRATION_REQUEST_SUCCESSFUL_TITLE = 'Registration Request Recieved';
$LNG->REGISTRATION_REQUEST_SUCCESSFUL_MESSAGE = 'Thank you for your interest in contributing to this Debate Hub.<br>Your registration request has been sent and you will be contacted shortly.';

$LNG->REGISTRATION_REQUEST_SUCCESSFUL_TITLE_ADMIN = 'Registration of new user successful';
$LNG->REGISTRATION_REQUEST_SUCCESSFUL_MESSAGE_ADMIN = "An email has been sent to the new User with their Sign In details";

$LNG->FORM_HEADER_MESSAGE = 'Please be aware that all data you enter here will be publically viewable on this site by other users.';
$LNG->FORM_REQUIRED_FIELDS_MESSAGE_PART1 = '(fields with a';
$LNG->FORM_REQUIRED_FIELDS_MESSAGE_PART2 = 'are compulsory';
$LNG->FORM_REQUIRED_FIELDS_MESSAGE_PART3 = ', unless they are in an optional subsection which you are not completing)';

$LNG->FORM_REQUIRED_FIELDS = 'indicates required field';
$LNG->FORM_LABEL_SUMMARY = 'Summary:';
$LNG->FORM_LABEL_DESC = 'Description:';
$LNG->FORM_LABEL_URL = 'Url:';
$LNG->FORM_LABEL_NAME = 'Name:';

$LNG->FORM_DESC_PLAIN_TEXT_LINK = 'Plain text';
$LNG->FORM_DESC_PLAIN_TEXT_HINT = 'Switch to a plain text. Formatting will be lost.';
$LNG->FORM_DESC_HTML_TEXT_LINK = 'Formatting';
$LNG->FORM_DESC_HTML_TEXT_HINT = 'Show formatting toolbar.';
$LNG->FORM_DESC_HTML_SWITCH_WARNING = 'Are you sure you want to switch to plain text? Warning: All Formatting will be lost.';

$LNG->FORM_BUTTON_REMOVE = 'remove';
$LNG->FORM_BUTTON_ADD_ANOTHER = 'add another';
$LNG->FORM_BUTTON_ADD = 'Add';
$LNG->FORM_BUTTON_PUBLISH = 'Publish';
$LNG->FORM_BUTTON_CANCEL = 'Cancel';
$LNG->FORM_BUTTON_CLOSE = 'Close';
$LNG->FORM_BUTTON_CONTINUE = 'Continue';
$LNG->FORM_BUTTON_PRINT_PAGE = 'Print Page';

$LNG->FORM_ERROR_NOT_ADMIN = 'You do not have permissions to view this page';
$LNG->FORM_ERROR_MESSAGE = 'The following problems were found, please try again';
$LNG->FORM_ERROR_MESSAGE_LOGIN = 'The following issues were found with your sign in attempt:';
$LNG->FORM_ERROR_MESSAGE_REGISTRATION = 'The following problems were found with your registration, please try again:';
$LNG->FORM_ERROR_NOT_ADMIN = "Sorry you need to be an administrator to access this page";
$LNG->FORM_ERROR_PASSWORD_MISMATCH = "The password and password confirmation don't match. Please try again.";
$LNG->FORM_ERROR_PASSWORD_MISSING = "Please enter a password.";
$LNG->FORM_ERROR_NAME_MISSING = 'Please enter your full name.';
$LNG->FORM_ERROR_INTEREST_MISSING = "Please enter your interest in having an account with us.";
$LNG->FORM_ERROR_URL_INVALID = "Please enter a full valid URL (including 'http://').";
$LNG->FORM_ERROR_EMAIL_INVALID = "Please enter a valid email address.";
$LNG->FORM_ERROR_EMAIL_USED = "This email address is already in use, please either Sign In or select a different email address.";
$LNG->FORM_ERROR_CAPTCHA_INVALID = "The reCAPTCHA wasn't entered correctly. Please try it again.";

//Activity Forms
$LNG->FORM_ACTIVITY_HEADING = 'Recent Activity For';
$LNG->FORM_ACTIVITY_TABLE_HEADING_DATE = 'Date';
$LNG->FORM_ACTIVITY_TABLE_HEADING_TYPE = 'Type';
$LNG->FORM_ACTIVITY_TABLE_HEADING_DONEBY = 'Done By';
$LNG->FORM_ACTIVITY_TABLE_HEADING_ACTION = 'Action';
$LNG->FORM_ACTIVITY_TABLE_HEADING_ITEM = 'Item';
$LNG->FORM_ACTIVITY_ACTION_STARTED_FOLLOWING = 'started following';
$LNG->FORM_ACTIVITY_ACTION_STARTED_FOLLOWING_ITEM = 'started following this item';
$LNG->FORM_ACTIVITY_ACTION_VOTE_PROMOTED = 'promoted';
$LNG->FORM_ACTIVITY_ACTION_VOTE_DEMOTED = 'demoted';
$LNG->FORM_ACTIVITY_ACTION_VOTE_PROMOTED_ITEM = 'promoted this item';
$LNG->FORM_ACTIVITY_ACTION_VOTE_DEMOTED_ITEM = 'demoted this item';
$LNG->FORM_ACTIVITY_ACTION_ADDED = 'added';
$LNG->FORM_ACTIVITY_ACTION_EDITED = 'edited';
$LNG->FORM_ACTIVITY_ACTION_ADDED_ITEM = 'added this item';
$LNG->FORM_ACTIVITY_ACTION_EDITED_ITEM = 'edited this item';
$LNG->FORM_ACTIVITY_ACTION_ASSOCIATED = 'associated';
$LNG->FORM_ACTIVITY_ACTION_DESOCIATED = 'removed association';
$LNG->FORM_ACTIVITY_ACTION_ADDED_RESOURCE = "added the ".$LNG->RESOURCE_NAME;
$LNG->FORM_ACTIVITY_ACTION_ADDED_EVIDENCE_PRO = "added Supporting ".$LNG->ARGUMENT_NAME;
$LNG->FORM_ACTIVITY_ACTION_ADDED_EVIDENCE_CON = "added Counter ".$LNG->ARGUMENT_NAME;
$LNG->FORM_ACTIVITY_ACTION_ADDED_EVIDENCE = "associated this with the ".$LNG->ARGUMENT_NAME;
$LNG->FORM_ACTIVITY_ACTION_ASSOCIATED_WITH = "associated this with the";
$LNG->FORM_ACTIVITY_ACTION_REMOVED = "removed";
$LNG->FORM_ACTIVITY_ACTION_REMOVED_RESOURCE = "removed the ".$LNG->RESOURCE_NAME;
$LNG->FORM_ACTIVITY_ACTION_REMOVED_EVIDENCE = "removed the ".$LNG->ARGUMENT_NAME;
$LNG->FORM_ACTIVITY_ACTION_REMOVED_ASSOCIATION = "removed association with";
$LNG->FORM_ACTIVITY_ACTION_INDICATED_THAT = 'indicated that';
$LNG->FORM_ACTIVITY_ACTION_STRONG_SOLUTION = 'was a strong '.$LNG->SOLUTION_NAME.' for';
$LNG->FORM_ACTIVITY_ACTION_CONVINCING_EVIDENCE = 'was convincing '.$LNG->ARGUMENT_NAME.' for';
$LNG->FORM_ACTIVITY_ACTION_SOUND_EVIDENCE = 'was sound '.$LNG->ARGUMENT_NAME.' for';
$LNG->FORM_ACTIVITY_ACTION_PROMOTED = 'should be promoted against';
$LNG->FORM_ACTIVITY_ACTION_WEAK_SOLUTION = 'was a weak '.$LNG->SOLUTION_NAME.' for';
$LNG->FORM_ACTIVITY_ACTION_UNCONVINCING_EVIDENCE = 'was unconvincing '.$LNG->ARGUMENT_NAME.' for';
$LNG->FORM_ACTIVITY_ACTION_UNSOUND_EVIDENCE = 'was unsound '.$LNG->ARGUMENT_NAME.' for';
$LNG->FORM_ACTIVITY_ACTION_DEMOTED = 'should be demoted against';
$LNG->FORM_ACTIVITY_LABEL_WITH = 'with';
$LNG->FORM_ACTIVITY_LABEL_FROM = 'from';
$LNG->FORM_ACTIVITY_PROBLEM_MESSAGE = 'The following problems were found retrieving the activities data: ';

//Issue
$LNG->FORM_ISSUE_TITLE_ADD = 'Add a new '.$LNG->DEBATE_NAME;
$LNG->FORM_ISSUE_TITLE_EDIT = 'Edit this '.$LNG->DEBATE_NAME;
$LNG->FORM_ISSUE_ENTER_SUMMARY_ERROR = 'Please enter an '.$LNG->ISSUE_NAME.' summary before trying to publish';
$LNG->FORM_ISSUE_CREATE_ERROR_MESSAGE = 'There was an problem creating the '.$LNG->ISSUE_NAME.':';
$LNG->FORM_ISSUE_HEADING_MESSAGE = 'Add a question you are investigating or a '.$LNG->ISSUE_NAME.' you think the community has to tackle.';
$LNG->FORM_ISSUE_LABEL_SUMMARY = $LNG->ISSUE_NAME.' Summary:';
$LNG->FORM_ISSUE_NOT_FOUND = 'The required '.$LNG->ISSUE_NAME.' could not be found';

/** FORM ROLLOVER HINTS **/
// Issues
$LNG->ISSUE_SUMMARY_FORM_HINT = '(compulsory) - Enter an new '.$LNG->ISSUE_NAME.' summary. This will form the '.$LNG->DEBATE_NAME.' title displayed in lists and should describe the problem or subject the debate is on.';
$LNG->ISSUE_DESC_FORM_HINT = '(optional) - Enter a longer description of the '.$LNG->ISSUE_NAME.' that you wish to debate';

/**** EMAIL TEXT *****/
$LNG->WELCOME_REGISTER_OPEN_SUBJECT = "Welcome to the ".$CFG->SITE_TITLE;
$LNG->WELCOME_REGISTER_OPEN_BODY = 'Thank you for registering with us.<br><br>For more information about what the Debate Hub is, why not read our <a href="'.$CFG->homeAddress.'ui/pages/about.php">about page</a>.<br>For help in getting started using the hub why not visit our <a href="'.$CFG->homeAddress.'help/">help page</a>.<br>Why not start using the <a href="'.$CFG->homeAddress.'">'.$CFG->SITE_TITLE.'</a> today.';

$LNG->VALIDATE_REGISTER_SUBJECT = "Completing your registration on ".$CFG->SITE_TITLE;

$LNG->WELCOME_REGISTER_REQUEST_SUBJECT = "Registration request for the ".$CFG->SITE_TITLE;
$LNG->WELCOME_REGISTER_REQUEST_BODY = 'Thank you for requesting an account on the <a href="'.$CFG->homeAddress.'">'.$CFG->SITE_TITLE.'</a>.<br>This is to acknowledge that we have received your request.<br>We will attempt to process your request within 24 hours, but at busy times it may take longer.<br>You will receive a further email with your Sign In details, if your request is successful.<br><br>Thanks again for your interest.';
$LNG->WELCOME_REGISTER_REQUEST_BODY_ADMIN = "A new User has requested an account. Please use the Admin area to accept or reject this new User.";

$LNG->WELCOME_REGISTER_CLOSED_SUBJECT = "Registration on the ".$CFG->SITE_TITLE;

$LNG->VALIDATE_GROUP_JOIN_SUBJECT = "Group Join Request from ".$CFG->SITE_TITLE;

/*** NODE LISTINGS AND ITEMS ***/
$LNG->NODE_DETAIL_BUTTON_HINT = 'Go to full information on this item.';
$LNG->NODE_TYPE_ICON_HINT = 'View original image';
$LNG->NODE_EXPLORE_BUTTON_TEXT = 'Explore >>';
$LNG->NODE_EXPLORE_BUTTON_HINT = 'Click to show/hide where you can go and see more information and activities around this item';

$LNG->NODE_VOTE_FOR_ICON_ALT = 'Voting For';
$LNG->NODE_VOTE_AGAINST_ICON_ALT = 'Voting Against';
$LNG->NODE_VOTE_REMOVE_HINT = 'Unset...';
$LNG->NODE_VOTE_FOR_ADD_HINT = 'Promote this...';
$LNG->NODE_VOTE_FOR_SOLUTION_HINT = 'Strong '.$LNG->SOLUTION_NAME.' for this';
$LNG->NODE_VOTE_FOR_EVIDENCE_SOLUTION_HINT = 'Convincing '.$LNG->ARGUMENT_NAME.' for this';
$LNG->NODE_VOTE_AGAINST_ADD_HINT = 'Demote this...';
$LNG->NODE_VOTE_AGAINST_SOLUTION_HINT = 'Weak '.$LNG->SOLUTION_NAME.' for this';
$LNG->NODE_VOTE_AGAINST_EVIDENCE_SOLUTION_HINT = 'Unconvincing '.$LNG->ARGUMENT_NAME.' for this';
$LNG->NODE_VOTE_FOR_LOGIN_HINT = 'Sign In to Promote this';
$LNG->NODE_VOTE_AGAINST_LOGIN_HINT = 'Sign In to Demote this';
$LNG->NODE_VOTE_MENU_TEXT = 'Vote:';
$LNG->NODE_VOTE_OWN_HINT = 'You cannot vote on your own items';

$LNG->NODE_ADDED_ON = 'Added on:';
$LNG->NODE_URL_HEADING = 'Url:';

$LNG->NODE_DISCONNECT_CHECK_MESSAGE_PART1 = 'Are you sure you want to disconnect';
$LNG->NODE_DISCONNECT_CHECK_MESSAGE_PART2 = 'from';
$LNG->NODE_DISCONNECT_CHECK_MESSAGE_PART3 = '?';
$LNG->NODE_DELETE_CHECK_MESSAGE = 'Are you sure you want to delete the';
$LNG->NODE_DELETE_CHECK_MESSAGE_ITEM = 'item';
$LNG->NODE_FOLLOW_ITEM_HINT = 'Follow this item...';
$LNG->NODE_UNFOLLOW_ITEM_HINT = 'Unfollow this item...';

/** BUILDER TOOLBAR **/
$LNG->BUILDER_GOTO_HOME_SITE_HINT = "go to ".$CFG->SITE_TITLE." Website";

/** USERS **/
$LNG->USERS_UNFOLLOW = 'Unfollow this person...';
$LNG->USERS_FOLLOW = 'Follow this person...';
$LNG->USERS_FOLLOW_ICON_ALT = 'Follow';
$LNG->USERS_STARTED_FOLLOWING_ON = 'Started following on:';
$LNG->USERS_LAST_LOGIN = 'Last Sign In:';
$LNG->USERS_LAST_ACTIVE = 'Last Active:';
$LNG->USERS_DATE_JOINED = 'Date Joined:';

/** USER HOME PAGE **/
$LNG->USER_HOME_LOCATION_LABEL = 'Location:';
$LNG->USER_HOME_TABLE_ITEM_TYPE = 'Item Type';
$LNG->USER_HOME_TABLE_CREATION_COUNT = 'Count';
$LNG->USER_HOME_TABLE_VIEW = 'View';
$LNG->USER_HOME_TABLE_TYPE = 'Type';
$LNG->USER_HOME_TABLE_NAME = 'Name';
$LNG->USER_HOME_TABLE_ACTION = 'Action';
$LNG->USER_HOME_TABLE_PICTURE = 'Picture';
$LNG->USER_HOME_PROFILE_HEADING = 'Profile';
$LNG->USER_HOME_VIEW_CONTENT_HEADING = 'Content Creation Summary';
$LNG->USER_HOME_VIEW_ACTIVITIES_LINK = "( View all Activity for this person )";
$LNG->USER_HOME_VIEW_ACTIVITIES_HINT =  "This opens a new window and may take some time to load depending on the volume of activity by that person";
$LNG->USER_HOME_FOLLOWING_HEADING = 'Following';
$LNG->USER_HOME_ACTIVITY_ALERT = 'Send email Alert of New Activity';
$LNG->USER_HOME_EMAIL_HOURLY = 'Hourly';
$LNG->USER_HOME_EMAIL_DAILY = 'Daily';
$LNG->USER_HOME_EMAIL_WEEKLY = 'Weekly';
$LNG->USER_HOME_EMAIL_MONTHLY = 'Monthly';
$LNG->USER_HOME_PERSON_LABEL = 'Person';
$LNG->USER_HOME_UNFOLLOW_LINK = 'Unfollow';
$LNG->USER_HOME_EXPLORE_LINK = 'Explore';
$LNG->USER_HOME_ACTIVITY_LINK = 'Activity';
$LNG->USER_HOME_NOT_FOLLOWING_MESSAGE = 'Not following any people or items yet.';
$LNG->USER_HOME_FOLLOWERS_HEADING = 'Followers';
$LNG->USER_HOME_NO_FOLLOWERS_MESSAGE = 'No followers yet.';
$LNG->USER_HOME_ANALYTICS_LINK_TEXT = '( View Analytics for this person )';
$LNG->USER_HOME_ANALYTICS_LINK_HINT =  "This opens a new window and may take some time to load depending on the volume of activity by that person";

/** MAIN TAB SCREENS - TABBERLIB **/
$LNG->TAB_ADD_GROUP_LINK = 'Add '.$LNG->GROUP_NAME;
$LNG->TAB_ADD_ISSUE_LINK = 'Add '.$LNG->DEBATE_NAME;
$LNG->TAB_ADD_ISSUE_HINT = 'Add '.$LNG->DEBATE_NAME;
$LNG->TAB_ADD_GROUP_HINT = 'Add '.$LNG->GROUP_NAME;

/** HOMEPAGE **/
$LNG->HOMEPAGE_TITLE = '';

$LNG->HOMEPAGE_FIRST_PARA = '<b>'.$CFG->SITE_TITLE.'</b> is an open, online, collaborative tool to support collective ideation, deliberation and democratic decision making.';
$LNG->HOMEPAGE_FIRST_PARA .= ' It allows you to set up staged online challenges/debates, that can be launched and addressed in the following way: users can collectively propose new ideas to tackle open challenges/debates; they can discuss and argument in favour or against the proposed ideas; they can then reduce and select the most promising ideas by building on the analysis of the arguments raised in favour and against each idea; they can finally vote for the most promising ideas that are worth pursuing further.';

$LNG->HOMEPAGE_SECOND_PARA_PART2 = $CFG->SITE_TITLE.' has a very simple User Interface, which may look like a common web forum, but is enhanced by a semantic data model. This allows a better informed idea selection support as well as the development of advanced analytics on your group, debate and challenge data, which are delivered to you with a visualisation dashboard.</p>';
$LNG->HOMEPAGE_SECOND_PARA_PART2 .= '<p>'.$CFG->SITE_TITLE.' also has moderator features (such as idea merging, splitting and moving) to help reducing idea duplication in online discussion, which is one of the main weaknesses of existing online ideation and debate platforms.</p>';
$LNG->HOMEPAGE_SECOND_PARA_PART2 .= '<p>At present '.$CFG->SITE_TITLE.' has 500 users, from 6 countries, 40 community groups and 100 debates. Users indicate that '.$CFG->SITE_TITLE.' is a very useful platform in supporting and effectively managing collaborative decision-making.';

//$LNG->HOMEPAGE_FIRST_PARA = '<b>'.$CFG->SITE_TITLE.'</b> gives online communities a place to: i. raise issues; ii. share ideas; iii. debate the pros and cons; iv. and vote contributions in order to collectively organize and progress good ideas forward.  DebateHub is distinctive in its use of advanced analytics to show you the best argued ideas, and visualisations of your community.';
//$LNG->HOMEPAGE_SECOND_PARA_PART2 = '<b>For Community Members:</b><br>Debate Hub helps you share new ideas, but also opens them up for debate. This helps you make the case for your viewpoint, and identify the most robust ideas in all the noise.';
//$LNG->HOMEPAGE_SECOND_PARA_PART2 .= '<p><b>For Community Managers:</b><br>Debate Hub provides new tools to organise your community\'s contributions, reduces idea duplication, and supports content analysis and summarisation. The analytics dashboard onto your groups helps you spot connections between people and ideas, detect gaps in knowledge, discover new patterns of (dis)agreement, and produce visual summaries of the community debate.</p>';
//$LNG->HOMEPAGE_SECOND_PARA_PART2 .= 'To do so you can:';
//$LNG->HOMEPAGE_SECOND_PARA_PART2 .= '<ul>';
//$LNG->HOMEPAGE_SECOND_PARA_PART2 .= '<li>Map your ideas - add '.$LNG->ISSUES_NAME.', ';
//$LNG->HOMEPAGE_SECOND_PARA_PART2 .= $LNG->SOLUTIONS_NAME.', ';
//$LNG->HOMEPAGE_SECOND_PARA_PART2 .= $LNG->ARGUMENTS_NAME.' and '.$LNG->RESOURCES_NAME.'</li></ul>';

$LNG->FOOTER_PARTNERSHIP_LABEL = 'In Partnership with';
$LNG->FOOTER_DEVELOPED_BY = 'Developed By';
$LNG->HOMEPAGE_KEEP_READING = 'keep reading';
$LNG->HOMEPAGE_READ_LESS = 'read less';

/** WIDGETS **/
$LNG->WIDGET_FOLLOW_SIGNIN_HINT = 'Sign In to follow this entry';
$LNG->WIDGET_NONE_FOUND_PART1 = 'No';
$LNG->WIDGET_NONE_FOUND_PART2 = 'added yet';
$LNG->WIDGET_NO_RESULTS_FOUND = 'No results found';
$LNG->WIDGET_NO_GROUPS_FOUND = 'No '.$LNG->GROUPS_NAME.' found';

/** ADMIN AREA **/

$LNG->ADMIN_TITLE = "Administration Area";
$LNG->ADMIN_BUTTON_HINT = "This launches in a new window";
$LNG->ADMIN_STATS_BUTTON_HINT = "Go to the Site Analytics pages";
$LNG->ADMIN_REGISTER_NEW_USER_LINK = 'Register a New User';
$LNG->ADMIN_NOT_ADMINISTRATOR_MESSAGE = 'Sorry you need to be an administrator to access this page';
$LNG->ADMIN_MANAGE_USERS_DELETE_ERROR = 'There was an issue deleting the user with the id:';

$LNG->NODE_NEWS_POSTED_ON = 'Posted on';
$LNG->ADMIN_MANAGE_NEWS_LINK = "Manage ".$LNG->NEWSS_NAME;
$LNG->ADMIN_MANAGE_NEWS_DELETE_ERROR = 'There was an issue deleting the '.$LNG->NEWS_NAME.' with the id:';
$LNG->ADMIN_NEWS_MISSING_NAME_ERROR = 'You must enter a '.$LNG->NEWS_NAME.' title.';
$LNG->ADMIN_NEWS_ID_ERROR  = 'Error passing '.$LNG->NEWS_NAME.' id.';
$LNG->ADMIN_NEWS_DELETE_QUESTION_PART1 = 'Are you sure you want to delete the item'.$LNG->NEWS_NAME;
$LNG->ADMIN_NEWS_DELETE_QUESTION_PART2 = '?';
$LNG->ADMIN_NEWS_DELETE_SUCCESS_PART1 = $LNG->NEWS_NAME;
$LNG->ADMIN_NEWS_DELETE_SUCCESS_PART2 = 'has now been deleted.';
$LNG->ADMIN_NEWS_TITLE = "Manage ".$LNG->NEWSS_NAME;
$LNG->ADMIN_NEWS_ADD_NEW_LINK = 'Add '.$LNG->NEWS_NAME;
$LNG->ADMIN_NEWS_NAME_LABEL = 'Title:';
$LNG->ADMIN_NEWS_DESC_LABEL = 'Description:';
$LNG->ADMIN_NEWS_TITLE_HEADING = $LNG->NEWS_NAME;
$LNG->ADMIN_NEWS_ACTION_HEADING = 'Action';
$LNG->ADMIN_NEWS_EDIT_LINK = 'edit';
$LNG->ADMIN_NEWS_DELETE_LINK = 'delete';

$LNG->ADMIN_CRON_FOLLOW_USER_ACTIVITY_MESSAGE = 'There has been activity for';
$LNG->ADMIN_CRON_FOLLOW_SEE_ACTIVITY_LINK = 'See activity';
$LNG->ADMIN_CRON_FOLLOW_ACTIVITY_FOR = 'Activity for';
$LNG->ADMIN_CRON_FOLLOW_EXPLORE_LINK = 'Explore';
$LNG->ADMIN_CRON_FOLLOW_ON_THE = 'On the';
$LNG->ADMIN_CRON_FOLLOW_THIS_ITEM = 'this item';
$LNG->ADMIN_CRON_FOLLOW_STARTED = 'started following';
$LNG->ADMIN_CRON_FOLLOW_PROMOTED = 'promoted';
$LNG->ADMIN_CRON_FOLLOW_DEMOTED = 'demoted';
$LNG->ADMIN_CRON_FOLLOW_ADDED = 'added';
$LNG->ADMIN_CRON_FOLLOW_EDITED = 'edited';
$LNG->ADMIN_CRON_FOLLOW_ADDED_RESOURCE = 'added the '.$LNG->RESOURCE_NAME;
$LNG->ADMIN_CRON_FOLLOW_ADDED_SUPPORTING_EVIDENCE = 'added  '.$LNG->PRO_NAME;
$LNG->ADMIN_CRON_FOLLOW_ADDED_COUNTER_EVIDENCE = 'added '.$LNG->CON_NAME;
$LNG->ADMIN_CRON_FOLLOW_ASSOCIATED_EVIDENCE = 'associated this with the '.$LNG->ARGUMENT_NAME;
$LNG->ADMIN_CRON_FOLLOW_ASSOCIATED_WITH = 'associated this with the';
$LNG->ADMIN_CRON_FOLLOW_REMOVED = 'removed';
$LNG->ADMIN_CRON_FOLLOW_REMOVED_RESOURCE = 'removed the '.$LNG->RESOURCE_NAME;
$LNG->ADMIN_CRON_FOLLOW_REMOVED_SUPPORTING_EVIDENCE = 'removed  '.$LNG->PRO_NAME;
$LNG->ADMIN_CRON_FOLLOW_REMOVED_COUNTER_EVIDENCE = 'removed '.$LNG->CON_NAME;
$LNG->ADMIN_CRON_FOLLOW_REMOVED_EVIDENCE = 'removed the '.$LNG->ARGUMENT_NAME;
$LNG->ADMIN_CRON_FOLLOW_REMOVED_ASSOCIATION = 'removed association with';
$LNG->ADMIN_CRON_FOLLOW_DATE_FROM_TO_PART1 = 'From';
$LNG->ADMIN_CRON_FOLLOW_DATE_FROM_TO_PART2 = 'To';
$LNG->ADMIN_CRON_FOLLOW_WEEKLY = 'Weekly';
$LNG->ADMIN_CRON_FOLLOW_WEEKLY_TITLE = 'Weekly Debate Hub Activity Report';
$LNG->ADMIN_CRON_FOLLOW_WEEKLY_DIGEST_RUN = 'Weekly Digest for Activites on '.$CFG->SITE_TITLE.' Run';
$LNG->ADMIN_CRON_FOLLOW_MONTHLY = 'Monthly';
$LNG->ADMIN_CRON_FOLLOW_MONTHLY_TITLE = 'Monthly Debate Hub Activity Report';
$LNG->ADMIN_CRON_FOLLOW_MONTHLY_DIGEST_RUN = 'Monthly Digest for Activites on '.$CFG->SITE_TITLE.' Run';
$LNG->ADMIN_CRON_FOLLOW_DAILY = 'Daily';
$LNG->ADMIN_CRON_FOLLOW_DAILY_TITLE = 'Daily Debate Hub Activity Report';
$LNG->ADMIN_CRON_FOLLOW_DAILY_DIGEST_RUN = 'Daily Digest for Activites on '.$CFG->SITE_TITLE.' Run';
$LNG->ADMIN_CRON_FOLLOW_HOURLY = 'Hourly';
$LNG->ADMIN_CRON_FOLLOW_HOURLY_TITLE = 'Hourly Debate Hub Activity Report';
$LNG->ADMIN_CRON_FOLLOW_HOURLY_DIGEST_RUN = 'Hourly Digest for Activites on '.$CFG->SITE_TITLE.' Run';
$LNG->ADMIN_CRON_FOLLOW_NO_DIGEST = 'No email digest for:';
$LNG->ADMIN_CRON_FOLLOW_UNSUBSCRIBE_PART1 = 'To stop receiving this email digest or change the frequency it is sent please login to the hub and either select a different frequency option or uncheck \'Send email Alert of New Activity\' on your';
$LNG->ADMIN_CRON_FOLLOW_UNSUBSCRIBE_PART2 = $LNG->HEADER_MY_HUB_LINK.' home page';

$LNG->ADMIN_CREATE_LINK_TYPES_TITLE = 'Create Link Types';
$LNG->ADMIN_CREATE_NODE_TYPES_TITLE = 'Create Node Types';
$LNG->ADMIN_CRON_RECENT_ACTIVITY_DIGEST_RUN = 'Recent Activite Digest on '.$CFG->SITE_TITLE.' Run';
$LNG->ADMIN_CRON_RECENT_ACTIVITY_NO_DIGEST = 'No recent activity digest for:';
$LNG->ADMIN_CRON_RECENT_ACTIVITY_TITLE = 'Debate Hub Recent Activity Report';
$LNG->ADMIN_CRON_RECENT_ACTIVITY_MESSAGE = 'See below for the top 5 most recent items entered for each Debate Hub Category.';

$LNG->ADMIN_NEWS_USERS = 'User List';

/** HELP PAGES **/
$LNG->HELP_NETWORKMAP_TITLE = 'Network Map';
$LNG->HELP_NETWORKMAP_BODY = '<b>Background:</b><br><br>&nbsp;&nbsp;&nbsp;';
$LNG->HELP_NETWORKMAP_BODY .= '<b>L-drag to pan</b><br>&nbsp;&nbsp;&nbsp;';
$LNG->HELP_NETWORKMAP_BODY .= '<b>R-click</b> to fit network on screen (Apple-Click on Macs)<br>&nbsp;&nbsp&nbsp;';
$LNG->HELP_NETWORKMAP_BODY .= '<b>R-drag to zoom in/out</b> (Apple-Drag on Macs)<br><br>';
$LNG->HELP_NETWORKMAP_BODY .= '<b>Ideas:</b><br><br>&nbsp;&nbsp;&nbsp;';
$LNG->HELP_NETWORKMAP_BODY .= '<b>L-click</b> to highlight what\'s connected<br>&nbsp;&nbsp;&nbsp;';
$LNG->HELP_NETWORKMAP_BODY .= '<b>L-click</b> to view/edit its profile<br>&nbsp;&nbsp;&nbsp;';
$LNG->HELP_NETWORKMAP_BODY .= '<b>Duplicate Ideas</b> (created by >1 user) have a border<br>&nbsp;&nbsp;&nbsp;';
$LNG->HELP_NETWORKMAP_BODY .= '<b>L-click duplicate Ideas</b> to view profiles in Idea List<br><br>';
$LNG->HELP_NETWORKMAP_BODY .= '<b>Connections:</b><br><br>&nbsp;&nbsp;&nbsp;';
$LNG->HELP_NETWORKMAP_BODY .= '<b>Mouse over blobs</b> to view an Idea\'s<br>&nbsp;&nbsp;&nbsp;';

/** CORE **/
$LNG->CORE_UNKNOWN_USER_ERROR = 'User unknown';
$LNG->CORE_NOT_IMAGE_ERROR = 'Sorry you can only upload images.';
$LNG->CORE_NOT_IMAGE_TOO_LARGE_ERROR = 'Sorry image file is too large.';
$LNG->CORE_NOT_IMAGE_UPLOAD_ERROR = 'An error occured uploading the image';
$LNG->CORE_NOT_IMAGE_RESIZE_ERROR = 'Error resizing image';
$LNG->CORE_NOT_IMAGE_SCALE_ERROR = 'Error scaling image.';

$LNG->CORE_SESSION_OK = 'OK';
$LNG->CORE_SESSION_INVALID = 'Session Invalid';

$LNG->CORE_AUDIT_NOT_XML_ERROR = 'Not a valid XML file';
$LNG->CORE_AUDIT_CONNECTION_NOT_FOUND_ERROR = 'Connection not found';
$LNG->CORE_AUDIT_NODE_NOT_FOUND_ERROR = 'Node not found';
$LNG->CORE_AUDIT_URL_NOT_FOUND_ERROR = 'URL not found';
$LNG->CORE_AUDIT_CONNECTION_ID_MISSING_ERROR = 'Connection id data missing - data could not be loaded';
$LNG->CORE_AUDIT_CONNECTION_DATA_MISSING_ERROR = 'Connection data missing';
$LNG->CORE_AUDIT_NODE_ID_MISSING_ERROR = 'Node id data missing - node could not be loaded';
$LNG->CORE_AUDIT_NODE_DATA_MISSING_ERROR = 'Node data missing';
$LNG->CORE_AUDIT_URL_ID_MISSING_ERROR = 'Url id data missing - url could not be loaded';
$LNG->CORE_AUDIT_URL_DATA_MISSING_ERROR = 'Url data missing';
$LNG->CORE_AUDIT_TAG_ID_MISSING_ERROR = 'Tag id data missing - Tag could not be loaded';
$LNG->CORE_AUDIT_TAG_DATA_MISSING_ERROR = 'Tag data missing';
$LNG->CORE_AUDIT_USER_ID_MISSING_ERROR = 'User id data missing - user could not be loaded';
$LNG->CORE_AUDIT_USER_DATA_MISSING_ERROR = 'User data missing';
$LNG->CORE_AUDIT_GROUP_ID_MISSING_ERROR = 'Group id data missing - Group could not be loaded';
$LNG->CORE_AUDIT_GROUP_DATA_MISSING_ERROR = 'Group data missing';
$LNG->CORE_AUDIT_ROLE_ID_MISSING_ERROR = 'Node Type id data missing - Node Type could not be loaded';
$LNG->CORE_AUDIT_ROLE_DATA_MISSING_ERROR = 'Node Type data missing';
$LNG->CORE_AUDIT_LINK_ID_MISSING_ERROR = 'Linktype id data missing - Link Type could not be loaded';
$LNG->CORE_AUDIT_LINK_DATA_MISSING_ERROR = 'Link Type data missing';

$LNG->CORE_FORMAT_NOT_IMPLEMENTED_MESSAGE = 'Not yet implemented';
$LNG->CORE_FORMAT_INVALID_SELECTION_ERROR = 'Invalid format selection';

$LNG->CORE_HELP_ERRORCODES_TITLE = 'Help - API Error codes';
$LNG->CORE_HELP_ERRORCODES_CODE_HEADING = 'Code';
$LNG->CORE_HELP_ERRORCODES_MEANING_HEADING = 'Meaning';

$LNG->CORE_DATAMODEL_GROUP_CANNOT_REMOVE_MEMBER = 'Cannot remove user as admin as group will have no admins';

/**
 * THESE ARE ERROR MESSAGE SENT FROM THE API CODE CODE
 * YOU MAY CHOOSE NOT TO TRANSLATE THESE
 */
$LNG->ERROR_REQUIRED_PARAMETER_MISSING_MESSAGE = "Required parameter missing";
$LNG->ERROR_INVALID_METHOD_SPECIFIED_MESSAGE = "Invalid or no method specified";
$LNG->ERROR_INVALID_ORDERBY_MESSAGE = "Invalid order by selection";
$LNG->ERROR_INVALID_SORT_MESSAGE = "Invalid sort selection";
$LNG->ERROR_BLANK_NODEID_MESSAGE = "The item id cannot be blank.";
$LNG->ERROR_ACCESS_DENIED_MESSAGE = "Access denied";
$LNG->ERROR_LOGIN_FAILED_MESSAGE = "Sign In failed: Your email or password are wrong. Please try again.";
$LNG->ERROR_LOGIN_FAILED_SUSPENDED_MESSAGE = "Sign In failed: This account has been suspended";
$LNG->ERROR_LOGIN_FAILED_UNVALIDATED_MESSAGE = "Sign In failed: This account has not completed the registration process by having its Email address validated.";
$LNG->ERROR_LOGIN_FAILED_EXTERNAL_MESSAGE = "The account with the given email address was created with an external service and does not have a local password.<br>You must sign in to this account using:";

$LNG->ERROR_INVALID_JSON_ERROR_NONE = "No JSON errors";
$LNG->ERROR_INVALID_JSON_ERROR_DEPTH = "Maximum stack depth exceeded in the JSON";
$LNG->ERROR_INVALID_JSON_ERROR_STATE_MISMATCH = "Underflow or the modes mismatch";
$LNG->ERROR_INVALID_JSON_ERROR_CTRL_CHAR = "Unexpected control character found in the JSON";
$LNG->ERROR_INVALID_JSON_ERROR_SYNTAX = "Syntax error, malformed JSON";
$LNG->ERROR_INVALID_JSON_ERROR_UTF8 = "Malformed UTF-8 characters, possibly incorrectly encoded";
$LNG->ERROR_INVALID_JSON_ERROR_DEFAULT = "An unknown error has occurred decoding the JSON";

$LNG->ERROR_INVALID_METHOD_FOR_TYPE_MESSAGE = "Method not allowed for this format type";
$LNG->ERROR_DUPLICATION_MESSAGE = "Duplication Error";
$LNG->ERROR_INVALID_EMAIL_FORMAT_MESSAGE = "Invalid email format";
$LNG->ERROR_DATABASE_MESSAGE = "Database error";
$LNG->ERROR_USER_NOT_FOUND_MESSAGE = 'User not found in database';
$LNG->ERROR_URL_NOT_FOUND_MESSAGE = 'Url not found in database';
$LNG->ERROR_TAG_NOT_FOUND_MESSAGE = 'Tag not found in database';
$LNG->ERROR_ROLE_NOT_FOUND_MESSAGE = 'Node Type (Role) not found in database';
$LNG->ERROR_LINKTYPE_NOT_FOUND_MESSAGE = 'Link Type not found in database';
$LNG->ERROR_NODE_NOT_FOUND_MESSAGE = 'Node not found in database';
$LNG->ERROR_CONNECTION_NOT_FOUND_MESSAGE = 'Connection not found in database';
$LNG->ERROR_INVALID_CONNECTION_MESSAGE = "Invalid connection combination. Does not match the datamodel.";
$LNG->ERROR_INVALID_PARAMETER_TYPE_MESSAGE = "Invalid parameter type";

/** NEW USER HOME PAGE ARRANGEMENT **/
$LNG->TAB_USER_DATA = 'My Data';
$LNG->TAB_USER_GROUP = 'My '.$LNG->GROUPS_NAME;
$LNG->TAB_USER_SOCIAL = 'My Social Network';

/** SPAM REPORTING **/
$LNG->SPAM_CONFIRM_MESSAGE_PART1= 'Are you sure you want to report';
$LNG->SPAM_CONFIRM_MESSAGE_PART2= 'as Spam / Inappropriate?';
$LNG->SPAM_SUCCESS_MESSAGE = 'has been reported as spam';
$LNG->SPAM_REPORTED_TEXT = 'Reported as Spam';
$LNG->SPAM_REPORTED_HINT = 'This has been reported as Spam / Inappropriate content';
$LNG->SPAM_REPORT_TEXT = 'Report as Spam';
$LNG->SPAM_REPORT_HINT = 'Report this as Spam / Inappropriate content';
$LNG->SPAM_LOGIN_REPORT_TEXT = 'Sign In to Report as Spam';
$LNG->SPAM_LOGIN_REPORT_HINT = 'Sign In to Report this as Spam / Inappropriate content';
$LNG->SPAM_ADMIN_MANAGER_SPAM_LINK = "Reported Items";
$LNG->SPAM_ADMIN_TITLE = "Item Report Manager";
$LNG->SPAM_ADMIN_ID_ERROR = "Can not process request as nodeid is missing";
$LNG->SPAM_ADMIN_TABLE_HEADING0 = "Reported By";
$LNG->SPAM_ADMIN_TABLE_HEADING1 = "Title";
$LNG->SPAM_ADMIN_TABLE_HEADING2 = "Action";
$LNG->SPAM_ADMIN_DELETE_CHECK_MESSAGE = "Are you sure you want to delete the item?: ";
$LNG->SPAM_ADMIN_RESTORE_CHECK_MESSAGE = "Are you sure you want to set as NOT SPAM?: ";
$LNG->SPAM_ADMIN_RESTORE_BUTTON = "Not Spam";
$LNG->SPAM_ADMIN_DELETE_BUTTON = "Delete";
$LNG->SPAM_ADMIN_VIEW_BUTTON = "View Details";
$LNG->SPAM_ADMIN_NONE_MESSAGE = 'There are currently no items reported as Spam / Inappropriate';

$LNG->SPAM_USER_REPORTED = 'User has been reported as a Spammer / Inappropriate';
$LNG->SPAM_USER_REPORT = 'Report this User as a Spammer / Inappropriate';
$LNG->SPAM_USER_LOGIN_REPORT = 'Login to report this User or Group as Spam / Inappropriate';
$LNG->SPAM_USER_REPORTED_ALT = 'Reported';
$LNG->SPAM_USER_REPORT_ALT = 'Report';
$LNG->SPAM_USER_LOGIN_REPORT_ALT = 'Login to Report';
$LNG->SPAM_USER_ADMIN_TABLE_HEADING0 = "Reported By";
$LNG->SPAM_USER_ADMIN_TABLE_HEADING1 = "User Name";
$LNG->SPAM_USER_ADMIN_TABLE_HEADING2 = "Action";
$LNG->SPAM_USER_ADMIN_VIEW_BUTTON = "View User Home";
$LNG->SPAM_USER_ADMIN_VIEW_HINT = "Open a new Window showing this user's home page";
$LNG->SPAM_USER_ADMIN_RESTORE_BUTTON = "Restore Account";
$LNG->SPAM_USER_ADMIN_RESTORE_HINT = "Restore this user account to active";
$LNG->SPAM_USER_ADMIN_DELETE_BUTTON = "Delete Account";
$LNG->SPAM_USER_ADMIN_DELETE_HINT = "Delete this user account and all their data";
$LNG->SPAM_USER_ADMIN_SUSPEND_BUTTON = "Suspend Account";
$LNG->SPAM_USER_ADMIN_SUSPEND_HINT = "Suspend this user account and prevent them signing in";
$LNG->SPAM_USER_ADMIN_DELETE_CHECK_MESSAGE_PART1 = "Are you sure you want to delete the user: ";
$LNG->SPAM_USER_ADMIN_DELETE_CHECK_MESSAGE_PART2 = "Be warned: all their data will be permanently deleted. If you have not done so, you should check their contributions first by clicking '".$LNG->SPAM_USER_ADMIN_VIEW_BUTTON."'";;
$LNG->SPAM_USER_ADMIN_RESTORE_CHECK_MESSAGE_PART1 = "Are you sure you want to restore the account of: ";
$LNG->SPAM_USER_ADMIN_RESTORE_CHECK_MESSAGE_PART2 = "This will remove this user from this list";
$LNG->SPAM_USER_ADMIN_SUSPEND_CHECK_MESSAGE = "Are you sure you want to suspend the account of: ";
$LNG->SPAM_USER_ADMIN_NONE_MESSAGE = 'There are currently no users reported as Spammers / Inappropriate';
$LNG->SPAM_USER_ADMIN_TITLE = "User Report Manager";
$LNG->SPAM_USER_ADMIN_MANAGER_SPAM_LINK = "Reported Users";
$LNG->SPAM_USER_ADMIN_ID_ERROR = "Can not process request as userid is missing";
$LNG->SPAM_USER_ADMIN_NONE_SUSPENDED_MESSAGE = 'There are currently no users suspended';
$LNG->SPAM_USER_ADMIN_SPAM_TITLE = 'Users Reported';
$LNG->SPAM_USER_ADMIN_SUSPENDED_TITLE = 'Users Suspended';

/** EXTERNAL LOGIN **/
$LNG->LOGIN_EXTERNAL_ERROR_HYBRIDAUTH_0 = 'Unspecified error.';
$LNG->LOGIN_EXTERNAL_ERROR_HYBRIDAUTH_1 = 'Hybriauth configuration error.';
$LNG->LOGIN_EXTERNAL_ERROR_HYBRIDAUTH_2 = 'Provider not properly configured.';
$LNG->LOGIN_EXTERNAL_ERROR_HYBRIDAUTH_3 = 'Unknown or disabled provider.';
$LNG->LOGIN_EXTERNAL_ERROR_HYBRIDAUTH_4 = 'Missing provider application credentials.';
$LNG->LOGIN_EXTERNAL_ERROR_HYBRIDAUTH_5 = 'Authentication failed. The user has canceled the authentication or the provider refused the connection.';
$LNG->LOGIN_EXTERNAL_ERROR_HYBRIDAUTH_6 = 'User profile request failed. Most likely the user is not connected to the provider and he should try to authenticate again';
$LNG->LOGIN_EXTERNAL_ERROR_HYBRIDAUTH_7 = 'User not connected to the provider.';

$LNG->LOGIN_EXTERNAL_ERROR_ACCOUNT_UNVALIDATED = 'An Debate Hub user account already exists on this site using the email address from your external profile, but that user account has not completed the registration process.<br>If you own that user account you need to reply to the email you where sent to complete your registration, before you can Sign In.';
$LNG->LOGIN_EXTERNAL_ERROR_ACCOUNT_UNVALIDATED_EXISTING = 'An Debate Hub user account already exists on this site using the email address from your external profile, but that Debate Hub user account has not had the email address verify yet.<br><br>If you own that Debate Hub user account you first need to <a href="'.$CFG->homeAddress.'ui/pages/login.php">Sign In</a> using that account and verify your email address from your profile page, before you can use any external services to Sign In to this Hub in the future.';
$LNG->LOGIN_EXTERNAL_ERROR_ACCOUNT_UNAUTHORIZED = 'An Debate Hub user account already exists using the email address from your external profile, however that account is awaiting authorization, so we cannot log you in at this time.';
$LNG->LOGIN_EXTERNAL_ERROR_ACCOUNT_SUSPENDED = 'An Debate Hub user account already exists on this site using the email address on your external profile, however the account has currently been suspended, so we cannot log you in at this time.';
$LNG->LOGIN_EXTERNAL_ERROR_ACCOUNT_PROVIDER_UNVALIDATED = 'It seems you have tried to sign in with'; // Provder service name will be inserted here .e.g Facebook, Yahoo, Google etc.
$LNG->LOGIN_EXTERNAL_ERROR_ACCOUNT_PROVIDER_UNVALIDATED_PART2 = 'before but did not complete the email validation required.';
$LNG->LOGIN_EXTERNAL_ERROR_ACCOUNT_PROVIDER_UNVALIDATED_PART2 .= '<br><br>Please respond to the email you where sent, before you try to Sign In with this service again.';
$LNG->LOGIN_EXTERNAL_ERROR_ACCOUNT_PROVIDER_UNVALIDATED_PART2 .= '<br><br>Alternatively, request another validation email by clicking the button below.';
$LNG->LOGIN_EXTERNAL_ERROR_USER_LOAD_FAILED = 'Failed to load user acount: ';
$LNG->LOGIN_EXTERNAL_ERROR_REGISTRATION_CLOSED = "Based on the email address given we can see that you do not have an account with us yet.<br><br>Unfortunately registration on this site is currently by invitation only.";
$LNG->LOGIN_EXTERNAL_ERROR_REQUIRES_AUTHORISATION = 'Based on the email address given we can see that you do not have an account with us yet.<br><br>This Debate Hub currently requires registration requests to be authorised.<br>So please go to the <a href="'.$CFG->homeAddress.'ui/pages/registerrequest.php">Sign Up</a> page and complete the registration request form.';

$LNG->LOGIN_EXTERNAL_FIRST_TIME = 'We can see that this is the first time you have tried to sign in to this site using'; // Provder service name will be inserted here .e.g Facebook, Yahoo, Google etc.
$LNG->LOGIN_EXTERNAL_ERROR_EMAIL_UNVALIDATED_PART1 = '<br><br>Unfortunately the email address on the profile information they hold on you has not been verified by them. So before we can associated this external profile to an account in our Hub we need to validate the email address.<br><br>Therefore you have now been sent an email. Please click on the link in the email to complete the registration of your'; // Provder service name will be inserted here .e.g Facebook, Yahoo, Google etc.
$LNG->LOGIN_EXTERNAL_ERROR_EMAIL_UNVALIDATED_PART2 = 'profile on this Hub.';

$LNG->LOGIN_EXTERNAL_ERROR_NO_EMAIL_PART1 = '<br><br>Unfortunately'; // Provder service name will be inserted here .e.g Facebook, Yahoo, Google etc.
$LNG->LOGIN_EXTERNAL_ERROR_NO_EMAIL_PART2 = 'has not given us your email address, so we cannot check if you have an account with us already or create a new one if required.<br><br>Therefore, please enter the Email address you wish to use on this Debate Hub below and press Login.';

$LNG->LOGIN_EXTERNAL_EMAIL_VERIFICALTION_MESSAGE = 'You will shortly receive an email.';
$LNG->LOGIN_EXTERNAL_EMAIL_VERIFICALTION_MESSAGE .= '<br>You must click on the link inside to complete your registration on this Hub.';

$LNG->LOGIN_EXTERNAL_EMAIL_VERIFICALTION_MESSAGE2 = 'There was no existing Hub user account for the email address on your external profile, so we have now created one and associated it to that external profile.';
$LNG->LOGIN_EXTERNAL_EMAIL_VERIFICALTION_MESSAGE2 .= '<br>However, the email address has not been validated by the external service provider, so before we can complete your registration we must first validate that email address belongs to you.';
$LNG->LOGIN_EXTERNAL_EMAIL_VERIFICALTION_MESSAGE2 .= '<br><br>'.$LNG->LOGIN_EXTERNAL_EMAIL_VERIFICALTION_MESSAGE;

$LNG->LOGIN_EXTERNAL_TITLE = 'Social Sign On';

$LNG->LOGIN_EXTERNAL_COMPLETE_TITLE = 'SOCIAL SIGN ON - Completing Email Validation';
$LNG->LOGIN_EXTERNAL_COMPLETE_FAILED = 'The Social sign on record associated with the given id is no longer available. Please try Signing Up/In again';
$LNG->LOGIN_EXTERNAL_COMPLETE_FAILED = 'Your email validation could not be completed as the record id given was invalid. Please try Signing Up/In again';
$LNG->LOGIN_EXTERNAL_COMPLETE_FAILED_USER = 'The existing User account that is associated with the given email address is no longer available';
$LNG->LOGIN_EXTERNAL_COMPLETE_FAILED_INVALID = 'Your email validation could not be completed as the validation key given was invalid for the given external provider record id. <br><br>Please try again using a different provider, or create a local Debate Hub account';
$LNG->LOGIN_EXTERNAL_REGISTER_COMPLETE_FAILED = 'Your registration could not be completed as the user id given did not belong to the external provider record given.<br><br>Please try again using a different provider, or create a local Debate Hub account';

// Messages used when the provider didn't supply the email address so the user was asked to
$LNG->LOGIN_EXTERNAL_NO_EMAIL_ACCOUNT_EXISTS = 'An LiteMap user account already exists on this site using the email address you have given us';

$LNG->LOGIN_EXTERNAL_UNVALIDATED_TITLE = 'Validate Your Debate Hub Email Address';


$LNG->LOGIN_EXTERNAL_NO_EMAIL_ERROR_ACCOUNT_UNVALIDATED = $LNG->LOGIN_EXTERNAL_NO_EMAIL_ACCOUNT_EXISTS.', but that user account has not completed its registration process.<br><br>If you own that Debate Hub user account you need to reply to the email you where sent to complete your registration, before you can use any external services to Sign In to this Hub.';
$LNG->LOGIN_EXTERNAL_NO_EMAIL_ERROR_ACCOUNT_UNVALIDATED_EXISTING = $LNG->LOGIN_EXTERNAL_NO_EMAIL_ACCOUNT_EXISTS.', but that Debate Hub user account has not had its email address validated yet.<br><br>If you own that Debate Hub user account you first need to <a href="'.$CFG->homeAddress.'ui/pages/login.php">Sign In</a> using that account and validate your email address from your profile page, before you can use any external services to Sign In to this Hub in the future.';
$LNG->LOGIN_EXTERNAL_NO_EMAIL_ERROR_ACCOUNT_UNAUTHORIZED = $LNG->LOGIN_EXTERNAL_NO_EMAIL_ACCOUNT_EXISTS.', however that account is awaiting authorization, so we cannot log you in at this time.';
$LNG->LOGIN_EXTERNAL_NO_EMAIL_ERROR_ACCOUNT_SUSPENDED = $LNG->LOGIN_EXTERNAL_NO_EMAIL_ACCOUNT_EXISTS.', however the account has currently been suspended, so we cannot log you in at this time.';

$LNG->LOGIN_EXTERNAL_NO_EMAIL_EXISTING_VALIDATE_TITLE_PART1 = 'Validate Your';
$LNG->LOGIN_EXTERNAL_NO_EMAIL_EXISTING_VALIDATE_TITLE_PART2 = 'Email Address';
$LNG->LOGIN_EXTERNAL_NO_EMAIL_EXISTING_VALIDATE_MESSAGE_PART1 = $LNG->LOGIN_EXTERNAL_NO_EMAIL_ACCOUNT_EXISTS.'. In order for us to associate your'; // Provder service name will be inserted here .e.g Facebook, Yahoo, Google etc.
$LNG->LOGIN_EXTERNAL_NO_EMAIL_EXISTING_VALIDATE_MESSAGE_PART2 = 'account with this Debate Hub user account we first need to validate that you are the owner of the email address you have given us.<br><br>Therefore we have sent you an email. Please click on the link inside to validate your email address and complete the registration of your external profile with us.';

$LNG->LOGIN_EXTERNAL_NO_EMAIL_VERIFICALTION_TITLE = 'Registration Successful';
$LNG->LOGIN_EXTERNAL_NO_EMAIL_VERIFICALTION_MESSAGE_PART1 = 'There was no existing Debate Hub user account for the email address you have given us, so we have now created one and associated it to your'; // Provder service name will be inserted here .e.g Facebook, Yahoo, Google etc.
$LNG->LOGIN_EXTERNAL_NO_EMAIL_VERIFICALTION_MESSAGE_PART2 = 'profile.';
$LNG->LOGIN_EXTERNAL_NO_EMAIL_VERIFICALTION_MESSAGE_PART3 = '<br>However, to complete your registration with us we must first validate that you are the owner of the email address you have given us.';
$LNG->LOGIN_EXTERNAL_NO_EMAIL_VERIFICALTION_MESSAGE_PART3 .= '<br><br>'.$LNG->LOGIN_EXTERNAL_EMAIL_VERIFICALTION_MESSAGE;

$LNG->LOGIN_EXTERNAL_WELCOME_TITLE = 'Welcome to the Debate Hub';
$LNG->LOGIN_EXTERNAL_WELCOME_MESSAGE_PART1 = 'There was no existing Debate Hub user account for the email address:';
$LNG->LOGIN_EXTERNAL_WELCOME_MESSAGE_PART2 = ', so we have now created one and associated it to your'; // Provder service name will be inserted here .e.g Facebook, Yahoo, Google etc.
$LNG->LOGIN_EXTERNAL_WELCOME_MESSAGE_PART3 = 'profile.';
$LNG->LOGIN_EXTERNAL_WELCOME_MESSAGE_PART4 = 'You should receive a welcome email shortly.';

$LNG->LOGIN_EXTERNAL_ENTER_BUTTON = 'Enter Site';

/** NEW LOGIN ADDITIONS **/
$LNG->VALIDATION_COMPLETE_TITLE = 'Email Address Validation';
$LNG->VALIDATION_FAILED = 'Your email address validation could not be completed. Please try again';
$LNG->VALIDATION_FAILED_INVALID = 'Your email address validation could not be completed as the Validation key was invalid for the given user. Please try again';
$LNG->VALIDATION_SUCCESSFUL_LOGIN = "Thank you for validating your email address with us.</a>";

$LNG->EMAIL_VALIDATE_TEXT = 'Send New Validation Email';
$LNG->EMAIL_VALIDATE_HINT = 'Click here to be sent another validation email for you to complete your registration of this external profile with us.';
$LNG->EMAIL_VALIDATE_MESSAGE = 'You have been sent an email to validate that you own the email address you tried to Sign In with.';

/** ADMIN USER REGISTRATION MANAGER **/
$LNG->REGSITRATION_ADMIN_MANAGER_LINK = "Registration Requests";
$LNG->REGSITRATION_ADMIN_TITLE = 'User Registration Manager';

$LNG->REGSITRATION_ADMIN_UNREGISTERED_TITLE = "Registration Requests";
$LNG->REGSITRATION_ADMIN_UNVALIDATED_TITLE = "Unvalidated Registrations";
$LNG->REGSITRATION_ADMIN_REVALIDATE_BUTTON = "Revalidate";
$LNG->REGSITRATION_ADMIN_REMOVE_BUTTON = "Remove";
$LNG->REGSITRATION_ADMIN_REMOVE_CHECK_MESSAGE = "Are you sure you want to REMOVE this user registration?: ";
$LNG->REGSITRATION_ADMIN_REVALIDATE_CHECK_MESSAGE = "Are you sure you want to send another validation email to this user?: ";
$LNG->REGSITRATION_ADMIN_USER_REMOVED = 'has had their acount removed from the system';
$LNG->REGSITRATION_ADMIN_USER_EMAILED_REVALIDATED = 'has been re-emailed that their registration request was accepted';

$LNG->REGSITRATION_ADMIN_REJECT_CHECK_MESSAGE = "Are you sure you want to REJECT this user registration request?: ";
$LNG->REGSITRATION_ADMIN_ACCEPT_CHECK_MESSAGE = "Are you sure you want to ACCEPT this user registration request?: ";
$LNG->REGSITRATION_ADMIN_NONE_MESSAGE = 'There are currently no users requesting registration';
$LNG->REGSITRATION_ADMIN_VALIDATION_NONE_MESSAGE = 'There are currently no users awaiting validation';
$LNG->REGSITRATION_ADMIN_TABLE_HEADING_NAME = "Name";
$LNG->REGSITRATION_ADMIN_TABLE_HEADING_DESC = "Description";
$LNG->REGSITRATION_ADMIN_TABLE_HEADING_INTEREST = "Interest";
$LNG->REGSITRATION_ADMIN_TABLE_HEADING_WEBSITE = "website";
$LNG->REGSITRATION_ADMIN_TABLE_HEADING_ACTION = "Action";
$LNG->REGSITRATION_ADMIN_REJECT_BUTTON = 'Reject';
$LNG->REGSITRATION_ADMIN_ACCEPT_BUTTON = 'Accept';
$LNG->REGSITRATION_ADMIN_ID_ERROR = "Can not process user request as userid is missing";
$LNG->REGSITRATION_ADMIN_USER_EMAILED_ACCEPTANCE = 'has been emailed that their registration request was accepted';
$LNG->REGSITRATION_ADMIN_USER_EMAILED_REJECTION = 'has been emailed that their registration request was rejected';
$LNG->REGSITRATION_ADMIN_EMAIL_REQUEST_SUBJECT = $LNG->WELCOME_REGISTER_REQUEST_SUBJECT;

// %s will be replace with the name of the current Debate Hub. When translating please leave this in the sentence appropariately placed.
$LNG->REGSITRATION_ADMIN_EMAIL_REJECT_BODY = 'Thank you for requesting registration on the %s.<br>Unfortunately, on this occasion, your request for a user account has not been successful.';

$LNG->RECENT_EMAIL_DIGEST_LABEL = 'Email Digest:';
$LNG->RECENT_EMAIL_DIGEST_REGISTER_MESSAGE = "Tick to receive a monthly email digest of recent activity.";
$LNG->RECENT_EMAIL_DIGEST_PROFILE_MESSAGE = "Opt in/out of receiving a monthly email digest of recent activity.";
?>