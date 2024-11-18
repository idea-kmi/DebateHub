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
	header('Content-Type: text/javascript;');
	include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
?>

/**
 * Functions for the search results page 'search.php'.
 */

/**
 *	Add the filter and sort controls for the page.
 */
function addControls(container) {
	var tb3 = new Element("div", {'class':'toolbarrowsearch row d-inline-block'});
	var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>',connectedness:'<?php echo $LNG->SORT_CONNECTIONS; ?>', vote:'<?php echo $LNG->SORT_VOTES; ?>'};
	tb3.insert(displaySortForm(sortOpts));
	container.insert(tb3);
}

function buildSearchToolbar(container) {
	addControls(container);
}

/**
 *	load next/previous set of nodes
 */
function loadissues(context,args){
	args['filternodetypes'] = "Issue";

	const contentissuelist = document.getElementById('content-issue-list');
	contentissuelist.innerHTML = "";
	contentissuelist.appendChild(getLoading("<?php echo $LNG->LOADING_ISSUES; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);

	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){

			try {
				var json = transport.responseText.evalJSON();
			} catch(err) {
				console.log(err);
			}

			if(json.error){
				alert(json.error[0].message);
				return;
			}

			//display nav
			var total = json.nodeset[0].totalno;

			//display nodes
			if(json.nodeset[0].nodes.length > 0){

				//preprosses nodes to add searchid if it is there
				if (args['searchid'] && args['searchid'] != "") {
					var nodes = json.nodeset[0].nodes;
					var count = nodes.length;
					for (var i=0; i < count; i++) {
						var node = nodes[i];
						node.cnode.searchid = args['searchid'];
					}
				}

				//set the count in header
				document.getElementById('issue-list-count').innerHTML = "";
				document.getElementById('issue-list-count-main').innerHTML = "";
				document.getElementById('content-issue-list').innerHTML = "";
				document.getElementById('issue-list-title').innerHTML = "";

				document.getElementById('content-issue-main').style.display = "block";
				document.getElementById('issue-result-menu').href = "#issueresult";
				document.getElementById('issue-result-menu').className = '';

				if (total > parseInt( args["max"] )) {
					const contentissuelist = document.getElementById('content-issue-list');
					contentissuelist.innerHTML = "";	
					contentissuelist.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"issues"));
				}

				document.getElementById('issue-list-count').innerHTML = json.nodeset[0].totalno;
				document.getElementById('issue-list-count-main').innerHTML = json.nodeset[0].totalno;

				if (json.nodeset[0].nodes.length > 1) {
					document.getElementById("issue-list-title").innerHTML = "<?php echo $LNG->ISSUES_NAME; ?>";
				} else {
					document.getElementById("issue-list-title").innerHTML = "<?php echo $LNG->ISSUE_NAME; ?>";
				}

				displaySearchNodes(document.getElementById("content-issue-list"),json.nodeset[0].nodes,parseInt(args['start'])+1, true);

				if (total > parseInt( args["max"] )) {
					document.getElementById("content-issue-list").appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"issues"));
				}
			} else {
				document.getElementById('content-issue-main').style.display = "none";
				document.getElementById('content-issue-list').innerHTML = "";
				document.getElementById('issue-result-menu').href = "javascript:return false";
				document.getElementById('issue-result-menu').className = 'inactive';
				document.getElementById('issue-list-count-main').innerHTML = "";
				document.getElementById('issue-list-count-main').insert('0');
			}
		}
	});
}

/**
 *	load next/previous set of nodes
 */
