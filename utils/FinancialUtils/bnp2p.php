<?php
// Copyright @ISmartCoder
// Updates Channel t.me/TheSmartDev 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    $action = isset($_POST['action']) ? filter_var($_POST['action'], FILTER_SANITIZE_STRING) : '';

    if ($action === 'fetch_currencies') {
        $url = 'https://a360api-c8fbf2fa3cda.herokuapp.com/p2p/currencies';
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
            echo json_encode(['error' => 'Failed to fetch currencies: ' . ($curl_error ?: 'HTTP ' . $http_code)]);
            exit;
        }
        $data = json_decode($response, true);
        if ($data && isset($data['success']) && $data['success']) {
            echo json_encode($data['data']);
        } else {
            echo json_encode(['error' => 'Failed to fetch currencies']);
        }
        exit;
    }

    if ($action === 'fetch_methods') {
        $pay_type = isset($_POST['pay_type']) ? filter_var($_POST['pay_type'], FILTER_SANITIZE_STRING) : 'BDT';
        $url = 'https://a360api-c8fbf2fa3cda.herokuapp.com/p2p/methods';
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
            echo json_encode(['error' => 'Failed to fetch payment methods: ' . ($curl_error ?: 'HTTP ' . $http_code)]);
            exit;
        }
        $data = json_decode($response, true);
        if ($data && isset($data['success']) && $data['success']) {
            echo json_encode(['methods' => array_values($data['data'][$pay_type] ?? []), 'all_methods' => $data['data']]);
        } else {
            echo json_encode(['error' => 'Failed to fetch payment methods']);
        }
        exit;
    }

    if ($action === 'fetch_p2p') {
        $asset = isset($_POST['asset']) ? filter_var($_POST['asset'], FILTER_SANITIZE_STRING) : 'USDT';
        $pay_type = isset($_POST['pay_type']) ? filter_var($_POST['pay_type'], FILTER_SANITIZE_STRING) : 'BDT';
        $pay_method = isset($_POST['pay_method']) ? filter_var($_POST['pay_method'], FILTER_SANITIZE_STRING) : 'ALL';
        $trade_type = isset($_POST['trade_type']) ? filter_var($_POST['trade_type'], FILTER_SANITIZE_STRING) : 'SELL';
        $limit = isset($_POST['limit']) ? filter_var($_POST['limit'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 500]]) : 100;
        $sort_by = isset($_POST['sort_by']) ? filter_var($_POST['sort_by'], FILTER_SANITIZE_STRING) : 'price';
        $order = isset($_POST['order']) ? filter_var($_POST['order'], FILTER_SANITIZE_STRING) : 'asc';
        $min_completion_rate = isset($_POST['min_completion_rate']) ? filter_var($_POST['min_completion_rate'], FILTER_VALIDATE_FLOAT) : null;
        $min_orders = isset($_POST['min_orders']) ? filter_var($_POST['min_orders'], FILTER_VALIDATE_INT) : null;
        $online_only = isset($_POST['online_only']) ? filter_var($_POST['online_only'], FILTER_VALIDATE_BOOLEAN) : false;
        $query_params = [
            'asset' => $asset,
            'pay_type' => $pay_type,
            'pay_method' => $pay_method,
            'trade_type' => $trade_type,
            'limit' => $limit,
            'sort_by' => $sort_by,
            'order' => $order
        ];
        if ($min_completion_rate !== null) $query_params['min_completion_rate'] = $min_completion_rate;
        if ($min_orders !== null) $query_params['min_orders'] = $min_orders;
        if ($online_only) $query_params['online_only'] = 'true';
        $url = 'https://a360api-c8fbf2fa3cda.herokuapp.com/p2p?' . http_build_query($query_params);
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
            echo json_encode(['error' => 'Failed to fetch P2P data: ' . ($curl_error ?: 'HTTP ' . $http_code)]);
            exit;
        }
        $data = json_decode($response, true);
        if ($data && isset($data['success']) && $data['success']) {
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'API error: Invalid response or no data']);
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
  <meta name="description" content="CC Xen P2P Checker: Fetch Binance P2P trading offers with ease. Developed by Abir Arafat Chawdhury (@ISmartCoder).">
  <meta name="keywords" content="CC Xen P2P Checker, Binance P2P, crypto trading, @ISmartCoder">
  <meta name="author" content="Abir Arafat Chawdhury (@ISmartCoder)">
  <title>Smat P2P Checker</title>
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
      padding: 0 1rem;
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
      margin: 0 auto;
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
    .input-field, select {
      background: rgba(255, 255, 255, 0.9);
      color: #1f2937;
      border: 1px solid #d1d5db;
      padding: 1rem;
      border-radius: 0.75rem;
      width: 100%;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      animation: slideIn 0.5s ease-in-out;
    }
    .input-field:focus, select:focus {
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
      font-size: 1.2rem;
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
      margin: 1.5rem auto;
      font-size: 1.2rem;
      font-weight: 500;
      animation: shake 0.3s ease-in-out;
      max-width: 90%;
    }
    .result-box {
      background: rgba(255, 255, 255, 0.9);
      border: 1px solid #e5e7eb;
      border-radius: 1rem;
      padding: 2rem;
      margin: 2rem auto;
      font-family: 'Inter', sans-serif;
      font-size: 1.1rem;
      line-height: 1.8;
      color: #1f2937;
      box-shadow: inset 0 3px 12px rgba(0, 0, 0, 0.05);
      animation: slideIn 0.6s ease-in-out;
      max-width: 80vw;
      overflow-x: auto;
    }
    .json-result {
      white-space: pre-wrap;
      font-family: 'Courier New', monospace;
      font-size: 1rem;
      padding: 1rem;
      background: #f5f5f5;
      border-radius: 0.5rem;
      max-height: 400px;
      overflow-y: auto;
    }
    .list-box {
      background: rgba(255, 255, 255, 0.9);
      border: 1px solid #e5e7eb;
      border-radius: 1rem;
      padding: 1.5rem;
      margin: 2rem auto;
      font-family: 'Inter', sans-serif;
      font-size: 1.1rem;
      line-height: 1.6;
      color: #1f2937;
      box-shadow: inset 0 3px 12px rgba(0, 0, 0, 0.05);
      animation: slideIn 0.6s ease-in-out;
      max-width: 80vw;
    }
    .list-box ul {
      list-style: none;
      padding: 0;
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
    }
    .list-box li {
      background: rgba(124, 58, 237, 0.1);
      padding: 0.75rem 1.5rem;
      border-radius: 0.5rem;
      font-weight: 500;
      font-size: 1.1rem;
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
    <img src="https://i.ibb.co/yBNCxwvM/enhanced.png" alt="CC Xen Generator Logo" class="logo">
    <div class="flex items-center justify-center mt-6 sm:mt-8">
      <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold glow stylish-text mr-4">Smart P2P Checker Tool</h1>
      <span class="inline-block w-12 h-12">
        <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12" cy="12" r="10" fill="#10b981" stroke="#ffffff" stroke-width="2"/>
          <path d="M9 12.5L11 15L15 9" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
    </div>
    <p class="welcome-message mt-3 sm:mt-4 tracking-wide stylish-text text-lg sm:text-xl leading-relaxed max-w-5xl mx-auto">
      The Ultimate P2P Checker: Fetch Binance P2P trading offers with ease. Built with passion by @ISmartCoder. Still under active development, daily updated by @TheSmartDev.
    </p>
    <div class="status-indicator mt-6 text-lg sm:text-xl stylish-text flex items-center justify-center">
      <span class="status-dot mr-2"></span> Status: <i>Online</i>
    </div>
  </header>
  <section id="p2p-checker" class="section-card max-w-5xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-8 sm:mb-10 glow stylish-text">P2P Trading Checker</h2>
    <div class="tool-card">
      <div class="tool-card-content">
        <div class="flex justify-center mb-8">
          <i class="fas fa-exchange-alt text-6xl text-gray-600 animate-pulse"></i>
        </div>
        <h3 class="text-2xl font-bold stylish-text mb-8 text-center">Fetch P2P Offers</h3>
        <div id="form-container" class="mb-8">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium stylish-text mb-3">Asset</label>
              <select id="asset" class="input-field"><option>Loading...</option></select>
            </div>
            <div>
              <label class="block text-sm font-medium stylish-text mb-3">Fiat Currency</label>
              <select id="pay_type" class="input-field"><option>Loading...</option></select>
            </div>
            <div>
              <label class="block text-sm font-medium stylish-text mb-3">Payment Method</label>
              <select id="pay_method" class="input-field"><option value="ALL">ALL</option></select>
            </div>
            <div>
              <label class="block text-sm font-medium stylish-text mb-3">Trade Type</label>
              <select id="trade_type" class="input-field">
                <option value="SELL">SELL</option>
                <option value="BUY">BUY</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium stylish-text mb-3">Limit (1-500)</label>
              <input type="number" id="limit" class="input-field" placeholder="e.g., 100" min="1" max="500" value="100">
            </div>
            <div>
              <label class="block text-sm font-medium stylish-text mb-3">Sort By</label>
              <select id="sort_by" class="input-field">
                <option value="price">Price</option>
                <option value="completion_rate">Completion Rate</option>
                <option value="available_amount">Available Amount</option>
                <option value="monthly_orders">Monthly Orders</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium stylish-text mb-3">Order</label>
              <select id="order" class="input-field">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium stylish-text mb-3">Min Completion Rate (0-100)</label>
              <input type="number" id="min_completion_rate" class="input-field" placeholder="e.g., 90" min="0" max="100">
            </div>
            <div>
              <label class="block text-sm font-medium stylish-text mb-3">Min Monthly Orders</label>
              <input type="number" id="min_orders" class="input-field" placeholder="e.g., 10">
            </div>
            <div>
              <label class="block text-sm font-medium stylish-text mb-3">Online Only</label>
              <select id="online_only" class="input-field">
                <option value="false">No</option>
                <option value="true">Yes</option>
              </select>
            </div>
          </div>
        </div>
        <div class="text-center">
          <button onclick="fetchP2P()" class="button font-bold">Fetch Offers</button>
        </div>
        <p id="error" class="error hidden"></p>
        <div id="results" class="hidden">
          <h4 class="text-xl font-bold stylish-text mb-6 text-center">P2P Offers</h4>
          <div class="result-box">
            <p><i class="fas fa-chart-line"></i> <strong>Average Price:</strong> <span id="avg_price"></span> <span id="fiat_unit"></span></p>
            <p><i class="fas fa-arrow-down"></i> <strong>Min Price:</strong> <span id="min_price"></span> <span id="fiat_unit_min"></span></p>
            <p><i class="fas fa-arrow-up"></i> <strong>Max Price:</strong> <span id="max_price"></span> <span id="fiat_unit_max"></span></p>
            <p><i class="fas fa-wallet"></i> <strong>Total Available:</strong> <span id="total_available"></span> <span id="asset_unit"></span></p>
            <p><i class="fas fa-users"></i> <strong>Total Sellers:</strong> <span id="total_found"></span></p>
            <p><i class="fas fa-user"></i> <strong>API Owner:</strong> <span id="api_owner"></span></p>
            <p><i class="fas fa-bullhorn"></i> <strong>Updates Channel:</strong> <a id="api_updates" href="" target="_blank" class="text-blue-600 hover:underline"></a></p>
            <p><i class="fas fa-clock"></i> <strong>Timestamp:</strong> <span id="timestamp"></span></p>
          </div>
          <div id="json-result-box" class="result-box mt-4">
            <h4 class="text-xl font-bold stylish-text mb-4">Raw JSON Response</h4>
            <pre id="json-result" class="json-result"></pre>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section id="currencies-checker" class="section-card max-w-5xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-8 sm:mb-10 glow stylish-text">Available Currencies</h2>
    <div class="tool-card">
      <div class="tool-card-content">
        <div class="flex justify-center mb-8">
          <i class="fas fa-coins text-6xl text-gray-600 animate-pulse"></i>
        </div>
        <h3 class="text-2xl font-bold stylish-text mb-8 text-center">Supported Assets & Currencies</h3>
        <div class="list-box">
          <h4>Cryptocurrencies</h4>
          <ul id="crypto-assets"><li>Loading...</li></ul>
        </div>
        <div class="list-box mt-4">
          <h4>Fiat Currencies</h4>
          <ul id="fiat-currencies"><li>Loading...</li></ul>
        </div>
      </div>
    </div>
  </section>
  <section id="methods-checker" class="section-card max-w-5xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-8 sm:mb-10 glow stylish-text">Available Payment Methods</h2>
    <div class="tool-card">
      <div class="tool-card-content">
        <div class="flex justify-center mb-8">
          <i class="fas fa-credit-card text-6xl text-gray-600 animate-pulse"></i>
        </div>
        <h3 class="text-2xl font-bold stylish-text mb-8 text-center">Payment Methods for Selected Fiat</h3>
        <div class="grid grid-cols-1 max-w-md mx-auto mb-6">
          <div>
            <label class="block text-sm font-medium stylish-text mb-3">Select Fiat Currency</label>
            <select id="methods-pay-type" class="input-field"><option>Loading...</option></select>
          </div>
        </div>
        <div class="text-center mb-6">
          <button onclick="fetchPaymentMethods()" class="button font-bold">Fetch Payment Methods</button>
        </div>
        <p id="methods-error" class="error hidden"></p>
        <div class="list-box">
          <h4>Payment Methods</h4>
          <ul id="payment-methods"><li>Loading...</li></ul>
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
        <p class="text-base sm:text-lg font-bold stylish-text">© 2025 Smart Binance P2P. All rights reserved.</p>
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
    async function fetchCurrencies() {
      try {
        const formData = new FormData();
        formData.append('action', 'fetch_currencies');
        const response = await fetch('', {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        const data = await response.json();
        if (data.error) throw new Error(data.error);
        const assetSelect = document.getElementById('asset');
        const payTypeSelect = document.getElementById('pay_type');
        const methodsPayTypeSelect = document.getElementById('methods-pay-type');
        const cryptoAssetsList = document.getElementById('crypto-assets');
        const fiatCurrenciesList = document.getElementById('fiat-currencies');
        assetSelect.innerHTML = data.crypto_assets.map(asset => `<option value="${asset}">${asset}</option>`).join('');
        payTypeSelect.innerHTML = data.fiat_currencies.map(currency => `<option value="${currency}">${currency}</option>`).join('');
        methodsPayTypeSelect.innerHTML = data.fiat_currencies.map(currency => `<option value="${currency}">${currency}</option>`).join('');
        assetSelect.value = 'USDT';
        payTypeSelect.value = 'BDT';
        methodsPayTypeSelect.value = 'BDT';
        cryptoAssetsList.innerHTML = data.crypto_assets.map(asset => `<li>${asset}</li>`).join('');
        fiatCurrenciesList.innerHTML = data.fiat_currencies.map(currency => `<li>${currency}</li>`).join('');
        fetchPaymentMethods();
      } catch (err) {
        console.error('Failed to fetch currencies:', err);
        document.getElementById('error').textContent = err.message;
        document.getElementById('error').classList.remove('hidden');
      }
    }
    async function fetchPaymentMethods() {
      const payType = document.getElementById('pay_type').value;
      const methodsPayType = document.getElementById('methods-pay-type').value;
      try {
        const formData = new FormData();
        formData.append('action', 'fetch_methods');
        formData.append('pay_type', methodsPayType);
        const response = await fetch('', {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        const data = await response.json();
        if (data.error) throw new Error(data.error);
        const payMethodSelect = document.getElementById('pay_method');
        const paymentMethodsList = document.getElementById('payment-methods');
        payMethodSelect.innerHTML = ['ALL', ...data.methods].map(method => `<option value="${method}">${method}</option>`).join('');
        payMethodSelect.value = 'ALL';
        paymentMethodsList.innerHTML = data.methods.length ? data.methods.map(method => `<li>${method}</li>`).join('') : '<li>No payment methods available</li>';
      } catch (err) {
        console.error('Failed to fetch payment methods:', err);
        document.getElementById('methods-error').textContent = err.message;
        document.getElementById('methods-error').classList.remove('hidden');
      }
    }
    async function fetchP2P() {
      const errorEl = document.getElementById('error');
      const resultsEl = document.getElementById('results');
      const jsonResult = document.getElementById('json-result');
      const button = document.querySelector('#p2p-checker .button');
      const asset = document.getElementById('asset').value;
      const pay_type = document.getElementById('pay_type').value;
      const pay_method = document.getElementById('pay_method').value;
      const trade_type = document.getElementById('trade_type').value;
      const limit = document.getElementById('limit').value;
      const sort_by = document.getElementById('sort_by').value;
      const order = document.getElementById('order').value;
      const min_completion_rate = document.getElementById('min_completion_rate').value;
      const min_orders = document.getElementById('min_orders').value;
      const online_only = document.getElementById('online_only').value;
      button.textContent = 'Fetching...';
      button.disabled = true;
      errorEl.classList.add('hidden');
      resultsEl.classList.add('hidden');
      jsonResult.textContent = 'Loading...';
      const formData = new FormData();
      formData.append('action', 'fetch_p2p');
      formData.append('asset', asset);
      formData.append('pay_type', pay_type);
      formData.append('pay_method', pay_method);
      formData.append('trade_type', trade_type);
      formData.append('limit', limit || '100');
      formData.append('sort_by', sort_by);
      formData.append('order', order);
      if (min_completion_rate) formData.append('min_completion_rate', min_completion_rate);
      if (min_orders) formData.append('min_orders', min_orders);
      formData.append('online_only', online_only);
      try {
        const response = await fetch('', {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        const data = await response.json();
        if (data.error) {
          errorEl.textContent = data.error;
          errorEl.classList.remove('hidden');
        } else {
          document.getElementById('avg_price').textContent = data.statistics.avg_price;
          document.getElementById('min_price').textContent = data.statistics.min_price;
          document.getElementById('max_price').textContent = data.statistics.max_price;
          document.getElementById('total_available').textContent = data.statistics.total_available;
          document.getElementById('total_found').textContent = data.total_found;
          document.getElementById('api_owner').textContent = data.api_owner;
          document.getElementById('api_updates').textContent = data.api_updates;
          document.getElementById('api_updates').href = data.api_updates;
          document.getElementById('timestamp').textContent = data.timestamp;
          document.getElementById('fiat_unit').textContent = pay_type;
          document.getElementById('fiat_unit_min').textContent = pay_type;
          document.getElementById('fiat_unit_max').textContent = pay_type;
          document.getElementById('asset_unit').textContent = asset;
          jsonResult.textContent = JSON.stringify(data, null, 2);
          resultsEl.classList.remove('hidden');
        }
      } catch (err) {
        errorEl.textContent = 'Failed to process request: ' + err.message;
        errorEl.classList.remove('hidden');
      } finally {
        button.textContent = 'Fetch Offers';
        button.disabled = false;
      }
    }
    document.getElementById('pay_type').addEventListener('change', fetchPaymentMethods);
    document.getElementById('methods-pay-type').addEventListener('change', fetchPaymentMethods);
    document.addEventListener('DOMContentLoaded', fetchCurrencies);
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.onkeydown = function(e) {
      if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J')) || (e.ctrlKey && e.key === 'U')) {
        return false;
      }
    };
  </script>
</body>
</html>