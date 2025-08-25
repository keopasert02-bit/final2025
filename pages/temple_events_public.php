<?php 
include '../config.php';
include 'header.php';
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">
            <i class="fas fa-calendar-alt me-3"></i> ຂ່າວສານງານບຸນປະເພນີຕ່າງໆຂອງວັດສະພັງໝໍ້
        </h1>
    </div>
</div>

<style>
    .page-header {
        background: linear-gradient(135deg, #d4af37, #b8941f);
        color: white;
        padding: 3rem 1rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .page-title {
        font-size: 2.5rem;
        font-weight: bold;
    }
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
        padding: 2rem 1rem;
    }
    .event-card {
        background: #fffdf5;
        border: 1px solid #e6d8b8;
        border-left: 6px solid #d4af37;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }
    .event-card:hover {
        transform: translateY(-5px);
    }
    .event-title {
        font-weight: 600;
        color: #8b5e3c;
        font-size: 1.2rem;
    }
    .event-date {
        font-size: 0.95rem;
        color: #a87c2e;
        margin-top: 0.5rem;
    }
    .event-description {
        margin-top: 1rem;
        font-size: 1rem;
        background: #fef9e8;
        padding: 1rem;
        border-left: 4px solid #d4af37;
        border-radius: 8px;
    }
    .no-events {
        background: #fffbe8;
        border: 1px dashed #d4af37;
        padding: 3rem;
        text-align: center;
        border-radius: 12px;
        margin: 2rem;
    }
    .info-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        margin: 3rem 1rem;
    }
    .info-card {
        flex: 1;
        min-width: 300px;
        background: #fffdf5;
        border: 1px solid #e9e0c7;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
    }
    .info-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #8b5e3c;
    }
    .info-card-content {
        font-size: 1rem;
        color: #6c5b39;
    }
</style>

<div class="container">
    <div class="events-grid">
        <?php
        $stmt = $conn->prepare("SELECT title, event_date, description FROM temple_events ORDER BY event_date DESC LIMIT 10");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0):
            while ($event = $result->fetch_assoc()):
        ?>
        <div class="event-card">
            <h3 class="event-title"><?= htmlspecialchars($event['title']) ?></h3>
            <div class="event-date">
                <i class="fas fa-calendar-day"></i>
                <?= date('d/m/Y', strtotime($event['event_date'])) ?>
            </div>
            <div class="event-description">
                <?= nl2br(htmlspecialchars($event['description'])) ?>
            </div>
        </div>
        <?php endwhile; else: ?>
        <div class="no-events">
            <i class="fas fa-calendar-times fa-2x mb-3"></i>
            <p>ຍັງບໍ່ມີກິດຈະກໍາໃໝ່ໃນຂະນະນີ້</p>
            <small>ກະລຸນາຕິດຕາມຂ່າວສານຈາກວັດ</small>
        </div>
        <?php endif; ?>
    </div>

    <div class="info-cards">
        <div class="info-card">
            <div class="info-card-title">ເວລາສວດມົນ</div>
            <div class="info-card-content">
                <p>🕯️ ເຊົ້າ: 04:00 - 05:00 ໂມງ</p>
                <p>🕯️ ແລງ: 18:00 - 19:00 ໂມງ</p>
            </div>
        </div>
        <div class="info-card">
            <div class="info-card-title">ການບໍລິການ</div>
            <div class="info-card-content">
                <p>🤝 ຮັບປຶກສາທາງສາສະໜາ</p>
                <p>🎁 ຮັບຖວາຍສັງຄະທານ</p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
