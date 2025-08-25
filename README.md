<?php
include '../config.php';  // ควรอยู่ก่อน header เพื่อให้ $conn พร้อมใช้
include 'header.php';
?>

<style>
/* Modern Clean Styles */
.hero-section {
    background: linear-gradient(135deg, 
        rgba(255, 215, 0, 0.08) 0%, 
        rgba(255, 255, 255, 0.95) 100%);
    border-radius: 16px;
    border: 1px solid rgba(255, 215, 0, 0.2);
    backdrop-filter: blur(10px);
}

.gradient-text {
    background: linear-gradient(45deg, #B8860B, #DAA520, #FFD700);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
}

.modern-card {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.event-item {
    background: linear-gradient(135deg, white 0%, rgba(255, 215, 0, 0.02) 100%);
    border-radius: 12px;
    border-left: 4px solid #DAA520;
    padding: 20px;
    margin-bottom: 16px;
    transition: all 0.3s ease;
}

.event-item:hover {
    transform: translateX(8px);
    box-shadow: 0 4px 16px rgba(218, 165, 32, 0.15);
    border-left-width: 6px;
}

.image-gallery {
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.image-gallery:hover {
    transform: scale(1.02);
}

.section-title {
    font-size: 2.2rem;
    margin-bottom: 2rem;
    text-align: center;
    position: relative;
}

.section-title::after {
    content: '';
    display: block;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #DAA520, #FFD700);
    margin: 16px auto;
    border-radius: 2px;
}

.icon-modern {
    color: #DAA520;
    margin-right: 12px;
    font-size: 1.2em;
}

.text-gold {
    color: #B8860B;
    font-weight: 600;
}

.btn-modern {
    background: linear-gradient(135deg, #DAA520 0%, #FFD700 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(218, 165, 32, 0.2);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(218, 165, 32, 0.3);
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .section-title {
        font-size: 1.8rem;
    }
    
    .hero-section {
        padding: 2rem !important;
    }
    
    .event-item:hover {
        transform: translateX(4px);
    }
}
</style>


<div class="container my-5">
    <!-- Hero Section -->
    <section class="hero-section p-5 mb-5" data-aos="fade-up">
        <div class="text-center">
            <h1 class="display-4 gradient-text mb-4">
                <i class="fas fa-temple icon-modern"></i>
                ຍິນດີຕ້ອນຮັບສູ່ ວັດສະພັງໝໍ້ ໄຊຍະຣາມ
            </h1>
            <p class="lead text-muted mb-4">
                <i class="fas fa-lotus icon-modern"></i>
               "ຄວາມພະຍາຍາມບໍ່ເຄີຍທໍລະຍົດຜູ້ໃດ."
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <img src="images/IMG_7949.JPG" class="img-fluid rounded-4 shadow" alt="ຮູບພາບວັດສະພັງໝໍ້ ໄຊຍະຣາມ" style="max-height: 400px; object-fit: cover; width: 100%;">
                </div>
            </div>
        </div>
    </section>

    <!-- History Section -->
    <section class="mb-5" data-aos="fade-up">
        <div class="modern-card p-5 shadow-lg rounded-4 bg-light">
            <h2 class="section-title gradient-text text-center mb-4">
                <i class="fas fa-landmark icon-modern me-2"></i>
                ປະຫວັດວັດສະພັງໝໍ້
            </h2>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <p class="fs-5 lh-lg text-dark text-indent">ວັດສະພັງໝໍ້ ເມືອງໄຊເສດຖາ ນະຄອນຫລວງວຽງຈັນ

ວັດສະພັງຫມໍ້ ເປັນວັດທີສຳຄັນວັດຫນຶ່ງຂອງເມືອງວຽງຈັນໃນອະດິດ ເພາະເມື່ອກ່ອນບັນດາເຈົ້ານາຍ ຫລືຂ້າຣາຊະການຊັ້ນຜູ້ໃຫຍ່ ກ່ອນຈະໄດ້ຮັບຕຳແຫນ່ງໃຫ້ປົກຄອງບ້ານເມືອງ ກໍ່ຈະໄດ້ມເອົານ້ຳເພື່ອໄປເຮັດພິທີພິພັດສັດຈະຍາບັນ ( ນ້ຳສາບານ )

ບັນດາໜອງສະພັງ ຕ່າງໆ ທີ່ມີຊື່ຂຶ້ນຕົ້ນວ່າ ເຊັ່ນ : ສະພັງໜໍ້ , ສະພັງເມີກ, ສະພັງເລນ ປະຈຸບັນນີ້ເຫລືອແຕ່ 3 ສະພັງ ສະໄຫມກ່ອນນັ້ນມີຫລາຍໆ ເຊັ່ນ : ສະພັງມ່ວງ ( ຜູ້ເຖົ້າເພິ່ນວ່າຢູ່ແຖວໂຮງແຮມຣາຊວົງ ປະຈຸບັນແມ່ນເປັນຕຶກທີສູງທີ່ສຸດໃນປະເທດລາວເຮົາ ແຕ່ຍັງສ້າງບໍ່ທັນແລ້ວເທື່ອ ) ບັນດາສະພັງ ຫລືໜອງເຫລົ່ານີ້ ກ່ຽວພັນກັບເລື່ອງຂອງພຣະຍານາກ ທັງເປັນບ່ອນເຂັດຍຳທັງນັ້ນ ແລະໃນອະດິດເກົ່າກ່ອນ ບັນດາຂ້າຣາຊການ ຫລືເຈົ້ານາຍຂັ້ນແລະຊັ້ນຕ່າງໆ ກ່ອນຈະເຂົ້າຮັບຕຳແຫນ່ງຕ່າງ ເພິ່ນກໍ່ຈະນຳເອົານ້ຳຈາກ 3 ສະພັງ ( ສະພັງເລນ, ສະພັງໝໍ້, ສະພັງເມີກ ) ແລະນ້ຳຈາກ 9 ວັດ ທີ່ສຳຄັນໃນເມືອງວຽງຈັນທນ໌ ເຊັ່ນ : ວັດອົງຕື້, ອິນແປງ, ມີໄຊ, ວັດຈັນ, ຫາຍໂສກ, ຊຽງຍີນ, ສຼີສະເກດ, ເກົ້າຍອດ, ເພຽວັດ ເປັນຕົ້ນ ເພື່ອນຳເອົາໄປເຮັດນ້ຳ ພິພັດສັດຈະຍາບັນ ( ນ້ຳສາບານ ) ພິທີດັ່ງກ່າວ ແມ່ນຈັດຂຶ້ນທີ່ ວັດອົງຕື້ ມະຫາວິຫາຣ ຫລືວັດສີສະເກດ ເພື່ອໃຫ້ບັນດາເຈົ້ານາຍ ແລະຣາຊການຕ່າງໆ ທີ່ຈະຮັບຕ່ຳແຫນ່ງທັງຫລາຍ ດື່ມນ້ຳນີ້ຮ່ວມກັນ ນ້ຳຕ່າງໆນີ້ກໍ່ຈະມີດາບ ມີຫ້ອກ ມີງ້າວ ຈຸ່ມລົງນ້ຳ ເພື່ອເປັນການສາບແຊ່ງ 
ຖ້າເຮັດບໍ່ດີກັບຊາດ ບ້ານເມືອງ ຫລືກັບປະຊາຊົນ ຖ້າເຮັດດີ ຊື່ສັດຕໍ່ປະເທດຊາດ ບ້ານເມືອງ ກໍ່ຂໍໃຫ້ຈະເຣິນຮຸ່ງເຮືອງໄປຫນ້າ ...</p>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="history.php" class="btn btn-outline-warning btn-lg px-5 py-3 rounded-pill shadow-sm">
                    <i class="fas fa-expand me-2"></i> ປະຫວັດວັດເພີ່ມເຕີມ
                </a>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="mb-5" data-aos="fade-up">
        <div class="modern-card p-5">
            <h2 class="section-title gradient-text">
                <i class="fas fa-calendar-check icon-modern"></i>
                ຂ່າວສານງານບຸນປະເພນີຕ່າງໆ
            </h2>

            <?php
            $stmt = $conn->prepare("SELECT title, event_date, description FROM temple_events ORDER BY event_date DESC LIMIT 5");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
                while ($event = $result->fetch_assoc()):
            ?>
                <div class="event-item">
                    <h5 class="text-gold mb-2">
                        <i class="fas fa-star icon-modern text-warning"></i>
                        <?= htmlspecialchars($event['title']) ?>
                    </h5>
                    <small class="text-muted d-block mb-3">
                        <i class="fas fa-calendar-alt icon-modern text-primary"></i>
                        <?= date("d/m/Y", strtotime($event['event_date'])) ?>
                    </small>
                    <p class="mb-0 lh-lg text-dark"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                </div>
            <?php
                endwhile;
            else:
            ?>
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                    <p class="text-muted mt-3 fs-5">ຍັງບໍ່ມີກິດຈະກຳໃໝ່ໃນຂະນະນີ້</p>
                </div>
            <?php endif;
            $stmt->close();
            ?>

        </div>
    </section>

    <!-- Gallery Section -->
    <section data-aos="fade-up">
        <div class="modern-card p-5">
            <h2 class="section-title gradient-text">
                <i class="fas fa-images icon-modern"></i>
                ຮູບພາບບັນຍາກາດພາຍໃນວັດ
            </h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="image-gallery position-relative">
                        <img src="images/gallery2.jpg" class="img-fluid w-100" alt="ບັນຍາກາດພາຍໃນວັດ" style="height: 250px; object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.6));">
                            <small class="text-white fw-medium">ບັນຍາກາດພາຍໃນວັດ</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="image-gallery position-relative">
                        <img src="images/IMG_7949.JPG" class="img-fluid w-100" alt="ບັນຍາກາດພາຍໃນວັດ" style="height: 250px; object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.6));">
                            <small class="text-white fw-medium">ບັນຍາກາດພາຍໃນວັດ</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="image-gallery position-relative">
                        <img src="images/gallery1.jpg" class="img-fluid w-100" alt="ບັນຍາກາດພາຍໃນວັດ" style="height: 250px; object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.6));">
                            <small class="text-white fw-medium">ບັນຍາກາດພາຍໃນວັດ</small>
                        </div>
                    </div>
                </div>
                <!-- รูปอื่นๆ เหมือนเดิม -->
            </div>
        </div>
    </section>

</div>

<?php
$conn->close(); // ปิด DB connection
include 'footer.php';
?>
