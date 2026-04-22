@import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Tajawal', sans-serif;
  background: #fdf9f3;
  color: #3e2723;
  direction: rtl;
  overflow-x: hidden;
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 20px;
}

/* User Avatar & Menu */
#auth-section {
  position: relative;
  display: flex;
  align-items: center;
  margin-right: auto;
  margin-left: 0;
  padding-left: 15px;
  order: 1;
  flex-shrink: 0;
  z-index: 1000;
  height: 100%;
  align-items: center;
}

.user-avatar-container {
  position: relative;
  display: inline-block;
  height: 100%;
  display: flex;
  align-items: center;
}

.user-avatar {
  cursor: pointer;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  transition: all 0.3s ease;
  background: rgba(0, 0, 0, 0.03);
  z-index: 1001;
}

.avatar-circle {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 16px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  border: 2px solid rgba(255, 255, 255, 0.5);
}

/* تأثير النبض الخفيف */
@keyframes pulse {
  0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.2); }
  70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(0, 0, 0, 0); }
  100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 0, 0, 0); }
}

.avatar-circle.pulse {
  animation: pulse 2s infinite;
}

/* تأثير التحويم */
.avatar-circle:hover {
  transform: scale(1.1) rotate(5deg);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

/* تأثير الدوران عند التحويم */
.avatar-circle.spin:hover {
  animation: spin 0.5s ease;
}

@keyframes spin {
  from { transform: rotate(0deg) scale(1.1); }
  to { transform: rotate(360deg) scale(1.1); }
}

/* تأثير اللمسة النهائية */
.avatar-circle::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: 50%;
  box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.3);
  transition: all 0.3s ease;
}

.avatar-circle:hover::after {
  box-shadow: inset 0 0 15px rgba(255, 255, 255, 0.5);
}

.user-avatar:hover .avatar-circle {
  transform: scale(1.1);
}

.user-menu {
  display: none;
  position: absolute;
  top: 100%;
  right: 0;
  background: white;
  border-radius: 12px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  padding: 10px 0;
  min-width: 200px;
  z-index: 1002;
  text-align: right;
  border: 1px solid rgba(0, 0, 0, 0.1);
  margin-top: 10px;
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all 0.3s ease;
}

