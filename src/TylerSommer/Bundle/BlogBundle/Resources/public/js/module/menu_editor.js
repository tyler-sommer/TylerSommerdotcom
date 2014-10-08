(function($) {
  var _$menuForm = $(document.getElementById('menu-form')),
    _$definitions = $(document.getElementById('definitions')),
    _rowPrototype = _$menuForm.data('prototype'),
    _currentIndex = 0;

  var _getRowData = function(row) {
    var $row = $(row),
      data = [];
    $row.find('input, select').each(function(index, el) {
      data.push($(el).val());
    });

    return data;
  };

  var _setRowData = function(row, data) {
    var $row = $(row);
    $row.find('input, select').each(function(index, el) {
      $(el).val(data.shift());
    });
  };

  var _swapData = function(rowA, rowB) {
    var $rowA = $(rowA),
      $rowB = $(rowB);

    var rowAData = _getRowData($rowA),
      rowBData = _getRowData($rowB);

    _setRowData($rowA, rowBData);
    _setRowData($rowB, rowAData);
  };

  var _moveUp = function(row) {
    var $row = $(row),
      $prevRow = $row.prev('.item-row');

    if ($prevRow.size() <= 0) {
      return;
    }

    _swapData($row, $prevRow);
  };

  var _moveDown = function(row) {
    var $row = $(row),
      $nextRow = $row.next('.item-row');

    if ($nextRow.size() <= 0) {
      return;
    }

    _swapData($row, $nextRow);
  };

  var _bindRowHandlers = function(row) {
    var $row = $(row);
    $row.find('.remove').click(function() {
      $row.slideUp('fast', function() { $row.remove(); });
    });

    $row.find('.move-up').click(function() {
      _moveUp($row);
    });

    $row.find('.move-down').click(function() {
      _moveDown($row);
    });
  };

  $('.add-item', _$menuForm).click(function() {
    _addRow();
  });

  var _addRow = function() {
    var html = _rowPrototype.replace(/__name__/g, _currentIndex),
      $row = $(html).hide().appendTo(_$definitions).slideDown('fast');

    _currentIndex++;
    
    _bindRowHandlers($row);
  };

  $('.item-row', _$definitions).each(function(index, row) {
    _currentIndex++;

    _bindRowHandlers(row);
  });

  return {

  };
})(jQuery);
