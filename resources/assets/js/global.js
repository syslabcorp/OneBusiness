/**
 * @param string message
 * @param string title
 * @param boolean isReload Reload page after close dialog
 */
showAlertMessage = (message, title = "Alert", isReload = false) => {
  swal({
    title: "<div class='delete-title'>" + title + "</div>",
    text:  "<div class='delete-text'>" + message + "</strong></div>",
    html:  true,
    customClass: 'swal-wide',
    showCancelButton: false,
    closeOnConfirm: true,
    allowEscapeKey: !isReload
  }, (data) => {
    if(isReload) {
      window.location.reload()
    }
  });
}

/**
 * @param string message
 * @param string title
 * @param function callback
 */
showConfirmMessage = (message, title = "Alert", callback = () => {}) => {
  swal({
    title: "<div class='delete-title'>" + title + "</div>",
    text:  "<div class='delete-text'>" + message + "</strong></div>",
    html:  true,
    customClass: 'swal-wide',
    confirmButtonClass: 'btn-danger',
    confirmButtonText: 'Delete',
    showCancelButton: true,
    closeOnConfirm: true,
    allowEscapeKey: true
  }, (data) => {
    if(data) {
      callback()
    }
  });
}

