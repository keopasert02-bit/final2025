<?php $page_title = "ປະຫວັດວັດ - ວັດສະພັງໝໍ້"; ?>
<?php include 'header.php'; ?>

<style>
/* Enhanced Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-40px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(40px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-15px);
    }
}

@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

@keyframes gradientShift {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(163, 108, 44, 0.4);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 20px rgba(163, 108, 44, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(163, 108, 44, 0);
    }
}

/* Enhanced Classes */
.animate-fade-up {
    animation: fadeInUp 1s ease-out forwards;
    opacity: 0;
}

.animate-fade-left {
    animation: fadeInLeft 1s ease-out forwards;
    opacity: 0;
}

.animate-fade-right {
    animation: fadeInRight 1s ease-out forwards;
    opacity: 0;
}

.animate-float {
    animation: float 4s ease-in-out infinite;
}

.text-gold {
    background: linear-gradient(45deg, #a36c2c, #d4af37, #c49a6c);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: gradientShift 3s ease infinite;
    font-weight: 700;
}

.hero-section {
    background: linear-gradient(135deg, 
        rgba(163, 108, 44, 0.1) 0%,
        rgba(196, 154, 108, 0.05) 50%,
        rgba(212, 175, 55, 0.1) 100%);
    border-radius: 25px;
    padding: 3rem;
    margin-bottom: 3rem;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(163, 108, 44, 0.2);
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, 
        transparent, 
        rgba(255, 255, 255, 0.2), 
        transparent);
    animation: shimmer 3s infinite;
}

.history-section {
    background: linear-gradient(135deg, 
        rgba(255, 248, 241, 0.95) 0%,
        rgba(243, 228, 208, 0.9) 100%);
    border-left: 8px solid #c49a6c;
    border-right: 2px solid rgba(196, 154, 108, 0.3);
    padding: 40px;
    border-radius: 20px;
    box-shadow: 
        0 20px 40px rgba(163, 108, 44, 0.1),
        0 5px 15px rgba(0, 0, 0, 0.08),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
    font-size: 1.2rem;
    line-height: 2;
    position: relative;
    margin-bottom: 3rem;
    backdrop-filter: blur(15px);
    transition: all 0.4s ease;
}

.history-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #c49a6c, #d4af37, #a36c2c);
    border-radius: 20px 20px 0 0;
}

.history-section:hover {
    transform: translateY(-5px);
    box-shadow: 
        0 25px 50px rgba(163, 108, 44, 0.15),
        0 10px 25px rgba(0, 0, 0, 0.12);
}

.history-section p {
    text-indent: 3em;
    margin-bottom: 1.8rem;
    color: #5a4a3a;
    font-weight: 400;
    text-align: justify;
    position: relative;
    transition: all 0.3s ease;
}

.history-section p:hover {
    color: #4a3a2a;
    transform: translateX(10px);
}

.history-section p:first-letter {
    font-size: 1.5em;
    font-weight: 700;
    color: #a36c2c;
    text-shadow: 0 2px 4px rgba(163, 108, 44, 0.3);
}

.history-image {
    border: 6px solid #f3e4d0;
    border-radius: 20px;
    max-height: 500px;
    object-fit: cover;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 15px 30px rgba(163, 108, 44, 0.2);
    position: relative;
    overflow: hidden;
}

.history-image:hover {
    transform: scale(1.05) rotateY(5deg);
    border-color: #c49a6c;
    box-shadow: 0 25px 50px rgba(163, 108, 44, 0.3);
}

.gallery-container {
    background: linear-gradient(135deg, 
        rgba(255, 255, 255, 0.95) 0%,
        rgba(248, 245, 240, 0.9) 100%);
    border-radius: 20px;
    padding: 2.5rem;
    backdrop-filter: blur(15px);
    border: 2px solid rgba(163, 108, 44, 0.1);
    box-shadow: 0 10px 30px rgba(163, 108, 44, 0.08);
}

