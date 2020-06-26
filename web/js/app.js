$(function () {
  fixMenu();
  fixPopover();
  highlightCode();
});

$('#hide-menu-button').on('click', function () {
  fixHideMenu();
});

$('.cm-submenu').on('click', function () {
  fixSubMenu($(this));
});

/**
 * Fix hide menu
 */
function fixHideMenu() {
  let $submenuOpen = $('.cm-submenu.open');
  if ($submenuOpen.length) {
    setTimeout(function() {
      $submenuOpen.addClass('open');
      fixMenu();
    }, 1);
  }
}

/**
 * Fix submenu
 *
 * @param $submenu
 */
function fixSubMenu($submenu) {
  if (!$('.cm-menu-toggled').length) {
    let hasActive = $submenu.find('.active').length;
    if (hasActive) {
      if ($submenu.hasClass('open')) {
        $submenu.addClass('active');
      } else {
        $submenu.removeClass('active');
      }
    } else {
      let $active = $('.cm-submenu .active');
      if ($active.length) {
        $active.closest('.cm-submenu').addClass('active');
      }
    }
  } else {
    setTimeout(function () {
      $('.popover-content a').on('click', function () {
        disableClicks();
      });
    }, 1);
  }
}

/**
 * Fix menu
 */
function fixMenu() {
  let $opens = $('.open');
  $opens.nextAll().css('transform', 'translateY(' + $opens.children('ul').height() + 'px)');
}

/**
 * Fix Popover
 */
function fixPopover() {
  $('.cm-popover').css('top', '-50px');
}

/**
 * Highlight code
 */
function highlightCode() {
  $('pre > code').each(function () {
    hljs.highlightBlock(this);
  });
}

/**
 * Remove alert blocks if present
 */
function removeAlerts() {
  let $alerts = $('.alert');
  if ($alerts.length) {
    $alerts.remove();
  }
}

/**
 * Initialize CKEditor
 */
function initCkeditor() {
  CKEDITOR.replace('description', {
    language: 'ru',
    filebrowserBrowseUrl: '/elfinder/ckeditor'
  });
}

/**
 * Disable clicking events, change cursor style to "wait" property
 */
function disableClicks() {
  $(document.body).children().slice(0, 3).css('cursor', 'wait').children().css('pointer-events', 'none');
}

/**
 * Enable clicking events, remove cursor style
 */
function enableClicks() {
  $(document.body).children().slice(0, 3).removeAttr('style').children().removeAttr('style');
}

/**
 * Change cursor style to "not-allowed" property
 */
function cursorNotAllowed() {
  $(document.body).children().slice(0, 3).css('cursor', 'not-allowed');
}