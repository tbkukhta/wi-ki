/**
 * Add tooltip
 */
$(function () {
  $('.app-tooltip').tooltip();
});

/**
 * Validate #create-project-form and create project
 */
$('#create-project-form').on('beforeSubmit', function () {
  disableClicks();
  let $form = $(this);
  $.ajax({
    url: '/project/create',
    type: 'post',
    data: $form.serialize(),
    success: function (response) {
      if (response) {
        $form.yiiActiveForm('updateMessages', response, true); // Render validation messages for attributes
      }
    },
    error: function (xhr) {
      if (xhr.responseText !== '') {
        console.log(xhr.responseText);
        alert('Возникла ошибка. Подробности в консоли.');
      }
    },
    complete: function () {
      enableClicks();
    }
  });
  return false; // Cancel form submitting
});

/**
 * Clear #create-project-form after closing #addProject modal
 */
$('#addProject').on('hidden.bs.modal', function () {
  $('#create-project-form').trigger('reset');
});

/**
 * Validate #change-password-form and save password
 */
$('#change-password-form').on('beforeSubmit', function () {
  disableClicks();
  let $form = $(this);
  $.ajax({
    url: '/user/change-password',
    type: 'post',
    data: $form.serialize(),
    success: function (response) {
      if (response) {
        $form.yiiActiveForm('updateMessages', response, true); // Render validation messages for attributes
      } else {
        $form.trigger('reset');
        $('.display-success').text('Пароль успешно изменён.');
      }
    },
    error: function (xhr) {
      console.log(xhr.responseText);
      alert('Возникла ошибка. Подробности в консоли.');
    },
    complete: function () {
      enableClicks();
    }
  });
  return false; // Cancel form submitting
}).find('.btn-primary').on('click', function () {
  $('.display-success').empty();
});

/**
 * Clear #change-password-form after closing #changePassword modal
 */
$('#changePassword').on('hidden.bs.modal', function () {
  $('.display-success').empty();
  $('#change-password-form').trigger('reset');
});

/**
 * Disable clicking events, change cursor style to "wait" property
 */
function disableClicks() {
  $(document.body).css('cursor', 'wait').children(':first').css('pointer-events', 'none');
}

/**
 * Enable clicking events, remove cursor style
 */
function enableClicks() {
  $(document.body).removeAttr('style').children(':first').removeAttr('style');
}