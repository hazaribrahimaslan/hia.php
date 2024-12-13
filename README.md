# hia.php PHP Kütüphanesi
<p>Veritabanı işlemleri, kullanıcı sistemi ve diğer tüm özellikler tek kütüphanede.</p>
<p><b>Özellikleri</b></p>
<ul>
  <li>Veritabanı bağlantısı.</li>
  <li>Veri sorgulama,listeleme, ekleme, güncelleme ve silme.</li>
  <li>Veri türünü ve uzunluğunu kontrol etme.</li>
  <li>PHPMailer ile E-posta gönderme.</li>
  <li>Dosya yükleme.</li>
  <li>Sayfa oluşturma ve yazdırma.</li>
  <li>Başlık ve menü oluşturma.</li>
  <li>Başlık, paragraf ve link oluşturma.</li>
  <li>Tablo oluşturma.</li>
  <li>Form oluşturma.</li>
</ul>
<p><b>Dosyalar</b></p>
<ul>
  <li>hia.php : php kütüphanesi. Kütüpheneyi kullanacağınız dosyalara inlude etmeniz yeterli.</li>
  <li>hia.css : stil dosyasıdır.</li>
  <li>index.php : örnek sayfa.</li>
</ul>
<p><b>Örnek kullanımı</b></p>
<code>
include "hia.php";
$hia = new hia();
$hia->page("icon.png",
    "Merhaba dünya!",
    "Bu bir hia.php merhaba dünya sayfası.",
    $hia->title("1", "Merhaba Dünya!")
);
</code>
