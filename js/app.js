//const x = document.getElementById("flexy");
//const flexy = document.querySelector('ul.li.flexy');
//document.body.onload = addElement; 

const button = document.querySelector('button.add');
const input = document.querySelector('input.new');
const input2 = document.querySelector('input.new2');
const flexy = document.querySelector('#flexy');
//var container = document.getElementById('flex-container');
let itemsArray = localStorage.getItem('items') ? JSON.parse(localStorage.getItem('items')) : [];

localStorage.setItem('items', JSON.stringify(itemsArray));
const data = JSON.parse(localStorage.getItem('items'));

//function addElement () { 
  // create a new div element 
  //var newDiv = document.createElement("figure"); 
  // and give it some content 
 // var newContent = document.createTextNode("Hi there and greetings!"); 
  // add the text node to the newly created div
 // newDiv.appendChild(newContent);  
    // add the newly created element and its content into the DOM 
 // var currentDiv = document.getElementById("div1"); 
 // document.body.insertBefore(newDiv, currentDiv); 
//}
//var text = "<h1>Hello World</h1>";
//const nli = document.createElement('flex-item');
//nli.textConent = 'test'
//x.appendChild(nli);

button.addEventListener('click', function(e) {
	e.preventDefault();
	itemsArray.push(input.value);
	localStorage.setItem('items', JSON.stringify(itemsArray));
 //document.body.insertBefore("figure");
  const newflexy = document.createElement("figure");
  //newflexy.className = 'flex-item';
  //newflexy.classList.add('flex-item');
 // newflexy.className="flex-item";
//  newflexy.setAttribute("class", "flex-item");

  newflexy.innerHTML =  '<a href="' + input2.value + '" target="_blank"><img src="images/googlei.png" alt="test" class="tile"> <figcaption>' + input.value + '</figcaption></a></figure></li></ul>';
  document.getElementById("flexy").appendChild(newflexy);
 // totalText.innerHTML = `(${newflexy.childElementCount} items)`;
  input.value = '';
});

//var printStorageBody = function () {
  //  $("body").html("");
   // $("<pre>")
  //  .text(JSON.stringify(localStorage, null, '\t'))
    ////.appendTo("body");