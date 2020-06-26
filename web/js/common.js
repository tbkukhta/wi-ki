/**
 * Scroll to html block
 *
 * @param block
 */
function scrollTo(block) {
  $('html, body').animate({
    scrollTop: block.offset().top - 70
  }, 0);
}

/**
 * Disable clicks on button delete click
 * Enable clicks on cancel delete element
 *
 * @param element
 * @returns {boolean}
 */
function confirmDelete(element) {
  disableClicks();
  if (confirm('Вы уверены, что хотите удалить ' + element + '?')) {
    return true;
  } else {
    enableClicks();
    return false;
  }
}

/**
 * Disable clicks on button/link click
 */
$('.execute-btn').on('click', function () {
  disableClicks();
});

/**
 * Enable clicks if form has validation errors
 */
$('#w0').on('afterValidate', function () {
  let $hasError = $(this).find('.has-error');
  if ($hasError.length) {
    scrollTo($hasError);
    enableClicks();
  }
});