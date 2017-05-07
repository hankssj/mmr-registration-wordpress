/* Event Booking Pro - WordPress Plugin
 * Get plugin at: http://iplusstd.com/item/eventBookingPro/
 * iPlus Studio
 */

(function($){

var DATE_FORMAT = {
  formats: [
    ['F j, Y', 'November 2 , 2015'],
    ['l F j, Y', 'Saturday November 2, 2015'],
    ['D M j Y', 'Sat Nov 2 2014'],
    ['D j. F Y', 'Sat 2. November 2014'],

    ['j F Y', '2 November 2014'],
    ['j. F Y', '2. November 2014'],
    ['l j F Y', 'Saturday 2 November 2014'],
    ['l, j. F Y', 'Saturday, 2. November 2014'],

    ['m.d.y', 'M.D.01'],
    ['m.d.Y', 'M.D.2001'],
    ['d.m.y', 'D.M.14'],
    ['d.m.Y', 'D.M.2014'],

    ['j, n, Y', 'D, M, 2014'],
    ['n, j, Y', 'M, D, 2014'],
    ['j, n, y', 'D, M, 14'],
    ['n, j, y', 'M, D, 14'],

    ['j/n/Y', 'D/M/2014'],
    ['n/j/Y', 'M/D/2014'],
    ['j/n/y', 'D/M/14'],
    ['n/j/y', 'M/D/14'],

    ['j - n - Y', 'D - M - 2014'],
    ['n - j - Y', 'M - D - 2014'],
    ['j - n - y', 'D - M - 14'],
    ['n - j - y', 'M - D - 14'],
    ['Y/m/d', '2015/M/D']
  ],
  init: function () {
    this.getFormats = _.map(this.formats, function(format) { return format[0] });
   this.getLabels =  _.map(this.formats, function(format) { return format[1] });

    return this;
  }
}.init();

function doOption(parms){
	var itemSpecialClass = (parms.hasOwnProperty("itemClass")) ? parms.itemClass : "";
	var optionHtml = '<div class="item ' + itemSpecialClass + '">';

	if (parms.hasOwnProperty("before") && parms.before != "") {
		optionHtml += ' ' + parms.before;
  }

  if (parms.title !== ''){
    optionHtml += '<span class="label">' + parms.title + '</span>';
  }

	switch(parms.type) {
		case "input":
			var maxlength = (parms.hasOwnProperty('maxlength')) ? 'maxlength="' + parms.maxlength + '"' : "";
			optionHtml += '<input name="'+parms.name+'" value="'+parms.value+'"  class="settingField" type="text" '+maxlength+'/>';
			break;

		case "input-mini":
			var maxlength = (parms.hasOwnProperty('maxlength')) ? 'maxlength="' + parms.maxlength + '"' : "";
			optionHtml += '<input name="'+parms.name+'" value="'+parms.value+'"  class="settingField settingField-mini" type="text" '+maxlength+'  />';
			break;

		case "input-large":
			var maxlength = (parms.hasOwnProperty('maxlength')) ? 'maxlength="' + parms.maxlength + '"' : "";
			optionHtml += '<input name="'+parms.name+'" value="'+parms.value+'"  class="settingField settingField-large" type="text" '+maxlength+'/>';
			break;

		case "textarea":
			optionHtml += '<textarea name="'+parms.name+'" type="text">'+parms.value+'</textarea>';
			break;

		case "password":
			optionHtml += '<input name="'+parms.name+'" value="'+parms.value+'"  class="settingField" type="password"  />';
			break;

		case "number":
			var Min = (parms.hasOwnProperty('min')) ? 'min="'+parms.min+'"' : "";
			var Max = (parms.hasOwnProperty('max')) ? 'max="'+parms.max+'"' : "";
			optionHtml += '<input name="'+parms.name+'" value="'+parms.value+'" type="number" '+Min+' '+Max+' />';

			break;

		case "color":
			optionHtml += '<input id="'+parms.name+'" name="'+parms.name+'" class="colorPicker" data-default-color="'+parms.defaultValue+'"  type="text"  value="'+parms.value+'" />';
			break;

		case "select":
			optionHtml += '<select id="'+parms.name+'" name="'+parms.name+'">';

			var isSelected;
			for(var opt in parms.values){
				isSelected=(parms.values[opt] == parms.value) ? 'selected="selected"' : '';
				optionHtml += '<option value="'+parms.values[opt]+'" '+isSelected+'>'+parms.options[opt]+'</option>';
			}

			optionHtml += '</select>';
			break;

		case "toggle":
			var checked = (parms.value=="true") ? "checked" : "";
			var toggleClass = (parms.toggleClass) ? parms.toggleClass : '';
			optionHtml += '<div class="hasWrapper"><div class="make-switch switch-square '+ toggleClass +'" id="'+parms.name+'" data-isAnOption="yes"><input type="checkbox" '+checked+'></div></div>';
			break;

		case "advancedToggle":
			break;

    case "html":
      optionHtml += parms.html;
      break;
	}

	if (parms.hasOwnProperty("after") && parms.after != "") {
		optionHtml += ' ' + parms.after;
  }

	if (parms.hasOwnProperty("info") && parms.info != "") {
		optionHtml += '<a href="#" class="tip-below tooltip" data-tip="' + parms.info + '">?</a>';
  }

	optionHtml += '</div>';
	return optionHtml;

}

function getToggling(parms) {
	var checked = (parms.value=="true") ? "checked" : "";

  if (parms.value == "show") {
		checked = "checked";
  }

	var inverseToggle = parms.hasOwnProperty('inverseToggle') ? 'inverseToggle' : '';

	var hidden = false;
	hidden = hidden|| (parms.value == "hide");

	if (parms.hasOwnProperty('inverseToggle')) {
		hidden = hidden || (parms.value === "true");
	} else {
		hidden = hidden || (parms.value === "false");
	}

	hidden = (hidden) ? "display: none;" : "";
	var isIncluded = parms.hasOwnProperty('name') ? 'yes' : 'no';
	var boxId = (isIncluded) ? parms.name: "box-switch" ;

	var optionHtml = '<div class="switcher"><div class="item">';
    optionHtml += '<span class="label">' + parms.title + '</span>';
    optionHtml += '<div class="hasWrapper">';
    optionHtml += '<div class="make-switch switch-square hasCnt ' + inverseToggle + '" id="' + boxId + '" data-isAnOption="' + isIncluded + '">';
    optionHtml += '<input type="checkbox" ' + checked + '></div>';
    optionHtml += '</div>'

		if (parms.hasOwnProperty("info") && parms.info != "") {
			optionHtml += '<a href="#" class="tip-below tooltip" style="float:none; margin-left: 40px; vertical-align: top;" data-tip="' + parms.info + '">?</a>';
    }

    optionHtml += '</div>'

    optionHtml += '<div class="cnt" style="'+hidden+'" >';
    for (var i in parms.items) {
      optionHtml += parms.items[i];
    }
    optionHtml += '</div>'

  optionHtml += '</div>';

  return optionHtml;
}


$(document).ready(function(){


window.appSettings = {
  googleMapsEnabled: 'true',
  emailBookingCanceled: 'false',
  emailRulesEnabled: 'false',
  sendEmailWhenCancelled: 'true'
};


var updateAdminAppSettings = function () {
  $.ajax({
    type:'POST',
    url: 'admin-ajax.php',
    data: 'action=ebp_get_admin_app_settings',
    success: function(response) {
      window.appSettings = $.parseJSON(response);
    }
  });
};
updateAdminAppSettings()


  // get version
  if ($("#ebp-version").length > 0) {
    $.ajax({
      type:'GET',
      dataType: 'jsonp',
      url: 'http://iplusstd.com/item/eventBookingPro/ebp.version.json?version=' + Date.now(),
      jsonpCallback: "processJSON",
      error: function (err) {
        if (err) console.error(err);
      },
      success: function(response) {
        var version = response.version;
        if (parseFloat(version) != parseFloat( $("#ebp-version").attr("data-version"))) {
          $("#ebp-version").html('Version ' + version + ' is available.');
        } else {
          $("#ebp-version").html("EBP is up to date.")
        }
      }

    });
  }


	//Button Events
	$(".eventlist li a").click(function(e){
		e.preventDefault();
		eventClicked($(this));
  });

	$('#createEventBtn').click(function(e){
		e.preventDefault();
		var newId = Number($("#eventCount").val()) + 1;
		$("#eventCount").val(newId);
		var html = '<li id="event_' + newId + '"><a href="#" data-id="' + newId + '" class="btnE btn-block "><small>' + newId + '</small><span>Event Name</span></a></li>';
		$(".eventlist li a.active").removeClass("active");
		$('.eventlist').append(html);
		$("#event_" + newId).find("a").addClass("active");

		$(".eventlist li:last-child a").click(function(e) {
			eventClicked($(this))
		});

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();

    if (dd < 10) dd = '0'+dd;
    if (mm < 10) mm ='0'+mm;

    today = yyyy + '-' + mm + '-' + dd;

		$('#loader').slideDown(100);
		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=event_get_addons_data',
			success: function(response) {
				var data = $.parseJSON(response);
				var formData = data.formsData;
				var emailsData = data.emailsData;
				prepareEventPage(newId, {
          id: newId,
          name: "Event Name",
          occur: [
            {
              id: 'new',
              start_date: today,
              start_time: '15:00:00',
              end_date: today,
              end_time: '17:00:00',
              bookingDirectly: 'true',
              bookingEndsWithEvent: 'true'
            }
          ],
          tickets: [
            {
              id: 'new',
              name: 'Regular',
              cost: '0',
              allowed: '20'
            }
          ],
          image: "",
          info: "",
          allowed: "20",
          cost: "0",
          showPrice: "true",
          modal: "true" ,
          showSpots: "true",
          mapAddressType: "address",
          mapAddress: "",
          address: "",
          mapZoom: "16",
          mapType: "ROADMAP",
          hasForms: formData.hasForms,
          forms: formData.forms,
          maxSpots: "-1",
          gateways: "",
          background: "",
					emailTemplateID: "-1",
          hasEmailTemplates: emailsData.hasEmailTemplates,
          hasEmailRules: emailsData.hasEmailRules,
          emailTemplates: emailsData.emailTemplates,
          emailRules: [],
          eventStatus: "active"
        });
				$('#loader').slideUp(100);
			}
		});
	});

	function duplicateEvent(name){
		var newId = Number($("#eventCount").val()) + 1;
		$("#eventCount").val(newId);
		var html ='<li id="event_' + newId + '"><a href="#" data-id="' + newId + '" class="btnE btn-block "><small>' + newId + '</small><span>Event Name</span></a></li>';
		$(".eventlist li a.active").removeClass("active");
		$('.eventlist').append(html);
		$("#event_" + newId).find("a").addClass("active");
		$('#event-id').val(newId);
		$('.Ebp--Occurrences--Occurrence input[name="occurid"]').val("new");
		$('.event-row input[name="ticketid"]').val("new");
    $('.ebp_email_rule').attr('data-id', '-1');

		saveEvent();
		window.scrollTo(0,0);
	}


	$(".adminHeader a.EBP--TopBtn.settings").click(function(e){
		e.preventDefault();

		$(".eventlist li a.active").removeClass("active");
		$(".adminHeader a.EBP--TopBtn").removeClass("active");
		$(this).addClass("active");
		$('#loader').slideDown(100);

		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_settings&id=1',
			success: function(response) {
				var data = $.parseJSON(response);
				getSettingsPage(1, data);
				$('#loader').slideUp(100);
			}
		});
  });

  $('.EBP--TopBtn.category').live("click", function(e){
    e.preventDefault();

    $(".eventlist li a.active").removeClass("active");
    $(".adminHeader a.EBP--TopBtn").removeClass("active");
    $(this).addClass("active");
    $('#loader').slideDown(100);
    $.ajax({
      type:'POST',
      url: 'admin-ajax.php',
      data: 'action=ebp_get_categories',
      success: function(response) {
        $('.eventDetails .cnt').hide();
        $('.eventDetails .cnt').html(response);
        $('.eventDetails .cnt').fadeIn(200);
        $('#loader').slideUp(100);
      }
    });
	});

	$('a.newCategory').live("click", function(e) {
		e.preventDefault();

		var newId = $(this).attr("data-id");
		$(this).attr("data-id", parseInt(newId, 10) + 1);
		$(".categories").find("a.newCategory").before('<a href="#" class="category editCategory" data-id="' + newId + '">Category Name</a>');

    buildCategorySection(newId, {
      id: newId,
      name: "Category Name"
    });

		saveCategory();
	});

	$('a.editCategory').live("click", function(e) {
		e.preventDefault();
		var id=$(this).attr("data-id");
		$('#loader').slideDown(100);
		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_get_category&id=' + id,
      error: function(error) {
        $('#loader').slideUp(100);

        console.error(error);
        alert('check console for error');
      },
			success: function(response) {
				$('#loader').slideUp(100);

        var json = $.parseJSON(response);

        if (json.error != null) {
          alert("Error getting category!");
          return;
        }


				buildCategorySection(id, json);
			}
		});
	});

	function saveCategory() {
		$('#loader').slideDown(100);
		var id = $('#categoryForm input[name="categoryid"]').val();

		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_save_category'
        + '&id=' + id
        +'&name=' + $('#categoryForm input[name="name"]').val(),
			success: function(response) {
        $('#loader').slideUp(100);
        var responseJson = $.parseJSON(response);
        if (responseJson.error !== null) {
          alert(responseJson.error);
          return;
        }


				$('a.newCategory').attr("data-id", responseJson.maxId);
				var $btn = $(".categories").find("[data-id='" + responseJson.id + "']");
				$btn.text(responseJson.html);
			}
		});
	}

	function buildCategorySection(id, data) {
		var html = '<form name="categoryForm"  method="post" id="categoryForm" class="eventForm">';
          html += '<input name="categoryid" value="' + id + '"  type="hidden"  />';

			    html += '<div class="EBP--CategoryDetails--Head"><span class="id">Name: </span><input  name="name" value="' + data.name.replace(/\\/g, '') + '" class="EBP--CategoryDetails--Name" type="text"  /></div>';

          html += '<div class="EBP--CategoryDetails--Btns">';
					  html += '<a href="#" class="btn btn-small btn-danger category-delete">Delete</a> ';
					  html += '<a href="#" class="btn btn-small btn-success categoty-save">Save</a>';
          html +='</div>';

    html += ' </form>';

		$(".EBP--CategoryDetails").html(html);

		$('.category-delete').live('click', function(e){
			e.preventDefault();
			deleteCategory();
		});

		$('.categoty-save').click(function(e) {
			e.preventDefault();
			saveCategory();
		});
	}

	function deleteCategory(){
		$('#loader').slideDown(100);
		var id = $('#categoryForm input[name="categoryid"]').val();

		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_delete_category&id=' + id,
			success: function(response) {
				$('#loader').slideUp(100);
				$(".categories").find("[data-id='" + id + "']").remove();
				$(".EBP--CategoryDetails").empty();
			}
		});
	}

	function eventCategories($cnt){
		var id = $("#event-id").val();
		$('#loader').slideDown(100);
		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_get_event_categories&event-id=' + id,
			success: function(response) {
				$cnt.prepend(response);
				$cnt.addClass('loadedAlready');
				$('#loader').slideUp(100);

				$('a.category.toggle').click(function(e){
					e.preventDefault();
					if ($(this).hasClass('notselected')) {
						$(this).removeClass('notselected');
          } else {
						$(this).addClass('notselected');
          }
				});
			}
		});
	}

	$('.EBP--TopBtn.addons').click(function(e) {
		e.preventDefault();

		$(".eventlist li a.active").removeClass("active");
		$(".adminHeader a.EBP--TopBtn").removeClass("active");
		$(this).addClass("active");
		$('#loader').slideDown(100);
		$('.eventDetails .cnt').fadeOut(200);
		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=checkAddons',
			success: function(response) {
				$('.eventDetails .cnt').html(response);
				$('.eventDetails .cnt').fadeIn(200);
				$('#loader').slideUp(100);
			}
		});
	});

	$('.EBP--TopBtn.coupon').click(function(e){
		e.preventDefault();

		$(".eventlist li a.active").removeClass("active");
		$(".adminHeader a.EBP--TopBtn").removeClass("active");
		$(this).addClass("active");
		$('.eventDetails .cnt').fadeOut(200);
		$('#loader').slideDown(100);
		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_get_coupons_admin_page',
      error: function(error) {
        $('#loader').slideUp(100);
        console.error(error);
        alert('Check console for error');
      },
			success: function(response) {
				$('.eventDetails .cnt').html(response);
				$('.eventDetails .cnt').fadeIn(200);
				$('#loader').slideUp(100);
			}
		});
	});

	$('a.newCoupon').live("click", function(e) {
		e.preventDefault();
		var newId = $(this).attr("data-id");
		$(this).attr("data-id", parseInt(newId,10) + 1);
		$(".coupons").find("a.newCoupon").before('<a href="" class="coupon editCoupon" data-id="' + newId + '">Coupon Name</a>');

    buildCouponSection(newId, {
      id: newId,
      name: "Coupon Name",
      amount: "0",
      code: "code_" + newId,
      isActive:"true",
      allowed: -1
    });

		saveCoupon();
	});

	$('a.editCoupon').live("click", function(e) {
		e.preventDefault();
		var id = $(this).attr("data-id");
		$('#loader').slideDown(100);
		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_coupon_get&id=' + id,
      error: function(error) {
        $('#loader').slideUp(100);

        console.error(error);
        alert('check console for error');
      },
			success: function(response) {
				$('#loader').slideUp(100);

        var json = $.parseJSON(response);

        if (json.error != null) {
          alert("Error getting coupon!");
          return;
        }

				buildCouponSection(id, json)
			}
		});
	});

	$('.EBP--TopBtn.shortcode').click(function(e) {
		e.preventDefault();

		$(".eventlist li a.active").removeClass("active");
		$(".adminHeader a.EBP--TopBtn").removeClass("active");
		$(this).addClass("active");
		var html = '<h2>Shortcodes</h2>';

    // <span class="new marginRight10">new </span><strong>
		html += '<div class="addonCnt">';
			html += '<h3>Event Box</h3>';
			html += '<span>To use: [eventBox id="EventID"]</span>';
			html += '<h4>Options: </h4><em>* : required</em>';
			html += '<ul>';
				html += '<li><strong>width</strong>  <em>in px</em></li>';
				html += '<li><strong>id *</strong>: ID of the event</li>';
				html += '<li><strong>date_id</strong>: If the event has multiple dates then you can specifiy which one to display. If this is not set then the eventBox will display the next upcoming date. If all dates have passed then the event box will display a message set from the settings, check examples.</li>';
				html += '<li><strong>show_all_tickets</strong>: (true/ false) when set it overrides the default value that was set in settings page</li>';
			html += '</ul>';

			html += '<h4>Examples:</h4>';
				html += '<ul><li>[eventBox id="2"]</li><li>[eventBox width="400" id="3"]</li><li>[eventBox id="2" date_id="9"]</li></ul>';
		html += '</div>';


		html += '<div class="addonCnt">';
			html += '<h3>Event Card</h3>';
			html += '<span>To use: [eventCard id="EventID"]</span>';
			html += '<h4>Options: </h4><em>* : required</em>';
			html += '<ul>';
				html += '<li><strong>width</strong>  <em>in px</em></li>';
					html += '<li><strong>id *</strong>: ID of the event</li>';
					html += '<li><strong>expand</strong>: <ul><li>on (default)</li><li>off</li></ul></li>';
					html += '<li><strong>date_id</strong>: If the event has multiple dates then you can specifiy which one to display. If this is not set then the eventBox will display the next upcoming date. If all dates have passed then the event box will display a message set from the settings, check examples.</li>';
			html += '</ul>';

			html += '<h4>Examples:</h4>';
				html += '<ul><li>[eventCard id="2"]</li><li>[eventCard width="400" id="3"]</li><li>[eventCard id="2" date_id="9"]</li></ul>';

				html += '<em>Note: To list event cards use eventsList shortcode with type="cards". Details below.</em>';
		html += '</div>';



		html += '<div class="addonCnt">';
			html += '<h3>Event Button</h3>';
			html += '<span>To use: [eventButton id="EventID"]Custom Button Text[/eventButton]</span>';
			html += '<h4>Options: </h4><em>* : required</em>';
			html += '<ul>';
				html += '<li><strong>include_price</strong>: true/false (override option set in settings)</li>';
				html += '<li><strong>id *</strong>: ID of the event</li>';
				html += '<li><strong>date_id</strong>: If the event has multiple dates then you can specifiy which one to display. If this is not set then the eventBox will display the next upcoming date. If all dates have passed then a "(passed)" msg is appended to the button</li>';
				html += '<li>Leave Shortcode content empty to display text set in settings page<ul><li>Example:[eventButton id="2"][/eventButton]</li><li>Add text to override the button text[eventButton id="2"]Buy Now[/eventButton]</li></ul></li>';

			html += '</ul>';

			html += '<h4>Examples:</h4>';
				html += '<ul><li>[eventButton id="2"][/eventButton]</li>';
				html += '<li>[eventButton id="1"]Buy Now[/eventButton]</li>';

				html += '<li>[eventButton id="1" include_price="true"][/eventButton]</li>';
				html += '</ul>';
		html += '</div>';

		html += '<div class="addonCnt">';
			html += '<h3>Calendar Shortcode</h3>';
			html += '<span>To use: [eventCalendar]</span>';
			html += '<h4>Options: </h4><em>All are optional</em>';
			html += '<ul>';
        html += '<li><strong>width</strong> <em>in px</em></li>';
        html += '<li><strong>height</strong> <em>in px</em></li>';
        html += '<li><strong>categories</strong>:';
          html += '<ul>';
            html += '<li>List of category ids to display events from. Example: 1,4,7</li>';
            html += '<li>When not used, all events are loaded</li>';
          html += '</ul>';
        html += '</li>';
      html += '<li><strong>display_mode</strong>:';
        html += '<ul>';
          html += '<li><strong>tooltip</strong>: Shows a dot on the calendar day cell. Event are then shown in a tooltip. <em>(default)</em></li>';
          html += '<li><strong>show_directly</strong>: Shows events directly on the calendar day cell</li>';
          html += '<li><strong>show_spread</strong>: Shows events spread over all days of the duration of the event.</li>';
        html += '</ul>';
      html += '</li>';
    html += '<li><strong>show_spots_left</strong>: Show spots left inside the tooltip or on the calendar day cell directly. <em>(on/off): Default is off.</em></li>';
  html += '</ul>';

  html += '<h4>Deprecated/Removed options:</h4>';
  html += '<ul>';
    html += '<li><strong><strong>lazy_load (removed)</strong>:</strong> (Now by default active)</li>';
    html += '<li><strong>loadall</strong>: Pre loads all events. <em>Default is false. (Not supported anymore, option ignored)</em></li>';
    html += '<li><strong>tooltip (deprecated)</strong>: Shows the event names when hovering over the day: <em>(Supported and will force display_mode to tooltip)</em></li>';
    html += '<li><strong>show_events_directly (deprecated)</strong>: Shows the event names directly on the calendar: <em>(Supported and will force display_mode to show_directly</em></li>';
  html += '</ul>';


			html += '<h4>Examples:</h4>';
				html += '<ul><li>[eventCalendar]</li><li>[eventCalendar width="400" categories="3"]</li><li>[eventCalendar width="400" height=400"]</li></ul>';
		html += '</div>';



		html += '<div class="addonCnt">';
			html += '<h3>Events List</h3>';
			html += '<p class="desc">Displays a list of events.</p>';
			html += '<span>To use: [eventslist]</span>';
			html += '<h4>Options: </h4><em>All are optional</em>';
			html += '<ul>';
				html += '<li><strong>type</strong><ul><li>box: <em>Displays events as box (default)</em></li><li>card: <em>Displays events as cards</em></li><li>cardExpand: <em>Displays events as Expandable Cards</em></li></ul></li>';
				html += '<li><strong>width</strong>  <em>in px</em>, if not set will default to the settings.</li>';
				html += '<li><strong>categories</strong>:';
					html += '<ul><li>Dont include to display all.</li><li>List of ids to display events from those categories only. Example: 1,2,3</li></ul>';
					html += '</li>';
				html += '<li><strong>events</strong>: <em>Default: "all", values: "all" - "upcoming" - "passed"</li>';
                html += '<li><strong>months</strong>: <em>Gets events in set months. Ex "1,2" will display events from Jan and Feb.</em> Dont set to fetch from all months.</li>';
                html += '<li><strong>nextdays</strong>: <em>If set will show events in the coming set days. Ex: 15 will show events of the comming 15 days.</em></li>';
                html += '<li><strong>order</strong>: <em>Default: "asc", values:  "asc" - "desc"</em></li>';
				html += '<li><strong>limit</strong>: <em>Default: "100" </em></li>';
				html += '<li><strong>filter</strong>: <em>Allows user to filter events by categories. Default: "off" ("on"/"off") </em></li>';

        html += '<li>show_occurences_as_seperate</strong>: <em>When off, will show event once in the list even if it has more than one occurrence. If turned on, will show every occurrences as a new entires (eventbox/card) in the list. "more dates" button will still be shown on the event.. Default: "off" ("on"/"off") </em></li>';


			html += '</ul>';
            html += '<em>Mixing options can be helpful. Ex: if you set months="2" and nextdays="15" then only events of the next 15 days that fall in feb will be displayed. Example: if today is 20 feb, then events from 20-28 will be shown. Similarly, If today is 3 feb, then events from 3-18 feb will be shown.';

			html += '<h4>Examples:</h4>';
				html += '<ul><li>[eventslist]</li><li>[eventslist width="400" categories="1,3"]</li><li>[eventslist months="1,2,3"]</li><li>[eventslist nextdays="15"]</li><li>[eventslist events="upcoming" limit="5"]</li><li>[eventslist events="upcoming" type="card"]</li></ul>';
		html += '</div>';


		html += '<div class="addonCnt isAddon">';
			html += '<h3>ByDay Calendar</h3>';
			html += '<p class="desc">This interactive horizontal calendar will allow you to see events for a chosen day for the focused month.</p>';
			html += '<span>To use: [byDayCalendar]</span>';
			html += '<h4>Options: </h4><em>All are optional</em>';
			html += '<ul>';
        html += '<li>type</strong><ul><li>box: <em>Displays events as box (default)</em></li><li>card: <em>Displays events as cards</em></li><li>cardExpand: <em>Displays events as Expandable Cards</em></li></ul></li>';
				html += '<li><strong>width</strong>  <em>in px</em></li>';
				html += '<li><strong>categories</strong>:';
					html += '<ul><li>Dont include to display all.</li><li>List of ids to display events from those categories only. Eample: 1,2,3</li></ul>';
					html += '</li>';
			html += '</ul>';

			html += '<h4>Examples:</h4>';
				html += '<ul><li>[byDayCalendar]</li><li>[byDayCalendar width="400" categories="3"]</li><li>[byDayCalendar categories="1,2,4"]</li></ul>';
		html += '</div>';

    html += '<div class="addonCnt isAddon">';
      html += '<h3>Weekly Calendar</h3>';
      html += '<p class="desc">A weekly view of your events calendar</p>';
      html += '<span>To use: [eventWeeklyView]</span>';
      html += '<h4>Options: </h4><em>All are optional</em>';
      html += '<ul>';

  html += '<li><strong>width</strong> <em>in px</em></li>';
  html += '<li><strong>height</strong> <em>in px</em></li>';
  html += '<li><strong>categories</strong>:';
html += '<ul>';
  html += '<li>List of category ids to display events from. Example: 1,4,7</li>';
  html += '<li>When not used, all events are loaded</li>';
html += '</ul>';
html += '</li>';
  html += '<li><strong>show_spots_left</strong>: Show spots left inside the tooltip or on the calendar day cell directly. <em>(on/off): Default is off.</em></li>';
  html += '<li><strong>show_background: </strong><em>(on/off): Default is off.</em></li>';

      html += '</ul>';

      html += '<h4>Examples:</h4>';
        html += "<ul><li>[eventWeeklyView width='400' height='1000' ]</li><li>[eventWeeklyView width='400' height='1000' show_spots_left='on']</li><li>[eventWeeklyView width='400' height='1000' show_background='on']</li></ul>";
    html += '</div>';

        html += '<div class="addonCnt isAddon">';
      html += '<h3>Booked events</h3>';
      html += '<p class="desc">Show booked events for the user on your website + ability to cancel them.</span>';
      html += '<span>To use: [listBookedEvents]</span>';
      html += '<a href="http://iplusstd.com/item/eventBookingPro/example/users-addon/">Users addon</a>'
    html += '</div>';


		html += '</div>';

		$('.eventDetails .cnt').hide();
    $('.eventDetails .cnt').html(html);
		$('.eventDetails .cnt').fadeIn(200)
  });

	function getDetails(id){
		prepareEventPage(id,["load"])
	}

	function buildCouponSection(id, data) {
		var html = '<form name="couponForm" method="post" id="couponForm">';
          html += '<input name="couponid" value="'+id+'" type="hidden" />';

          html += '<div class="EBP--CouponsPage--Head"><span class="id">Name: </span><input  name="name" value="'+data.name.replace(/\\/g, '')+'" class="EBP--CouponsPage--Name" type="text"  /></div>';

   			html += '<div class="EBP--CouponsPage--Row"><span>Code:</span><input name="code" value="'+data.code+'"  type="text" /></div>';
				html += '<div class="EBP--CouponsPage--Row"><span>Amount: <a href="#" class="tip-below tooltip" data-tip="Enter either a percentage value (10%) or a direct amount (10)">?</a></span><input name="amount" value="'+data.amount+'"  type="text" /></div>';

            html += '<div class="EBP--CouponsPage--Row">';
                html += '<span>Deducts from:</span>';
                html += '<select name="type">';
                   var isSelected = (data.type == 'single') ? ' selected="selected"':'';
                   html += '<option value="single" '+isSelected+'>Single cost</option>';
                   var isSelected = (data.type == 'total') ? ' selected="selected"':'';
                   html += '<option value="total" '+isSelected+'>Total cost</option></option>';
                html += '</select>';

            html += '</div>';

            html += '<div class="EBP--CouponsPage--Row"><span>Limit:</span><input name="maxAllowed" value="'+data.maxAllowed+'"  min="-1" type="number" /> <em style="margin-left:10px">-1 for unlimited</em></div>';

            html += '<div class="EBP--CouponsPage--Row EBP--CouponsPage--Row-Large"><span></span>';

            var checked = (data.isActive == "true")?'checked':'';

            html += '<div class="hasWrapper"><div class="switch-square" id="coupon-active" data-isAnOption="yes" data-on-label="Enabled" data-off-label="Disabled"><input type="checkbox" '+checked+'></div>';

            html += '</div></div>';

            html += '<div class="alert alert-danger" style="display:none;"></div>';
                html += '<div class="EBP--CouponsPage--Btns">';
                html += '<a href="#" class="btn btn-small btn-danger coupon-delete">Delete</a> ';
                html += '<a href="#" class="btn btn-small btn-success coupon-save">Save</a> </div>';

             html += ' </form>';

		$(".EBP--CouponsDetails").html(html);
		$('#coupon-active')['bootstrapSwitch']();

		$('.coupon-delete').live('click', function(e) {
			e.preventDefault();
			deleteCoupon();
		});

		$('.coupon-save').click(function(e) {
			e.preventDefault();
			saveCoupon();
		});
	}

	function eventCoupons($cnt){
		var id = $("#event-id").val();
		$('#loader').slideDown(100);
		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_event_coupon_fetch&event-id=' + id,
			success: function(response) {
				$cnt.prepend(response);
				$cnt.addClass("loadedAlready");
				$('#loader').slideUp(100);

				$('a.coupon.toggle').click(function(e) {
					e.preventDefault();

          if ($(this).hasClass("notselected")) {
						$(this).removeClass("notselected");
          } else {
						$(this).addClass("notselected");
          }
				});
			}
		});
	}

	function getShortCodes($cnt){
		var id = $("#event-id").val();

		$('#loader').slideDown(100);

    $.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_get_event_shortcodes&event-id=' +id,
			success: function(response) {
        json = $.parseJSON(response);
				var html = '<div class="details_sc">';

				html += "<div class='alert-info alert'>Check the 'Shortcode page' for a detailed parameters explanation!</div>";

				html += '<h3>Shortcodes for this event</h3>';

				html += '<div class="addonCnt">';
					html += '<h3>Main ShortCodes</h3>';
					html += '<h4>Main Event Box:</h4>';
					html += '<p>[eventBox id="' + id + '"]</p>';
					html += '<h4>Main Event Card:</h4>';
					html += '<p>[eventCard id="' + id + '"]</p>';
					html += '<h4>Main Event Button:</h4><p>[eventButton id="' + id + '"][/eventButton]</p>';
				html += '</div>';

				var subData;
				for (var i = 0; i < json.length; i++) {
					subData = json[i]

					html += '<div class="addonCnt">';
				    html += "<h3>Shortcodes for event that occurs on " + subData.start_date + " @ " + subData.start_time + "</h3>";
  					html += '<h4>Specific Event Box:</h4>';
  					html += '<p>[eventBox id="' + id + '" date_id="' + subData.id + '"]</p>';
  					html += '<h4>Specific Event Card:</h4>';
  					html += '<p>[eventCard id="' + id + '" date_id="' + subData.id + '"]</p>';

  					html += '<h4>Specific Event Button:</h4>';
  					html += '<p>[eventButton id="' + id + '" date_id="' + subData.id + '"][/eventButton]</p>';
					html += '</div>';
				}

				html += '</div>';
				$cnt.html(html);

				$cnt.addClass("loadedAlready");
				$('#loader').slideUp(100);
			}
		});
	}

	function getBookingTable() {
		if ($("#ticketDateIDSELECT").length < 1){
			getBookings();
			return;
		} else {
  		var bookingDateID = $("#ticketDateIDSELECT").val();

  		$('#loader').slideDown(100);
  		$.ajax({
  			type:'POST',
  			url: $('input[name="ajaxlink"]').val() + '/wp-admin/admin-ajax.php',
  			data: 'action=event_booking_fetch&id=' + bookingDateID,
  			success: function(response) {
  				$('#loader').slideUp(100);
  				$(".bookings").html(response);

          $('.table').dynatable({
            features: {
              pushState: false
            }
          });

          CSV($("#bookings"));
  				valdiateBookingTables();
  			}
  		});
		}
	}

	function getBookings() {
		var id = $("#event-id").val();
		$('#loader').slideDown(100);
		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=event_booking_main&event-id=' +id,
			success: function(response) {
				$("#bookings").html(response);

        $('.table').dynatable({
          features: {
            pushState: false
          }
        });

        CSV($("#bookings"));
				valdiateBookingTables();

				$("#ticketDateIDSELECT").change(function() {
					getBookingTable();
				});

				$("#bookings").addClass("loadedAlready");
				$('#loader').slideUp(100);
			}
		});
	}


  $('.bookings .EBP--BookingDetails').live("click", function(e) {
    e.preventDefault();
    if ($(this).height() < 90 || e.offsetY < $(this).height() - 30) {
      return;
    }
    if ($(this).hasClass('EBP--expanded')) {
      $(this).removeClass('EBP--expanded');
    } else {
      $(this).addClass('EBP--expanded');
    }
  });

	$('.bookings a.markPaid').live("click", function(e) {
    e.preventDefault();
    markBook($(this))
  });

  $('.deleteBooking').live("click", function(e) {
    e.preventDefault();
    var confirmMsg = 'Are you sure?';

    if (window.appSettings.emailBookingCanceled == 'true') {
      confirmMsg += '\n\nThis will also send an email to the buyer.';
    }

    var confirm = window.confirm(confirmMsg);

    if (confirm) {
      deleteBooking($(this));
    }
  });

  $('.editBooking').live("click", function(e) {
    e.preventDefault();
    editBooking($(this))
  });

  $('#addNewBooking').live("click", function(e) {
    e.preventDefault();
    addBooking();
  });

  $('.resendEmail').live("click", function(e) {
    e.preventDefault();
    resendEmail($(this))
  });

	function markBook($this) {
    $('#loader').slideDown(100);
    var id = $this.attr("data-id");

    $.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=update_booking_status&id=' + id,

			success: function(response) {
				$this.parent().html(response);
				$('#loader').slideUp(100);
			}
		});
	}

	function CSV($cnt) {
		var $table = $cnt.find("table");
		if ($table.length > 0) {
      var $button = $("<a href='#' class='btn btn-primary'></a>");
      $button.text("Export to spreadsheet");
      $cnt.find(".bookings").append($button);

      $button.click(function () {
        toCSV($cnt)
      });
		}
	}


	function toCSV($cnt) {
		var id = $("#event-id").val();
		var startTime = $('#ticketDateIDSELECT').find(":selected").attr("data-startTime");

		var CSVDATA = [['', 'Booking-ID','Ticket','Type','Coupon','Quantity','Name','Email','Amount','Date','Payment-ID','Status','Details']];

		var temp, count;
		var cellValue = "";
		var cellArr;

		$cnt.find("table tr").each(function(index, element) {
			temp = [''];
			count = 0;
			$(this).find("td").each(function(index, element) {
				count ++;

        if (count == 8){
					cellValue = $(this).html()
				} else if(count < 13) {
		  		temp.push(encodeToCSV($(this).text()));
        }
			});

			if (cellValue != "") {
				cellValue = cellValue.replace(/<br>/g,"%");
				cellArr = cellValue.split("%");

        for(var s in cellArr) {
					temp.push(cellArr[s].split(":"));
        }
			}
			CSVDATA.push(temp);
		});

		var csvRows = ["Event:,"+$(".eventlist .active span").text()];
		csvRows.push("Starting-Date:,"+$('#ticketDateIDSELECT').find(":selected").text().replace(",","-"))
		csvRows.push('')
		csvRows.push("Bookings: ")

		for (var i = 0, l = CSVDATA.length; i < l; ++i) {
			csvRows.push(CSVDATA[i].join(','));
		}

		var csvString = csvRows.join("%0A");
		var a = document.createElement('a');
		a.href = 'data:attachment/csv,' + csvString;
		a.target = '_blank';
		a.download = 'bookings'+id+'.csv';

		document.body.appendChild(a);
		a.click();
	}

	function encodeToCSV(str) {
		str = str.replace(/<br>/g, '|').replace(" ", "-");
  	return '"' + str + '"';
	}

	function getEventDetails() {
		var id = $("#event-id").val();
		$('#loader').slideDown(100);
		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_get_event&event-id=' + id,
			success: function(response) {
				$("#eventEdit").addClass("loadedAlready");
				$('#loader').slideUp(100);
				var data = $.parseJSON(response);
				getEventPageMarkUp(id,data)
			}
		});
	}

	function getOccurenceHTML(id, startObj, endObj, startBookingObj, endBookingObj){
		var startDate = startObj.date;
	 	var startTime = startObj.time;

		var endDate = endObj.date;
		var endTime = endObj.time;

		if (!startBookingObj.date || startBookingObj.date === '') startBookingObj.date = startObj.date;
		if (!startBookingObj.time || startBookingObj.time === '') startBookingObj.time = startObj.time;

		if (!endBookingObj.date || endBookingObj.date === '') endBookingObj.date = endObj.date;
		if (!endBookingObj.time || endBookingObj.time === '') endBookingObj.time = endObj.time;

    var html = '<div class="Ebp--Occurrences--Occurrence">';

          html += '<div class="date-group start">  <span>Starts on:</span>';
            html += '<input  name="occurid" value="'+id+'" class="txtField" type="hidden"  />';
            html += '<input  name="date" value="'+startDate+'" class="txtField" type="text"  style="width:250px"/>';
            html += '<input  name="time" value="'+startTime+'" data-value="'+startTime+'" class="txtField" style="width:100px" type="text"  />';
            html += '<a href="#" class="tip-below tooltip Ebp--Occurrences--Occurrence--Delete close" data-tip="Deleting this will also delete all bookings for this occurrence! Edit the date/time to preserve the bookings made!">x</a>';
          html += '</div>';

          html += '<div class="date-group end">  <span>Ends On:</span>';
            html += '<input name="date" value="'+endDate+'" class="txtField" type="text" style="width:250px"/>';
            html += '<input name="time" value="'+endTime+'" data-value="'+endTime+'" class="txtField" style="width:100px" type="text"  />';
        	html += '</div>';

        	var uniqueId = _.uniqueId('bookingStart_checkbox_');

        	var isChecked = '';
        	var hideOptions = ''
        	if (startBookingObj.on === true  || startBookingObj.on === 'true') {
        		 isChecked = 'checked';
        		 hideOptions = 'style="display: none"';
        	}

        	html += '<div class="date-group bookingStart">  <span></span>';
        		html += '<input id="'+uniqueId+'" name="toggler" type="checkbox" '+isChecked+'/> <label for="'+uniqueId+'" >Booking opens immediately.</label>';

        		html += '<div class="options" '+hideOptions+'>';
        		html += '<input name="date" value="'+startBookingObj.date+'" class="txtField" type="text" style="width:250px"/>';
            html += '<input name="time" value="'+startBookingObj.time+'" data-value="'+startBookingObj.time+'" class="txtField" style="width:100px" type="text"  />';
            html += '</div>';
        	html += '</div>';

        	uniqueId = _.uniqueId('bookingEnd_checkbox_');


        	var isChecked = '';
        	var hideOptions = ''
        	if (endBookingObj.on === true || endBookingObj.on === 'true') {
        		 isChecked = 'checked';
        		 hideOptions = 'style="display: none"';
        	}

        	html += '<div class="date-group bookingEnd">  <span></span>';
        		html += '<input id="' + uniqueId + '" name="toggler" type="checkbox" ' + isChecked + '/> <label for="' + uniqueId + '" >Booking closes when event starts.</label>';

        		html += '<div class="options" ' +hideOptions + '>';
        		html += '<input name="date" value="' + endBookingObj.date + '" class="txtField" type="text" style="width:250px"/>';
            html += '<input name="time" value="' + endBookingObj.time + '" data-value="' + endBookingObj.time + '" class="txtField" style="width:100px" type="text"  />';
            html += '</div>';
        	html += '</div>';

    html += '</div>';

    return html;
  }

  // generate select field
  function generateSelectField(name, options, selectedItem) {
    var option;
    var selected;

    var selectHtml = '<select name="' + name + '" id="' + name + '">';

    for (var i = 0; i < options.length; i++) {
      option = options[i];

      selected = (option.id == selectedItem) ? 'selected="selected"' : '';

      selectHtml += '<option value="' + option.id + '" ' + selected + '>' + option.name.replace(/\\/g, '') + "</option>";
    }

    selectHtml += '</select>';

    return selectHtml;
  }

  // email_rules
  var GLOBAL_EMAIL_TEMPLATES;
  var GLOBAL_EMAIL_INC = 0;
  function addEmailRule (id, choosenEmailTemplate, hours, before, activated) {
    var html = '';
    GLOBAL_EMAIL_INC++;
    var beforeChecked = (before) ? 'checked' : '';
    var afterChecked = (!before) ? 'checked' : '';

    html += '<div class="ebp_email_rule" data-id="' + id +'">';
      // email template
      html += '<div>Email: ' + generateSelectField('rule_email_template_' + GLOBAL_EMAIL_INC, GLOBAL_EMAIL_TEMPLATES, choosenEmailTemplate) + '</div>';

      // hours
      html += '<div>'
        html +=  'Hours: <input type="number" name="hours" value="' + hours + '" min="0" />'
      html += '</div>'

      // before/after
      var beforeId = 'before_email_rule_' + GLOBAL_EMAIL_INC;
      var afterId = 'after_email_rule_' + GLOBAL_EMAIL_INC;
      html += '<div>'
        html += '<div class="radioOption"><input type="radio" id="' + beforeId + '" name="rule_' + GLOBAL_EMAIL_INC + '" value="before" ' + beforeChecked + '/> <label for="' + beforeId + '">Before</label></div>'
        html += '<div class="radioOption"><input type="radio" id="' + afterId + '" name="rule_' + GLOBAL_EMAIL_INC + '" value="after" ' + afterChecked + '/> <label for="' + afterId + '">After</label></div>'
      html += '</div>'

      // activated
      var activatedId = 'active_email_rule_' + GLOBAL_EMAIL_INC;
      var activedChecked = (activated) ? 'checked' : '';
      html += '<div>'
      html += '<div class="radioOption"><input type="checkbox" id="' + activatedId + '" name="activated" ' + activedChecked + '/> <label for="' + activatedId + '">Active</label></div>'
      html += '</div>'

      // remove
      html += '<div><a href="#" class="tip-below tooltip removeEventRule" data-tip="Deleting the rule will also delete all scheduled emails">x</a></div>';
    html += '</div>'

    return html;
  }

  function emailRulesHTML(emailTemplates, emailRules) {
    GLOBAL_EMAIL_TEMPLATES = emailTemplates;
    GLOBAL_EMAIL_INC = 0;
    var html = '';
    html += '<div class="event-row-large">';
      html += '<span class="Ebp--Title" style="margin-bottom:10px">Email Rules :</div>';

      if (window.appSettings.emailRulesEnabled !== 'true') {
        html += '<small>Disabled: rules are ignored. Activate in Settings > Email Rules</small>';
      }

      html += '<div class="emailRules">';
      var rule;
      for (var i = 0; i < emailRules.length; i++) {
        rule = emailRules[i];

        html += addEmailRule(rule.id, rule.template, rule.hours, rule.isBefore === 'true', rule.activated === 'true');
      };

      html += '</div>';

      html += '<a class="addEventRule btn btn-primary" href="#">+ Add event rule</a>'

    html += '</div>'
    return html;
  }

  // email_rules
  function emailRulesHooks() {
    $('.addEventRule').click(function(e) {
      e.preventDefault()
      var newRule = addEmailRule('-1', '', 24, true, true);
      $('#eventForm .emailRules').append(newRule);
    });

    $(document).on('click','#eventForm .emailRules .removeEventRule', function(e) {
      e.preventDefault();
      $(this).parent().parent().remove();
    });
  }

  // tickets
  function getSubTicketHtml (subTicket) {
    var html = '<div class="Ebp--Tickets--SubTickets">';
        html += '<div><em class="ticketname" >Name</em><em class="cost">Price</em></div>';
        html += '<div class="Ebp--Tickets--SubTickets--subTicket">';
          html += '<input class="name" name="name" value="' + subTicket.name.replace(/\\/g, '') + '" type="text"  />';
          html += '<input class="cost" name="cost" value="' + subTicket.cost + '" type="number"  /> ';
          html += '<a href="#" class="Ebp--Tickets--SubTickets--Delete">x</a>';
      html += '</div>';
    html += '</div>';

    return html;
  }

  function getTicketHtml (ticket) {
    var html = '<div class="Ebp--Tickets--Ticket">';
         html += '<div><em class="ticketname" >Name</em><em class="cost">Price</em><em class="allowed">Spots</em></div>';
       html += '<div>';
          html += '<input name="ticketid" value="' + ticket.id + '" class="txtField" type="hidden"  />';
          html += '<input class="ticketname" name="ticketname" value="' + ticket.name.replace(/\\/g, '') + '" type="text"  />';
          html += '<input class="cost" name="cost" value="' + ticket.cost + '" type="number"  /> ';
          html += '<input class="allowed" name="allowed" value="' + ticket.allowed + '" type="number"  /> ';
          html += '<a href="#" class="Ebp--Tickets--TicketDelete">x</a>';
       html += '</div>';

      html += '<div class="Ebp--Tickets--Ticket--SubCnt">';

      if (ticket.breakdown && ticket.breakdown != '' && ticket.breakdown.length > 0) {
        ticket.breakdown.forEach(function(subTicket) {
          html += getSubTicketHtml (subTicket);
        });
      }

        html += '<a href="#" class="btn Ebp--Tickets--SubTickets--Add">Add sub ticket</a>';
      html += '</div>';

     html += '</div>';

     return html;
  }
  function getEditTicketsSection(tickets, maxSpots) {
    var html = '<div class="Ebp--Tickets sect">';

    html += '<div class="Ebp--Title">Tickets : </div>';

    html += '<small><a class="Ebp--HelpBtn" href="http://iplusstd.com/item/eventBookingPro/example/tickets" target="_blank">?</a></small>';
    html += '<div class="Ebp--Tickets--Row Ebp--Tickets--List">';
    var ticket;
    for (var i = 0; i < tickets.length; i++) {
      ticket = tickets[i];
      ticket.breakdown = $.parseJSON(ticket.breakdown);
      html += getTicketHtml(ticket);
    }
    html += '</div>';


    html += '<div class="Ebp--Tickets--Row Ebp--Tickets--Add">';
    html += '<a class="btn btn-primary Ebp--Tickets--Add--Simple" href="#">Add ticket</a> ';
    html += '</div>';


    html += '<div class="Ebp--Tickets--MaxSpots">';
    html += '<div style=" vertical-align:top; padding-top:3px; margin-bottom:10px;">Maximum allowed spots per occurrence: </div>';

    var checked = (maxSpots != "-1") ? "checked" : "";

    html += '<div class="Ebp--Tickets--MaxSpotsCnt">';
    html += '<div class="hasWrapper"><div class="switch-square" id="maxSpotsSwitcher" data-isAnOption="yes" data-on-label="Enabled" data-off-label="Disabled"><input type="checkbox" ' + checked + ' ></div>';
    html += '</div>';

    html += '<div class="Ebp--Tickets--MaxSpotsInput"><input type="number" id="maxSpots" name="maxSpots" class="txtField mini" value="' + maxSpots + '"/></div>';

    html += '</div>';


    html += '</div>';
    html += '</div>';

    return html;
  }

  // get event page
	function getEventPageMarkUp(id, data) {
		var checked;

		var html = '<form name="eventForm"  method="post" id="eventForm" class="eventForm">';

		html += '<div class="sect">';
				 html += '<div class="head"><span class="Ebp--Title">Name : </span><input id="name" name="name" value="' + data.name.replace(/\\/g, '') + '" class="eventNametxtField" type="text"  /></div>';
			html += '</div>';

      // occurrences
		  html += '<div class="Ebp--Occurrences sect">';
				html += '<div class="Ebp--Title">Occurrences : </div>';
        html += '<div class="Ebp--Occurrences--List">';
				var dates = data.occur
				var subOcc;
				var startObj, endObj, startBookingObj, endBookingObj;
				for (var j = 0; j < dates.length; j++) {
          subOcc = dates[j];

          if (subOcc != "") {
						startObj = {date: subOcc.start_date, time: subOcc.start_time};
						endObj = {date: subOcc.end_date, time: subOcc.end_time};

						startBookingObj = {on: subOcc.bookingDirectly, date: subOcc.startBooking_date, time: subOcc.startBooking_time}
    				endBookingObj = {on: subOcc.bookingEndsWithEvent, date: subOcc.endBooking_date, time: subOcc.endBooking_time}

						html += getOccurenceHTML(subOcc.id, startObj, endObj, startBookingObj, endBookingObj);
					}
				}
        html += '</div>';

  			html += '<div class="Ebp--Occurrences--Add Ebp--Occurrences--Add--Single">';
          html += '<a href="" class="btn btn-primary">Add occurrence</a>';
  			html += '</div>';

        html += '<div class="Ebp--Occurrences--Add Ebp--Occurrences--Add--Multiple">';
          html += '<a href="" class="btn btn-primary">Generate batch (multiple) occurrences</a>';
  			html += '</div>';

  		html += '</div>';


      // booking
				html += '<div class="sect">';
				html += '<div class="Ebp--Title">Booking : </div>';

					html += '<div class="event-row-large">';
						html += doOption({name: "paypal", value: data.paypal, type: "toggle", title: "PayPal: "});
					html += '</div>';

					html += '<div class="event-row-large">';
						html += doOption({name: "offlineBooking", value: data.modal, type: "toggle", title: "Offline Booking: "});
					html += '</div>';

					if (data.gateways != ""){
            var gatway = data.gateways.split("%");
						var gatwaySubData;
						for(var i = 0; i < gatway.length; i++) {
							gatwaySubData = gatway[i].split('=');

							html += '<div class="event-row-large">';

                html += '<span class="label" style=" vertical-align:top; padding-top:3px; margin-bottom:10px;">'+gatwaySubData[0]+':</span>';

  							checked = (gatwaySubData[1] ==='true') ? 'checked' : '';
  							html += '<div class="hasWrapper">';
                  html += '<div class="switch-square gateway" data-name="' + gatwaySubData[0] + '" data-isAnOption="yes" data-on-label="On" data-off-label="Off"><input type="checkbox" ' + checked + '></div>';
							  html += '</div>';

              html += '</div>';
						}
					}


					html += '<div class="event-row-large">';
						html += doOption({name: "showPrice", value: data.showPrice, type: "toggle", title: "Show Price: "});
					html += '</div>';

					html += '<div class="event-row-large">';
						html += doOption({name: "showSpots", value: data.showSpots, type: "toggle", title: "Show Spots Left: "});
					html += '</div>';


				html += '</div>';

        // tickets
        html += getEditTicketsSection(data.tickets, data.maxSpots);


        //image
				html += '<div class="sect">';
					html += '<div class="Ebp--Title">Image : </div>';

						html += "<div class='sectCnt upload'>";

							html += '<input type="hidden" id="image" class="regular-text text-upload" name="image" value="'+data.image+'" />';

							html += '<a href="#" class="btn btn-primary button-upload">Add/Change</a>';
							html += '<a href="#" class="removeImg" style="margin-left:10px">remove</a>';
							html += '<img  src="'+data.image+'" class="preview-upload"/>';
						html += '</div>';

				html += '</div>';


       	//background
				if (!data.background) data.background = '';
				html += '<div class="sect">';
					html += '<div class="Ebp--Title">Background : </div>';
          html += '<small>Can be used in EventCard and EventCalendar (Set in Settings page)</small>';

						html += "<div class='sectCnt upload'>";

							html += '<input type="hidden" id="background" class="regular-text text-upload" name="background" value="'+data.background+'" />';

							html += '<a href="#" class="btn btn-primary button-upload">Add/Change</a>';
							html += '<a href="#" class="removeImg" style="margin-left:10px">remove</a>';
							html += '<img  src="' + data.background.split('__and__')[0] + '" class="preview-upload"/>';
						html += '</div>';

				html += '</div>';


				//map
				html += '<div class="sect" id="mapControl">';
				html += '<div class="Ebp--Title">Location: </div>';

					 html += '<div class="event-row spaced"><span style="vertical-align:top;">Address Type:</span>';

						var addressType = (data.mapAddressType=="address") ? 'selected="selected"' : '';
						var latlongType = (data.mapAddressType=="latlng") ? 'selected="selected"' : '';
						html += '<select id="mapAddressType" name="mapAddressType">';
							html += '<option value="address" '+addressType+'>Address</option>';
							html += '<option value="latlng" '+latlongType+'>Latitude/Longitude</option>';
						html += '</select>';
					 html += "</div>";

				    html += '<div class="event-row spaced"><span style="vertical-align:top;">Location address (gmaps):</span>';
							html += '<input  name="mapAddress" value="'+data.mapAddress+'" class="txtField" type="text"  />';
              html += '<small  style="float:none;margin-left:10px; display: inline-block;" >leave location emtpy to remove googlemaps. </small>';
						html += "</div>";

						html += '<div class="event-row spaced"><span style="vertical-align:top;">Map Zoom Level:</span>';
					    	html += '<input name="mapZoom" value="'+data.mapZoom+'" class="txtField mini" type="number"  /> ';
					    html += "</div>";



					html += '<div class="event-row spaced"><span style="vertical-align:top;">Map Type:</span>';
						var HYBRID = (data.mapType=="HYBRID") ? 'selected="selected"' : '';
						var ROADMAP = (data.mapType=="ROADMAP") ? 'selected="selected"' : '';
						var SATELLITE = (data.mapType=="SATELLITE") ? 'selected="selected"' : '';
						var TERRAIN = (data.mapType=="TERRAIN") ? 'selected="selected"' : '';

						html += '<select id="mapType" name="mapType">';
							html += '<option value="HYBRID" '+HYBRID+'>HYBRID</option>';
							html += '<option value="ROADMAP" '+ROADMAP+'>ROADMAP</option>';
							html += '<option value="SATELLITE" '+SATELLITE+'>SATELLITE</option>';
							html += '<option value="TERRAIN" '+TERRAIN+'>TERRAIN</option>';
						html += '</select>';

            if (window.appSettings.googleMapsEnabled == 'false') {
              html += '<div class="warning" style="margin-top:20px">Warning: Maps are disabled. To use them enable maps in Settings > Maps</div>';
            }

					 html += "</div>";

             html += '<div class="event-row spaced"><span style="vertical-align:top;">Alternative Location address:</span>';
              html += '<input  name="address" value="'+data.address+'" class="txtField" type="text"  />';
              html += '<small  style="float:none;margin-left:10px; display: inline-block;" >Used for display only. If left empty location above is used (if available).</small>';
            html += "</div>";


            html += "</div>";


					   //description
					   html += '<div class="sect">';
					   html += '<div class="Ebp--Title">Description : </div>';

					   html += '<div class="event-row spaced"><textarea id="info" name="info"class="textareaField">'+data.info.replace(/\\/g, '')+'</textarea> </div>';
					   html += '</div>';

						//form
						html += '<div class="sect">';
							html += '<div class="Ebp--Title">Forms : </div>';
								if (data.hasForms == "false"){
									 html += '<div class="event-row spaced ">';
									html += '<div class="alert alert-info"><h3>To use Custom Forms you have to <a href="http://iplusstd.com/item/eventBookingPro/buyFormsAddon.php">purchase</a>/enable the <a href="http://iplusstd.com/item/eventBookingPro/buyFormsAddon.php">Event Booking Forms Add-on</a></h3>';
									html += "Default form will be used. Customize it in settings page: It contains 4 fields: Name, email, phone and Address. The last two can be toggled on and off.</div>";
									html += '<input  id="formID" type="hidden" value="'+data.form+'">';
								} else {
									 html += '<div class="event-row ">';

									 html += '<span style="vertical-align:top;">Choose Form:</span>';
									html += generateSelectField('formID', data.forms, data.form)
								}

							html += '</div>';
						html += '</div>';

						//email
						html += '<div class="sect">';
							html += '<span class="Ebp--Title" style="margin-bottom:10px">Emails : </div>';

                if (data.hasEmailTemplates == "false"){
                  html += '<div class="event-row spaced ">';
										html += '<div class="alert alert-info"><h3>Requires Email Templates Add-on. <a href="http://iplusstd.com/item/eventBookingPro/buyEmailTemplatesAddon.php">Purchase</a> or enable the addon.</h3><br/>Allows you to change the email template and set an alternative email address to receive email when a booking happens.</div>';
										html += '<input id="emailTemplateID" type="hidden" value="'+data.emailTemplateID+'">';
									html += '</div>';
								} else {
                  var emailTemplates = data.emailTemplates;

									html += '<div class="event-row ">';

									 html += '<span style="vertical-align:top;">Confirmation Email Template:</span>';
                	 html += generateSelectField("emailTemplateID", emailTemplates, data.emailTemplateID);
									html += '</div>';

									html += '<div class="event-row-large">';
										if (!validateEmail(data.ownerEmail)) data.ownerEmail = '';

										html +=  getToggling({title: 'Alternative email address:',
												info: "Alternative Email address to receive an email when a booking happens. Default is set in settings.",
												value: (data.ownerEmail === '') ? 'false' : 'true',
												name: "ownerEmailToggle",
												items: [
														doOption({name: "ownerEmail", value: data.ownerEmail,
																type: "input", title: "Email: "})
														]})	;

									html += '</div>';

                  // email_rules
                  var clonedEmailTempaltes = emailTemplates.slice(0);
                  // remove first element (default email template)
                  clonedEmailTempaltes.shift();
                  if (data.hasEmailRules === 'true') {
                    var emailRulesArr = (data.emailRules) ? data.emailRules : [];

                    html += emailRulesHTML(clonedEmailTempaltes, emailRulesArr);
                  } else {
                    html += '<div class="alert alert-info" style="margin-top:20px">Update email addon to v 1.0+ and use the "email rules" feature.</div>';
                  }
							}

						html += '</div>';

            html += '<div class="sect EventOperations--Sect">';
            html += '<span class="Ebp--Title" style="margin-bottom:10px">Event operations: </span>';
            html += '<div class="sectCnt" style="min-height: 35px;"><a href="#" class="btn btn-small btn-activate">Activate</a><a href="#" class="btn btn-small btn-cancel btn-danger">Cancel Event</a><a href="#" class="btn btn-small btn-operationLogs">Show logs</a></div>';
            html += '</div>';



            html += '<div class="btns stick"><a href="#" class="btn btn-small btn-danger btn-delete">Delete</a><a href="#" class="btn btn-small btn-duplicate btn-primary">Duplicate</a><a href="#" class="btn btn-small btn-success btn-save">Save</a></div>';



					 html += '</form><br class="ev-clear"/>';

		$("#eventEdit").html(html);

    setEventStatus(data.eventStatus);


    // email_rules
    html += emailRulesHooks();

    //
		$('.switch-square')['bootstrapSwitch']();

		// start do hiding
		setTimeout(function() {
		 	$('.switcher').each(function(index, element) {
		 		var $switcher = $(this).find('.make-switch');
        if ($switcher.hasClass("hasCnt")) {
          var inverseToggle = $switcher.hasClass('inverseToggle');
          isOn = $switcher.find('.switch-animate').hasClass('switch-off');

        	if (!inverseToggle && isOn || inverseToggle && !isOn) {
						$(this).find(".cnt").slideUp(100);
        	} else {
        		$(this).find(".cnt").slideDown(100);
        	}
        }
			});
		 }, 10);


  	$('.make-switch').on('switch-change', function (e, data) {
      var $parent = $(this).parent().parent().parent();

      if ($(this).hasClass("hasCnt")) {
        var inverseToggle = $(this).hasClass('inverseToggle');

        if ((!inverseToggle && data.value ) || (inverseToggle && !data.value)) {
          var oldAttr = ($parent.find(".isBorder").attr("data-oldvalue")) ? $parent.find(".isBorder").attr("data-oldvalue") : "1";

          $parent.find(".isBorder input").val(oldAttr);
          $parent.find(".cnt").slideDown(100);

        } else {
          $parent.find(".isBorder").attr("data-oldvalue", $parent.find(".isBorder input").val());

          $parent.find(".cnt").slideUp(100, "linear", function() {
              $(this).find(".isBorder input").val("0");
          });
        }
      }
  	});

		if (data.maxSpots == "-1") {
			$('.Ebp--Tickets--MaxSpotsInput').hide();
    }

	  $('#maxSpotsSwitcher').on('switch-change', function (e, data) {
			if (data.value) {
				if (parseInt($('input[name="maxSpots"]').val()) < 0) {
					$('input[name="maxSpots"]').val("1");
        }
				$('.Ebp--Tickets--MaxSpotsInput').show(100);
			} else {
				$('.Ebp--Tickets--MaxSpotsInput').hide(100,"linear",function(){
						$('input[name="maxSpots"]').val("-1");
				});
			}
		});


		$('.upload').uploader();


		$(".Ebp--Occurrences--Add--Single a").click(function(e){
			e.preventDefault();

      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth()+1;
      var yyyy = today.getFullYear();

      if (dd < 10) dd = '0'+dd;
      if (mm < 10) mm ='0'+mm;

      var lastDateUsed = yyyy + '-' + mm + '-' + dd;;

      if ($('.Ebp--Occurrences--Occurrence').length > 0) {
        var lastDateObj = $('.Ebp--Occurrences--Occurrence').last().find('.start input[name="date"]').datepicker('getDate');
        lastDateUsed = lastDateObj.getFullYear() + "-" + (lastDateObj.getMonth() + 1) + "-" + lastDateObj.getDate();
      }

			var startObj = {
				date: lastDateUsed,
				time :"20:00:00"
			};
      var endObj = {
        date: startObj.date,
        time :"22:00:00"
      };

			var startBookingObj = {
        on: 'true',
        date: '',
        time: ''
      };

			var occurrence = getOccurenceHTML("new", startObj, endObj, startBookingObj, startBookingObj);

			$('.Ebp--Occurrences .Ebp--Occurrences--List').append(occurrence);

			$('.Ebp--Occurrences .Ebp--Occurrences--Occurrence').last().find('.start input[name="date"]').attr("id", "");
			$('.Ebp--Occurrences .Ebp--Occurrences--Occurrence').last().find('.end input[name="date"]').attr("id", "");

			if ($('.Ebp--Occurrences .Ebp--Occurrences--Occurrence').last().find('.start input[name="date"]').hasClass("hasDatepicker")){
				$('.Ebp--Occurrences .Ebp--Occurrences--Occurrence').last().find('.start input[name="date"]').removeClass("hasDatepicker");
      }

      if ($('.Ebp--Occurrences .Ebp--Occurrences--Occurrence').last().find('.end input[name="date"]').hasClass("hasDatepicker")) {
				$('.Ebp--Occurrences .Ebp--Occurrences--Occurrence').last().find('.end input[name="date"]').removeClass("hasDatepicker");
      }
			getDateRowStuff($('.Ebp--Occurrences .Ebp--Occurrences--Occurrence').last())
		});

		$(".Ebp--Occurrences--Occurrence").each(function(index, element) {
			getDateRowStuff($(this))
		});

    try {
      tinymce.remove();
    } catch(exception){
      console.log(exception)
    }

    tinymce.init({
      selector: 'textarea',
      height: 500,
      plugins: [
        'advlist autolink lists link image charmap preview anchor',
        'searchreplace visualblocks code',
        'insertdatetime media table contextmenu paste code'
      ],
      toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
      content_css: [
        '//fonts.googleapis.com/css?family=Open+Sans',
        '//www.tinymce.com/css/codepen.min.css'
      ]
    });
	}

  function setEventStatus(active) {
    $('.btn-activate').attr('data-active', active);
    var id = $("#event-id").val();
    $eventLI = $("#event_" + id);
    $eventLI.removeClass('eventDeactivated');

    if (active == 'active') {
      $('.btn-activate').text('Deactivate event');
      $('.btn-activate').addClass('btn-danger');
      $('.btn-activate').removeClass('btn-success');
    } else {
      $('.btn-activate').text('Activate event');
       $('.btn-activate').addClass('btn-success');
      $('.btn-activate').removeClass('btn-danger');
      $eventLI.addClass('eventDeactivated');
    }
  }

  $('.btn-activate').live('click', function(e){
    e.preventDefault();

    var eventStatus;
    if ($('.btn-activate').attr('data-active') == 'active') {
      eventStatus = 'canceled';
    } else {
      eventStatus = 'active';
    }

    var id = $("#event-id").val();
    $('#loader').slideDown(100);
    $.ajax({
      type:'POST',
      url: 'admin-ajax.php',
      data: 'action=ebp_set_event_active&id=' + id +'&eventStatus=' + eventStatus,
      success: function(response) {
        $('#loader').slideUp(100);
        setEventStatus(eventStatus);
      }
    });
  });


  $('.btn-operationLogs').live('click', function(e){
    e.preventDefault();

    var id = $("#event-id").val();
    $('#loader').slideDown(100);
    $.ajax({
      type:'POST',
      url: 'admin-ajax.php',
      data: 'action=ebp_get_operation_logs&id=' + id,
      success: function(response) {
        var json = $.parseJSON(response);

        $('#loader').slideUp(100);
        var html = '<h2>"' + $("#name").val() + '" logs</h2>';

        if (json.length < 1) {
          html += 'No operations yet!';
        } else {
          html += '<table class="table">'
          html += '<thead><th>Date</th><th>Operation</th><th>User</th><th>Notes</th></thead>'
          html += '<tbody>';
          json.forEach(function(record) {
            html += '<tr>';
            html += '<td>' + record.date + '</td>';
            html += '<td>' + record.type + '</td>';
            html += '<td>' + record.user_login + '</td>';
            html += '<td>' + record.log + '</td>';
            html += '</tr>';
          })
          html += '<tbody>';
          html += '</table>'
        }

        setPopUpContent(html);
        openPopUp();
        $('.table').dynatable({
          features: {
            pushState: false
          }
        });
      }
    });
  });

  $('.btn-cancel').live('click', function(e){
    e.preventDefault();

    if (window.appSettings.sendEmailWhenCancelled == 'true') {
      var alertResult = confirm('This will send an email to all bookers of upcoming occurrences.');
      if (!alertResult) return;
    }

    var id = $("#event-id").val();
    $('#loader').slideDown(100);
    $.ajax({
      type:'POST',
      url: 'admin-ajax.php',
      data: 'action=ebp_set_event_cancel&id=' + id,
      success: function(response) {
        $('#loader').slideUp(100);
        setEventStatus('canceled');
      }
    });
  });


	$('.upload a.removeImg').live("click",function(e) {
		e.preventDefault();
		$(this).parent().find('input').val('');
		$(this).parent().find(".preview-upload").attr('src',"");
	});

	$(".Ebp--Tickets--Add--Simple").live("click",function(e) {
    e.preventDefault();

    $('.Ebp--Tickets .Ebp--Tickets--List').append(getTicketHtml({
      id: 'new',
      name: 'Ticket Name',
      cost: 0,
      allowed: 100,
      breakdown: []
    }));
  });

   $(".Ebp--Tickets--SubTickets--Add").live("click",function(e) {
    e.preventDefault();
    $(this).before(getSubTicketHtml ({
        name: 'Name',
        cost: 0
    }));
  });


  $(".Ebp--Tickets .Ebp--Tickets--TicketDelete").live("click",function(e) {
    e.preventDefault();
    $(this).parent().parent().remove();
  });

  $(".Ebp--Tickets--SubTickets--Delete").live("click",function(e) {
    e.preventDefault();
    $(this).parent().parent().remove();
  });

  $(".Ebp--Occurrences--Occurrence--Delete").live("click", function(e){
    e.preventDefault();
    $(this).parent().parent().remove();
  });




	function getDateRowStuff($which){
		if ($which.attr("data-processed") === "processed") return;

		$which.attr("data-processed","processed");



		$which.find('.start input[name="time"]').timepicker({defaultTime: $which.find('.start input[name="time"]').attr("data-value")});
		$which.find('.end input[name="time"]').timepicker({defaultTime: $which.find('.end input[name="time"]').attr("data-value")});

		var date_str_s = $which.find('.start input[name="date"]').val().split("-");
		var date_str_e = $which.find('.end input[name="date"]').val().split("-");

		var date_s = new Date(date_str_s[0], date_str_s[1]-1, date_str_s[2]);
		var date_e = new Date(date_str_e[0], date_str_e[1]-1, date_str_e[2]);

		$which.find('.start input[name="date"]').datepicker({ dateFormat: "DD, d MM, yy"});
		$which.find('.end input[name="date"]').datepicker({ dateFormat: "DD, d MM, yy"});

		$which.find('.start input[name="date"]').datepicker("setDate", date_s);
		$which.find('.end input[name="date"]').datepicker("setDate", date_e );

		//bookingStart
		$which.find('.bookingStart input[name="toggler"]').click(function () {
		  $(this).parent().find('.options').toggle(!this.checked);
		});
		$which.find('.bookingEnd input[name="toggler"]').click(function () {
		  $(this).parent().find('.options').toggle(!this.checked);
		});

		$which.find('.bookingStart input[name="time"]').timepicker({defaultTime: $which.find('.bookingStart input[name="time"]').attr("data-value")});
		$which.find('.bookingEnd input[name="time"]').timepicker({defaultTime: $which.find('.bookingEnd input[name="time"]').attr("data-value")});

		date_str_s = $which.find('.bookingStart input[name="date"]').val().split("-");
		date_str_e = $which.find('.bookingEnd input[name="date"]').val().split("-");

		date_s = new Date(date_str_s[0], date_str_s[1] -1, date_str_s[2]);
		date_e = new Date(date_str_e[0], date_str_e[1]-1, date_str_e[2]);

		$which.find('.bookingStart input[name="date"]').datepicker({ dateFormat: "DD, d MM, yy"});
		$which.find('.bookingEnd input[name="date"]').datepicker({ dateFormat: "DD, d MM, yy"});

		$which.find('.bookingStart input[name="date"]').datepicker("setDate", date_s);
		$which.find('.bookingEnd input[name="date"]').datepicker("setDate", date_e );
	}

	// btns
  $('.btn-delete').live('click', function(e){
    e.preventDefault();
    deleteData();
  });

  $('.btn-save').live('click',function(e){
    e.preventDefault();
    saveEvent();
  });

  $('.btn-duplicate').live('click',function(e){
    e.preventDefault();
    duplicateEvent($('#eventForm #name').val());
  });

	$('.Ebp--Occurrences--Add--Multiple a').live('click', function(e){
		e.preventDefault();
		openAdvancedDates()
	});

	$('.ebp-content input[name="start_time"]').timepicker();
	$('.ebp-content input[name="end_time"]').timepicker();
	$('.ebp-content input[name="start_date"]').datepicker({ dateFormat: "DD, d MM, yy"});
	$('.ebp-content input[name="end_date"]').datepicker({ dateFormat: "DD, d MM, yy"});

  $('#advancedDates #bookingsStartClose input[name="BookingCloseOpenTime"]').timepicker();

	$('.ebp-content input[name="start_date"]').datepicker("setDate", $('.ebp-content input[name="end_date"]').datepicker('getDate'));
	$('.ebp-content input[name="end_date"]').datepicker("setDate", $('.ebp-content input[name="end_date"]').datepicker('getDate'));

	$('.ebp-content  .generateDates').live("click", function(e) {
		e.preventDefault();
		var startDate = $('.ebp-content input[name="start_date"]').datepicker('getDate');
		var endDate = $('.ebp-content input[name="end_date"]').datepicker('getDate');
		var per = $('.ebp-content  input[name="advanced_per"]:checked').val();

		var startTime_F = $('.ebp-content input[name="start_time"]').val();
		var endTime_F = $('.ebp-content input[name="end_time"]').val();
		var days = parseInt($('.ebp-content input[name="event_days"]').val()) - 1;

		var startYear = startDate.getFullYear();
		var endYear = endDate.getFullYear();

		var startMonth = startDate.getMonth() + 1;
		var endMonth = endDate.getMonth() + 1;

		var startDay = startDate.getDate();
		var endDay = endDate.getDate();

		var daysOkay = [];

		if (per === "month") {
			for(var c = 1; c <= 31;c++)
				daysOkay.push($(".daysCheck #w-"+c).is(':checked'));
		} else if (per === "week"){
			for(var c = 0; c <= 6;c++)
				daysOkay.push($(".weekDaysCheck #d-"+c).is(':checked'));
		}

		var datesArr = [];

		var d_s,d_e, m_s,m_e;

		for (var y = startYear; y <= endYear; y++) {
			m_s = 1;
			m_e = 12;

			if (y == startYear) m_s = startMonth;

      if (y == endYear)	m_e = endMonth;

			for (var m = m_s; m <= m_e; m++){
				d_s = 1;

				if (m == startMonth && y == startYear) d_s = startDay;

				d_e = 31;

				if (m == endMonth && y == endYear) d_e = endDay;

				for(var d = d_s; d <= d_e; d++){
					if (per === "month"){
						if (daysOkay[d-1] && isValidDate(m, d, y)) {
              datesArr.push(y + "-" + m + "-" + d);
            }
					} else if (per === "week") {

            if (isValidDate(m, d, y)) {
							var dayDate = new Date(y, m-1, d);

              if (daysOkay[dayDate.getDay()]) {
                datesArr.push(y + "-" + m + "-" + d)
              }
						}
					}
				}
			}
		}

    // bookings
    var batchStartBookingObj = getBookingClosesOpensOptions($('#advancedDates #bookingsStartClose .bookingOpens'));
    var batchEndBookingObj = getBookingClosesOpensOptions($('#advancedDates #bookingsStartClose .bookingCloses'));

    function getBookingClosesOpensOptions($localParent) {
      var isOn = $localParent.find('input[type="checkbox"]').is(':checked');
      var bookingClosesOpensDays = '';
      var bookingClosesOpensTime = '';

      if (!isOn) {
        bookingClosesOpensDays = $localParent.find('.CB_deselected_Cnt input[name="bookingCloseOpenDays"]').val();
        bookingClosesOpensTime = formatTime($localParent.find('.CB_deselected_Cnt input[name="BookingCloseOpenTime"]').val());
      }

      return {on: isOn, days: bookingClosesOpensDays, time: bookingClosesOpensTime};
    }

		var finishDate;
    var startObj;
    var endObj;
    var startBookingObj;
    var endBookingObj;

    var startBookingOn;
    var startBookingTime;
    var startBookingDay;
    var endBookingOn;
    var endBookingTime;
    var endBookingDay;

		for (var o in datesArr) {
      finishDate = editDateByDays(datesArr[o], days);

			startObj = {date: datesArr[o], time: startTime_F};
			endObj = {date: finishDate, time: endTime_F};


      startBookingOn =  batchStartBookingObj.on;
      startBookingTime = (!startBookingOn) ? batchStartBookingObj.time : "";
      if (startBookingOn) {
        startBookingDate = '';
      } else {
        startBookingDate = editDateByDays(datesArr[o], - parseInt(batchStartBookingObj.days))
      }
      startBookingObj = {on: startBookingOn, date: startBookingDate, time: startBookingTime};

      endBookingOn =  batchEndBookingObj.on;
      endBookingTime = (!endBookingOn) ? batchEndBookingObj.time : "";
      if (endBookingOn) {
        endBookingDate = '';
      } else {
         endBookingDate = editDateByDays(datesArr[o], - parseInt(batchEndBookingObj.days))
      }

      endBookingObj = {on: endBookingOn, date: endBookingDate, time: endBookingTime};

			$('.Ebp--Occurrences--Add--Single').before(getOccurenceHTML("new", startObj, endObj, startBookingObj, endBookingObj));
		}

		$(".Ebp--Occurrences--Occurrence").each(function(index, element) {
			getDateRowStuff($(this));
		});

		$(".ebp-show").removeClass('ebp-show' );
		$(document).removeClass('ebp-perspective');

	});

  function editDateByDays(dateString, days) {
    var date = new Date(dateString);

    date.setDate(date.getDate() + days);
    return date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate()
  }

	function isValidDate(m, d, y) {
		if (m == 3 && d == 30) return true;

    var date = new Date(y, m - 1, d);

    return (date.getFullYear() == y && (date.getMonth() + 1) == m && date.getDate() == d);
  }

	$('#advancedDates input[name="advanced_per"]').change(function () {
    if ($(this).is(':checked')) {
			$('.ebp-content .radioToggled').not("." + $(this).attr("data-toggle")).hide(100);
			$("." + $(this).attr("data-toggle")).show(100);
		}
  });


  $('#advancedDates #bookingsStartClose input[type="checkbox"]').change(function () {

    var isSelected = $(this).is(':checked');
    var $cbCnt = $(this).parent().find('.CB_deselected_Cnt')

    if (isSelected) {
      $cbCnt.hide();
    } else {
      $cbCnt.show();
    }
  });

  function resetBookingsStartClose() {
    $('#advancedDates #bookingsStartClose input[type="checkbox"]').each(function () {
      $(this).parent().find('.CB_deselected_Cnt').hide();
      $(this).attr('checked', true);
    })
  }

	function openAdvancedDates(){
		var $modalAdvaced = $('#advancedDates');
    resetBookingsStartClose();

		$modalAdvaced.addClass('ebp-show');

		setTimeout( function() {$(document).addClass('ebp-perspective');}, 25);

		$(document).on('click', 'a.ebp-close, .ebp-overlay', function(e) {
			$modalAdvaced.removeClass('ebp-show' );
			$(document).removeClass('ebp-perspective');
		 });
	}

	function prepareEventPage(id, data) {
    var html = '<ul class="nav nav-tabs">';
    html += '<li class="active"><a href="#" id="edit-btn" class="tabBtn">Edit Event</a></li>';
    html += '<li><a href="#" id="booking-btn"  class="tabBtn  ">View Bookings</a></li>';
    html += '<li><a href="#" id="coupon-btn"  class="tabBtn  ">Coupons</a></li>';
    html += '<li><a href="#" id="category-btn"  class="tabBtn ">Categories</a></li>';
    html += '<li><a href="#" id="shortcode-btn"  class="tabBtn ">Shortcodes</a></li></ul>';
    html += '<input type="hidden" id="event-id" value="'+id+'"/>';
    html += '<br class="ev-clear"><div id="eventEdit" class="eventCnt"></div>';
    html += '<div id="bookings" class="eventCnt" style="display:none;"></div>';
    html += '<div id="eventShortCodes" class="eventCnt" style="display:none;"></div>';
    html += '<div id="eventCoupons" class="eventCnt" style="display:none;">';
    html += '<div style="display:block; width:100%; position:relative; margin-top:30px;">';
    html += '<a href="#" class="btn btn-small btn-success eventCouponsSave" style="margin:0 auto;">Save</a>';
    html += '</div>';
    html += '</div>';

    html += '<div id="eventCategories" class="eventCnt" style="display:none;">';
    html += '<div style="display:block; width:100%; position:relative; margin-top:30px;">';
    html += '<a href="#" class="btn btn-small btn-success eventCategoriesSave" style="margin:0 auto;">Save</a>';
    html += '</div>';
    html += '</div>';

    $('.eventDetails .cnt').html(html);

    if (data[0] === "load") {
        getEventDetails();
    } else {
        getEventPageMarkUp(id, data);
        saveEvent();
    }
	}

  $(".eventCouponsSave").live('click', function(e) {
    e.preventDefault();
    saveEventCoupons()
  });

  $("#coupon-btn").live('click', function(e) {
    e.preventDefault();
    $(this).parent().parent().find(".active").removeClass("active");

    if (!$(this).parent().hasClass("active")){
      $(this).parent().addClass("active");
      $("#eventEdit").slideUp(100);
      $("#bookings").slideUp(100);
      $("#eventShortCodes").slideUp(100);
      $("#eventCoupons").slideDown(100);
      $("#eventCategories").slideUp(100);

      if (!$("#eventCoupons").hasClass("loadedAlready")) {
        eventCoupons($("#eventCoupons"));
      }
    }

  });

  $("#category-btn").live('click', function(e) {
    e.preventDefault();
    $(this).parent().parent().find(".active").removeClass("active");

    if (!$(this).parent().hasClass("active")){
      $(this).parent().addClass("active");
      $("#eventEdit").slideUp(100);
      $("#bookings").slideUp(100);
      $("#eventShortCodes").slideUp(100);
      $("#eventCoupons").slideUp(100);
      $("#eventCategories").slideDown(100);

      if (!$("#eventCategories").hasClass("loadedAlready")) {
        eventCategories($("#eventCategories"));
      }
    }
  });

  $(".eventCategoriesSave").live('click',function(e){
    e.preventDefault();
    $('#loader').slideDown(100);
    var id = $('#event-id').val();
    var data = "";
    $('#eventCategories a.category.toggle').each(function(index, element) {
      data += '&categoryid-' + $(this).attr("data-id") + '=' + $(this).attr("data-id")
        + '&selected-' + $(this).attr("data-id") + '=' + $(this).hasClass("notselected");
    });

    $.ajax({
      type:'POST',
      url: 'admin-ajax.php',
      data: 'action=ebp_event_categories_update&id=' + id + "&data=" + data,
      success: function(response) {
          $('#loader').slideUp(100);
      }
    });
  });

  $("#booking-btn").live('click', function(e) {
    e.preventDefault();
    $(this).parent().parent().find(".active").removeClass("active");

    if (!$(this).parent().hasClass("active")) {
      $(this).parent().addClass("active");
      $("#eventEdit").slideUp(100);
      $("#eventCoupons").slideUp(100);
      $("#bookings").slideDown(100);
      $("#eventShortCodes").slideUp(100);
      $("#eventCategories").slideUp(100);
      getBookings();
    }
  });

  $("#shortcode-btn").live('click', function(e) {
    e.preventDefault();
    $(this).parent().parent().find(".active").removeClass("active");
    if (!$(this).hasClass("active")){
      $(this).parent().addClass("active");
      $("#eventEdit").slideUp(100);
      $("#eventCoupons").slideUp(100);
      $("#bookings").slideUp(100);
      $("#eventShortCodes").slideDown(100);
      $("#eventCategories").slideUp(100);
      getShortCodes($("#eventShortCodes"));
    }
  });

  $("#edit-btn").live('click', function(e) {
    e.preventDefault();
    $(this).parent().parent().find(".active").removeClass("active");
    if (!$(this).parent().hasClass("active")){
        $(this).parent().addClass("active");
        $("#eventEdit").slideDown(100);
        $("#eventCoupons").slideUp(100)
        $("#eventCategories").slideUp(100);;
        $("#eventShortCodes").slideUp(100);
        $("#bookings").slideUp(100);

        if (!$("#eventEdit").hasClass("loadedAlready")) getEventDetails();
    }
  });

	function eventClicked($which) {
		$(".adminHeader a.EBP--TopBtn").removeClass("active");
		$(".eventlist li a.active").removeClass("active");
		$which.addClass("active");

     getDetails($which.attr("data-id"));
	}

	function deleteData(){
		var id = $("#event-id").val();
		$('#loader').slideDown(100);
		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_event_delete&id=' + id,
			success: function(response) {
				$('#loader').slideUp(100);
			}
		});

		$("#event_"+id).remove();
		$(".eventDetails .cnt").empty();
	}

	function resendEmail($which) {
		$('#loader').slideDown(100);
		var id = $which.parent().parent().find("td:first").html();

		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=resendEmail&id=' + id,
			success: function(response) {
				$('#loader').slideUp(100);
			}
		});
	}


	function deleteBooking($which){
		var id = $which.parent().parent().find("td:first").html();

		$('#loader').slideDown(100);
		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=booking_delete&id=' + id,
			success: function(response) {
        $('#loader').slideUp(100);
				$which.parent().parent().remove();
				valdiateBookingTables()
			}
		});

	}
	var $lastBookingClicked;


	function addBooking () {
		var id = "-1";

		var html = '<h3>Add Booking</h3>';
		html += '<ul id="editBookingUL" data-id="'+id+'">';
		html += '<li><span>Tickets</span></li>';
		var label;
    var labelName;
    var defaultTaxRate = $('#bookings input[name="taxRate"]').val();

		for(var i = 3; i < $(".bookings table thead th").length - 2; i++) {
			label = $(".bookings table thead th:nth-child( " + i + ")").text()
      labelName = label.toLowerCase().replace(" ", "_");
			if (labelName === 'quantity' ) {
				html += '<li><span>'+ label +'</span><input type="number" min="1" name="quantity" value="1"></li>';
			} else if (labelName === 'tax_rate' ) {
        html += '<li><span>'+ label +'</span><input name="'+labelName+'" type="number" min="0" max="100" value="'+defaultTaxRate+'" ></li>';
      } else {
				html += '<li><span>'+ label +'</span><input name="'+labelName+'" value=""></li>';
			}
		}

		html += '</ul>';
		html += '<div style="text-align:center;">';
		html += '<a href="#" class="btn btn-small btn-primary saveBookingInfo">save</a>';
        html += '</div>';
		setPopUpContent(html);
		openPopUp();
		setTimeout(function() {	calcManualBooking();}, 10);


		$("#editBookingUL li:first-child").append('<select>'+$("#availabeTickets").html()+'</select>');

		$("#editBookingUL li:nth-child(3)").html('<span>Coupon</span><select>'+$("#availabeCoupons").html()+'</select>');
		$("#editBookingUL li:first-child select option:first-child").attr("selected","selected");
		$("#editBookingUL li:nth-child(11) input").datepicker({ dateFormat: "DD, d MM, yy"});
    $("#editBookingUL li:nth-child(11) input").datepicker("setDate", new Date());


		$('#editBookingUL li:first-child select, #editBookingUL li:nth-child(3) select, #editBookingUL li input').change(calcManualBooking);
	}

	function calcManualBooking() {
		var cost = parseFloat($('#editBookingUL li:first-child select option:selected').attr('data-cost'))
		var quantity = parseInt($('#editBookingUL li input[name="quantity"]').val());

    var taxRate = parseFloat($('#editBookingUL li input[name="tax_rate"]').val());
    if (isNaN(taxRate)) {
      taxRate = 0;
    }

		var couponType = $('#editBookingUL li:nth-child(3) select option:selected').attr('data-type');
		var couponAmount = $('#editBookingUL li:nth-child(3) select option:selected').attr('data-amount');
		var couponAmountFormatted = parseFloat(couponAmount)
	  var total = quantity * cost;

	  var newPrice;
	  if (couponType === 'single') {
      if (couponAmount.indexOf("%") > -1)
        newPrice = cost - couponAmountFormatted * cost/100;
      else {
        newPrice = cost - couponAmountFormatted;
      }

      total = quantity * newPrice;
    } else if (couponType === 'total') {
      if (couponAmount.indexOf("%") > -1)
        total = total -  (total * couponAmountFormatted)/100;
      else{
        total = total - couponAmountFormatted;
      }
    }

    // fix floating point
    total = Math.round(total * 1000) / 1000
    // amount taxed
    var totalTaxed = total + total * taxRate / 100;
    totalTaxed = Math.round(totalTaxed * 1000) / 1000

	  $('#editBookingUL li input[name="amount"]').val(total)
    $('#editBookingUL li input[name="amount_taxed"]').val(totalTaxed)
	}

	//editing booking
	function editBooking($which){

		var id = $which.parent().parent().find("td:first").html();
		$lastBookingClicked = $which.parent().parent();

		var html = '<h3>Edit Booking</h3>';
		html += '<ul id="editBookingUL" data-id="'+id+'">';
		html += '<li><span>Tickets</span></li>';

		var label;
		for (var i = 3; i < $(".bookings table thead th").length - 2; i++){
			label = $(".bookings table thead th:nth-child(" + i + ")").text();
      labelName = label.toLowerCase().replace(" ", "_");
      if (labelName === 'quantity' ) {
				html += '<li><span>'+ label + '</span><input type="number" min="1" name="' + labelName + '" value="' + $lastBookingClicked.find("td:nth-child(" + i + ")").text()+'"></li>';
			} else if (labelName === 'amount' || labelName === 'amount_taxed') {
       	html += '<li><span>'+ label +'</span><input type="number" min="0" name="' + labelName + '" value="' + $lastBookingClicked.find("td:nth-child(" + i + ")").text().replace(/[^0-9\.]+/g, '')+'"></li>';
			} else if (labelName === 'tax_rate') {
        var rate = $lastBookingClicked.find("td:nth-child(" + i + ")").text();

        html += '<li><span>'+ label +'</span><input type="number" min="0" max="100" name="' + labelName + '" value="' + rate +'"></li>';
      } else {
				html += '<li><span>'+label+'</span><input name="' + labelName + '" value="'+$lastBookingClicked.find("td:nth-child(" + i + ")").text()+'"></li>';
			}
		}

		html += '</ul>';
		html += '<div style="text-align:center;">';
		html += '<a href="#" class="btn btn-small btn-primary saveBookingInfo">save</a>';
    html += '</div>';
		setPopUpContent(html);
		openPopUp();

		var ticketName = $lastBookingClicked.find("td:nth-child(2)").text();
		var couponName =  $lastBookingClicked.find("td:nth-child(4)").text();
		$("#editBookingUL li:first-child").append('<select>'+$("#availabeTickets").html()+'</select>');
		$("#editBookingUL li:nth-child(3)").html('<span>Coupon</span><select>'+$("#availabeCoupons").html()+'</select>');
		$("#editBookingUL li:first-child select option:first-child").attr("selected", "selected");


		$('#editBookingUL li:first-child select, #editBookingUL li:nth-child(3) select, #editBookingUL li input').change(calcManualBooking);

		$("#editBookingUL li:nth-child(3) select option").each(function(index, element) {
      if ($(this).text() == couponName)
				$(this).attr("selected","selected");
    });

		$("#editBookingUL li:first-child select option").each(function(index, element) {
      if ($(this).text() == ticketName)
				$(this).attr("selected"," selected");
    });

		var date_str_s = $("#editBookingUL li:nth-child(11) input").val().split("-");
		var date_s = new Date(date_str_s[0],date_str_s[1]-1,date_str_s[2]);
		$("#editBookingUL li:nth-child(11) input").datepicker({ dateFormat: "DD, d MM, yy"});
		$("#editBookingUL li:nth-child(11) input").datepicker("setDate", date_s);
	}

	$('.ebp-content  .saveBookingInfo').live("click", function(e){
		e.preventDefault();
		var $modal = $('#popUpBox');

		$('#loader').slideDown(100);
		var id = $("#editBookingUL").attr("data-id");

		var data = "";

		data += $("#event-id").val() + "|";
		data += $(".bookings input[name='data_id']").val() + "|";

		data += $modal.find('ul#editBookingUL li:nth-child(1) select').val() + "|";
		for(var i = 2; i <= $modal.find('ul#editBookingUL li').length; i++) {
			if (i == 3) {
					data += $modal.find('ul#editBookingUL li:nth-child(3) select').val() + "|";
			} else if (i == 11) {
        var newDate = $modal.find('ul#editBookingUL li:nth-child(' + i +') input').datepicker('getDate');
        var newDateStr = newDate.getFullYear() + "-" + (newDate.getMonth() + 1)+"-"+newDate.getDate();
        data += newDateStr+"|";
			} else
				data += $modal.find('ul#editBookingUL li:nth-child(' + i +') input').val() + "|";
		}

		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=save_booking&id=' + id+'&info='+data,
			success: function(response) {
				$('#loader').slideUp(100);
					$modal.removeClass('ebp-show' );
				$(document).removeClass( 'ebp-perspective' );
				getBookingTable();
			}
		});
	});

	function valdiateBookingTables() {
		if ($(".bookings table tbody tr").length>0) {
			$(".bookings").slideDown(0);
			$("#bookings .noRecords").slideUp(0);
		} else {
			$(".bookings").slideUp(0);
			$("#bookings .noRecords").slideDown(0);
		}

	}

	function setPopUpContent(content) {
		var $modal = $('#popUpBox');
		$modal.find(".ebp-content>div").html(content);
	}

	function openPopUp() {

		var $modal = $('#popUpBox');

		$modal.addClass('ebp-show');

		setTimeout(function() {
	    $('.ebp-content .offlineloader').hide();
          $(document).addClass( 'ebp-perspective');
      }, 25 );

		$(document).on('click', 'a.ebp-close, .ebp-overlay', function(e){
			$modal.removeClass('ebp-show');
			$(document).removeClass( 'ebp-perspective' );
		 });

	}

	function formatTime(time) {

		var timeParts = time.split(" ");
		var hourMin = timeParts[0].split(":");
		var hour = parseInt(hourMin[0]);
		var min = parseInt(hourMin[1]);

		if (timeParts[1] && timeParts[1].toLowerCase() == "pm" && hour != 12) {
			hour += 12;
    }

		if (timeParts[1] && timeParts[1].toLowerCase() == "am" && hour == 12) {
			hour = 0;
    }

		if (hour == 24) {
			hour = 0;
    }

    if (hour < 10) {
      hour = "0" + hour
    }

    if (min < 10) {
      min = "0" + min
    }

    if (hour === 0) {
      hour = "00";
    }

    if (min === 0) {
      min = "00";
    }
		return  hour + ":" + min + ":" + "00";
	}

	function saveEvent() {
    var id = $('#event-id').val();

		var occur = [];

		var startDate, endDate, timeStart, timeEnd;
		$('.Ebp--Occurrences--Occurrence').each(function(index, element) {
		  startDate = $(this).find('.start input[name="date"]').datepicker('getDate');
		  endDate = $(this).find('.end input[name="date"]').datepicker('getDate');

		  timeStart = formatTime($(this).find('.start input[name="time"]').val());
		  timeEnd = formatTime($(this).find('.end input[name="time"]').val());

		  bookingStarOn = $(this).find('.bookingStart input[name="toggler"]').is(':checked') ? 'true' : 'false'
		  bookingEndOn = $(this).find('.bookingEnd input[name="toggler"]').is(':checked') ? 'true' : 'false'

		  bookingStartDate = $(this).find('.bookingStart input[name="date"]').datepicker('getDate');
		  bookingEndDate = $(this).find('.bookingEnd input[name="date"]').datepicker('getDate');

		  bookingStartTime = formatTime($(this).find('.bookingStart input[name="time"]').val());
		  bookingEndTime = formatTime($(this).find('.bookingEnd input[name="time"]').val());

      occur.push({
        id: $(this).find('input[name="occurid"]').val(),
        start_date: startDate.getFullYear() + "-" + (startDate.getMonth() + 1) + "-" + startDate.getDate(),
        start_time: timeStart,
        end_date: endDate.getFullYear() + "-" + (endDate.getMonth() + 1) + "-" + endDate.getDate(),
        end_time: timeEnd,

        bookingDirectly: bookingStarOn,
        startBooking_date: bookingStartDate.getFullYear() + "-" + (bookingStartDate.getMonth() + 1) + "-" + bookingStartDate.getDate(),
        startBooking_time: bookingStartTime,
        bookingEndsWithEvent: bookingEndOn,
        endBooking_date: bookingEndDate.getFullYear() + "-" + (bookingEndDate.getMonth() + 1) + "-" + bookingEndDate.getDate(),
        endBooking_time: bookingEndTime
      });
		});

    if (occur.length < 1) {
      alert("Please add at least one occurrence.");
      return;
    }

    var tickets = [];
   	$('.Ebp--Tickets--Ticket').each(function(index, element) {

      var breakdown = [];
      $(this).find('.Ebp--Tickets--SubTickets--subTicket').each(function () {
        breakdown.push({
          cost: $(this).find('input[name="cost"]').val(),
          name: $(this).find('input[name="name"]').val()
        })
      });

      tickets.push({
        id: $(this).find('input[name="ticketid"]').val(),
        cost: $(this).find('input[name="cost"]').val(),
        name: $(this).find('input[name="ticketname"]').val(),
        allowed: $(this).find('input[name="allowed"]').val(),
        breakdown: breakdown
      });
		});

    if (tickets.length < 1) {
      alert("Please add at least one ticket.");
      return;
    }

    // collect email rules
    var hasEmailRules = $('#eventForm .emailRules').length > 0;
    var emailRulesData = [];

    if (hasEmailRules) {
      $('#eventForm .emailRules .ebp_email_rule').each(function () {
        var isBefore = ($(this).find('input[type="radio"]:checked').val() == 'before') ? 'true' : 'false';
        var activated = ($(this).find('input[name="activated"]').is(':checked')) ? 'true' : 'false';

        emailRulesData.push({
          id:  $(this).attr('data-id'),
          template: $(this).find('select').val(),
          hours: $(this).find('input[name="hours"]').val(),
          isBefore: isBefore,
          activated: activated
        });

      });
    }

   	var form = $('#eventForm #formID').val();
		var emailTemplateID = $('#eventForm #emailTemplateID').val();
		var maxSpots = $('#eventForm #maxSpots').val();

		var infoText = (tinyMCE && tinyMCE.activeEditor) ? encodeURIComponent(tinyMCE.activeEditor.getContent()) : '';

		var gatewayData = '';

		$('.gateway').each(function(index, element) {
      gatewayData += $(this).attr('data-name') + '=';
   	  gatewayData += $(this).bootstrapSwitch('status') ? 'true' : 'false';
      gatewayData += '%';
    });

		if (gatewayData.length > 1 && gatewayData.charAt(gatewayData.length-1) === "%") {
			gatewayData = gatewayData.substring(0, gatewayData.length - 1);
    }

		var ownerEmail = 'default';
		var hasOwnerEmail = $("#ownerEmailToggle").bootstrapSwitch('status');

    if (hasOwnerEmail) {
			ownerEmail = $('#eventForm input[name="ownerEmail"]').val();

      if (!validateEmail(ownerEmail)) {
				alert('Alternate email is not valid.');
				return;
			}
		}

    $('#loader').slideDown(100);
    var saveEventFormData = 'action=ebp_event_save&event-id=' + id
      + '&name='+ encodeURIComponent($('#eventForm #name').val())
      + '&info=' + infoText
      + '&image=' + $('#eventForm #image').val().split('__and__')[0]
      + '&mapAddressType=' + encodeURIComponent($('#eventForm #mapAddressType').val())
      + '&mapAddress=' + encodeURIComponent($('#eventForm input[name="mapAddress"]').val())
      + '&mapZoom=' + encodeURIComponent($('#eventForm input[name="mapZoom"]').val())
      + '&mapType=' + encodeURIComponent($('#eventForm #mapType').val())
      + '&address=' + encodeURIComponent($('#eventForm input[name="address"]').val())
      + '&paypal=' + $("#paypal").bootstrapSwitch('status')
      + '&offlineBooking=' + $("#offlineBooking").bootstrapSwitch('status')
      + '&showSpots=' + $("#showSpots").bootstrapSwitch('status')
      + '&showPrice=' + $("#showPrice").bootstrapSwitch('status')
      + '&occurrences=' + JSON.stringify(occur)
      + '&tickets=' + encodeURIComponent(JSON.stringify(tickets))
      + '&form=' + form
      + '&maxSpots=' + maxSpots
      + '&gateways=' + gatewayData
      + '&background=' + $('#eventForm #background').val()
      + '&ownerEmail=' + ownerEmail
      + '&emailTemplateID=' + emailTemplateID;

      if (hasEmailRules) {
        saveEventFormData += '&emailRulesData=' + JSON.stringify(emailRulesData);
      }

		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: saveEventFormData,

			error: function(response) {
				console.error(response);
				alert('An error occurred while saving. Check console for details.');
				$('#loader').slideUp(100);
			},
			success: function(response) {
				$("#event_"+id).find("span").html($('#eventForm #name').val());
				$('#loader').slideUp(100);
			}
		});
	}

	function saveEventCoupons() {
    $('#loader').slideDown(100);
    var id = $('#event-id').val();
    var data = "";
    $('#eventCoupons a.coupon.toggle').each(function(index, element) {
      if ($(this).attr("data-id") % 500 == 0) {
        sendEventCouponData(id, data, false);
        data = "";
      }

      data += '&couponid-'+$(this).attr("data-id")+'='+$(this).attr("data-id")+'&selected-'+$(this).attr("data-id")+'='+$(this).hasClass("notselected");
    });

    sendEventCouponData(id, data, true);
	}

	function sendEventCouponData(id, data,lastOne) {
		$.ajax({
      type:'POST',
      url: 'admin-ajax.php',
      data: 'action=ebp_event_coupon_update&id=' + id + "&data=" + data,
      error: function(error) {
        console.error(error);
        alert('check console for error');
      },

      success: function(response) {
          if (lastOne) $('#loader').slideUp(100);
      }
    });
	}


	function deleteCoupon() {
		$('#loader').slideDown(100);
		var id = $('#couponForm input[name="couponid"]').val();

		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_coupon_delete&id=' + id,

      error: function(error) {
        $('#loader').slideUp(100);

        console.error(error);
        alert('check console for error');
      },

      success: function(response) {
				$('#loader').slideUp(100);
				$(".coupons").find("[data-id='" + id + "']").remove();
				$(".EBP--CouponsDetails").empty();
			}
		});
	}

	function saveCoupon() {
		$('#loader').slideDown(100);
		var id = $('#couponForm input[name="couponid"]').val();

		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_coupon_save'
      +'&id=' + id
			+'&name=' + $('#couponForm input[name="name"]').val()
			+'&amount=' + $('#couponForm input[name="amount"]').val()
			+'&code=' + $('#couponForm input[name="code"]').val()
      +'&type=' + $('#couponForm select[name="type"]').val()
      +'&maxAllowed=' + $('#couponForm input[name="maxAllowed"]').val()
			+'&isActive=' +$("#coupon-active").bootstrapSwitch('status') ,

      error: function(error) {
        $('#loader').slideUp(100);

        console.error(error);
        alert('check console for error');
      },
			success: function(response) {
				$('#loader').slideUp(100);
        var json = $.parseJSON(response);

        if (json.error != null) {
          if (json.error === "codeError") {
            $('#couponForm .alert').html("Code already taken!");
            $('#couponForm .alert').slideDown(100);
          } else {
            alert('Error while saving coupon');
          }

          return;
        }


				$('#couponForm .alert').slideUp(100);
				$('a.newCoupun').attr("data-id", json.maxId);

				var $btn = $(".coupons").find("[data-id='" + json.id + "']");
				$btn.text(json.html);

        if (!$("#coupon-active").bootstrapSwitch('status') && !$btn.hasClass("deactive") ) {
					$btn.addClass("deactive");
        } else if ($btn.hasClass("deactive") && $("#coupon-active").bootstrapSwitch('status')) {
					$btn.removeClass("deactive");
        }
			}
		});
	}

	function saveSettingsData() {
		$('#loader').slideDown(100);
		var id = $("#setting-id").val();

    // fix images
    var $cppLogo = $("#settingsForm").find('input[name="cpp_logo_image"]');
    var $cppHeader = $("#settingsForm").find('input[name="cpp_header_image"]');

    if ($cppHeader.length && $cppHeader.val().indexOf('__and__') > 0) {
      $cppHeader.val($cppHeader.val().split('__and__')[0]);
    }
    if ($cppLogo.length && $cppLogo.val().indexOf('__and__') > 0) {
      $cppLogo.val($cppLogo.val().split('__and__')[0]);
    }

    $.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_save_settings&id=' + id + "&" + $("#settingsForm").serialize(),
			success: function(response) {
				$('#loader').slideUp(100);
        updateAdminAppSettings();
			}
		});
	}

	function getSettingsPage(id, data) {

		var html = '<div class="settingsPage"><div id="changeSettings" class="settingsBtns">';
		var isBtnActive;

		isBtnActive = (id == 1) ? 'active' : '';
		html += '<a href="#" class="btn btn-auto ' + isBtnActive + '" data-id="1">Event Box</a>';

		isBtnActive = (id == 8) ? 'active' : '';
		html += '<a href="#" class="btn btn-auto ' + isBtnActive + '" data-id="8">Event Card</a>';

		isBtnActive = (id == 2) ? 'active' : '';
		html += '<a href="#" class="btn btn-auto ' + isBtnActive + '" data-id="2">Calendar</a>';

		isBtnActive = (id == 9) ? 'active' : '';
		html += '<a href="#" class="btn btn-auto ' + isBtnActive + '" data-id="9">Events List</a>';

		isBtnActive = (id == 4) ? 'active' : '';
		html += '<a href="#" class="btn btn-auto ' + isBtnActive + ' " data-id="4">Booking Form</a>';

		isBtnActive = (id == 3) ? 'active' : '';
		html += '<a href="#" class="btn btn-auto ' + isBtnActive + '" data-id="3">Price - Tax - Coupons - Booking</a>';

		isBtnActive = (id == 7) ? 'active' : '';
		html += '<a href="#" class="btn btn-auto ' + isBtnActive + '" data-id="7">PayPal</a>';

		isBtnActive = (id == 5) ? 'active' : '';
		html += '<a href="#" class="btn btn-auto ' + isBtnActive + '" data-id="5">Email</a>';

    if (data.hasEmailRules) {
      isBtnActive = (id == 11) ? 'active' : '';;
      html += '<a href="#" class="btn btn-auto addon-btn ' + isBtnActive + '" data-id="11">Email Rules</a>'
    }
		if (data.hasDayListCalendar) {
			isBtnActive = (id == 6) ? 'active' : '';
			html += '<a href="#" class="btn btn-auto ' + isBtnActive + '" data-id="6">Day List Addon</a>'
		}

    isBtnActive = (id == 12) ? 'active' : '';;
    html += '<a href="#" class="btn btn-auto ' + isBtnActive + '" data-id="12">Maps</a>'

    isBtnActive = (id == 10) ? 'active' : '';
    html += '<a href="#" class="btn btn-auto ' + isBtnActive + '" data-id="10">Utils</a>';


		  html += '<input type="hidden" id="setting-id" value="' + id + '"/></div>';
			html += '<form id="settingsForm" name="settingsForm">';

				html += '<div class="optionsCnt">';

        if (id == 12) {
          html += doOption({name: "googleMapsEnabled", value: data.googleMapsEnabled, type: "toggle",
            title: 'Enable Google maps'});

          html += doOption({name: "googleMapsLoadLib", value: data.googleMapsLoadLib, type: "toggle",
            title: 'Load Google maps lib',  info: 'Turn this off if you want to force the plugin not to load google maps js library in the frontend'});


          html += doOption({name: "googleMapsAPIKey", value: data.googleMapsAPIKey, type: "input",
            title: 'API key', after: '<em>Optional. <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">Get key</a></em>', info: 'Add this if you can not see google maps on your website.'});

        } else if (id == 11) {
          html += doOption({type: "toggle", name: "emailRulesEnabled", value: data.emailRulesEnabled,
            title:'',before: '<p class="settingTitle">Enable email rules</p>', after: '<p>If you do not plan to have email rules then keep this off. Better overall performance. <br/><br/>When this is turned off all due rules are skipped.</p><div class="sep"></div>'});

          html += doOption({name: "emailBookingCanceled", value: data.emailBookingCanceled, type: "toggle",
            title: '', before: '<p class="settingTitle">Send email when booking is canceled/deleted</p>'});


          var emailTemplates = data.emailTemplates;

          html += doOption({type: "html",
            title: 'Email Template', html: generateSelectField("emailBookingCanceledTemplate", emailTemplates, data.emailBookingCanceledTemplate), after: '<div class="sep"></div>'});

          html += doOption({name: "emailOccurenceDeleted", value: data.emailOccurenceDeleted, type: "toggle",
            title: '', before: '<p class="settingTitle">Send email when occurrence is deleted</p>'});

          html += doOption({type: "html",
            title: 'Email Template', html: generateSelectField("emailOccurenceCanceledTemplate", emailTemplates, data.emailOccurenceCanceledTemplate), after: '<div class="sep"></div>'});

          html += doOption({name: "emailEventCanceled", value: data.emailEventCanceled, type: "toggle",
            title: '', before: '<p class="settingTitle">Send email when event is canceled</p>'});

          html += doOption({type: "html",
            title: 'Email Template', html: generateSelectField("emailEventCanceledTemplate", emailTemplates, data.emailEventCanceledTemplate), after: '<div class="sep"></div>'});


          html += doOption({name: "emailRule_afterForCameOnly", value: data.emailRule_afterForCameOnly, type: "toggle",
            title: '', before: '<p class="settingTitle">"After email rules" apply only for checked-in</p><div style="margin-bottom:10px">Only activate this if you are using the <strong>Check-In addon</strong> and you wish to send "after" emails only to those that came (where marked as checked in).</div>', after: '<div class="sep"></div>'});

          html += doOption({name: "emailRule_validStatus", value: data.emailRule_validStatus, type: "input-large",
            title: '', before: '<p class="settingTitle">Valid statuses</p><div style="margin-bottom:10px">Email rules only apply to bookings with the following statuses:</div>', after: '<div style="margin-top:10px">Add new ones only if you are editing booking statuses manually or if you are using custom payment gateways. <em>Use lower case only!</em><br/><br/>Note: <em class="tag">Paid</em> and <em class="tag">Not paid</em> are for offline booking.<br/>Paypal payments are first marked as <em class="tag">pending</em>, when a change happens paypal notifies the plugin with the new status (<em class="tag">completed</em>, <em class="tag">canceled</em>, <em class="tag">refunded</em>...)</div><div class="sep"></div>'});

        } else if (id == 9) {

					html += '<h3 id="">Filters</h3>';

					html += doOption({name: 'eventsListFilterLable', value: data.eventsListFilterLable, type: 'input', title: 'Text before filters:'});

					html += doOption({name: "eventsListFilterColor", value: data.eventsListFilterColor, defaultValue: "#1abc9c",
						type: "color", title: "Button color:"});

					html += doOption({name: "eventsListFilterTextColor", value: data.eventsListFilterTextColor, defaultValue: "#1abc9c",
						type: "color", title: "Button text color:"});

					html += doOption({name: "eventsListFilterBorderRadius", value: data.eventsListFilterBorderRadius,
									type: "number", min: 0, title: "Button border radius:", after: "px",
									info: "Radius of the border (0) for perfect square."});

					html += doOption({name: "eventsListFilterFontSize", value: data.eventsListFilterFontSize,
						type: "number", min: 0,
						title: "Font size:", after:"px"});

					html += doOption({name: "eventsListFilterPaddingSides", value: data.eventsListFilterPaddingSides,
						type: "number", min: 0,
						title: "Side padding:", after:"px"});

					html += doOption({name: "eventsListFilterPaddingVertical", value: data.eventsListFilterPaddingVertical,
						type: "number", min: 0,
						title: "Vertical padding:", after:"px"});


				} else if (id == 6) {

					html += doOption({name: "dayCal_bgColor", value: data.dayCal_bgColor, defaultValue: "#F4F4F4",
									type: "color", title: "Nav Background color:"});


					html += doOption({name: "dayCal_mainColor", value: data.dayCal_mainColor, defaultValue: "#2ECC71",
									type: "color", title: "Main color:"});

					html += doOption({name: "dayCal_subColor", value: data.dayCal_subColor, defaultValue: "#CCCCCC",
									type: "color", title: "Secondary color:"});

					html += doOption({name: "dayCal_monthColor", value: data.dayCal_monthColor, defaultValue: "#495468",
									type: "color", title: "Month color:"});

					html += doOption({name: "dayCal_daysColor", value: data.dayCal_daysColor, defaultValue: "#919191",
									type: "color", title: "Days color:"});


					html += doOption({name: "dayCal_bordersOff", value: data.dayCal_bordersOff, type: "toggle", title: "List Navigator Borders:"});


					html += doOption({name: "dayCal_borderColor", value: data.dayCal_borderColor, defaultValue: "#dddddd",
									type: "color", title: "Nav Border color:"});

				}

				else if (id == 5) {
					html += '<h3 id="emailSettings">Email Configuration</h3>';

					html += '<p style="font-style:italic;"><a  href="http://iplusstd.com/item/eventBookingPro/example/email-configuration-help/" target="_blank">Popular configurations and steps to follow when having problems.</a></p>';

					html += doOption({name: 'email_mode', value: data.email_mode, type: 'select', title: 'EMAIL MODE:',
						values: ['3', '1', '4'], options: ['SMTP', 'MAIL', 'WP Mail']});

           html += doOption({name: "email_utf8", value: data.email_utf8, type: "toggle",
            title: "UTF-8:", info: "Use UTF-8 as the email's encoding"});
					html += doOption({name: 'SMTP_EMAIL', value: data.SMTP_EMAIL, type: 'input', title: 'Email Address:'});

					html += '<div style="padding: 1px 20px 20px; margin-bottom:20px; background:#F1F1F1;"><h4>Not needed in case of "WP MAIL" mode:</h4>';

					html += doOption({name: 'SMTP_PASS', value: data.SMTP_PASS, type: 'password', title: 'Email Password:'});

					html += doOption({name: 'SMTP_HOST', value: data.SMTP_HOST, type: 'input', title: 'SMPT HOST:'});

					html += doOption({name: 'SMTP_PORT', value: data.SMTP_PORT, type: 'number', title: 'SMPT PORT:'});

					html += doOption({name: 'emailSSL', value: data.emailSSL, type: 'select', title: 'Encryption:',
						values: ['false','ssl','tls'], options: ['None','SSL','TLS']});

					html += '</div>';

					html += '<a href="#" class="testEmail btn btn-primary">Send Test Email</a>';
					html += '<p id="testEmailDiv"></p>';

					html += '<h3 id="emailSettings">Email Settings:</h3>';

					html += doOption({name: "SMTP_NAME", value: data.SMTP_NAME, type: "input", title: "Sender name: "});

					html += doOption({name: "emailSubject", value: data.emailSubject, type: "input-large", title: "Email Subject:",
						after: '<div style="margin-left:20px;">You can now include keywords in subject.</div>' + getListOfEmailTemplates()});


					html += doOption({name: "sendEmailToCustomer", value: data.sendEmailToCustomer, type: "toggle",
						title: "Customer email:", info: "When enabled the customer will receive a confirmation email (customize below)"});

					html += doOption({name: "sendEmailToAdmin", value: data.sendEmailToAdmin, type: "toggle",
						title: "Admin email:", info: "When enabled the admin will receive an email (customize below)"});


					html += '<h3 id="emailTemplateDiv">on Success: Customer Email Template</h3>';

					html += '<textarea class="emailTemplate" id="emailTemplate" name="emailTemplate" >'+data.emailTemplate.replace(/\\/g, '')+'</textarea>';
					html += '<a href="#" class="loadDefaultTemplate">Load Default Template</a>';
					html += '<p><strong>You can use the keywords in your email templates.</strong> They will be replaced with their corresponding values.</ul>';

					html += getListOfEmailTemplates();


					html += '<h3 id="OwnerEmailTemplateDiv">on Success: Admin Email Template</h3>';

					html += '<textarea class="emailTemplate" id="ownerEmailTemplate" name="ownerEmailTemplate" >'+data.ownerEmailTemplate.replace(/\\/g, '')+'</textarea>';
					html += '<a href="#" class="loadDefaultOwnerTemplate">Load Default Template</a>';
					html += '<p><strong>You can use the keywords in your email templates.</strong> They will be replaced with their corresponding values.</ul>';

					html += getListOfEmailTemplates();



					html += '<h3 id="refundemailTemplateDiv">on Refund: Buyer Email Template</h3>';

					html += '<textarea class="emailTemplate" id="refundEmailTemplate" name="refundEmailTemplate" >'+data.refundEmailTemplate.replace(/\\/g, '')+'</textarea>';
					html += '<p><strong>You can use the keywords in your email templates.</strong> They will be replaced with their corresponding values</ul>';

					html += getListOfEmailTemplates();


					html += '<h3 id="refundOwneremailTemplateDiv">on Refund: Admin Email Template</h3>';

					html += '<textarea class="emailTemplate" id="refundOwnerEmailTemplate" name="refundOwnerEmailTemplate" >'+data.refundOwnerEmailTemplate.replace(/\\/g, '')+'</textarea>';
					html += '<p><strong>You can use the keywords in your email templates.</strong> They will be replaced with their corresponding values</ul>';

					html += getListOfEmailTemplates();


				} else if (id == 3) {


					html += '<h3 id="priceSettings">Price Settings</h3>';


					html += '<div class="item">';
					html += '<span class="label" >Currency:</span><select id="currency" name="currency">';

                    var currency=(data.currency === "EUR")?'selected="selected"':'';
					html += '<option value="EUR" ' + currency + '>Euro</option>';

                    currency=(data.currency === "USD")?'selected="selected"':'';
					html += '<option value="USD" ' + currency + '>U.S. Dollar</option>';

                    currency=(data.currency === "THB")?'selected="selected"':'';
					html += '<option value="THB" ' + currency + '>Thai Baht</option>';

                    currency=(data.currency === "TWD")?'selected="selected"':'';
					html += '<option value="TWD" ' + currency + '>Taiwan New Dollar</option>';

                    currency=(data.currency === "CHF")?'selected="selected"':'';
					html += '<option value="CHF" ' + currency + '>Swiss Franc</option>';

                    currency=(data.currency === "SEK")?'selected="selected"':'';
					html += '<option value="SEK" ' + currency + '>Swedish Krona</option>';

                    currency=(data.currency === "SGD")?'selected="selected"':'';
					html += '<option value="SGD" ' + currency + '>Singapore Dollar</option>';

                    currency=(data.currency === "GBP")?'selected="selected"':'';
					html += '<option value="GBP" ' + currency + '>Pound Sterling</option>';

                    currency=(data.currency === "PLN")?'selected="selected"':'';
					html += '<option value="PLN" ' + currency + '>Polish Zloty</option>';

                    currency=(data.currency === "PHP")?'selected="selected"':'';
					html += '<option value="PHP" ' + currency + '>Philippine Peso</option>';

                    currency=(data.currency === "NZD")?'selected="selected"':'';
					html += '<option value="NZD" ' + currency + '>New Zealand Dollar</option>';

                    currency=(data.currency === "NOK")?'selected="selected"':'';
					html += '<option value="NOK" ' + currency + '>Norwegian Krone</option>';

                    currency=(data.currency === "MXN")?'selected="selected"':'';
					html += '<option value="MXN" ' + currency + '>Mexican Peso</option>';

                    currency=(data.currency === "JPY")?'selected="selected"':'';
					html += '<option value="JPY" ' + currency + '>Japanese Yen</option>';

                    currency=(data.currency === "ILS")?'selected="selected"':'';
					html += '<option value="ILS" ' + currency + '>Israeli New Sheqel</option>';

                    currency=(data.currency === "HUF")?'selected="selected"':'';
					html += '<option value="HUF" ' + currency + '>Hungarian Forint</option>';

                    currency=(data.currency === "HKD")?'selected="selected"':'';
					html += '<option value="HKD" ' + currency + '>Hong Kong Dollar</option>';

                    currency=(data.currency === "DKK")?'selected="selected"':'';
					html += '<option value="DKK" ' + currency + '>Danish Krone</option>';

                    currency=(data.currency === "CZK")?'selected="selected"':'';
					html += '<option value="CZK" ' + currency + '>Czech Koruna</option>';

                    currency=(data.currency === "CAD")?'selected="selected"':'';
					html += '<option value="CAD" ' + currency + '>Canadian Dollar</option>';

                    currency=(data.currency === "AUD")?'selected="selected"':'';
					html += '<option value="AUD" ' + currency + '>Australian Dollar</option>';

					currency=(data.currency === "RUB")?'selected="selected"':'';
					html += '<option value="RUB" ' + currency + '>Russia Ruble</option>';

					currency=(data.currency === "TLR")?'selected="selected"':'';
					html += '<option value="TLR" ' + currency + '>Turkish Lira (TLR)</option>';

					currency=(data.currency === "TRY")?'selected="selected"':'';
					html += '<option value="TRY" ' + currency + '>Turkish Lira (TRY)</option>';

					currency=(data.currency === "MYR")?'selected="selected"':'';
					html += '<option value="MYR" ' + currency + '>Malaysia Ringgit</option>';

					currency=(data.currency === "BRL")?'selected="selected"':'';
					html += '<option value="BRL" ' + currency + '>Brazilian Real</option>';

					currency=(data.currency === "LEM")?'selected="selected"':'';
					html += '<option value="LEM" ' + currency + '>Lempira</option>';

					currency=(data.currency === "IND")?'selected="selected"':'';
					html += '<option value="IND" ' + currency + '>Indonesian (Rp)</option>';

					currency=(data.currency === "LTL")?'selected="selected"':'';
					html += '<option value="LTL" ' + currency + '>Lithuanian Litas</option>';

					currency=(data.currency === "INR")?'selected="selected"':'';
					html += '<option value="INR" ' + currency + '>Indian Rupee</option>';

					currency=(data.currency === "ZAR")?'selected="selected"':'';
					html += '<option value="ZAR" ' + currency + '>South African</option>';

					currency=(data.currency === "VEF")?'selected="selected"':'';
					html += '<option value="VEF" ' + currency + '>Venezuelan Bolívar</option>';

					currency=(data.currency === "KRW")?'selected="selected"':'';
					html += '<option value="KRW" ' + currency + '>Korea Won</option>';

					currency=(data.currency === "VND")?'selected="selected"':'';
					html += '<option value="VND" ' + currency + '>Vietnam</option>';

					currency=(data.currency === "GHC")?'selected="selected"':'';
					html += '<option value="GHC" ' + currency + '>Ghana Cedis</option>';

					currency=(data.currency === "RWF")?'selected="selected"':'';
					html += '<option value="RWF" ' + currency + '>Rwandan Franc</option>';

          currency=(data.currency === "CRC")?'selected="selected"':'';
          html += '<option value="CRC" ' + currency + '>Costa Rican Colon</option>';

					html += '</select>';
					html += '</div>';


					html += doOption({name: "currencyBefore", value: data.currencyBefore, type: "toggle", title: "Currency Before Price:"});

					html += doOption({name: "priceDecimalCount", value: data.priceDecimalCount, type: "number", min: 0, max:5, title: "Decimals:", info: "Number of decimal numbers."});

					html += doOption({name: "priceDecPoint", value: data.priceDecPoint, type: "input", maxlength:"1", title: "Decimal point:"});

					html += doOption({name: "priceThousandsSep", value: data.priceThousandsSep, type: "input", maxlength:"1", title: "Thousand Seperator: "});

          html += '<h3 id="taxSettings">Tax</h3>';
          html += doOption({name: "tax_rate", value: data.tax_rate, type: "number", min: 0, max: 100, title: "Tax rate:"});
          html += doOption({name: "showTaxInBookingForm", value: data.showTaxInBookingForm, type: "toggle", title: "Show tax column in booking form:"});

          html += '<h3 id="couponsSettings">Coupons</h3>';
          html += doOption({name: 'coupon_expired_msg', value: data.coupon_expired_msg, type: 'input', title: 'Coupon expired text:'});
          html += doOption({name: 'coupon_not_found_msg', value: data.coupon_not_found_msg, type: 'input', title: 'Coupon not found text:'});
          html += doOption({name: 'coupon_msg', value: data.coupon_msg, type: 'input', title: 'Coupon found text:', 'info': 'Supports %name% and %amount% to. Example: %name$- Discount of %amount%'});


					html += '<h3 id="bookingSettings">Booking Settings</h3>';

          html += getToggling({title: 'Count only successful bookings:',
                    value: data.spotsLeftStrict,
                    name: "spotsLeftStrict",

                    items: [
                        doOption({name: "statusesCountedAsCompleted", value: data.statusesCountedAsCompleted,
                            type: "input", title: "statuses counted as completed:",
                            info: "Separate by comma, for offline payments you have 'not paid' and 'paid'. Default: paid, not paid, ok, completed, successful, success"})
                        ]});



					html += doOption({name: "couponsEnabled", value: data.couponsEnabled,
										type: "toggle", title: "Enable Coupons:"});


					html += doOption({name: "multipleBookings", value: data.multipleBookings,
										type: "toggle", title: "Multiple Booking:"});

					html += getToggling({title: 'Limit bookings per email (quantity-wise):',
										value: data.limitBookingPerEmail,
										name: "limitBookingPerEmail",
										info: "This will limit quantity allowed",
										items: [
												doOption({name: "limitBookingPerEmailCount", value: data.limitBookingPerEmailCount,
														type: "number", title: "Limit:"})
												]});


					html += getToggling({title: 'Limit bookings per booking (booking-wise):',
										value: data.limitBookingPerTime,
										name: "limitBookingPerTime",
										info: "This means that the user can only book X times disregarding quantity.",

										items: [
												doOption({name: "limitBookingPerTimeCount", value: data.limitBookingPerTimeCount,
														type: "number", title: "Limit:"})
												]});


					html += getToggling({title: 'Return to same page after paypal payment',
										value: data.return_same_page,
										name: "return_same_page",
										inverseToggle: "true",
										items: [
												doOption({name: "return_page_url", value: data.return_page_url,
														type: "input", title: "Page URL:"})
												]});


          html += doOption({name: "eventCancelledTxt", value: data.eventCancelledTxt, type: "input", title: "Event canceled text:"});

				} else if (id == 7) {

					html += '<h3 id="paymentSettings">PayPal Settings</h3>';

					html += doOption({name: "paypalAccount", value: data.paypalAccount, type: "input", title: "PayPal Account:", info: "Your paypal account. A standard account is needed."});


					html += doOption({name: "sandbox", value: data.sandbox,
										type: "toggle", title: "Payment Sandbox:"});

					html += doOption({name: "force_ssl_v3", value: data.force_ssl_v3,
										type: "toggle", title: "SSL v3:",
										info :'Recommended On. Turn off in case your server doest support cURL SSL v3'});

					html += '<div class="item">';



					html += '<span class="upload"><span class="label">Header Image (750x90):</span>';
						html += '<input type="hidden" class="regular-text text-upload" name="cpp_header_image" value="'+data.cpp_header_image+'"/>';
						html += '<a href="#" class="btn btn-primary button-upload">Add/Change</a>';
						html += '<a href="#" class="removeImg" style="margin-left:10px;">x</a>';
						html += '<img  src="'+data.cpp_header_image+'" class="preview-upload"/>';
					html += "</span></div>";


					html += doOption({name: "cpp_headerback_color", value: data.cpp_headerback_color, defaultValue: "#FFF", type: "color", title: "Header bg color:"});

					html += doOption({name: "cpp_headerborder_color", value: data.cpp_headerborder_color, defaultValue: "#EEE", type: "color", title: "Header border color:"});


					html += '<div class="item">';
					html += '<span class="upload"><span class="label">Logo (190x60):</span>';
						html += '<input type="hidden" class="regular-text text-upload" name="cpp_logo_image" value="'+data.cpp_logo_image+'"/>';
						html += '<a href="#" class="btn btn-primary button-upload">Add/Change</a>';
						html += '<a href="#" class="removeImg" style="margin-left:10px;">x</a>';
						html += '<img  src="'+data.cpp_logo_image+'" class="preview-upload"/>';

					html += "</span></div>";

					html += doOption({name: "cpp_payflow_color", value: data.cpp_payflow_color, defaultValue: "#FFF", type: "color", title: "Body bg color:"})


				} else if (id == 4 ) {

					html += '<h3 id="modalGeneralSettings">General Settings</h3>';


          html += doOption({name: "mobileSeperatePage", value: data.mobileSeperatePage,
                    type: "toggle", title: "Open booking form as a separate page on mobile:"});

					html += doOption({name: "modalMainColor", value: data.modalMainColor, defaultValue: "#2ECC71", type: "color", title: "Content background Color:", info: "This is the color of the pop up box."});

					html += doOption({name: "modalOverlayColor", value: data.modalOverlayColor, defaultValue: "#2ECC71", type: "color", title: "Overlay Color:", info: "This is the color that will surround the popup  box."});

					html += doOption({name: "popupOverlayAlpha", value: data.popupOverlayAlpha, type: "number", min: 0, max:100, title: "Overlay opacity:", after:"(0-100)", info: "This is the opacity of color that will surround the popup  box."});

					html += doOption({name: "requirePhone", value: data.requirePhone,
										type: "toggle", title: "Require Phone Number:"});

					html += doOption({name: "requireAddress", value: data.requireAddress,
										type: "toggle", title: "Require Address:"});

					html += doOption({name: "modal_includeTime", value: data.modal_includeTime,
										type: "toggle", title: "Include time in booking page:"});

					html += doOption({name: "ticketsOrder", value: data.ticketsOrder, type: "select",
									title: "Ticket list order:",  values: ["1", "2", "3", "4", "5"],
									options: ["Default Order", "Ticket Name Ascending", "Ticket Name Descending", "Price Ascending", "Price Descending"]});


          html += doOption({name: "bookingFormTicketCntShowPrice", value: data.bookingFormTicketCntShowPrice,
                    type: "toggle", title: "Show price beside ticket name:"});

					html += '<h3 id="successSettings">Success (After booking) Settings</h3>';

					html += doOption({name: "doAfterSuccess", value: data.doAfterSuccess, type: "select",
									title: "Action:",
									values: ["popup","close","redirect","msg"],
									options: ["Open success popup","Close booking popup","Redirect to page","Show message below booking button"]});


					html += doOption({name: "doAfterSuccessRedirectURL", value: data.doAfterSuccessRedirectURL,
									type: "input", title: "Redirect Page URL:"});

					html += doOption({name: "doAfterSuccessTitle", value: data.doAfterSuccessTitle,
									type: "input", title: "Success Popup title: "});

					html += doOption({name: "doAfterSuccessMessage", value: data.doAfterSuccessMessage,
									type: "textarea", title: "Success Popup Message:"});

					html += doOption({name: "eventBookedTxt", value: data.eventBookedTxt, type: "input",
									title: "Message to show below button"});

					html += doOption({name: "bookingTxt", value: data.bookingTxt, type: "input",
									title: "Booking loader text:"});


					html += '<h3 id="textsSettings">Text Settings</h3>';

					html += doOption({name: "modalSpotsLeftTxt", value: data.modalSpotsLeftTxt, type: "input",
									title: "Spots Left text:"});

					html += doOption({name: "modalQuantityTxt", value: data.modalQuantityTxt, type: "input",
									title: "Quantity text:"});

					html += doOption({name: "modalSingleCostTxt", value: data.modalSingleCostTxt, type: "input",
									title: "Singe price text:"});

					html += doOption({name: "modalTotalCostTxt", value: data.modalTotalCostTxt, type: "input",
									title: "Total price text:"});

					html += doOption({name: "couponTxt", value: data.couponTxt, type: "input",
									title: "Coupon placeholder text:"});

					html += doOption({name: "applyTxt", value: data.applyTxt, type: "input",
									title: "Coupon Submit Button:"});

					html += '<br/>';

					html += doOption({name: "modalNameTxt", value: data.modalNameTxt, type: "input", title: "Initial Name Text:"});

					html += doOption({name: "modalEmailTxt", value: data.modalEmailTxt, type: "input", title: "Initial Email Text:"});
					html += doOption({name: "modalPhoneTxt", value: data.modalPhoneTxt, type: "input", title: "Initial Phone Text:"});

					html += doOption({name: "modalAddressTxt", value: data.modalAddressTxt, type: "input", title: "Initial Address Text:"});


					html += doOption({name: "bookingLimitText", value: data.bookingLimitText, type: "input",
						title: "Limit Error message (per quantity):",
						info: "Use %left% to include the quantity of tickets left."});

					html += doOption({name: "bookingLimitTimeText", value: data.bookingLimitTimeText, type: "input",
						title: "Limit Error message (per booking):",
						info: "Use %left% to include the number of bookings left."});

          html += doOption({name: "duplicateOnQuantityText", value: data.duplicateOnQuantityText, type: "input",
            title: "Duplicate fields title:",
            info: "Use %x% to display ticket number. Use %name% to display name of sub-ticket. use %name_group% for complex expressions. Ex: '%name_group% - (%name%)%name_group%' will display ' - (name)' when possible and will remove it all when not."});


					html += '<h3 id="modalTitleSettings">Title Settings</h3>';


					html += doOption({name: "modal_titleSize", value: data.modal_titleSize,
						type: "number", min: 0,
						title: "Title font size:", after:"px"});

					html += doOption({name: "modal_titleLineHeight", value: data.modal_titleLineHeight,
						type: "number", min: 0,
						title: "Title line height:", after:"px"});


					html += doOption({name: "modal_titleFontType", value: data.modal_titleFontType,
						type: "select", title: "Title font style:",
						values: ["normal","italic","bold","100","300","500","700"],
						options: ["Normal","Italic","Bold","100","300","500","700"]});

					html += doOption({name: "modal_titleMarginBottom", value: data.modal_titleMarginBottom,
						type: "number", min: 0,
						title: "Title Margin Bottom:"});




					html += '<h3 id="modalMainSettings">Content Settings</h3>';

					html += doOption({name: "modal_txtColor", value: data.modal_txtColor, defaultValue: "#FFF",
						type: "color", title: "text color:"});



					html += '<h3 id="modalInputs">Inputs Settings</h3>';


					html += doOption({name: "modal_input_txtColor", value: data.modal_input_txtColor, defaultValue: "#000", type: "color", title: "Text color:"});

					html += doOption({name: "modal_inputHover_txtColor", value: data.modal_inputHover_txtColor, defaultValue: "#333", type: "color", title: "Hover Text color:"});

					html += doOption({name: "modal_input_bgColor", value: data.modal_input_bgColor, defaultValue: "#FFF", type: "color", title: "Input background color:"});


					html += doOption({name: "modal_inputHover_bgColorHover", value: data.modal_inputHover_bgColorHover, defaultValue: "#FFF", type: "color", title: "Hover Input background color:"});


					html += doOption({name: "modal_input_bgColorAlpha", value: data.modal_input_bgColorAlpha, type: "number", min: 0, max: 100, title: "Input background Opacity:", after: "(0-100)"});

					html += doOption({name: "modal_inputHover_bgColorAlpha", value: data.modal_inputHover_bgColorAlpha, type: "number", min: 0, max: 100, title: "Hover Input background Opacity:", after: "(0-100)"});



					html += doOption({name: "modal_input_fontSize", value: data.modal_input_fontSize, type: "number", min: 0, title: "Font size:", after: "px"});

					html += doOption({name: "modal_input_lineHeight", value: data.modal_input_lineHeight, type: "number", min: 0, title: "Line height:", after: "px"});

					html += doOption({name: "modal_input_topPadding", value: data.modal_input_topPadding, type: "number", min: 0, title: "Top/Bottom Padding:", after: "px"});

					html += doOption({name: "modal_input_space", value: data.modal_input_space, type: "number", min: 0, title: "Space between:", after: "px"});



					html += '<h3 id="selectSettings">Dropdown (Select) Settings</h3>';


          html += getToggling({title: 'Label as first option:',
                    value: data.modal_selectLabelAsNoneOption,
                    name: "modal_selectLabelAsNoneOption",
                    inverseToggle: "yes",
                    items: [
                      doOption({name: "modal_selectNoneOption", value: data.modal_selectNoneOption,
                        type: "input", title: "Text for first option:"})
                    ]});


					html += doOption({name: "modal_selectHoverColor", value: data.modal_selectHoverColor, defaultValue: "#208F4F", type: "color", title: "Select Background Hover Color:"});

					html += doOption({name: "modal_selectTxtHoverColor", value: data.modal_selectTxtHoverColor, defaultValue: "#FFF", type: "color", title: "Select Text Hover Color:"});


					html += '<h3 id="checkSettings">Checkbox/Radio Settings</h3>';


					html += doOption({name: "checkBoxTextColor", value: data.checkBoxTextColor, defaultValue: "#EEE",
									type: "color", title: "Text Color:"});

					html += doOption({name: "checkBoxColor", value: data.checkBoxColor, defaultValue: "#111",
									type: "color", title: "Tick/Dot Color:"});

					html += doOption({name: "checkBoxMarginTop", value: data.checkBoxMarginTop,
									type: "number", min: 0, title: "Margin Top:", after: "px"});

					html += doOption({name: "checkBoxMarginBottom", value: data.checkBoxMarginBottom,
									type: "number", min: 0, title: "Margin Bottom:", after: "px"});



					html += '<h3 id="modalSettings">Button  Settings</h3>';

					html += doOption({name: "modalBookText", value: data.modalBookText, type: "input",
									title: "Offline Booking Button:",
									info: "Text of the offline booking  buttin shown in the popup."});

					html += doOption({name: "paypalBtnTxt", value: data.paypalBtnTxt, type: "input",
									title: "PayPal Booking Button:",info: "Text of the paypal button shown in the popup."});


					html += doOption({name: "modal_btnTxtColor", value: data.modal_btnTxtColor, defaultValue: "#FFF",
									type: "color", title: "Button text color:"});

					html += doOption({name: "modal_btnFontSize", value: data.modal_btnFontSize,
									type: "number", min: 0, title: "Button font size:", after: "px"});

					html += doOption({name: "modal_btnLineHeight", value: data.modal_btnLineHeight,
									type: "number", min: 0, title: "Button line size:", after: "px"});


					html += doOption({name: "modal_btnFontType", value: data.modal_btnFontType, type: "select",
									title: "Button font style:",  values: ["normal","italic","bold","100","300","500","700"],
									options: ["Normal","Italic","Bold","100","300","500","700"]});

					html += doOption({name: "modal_btnTopPadding", value: data.modal_btnTopPadding,
									type: "number", min: 0, title: "Button top/bottom padding:", after: "px"});

					html += doOption({name: "modal_btnSidePadding", value: data.modal_btnSidePadding,
									type: "number", min: 0, title: "Button side padding:", after: "px",
									info: "Inside space left between button text and button borders"});


					html += doOption({name: "modal_btnMarginTop", value: data.modal_btnMarginTop,
									type: "number", min: 0, title: "Button top margin:", after: "px"});


					html += doOption({name: "modal_btnBorderRadius", value: data.modal_btnBorderRadius,
									type: "number", min :0, title: "Button side padding:", after: "px",
									info: "Radius of the border (0) for perfect square."});



				html += '<h3 id="modalDateSettings">Date Settings <small>Popup when you press more dates</small></h3>';

				html += '<h4>Section Title (Passed and Upcoming Text) formatting</h4>';


					html += doOption({name: "modal_dateTitleColor", value: data.modal_dateTitleColor, defaultValue: "#999",
										type: "color", title: "Color:"});

					html += doOption({name: "modal_dateTitleFontSize", value: data.modal_dateTitleFontSize,
										type: "number", min: 0, title: "Font size:", after: "px"});

					html += doOption({name: "modal_dateTitleFontLineHeight", value: data.modal_dateTitleFontLineHeight,
										type: "number", min: 0, title: "Line height:", after: "px"});


					html += doOption({name: "modal_dateTitleTextAlign", value: data.modal_dateTitleTextAlign,
									type: "select", title: "Text alignment:",
									values: ["left","center","right"],
									options: ["Left","Center","Right"]});



					html += doOption({name: "modal_dateTitleFontStyle", value: data.modal_dateTitleFontStyle,
									type: "select", title: "font style:",
									values: ["normal","italic","bold","100","300","500","700"],
									options: ["Normal","Italic","Bold","100","300","500","700"]});


					html += doOption({name: "modal_dateTitlePaddingSides", value: data.modal_dateTitlePaddingSides,
										type: "number", min: 0, title: "Padding sides:", after: "px"});

					html += doOption({name: "modal_dateTitleMarginBottom", value: data.modal_dateTitleMarginBottom,
										type: "number", min: 0, title: "Margin Bottom:", after: "px"});




					html += '<h4>Date and Time Formatting</h4>';


					html += doOption({name: "moreDateSectionMarginBottom", value: data.moreDateSectionMarginBottom,
										type: "number", min: 0, title: "Space between date occurences:", after: "px"});


					// html += doOption({name: "modal_dateColor", value: data.modal_dateColor, defaultValue: "#999",
									// type: "color", title: "Date Color:",
									// info: "Color of the modal_date and time"});


					html += doOption({name: "modal_dateFontSize", value: data.modal_dateFontSize,
										type: "number", min: 0, title: "Date font size:", after: "px"});


					html += doOption({name: "modal_dateTextAlign", value: data.modal_dateTextAlign,
								type: "select", title: "Text alignment:",
								values: ["left","center","right"],
								options: ["Left","Center","Right"]});

					html += doOption({name: "modal_dateFontStyle", value: data.modal_dateFontStyle,
								type: "select", title: "Title font style:",
								values: ["normal","italic","bold","100","300","500","700"],
								options: ["Normal","Italic","Bold","100","300","500","700"]});

					// html += doOption({name: "modal_dateLableColor", value: data.modal_dateLableColor, defaultValue: "#666",
									// type: "color", title: "Button text color:",
									// info:'Color of labels such as "starts on" and "ends on"'});



					html += doOption({name: "modal_dateLableSize", value: data.modal_dateLableSize,
										type: "number", min: 0, title: "Date Label Font size:", after: "px"});

					html += doOption({name: "modal_dateLabelLineHeight", value: data.modal_dateLabelLineHeight,
										type: "number", min: 0, title: "Date Label Line height:", after: "px"});


					html += doOption({name: "modal_dateLabelStyle", value: data.modal_dateLabelStyle,
								type: "select", title: "Date Label font style:",
								values: ["normal","italic","bold","100","300","500","700"],
								options: ["Normal","Italic","Bold","100","300","500","700"]});




					html += doOption({name: "modal_datePaddingSides", value: data.modal_datePaddingSides,
										type: "number", min: 0, title: "Date padding sides:", after: "px"});

					html += doOption({name: "modal_datePaddingTop", value: data.modal_datePaddingTop,
										type: "number", min: 0, title: "Date padding top:", after: "px"});

					html += doOption({name: "modal_datePaddingBottom", value: data.modal_datePaddingBottom,
										type: "number", min: 0, title: "Date padding bottom:", after: "px"});

					html += doOption({name: "modal_dateMarginTop", value: data.modal_dateMarginTop,
										type: "number", min: 0, title: "Date margin top:", after: "px"});

					html += doOption({name: "modal_dateMarginBottom", value: data.modal_dateMarginBottom,
										type: "number", min: 0, title: "Date margin bottom:", after: "px"});

				} else if (id == 10) {

          html += '<h3>Mobile Booking Page Fix</h3>';
          html += '<a class="btn btn-primary fixMobilePage" href="#">Press this ONCE if you the mobile booking page is not working!</a>';

					html += '<h3>Clean event occurrences (Fix)</h3>';
					html += "<p>This will remove all occurrences that shouldn't be there.</p>";
					html += '<a class="btn btn-primary fixOccurences" href="#">Press this ONCE to clean your event occurrences!</a>';


          html += '<h3>Database encoding</h3>';
          html += '<p>Change database EBP tables to UTF-8. Supports all international characters.</p>';
          html += '<a href="#" class="btn btn-primary setCollation">Set collation/encoding to UTF-8</a>';


				} else if (id == 8) {
					html += '<h3 id="textS">Texts Settings</h3>';

					html += doOption({name: "btnTxt", value: data.btnTxt, type: "input", title: "Booking Page Button:",
									info: "Text of the button that will open the booking page(popup/modal)"});

					html += doOption({name: "eventDescriptionTitle", value: data.eventDescriptionTitle, type: "input",
									title: "Event Information title: "});

					html += doOption({name: "bookedTxt", value: data.bookedTxt, type: "input",
									title: "All booked text:",
									info: "Text shown when the event is fully sold out/booked."});


					html += doOption({name: "modalBookText", value: data.modalBookText, type: "input",
									title: "Offline Booking Button:",
									info: "Text of the offline booking  buttin shown in the popup."});

					html += doOption({name: "paypalBtnTxt", value: data.paypalBtnTxt, type: "input",
									title: "PayPal Booking Button:",
									info: "Text of the paypal button shown in the popup."});

					html += doOption({name: "bookedTxt", value: data.bookedTxt, type: "input",
									title: "All booked text:",
									info: "Text shown when the event is fully sold out/booked."});


					html += doOption({name: "passedTxt", value: data.passedTxt, type: "input",
									title: "Passed Event text:",
									info: "Text shown when the event passes."});



					html += doOption({name: "bookingStartsTxts", value: data.bookingStartsTxts, type: "input",
									title: '"Booking havent started" text:',
									info: "Use %date% to add the starting date and %time% to add the starting time."});

					html += doOption({name: "bookingEndedTxt", value: data.bookingEndedTxt, type: "input",
									title: '"Booking ended" text:'
								});


					html += doOption({name: "statsOnTxt", value: data.statsOnTxt, type: "input",
									title: "Starts On text:"});

					html += doOption({name: "endsOnTxt", value: data.endsOnTxt, type: "input",
									title: "Ends On text:"});

					html += doOption({name: "closeTextTxt", value: data.closeTextTxt, type: "input",
									title: "Unexpand text:"});

					html += doOption({name: "ExpandTextTxt", value: data.ExpandTextTxt, type: "input",
									title: "Expand text:"});

					html += doOption({name: "freeTxt", value: data.freeTxt, type: "input",
									title: "Text to show when price is zero:"});

					html += doOption({name: "spotsLeftTxt", value: data.spotsLeftTxt, type: "input",
									title: "Spots Left text:"});


					html += '<h3 id="general2">Event Card Settings</h3>';

					html += doOption({name: "boxWidth", value: data.boxWidth,
									type: "number", min: 0, title: "Event Card Width:", after: "px"});


					html += getToggling({title: 'Center Event Box:',
										value: data.boxAlign,
										name: "boxAlign",
										inverseToggle: "yes",
										items: [
												doOption({name: "boxMarginSides", value: data.boxMarginSides,
														type: "number", min: 0, title: "Card side margin:", after: "px"})
												]});

				 	html += doOption({name: "boxMarginTop", value: data.boxMarginTop,
									type: "number", min: 0, title: "Card top margin:", after: "px"});

					html += doOption({name: "boxMarginBottom", value: data.boxMarginBottom,
									type: "number", min: 0, title: "Card bottom margin:", after: "px"});


					html += doOption({name: "boxPaddingSides", value: data.boxPaddingSides,
									type: "number", min: 0, title: "Card  side padding:", after: "px"});

					html += doOption({name: "boxPaddingTop", value: data.boxPaddingTop,
									type: "number", min: 0, title: "Card top padding::", after: "px"});

					html += doOption({name: "boxPaddingBottom", value: data.boxPaddingBottom,
									type: "number", min: 0, title: "Card bottom padding::", after: "px"});



				html += getToggling({title: 'Box border:', value:(data.boxBorder=="0")?"hide":"show",
									items: [
											doOption({name: "boxBorder", value: data.boxBorder,
													itemClass:"isBorder",
													type: "number", min: 0, title: "border size:", after: "px"}),

											doOption({name: "boxBorderColor", value: data.boxBorderColor,
													 defaultValue: "#F2F2F2", type: "color",
													 title: "border color:"})
											]});




				html += doOption({name: "boxBorderRadius", value: data.boxBorderRadius,
									type: "number", min: 0, title: "Box Border Radius:", after: "px",
									info: "Radius of the border (0) for perfect square."});

        html += '<h3 id="bgSettings">Background Settings</h3>';

        html += doOption({name: "eventCardImageAsBackground", value: data.eventCardImageAsBackground,
                  type: "toggle", title: 'EventCard Image background image:'});

        html += doOption({name: "boxBgColor", value: data.boxBgColor, defaultValue: "#f9f9f9",
                  type: "color", title: "Card background color:"});


        html += doOption({name: "cardDescriptionBackColor", value: data.cardDescriptionBackColor, defaultValue: "#F1f1f1",
                  type: "color", title: "Card Expandable Details background Color:"});


        html += '<h3 id="titleSettings">Title Settings</h3>';

        html += doOption({name: "titleColor", value: data.titleColor, defaultValue: "#495468",
                  type: "color", title: "Title color:"});

          html += doOption({name: "titleFontSize", value: data.titleFontSize,
                    type: "number", min: 0, title: "Title font size:", after: "px"});

        html += doOption({name: "titleMarginBottom", value: data.titleMarginBottom,
                  type: "number", min: 0, title: "Title margin bottom:", after: "px"});

          html += doOption({name: "titleTextAlign", value: data.titleTextAlign,
                type: "select", title: "Tite text alignment:",
                values: ["left","center","right"],
                options: ["Left","Center","Right"]});

          html += doOption({name: "titleFontStyle", value: data.titleFontStyle,
                type: "select", title: "Title font style:",
                values: ["normal","italic","bold","100","300","500","700"],
                options: ["Normal","Italic","Bold","100","300","500","700"]});


				html += '<h3 id="imgSettings">Thumbnail Settings</h3>';

				html += doOption({name: "eventCardShowThumbnail", value: data.eventCardShowThumbnail,
									type: "toggle", title: 'Show thumbnail:'});

				html += doOption({name: "eventCardThumbnailWidth", value: data.eventCardThumbnailWidth,
						type: "input-mini", title: 'Thumbnail width (no expand):',
						after: 'px or %', info: 'percentage example: 20%, width example: 50'});

				html += doOption({name: "eventCardExpandThumbnailWidth", value: data.eventCardExpandThumbnailWidth,
						type: "input-mini", title: 'Thumbnail width (can expand):',
						after: 'px or %', info: 'percentage example: 20%, width example: 50'});



				html += doOption({name: "eventCardShowImage", value: data.eventCardShowImage,
									type: "toggle", title: 'Show full image (expand mode):'});
        html +=  getToggling({title: 'Crop Image:',
					value: data.imageCrop,
					name: "imageCrop",
					items: [
							doOption({name: "imageHeight", value: data.imageHeight,
									type: "number", min: 0, title: "Image maximum Height:", after: "px"})
							]})	;

				html += doOption({name: "imageMarginSides", value: data.imageMarginSides,
										type: "number", min: 0, title: "Image side margin:", after: "px"});

				html += doOption({name: "imageMarginTop", value: data.imageMarginTop,
										type: "number", min: 0, title: "Image top margin:", after: "px"});

				html += doOption({name: "imageMarginBottom", value: data.imageMarginBottom,
										type: "number", min: 0, title: "Image bottom margin:", after: "px"});


				html += '<h3 id="mapSettings">Map Settings</h3>';

				html += doOption({name: "mapHeight", value: data.mapHeight,
										type: "number", min: 0, title: "Map height:", after: "px"});


				html += '<h3 id="descSettings">Description Settings</h3>';


					html += doOption({name: "infoNoButton", value: data.infoNoButton,
										type: "toggle", title: 'Show Read More button:',
										info: "If actiavted then the description will have a read more button and only text equal to the  maximum height set below will be displayed.If deactivated then the description will be displayed all."});


				html += doOption({name: "infoMaxHeight", value: data.infoMaxHeight,
									type: "number", min: 0, title: "Maximum info height:", after: "px"});

				html += doOption({name: "infoExpandText", value: data.infoExpandText, type: "input",
								title: "Expand text:"});


				html += doOption({name: "infoTitleColor", value: data.infoTitleColor, defaultValue: "#111",
									type: "color", title: "Info Title Color:"});

				html += doOption({name: "infoTitleFontSize", value: data.infoTitleFontSize,
									type: "number", min: 0, title: "Info Title Font Size:", after: "px"});


				html += doOption({name: "infoColor", value: data.infoColor, defaultValue: "#111",
									type: "color", title: "Info Color:"});


				html += doOption({name: "infoFontSize", value: data.infoFontSize,
									type: "number", min: 0, title: "Info font size:", after: "px"});

				html += doOption({name: "infoLineHeight", value: data.infoLineHeight,
									type: "number", min: 0, title: "Info line height:", after: "px"});


				html += doOption({name: "infoTextAlign", value: data.infoTextAlign,
							type: "select", title: "Info text alignment:",
							values: ["left","center","right"],
							options: ["Left","Center","Right"]});

				html += doOption({name: "infoFontStyle", value: data.infoFontStyle,
							type: "select", title: "Info font style:",
							values: ["normal","italic","bold","100","300","500","700"],
							options: ["Normal","Italic","Bold","100","300","500","700"]});


				html += doOption({name: "infoPaddingSides", value: data.infoPaddingSides,
								type: "number", min: 0, title: "Info side spacing:", after: "px"});

				html += doOption({name: "infoPaddingTop", value: data.infoPaddingTop,
								type: "number", min: 0, title: "Info top spacing:", after: "px"});

				html += doOption({name: "infoPaddingBottom", value: data.infoPaddingBottom,
								type: "number", min: 0, title: "Info bottom spacing:", after: "px"});

				// html += doOption({name: "infoMarginTop", value: data.infoMarginTop,
				// 				type: "number", min: 0, title: "Info margin top:", after: "px"});

				// html += doOption({name: "infoMarginBottom", value: data.infoMarginBottom,
				// 				type: "number", min: 0, title: "Info margin bottom:", after: "px"});



				html += getToggling({title: 'Info bottom border:',
								value:(data.infoBorderSize=="0")?"hide":"show",
								items: [
										doOption({name: "infoBorderSize", value: data.infoBorderSize,
												itemClass:"isBorder",
												type: "number", min: 0, title: "border size:", after: "px"}),

										doOption({name: "infoBorderColor", value: data.infoBorderColor,
												 defaultValue: "#eee", type: "color",
												 title: "border color:"})
										]});


				html += '<h3 id="dateSettings">Date Settings</h3>';



				html += doOption({name: "dateFormat", value: data.dateFormat,
								type: "select", title: "Date Format:",
								values: DATE_FORMAT.getFormats,
								options: DATE_FORMAT.getLabels
							});



				html += doOption({name: "timeFormat", value: data.timeFormat,
								type: "select", title: "Time format:",
								values: ["g:i a","g:i A","H:i"],
								options: ["5:30 pm","5:30 PM","17:30"]});




				html += doOption({name: "includeEndsOn", value: data.includeEndsOn,
										type: "toggle", title: 'Include "Ends On" date:',
										info: "Adds the time and date the event ends in the event box."});



				html += '<div class="alert alert-info">The <strong>date & time</strong> that is displayed by default is the nearest upcoming occurrence of the event. If all dates passed then the last occurrence will be displayed.<br/> You can list all dates, only upcoming dates or passed dates in a modal box. Use the settings below to configure that.</div>';
				html += '<h4>"More Dates" Settings <small>For events that reoccur.</small></h4>';



				html += doOption({name: "moreDateOn", value: data.moreDateOn,
										type: "toggle", title: 'Enable More Dates:',
										info: "When pressed a modal will appear with previous and upcoming events."});


				html += doOption({name: "moreDatePassed", value: data.moreDatePassed,
										type: "toggle", title: 'List Passed Dates:',
										info: "Passed Events will be listed in the modal when the more dates button is pressed"});


				html += doOption({name: "moreDateUpcoming", value: data.moreDateUpcoming,
										type: "toggle", title: 'List Upcoming Dates:',
										info: "Upcoming Events will be listed in the modal when the more dates button is pressed"});




				html += doOption({name: "moreDateTxt", value: data.moreDateTxt, type: "input",
								title: '"More Date" text:'});

				html += doOption({name: "moreDateTextAlign", value: data.moreDateTextAlign,
								type: "select", title: "More dates link alignment:",
								values: ["left","center","right"],
								options: ["Left","Center","Right"]});


				html += doOption({name: "moreDateMarginTop", value: data.moreDateMarginTop,
									type: "number", min: 0, title: '"More Date" margin top:', after: "px"});


				html += doOption({name: "moreDateColor", value: data.moreDateColor, defaultValue: "#c4c4c4",
									type: "color", title: '"More Date" margin color:'});


				html += doOption({name: "moreDateHoverColor", value: data.moreDateHoverColor, defaultValue: "#a3a3a3",
									type: "color", title: '"More Date" margin hover color:'});


				html += doOption({name: "moreDateSize", value: data.moreDateSize,
										type: "number", min: 0, title: '"More dates" link font size', after: "px"});



				html += doOption({name: "moreDateFontStyle", value: data.moreDateFontStyle,
							type: "select", title: "Title font style:",
							values: ["normal","italic","bold","100","300","500","700"],
							options: ["Normal","Italic","Bold","100","300","500","700"]});

				html += doOption({name: "passedOccurencesText", value: data.passedOccurencesText, type: "input",
								title: '"Passed Dates" text:'});

				html += doOption({name: "upcomingOccurencesText", value: data.upcomingOccurencesText, type: "input",
								title: '"Upcomming Dates" text:'});

				html += '<div class="alert alert-info">Customize how the date and time appears in the modal from the modal settings</div>';

				html += '<h4>Date & time formatting</h4>';


				html += doOption({name: "dateColor", value: data.dateColor, defaultValue: "#999",
									type: "color", title: 'Date color:'});

				html += doOption({name: "dateFontSize", value: data.dateFontSize,
										type: "number", min: 0, title: "Date font size:", after: "px"});

				html += doOption({name: "dateTextAlign", value: data.dateTextAlign,
							type: "select", title: "Date text alignment:",
							values: ["left","center","right"],
							options: ["Left","Center","Right"]});

				html += doOption({name: "dateFontStyle", value: data.dateFontStyle,
							type: "select", title: "Date font style:",
							values: ["normal","italic","bold","100","300","500","700"],
							options: ["Normal","Italic","Bold","100","300","500","700"]});




				html += doOption({name: "dateMarginTop", value: data.dateMarginTop,
								type: "number", min: 0, title: "Date margin top:", after: "px"});

				html += doOption({name: "dateMarginBottom", value: data.dateMarginBottom,
								type: "number", min: 0, title: "Date margin bottom:", after: "px"})	;



				html += '<h3 id="LocationSettings">Location Settings</h3>';

				html += doOption({name: "locationColor", value: data.locationColor, defaultValue: "#111",
									type: "color", title: "Color:"});

				html += doOption({name: "locationFontSize", value: data.locationFontSize,
									type: "number", min: 0, title: "Font size:", after: "px"});


				html += doOption({name: "locationTextAlign", value: data.locationTextAlign,
							type: "select", title: "Text alignment:",
							values: ["left","center","right"],
							options: ["Left","Center","Right"]});

				html += doOption({name: "locationFontStyle", value: data.locationFontStyle,
							type: "select", title: "Font style:",
							values: ["normal","italic","bold","100","300","500","700"],
							options: ["Normal","Italic","Bold","100","300","500","700"]});


				html += '<h3 id="detailSettings">Details/tickets Settings</h3>';

					html += doOption({name: "showAllTickets", value: data.showAllTickets,
										type: "toggle", title: 'Show all tickets in eventBox:',
										info: "if set to false, only first ticket will be shown in event box. ALl tickets will still appear in booking popup!"});


					html += doOption({name: "detailsColor", value: data.detailsColor, defaultValue: "#999",
									type: "color", title: "Details color:"});

					html += doOption({name: "detailsFontSize", value: data.detailsFontSize,
										type: "number", min: 0, title: "Details font size:", after: "px"});



					html += doOption({name: "detailsFontStyle", value: data.detailsFontStyle,
								type: "select", title: "Title font style:",
								values: ["normal","italic","bold","100","300","500","700"],
								options: ["Normal","Italic","Bold","100","300","500","700"]});




					html += doOption({name: "detailsLableColor", value: data.detailsLableColor, defaultValue: "#CCC",
									type: "color", title: "Spots Left color:",
									info: "Color of labels such  the text in spots left"});

					html += doOption({name: "detailsLableSize", value: data.detailsLableSize,
										type: "number", min: 0, title: "Spots Left font size:", after: "px"});


					html += doOption({name: "detailsLabelStyle", value: data.detailsLabelStyle,
								type: "select", title: "Spots Left font style:",
								values: ["normal","italic","bold","100","300","500","700"],
								options: ["Normal","Italic","Bold","100","300","500","700"]});



				html += '<h3 id="btnSettings">Button Settings</h3>';


				html += doOption({name: "showPrice", value: data.showPrice,
										type: "toggle", title: 'Show price in button:',
										info: "Adds price to buttons."});



				html += doOption({name: "btnColor", value: data.btnColor, defaultValue: "#fff",
									type: "color", title: "Button text color:"});

				html += doOption({name: "btnBgColor", value: data.btnBgColor, defaultValue: "#2ecc71",
									type: "color", title: "Button background color:"});


				html += doOption({name: "btnFontSize", value: data.btnFontSize,
										type: "number", min: 0, title: "Button font size:", after: "px"});


				html += doOption({name: "btnFontType", value: data.btnFontType,
							type: "select", title: "Button font style:",
							values: ["normal","italic","bold","100","300","500","700"],
							options: ["Normal","Italic","Bold","100","300","500","700"]});



					html += doOption({name: "btnSidePadding", value: data.btnSidePadding,
									type: "number", min: 0, title: "Button padding sides:", after: "px"});

					html += doOption({name: "btnTopPadding", value: data.btnTopPadding,
									type: "number", min: 0, title: "Button padding top/bottom:", after: "px"});


					html += doOption({name: "btnMarginTop", value: data.btnMarginTop,
									type: "number", min: 0, title: "Button margin top:", after: "px"});

					html += doOption({name: "btnMarginBottom", value: data.btnMarginBottom,
									type: "number", min: 0, title: "Button margin bottom:", after: "px"});


					html += doOption({name: "btnBorderRadius", value: data.btnBorderRadius,
									type: "number", min: 0, title: "Button Border Radius:", after: "px",
									info: "Radius of the border (0) for perfect square."});


				} else if (id == 2) {
          html += '<h3 id="general2">General Setting</h3>';

          html += doOption({name: 'cal_startIn', value: data.cal_startIn,
                type: 'select', title: 'Calendar first day:',
                values: ['1', '2', '3', '4', '5', '6', '0'],
                options: ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']});



          html += doOption({name: 'cal_displayWeekAbbr', value: data.cal_displayWeekAbbr,
                    type: 'toggle', title: 'Day abbreviations:', info: 'toggle on to show abbreviations rather than full name.'});

          html += doOption({name: 'cal_displayMonthAbbr', value: data.cal_displayMonthAbbr,
                    type: 'toggle', title: 'Month abbreviations:', info: 'toggle on to show abbreviations rather than full name.'});



          html += doOption({name: 'cal_weeks', value: data.cal_weeks,
                type: 'textarea', title: 'Day names:', info: 'comma separated list. Keep order'});

          html += doOption({name: 'cal_weekabbrs', value: data.cal_weekabbrs,
                type: 'textarea', title: 'Day names abbreviations:', info: 'comma separated list. Keep order'});

          html += doOption({name: 'cal_months', value: data.cal_months,
                type: 'textarea', title: 'Month names:', info: 'comma separated list. Keep order'});

          html += doOption({name: 'cal_monthabbrs', value: data.cal_monthabbrs,
                type: 'textarea', title: 'Month names abbreviations:', info: 'comma separated list. Keep order'});



          html += doOption({name: "cal_width", value: data.cal_width,
                    type: "number", min: 0, title: "Calendar width:", after: "px"});

          html += doOption({name: "cal_height", value: data.cal_height,
                    type: "number", min: 0, title: "Calendar Height:", after: "px"});

          html += doOption({name: "boxMarginTop", value: data.boxMarginTop,
                    type: "number", min: 0, title: "Box top margin:", after: "px"});

          html += doOption({name: "boxMarginBottom", value: data.boxMarginBottom,
                    type: "number", min: 0, title: "Box bottom margin:", after: "px"});


          html += doOption({name: "cal_color", value: data.cal_color, defaultValue: "#2ecc71",
                  type: "color", title: "Calendar main color:"});

          html += doOption({name: "cal_bgColor", value: data.cal_bgColor, defaultValue: "#F6F6F6",
                  type: "color", title: "Calendar bg color:"});

          html += doOption({name: "cal_boxColor", value: data.cal_boxColor, defaultValue: "#FFF",
                  type: "color", title: "Calendar box color:"});

          html += doOption({name: "cal_titleBgColor", value: data.cal_titleBgColor, defaultValue: "#FFF",
                  type: "color", title: "Calendar title bg color:"});


          html += doOption({name: "calTodayColor", value: data.calTodayColor, defaultValue: "#2ecc71",
                  type: "color", title: 'Color of "today" box'});

          html += doOption({name: "calEventDayColor", value: data.calEventDayColor, defaultValue: "#FFF",
                  type: "color", title: "Event Day box color:"});

          html += doOption({name: "calEventDayColorHover", value: data.calEventDayColorHover, defaultValue: "#FFF",
                  type: "color", title: "Event Day box color hover:"});


          html += doOption({name: "cal_dateColor", value: data.cal_dateColor, defaultValue: "#686a6e",
                  type: "color", title: "Calendar date color:"});

          html += doOption({name: "calEventDayDotColor", value: data.calEventDayDotColor, defaultValue: "#ddd",
                  type: "color", title: "Event dot color:"});

          html += doOption({name: "calEventDayDotColorHover", value: data.calEventDayDotColorHover,
                  defaultValue: "#2ecc71",
                  type: "color", title: "Event dot hover color:"});

            html += doOption({name: "calendarImageAsBackground", value: data.calendarImageAsBackground,
                  type: "toggle", title: 'Event Day Image background:'});

          html += doOption({name: "cal_hasBoxShadow", value: data.cal_hasBoxShadow,
            type: "toggle", title: "Box shadow around calendar:"});


          html += getToggling({title: 'Calendar top border:', value:(data.cal_topBorder=="0")?"hide":"show",
                    items: [
                        doOption({name: "cal_topBorder", value: data.cal_topBorder,
                            itemClass:"isBorder",
                            type: "number", min: 0, title: "border size:", after: "px"}),

                        doOption({name: "cal_topBorderColor", value: data.cal_topBorderColor,
                             defaultValue: "#2ecc71", type: "color",
                             title: "border color:"})
                        ]});




          html += getToggling({title: 'Calendar bottom border:', value:(data.cal_bottomBorder=="0")?"hide":"show",
                    items: [
                        doOption({name: "cal_bottomBorder", value: data.cal_bottomBorder,
                            itemClass:"isBorder",
                            type: "number", min: 0, title: "border size:", after: "px"}),

                        doOption({name: "cal_bottomBorderColor", value: data.cal_bottomBorderColor,
                             defaultValue: "#EEE", type: "color",
                             title: "border color:"})
                        ]});


            html += getToggling({title: 'Calendar sides border:',
                    value:(data.cal_sideBorder=="0")?"hide":"show",
                    items: [
                        doOption({name: "cal_sideBorder", value: data.cal_sideBorder,
                            itemClass:"isBorder",
                            type: "number", min: 0, title: "border size:", after: "px"}),

                        doOption({name: "cal_sideBorderColor", value: data.cal_sideBorderColor,
                             defaultValue: "#EEE", type: "color",
                             title: "border color:"})
                        ]});





          html += '<div class="item">';
          html += '<span class="label">Space between calendar border and event content:</span><input id="cal_paddingSides" name="cal_paddingSides" value="'+data.cal_paddingSides+'" class="intTxt" type="number"  /> px';
          html += '</div>';


        } else {

					html += '<h3 id="textS">Texts Settings</h3>';

					html += doOption({name: "btnTxt", value: data.btnTxt, type: "input", title: "Booking Page Button:",
									info: "Text of the button that will open the booking page(popup/modal)"});

					html += doOption({name: "modalBookText", value: data.modalBookText, type: "input",
									title: "Offline Booking Button:",
									info: "Text of the offline booking  buttin shown in the popup."});

					html += doOption({name: "paypalBtnTxt", value: data.paypalBtnTxt, type: "input",
									title: "PayPal Booking Button:",
									info: "Text of the paypal button shown in the popup."});

					html += doOption({name: "bookedTxt", value: data.bookedTxt, type: "input",
									title: "All booked text:",
									info: "Text shown when the event is fully sold out/booked."});


					html += doOption({name: "passedTxt", value: data.passedTxt, type: "input",
									title: "Passed Event text:",
									info: "Text shown when the event passes."});

					html += doOption({name: "bookingStartsTxts", value: data.bookingStartsTxts, type: "input",
									title: '"Booking havent started" text:',
									info: "Use %date% to add the starting date and %time% to add the starting time."});

					html += doOption({name: "bookingEndedTxt", value: data.bookingEndedTxt, type: "input",
									title: '"Booking ended" text:'
								});

					html += doOption({name: "statsOnTxt", value: data.statsOnTxt, type: "input",
									title: "Starts On text:"});

					html += doOption({name: "endsOnTxt", value: data.endsOnTxt, type: "input",
									title: "Ends On text:"});

					html += doOption({name: "closeTextTxt", value: data.closeTextTxt, type: "input",
									title: "Unexpand text:"});

					html += doOption({name: "ExpandTextTxt", value: data.ExpandTextTxt, type: "input",
									title: "Expand text:"});

					html += doOption({name: "freeTxt", value: data.freeTxt, type: "input",
									title: "Text to show when price is zero:"});

					html += doOption({name: "spotsLeftTxt", value: data.spotsLeftTxt, type: "input",
									title: "Spots Left text:"});

					html += doOption({name: "addToCalendarText", value: data.addToCalendarText, type: "input",
									title: "Add to Google Calendar text:"});

					html += doOption({name: "NoEventsInList", value: data.NoEventsInList, type: "input",
									title: "EventsList Shortcode: No events text: "});


				if (id == 1) {

					html += '<h3 id="general2">Event Box Settings</h3>';

					html += doOption({name: "boxWidth", value: data.boxWidth,
									type: "number", min: 0, title: "Event box width:", after: "px"});



					html += getToggling({title: 'Center Event Box:',
										value: data.boxAlign,
										name: "boxAlign",
										inverseToggle: "yes",
										items: [
												doOption({name: "boxMarginSides", value: data.boxMarginSides,
														type: "number", min: 0, title: "Box side margin:", after: "px"})
												]});


				 	html += doOption({name: "boxMarginTop", value: data.boxMarginTop,
									type: "number", min: 0, title: "Box top margin:", after: "px"});

					html += doOption({name: "boxMarginBottom", value: data.boxMarginBottom,
									type: "number", min: 0, title: "Box bottom margin:", after: "px"});


					html += doOption({name: "boxPaddingSides", value: data.boxPaddingSides,
									type: "number", min: 0, title: "Box  side padding:", after: "px"});

					html += doOption({name: "boxPaddingTop", value: data.boxPaddingTop,
									type: "number", min: 0, title: "Box top padding::", after: "px"});

					html += doOption({name: "boxPaddingBottom", value: data.boxPaddingBottom,
									type: "number", min: 0, title: "Box bottom padding::", after: "px"});


					html += doOption({name: "boxBgColor", value: data.boxBgColor, defaultValue: "#FFF",
									type: "color", title: "Box background color:"});


					html += doOption({name: "box_hasBoxShadow", value: data.box_hasBoxShadow,
            type: "toggle", title: "Box shadow around enevtBox:"});

					html += getToggling({title: 'Box border:', value:(data.boxBorder=="0")?"hide":"show",
										items: [
												doOption({name: "boxBorder", value: data.boxBorder,
														itemClass:"isBorder",
														type: "number", min: 0, title: "border size:", after: "px"}),

												doOption({name: "boxBorderColor", value: data.boxBorderColor,
														 defaultValue: "#EEE", type: "color",
														 title: "border color:"})
												]});

				    html += doOption({name: "boxBorderRadius", value: data.boxBorderRadius,
									type: "number", min: 0, title: "Box Border Radius:", after: "px",
									info: "Radius of the border (0) for perfect square."});

				}


				html += '<h3 id="imgSettings">Image Settings<small>  Only applicable if an image was uploaded.</small></h3>';



                    html += getToggling({title: 'Crop Image:',
										value: data.imageCrop,
										name: "imageCrop",

										items: [
												doOption({name: "imageHeight", value: data.imageHeight,
														type: "number", min: 0, title: "Image maximum Height:", after: "px"})
												]})	;




				html += doOption({name: "imageMarginSides", value: data.imageMarginSides,
										type: "number", min: 0, title: "Image side margin:", after: "px"});

				html += doOption({name: "imageMarginTop", value: data.imageMarginTop,
										type: "number", min: 0, title: "Image top margin:", after: "px"});

				html += doOption({name: "imageMarginBottom", value: data.imageMarginBottom,
										type: "number", min: 0, title: "Image bottom margin:", after: "px"});



				html += '<h3 id="mapSettings">Map Settings</h3>';


				html += doOption({name: "mapHeight", value: data.mapHeight,
										type: "number", min: 0, title: "Map height:", after: "px"});

        html += '<h3 id="LocationSettings">Location Settings</h3>';


        html += doOption({name: "eventBoxIncludeAddress", value: data.eventBoxIncludeAddress,
                    type: "toggle", title: "Include address as text above map:"});

        html += doOption({name: "locationColor", value: data.locationColor, defaultValue: "#111",
                  type: "color", title: "Color:"});

        html += doOption({name: "locationFontSize", value: data.locationFontSize,
                  type: "number", min: 0, title: "Font size:", after: "px"});


        html += doOption({name: "locationTextAlign", value: data.locationTextAlign,
              type: "select", title: "Text alignment:",
              values: ["left","center","right"],
              options: ["Left","Center","Right"]});

        html += doOption({name: "locationFontStyle", value: data.locationFontStyle,
              type: "select", title: "Font style:",
              values: ["normal","italic","bold","100","300","500","700"],
              options: ["Normal","Italic","Bold","100","300","500","700"]});

				html += '<h3 id="titleSettings">Title Settings</h3>';


				html += doOption({name: "titleColor", value: data.titleColor, defaultValue: "#111",
									type: "color", title: "Title color:"});

					html += doOption({name: "titleFontSize", value: data.titleFontSize,
										type: "number", min: 0, title: "Title font size:", after: "px"});

					html += doOption({name: "titleLineHeight", value: data.titleLineHeight,
										type: "number", min: 0, title: "Title line height:", after: "px"});

					html += doOption({name: "titleTextAlign", value: data.titleTextAlign,
								type: "select", title: "Tite text alignment:",
								values: ["left","center","right"],
								options: ["Left","Center","Right"]});

					html += doOption({name: "titleFontStyle", value: data.titleFontStyle,
								type: "select", title: "Title font style:",
								values: ["normal","italic","bold","100","300","500","700"],
								options: ["Normal","Italic","Bold","100","300","500","700"]});

					html += doOption({name: "titlePaddingSides", value: data.titlePaddingSides,
									type: "number", min: 0, title: "Title padding sides:", after: "px"});

					html += doOption({name: "titlePaddingTop", value: data.titlePaddingTop,
									type: "number", min: 0, title: "Title padding top:", after: "px"});

					html += doOption({name: "titlePaddingBottom", value: data.titlePaddingBottom,
									type: "number", min: 0, title: "Title padding bottom:", after: "px"});

					html += doOption({name: "titleMarginTop", value: data.titleMarginTop,
									type: "number", min: 0, title: "Title margin top:", after: "px"});

					html += doOption({name: "titleMarginBottom", value: data.titleMarginBottom,
									type: "number", min: 0, title: "Title margin bottom:", after: "px"});



				html += getToggling({title: 'Title bottom border:',
									value:(data.titleBottomBorder=="0")?"hide":"show",
										items: [
												doOption({name: "titleBottomBorder", value: data.titleBottomBorder,
														itemClass:"isBorder",
														type: "number", min: 0, title: "border size:", after: "px"}),

												doOption({name: "titleBottomBorderColor", value: data.titleBottomBorderColor,
														 defaultValue: "#eee", type: "color",
														 title: "border color:"})
												]});


				html += '<h3 id="descSettings">Description Settings</h3>';


					html += doOption({name: "infoNoButton", value: data.infoNoButton,
										type: "toggle", title: 'Show Read More button:',
										info: "If actiavted then the description will have a read more button and only text equal to the  maximum height set below will be displayed.If deactivated then the description will be displayed all."});



				html += doOption({name: "infoMaxHeight", value: data.infoMaxHeight,
									type: "number", min: 0, title: "Maximum info height:", after: "px"});

				html += doOption({name: "infoExpandText", value: data.infoExpandText, type: "input",
								title: "Expand text:"});

				html += doOption({name: "infoColor", value: data.infoColor, defaultValue: "#111",
									type: "color", title: "Info color:"});

				html += doOption({name: "infoFontSize", value: data.infoFontSize,
									type: "number", min: 0, title: "Info font size:", after: "px"});

				html += doOption({name: "infoLineHeight", value: data.infoLineHeight,
									type: "number", min: 0, title: "Info line height:", after: "px"});

				html += doOption({name: "infoTextAlign", value: data.infoTextAlign,
							type: "select", title: "Info text alignment:",
							values: ["left","center","right"],
							options: ["Left","Center","Right"]});

				html += doOption({name: "infoFontStyle", value: data.infoFontStyle,
							type: "select", title: "Info font style:",
							values: ["normal","italic","bold","100","300","500","700"],
							options: ["Normal","Italic","Bold","100","300","500","700"]});



				html += doOption({name: "infoPaddingSides", value: data.infoPaddingSides,
								type: "number", min: 0, title: "Info side spacing ", after: "px"});

				html += doOption({name: "infoPaddingTop", value: data.infoPaddingTop,
								type: "number", min: 0, title: "Info top spacing:", after: "px"});

				html += doOption({name: "infoPaddingBottom", value: data.infoPaddingBottom,
								type: "number", min: 0, title: "Info bottom spacing:", after: "px"});

				// html += doOption({name: "infoMarginTop", value: data.infoMarginTop,
				// 				type: "number", min: 0, title: "Info margin top:", after: "px"});

				// html += doOption({name: "infoMarginBottom", value: data.infoMarginBottom,
				// 				type: "number", min: 0, title: "Info margin bottom:", after: "px"});



				html += getToggling({title: 'Info bottom border:',
								value:(data.infoBorderSize=="0")?"hide":"show",
								items: [
										doOption({name: "infoBorderSize", value: data.infoBorderSize,
												itemClass:"isBorder",
												type: "number", min: 0, title: "border size:", after: "px"}),

										doOption({name: "infoBorderColor", value: data.infoBorderColor,
												 defaultValue: "#eee", type: "color",
												 title: "border color:"})
										]});



				html += '<h3 id="dateSettings">Date Settings</h3>';

				html += doOption({name: "dateFormat", value: data.dateFormat,
								type: "select", title: "Date Format:",
								values: DATE_FORMAT.getFormats,
								options: DATE_FORMAT.getLabels
							});


				html += doOption({name: "timeFormat", value: data.timeFormat,
								type: "select", title: "Time format:",
								values: ["g:i a","g:i A","H:i"],
								options: ["5:30 pm","5:30 PM","17:30"]});



				html += doOption({name: "includeEndsOn", value: data.includeEndsOn,
										type: "toggle", title: 'Include "Ends On" date:',
										info: "Adds the time and date the event ends in the event box."});




				html += '<div class="alert alert-info">The <strong>date & time</strong> that is displayed by default is the nearest upcoming occurrence of the event. If all dates passed then the last occurrence will be displayed.<br/> You can list all dates, only upcoming dates or passed dates in a modal box. Use the settings below to configure that.</div>';
				html += '<h4>"More Dates" Settings <small>For events that reoccur!</small></h4>';



				html += doOption({name: "moreDateOn", value: data.moreDateOn,
										type: "toggle", title: 'Enable More Dates:',
										info: "When pressed a modal will appear with previous and upcoming events."});

			html += doOption({name: "permenantMoreButton", value: data.permenantMoreButton,
										type: "toggle", title: 'Always Show "More Dates" Button:',
										info: "The more dates button will always show even if only 1 date is upcoming."});

				html += doOption({name: "moreDatePassed", value: data.moreDatePassed,
										type: "toggle", title: 'List Passed Dates:',
										info: "Passed Events will be listed in the modal when the more dates button is pressed"});


				html += doOption({name: "moreDateUpcoming", value: data.moreDateUpcoming,
										type: "toggle", title: 'List Upcoming Dates:',
										info: "Upcoming Events will be listed in the modal when the more dates button is pressed"});




				html += doOption({name: "moreDateTxt", value: data.moreDateTxt, type: "input",
								title: '"More Date" text:'});

				html += doOption({name: "moreDateTextAlign", value: data.moreDateTextAlign,
								type: "select", title: "More dates link alignment:",
								values: ["left","center","right"],
								options: ["Left","Center","Right"]});


				html += doOption({name: "moreDateMarginTop", value: data.moreDateMarginTop,
									type: "number", min: 0, title: '"More Date" margin top:', after: "px"});


				html += doOption({name: "moreDateColor", value: data.moreDateColor, defaultValue: "#888",
									type: "color", title: '"More Date" margin color:'});


				html += doOption({name: "moreDateHoverColor", value: data.moreDateHoverColor, defaultValue: "#999",
									type: "color", title: '"More Date" margin hover color:'});


				html += doOption({name: "moreDateSize", value: data.moreDateSize,
										type: "number", min: 0, title: '"More dates" link font size', after: "px"});


				html += doOption({name: "moreDateFontStyle", value: data.moreDateFontStyle,
							type: "select", title: "Title font style:",
							values: ["normal","italic","bold","100","300","500","700"],
							options: ["Normal","Italic","Bold","100","300","500","700"]});

				html += doOption({name: "passedOccurencesText", value: data.passedOccurencesText, type: "input",
								title: '"Passed Dates" text:'});

				html += doOption({name: "upcomingOccurencesText", value: data.upcomingOccurencesText, type: "input",
								title: '"Upcomming Dates" text:'});


				html += '<div class="alert alert-info">Customize how the date and time appears in the modal from the modal settings</div>';

				html += '<h4>Date & time formatting <small>In the Event Box only</small></h4>';


				html += doOption({name: "dateColor", value: data.dateColor, defaultValue: "#999",
									type: "color", title: 'Date color:'});

				html += doOption({name: "dateFontSize", value: data.dateFontSize,
										type: "number", min: 0, title: "Date font size:", after: "px"});


				html += doOption({name: "dateTextAlign", value: data.dateTextAlign,
							type: "select", title: "Date text alignment:",
							values: ["left","center","right"],
							options: ["Left","Center","Right"]});

				html += doOption({name: "dateFontStyle", value: data.dateFontStyle,
							type: "select", title: "Date font style:",
							values: ["normal","italic","bold","100","300","500","700"],
							options: ["Normal","Italic","Bold","100","300","500","700"]});


				html += doOption({name: "dateLableColor", value: data.dateLableColor, defaultValue: "#666",
									type: "color", title: 'Date label color:'});


				html += doOption({name: "dateLableSize", value: data.dateLableSize,
										type: "number", min: 0, title: "Date label font size:", after: "px"});

				html += doOption({name: "dateLabelLineHeight", value: data.dateLabelLineHeight,
									type: "number", min: 0, title: "Date label line height:", after: "px"});

				html += doOption({name: "dateLabelStyle", value: data.dateLabelStyle,
							type: "select", title: "Date label font style:",
							values: ["normal","italic","bold","100","300","500","700"],
							options: ["Normal","Italic","Bold","100","300","500","700"]});




				html += doOption({name: "datePaddingSides", value: data.datePaddingSides,
									type: "number", min: 0, title: "Date padding sides:", after: "px"})	;

				html += doOption({name: "datePaddingTop", value: data.datePaddingTop,
								type: "number", min: 0, title: "Date padding top:", after: "px"});

				html += doOption({name: "datePaddingBottom", value: data.datePaddingBottom,
								type: "number", min: 0, title: "Date padding bottom:", after: "px"});

				html += doOption({name: "dateMarginTop", value: data.dateMarginTop,
								type: "number", min: 0, title: "Date margin top:", after: "px"});

				html += doOption({name: "dateMarginBottom", value: data.dateMarginBottom,
								type: "number", min: 0, title: "Date margin bottom:", after: "px"});



				html += getToggling({title: 'Date bottom border:',
								value:(data.dateBorderSize=="0")?"hide":"show",
								items: [
										doOption({name: "dateBorderSize", value: data.dateBorderSize,
												itemClass:"isBorder",
												type: "number", min: 0, title: "border size:", after: "px"}),

										doOption({name: "dateBorderColor", value: data.dateBorderColor,
												 defaultValue: "#eee", type: "color",
												 title: "border color:"})
										]});




				html += '<h3 id="detailSettings">Details/tickets Settings</h3>';

					html += doOption({name: "showAllTickets", value: data.showAllTickets,
										type: "toggle", title: 'Show all tickets in event Box:',
										info: "if set to false, only first ticket will be shown in event box. ALl tickets will still appear in booking popup!"});


					html += doOption({name: "detailsColor", value: data.detailsColor, defaultValue: "#999",
									type: "color", title: "Details color:"});

					html += doOption({name: "detailsFontSize", value: data.detailsFontSize,
										type: "number", min: 0, title: "Details font size:", after: "px"});

					html += doOption({name: "detailsFontLineHeight", value: data.detailsFontLineHeight,
										type: "number", min: 0, title: "Details line height:", after: "px"});


					html += doOption({name: "detailsFontStyle", value: data.detailsFontStyle,
								type: "select", title: "Title font style:",
								values: ["normal","italic","bold","100","300","500","700"],
								options: ["Normal","Italic","Bold","100","300","500","700"]});




					html += doOption({name: "detailsLableColor", value: data.detailsLableColor, defaultValue: "#CCC",
									type: "color", title: "Details Label color:",
									info: "Color of labels such  the text in spots left"});

					html += doOption({name: "detailsLableSize", value: data.detailsLableSize,
										type: "number", min: 0, title: "Details Label font size:", after: "px"});

					html += doOption({name: "detailsLabelLineHeight", value: data.detailsLabelLineHeight,
										type: "number", min: 0, title: "Details Label line height:", after: "px"});


					html += doOption({name: "detailsLabelStyle", value: data.detailsLabelStyle,
								type: "select", title: "Title Label font style:",
								values: ["normal","italic","bold","100","300","500","700"],
								options: ["Normal","Italic","Bold","100","300","500","700"]});



					html += doOption({name: "detailsPaddingSides", value: data.detailsPaddingSides,
									type: "number", min: 0, title: "Details padding sides:", after: "px"});

					html += doOption({name: "detailsPaddingTop", value: data.detailsPaddingTop,
									type: "number", min: 0, title: "Details padding top:", after: "px"});

					html += doOption({name: "detailsPaddingBottom", value: data.detailsPaddingBottom,
									type: "number", min: 0, title: "Details padding bottom:", after: "px"});

					html += doOption({name: "detailsMarginTop", value: data.detailsMarginTop,
									type: "number", min: 0, title: "Details margin top:", after: "px"});

					html += doOption({name: "detailsMarginBottom", value: data.detailsMarginBottom,
									type: "number", min: 0, title: "Details margin bottom:", after: "px"});



				html += getToggling({title: 'Details bottom border:',
								value:(data.detailsBorderSize=="0")?"hide":"show",
								items: [
										doOption({name: "detailsBorderSize", value: data.detailsBorderSize,
												itemClass:"isBorder",
												type: "number", min: 0, title: "border size:", after: "px"}),

										doOption({name: "detailsBorderColor", value: data.detailsBorderColor,
												 defaultValue: "#eee", type: "color",
												 title: "border color:"}),
										doOption({name: "detailsBorderSide", value: data.detailsBorderSide,
												type: "number", min: 0,
												title: "Details border Seperator size:", after: "px"})
										]});





				html += '<h3 id="btnSettings">Button Settings</h3>';


				html += doOption({name: "showPrice", value: data.showPrice,
										type: "toggle", title: 'Show price in button:',
										info: "Adds price to buttons."});



				html += doOption({name: "btnColor", value: data.btnColor, defaultValue: "#fff",
									type: "color", title: "Button text color:"});

				html += doOption({name: "btnBgColor", value: data.btnBgColor, defaultValue: "#2ecc71",
									type: "color", title: "Button background color:"});


				html += doOption({name: "btnFontSize", value: data.btnFontSize,
										type: "number", min: 0, title: "Button font size:", after: "px"});

				html += doOption({name: "btnLineHeight", value: data.btnLineHeight,
									type: "number", min: 0, title: "Button line height:", after: "px"});


				html += doOption({name: "btnFontType", value: data.btnFontType,
							type: "select", title: "Button font style:",
							values: ["normal","italic","bold","100","300","500","700"],
							options: ["Normal","Italic","Bold","100","300","500","700"]});



					html += doOption({name: "btnSidePadding", value: data.btnSidePadding,
									type: "number", min: 0, title: "Button padding sides:", after: "px"});

					html += doOption({name: "btnTopPadding", value: data.btnTopPadding,
									type: "number", min: 0, title: "Button padding top/bottom:", after: "px"});


					html += doOption({name: "btnMarginTop", value: data.btnMarginTop,
									type: "number", min: 0, title: "Button margin top:", after: "px"});

					html += doOption({name: "btnMarginBottom", value: data.btnMarginBottom,
									type: "number", min: 0, title: "Button margin bottom:", after: "px"});


				    html += getToggling({title: 'Button top border:',
								value:(data.btnBorder=="0")?"hide":"show",
								items: [
										doOption({name: "btnBorder", value: data.btnBorder,
												itemClass:"isBorder",
												type: "number", min: 0, title: "border size:", after: "px"}),

										doOption({name: "btnBorderColor", value: data.btnBorderColor,
												 defaultValue: "#eee", type: "color",
												 title: "border color:"})
										]});


					html += doOption({name: "btnBorderRadius", value: data.btnBorderRadius,
									type: "number", min: 0, title: "Button Border Radius:", after: "px",
									info: "Radius of the border (0) for perfect square."});




					html += '<h3 id="addToCalendar">Add to Calendar Settings</h3>';
					html += doOption({name: "addToCalendar", value: data.addToCalendar, type: "toggle", title: "Add to Calendar Button:"});


					html += doOption({name: "addToCalendarTextColor", value: data.addToCalendarTextColor, defaultValue: "#ADADAD", type: "color", title: "Text Color:"});
					html += doOption({name: "addToCalendarTextHoverColor", value: data.addToCalendarTextHoverColor, defaultValue: "#CCC", type: "color", title: "Text Hover Color:"});


					html += doOption({name: "addToCalendarTextFontSize", value: data.addToCalendarTextFontSize, type: "number", min: 0, title: "Text font size:", after:"px"});

					html += doOption({name: "addToCalendarTextFontStyle", value: data.addToCalendarTextFontStyle,
										type: "select", title: "Font style:",
										values: ["normal","italic","bold","100","300","500","700"],
										options: ["Normal","Italic","Bold","100","300","500","700"]});

					html += doOption({name: "addToCalendarMarginSide", value: data.addToCalendarMarginSide, type: "number", min: 0, title: "Margin Side:", after:"px"});
					html += doOption({name: "addToCalendarMarginBottom", value: data.addToCalendarMarginBottom, type: "number", min: 0, title: "Margin Bottom:", after:"px"});

					html += doOption({name: "addToCalendarAlign", value: data.addToCalendarAlign,
										type: "select", title: "Alignment:",
										values: ["left","right"],
										options: ["Left","Right"]});



					html += doOption({name: "addToCalendarText", value: data.addToCalendarText, type: "input",
										title: "Add to Google Calendar text:"})
				}

				html += '</div>';

				html += '<div class="btnsCnt"><div class="btnsCntInner">';

        if (id != 10) {
          html += '<div class="thebtn"><a href="#" class="btn btn-small btn-success btn-settings-save">Save</a></div>';
        }

				if (id == 8) {
					html += '<div class="quicklinkCnt">';
					html += '<span>Quick Links</span>';


					html += '<a href="#textS" class="quicklink">Text Settings</a>';
					html += '<a href="#general2" class="quicklink">General Settings</a>';
          html += '<a href="#bgSettings" class="quicklink">Background Settings</a>';
					html += '<a href="#titleSettings" class="quicklink">Title Settings</a>';
					html += '<a href="#imgSettings" class="quicklink">Thumbnail Settings</a>';
          html += '<a href="#mapSettings" class="quicklink">Map Settings</a>';
					html += '<a href="#descSettings" class="quicklink">Description Settings</a>';
					html += '<a href="#dateSettings" class="quicklink">Date Settings</a>';
					html += '<a href="#LocationSettings" class="quicklink">Location Settings</a>';
					html += '<a href="#btnSettings" class="quicklink">Button Settings</a>';


					html += '</div>'

				} else if (id == 3 || id == 1) {
					html += '<div class="quicklinkCnt">';
					html += '<span>Quick Links</span>';


					html += '<a href="#textS" class="quicklink">Text Settings</a>';
					html += '<a href="#general2" class="quicklink">General Settings</a>';


					html += '<a href="#imgSettings" class="quicklink">Image Settings</a>';
					html += '<a href="#mapSettings" class="quicklink">Map Settings</a>';
          html += '<a href="#LocationSettings" class="quicklink">Location Settings</a>';
					html += '<a href="#titleSettings" class="quicklink">Title Settings</a>';
					html += '<a href="#descSettings" class="quicklink">Description Settings</a>';
					html += '<a href="#dateSettings" class="quicklink">Date Settings</a>';
					html += '<a href="#detailSettings" class="quicklink">Details/tickets Settings</a>';
					html += '<a href="#btnSettings" class="quicklink">Button Details Settings</a>';
					html += '<a href="#addToCalendar" class="quicklink">Add To Calendar</a>';

					html += '</div>'

				} else if (id == 4) {

					html += '<div class="quicklinkCnt">';
					html += '<span>Quick Links</span>';

					html += '<a href="#modalGeneralSettings" class="quicklink">General Settings</a>';

					html += '<a href="#successSettings" class="quicklink">Success Settings</a>';

					html += '<a href="#modalTitleSettings" class="quicklink">Title Settings</a>';
					html += '<a href="#modalMainSettings" class="quicklink">Content Color</a>';
					html += '<a href="#modalInputs" class="quicklink">Input Settings</a>';
					html += '<a href="#selectSettings" class="quicklink">Dropdown Settings</a>';
					html += '<a href="#checkSettings" class="quicklink">CheckBox/RadioButton</a>';

					html += '<a href="#modalSettings" class="quicklink">Button Settings</a>';

					html += '<a href="#modalDateSettings" class="quicklink">Date Settings</a>';
					html += '</div>'

				} else if (id == 3) {

					html += '<div class="quicklinkCnt">';
					html += '<span>Quick Links</span>';

					html += '<a href="#priceSettings" class="quicklink">Price Settings</a>';
					html += '<a href="#taxSettings" class="quicklink">Tax Settings</a>';
          html += '<a href="#couponsSettings" class="quicklink">Coupons Settings</a>';
          html += '<a href="#bookingSettings" class="quicklink">Booking Settings</a>';


					html += '</div>';

				} else if (id == 5) {
					html += '<div class="quicklinkCnt">';
					html += '<span>Quick Links</span>';
					html += '<a href="#emailSettings" class="quicklink">Email Settings</a>';
					html += '<a href="#emailTemplateDiv" class="quicklink">Buyer Email (success)</a>';
					html += '<a href="#OwnerEmailTemplateDiv" class="quicklink">Owner Email (success)</a>';
					html += '<a href="#refundemailTemplateDiv" class="quicklink">Buyer Email (refund)</a>';
					html += '<a href="#refundOwneremailTemplateDiv" class="quicklink">Owner Email (refund)</a>';

					html += '</div>';
				}


				html += '</div></div>';

				html += '</form></div>';


		$('.eventDetails .cnt').html(html);

    // init color pickers
    $('.colorPicker').wpColorPicker();

    // init upload
    $('.upload').uploader();

    // init email related functions
		$('.loadDefaultTemplate').click(function(e){
      e.preventDefault();
      $('#loader').slideDown(100);

      $.ajax({
        type:'POST',
        url: 'admin-ajax.php',
        data: 'action=get_email_default_template',
        success: function(response) {
          $("#emailTemplate").val(response);
          $('#loader').slideUp(100);
        }
      });
		});

    $('.loadDefaultOwnerTemplate').click(function(e){
      e.preventDefault();
      $('#loader').slideDown(100);

      $.ajax({
          type:'POST',
          url: 'admin-ajax.php',
          data: 'action=get_owner_email_default_template',
          success: function(response) {
            $("#ownerEmailTemplate").val(response);
            $('#loader').slideUp(100);
          }
      });
    });

    $('.emailTemplateCodes a').click(function(e){
			e.preventDefault();
			if($(this).text() === 'Show Keywords') {
				$(this).text('Hide Keywords')
				$(this).parent().find('li').show();
			} else {
				$(this).text('Show Keywords')
				$(this).parent().find('li').hide();
			}

		});

		$('.emailTemplateCodes li').hide();

    $('.testEmail').on("click",function(e){
      e.preventDefault();
      $('#loader').slideDown(100);

      $("#testEmailDiv").html("Saving settings...");

      var id = $("#setting-id").val();

      $.ajax({
        type:'POST',
        url: 'admin-ajax.php',
        data: 'action=ebp_save_settings&id='+id+"&"+$("#settingsForm").serialize(),
        success: function(response) {
          $("#testEmailDiv").append("<br/>Testing Email...");

          $.ajax({
            type:'POST',
            url: 'admin-ajax.php',
            data: 'action=testEmail',
            success: function(response) {
              $("#testEmailDiv").append("<br/><br/>"+response);
              $('#loader').slideUp(100);
            }
          });
        }
      });
    });


    // init togglers
    $('.make-switch')['bootstrapSwitch']();

    // init do hiding
    setTimeout(function() {
      $('.switcher').each(function(index, element) {
    		var $switcher = $(this).find('.make-switch');

        if ($switcher.hasClass("hasCnt")) {
          var inverseToggle = $switcher.hasClass('inverseToggle');
          isOn = $switcher.find('.switch-animate').hasClass('switch-off');

        	if (!inverseToggle && isOn || inverseToggle && !isOn) {
    				$(this).find(".cnt").slideUp(100);
        	} else {
        		$(this).find(".cnt").slideDown(100);
        	}

        }
      });

    }, 10);

  	$('.make-switch').on('switch-change', function (e, data) {
      if ($(this).hasClass("hasCnt")) {
        var inverseToggle = $(this).hasClass('inverseToggle');
        var $localParent = $(this).parent().parent().parent();

        if ((!inverseToggle && data.value ) || (inverseToggle && !data.value)) {

          var oldAttr = ($localParent.find(".isBorder").attr("data-oldvalue")) ? $localParent.find(".isBorder").attr("data-oldvalue") : "1";

          $localParent.find(".isBorder input").val(oldAttr);
          $localParent.find(".cnt").slideDown(100);
        } else {
          $localParent.find(".isBorder").attr("data-oldvalue",$localParent.find(".isBorder input").val());

          $localParent.find(".cnt").slideUp(100, "linear", function() {
            $(this).find(".isBorder input").val("0");
          });
        }
      }
  	});
	}


  $(document).on("click", '.fixMobilePage', function(e){
    e.preventDefault();
    $('#loader').slideDown(100);
    $.ajax({
      type:'POST',
      url: 'admin-ajax.php',
      data: 'action=fix_mobile_page',
      success: function(response) {
        $('#loader').slideUp(100);
      }
    });
  });


  $(document).on("click", '.setCollation',function(e){
    e.preventDefault();
    $('#loader').slideDown(100);

    $.ajax({
      type:'POST',
      url: 'admin-ajax.php',
      data: 'action=ebp_set_collation',
      success: function(response) {
        $('#loader').slideUp(100);
      }
    });
  });

	$(document).on("click", '.fixOccurences',function(e){
		e.preventDefault();
		$('#loader').slideDown(100);
		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_clean_occurences',
			success: function(response) {
				$('#loader').slideUp(100);
			}
		});
	});

	$(document).on("click", '.quicklink',function(e){
		e.preventDefault();
		$('html, body').animate({
			scrollTop: $($(this).attr("href")).offset().top-50
		}, 300);
	});

	$(document).on("click", '.btn-settings-save',function(e){
		e.preventDefault();
		saveSettingsData();
	});

	$(document).on("click", "#changeSettings a",function(e){
		if ($(this).hasClass("active")) return;

		$('#loader').slideDown(100);
		$('.eventDetails .cnt').fadeOut(200);
		var id_toLoad = $(this).attr("data-id");

		$.ajax({
			type:'POST',
			url: 'admin-ajax.php',
			data: 'action=ebp_settings&id='+id_toLoad,
			success: function(response) {
				var data=$.parseJSON(response);
				getSettingsPage(id_toLoad, data);
				$('.eventDetails .cnt').fadeIn(200);
				$('#loader').slideUp(100);
			}
		});
	});

	function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

	var getListOfEmailTemplates = function () {
		var list ='<ul class="emailTemplateCodes">'
			list +='<a href="#">Show Keywords</a>'
			list += '<li><strong>%booking_QR_Code%:</strong><span>QR code with booking id:  *Requires the Check-in Addon.</span></li>';
			list += '<li><strong>%transactionID_QR_Code%:</strong><span>QR code with transaction id: *Requires the Check-in Addon</span></li>';

			list += '<li><strong>%bookingType%</strong></li>';

			list += '<li><strong>%payer_name%</strong></li>';
			list += '<li><strong>%payer_email%</strong></li>';
			list += '<li><strong>%quantity%</strong></li>';

			list += '<li><strong>%paymentID%</strong><span>Id used in plugin (ID column)</span></li>';
			list += '<li><strong>%paymentIDFormatted%:</strong><span>Payment id as 10 digits</span></li>';
			list += '<li><strong>%transaction_id%</strong><span>Paypal or other gateway transaction Id.</span></li>';
			list += '<li><strong>%paymentDate%</strong></li>';

			list += '<li><strong>%payment_amount%</strong></li>';
      list += '<li><strong>%payment_amount_taxed%</strong></li>';
      list += '<li><strong>%tax_rate%</strong></li>';

			list += '<li><strong>%currency%</strong></li>';
			list += '<li><strong>%couponMarkUp%</strong></li>';

			list += '<li><strong>%ticketName%</strong></li>';
			list += '<li><strong>%ticketID%</strong></li>';


			list += '<li><strong>%eventname%</strong></li>';
			list += '<li><strong>%event_desc%</strong></li>';
			list += '<li><strong>%event_address%</strong></li>';
			list += '<li><strong>%eventid%:</strong> <span>Event Id as shown in admin panel</span></li>';
			list += '<li><strong>%eventid_formatted%:</strong><span>Event Id as 10 digits</span></li>';

      list += '<li><strong>%event_categories%:</strong><span>Lists all categories the event belongs too.</span></li>';


			list += '<li><strong>%start_time%</strong></li>';
			list += '<li><strong>%dateID%</strong></li>';
			list += '<li><strong>%startDate%</strong></li>';
			list += '<li><strong>%end_time%</strong></li>';
			list += '<li><strong>%endDate%</strong></li>';


			list += '<li><strong>For Form Manager addon users:</strong>:</li>';
			list += '<li><strong>%allExtraFields%</strong><span>Displays all extra fields</span></li>';
			list += '<li><strong> %inputName%</strong><span>example: %color%</span></li>';
		list += '</ul>'

		return list;
	}
})
})(jQuery);