function loadsolutions(context,args){
	args['filternodetypes'] = "Solution";

	const contentsolutionlist = document.getElementById('content-solution-list');
	contentsolutionlist.innerHTML = "";
	contentsolutionlist.appendChild(getLoading("<?php echo $LNG->LOADING_SOLUTIONS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);

	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){

			try {
				var json = transport.responseText.evalJSON();
			} catch(err) {
				console.log(err);
			}

			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var total = json.nodeset[0].totalno;

			if(json.nodeset[0].nodes.length > 0){

				//preprosses nodes to add searchid if it is there
				if (args['searchid'] && args['searchid'] != "") {
					var nodes = json.nodeset[0].nodes;
					var count = nodes.length;
					for (var i=0; i < count; i++) {
						var node = nodes[i];
						node.cnode.searchid = args['searchid'];
					}
				}

				//set the count in header
				document.getElementById('solution-list-count').innerHTML = "";
				document.getElementById('solution-list-count-main').innerHTML = "";
				document.getElementById('content-solution-list').innerHTML = "";
				document.getElementById('solution-list-title').innerHTML = "";

				document.getElementById('content-solution-main').style.display = "block";
				document.getElementById('solution-result-menu').href = "#solutionresult";
				document.getElementById('solution-result-menu').className = '';

				if (total > parseInt( args["max"] )) {
					const contentsolutionlist = document.getElementById('content-solution-list');
					contentsolutionlist.innerHTML = "";
					contentsolutionlist.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"solutions"));
				}

				document.getElementById('solution-list-count').innerHTML = json.nodeset[0].totalno;
				document.getElementById('solution-list-count-main').innerHTML = json.nodeset[0].totalno;

				if (json.nodeset[0].nodes.length > 1) {
					document.getElementById("solution-list-title").innerHTML = "<?php echo $LNG->SOLUTIONS_NAME; ?>";
				} else {
					document.getElementById("solution-list-title").innerHTML = "<?php echo $LNG->SOLUTION_NAME; ?>";
				}
				displaySearchNodes(document.getElementById("content-solution-list"),json.nodeset[0].nodes,parseInt(args['start'])+1, true);

				if (total > parseInt( args["max"] )) {
					document.getElementById("content-solution-list").appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"solutions"));
				}
			} else {
				document.getElementById('content-solution-main').style.display = "none";
				document.getElementById('content-solution-list').innerHTML = "";
				document.getElementById('solution-result-menu').href = "javascript:return false";
				document.getElementById('solution-result-menu').className = 'inactive';
				document.getElementById('solution-list-count-main').innerHTML = "";
				document.getElementById('solution-list-count-main').insert('0');
			}
		}
	});
}


/**
 *	load next/previous set of pro nodes
 */
function loadpros(context,args){

	var types = "Pro";

	if (args['filternodetypes'] == "" || types.indexOf(args['filternodetypes']) == -1) {
		args['filternodetypes'] = types;
	}

	document.getElementById('content-pro-main').style.display = "block";
	const contentprolist = document.getElementById('content-pro-list');
	contentprolist.innerHTML = "";
	contentprolist.appendChild(getLoading("<?php echo $LNG->LOADING_PROS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);

	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){

			try {
				var json = transport.responseText.evalJSON();
			} catch(err) {
				console.log(err);
			}

			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var total = json.nodeset[0].totalno;

			//display nodes
			if(json.nodeset[0].nodes.length > 0){

				//preprosses nodes to add searchid if it is there
				if (args['searchid'] && args['searchid'] != "") {
					var nodes = json.nodeset[0].nodes;
					var count = nodes.length;
					for (var i=0; i < count; i++) {
						var node = nodes[i];
						node.cnode.searchid = args['searchid'];
					}
				}

				//set the count in tab header
				document.getElementById('pro-list-count').innerHTML = "";
				document.getElementById('pro-list-count-main').innerHTML = "";
				document.getElementById('content-pro-list').innerHTML = "";
				document.getElementById('pro-list-title').innerHTML = "";

				if (total > parseInt( args["max"] )) {
					const contentprolist = document.getElementById('content-pro-list');
					contentprolist.innerHTML = "";
					contentprolist.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"pro"));
				}

				document.getElementById('content-pro-main').style.display = "block";
				document.getElementById('pro-result-menu').href = "#proresult";
				document.getElementById('pro-result-menu').className = '';

				document.getElementById('pro-list-count').innerHTML = json.nodeset[0].totalno;
				document.getElementById('pro-list-count-main').innerHTML = json.nodeset[0].totalno;

				if (json.nodeset[0].nodes.length > 1) {
					document.getElementById("pro-list-title").innerHTML = "<?php echo $LNG->PROS_NAME; ?>";
				} else {
					document.getElementById("pro-list-title").innerHTML = "<?php echo $LNG->PRO_NAME; ?>";
				}
				displaySearchNodes(document.getElementById("content-pro-list"),json.nodeset[0].nodes,parseInt(args['start'])+1, true);

				if (total > parseInt( args["max"] )) {
					const contentprolist = document.getElementById('content-pro-list');
					contentprolist.innerHTML = "";
					contentprolist.insert(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"pro"));
				}
			} else {
				document.getElementById('content-pro-main').style.display = "none";
				document.getElementById('content-pro-list').innerHTML = "";
				document.getElementById('pro-result-menu').href = "javascript:return false";
				document.getElementById('pro-result-menu').className = 'inactive';
				document.getElementById('pro-list-count-main').innerHTML = "";
				document.getElementById('pro-list-count-main').insert('0');
			}
		}
	});
}

