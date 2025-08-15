<?php
// Copyright @ISmartCoder
// Updates Channel t.me/TheSmartDev 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    $country_code = isset($_POST['country_code']) ? filter_var($_POST['country_code'], FILTER_SANITIZE_STRING) : 'BD';
    $amount = 1; // Fixed to 1 as per API example
    $url = "https://a360api-c8fbf2fa3cda.herokuapp.com/fake/address?code={$country_code}&amount={$amount}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0']);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    if ($response === false || $http_code !== 200) {
        echo json_encode(['error' => 'Failed to connect to API: ' . ($curl_error ?: 'HTTP ' . $http_code)]);
        exit;
    }
    $data = json_decode($response, true);
    if ($data && !isset($data['error'])) {
        echo json_encode([
            'status' => 'SUCCESS',
            'street_address' => isset($data['street_address']) ? htmlspecialchars($data['street_address']) : 'Unknown',
            'street_name' => isset($data['street_name']) ? htmlspecialchars($data['street_name']) : 'Unknown',
            'building_number' => isset($data['building_number']) ? htmlspecialchars($data['building_number']) : 'Unknown',
            'city' => isset($data['city']) ? htmlspecialchars($data['city']) : 'Unknown',
            'state' => isset($data['state']) ? htmlspecialchars($data['state']) : 'Unknown',
            'postal_code' => isset($data['postal_code']) ? htmlspecialchars($data['postal_code']) : 'Unknown',
            'country' => isset($data['country']) ? htmlspecialchars($data['country']) : 'Unknown',
            'country_code' => isset($data['country_code']) ? htmlspecialchars($data['country_code']) : 'Unknown',
            'currency' => isset($data['currency']) ? htmlspecialchars($data['currency']) : 'Unknown',
            'person_name' => isset($data['person_name']) ? htmlspecialchars($data['person_name']) : 'Unknown',
            'gender' => isset($data['gender']) ? htmlspecialchars($data['gender']) : 'Unknown',
            'phone_number' => isset($data['phone_number']) ? htmlspecialchars($data['phone_number']) : 'Unknown',
            'country_flag' => isset($data['country_flag']) ? htmlspecialchars($data['country_flag']) : '🇺🇳'
        ]);
    } else {
        echo json_encode([
            'status' => 'ERROR',
            'street_address' => 'Unknown',
            'street_name' => 'Unknown',
            'building_number' => 'Unknown',
            'city' => 'Unknown',
            'state' => 'Unknown',
            'postal_code' => 'Unknown',
            'country' => 'Unknown',
            'country_code' => 'Unknown',
            'currency' => 'Unknown',
            'person_name' => 'Unknown',
            'gender' => 'Unknown',
            'phone_number' => 'Unknown',
            'country_flag' => '🇺🇳'
        ]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta name="description" content="Smart Fake Address Generator: Generate realistic fake addresses with ease. Developed by Abir Arafat Chawdhury (@ISmartCoder).">
  <meta name="keywords" content="Fake Address Generator, address generator, @ISmartCoder">
  <meta name="author" content="Abir Arafat Chawdhury (@ISmartCoder)">
  <title>Smart Fake Address Generator</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      color: #1f2937;
      overflow-x: hidden;
      overflow-y: auto;
      margin: 0;
      background: linear-gradient(135deg, #e0e7ff, #f3e8ff);
    }
    #three-canvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      opacity: 0.6;
    }
    .section-card {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(20px);
      border-radius: 2rem;
      padding: 2rem;
      margin: 2rem auto;
      box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
      border: 1px solid rgba(209, 213, 219, 0.4);
      max-width: 90vw;
      position: relative;
      z-index: 10;
      animation: fadeIn 1s ease-in-out;
    }
    @media (min-width: 640px) {
      .section-card {
        padding: 3rem;
        max-width: 80vw;
      }
    }
    .tool-card {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(240, 248, 255, 0.9));
      backdrop-filter: blur(12px);
      border-radius: 1.5rem;
      padding: 2.5rem;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      max-width: 95vw;
      border: 2px solid rgba(209, 213, 219, 0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .tool-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 60px rgba(0, 0, 0, 0.15);
    }
    @media (min-width: 640px) {
      .tool-card {
        padding: 3.5rem;
        max-width: 75vw;
      }
    }
    .input-field {
      background: rgba(255, 255, 255, 0.9);
      color: #1f2937;
      border: 1px solid #d1d5db;
      padding: 1rem;
      border-radius: 0.75rem;
      width: 100%;
      font-size: 1rem;
      transition: all 0.3s ease;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      animation: slideIn 0.5s ease-in-out;
    }
    .input-field:focus {
      outline: none;
      border-color: #7c3aed;
      box-shadow: 0 0 12px rgba(124, 58, 237, 0.3);
      transform: scale(1.02);
    }
    .button {
      background: linear-gradient(90deg, #7c3aed, #db2777);
      color: #ffffff;
      padding: 1rem 3rem;
      border-radius: 0.75rem;
      font-size: 1.1rem;
      font-weight: 600;
      box-shadow: 0 5px 20px rgba(124, 58, 237, 0.4);
      transition: all 0.3s ease;
      animation: pulseButton 2s infinite;
    }
    .button:hover {
      box-shadow: 0 0 25px rgba(124, 58, 237, 0.6);
      transform: translateY(-3px);
    }
    .button:disabled {
      background: #9ca3af;
      box-shadow: none;
      cursor: not-allowed;
    }
    .error {
      color: #ef4444;
      text-align: center;
      margin-top: 1.5rem;
      font-size: 1.1rem;
      font-weight: 500;
      animation: shake 0.3s ease-in-out;
    }
    .result-box {
      background: rgba(255, 255, 255, 0.9);
      border: 1px solid #e5e7eb;
      border-radius: 1rem;
      padding: 2rem;
      margin-top: 1.5rem;
      font-family: 'Courier New', monospace;
      font-size: 1rem;
      line-height: 1.8;
      color: #1f2937;
      box-shadow: inset 0 3px 12px rgba(0, 0, 0, 0.05);
      animation: slideIn 0.6s ease-in-out;
    }
    .result-box p {
      margin: 0.5rem 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .glow {
      font-family: 'Poppins', sans-serif;
      text-shadow: 0 0 12px rgba(124, 58, 237, 0.5), 0 0 24px rgba(124, 58, 237, 0.3);
      letter-spacing: 1px;
    }
    .stylish-text {
      font-family: 'Poppins', sans-serif;
      letter-spacing: 0.5px;
      color: #1f2937;
    }
    .logo {
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #7c3aed;
      box-shadow: 0 0 25px rgba(124, 58, 237, 0.5);
      width: 24vw;
      height: 24vw;
      max-width: 180px;
      max-height: 180px;
      margin: 0 auto;
      position: relative;
      animation: logoGlow 2.5s ease-in-out infinite;
      transition: transform 0.4s ease-in-out;
    }
    .logo:hover {
      transform: scale(1.15);
    }
    @keyframes logoGlow {
      0% { box-shadow: 0 0 10px rgba(124, 58, 237, 0.5), 0 0 20px rgba(124, 58, 237, 0.3); }
      50% { box-shadow: 0 0 25px rgba(124, 58, 237, 0.8), 0 0 35px rgba(124, 58, 237, 0.5); }
      100% { box-shadow: 0 0 10px rgba(124, 58, 237, 0.5), 0 0 20px rgba(124, 58, 237, 0.3); }
    }
    @media (min-width: 640px) {
      .logo {
        max-width: 220px;
        max-height: 220px;
      }
    }
    @media (min-width: 768px) {
      .logo {
        max-width: 260px;
        max-height: 260px;
      }
    }
    .status-indicator {
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      font-weight: bold;
      font-size: 1rem;
      color: #10b981;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }
    .status-indicator:hover {
      transform: translateY(-3px);
    }
    @media (min-width: 640px) {
      .status-indicator {
        font-size: 1.1rem;
      }
    }
    .status-dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: #10b981;
      animation: pulse 1.8s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.3); opacity: 0.7; }
      100% { transform: scale(1); opacity: 1; }
    }
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      20%, 60% { transform: translateX(-5px); }
      40%, 80% { transform: translateX(5px); }
    }
    .footer-container {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(240, 248, 255, 0.4));
      backdrop-filter: blur(15px);
      border-radius: 1.5rem;
      padding: 3rem;
      box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.4);
      position: relative;
      overflow: hidden;
    }
    .footer-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, rgba(124, 58, 237, 0.1), rgba(16, 185, 129, 0.1));
      opacity: 0.4;
      z-index: 0;
    }
    .footer-content {
      position: relative;
      z-index: 1;
    }
    .footer-link {
      color: #1f2937;
      transition: all 0.3s ease;
    }
    .footer-link:hover {
      color: #7c3aed;
      transform: translateY(-2px);
    }
    .social-icon {
      transition: all 0.5s ease;
      position: relative;
    }
    .social-icon:hover {
      transform: scale(1.4) rotate(360deg);
      color: #7c3aed;
      text-shadow: 0 0 15px rgba(124, 58, 237, 0.9);
      animation: pulseGlow 1.2s infinite;
    }
    @keyframes pulseGlow {
      0% { text-shadow: 0 0 15px rgba(124, 58, 237, 0.9); }
      50% { text-shadow: 0 0 20px rgba(124, 58, 237, 1); }
      100% { text-shadow: 0 0 15px rgba(124, 58, 237, 0.9); }
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulseButton {
      0% { box-shadow: 0 5px 15px rgba(124, 58, 237, 0.4); }
      50% { box-shadow: 0 8px 25px rgba(124, 58, 237, 0.6); }
      100% { box-shadow: 0 5px 15px rgba(124, 58, 237, 0.4); }
    }
  </style>
</head>
<body>
  <canvas id="three-canvas"></canvas>
  <header class="text-center py-8 sm:py-16">
    <img src="https://i.ibb.co/yBNCxwvM/enhanced.png" alt="Smart Fake Address Generator Logo" class="logo">
    <div class="flex items-center justify-center mt-6 sm:mt-8">
      <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold glow stylish-text mr-4">Smart Fake Address Generator</h1>
      <span class="inline-block w-12 h-12">
        <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12" cy="12" r="10" fill="#10b981" stroke="#ffffff" stroke-width="2"/>
          <path d="M9 12.5L11 15L15 9" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
    </div>
    <p class="welcome-message mt-3 sm:mt-4 tracking-wide stylish-text text-lg sm:text-xl leading-relaxed max-w-5xl mx-auto">
      The Ultimate Fake Address Generator: Generate realistic fake addresses with ease. Built with passion by @ISmartCoder. Still under active development, daily updated by @TheSmartDev.
    </p>
    <div class="status-indicator mt-6 text-lg sm:text-xl stylish-text flex items-center justify-center">
      <span class="status-dot mr-2"></span> Status: <i>Online</i>
    </div>
  </header>
  <section id="address-generator" class="section-card max-w-5xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-8 sm:mb-10 glow stylish-text">Fake Address Generator</h2>
    <div class="tool-card">
      <div class="tool-card-content">
        <div class="flex justify-center mb-8">
          <i class="fas fa-map-marked-alt text-6xl text-gray-600 animate-pulse"></i>
        </div>
        <h3 class="text-2xl font-bold stylish-text mb-8 text-center">Generate Fake Address</h3>
        <div id="form-container" class="mb-8">
          <div class="grid grid-cols-1 max-w-md mx-auto">
            <div>
              <label class="block text-sm font-medium stylish-text mb-3">Country Code</label>
              <input type="text" id="country-code-input" class="input-field" placeholder="e.g., BD" value="BD" required>
            </div>
          </div>
        </div>
        <div class="text-center">
          <button onclick="generateAddress()" class="button font-bold">Generate Address</button>
        </div>
        <p id="error" class="error hidden"></p>
        <div id="results" class="hidden">
          <h4 class="text-xl font-bold stylish-text mb-6 text-center">Address Details</h4>
          <div class="result-box">
            <p><i class="fas fa-check-circle"></i> <strong>Status:</strong> <span id="status"></span></p>
            <p><i class="fas fa-road"></i> <strong>Street Address:</strong> <span id="street_address"></span></p>
            <p><i class="fas fa-street-view"></i> <strong>Street Name:</strong> <span id="street_name"></span></p>
            <p><i class="fas fa-building"></i> <strong>Building Number:</strong> <span id="building_number"></span></p>
            <p><i class="fas fa-city"></i> <strong>City:</strong> <span id="city"></span></p>
            <p><i class="fas fa-map"></i> <strong>State:</strong> <span id="state"></span></p>
            <p><i class="fas fa-mail-bulk"></i> <strong>Postal Code:</strong> <span id="postal_code"></span></p>
            <p><i class="fas fa-globe"></i> <strong>Country:</strong> <span id="country"></span></p>
            <p><i class="fas fa-flag"></i> <strong>Country Code:</strong> <span id="country_code"></span></p>
            <p><i class="fas fa-money-bill"></i> <strong>Currency:</strong> <span id="currency"></span></p>
            <p><i class="fas fa-user"></i> <strong>Person Name:</strong> <span id="person_name"></span></p>
            <p><i class="fas fa-venus-mars"></i> <strong>Gender:</strong> <span id="gender"></span></p>
            <p><i class="fas fa-phone"></i> <strong>Phone Number:</strong> <span id="phone_number"></span></p>
            <p><i class="fas fa-flag-checkered"></i> <strong>Country Flag:</strong> <span id="country_flag"></span></p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <footer class="bg-gradient-to-r from-indigo-50 to-purple-50 py-10 sm:py-16 mt-auto">
    <div class="footer-container max-w-5xl mx-auto text-center">
      <div class="footer-content">
        <h3 class="text-2xl sm:text-3xl font-semibold mb-6 sm:mb-8 glow stylish-text">Connect with Us</h3>
        <div class="flex justify-center space-x-6 sm:space-x-10 flex-wrap mb-10">
          <a href="https://facebook.com/abirxdhackz" target="_blank" class="social-icon"><i class="fab fa-facebook-f text-3xl sm:text-4xl"></i></a>
          <a href="https://instagram.com/abirxdhackz" target="_blank" class="social-icon"><i class="fab fa-instagram text-3xl sm:text-4xl"></i></a>
          <a href="https://x.com/abirxdhackz" target="_blank" class="social-icon"><i class="fab fa-x-twitter text-3xl sm:text-4xl"></i></a>
          <a href="https://github.com/abirxdhack" target="_blank" class="social-icon"><i class="fab fa-github text-3xl sm:text-4xl"></i></a>
          <a href="https://youtube.com/@abirxdhackz" target="_blank" class="social-icon"><i class="fab fa-youtube text-3xl sm:text-4xl"></i></a>
          <a href="https://t.me/ISmartCoder" target="_blank" class="social-icon"><i class="fab fa-telegram text-3xl sm:text-4xl"></i></a>
        </div>
        <p class="text-base sm:text-lg stylish-text mb-8">Empowering the digital world with innovative tools and solutions, crafted with passion by @ISmartCoder.</p>
        <div class="flex justify-center space-x-6 sm:space-x-8 mb-10">
          <a href="#privacy" class="text-sm stylish-text footer-link">Privacy Policy</a>
          <a href="#terms" class="text-sm stylish-text footer-link">Terms of Service</a>
          <a href="https://t.me/TheSmartDev" class="text-sm stylish-text footer-link">Updates</a>
        </div>
        <p class="text-base sm:text-lg font-bold stylish-text">© 2025 Smart Fake Address Generator. All rights reserved.</p>
      </div>
    </div>
  </footer>
  <script>
    const canvas = document.getElementById('three-canvas');
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ canvas, alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    camera.position.z = 5;
    const particles = new THREE.Group();
    const colors = [0x7c3aed, 0xdb2777, 0x10b981];
    for (let i = 0; i < 600; i++) {
      const geometry = new THREE.SphereGeometry(0.015, 16, 16);
      const material = new THREE.MeshBasicMaterial({ color: colors[Math.floor(Math.random() * colors.length)] });
      const particle = new THREE.Mesh(geometry, material);
      particle.position.set(
        (Math.random() - 0.5) * 14,
        (Math.random() - 0.5) * 14,
        (Math.random() - 0.5) * 14
      );
      particle.userData.velocity = new THREE.Vector3(
        (Math.random() - 0.5) * 0.015,
        (Math.random() - 0.5) * 0.015,
        (Math.random() - 0.5) * 0.015
      );
      particles.add(particle);
    }
    scene.add(particles);
    function animate() {
      requestAnimationFrame(animate);
      particles.children.forEach(particle => {
        particle.position.add(particle.userData.velocity);
        if (Math.abs(particle.position.x) > 7) particle.userData.velocity.x *= -1;
        if (Math.abs(particle.position.y) > 7) particle.userData.velocity.y *= -1;
        if (Math.abs(particle.position.z) > 7) particle.userData.velocity.z *= -1;
      });
      particles.rotation.y += 0.003;
      renderer.render(scene, camera);
    }
    animate();
    window.addEventListener('resize', () => {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    });
    async function generateAddress() {
      const errorEl = document.getElementById('error');
      const resultsEl = document.getElementById('results');
      const button = document.querySelector('.button');
      const countryCode = document.getElementById('country-code-input').value.trim();
      if (!countryCode) {
        errorEl.textContent = 'Please enter a valid country code.';
        errorEl.classList.remove('hidden');
        return;
      }
      button.textContent = 'Generating...';
      button.disabled = true;
      errorEl.classList.add('hidden');
      resultsEl.classList.add('hidden');
      let formData = new FormData();
      formData.append('country_code', countryCode);
      try {
        const response = await fetch('', {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        if (data.error) {
          errorEl.textContent = data.error;
          errorEl.classList.remove('hidden');
        } else {
          document.getElementById('status').textContent = data.status;
          document.getElementById('street_address').textContent = data.street_address;
          document.getElementById('street_name').textContent = data.street_name;
          document.getElementById('building_number').textContent = data.building_number;
          document.getElementById('city').textContent = data.city;
          document.getElementById('state').textContent = data.state;
          document.getElementById('postal_code').textContent = data.postal_code;
          document.getElementById('country').textContent = data.country;
          document.getElementById('country_code').textContent = data.country_code;
          document.getElementById('currency').textContent = data.currency;
          document.getElementById('person_name').textContent = data.person_name;
          document.getElementById('gender').textContent = data.gender;
          document.getElementById('phone_number').textContent = data.phone_number;
          document.getElementById('country_flag').textContent = data.country_flag;
          resultsEl.classList.remove('hidden');
        }
      } catch (err) {
        errorEl.textContent = 'Failed to process request: ' + err.message;
        errorEl.classList.remove('hidden');
      } finally {
        button.textContent = 'Generate Address';
        button.disabled = false;
      }
    }
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.onkeydown = function(e) {
      if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J')) || (e.ctrlKey && e.key === 'U')) {
        return false;
      }
    };
  </script>
</body>
</html>