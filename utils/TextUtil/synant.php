<?php
// Copyright @ISmartCoder
// Updates Channel t.me/TheSmartDev 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    
    
    if (isset($_POST['action']) && $_POST['action'] === 'synonym' && isset($_POST['word'])) {
        $word = filter_var($_POST['word'], FILTER_SANITIZE_STRING);
        if (!$word) {
            echo json_encode(['error' => 'Word is required']);
            exit;
        }
        
        $api_url = "https://a360api-c8fbf2fa3cda.herokuapp.com/eng/syn?word=" . urlencode($word);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0']);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);
        
        if ($response === false) {
            echo json_encode(['error' => 'Failed to fetch synonyms: ' . $curl_error]);
            exit;
        }
        
        $data = json_decode($response, true);
        if ($http_code !== 200) {
            $error_message = 'Failed to connect to API: HTTP ' . $http_code;
            if ($http_code === 400 && $data && isset($data['error'])) {
                $error_message = 'Invalid request for synonyms: ' . htmlspecialchars($data['error']);
            }
            echo json_encode(['error' => $error_message]);
            exit;
        }
        
        if ($data && isset($data['response'])) {
            echo json_encode([
                'status' => 'success',
                'words' => array_map('htmlspecialchars', $data['response'] ?? []),
                'count' => count($data['response'] ?? []),
                'api_owner' => isset($data['api_owner']) ? htmlspecialchars($data['api_owner']) : '@ISmartCoder',
                'api_updates' => isset($data['api_updates']) ? htmlspecialchars($data['api_updates']) : 't.me/TheSmartDev'
            ]);
        } else {
            echo json_encode([
                'status' => 'ERROR',
                'error' => 'No synonyms found for the specified word',
                'api_owner' => '@ISmartCoder',
                'api_updates' => 't.me/TheSmartDev'
            ]);
        }
        exit;
    }
    
    
    if (isset($_POST['action']) && $_POST['action'] === 'antonym' && isset($_POST['word'])) {
        $word = filter_var($_POST['word'], FILTER_SANITIZE_STRING);
        if (!$word) {
            echo json_encode(['error' => 'Word is required']);
            exit;
        }
        
        $api_url = "https://a360api-c8fbf2fa3cda.herokuapp.com/eng/ant?word=" . urlencode($word);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0']);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);
        
        if ($response === false) {
            echo json_encode(['error' => 'Failed to fetch antonyms: ' . $curl_error]);
            exit;
        }
        
        $data = json_decode($response, true);
        if ($http_code !== 200) {
            $error_message = 'Failed to connect to API: HTTP ' . $http_code;
            if ($http_code === 400 && $data && isset($data['error'])) {
                $error_message = 'Invalid request for antonyms: ' . htmlspecialchars($data['error']);
            }
            echo json_encode(['error' => $error_message]);
            exit;
        }
        
        if ($data && isset($data['response'])) {
            echo json_encode([
                'status' => 'success',
                'words' => array_map('htmlspecialchars', $data['response'] ?? []),
                'count' => count($data['response'] ?? []),
                'api_owner' => isset($data['api_owner']) ? htmlspecialchars($data['api_owner']) : '@ISmartCoder',
                'api_updates' => isset($data['api_updates']) ? htmlspecialchars($data['api_updates']) : 't.me/TheSmartDev'
            ]);
        } else {
            echo json_encode([
                'status' => 'ERROR',
                'error' => 'No antonyms found for the specified word',
                'api_owner' => '@ISmartCoder',
                'api_updates' => 't.me/TheSmartDev'
            ]);
        }
        exit;
    }
    
    echo json_encode(['error' => 'Invalid action']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta name="description" content="Smart Synonym & Antonym Finder: Search for synonyms and antonyms of a word with a vibrant, futuristic interface. Developed by Abir Arafat Chawdhury (@ISmartCoder).">
  <meta name="keywords" content="Synonym Finder, Antonym Finder, Word Search, @ISmartCoder, Futuristic UI">
  <meta name="author" content="Abir Arafat Chawdhury (@ISmartCoder)">
  <title>Smart Synonym & Antonym Finder</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      color: #1e293b;
      overflow-x: hidden;
      overflow-y: auto;
      margin: 0;
      background: linear-gradient(135deg, #f3e8ff, #dbeafe);
    }
    #three-canvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      opacity: 0.5;
    }
    .section-card {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(15px);
      border-radius: 1.5rem;
      padding: 2rem;
      margin: 2rem auto;
      box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
      border: 1px solid rgba(209, 213, 219, 0.3);
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
      border: 2px solid rgba(107, 33, 168, 0.3);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .tool-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 60px rgba(107, 33, 168, 0.4);
    }
    @media (min-width: 640px) {
      .tool-card {
        padding: 3.5rem;
        max-width: 75vw;
      }
    }
    .input-field {
      background: rgba(255, 255, 255, 0.9);
      color: #1e293b;
      border: 1px solid #d1d5db;
      padding: 1rem;
      border-radius: 0.75rem;
      width: 100%;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      animation: slideIn 0.5s ease-in-out;
    }
    .input-field:focus {
      outline: none;
      border-color: #6b21a8;
      box-shadow: 0 0 12px rgba(107, 33, 168, 0.5);
      transform: scale(1.02);
    }
    .input-field::placeholder {
      color: #6b7280;
    }
    .button {
      background: linear-gradient(90deg, #6b21a8, #3b82f6);
      color: #ffffff;
      padding: 1rem 3rem;
      border-radius: 0.75rem;
      font-size: 1.2rem;
      font-weight: 700;
      font-family: 'Orbitron', sans-serif;
      box-shadow: 0 5px 20px rgba(107, 33, 168, 0.5);
      transition: all 0.3s ease;
      animation: pulseButton 2s infinite;
    }
    .button:hover {
      box-shadow: 0 0 30px rgba(107, 33, 168, 0.7);
      transform: translateY(-3px);
    }
    .button:disabled {
      background: #9ca3af;
      box-shadow: none;
      cursor: not-allowed;
    }
    .error {
      color: #f87171;
      text-align: center;
      margin-top: 1.5rem;
      font-size: 1.2rem;
      font-weight: 600;
      font-family: 'Orbitron', sans-serif;
      animation: shake 0.3s ease-in-out;
    }
    .result-box {
      background: linear-gradient(135deg, #f8fafc, #e2e8f0);
      border-radius: 1rem;
      padding: 2.5rem;
      margin-top: 2rem;
      font-family: 'Orbitron', sans-serif;
      font-size: 1.2rem;
      line-height: 2;
      color: #1e293b;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      animation: slideIn 0.6s ease-in-out;
      position: relative;
      overflow: hidden;
      border: 1px solid rgba(107, 33, 168, 0.4);
    }
    .result-box::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(107, 33, 168, 0.2) 0%, transparent 70%);
      animation: glowPulse 5s infinite ease-in-out;
      z-index: -1;
    }
    .result-box p {
      margin: 1rem 0;
      display: flex;
      align-items: center;
      gap: 1.5rem;
      font-weight: 600;
      transition: transform 0.3s ease, background 0.3s ease;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
    }
    .result-box p:hover {
      transform: translateX(10px);
      background: rgba(107, 33, 168, 0.1);
    }
    .result-box i {
      color: #6b21a8;
      font-size: 1.8rem;
      transition: transform 0.3s ease;
    }
    .result-box p:hover i {
      transform: scale(1.2);
    }
    .result-box span {
      font-family: 'Courier New', monospace;
      font-size: 1.3rem;
      color: #1e293b;
      word-break: break-all;
      flex-grow: 1;
    }
    .word-card {
      background: linear-gradient(135deg, #f8fafc, #e2e8f0);
      border-radius: 1rem;
      padding: 1rem;
      margin: 0.5rem;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: 1px solid rgba(107, 33, 168, 0.3);
      display: inline-flex;
      align-items: center;
      gap: 1rem;
      font-family: 'Poppins', sans-serif;
      font-size: 1.1rem;
      color: #1e293b;
    }
    .word-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(107, 33, 168, 0.4);
    }
    .word-card span {
      font-family: 'Courier New', monospace;
      word-break: break-word;
    }
    .copy-button {
      background: linear-gradient(90deg, #6b21a8, #3b82f6);
      color: #ffffff;
      padding: 0.6rem 1.2rem;
      border-radius: 0.5rem;
      font-size: 1rem;
      font-weight: 600;
      font-family: 'Orbitron', sans-serif;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.3s ease;
      margin-left: auto;
    }
    .copy-button:hover {
      box-shadow: 0 0 20px rgba(107, 33, 168, 0.7);
      transform: translateY(-2px);
    }
    .copy-button i {
      font-size: 1.1rem;
    }
    .glow {
      font-family: 'Orbitron', sans-serif;
      text-shadow: 0 0 15px rgba(107, 33, 168, 0.7), 0 0 30px rgba(107, 33, 168, 0.4);
      letter-spacing: 1.5px;
    }
    .stylish-text {
      font-family: 'Poppins', sans-serif;
      letter-spacing: 0.5px;
      color: #1e293b;
    }
    .logo {
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #6b21a8;
      box-shadow: 0 0 30px rgba(107, 33, 168, 0.7);
      width: 24vw;
      height: 24vw;
      max-width: 200px;
      max-height: 200px;
      margin: 0 auto;
      position: relative;
      animation: logoGlow 2s ease-in-out infinite;
      transition: transform 0.4s ease-in-out;
    }
    .logo:hover {
      transform: scale(1.2);
    }
    @keyframes logoGlow {
      0% { box-shadow: 0 0 10px rgba(107, 33, 168, 0.7), 0 0 20px rgba(107, 33, 168, 0.4); }
      50% { box-shadow: 0 0 30px rgba(107, 33, 168, 0.9), 0 0 40px rgba(107, 33, 168, 0.6); }
      100% { box-shadow: 0 0 10px rgba(107, 33, 168, 0.7), 0 0 20px rgba(107, 33, 168, 0.4); }
    }
    @media (min-width: 640px) {
      .logo {
        max-width: 240px;
        max-height: 240px;
      }
    }
    @media (min-width: 768px) {
      .logo {
        max-width: 280px;
        max-height: 280px;
      }
    }
    .status-indicator {
      display: inline-flex;
      align-items: center;
      gap: 1rem;
      font-weight: bold;
      font-size: 1.2rem;
      font-family: 'Orbitron', sans-serif;
      color: #6b21a8;
      text-shadow: 0 0 10px rgba(107, 33, 168, 0.5);
      transition: transform 0.3s ease;
    }
    .status-indicator:hover {
      transform: translateY(-3px);
    }
    @media (min-width: 640px) {
      .status-indicator {
        font-size: 1.3rem;
      }
    }
    .status-dot {
      width: 14px;
      height: 14px;
      border-radius: 50%;
      background-color: #6b21a8;
      box-shadow: 0 0 10px rgba(107, 33, 168, 0.7);
      animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.4); opacity: 0.6; }
      100% { transform: scale(1); opacity: 1; }
    }
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      20%, 60% { transform: translateX(-5px); }
      40%, 80% { transform: translateX(5px); }
    }
    @keyframes glowPulse {
      0% { opacity: 0.3; transform: rotate(0deg); }
      50% { opacity: 0.5; transform: rotate(180deg); }
      100% { opacity: 0.3; transform: rotate(360deg); }
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
      background: linear-gradient(45deg, rgba(107, 33, 168, 0.2), rgba(59, 130, 246, 0.2));
      opacity: 0.5;
      z-index: -1;
    }
    .footer-content {
      position: relative;
      z-index: 1;
    }
    .footer-link {
      color: #1e293b;
      font-family: 'Orbitron', sans-serif;
      transition: all 0.3s ease;
    }
    .footer-link:hover {
      color: #6b21a8;
      text-shadow: 0 0 10px rgba(107, 33, 168, 0.7);
      transform: translateY(-2px);
    }
    .social-icon {
      transition: all 0.5s ease;
      position: relative;
    }
    .social-icon:hover {
      transform: scale(1.5) rotate(360deg);
      color: #6b21a8;
      text-shadow: 0 0 20px rgba(107, 33, 168, 0.9);
      animation: pulseGlow 1s infinite;
    }
    @keyframes pulseGlow {
      0% { text-shadow: 0 0 15px rgba(107, 33, 168, 0.9); }
      50% { text-shadow: 0 0 25px rgba(107, 33, 168, 1); }
      100% { text-shadow: 0 0 15px rgba(107, 33, 168, 0.9); }
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
      0% { box-shadow: 0 5px 20px rgba(107, 33, 168, 0.5); }
      50% { box-shadow: 0 8px 30px rgba(107, 33, 168, 0.7); }
      100% { box-shadow: 0 5px 20px rgba(107, 33, 168, 0.5); }
    }
  </style>
