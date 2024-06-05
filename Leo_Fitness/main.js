// document.addEventListener("DOMContentLoaded", function() {
//     document.getElementById("signupForm").addEventListener("submit", function(event) {
//         event.preventDefault();
//         SubmitData();
//     });

//     document.getElementById("followBtn").addEventListener("click", function() {
//         var socialLinks = document.getElementById("socialLinks");
//         socialLinks.style.display = socialLinks.style.display === "none" ? "block" : "none";
//     });

//     document.getElementById("emailBtn").addEventListener("click", function() {
//         var emailLink = document.getElementById("emailLink");
//         emailLink.style.display = emailLink.style.display === "none" ? "block" : "none";
//     });
// });

// function SubmitData() {
//     localStorage.clear();
//     var name = document.getElementById("Name").value;
//     localStorage.setItem("Name", name);

//     var age = document.getElementById("Age").value;
//     localStorage.setItem("Age", age);

//     var gender = document.getElementById("Gender").value;
//     localStorage.setItem("Gender", gender);

//     var email = document.getElementById("Email").value;
//     localStorage.setItem("Email", email);

//     alert("Form submitted successfully!");
// }
