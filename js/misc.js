$('.carousel').flickity({
  	// options
  	cellAlign: 'left',
  	contain: true,
  	fullscreen: true,
	lazyLoad: 2
});

// jQuery
$('.grid').packery({
	itemSelector: '.grid-item',
	gutter: 6,
	columnWidth: 68
});

// Material Select Initialization
$(document).ready(function() {
$('.mdb-select').materialSelect();
});

(function() {
'use strict';
window.addEventListener('load', function() {
// Fetch all the forms we want to apply custom Bootstrap validation styles to
var forms = document.getElementsByClassName('needs-validation');
// Loop over them and prevent submission
var validation = Array.prototype.filter.call(forms, function(form) {
form.addEventListener('submit', function(event) {
if (form.checkValidity() === false) {
event.preventDefault();
event.stopPropagation();
}
form.classList.add('was-validated');
}, false);
});
}, false);
})();