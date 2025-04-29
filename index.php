<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - PSA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
</head>
<body>
<body>
  <div class="main-wrapper">
    <div class="login-box">
      <form id="loginForm" class="login-form">
        <!-- form content -->
        <div class="login-container">

<!-- Welcome Panel -->
<div class="welcome-section">
  <h1>Welcome!</h1>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.</p>
</div>

<!-- Login Panel -->
<div class="login-box">
  <form id="loginForm" class="login-form">
    <h2>Sign in</h2>
    <div class="form-group">
      <label for="username">User Name</label>
      <input type="text" id="username" name="username" placeholder="username" required>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="••••••••" required>
    </div>
    <button type="submit" class="btn-login">Submit</button>
    <div class="options">
      <a href="#">Forgot password?</a>
</div>

</div>
      </form>
    </div>
  </div>
</body>

</body>

  

  <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);

      fetch('login.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Login Successful!',
            text: data.message,
            timer: 1500,
            showConfirmButton: false,
            backdrop: true, // use default backdrop
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false
          }).then(() => {
            window.location.href = 'home.php';
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: data.message
          });
        }
      })
      .catch(() => {
        Swal.fire('Error', 'An error occurred. Please try again.', 'error');
      });
    });
  </script>

<style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      height: 100vh;
      background: url('assets/er.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      margin: 0;
      padding: 0;
    }

    html, body {
  height: 100%;
  margin: 0;
  padding: 0;
  overflow: hidden; /* Prevent scrollbar */
  display: block;
  background: url('assets/er.jpg') no-repeat center center fixed;
  background-size: cover;
  font-family: 'Segoe UI', sans-serif;
  position: relative;
}


.main-wrapper {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

    .login-container {
      display: flex;
      width: 90%;
      max-width: 1000px;
      background-color: rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.6);
    }

    .welcome-section {
      flex: 1;
      padding: 50px;
      background: rgba(0, 0, 0, 0.4);
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .welcome-section h1 {
      font-size: 48px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .welcome-section p {
      font-size: 16px;
      line-height: 1.6;
      margin-bottom: 30px;
    }

    .welcome-section .btn-learn {
      padding: 10px 20px;
      background: linear-gradient(to right,rgb(30, 124, 247),rgb(0, 55, 236));
      border: none;
      border-radius: 30px;
      color: #fff;
      font-weight: bold;
      text-decoration: none;
      width: fit-content;
    }

    .login-box {
      flex: 1;
      padding: 50px;
      background: rgba(255, 255, 255, 0.05);
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-form h2 {
      font-size: 32px;
      text-align: center;
      margin-bottom: 30px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
    }

    .form-group input {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #fff;
      border-radius: 8px;
      background: transparent;
      color: #fff;
    }

    .form-group input::placeholder {
      color: rgba(255, 255, 255, 0.6);
    }

    .form-group input:focus {
      border-color: #fff;
      outline: none;
      box-shadow: 0 0 5px #fff;
    }

    .btn-login {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      background: linear-gradient(to right,rgb(0, 80, 145),rgb(0, 1, 12));
      color: white;
      border: none;
      border-radius: 25px;
      font-weight: bold;
      cursor: pointer;
    }

    .btn-login:hover {
      opacity: 0.9;
    }

    .options {
      text-align: right;
      font-size: 0.9rem;
      margin-top: 10px;
    }

    .options a {
      color: #ddd;
      text-decoration: none;
    }

    .register-link {
      text-align: center;
      margin-top: 20px;
      font-size: 0.9rem;
    }

    .register-link a {
      color: #fff;
      text-decoration: underline;
    }

    .social-icons {
      margin-top: 20px;
      text-align: center;
    }

    .social-icons i {
      font-size: 20px;
      margin: 0 10px;
      color: #fff;
      cursor: pointer;
    }

    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
      }

      .welcome-section, .login-box {
        width: 100%;
        padding: 30px;
      }
    }
  </style>
</html>