.user-avatar-container:hover .user-menu {
  display: block;
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.user-avatar:hover .user-menu {
  opacity: 1;
  transform: translateY(0);
}

.user-menu:hover {
  display: block;
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.user-menu p {
  padding: 10px 20px;
  margin: 0;
  font-weight: 600;
  color: #3e2723;
  border-bottom: 1px solid #f0f0f0;
  white-space: nowrap;
  font-size: 14px;
}

.user-menu a {
  display: block;
  padding: 10px 20px;
  color: #5d4037;
  text-decoration: none;
  transition: all 0.2s ease;
  font-size: 14px;
}

.user-menu a:hover {
  background-color: #f8f8f8;
  color: #ba7d37;
  padding-right: 25px;
}

.fav-btn {
  border: 1px solid rgba(62,39,35,0.12);
  background: #fff;
  color: #3e2723;
  border-radius: 999px;
  padding: 10px 12px;
  cursor: pointer;
  font-weight: 900;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.fav-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(62,39,35,0.10);
}

.fav-btn.is-active {
  background: linear-gradient(135deg, #ffb74d, #ff9800);
  border-color: rgba(186,125,55,0.25);
}

.login-link {
  background: #ffb74d;
  color: #3e2723;
  padding: 8px 20px;
  border-radius: 20px;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s;
}

.login-link:hover {
  background: #ffa726;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Navbar */
.navbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 80px;
  background: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  z-index: 1000;
  box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
}

.nav-container {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 50px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 100%;
  position: relative;
  gap: 20px;
}

.cart-link {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  flex-shrink: 0;
  order: 1;
  padding: 0;
  transform: translateY(7px);
}

.cart-link:hover {
  transform: none;
  background: transparent;
  box-shadow: none;
}

.cart-icon {
  width: 86px;
  height: 86px;
  display: block;
  object-fit: contain;
}

.cart-badge {
  position: absolute;
  top: -8px;
  left: -8px;
  min-width: 22px;
  height: 22px;
  padding: 0 6px;
  border-radius: 999px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 900;
  font-size: 12px;
  background: linear-gradient(135deg, #ffb74d, #ff9800);
  color: #3e2723;
  border: 2px solid rgba(255, 255, 255, 0.95);
  box-shadow: 0 10px 20px rgba(0,0,0,0.12);
}

.nav-center {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  order: 2;
  margin: 0 auto;
  max-width: 900px;
  height: 100%;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 50px;
  align-items: center;
  margin: 0;
  padding: 0;
  height: 100%;
}

.nav-links a {
  text-decoration: none;
  color: #5d4037;
  font-weight: 600;
  font-size: 16px;
  transition: all 0.3s ease;
  position: relative;
  padding: 10px 0;
}

.nav-links a:hover,
.nav-links a.active {
  color: #ff9800;
}

.nav-links a::after {
  content: '';
  position: absolute;
  bottom: 0;
  right: 0;
  width: 0;
  height: 2px;
  bottom: -6px;
  right: 0;
  background: #ffb74d;
  transition: width 0.3s;
}

.nav-links a:hover {
  color: #ba7d37;
}

.nav-links a:hover::after {
  width: 100%;
  left: 0;
  right: auto;
}

.nav-links a.active {
  color: #ba7d37;
  font-weight: bold;
}

/* شريط الزخرفة التراثية */
.heritage-divider {
  text-align: center;
  padding: 30px 0;
  background: linear-gradient(135deg, #fff9f3, #fdf9f3);
}

.divider-img {
  height: 40px;
  width: auto;
  opacity: 0.8;
  transition: all 0.3s ease;
}

.divider-img:hover {
  opacity: 1;
  transform: scale(1.05);
}

@media (max-width: 768px) {
  .heritage-divider {
    padding: 20px 0;
  }
  
  .divider-img {
    height: 30px;
  }
}

/* اللوجو */
.logo-link {
  display: block;
  transition: all 0.3s ease;
  margin-left: auto;
  margin-right: 0;
  order: 3;
  flex-shrink: 0;
}

.logo {
  height: 68px;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.logo-link:hover .logo {
  transform: scale(1.08);
  filter: drop-shadow(0 8px 20px rgba(255, 183, 77, 0.5));
}

/* زر تسجيل الدخول */
#login-btn {
  background: #ffb74d;
  color: #3e2723;
  border: none;
  padding: 10px 26px;
  border-radius: 50px;
  font-weight: bold;
  font-size: 15px;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(255, 183, 77, 0.4);
  transition: all 0.3s;
}

#login-btn:hover {
  background: #ffcc80;
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(255, 183, 77, 0.5);
}

/* أيقونة المستخدم */
.user-icon {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: #ffb74d;
  color: #3e2723;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 18px;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(255, 183, 77, 0.4);
}

.user-icon + .user-menu {
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  min-width: 180px;
  text-align: center;
  display: none;
  z-index: 100;
}

.user-icon:hover + .user-menu,
.user-icon + .user-menu:hover {
  display: block;
}

.user-icon + .user-menu p {
  padding: 12px;
  border-bottom: 1px solid #eee;
  font-weight: bold;
}

.user-icon + .user-menu a {
  display: block;
  padding: 12px;
  color: #5d4037;
  text-decoration: none;
}

.user-icon + .user-menu a:hover {
  background: #fdf9f3;
  color: #ba7d37;
}

/* تصميم بطاقات المنتجات المحسّن */
.product-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.08);
  overflow: hidden;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  border: 1px solid rgba(186, 125, 55, 0.1);
}

.product-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 20px 50px rgba(186, 125, 55, 0.25);
  border-color: rgba(186, 125, 55, 0.3);
}

.card-image {
  position: relative;
  overflow: hidden;
  height: 280px;
}

.card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.product-card:hover .card-image img {
  transform: scale(1.15) rotate(1deg);
  filter: brightness(1.05);
}

.card-image .badge {
  position: absolute;
  top: 15px;
  left: 15px;
  background: linear-gradient(135deg, #ffb74d, #ff9800);
  color: #3e2723;
  padding: 8px 16px;
  border-radius: 25px;
  font-weight: bold;
  font-size: 0.85rem;
  box-shadow: 0 4px 15px rgba(255, 183, 77, 0.4);
  z-index: 2;
}

.card-content {
  padding: 20px;
  text-align: center;
}

.card-content h3 {
  color: #3e2723;
  font-size: 1.2rem;
  margin-bottom: 12px;
  font-weight: 700;
  line-height: 1.4;
  transition: color 0.3s;
}

.card-content h3:hover {
  color: #ba7d37;
}

.card-content .price {
  color: #ba7d37;
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.card-content .price span {
  text-decoration: line-through;
  color: #999;
  font-size: 1rem;
  font-weight: normal;
}

.card-content .actions {
  display: flex;
  gap: 12px;
  align-items: center;
  justify-content: center;
}

.add-to-cart {
  background: linear-gradient(135deg, #ffb74d, #ff9800);
  color: #3e2723;
  border: none;
  padding: 12px 24px;
  border-radius: 25px;
  font-weight: bold;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(255, 183, 77, 0.3);
}

.add-to-cart:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(255, 183, 77, 0.5);
  background: linear-gradient(135deg, #ffcc80, #ffb74d);
}

.add-to-cart:active {
  transform: translateY(0);
}

.fav-btn {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: #f5f5f5;
  border: 2px solid #e0e0e0;
  color: #999;
  font-size: 18px;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.fav-btn:hover {
  background: #ffebee;
  border-color: #ef5350;
  color: #ef5350;
  transform: scale(1.1);
}

.fav-btn.is-active {
  background: linear-gradient(135deg, #ef5350, #e53935);
  border-color: #e53935;
  color: white;
  animation: heartBeat 0.6s ease-in-out;
}

@keyframes heartBeat {
  0%, 100% { transform: scale(1); }
  25% { transform: scale(1.3); }
  50% { transform: scale(1.1); }
  75% { transform: scale(1.2); }
}

/* شبكة المنتجات */
.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
  padding: 20px;
  max-width: 1400px;
  margin: 0 auto;
}

@media (max-width: 768px) {
  .products-grid {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    padding: 15px;
  }
  
  .card-image {
    height: 220px;
  }
  
  .card-content {
    padding: 15px;
  }
  
  .card-content h3 {
    font-size: 1.1rem;
  }
  
  .card-content .price {
    font-size: 1.3rem;
  }
  
  .add-to-cart {
    padding: 10px 20px;
    font-size: 0.9rem;
  }
  
  .fav-btn {
    width: 38px;
    height: 38px;
    font-size: 16px;
  }
}
.hero {
  position: relative;
  height: 100vh;
  overflow: hidden;
}

.overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to bottom, transparent 0%, rgba(62, 39, 35, 0.4) 100%);
  z-index: 1;
}

.hero-content {
  position: absolute;
  bottom: 15%;
  left: 50%;
  transform: translateX(-50%);
  text-align: center;
  color: white;
  z-index: 2;
  width: 90%;
  max-width: 900px;
}

.hero-content h1 {
  font-size: 5.5rem;
  font-weight: 900;
  text-shadow: 0 4px 20px rgba(0,0,0,0.7);
  margin-bottom: 15px;
  letter-spacing: 2px;
}

.hero-content p {
  font-size: 1.8rem;
  margin-bottom: 30px;
  opacity: 0.95;
}

/* الأزرار العامة */
.btn {
  background: #ffb74d;
  color: #3e2723;
  border: none;
  padding: 16px 42px;
  border-radius: 50px;
  font-size: 1.2rem;
  font-weight: bold;
  cursor: pointer;
  box-shadow: 0 8px 25px rgba(255, 183, 77, 0.5);
  transition: all 0.3s;
}

.btn:hover {
  background: #ffcc80;
  transform: translateY(-4px);
  box-shadow: 0 15px 35px rgba(255, 183, 77, 0.6);
}

.btn-large {
  padding: 20px 60px;
  font-size: 1.5rem;
}

/* Sidebar Login */
#sidebar-login {
  position: fixed;
  top: 0;
  left: -420px;
  width: 380px;
  max-width: 90vw;
  height: 100vh;
  background: rgba(249, 245, 240, 0.95);
  backdrop-filter: blur(15px);
  box-shadow: -10px 0 40px rgba(0,0,0,0.2);
  transition: left 0.4s ease;
  padding: 40px;
  z-index: 1100;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

#sidebar-login.open {
  left: 0;
}

.close-btn {
  position: absolute;
  top: 25px;
  left: 25px;
  font-size: 32px;
  background: none;
  border: none;
  color: #5d4037;
  cursor: pointer;
}

.login-form h2 {
  font-size: 2.2rem;
  margin-bottom: 30px;
  color: #5d4037;
}

.login-form input {
  width: 100%;
  padding: 16px;
  margin: 12px 0;
  border: 1px solid #d7ccc8;
  border-radius: 12px;
  background: white;
  font-size: 16px;
}

.submit {
  width: 100%;
  background: #ffb74d;
  color: #3e2723;
  border: none;
  padding: 16px;
  border-radius: 12px;
  font-weight: bold;
  font-size: 17px;
  cursor: pointer;
  margin-top: 10px;
}

.submit:hover {
  background: #ffcc80;
}

.signup-link {
  color: #ba7d37;
  margin-top: 20px;
  font-weight: bold;
  text-decoration: none;
}

/* Footer */
.site-footer {
  background: #3e2723;
  color: white;
  padding: 60px 20px;
  margin-top: 100px;
  text-align: center;
}

.footer-logo {
  height: 60px;
  margin-bottom: 20px;
}

/* رسائل الخطأ والنجاح */
.form-msg {
  padding: 12px;
  border-radius: 12px;
  margin: 15px 0;
  width: 100%;
  text-align: center;
  font-weight: bold;
}

.form-msg.error {
  background: rgba(255, 87, 87, 0.15);
  color: #c62828;
}

.form-msg.success {
  background: rgba(76, 175, 80, 0.15);
  color: #2e7d32;
}

/* موبايل */
@media (max-width: 768px) {
  .nav-links {
    display: none;
  }
  .navbar {
    padding: 0 20px;
  }
  .hero-content h1 {
    font-size: 3.2rem;
  }
  .hero-content p {
    font-size: 1.3rem;
  }
  #sidebar-login {
    width: 100%;
    left: -100%;
  }
  #sidebar-login.open {
    left: 0;
  }
}
/* الفئات منسدلة */
.dropdown {
  position: relative;
  display: inline-block;
}

