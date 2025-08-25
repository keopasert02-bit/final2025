</div> <!-- end .container -->
  </div> <!-- end .content-wrapper -->

  <!-- Footer -->
  <footer class="pt-5 pb-4 mt-5">
    <div class="container">
      <div class="row g-5">
        <!-- Column 1 -->
        <div class="col-md-4 text-center text-md-start">
          <h5>
            <i class="bi bi-flower1"></i> ວັດສະພັງໝໍ້
          </h5>
          <p class="text-muted fs-5">
            ເວັບໄຊທ໌ຈັດການຂໍ້ມູນ ແລະ ກິດຈະກໍາທາງສາສະໜາ.
          </p>
          <div class="social-links d-flex gap-3 mt-4 justify-content-center justify-content-md-start">
            <a href="#" title="Facebook"><i class="bi bi-facebook text-primary"></i></a>
            <a href="#" title="Line"><i class="bi bi-line text-success"></i></a>
            <a href="#" title="Instagram"><i class="bi bi-instagram text-danger"></i></a>
            <a href="#" title="YouTube"><i class="bi bi-youtube text-danger"></i></a>
          </div>
        </div>
        <!-- Column 2 -->
        <div class="col-md-4 text-center text-md-start">
          <h6>ເມນູເວັບ</h6>
          <ul class="list-unstyled fs-5">
            <li class="mb-3"><a href="index.php">ຂໍ້ມູນວັດ</a></li>
            <li class="mb-3"><a href="member.php">ພຣະສົງ</a></li>
            <li class="mb-3"><a href="temple_events_public.php">ງານບຸນ</a></li>
          </ul>
        </div>
        <!-- Column 3 -->
        <div class="col-md-4 text-center text-md-start">
          <h6>ຕິດຕໍ່</h6>
          <p class="text-muted fs-5">
            ນະຄອນຫຼວງວຽງຈັນ<br>
            +856 20 xxx-xxx<br>
            info@temple.la
          </p>
        </div>
      </div>
      <div class="text-center text-muted mt-5 pt-4 fs-6" style="border-top: 1px solid rgba(139,69,19,0.1);">
        © 2025 ວັດສະພັງໝໍ້. ສະຫງວນລິຂະສິດ.
      </div>
    </div>
  </footer>
</div> <!-- end .page-wrapper -->

<style>
  /* Footer Styles */
  footer {
    background: linear-gradient(135deg, #fdfdfb 0%, var(--temple-light) 100%);
    border-top: 4px solid var(--temple-gold);
    position: relative;
    overflow: hidden;
  }

  footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="footer-pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M20 5 L25 15 L35 15 L27 23 L30 33 L20 27 L10 33 L13 23 L5 15 L15 15 Z" fill="rgba(139,69,19,0.03)"/></pattern></defs><rect width="100%" height="100%" fill="url(%23footer-pattern)"/></svg>');
    opacity: 0.5;
    z-index: 1;
  }

  footer .container {
    position: relative;
    z-index: 2;
  }

  footer h5 {
    font-size: 1.8rem;
    font-weight: 700;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 1.5rem;
  }

  footer h5 i {
    background: var(--gradient-gold);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-right: 10px;
  }

  footer h6 {
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--temple-brown);
    margin-bottom: 1.2rem;
    position: relative;
  }

  footer h6::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 40px;
    height: 3px;
    background: var(--gradient-gold);
    border-radius: 2px;
  }

  .social-links a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fff 0%, #f8f8f8 100%);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    text-decoration: none;
  }

  .social-links a:hover {
    transform: translateY(-5px) scale(1.1);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
  }

  .social-links a i {
    font-size: 1.3rem;
  }

  footer ul li a {
    color: var(--temple-dark);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-block;
    position: relative;
  }

  footer ul li a::before {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--gradient-gold);
    transition: width 0.3s ease;
  }

  footer ul li a:hover {
    color: var(--temple-brown);
    transform: translateX(10px);
  }

  footer ul li a:hover::before {
    width: 100%;
  }

  /* Responsive Design for Footer */
  @media (max-width: 768px) {
    footer h5 {
      font-size: 1.5rem;
    }

    footer h6 {
      font-size: 1.2rem;
    }

    .social-links {
      justify-content: center !important;
    }

    .social-links a {
      width: 45px;
      height: 45px;
    }
  }

  @media (max-width: 576px) {
    footer {
      text-align: center;
    }

    footer h6::after {
      left: 50%;
      transform: translateX(-50%);
    }
  }
