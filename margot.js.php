<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2019 The Open University UK                                   *
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
?>

var highlightcolour = "#ffff99";

function processMargotResults(data) {
    document.body.style.cursor ="wait";
	document.getElementById('margotcount').innerHTML = "0";

    try {
		var json = JSON.parse(data);
		//console.log(json);
		var highlights = json["@graph"];

		var count = highlights.length;
		var item;

		var selection = "";
		if (window.getSelection) {
			selection = window.getSelection();
		} else if (document.getSelection) {
			selection = document.getSelection();
		} else if (document.selection) {
			selection = document.selection.createRange().text;
		}

		selection.removeAllRanges();

		document.getElementById('margotcount').innerHTML = count;

		for (var i=0; i<count; i++) {
			item = highlights[i];
			searchText(item, i, selection);
		}

		// toggle all argument divs
		var elements = document.getElementsByName("ideaargumentlink");
		for(var i = 0; i < elements.length; i++) {
			elements[i].click();
  		}
    } catch (e) {
        alert("Issuing parsing Margot results: "+e);
    }

    document.body.style.cursor ="default";
}

function searchText(item, i, selection) {
	var searchTerm = item["@text"];
	searchTerm = decodeHtml(searchTerm); // clean &amp; etc..

	//JSON parse has removed the unicode character that is actually there so this does not help
	//searchTerm = decodeURIComponent(searchTerm); //clean /u etc..

	var id = item["@id"];
	if (id.indexOf('main_site:nodes/') != -1) {
		id = id.replace('main_site:nodes/', '');
	}

	var itemFoundCount = 0
	var highlightColour = "#FFFF80";

	try {
		if (searchTerm == "") {
			return;
		}

		var fromElement = document.body;
		if (item["@from"] == "title") {
			var query = '[id^="desctoggle'+id+'"]';
			//console.log(query);
			fromElement = document.querySelector(query);
		} else if (item["@from"] == "description") {
			var query = '[id^="desc'+id+'"]';
			//console.log(query);
			fromElement = document.querySelector(query);
		} else {
			console.log("OTHER");
			console.log(item);
		}
		if (fromElement == null) {
			fromElement = document.body;
			console.log("Could not find:"+id+":"+item["@from"]);
		}

		//console.log(fromElement);

		var walker = document.createTreeWalker(fromElement, NodeFilter.SHOW_TEXT,
						null,
						true
						);

		var allText = "";

		var highlightNodes = new Array();

		searchTerm = searchTerm.replace(/\s+/g, " ");

		while (walker.nextNode()) {
	   		var node = walker.currentNode;
	   		//console.log(node);
	   		if (node.nodeType == Node.TEXT_NODE) {
				//textNodes[textNodes.length] = node
		   		allText += node.nodeValue;
				allText = allText.replace(/\s+/g, " ");

				//console.log(allText);

		   		var ind = allText.indexOf(searchTerm);
		   		if (ind > -1) {
		   			highlightNodes[highlightNodes.length] = node;
		   			break;
		   		}
		   	}
		}

		//console.log(highlightNodes.length+":"+searchTerm);

		if (highlightNodes.length > 0) {
			var reverseText = highlightNodes[0].nodeValue;
	   		reverseText = reverseText.replace(/\s+/g, " ");
			var ind = reverseText.indexOf(searchTerm);
		 	if (ind == -1) {
				while (walker.previousNode()) {
			   		var node = walker.currentNode;
	   				if (node.nodeType == Node.TEXT_NODE) {
						reverseText = node.nodeValue + reverseText;
		   				reverseText = reverseText.replace(/\s+/g, " ");
				   		var ind = reverseText.indexOf(searchTerm);
						highlightNodes[highlightNodes.length] = node;
				   		if (ind > -1) {
				   			break;
				   		}
				   	}
				}
			}

			highlightNodes.reverse();

			var count = highlightNodes.length;


			var splitText = searchTerm.split(' ');

			if (count > 0) {
				itemFoundCount ++;
			}

			var range = document.createRange();

			if (count == 1) {
				var node = highlightNodes[0];
				var nodeText = node.nodeValue;
				var firstindex = findIndexOfTermEvidenceHub(nodeText, splitText, 0, true);
				if (firstindex != -1) {
					range.setStart(node, firstindex);
					range.setEnd(node, (firstindex+searchTerm.length));

					var imageurl = "<?php echo $HUB_FLM->getImagePath('nodetypes/Default/idea.png'); ?>";
					if (item["@type"] == "claim") {
						imageurl = "<?php echo $HUB_FLM->getImagePath('nodetypes/Default/solution.png'); ?>";
					} else if (item["@type"] == "evidence") {
						imageurl = "<?php echo $HUB_FLM->getImagePath('nodetypes/Default/argument.png'); ?>";
					}

					var button = document.createElement("img");
					button.setAttribute("src", imageurl);
					button.setAttribute("width", "16");
					button.setAttribute("height", "16");
					button.setAttribute("name", "idealink");
					button.setAttribute("id", id+i+"--icon");
					button.setAttribute("class", "LITEMAP-imagebutton");
					button.border = "0";

					//button.addEventListener('mouseover', function(event) {
					//	selection.removeAllRanges();
					//	selection.addRange(range);
					//}, false);
					//button.addEventListener('mouseout', function() {
					//	selection.removeAllRanges();
					//}, false);

					range.insertNode(button);
					range.setStartAfter(button);

					// Add my own selection object
					if (document.getElementById('id+i+"--idealinkdiv"') == null) {
						var highlightdiv = document.createElement("span");
						highlightdiv.setAttribute("name", "idealinkdiv");
						highlightdiv.setAttribute("id", id+i+"--idealinkdiv");
						highlightdiv.setAttribute("style", "background-color:"+highlightcolour);
						var aNode = range.extractContents();
						highlightdiv.appendChild(aNode);
						range.insertNode(highlightdiv)
						range.setStartAfter(highlightdiv);
					}

					//selection.addRange(range);
				}
			} else {
				for (var i=0; i<count; i++) {
					var node = highlightNodes[i];
					var nodeText = node.nodeValue;
					if (i==0) {
						var firstindex = findIndexOfTermEvidenceHub(nodeText, splitText, 0, true);
						if (firstindex != -1) {
							range.setStart(node, firstindex);
						}

						var imageurl = "<?php echo $HUB_FLM->getImagePath('nodetypes/Default/idea.png'); ?>";
						if (item["@type"] == "claim") {
							imageurl = "<?php echo $HUB_FLM->getImagePath('nodetypes/Default/solution.png'); ?>";
						} else if (item["@type"] == "evidence") {
							imageurl = "<?php echo $HUB_FLM->getImagePath('nodetypes/Default/argument.png'); ?>";
						}

						var button = document.createElement("img");
						button.setAttribute("src", imageurl);
						button.setAttribute("width", "16");
						button.setAttribute("height", "16");
						button.setAttribute("name", "idealink");
						button.setAttribute("id", id+i+"--icon");
						button.setAttribute("class", "LITEMAP-imagebutton");
						button.border = "0";

						//button.addEventListener('mouseover', function(event) {
						//	selection.removeAllRanges();
						//	selection.addRange(range);
						//}, false);
						//button.addEventListener('mouseout', function() {
						//	selection.removeAllRanges();
						//}, false);

						range.insertNode(button);
						range.setStartAfter(button);

						// Add my own selection object
						if (document.getElementById('id+i+"--idealinkdiv"') == null) {
							var highlightdiv = document.createElement("span");
							highlightdiv.setAttribute("name", "idealinkdiv");
							highlightdiv.setAttribute("id", id+i+"--idealinkdiv");
							highlightdiv.setAttribute("style", "background-color:"+highlightcolour);
							var aNode = range.extractContents();
							highlightdiv.appendChild(aNode);
							range.insertNode(highlightdiv)
							range.setStartAfter(highlightdiv);
						}
					} else if (i == count-1) {
						var lastindex = findIndexOfTermEvidenceHub(nodeText, splitText, splitText.length-1, false);
						if (lastindex != -1) {
							range.setEnd(node, lastindex);
							selection.addRange(range);
						}
					} else {
						//set selection colour on node somehow?
					}
				}
			}

			//if (range) {
			//	range.startContainer.parentNode.scrollIntoView(true);
			//}
		}
	} catch(e) {
		console.log(e);
	}

	return itemFoundCount;
}

