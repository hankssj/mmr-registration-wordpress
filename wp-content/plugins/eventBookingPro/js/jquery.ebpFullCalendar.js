/* Event Booking Pro - WordPress Plugin
 * Get plugin at: http://iplusstd.com/item/eventBookingPro/
 * Moe Haydar
 */

(function($, window, undefined) {

	'use strict';

	$.EbpFullCalendar = function( options, element ) {
		this.$el = $(element);
		this._init(options);
	};

	// the options
	$.EbpFullCalendar.defaults = {
		weeks: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		weekabbrs: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
		monthabbrs: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
		// choose between values in options.weeks or options.weekabbrs
		displayWeekAbbr: false,
		// choose between values in options.months or options.monthabbrs
		displayMonthAbbr: false,
		// left most day in the calendar
		// 0 - Sunday, 1 - Monday, ... , 6 - Saturday
		startIn: 1,
		loadAll: false,
		openedMonth: [],
		displayMode: 'tooltip',
    showSpotsLeft: false,
    height: 400,
    prepareEvent: function () { return false;},
    doMap: function () { return false;}
	};

	$.EbpFullCalendar.prototype = {
		_init: function( options ) {
			// options
			this.options = $.extend( true, {}, $.EbpFullCalendar.defaults, options);
      this._updateTranslation();

			this.today = new Date();
			this.month = ( isNaN( this.options.month ) || this.options.month == null) ? this.today.getMonth(): this.options.month - 1;
			this.year = ( isNaN( this.options.year ) || this.options.year == null) ? this.today.getFullYear(): this.options.year;

      this.caldata = this.options.caldata || {};

      // get current month data
      this.getAndGotoMonth(this.month + 1, this.year)
      this._initEvents();


			this.initCalHeaderBtns();
      this.updateMonthYear();

      this._toolTips = [];
      this._dayEventNames = [];
		},

    _updateTranslation: function () {
      var self = this;
      $.ajax({
        type:'POST',
        url: $("input[name='ajaxlink']").val()+'/wp-admin/admin-ajax.php',
        data: 'action=get_calendar_translation',
        success: function(response) {
          var json = $.parseJSON(response);
          self.options.weeks = json.cal_weeks.split(',');
          self.options.weekabbrs = json.cal_weekabbrs.split(',');
          self.options.months = json.cal_months.split(',');
          self.options.monthabbrs = json.cal_monthabbrs.split(',');
        }
      });
    },

    _clickCallback: function($ref, id) {
      var self = this;
      if (id !== null && id > -1) {
       this._toolTips[id].qtip('hide');
      }

      self.openDay($ref, $ref.attr('data-dateids'));
    },

    _initEvents: function() {

			var self = this;
			this.$el.on('click.ebpFullCalendar', '.EBP--CalendarRow .EBP--CalendarCell--hasContent', function () {
        self._clickCallback($(this), $(this).attr('data-day-id'));
      });

      this.$el.on('click.ebpFullCalendar', '.EBP--CalendarContent', function () {
        self._clickCallback($(this), null);
      });

      this.$el.on('click.ebpFullCalendar', '.EBP--SpreadEvent', function () {
        self.openDay($(this), $(this).attr('data-dateid'));
      });


      this.$el.on('click.ebpFullCalendar', '.ebp-qtip', function () {
        var id = $(this).find(".tooltipCnt").attr('data-day-id');

        self._clickCallback(self._toolTips[id], id);
      });

		},

		initCalHeaderBtns: function() {
			var self = this;
      this.$el.parent().on( 'click.ebpFullCalendar', '.EBP--Next', function() {
        var month;
        var year = self.getYear();
        if (!self.options.loadAll) {
          year = self.getMonth() < 12 ? year: ++year;
          month = self.getMonth() < 12 ? self.getMonth()+1: 1;

          self.getAndGotoMonth(month, year);

        } else {
          self.gotoNextMonth();
          self.updateMonthYear();
        }
      });

      this.$el.parent().on( 'click.ebpFullCalendar', '.EBP--Prev', function() {
        var month;
        var year = self.getYear();

        if (!self.options.loadAll) {
          year = self.getMonth() != 1 ? year: --year;
          month = self.getMonth() != 1 ? self.getMonth()-1: 12;
          self.getAndGotoMonth(month, year);

        } else {
          self.gotoPreviousMonth();
          self.updateMonthYear();
        }
      });
		},

    getAndGotoMonth: function (month, year) {
      var self = this;

      if (!self.options.openedMonth[month + "" + year]) {
        self.$el.find(".EBP--CalendarBlocker").fadeIn(300);
        var calWidth = self.$el.attr("data-init-width");
        var calCategories = self.$el.attr("data-categories");

        var showSpotsLeft = (self.options.showSpotsLeft === true) ? '&showSpotsLeft=true' : '';

        var ajaxData = 'action=getCalData'
          + '&type=all'
          + '&month=' + month
          + '&year=' + year
          + '&width=' + calWidth
          + '&categories=' + calCategories
          + '&displaymode=' + self.options.displayMode
          + showSpotsLeft;

        $.ajax({
          type:'POST',
          url: $("input[name='ajaxlink']").val()+'/wp-admin/admin-ajax.php',
          data: ajaxData,
          success: function(response) {
            var data = $.parseJSON(response);

            self.setData(data.events);

            self.options.openedMonth[month + "" + year] = true;
            self.$el.find(".EBP--CalendarBlocker").fadeOut(300);
            self.goto(month - 1, year)
            self.updateMonthYear();
          }
        });

      } else {
        self.goto(month - 1, year)
        self.updateMonthYear();
      }
    },

    updateMonthYear: function() {
      var $month = this.$el.parent().find( '.EBP--Month' );
      var $year = this.$el.parent().find( '.EBP--Year' );
      $month.html(this.getMonthName() );
      $year.html(this.getYear());

      this.options.openedMonth[this.getMonth() + "" + this.getYear()] = true;

    },


    // on day click logic
    openDay: function ($ref, daysIds) {
      var self =  this;
      var $calendar = self.$el;

      var $eventHolder = $( '<div class="EBP--CalendarEventContent EBP--Scrollable--outer"></div>');
      var $eventsCnt = $( '<div class="calanderCnt"></div>');
      var $close = $( '<span class="eventClose"></span>');
      var $calParent = $calendar.parent().parent();

      // append or get
      $eventHolder.append($eventsCnt).insertAfter($calParent.find('.EBP--Inner'));
      $eventsCnt.append( $close);

      if ($ref.attr('data-loaded') == 'true') {
        self._displayCalendarDay($calParent);
      } else {
        var dateIds = daysIds;
        self._getAndDisplayCalendarDay($calParent, $eventsCnt, dateIds);
      }

      // remove function
      function removeElem() {
        $(this).find('.EBP--CalendarEventContent').remove();
      }
      $calParent.find('.eventClose').click(function(e) {
          TweenMax_.to($calParent.find('.EBP--CalendarEventContent'), 0.5, {css:{top:'100%',opacity:0}, ease:'Expo.easeOut',
            onComplete: removeElem, onCompleteScope: $calParent});
      });
    },

    _prepareEventToDisplay: function ($calParent) {
       var self =  this;
      $calParent.find('.EBP--CalendarEventContent h3.title').each(function(index, element) {
        $(this).before('<h4 style="margin-bottom:0;">' + $(this).text() + '</h4>');
        $(this).remove();
      });

      $calParent.find('.EBP--CalendarEventContent').scrollbar();

      $calParent.find('.EBP--CalendarEventContent').each(function(index, element) {
        self.options.prepareEvent($(this), false)
      });
    },

    _getAndDisplayCalendarDay: function ($calParent, $eventsCnt, dateIds) {
      var self =  this;
      var $loader = $( '<div class="EBP--CalendarLoaderCnt"></div>').append($calParent.find('#calendarLoader').clone());

      $eventsCnt.prepend($loader)
      // animate to show
      TweenMax_.fromTo($calParent.find('.EBP--CalendarEventContent' ), 0.5, {css:{top:'100%'}}, {css:{top:0,opacity:1}, ease:'Expo.easeOut'});

      var calWidth = self.$el.attr("data-init-width");
      var start_time = new Date().getTime();

      $.ajax({
        type:'POST',
        url: $("input[name='ajaxlink']").val()+'/wp-admin/admin-ajax.php',
        data: 'action=getCalDayData&dateIds=' + dateIds + '&width=' + calWidth,
        success: function(response) {
          var data = $.parseJSON(response);
          $loader.remove();

          $eventsCnt.prepend(data.html);
          self._prepareEventToDisplay($calParent);

          var request_time = new Date().getTime() - start_time;
          var delay = Math.max(500 - request_time, 0);

          self._eventDoMap($calParent, delay);

          // maybe add the html to cache
        }
      });
    },


    _displayCalendarDay: function ($calParent) {
      var self =  this;
      self._prepareEventToDisplay($calParent);

      // animate to show
      TweenMax_.fromTo($calParent.find('.EBP--CalendarEventContent' ), 0.5, {css:{top:'100%'}}, {css:{top:0,opacity:1}, ease:'Expo.easeOut'});

      // do map after tween
      self._eventDoMap($calParent, 500);
    },

    _eventDoMap: function ($calParent, delay) {
      var self =  this;
      setTimeout(function() {
        $calParent.find('.EBP--CalendarEventContent .map_canvas').each(function(index, element) {
          self.options.doMap(this, $(this).attr('data-address'), $(this).attr('data-maptype'), parseInt($(this).attr('data-zoom'), 10), $(this).attr('data-addressType'), true);
        });

      }, delay);
    },

		_renderCalendar: function (callback) {

      this.$cal = $( '<div class="EBP--Calendar">' );
      this.$el.find('.EBP--Calendar' ).remove().end().append(this.$cal);

      this._renderHead();
      this._renderBody();

      var rowClass = null;
      switch (this.rowTotal) {
        case 4: rowClass = 'EBP--Calendar-four-rows'; break;
        case 5: rowClass = 'EBP--Calendar-five-rows'; break;
        case 6: rowClass = 'EBP--Calendar-six-rows'; break;
      }
      if (rowClass !== null) {
        this.$cal.addClass(rowClass);
      }

			if (callback) {
        callback.call();
      }
		},

		_renderHead: function() {
			var html = '<div class="EBP--CalendarHead">';

			for (var i = 0; i <= 6; i++) {
				var pos = i + this.options.startIn;
				var j = pos > 6 ? pos - 6 - 1: pos;

				html += '<div>';
				html += this.options.displayWeekAbbr ? this.options.weekabbrs[ j ]: this.options.weeks[ j ];
				html += '</div>';
			}

			html += '</div>';

			this.$cal.append(html);
		},

		_renderBody: function() {
      var self = this;
      var isSpread = self.options.displayMode === 'show_spread';
			var d = new Date( this.year, this.month + 1, 0);
			var monthLength = d.getDate();
		  var firstDay = new Date( this.year, this.month, 1);
      var showSpotsLeft = self.options.showSpotsLeft;
      self._dayEventNames = [];

			this.startingDay = firstDay.getDay();

      var $fcBody = jQuery('<div class="EBP--CalendarBody"></div>');

		  var day = 1;

      function eventComparer(a,b) {
        if (a.days < b.days)
          return 1;
        if (a.days > b.days)
          return -1;
        return 0;
      }

      var weeksContentArr = [[]];

			// this loop is for weeks (rows)
			for (var i = 0; i < 7; i++) {
        var $fcRow = jQuery('<div class="EBP--CalendarRow"></div>');
        var $fcRowEvents = jQuery('<div class="EBP--CalendarRow-events"></div>');

        $fcBody.append($fcRow);

        var rowCols = [0,0,0,0,0,0,0];
        var spreadEventsArr = [];
        var nextWeekContentArr = [];
        weeksContentArr.push(nextWeekContentArr);
        var prevWeekContent = (i > 0) ? weeksContentArr[i] : [];

        // this loop is for weekdays (cells)
				for (var j = 0; j <= 6; j++) {
          var spreadEvents = [];
          spreadEventsArr.push(spreadEvents);
					var pos = this.startingDay - this.options.startIn;
          var p = (pos < 0) ? (6 + pos + 1) : pos;
          var $fcDayCell = null;
          var today = this.month === this.today.getMonth() && this.year === this.today.getFullYear() && day === this.today.getDate();
          var content = null;

          var name;
          var names = [];
          var namesId;

          var dateIdsArr = [];
          var dateIds = ''
          var background = '';

					if (day <= monthLength && ( i > 0 || j >= p)) {
						$fcDayCell = jQuery('<span class="EBP--CalendarCellDate">' + day + '</span>');

						// this day is:
						var strdate = ( this.month + 1 < 10 ? '0' + ( this.month + 1 ): this.month + 1 ) + '-' + ( day < 10 ? '0' + day: day ) + '-' + this.year;


            if (this.caldata[strdate]) {
              // sort event by duration
              content = $.extend(true, [], this.caldata[strdate]).sort(eventComparer);
              // if first day of week, add continuation of events
              if (j === 0 && isSpread) {
                prevWeekContent.forEach(function(prevEvent) {
                  content.unshift(prevEvent);
                });
              }
            } else {
              if (j === 0 && prevWeekContent.length > 0 && isSpread) {
                content = prevWeekContent;
              }
            }

            if (content) {
              for (var e = 0; e < content.length; e++) {
                var currentEvent = content[e];

                if (currentEvent.fromPreviousWeeks === 'true') {
                  currentEvent.fromPreviousWeeks = true
                }

                // extracts name
                name = currentEvent.name;
                if (showSpotsLeft) {
                  name += ' <em class="EBP--CalendarSpots">(' + currentEvent.spots + ')</em>'
                }
                names.push(name);

                // extracts dateIds
                dateIdsArr.push(currentEvent.dateId);

                // extract backgrounds
                // we will take first background (todo: do loop in future)
                if (background === '' && currentEvent.background && currentEvent.fromPreviousWeeks !== true) background = currentEvent.background;

                if (isSpread) {
                  var extraClass = '';

                  var width = 100;
                  var days = parseInt(currentEvent.days);

                  var possible = days;
                  var leftDays = 7 - j;
                  if (day + leftDays > monthLength) {
                    leftDays = monthLength - day + 1;
                  }

                  if (days > leftDays) {
                    possible = leftDays;
                  }

                  width = 100 * possible - 6;

                  if (currentEvent.fromPreviousWeeks === true) {
                    extraClass += ' EBP--SpreadEvent--Continuation';
                    width += 6;
                  }

                  if (days <= leftDays) {
                    width -= 6;
                  } else {
                    extraClass += ' EBP--SpreadEvent--Continues'
                    var copiedEvent = jQuery.extend({}, currentEvent)
                    copiedEvent.days -= possible;
                    copiedEvent.fromPreviousWeeks = true;
                    nextWeekContentArr.push(copiedEvent);
                  }

                  for(var k = 1; k < possible; k++) {
                    rowCols[j+k]++;
                  }
                  var spreadEvent = '<div class="EBP--SpreadEvent '+extraClass+'" data-dateid="'+currentEvent.dateId+'" style="width:'+width+'%;">' + name +'</div>';
                  spreadEvents.push(spreadEvent);
                }
              };
            }

						day++;
					} else {
						today = false;
					}

					var cellClasses = today ? 'EBP--CalendarCellToday ': '';
					if (content) {
						cellClasses += 'EBP--CalendarCell--hasContent';
					}

          var dataAtt = 'data-loaded="false"';

          namesId = self._dayEventNames.push(names) - 1;
          if (namesId >= 0) {
            dataAtt += ' data-names-index="' + namesId + '"';
          }

          if (dateIdsArr.length > 0) {
            dataAtt += ' data-dateids="' + dateIdsArr + '"';
          }

        	var dayHtml = cellClasses !== ''
            ? '<div class="EBP--CalendarCell ' + cellClasses + '" title="" ' + dataAtt + '></div>'
            : '<div class="EBP--CalendarCell"></div>';

          var $day = jQuery(dayHtml);

          if (background !== '') {
            $day.css('background', 'url(' + background + ') center center');
          }

          if ($fcDayCell !== null) {
            $day.append($fcDayCell);
          }

          $fcRow.append($day);

          if (self.options.displayMode === 'show_directly') {
            var $dayContent = jQuery('<div class="EBP--CalendarCell"></div>');
            if (content) {
              var $dayContentWrapper = jQuery('<div class="EBP--CalendarContent" ' + dataAtt + '></div>');
              $dayContent.append($dayContentWrapper);

              var datContentEvents = '';
              names.forEach(function (title) {
                datContentEvents += '<div class="EBP--CellEvent">' + title +'</div>';
              });
              $dayContentWrapper.html(datContentEvents);
            }

            $fcRowEvents.append($dayContent);
          }
				}

        if (self.options.displayMode === 'show_spread') {
          for (var k = spreadEventsArr.length - 1; k >= 0; k--) {
            var spreadEvents = spreadEventsArr[k];

            var $rowContent = jQuery('<div class="EBP--CalendarCell EBP--CalendarCell--Spread"></div>');
            $fcRowEvents.append($rowContent);

            if (spreadEvents.length > 0) {
              var $dayContentWrapper = jQuery('<div class="EBP--CalendarSpreadWrapper"></div>');
              $rowContent.append($dayContentWrapper);
              spreadEvents.forEach(function(spreadEvent) {
                $dayContentWrapper.append(spreadEvent);
              });
              for(var l=0; l <rowCols[k]; l++) {
                $dayContentWrapper.prepend('<div class="EBP--SpreadEvent EBP--SpreadEvent--Empty"></div>');
              }
              $rowContent.append($dayContentWrapper);
            }

          };


          $fcRow.append($fcRowEvents);
        }

        if (self.options.displayMode === 'show_directly') {
          $fcRow.append($fcRowEvents);
        }

				// stop making rows if we've run out of days
				if (day > monthLength) {
					this.rowTotal = i + 1;
					break;
				}
			}

			this.$cal.append($fcBody);


      if (this.options.displayMode == 'tooltip') {
        this.updateTitlesWithTooltips();
      }
		},


    updateTitlesWithTooltips:function () {
      var self = this;

      if (self.$el.find('.ebptooltips').length > 0) {
        self._cleanToolTips();
      }
      self._toolTips = [];

      self.$el.append('<div class="ebptooltips"></div>');

      var tooltipLevel = 0;
      $(".EBP--CalendarCell--hasContent").each(function(index, element) {

        var $fcContent = $(this);
        var tooltipContent = '<div class="tooltipCnt" data-day-id="'+tooltipLevel+'" data-dateids="'+ $(this).attr('data-dateids') +'">';
        $(this).attr("data-day-id", tooltipLevel)

        self._dayEventNames[$(this).attr('data-names-index')].forEach(function (title) {
          tooltipContent += '<div class="EBP--CellEvent">' + title +'</div>';
        });

        tooltipContent += '</div>';

        var tooltip = $(this).qtip({
          style: {
            classes: 'ebp-qtip',
            tip: {
             border: 0,
             width: 19,
             height: 9
            }
          },
          content: {
            text: tooltipContent
          },
          show: {
            effect: function(offset) {
              $(this).fadeIn(300);
            }
          },
          hide: {
            fixed: true,
            effect: function(offset) {
              $(this).fadeOut(300);
            }
          },
          position: {
            target: $fcContent,
            my: 'bottom center',
            at: 'bottom center',
            adjust: {
              y: -10
            },

            container: self.$el.find('.ebptooltips')
          }
        });
        self._toolTips.push(tooltip);
        tooltipLevel++;
      });
    },

    _cleanToolTips: function () {
      this._toolTips.forEach(function (localTooltip){
        localTooltip.qtip('destroy');
      });
    },

		// based on http://stackoverflow.com/a/8390325/989439
		_isValidDate: function( date ) {

			date = date.replace(/-/gi,'');
			var month = parseInt( date.substring( 0, 2 ), 10 ),
				day = parseInt( date.substring( 2, 4 ), 10 ),
				year = parseInt( date.substring( 4, 8 ), 10 );

			if ( ( month < 1 ) || ( month > 12 ) ) {
				return false;
			}
			else if ( ( day < 1 ) || ( day > 31 ) )  {
				return false;
			}
			else if ( ( ( month == 4 ) || ( month == 6 ) || ( month == 9 ) || ( month == 11 ) ) && ( day > 30 ) )  {
				return false;
			}
			else if ( ( month == 2 ) && ( ( ( year % 400 ) == 0) || ( ( year % 4 ) == 0 ) ) && ( ( year % 100 ) != 0 ) && ( day > 29 ) )  {
				return false;
			}
			else if ( ( month == 2 ) && ( ( year % 100 ) == 0 ) && ( day > 29 ) )  {
				return false;
			}

			return {
				day: day,
				month: month,
				year: year
			};
		},

		_move: function( period, dir, callback ) {
			if (dir === 'previous') {
				if ( period === 'month') {
					this.year = this.month > 0 ? this.year: --this.year;
					this.month = this.month > 0 ? --this.month: 11;
				} else if ( period === 'year') {
					this.year = --this.year;
				}
			} else if ( dir === 'next') {

        if ( period === 'month') {
					this.year = this.month < 11 ? this.year: ++this.year;
					this.month = this.month < 11 ? ++this.month: 0;
				} else if ( period === 'year') {
					this.year = ++this.year;
				}
			}

			this._renderCalendar(callback);
		},
		/*************************
		******PUBLIC METHODS *****
		**************************/
		getYear: function() {
			return this.year;
		},

		getMonth: function() {
			return this.month + 1;
		},

		getMonthName: function() {
			return this.options.displayMonthAbbr ? this.options.monthabbrs[this.month]: this.options.months[this.month];
		},

		// gets the cell's content div associated to a day of the current displayed month
		// day: 1 - [28||29||30||31]
		getCell: function( day ) {

			var row = Math.floor( ( day + this.startingDay - this.options.startIn ) / 7 );
			var pos = day + this.startingDay - this.options.startIn - ( row * 7 ) - 1;

			return this.$cal.find('div.EBP--CalendarBody').children('.EBP--CalendarRow').eq(row).children('div').eq(pos).children('div');
		},

		setData: function( caldata ) {
			caldata = caldata || {};
			$.extend(this.caldata, caldata);
		},

		// goes to today's month/year
		gotoNow: function(callback) {
			this.month = this.today.getMonth();
			this.year = this.today.getFullYear();
			this._renderCalendar(callback);
		},

		// goes to month/year
		goto: function(month, year, callback) {
			this.month = month;
			this.year = year;
			this._renderCalendar(callback);
		},

		gotoPreviousMonth: function(callback) {
			this._move( 'month', 'previous', callback);
		},

		gotoPreviousYear: function(callback) {
			this._move( 'year', 'previous', callback);
		},

		gotoNextMonth: function(callback) {
			this._move( 'month', 'next', callback);
		},

		gotoNextYear: function(callback) {
			this._move( 'year', 'next', callback);
		}
	};

	var logError = function( message ) {
		if (window.console) {
			window.console.error(message);
		}
	};

	$.fn.ebpFullCalendar = function(options) {
		var instance = $.data( this, 'ebpFullCalendar' );

		if (typeof options === 'string') {
			var args = Array.prototype.slice.call( arguments, 1 );

			this.each(function() {
				if (!instance) {
					logError("cannot call methods on ebpFullCalendar prior to initialization; " +
					"attempted to call method '" + options + "'");

          return;
				}

				if (!$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {
					logError("no such method '" + options + "' for ebpFullCalendar instance");

          return;
				}

				instance[options].apply(instance, args);
			});
		} else {
			this.each(function() {
				if (instance) {
					instance._init();
				} else {
					instance = $.data(this, 'ebpFullCalendar', new $.EbpFullCalendar(options, this));
				}
			});
		}

		return instance;
	};

})(jQuery, window);
