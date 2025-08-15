<?php
// Copyright @ISmartCoder
// Updates Channel: t.me/TheSmartDev
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta name="description" content="Smart Multifunctional Web: Your ultimate toolkit for financial, network, media, text, image, and web tools. Developed by Abir Arafat Chawdhury (@ISmartCoder).">
  <meta name="keywords" content="Smart Multifunctional Web, toolkit, financial tools, media downloader, network tools, text tools, image tools, web tools, @ISmartCoder">
  <meta name="author" content="Abir Arafat Chawdhury (@ISmartCoder)">
  <title>Smart Multifunctional Web - Ultimate Toolkit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/ScrollTrigger.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      color: #1f2937;
      overflow-x: hidden;
      overflow-y: auto;
      margin: 0;
      background: linear-gradient(135deg, #f9fafb, #d1d5db);
    }
    #three-canvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      opacity: 0.4;
      will-change: transform;
    }
    .section-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 1.5rem;
      padding: 2rem;
      margin: 1.5rem auto;
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
      transition: transform 0.4s ease, box-shadow 0.4s ease;
      border: 1px solid rgba(0, 0, 0, 0.1);
      max-width: 95vw;
    }
    @media (min-width: 640px) {
      .section-card {
        padding: 2.5rem;
        max-width: 85vw;
      }
      .section-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
      }
    }
    .tool-card {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.3));
      backdrop-filter: blur(8px);
      border-radius: 1.2rem;
      padding: 2rem;
      box-shadow: 0 3px 15px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      position: relative;
      overflow: hidden;
      max-width: 95vw;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .tool-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, rgba(59, 130, 246, 0.3), rgba(16, 185, 129, 0.3));
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: 0;
    }
    .tool-card:hover::before {
      opacity: 1;
    }
    .tool-card:hover {
      transform: scale(1.05) rotate(1deg);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
    }
    .tool-card-content {
      position: relative;
      z-index: 1;
    }
    .tool-card.financial { border-left: 5px solid #3b82f6; }
    .tool-card.financial .holo-glow { background: linear-gradient(45deg, #3b82f6, #60a5fa); }
    .tool-card.network { border-left: 5px solid #10b981; }
    .tool-card.network .holo-glow { background: linear-gradient(45deg, #10b981, #34d399); }
    .tool-card.media { border-left: 5px solid #8b5cf6; }
    .tool-card.media .holo-glow { background: linear-gradient(45deg, #8b5cf6, #a78bfa); }
    .tool-card.text { border-left: 5px solid #f59e0b; }
    .tool-card.text .holo-glow { background: linear-gradient(45deg, #f59e0b, #fbbf24); }
    .tool-card.image { border-left: 5px solid #ec4899; }
    .tool-card.image .holo-glow { background: linear-gradient(45deg, #ec4899, #f472b6); }
    .tool-card.web { border-left: 5px solid #ef4444; }
    .tool-card.web .holo-glow { background: linear-gradient(45deg, #ef4444, #f87171); }
    .holo-glow {
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      opacity: 0.2;
      pointer-events: none;
      transition: opacity 0.3s ease;
    }
    .tool-card:hover .holo-glow {
      opacity: 0.4;
    }
    @media (min-width: 640px) {
      .tool-card {
        padding: 2.5rem;
        max-width: 80vw;
      }
    }
    .feature-card {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.3));
      backdrop-filter: blur(8px);
      border-radius: 1.2rem;
      padding: 1.5rem;
      box-shadow: 0 3px 15px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      text-align: center;
      position: relative;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, rgba(59, 130, 246, 0.3), rgba(16, 185, 129, 0.3));
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: 0;
    }
    .feature-card:hover::before {
      opacity: 1;
    }
    .feature-card:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
    }
    .feature-card-content {
      position: relative;
      z-index: 1;
    }
    .glow {
      font-family: 'Poppins', sans-serif;
      text-shadow: 0 0 8px rgba(31, 41, 55, 0.6), 0 0 16px rgba(31, 41, 55, 0.4);
      letter-spacing: 0.75px;
    }
    .stylish-text {
      font-family: 'Poppins', sans-serif;
      letter-spacing: 0.75px;
      color: #1f2937;
    }
    .btn-glow {
      background: linear-gradient(90deg, #1f2937, #4b5563);
      color: #ffffff;
      transition: all 0.3s ease;
      padding: 0.75rem 1.5rem;
      border-radius: 0.75rem;
      font-size: 0.9rem;
      box-shadow: 0 2px 5px rgba(31, 41, 55, 0.3);
      position: relative;
      overflow: hidden;
    }
    .btn-glow::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
      transition: left 0.3s ease;
    }
    .btn-glow:hover::before {
      left: 100%;
    }
    .btn-glow:hover {
      box-shadow: 0 0 20px rgba(31, 41, 55, 0.6);
      transform: scale(1.05);
    }
    @media (min-width: 640px) {
      .btn-glow {
        padding: 1rem 2rem;
        font-size: 1rem;
      }
    }
    .logo {
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #1f2937;
      box-shadow: 0 0 15px rgba(31, 41, 55, 0.4);
      width: 24vw;
      height: 24vw;
      max-width: 120px;
      max-height: 120px;
      margin: 0 auto;
      position: relative;
      animation: logoGlow 3s ease-in-out infinite;
      transition: transform 0.4s ease-in-out;
    }
    .logo:hover {
      transform: scale(1.1);
    }
    @keyframes logoGlow {
      0% { box-shadow: 0 0 10px rgba(59, 130, 246, 0.5), 0 0 20px rgba(59, 130, 246, 0.3); }
      50% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.8), 0 0 30px rgba(59, 130, 246, 0.5); }
      100% { box-shadow: 0 0 10px rgba(59, 130, 246, 0.5), 0 0 20px rgba(59, 130, 246, 0.3); }
    }
    @media (min-width: 640px) {
      .logo {
        max-width: 160px;
        max-height: 160px;
      }
    }
    @media (min-width: 768px) {
      .logo {
        max-width: 200px;
        max-height: 200px;
      }
    }
    .status-indicator {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: bold;
      font-size: 0.9rem;
      color: #10b981;
      text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }
    .status-indicator:hover {
      transform: translateY(-2px);
    }
    @media (min-width: 640px) {
      .status-indicator {
        font-size: 1rem;
      }
    }
    .status-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background-color: #10b981;
      animation: pulse 2.5s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.2); opacity: 0.8; }
      100% { transform: scale(1); opacity: 1; }
    }
    .section-container {
      opacity: 0;
      transform: translateY(50px);
      transition: opacity 0.8s ease, transform 0.8s ease;
    }
    .section-container.visible {
      opacity: 1;
      transform: translateY(0);
    }
    .masonry-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      padding: 0 1.5rem;
    }
    .tool-icon {
      transition: transform 0.3s ease;
    }
    .tool-card:hover .tool-icon {
      transform: scale(1.2) rotate(5deg);
    }
    .owner-card {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.3));
      backdrop-filter: blur(8px);
      border-radius: 1.2rem;
      padding: 2rem;
      box-shadow: 0 3px 15px rgba(0, 0, 0, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.2);
      position: relative;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .owner-card:hover {
      transform: scale(1.02);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
    }
    .owner-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, rgba(59, 130, 246, 0.3), rgba(16, 185, 129, 0.3));
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: 0;
    }
    .owner-card:hover::before {
      opacity: 1;
    }
    .owner-card-content {
      position: relative;
      z-index: 1;
    }
    .footer-link {
      color: #1f2937;
      transition: color 0.3s ease, transform 0.3s ease;
    }
    .footer-link:hover {
      color: #3b82f6;
      transform: translateY(-2px);
    }
    .social-icon {
      transition: transform 0.5s ease, color 0.5s ease, text-shadow 0.5s ease;
      position: relative;
    }
    .social-icon:hover {
      transform: scale(1.3) rotate(360deg);
      color: #3b82f6;
      text-shadow: 0 0 12px rgba(59, 130, 246, 0.9);
      animation: pulseGlow 1.5s infinite;
    }
    @keyframes pulseGlow {
      0% { text-shadow: 0 0 12px rgba(59, 130, 246, 0.9); }
      50% { text-shadow: 0 0 18px rgba(59, 130, 246, 1); }
      100% { text-shadow: 0 0 12px rgba(59, 130, 246, 0.9); }
    }
    .footer-container {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.35));
      backdrop-filter: blur(12px);
      border-radius: 1rem;
      padding: 3rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
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
      background: linear-gradient(45deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
      opacity: 0.3;
      z-index: 0;
    }
    .footer-content {
      position: relative;
      z-index: 1;
    }
  </style>
</head>
<body>
  <!-- Three.js Canvas -->
  <canvas id="three-canvas"></canvas>
  <!-- Hero Section -->
  <header class="text-center py-6 sm:py-12">
    <img src="https://i.ibb.co/yBNCxwvM/enhanced.png" alt="Smart Multifunctional Web Logo" class="logo">
    <div class="flex items-center justify-center mt-4 sm:mt-6">
      <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold glow stylish-text mr-4">Smart Multifunctional Web</h1>
      <span class="inline-block w-10 h-10">
        <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12" cy="12" r="10" fill="#28a745" stroke="#ffffff" stroke-width="2"/>
          <path d="M9 12.5L11 15L15 9" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
    </div>
    <p class="welcome-message mt-2 sm:mt-3 tracking-wide stylish-text text-lg sm:text-xl leading-relaxed max-w-5xl mx-auto">
      The Ultimate Toolkit: Empowering users with tools for financial analysis, media downloading, network diagnostics, and more. Built with passion by @ISmartCoder. It's Still Under Active Development Not Fully Operational & Daily Updated By @TheSmartDev
    </p>
    <div class="status-indicator mt-4 text-lg sm:text-xl stylish-text flex items-center justify-center">
      <span class="status-dot mr-2"></span> Status: <i>Online</i>
    </div>
  </header>
  <!-- Financial Tools Section -->
  <section id="financial-tools" class="section-container section-card max-w-5xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-6 sm:mb-8 glow stylish-text">Financial Tools</h2>
    <div class="masonry-grid">
      <div class="tool-card financial" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-credit-card text-4xl mb-4 text-blue-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Credit Card Generator</h3>
          <p class="text-base stylish-text mb-4">Generate valid credit card numbers for testing purposes.</p>
          <a href="./utils/FinancialUtils/ccxen.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card financial" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-search-dollar text-4xl mb-4 text-blue-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Bin Checker</h3>
          <p class="text-base stylish-text mb-4">Verify details of a credit card BIN number.</p>
          <a href="./utils/FinancialUtils/binchk.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card financial" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-list-alt text-4xl mb-4 text-blue-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Multi Bin Checker</h3>
          <p class="text-base stylish-text mb-4">Check multiple BINs simultaneously.</p>
          <a href="./utils/FinancialUtils/mbinchk.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card financial" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fab fa-bitcoin text-4xl mb-4 text-blue-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Binance P2P</h3>
          <p class="text-base stylish-text mb-4">Access Binance P2P trading data.</p>
          <a href="./utils/FinancialUtils/bnp2p.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card financial" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-chart-line text-4xl mb-4 text-blue-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Binance Token Price</h3>
          <p class="text-base stylish-text mb-4">Track real-time Binance token prices.</p>
          <a href="./utils/FinancialUtils/ptoken.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card financial" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-arrow-up text-4xl mb-4 text-blue-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Binance Gainers</h3>
          <p class="text-base stylish-text mb-4">View top gaining tokens on Binance.</p>
          <a href="./utils/FinancialUtils/gainers.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card financial" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-arrow-down text-4xl mb-4 text-blue-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Binance Losers</h3>
          <p class="text-base stylish-text mb-4">Monitor underperforming Binance tokens.</p>
          <a href="./utils/FinancialUtils/losers.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card financial" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-exchange-alt text-4xl mb-4 text-blue-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Binance Token Price Exchanger</h3>
          <p class="text-base stylish-text mb-4">Compare token prices across exchanges.</p>
          <a href="./utils/FinancialUtils/tknchng.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card financial" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-university text-4xl mb-4 text-blue-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Bank Credit Card Extrapolator</h3>
          <p class="text-base stylish-text mb-4">Extrapolate credit card details from partial data.</p>
          <button onclick="useTool('Bank Credit Card Extrapolator')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
      <div class="tool-card financial" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-database text-4xl mb-4 text-blue-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Bank Bin Database</h3>
          <p class="text-base stylish-text mb-4">Access a comprehensive Banks BIN database.</p>
          <a href="./utils/FinancialUtils/bnbank.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card financial" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-globe text-4xl mb-4 text-blue-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Country Bin Database</h3>
          <p class="text-base stylish-text mb-4">Search BINs by country.</p>
          <a href="./utils/FinancialUtils/bncntry.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card financial" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-key text-4xl mb-4 text-blue-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Stripe Keys Checker</h3>
          <p class="text-base stylish-text mb-4">Verify Stripe API keys for validity.</p>
          <a href="./utils/FinancialUtils/skchk.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card financial" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-info-circle text-4xl mb-4 text-blue-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Stripe Keys Info</h3>
          <p class="text-base stylish-text mb-4">Retrieve detailed Stripe key information.</p>
          <a href="./utils/FinancialUtils/skchk.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
    </div>
  </section>
  <!-- Network Tools Section -->
  <section id="network-tools" class="section-container section-card max-w-5xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-6 sm:mb-8 glow stylish-text">Network Tools</h2>
    <div class="masonry-grid">
      <div class="tool-card network" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-network-wired text-4xl mb-4 text-green-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Proxy Checker</h3>
          <p class="text-base stylish-text mb-4">Test proxy servers for speed and reliability.</p>
          <a href="./utils/NetUtils/proxychk.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card network" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-globe-americas text-4xl mb-4 text-green-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">IP Checker</h3>
          <p class="text-base stylish-text mb-4">Analyze details of any IP address.</p>
          <a href="./utils/NetUtils/ipinfo.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card network" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-globe text-4xl mb-4 text-green-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Domain Checker</h3>
          <p class="text-base stylish-text mb-4">Verify domain availability and WHOIS data.</p>
          <button onclick="useTool('Domain Checker')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
      <div class="tool-card network" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-server text-4xl mb-4 text-green-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Domain to IP</h3>
          <p class="text-base stylish-text mb-4">Resolve domains to their IP addresses.</p>
          <button onclick="useTool('Domain to IP')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
      <div class="tool-card network" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-user-secret text-4xl mb-4 text-green-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">User Agent Checker</h3>
          <p class="text-base stylish-text mb-4">Identify browser and device user agents.</p>
          <button onclick="useTool('User Agent Checker')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
      <div class="tool-card network" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-info-circle text-4xl mb-4 text-green-600 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">My IP Info Check</h3>
          <p class="text-base stylish-text mb-4">Get detailed information about your IP.</p>
          <button onclick="useTool('My IP Info Check')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
    </div>
  </section>
<!-- Media Tools Section -->
<section id="media-tools" class="section-container section-card max-w-5xl mx-auto">
  <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-6 sm:mb-8 glow stylish-text">Media Tools</h2>
  <div class="masonry-grid">
    <div class="tool-card media" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
      <div class="holo-glow"></div>
      <div class="tool-card-content">
        <i class="fab fa-youtube text-4xl mb-4 text-purple-600 tool-icon"></i>
        <h3 class="text-xl font-bold stylish-text mb-2">YouTube Video Downloader</h3>
        <p class="text-base stylish-text mb-4">Download YouTube videos in various formats.</p>
        <a href="./utils/MediaUtils/ytdl.php" class="btn-glow font-bold">Use Tool</a>
      </div>
    </div>
    <div class="tool-card media" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
      <div class="holo-glow"></div>
      <div class="tool-card-content">
        <i class="fab fa-facebook text-4xl mb-4 text-purple-600 tool-icon"></i>
        <h3 class="text-xl font-bold stylish-text mb-2">Facebook Video Downloader</h3>
        <p class="text-base stylish-text mb-4">Save videos from Facebook effortlessly.</p>
        <a href="./utils/MediaUtils/fbdl.php" class="btn-glow font-bold">Use Tool</a>
      </div>
    </div>
    <div class="tool-card media" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
      <div class="holo-glow"></div>
      <div class="tool-card-content">
        <i class="fab fa-x-twitter text-4xl mb-4 text-purple-600 tool-icon"></i>
        <h3 class="text-xl font-bold stylish-text mb-2">Twitter Video Downloader</h3>
        <p class="text-base stylish-text mb-4">Download videos from Twitter.</p>
        <a href="./utils/MediaUtils/twitdl.php" class="btn-glow font-bold">Use Tool</a>
      </div>
    </div>
    <div class="tool-card media" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
      <div class="holo-glow"></div>
      <div class="tool-card-content">
        <i class="fas fa-th text-4xl mb-4 text-purple-600 tool-icon"></i>
        <h3 class="text-xl font-bold stylish-text mb-2">Thread Video Downloader</h3>
        <p class="text-base stylish-text mb-4">Grab videos from Thread platforms.</p>
        <a href="./utils/MediaUtils/thrdl.php" class="btn-glow font-bold">Use Tool</a>
      </div>
    </div>
    <div class="tool-card media" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
      <div class="holo-glow"></div>
      <div class="tool-card-content">
        <i class="fab fa-tiktok text-4xl mb-4 text-purple-600 tool-icon"></i>
        <h3 class="text-xl font-bold stylish-text mb-2">TikTok Video Downloader</h3>
        <p class="text-base stylish-text mb-4">Download TikTok videos without watermarks.</p>
        <a href="./utils/MediaUtils/tikdl.php" class="btn-glow font-bold">Use Tool</a>
      </div>
    </div>
    <div class="tool-card media" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
      <div class="holo-glow"></div>
      <div class="tool-card-content">
        <i class="fab fa-instagram text-4xl mb-4 text-purple-600 tool-icon"></i>
        <h3 class="text-xl font-bold stylish-text mb-2">Instagram Video Downloader</h3>
        <p class="text-base stylish-text mb-4">Save Instagram videos and reels.</p>
        <a href="./utils/MediaUtils/insta.php" class="btn-glow font-bold">Use Tool</a>
      </div>
    </div>
    <div class="tool-card media" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
      <div class="holo-glow"></div>
      <div class="tool-card-content">
        <i class="fab fa-pinterest text-4xl mb-4 text-purple-600 tool-icon"></i>
        <h3 class="text-xl font-bold stylish-text mb-2">Pinterest Video Downloader</h3>
        <p class="text-base stylish-text mb-4">Download videos from Pinterest with ease.</p>
        <a href="./utils/MediaUtils/pntdl.php" class="btn-glow font-bold">Use Tool</a>
      </div>
    </div>
  </div>
</section>
  <!-- Text Tools Section -->
  <section id="text-tools" class="section-container section-card max-w-5xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-6 sm:mb-8 glow stylish-text">Text Tools</h2>
    <div class="masonry-grid">
      <div class="tool-card text" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-spell-check text-4xl mb-4 text-amber-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Grammar Checker</h3>
          <p class="text-base stylish-text mb-4">Correct grammar and improve your writing.</p>
          <button onclick="useTool('Grammar Checker')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
      <div class="tool-card text" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-check-circle text-4xl mb-4 text-amber-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Spell Checker</h3>
          <p class="text-base stylish-text mb-4">Identify and fix spelling mistakes.</p>
          <button onclick="useTool('Spell Checker')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
      <div class="tool-card text" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-volume-up text-4xl mb-4 text-amber-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Pronunciation Checker</h3>
          <p class="text-base stylish-text mb-4">Verify word pronunciation with audio guides.</p>
          <a href="./utils/TextUtil/prne.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card text" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-thesaurus text-4xl mb-4 text-amber-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Synonym Antonym Checker</h3>
          <p class="text-base stylish-text mb-4">Find synonyms and antonyms for words.</p>
          <a href="./utils/TextUtil/synant.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card text" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-code text-4xl mb-4 text-amber-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Decoders</h3>
          <p class="text-base stylish-text mb-4">Decode Base64, URL, and other formats.</p>
          <button onclick="useTool('Decoders')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
      <div class="tool-card text" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-language text-4xl mb-4 text-amber-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Google Translator</h3>
          <p class="text-base stylish-text mb-4">Translate text across multiple languages.</p>
          <a href="./utils/TextUtil/gtr.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
    </div>
  </section>
  <!-- Image Tools Section -->
  <section id="image-tools" class="section-container section-card max-w-5xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-6 sm:mb-8 glow stylish-text">Image Tools</h2>
    <div class="masonry-grid">
      <div class="tool-card image" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-image text-4xl mb-4 text-pink-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Image Enhancer</h3>
          <p class="text-base stylish-text mb-4">Enhance image quality with AI-powered tools.</p>
          <button onclick="useTool('Image Enhancer')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
      <div class="tool-card image" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-file-alt text-4xl mb-4 text-pink-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Image OCR</h3>
          <p class="text-base stylish-text mb-4">Extract text from images with high accuracy.</p>
          <button onclick="useTool('Image OCR')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
      <div class="tool-card image" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-search text-4xl mb-4 text-pink-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Image Analysis</h3>
          <p class="text-base stylish-text mb-4">Analyze images for objects and metadata.</p>
          <button onclick="useTool('Image Analysis')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
    </div>
  </section>
  <!-- Web Tools Section -->
  <section id="web-tools" class="section-container section-card max-w-5xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-6 sm:mb-8 glow stylish-text">Web Tools</h2>
    <div class="masonry-grid">
      <div class="tool-card web" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-ticket-alt text-4xl mb-4 text-red-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Site Coupon Scraper</h3>
          <p class="text-base stylish-text mb-4">Extract coupons from websites.</p>
          <button onclick="useTool('Site Coupon Scraper')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
      <div class="tool-card web" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-code text-4xl mb-4 text-red-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Website Source Code Downloader</h3>
          <p class="text-base stylish-text mb-4">Download a website’s source code.</p>
          <button onclick="useTool('Website Source Code Downloader')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
      <div class="tool-card web" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-camera text-4xl mb-4 text-red-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Website SS Capture</h3>
          <p class="text-base stylish-text mb-4">Capture high-quality website screenshots.</p>
          <button onclick="useTool('Website SS Capture')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
      <div class="tool-card web" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-user-check text-4xl mb-4 text-red-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">User Info Checker</h3>
          <p class="text-base stylish-text mb-4">Retrieve user information from platforms.</p>
          <a href="./utils/WebUtils/userinfo.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card web" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fab fa-telegram text-4xl mb-4 text-red-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Telegram Bot Users Export</h3>
          <p class="text-base stylish-text mb-4">Export user data from Telegram bots.</p>
          <button onclick="useTool('Telegram Bot Users Export')" class="btn-glow font-bold">Use Tool</button>
        </div>
      </div>
      <div class="tool-card web" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-map-marker-alt text-4xl mb-4 text-red-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Fake Address Generator</h3>
          <p class="text-base stylish-text mb-4">Generate realistic fake addresses.</p>
          <a href="./utils/WebUtils/fakeadrs.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
      <div class="tool-card web" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow"></div>
        <div class="tool-card-content">
          <i class="fas fa-envelope text-4xl mb-4 text-red-500 tool-icon"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Temp Mail</h3>
          <p class="text-base stylish-text mb-4">Create temporary email addresses.</p>
          <a href="./utils/WebUtils/tmail.php" class="btn-glow font-bold">Use Tool</a>
        </div>
      </div>
    </div>
  </section>
  <!-- Features Section -->
  <section id="features" class="section-container section-card max-w-5xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-6 sm:mb-8 glow stylish-text">Why Choose Us?</h2>
    <div class="masonry-grid">
      <div class="feature-card" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow" style="background: linear-gradient(45deg, #3b82f6, #60a5fa);"></div>
        <div class="feature-card-content">
          <i class="fas fa-rocket text-4xl mb-4 text-blue-600"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Lightning Fast</h3>
          <p class="text-base stylish-text mb-4">Experience blazing-fast performance with optimized tools for instant results.</p>
        </div>
      </div>
      <div class="feature-card" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow" style="background: linear-gradient(45deg, #3b82f6, #60a5fa);"></div>
        <div class="feature-card-content">
          <i class="fas fa-tools text-4xl mb-4 text-blue-600"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Versatile Toolkit</h3>
          <p class="text-base stylish-text mb-4">Access a wide range of tools for financial, media, and web tasks in one place.</p>
        </div>
      </div>
      <div class="feature-card" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow" style="background: linear-gradient(45deg, #3b82f6, #60a5fa);"></div>
        <div class="feature-card-content">
          <i class="fas fa-shield-alt text-4xl mb-4 text-blue-600"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Secure & Reliable</h3>
          <p class="text-base stylish-text mb-4">Built with security in mind, ensuring safe and dependable operations.</p>
        </div>
      </div>
      <div class="feature-card" data-tilt data-tilt-max="15" data-tilt-glare data-tilt-max-glare="0.4">
        <div class="holo-glow" style="background: linear-gradient(45deg, #3b82f6, #60a5fa);"></div>
        <div class="feature-card-content">
          <i class="fas fa-lightbulb text-4xl mb-4 text-blue-600"></i>
          <h3 class="text-xl font-bold stylish-text mb-2">Innovative Solutions</h3>
          <p class="text-base stylish-text mb-4">Cutting-edge tools designed to redefine the digital landscape with creativity.</p>
        </div>
      </div>
    </div>
  </section>
  <!-- Owner Section -->
  <section id="owner" class="section-container section-card max-w-5xl mx-auto text-center">
    <h2 class="text-3xl sm:text-4xl font-semibold mb-6 sm:mb-8 glow stylish-text">Owner Of This Project</h2>
    <div class="owner-card">
      <div class="holo-glow" style="background: linear-gradient(45deg, #3b82f6, #60a5fa);"></div>
      <div class="owner-card-content">
        <img src="https://i.ibb.co/M5R2zkx9/enhanced-2.png" alt="Developer Profile Logo" class="logo mb-4">
        <p class="text-base sm:text-lg stylish-text mb-4">Abir Arafat Chawdhury, a visionary entrepreneur and tech enthusiast, leads the charge as CEO and founder of Abir X Official Community. Crafting innovative web projects, Abir brings clients' visions to life through freelancing on Upwork and Fiverr. His passion for coding fuels a mission to create smarter, cutting-edge solutions that redefine the digital landscape.</p>
        <a href="https://t.me/ISmartCoder" target="_blank" class="inline-flex items-center btn-glow font-bold py-2 px-4 sm:py-3 sm:px-6 rounded-xl mt-4 stylish-text">
          <i class="fab fa-telegram mr-2 text-lg sm:text-xl"></i> Contact @ISmartCoder
        </a>
      </div>
    </div>
  </section>
  <!-- Footer -->
  <footer class="bg-gradient-to-r from-gray-100 to-gray-200 py-8 sm:py-12 mt-auto">
    <div class="footer-container max-w-5xl mx-auto text-center">
      <div class="footer-content">
        <h3 class="text-2xl sm:text-3xl font-semibold mb-4 sm:mb-6 glow stylish-text">Connect with Us</h3>
        <div class="flex justify-center space-x-4 sm:space-x-8 flex-wrap mb-8">
          <a href="https://facebook.com/abirxdhackz" target="_blank" class="social-icon"><i class="fab fa-facebook-f text-2xl sm:text-3xl"></i></a>
          <a href="https://instagram.com/abirxdhackz" target="_blank" class="social-icon"><i class="fab fa-instagram text-2xl sm:text-3xl"></i></a>
          <a href="https://x.com/abirxdhackz" target="_blank" class="social-icon"><i class="fab fa-x-twitter text-2xl sm:text-3xl"></i></a>
          <a href="https://github.com/abirxdhack" target="_blank" class="social-icon"><i class="fab fa-github text-2xl sm:text-3xl"></i></a>
          <a href="https://youtube.com/@abirxdhackz" target="_blank" class="social-icon"><i class="fab fa-youtube text-2xl sm:text-3xl"></i></a>
          <a href="https://t.me/ISmartCoder" target="_blank" class="social-icon"><i class="fab fa-telegram text-2xl sm:text-3xl"></i></a>
        </div>
        <p class="text-base sm:text-lg stylish-text mb-6">Empowering the digital world with innovative tools and solutions, crafted with passion by @ISmartCoder.</p>
        <div class="flex justify-center space-x-4 sm:space-x-6 mb-8">
          <a href="#privacy" class="text-sm stylish-text footer-link">Privacy Policy</a>
          <a href="#terms" class="text-sm stylish-text footer-link">Terms of Service</a>
          <a href="https://t.me/TheSmartDev" class="text-sm stylish-text footer-link">Updates</a>
        </div>
        <p class="text-base sm:text-lg font-bold stylish-text">© 2025 Smart Multifunctional Web. All rights reserved.</p>
      </div>
    </div>
  </footer>
  <!-- Scripts -->
  <script>
    // Three.js Particle Background
    const canvas = document.getElementById('three-canvas');
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ canvas, alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    camera.position.z = 5;
    const particles = new THREE.Group();
    const colors = [0x3b82f6, 0x10b981, 0x8b5cf6];
    for (let i = 0; i < 400; i++) {
      const geometry = new THREE.SphereGeometry(0.02, 16, 16);
      const material = new THREE.MeshBasicMaterial({ color: colors[Math.floor(Math.random() * colors.length)] });
      const particle = new THREE.Mesh(geometry, material);
      particle.position.set(
        (Math.random() - 0.5) * 12,
        (Math.random() - 0.5) * 12,
        (Math.random() - 0.5) * 12
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
        if (Math.abs(particle.position.x) > 6) particle.userData.velocity.x *= -1;
        if (Math.abs(particle.position.y) > 6) particle.userData.velocity.y *= -1;
        if (Math.abs(particle.position.z) > 6) particle.userData.velocity.z *= -1;
      });
      particles.rotation.y += 0.001;
      renderer.render(scene, camera);
    }
    animate();
    window.addEventListener('resize', () => {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    });
    // GSAP Scroll Animations
    gsap.registerPlugin(ScrollTrigger);
    document.querySelectorAll('.section-container').forEach(section => {
      gsap.to(section, {
        scrollTrigger: {
          trigger: section,
          start: 'top 80%',
          end: 'bottom 20%',
          toggleClass: 'visible',
          once: false
        }
      });
    });
    
    VanillaTilt.init(document.querySelectorAll('.tool-card, .feature-card, .owner-card'), {
      max: 15,
      speed: 400,
      glare: true,
      'max-glare': 0.4
    });
    
    function useTool(toolName) {
      alert(`${toolName} is coming soon! Check t.me/TheSmartDev for updates.`);
    }
  </script>
</body>
</html>