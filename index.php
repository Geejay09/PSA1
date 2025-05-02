<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - PSA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
</head>
<body>
  <!-- New Background Elements -->
  <div class="bg-particles">
    <div class="particle particle-1"></div>
    <div class="particle particle-2"></div>
    <div class="particle particle-3"></div>
    <div class="particle particle-4"></div>
    <div class="particle particle-5"></div>
    <div class="particle particle-6"></div>
  </div>
  
  <div class="bg-shapes">
    <div class="shape shape-triangle"></div>
    <div class="shape shape-circle"></div>
    <div class="shape shape-square"></div>
  </div>

  <div class="login-wrapper">
    <!-- Floating Logo -->
    <div class="logo-container">
      <div class="logo-border">
        <img src="assets/psa.png" alt="Company Logo" class="logo-img">
      </div>
    </div>

    <!-- Main Container -->
    <div class="login-container">
      <!-- Left Panel - Welcome Section -->
      <div class="welcome-panel">
        <div class="welcome-content">
          <h1>Welcome to <span>P.R.I.S.M</span></h1>
          <p class="welcome-text">Property Requisition, Issuance, and Stock Management</p>
          <p class="system-description">A system by the Philippine Statistics Authority for managing supply documents, reports, and inventory with ease and accuracy.</p>
        </div>
      </div>

      <!-- Right Panel - Login Form -->
      <div class="login-panel">
        <div class="login-content">
          <h2>Sign In</h2>
          <p class="login-subtitle">Please Enter your credentials to continue</p>
          
          <form id="loginForm" class="login-form">
            <div class="form-group">
              <label for="email">Email Address</label>
              <div class="input-group">
                <i class="bi bi-envelope-at"></i>
                <input type="email" id="email" name="email" placeholder="your@gmail.com" required>
              </div>
            </div>
            
            <div class="form-group">
              <label for="password">Password</label>
              <div class="input-group">
                <i class="bi bi-lock"></i>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
                <i class="bi bi-eye-fill toggle-password" onclick="togglePassword()"></i>
              </div>
            </div>
            
            <div class="form-options">
              <a href="#" class="forgot-password">Forgot password?</a>
            </div>
            
            <button type="submit" class="login-button">
              <span>Sign In</span>
              <i class="bi bi-arrow-right"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript remains the same as before -->
  <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const form = e.target;
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalBtnText = submitBtn.innerHTML;
      
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span>Signing In...</span><i class="bi bi-arrow-repeat bi-spin"></i>';
      
      const formData = new FormData(form);
      
      fetch('login.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              Swal.fire({
                  icon: 'success',
                  title: `Welcome, ${data.user.name}!`,
                  text: `Logged in as ${data.user.position}`,
                  timer: 1500,
                  showConfirmButton: false,
                  willClose: () => {
                      window.location.href = 'home.php';
                  }
              });
          } else {
              throw new Error(data.message || 'Login failed');
          }
      })
      .catch(error => {
          Swal.fire({
              icon: 'error',
              title: 'Login Failed',
              text: error.message,
              confirmButtonColor: '#64ffda'
          });
      })
      .finally(() => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
      });
    });

    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.querySelector('.toggle-password');
      
      if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          toggleIcon.classList.remove('bi-eye-fill');
          toggleIcon.classList.add('bi-eye-slash-fill');
      } else {
          passwordInput.type = 'password';
          toggleIcon.classList.remove('bi-eye-slash-fill');
          toggleIcon.classList.add('bi-eye-fill');
      }
    }
  </script>

  <style>
    :root {
      --primary: #64ffda;
      --primary-dark: #1ce9b6;
      --primary-light: rgba(100, 255, 218, 0.1);
      --accent: #00b4d8;
      --dark: #0a192f;
      --darker: #020c1b;
      --light: #ccd6f6;
      --lighter: #e6f1ff;
      --gray: #8892b0;
      --border: rgba(100, 255, 218, 0.2);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--darker);
      color: var(--light);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      line-height: 1.6;
      position: relative;
      overflow: hidden;
    }

    /* New Background Styles */
    .bg-particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      overflow: hidden;
    }

    .particle {
      position: absolute;
      background: rgba(100, 255, 218, 0.08);
      border-radius: 50%;
      animation: float 15s infinite linear;
    }

    .particle-1 {
      width: 10px;
      height: 10px;
      top: 20%;
      left: 10%;
      animation-duration: 20s;
      background: var(--primary);
    }

    .particle-2 {
      width: 8px;
      height: 8px;
      top: 60%;
      left: 85%;
      animation-duration: 25s;
      animation-delay: 2s;
      background: var(--accent);
    }

    .particle-3 {
      width: 6px;
      height: 6px;
      top: 80%;
      left: 15%;
      animation-duration: 18s;
      animation-delay: 4s;
      background: var(--primary-dark);
    }

    .particle-4 {
      width: 12px;
      height: 12px;
      top: 30%;
      left: 70%;
      animation-duration: 22s;
      animation-delay: 1s;
      background: var(--accent);
    }

    .particle-5 {
      width: 5px;
      height: 5px;
      top: 10%;
      left: 50%;
      animation-duration: 30s;
      animation-delay: 3s;
      background: var(--primary);
    }

    .particle-6 {
      width: 7px;
      height: 7px;
      top: 70%;
      left: 30%;
      animation-duration: 17s;
      animation-delay: 5s;
      background: var(--primary-dark);
    }

    .bg-shapes {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -2;
    }

    .shape {
      position: absolute;
      opacity: 0.05;
      animation: rotate 60s infinite linear;
    }

    .shape-triangle {
      width: 200px;
      height: 200px;
      top: 10%;
      left: 10%;
      background: transparent;
      border-left: 3px solid var(--primary);
      border-right: 3px solid transparent;
      border-bottom: 3px solid transparent;
      clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
      animation-duration: 80s;
    }

    .shape-circle {
      width: 300px;
      height: 300px;
      bottom: 10%;
      right: 10%;
      border: 3px solid var(--accent);
      border-radius: 50%;
      animation-duration: 100s;
      animation-direction: reverse;
    }

    .shape-square {
      width: 150px;
      height: 150px;
      top: 50%;
      left: 80%;
      transform: translate(-50%, -50%) rotate(15deg);
      border: 3px solid var(--primary-dark);
      animation-duration: 70s;
    }

    @keyframes float {
      0% {
        transform: translateY(0) translateX(0);
      }
      50% {
        transform: translateY(-100px) translateX(50px);
      }
      100% {
        transform: translateY(0) translateX(0);
      }
    }

    @keyframes rotate {
      from {
        transform: rotate(0deg);
      }
      to {
        transform: rotate(360deg);
      }
    }

    /* Rest of your styles remain unchanged */
    .login-wrapper {
      width: 100%;
      max-width: 1000px;
      position: relative;
    }

    .logo-container {
      position: absolute;
      top: -50px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 10;
    }

    .logo-border {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: var(--dark);
      border: 2px solid var(--primary);
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 0 0 0 4px rgba(10, 25, 47, 0.9), 0 0 20px rgba(100, 255, 218, 0.4);
      overflow: hidden;
    }

    .logo-img {
      width: 80%;
      height: 80%;
      object-fit: contain;
    }

    .login-container {
      display: flex;
      background: rgba(10, 25, 47, 0.85);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      border: 1px solid var(--border);
      backdrop-filter: blur(8px);
    }

    .welcome-panel {
      flex: 1;
      padding: 40px 30px;
      background: linear-gradient(135deg, rgba(10, 25, 47, 0.9), rgba(23, 42, 69, 0.9));
      display: flex;
      flex-direction: column;
      justify-content: center;
      border-right: 1px solid var(--border);
    }

    .welcome-content {
      max-width: 350px;
      margin: 0 auto;
    }

    .welcome-panel h1 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 15px;
      color: var(--lighter);
    }

    .welcome-panel h1 span {
      background: linear-gradient(90deg, var(--primary), var(--accent));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    .welcome-text {
      color: var(--gray);
      margin-bottom: 15px;
      font-size: 0.95rem;
    }

    .system-description {
      color: var(--gray);
      font-size: 0.85rem;
      line-height: 1.5;
    }

    .login-panel {
      flex: 1;
      padding: 40px 30px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-content {
      max-width: 320px;
      margin: 0 auto;
      width: 100%;
    }

    .login-panel h2 {
      font-size: 1.75rem;
      font-weight: 600;
      margin-bottom: 8px;
      color: var(--lighter);
    }

    .login-subtitle {
      color: var(--gray);
      margin-bottom: 25px;
      font-size: 0.9rem;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: var(--light);
      font-size: 0.85rem;
    }

    .input-group {
      position: relative;
    }

    .input-group i {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray);
      font-size: 0.95rem;
    }

    .input-group .toggle-password {
      left: auto;
      right: 14px;
      cursor: pointer;
      color: var(--gray);
    }

    .input-group input {
      width: 100%;
      padding: 12px 14px 12px 42px;
      border: 1px solid var(--border);
      border-radius: 8px;
      background: rgba(10, 25, 47, 0.5);
      color: var(--lighter);
      font-size: 0.9rem;
      transition: all 0.3s ease;
    }

    .input-group input::placeholder {
      color: var(--gray);
    }

    .input-group input:focus {
      border-color: var(--primary);
      outline: none;
      box-shadow: 0 0 0 3px rgba(100, 255, 218, 0.2);
    }

    .form-options {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 20px;
      font-size: 0.8rem;
    }

    .forgot-password {
      color: var(--primary);
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .forgot-password:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .login-button {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: var(--dark);
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.95rem;
      cursor: pointer;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 180, 216, 0.3);
    }

    .login-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 180, 216, 0.4);
    }

    .login-button:active {
      transform: translateY(0);
    }

    /* Responsive Design */
    @media (max-width: 992px) {
      .welcome-panel, .login-panel {
        padding: 35px 25px;
      }
    }

    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
      }

      .welcome-panel {
        border-right: none;
        border-bottom: 1px solid var(--border);
        padding: 30px 25px;
      }

      .login-panel {
        padding: 30px 25px;
      }

      .welcome-content, .login-content {
        max-width: 100%;
      }

      .logo-container {
        top: -40px;
      }

      .logo-border {
        width: 80px;
        height: 80px;
      }
    }

    @media (max-width: 576px) {
      .welcome-panel, .login-panel {
        padding: 30px 20px;
      }

      .welcome-panel h1 {
        font-size: 1.75rem;
      }

      .login-panel h2 {
        font-size: 1.5rem;
      }
    }

    @media (max-width: 400px) {
      .welcome-panel, .login-panel {
        padding: 25px 15px;
      }

      .logo-container {
        top: -30px;
      }

      .logo-border {
        width: 60px;
        height: 60px;
      }
    }
  </style>
</body>
</html>