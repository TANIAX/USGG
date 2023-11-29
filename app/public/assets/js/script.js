/**
 * Reveals elements with the "reveal" class when they are scrolled into view.
 * @function
 * @returns {void}
 */
function reveal() {
    var reveals = document.querySelectorAll(".reveal , .reveal-left , .reveal-right , .reveal-reverse");
    for (var i = 0; i < reveals.length; i++) {
        var windowHeight = window.innerHeight;
        var elementTop = reveals[i].getBoundingClientRect().top;
        var elementVisible = 150;
        if (elementTop < windowHeight - elementVisible) {
            reveals[i].classList.add("active");
        } else {
            reveals[i].classList.remove("active");
        }
    }
}

/**
 * Moves the magnet button based on the mouse position.
 * @param {Event} event - The mouse event object.
 */
function moveMagnet(event) {
    var magnetButton = event.currentTarget
    var bounding = magnetButton.getBoundingClientRect()
  
    TweenMax.to( magnetButton, 1, {
      x: ((( event.clientX - bounding.left)/magnetButton.offsetWidth) - 0.5) * strength,
      y: ((( event.clientY - bounding.top)/magnetButton.offsetHeight) - 0.5) * strength,
      ease: Power4.easeOut
    })
  }



window.addEventListener("scroll", reveal);
reveal();
var magnets = document.querySelectorAll('.magnetic')
var strength = 50
magnets.forEach( (magnet) => {
  magnet.addEventListener('mousemove', moveMagnet );
  magnet.addEventListener('mouseout', function(event) {
    TweenMax.to( event.currentTarget, 1, {x: 0, y: 0, ease: Power4.easeOut})
  } );
});