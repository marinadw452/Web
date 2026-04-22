document.addEventListener("DOMContentLoaded", function() {
  const dropdown = document.querySelector(".dropdown");
  const dropbtn = dropdown.querySelector(".dropbtn");

  dropbtn.addEventListener("click", function(e) {
    e.preventDefault();
    dropdown.classList.toggle("open");
  });

  // إغلاق القائمة إذا ضغطت خارجها
  document.addEventListener("click", function(e) {
    if (!dropdown.contains(e.target)) {
      dropdown.classList.remove("open");
    }
  });

  // Sidebar login toggle
  const loginBtn = document.getElementById("login-btn");
  const sidebar = document.getElementById("sidebar-login");
  const closeBtn = document.querySelector(".close-btn");

  if(loginBtn){
    loginBtn.addEventListener("click", () => sidebar.classList.add("open"));
  }
  if(closeBtn){
    closeBtn.addEventListener("click", () => sidebar.classList.remove("open"));
  }
});