/**
 *	load next/previous set of evidence nodes
 */
function loadcons(context,args) {

	var types = "Con";

	if (args['filternodetypes'] == "" || types.indexOf(args['filternodetypes']) == -1) {
		args['filternodetypes'] = types;
	}

	document.getElementById('content-con-main').style.display = "block";
	const contentconlist = document.getElementById('content-con-list');
	contentconlist.innerHTML = "";
	contentconlist.appendChild(getLoading("<?php echo $LNG->LOADING_CONS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);

	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){

			try {
				var json = transport.responseText.evalJSON();
			} catch(err) {
				console.log(err);
			}

			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var total = json.nodeset[0].totalno;

			if(json.nodeset[0].nodes.length > 0){

				//preprosses nodes to add searchid if it is there
				if (args['searchid'] && args['searchid'] != "") {
					var nodes = json.nodeset[0].nodes;
					var count = nodes.length;
					for (var i=0; i < count; i++) {
						var node = nodes[i];
						node.cnode.searchid = args['searchid'];
					}
				}

				//set the count in header
				document.getElementById('con-list-count').innerHTML = "";
				document.getElementById('con-list-count-main').innerHTML = "";
				document.getElementById('content-con-list').innerHTML = "";
				document.getElementById('con-list-title').innerHTML = "";

				document.getElementById('content-con-main').style.display = "block";
				document.getElementById('con-result-menu').href = "#conresult";
				document.getElementById('con-result-menu').className = '';

				if (total > parseInt( args["max"] )) {
					const contentconlist = document.getElementById('content-con-list');
					contentconlist.innerHTML = "";
					contentconlist.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"con"));
				}

				document.getElementById('con-list-count').innerHTML = json.nodeset[0].totalno;
				document.getElementById('con-list-count-main').innerHTML = json.nodeset[0].totalno;

				if (json.nodeset[0].nodes.length > 1) {
					document.getElementById("con-list-title").innerHTML = "<?php echo $LNG->CONS_NAME; ?>";
				} else {
					document.getElementById("con-list-title").innerHTML = "<?php echo $LNG->CON_NAME; ?>";
				}

				displaySearchNodes(document.getElementById("content-con-list"),json.nodeset[0].nodes,parseInt(args['start'])+1, true);

				if (total > parseInt( args["max"] )) {
					const contentevidencelist = document.getElementById('content-evidence-list');
					contentevidencelist.innerHTML = "";
					contentevidencelist.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"con"));
				}
			} else {
				document.getElementById('content-con-main').style.display = "none";
				document.getElementById('content-con-list').innerHTML = "";
				document.getElementById('con-result-menu').href = "javascript:return false";
				document.getElementById('con-result-menu').className = 'inactive';
				document.getElementById('con-list-count-main').innerHTML = "";
				document.getElementById('con-list-count-main').insert('0');
			}
		}
	});
}

/**
 *	load next/previous set of users
 */
