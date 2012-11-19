/*
 formats currency values
 */

(function( $ ) {
  $.fn.formatCurrency = function(flag, limit, callback) {
    var cformat= function (el){
      crv =el.val().trim();
      crv = (isNaN(crv) || crv === '' || crv === null ) ? 0.00 : crv;
      if (limit && crv > limit) {
        crv = limit;
      }
      el.val(parseFloat(crv).toFixed(2));
    }
    this.each(function(){
      el =$(this);
      if(!flag){cformat(el)};
      el.blur(function(){
        cformat($(this));
        if (callback) {
          callback.apply(this);
        }
      });
    });
  };
})( jQuery );

/*
 formats phone number on blur
 */
(function( $ ) {
  $.fn.formatPhone = function(){
    this.each(function(){
      $(this).blur(function(){
        el=$(this);
        value =el.val();num='';
        for (n = 0; n < value.length; n++)
          if ( !isNaN(value.charAt(n)) && value.charAt(n)!=' ') {num=num+value.charAt(n);}
        size = num.length;value='';
        value = (size>=3)? '('+num.substring(0,3)+') ':'';
        value = (size>3) ? value+num.substring(3,Math.min(size,6)):value;
        value = (size>6) ? value+'-'+num.substring(6,size):value;
        el.val(value);
      });
    });
  }
})( jQuery );