.gallery-image {
    border: 4px solid #f3e4d0;
    border-radius: 15px;
    width: 100%;
    height: 280px;
    object-fit: cover;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.gallery-image:hover {
    transform: scale(1.08) translateY(-10px);
    border-color: #c49a6c;
    box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.2),
        0 0 30px rgba(163, 108, 44, 0.3);
    z-index: 10;
}

.gallery-item {
    position: relative;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
}

.gallery-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, 
        rgba(163, 108, 44, 0.1), 
        transparent 50%, 
        rgba(196, 154, 108, 0.1));
    opacity: 0;
    transition: opacity 0.4s ease;
    border-radius: 15px;
    z-index: 1;
}

.gallery-item:hover::before {
    opacity: 1;
}

.section-title {
    position: relative;
    display: inline-block;
    padding-bottom: 1rem;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #c49a6c, #d4af37, #a36c2c);
    border-radius: 2px;
}

.icon-enhanced {
    text-shadow: 0 0 15px rgba(163, 108, 44, 0.5);
    transition: all 0.3s ease;
    display: inline-block;
}

.icon-enhanced:hover {
    transform: scale(1.2) rotate(10deg);
    text-shadow: 0 0 25px rgba(163, 108, 44, 0.8);
}

.divider-enhanced {
    height: 3px;
    background: linear-gradient(90deg, 
        transparent, 
        #c49a6c 20%, 
        #d4af37 50%, 
        #a36c2c 80%, 
        transparent);
    margin: 3rem auto;
    border-radius: 3px;
    width: 60%;
    position: relative;
    overflow: hidden;
}

.divider-enhanced::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, 
        transparent, 
        rgba(255, 255, 255, 0.8), 
        transparent);
    animation: shimmer 2s infinite;
}

