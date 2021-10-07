/**
 * Login System test JavaScript
 *
 * @author Vlad Cocis
 *
 */
let myJSON = {"email":"john@example.com", "password":"johnpassword"};

const postJSON = (api) => {
 fetch(api, {
   method: 'POST',
   headers : new Headers(),
   body:JSON.stringify(myJSON)})
 .then(
   function (response) {
     return response.json();
   })
   .then(
     function(data) {
     console.log(data);
   })
   .catch(function (err) {
     console.log("Something went wrong!", err);
   }
 );
}

postJSON("http://localhost/Project/API/api/login");