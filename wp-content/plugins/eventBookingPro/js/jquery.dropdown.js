/**
 * jquery.dropdown.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Edited by Moe Haydar
 */
;(function($, window, undefined) {

	'use strict';

	$.DropDownEBP = function(options, element) {
		this.$el = $(element);
		this._init(options);
	};

	// the options
	$.DropDownEBP.defaults = {
		speed : 300,
		easing : 'ease',
		gutter : 0,
		// initial stack effect
		stack : true,
		// delay between each option animation
		delay : 0,
		// random angle and positions for the options
		random : false,
		// rotated [right||left||false] : the options will be rotated to thr right side or left side.
		// make sure to tune the transform origin in the stylesheet
		rotated : false,
		// effect to slide in the options. value is the margin to start with
		slidingIn : false,
		onOptionSelect : function(opt) { return false; }
	};

	$.DropDownEBP.prototype = {
		_init : function(options) {
			// options
			this.options = $.extend(true, {}, $.DropDownEBP.defaults, options);
			this._layout();
			this._initEvents();
		},

		_layout : function () {
			var self = this;
			this.minZIndex = 1000;
			var selected = this._transformSelect();
			this.opts = this.listopts.children('li');
			this.optsCount = this.opts.length;
			this.size = { width : this.dd.width(), height : this.dd.height() };

			var elName = this.$el.attr('name'), elId = this.$el.attr('id'),
				inputName = elName !== undefined ? elName : elId !== undefined ? elId : 'cd-dropdown-' + (new Date()).getTime();

			this.inputEl = $('<input type="hidden" name="' + inputName + '" data-cost="' + selected.cost + '" data-costs="' + selected.costs + '" data-breakdown="' + selected.breakdown + '" data-names="' + selected.names + '" value="' + selected.value + '"></input>').insertAfter(this.selectlabel);

			this.selectlabel.css('z-index', this.minZIndex + this.optsCount);
			this._positionOpts();
			if (ModernizrC.csstransitions) {
				setTimeout(function () {self.opts.css('transition', 'all ' + self.options.speed + 'ms ' + self.options.easing); }, 25);
			}

		},

		_transformSelect : function () {
			var optshtml = '', selectlabel = '', value = -1, selectedCost = 0, selectedBreakdown, selectedNames, selectedCosts;

			this.$el.children('option').each(function () {

				var $this = $(this),
					val = isNaN($this.attr('value')) ? $this.attr('value') : Number($this.attr('value')) ,
					cost = isNaN($this.attr('data-cost')) ? $this.attr('data-cost') : Number($this.attr('data-cost')),
					breakdown = $this.attr('data-breakdown'),
					names = $this.attr('data-names'),
					costs = $this.attr('data-costs'),
					allowed = isNaN($this.attr('data-allowed')) ? $this.attr('data-allowed') : Number($this.attr('data-allowed')) ,
					classes = $this.attr('class'),
					selected = $this.attr('selected'),
					label = $this.text();

				if (val !== -1) {
					var li_html = '';
					var class_html = (classes !== undefined) ? 'class="' + classes + '"' : '';

					var datas = ''

					if (allowed || allowed == 0) datas += 'data-allowed="' + allowed + '"';
					if (cost || cost >= 0) datas += 'data-cost="' + cost + '"';
					if ($this.attr('data-bookingStatus')) datas += 'data-bookingStatus="' + $this.attr('data-bookingStatus') + '"';
					if ($this.attr('data-startsTxt')) datas += 'data-startsTxt="' + $this.attr('data-startsTxt') + '"';
					if ($this.attr('data-endsTxt')) datas += 'data-endsTxt="' + $this.attr('data-endsTxt') + '"';
					if ($this.attr('data-fee-type')) datas += 'data-fee-type="' + $this.attr('data-fee-type') + '"';
					if ($this.attr('data-breakdown')) datas += 'data-breakdown="' + $this.attr('data-breakdown') + '"';
					if ($this.attr('data-names')) datas += 'data-names="' + $this.attr('data-names') + '"';
					if ($this.attr('data-costs')) datas += 'data-costs="' + $this.attr('data-costs') + '"';


					li_html = '<li data-value="' + val + '" ' + datas + '><span ' + class_html + '>' + label + '</span></li>';

					optshtml += li_html

				}

				if (selected) {
					selectlabel = label;
					value = val;
					selectedCost = cost;
					selectedBreakdown = breakdown;
					selectedNames = names;
					selectedCosts = costs;
				}

			});

			this.listopts = $('<ul/>').append(optshtml);
			this.selectlabel = $('<span/>').append(selectlabel);
			this.dd = $('<div class="cd-dropdown"/>').append(this.selectlabel, this.listopts).insertAfter(this.$el);
			this.$el.remove();

			return {
				value: value,
				cost: selectedCost,
				breakdown: selectedBreakdown,
				names: selectedNames,
				costs: selectedCosts
			};
		},

		_positionOpts : function(anim) {
			var self = this;

			this.listopts.css('height', 'auto');
			this.listopts.css('top', (self.size.height + self.options.gutter));

			this.opts
				.each(function(i) {
					$(this).css({
						zIndex : self.minZIndex + self.optsCount - 1 - i,
						top : self.options.slidingIn ? (i) * (self.size.height + self.options.gutter) : 0,
						left : 0,
						marginLeft : self.options.slidingIn ? i % 2 === 0 ? self.options.slidingIn : - self.options.slidingIn : 0,
						opacity : self.options.slidingIn ? 0 : 1,
						transform : 'none'
					});
				});

			if (!this.options.slidingIn) {
				this.opts
					.eq(this.optsCount - 1)
					.css({ top : this.options.stack ? 9 : 0, left : this.options.stack ? 4 : 0, width : this.options.stack ? this.size.width - 8 : this.size.width, transform : 'none' })
					.end()
					.eq(this.optsCount - 2)
					.css({ top : this.options.stack ? 6 : 0, left : this.options.stack ? 2 : 0, width : this.options.stack ? this.size.width - 4 : this.size.width, transform : 'none' })
					.end()
					.eq(this.optsCount - 3)
					.css({ top : this.options.stack ? 3 : 0, left : 0, transform : 'none' });
			}

		},

		_initEvents : function () {
			var self = this;

			this.selectlabel.on('mousedown.dropdown', function(event) {
				self.opened ? self.close() : self.open();
				return false;

			});

			this.opts.on('click.dropdown', function () {
				if (self.opened) {
					var opt = $(this);
					self.inputEl.val(opt.data('value'));

					var cost = isNaN(opt.attr('data-cost')) ? '' : Number(opt.attr('data-cost'));
					self.inputEl.attr('data-cost', cost);

					var dataFeeType = (opt.attr('data-fee-type')) ? opt.attr('data-fee-type') : '';
					self.inputEl.attr('data-fee-type', dataFeeType);

					var breakdown = (opt.attr('data-breakdown')) ? opt.attr('data-breakdown') : '';
					self.inputEl.attr('data-breakdown', breakdown);

					var names = (opt.attr('data-names')) ? opt.attr('data-names') : '';
					self.inputEl.attr('data-names', names);

					var costs = (opt.attr('data-costs')) ? opt.attr('data-costs') : '';
					self.inputEl.attr('data-costs', costs);


					self.selectlabel.html(opt.html());
					self.close();
					self.options.onOptionSelect(opt);
				}
			});

		},

		open : function () {
			var self = this;
			var h;
			this.dd.toggleClass('cd-active');
			var listHeight =(this.optsCount + 1) * (this.size.height + this.options.gutter);

			this.listopts.css('height', listHeight);

			if (this.listopts.height() > 450) {
				this.listopts.css('height',"450");
				this.listopts.css('overflow-y',"scroll");
				this.listopts.css('overflow-x',"hidden");
			}

			this.opts.each(function(i) {
			h = (self.size.height > 40)?self.size.height:40;
				$(this).css({
					opacity : 1,
					top : self.options.rotated ? h + self.options.gutter : (i) * (h + self.options.gutter),
					left : self.options.random ? Math.floor(Math.random() * 11 - 5) : 0,

					marginLeft : 0,
					transform : self.options.random ?
						'rotate(' + Math.floor(Math.random() * 11 - 5) + 'deg)' :
						self.options.rotated ?
							self.options.rotated === 'right' ?
								'rotate(-' + (i * 5) + 'deg)' :
								'rotate(' + (i * 5) + 'deg)'
							: 'none',
					transitionDelay : self.options.delay && ModernizrC.csstransitions ? self.options.slidingIn ? (i * self.options.delay) + 'ms' : ((self.optsCount - 1 - i) * self.options.delay) + 'ms' : 0
				});
			});
			this.opened = true;
		},

		close : function () {
			var self = this;

			this.dd.toggleClass('cd-active');
			if (this.options.delay && ModernizrC.csstransitions) {
				this.opts.each(function(i) {
					$(this).css({ 'transition-delay' : self.options.slidingIn ? ((self.optsCount - 1 - i) * self.options.delay) + 'ms' : (i * self.options.delay) + 'ms' });
				});
			}
			this._positionOpts(true);
			this.opened = false;
			this.listopts.css('overflow-y', 'default');
			this.listopts.css('overflow-x', 'default');
		}
	}

	$.fn.dropdownEBP = function(options) {
		var instance = $.data(this, 'dropdownEBP');
		if (typeof options === 'string') {
			var args = Array.prototype.slice.call(arguments, 1);
			this.each(function () {
				instance[ options ].apply(instance, args);
			});
		} else {
			this.each(function () {
				instance ? instance._init() : instance = $.data(this, 'dropdownEBP', new $.DropDownEBP(options, this));
			});
		}
		return instance;
	};

})(jQuery, window);