function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

function findIndexOfTermEvidenceHub(textBlock, termArray, indexPoint, down) {

	var searchTerm = "";

	if (down) {
		for (var i=0; i<=indexPoint; i++) {
			if (searchTerm != "") {
				searchTerm += " "+termArray[i];
			} else {
				searchTerm += termArray[i];
			}
		}
	} else {
		for (var i=termArray.length-1; i>=indexPoint; i--) {
			if (searchTerm != "") {
				searchTerm = termArray[i]+" "+searchTerm;
			} else {
				var next = termArray[i];
				if (next != "") {
					searchTerm += next;
				} else {
					// if the last item in the array of words is a space move back one which hopefully will be a word!
					if (i==indexPoint) {
						indexPoint--;
					}
				}
			}
		}
	}

	if (searchTerm != "") {
		var ind1 = textBlock.indexOf(searchTerm);
		var ind2 = textBlock.lastIndexOf(searchTerm);
		if (ind1 != ind2) {
			return findIndexOfTermEvidenceHub(textBlock, termArray, indexPoint+1, down);
		} else {
			if (down) {
				return ind1;
			} else {
				return ind1+searchTerm.length;
			}
		}
	} else {
		return -1;
	}
}

/**
 * Remove the icons and the highlighting from the page.
 */
function clearMargotResults() {
	// close all argument divs?
	var elements = document.getElementsByName("idealinkdiv");
	for(var i = 0; i < elements.length; i++) {
		elements[i].click();
	}

	document.getElementById('margotdata').value = "";
	clearMargotHighlights2();
	clearMargotIcons();
}