function loadusers(context,args){

	const contentuserlist = document.getElementById('content-user-list');
	contentuserlist.innerHTML = "";
	contentuserlist.appendChild(getLoading("<?php echo $LNG->LOADING_USERS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getusersby" + context + "&includegroups=false&" + Object.toQueryString(args);

	new Ajax.Request(reqUrl, { method:'get',
		onError: function(error) {
			alert(error);
		},
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var total = json.userset[0].totalno;

			if(json.userset[0].count > 0){

				//preprosses nodes to add searchid if it is there
				if (args['searchid'] && args['searchid'] != "") {
					var users = json.userset[0].users;
					var count = users.length;
					for (var i=0; i < count; i++) {
						var user = users[i].user;
						user.searchid = args['searchid'];
					}
				}

				//set the count in header
				document.getElementById('user-list-count').innerHTML = "";
				document.getElementById('user-list-count-main').innerHTML = "";
				document.getElementById('content-user-list').innerHTML = "";
				document.getElementById('user-list-title').innerHTML = "";

				document.getElementById('user-result-menu').href = "#userresult";
				document.getElementById('user-result-menu').className = '';

				document.getElementById('content-user-main').style.display = "block";

				if (total > parseInt( args["max"] )) {
					const contentuserlist = document.getElementById('content-user-list');
					contentuserlist.innerHTML = "";
					contentuserlist.appendChild(createNav(total,json.userset[0].start,json.userset[0].count,args,context,"users"));
				}

				document.getElementById('user-list-count').innerHTML = json.userset[0].totalno;
				document.getElementById('user-list-count-main').innerHTML = json.userset[0].totalno;

				if (json.userset[0].users.length > 1) {
					document.getElementById("user-list-title").innerHTML = "<?php echo $LNG->USERS_NAME; ?>";
				} else {
					document.getElementById("user-list-title").innerHTML = "<?php echo $LNG->USER_NAME; ?>";
				}

				if (json.userset[0].users.length > 1) {
					var tb2 = new Element("div", {'class':'toolbarrow'});
					var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_NAME; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>'};
					tb2.insert(displaySortForm(sortOpts,args,'user',reorderUsers));
					document.getElementById("content-user-list").appendChild(tb2);
				}

				displayUsers(document.getElementById("content-user-list"),json.userset[0].users,parseInt(args['start'])+1);

				if (total > parseInt( args["max"] )) {
					const contentuserlist = document.getElementById('content-user-list');
					contentuserlist.innerHTML = "";					
					contentuserlist.appendChild(createNav(total,json.userset[0].start,json.userset[0].count,args,context,"users"));
				}
			} else {
				document.getElementById('content-user-main').style.display = "none";
				document.getElementById('content-user-list').innerHTML = "";
				document.getElementById('user-result-menu').href = "javascript:return false";
				document.getElementById('user-result-menu').className = 'inactive';
				document.getElementById('user-list-count-main').innerHTML = "";
				document.getElementById('user-list-count-main').insert('0');
			}
		}
	});
}

/**
 *	load next/previous set of groups
 */
function loadgroups(context,args){

	const contentgrouplist = document.getElementById('content-group-list');
	contentgrouplist.innerHTML = "";
	contentgrouplist.appendChild(getLoading("<?php echo $LNG->LOADING_GROUPS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getgroupsby" + context + "&" + Object.toQueryString(args);

	//alert(reqUrl);

	new Ajax.Request(reqUrl, { method:'get',
		onError: function(error) {
			alert(error);
		},
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var total = json.groupset[0].totalno;

			//alert(json.groupset[0].count);

			if(json.groupset[0].count > 0){

				//preprosses nodes to add searchid if it is there
				if (args['searchid'] && args['searchid'] != "") {
					var groups = json.groupset[0].groups;
					var count = groups.length;
					for (var i=0; i < count; i++) {
						var group = groups[i];
						if (group) {
							group.searchid = args['searchid'];
						}
					}
				}

				//set the count in header
				document.getElementById('group-list-count').innerHTML = "";
				document.getElementById('group-list-count-main').innerHTML = "";
				document.getElementById('content-group-list').innerHTML = "";
				document.getElementById('group-list-title').innerHTML = "";

				document.getElementById('group-result-menu').href = "#groupresult";
				document.getElementById('group-result-menu').className = '';

				document.getElementById('content-group-main').style.display = "block";

				if (total > parseInt( args["max"] )) {
					const contentgrouplist = document.getElementById('content-group-list');
					contentgrouplist.innerHTML = "";
					contentgrouplist.appendChild(createNav(total,json.groupset[0].start,json.userset[0].count,args,context,"groups"));
				}

				document.getElementById('group-list-count').innerHTML = json.groupset[0].totalno;
				document.getElementById('group-list-count-main').innerHTML = json.groupset[0].totalno;

				if (json.groupset[0].groups.length > 1) {
					document.getElementById("group-list-title").innerHTML = "<?php echo $LNG->GROUPS_NAME; ?>";
				} else {
					document.getElementById("group-list-title").innerHTML = "<?php echo $LNG->GROUP_NAME; ?>";
				}

				if (json.groupset[0].groups.length > 1) {
					var tb2 = new Element("div", {'class':'toolbarrow'});
					var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_NAME; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>'};
					tb2.insert(displaySortForm(sortOpts,args,'group',reorderGroups));
					document.getElementById("content-group-list").appendChild(tb2);
				}

				displayGroups(document.getElementById("content-group-list"),json.groupset[0].groups,parseInt(args['start'])+1, "400px","200px", false, true);

				if (total > parseInt( args["max"] )) {
					const contentgrouplist = document.getElementById('content-group-list');
					contentgrouplist.innerHTML = "";					
					contentgrouplist.appendChild(createNav(total,json.groupset[0].start,json.userset[0].count,args,context,"groups"));
				}
			} else {
				document.getElementById('content-group-main').style.display = "none";
				document.getElementById('content-group-list').innerHTML = "";
				document.getElementById('group-result-menu').href = "javascript:return false";
				document.getElementById('group-result-menu').className = 'inactive';
				document.getElementById('group-list-count-main').innerHTML = "";
				document.getElementById('group-list-count-main').insert('0');
			}
		}
	});
}

/**
 *	load comment nodes for search
 */
function loadcomment(context,args) {

	var types = 'Comment';

	if (args['filternodetypes'] == "" || types.indexOf(args['filternodetypes']) == -1) {
		args['filternodetypes'] = types;
	}

	const contentcommentlist = document.getElementById('content-comment-list');
	contentcommentlist.innerHTML = "";
	contentcommentlist.appendChild(getLoading("<?php echo $LNG->LOADING_RESOURCES; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var total = json.nodeset[0].totalno;

			if(total > 0){

				//preprosses nodes to add searchid if it is there
				if (args['searchid'] && args['searchid'] != "") {
					var nodes = json.nodeset[0].nodes;
					var count = nodes.length;
					for (var i=0; i < count; i++) {
						var node = nodes[i];
						node.cnode.searchid = args['searchid'];
					}
				}

				//set the count in header
				document.getElementById('comment-list-count').innerHTML = "";
				document.getElementById('comment-list-count-main').innerHTML = "";
				document.getElementById('content-comment-list').innerHTML = "";
				document.getElementById('comment-list-title').innerHTML = "";

				document.getElementById('content-comment-main').style.display = "block";
				document.getElementById('comment-result-menu').href = "#commentresult";
				document.getElementById('comment-result-menu').className = '';

				if (total > parseInt( args["max"] )) {
					const contentcommentlist = document.getElementById('content-comment-list');
					contentcommentlist.innerHTML = "";
					contentcommentlist.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"comment"));
				}

				document.getElementById('comment-list-count').innerHTML = json.nodeset[0].totalno;
				document.getElementById('comment-list-count-main').innerHTML = json.nodeset[0].totalno;

				if (json.nodeset[0].nodes.length > 1) {
					document.getElementById("comment-list-title").innerHTML = "<?php echo $LNG->COMMENTS_NAME; ?>";
				} else {
					document.getElementById("comment-list-title").innerHTML = "<?php echo $LNG->COMMENT_NAME; ?>";
				}
				displaySearchNodes(document.getElementById("content-comment-list"),json.nodeset[0].nodes,parseInt(args['start'])+1, true);

				if (total > parseInt( args["max"] )) {
					const contentcommentlist = document.getElementById('content-comment-list');
					contentcommentlist.innerHTML = "";
					contentcommentlist.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"comment"));
				}
			} else {
				document.getElementById('content-comment-main').style.display = "none";
				document.getElementById('content-comment-list').innerHTML = "";
				document.getElementById('comment-result-menu').href = "javascript:return false";
				document.getElementById('comment-result-menu').className = 'inactive';
				document.getElementById('comment-list-count-main').innerHTML = "";
				document.getElementById('comment-list-count-main').insert('0');
			}
		}
	});
}

