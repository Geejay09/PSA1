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
  <!-- Background Elements -->
  <div class="bg-shape shape-1"></div>
  <div class="bg-shape shape-2"></div>
  <div class="bg-shape shape-3"></div>
  <div class="horizon-line"></div>
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>

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
          <h1>Welcome to <span>PRISM</span></h1>
          <p class="welcome-text">Property Requisition, Issuance, and Stock Management</p>
          <p3>A system by the Philippine Statistics Authority for managing supply documents, reports, and inventory with ease and accuracy.</p3>
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
                <input type="email" id="email" name="email" placeholder="your@email.com" required>
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

  <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    
    // Disable button and show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span>Signing In...</span><i class="bi bi-arrow-repeat bi-spin"></i>';
    
    const formData = new FormData(form);
    
    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            // Get error message from response
            return response.json().then(err => {
                throw new Error(err.message || 'Login failed');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: `Welcome, ${data.user.name}!`,
                text: `Logged in as ${data.user.position}`,
                timer: 1500,
                showConfirmButton: false,
                backdrop: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                willClose: () => {
                    window.location.href = 'home.php';
                }
            });
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: error.message,
            confirmButtonColor: '#64ffda',
            background: 'var(--dark)',
            color: 'var(--light)'
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

// Add particles animation
document.addEventListener('DOMContentLoaded', function() {
    const particles = document.querySelectorAll('.particle');
    particles.forEach(particle => {
        // Randomize animation duration and delay
        const duration = Math.random() * 20 + 10;
        const delay = Math.random() * 5;
        particle.style.animation = `particles ${duration}s ${delay}s infinite linear`;
        
        // Randomize starting position
        particle.style.left = `${Math.random() * 100}vw`;
    });
});

async function addEmployee(formData) {
    try {
        const response = await fetch('add_employee.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to add employee');
        }

        Swal.fire({
            icon: 'success',
            title: 'Employee Added',
            html: `
                <p>${result.message}</p>
                <div class="employee-details">
                    <p><strong>Name:</strong> ${result.user.name}</p>
                    <p><strong>Email:</strong> ${result.user.email}</p>
                    <p><strong>Position:</strong> ${result.user.position}</p>
                    <p><strong>Access Level:</strong> ${result.user.access_level}</p>
                </div>
            `,
            confirmButtonColor: '#64ffda',
            background: 'var(--dark)',
            color: 'var(--light)'
        });

        // Reset form or redirect
        form.reset();

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message,
            confirmButtonColor: '#64ffda',
            background: 'var(--dark)',
            color: 'var(--light)'
        });
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
      background: linear-gradient(135deg, var(--darker), var(--dark));
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

    /* Background Elements */
    .bg-shape {
      position: fixed;
      border-radius: 50%;
      filter: blur(60px);
      opacity: 0.15;
      z-index: -1;
    }

    .shape-1 {
      width: 300px;
      height: 300px;
      background: var(--primary);
      top: -100px;
      left: -100px;
      animation: float 25s infinite alternate ease-in-out;
    }

    .shape-2 {
      width: 400px;
      height: 400px;
      background: var(--accent);
      bottom: -150px;
      right: -100px;
      animation: float 30s infinite alternate-reverse ease-in-out;
    }

    .shape-3 {
      width: 200px;
      height: 200px;
      background: var(--primary-dark);
      top: 40%;
      left: 60%;
      animation: float 20s infinite alternate ease-in-out;
    }

    .horizon-line {
      position: fixed;
      bottom: 25%;
      left: 0;
      width: 100%;
      height: 1px;
      background: linear-gradient(90deg, 
        transparent, 
        rgba(100, 255, 218, 0.3), 
        transparent);
      box-shadow: 0 0 10px rgba(100, 255, 218, 0.2);
      transform: rotate(-1deg);
      z-index: -1;
    }

    .particle {
      position: fixed;
      background: var(--light);
      border-radius: 50%;
      opacity: 0;
      animation: particles 15s infinite linear;
      z-index: -1;
    }

    @keyframes float {
      0%, 100% { transform: translate(0, 0); }
      25% { transform: translate(5%, 5%); }
      50% { transform: translate(10%, -5%); }
      75% { transform: translate(-5%, 10%); }
    }

    @keyframes particles {
      0% {
        transform: translateY(0) translateX(0);
        opacity: 0;
      }
      10% {
        opacity: 0.1;
      }
      100% {
        transform: translateY(-100vh) translateX(20px);
        opacity: 0;
      }
    }

    .particle:nth-child(1) {
      width: 2px;
      height: 2px;
      top: 20vh;
      left: 20vw;
      animation-delay: 0s;
    }
    .particle:nth-child(2) {
      width: 1px;
      height: 1px;
      top: 60vh;
      left: 75vw;
      animation-delay: 3s;
    }
    .particle:nth-child(3) {
      width: 1.5px;
      height: 1.5px;
      top: 80vh;
      left: 50vw;
      animation-delay: 6s;
    }

    .login-wrapper {
      width: 100%;
      max-width: 1000px;
      position: relative;
    }

    /* Logo Container */
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

    /* Main Login Container */
    .login-container {
      display: flex;
      background: rgba(10, 25, 47, 0.8);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      border: 1px solid var(--border);
      backdrop-filter: blur(10px);
    }

    /* Welcome Panel */
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
      margin-bottom: 30px;
      font-size: 0.95rem;
    }

    .features-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .feature {
      display: flex;
      align-items: center;
      gap: 10px;
      color: var(--light);
      font-size: 0.9rem;
    }

    .feature i {
      color: var(--primary);
      font-size: 1rem;
    }

    /* Login Panel */
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

    /* Form Styles */
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

    /* Form Options */
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

    /* Login Button */
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