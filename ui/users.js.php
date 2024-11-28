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
 * Javascript functions for drawing a list of users
 */
function displayUsers(objDiv,users,start){
	var lOL = document.createElement("ol", {'class':'user-list-ol user-list-tab-view'});
	for(var i=0; i< users.length; i++){
		if(users[i].user){
			var iUL = document.createElement("li", {'id':users[i].user.userid, 'class':'user-list-li'});
			lOL.appendChild(iUL);
			var blobDiv = document.createElement("div", {'class':'user-blob'});
			var blobUser = renderUser(users[i].user);
			blobDiv.appendChild(blobUser);
			iUL.appendChild(blobDiv);
		}
	}
	objDiv.appendChild(lOL);
}

/**
 * Javascript functions for drawing a list of groups
 */
function displayGroups(objDiv,groups,start, mainheading, cropdesc){
	var lOL = document.createElement("div", {'start':start, 'class':'groups-div'});
	for(var i=0; i< groups.length; i++){
		if(groups[i].group){
			var blobDiv = document.createElement("div", {'class':'d-inline-block m-2'});
			var blobUser = renderGroup(groups[i].group, mainheading, cropdesc);
			blobDiv.appendChild(blobUser);
			lOL.appendChild(blobDiv);
		}
	}
	objDiv.appendChild(lOL);
}


/**
 * Javascript functions for drawing a list of groups
 * Used for not logged in homepage items
 */
function displayHomeGroups(objDiv,groups,start, width, height){
	var lOL = document.createElement("div", {'start':start, 'class':'home-groups'});
	for(var i=0; i< groups.length; i++){
		if(groups[i].group){
			var blobDiv = document.createElement("div", {'class':'d-inline-flex m-2'});
			var blobUser = renderHomeGroup(groups[i].group, width, height);
			blobDiv.appendChild(blobUser);
			lOL.appendChild(blobDiv);
		}
	}
	objDiv.appendChild(lOL);
}

/**
 * Javascript functions for drawing a list of my groups
 */
function displayMyGroups(objDiv,groups,start){
	var lOL = document.createElement("div", {'start':start, 'class':'groups-div'});
	for(var i=0; i< groups.length; i++){
		if(groups[i].group){
			var blobDiv = document.createElement("div", {'class':'d-inline-block m-2'});
			var blobUser = renderMyGroup(groups[i].group);
			blobDiv.appendChild(blobUser);
			lOL.appendChild(blobDiv);
		}
	}
	objDiv.appendChild(lOL);
}

/**
 * Javascript functions for drawing list of users in a widget
 */
function displayWidgetUsers(objDiv,users,start){
	var lOL = document.createElement("ol", {'class':'user-list-ol user-dashboard-view'});
	for(var i=0; i< users.length; i++){
		if(users[i].user){
			var iUL = document.createElement("li", {'id':users[i].user.userid, 'class':'user-list-li'});
			lOL.appendChild(iUL);
			var blobDiv = document.createElement("div", {'class':'user-blob'});
			var blobUser = renderWidgetUser(users[i].user);
			blobDiv.appendChild(blobUser);
			iUL.appendChild(blobDiv);
		}
	}
	objDiv.appendChild(lOL);
}

/**
 * Javascript functions for drawing a list of users in a report
 */
function displayReportUsers(objDiv,users,start){
	for(var i=0; i< users.length; i++){
		if(users[i].user){
			var iUL = document.createElement("span", {'id':users[i].user.userid, 'class':'idea-list-li'});
			objDiv.appendChild(iUL);
			var blobDiv = document.createElement("div", {'class':' '});
			var blobUser = renderReportUser(users[i].user);
			blobDiv.appendChild(blobUser);
			iUL.appendChild(blobDiv);
		}
	}
}

/**
 * Makes ajax call for the current user to follow a person with the userid of the given obj.
 */