/**
 *	load news nodes for search
 */
function loadnews(context,args) {

	var types = 'News';

	if (args['filternodetypes'] == "" || types.indexOf(args['filternodetypes']) == -1) {
		args['filternodetypes'] = types;
	}

	const contentnewslist = document.getElementById('content-news-list');
	contentnewslist.innerHTML = "";
	contentnewslist.appendChild(getLoading("<?php echo $LNG->LOADING_ITEMS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var total = json.nodeset[0].totalno;

			if(total > 0){

				//preprosses nodes to add searchid if it is there
				if (args['searchid'] && args['searchid'] != "") {
					var nodes = json.nodeset[0].nodes;
					var count = nodes.length;
					for (var i=0; i < count; i++) {
						var node = nodes[i];
						node.cnode.searchid = args['searchid'];
					}
				}

				//set the count in header
				document.getElementById('news-list-count').innerHTML = "";
				document.getElementById('news-list-count-main').innerHTML = "";
				document.getElementById('content-news-list').innerHTML = "";
				document.getElementById('news-list-title').innerHTML = "";

				document.getElementById('content-news-main').style.display = "block";
				document.getElementById('news-result-menu').href = "#newsresult";
				document.getElementById('news-result-menu').className = '';

				if (total > parseInt( args["max"] )) {
					const contentnewslist = document.getElementById('content-news-list');
					contentnewslist.innerHTML = "";
					contentnewslist.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"news"));
				}

				document.getElementById('news-list-count').innerHTML = json.nodeset[0].totalno;
				document.getElementById('news-list-count-main').innerHTML = json.nodeset[0].totalno;

				if (json.nodeset[0].nodes.length > 1) {
					document.getElementById("news-list-title").innerHTML = "<?php echo $LNG->NEWSS_NAME; ?>";
				} else {
					document.getElementById("news-list-title").innerHTML = "<?php echo $LNG->NEWS_NAME; ?>";
				}
				displaySearchNodes(document.getElementById("content-news-list"),json.nodeset[0].nodes,parseInt(args['start'])+1, true);

				if (total > parseInt( args["max"] )) {
					const contentnewslist = document.getElementById('content-news-list');
					contentnewslist.innerHTML = "";
					contentnewslist.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"news"));
				}
			} else {
				document.getElementById('content-news-main').style.display = "none";
				document.getElementById('news-result-menu').href = "javascript:return false";
				document.getElementById('news-result-menu').className = 'inactive';
				document.getElementById('news-list-count-main').innerHTML = "";
				document.getElementById('news-list-count-main').insert('0');
			}
		}
	});
}

