.slider-container {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
}
.slides { position: relative; width: 100%; height: 100%; }
.slide {
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 100%;
  object-fit: cover;
  opacity: 0;
  transition: opacity 1.6s ease-in-out;
}
.slide.active { opacity: 1; }

.prev-btn, .next-btn {
  position: absolute;
  top: 50%; transform: translateY(-50%);
  background: rgba(255,183,77,0.5);
  backdrop-filter: blur(10px);
  color: white; border: none;
  width: 60px; height: 60px;
  border-radius: 50%;
  font-size: 28px; cursor: pointer;
  z-index: 10; transition: 0.4s;
}
.prev-btn { left: 30px; }
.next-btn { right: 30px; }
.prev-btn:hover, .next-btn:hover {
  background: #ffb74d;
  transform: translateY(-50%) scale(1.15);
}

.dots {
  position: absolute;
  bottom: 30px; left: 50%;
  transform: translateX(-50%);
  display: flex; gap: 12px; z-index: 10;
}
.dot {
  width: 14px; height: 14px;
  border-radius: 50%;
  background: rgba(255,255,255,0.5);
  cursor: pointer; transition: 0.4s;
}
.dot.active, .dot:hover {
  background: #ffb74d;
  transform: scale(1.4);
}