.dropbtn {
  color: #5d4037;
  font-weight: 600;
  font-size: 17px;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
}

.arrow {
  font-size: 12px;
  transition: transform 0.3s ease;
}

.dropdown:hover .arrow,
.dropdown.open .arrow {
  transform: rotate(180deg);
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  right: -20px;
  background: white;
  min-width: 220px;
  border-radius: 16px;
  box-shadow: 0 12px 35px rgba(0,0,0,0.18);
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all 0.35s ease;
  z-index: 999;
  padding: 12px 0;
  list-style: none;
}

.dropdown:hover .dropdown-menu,
.dropdown.open .dropdown-menu {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.dropdown-menu li a {
  display: block;
  padding: 14px 24px;
  color: #5d4037;
  text-decoration: none;
  font-size: 16px;
  font-weight: 500;
  transition: all 0.3s;
}

.dropdown-menu li a:hover {
  background: #fdf9f3;
  color: #ba7d37;
  padding-right: 32px;
  font-weight: 600;
}

/* موبايل */
@media (max-width: 992px) {
  .dropdown:hover .dropdown-menu {
    display: none;
  }
}

/* ===== قسم الحساب في النافبار ===== */

/* الدائرة اللي فيها الحرف الأول */
.user-profile .user-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;                  /* تجعلها دائرة كاملة */
    background: linear-gradient(135deg, #d7b98e, #ba7d37);
    color: white;
    font-size: 20px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
    border: 3px solid white;             /* إطار أبيض حول الدائرة */
}

/* تأثير عند المرور بالماوس */
.user-profile .user-avatar:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

/* الكونتينر اللي يحتوي الدائرة والقائمة المنسدلة */
.user-profile {
    position: relative;
    display: inline-block;
}

/* القائمة المنسدلة تحت الدائرة */
.user-profile .user-menu {
    display: none;
    position: absolute;
    top: 60px;                           /* مسافة تحت الدائرة */
    left: 50%;
    transform: translateX(-50%);
    background: white;
    min-width: 200px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    padding: 15px 0;
    z-index: 1000;
    text-align: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

/* إظهار القائمة عند المرور بالماوس على الدائرة */
.user-profile:hover .user-menu {
    display: block;
    opacity: 1;
    visibility: visible;
    top: 55px;
}

/* اسم المستخدم ودوره داخل القائمة */
.user-profile .user-name {
    margin: 0 0 5px 0;
    font-size: 16px;
    color: #333;
}

.user-profile .user-role {
    display: block;
    font-size: 13px;
    color: #777;
    margin-bottom: 12px;
}

/* الروابط داخل القائمة */
.user-profile .user-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.user-profile .user-links li {
    padding: 10px 20px;
    transition: background 0.2s;
}

.user-profile .user-links li:hover {
    background: #f8f9fa;
}

.user-profile .user-links li a {
    text-decoration: none;
    color: #333;
    font-size: 14px;
    display: block;
}

.user-profile .user-links .logout a {
    color: #e74c3c;
    font-weight: 500;
}

/* زر تسجيل الدخول لو ما مسجل */
.user-profile .login-btn {
    background: #d7b98e;
    color: white;
    padding: 10px 20px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s;
}

.user-profile .login-btn:hover {
    background: #ba7d37;
}

/* أنماط صفحة الملف الشخصي */
.profile-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
    padding: 30px;
    margin-top: 30px;
}

.profile-header {
    text-align: center;
    margin-bottom: 30px;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #d7b98e, #ba7d37);
    color: white;
    font-size: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    border: 5px solid white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.profile-container.heritage-bg {
    position: relative;
    padding: 30px 0;
    margin-top: 90px;
    overflow: hidden;
}

.profile-container.heritage-bg > .container {
    position: relative;
    z-index: 2;
}

.profile-container.heritage-bg::before {
    content: "";
    position: absolute;
    inset: -60px;
    background:
        radial-gradient(900px 450px at 20% 10%, rgba(215, 185, 142, 0.45), transparent 60%),
        radial-gradient(700px 420px at 80% 25%, rgba(186, 125, 55, 0.18), transparent 65%),
        radial-gradient(900px 500px at 40% 90%, rgba(255, 183, 77, 0.16), transparent 70%),
        linear-gradient(180deg, rgba(253, 249, 243, 0.92), rgba(253, 249, 243, 0.92));
    opacity: 1;
    pointer-events: none;
    z-index: 0;
}

.profile-container.heritage-bg::after {
    content: "";
    position: absolute;
    inset: -80px;
    background:
        repeating-linear-gradient(
            135deg,
            rgba(186, 125, 55, 0.08) 0px,
            rgba(186, 125, 55, 0.08) 8px,
            rgba(215, 185, 142, 0.06) 8px,
            rgba(215, 185, 142, 0.06) 18px
        );
    filter: blur(0.2px);
    opacity: 0.35;
    transform: translate3d(0, 0, 0);
    animation: heritageSandDrift 18s linear infinite;
    pointer-events: none;
    z-index: 1;
}

@keyframes heritageSandDrift {
    0% {
        transform: translate3d(-40px, 10px, 0);
    }
    100% {
        transform: translate3d(120px, -60px, 0);
    }
}

.profile-container .form-control:focus {
    border-color: #ba7d37;
    box-shadow: 0 0 0 0.25rem rgba(186, 125, 55, 0.22);
}

.profile-container .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(62, 39, 35, 0.12);
}

.form-label {
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 16px;
    transition: all 0.3s;
}

.form-control:focus {
    border-color: #ff9800;
    box-shadow: 0 0 0 0.25rem rgba(255, 152, 0, 0.25);
}

.btn-primary {
    background: #ff9800;
    border: none;
    padding: 12px 30px;
    border-radius: 30px;
    color: white;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: #ffa726;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 152, 0, 0.4);
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* إنميشنات تتبع الطلبات */
.tracking-timeline {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 40px 0;
    padding: 0 20px;
}

.tracking-timeline::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #ffb74d 0%, #ff9800 50%, #e0e0e0 50%, #e0e0e0 100%);
    transform: translateY(-50%);
    z-index: 1;
}

.timeline-step {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
}

.step-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f5f5f5;
    border: 3px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.step-icon::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: -1;
}

.timeline-step.active .step-icon {
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    border-color: #ff9800;
    transform: scale(1.1);
    animation: pulse 2s infinite;
}

.timeline-step.active .step-icon::before {
    width: 100%;
    height: 100%;
}