/**
 *	Reorder the groups tab
 */
function reorderGroups(){
	// change the sort and orderby ARG values
	GROUP_ARGS['start'] = 0;
	GROUP_ARGS['sort'] = document.getElementById('select-sort-group').options[document.getElementById('select-sort-group').selectedIndex].value;
	GROUP_ARGS['orderby'] = document.getElementById('select-orderby-group').options[document.getElementById('select-orderby-group').selectedIndex].value;

	loadgroups(CONTEXT,GROUP_ARGS);
}

/**
 *	Reorder the users tab
 */
function reorderUsers(){
	// change the sort and orderby ARG values
	USER_ARGS['start'] = 0;
	USER_ARGS['sort'] = document.getElementById('select-sort-user').options[document.getElementById('select-sort-user').selectedIndex].value;
	USER_ARGS['orderby'] = document.getElementById('select-orderby-user').options[document.getElementById('select-orderby-user').selectedIndex].value;

	loadusers(CONTEXT,USER_ARGS);
}

/**
 * show the sort form
 */
function displayUserSortForm(sortOpts,args,tab,handler){

	var sbTool = new Element("span", {'class':'sortback toolbar2 col-auto'});
    sbTool.insert("<?php echo $LNG->SORT_BY; ?>: ");

    var selOrd = new Element("select");
    selOrd.id = "select-orderby-"+tab;
    selOrd.className = "toolbar form-select";
    selOrd.name = "orderby";
 	selOrd.onchange = handler;
    sbTool.insert(selOrd);
    for(var key in sortOpts){
        var opt = new Element("option");
        opt.value=key;
        opt.insert(sortOpts[key].valueOf());
        selOrd.insert(opt);
        if(args.orderby == key){
        	opt.selected = true;
        }
    }
    var sortBys = {ASC: '<?php echo $LNG->SORT_ASC; ?>', DESC: '<?php echo $LNG->SORT_DESC; ?>'};
    var sortBy = new Element("select");
    sortBy.id = "select-sort-"+tab;
    sortBy.className = "toolbar form-select";
    sortBy.name = "sort";
 	sortBy.onchange = handler;
    sbTool.insert(sortBy);
    for(var key in sortBys){
        var opt = new Element("option");
        opt.value=key;
        opt.insert(sortBys[key]);
        sortBy.insert(opt);
        if(args.sort == key){
        	opt.selected = true;
        }
    }

    return sbTool;
}

