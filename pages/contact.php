<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຕິດຕໍ່ວັດສະພັງໝໍ້ ໄຊຍະຣາມ</title>
    <meta name="description" content="ຂໍ້ມູນຕິດຕໍ່ວັດສະພັງໝໍ້ ໄຊຍະຣາມ ແຂວງຈຳປາສັກ ພ້ອມແຜນທີ່ Google Maps">
    
    <!-- Bootstrap + FontAwesome + Google Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --temple-gold: #d4af37;
            --temple-dark-gold: #b8941f;
            --temple-light-gold: #f4e8c1;
            --temple-brown: #8b4513;
            --temple-cream: #faf5e4;
        }

        * { font-family: 'Noto Sans Lao', sans-serif; }

        body {
            background: linear-gradient(135deg, var(--temple-cream), #fff8e7);
            margin: 0;
        }

        .navbar {
            background-color: var(--temple-gold);
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-item.active .nav-link {
            color: #fffacd !important;
        }

        .temple-header {
            background: linear-gradient(135deg, var(--temple-gold), var(--temple-dark-gold));
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        .temple-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .contact-info-section {
            padding: 40px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .contact-item {
            margin-bottom: 20px;
        }

        .contact-icon {
            color: var(--temple-gold);
            margin-right: 15px;
            font-size: 1.3rem;
            width: 40px;
            height: 40px;
            background: #fff8dc;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .map-frame iframe {
            border: none;
            width: 100%;
            height: 400px;
            border: 6px solid var(--temple-light-gold);
            border-radius: 10px;
        }

        footer {
            background-color: var(--temple-dark-gold);
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container my-5">
    <div class="temple-header">
        <h2><i class="fas fa-phone-alt me-2"></i>ຕິດຕໍ່ວັດສະພັງໝໍ້ ໄຊຍະຣາມ</h2>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="contact-info-section">
                <h4 class="mb-4 text-dark"><i class="fas fa-info-circle me-2"></i>ຂໍ້ມູນຕິດຕໍ່</h4>

                <div class="contact-item d-flex">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <strong>ທີ່ຢູ່:</strong><br>
                        ບ້ານສະພັງໝໍ້, ເມືອງໄຊເສດຖາ, ແຂວງຈຳປາສັກ, ສປປ ລາວ
                    </div>
                </div>

                <div class="contact-item d-flex">
                    <div class="contact-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <strong>ເບີໂທ:</strong><br>
                        <a href="tel:+85620XXXXXXX" class="text-break">+856 20 XXXX XXXX</a>
                    </div>
                </div>

                <div class="contact-item d-flex">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <strong>ອີເມວ:</strong><br>
                        <a href="mailto:info@temple.la" class="text-break">info@temple.la</a>
                    </div>
                </div>

                <div class="contact-item d-flex">
                    <div class="contact-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <strong>ເວລາເປີດ:</strong><br>
                        ທຸກມື້ 05:00 - 20:00 ໂມງ
                    </div>
                </div>
            </div>
        </div>

     <div class="col-lg-6" data-aos="fade-left">
  <div class="map-frame rounded-4 shadow">
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3434.577420863893!2d102.63259187463953!3d17.963303185960186!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x312467ee9895655d%3A0xf1eb8bcbf2de20df!2sSaphangmor%20Temple!5e1!3m2!1sen!2sus!4v1753150637943!5m2!1sen!2sus" 
      width="100%" 
      height="350" 
      style="border:0;" 
      allowfullscreen="" 
      loading="lazy" 
      referrerpolicy="no-referrer-when-downgrade"
      title="ແຜນທີ່ວັດສະພັງໝໍ້">
    </iframe>
  </div>
</div>


    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
