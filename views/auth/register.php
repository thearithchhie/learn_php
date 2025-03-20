
<?php require_once '../views/layout/header.php'; ?>
<?php require_once '../views/layout/navigation.php'; ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0">Create an Account</h4>
        </div>
        <div class="card-body">
          <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                  <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          
          <form action="/register" method="POST">
            <div class="mb-3">
              <label class="form-label">Account Type*</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="user_type" id="jobSeeker" value="job_seeker" checked>
                <label class="form-check-label" for="jobSeeker">
                  Job Seeker - I'm looking for a job
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="user_type" id="employer" value="employer">
                <label class="form-check-label" for="employer">
                  Employer - I'm hiring
                </label>
              </div>
            </div>
            
            <div class="mb-3">
              <label for="name" class="form-label">Full Name*</label>
              <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="mb-3">
              <label for="email" class="form-label">Email Address*</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
              <label for="password" class="form-label">Password*</label>
              <input type="password" class="form-control" id="password" name="password" required>
              <div class="form-text">Must be at least 8 characters with letters, numbers, and special characters.</div>
            </div>
            
            <div class="mb-3">
              <label for="password_confirm" class="form-label">Confirm Password*</label>
              <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
            </div>
            
            <div id="companyFields" style="display: none;">
              <div class="mb-3">
                <label for="company_name" class="form-label">Company Name*</label>
                <input type="text" class="form-control" id="company_name" name="company_name">
              </div>
              
              <div class="mb-3">
                <label for="company_website" class="form-label">Company Website</label>
                <input type="url" class="form-control" id="company_website" name="company_website">
              </div>
            </div>
            
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
              <label class="form-check-label" for="terms">I agree to the <a href="/terms" target="_blank">Terms of Service</a> and <a href="/privacy" target="_blank">Privacy Policy</a></label>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Register</button>
          </form>
          
          <div class="mt-3 text-center">
            Already have an account? <a href="/login">Login here</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Show/hide company fields based on account type selection
  document.addEventListener('DOMContentLoaded', function() {
    const accountTypes = document.querySelectorAll('input[name="user_type"]');
    const companyFields = document.getElementById('companyFields');
    
    accountTypes.forEach(function(radio) {
      radio.addEventListener('change', function() {
        if (this.value === 'employer') {
          companyFields.style.display = 'block';
          document.getElementById('company_name').required = true;
        } else {
          companyFields.style.display = 'none';
          document.getElementById('company_name').required = false;
        }
      });
    });
  });
</script>

<?php require_once '../views/layout/footer.php'; ?>