/**
 * Handle when the sort menus are changed
 */
function handleSort() {
	// used as global holding space
	NODE_ARGS['start'] = 0;
	NODE_ARGS['sort'] = document.getElementById('select-sort-node').options[document.getElementById('select-sort-node').selectedIndex].value;
	NODE_ARGS['orderby'] = document.getElementById('select-orderby-node').options[document.getElementById('select-orderby-node').selectedIndex].value;

	ISSUE_ARGS['start'] = 0;
	ISSUE_ARGS['sort'] = NODE_ARGS['sort'];
	ISSUE_ARGS['orderby'] = NODE_ARGS['orderby'];
	loadissues(CONTEXT,ISSUE_ARGS);

	SOLUTION_ARGS['start'] = 0;
	SOLUTION_ARGS['sort'] = NODE_ARGS['sort'];
	SOLUTION_ARGS['orderby'] = NODE_ARGS['orderby'];
	loadsolutions(CONTEXT,SOLUTION_ARGS);

	PRO_ARGS['start'] = 0;
	PRO_ARGS['sort'] = NODE_ARGS['sort'];
	PRO_ARGS['orderby'] = NODE_ARGS['orderby'];
	loadpros(CONTEXT,PRO_ARGS);

	CON_ARGS['start'] = 0;
	CON_ARGS['sort'] = NODE_ARGS['sort'];
	CON_ARGS['orderby'] = NODE_ARGS['orderby'];
	loadcons(CONTEXT,CON_ARGS);

	COMMENT_ARGS['start'] = 0;
	COMMENT_ARGS['sort'] = NODE_ARGS['sort'];
	COMMENT_ARGS['orderby'] = NODE_ARGS['orderby'];
	loadcomments(CONTEXT,COMMENT_ARGS);

	NEWS_ARGS['start'] = 0;
	NEWS_ARGS['sort'] = NODE_ARGS['sort'];
	NEWS_ARGS['orderby'] = NODE_ARGS['orderby'];
	loadnews(CONTEXT,NEWS_ARGS);
}

/**
 * show the sort form
 */
function displaySortForm(sortOpts){

	var sbTool = new Element("span", {'class':'sortback toolbar2 col-auto'});
    sbTool.insert("<?php echo $LNG->SORT_BY; ?>: ");

    var selOrd = new Element("select");
    selOrd.id = "select-orderby-node";
    selOrd.className = "toolbar form-select";
    selOrd.name = "orderby";
    sbTool.insert(selOrd);
 	selOrd.onchange = handleSort;
    for(var key in sortOpts){
        var opt = new Element("option");
        opt.value=key;
        opt.insert(sortOpts[key].valueOf());
        selOrd.insert(opt);
        if(NODE_ARGS.orderby == key){
        	opt.selected = true;
        }
    }
    var sortBys = {ASC: '<?php echo $LNG->SORT_ASC; ?>', DESC: '<?php echo $LNG->SORT_DESC; ?>'};
    var sortBy = new Element("select");
    sortBy.id = "select-sort-node";
    sortBy.className = "toolbar form-select";
    sortBy.name = "sort";
    sbTool.insert(sortBy);
 	sortBy.onchange = handleSort;
    for(var key in sortBys){
        var opt = new Element("option");
        opt.value=key;
        opt.insert(sortBys[key]);
        sortBy.insert(opt);
        if(NODE_ARGS.sort == key){
        	opt.selected = true;
        }
    }

    return sbTool;
}

