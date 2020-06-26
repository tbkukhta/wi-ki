let tab, loaded = [];

$(function () {
  if ($('.comments-box').length) {
    $(window).on('hashchange', function () {
      if (location.hash === '#business') {
        tab = 1;
      } else {
        tab = 0;
      }
      if (!loaded[tab]) {
        disableClicks();
        commentCreateInit();
        if (parseInt($('#comments-count-' + tab).text()) > 0) {
          $('#filter-row-' + tab + ' .input-daterange').datepicker({
            language: 'ru',
            format: 'dd-M-yyyy',
            todayHighlight: true,
            autoclose: true
          });
          $.when(displayComments()).done(function () {
            enableClicks();
          });
        } else {
          enableClicks();
        }
        loaded[tab] = true;
      }
    }).trigger('hashchange');
  }
});

/**
 * Generate alert block
 *
 * @param type
 * @param message
 * @returns {jQuery|HTMLElement}
 */
function renderAlert(type, message) {
  return $('<div class="alert-' + type + ' alert fade in">' +
    '<button type="button" class="close" data-dismiss="alert">×</button>' + message +
    '</div>');
}

/**
 * Display comments with pagination
 */
function displayComments() {
  let result = false;
  let $container = $('#pagination-' + tab);
  let $filter = $('#filter-row-' + tab);
  $container.pagination({
    className: 'paginationjs-theme-blue',
    pageSize: 5,
    showNavigator: true,
    formatNavigator: '<span style="color: #289de9">' +
      '<span class="element-from"></span>-<span class="element-to"></span>/<%= totalNumber %>' +
      '</span>',
    dataSource: function (done) {
      result = $.ajax({
        url: '/comment/display',
        type: 'post',
        data: {
          comment_params: {
            'article_model': getArticleModel(),
            'article_url': location.pathname,
            'article_tab': tab,
            'date_from': $filter.find('.from-date').val(),
            'date_to': $filter.find('.to-date').val(),
            'author_id': $filter.find('.by-author option:selected').val(),
            'tag_id': $filter.find('.by-tag option:selected').val()
          }
        },
        success: function (response) {
          $('#comments-count-' + tab).text(response.count);
          done(response.data);
        },
        error: function (xhr) {
          console.log(xhr.responseText);
          alert('Возникла ошибка. Подробности в консоли.');
        }
      });
    },
    callback: function (data) {
      let htmlData = '';
      $.each(data, function () {
        htmlData += this;
      });
      $('#comments-row-' + tab).html(htmlData);
    },
    afterPaging: function () {
      let $elements = $('#comments-row-' + tab).find('.comment-block');
      $container.find('.element-from').text($elements.first().data('index'));
      $container.find('.element-to').text($elements.last().data('index'));
    },
    afterInit: function () {
      if ($container.pagination('getTotalPage') < 2) {
        $container.pagination('hide');
      }
    }
  });
  return result;
}

/**
 * Create comment
 */
function commentCreateInit() {
  let $hideMe = $('#hide-me-' + tab);
  let $container = $hideMe.parent();
  let $form = $('#comment-create-form-' + tab);
  let $fileInput = $('#file-input-create-' + tab);
  $fileInput.fileinput({
    language: 'ru',
    uploadAsync: false,
    showUpload: false,
    showCancel: false,
    showClose: false,
    previewFileType: 'any',
    elErrorContainer: '<div style="display: none"></div>',
    fileActionSettings: {
      showUpload: false,
    },
    layoutTemplates: {
      progress: ''
    },
    previewZoomButtonClasses: {
      prev: 'btn btn-navigate',
      next: 'btn btn-navigate',
      toggleheader: 'hidden',
      fullscreen: 'hidden',
      borderless: 'hidden',
      close: 'btn btn-kv btn-default btn-outline-secondary'
    },
    uploadUrl: '/comment/create',
    uploadExtraData: function () {
      return {
        form: $form.serialize(),
        count: parseInt($('#comments-count-' + tab).text())
      };
    }
  }).on('filebatchuploadsuccess', function (event, data) {
    $.when(displayComments()).done(function () {
      if (!$('#filter-row-' + tab).length) {
        $('#comments-' + (tab ? 'business' : 'dev')).prepend(data.response.success.filter);
        $('#filter-row-' + tab + ' .input-daterange').datepicker({
          language: 'ru',
          format: 'dd-M-yyyy',
          todayHighlight: true,
          autoclose: true
        });
      }
      renderAlert('success', data.response.success.message).prependTo($('#comment-' + data.response.success.id + ' .comment-container'));
      $form.trigger('reset');
      scrollTo($hideMe.collapse('hide'));
      enableClicks();
    });
  }).on('filebatchuploaderror', function (event, data) {
    cursorNotAllowed();
    $container.css('pointer-events', 'auto').parent().css('cursor', 'auto');
    if (data.response.error !== undefined) {
      scrollTo(renderAlert('danger', data.response.error).prependTo($hideMe));
    } else {
      console.log(data.jqXHR.responseText);
      alert('Возникла ошибка. Подробности в консоли.');
    }
  });
  
  $('#comment-create-add-' + tab).on('click', function () {
    removeAlerts();
    scrollTo($(this));
    if ($hideMe.hasClass('in')) {
      $form.trigger('reset');
      $container.removeAttr('style').parent().removeAttr('style');
      enableClicks();
    } else {
      disableClicks();
      cursorNotAllowed();
      $container.css('pointer-events', 'auto').parent().css('cursor', 'auto');
    }
  });
  
  $form.on('afterValidate', function (event, messages, errorAttributes) {
    if (errorAttributes.length) {
      scrollTo($hideMe);
    }
  }).on('beforeSubmit', function () {
    removeAlerts();
    disableClicks();
    $container.removeAttr('style').parent().removeAttr('style');
    $fileInput.fileinput('upload'); // Upload files and create comment
    return false; // Cancel form submitting
  });
}

