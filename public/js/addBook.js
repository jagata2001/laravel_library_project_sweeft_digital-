const authors = document.querySelectorAll(".author-data p");
const searchText = document.querySelector("#searchText");
const searchButton = document.querySelector("#searchButton");


const filterAuthors = ()=>{
  const authorNameSurname = searchText.value;
  if(authorNameSurname.length < 2){
    for(const author of authors){
      author.parentElement.style.display = "";
    }
    return;
  };

  for(const author of authors){
    if(!author.textContent.startsWith(authorNameSurname)){
      author.parentElement.style.display = "none";
    }else{
      author.parentElement.style.display = "";
    }
  }
}
searchText.addEventListener("keyup", filterAuthors);
searchButton.addEventListener("click",filterAuthors);
