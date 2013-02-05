// $Id: functions.js,v 1.1 2008/12/19 23:46:36 iikka Exp $

function preinit() {
	// Initialize
	tinyMCE.setWindowArg('mce_windowresize', false);
}

function init() {
  window.focus();
  loadCategories(null);
}

/**
 * loadCategories()
 * @param obj parent Object
 **/
function loadCategories(obj) {
  var params = '';
  var mid = '0';
  var top = 75;
  var objTop;
  if (obj != null) {
    mid = $(obj).get(0).value;

    try
    {
      objTop = $(obj).attr('id').match(/_(\d+)_sel/);
      top += (objTop[1] * 17);
    }
    catch (e) {
      top += 0;
    }
  }
  params = 'ltc-type=linktocontent_menu&ltc-menu-id=' + mid;

  $('#statusImg').css({top: top + 'px'}).show();

  $.ajax({
    type: "POST",
    url: _getBaseHref() + 'index.php?q=linktocontent',
    data: params,
    error: function(xml, msg, exc) {
      $('#statusImg').hide();
      alert('Error: ' + msg + '\n' + xml.responseText);
    },
    success: function(data){
      try {
        var results = eval('(' + data + ');');
        _clearNodeList();
        if ((obj == null) || ($(obj).get(0).value > -1)) {
          if (_fillDropdown(obj, results)) {
            window.resizeBy(0, 17);
          }
        }
        _fillNodelist(results);
      }
      catch (e) {
        alert('Error on retrieving data from module.\n' + e.name + '\n' + e.message);
      } 
      finally {
        $('#statusImg').hide();
      }
    }});
}

function insertAction() {
  if (selectedNode == null) {
    return;
  }
	var inst = tinyMCE.getInstanceById(tinyMCE.getWindowArg('editor_id'));
	var elm = inst.getFocusElement();

	elm = tinyMCE.getParentElement(elm, "a");

	tinyMCEPopup.execCommand("mceBeginUndoLevel");

	// Create new anchor elements
	var path = selectedNode.cells[0].firstChild.nodeValue;
	if (elm == null) {
		var insertText = selectedNode.cells[1].firstChild.nodeValue;
    if (inst.selection.getSelectedText().length == 0) {
      tinyMCEPopup.execCommand("mceInsertContent", false,
        '<a href="' + path + '">' + insertText + '</a>');
		}
    else {
   	  tinyMCEPopup.execCommand("mceInsertContent", false,
        '<a href="' + path + '">' + inst.selection.getSelectedHTML() + '</a>');
    } // end (getSelectedText == 0)
	}
  else {
    elm.setAttribute("mce_href", path);
  	// Refresh in old MSIE
  	if (tinyMCE.isMSIE5)
  		elm.outerHTML = elm.outerHTML;
	}

	tinyMCE._setEventsEnabled(inst.getBody(), false);
	tinyMCEPopup.execCommand("mceEndUndoLevel");
	tinyMCEPopup.close();
}

function loadScript(url) {
  $('head', document).append('<script type="text/javascript" src="' + url + '"></script>');
}

/**
 * returns the base drupal path
 * @access public
 **/
function _getBaseHref() {
  var url = tinyMCE.baseURL.substring(0, tinyMCE.baseURL.indexOf('modules/'));
  if (url.indexOf('sites/') > -1)
    url = url.substring(0, url.indexOf('sites/'));
  return url;
}

// dropdown functions
/**
 *
 * @access public
 * @return void
 **/
function _createDropdown(obj){
  var dropdown;
  var elemname = '';
  if (obj == null) {
    elemname = 'browse_sel_-1';
  }
  else {
    elemname = $(obj).parent().attr('id');
    _removeDescendant($(obj));
  }

  var level = parseInt(elemname.substring(elemname.lastIndexOf("_") + 1, elemname.length)) + 1;

  var elemBase = elemname.substring(0, elemname.lastIndexOf("_") + 1);

  // create surrounding <div>
  var sel_div = $('<div></div>');
  $(sel_div).attr('name', elemBase + level);
  $(sel_div).attr('id', elemBase + level);
  $(sel_div).attr('style', 'display: block; margin: 1px 0; padding: 0; border: 0;');

  // create <select>
  var select = $('<select></select>');
  $(select).attr('size', 1);
  $(select).attr('name', elemBase + level + '_sel');
  $(select).attr('id', elemBase + level + '_sel');

  // add event handler
  $(select).change(function() {
    _removeDescendant($(this).parent());
    _clearNodeList();
    loadCategories($(this));
  });

  // create image (only if level > 0)
  if (level > 0) {
    var img = $('<img />');
    $(img).attr('id', elemname.substring(0, elemname.lastIndexOf('_') + 1) + level + '_img');
    $(img).attr('alt', '');
    $(img).attr('src', 'images/descendant.gif');
    // extra margin for IE
    $(img).css({margin: '0px 2px', border: 0});
    $(img).css({marginLeft: String((parseInt(level) - 1) * 14) + 'px', marginRight: '2px'});
    $(sel_div).append($(img));
  }

  $(sel_div).append($(select));
  $(sel_div).hide();
  $('#form_browse').append($(sel_div));
  $(sel_div).show('slow');
  return select;
}