/**
 * Update comment
 *
 * @param commentId
 */
function commentUpdate(commentId) {
  removeAlerts();
  disableClicks();
  let $comment = $('#comment-' + commentId + ' .comment-container');
  $.ajax({
    url: '/comment/update',
    type: 'post',
    data: {
      comment_id: commentId
    },
    success: function (data) {
      $comment.children(':first').addClass('hidden');
      $comment.append(data);
      let deletedFiles = [];
      let $form = $('#comment-update-form-' + commentId);
      let $fileInput = $('#file-input-' + commentId);
      $fileInput.fileinput({
        language: 'ru',
        uploadAsync: false,
        showUpload: false,
        showCancel: false,
        showClose: false,
        previewFileType: 'any',
        elErrorContainer: '<div style="display: none"></div>',
        fileActionSettings: {
          showUpload: false,
        },
        layoutTemplates: {
          progress: ''
        },
        previewZoomButtonClasses: {
          prev: 'btn btn-navigate',
          next: 'btn btn-navigate',
          toggleheader: 'hidden',
          fullscreen: 'hidden',
          borderless: 'hidden',
          close: 'btn btn-kv btn-default btn-outline-secondary'
        },
        uploadUrl: '/comment/save',
        uploadExtraData: function () {
          return {
            form: $form.serialize(),
            deleted_files: JSON.stringify(deletedFiles)
          };
        }
      }).on('filebatchuploadsuccess', function (event, data) {
        if (data.response.unchanged !== undefined) {
          $comment.children(':first').removeClass('hidden');
          $comment.children().slice(1).remove();
          scrollTo(renderAlert('success', data.response.unchanged).prependTo($comment));
          enableClicks();
        } else {
          $.when(displayComments()).done(function () {
            scrollTo(renderAlert('success', data.response.success).prependTo($('#comment-' + commentId + ' .comment-container')));
            enableClicks();
          });
        }
      }).on('filebatchuploaderror', function (event, data) {
        cursorNotAllowed();
        $comment.css('pointer-events', 'auto').parent().css('cursor', 'auto');
        if (data.response.error !== undefined) {
          scrollTo(renderAlert('danger', data.response.error).prependTo($comment));
        } else {
          console.log(data.jqXHR.responseText);
          alert('Возникла ошибка. Подробности в консоли.');
        }
      });
      
      /**
       * Mark attachment file for deletion
       */
      $form.find('.attachment').on('click', function () {
        if (confirm('Вы уверены, что хотите удалить файл?')) {
          let $attachment = $(this);
          let $attachmentsBox = $attachment.parent();
          deletedFiles.push($attachment.attr('id'));
          $attachment.remove();
          if (!$attachmentsBox.find('a').length) {
            $attachmentsBox.remove();
          }
        }
        return false;
      });
  
      $form.on('afterValidate', function (event, messages, errorAttributes) {
        if (errorAttributes.length) {
          scrollTo($comment);
        }
      }).on('beforeSubmit', function () {
        removeAlerts();
        disableClicks();
        $comment.removeAttr('style').parent().removeAttr('style');
        $fileInput.fileinput('upload');  // Upload files and save comment
        return false; // Cancel form submitting
      });
  
      scrollTo($comment);
      cursorNotAllowed();
      $comment.css('pointer-events', 'auto').parent().css('cursor', 'auto');
    },
    error: function (xhr) {
      console.log(xhr.responseText);
      enableClicks();
      alert('Возникла ошибка. Подробности в консоли.');
    }
  });
}

/**
 * Cancel comment update
 *
 * @param commentId
 */
function commentCancelUpdate(commentId) {
  removeAlerts();
  let $comment = $('#comment-' + commentId + ' .comment-container');
  $comment.removeAttr('style').parent().removeAttr('style');
  $comment.children(':first').removeClass('hidden');
  $comment.children().slice(1).remove();
  scrollTo($comment);
  enableClicks();
}

/**
 * Delete comment
 *
 * @param commentId
 */
function commentDelete(commentId) {
  removeAlerts();
  if (confirm('Вы уверены, что хотите удалить комментарий?')) {
    disableClicks();
    let $comment = $('#comment-' + commentId + ' .comment-container');
    let nextId = $comment.next().attr('id');
    let prevId = $comment.prev().attr('id');
    $.ajax({
      url: '/comment/delete',
      type: 'post',
      data: {
        comment_id: commentId
      },
      success: function (data) {
        if (data.success !== undefined) {
          $.when(displayComments()).done(function () {
            let $comments = $('#comments-row-' + tab);
            let $alert = renderAlert('success', data.success);
            if ($comments.find('#' + nextId).length) {
              scrollTo($alert.insertBefore($comments.find('#' + nextId + ' .comment-container')));
            } else if ($comments.find('#' + prevId).length) {
              scrollTo($alert.insertAfter($comments.find('#' + prevId + ' .comment-container')));
            } else {
              scrollTo($alert.prependTo($comments));
            }
            if (!$comments.find('.comment-block').length) {
              $('#filter-row-' + tab).remove();
              dateFrom[tab] = '';
              dateTo[tab] = '';
            }
            enableClicks();
          });
        } else {
          scrollTo(renderAlert('danger', data.error).prependTo($comment));
          enableClicks();
        }
      },
      error: function (xhr) {
        console.log(xhr.responseText);
        enableClicks();
        alert('Возникла ошибка. Подробности в консоли.');
      },
    });
  }
}