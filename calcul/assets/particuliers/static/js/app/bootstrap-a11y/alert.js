// Alert Extension
// ===============================
+function ($) {
  $('.alert').attr('role', 'alert')
  //$('.close').removeAttr('aria-hidden').wrapInner('<span aria-hidden="true"></span>').append('<span class="sr-only">Close</span>')
  $(document).on('click.bs.alert.data-api', dismiss, Alert.prototype.close)
}(jQuery);
