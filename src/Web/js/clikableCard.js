$(document).ready(() => {
  $(document.body).on('click', '.card-img-top[data-clickable=true]', (e) => {
    var href = $(e.currentTarget).data('href');
    window.location = href;
  });
});