async function followUser(obj) {
	var reqUrl = SERVICE_ROOT + "&method=addfollowing&itemid="+obj.userid;
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}	
		obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("following.png"); ?>');
		obj.setAttribute('title', '<?php echo $LNG->USERS_UNFOLLOW; ?>');
		obj.onclick = function() { unfollowUser(this); };
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

/**
 * Makes ajax call for the current user to unfollow a person with the userid of the given obj.
 */
async function unfollowUser(obj) {
	var reqUrl = SERVICE_ROOT + "&method=deletefollowing&itemid="+obj.userid;
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}	
		obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("follow.png"); ?>');
		obj.setAttribute('title', '<?php echo $LNG->USERS_FOLLOW; ?>');
		obj.onclick = function() {
  			followUser(this);
		};
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}


/**
 *  Makes ajax call to follow the given userid. Called from user home page follow list.
 */
async function followMyUser(userid) {
	var reqUrl = SERVICE_ROOT + "&method=addfollowing&itemid="+userid;
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}		
		try {
			window.location.reload(true);
		} catch(err) {
			//do nothing
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

/**
 * Makes ajax call to unfollow the given userid. Called from user home page follow list.
 */
async function unfollowMyUser(userid) {
	var reqUrl = SERVICE_ROOT + "&method=deletefollowing&itemid="+userid;
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}		
		try {
			window.location.reload(true);
		} catch(err) {
			//do nothing
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}


/**
 * Send a spam alert to the server.
 */
async function reportGroupSpamAlert(obj, group) {
	var ans = confirm("Are you sure you want to report \n\n"+group.name+"\n\nas an Inappropriate?\n\n");
	if (ans){
		var reqUrl = URL_ROOT + "ui/admin/spamalert.php?type=user&id="+obj.id;
		try {
			const json = await makeAPICall(reqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}			
			obj.setAttribute('alt', '<?php echo $LNG->SPAM_GROUP_REPORTED_ALT; ?>');
			obj.setAttribute('title', '<?php echo $LNG->SPAM_GROUP_REPORTED; ?>');
			obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("flag-grey.png"); ?>');				
			obj.style.cursor = 'auto';
			obj.unbind("click");
			obj.onclick = null;
			if (group !== undefined) {
				group.status = 1;
			}
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}			
	}
}

/**
 * Send a spam alert to the server.
 */
async function reportUserSpamAlert(obj, user) {
	var ans = confirm("Are you sure you want to report \n\n"+obj.dataset.label+"\n\nas a Spammer / Inappropriate?\n\n");
	if (ans){
		var reqUrl = URL_ROOT + "ui/admin/spamalert.php?type=user&id="+obj.id;
		try {
			const json = await makeAPICall(reqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}			
			obj.setAttribute('alt', '<?php echo $LNG->SPAM_USER_REPORTED_ALT; ?>');
			obj.setAttribute('title', '<?php echo $LNG->SPAM_USER_REPORTED; ?>');
			obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("flag-grey.png"); ?>');				
			obj.style.cursor = 'auto';
			obj.unbind("click");
			obj.onclick = null;
			if (user !== undefined) {
				user.status = 1;
			}
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}			
	}
}

/**
 * Create a span button to report spam / show spam reported / or say login to report.
 *
 * @param group, the group to report
 */
function createGroupSpamButton(group) {

	// Add spam icon
	var spamimg = document.createElement('img');
	spamimg.classList.add('spamicon');
	if(USER != ""){
		if (group.status == <?php echo $CFG->USER_STATUS_REPORTED; ?>) {
			spamimg.setAttribute('alt', '<?php echo $LNG->SPAM_GROUP_REPORTED_ALT; ?>');
			spamimg.setAttribute('title', '<?php echo $LNG->SPAM_GROUP_REPORTED; ?>');
			spamimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("flag-grey.png"); ?>');
		} else if (group.status == <?php echo $CFG->USER_STATUS_ACTIVE; ?>) {
			spamimg.setAttribute('alt', '<?php echo $LNG->SPAM_GROUP_REPORT_ALT; ?>');
			spamimg.setAttribute('title', '<?php echo $LNG->SPAM_GROUP_REPORT; ?>');
			spamimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("flag.png"); ?>');
			spamimg.classList.add('active');
			spamimg.id = group.groupid;
			spaming.onclick = function () { reportGroupSpamAlert(this, group) };
		}
	} else {
		spamimg.setAttribute('alt', '<?php echo $LNG->SPAM_GROUP_LOGIN_REPORT_ALT; ?>');
		spamimg.setAttribute('title', '<?php echo $LNG->SPAM_GROUP_LOGIN_REPORT; ?>');
		spamimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("flag-grey.png"); ?>');
	}
	return spamimg;
}

/**
 * Create a span button to report spam / show spam reported / or say login to report.
 *
 * @param user the user to report
 */
function createUserSpamButton(user) {

	// Add spam icon
	var spamimg = document.createElement('img');
	spamimg.classList.add('spamicon');
	if(USER != ""){
		if (user.status == <?php echo $CFG->USER_STATUS_REPORTED; ?>) {
			spamimg.setAttribute('alt', '<?php echo $LNG->SPAM_USER_REPORTED_ALT; ?>');
			spamimg.setAttribute('title', '<?php echo $LNG->SPAM_USER_REPORTED; ?>');
			spamimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("flag-grey.png"); ?>');
		} else if (user.status == <?php echo $CFG->USER_STATUS_ACTIVE; ?>) {
			spamimg.setAttribute('alt', '<?php echo $LNG->SPAM_USER_REPORT_ALT; ?>');
			spamimg.setAttribute('title', '<?php echo $LNG->SPAM_USER_REPORT; ?>');
			spamimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("flag.png"); ?>');
			spamimg.classList.add('active');
			spamimg.id = user.userid;
			spamimg['data-label'] = user.name;
			spamimg.onclick = function (){ reportUserSpamAlert(this, user) };
		}
	} else {
		spamimg.setAttribute('alt', '<?php echo $LNG->SPAM_USER_LOGIN_REPORT_ALT; ?>');
		spamimg.setAttribute('title', '<?php echo $LNG->SPAM_USER_LOGIN_REPORT; ?>');
		spamimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("flag-grey.png"); ?>');
	}
	return spamimg;
}

/**
 * Draw a single user item in a list.
 */
function renderUser(user){

	var uDiv = document.createElement("div",{id:'context', "class": "row"});

	var nodetableDiv = document.createElement("div", {'class':' '});
	uDiv.appendChild(nodetableDiv);

	var nodeTable = document.createElement( 'div', {'class':'nodetable boxborder boxbackground'} );

	nodetableDiv.appendChild(nodeTable);

	var row = document.createElement( 'div', {'class':'nodetablerow'} );
	nodeTable.appendChild(row);

	var imageCell = document.createElement( 'div', {'class':'nodetablecelltop'} );
	row.appendChild(imageCell);

	var imageDiv = document.createElement("div");

	var imageObj = document.createElement('img',{'alt':user.name, 'title': user.name, 'src': user.photo});

	var imagelink = document.createElement('a');
	if (user.searchid && user.searchid != "") {
		imagelink.href = URL_ROOT+"user.php?userid="+user.userid+"&sid="+user.searchid;
	} else {
		imagelink.href = URL_ROOT+"user.php?userid="+user.userid;
	}

	imagelink.appendChild(imageObj);
	imageDiv.appendChild(imagelink);
	imageCell.appendChild(imageDiv);
	imageCell.title = '<?php echo $LNG->NODE_DETAIL_BUTTON_HINT; ?>';

	var textCell = document.createElement( 'div', {'class':'nodetablecelltop'} );
	row.appendChild(textCell);

	var textDiv = document.createElement('div', {'class':' '});
	textCell.appendChild(textDiv);

	var uiDiv = document.createElement("div",{id:'contextinfo', "class":"col contextinfo"});
	
	if (user.searchid && user.searchid != "") {
		uiDiv.innerHTML += "<b><a href='user.php?userid="+ user.userid +"&sid="+user.searchid+"'>" + user.name + "</a></b>";
	} else {
		uiDiv.innerHTML += "<b><a href='user.php?userid="+ user.userid +"'>" + user.name + "</a></b>";
	}

	<?php if ($CFG->SPAM_ALERT_ON) { ?>
	// Add spam icon
	var spamDiv = document.createElement("div");
	spamDiv.appendChild(createUserSpamButton(user));
	imageCell.appendChild(spamDiv);
	<?php } ?>

	if(USER != ""){
		var followDiv = document.createElement("div");
		var followbutton = document.createElement('img');
		followbutton.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("follow.png"); ?>');
		followbutton.setAttribute('alt', '<?php echo $LNG->USERS_FOLLOW_ICON_ALT; ?>');
		followbutton.setAttribute('id','follow'+user.userid);
		followbutton.userid = user.userid;
		followDiv.appendChild(followbutton);
		if (user.userfollow && user.userfollow == "Y") {
			follow.onclick =  function (){ unfollowUser(this) };
			followbutton.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("following.png"); ?>');
			followbutton.setAttribute('title', '<?php echo $LNG->USERS_UNFOLLOW; ?>');
		} else {
			followbutton.onclick = function (){ followUser(this) };
			followbutton.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("follow.png"); ?>');
			followbutton.setAttribute('title', '<?php echo $LNG->USERS_FOLLOW; ?>');
		}
		uiDiv.appendChild(followDiv);
	}

	var str = "<div>";
	if (user.creationdate && user.creationdate > 0) {
		var cDate = new Date(user.creationdate*1000);
		str += "<span class=\"user-date-joined\"><b><?php echo $LNG->USERS_DATE_JOINED; ?> </b>"+cDate.format(DATE_FORMAT)+"</span>";
	} else {
		var cDate = new Date(user.creationdate*1000);
		str += "<span class=\"user-date-joined\"><b><?php echo $LNG->USERS_DATE_JOINED; ?> </b>"+cDate.format(DATE_FORMAT)+"</span>";
	}
	if (user.lastactive && user.lastactive > 0) {
		var cDate = new Date(user.lastactive*1000);
		str += "<span class=\"user-last-active\"><b><?php echo $LNG->USERS_LAST_ACTIVE; ?> </b>"+cDate.format(TIME_FORMAT)+"</span>";
	} else {
		var cDate = new Date(user.lastlogin*1000);
		str += "<span class=\"user-last-login\"><b><?php echo $LNG->USERS_LAST_LOGIN; ?> </b>"+cDate.format(TIME_FORMAT)+"</span>";
	}
	uiDiv.innerHTML += str+"</div>";

	if(user.description != ""){
		uiDiv.innerHTML += "<div>"+user.description+"</div>";
	}
	if(user.website != ""){
        uiDiv.innerHTML += "<div><a href='"+user.website+"' target='_blank'>"+user.website+"</a></div>";
    }

	textCell.appendChild(uiDiv);
	return uDiv;
}

/**
 * Draw a single group item in a list.
 */
function renderGroup(group, mainheading, cropdesc){

	var iDiv = document.createElement("div", {'class':'card border-0 my-2'});

	var nodetableDiv = document.createElement("div", {'class':'card-body pb-1'});
	var nodeTable = document.createElement( 'div', {'class':'nodetableGroup border border-2'} );

	nodetableDiv.appendChild(nodeTable);

	var row = document.createElement( 'div', {'class':'d-flex flex-row'} );
	nodeTable.appendChild(row);

	var imageCell = document.createElement( 'div', {'class':'p-2'} );
	row.appendChild(imageCell);

	var imageObj = document.createElement('img',{'alt':group.name, 'title': group.name, 'src': group.photo});
	var imagelink = document.createElement('a', {'href':URL_ROOT+"group.php?groupid="+group.groupid });

	imagelink.appendChild(imageObj);
	imageCell.appendChild(imagelink);

	var textCell = document.createElement( 'div', {'class':'p-2'} );
	row.appendChild(textCell);

	var title = group.name;
	var description = group.description;

	if (mainheading) {
		var exploreButton = document.createElement('h1');
		textCell.appendChild(exploreButton);
		exploreButton.innerHTML += title;
	} else {
		var exploreButton = document.createElement('a', {'title':'<?php echo $LNG->NODE_DETAIL_BUTTON_HINT; ?>', 'class':''});
		if (group.searchid && group.searchid != "") {
			exploreButton.href= "<?php echo $CFG->homeAddress; ?>group.php?groupid="+group.groupid+"&sid="+group.searchid;
		} else {
			exploreButton.href= "<?php echo $CFG->homeAddress; ?>group.php?groupid="+group.groupid;
		}
		exploreButton.innerHTML += title;
		textCell.appendChild(exploreButton);
	}
	
	if (description != "") {
		if (mainheading) {
			var textDivinner = document.createElement('div', {'class':' '});
			textDivinner.innerHTMl = description;
			textCell.appendChild(textDivinner);
		} else {
			if (description != "" && title.length <=80) {
				var plaindesc = removeHTMLTags(description);
				var hint = plaindesc;
				var croplength = 110-title.length;
				if (plaindesc && plaindesc.length > croplength) {
					hint = plaindesc;
					var plaincrop = plaindesc.substr(0,croplength)+"...";
					textCell.innerHTML += '<p title="'+hint+'">'+plaincrop+'</p>';
				} else {
					textCell.innerHTML += '<p>'+plaindesc+'</p>';
				}
			}
		}			
	}
	
	// Show any associated url on the main group page only.
	if(mainheading && group.website != ""){
		textCell.innerHTML += "<div style='float:left;margin-bottom:5px;'><a href='"+group.website+"' target='_blank' style='word-wrap:break-word;overflow-wrap: break-word;'>"+group.website+"</a></div>";
    }

	var rowToolbar = document.createElement( 'div', {'class':'nodetablerow'} );
	nodeTable.appendChild(rowToolbar);

	var toolbarCell = document.createElement( 'div', {'class':'nodetablecellbottom'} );
	rowToolbar.appendChild(toolbarCell);

	var userDiv = document.createElement("div", {'class':'nodetablecellbottom'} );
	toolbarCell.appendChild(userDiv);

	var toolbarDivOuter = document.createElement("div", {'class':'nodetablecellbottom'} );
	rowToolbar.appendChild(toolbarDivOuter);

	var toolbarDiv = document.createElement("div", {'class':'d-flex justify-content-end'} );
	toolbarDivOuter.appendChild(toolbarDiv);

	// IF OWNER MANAGE GROUPS
	if (mainheading) {
		if (NODE_ARGS['isgroupadmin'] == "true") {
			toolbarDiv.innerHTML += '<span class="active p-2 editgroup-link" onclick="loadDialog(\'editgroup\',\'<?php echo $CFG->homeAddress?>ui/popups/groupedit.php?groupid='+group.groupid+'\', 900,800);"><?php echo $LNG->GROUP_MANAGE_TITLE; ?></span>';
		}
	}

	if (mainheading) {
		<?php if ($CFG->SPAM_ALERT_ON) { ?>
	    // Add spam icon
	    const spamDiv = document.createElement("div");
		spamDiv.className = "p-2";
		const item = createGroupSpamButton(group);
	    spamDiv.appendChild(item);
	    toolbarDiv.appendChild(spamDiv);
	    <?php } ?>

		var jsonldButton = document.createElement("div", {'class':'p-2', 'title':'<?php echo $LNG->GRAPH_JSONLD_HINT_GROUP;?>'});
		var jsonldButtonicon = document.createElement("img", {'src':"<?php echo $HUB_FLM->getImagePath('json-ld-data-24.png'); ?>", 'alt':'API call'});
		jsonldButton.insappendChildert(jsonldButtonicon);
		var jsonldButtonhandler = function() {
			var code = URL_ROOT+'api/conversations/'+NODE_ARGS['groupid'];
			textAreaPrompt('<?php echo $LNG->GRAPH_JSONLD_MESSAGE_GROUP; ?>', code, "", "", "");
		};
		jsonldButton.onclick = jsonldButtonhandler;
		toolbarDiv.appendChild(jsonldButton);
	}

	var statstableDiv = document.createElement("div", {'class':'card-footer border-0 bg-white py-0 text-center'});
	var statsTable = document.createElement( 'div', {'class':'nodetable'} );
	statstableDiv.appendChild(statsTable);

	var innerRowStats = document.createElement( 'div', {'class':'row'} );
	statsTable.appendChild(innerRowStats);

	var innerStatsCellPeople = document.createElement( 'div', {'class':'col-auto'} );
	innerRowStats.appendChild(innerStatsCellPeople);

	innerStatsCellPeople.innerHTML += '<p><strong><?php echo $LNG->GROUP_BLOCK_STATS_PEOPLE; ?></strong>'+'<span> '+group.membercount+'</span></p>';

	var innerStatsCellDebates = document.createElement( 'div', {'class':'col-auto'} );
	innerRowStats.appendChild(innerStatsCellDebates);

	innerStatsCellDebates.innerHTML += '<p><strong><?php echo $LNG->GROUP_BLOCK_STATS_ISSUES;?></strong>'+'<span> '+group.debatecount+'</span></p>';

	var innerStatsCellVotes = document.createElement( 'div', {'class':'col-auto'} );
	innerRowStats.appendChild(innerStatsCellVotes);

	innerStatsCellVotes.innerHTML += '<p><strong><?php echo $LNG->GROUP_BLOCK_STATS_VOTES;?></strong>'+'<span> '+group.votes+'</span></p>';

	iDiv.appendChild(nodetableDiv);
	iDiv.appendChild(statstableDiv);

	return iDiv;
}

/**
 * Draw a single group item in a list on the homepage.
 */
function renderHomeGroup(group, width, height){	
	var iDiv = document.createElement("div", {'class':'card border-0 my-2'});

	var nodetableDiv = document.createElement("div", {'class':'card-body p-1'});
	var nodeTable = document.createElement( 'div', {'class':'nodetable border border-2'} );

	nodetableDiv.appendChild(nodeTable);

	var row = document.createElement( 'div', {'class':'d-flex flex-row'} );
	nodeTable.appendChild(row);

	var imageCell = document.createElement( 'div', {'class':'p-2'} );
	row.appendChild(imageCell);

	var imageObj = document.createElement('img',{'alt':group.name, 'title': group.name, 'src': group.photo});
	var imagelink = document.createElement('a', {'href':URL_ROOT+"group.php?groupid="+group.groupid });

	imagelink.appendChild(imageObj);
	imageCell.appendChild(imagelink);

	var textCell = document.createElement( 'div', {'class':'p-2'} );
	row.appendChild(textCell);

	var textDiv = document.createElement('div', {'class':' '});
	textCell.appendChild(textDiv);

	var title = group.name;

	var exploreButton = document.createElement('a', {'title':'<?php echo $LNG->NODE_DETAIL_BUTTON_HINT; ?>'});
	if (group.searchid && group.searchid != "") {
		exploreButton.href= "<?php echo $CFG->homeAddress; ?>group.php?groupid="+group.groupid+"&sid="+group.searchid;
	} else {
		exploreButton.href= "<?php echo $CFG->homeAddress; ?>group.php?groupid="+group.groupid;
	}
	exploreButton.innerHTML += title;
	textDiv.appendChild(exploreButton);

	var statstableDiv = document.createElement("div", {'class':'card-footer border-0 bg-white py-0 text-center'});
	var statsTable = document.createElement( 'div', {'class':'nodetable'} );
	statstableDiv.appendChild(statsTable);

	var innerRowStats = document.createElement( 'div', {'class':'row'} );
	statsTable.appendChild(innerRowStats);

	var innerStatsCellPeople = document.createElement( 'div', {'class':'col-auto'} );
	innerRowStats.appendChild(innerStatsCellPeople);

	innerStatsCellPeople.innerHTML += '<p class="mb-0"><strong><?php echo $LNG->GROUP_BLOCK_STATS_PEOPLE; ?></strong>'+
	'<span> '+group.membercount+'</span></p>';

	var innerStatsCellDebates = document.createElement( 'div', {'class':'col-auto'} );
	innerRowStats.appendChild(innerStatsCellDebates);

	innerStatsCellDebates.innerHTML += '<p class="mb-0"><strong><?php echo $LNG->GROUP_BLOCK_STATS_ISSUES;?></strong>'+
	'<span> '+group.debatecount+'</span></p>';

	var innerStatsCellVotes = document.createElement( 'div', {'class':'col-auto'} );
	innerRowStats.appendChild(innerStatsCellVotes);

	innerStatsCellVotes.innerHTML += '<p class="mb-0"><strong><?php echo $LNG->GROUP_BLOCK_STATS_VOTES;?></strong>'+
	'<span> '+group.votes+'</span></p>';

	iDiv.appendChild(nodetableDiv);
	iDiv.appendChild(statstableDiv);

	return iDiv;
}

/**
 * Draw a single group item in a list.
 */
function renderMyGroup(group){

	var iDiv = document.createElement("div", {'class':'card border-0 my-2'});

	var nodetableDiv = document.createElement("div", {'class':'card-body pb-1'});
	var nodeTable = document.createElement( 'div', {'class':'nodetableGroup border border-2'} );

	nodetableDiv.appendChild(nodeTable);

	var row = document.createElement( 'div', {'class':'d-flex flex-row'} );
	nodeTable.appendChild(row);

	var imageCell = document.createElement( 'div', {'class':'p-2'} );
	row.appendChild(imageCell);

	var imageObj = document.createElement('img',{'alt':group.name, 'title': group.name,'src': group.photo});
	var imagelink = document.createElement('a', {
		'href':URL_ROOT+"group.php?groupid="+group.groupid
	});

	imagelink.appendChild(imageObj);
	imageCell.appendChild(imagelink);
	imageCell.title = '<?php echo $LNG->NODE_DETAIL_BUTTON_HINT; ?>';

	var textCell = document.createElement( 'div', {'class':'nodetablecelltop'} );
	row.appendChild(textCell);

	var textDiv = document.createElement('div', {'class':'m-1'});
	textCell.appendChild(textDiv);

	var title = group.name;
	var description = group.description;

	var exploreButton = document.createElement('a', {'title':'<?php echo $LNG->NODE_DETAIL_BUTTON_HINT; ?>', 'class':'active'});
	if (group.searchid && group.searchid != "") {
		exploreButton.href= "<?php echo $CFG->homeAddress; ?>group.php?groupid="+group.groupid+"&sid="+group.searchid;
	} else {
		exploreButton.href= "<?php echo $CFG->homeAddress; ?>group.php?groupid="+group.groupid;
	}
	exploreButton.innerHTML += title;
	textDiv.appendChild(exploreButton);
	textDiv.innerHTML += "<br />";

	if (description != "") {
		var plaindesc = removeHTMLTags(description);
		var hint = description;
		if (plaindesc.length > 90) {
			hint = plaindesc;
			plaindesc = plaindesc.substr(0,90)+"...";
			textDiv.innerHTML += '<p title="'+hint+'">'+plaindesc+'</p>';
		} else {
			textDiv.innerHTML += '<p>'+plaindesc+'</p>';
		}
	}

	var rowToolbar = document.createElement( 'div', {'class':'nodetablerow'} );
	nodeTable.appendChild(rowToolbar);

	var toolbarCell = document.createElement( 'div', {'class':'nodetablecellbottom'} );
	rowToolbar.appendChild(toolbarCell);

	var userDiv = document.createElement("div", {'class':'nodetablecellbottom'} );
	toolbarCell.appendChild(userDiv);

	var toolbarDivOuter = document.createElement("div", {'class':'nodetablecellbottom'} );
	rowToolbar.appendChild(toolbarDivOuter);

	var toolbarDiv = document.createElement("div", {'class':'text-end m-2'} );
	toolbarDivOuter.appendChild(toolbarDiv);

	// IF OWNER MANAGE GROUPS
	if (USER != "" && group.members[0].userset.users) {
		var members = group.members[0].userset.users;
		for(var i=0; i<members.length; i++) {
			var member = members[i].user;
			if (member.userid == USER && member.isAdmin) {
				toolbarDiv.innerHTML += '<span class="active p-2 editgroup-link" onclick="loadDialog(\'editgroup\',\'<?php echo $CFG->homeAddress?>ui/popups/groupedit.php?groupid='+group.groupid+'\', 900,800);"><?php echo $LNG->GROUP_MANAGE_SINGLE_TITLE; ?></span>';
				break;
			}
		}
	}

	var jsonldButton = document.createElement("div", {'style':'float:right;padding-right:5px;', 'title':'<?php echo $LNG->GRAPH_JSONLD_HINT_GROUP;?>'});
	var jsonldButtonicon = document.createElement("img", {'style':'vertical-align:middle','src':"<?php echo $HUB_FLM->getImagePath('json-ld-data-24.png'); ?>", 'alt':'json LD Data'});
	jsonldButton.appendChild(jsonldButtonicon);
	var jsonldButtonhandler = function() {
		var code = URL_ROOT+'api/conversations/'+group.groupid;
		textAreaPrompt('<?php echo $LNG->GRAPH_JSONLD_MESSAGE_GROUP; ?>', code, "", "", "");
	};
	jsonldButton.onclick = jsonldButtonhandler;
	toolbarDiv.appendChild(jsonldButton);

	iDiv.appendChild(nodetableDiv);

	return iDiv;
}

/**
 * Draw a single user entry in a widget list.
 */
function renderWidgetUser(user){

	var uDiv = document.createElement("div",{id:'context'});
	var imgDiv = document.createElement("div", {'style':'clear:both;float:left'});
	var cI = document.createElement("div", {'class':'idea-user2', 'style':'clear:both;float:left;'});
	if(user.isgroup == 'Y'){
		cI.innerHTML += "<a href='group.php?groupid="+ user.userid +"'><img border='0' src='"+user.thumb+"'/></a>";
	} else {
		cI.innerHTML += "<a href='user.php?userid="+ user.userid +"'><img border='0' src='"+user.thumb+"'/></a>";
	}

	imgDiv.appendChild(cI);

	var uiDiv = document.createElement("div", {'style':'float:left;'});
	if(user.isgroup == 'Y'){
		uiDiv.innerHTML += "<b><a href='group.php?groupid="+ user.userid +"'>" + user.name + "</a></b>";
	} else {
		uiDiv.innerHTML += "<b><a href='user.php?userid="+ user.userid +"'>" + user.name + "</a></b>";
	}
	if (user.followdate){
		var cDate = new Date(user.followdate*1000);
		uiDiv.innerHTML += "<br /><b><?php echo $LNG->USERS_STARTED_FOLLOWING_ON; ?> </b>"+ cDate.format(DATE_FORMAT);
	}

	imgDiv.appendChild(uiDiv);
	uDiv.appendChild(imgDiv);

	uDiv.innerHTML += "<div style='clear:both'></div>";
	return uDiv;
}

/**
 * Draw a single user entry in a report list.
 */
function renderReportUser(user){

	var uDiv = document.createElement("div",{id:'context'});
	var imgDiv = document.createElement("div", {'style':'clear:both;float:left'});

	var uiDiv = document.createElement("div", {'style':'float:left;'});
	uiDiv.innerHTML += "<div style='float:left;width:600px;'>"+user.name+"</div>";

	imgDiv.appendChild(uiDiv);
	uDiv.appendChild(imgDiv);

	uDiv.innerHTML += "<div style='clear:both'></div>";
	return uDiv;
}
