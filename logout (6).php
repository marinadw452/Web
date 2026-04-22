<?php
$page_title = 'حول أيدي طيّبة';
include 'includes/header.php';
?>

<section class="hero" style="height:45vh; position:relative; margin-top:90px; border-radius:28px; overflow:hidden;">
  <video autoplay muted loop playsinline preload="metadata" poster="images/about-hero.jpg" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 70%;z-index:0;">
    <source src="images/mm%20.mp4" type="video/mp4">
  </video>
  <div class="overlay"></div>
  <div class="hero-content" style="bottom:20%;">
    <h1 style="font-size:4rem;">حول أيدي طيّبة</h1>
    <p style="font-size:1.4rem;">منصة تجمع الصناع بالعملاء بكل حب وإبداع</p>
  </div>
</section>

<div class="heritage-divider">
  <img src="images/heritage-divider.png" alt="زخرفة تراثية" class="divider-img">
</div>

<section style="padding:40px 0 10px;">
  <div class="container">
    <div style="max-width:1100px;margin:auto;background:white;border-radius:20px;overflow:hidden;box-shadow:0 15px 40px rgba(0,0,0,0.1);border:1px solid rgba(186,125,55,0.12);">
      <div style="padding:60px 40px;text-align:center;">
        <h2 style="font-size:3rem;color:#ba7d37;margin-bottom:25px;">من نحن</h2>
        <p style="font-size:1.3rem;line-height:2;color:#5d4037;">
          أيدي طيّبة هي منصة إلكترونية سعودية تهدف إلى دعم الحرفيين والصناع التقليديين،
          وتوفير منتجات يدوية أصيلة بجودة عالية للعملاء في كل مكان.
          نؤمن أن كل قطعة تحمل روح صانعها وقصة تراثنا العريق.
        </p>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:30px;padding:40px;background:#f9f5f0;">
        <div style="background:white;padding:30px;border-radius:16px;text-align:center;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
          <h3 style="color:#ba7d37;font-size:1.8rem;margin-bottom:15px;">جودة مضمونة</h3>
          <p style="color:#5d4037;">كل منتج يمر بمراجعة دقيقة قبل عرضه</p>
        </div>
        <div style="background:white;padding:30px;border-radius:16px;text-align:center;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
          <h3 style="color:#ba7d37;font-size:1.8rem;margin-bottom:15px;">دعم الصناع</h3>
          <p style="color:#5d4037;">نساعد الحرفيين على التوسع وبيع منتجاتهم</p>
        </div>
        <div style="background:white;padding:30px;border-radius:16px;text-align:center;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
          <h3 style="color:#ba7d37;font-size:1.8rem;margin-bottom:15px;">تجربة سهلة</h3>
          <p style="color:#5d4037;">تصفح وشراء بكل راحة وأمان</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>