.timeline-dot {
    width: 20px;
    height: 20px;
    background: linear-gradient(45deg, #c49a6c, #d4af37);
    border-radius: 50%;
    position: absolute;
    left: -10px;
    top: 50%;
    transform: translateY(-50%);
    box-shadow: 0 0 15px rgba(163, 108, 44, 0.5);
    animation: pulse 2s infinite;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-section {
        padding: 2rem;
        border-radius: 15px;
    }
    
    .history-section {
        padding: 2rem;
        border-radius: 15px;
        font-size: 1.1rem;
    }
    
    .history-section p {
        text-indent: 2em;
    }
    
    .gallery-image {
        height: 220px;
    }
    
    .gallery-container {
        padding: 1.5rem;
        border-radius: 15px;
    }
}

/* Loading Animation */
.page-loader {
    opacity: 0;
    animation: fadeInUp 0.8s ease-out 0.2s forwards;
}

/* Scroll Progress Bar */
.scroll-progress {
    position: fixed;
    top: 0;
    left: 0;
    width: 0%;
    height: 4px;
    background: linear-gradient(90deg, #c49a6c, #d4af37, #a36c2c);
    z-index: 9999;
    transition: width 0.1s ease;
}
</style>

<!-- Scroll Progress Bar -->
<div class="scroll-progress" id="scrollProgress"></div>

<div class="container my-5 page-loader">
    <!-- Hero Section -->
    <section class="hero-section text-center animate-fade-up">
        <h1 class="display-4 fw-bold text-gold section-title">
            <i class="fas fa-landmark me-3 icon-enhanced animate-float"></i>
            ປະຫວັດວັດສະພັງໝໍ້ ໄຊຍະຣາມ
        </h1>
        <p class="lead text-muted mt-4 fs-3">
            <i class="fas fa-om me-2 icon-enhanced"></i>
            ແຫ່ງພຸດທທຳ ແຫ່ງຈິດໃຈ ແຫ່ງສັນຕິພາບ
            <i class="fas fa-lotus ms-2 icon-enhanced"></i>
        </p>
        <div class="divider-enhanced"></div>
    </section>

    <!-- Temple History Content -->
  <!-- Temple History Content -->
<section class="history-section animate-fade-left py-5">
    <div class="container">
        <div class="timeline-dot mb-3"></div>
        <h3 class="text-gold mb-4">
            <i class="fas fa-scroll me-2 icon-enhanced"></i>
            ຄວາມເປັນມາຂອງວັດ
        </h3>

        <div class="timeline-item">
            <h4 class="text-gold">ສະຕະວັດທີ 19: ການກໍ່ຕັ້ງ ແລະ ຈຸດເລີ່ມຕົ້ນ</h4>
            <p>
                <strong>ວັດສະພັງໝໍ້</strong> ໄດ້ຖືກສ້າງຕັ້ງຂຶ້ນໃນຊ່ວງຕົ້ນສະຕະວັດທີ 19, ເປັນສູນກາງທາງຈິດວິນຍານທີ່ສຳຄັນ ແລະ ເປັນສະຖານທີ່ສັກສິດສຳລັບຊາວບ້ານເພື່ອປະຕິບັດພິທີກຳທາງພຸດທະສາສະໜາ. ການກໍ່ຕັ້ງວັດແຫ່ງນີ້ໄດ້ສະແດງເຖິງຄວາມເລິກເຊິ່ງຂອງສັດທາໃນຊຸມຊົນທ້ອງຖິ່ນ ແລະ ຄວາມຕ້ອງການທີ່ຈະມີສະຖານທີ່ເພື່ອບຳເພັນກຸສົນ ແລະ ສຶກສາທຳ.
            </p>
        </div>

        <div class="timeline-item mt-4">
            <h4 class="text-gold">ການພັດທະນາ ແລະ ການບູລະນະຄືນໃໝ່</h4>
            <p>
                ຕະຫຼອດໄລຍະເວລາ, ວັດແຫ່ງນີ້ໄດ້ຜ່ານການພັດທະນາ ແລະ ການບູລະນະຄືນໃໝ່ຫຼາຍຄັ້ງ. ການປັບປຸງເຫຼົ່ານີ້ບໍ່ພຽງແຕ່ເປັນການຮັກສາສະຖານທີ່ສັກສິດເທົ່ານັ້ນ, ແຕ່ຍັງເປັນການຢືນຢັນເຖິງຄວາມສຳຄັນຂອງພະສົງ ແລະ ພຸດທະສາສະໜາທີ່ເປັນຮາກຖານອັນເລິກເຊິ່ງໃນສັງຄົມລາວ. ແຕ່ລະການບູລະນະໄດ້ເສີມສ້າງບົດບາດຂອງວັດໃຫ້ແຂງແກ່ນຍິ່ງຂຶ້ນ.
            </p>
        </div>

        <div class="timeline-item mt-4">
            <h4 class="text-gold">ບົດບາດສຳຄັນທາງດ້ານການສຶກສາ ແລະ ວັດທະນະທຳ</h4>
            <p>
                ນອກເໜືອຈາກການເປັນສະຖານທີ່ປະຕິບັດທຳ, ວັດສະພັງໝໍ້ ຍັງໄດ້ເຮັດໜ້າທີ່ເປັນສູນກາງທາງດ້ານການສຶກສາ ແລະ ວັດທະນະທຳ. ວັດໄດ້ເປັນບ່ອນສອນພຸດທະທຳ ແລະ ໃຫ້ການສຶກສາແກ່ເຍົາວະຊົນ, ລວມທັງການບວດຊົ່ວຄາວ, ການສອນຫຼັກທຳຕ່າງໆ, ແລະ ການຈັດງານບຸນປະເພນີທີ່ສຳຄັນຕ່າງໆຕະຫຼອດປີ. ບົດບາດນີ້ໄດ້ຊ່ວຍຮັກສາ ແລະ ເຜີຍແຜ່ຄຸນຄ່າທາງພຸດທະສາສະໜາຈາກລຸ້ນສູ່ລຸ້ນ.
            </p>
        </div>

        <div class="timeline-item mt-4">
            <h4 class="text-gold">ມໍລະດົກ ແລະ ອະນາຄົດ</h4>
            <p>
                ມາຮອດປັດຈຸບັນ, ວັດສະພັງໝໍ້ ຍັງຄົງເປັນສະຖານທີ່ສຳຄັນທີ່ຮັກສາໄວ້ເຊິ່ງມໍລະດົກທາງວັດທະນະທຳ ແລະ ຈິດວິນຍານອັນລ້ຳຄ່າຂອງປະເທດລາວ. ວັດແຫ່ງນີ້ເປັນສັນຍາລັກຂອງຄວາມອົດທົນ ແລະ ຄວາມສັດທາ, ເຊິ່ງສືບຕໍ່ສ້າງແຮງບັນດານໃຈໃຫ້ແກ່ຄົນລຸ້ນໃໝ່ ແລະ ເປັນພື້ນຖານທາງສິນທຳຂອງຊຸມຊົນ.
            </p>
        </div>

        <div class="mt-4 text-center">
            <i class="fas fa-dharmachakra text-gold icon-enhanced" style="font-size: 2.5rem;"></i>
        </div>
    </div>
</section>


    <!-- Gallery Section -->
    <section class="animate-fade-right">
        <div class="gallery-container">
            <h3 class="text-center mb-5 text-gold section-title">
                <i class="fas fa-images me-3 icon-enhanced"></i>
                ຮູບບັນຍາກາດພາຍໃນວັດ
            </h3>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="gallery-item">
                    <img src="images/IMG_7949.JPG" alt="ວັດສະພັງໝໍ້ ໃນຍາມເຊົ້າ"class="gallery-image">
                        <div class="position-absolute bottom-0 start-0 end-0 p-3 text-white" 
                             style="background: linear-gradient(transparent, rgba(0,0,0,0.7)); border-radius: 0 0 15px 15px;">
                            <small class="fw-bold">ພະອຸໂບສົດ ແລະ ວິຫານຫຼວງ</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gallery-item">
                        <img src="images/gallery3.jpg" alt="ຮູບວັດ 2" class="gallery-image">
                        <div class="position-absolute bottom-0 start-0 end-0 p-3 text-white" 
                             style="background: linear-gradient(transparent, rgba(0,0,0,0.7)); border-radius: 0 0 15px 15px;">
                            <small class="fw-bold">ກິດຈະກຳທາງທຳປະຈຳວັນ</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gallery-item">
                        <img src="images/gallery1.jpg" alt="ຮູບວັດ 3" class="gallery-image">
                        <div class="position-absolute bottom-0 start-0 end-0 p-3 text-white" 
                             style="background: linear-gradient(transparent, rgba(0,0,0,0.7)); border-radius: 0 0 15px 15px;">
                            <small class="fw-bold">ສະຖາປັດຕະຍະກຳແບບລາວ</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <p class="text-muted fs-5 mb-4">
                    <i class="fas fa-camera me-2"></i>
                    ຮູບບັນຍາກາດພາຍໃນວັດ ແລະ ກິດຈະກຳຕ່າງໆ
                </p>
                <a href="temple_gallery/gallery_list.php" class="btn btn-outline-warning btn-lg px-5 py-3 rounded-pill">
  <i class="fas fa-expand me-2"></i> ເບິ່ງຮູບພາບເພີ່ມເຕີມ
</a>

            </div>
        </div>
    </section>
</div>
<?php include 'footer.php'; ?>
<script>
document.addEventListener('scroll', function() {
  const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
  const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  const scrolled = (scrollTop / scrollHeight) * 100;
  document.getElementById('scrollProgress').style.width = scrolled + '%';
});
</script>
