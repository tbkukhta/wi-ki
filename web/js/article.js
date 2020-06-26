$(function () {
  let $navDev = $('#nav-dev');
  if ($navDev.length && $navDev.hasClass('active')) {
    location.hash = '#dev';
  }
});

function getArticleModel() {
  let urlParts = location.pathname.split('/');
  let articleModel;
  if (urlParts[4] !== undefined) {
    articleModel = 'topic_item';
  } else if (urlParts[3] !== undefined) {
    articleModel = 'topic';
  } else {
    articleModel = 'project';
  }
  return articleModel;
}

/* Tabs */

$('#nav-dev a').on('click', function () {
  removeAlerts();
  $('#comments-dev').removeClass('hidden');
  $('#comments-business').addClass('hidden');
  $('#nav-business').removeClass('active');
  $('#nav-dev').addClass('active');
  location.hash = '#dev';
});

$('#nav-business a').on('click', function () {
  removeAlerts();
  $('#comments-business').removeClass('hidden');
  $('#comments-dev').addClass('hidden');
  $('#nav-dev').removeClass('active');
  $('#nav-business').addClass('active');
  location.hash = '#business';
});

/* Title */

/**
 * Update title
 *
 * @param articleId
 */
function titleUpdate(articleId) {
  let $title = $('#title-box');
  removeAlerts();
  disableClicks();
  $.ajax({
    url: '/article/title-update',
    type: 'post',
    data: {
      article_id: articleId,
      article_model: getArticleModel()
    },
    success: function (data) {
      $title.children(':first').addClass('hidden');
      scrollTo($title.append(data));
      cursorNotAllowed();
      $title.css('cursor', 'auto').find('form').css('pointer-events', 'auto');
    },
    error: function (xhr) {
      console.log(xhr.responseText);
      enableClicks();
      alert('Возникла ошибка. Подробности в консоли.');
    }
  });
}

/**
 * Cancel title update
 */
function titleCancelUpdate() {
  let $title = $('#title-box');
  $title.removeAttr('style');
  $title.children(':first').removeClass('hidden');
  $title.children().slice(1).remove();
  scrollTo($title);
  enableClicks();
}

/**
 * If no changes on submit, cancel title update
 */
$(document).on('click', '#title-submit-button', function (e) {
  if ($('#article-name').text() === $('#title').val()) {
    e.preventDefault();
    titleCancelUpdate();
  }
});

/**
 * Save title
 */
$(document).on('beforeSubmit', '#title-update-form', function () {
  let $title = $('#title-box');
  $title.removeAttr('style');
  disableClicks();
  let articleModel = getArticleModel();
  let urlParts = location.pathname.split('/');
  $.ajax({
    url: '/article/title-save',
    type: 'post',
    data: {
      form: $(this).serializeArray(),
      article_model: articleModel,
      project_model: urlParts[2]
    },
    success: function (data) {
      $title.html(data['view_title']);
      let isOpen = $('.cm-submenu.open').length;
      urlParts[urlParts.length - 1] = data['slug'];
      let newUrl = urlParts.join('/');
      history.pushState(null, null, newUrl + location.hash);
      $('#menu-box').html(data['view_menu']).find('.execute-btn').on('click', function () {
        disableClicks();
      });
      $('#hide-menu-button').remove();
      $('<div id="hide-menu-button" class="btn btn-primary md-menu-white" data-toggle="cm-menu"></div>')
        .appendTo($('#hide-menu'))
        .on('click', function () {
          fixHideMenu();
        });
      $('#cm-submenu-popover').remove();
      let $script = $('script').filter(function () {
        return this.src.match(/clearmin\.min\.js/);
      });
      let src = $script.attr('src');
      $script.remove();
      document.body.appendChild(document.createElement('script')).src = src;
      document.title = data['name'];
      let $mainTitle = $('#main-title');
      let $breadcrumb = $mainTitle.find('li.active');
      if ($breadcrumb.length) {
        $breadcrumb.text(data['name']);
      } else {
        $mainTitle.text(data['name']);
      }
      $('[href = "' + newUrl + '"]').closest('li').addClass('active');
      if (articleModel !== 'project') {
        let $submenu = $('.active').closest('.cm-submenu');
        if (isOpen) {
          $submenu.addClass('open');
          fixMenu();
        } else {
          $submenu.addClass('active');
        }
      } else {
        $('#project-title').text(data['name']);
        $('#project-update a').attr('href', newUrl + '/update');
      }
      $('.cm-submenu').on('click', function () {
        fixSubMenu($(this));
      });
      setTimeout(function () {
        fixPopover();
      }, 1000);
      scrollTo($title);
      enableClicks();
    },
    error: function (xhr) {
      console.log(xhr.responseText);
      cursorNotAllowed();
      $title.css('cursor', 'auto').find('form').css('pointer-events', 'auto');
      alert('Возникла ошибка. Подробности в консоли.');
    }
  });
  return false; // Cancel form submitting.
});