</style>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  // Initialize AOS
  AOS.init({
    duration: 1000,
    once: true,
    offset: 100
  });

  // Active nav link
  const currentPath = window.location.pathname.split('/').pop();
  document.querySelectorAll('.nav-link').forEach(link => {
    const href = link.getAttribute('href');
    if (href && currentPath === href) {
      link.classList.add('active');
    }
  });

  // Smooth dropdown animations
  document.querySelectorAll('.dropdown').forEach(dropdown => {
    const menu = dropdown.querySelector('.dropdown-menu');
    
    dropdown.addEventListener('show.bs.dropdown', () => {
      menu.style.transform = 'translateY(-10px)';
      menu.style.opacity = '0';
      setTimeout(() => {
        menu.style.transform = 'translateY(0)';
        menu.style.opacity = '1';
      }, 10);
    });
  });

  // Enhanced scroll behavior
  let lastScrollTop = 0;
  const navbar = document.querySelector('.navbar');
  
  window.addEventListener('scroll', () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > 100) {
      navbar.style.background = 'linear-gradient(135deg, rgba(139, 69, 19, 0.95) 0%, rgba(166, 92, 33, 0.95) 50%, rgba(193, 118, 49, 0.95) 100%)';
      navbar.style.backdropFilter = 'blur(20px)';
    } else {
      navbar.style.background = 'linear-gradient(135deg, #8b4513 0%, #a65c21 50%, #c17631 100%)';
      navbar.style.backdropFilter = 'blur(10px)';
    }
    
    lastScrollTop = scrollTop;
  });

  // Add loading states for better UX
  document.querySelectorAll('a[href]').forEach(link => {
    if (!link.href.startsWith('#')) {
      link.addEventListener('click', (e) => {
        if (!e.ctrlKey && !e.metaKey) {
          link.style.opacity = '0.7';
          link.style.transform = 'scale(0.98)';
        }
      });
    }
  });

  // Custom JavaScript for this page
  <?php if (isset($additional_js)) echo $additional_js; ?>
</script>
<script>
function showLoginAlert(event) {
  event.preventDefault();

  Swal.fire({
    icon: 'warning',
    title: 'ທ່ານຈຳເປັນຕ້ອງເຂົ້າລະບົບ',
    text: 'ເຂົ້າລະບົບເພື່ອເບິ່ງລາຍລະອຽດໜ້າວຽກຂອງພຣະ',
    showCancelButton: true,
    confirmButtonText: 'ເຂົ້າລະບົບ',
    cancelButtonText: 'ຍົກເລີກ',
    confirmButtonColor: '#6f42c1',
    cancelButtonColor: '#6c757d',
    reverseButtons: true,
    customClass: {
      popup: 'shadow-lg rounded-4 px-3 py-4',
      title: 'fw-bold',
      confirmButton: 'btn btn-primary',
      cancelButton: 'btn btn-secondary me-2'
    },
    buttonsStyling: false
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = '../login.php';
    }
  });
}
</script>
<script>
  function logoutConfirm(e) {
    e.preventDefault(); // ป้องกันไม่ให้ลิงก์ทำงานทันที

    Swal.fire({
      title: 'ທ່ານຕ້ອງການອອກຈາກລະບົບບໍ່?',
      text: "ຖ້າກົດຢືນຢັນ ທ່ານຈະຖືກອອກຈາກລະບົບ",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'ອອກຈາກລະບົບ',
      cancelButtonText: 'ຍົກເລີກ'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "../logout.php";
      }
    });
  }
</script>

</body>
</html>