/* Event Booking Pro - WordPress Plugin
 * Get plugin from: http://iplusstd.com/item/eventBookingPro/
 * Moe Haydar
 */

(function($) {
  var $modal = null;

  function doResize() {
    $('.eventDisplayCnt').each(function(index, element) {
      eventImageResizer($(this));
    });
  }

  $(window).resize(function() {
    doResize();
  });


  function eventImageResizer ($which) {
    var imgCropping =  $('.eventImage').first().attr("data-image-crop") ;
    var imgSetHeight =  $('.eventImage').first().attr("data-image-height");

    if (imgCropping == 'true') {
      $('.imgHolder.expandImg').each(function(index, element) {
        if (parseInt($(this).find('img').css('marginTop')) == 0) {
          $(this).height($(this).find('img').height());
        } else {
          var imgH = parseInt($(this).find('img').height()) - imgSetHeight;
          if (imgH > 0 ) {
            var imageH = $(this).find('img').height() - imgSetHeight;
            $(this).find(' img').css('marginTop',-parseInt(imageH/2));
            $(this).height(imgSetHeight);
          } else {
            $(this).height($(this).find('img').height());
            $(this).find(' img').css('marginTop',0);
          }
        }
      });
    }
  }

  function getMapType (mapString) {
     switch(mapString) {
      case "HYBRID":
        return google.maps.MapTypeId.HYBRID;
      case "TERRAIN":
        return google.maps.MapTypeId.TERRAIN;
      case "SATELLITE":
        return google.maps.MapTypeId.SATELLITE;
      case "ROADMAP":
      default:
        return google.maps.MapTypeId.ROADMAP;
     }
  }

  function doMap (mapCanvas, address, mapformat, zoomValue, addressType, scrollwheel) {
    if (address === '') return;

    mapCanvas.style.display = 'block';

    var mapOptions = {
      zoom: parseInt(zoomValue),
      mapTypeId: getMapType(mapformat)
    };

    if (scrollwheel === 'false') mapOptions.scrollwheel = false;

    var map = new google.maps.Map(mapCanvas, mapOptions);

    var geocoder = new google.maps.Geocoder();
    // address from latlng
    if (addressType === 'latlng') {
      var latlngStr = address.split(",", 2);
      var lat = parseFloat(latlngStr[0]);
      var lng = parseFloat(latlngStr[1]);
      var latlng = new google.maps.LatLng(lat, lng);

      geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          map.setCenter(results[0].geometry.location);
          var marker = new google.maps.Marker({
            map: map,
            position: latlng
          });

          google.maps.event.trigger(mapCanvas, "resize");
        } else {
          mapCanvas.style.display = 'none';
        }
      });
    } else {
      geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          map.setCenter(results[0].geometry.location);
          var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
          });

          google.maps.event.trigger(mapCanvas, "resize");
        } else {
          mapCanvas.style.display='none';
        }
      });
    }
  }

  $(document).ready(function() {

    // loading template
    var loadingTemplate = '<div class="EBP--loading"><div id="EBPLOADER"><div id="EBPLOADER_1" class="EBPLOADER"></div><div id="EBPLOADER_2" class="EBPLOADER"></div><div id="EBPLOADER_3" class="EBPLOADER"></div><div id="EBPLOADER_4" class="EBPLOADER"></div><div id="EBPLOADER_5" class="EBPLOADER"></div><div id="EBPLOADER_6" class="EBPLOADER"></div><div id="EBPLOADER_7" class="EBPLOADER"></div><div id="EBPLOADER_8" class="EBPLOADER"></div></div></div>';

    // BOOKING FORM
    var BOOKING_FORM_STEPS = {
      FORM_BOOKING: 'FORM_BOOKING',
      FORM_CONFIG: 'FORM_CONFIG',
      FORM_MORE_DATES: 'FORM_MORE_DATES',
      FORM_COMING: 'FORM_COMING',
      FORM_GIFT_CARD_BOOKING: 'FORM_GIFT_CARD_BOOKING'
    }

    var EBP_MAIN_ACTION = 'ebp_get_booking_step';
    var EBP_GIFT_CARD_ACTION = 'ebp_gift_card_form';

    var LOCALS = null;

    var $originalQuantityCnt = jQuery('<div></div>');
    var $duplicatableFields = null;
    var $modal = jQuery('<div class="EBP--modal EBP--fullpage"></div>');
    var $modalContent = jQuery('<div class="EBP--content"></div>');
    var $modaleContentScroll;

    var $closeBtnCnt = jQuery('<div class="EBP--closeBtn"><a>x</a></div>');
    var $closeBtn = jQuery('<a href="#">x</a>');

    var $loadingModalContent = null;

    function isMobilePage () {
      return window.ebpIsMobile === true;
    }
    function isMobilePageBooking () {
      return window.ebpMobileIsBooking === true;
    }

    function initEBP () {
      initBoxes($('body'));
      initCards($('body'));

      $('body').append($modal);

      $('body').append('<div class="EBP--overlay"></div>');

      $(document).on('click', '.EBP--overlay, .EBP--closeBtn a', function(e) {
        e.preventDefault();
        closePopUp();
      });
    };

    var handleError = function (err) {
      console.error(err);
      alert(err);
    };

    var setModalContent = function ($html, closeBtn, scroll) {
      if ($modaleContentScroll) {
        $modaleContentScroll.remove();
      }

      $modalContent.remove();

      $modal.removeClass('fullHeight');
      $modalContent = jQuery('<div class="EBP--content EBP--hiddenState"></div>');

      if (scroll) {
        $modaleContentScroll = jQuery('<div class="EBP--Scrollable--outer"></div>');
        $modaleContentScroll.html($html);
        $modalContent.append($modaleContentScroll);

        $modaleContentScroll.scrollbar();
      } else {
        $modalContent.append($html);
      }

      if (closeBtn) {
        $modalContent.prepend($closeBtnCnt)
      }

      $modal.append($modalContent);
      setTimeout(function() {
        $modalContent.removeClass('EBP--hiddenState');
      }, scroll ? 100 : 300);

    }

    var showModalLoader = function () {
      if ($loadingModalContent === null) {
        $loadingModalContent = jQuery(loadingTemplate);
      }
      setModalContent($loadingModalContent, false, false);
    }

    var showModal = function () {
      $modal.addClass("EBP--show");
      if (!isMobilePage()) {
        setTimeout( function() {$('body').addClass('EBP--perspective' )}, 25);
      }
    }

    var loadConfig = function(cb) {
      LOCALS = {};
      getModalStep(EBP_MAIN_ACTION, BOOKING_FORM_STEPS.FORM_CONFIG, null, function (response) {
        var json = JSON.parse(response);
        LOCALS.showTax = EbpUtil.isTrue(json.showTaxInBookingForm);
        LOCALS.tax = parseFloat(json.tax_rate);
        LOCALS.currency = json.currency;
        LOCALS.currencyBefore = EbpUtil.isTrue(json.currencyBefore);
        LOCALS.priceDecimalCount = json.priceDecimalCount;
        LOCALS.priceDecPoint = json.priceDecPoint;
        LOCALS.priceThousandsSep = json.priceThousandsSep;

        if (cb) cb();
      });
    }

    var openAndIntModal = function(action, modalType, opt) {
      if (LOCALS === null) {
        loadConfig(function() {
          loadModalContent(action, modalType, opt);
        });
      } else {
        loadModalContent(action, modalType, opt);
      }
    }

    var loadModalContent = function (action, modalType, opt) {
      showModalLoader();
      // get form html
      getModalStep(action, modalType, opt, function (formHtml) {
        setModalContent(formHtml, true, true);
        if (modalType === BOOKING_FORM_STEPS.FORM_BOOKING) {
          initBookingForm();
        } else if (modalType === BOOKING_FORM_STEPS.FORM_GIFT_CARD_BOOKING) {
          initGiftCardForm();
        }
      });
    }

    var closePopUp = function() {
      $('body').removeClass('EBP--perspective');
      $modal.removeClass('EBP--show');
      $modalContent.empty();
    }

    var getModalStep = function (action, step, options, cb) {
      var dataOptions = '&step=' + step;
      if (options) {
        for (var property in options) {
          if (options.hasOwnProperty(property)) {
            dataOptions += '&' + property + '=' + options[property]
          }
        }
      }

      $.ajax({
        type:'POST',
        url: $("input[name='ajaxlink']").val() + '/wp-admin/admin-ajax.php',
        data: 'action=' + action + dataOptions,
        error: function (error) {
          console.error(error);
        },
        success: function(response) {
          cb(response);
        }
      });
    }

    function browserIsMobile() {
      return $(document).width() < 760;
    }

    $(document).on('click', '.ebp-trigger', function(e) {
      e.preventDefault();
      var isMoreDates = $(this).hasClass("isMoreDate");

      var dataID = $(this).attr("data-dateid");
      var id = $(this).attr("data-id");

      var type = (isMoreDates) ? 'eventOccurrences' : 'book';

      if (browserIsMobile() && $(this).attr('data-seperatePage') === 'true') {
        var url = $("input[name='ebpMobilegPage']").val();

        url += (url.indexOf("?") > -1) ? "&" : "?";

        url += 'type=' + type + '&id=' + id + '&date_id=' + dataID;

        window.location.href = url;
      } else {

        if (isMoreDates) {
          showModal();
          openAndIntModal(EBP_MAIN_ACTION, BOOKING_FORM_STEPS.FORM_MORE_DATES, {eventId: id, dateId: dataID});

        } else {
          showModal();
          openAndIntModal(EBP_MAIN_ACTION, BOOKING_FORM_STEPS.FORM_BOOKING, {eventId: id, dateId: dataID});
        }
      }
    });

    // booked people modal
    $(document).on('click', '.ebp_btn_people', function(e) {
      e.preventDefault();

      var dataID = $(this).attr("data-date");
      var id = $(this).attr("data-event");

      if (browserIsMobile() && $(this).attr('data-seperatePage') === 'true') {
        var url = $("input[name='ebpMobilegPage']").val();

        url += (url.indexOf("?") > -1) ? "&" : "?";

        url += 'type=coming&id=' + id + '&date_id=' + dataID;

        window.location.href = url;
      } else {
        showModal();
        openAndIntModal(EBP_MAIN_ACTION, BOOKING_FORM_STEPS.FORM_COMING, {eventId: id, dateId: dataID});
      }
    });

    // More dates page, book button
    $(document).on('click', '.Modal--directDateBook', function(e) {
      e.preventDefault();

      var dataID = $(this).attr("data-to-open");
      var id = $(this).attr("data-id");

      if (browserIsMobile() && $(this).attr('data-seperatePage') === 'true') {
         var url = $("input[name='ebpMobilegPage']").val();

        url += (url.indexOf("?") > -1) ? "&" : "?";

        url += 'type=book&id=' + id + '&date_id=' + dataID;

        window.location.href = url;
      } else {
        loadModalContent(EBP_MAIN_ACTION, BOOKING_FORM_STEPS.FORM_BOOKING, {eventId: id, dateId: dataID});
      }
    });

    // Gift card
    $(document).on('click', '.EBP--GiftCard .EBP--BookBtn', function(e) {
      e.preventDefault();
      showModal();
      var giftCardAmount = $(this).attr('data-amount');
      var giftCardName = $(this).attr('data-name');
      var giftCardLimit = $(this).attr('data-limit');

      openAndIntModal(EBP_GIFT_CARD_ACTION, BOOKING_FORM_STEPS.FORM_GIFT_CARD_BOOKING, {
        amount: giftCardAmount,
        name: giftCardName,
        limit: giftCardLimit
      });
    });


    function initGiftCardForm() {

      $modalContent.find('#toSamePerson').change(function() {
        if($(this).is(":checked")) {
          $modalContent.find('.recipientCnt').slideDown(50);
          $modalContent.find('input[name="toname"]').addClass('isRequired');
          $modalContent.find('input[name="toemail"]').addClass('isRequired');
        } else {
          $modalContent.find('.recipientCnt').slideUp(50);
          $modalContent.find('input[name="toname"]').removeClass('isRequired');
          $modalContent.find('input[name="toemail"]').removeClass('isRequired');
        }
      });

      $modalContent.find('.Modal--BookBtn').click(function (e) {
        e.preventDefault();

        doGiftCardBooking($(this));
      });
    }

    function doGiftCardBooking($bookingBtn) {
      if ($bookingBtn.hasClass('deactive')) return;

      $modalContent.find('.incorrect').each(function (index, element) {
        $(this).removeClass('incorrect');
      });

      if (!validateAllRequiredOkay()) return false;

      activateButtons(false);

      var $loader = $modalContent.find('.Modal--BookingLoader');
      $loader.show(200);

      if ($modaleContentScroll) {
        $modaleContentScroll.animate({
          scrollTop: $loader.offset().top - $modaleContentScroll.offset().top + $modaleContentScroll.scrollTop()
        });
      }

      var giftName = $modalContent.find('input[name="giftName"]').val();
      var title = $modalContent.find('.Modal--title').text();
      var amount = $modalContent.find('input[name="amount"]').val();
      var bookingType = $bookingBtn.attr("data-type");
      var currentPage = document.URL;

      var name = $modalContent.find('input[name="name"]').val();
      var email = $modalContent.find('input[name="email"]').val();
      var toName = '';
      var toEmail = '';
      var toMessage = '';

      if ($modalContent.find('#toSamePerson').is(':checked')) {
        toName = $modalContent.find('input[name="toname"]').val();
        toEmail = $modalContent.find('input[name="toemail"]').val();
        toMessage = $modalContent.find('textarea[name="tomessage"]').val();
      } else {
        toName = name;
        toEmail = email;
      }

      var limit = $modalContent.find('input[name="limit"]').val();;

      $.ajax({
        type:'POST',
        url: $("input[name='ajaxlink']").val() + '/wp-admin/admin-ajax.php',
        data:'action=ebp_gift_card_book' +
         '&bookingType=' + bookingType +
         '&amount=' + amount +
         '&giftName=' + giftName +
         '&title=' + title +
         '&limit=' + limit +
         '&name=' + name +
         '&email=' + email +
         '&toName=' + toName +
         '&toEmail=' + toEmail +
         '&toMessage=' + toMessage +
         '&currentPage=' + currentPage,
        error: function(response) {
          activateButtons(true);

          $modalContent.find(".Modal--BookingLoader").html("Error Sending !");
        },
        success: function(response) {

          var json = $.parseJSON(response);

          switch (json.code) {
            case 'FORM':
              $(json.form).appendTo('body').submit();
            break;

            case 'URL':
              window.location.href = json.url;
            break;

            case 'SUCCESS':
              $modalContent.find('.Modal--BookingLoader').html(json.successText);

              resetForm();

              switch (json.action) {
                case 'popup':
                  setTimeout(function() {
                    setModalContent(json.popup, true, false);
                  }, 10);
                  break;

                case 'close':
                  closePopUp();
                  break;

                case 'redirect':
                  // backwards compatibility
                  if (json.url.indexOf('error&') > -1) {
                    console.error(json.url)
                    alert('An error occurred!')
                  } else {
                    window.location.href = json.url;
                  }
                  break;
              }
            break;
            case 'ERROR':
            default:
              $modalContent.find('.Modal--BookingLoader').html(json.error);
              activateButtons(true);
              console.log(json)
            break;
          }

        }
      });
    }

    //  INIT THE PLUGIN

    if (!isMobilePage()) {
      initEBP();
    } else {
      $modalContent = $('.EBP--content');
      $modal = $('.EBP--modal')
      if (isMobilePageBooking()) {
        loadConfig(function() {
          initBookingForm();
          showModal();
        })
      } else {
        showModal();
      }
    }


    // Events List
    $('.Ebp--EventsList.Ebp--NotInited').each(function() {
      var $ref = $(this);
      $ref.removeClass('Ebp--NotInited');
      var $eventListLoader = jQuery(loadingTemplate);
      $ref.append($eventListLoader);
      var dataOptions = '';
      dataOptions += '&events=' + $(this).attr('data-events');
      dataOptions += '&order=' + $(this).attr('data-order');
      dataOptions += '&type=' + $(this).attr('data-type');
      dataOptions += '&categories=' + $(this).attr('data-categories');
      dataOptions += '&limit=' + $(this).attr('data-limit');
      dataOptions += '&width=' + $(this).attr('data-width');
      dataOptions += '&months=' + $(this).attr('data-months');
      dataOptions += '&nextdays=' + $(this).attr('data-nextdays');
      dataOptions += '&filter=' + $(this).attr('data-filter');
      dataOptions += '&show_occurences_as_seperate=' + $(this).attr('data-show_occurences_as_seperate');

      $.ajax({
        type:'POST',
        url: $("input[name='ajaxlink']").val() + '/wp-admin/admin-ajax.php',
        data: 'action=ebp_get_events_list_data'+ dataOptions,
        error: function (error) {
          console.error(error);
        },
        success: function(response) {
          $eventListLoader.remove();
          $ref.html(response);
          initCards($ref);
          initBoxes($ref);
        }
      });
    });

    // event list filter
    $(document).on('click', '.catFilter', function(e) {
      e.preventDefault();
      var $this = $(this);
      var catList = [] ;

      var catClass = $this.attr('data-cat-id');

      if (catClass === 'ebpCat_all') {
        $this.parent().find('.catFilter.isHidden').removeClass("isHidden");
      } else {
        $this.parent().find('.catFilter').not('.isHidden').addClass("isHidden");

        if ($this.hasClass("isHidden")) {
          $this.removeClass("isHidden");
        }
      }

      $this.parent().find('.catFilter').not('.isHidden').each(function(){
        catList.push($(this).attr('data-cat-id'));
      });

      $this.parent().parent().find('.isAnEvent').each(function() {
        var classSet =  $(this).attr('class');

        var show = false;
        for (cl in catList) {
          if (classSet.indexOf(catList[cl]) > -1) {
            show = true;
            break;
          }
        }

        if (show) {
         $(this).show();
         $(this).parent('.eventDisplayCnt').show();
        } else {
          $(this).hide();
         $(this).parent('.eventDisplayCnt').hide();
        }
      })
    });

    var $activeDateSelect = null;

    // cancel booking
    $(document).on('click', '.ebpUsersCancelBtn', function(e) {
      e.preventDefault();
      var bId = $(this).attr('data-id');
      var dataLink = $(this).attr('data-link');
      var $this = $(this);

      var r = confirm('Are you sure you want to cancel this booking?');
      if (!r) return;
      $.ajax({
        type: 'POST',
        url: dataLink,
        data: 'action=booking_delete&id=' + bId,
        success: function(response) {
          $this.parent().parent().remove();
        }
      });
    });


    function initBoxes($ref) {
      $ref.find(".eventDisplayCnt").not(".isCalendar").not(".eventCardCnt").each(function() {
        prepareEvent($(this));
      });
    }

    function initCards($ref) {
      prepareEvent($ref.find('.eventCardExtendedCnt'), false, false, function ($which) {
        $which.find(".eventDescription").css("display", "none");
        $which.find(".eventDescription").css('opacity', 1);
        $which.find(".eventDescription").css('height', 'auto');
      });
    }

    $(document).on('click', '.eventCardExtendedCnt .eventCardCnt', function(e) {
       if (!$(e.target).is("a")) {
        e.preventDefault();

        if (!$(this).parent().find(".eventCardCnt").hasClass("open")) {
          $(this).parent().find(".eventCardCnt").addClass("open");

          var $eventDesc =  $(this).parent().find(".eventDescription");

          $eventDesc.slideDown();

          if (!$eventDesc.hasClass('inited')) {
            $eventDesc.addClass('inited');
            initMedia($eventDesc);
          }

        } else {
          $(this).parent().find(".eventCardCnt").removeClass("open");
          $(this).parent().find(".eventDescription").slideUp();
        }
      }
    });

    $(document).on('click', '.eventCardExtendedCnt .eventDescription  a.hideDetails', function(e) {
      e.preventDefault();
      $(this).parent().parent().find(".eventDescription").removeClass("open");
      $(this).parent().parent().find(".eventDescription").slideUp();
    });

    function prepareEvent($which, initMap, initImage, callback) {
      if (initMap !== false) initMap = true;
      if (initImage !== false) initImage = true;

      //get info
      $which.find(".info").not(".deactive").each(function() {
        if (parseInt($(this).find(".cnt").height()) > parseInt($(this).attr("data-height"))) {
          $(this).css("height", $(this).attr("data-height"));
          var $expandBtn = $('<a href="#" class="expand"></a>').text($(this).attr("data-expandTxt"));

          $expandBtn.click(function(e) {
            e.preventDefault();
            toggleTxtBox($(this).parent());
          });

          $(this).append($expandBtn);

        } else {
          $(this).css("height", "auto");
        }
      });


      if (initMap || initImage) {
        initMedia($which, initMap, initImage);
      }

      eventImageResizer($which);

      if (typeof callback == 'function') {
        setTimeout(function() { callback($which);}, 1);
      }
    }



    function toggleTxtBox($that) {
      if ($that.hasClass("opened")) {
        $that.removeClass("opened");

        $that.find("a.expand").html($that.attr("data-expandTxt"));
        $that.height($that.attr("data-height"));

      } else {
        $that.addClass("opened");
        $that.find("a.expand").html($that.attr("data-closeTxt"));
        $that.height(($that.find('.cnt').height()+$that.find("a.expand").height() + 20));

      }
    }

    function getImg($where) {
      var img = $where.attr("data-image");
      var imgCropping = $where.attr("data-image-crop") ;
      var imgSetHeight = $where.attr("data-image-height");
      var imgWidth = $where.attr("data-image-width");

      if ($where.find('.imgHolder').length > 0) return;

      var $imgRef = $('<img src="' + img + '" />');
      var $holderRef = $('<div class="imgHolder"></div>').append($imgRef);

      $where.append($holderRef);

      $imgRef.css("display", "block");

      $imgRef.on("load",function(e) {
        if (imgCropping == "true") {
          var imgH = parseInt($imgRef.height()) - imgSetHeight;

          if (imgH > 0) {
            $imgRef.css("display", "block");

            $holderRef.height(imgSetHeight);

            $imgRef.css("margin-top", -parseInt(imgH/2, 10));
            $holderRef.addClass("expandImg");

            var imageH = $imgRef.height() - imgSetHeight;

            $holderRef.find(' img').css("margin-top", -parseInt(imageH/2,10));
            $holderRef.height(imgSetHeight);

            $where.find('.imgHolder.expandImg').click(function(e) {
              if (parseInt($(this).find('img').css("margin-top"),10) < 0) {
                TweenMax_.to($(this).find('img'), 0.5, {css:{marginTop:0}, ease:Expo.easeOut});
                TweenMax_.to($(this), 0.5, {css:{height:$(this).find('img').height()}, ease:Expo.easeOut});
              } else {
                var imageH=$(this).find('img').height() - imgSetHeight;
                TweenMax_.to($(this).find(' img'), 0.5, {css:{marginTop:-parseInt(imageH/2,10)}, ease:Expo.easeOut});
                TweenMax_.to($(this), 0.5, {css:{height:imgSetHeight}, ease:Expo.easeOut});
              }
            });
          } else {
            $imgRef.css("display","block");
          }
        } else {
          $imgRef.css("display","block");
        }
      });
    }

    function initMedia($which, initMap, initImage) {
      if (initMap !== false) initMap = true;
      if (initImage !== false) initImage = true;

      // get images
      if (initImage) {
        $which.find(".eventImage").each(function(index, element) {
          if ($(this).attr("data-image") != "")
            getImg($(this));
        });
      }

      //initialize map
      if (initMap) {
        $which.find('.map_canvas').each(function(index, element) {

          doMap(this, $(this).attr("data-address"), $(this).attr("data-maptype"), parseInt($(this).attr("data-zoom"),10),
            $(this).attr("data-addressType"), true);
        });
      }
    }


    function initBookingFields($ref) {
      //do form selects
      $ref.find('select').each(function(index, element) {
        $(this).dropdownEBP({
          gutter : 2,
          stack : false,
          slidingIn : 100,
          onOptionSelect: function($opt) {
            setTimeout(function() {calcTotal();}, 100);
          }
        });
      });

      // feeable option watch and update total price on change
       $ref.find('.hasRadioButton.hasFee input[type="radio"]').change(calcTotal);
       $ref.find('.hasCheckBoxes.hasFee input[type="checkbox"]').change(calcTotal);
    }

    function updateSpotsLeft() {
      if ($modalContent.find('.Modal--Tickets input[name="Modal--TicketType"]').length < 0 || $modalContent.find('.Modal--Tickets input[name="Modal--Occurrence"]').length < 0) {
        console.log("not initialized");
        return;
      }

      var ticketId = $modalContent.find('.Modal--Tickets input[name="Modal--TicketType"]').val();
      var dateId =  $modalContent.find('.Modal--Tickets input[name="Modal--Occurrence"]').val();
      $modalContent.find(".Modal--Tickets .Modal--SpotsLeft span").text("");
      activateButtons(false);
      $.ajax({
        type: 'POST',
        url: $("input[name='ajaxlink']").val() + '/wp-admin/admin-ajax.php',
        data: 'action=ebp_check_spots&ticket=' + ticketId + '&date_id=' + dateId,
        success: function(response) {
          $modalContent.find(".Modal--Tickets .Modal--SpotsLeft span").text(response);

          // reset quantity
          resetQuantity();

          activateButtons(parseInt($modalContent.find(".Modal--SpotsLeft span").html()) !== 0)

          calcTotal();
        }
      });
    }

    function activateButtons(activate) {
      if (!activate) {
       $modalContent.find(".Modal--BookingBtnsCnt a").each(function(index, element) {
          if (!$(this).hasClass("deactive")) $(this).addClass("deactive")
        });
      } else {
       $modalContent.find(".Modal--BookingBtnsCnt a").each(function(index, element) {
          if ($(this).hasClass("deactive")) $(this).removeClass("deactive")
        });
      }
    }
    function resetQuantity() {
      resetDuplicateCnt();
      // resets all quantities to 0 and increment first quantity to 1
      $modalContent.find('.Modal--QuantityCnt .Modal--QuantityBtns span').html(0);
      updateQuantity($modalContent.find('.Modal--QuantityCnt').first().find('.up'));
    }

    function updateQuantityCnt() {
      var $currentTicket = $modalContent.find('.Modal--Tickets input[name="Modal--TicketType"]');
      var dataBreakdown = $currentTicket.attr('data-breakdown');

      $modalContent.find('.Modal--Quantity').html("");
      var index = 0;

      if (!dataBreakdown || dataBreakdown == 'false') {
        var $toAdd = $originalQuantityCnt.clone(true);
        $toAdd.attr('data-index', index);
        $modalContent.find('.Modal--Quantity').append($toAdd);
      } else {
        var ticketNames = $currentTicket.attr('data-names');
        var ticketNamesArr = [];
        if (ticketNames) {
          ticketNamesArr = $currentTicket.attr('data-names').split('&;');
        }

        ticketNamesArr.forEach(function(name) {
          var $toAdd = $originalQuantityCnt.clone(true);
          $toAdd.attr('data-index', index);
          $toAdd.find('.nameLabel').text(name);
          $modalContent.find('.Modal--Quantity').append($toAdd);
          index++;
        });

        // add final total cost
        // only adds the total column
        if (ticketNamesArr.length > 1) {
          var $toAdd = $originalQuantityCnt.clone(true);
          $toAdd.addClass("Modal--QuantityFinalTotal");
          $toAdd.removeClass("Modal--QuantityCnt")
          $toAdd.find('.single, .singleLabel, .nameLabel, .Modal--QuantityBtns').remove();
          $toAdd.find('.totalLabel').prepend('<div class="topBorder"></div>');
          $modalContent.find('.Modal--Quantity').append($toAdd);
        }
      }
    }

    function initBookingForm() {
      // handle duplicate feature
      $originalQuantityCnt = $modalContent.find('.Modal--QuantityCnt').clone(true);
      $duplicatableFields = $modalContent.find('form .formInput[data-duplicate="true"]').clone(true);
      $modalContent.find('form .formInput[data-duplicate="true"]').remove();
      $modalContent.find('.ebp_form_duplicate_cnt').parent().addClass("EBP--DuplicateCnt")

      // init date dropdown
      $modalContent.find('.Modal--OccurrenceSelect select').each(function(index, element) {
        $(this).dropdownEBP({
          gutter : 5,
          stack : false,
          slidingIn : 100,
          onOptionSelect: function($opt) {
            var bookingStatus = $opt.attr("data-bookingStatus");

            if (bookingStatus == 1) {
              $modalContent.find('.Modal--BookingBtnsCnt').hide()
              $modalContent.find('.Modal--NoBuy').html($opt.attr("data-startsTxt"))
              $modalContent.find('.Modal--NoBuy').show()
            } else if (bookingStatus > 1) {
              $modalContent.find('.Modal--BookingBtnsCnt').hide();
              $modalContent.find('.Modal--NoBuy').html($opt.attr("data-endsTxt"))
              $modalContent.find('.Modal--NoBuy').show()
            } else {
              $modalContent.find('.Modal--BookingBtnsCnt').show();
              $modalContent.find('.Modal--NoBuy').hide()
            }
            $modalContent.find('.Modal--NoBuy').css('line-height', $modalContent.find('.Modal--BookingBtnsCnt').height() + 'px');
            updateSpotsLeft();
          }
        });
      });

      // init ticket dropdown
      $modalContent.find('.Modal--TicketSelect select').each(function(index, element) {
        $(this).dropdownEBP({
          gutter : 5,
          stack : false,
          slidingIn : 100,
          onOptionSelect: function($opt) {
            updateSpotsLeft();
            updateQuantityCnt();
          }
        });
      });

      initBookingFields($modalContent);
      updateSpotsLeft();
      updateQuantityCnt();

      $modalContent.find('.Modal--BookBtn').click(function (e) {
        e.preventDefault();
        doBooking($(this));
      });
    }

    function resetDuplicateCnt() {
      $modalContent.find('form .ebp_form_duplicate_cnt').empty();
    };

    function performDuplication (isIncrement, quantity, qunatityCntIndex, quantityName) {
      if (!isIncrement) {
          $modalContent.find('form .quantityIndex' + qunatityCntIndex).last().remove();
      } else {
        var toClone = [];
        var $newFields = $duplicatableFields.clone(true);

        $newFields.each(function() {
          // update names and ids
          $(this).find('input, label, textarea, .fieldHolder').each(function() {
            var $this = $(this);
            var unique = '_EBP_UNIQUE_' + qunatityCntIndex + '_' + quantity;
            if ($this.attr('id')) $(this).attr('id', $(this).attr('id') + unique);

            if ($this.attr('name')) $(this).attr('name', $(this).attr('name') + unique);

            if ($this.attr('for')) $(this).attr('for', $(this).attr('for') + unique);

            if ($this.attr('data-name')) $(this).attr('data-name', $(this).attr('data-name') + unique);
          });
        });

        var $duplCnt = $modalContent.find('form .ebp_form_duplicate_cnt');
        if ($newFields.length > 0 && $duplCnt.length > 0) {
          var $duplHolder = $('<div class="duplHolder quantityIndex' + qunatityCntIndex + '" data-subticket="' + quantityName + '"></div>');

          var string = $duplCnt.attr('data-titletext').replace('%x%', quantity);
          //%name_group% - (%name%)%name_group%
          if ($modalContent.find('.Modal--QuantityCnt').length > 1) {
            string = string.replace(/%name%/g, quantityName).replace(/(%name_group%)/g, "");
          } else {
            string = string.replace(/%name%/g, "").replace(/(%name_group%)(?:.*%name_group%)/, "");
          }

          $duplHolder.append('<div class="EBP--DuplicateTitle">' + string + '</div>');

          $duplHolder.append($newFields);

          $duplCnt.append($duplHolder);

          initBookingFields($duplHolder);
        }
      }
    }

    $(document).on('click', '.Modal--QuantityBtns a', function(e) {
      e.preventDefault();
      updateQuantity($(this));
    });

    function getTotalQuantity() {
      var newTotalQuantity = 0;
      $modalContent.find('.Modal--QuantityBtns').each(function() {
        newTotalQuantity += parseInt($(this).find('span').html());
      });
      return newTotalQuantity;
    }
    function updateQuantity($ref) {
      var $quantityCnt = $ref.parent().parent().parent();
      var newQuantity = parseInt($quantityCnt.find('.Modal--QuantityBtns span').html());

      var isIncrement = $ref.hasClass("up");

      // stop if quantity exceeds the limit
      var newTotalQuantity = getTotalQuantity();

      if (isIncrement) {
        newQuantity ++;
        newTotalQuantity ++;
      } else {
        newQuantity--;
        newTotalQuantity --;
      }

      if (newQuantity < 0) return;

      if (newTotalQuantity <= 0) return;

      if (newTotalQuantity > parseInt($modalContent.find('.Modal--SpotsLeft span').html())) return;


      $modalContent.find('input[name="totalQuantity"]').val(newTotalQuantity);
      // perform incremental
      performDuplication(isIncrement, newQuantity, $quantityCnt.attr('data-index'), $quantityCnt.find('.nameLabel').text());


      var initPrice = EbpUtil.getFormattedNumber($quantityCnt.find('.single strong').text(), LOCALS.priceDecPoint, LOCALS.priceThousandsSep, LOCALS.currency);
      var newTotal = newQuantity * initPrice;
      newTotal = EbpUtil.formatPrice(newTotal, LOCALS.priceDecPoint, LOCALS.priceThousandsSep, LOCALS.priceDecimalCount);

      $quantityCnt.find('.Modal--QuantityBtns span').html(newQuantity);

      $quantityCnt.find('.total strong').html(newTotal);

      if (newTotalQuantity < 1) {
        activateButtons(false);
      } else {
        activateButtons(true);
      }

      calcTotal();
    }

    // checking coupon btn
    $(document).on('click', '.Modal--CouponBtn', function(e) {
      e.preventDefault();

      $modalContent.find('.Modal--CouponResult').html("checking...");
      var coupon = $modalContent.find('input[name="coupon-code"]').val();
      var id = $modalContent.find('input[name="eventID"]').val();

      $.ajax({
        type:'POST',
        url: $("input[name='ajaxlink']").val() + '/wp-admin/admin-ajax.php',
        data: 'action=ebp_check_coupon&code=' + coupon + '&event=' + id,
        error: function (error) {
          console.error(error);
        },
        success: function(response) {
          var json = $.parseJSON(response);
          switch (json.code) {
            case 'GIFT_CARD_NOT_FOUND':
            case 'GIFT_CARD_FINISHED':
            case 'COUPON_NOT_FOUND':
            case 'COUPON_EXPIRED':
              $modalContent.find('.Modal--CouponResult').attr("data-name", "");
              $modalContent.find('.Modal--CouponResult').attr("data-type", "");
              $modalContent.find('.Modal--CouponResult').attr("data-id", "");
              $modalContent.find('.Modal--CouponResult').attr("data-amount", "");
              $modalContent.find('.Modal--CouponResult').html(json.msg);
            break;
            case 'COUPON_FOUND':
            case 'GIFT_CARD_OKAY':
              $modalContent.find('.Modal--CouponResult').html(json.msg);
              $modalContent.find('.Modal--CouponResult').attr("data-name", json.coupon);
              $modalContent.find('.Modal--CouponResult').attr("data-type", json.type);
              $modalContent.find('.Modal--CouponResult').attr("data-id", json.id);
              $modalContent.find('.Modal--CouponResult').attr("data-amount", json.amount);
            break
          }
          calcTotal();
        }
      });
    });

    function calcTotal() {
      var totalAmount = 0;
      var $currentTicket = $modalContent.find('.Modal--Tickets input[name="Modal--TicketType"]');
      var moreThanOneTotal = $currentTicket.attr('data-breakdown') && $currentTicket.attr('data-breakdown') !== 'false';

      // get extra cost of non duplicate
      var selector = '.formInput[data-duplicate="false"]';
      var extraCostNoDuplicate = getExtraCostBySelector(selector);
      totalAmount += extraCostNoDuplicate;

      if (!moreThanOneTotal) {
        var initPrice = parseFloat($modalContent.find('.Modal--Tickets input[name="Modal--TicketType"]').attr('data-cost').replace("," , ""));
        totalAmount += calcTotalPerRow($modalContent.find('.Modal--QuantityCnt'), initPrice);
      } else {
        var costs = $currentTicket.attr('data-costs').split('&;');
        var i = 0;
        $modalContent.find('.Modal--QuantityCnt').each(function () {
          var initPrice = parseFloat(costs[i].replace("," , ""));
          totalAmount += calcTotalPerRow($(this), initPrice);
          i++;
        });
      }

      // calculate coupon total
      var initTotalAmount = totalAmount;
      var usingCoupon = false;
      var couponType = $modalContent.find('.Modal--CouponResult').attr("data-type");
      if (couponType === 'GIFT_CARD' || couponType === 'total') {
        var couponAmount = $modalContent.find('.Modal--CouponResult').attr("data-amount");
        if (couponAmount.indexOf("%") > -1) {
          totalAmount = totalAmount - parseFloat(couponAmount) * totalAmount/100;
        } else {
          totalAmount = totalAmount - parseFloat(couponAmount);
        }
        var couponAmountUsed = (totalAmount > 0) ? couponAmount : initTotalAmount;
        $modalContent.find('input[name="couponAmountUsed"]').val(couponAmountUsed);

        if (totalAmount < 0) {
          totalAmount = 0;
        }
        usingCoupon = true;
      } else {
        $modalContent.find('input[name="couponAmountUsed"]').val('');
      }

      var $lastTotal = (moreThanOneTotal) ? $modalContent.find('.Modal--QuantityFinalTotal') :  $modalContent.find('.Modal--QuantityCnt');

      if (usingCoupon && initTotalAmount > 0) {
        var initTotalAmountFormatted = EbpUtil.formatPriceWithCurrency(initTotalAmount, LOCALS.priceDecPoint, LOCALS.priceThousandsSep, LOCALS.priceDecimalCount, LOCALS.currencyBefore, LOCALS.currency);
        $lastTotal.find('.total .ebp-prep').html('<span class="EBP--Stripped">' + initTotalAmountFormatted + '</span>');
      } else {
        $lastTotal.find('.total .ebp-prep').html("");
      }

      var totalAmountFormatted = EbpUtil.formatPrice(totalAmount, LOCALS.priceDecPoint, LOCALS.priceThousandsSep, LOCALS.priceDecimalCount);
      var amountTaxed = calcTax(totalAmount);

      $modalContent.find('input[name="totalAmount"]').val(totalAmountFormatted);
      $modalContent.find('input[name="totalAmountTaxed"]').val(amountTaxed);

      $lastTotal.find('.total strong').html(totalAmountFormatted);
      if (moreThanOneTotal) {
        showTaxInTotal($('.Modal--QuantityFinalTotal'), amountTaxed);
      }
    }

    function calcTotalPerRow($ref, initPrice) {
      var index = $ref.attr("data-index");

      var quantity = parseInt($ref.find('.Modal--QuantityBtns span').html(),10);

      var newPrice = initPrice;
      var totalAmount = quantity * initPrice;

      // calculate coupon for single
      var usingCoupon = false;
      var couponType = $modalContent.find('.Modal--CouponResult').attr("data-type");
      if (couponType === 'single') {
        var couponAmount = $modalContent.find('.Modal--CouponResult').attr("data-amount");
        if (couponAmount.indexOf("%") > -1) {
          newPrice = initPrice - parseFloat(couponAmount) * initPrice/100;
        } else {
          newPrice = initPrice - parseFloat(couponAmount);
        }
        usingCoupon = true;
        totalAmount = quantity * newPrice;
      }

      // calculate extra cost
      var selector = '.quantityIndex' + index;
      totalAmount += getExtraCostBySelector(selector);

      // validate
      if (totalAmount < 0) totalAmount = 0;
      if (newPrice < 0) newPrice = 0;

      var totalAmountFormatted = EbpUtil.formatPrice(totalAmount, LOCALS.priceDecPoint, LOCALS.priceThousandsSep, LOCALS.priceDecimalCount);
      var newPriceFormatted = EbpUtil.formatPrice(newPrice, LOCALS.priceDecPoint, LOCALS.priceThousandsSep, LOCALS.priceDecimalCount)

      $ref.find('.single strong').html(newPriceFormatted);

      // if we have coupon, append init Price striped
      if (usingCoupon && initPrice > 0) {
        var initPriceFormatted = EbpUtil.formatPriceWithCurrency(initPrice, LOCALS.priceDecPoint, LOCALS.priceThousandsSep, LOCALS.priceDecimalCount, LOCALS.currencyBefore, LOCALS.currency);
        $ref.find('.single .ebp-prep').html('<span class="EBP--Stripped">' + initPriceFormatted + '</span>');
      } else {
        $ref.find('.single .ebp-prep').html("");
      }

      $ref.find('.total strong').html(totalAmountFormatted);

      var amountTaxed = calcTax(totalAmount);
      showTaxInTotal($ref, amountTaxed);

      return totalAmount;
    }

    function calcTax(total) {
      // calculate tax and show (if enabled)
      var taxRate = LOCALS.tax;
      var showTax = LOCALS.showTax;
      var amountTaxed = EbpUtil.formatPrice(total + total * taxRate / 100, LOCALS.priceDecPoint, LOCALS.priceThousandsSep, LOCALS.priceDecimalCount);

      return amountTaxed;
    }

    function showTaxInTotal($ref, amountTaxed) {
      if ($ref.find('.total span.taxed').length < 1) {
          $ref.find('.total span.main')
            .clone()
            .removeClass("main")
            .addClass("taxed")
            .prepend("(")
            .append(")")
            .appendTo($ref.find('.total'));
        }

        $ref.find('.total span.taxed strong').html(amountTaxed);
    }

    function getExtraCostBySelector(selector) {
      var currCost = 0;
      var totalAddedCost = 0;
      var quantity = getTotalQuantity();

      $modalContent.find('form ' + selector + ' .cd-dropdown').each(function (index, element) {
        if ($(this).find("input").val() != 'none' && $(this).find("input").attr('data-cost')) {
          currCost = parseFloat($(this).find("input").attr('data-cost'));

          if (!isNaN(currCost) && isFinite(currCost)) {
            if ($(this).find("input").attr('data-fee-type') == 'ticket') {
              currCost *= quantity;
            }

            totalAddedCost += currCost;
          }
        }
      });

      $modalContent.find('form ' + selector + ' .hasRadioButton.hasFee input[type="radio"]').each(function (index, element) {
        if ($(this).is(':checked')) {
          currCost = parseFloat($(this).attr('data-cost'));

          if ($(this).attr('data-fee-type') == 'ticket') {
            currCost *= quantity;
          }

          totalAddedCost += currCost;
        }
      });

      $modalContent.find('form ' + selector + ' .hasCheckBoxes.hasFee').each(function (index, element) {
        $(this).find('input[type="checkbox"]').each(function (index, element) {
          if ($(this).is(':checked')) {
            currCost = parseFloat($(this).attr('data-cost'));

            if ($(this).attr('data-fee-type') == 'ticket') {
              currCost *= quantity;
            }

            totalAddedCost += currCost;
          }
        });
      });

      return totalAddedCost;
    }

    // input validation
    $modalContent.find('input, textarea').focus(function(e) {
      if ($(this).val() === '') {
        $(this).removeClass("incorrect");
      }
    });

    $modalContent.find('input, textarea').blur(function(e) {
      if ($(this).val() === '') {
        $(this).removeClass("incorrect");
      }
    });


    function resetForm () {
      $modalContent.find('a.Modal--BookBtn').each(function (index, element) {
        $(this).removeClass('deactive');
      });

      $modalContent.find('form input[type="text"]').each(function (index, element) {
        $(this).val('');
      });

      $modalContent.find('form input[type="radio"]').each(function (index, element) {
        if ($(this).is(':checked'))
          $(this).prop('checked', false);
      });

      $modalContent.find('form .hasCheckBoxes').each(function(index, element) {
        $(this).find('input[type="checkbox"]').each(function(index, element) {
          if ($(this).is(':checked'))
            $(this).prop('checked', false);
        });
      });

      $modalContent.find('form textarea[type!="hidden"]').each(function(index, element) {
        $(this).val('');
      });
    }

    /**
     * Booking handling
     */
    var validateAllRequiredOkay = function () {
      var isOkay = true;

      $modalContent.find("input.isRequired").each(function (index, element) {
        if ($(this).val() === "") {
          isOkay = false;
          $(this).addClass('incorrect');
        }

        if ($(this).hasClass('email') && !EbpUtil.validateEmail($(this).val())) {
          isOkay = false;
          $(this).addClass('incorrect');
        }
      });

      $modalContent.find("textarea.isRequired").each(function (index, element) {
        if ($(this).val() === "") {
          isOkay = false;
          $(this).addClass('incorrect');
        }
      });

      $modalContent.find(".fieldHolder.isRequired").each(function (index, element) {
        if ($(this).find('.cd-dropdown').length > 0) {
           if ($(this).find(".cd-dropdown input").val() == 'none') {
              isOkay = false;
              $(this).addClass('incorrect');
           }
        } else if ($(this).find("input:checked").length < 1) {
          isOkay = false;
          $(this).addClass('incorrect');
        }
      });

      return isOkay
    }


    var extractProperName = function (name) {
      return name.replace(/_EBP_UNIQUE_.*/, '').replace(/:/g , "");
    }

    var getFieldValue = function ($elem, isGeneral) {
      value = $elem.val();
      if (isGeneral && $elem.attr('data-fee-type') === "ticket") {
        value += ' (per ticket)';
      }

      return value;
    };

    var getFieldsBySelector = function ($selector, isGeneral) {
      var fields = {};

      $selector.find('input[type="text"]').each(function (index, element) {
        var inputName = $(this).attr("name");

        if (inputName != "name"  && inputName != "firstName" && inputName != "lastName" && inputName != "email") {
          if ($(this).val() !== '') {
            fields[extractProperName($(this).attr("name"))] = $(this).val();
          }
        }
      });

      $selector.find('textarea').each(function (index, element) {
        if ($(this).val() !== '') {
          fields[extractProperName($(this).attr("name"))] = $(this).val();
        }
      });

      $selector.find('.cd-dropdown').each(function (index, element) {

        if ($(this).find("input").val() != 'none') {
          fields[extractProperName($(this).find("input").attr("name"))] = getFieldValue($(this).find("input"));
        }
      });

      $selector.find('input[type="radio"]').each(function (index, element) {
        if ($(this).is(':checked')) {
          fields[extractProperName($(this).attr("name"))] = getFieldValue($(this));
        }
      });

      $selector.find('.hasCheckBoxes').each(function (index, element) {
        if ($(this).hasClass("isTerms")) return;

        var currInputs = null;
        var tots = 0;

        $(this).find('input[type="checkbox"]').each(function (index, element) {
          if ($(this).is(':checked')) {
            if (currInputs !== null) {
              currInputs += ', ';
            } else {
              currInputs = '';
            }

            currInputs += " " + getFieldValue($(this));
            tots ++;
          }
        });

        if (tots > 0) {
          fields[extractProperName($(this).attr("data-name"))] = currInputs;
        }
      });
      return fields;
    }

    var getTicketsBreakdown = function () {
      var breakdownArr = [];
      $modalContent.find('.Modal--QuantityCnt').each(function () {
        var breakdown = {
          type: $(this).find('.nameLabel').text(),
          quantity: $(this).find('.Modal--QuantityBtns span').html(),
        }

        breakdownArr.push(breakdown);
      });

      return breakdownArr;
    }

    var getSubTicketsDetails = function () {
      var detailsArr = [];
      $modalContent.find('form .EBP--DuplicateCnt .duplHolder').each(function () {
        var dupFields = {
          subtickettype: $(this).attr('data-subticket'),
          subticketinfo: $(this).find('.EBP--DuplicateTitle').text(),
        }
        var dupDetails = getFieldsBySelector($(this), false);
        $.extend(dupFields, dupDetails);

        detailsArr.push(dupFields);
      });

      return detailsArr;
    }


    var doBooking = function($bookingBtn) {
      // just in case do final price calculations
      calcTotal();

      if ($bookingBtn.hasClass('deactive')) return;

      $modalContent.find('.incorrect').each(function (index, element) {
        $(this).removeClass('incorrect');
      });

      if (!validateAllRequiredOkay()) return false;

      activateButtons(false);

      var eventID = $modalContent.find("input[name='eventID'] ").val();
      var ticket = $modalContent.find('.Modal--Tickets input[name="Modal--TicketType"]').val();
      var dateid = $modalContent.find('.Modal--Tickets input[name="Modal--Occurrence"]').val();

      var coupon = $modalContent.find(".Modal--CouponResult").attr("data-name");
      var couponID = $modalContent.find(".Modal--CouponResult").attr("data-id");
      var couponType = $modalContent.find(".Modal--CouponResult").attr("data-type");
      var couponAmountUsed = $modalContent.find("input[name='couponAmountUsed']").val();
      if (coupon == null) coupon = "";
      if (couponID == null) couponID = "";

      var amount = EbpUtil.getFormattedNumber($modalContent.find("input[name='totalAmount']").val(), LOCALS.priceDecPoint, LOCALS.priceThousandsSep, LOCALS.currency) + "";
      var amountTaxed = EbpUtil.getFormattedNumber($modalContent.find("input[name='totalAmountTaxed']").val(), LOCALS.priceDecPoint, LOCALS.priceThousandsSep, LOCALS.currency) + "";



      var taxRate = LOCALS.tax;

      var bookerName = '';
      if ($modalContent.find('input[name="name"]').length > 0) {
        bookerName = $modalContent.find('input[name="name"]').val();
      }

      var bookerEmail = $modalContent.find('input[name="email"]').val();
      var bookQuantity = $modalContent.find('input[name="totalQuantity"]').val();
      var eventName = $modalContent.find(".Modal--Title").text();

      var bookingType = $bookingBtn.attr("data-type");

      var bookingFields = {
        general: getFieldsBySelector($modalContent.find('form > .formInput:not(.EBP--DuplicateCnt)'), true),
        breakdown: getTicketsBreakdown(),
        subTickets: getSubTicketsDetails()
      };

      var formInputes = encodeURIComponent(JSON.stringify(bookingFields));
      var currentPage = document.URL;

      var $loader = $modalContent.find('.Modal--BookingLoader');
      $loader.html($loader.attr("data-text"));
      $loader.show(200);

      if ($modaleContentScroll) {
        $modaleContentScroll.animate({
          scrollTop: $loader.offset().top - $modaleContentScroll.offset().top + $modaleContentScroll.scrollTop()
        });
      }

      $.ajax({
        type:'POST',
        url: $("input[name='ajaxlink']").val() + '/wp-admin/admin-ajax.php',
        data:'action=ebp_book_event' +
         '&bookingType=' + bookingType +
         '&coupon=' + coupon +
         '&couponID=' + couponID +
         '&couponType=' + couponType +
         '&couponAmountUsed=' + couponAmountUsed +
         '&dateid=' +  dateid +
         '&ticket=' +  ticket +
         '&eventid=' +  eventID +
         '&quantity=' + bookQuantity +
         '&amount=' + amount +
         '&taxRate=' + taxRate +
         '&amountTaxed=' + amountTaxed +
         "&eventName=" + eventName +
         '&bookingDetails=' + formInputes +
         '&bookName=' + bookerName  +
         '&bookEmail=' + bookerEmail +
         '&currentPage=' + currentPage,
        error: function(response) {
          activateButtons(true);

          $modalContent.find(".Modal--BookingLoader").html("Error Sending !");
        },
        success: function(response) {
          var json = $.parseJSON(response);

          switch (json.code) {
            case 'FORM':
              $(json.form).appendTo('body').submit();
            break;

            case 'URL':
              window.location.href = json.url;
            break;

            case 'SUCCESS':
              $modalContent.find('.Modal--Tickets .Modal--SpotsLeft span').text(($modalContent.find('.Modal--Tickets .Modal--SpotsLeft span').text() - parseInt(bookQuantity)));

              $modalContent.find('.Modal--BookingLoader').html(json.successText);

              resetForm();

              switch (json.action) {
                case 'popup':
                  setTimeout(function() {
                    setModalContent(json.popup, true, false);
                  }, 10);
                  break;

                case 'close':
                  closePopUp();
                  break;

                case 'redirect':
                  // backwards compatibility
                  if (json.url.indexOf('error&') > -1) {
                    console.error(json.url)
                    alert('An error occurred!')
                  } else {
                    window.location.href = json.url;
                  }
                  break;
              }
            break;
            case 'ERROR':
            default:
              $modalContent.find('.Modal--BookingLoader').html(json.error);
              activateButtons(true);
              console.log(json)
            break;
          }

        }
      });
    };

    /**
     * Calendar Initialization
     */
    $('.EBP--CalendarContainer').each(function(index, element) {
      var $calendar = $(this);
      var cal = $calendar.ebpFullCalendar({
        prepareEvent : prepareEvent,
        doMap: doMap,
        displayMode: $calendar.attr('data-displaymode'),
        displayWeekAbbr: $calendar.attr('data-displayWeekAbbr'),
        displayMonthAbbr: ($calendar.attr('data-displayMonthAbbr') === 'true'),
        startIn: parseInt($calendar.attr('data-startIn')),
        showSpotsLeft: ($calendar.attr('data-show-spots-left') === 'on'),
        height: $calendar.attr('data-calHeight')
      });

    });

    /**
     * WeeklyView Initialization
     */
    $('.EBP--CalendarContainer-WeeklyView' ).each(function(index, element) {
      var $weeklyCal = $(this);

      var cal = $weeklyCal.ebpWeeklyView({
        prepareEvent : prepareEvent,
        doMap: doMap,
        displayWeekAbbr: $weeklyCal.attr('data-displayWeekAbbr'),
        displayMonthAbbr: ($weeklyCal.attr('data-displayMonthAbbr') === 'true'),
        startIn: parseInt($weeklyCal.attr('data-startIn')),
        showSpotsLeft: ($weeklyCal.attr('data-show-spots-left') === 'on'),
        showBackground: ($weeklyCal.attr('data-show-background') === 'on')
      });

    });

    /**
     * byday Calendar Initialization
     */
    $('.ebpCalendarWrap').each(function(index, element) {
      var $listCalendar = $(this);
      $listCalendar.ebpDayCalendar({
        prepareEvent : prepareEvent,
        carlWidth: $listCalendar.attr('data-init-width'),
        calCat: $listCalendar.attr('data-categories'),
        displayWeekAbbr : $listCalendar.attr('data-displayWeekAbbr'),
        displayMonthAbbr : ($listCalendar.attr('data-displayMonthAbbr') === 'true'),
        startIn : parseInt($listCalendar.attr('data-startIn'))
      });
    });
  });
})(jQuery);
