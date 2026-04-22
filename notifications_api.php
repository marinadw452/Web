<?php
require_once 'config.php';

$page_title = 'السلة';
include 'includes/header.php';
?>

<style>
  .ct-shell{max-width:1200px;margin:110px auto 60px;padding:0 16px;}
  .ct-card{background:#fff;border:1px solid rgba(62,39,35,0.08);border-radius:22px;box-shadow:0 18px 45px rgba(62,39,35,0.08);overflow:hidden;}
  .ct-head{padding:18px 18px 14px;background:linear-gradient(135deg, rgba(186,125,55,0.16), rgba(255,183,77,0.10));border-bottom:1px solid rgba(62,39,35,0.08);}
  .ct-head h1{margin:0;color:#3e2723;font-size:24px;font-weight:1000;}
  .ct-head p{margin:8px 0 0;color:#6d4c41;font-weight:800;}
  .ct-body{padding:16px 18px 20px;}
  .ct-row{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;border:1px solid rgba(62,39,35,0.08);border-radius:18px;padding:12px;background:#fff;margin:10px 0;}
  .ct-left{display:flex;align-items:center;gap:12px;min-width:280px;}
  .ct-img{width:72px;height:72px;border-radius:16px;overflow:hidden;background:#f3f4f6;border:1px solid rgba(0,0,0,0.06);}
  .ct-img img{width:100%;height:100%;object-fit:cover;display:block;}
  .ct-name{font-weight:1000;color:#3e2723;}
  .ct-meta{margin-top:6px;color:#8d6e63;font-weight:800;font-size:13px;display:flex;gap:10px;flex-wrap:wrap;}
  .ct-right{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
  .ct-qty{display:flex;align-items:center;gap:8px;background:#fdf9f3;border:1px solid rgba(62,39,35,0.10);border-radius:999px;padding:6px 10px;}
  .ct-qty input{width:70px;border:none;background:transparent;text-align:center;font-weight:1000;font-family:'Tajawal',sans-serif;}
  .ct-btn{border:none;border-radius:999px;padding:10px 14px;font-weight:1000;cursor:pointer;}
  .ct-btn.ghost{background:#fff;border:1px solid rgba(62,39,35,0.14);color:#3e2723;}
  .ct-btn.danger{background:#ffe8ea;color:#b00020;}
  .ct-total{margin-top:16px;padding:14px;border-radius:18px;background:#fff;border:1px solid rgba(62,39,35,0.08);display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
  .ct-total strong{font-weight:1000;color:#3e2723;font-size:18px;}
  .ct-empty{padding:26px;text-align:center;color:#8d6e63;font-weight:900;}
</style>

<div class="ct-shell">
  <div class="ct-card">
    <div class="ct-head">
      <h1>سلة المشتريات</h1>
      <p>تقدر تعدل الكمية أو تحذف منتجات — السلة محفوظة تلقائياً.</p>
    </div>

    <div class="ct-body">
      <div id="cartList"></div>
      <div id="cartTotal" class="ct-total" style="display:none;"></div>

      <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;">
        <a href="products.php" class="ct-btn ghost" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">متابعة التسوق</a>
        <a href="favorites.php" class="ct-btn ghost" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">المفضلة</a>
        <a href="checkout_center.php" class="ct-btn" id="checkoutBtn" style="text-decoration:none;display:none;align-items:center;justify-content:center;background:linear-gradient(135deg, #ffb74d, #ff9800);color:#3e2723;">إتمام الطلب</a>
        <button class="ct-btn danger" type="button" id="clearCartBtn">تفريغ السلة</button>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const list = document.getElementById('cartList');
  const totalBox = document.getElementById('cartTotal');
  const clearBtn = document.getElementById('clearCartBtn');

  function e(s){
    return String(s ?? '').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'",'&#039;');
  }

  function fmt(n){
    const x = Number(n) || 0;
    return x.toLocaleString('ar-SA');
  }

  function render(){
    if (!window.GoodHandsCart) {
      list.innerHTML = '<div class="ct-empty">تعذر تحميل بيانات السلة.</div>';
      return;
    }

    const items = window.GoodHandsCart.getItems();
    if (!items.length){
      list.innerHTML = '<div class="ct-empty">سلتك فاضية — اختار منتجات من صفحة المنتجات.</div>';
      totalBox.style.display = 'none';
      document.getElementById('checkoutBtn').style.display = 'none';
      return;
    }

    document.getElementById('checkoutBtn').style.display = 'inline-flex';

    let total = 0;
    list.innerHTML = items.map(it => {
      const price = Number(it.price) || 0;
      const qty = Number(it.qty) || 1;
      total += price * qty;

      const sizeText = it.size ? ('المقاس: ' + e(it.size)) : 'بدون مقاس';
      const img = it.image ? e(it.image) : 'images/products/placeholder.jpg';

      return `
        <div class="ct-row">
          <div class="ct-left">
            <div class="ct-img"><img src="${img}" onerror="this.src='images/products/placeholder.jpg'" alt="${e(it.name)}"></div>
            <div>
              <div class="ct-name">${e(it.name || 'منتج')}</div>
              <div class="ct-meta">
                <span>${sizeText}</span>
                <span>السعر: ${fmt(price)} ريال</span>
              </div>
            </div>
          </div>

          <div class="ct-right">
            <div class="ct-qty">
              <span style="font-weight:1000;color:#6d4c41;">الكمية</span>
              <input type="number" min="1" value="${qty}" data-qty="1" data-id="${e(it.id)}" data-size="${e(it.size || '')}">
            </div>
            <button class="ct-btn danger" type="button" data-remove="1" data-id="${e(it.id)}" data-size="${e(it.size || '')}">حذف</button>
            <a class="ct-btn ghost" href="product.php?id=${encodeURIComponent(it.id)}" style="text-decoration:none;">عرض المنتج</a>
          </div>
        </div>
      `;
    }).join('');

    totalBox.style.display = 'flex';
    totalBox.innerHTML = `
      <strong>الإجمالي</strong>
      <strong>${fmt(total)} ريال</strong>
    `;

    window.GoodHandsCart.updateBadge?.();
  }

  list.addEventListener('input', (ev) => {
    const inp = ev.target.closest('[data-qty="1"]');
    if (!inp || !window.GoodHandsCart) return;
    const id = inp.getAttribute('data-id');
    const size = inp.getAttribute('data-size') || '';
    const qty = Math.max(1, Number(inp.value) || 1);
    window.GoodHandsCart.updateQty(id, size, qty);
    render();
  });

  list.addEventListener('click', (ev) => {
    const btn = ev.target.closest('[data-remove="1"]');
    if (!btn || !window.GoodHandsCart) return;
    const id = btn.getAttribute('data-id');
    const size = btn.getAttribute('data-size') || '';
    window.GoodHandsCart.remove(id, size);
    render();
  });

  clearBtn.addEventListener('click', () => {
    if (!window.GoodHandsCart) return;
    if (!confirm('هل أنت متأكد من تفريغ السلة؟')) return;
    window.GoodHandsCart.setItems([]);
    render();
  });

  document.addEventListener('DOMContentLoaded', render);
})();
</script>

<?php include 'includes/footer.php'; ?>
