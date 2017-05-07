(function($) {
  $(function() {
    $.fn.uploader = function(options) {
      var selector = $(this).selector; // Get the selector
      // Set default options
      var defaults = {
        'preview': '.preview-upload',
        'text': '.text-upload',
        'button': '.button-upload',
      };
      var options  = $.extend(defaults, options);

  	  // When the Button is clicked...
      $(options.button).click(function() {
        // Get the Text element.
        var text = $(this).siblings(options.text);
        var $remove =$(this).siblings('.removeImg');
        wp.media.editor.send.attachment = function(props, attachment) {
          // Send this value to the Text field.
          var thumb = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
          text.attr('value', attachment.url + '__and__' + thumb).trigger('change');
        }

        wp.media.editor.open($(".pagePreview"));
        return false;
      });

      $(options.text).bind('change', function() {
      	// Get the value of current object
        var url = this.value;
        // Determine the Preview field
        var preview = $(this).siblings(options.preview);
        // Bind the value to Preview field
        $(preview).attr('src', url.split('__and__')[0]);
      });
    }
  });
}(jQuery));
