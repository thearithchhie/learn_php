<footer class="bg-primary text-white mt-5 pt-4">
    <!-- Cambodia skyline silhouette -->
    <div class="container">
        <div class="text-center mb-4">
            <img src="/assets/images/skyline-silhouette.png" alt="Skyline" class="img-fluid" style="max-height: 80px;">
        </div>
    </div>
    
    <div class="container py-4">
        <div class="row">
            <!-- Follow Us -->
            <div class="col-md-3 mb-4">
                <h5 class="mb-3">Follow JobBoard</h5>
                <div class="d-flex gap-3">
                    <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white fs-5"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="text-white fs-5"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white fs-5"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            
            <!-- Customer Service -->
            <div class="col-md-3 mb-4">
                <h5 class="mb-3">Customer Service</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/contact" class="text-white text-decoration-none">Contact Us</a></li>
                    <li class="mb-2"><a href="/privacy" class="text-white text-decoration-none">Privacy Policy</a></li>
                    <li class="mb-2"><a href="/terms" class="text-white text-decoration-none">Terms of Service</a></li>
                    <li class="mb-2"><a href="/account/delete" class="text-white text-decoration-none">Account Deletion</a></li>
                </ul>
            </div>
            
            <!-- Useful Information -->
            <div class="col-md-3 mb-4">
                <h5 class="mb-3">Useful Information</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/safety-tips" class="text-white text-decoration-none">Safety Tips</a></li>
                    <li class="mb-2"><a href="/posting-rules" class="text-white text-decoration-none">Job Posting Rules</a></li>
                    <li class="mb-2"><a href="/feedback" class="text-white text-decoration-none">Feedback</a></li>
                    <li class="mb-2"><a href="/faq" class="text-white text-decoration-none">FAQ</a></li>
                </ul>
            </div>
            
            <!-- Download App -->
            <div class="col-md-3 mb-4">
                <h5 class="mb-3">Download JobBoard app for FREE</h5>
                <div class="qr-code mb-3">
                    <img src="/assets/images/qr-code.png" alt="QR Code" class="img-fluid" style="max-width: 120px;">
                </div>
                <div class="d-flex flex-column gap-2">
                    <a href="#" class="text-white text-decoration-none">
                        <img src="/assets/images/app-store.png" alt="App Store" class="img-fluid" style="max-height: 40px;">
                    </a>
                    <a href="#" class="text-white text-decoration-none">
                        <img src="/assets/images/google-play.png" alt="Google Play" class="img-fluid" style="max-height: 40px;">
                    </a>
                    <a href="#" class="text-white text-decoration-none">
                        <img src="/assets/images/appgallery.png" alt="AppGallery" class="img-fluid" style="max-height: 40px;">
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Payment Methods -->
    <div class="container-fluid bg-dark py-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small>&copy; 2023 JobBoard. All rights reserved.</small>
                </div>
                <div>
                    <span class="me-2">We Accept:</span>
                    <img src="/assets/images/khmer-pay.png" alt="KhmerPay" height="30">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Back to Top Button -->
    <a href="#" class="position-fixed bottom-0 end-0 p-3 m-3 bg-secondary rounded-circle text-white" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
        <i class="bi bi-arrow-up"></i>
    </a>
</footer>

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script src="/assets/js/main.js"></script>

<!-- Script for Save Job functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Save job buttons
    const saveJobButtons = document.querySelectorAll('.save-job-btn');
    if (saveJobButtons.length > 0) {
        saveJobButtons.forEach(button => {
            button.addEventListener('click', function() {
                const jobId = this.getAttribute('data-job-id');
                
                // Check if user is logged in
                <?php if (isset($_SESSION['user_id'])): ?>
                    // Toggle bookmark icon
                    const icon = this.querySelector('i');
                    if (icon.classList.contains('bi-bookmark')) {
                        icon.classList.replace('bi-bookmark', 'bi-bookmark-fill');
                        saveJob(jobId);
                    } else {
                        icon.classList.replace('bi-bookmark-fill', 'bi-bookmark');
                        unsaveJob(jobId);
                    }
                <?php else: ?>
                    // Redirect to login page
                    window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
                <?php endif; ?>
            });
        });
    }
    
    function saveJob(jobId) {
        fetch('/saved-jobs/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'job_id=' + jobId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Job saved successfully');
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function unsaveJob(jobId) {
        fetch('/saved-jobs/unsave', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'job_id=' + jobId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Job unsaved successfully');
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    // Back to top button
    const backToTopButton = document.querySelector('a[href="#"]');
    if (backToTopButton) {
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Show/hide button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopButton.style.display = 'flex';
            } else {
                backToTopButton.style.display = 'none';
            }
        });
    }
});
</script>
</body>
</html>