// jQuery custom on class change event
// https://stackoverflow.com/a/19401707
$.fn.classChange = function(cb) {
  return $(this).each((_, el) => {
    new MutationObserver(mutations => {
      mutations.forEach(mutation => cb && cb(mutation.target, $(mutation.target).prop(mutation.attributeName)));
    }).observe(el, {
      attributes: true,
      attributeFilter: ['class'] 
    });
  });
}