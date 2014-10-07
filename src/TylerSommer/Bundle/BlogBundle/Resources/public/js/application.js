(function($) {
  $.fn.sf2select2 = function() {
    var values = $(this).data('values') || '',
      selected = $(this).val();

    $(this).val(selected).select2({ tags: values.split(','), width: '220px' });
  };
  
  $(function() {
    $.fn.datepicker.defaults.format = "mm/dd/yyyy";
    $.fn.datepicker.defaults.autoclose = true;
    $('input.date').datepicker();
  })
})(jQuery);
