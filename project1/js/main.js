

document.getElementById("btn").addEventListener("click", function(event){
    event.preventDefault();
    document.body.style.backgroundColor='yellow';

})


document.getElementById("delete").addEventListener("click", function(e) {
  e.preventDefault()
  document.body.style.backgroundColor = "white";
})



setTimeout(function(){
    document.body.style.backgroundColor='orange';
}, 5000);