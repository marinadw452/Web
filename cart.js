#wave-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; pointer-events: none; }
.hero, .featured-products-modern { position: relative; z-index: 2; }

.hero-content h1 { font-size: 5rem; animation: fadeUp 1s forwards; }
.hero-content p { font-size: 1.8rem; animation: fadeUp 1s 0.5s forwards; opacity: 0; }
.hero-content .btn { animation: fadeUp 1s 1s forwards; opacity: 0; }
@keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

.hero-content .btn,
.hero-content .btn:hover,
.hero-content .btn:focus,
.hero-content .btn:active,
.hero-content .btn:visited {
  text-decoration: none !important;
}

.hero-content .btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #ffb74d, #ff9800);
  color: #3e2723;
  border: none;
  padding: 16px 42px;
  border-radius: 999px;
  font-weight: 900;
  box-shadow: 0 12px 35px rgba(255, 183, 77, 0.55);
  transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
}

.hero-content .btn:hover {
  transform: translateY(-4px);
  box-shadow: 0 18px 45px rgba(255, 183, 77, 0.65);
  filter: brightness(1.03);
}

.featured-products-modern { background: #fdf9f3; padding: 140px 0 100px; margin-top: -80px; }
.section-header h2 { font-size: 3.5rem; color: #ba7d37; }
.modern-grid { gap: 40px; max-width: 1400px; margin: 0 auto; padding: 0 20px; }
.modern-card { border-radius: 24px; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.08); transition: 0.5s; }
.modern-card:hover { transform: translateY(-20px); box-shadow: 0 35px 80px rgba(255,183,77,0.3); }
.card-image img { height: 380px; object-fit: cover; transition: 0.8s; }
.modern-card:hover img { transform: scale(1.12); }
.card-badge { top: 16px; right: 16px; background: #ffb74d; color: #3e2723; padding: 8px 18px; border-radius: 50px; font-weight: bold; }
.price { font-size: 1.7rem; color: #ba7d37; font-weight: bold; }
.add-cart { background: #ffb74d; color: #3e2723; border: none; padding: 14px 36px; border-radius: 50px; font-weight: bold; transition: 0.3s; }
.add-cart:hover { background: #ffcc80; transform: translateY(-4px); box-shadow: 0 12px 30px rgba(255,183,77,0.4); }
.btn-large { background: #3e2723; color: #ffb74d; padding: 20px 60px; font-size: 1.5rem; border-radius: 50px; }