.timeline-step.completed .step-icon {
    background: linear-gradient(135deg, #4caf50, #45a049);
    border-color: #4caf50;
    animation: checkmark 0.6s ease-out;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

.step-info h4 {
    color: #3e2723;
    font-size: 1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.step-info p {
    color: #5d4037;
    font-size: 0.85rem;
    line-height: 1.4;
    max-width: 120px;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

/* إنميشنات */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.7);
    }
    70% {
        box-shadow: 0 0 0 20px rgba(255, 152, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0);
    }
}

@keyframes checkmark {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes progressFill {
    from {
        width: 0%;
    }
    to {
        width: var(--progress-width, 0%);
    }
}

/* تأثيرات إضافية للتتبع */
.tracking-progress {
    width: 100%;
    height: 8px;
    background: #f0f0f0;
    border-radius: 4px;
    overflow: hidden;
    margin: 20px 0;
}

.tracking-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #ffb74d, #ff9800);
    border-radius: 4px;
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    animation: progressFill 1.5s ease-out;
}

.tracking-status-text {
    text-align: center;
    margin: 20px 0;
    padding: 15px;
    background: #fff9f3;
    border-radius: 8px;
    border-left: 4px solid #ff9800;
}

.tracking-status-text h3 {
    color: #ff9800;
    margin-bottom: 5px;
    font-size: 1.2rem;
}

.tracking-status-text p {
    color: #5d4037;
    margin: 0;
}

/* إنميشن الإشعارات */
.notification-item {
    animation: slideInRight 0.5s ease-out;
}

.notification-item.unread {
    animation: slideInRight 0.5s ease-out, pulse 2s infinite;
}

.notification-badge {
    animation: pulse 2s infinite;
}

/* إنميزارت بطاقات الطلبات */
.order-card {
    animation: fadeInUp 0.6s ease-out;
    transition: all 0.3s ease;
}

.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

/* إنميزارت الأزرار */
.btn-primary, .update-btn, .submit-order-btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.btn-primary::before, .update-btn::before, .submit-order-btn::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-primary:hover::before, .update-btn:hover::before, .submit-order-btn:hover::before {
    width: 300px;
    height: 300px;
}

/* إنميزارت الحقول */
.form-group input, .form-group select, .form-group textarea {
    transition: all 0.3s ease;
}

.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(186, 125, 55, 0.3);
}

/* إنميزارت التحميل */
.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #ff9800;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 20px auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* إنميزارت النجاح */
.success-animation .checkmark-circle {
    animation: scaleIn 0.5s ease-out;
}

.success-animation .checkmark {
    animation: drawCheckmark 0.5s ease-out 0.3s both;
}

/* تصميم متجاوب للإنميزارت */
@media (max-width: 768px) {
    .tracking-timeline {
        flex-direction: column;
        gap: 30px;
        padding: 0;
    }
    
    .tracking-timeline::before {
        display: none;
    }
    
    .timeline-step {
        flex-direction: row;
        justify-content: flex-start;
        text-align: right;
        gap: 20px;
        animation: slideInRight 0.5s ease-out;
    }
    
    .step-icon {
        margin-bottom: 0;
        margin-left: 0;
    }
    
    .step-info {
        max-width: none;
        text-align: right;
    }
    
    .timeline-step:nth-child(even) {
        animation: slideInLeft 0.5s ease-out;
    }
}

/* تأثيرات خاصة للبائع */
.seller-order-item {
    animation: fadeInUp 0.6s ease-out;
}

.status-update-form {
    background: #fff9f3;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
    border: 2px solid #ffb74d;
    animation: slideInUp 0.5s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* تأثيرات الحالة */
.status-badge {
    position: relative;
    overflow: hidden;
}

.status-badge::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}

.tracking-timeline::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #ffb74d 0%, #ff9800 50%, #e0e0e0 50%, #e0e0e0 100%);
    transform: translateY(-50%);
    z-index: 1;
}

.timeline-step {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
}

.step-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f5f5f5;
    border: 3px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.step-icon::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: -1;
}

.timeline-step.active .step-icon {
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    border-color: #ff9800;
    transform: scale(1.1);
    animation: pulse 2s infinite;
}

.timeline-step.active .step-icon::before {
    width: 100%;
    height: 100%;
}

.timeline-step.completed .step-icon {
    background: linear-gradient(135deg, #4caf50, #45a049);
    border-color: #4caf50;
    animation: checkmark 0.6s ease-out;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

.step-info h4 {
    color: #3e2723;
    font-size: 1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.step-info p {
    color: #5d4037;
    font-size: 0.85rem;
    line-height: 1.4;
    max-width: 120px;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

.step-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f5f5f5;
    border: 3px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.step-icon::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: -1;
}

.timeline-step.active .step-icon {
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    border-color: #ff9800;
    transform: scale(1.1);
    animation: pulse 2s infinite;
}

.timeline-step.active .step-icon::before {
    width: 100%;
    height: 100%;
}

.timeline-step.completed .step-icon {
    background: linear-gradient(135deg, #4caf50, #45a049);
    border-color: #4caf50;
    animation: checkmark 0.6s ease-out;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

.step-info h4 {
    color: #3e2723;
    font-size: 1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.step-info p {
    color: #5d4037;
    font-size: 0.85rem;
    line-height: 1.4;
    max-width: 120px;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.7);
    }
    70% {
        box-shadow: 0 0 0 20px rgba(255, 152, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0);
    }
}

@keyframes checkmark {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes progressFill {
    from {
        width: 0%;
    }
    to {
        width: var(--progress-width, 0%);
    }
}

.tracking-progress {
    width: 100%;
    height: 8px;
    background: #f0f0f0;
    border-radius: 4px;
    overflow: hidden;
    margin: 20px 0;
}

.tracking-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #ffb74d, #ff9800);
    border-radius: 4px;
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    animation: progressFill 1.5s ease-out;
}

.tracking-status-text {
    text-align: center;
    margin: 20px 0;
    padding: 15px;
    background: #fff9f3;
    border-radius: 8px;
    border-left: 4px solid #ff9800;
}

.tracking-status-text h3 {
    color: #ff9800;
    margin-bottom: 5px;
    font-size: 1.2rem;
}

.tracking-status-text p {
    color: #5d4037;
    margin: 0;
}

.notification-item {
    animation: slideInRight 0.5s ease-out;
}

.notification-item.unread {
    animation: slideInRight 0.5s ease-out, pulse 2s infinite;
}

.notification-badge {
    animation: pulse 2s infinite;
}

.order-card {
    animation: fadeInUp 0.6s ease-out;
    transition: all 0.3s ease;
}

.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.btn-primary, .update-btn, .submit-order-btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.btn-primary::before, .update-btn::before, .submit-order-btn::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-primary:hover::before, .update-btn:hover::before, .submit-order-btn:hover::before {
    width: 300px;
    height: 300px;
}

.form-group input, .form-group select, .form-group textarea {
    transition: all 0.3s ease;
}

.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(186, 125, 55, 0.3);
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #ff9800;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 20px auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.success-animation .checkmark-circle {
    animation: scaleIn 0.5s ease-out;
}

.success-animation .checkmark {
    animation: drawCheckmark 0.5s ease-out 0.3s both;
}

@media (max-width: 768px) {
    .tracking-timeline {
        flex-direction: column;
        gap: 30px;
        padding: 0;
    }
    
    .tracking-timeline::before {
        display: none;
    }
    
    .timeline-step {
        flex-direction: row;
        justify-content: flex-start;
        text-align: right;
        gap: 20px;
        animation: slideInRight 0.5s ease-out;
    }
    
    .step-icon {
        margin-bottom: 0;
        margin-left: 0;
    }
    
    .step-info {
        max-width: none;
        text-align: right;
    }
    
    .timeline-step:nth-child(even) {
        animation: slideInLeft 0.5s ease-out;
    }
}

.seller-order-item {
    animation: fadeInUp 0.6s ease-out;
}

.status-update-form {
    background: #fff9f3;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
    border: 2px solid #ffb74d;
    animation: slideInUp 0.5s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.status-badge {
    position: relative;
    overflow: hidden;
}

.status-badge::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.7);
    }
    70% {
        box-shadow: 0 0 0 20px rgba(255, 152, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0);
    }
}

@keyframes checkmark {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.timeline-step {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
}

.step-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f5f5f5;
    border: 3px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.step-icon::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
    z-index: -1;
}

.timeline-step.active .step-icon {
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    border-color: #ff9800;
    transform: scale(1.1);
    animation: pulse 2s infinite;
}

.timeline-step.active .step-icon::before {
    width: 100%;
    height: 100%;
}

.timeline-step.completed .step-icon {
    background: linear-gradient(135deg, #4caf50, #45a049);
    border-color: #4caf50;
    animation: checkmark 0.6s ease-out;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

.step-info h4 {
    color: #3e2723;
    font-size: 1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.step-info p {
    color: #5d4037;
    font-size: 0.85rem;
    line-height: 1.4;
    max-width: 120px;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

/* %F%EJ4F' * */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.7);
    }
    70% {
        box-shadow: 0 0 0 20px rgba(255, 152, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0);
    }
}

@keyframes checkmark {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes progressFill {
    from {
        width: 0%;
    }
    to {
        width: var(--progress-width, 0%);
    }
}

/* %F%EJ4F' */
.tracking-progress {
    width: 100%;
    height: 8px;
    background: #f0f0f0;
    border-radius: 4px;
    overflow: hidden;
    margin: 20px 0;
}

.tracking-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #ffb74d, #ff9800);
    border-radius: 4px;
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    animation: progressFill 1.5s ease-out;
}

.tracking-status-text {
    text-align: center;
    margin: 20px 0;
    padding: 15px;
    background: #fff9f3;
    border-radius: 8px;
    border-left: 4px solid #ff9800;
}

.tracking-status-text h3 {
    color: #ff9800;
    margin-bottom: 5px;
    font-size: 1.2rem;
}

.tracking-status-text p {
    color: #5d4037;
    margin: 0;
}

/* %F%EJ4F' * */
.status-badge {
    position: relative;
    overflow: hidden;
}

.status-badge::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}

.step-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f5f5f5;
    border: 3px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.step-icon::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}

