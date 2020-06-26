/**
 * Filter comments by date
 */
let dateFrom = [], dateTo = [];
$(document).on('change', '.input-daterange', function () {
  removeAlerts();
  let $filter = $('#filter-row-' + tab);
  let fromDate = $filter.find('.from-date').val();
  let toDate = $filter.find('.to-date').val();
  if (fromDate === dateFrom[tab] && toDate === dateTo[tab]) {
    return;
  }
  dateFrom[tab] = fromDate;
  dateTo[tab] = toDate;
  if (fromDate === '' || toDate === '') {
    return;
  }
  $filter.find('select').val(0);
  displayCommentsSafely();
});

/**
 * Filter comments by author
 */
$(document).on('change', '.by-author', function () {
  removeAlerts();
  let $filter = $('#filter-row-' + tab);
  $filter.find('.by-tag').val(0);
  $filter.find('.input-daterange').datepicker('clearDates');
  displayCommentsSafely();
});

/**
 * Filter comments by tag
 */
$(document).on('change', '.by-tag', function () {
  removeAlerts();
  let $filter = $('#filter-row-' + tab);
  $filter.find('.by-author').val(0);
  $filter.find('.input-daterange').datepicker('clearDates');
  displayCommentsSafely();
});

/**
 * Clear filter
 */
$(document).on('click', '.filter-clear', function () {
  removeAlerts();
  let $filter = $('#filter-row-' + tab);
  if (
    $filter.find('.from-date').val() !== '' ||
    $filter.find('.to-date').val() !== '' ||
    $filter.find('.by-author') !== '0' ||
    $filter.find('.by-tag') !== '0'
  ) {
    $('#filter-row-' + tab + ' select').val(0);
    $('#filter-row-' + tab + ' .input-daterange').datepicker('clearDates');
    displayCommentsSafely();
  }
});

function displayCommentsSafely() {
  disableClicks();
  $.when(displayComments()).done(function () {
    enableClicks();
  });
}