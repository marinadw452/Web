<?php
require_once __DIR__ . '/config.php';

if (!isLoggedIn()) {
    redirect('login.php?redirect=' . urlencode($_SERVER['REQUEST_URI'] ?? 'checkout_center.php'));
}

$page_title = 'إتمام الطلب';
include __DIR__ . '/includes/header.php';
?>

<style>
  .ch-shell{max-width:1100px;margin:110px auto 60px;padding:0 16px;}
  .ch-grid{display:grid;grid-template-columns:1fr;gap:16px;}
  @media (min-width: 980px){.ch-grid{grid-template-columns: 0.95fr 1.05fr;}}
  .ch-card{background:#fff;border:1px solid rgba(62,39,35,0.08);border-radius:22px;box-shadow:0 18px 45px rgba(62,39,35,0.08);overflow:hidden;}
  .ch-head{padding:18px 18px 14px;background:linear-gradient(135deg, rgba(186,125,55,0.16), rgba(255,183,77,0.10));border-bottom:1px solid rgba(62,39,35,0.08);}
  .ch-head h1{margin:0;color:#3e2723;font-size:22px;font-weight:1000;}
  .ch-body{padding:16px 18px 18px;}
  .ch-empty{padding:26px;text-align:center;color:#8d6e63;font-weight:900;}
  .ch-row{border:1px solid rgba(62,39,35,0.08);border-radius:18px;padding:12px 14px;margin:10px 0;background:#fff;}
  .ch-seller{font-weight:1000;color:#3e2723;display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
  .ch-meta{margin-top:8px;color:#8d6e63;font-weight:800;font-size:13px;display:flex;gap:10px;flex-wrap:wrap;}
  .ch-total{margin-top:12px;padding:12px 14px;border-radius:18px;background:#fff;border:1px solid rgba(62,39,35,0.08);display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
  .ch-total strong{font-weight:1000;color:#3e2723;font-size:16px;}
  .ch-btn{border:none;border-radius:999px;padding:12px 16px;font-weight:1000;cursor:pointer;}
  .ch-btn.primary{background:linear-gradient(135deg,#ffb74d,#ff9800);color:#3e2723;}
  .ch-btn.ghost{background:#fff;border:1px solid rgba(62,39,35,0.14);color:#3e2723;}
  .ch-field{display:block;margin:10px 0;}
  .ch-field label{display:block;margin-bottom:6px;font-weight:900;color:#3e2723;}
  .ch-field input,.ch-field textarea{width:100%;padding:12px 14px;border:1px solid rgba(62,39,35,0.14);border-radius:14px;font-family:'Tajawal',sans-serif;font-weight:800;}
  .ch-note{margin-top:10px;font-weight:900;color:#6d4c41;}
</style>

<div class="ch-shell">
  <div class="ch-grid">
    <div class="ch-card">
      <div class="ch-head"><h1>ملخص السلة (حسب البائع)</h1></div>
      <div class="ch-body">
        <div id="quoteBox"></div>
        <div id="grandBox" class="ch-total" style="display:none;"></div>
        <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;">
          <a href="cart.php" class="ch-btn ghost" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">رجوع للسلة</a>
          <a href="products.php" class="ch-btn ghost" style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">متابعة التسوق</a>
        </div>
      </div>
    </div>

    <div class="ch-card">
      <div class="ch-head"><h1>بيانات العميل (الدفع عند الاستلام)</h1></div>
      <div class="ch-body">
        <form id="checkoutForm">
          <div class="ch-field">
            <label for="customer_name">الاسم الكامل *</label>
            <input id="customer_name" type="text" required>
          </div>
          <div class="ch-field">
            <label for="customer_phone">رقم الهاتف *</label>
            <input id="customer_phone" type="tel" required minlength="10" inputmode="numeric">
          </div>
          <div class="ch-field">
            <label for="customer_city">المدينة *</label>
            <input id="customer_city" type="text" required>
          </div>
          <div class="ch-field">
            <label for="customer_address">العنوان بالتفصيل *</label>
            <textarea id="customer_address" rows="3" required></textarea>
          </div>
          <div class="ch-field">
            <label for="notes">ملاحظات (اختياري)</label>
            <textarea id="notes" rows="3"></textarea>
          </div>

          <button class="ch-btn primary" type="submit" style="width:100%;">تأكيد الطلب</button>
          <div class="ch-note" id="msg"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const base = (window.__basePath || '');
  const quoteBox = document.getElementById('quoteBox');
  const grandBox = document.getElementById('grandBox');
  const form = document.getElementById('checkoutForm');
  const msg = document.getElementById('msg');

  function e(s){
    return String(s ?? '').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'",'&#039;');
  }
  function fmt(n){
    const x = Number(n) || 0;
    return x.toLocaleString('ar-SA');
  }

  function getCartItems(){
    if (!window.GoodHandsCart) return [];
    const items = window.GoodHandsCart.getItems();
    return (items || []).map(it => ({
      id: it.id,
      qty: it.qty,
      size: it.size || ''
    }));
  }

  async function api(action, payload){
    const res = await fetch(base + 'orders_api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ action, ...(payload||{}) })
    });
    return res.json();
  }

  async function loadQuote(){
    const items = getCartItems();
    if (!items.length){
      quoteBox.innerHTML = '<div class="ch-empty">سلتك فاضية.</div>';
      grandBox.style.display = 'none';
      return;
    }

    quoteBox.innerHTML = '<div class="ch-empty">جاري حساب الطلب...</div>';
    const data = await api('quote', {items});
    if (!data.ok){
      quoteBox.innerHTML = '<div class="ch-empty">' + e(data.error || 'تعذر حساب الطلب') + '</div>';
      grandBox.style.display = 'none';
      return;
    }

    const groups = data.groups || [];
    quoteBox.innerHTML = groups.map(g => {
      const itemsHtml = (g.items||[]).map(it => {
        const size = it.size ? (' — المقاس: ' + e(it.size)) : '';
        return `<div class="ch-meta"><span>${e(it.name)}${size}</span><span>الكمية: ${e(it.qty)}</span><span>الإجمالي: ${fmt(it.subtotal)} ريال</span></div>`;
      }).join('');

      return `
        <div class="ch-row">
          <div class="ch-seller">
            <span>بائع #${e(g.seller_id)}</span>
            <span style="color:#ba7d37;font-weight:1000;">${fmt(g.total)} ريال</span>
          </div>
          <div class="ch-meta">
            <span>المجموع الفرعي: ${fmt(g.subtotal)} ريال</span>
            <span>رسوم التوصيل: ${fmt(g.delivery_fee)} ريال</span>
          </div>
          ${itemsHtml}
        </div>
      `;
    }).join('');

    grandBox.style.display = 'flex';
    grandBox.innerHTML = `<strong>الإجمالي الكلي</strong><strong>${fmt(data.grand?.total)} ريال</strong>`;
  }

  form.addEventListener('submit', async (ev) => {
    ev.preventDefault();
    msg.textContent = 'جاري إنشاء الطلب...';
    msg.style.color = '#6d4c41';

    const items = getCartItems();
    if (!items.length){
      msg.textContent = 'السلة فارغة.';
      return;
    }

    const payload = {
      items,
      customer_name: document.getElementById('customer_name').value.trim(),
      customer_phone: document.getElementById('customer_phone').value.trim(),
      customer_city: document.getElementById('customer_city').value.trim(),
      customer_address: document.getElementById('customer_address').value.trim(),
      notes: document.getElementById('notes').value.trim(),
    };

    const phoneDigits = (payload.customer_phone || '').replace(/\D+/g,'');
    if (phoneDigits.length < 10) {
      msg.textContent = 'رقم الجوال يجب أن لا يقل عن 10 أرقام';
      msg.style.color = '#b00020';
      return;
    }

    const data = await api('create', payload);
    if (!data.ok){
      msg.textContent = data.error || 'فشل إنشاء الطلب';
      msg.style.color = '#b00020';
      return;
    }

    // تفريغ السلة محليًا
    window.GoodHandsCart?.setItems?.([]);

    msg.textContent = 'تم إنشاء الطلب بنجاح';
    msg.style.color = '#2e7d32';

    // توجيه لصفحة نجاح ثم طلباتي
    const og = encodeURIComponent(data.order_group || '');
    window.location.href = 'order_success.php?order=' + og;
  });

  document.addEventListener('DOMContentLoaded', loadQuote);
})();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
