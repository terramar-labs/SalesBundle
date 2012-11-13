var terramar = $.extend(terramar || {}, (function($) {

  return {
    getRoute: function getRoute(route, replacements) {
      for (var key in replacements) {
        route = route.replace(key, replacements[key]);
      }

      return route;
    },

    showAlert: function(selector, message, type) {
      $(selector).removeClass('success error').addClass(type).html(message).fadeIn('slow');
    },

    jsonRequest: function(url, data, success) {
      $.ajax({
        type: 'post',
        dataType: 'json',
        url: url,
        data: data,
        success: success || terramar.handleJsonResponse
      });
    },

    handleJsonResponse: function(response) {
      if (response.type == 'success') {
        if (response.reload) {
          window.location = window.location.href;
        } else if (response.redirect) {
          window.location = response.redirect;
        }

        return;
      }

      if (response.validations) {
        for (var e in response.validations) {
          $('#' + e).addClass('red');
        }
      }

      if (response.updates) {
        for (var key in response.updates) {
          $('#' + key).val(response.updates[key]);
        }
      }

      terramar.showAlert(response.location, response.message, response.type);
    }
  };
})(jQuery));