</head>
<body>
  <canvas id="three-canvas"></canvas>
  <header class="text-center py-8 sm:py-16">
    <img src="https://i.ibb.co/yBNCxwvM/enhanced.png" alt="Smart Synonym & Antonym Finder Logo" class="logo">
    <div class="flex items-center justify-center mt-6 sm:mt-8">
      <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold glow stylish-text mr-4">Smart Synonym & Antonym Finder</h1>
      <span class="inline-block w-12 h-12">
        <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12" cy="12" r="10" fill="#6b21a8" stroke="#ffffff" stroke-width="2"/>
          <path d="M9 12.5L11 15L15 9" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
    </div>
    <p class="welcome-message mt-3 sm:mt-4 tracking-wide stylish-text text-lg sm:text-xl leading-relaxed max-w-5xl mx-auto">
      The Ultimate Synonym & Antonym Finder: Discover synonyms and antonyms of any word with a vibrant, futuristic interface. Built with passion by @ISmartCoder. Still under active development, daily updated by @TheSmartDev.
    </p>
    <div class="status-indicator mt-6 text-lg sm:text-xl stylish-text flex items-center justify-center">
      <span class="status-dot mr-2"></span> Status: <i>Online</i>
    </div>
  </header>
  <section id="synonym-search" class="section-card max-w-5xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-8 sm:mb-10 glow stylish-text">Synonym Finder</h2>
    <div class="tool-card">
      <div class="tool-card-content">
        <div class="flex justify-center mb-8">
          <i class="fas fa-book-open text-6xl text-purple-600 animate-pulse"></i>
        </div>
        <h3 class="text-2xl font-bold stylish-text mb-8 text-center">Search for Synonyms</h3>
        <div id="synonym-form-container" class="mb-8">
          <div class="grid grid-cols-1 max-w-md mx-auto gap-4">
            <div>
              <label class="block text-sm font-medium stylish-text mb-3 text-gray-700">Word</label>
              <input type="text" id="synonym-word-input" class="input-field" placeholder="e.g., big">
            </div>
          </div>
        </div>
        <div class="text-center">
          <button onclick="searchSynonyms()" class="button font-bold">Search Synonyms</button>
        </div>
        <p id="synonym-error" class="error hidden"></p>
        <div id="synonym-results" class="hidden">
          <h4 class="text-2xl font-bold stylish-text mb-6 text-center text-purple-600">Synonym Results</h4>
          <div class="result-box">
            <p><i class="fas fa-check-circle"></i> <strong>Status:</strong> <span id="synonym-status"></span></p>
            <p><i class="fas fa-list"></i> <strong>Synonyms Found:</strong> <span id="synonym-count"></span></p>
          </div>
          <div id="synonym-list" class="mt-6 flex flex-wrap gap-2"></div>
        </div>
      </div>
    </div>
  </section>
  <section id="antonym-search" class="section-card max-w-5xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-8 sm:mb-10 glow stylish-text">Antonym Finder</h2>
    <div class="tool-card">
      <div class="tool-card-content">
        <div class="flex justify-center mb-8">
          <i class="fas fa-book text-6xl text-blue-500 animate-pulse"></i>
        </div>
        <h3 class="text-2xl font-bold stylish-text mb-8 text-center">Search for Antonyms</h3>
        <div id="antonym-form-container" class="mb-8">
          <div class="grid grid-cols-1 max-w-md mx-auto gap-4">
            <div>
              <label class="block text-sm font-medium stylish-text mb-3 text-gray-700">Word</label>
              <input type="text" id="antonym-word-input" class="input-field" placeholder="e.g., big">
            </div>
          </div>
        </div>
        <div class="text-center">
          <button onclick="searchAntonyms()" class="button font-bold">Search Antonyms</button>
        </div>
        <p id="antonym-error" class="error hidden"></p>
        <div id="antonym-results" class="hidden">
          <h4 class="text-2xl font-bold stylish-text mb-6 text-center text-blue-500">Antonym Results</h4>
          <div class="result-box">
            <p><i class="fas fa-check-circle"></i> <strong>Status:</strong> <span id="antonym-status"></span></p>
            <p><i class="fas fa-list"></i> <strong>Antonyms Found:</strong> <span id="antonym-count"></span></p>
          </div>
          <div id="antonym-list" class="mt-6 flex flex-wrap gap-2"></div>
        </div>
      </div>
    </div>
  </section>
  <footer class="bg-gradient-to-r from-purple-50 to-blue-50 py-10 sm:py-16 mt-auto">
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
        <p class="text-base sm:text-lg stylish-text mb-8">Empowering the digital world with cutting-edge tools and solutions, crafted with passion by @ISmartCoder.</p>
        <div class="flex justify-center space-x-6 sm:space-x-8 mb-10">
          <a href="#privacy" class="text-sm stylish-text footer-link">Privacy Policy</a>
          <a href="#terms" class="text-sm stylish-text footer-link">Terms of Service</a>
          <a href="https://t.me/TheSmartDev" class="text-sm stylish-text footer-link">Updates</a>
        </div>
        <p class="text-base sm:text-lg font-bold stylish-text">© 2025 Smart Synonym & Antonym Finder. All rights reserved.</p>
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
    const colors = [0x6b21a8, 0x3b82f6, 0x1e293b];
    for (let i = 0; i < 800; i++) {
      const geometry = new THREE.SphereGeometry(0.02, 16, 16);
      const material = new THREE.MeshBasicMaterial({ color: colors[Math.floor(Math.random() * colors.length)] });
      const particle = new THREE.Mesh(geometry, material);
      particle.position.set(
        (Math.random() - 0.5) * 16,
        (Math.random() - 0.5) * 16,
        (Math.random() - 0.5) * 16
      );
      particle.userData.velocity = new THREE.Vector3(
        (Math.random() - 0.5) * 0.02,
        (Math.random() - 0.5) * 0.02,
        (Math.random() - 0.5) * 0.02
      );
      particles.add(particle);
    }
    scene.add(particles);
    function animate() {
      requestAnimationFrame(animate);
      particles.children.forEach(particle => {
        particle.position.add(particle.userData.velocity);
        if (Math.abs(particle.position.x) > 8) particle.userData.velocity.x *= -1;
        if (Math.abs(particle.position.y) > 8) particle.userData.velocity.y *= -1;
        if (Math.abs(particle.position.z) > 8) particle.userData.velocity.z *= -1;
      });
      particles.rotation.y += 0.004;
      renderer.render(scene, camera);
    }
    animate();
    window.addEventListener('resize', () => {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    });
    
    async function searchSynonyms() {
      const errorEl = document.getElementById('synonym-error');
      const resultsEl = document.getElementById('synonym-results');
      const wordListEl = document.getElementById('synonym-list');
      const button = document.querySelector('#synonym-search .button');
      const word = document.getElementById('synonym-word-input').value.trim();
      
      if (!word) {
        errorEl.textContent = 'Please enter a valid word.';
        errorEl.classList.remove('hidden');
        return;
      }
      
      button.textContent = 'Searching...';
      button.disabled = true;
      errorEl.classList.add('hidden');
      resultsEl.classList.add('hidden');
      wordListEl.innerHTML = '';
      
      let formData = new FormData();
      formData.append('action', 'synonym');
      formData.append('word', word);
      
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
          document.getElementById('synonym-status').textContent = data.status;
          document.getElementById('synonym-count').textContent = data.count;
          
          if (data.words.length === 0) {
            wordListEl.innerHTML = '<p class="text-center stylish-text text-lg font-medium text-gray-700">No synonyms found.</p>';
          } else {
            data.words.forEach((word, index) => {
              const wordCard = document.createElement('div');
              wordCard.className = 'word-card';
              wordCard.innerHTML = `
                <span id="synonym-${index}">${word}</span>
                <button class="copy-button" onclick="copyText('synonym-${index}')"><i class="fas fa-copy"></i> Copy</button>
              `;
              wordListEl.appendChild(wordCard);
            });
          }
          resultsEl.classList.remove('hidden');
        }
      } catch (err) {
        errorEl.textContent = 'Failed to process request: ' + err.message;
        errorEl.classList.remove('hidden');
      } finally {
        button.textContent = 'Search Synonyms';
        button.disabled = false;
      }
    }
    
    async function searchAntonyms() {
      const errorEl = document.getElementById('antonym-error');
      const resultsEl = document.getElementById('antonym-results');
      const wordListEl = document.getElementById('antonym-list');
      const button = document.querySelector('#antonym-search .button');
      const word = document.getElementById('antonym-word-input').value.trim();
      
      if (!word) {
        errorEl.textContent = 'Please enter a valid word.';
        errorEl.classList.remove('hidden');
        return;
      }
      
      button.textContent = 'Searching...';
      button.disabled = true;
      errorEl.classList.add('hidden');
      resultsEl.classList.add('hidden');
      wordListEl.innerHTML = '';
      
      let formData = new FormData();
      formData.append('action', 'antonym');
      formData.append('word', word);
      
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
          document.getElementById('antonym-status').textContent = data.status;
          document.getElementById('antonym-count').textContent = data.count;
          
          if (data.words.length === 0) {
            wordListEl.innerHTML = '<p class="text-center stylish-text text-lg font-medium text-gray-700">No antonyms found.</p>';
          } else {
            data.words.forEach((word, index) => {
              const wordCard = document.createElement('div');
              wordCard.className = 'word-card';
              wordCard.innerHTML = `
                <span id="antonym-${index}">${word}</span>
                <button class="copy-button" onclick="copyText('antonym-${index}')"><i class="fas fa-copy"></i> Copy</button>
              `;
              wordListEl.appendChild(wordCard);
            });
          }
          resultsEl.classList.remove('hidden');
        }
      } catch (err) {
        errorEl.textContent = 'Failed to process request: ' + err.message;
        errorEl.classList.remove('hidden');
      } finally {
        button.textContent = 'Search Antonyms';
        button.disabled = false;
      }
    }
    
    function copyText(elementId) {
      const text = document.getElementById(elementId).textContent;
      navigator.clipboard.writeText(text).then(() => {
        const button = document.querySelector(`#${elementId}`).nextElementSibling;
        button.innerHTML = '<i class="fas fa-check"></i> Copied!';
        button.style.background = 'linear-gradient(90deg, #a78bfa, #3b82f6)';
        setTimeout(() => {
          button.innerHTML = '<i class="fas fa-copy"></i> Copy';
          button.style.background = 'linear-gradient(90deg, #6b21a8, #3b82f6)';
        }, 2000);
      }).catch(err => {
        console.error('Failed to copy: ', err);
      });
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