/* Description */

/**
 * Update description
 *
 * @param articleId
 */
function descriptionUpdate(articleId) {
  let $description = $('#description-box');
  removeAlerts();
  disableClicks();
  $.ajax({
    url: '/article/description-update',
    type: 'post',
    data: {
      article_id: articleId,
      article_model: getArticleModel()
    },
    success: function (data) {
      $description.children(':first').addClass('hidden');
      scrollTo($description.append(data));
      cursorNotAllowed();
      $description.css('cursor', 'auto').find('form').css('pointer-events', 'auto');
    },
    error: function (xhr) {
      console.log(xhr.responseText);
      enableClicks();
      alert('Возникла ошибка. Подробности в консоли.');
    }
  });
}

/**
 * Cancel description update
 */
function descriptionCancelUpdate() {
  let $description = $('#description-box');
  $description.removeAttr('style');
  $description.children(':first').removeClass('hidden');
  $description.children().slice(1).remove();
  scrollTo($description);
  enableClicks();
}

/**
 * If no changes on submit, cancel description update
 */
$(document).on('click', '#description-submit-button', function (e) {
  let $articleDescription = $('#article-description');
  let description = $articleDescription.length ? $articleDescription.html() : '';
  if (description === CKEDITOR.instances.description.getData()) {
    e.preventDefault();
    descriptionCancelUpdate();
  }
});

/**
 * Save description
 */
$(document).on('beforeSubmit', '#description-update-form', function () {
  let $description = $('#description-box');
  $description.removeAttr('style');
  disableClicks();
  $.ajax({
    url: '/article/description-save',
    type: 'post',
    data: {
      form: $(this).serializeArray(),
      article_model: getArticleModel()
    },
    success: function (data) {
      scrollTo($description.html(data));
      enableClicks();
    },
    error: function (xhr) {
      console.log(xhr.responseText);
      cursorNotAllowed();
      $description.css('cursor', 'auto').find('form').css('pointer-events', 'auto');
      alert('Возникла ошибка. Подробности в консоли.');
    }
  });
  return false; // Cancel form submitting.
});

/* Code */

/**
 * Update code
 *
 * @param articleId
 */
function codeUpdate(articleId) {
  let $code = $('#code-box');
  removeAlerts();
  disableClicks();
  $.ajax({
    url: '/article/code-update',
    type: 'post',
    data: {
      article_id: articleId,
      article_model: getArticleModel()
    },
    success: function (data) {
      $code.children(':first').addClass('hidden');
      scrollTo($code.append(data));
      cursorNotAllowed();
      $code.css('cursor', 'auto').find('form').css('pointer-events', 'auto');
    },
    error: function (xhr) {
      console.log(xhr.responseText);
      enableClicks();
      alert('Возникла ошибка. Подробности в консоли.');
    }
  });
}

/**
 * Cancel code update
 */
function codeCancelUpdate() {
  let $code = $('#code-box');
  $code.removeAttr('style');
  $code.children(':first').removeClass('hidden');
  $code.children().slice(1).remove();
  scrollTo($code);
  enableClicks();
}

/**
 * If no changes on submit, cancel code update
 */
$(document).on('click', '#code-submit-button', function (e) {
  let $articleCode = $('#article-code');
  let code = $articleCode.length ? $articleCode.text() : '';
  if (code === $('#code').val()) {
    e.preventDefault();
    codeCancelUpdate();
  }
});

/**
 * Save code
 */
$(document).on('beforeSubmit', '#code-update-form', function () {
  let $code = $('#code-box');
  $code.removeAttr('style').find('form').removeAttr('style');
  disableClicks();
  $.ajax({
    url: '/article/code-save',
    type: 'post',
    data: {
      form: $(this).serializeArray(),
      article_model: getArticleModel()
    },
    success: function (data) {
      scrollTo($code.html(data));
      highlightCode();
      enableClicks();
    },
    error: function (xhr) {
      console.log(xhr.responseText);
      cursorNotAllowed();
      $code.css('cursor', 'auto').find('form').css('pointer-events', 'auto');
      alert('Возникла ошибка. Подробности в консоли.');
    }
  });
  return false; // Cancel form submitting.
});