.timeline-step.active .step-icon {
    background: linear-gradient(135deg, #ffb74d, #ff9800);
    border-color: #ff9800;
    transform: scale(1.1);
    animation: pulse 2s infinite;
}

.timeline-step.active .step-icon::before {
    width: 100%;
    height: 100%;
}

.timeline-step.completed .step-icon {
    background: linear-gradient(135deg, #4caf50, #45a049);
    border-color: #4caf50;
    animation: checkmark 0.6s ease-out;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

.step-info h4 {
    color: #3e2723;
    font-size: 1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.step-info p {
    color: #5d4037;
    font-size: 0.85rem;
    line-height: 1.4;
    max-width: 120px;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.7);
    }
    70% {
        box-shadow: 0 0 0 20px rgba(255, 152, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0);
    }
}

@keyframes checkmark {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes progressFill {
    from {
        width: 0%;
    }
    to {
        width: var(--progress-width, 0%);
    }
}

.tracking-progress {
    width: 100%;
    height: 8px;
    background: #f0f0f0;
    border-radius: 4px;
    overflow: hidden;
    margin: 20px 0;
}

.tracking-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #ffb74d, #ff9800);
    border-radius: 4px;
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    animation: progressFill 1.5s ease-out;
}

.tracking-status-text {
    text-align: center;
    margin: 20px 0;
    padding: 15px;
    background: #fff9f3;
    border-radius: 8px;
    border-left: 4px solid #ff9800;
}

.tracking-status-text h3 {
    color: #ff9800;
    margin-bottom: 5px;
    font-size: 1.2rem;
}

.tracking-status-text p {
    color: #5d4037;
    margin: 0;
}

.notification-item {
    animation: slideInRight 0.5s ease-out;
}

.notification-item.unread {
    animation: slideInRight 0.5s ease-out, pulse 2s infinite;
}

.notification-badge {
    position: relative;
    overflow: hidden;
}

.notification-badge::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}

.step-icon:before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  background: linear-gradient(135deg, #ffb74d, #ff9800);
  border-radius: 50%;
  transform: translate(-50%, -50%);
  transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.timeline-step.active .step-icon:before {
  width: 100%;
  height: 100%;
}

.timeline-step.completed .step-icon:before {
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #4caf50, #45a049);
}

.order-card {
  animation: fadeInUp 0.6s ease-out;
  transition: all 0.3s ease;
}

.order-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 50px rgba(0,0,0,0.25);
}

.timeline-step.active .step-icon {
  background: linear-gradient(135deg, #ffb74d, #ff9800);
  border-color: #ff9800;
  transform: scale(1.1);
  animation: pulse 2s infinite;
}

.timeline-step.completed .step-icon {
  background: linear-gradient(135deg, #4caf50, #45a049);
  border-color: #4caf50;
  animation: checkmark 0.6s ease-out;
}

.step-icon i {
  font-size: 24px;
  color: #757575;
  transition: color 0.3s;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

.step-info h4 {
    color: #3e2723;
    font-size: 1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.step-info p {
    color: #5d4037;
    font-size: 0.85rem;
    line-height: 1.4;
    max-width: 120px;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

/* %F E J4F ' *  */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.7);
    }
    70% {
        box-shadow: 0 0 0 20px rgba(255, 152, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0);
    }
}

@keyframes checkmark {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes progressFill {
    from {
        width: 0%;
    }
    to {
        width: var(--progress-width, 0%);
    }
}

/* % # J1 '  D D * * ( 9  * */
.tracking-progress {
    width: 100%;
    height: 8px;
    background: #f0f0f0;
    border-radius: 4px;
    overflow: hidden;
    margin: 20px 0;
}

.tracking-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #ffb74d, #ff9800);
    border-radius: 4px;
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    animation: progressFill 1.5s ease-out;
}

.tracking-status-text {
    text-align: center;
    margin: 20px 0;
    padding: 15px;
    background: #fff9f3;
    border-radius: 8px;
    border-left: 4px solid #ff9800;
}

.tracking-status-text h3 {
    color: #ff9800;
    margin-bottom: 5px;
    font-size: 1.2rem;
}

.tracking-status-text p {
    color: #5d4037;
    margin: 0;
}

/* % F E J4F '  D 9 ' 1 ' *  */
.notification-item {
    animation: slideInRight 0.5s ease-out;
}

.notification-item.unread {
    animation: slideInRight 0.5s ease-out, pulse 2s infinite;
}

.notification-badge {
    position: relative;
    overflow: hidden;
}

.notification-badge::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}

/* % F E J2 ' 1 *  ( 7 ' B ' *  ' D 7 D ( ' *  */
.order-card {
  animation: fadeInUp 0.6s ease-out;
  transition: all 0.3s ease;
}

.order-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 50px rgba(0,0,0,0.25);
}

/* % F E J2 ' 1 *  ' D # 21 ' 1  */
.btn-primary, .update-btn, .submit-order-btn {
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.btn-primary:before, .update-btn:before, .submit-order-btn:before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
}

.timeline-step.active .step-icon:before {
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
}

.timeline-step.active .step-icon {
  width: 100%;
  height: 100%;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

.step-info h4 {
    color: #3e2723;
    font-size: 1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.step-info p {
    color: #5d4037;
    font-size: 0.85rem;
    line-height: 1.4;
    max-width: 120px;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

/* % F E J4F '  D 9 ' 1 ' *  */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.7);
    }
    70% {
        box-shadow: 0 0 0 20px rgba(255, 152, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0);
    }
}

@keyframes checkmark {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes progressFill {
    from {
        width: 0%;
    }
    to {
        width: var(--progress-width, 0%);
    }
}

/* % # J1 '  D D * * ( 9  * */
.tracking-progress {
    width: 100%;
    height: 8px;
    background: #f0f0f0;
    border-radius: 4px;
    overflow: hidden;
    margin: 20px 0;
}

.tracking-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #ffb74d, #ff9800);
    border-radius: 4px;
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    animation: progressFill 1.5s ease-out;
}

.tracking-status-text {
    text-align: center;
    margin: 20px 0;
    padding: 15px;
    background: #fff9f3;
    border-radius: 8px;
    border-left: 4px solid #ff9800;
}

.tracking-status-text h3 {
    color: #ff9800;
    margin-bottom: 5px;
    font-size: 1.2rem;
}

.tracking-status-text p {
    color: #5d4037;
    margin: 0;
}

/* % F E J4F '  D 9 ' 1 ' *  */
.notification-item {
    animation: slideInRight 0.5s ease-out;
}

.notification-item.unread {
    animation: slideInRight 0.5s ease-out, pulse 2s infinite;
}

.notification-badge {
    position: relative;
    overflow: hidden;
}

.notification-badge::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}

