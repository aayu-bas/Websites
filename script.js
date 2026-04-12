
// --------ImageSlider---------------
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}

document.getElementById("userIcon").addEventListener("click", function(){
  window.open("login.html", "_blank")
})

// --------------ProductSlider------------
function closeModal(){
  document.getElementById("cartModal").style.display="none";
}
function slide(dir,btn){
  const carousel=btn.closest('.wrapper').querySelector('.carousel');
  carousel.scrollBy({left:250*dir, behavior: 'smooth'});
}
function buy(product){
  document.getElementById("productname").innerText=product;
  document.getElementById("cartModal").style.display="block";
}

function checkout(){
  window.open("checkout.html",'_blank');
}

function result(){
  window.location.href="moreresult.html";
}