/**
 * Remove any selections from page.
 */
function clearMargotHighlights() {

	/*
	var sel = window.getSelection ? window.getSelection() : document.selection;
	if (sel) {
		if (sel.removeAllRanges) {
			sel.removeAllRanges();
		} else if (sel.empty) {
			sel.empty();
		}
	}*/

	if (window.getSelection) {
	  if (window.getSelection().empty) {  // Chrome
		window.getSelection().empty();
	  } else if (window.getSelection().removeAllRanges) {  // Firefox
		window.getSelection().removeAllRanges();
	  }
	} else if (document.selection) {  // IE?
	  document.selection.empty();
	}

}

function clearMargotHighlights2() {
	var elements = document.getElementsByName('idealinkdiv');
	for(var i = 0; i < elements.length; i++) {
		var next = elements[i]
		var nextid = next.id;
		var bits = nextid.split("--");
		var mainid = bits[0];

		var button = document.getElementById(mainid+"--icon");
		if (button != null) {
			var parent = next.parentElement;
			//console.log("parent");
			//console.log(parent);
			var child = next.firstChild;
			//console.log("child");
			//console.log(child);
			if (child.tagName === 'SPAN') {
				child = child.firstChild;
			}
			//console.log("child");
			//console.log(child);
			parent.insertBefore(child, button);
			parent.removeChild(next);
		}
	}

	document.body.normalize(); // very important to correct the structure
}

/**
 * Remove any icons added to the page by previous selects.
 */
function clearMargotIcons() {
	var nodeList = document.getElementsByName('idealink');
	if (nodeList != null) {
		for (var i=0; i<nodeList.length; i++) {
		    var item = nodeList[i];
		    if (item) {
				if (item.parentHTML) {
					item.parentNode.innerHTML = item.parentHTML;
					i--;
				} else {
					var parentNode = item.parentNode;
					if (parentNode) {
						parentNode.removeChild(item);
						i--;
					}
				}
			}
		}
	}

	document.body.normalize(); // very important to correct the structure
}