/**
 * display Nav
 */
function createNav(total, start, count, argArray, context, type){

	var nav = new Element ("div",{'id':'page-nav', 'class':'toolbarrow pb-3' });

	var header = createNavCounter(total, start, count, type);
	nav.insert(header);

	var pageNav = new Element ("nav",{'aria-label':'Page navigation' }); 
	var pageUL = new Element ("ul",{'class':'pagination' }); 

	if (total > parseInt( argArray["max"] )) {
		//previous
	    var prevSpan = new Element("li", {'id':"nav-previous", "class": "page-link"});
	    if(start > 0){
			prevSpan.update("<i class=\"fas fa-chevron-left fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_PREVIOUS_HINT; ?></span>");
	        prevSpan.classList.add("active");
	        prevSpan.onclick = function() {
	            var newArr = argArray;
	            newArr["start"] = parseInt(start) - newArr["max"];
	            eval("load"+type+"(context,newArr)");
	        };
	    } else {
			prevSpan.update("<i disabled class=\"fas fa-chevron-left fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NO_PREVIOUS_HINT; ?></span>");
	        prevSpan.classList.add("inactive");
	    }

		pageUL.insert(prevSpan);

	    //pages
	    var pageSpan = new Element("span", {'id':"nav-pages"});
	    var totalPages = Math.ceil(total/argArray["max"]);
	    var currentPage = (start/argArray["max"]) + 1;
	    for (var i = 1; i < totalPages+1; i++){
	    	var page = new Element("li", {'class':"nav-page"}).insert(i);
	    	if(i != currentPage){
		    	page.classList.add("active");
		    	var newArr = Object.clone(argArray);
		    	newArr["start"] = newArr["max"] * (i-1) ;
				page.addEventListener("click", function(event) { Pages.next.call(Pages, type, context, newArr); });
	    	} else {
	    		page.classList.add("currentpage");
	    	}
	    	pageUL.insert(page);
	    }

	    //next
	    var nextSpan = new Element("li", {'id':"nav-next", "class": "page-link"});
	    if(parseInt(start)+parseInt(count) < parseInt(total)){
			nextSpan.update("<i class=\"fas fa-chevron-right fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NEXT_HINT; ?></span>");
	        nextSpan.classList.add("active");
	        nextSpan.onclick = function() {
	            var newArr = argArray;
	            newArr["start"] = parseInt(start) + parseInt(newArr["max"]);
	            eval("load"+type+"(context, newArr)");
	        };
	    } else {
			nextSpan.update("<i class=\"fas fa-chevron-right fa-lg\" aria-hidden=\"true\" disabled></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NO_NEXT_HINT; ?></span>");
	        nextSpan.classList.add("inactive");
	    }

		pageUL.insert(nextSpan);

	    if( start > 0 || (parseInt(start)+parseInt(count) < parseInt(total))){
			pageNav.insert(pageUL);
			nav.insert(pageNav);
	    }
	}

	return nav;
}

/**
 * display nav header
 */
function createNavCounter(total, start, count, type){

    if(count != 0){
    	var objH = new Element("span",{'class':'nav'});
    	var s1 = parseInt(start)+1;
    	var s2 = parseInt(start)+parseInt(count);
        objH.insert("<strong>" + s1 + " <?php echo $LNG->LIST_NAV_TO; ?> " + s2 + " (" + total + ")</strong>");
    }
    return objH;
}

var Pages = {
	next: function(e){
		var data = $A(arguments);
		eval("load"+data[1]+"(data[2],data[3])");
	}
};