/* % F E J2 ' 1 *  ( 7 ' B ' *  ' D 7 D ( ' *  */
.order-card {
  animation: fadeInUp 0.6s ease-out;
  transition: all 0.3s ease;
}

.order-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 50px rgba(0,0,0,0.25);
}

/* % F E J2 ' 1 *  ' D # 21 ' 1  */
.btn-primary, .update-btn, .submit-order-btn {
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.btn-primary:before, .update-btn:before, .submit-order-btn:before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
}

.timeline-step.active .step-icon:before {
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
}

.timeline-step.active .step-icon {
  width: 100%;
  height: 100%;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

.step-info h4 {
    color: #3e2723;
    font-size: 1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.step-info p {
    color: #5d4037;
    font-size: 0.85rem;
    line-height: 1.4;
    max-width: 120px;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.completed .step-icon {
  background: linear-gradient(135deg, #4caf50, #45a049);
  border-color: #4caf50;
  animation: checkmark 0.6s ease-out;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

.step-info h4 {
    color: #3e2723;
    font-size: 1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.step-info p {
    color: #5d4037;
    font-size: 0.85rem;
    line-height: 1.4;
    max-width: 120px;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.7);
    }
    70% {
        box-shadow: 0 0 0 20px rgba(255, 152, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0);
    }
}

@keyframes checkmark {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes progressFill {
    from {
        width: 0%;
    }
    to {
        width: var(--progress-width, 0%);
    }
}

/* % # J1 '  D D * * ( 9  * */
.tracking-progress {
    width: 100%;
    height: 8px;
    background: #f0f0f0;
    border-radius: 4px;
    overflow: hidden;
    margin: 20px 0;
}

.tracking-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #ffb74d, #ff9800);
    border-radius: 4px;
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    animation: progressFill 1.5s ease-out;
}

.tracking-status-text {
    text-align: center;
    margin: 20px 0;
    padding: 15px;
    background: #fff9f3;
    border-radius: 8px;
    border-left: 4px solid #ff9800;
}

.tracking-status-text h3 {
    color: #ff9800;
    margin-bottom: 5px;
    font-size: 1.2rem;
}

.tracking-status-text p {
    color: #5d4037;
    margin: 0;
}

/* % F E J4F '  D 9 ' 1 ' *  */
.notification-item {
    animation: slideInRight 0.5s ease-out;
}

.notification-item.unread {
    animation: slideInRight 0.5s ease-out, pulse 2s infinite;
}

.notification-badge {
    position: relative;
    overflow: hidden;
}

.notification-badge::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}

/* % F E J2 ' 1 *  ( 7 ' B ' *  ' D 7 D ( ' *  */
.order-card {
  animation: fadeInUp 0.6s ease-out;
  transition: all 0.3s ease;
}

.order-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 50px rgba(0,0,0,0.25);
}

/* % F E J2 ' 1 *  ' D # 21 ' 1  */
.btn-primary, .update-btn, .submit-order-btn {
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.btn-primary:before, .update-btn:before, .submit-order-btn:before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
}

.timeline-step.active .step-icon:before {
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
}

.timeline-step.active .step-icon {
  width: 100%;
  height: 100%;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

.step-info h4 {
    color: #3e2723;
    font-size: 1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.step-info p {
    color: #5d4037;
    font-size: 0.85rem;
    line-height: 1.4;
    max-width: 120px;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.completed .step-icon {
  background: linear-gradient(135deg, #4caf50, #45a049);
  border-color: #4caf50;
  animation: checkmark 0.6s ease-out;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

.step-info h4 {
    color: #3e2723;
    font-size: 1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.step-info p {
    color: #5d4037;
    font-size: 0.85rem;
    line-height: 1.4;
    max-width: 120px;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.4);
    }
    70% {
        box-shadow: 0 0 0 20px rgba(255, 152, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 152, 0, 0);
    }
}

@keyframes checkmark {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes progressFill {
    from {
        width: 0%;
    }
    to {
        width: var(--progress-width, 0%);
    }
}

/* % # J1 '  D D * * ( 9  * */
.tracking-progress {
    width: 100%;
    height: 8px;
    background: #f0f0f0;
    border-radius: 4px;
    overflow: hidden;
    margin: 20px 0;
}

.tracking-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #ffb74d, #ff9800);
    border-radius: 4px;
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    animation: progressFill 1.5s ease-out;
}

.tracking-status-text {
    text-align: center;
    margin: 20px 0;
    padding: 15px;
    background: #fff9f3;
    border-radius: 8px;
    border-left: 4px solid #ff9800;
}

.tracking-status-text h3 {
    color: #ff9800;
    margin-bottom: 5px;
    font-size: 1.2rem;
}

.tracking-status-text p {
    color: #5d4037;
    margin: 0;
}

/* % F E J4F '  D 9 ' 1 ' *  */
.notification-item {
    animation: slideInRight 0.5s ease-out;
}

.notification-item.unread {
    animation: slideInRight 0.5s ease-out, pulse 2s infinite;
}

.notification-badge {
    position: relative;
    overflow: hidden;
}

.notification-badge::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}

/* % F E J2 ' 1 *  ( 7 ' B ' *  ' D 7 D ( ' *  */
.order-card {
  animation: fadeInUp 0.6s ease-out;
  transition: all 0.3s ease;
}

.order-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 50px rgba(0,0,0,0.25);
}

/* % F E J2 ' 1 *  ' D # 21 ' 1  */
.btn-primary, .update-btn, .submit-order-btn {
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.btn-primary:before, .update-btn:before, .submit-order-btn:before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
}

.timeline-step.active .step-icon:before {
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
}

.timeline-step.active .step-icon {
  width: 100%;
  height: 100%;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

.step-info h4 {
    color: #3e2723;
    font-size: 1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.step-info p {
    color: #5d4037;
    font-size: 0.85rem;
    line-height: 1.4;
    max-width: 120px;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.completed .step-icon {
  background: linear-gradient(135deg, #4caf50, #45a049);
  border-color: #4caf50;
  animation: checkmark 0.6s ease-out;
}

.step-icon i {
    font-size: 24px;
    color: #757575;
    transition: color 0.3s;
}

.timeline-step.active .step-icon i,
.timeline-step.completed .step-icon i {
    color: white;
}

.step-info h4 {
    color: #3e2723;
    font-size: 1rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.step-info p {
    color: #5d4037;
    font-size: 0.85rem;
    line-height: 1.4;
    max-width: 120px;
}

.timeline-step.active .step-info h4 {
    color: #ff9800;
    animation: fadeInUp 0.6s ease-out;
}

.timeline-step.completed .step-info h4 {
    color: #4caf50;
}
 } 
 
 . t i m e l i n e - s t e p . c o m p l e t e d   . s t e p - i n f o   h 4   { 
         c o l o r :   # 4 c a f 5 0 ; 
 } 
 
 / *   %FEJ4F'*  * / 
 @ k e y f r a m e s   p u l s e   { 
         0 %   { 
                 b o x - s h a d o w :   0   0   0   0   r g b a ( 2 5 5 ,   1 5 2 ,   0 ,   0 . 7 ) ; 
         } 
         7 0 %   { 
                 b o x - s h a d o w :   0   0   0   2 0 p x   r g b a ( 2 5 5 ,   1 5 2 ,   0 ,   0 ) ; 
         } 
         1 0 0 %   { 
                 b o x - s h a d o w :   0   0   0   0   r g b a ( 2 5 5 ,   1 5 2 ,   0 ,   0 ) ; 
         } 
 } 
 
 @ k e y f r a m e s   c h e c k m a r k   { 
         0 %   { 
                 t r a n s f o r m :   s c a l e ( 0 . 8 ) ; 
                 o p a c i t y :   0 ; 
         } 
         5 0 %   { 
                 t r a n s f o r m :   s c a l e ( 1 . 2 ) ; 
         } 
         1 0 0 %   { 
                 t r a n s f o r m :   s c a l e ( 1 ) ; 
                 o p a c i t y :   1 ; 
         } 
 } 
 
 @ k e y f r a m e s   f a d e I n U p   { 
         f r o m   { 
                 o p a c i t y :   0 ; 
                 t r a n s f o r m :   t r a n s l a t e Y ( 2 0 p x ) ; 
         } 
         t o   { 
                 o p a c i t y :   1 ; 
                 t r a n s f o r m :   t r a n s l a t e Y ( 0 ) ; 
         } 
 } 
 
 @ k e y f r a m e s   s l i d e I n L e f t   { 
         f r o m   { 
                 o p a c i t y :   0 ; 
                 t r a n s f o r m :   t r a n s l a t e X ( - 3 0 p x ) ; 
         } 
         t o   { 
                 o p a c i t y :   1 ; 
                 t r a n s f o r m :   t r a n s l a t e X ( 0 ) ; 
         } 
 } 
 
 @ k e y f r a m e s   s l i d e I n R i g h t   { 
         f r o m   { 
                 o p a c i t y :   0 ; 
                 t r a n s f o r m :   t r a n s l a t e X ( 3 0 p x ) ; 
         } 
         t o   { 
                 o p a c i t y :   1 ; 
                 t r a n s f o r m :   t r a n s l a t e X ( 0 ) ; 
         } 
 } 
 
 @ k e y f r a m e s   p r o g r e s s F i l l   { 
         f r o m   { 
                 w i d t h :   0 % ; 
         } 
         t o   { 
                 w i d t h :   v a r ( - - p r o g r e s s - w i d t h ,   0 % ) ; 
         } 
 } 
 
 / *   *#+J1'*  %6'AJ)  DD**(9  * / 
 . t r a c k i n g - p r o g r e s s   { 
         w i d t h :   1 0 0 % ; 
         h e i g h t :   8 p x ; 
         b a c k g r o u n d :   # f 0 f 0 f 0 ; 
         b o r d e r - r a d i u s :   4 p x ; 
         o v e r f l o w :   h i d d e n ; 
         m a r g i n :   2 0 p x   0 ; 
 } 
 
 . t r a c k i n g - p r o g r e s s - b a r   { 
         h e i g h t :   1 0 0 % ; 
         b a c k g r o u n d :   l i n e a r - g r a d i e n t ( 9 0 d e g ,   # f f b 7 4 d ,   # f f 9 8 0 0 ) ; 
         b o r d e r - r a d i u s :   4 p x ; 
         t r a n s i t i o n :   w i d t h   1 s   c u b i c - b e z i e r ( 0 . 4 ,   0 ,   0 . 2 ,   1 ) ; 
         a n i m a t i o n :   p r o g r e s s F i l l   1 . 5 s   e a s e - o u t ; 
 } 
 
 . t r a c k i n g - s t a t u s - t e x t   { 
         t e x t - a l i g n :   c e n t e r ; 
         m a r g i n :   2 0 p x   0 ; 
         p a d d i n g :   1 5 p x ; 
         b a c k g r o u n d :   # f f f 9 f 3 ; 
         b o r d e r - r a d i u s :   8 p x ; 
         b o r d e r - l e f t :   4 p x   s o l i d   # f f 9 8 0 0 ; 
 } 
 
 . t r a c k i n g - s t a t u s - t e x t   h 3   { 
         c o l o r :   # f f 9 8 0 0 ; 
         m a r g i n - b o t t o m :   5 p x ; 
         f o n t - s i z e :   1 . 2 r e m ; 
 } 
 
 . t r a c k i n g - s t a t u s - t e x t   p   { 
         c o l o r :   # 5 d 4 0 3 7 ; 
         m a r g i n :   0 ; 
 } 
 
 / *   %FEJ4F  'D%49'1'*  * / 
 . n o t i f i c a t i o n - i t e m   { 
         a n i m a t i o n :   s l i d e I n R i g h t   0 . 5 s   e a s e - o u t ; 
 } 
 
 . n o t i f i c a t i o n - i t e m . u n r e a d   { 
         a n i m a t i o n :   s l i d e I n R i g h t   0 . 5 s   e a s e - o u t ,   p u l s e   2 s   i n f i n i t e ; 
 } 
 
 . n o t i f i c a t i o n - b a d g e   { 
         a n i m a t i o n :   p u l s e   2 s   i n f i n i t e ; 
 } 
 
 / *   %FEJ2'1*  (7'B'*  'D7D('*  * / 
 . o r d e r - c a r d   { 
         a n i m a t i o n :   f a d e I n U p   0 . 6 s   e a s e - o u t ; 
         t r a n s i t i o n :   a l l   0 . 3 s   e a s e ; 
 } 
 
 . o r d e r - c a r d : h o v e r   { 
         t r a n s f o r m :   t r a n s l a t e Y ( - 5 p x ) ; 
         b o x - s h a d o w :   0   1 5 p x   4 0 p x   r g b a ( 0 , 0 , 0 , 0 . 1 5 ) ; 
 } 
 
 / *   %FEJ2'1*  'D#21'1  * / 
 . b t n - p r i m a r y ,   . u p d a t e - b t n ,   . s u b m i t - o r d e r - b t n   { 
         p o s i t i o n :   r e l a t i v e ; 
         o v e r f l o w :   h i d d e n ; 
         t r a n s i t i o n :   a l l   0 . 3 s   e a s e ; 
 } 
 
 . b t n - p r i m a r y : : b e f o r e ,   . u p d a t e - b t n : : b e f o r e ,   . s u b m i t - o r d e r - b t n : : b e f o r e   { 
         c o n t e n t :   " " ; 
         p o s i t i o n :   a b s o l u t e ; 
         t o p :   5 0 % ; 
         l e f t :   5 0 % ; 
         w i d t h :   0 ; 
         h e i g h t :   0 ; 
         b a c k g r o u n d :   r g b a ( 2 5 5 ,   2 5 5 ,   2 5 5 ,   0 . 3 ) ; 
         b o r d e r - r a d i u s :   5 0 % ; 
         t r a n s f o r m :   t r a n s l a t e ( - 5 0 % ,   - 5 0 % ) ; 
         t r a n s i t i o n :   w i d t h   0 . 6 s ,   h e i g h t   0 . 6 s ; 
 } 
 
 . b t n - p r i m a r y : h o v e r : : b e f o r e ,   . u p d a t e - b t n : h o v e r : : b e f o r e ,   . s u b m i t - o r d e r - b t n : : b e f o r e   { 
         w i d t h :   3 0 0 p x ; 
         h e i g h t :   3 0 0 p x ; 
 } 
 
 / *   %FEJ2'1*  'D-BHD  * / 
 . f o r m - g r o u p   i n p u t ,   . f o r m - g r o u p   s e l e c t ,   . f o r m - g r o u p   t e x t a r e a   { 
         t r a n s i t i o n :   a l l   0 . 3 s   e a s e ; 
 } 
 
 . f o r m - g r o u p   i n p u t : f o c u s ,   . f o r m - g r o u p   s e l e c t : f o c u s ,   . f o r m - g r o u p   t e x t a r e a : f o c u s   { 
         t r a n s f o r m :   t r a n s l a t e Y ( - 2 p x ) ; 
         b o x - s h a d o w :   0   5 p x   1 5 p x   r g b a ( 1 8 6 ,   1 2 5 ,   5 5 ,   0 . 3 ) ; 
 } 
 
 / *   %FEJ2'1*  'D*-EJD  * / 
 . l o a d i n g - s p i n n e r   { 
         w i d t h :   4 0 p x ; 
         h e i g h t :   4 0 p x ; 
         b o r d e r :   4 p x   s o l i d   # f 3 f 3 f 3 ; 
         b o r d e r - t o p :   4 p x   s o l i d   # f f 9 8 0 0 ; 
         b o r d e r - r a d i u s :   5 0 % ; 
         a n i m a t i o n :   s p i n   1 s   l i n e a r   i n f i n i t e ; 
         m a r g i n :   2 0 p x   a u t o ; 
 } 
 
 @ k e y f r a m e s   s p i n   { 
         0 %   {   t r a n s f o r m :   r o t a t e ( 0 d e g ) ;   } 
         1 0 0 %   {   t r a n s f o r m :   r o t a t e ( 3 6 0 d e g ) ;   } 
 } 
 
 / *   %FEJ2'1*  'DF,'-  * / 
 . s u c c e s s - a n i m a t i o n   . c h e c k m a r k - c i r c l e   { 
         a n i m a t i o n :   s c a l e I n   0 . 5 s   e a s e - o u t ; 
 } 
 
 . s u c c e s s - a n i m a t i o n   . c h e c k m a r k   { 
         a n i m a t i o n :   d r a w C h e c k m a r k   0 . 5 s   e a s e - o u t   0 . 3 s   b o t h ; 
 } 
 
 / *   *5EJE  E*,'H(  DD%FEJ2'1*  * / 
 @ m e d i a   ( m a x - w i d t h :   7 6 8 p x )   { 
         . t r a c k i n g - t i m e l i n e   { 
                 f l e x - d i r e c t i o n :   c o l u m n ; 
                 g a p :   3 0 p x ; 
                 p a d d i n g :   0 ; 
         } 
         
         . t r a c k i n g - t i m e l i n e : : b e f o r e   { 
                 d i s p l a y :   n o n e ; 
         } 
         
         . t i m e l i n e - s t e p   { 
                 f l e x - d i r e c t i o n :   r o w ; 
                 j u s t i f y - c o n t e n t :   f l e x - s t a r t ; 
                 t e x t - a l i g n :   r i g h t ; 
                 g a p :   2 0 p x ; 
                 a n i m a t i o n :   s l i d e I n R i g h t   0 . 5 s   e a s e - o u t ; 
         } 
         
         . s t e p - i c o n   { 
                 m a r g i n - b o t t o m :   0 ; 
                 m a r g i n - l e f t :   0 ; 
         } 
         
         . s t e p - i n f o   { 
                 m a x - w i d t h :   n o n e ; 
                 t e x t - a l i g n :   r i g h t ; 
         } 
         
         . t i m e l i n e - s t e p : n t h - c h i l d ( e v e n )   { 
                 a n i m a t i o n :   s l i d e I n L e f t   0 . 5 s   e a s e - o u t ; 
         } 
 } 
 
 / *   *#+J1'*  .'5)  DD('&9  * / 
 . s e l l e r - o r d e r - i t e m   { 
         a n i m a t i o n :   f a d e I n U p   0 . 6 s   e a s e - o u t ; 
 } 
 
 . s t a t u s - u p d a t e - f o r m   { 
         b a c k g r o u n d :   # f f f 9 f 3 ; 
         p a d d i n g :   2 0 p x ; 
         b o r d e r - r a d i u s :   8 p x ; 
         m a r g i n - t o p :   2 0 p x ; 
         b o r d e r :   2 p x   s o l i d   # f f b 7 4 d ; 
         a n i m a t i o n :   s l i d e I n U p   0 . 5 s   e a s e - o u t ; 
 } 
 
 @ k e y f r a m e s   s l i d e I n U p   { 
         f r o m   { 
                 o p a c i t y :   0 ; 
                 t r a n s f o r m :   t r a n s l a t e Y ( 3 0 p x ) ; 
         } 
         t o   { 
                 o p a c i t y :   1 ; 
                 t r a n s f o r m :   t r a n s l a t e Y ( 0 ) ; 
         } 
 } 
 
 / *   *#+J1'*  'D-'D)  * / 
 . s t a t u s - b a d g e   { 
         p o s i t i o n :   r e l a t i v e ; 
         o v e r f l o w :   h i d d e n ; 
 } 
 
 . s t a t u s - b a d g e : : a f t e r   { 
         c o n t e n t :   " " ; 
         p o s i t i o n :   a b s o l u t e ; 
         t o p :   0 ; 
         l e f t :   - 1 0 0 % ; 
         w i d t h :   1 0 0 % ; 
         h e i g h t :   1 0 0 % ; 
         b a c k g r o u n d :   l i n e a r - g r a d i e n t ( 9 0 d e g ,   t r a n s p a r e n t ,   r g b a ( 2 5 5 ,   2 5 5 ,   2 5 5 ,   0 . 4 ) ,   t r a n s p a r e n t ) ; 
         a n i m a t i o n :   s h i m m e r   3 s   i n f i n i t e ; 
 } 
 
 @ k e y f r a m e s   s h i m m e r   { 
         0 %   { 
                 l e f t :   - 1 0 0 % ; 
         } 
         1 0 0 %   { 
                 l e f t :   1 0 0 % ; 
         } 
 } 
 
 