/**
 * _fillDropdown
 * @return true if a dropdown was inserted
 **/
function _fillDropdown(obj, results){
  if (results.menus != false) {
    var select = _createDropdown(obj);
    $(select).addOption(-1, tinyMCELang['lang_linktomenu_choose_category']);
    for (key in results.menus) {
      if (results.menus[key].hasChildren) {
        $(select).addOption(results.menus[key].mid, results.menus[key].title);
      }
    }
    return true;
  }
  return false;
}

/**
* remove "child" elements
**/
function _removeDescendant(elem) {
  if (elem == null)
    return;
  $(elem).next().each(
    function() {
      _removeDescendant($(this));
      window.resizeBy(0, -17);
      $(this).hide('slow').remove();
    }
  );
}

// nodelist functions
var selectedNode = null;

function _fillNodelist(results) {
  if ((results == null) || (results.menus == null) || (results.menus == false))
    return;

  for (key in results.menus) {
    if (results.menus[key].root) {
      continue;
    }
    _addNodeToList(results.menus[key]);
  }
}

function _clearNodeList() {
  $('#nodelist tbody tr').each(
    function() {
      $(this).remove();
    }
  );
}

function selectRow(row) {
  if (!row) {
    return;
  }
  $('#nodelist tbody tr').each(function() { 
    $(this).css({background: '#ffffff'}); 
    selectedNode = null; 
  });
  selectedNode = row;
  $(row).css({background: '#dadfe9'});
}

function _addNodeToList(node){
  var tr = $('<tr></tr>').appendTo('#nodelist tbody');
  tr.hover(
    function() {
      if (selectedNode != this) {
        $(this).css({background: '#cecece'});
      }
    },
    function() {
      if (selectedNode != this) {
        $(this).css({background: '#ffffff'});
      }
    }
  );
  tr.click(function() {
    selectRow(this);
  });

  $('<td class="nid">' + node.path + '</td>').appendTo(tr);
  $('<td>' + node.title + '</td>').appendTo(tr);
}

/**
 * make the nodelist accessible by keyboard
 **/
$('.accessible').keypress(function(e) {
  switch (e.keyCode) {
    case 39:
    case 40: // move selection down
      if (selectedNode == null)
        selectedNode = $('#nodelist tbody tr').get(0);
      else
        selectedNode = $(selectedNode).next('tr').get(0);
      selectRow(selectedNode);
      break;
    case 37:
    case 38: // move selection up
      if (selectedNode == null)
        selectedNode = $('#nodelist tbody tr').get($('#nodelist tbody tr').length - 1);
      else
        selectedNode = $(selectedNode).prev('tr').get(0);
      selectRow(selectedNode);
    break;
  }
});

// options
$.fn.addOption = function()
{
	if(arguments.length == 0)
    return this;
	// select option when added? default is false
	var selectOption = false;
	// multiple items
	var multiple = false;
	if(typeof arguments[0] == "object")
	{
		multiple = true;
		var items = arguments[0];
	}
	if(arguments.length >= 2)
	{
		if(typeof arguments[1] == "boolean")
      selectOption = arguments[1];
		else if(typeof arguments[2] == "boolean")
      selectOption = arguments[2];
    if(!multiple)
		{
			var value = arguments[0];
			var text = arguments[1];
		}
	}
	this.each(
		function()
		{
			if(this.nodeName.toLowerCase() != "select")
        return;
			if(multiple)
			{
				for(v in items)
				{
					$(this).addOption(v, items[v], selectOption);
				}
			}
			else
			{
				var option = document.createElement("option");
				option.value = value;
				option.text = text;
				this.options.add(option);
			}
			if(selectOption)
			{
				this.options[this.options.length-1].selected = true;
			}
		}
	)
	return this;
}

$.fn.removeOption = function()
{
	if(arguments.length == 0)
    return this;
	if(typeof arguments[0] == "string")
    var value = arguments[0];
	else if(typeof arguments[0] == "number")
    var index = arguments[0];
	else
    return this;
	this.each(
		function()
		{
			if(this.nodeName.toLowerCase() != "select")
        return;
			if(value)
			{
				var optionsLength = this.options.length;
				for(var i=optionsLength-1; i>=0; i--)
				{
					if(this.options[i].value == value)
					{
						this.options[i] = null;
					}
				}
			}
			else
			{
				this.remove(index);
			}
		}
	)
	return this;
}
