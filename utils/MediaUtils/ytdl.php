<?php
// Copyright @ISmartCoder
// Updates Channel t.me/TheSmartDev 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    
    function extract_video_id($url) {
        $patterns = [
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([^&?\s]+)/',
            '/(?:https?:\/\/)?youtu\.be\/([^&?\s]+)/',
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/embed\/([^&?\s]+)/',
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/v\/([^&?\s]+)/',
            '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/shorts\/([^&?\s]+)/'
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        if (preg_match('/v=([^&?\s]+)/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    function parse_duration($duration) {
        if (empty($duration)) return 'N/A';
        preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $duration, $matches);
        $hours = isset($matches[1]) ? (int)$matches[1] : 0;
        $minutes = isset($matches[2]) ? (int)$matches[2] : 0;
        $seconds = isset($matches[3]) ? (int)$matches[3] : 0;
        $formatted = '';
        if ($hours > 0) $formatted .= $hours . 'h ';
        if ($minutes > 0) $formatted .= $minutes . 'm ';
        if ($seconds > 0) $formatted .= $seconds . 's';
        return trim($formatted) ?: '0s';
    }

    function fetch_youtube_details($video_id) {
        $api_key = 'AIzaSyClox4nsUjqMT7cqKhaz7asQGeWe5E-1gE';
        $url = "https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics,contentDetails&id={$video_id}&key={$api_key}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($response === false || $http_code !== 200) {
            return ['error' => 'Failed to fetch YouTube video details.'];
        }
        $data = json_decode($response, true);
        if (!$data || !isset($data['items']) || empty($data['items'])) {
            return ['error' => 'No video found for the provided ID.'];
        }
        $video = $data['items'][0];
        $snippet = $video['snippet'];
        $stats = $video['statistics'];
        $content_details = $video['contentDetails'];
        return [
            'title' => htmlspecialchars_decode($snippet['title'] ?? 'N/A', ENT_QUOTES),
            'channel' => htmlspecialchars_decode($snippet['channelTitle'] ?? 'N/A', ENT_QUOTES),
            'description' => htmlspecialchars_decode($snippet['description'] ?? 'N/A', ENT_QUOTES),
            'imageUrl' => $snippet['thumbnails']['high']['url'] ?? '',
            'duration' => parse_duration($content_details['duration'] ?? ''),
            'views' => number_format($stats['viewCount'] ?? 0, 0, '.', ','),
            'likes' => number_format($stats['likeCount'] ?? 0, 0, '.', ','),
            'comments' => number_format($stats['commentCount'] ?? 0, 0, '.', ',')
        ];
    }

    function fetch_youtube_search($query) {
        $api_key = 'AIzaSyClox4nsUjqMT7cqKhaz7asQGeWe5E-1gE';
        $search_url = "https://www.googleapis.com/youtube/v3/search?part=snippet&q=" . urlencode($query) . "&type=video&maxResults=10&key={$api_key}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $search_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $search_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($search_response === false || $http_code !== 200) {
            return ['error' => 'Failed to fetch search data.'];
        }
        $search_data = json_decode($search_response, true);
        $video_ids = array_map(function($item) { return $item['id']['videoId']; }, $search_data['items'] ?? []);
        if (empty($video_ids)) {
            return ['error' => 'No videos found for the provided query.'];
        }
        $videos_url = "https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics,contentDetails&id=" . implode(',', $video_ids) . "&key={$api_key}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $videos_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $videos_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($videos_response === false || $http_code !== 200) {
            return ['error' => 'Failed to fetch video details.'];
        }
        $videos_data = json_decode($videos_response, true);
        $videos_map = [];
        foreach ($videos_data['items'] ?? [] as $video) {
            $videos_map[$video['id']] = $video;
        }
        $result = [];
        foreach ($search_data['items'] ?? [] as $item) {
            $video_id = $item['id']['videoId'];
            $snippet = $item['snippet'];
            $video = $videos_map[$video_id] ?? [];
            $content_details = $video['contentDetails'] ?? [];
            $stats = $video['statistics'] ?? [];
            $result[] = [
                'title' => htmlspecialchars_decode($snippet['title'] ?? 'N/A', ENT_QUOTES),
                'channel' => htmlspecialchars_decode($snippet['channelTitle'] ?? 'N/A', ENT_QUOTES),
                'imageUrl' => $snippet['thumbnails']['high']['url'] ?? '',
                'link' => "https://www.youtube.com/watch?v={$video_id}",
                'duration' => parse_duration($content_details['duration'] ?? ''),
                'views' => number_format($stats['viewCount'] ?? 0, 0, '.', ','),
                'likes' => number_format($stats['likeCount'] ?? 0, 0, '.', ','),
                'comments' => number_format($stats['commentCount'] ?? 0, 0, '.', ',')
            ];
        }
        return $result;
    }

    if (isset($_POST['url']) && !empty($_POST['url'])) {
        $url = filter_var($_POST['url'], FILTER_SANITIZE_URL);
        $video_id = extract_video_id($url);
        if (!$video_id) {
            echo json_encode([
                'error' => 'Invalid YouTube URL.',
                'api_owner' => '@ISmartCoder',
                'api_updates' => 't.me/TheSmartDevs'
            ]);
            exit;
        }
        $standard_url = "https://www.youtube.com/watch?v={$video_id}";
        $youtube_data = fetch_youtube_details($video_id);
        if (isset($youtube_data['error'])) {
            $youtube_data = [
                'title' => 'Unavailable',
                'channel' => 'N/A',
                'description' => 'N/A',
                'imageUrl' => "https://img.youtube.com/vi/{$video_id}/hqdefault.jpg",
                'duration' => 'N/A',
                'views' => 'N/A',
                'likes' => 'N/A',
                'comments' => 'N/A'
            ];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.clipto.com/api/youtube');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['url' => $standard_url]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $ordered = [
            'api_owner' => '@ISmartCoder',
            'api_updates' => 't.me/TheSmartDevs'
        ];
        if ($response !== false && $http_code === 200) {
            $data = json_decode($response, true);
            $ordered['title'] = htmlspecialchars_decode($data['title'] ?? $youtube_data['title'], ENT_QUOTES);
            $ordered['channel'] = $youtube_data['channel'];
            $ordered['description'] = htmlspecialchars_decode($data['description'] ?? $youtube_data['description'], ENT_QUOTES);
            $ordered['thumbnail'] = $data['thumbnail'] ?? $youtube_data['imageUrl'];
            $ordered['thumbnail_url'] = "https://img.youtube.com/vi/{$video_id}/hqdefault.jpg";
            $ordered['duration'] = $youtube_data['duration'];
            $ordered['views'] = $youtube_data['views'];
            $ordered['likes'] = $youtube_data['likes'];
            $ordered['comments'] = $youtube_data['comments'];
            
            $preferred_formats = ['137', '136', '18', '135', '134', '133']; 
            $download_url = null;
            if (isset($data['medias']) && is_array($data['medias'])) {
                foreach ($preferred_formats as $format_id) {
                    foreach ($data['medias'] as $media) {
                        if ($media['formatId'] == $format_id && $media['type'] === 'video' && $media['ext'] === 'mp4') {
                            $download_url = $media['url'];
                            $ordered['download_format'] = $media['label'];
                            break 2;
                        }
                    }
                }
            }
            $ordered['url'] = $data['url'] ?? $standard_url;
            $ordered['download_url'] = $download_url; 
            if (!$download_url) {
                $ordered['error'] = 'No valid download URL found in API response.';
            }
            foreach ($data as $key => $value) {
                if (!array_key_exists($key, $ordered)) {
                    $ordered[$key] = $value;
                }
            }
        } else {
            $ordered['title'] = $youtube_data['title'];
            $ordered['channel'] = $youtube_data['channel'];
            $ordered['description'] = $youtube_data['description'];
            $ordered['thumbnail'] = $youtube_data['imageUrl'];
            $ordered['thumbnail_url'] = "https://img.youtube.com/vi/{$video_id}/hqdefault.jpg";
            $ordered['url'] = $standard_url;
            $ordered['download_url'] = null;
            $ordered['duration'] = $youtube_data['duration'];
            $ordered['views'] = $youtube_data['views'];
            $ordered['likes'] = $youtube_data['likes'];
            $ordered['comments'] = $youtube_data['comments'];
            $ordered['error'] = 'Failed to fetch download URL from Clipto API.';
        }
        echo json_encode($ordered);
        exit;
    } elseif (isset($_POST['query']) && !empty($_POST['query'])) {
        $query = filter_var($_POST['query'], FILTER_SANITIZE_STRING);
        $search_data = fetch_youtube_search($query);
        if (isset($search_data['error'])) {
            echo json_encode([
                'error' => $search_data['error'],
                'api_owner' => '@ISmartCoder',
                'api_updates' => 't.me/TheSmartDevs'
            ]);
            exit;
        }
        echo json_encode([
            'api_owner' => '@ISmartCoder',
            'api_updates' => 't.me/TheSmartDevs',
            'result' => $search_data
        ]);
        exit;
    } else {
        echo json_encode([
            'error' => 'Missing required parameters.',
            'api_owner' => '@ISmartCoder',
            'api_updates' => 't.me/TheSmartDevs'
        ]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta name="description" content="Smart YouTube Downloader: Download and search YouTube videos with a sleek, modern UI. Developed by Abir Arafat Chawdhury (@ISmartCoder).">
  <meta name="keywords" content="YouTube Downloader, YouTube Search, Video Downloader, @ISmartCoder">
  <meta name="author" content="Abir Arafat Chawdhury (@ISmartCoder)">
  <title>Smart YouTube Downloader</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@400;700&family=Orbitron:wght@400;700&family=Roboto+Mono:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      color: #1f2937;
      overflow-x: hidden;
      overflow-y: auto;
      margin: 0;
      background: linear-gradient(135deg, #a5b4fc, #f5d0fe);
    }
    #three-canvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      opacity: 0.75;
    }
    .section-card {
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(25px);
      border-radius: 2rem;
      padding: 2.5rem;
      margin: 2rem auto;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
      border: 1px solid rgba(209, 213, 219, 0.3);
      max-width: 90vw;
      position: relative;
      z-index: 10;
      animation: fadeIn 1.2s ease-in-out;
    }
    @media (min-width: 640px) {
      .section-card {
        padding: 3.5rem;
        max-width: 85vw;
      }
    }
    .tool-card {
      background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(240, 248, 255, 0.9));
      backdrop-filter: blur(15px);
      border-radius: 1.5rem;
      padding: 3rem;
      box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
      max-width: 95vw;
      border: 2px solid rgba(209, 213, 219, 0.3);
      transition: transform 0.4s ease, box-shadow 0.4s ease;
    }
    .tool-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 70px rgba(0, 0, 0, 0.25);
    }
    @media (min-width: 640px) {
      .tool-card {
        padding: 4rem;
        max-width: 80vw;
      }
    }
    .input-field {
      background: rgba(255, 255, 255, 0.97);
      color: #1f2937;
      border: 2px solid #d1d5db;
      padding: 1.3rem;
      border-radius: 1rem;
      width: 100%;
      font-size: 1.1rem;
      font-family: 'Roboto Mono', monospace;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      animation: slideIn 0.6s ease-in-out;
    }
    .input-field:focus {
      outline: none;
      border-color: #7c3aed;
      box-shadow: 0 0 20px rgba(124, 58, 237, 0.5);
      transform: scale(1.03);
    }
    .cool-button {
      background: linear-gradient(90deg, #7c3aed, #db2777);
      color: #ffffff;
      padding: 1.3rem 4rem;
      border-radius: 1rem;
      font-size: 1.2rem;
      font-weight: 700;
      font-family: 'Orbitron', sans-serif;
      box-shadow: 0 8px 25px rgba(124, 58, 237, 0.6);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      animation: pulseButton 2s infinite;
    }
    .cool-button::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
      transition: 0.5s;
    }
    .cool-button:hover::before {
      left: 100%;
    }
    .cool-button:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 35px rgba(124, 58, 237, 0.8);
    }
    .cool-button:disabled {
      background: #9ca3af;
      box-shadow: none;
      cursor: not-allowed;
    }
    .error {
      color: #ef4444;
      text-align: center;
      margin-top: 1.5rem;
      font-size: 1.2rem;
      font-weight: 600;
      font-family: 'Orbitron', sans-serif;
      animation: shake 0.4s ease-in-out;
    }
    .result-box {
      background: rgba(255, 255, 255, 0.98);
      border: 2px solid #e5e7eb;
      border-radius: 1.5rem;
      padding: 2rem;
      margin-top: 2rem;
      font-family: 'Roboto Mono', monospace;
      font-size: 1rem;
      line-height: 1.8;
      color: #1f2937;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      animation: slideIn 0.7s ease-in-out;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
      align-items: start;
      max-width: 100%;
      overflow: hidden;
    }
    @media (max-width: 768px) {
      .result-box {
        grid-template-columns: 1fr;
        gap: 1rem;
      }
    }
    .result-box .thumbnail-container {
      grid-column: 1 / -1;
      text-align: center;
    }
    .result-box .thumbnail-container img {
      max-width: 100%;
      max-height: 200px;
      border-radius: 1rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .result-box .download-container {
      grid-column: 1 / -1;
      text-align: center;
    }
    .result-box p {
      margin: 0.5rem 0;
      display: flex;
      align-items: flex-start;
      gap: 0.5rem;
      flex-wrap: wrap;
    }
    .result-box p strong {
      font-weight: 600;
      color: #1f2937;
      min-width: 100px;
      flex: 0 0 auto;
    }
    .result-box p span {
      flex: 1;
      word-break: break-word;
      max-width: 100%;
    }
    .result-box p.description {
      grid-column: 1 / -1;
    }
    .result-box p.description span {
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .result-box p a {
      display: inline-block;
    }
    .video-card {
      background: rgba(255, 255, 255, 0.98);
      border: 2px solid #e5e7eb;
      border-radius: 1.5rem;
      padding: 2rem;
      margin: 1.5rem 0;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      transition: transform 0.4s ease, box-shadow 0.4s ease;
      animation: slideIn 0.8s ease-in-out;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
      align-items: start;
      max-width: 100%;
      overflow: hidden;
    }
    @media (max-width: 768px) {
      .video-card {
        grid-template-columns: 1fr;
        gap: 1rem;
      }
    }
    .video-card .thumbnail-container {
      grid-column: 1 / -1;
      text-align: center;
    }
    .video-card .thumbnail-container img {
      max-width: 100%;
      max-height: 200px;
      border-radius: 1rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .video-card .buttons-container {
      grid-column: 1 / -1;
      display: flex;
      justify-content: center;
      gap: 1rem;
      flex-wrap: wrap;
    }
    .video-card:hover {
      transform: translateY(-12px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }
    .video-card p {
      margin: 0.5rem 0;
      display: flex;
      align-items: flex-start;
      gap: 0.5rem;
      flex-wrap: wrap;
    }
    .video-card p strong {
      font-weight: 600;
      color: #1f2937;
      min-width: 100px;
      flex: 0 0 auto;
    }
    .video-card p span {
      flex: 1;
      word-break: break-word;
      max-width: 100%;
    }
    .action-button {
      background: linear-gradient(90deg, #3b82f6, #10b981);
      color: #ffffff;
      padding: 0.9rem 2.5rem;
      border-radius: 0.75rem;
      font-size: 1rem;
      font-weight: 600;
      font-family: 'Orbitron', sans-serif;
      box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
      transition: all 0.3s ease;
      display: inline-block;
      text-align: center;
      text-decoration: none;
      position: relative;
      overflow: hidden;
    }
    .action-button.disabled {
      background: #9ca3af;
      cursor: not-allowed;
      box-shadow: none;
    }
    .action-button::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: 0.5s;
    }
    .action-button:not(.disabled):hover::before {
      left: 100%;
    }
    .action-button:not(.disabled):hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 25px rgba(59, 130, 246, 0.7);
    }
    .tab {
      background: linear-gradient(90deg, #7c3aed, #db2777);
      color: #ffffff;
      padding: 1rem 2.5rem;
      border-radius: 0.75rem;
      font-size: 1.1rem;
      font-weight: 600;
      font-family: 'Orbitron', sans-serif;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    .tab.active {
      background: linear-gradient(90deg, #3b82f6, #10b981);
      box-shadow: 0 8px 20px rgba(59, 130, 246, 0.6);
    }
    .tab:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(124, 58, 237, 0.6);
    }
    .tab::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: 0.5s;
    }
    .tab:hover::before {
      left: 100%;
    }
    .tab-content {
      display: none;
      animation: fadeIn 0.5s ease-in-out;
    }
    .tab-content.active {
      display: block;
    }
    .yt-icon {
      font-size: 3rem;
      color: #ff0000;
      margin: 0 auto 1rem;
      display: block;
      text-align: center;
    }
    .glow {
      font-family: 'Orbitron', sans-serif;
      text-shadow: 0 0 15px rgba(124, 58, 237, 0.7), 0 0 30px rgba(124, 58, 237, 0.5);
      letter-spacing: 1.5px;
    }
    .stylish-text {
      font-family: 'Poppins', sans-serif;
      letter-spacing: 0.7px;
      color: #1f2937;
    }
    .logo {
      border-radius: 50%;
      object-fit: cover;
      border: 5px solid #7c3aed;
      box-shadow: 0 0 35px rgba(124, 58, 237, 0.7);
      width: 24vw;
      height: 24vw;
      max-width: 200px;
      max-height: 200px;
      margin: 0 auto;
      position: relative;
      animation: logoGlow 2.5s ease-in-out infinite;
      transition: transform 0.4s ease-in-out;
    }
    .logo:hover {
      transform: scale(1.2);
    }
    @keyframes logoGlow {
      0% { box-shadow: 0 0 12px rgba(124, 58, 237, 0.7), 0 0 25px rgba(124, 58, 237, 0.5); }
      50% { box-shadow: 0 0 35px rgba(124, 58, 237, 1), 0 0 45px rgba(124, 58, 237, 0.7); }
      100% { box-shadow: 0 0 12px rgba(124, 58, 237, 0.7), 0 0 25px rgba(124, 58, 237, 0.5); }
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
      gap: 0.75rem;
      font-weight: bold;
      font-size: 1.1rem;
      color: #10b981;
      text-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease;
    }
    .status-indicator:hover {
      transform: translateY(-4px);
    }
    @media (min-width: 640px) {
      .status-indicator {
        font-size: 1.2rem;
      }
    }
    .status-dot {
      width: 14px;
      height: 14px;
      border-radius: 50%;
      background-color: #10b981;
      animation: pulse 1.8s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.4); opacity: 0.8; }
      100% { transform: scale(1); opacity: 1; }
    }
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      20%, 60% { transform: translateX(-6px); }
      40%, 80% { transform: translateX(6px); }
    }
    .footer-container {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.25), rgba(240, 248, 255, 0.45));
      backdrop-filter: blur(15px);
      border-radius: 1.5rem;
      padding: 3rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.5);
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
      background: linear-gradient(45deg, rgba(124, 58, 237, 0.15), rgba(16, 185, 129, 0.15));
      opacity: 0.5;
      z-index: 0;
    }
    .footer-content {
      position: relative;
      z-index: 1;
    }
    .footer-link {
      color: #1f2937;
      transition: all 0.3s ease;
      font-family: 'Orbitron', sans-serif;
    }
    .footer-link:hover {
      color: #7c3aed;
      transform: translateY(-3px);
    }
    .social-icon {
      transition: all 0.5s ease;
      position: relative;
    }
    .social-icon:hover {
      transform: scale(1.5) rotate(360deg);
      color: #7c3aed;
      text-shadow: 0 0 20px rgba(124, 58, 237, 1);
      animation: pulseGlow 1.2s infinite;
    }
    @keyframes pulseGlow {
      0% { text-shadow: 0 0 15px rgba(124, 58, 237, 0.9); }
      50% { text-shadow: 0 0 25px rgba(124, 58, 237, 1); }
      100% { text-shadow: 0 0 15px rgba(124, 58, 237, 0.9); }
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulseButton {
      0% { box-shadow: 0 8px 20px rgba(124, 58, 237, 0.6); }
      50% { box-shadow: 0 12px 30px rgba(124, 58, 237, 0.8); }
      100% { box-shadow: 0 8px 20px rgba(124, 58, 237, 0.6); }
    }
  </style>
</head>
<body>
  <canvas id="three-canvas"></canvas>
  <header class="text-center py-8 sm:py-16">
    <img src="https://i.ibb.co/yBNCxwvM/enhanced.png" alt="CC Xen Generator Logo" class="logo">
    <div class="flex items-center justify-center mt-6 sm:mt-8">
      <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold glow stylish-text mr-4">Smart YouTube Downloader</h1>
      <span class="inline-block w-12 h-12">
        <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12" cy="12" r="10" fill="#10b981" stroke="#ffffff" stroke-width="2"/>
          <path d="M9 12.5L11 15L15 9" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
    </div>
    <p class="welcome-message mt-3 sm:mt-4 tracking-wide stylish-text text-lg sm:text-xl leading-relaxed max-w-5xl mx-auto">
      The Ultimate YouTube Downloader: Download and search YouTube videos with a futuristic, sleek interface. Built with passion by @ISmartCoder. Daily updates by @TheSmartDev.
    </p>
    <div class="status-indicator mt-6 text-lg sm:text-xl stylish-text flex items-center justify-center">
      <span class="status-dot mr-2"></span> Status: <i>Online</i>
    </div>
  </header>
  <section id="yt-downloader" class="section-card max-w-5xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-semibold text-center mb-8 sm:mb-10 glow stylish-text">YouTube Downloader & Search</h2>
    <div class="tool-card">
      <div class="tool-card-content">
        <div class="flex justify-center mb-8 space-x-4">
          <button class="tab active" data-tab="download">Download</button>
          <button class="tab" data-tab="search">Search</button>
        </div>
        <div id="download-tab" class="tab-content active">
          <i class="fab fa-youtube yt-icon"></i>
          <h3 class="text-2xl font-bold stylish-text mb-8 text-center">Download YouTube Video</h3>
          <div class="mb-8">
            <div class="grid grid-cols-1 max-w-lg mx-auto">
              <div>
                <label class="block text-sm font-medium stylish-text mb-3">Enter YouTube URL</label>
                <input type="text" id="dl-input" class="input-field" placeholder="e.g., https://youtube.com/watch?v=abc123" required>
              </div>
            </div>
          </div>
          <div class="text-center">
            <button class="cool-button" data-action="download">Download Video</button>
          </div>
          <p id="dl-error" class="error hidden"></p>
          <div id="download-results" class="hidden">
            <h4 class="text-xl font-bold stylish-text mb-6 text-center">Video Details</h4>
            <div class="result-box">
              <div class="thumbnail-container">
                <img id="dl-thumbnail" src="" alt="Thumbnail">
              </div>
              <p><i class="fas fa-video"></i> <strong>Title:</strong> <span id="dl-title"></span></p>
              <p><i class="fas fa-user"></i> <strong>Channel:</strong> <span id="dl-channel"></span></p>
              <p class="description"><i class="fas fa-file-alt"></i> <strong>Description:</strong> <span id="dl-description"></span></p>
              <p><i class="fas fa-clock"></i> <strong>Duration:</strong> <span id="dl-duration"></span></p>
              <p><i class="fas fa-eye"></i> <strong>Views:</strong> <span id="dl-views"></span></p>
              <p><i class="fas fa-thumbs-up"></i> <strong>Likes:</strong> <span id="dl-likes"></span></p>
              <p><i class="fas fa-comments"></i> <strong>Comments:</strong> <span id="dl-comments"></span></p>
              <div class="download-container">
                <a id="dl-url" href="#" target="_blank" class="action-button">Download Video</a>
              </div>
            </div>
          </div>
        </div>
        <div id="search-tab" class="tab-content">
          <div class="flex justify-center mb-8">
            <i class="fas fa-search text-6xl text-gray-600 animate-pulse"></i>
          </div>
          <h3 class="text-2xl font-bold stylish-text mb-8 text-center">Search YouTube Videos</h3>
          <div class="mb-8">
            <div class="grid grid-cols-1 max-w-lg mx-auto">
              <div>
                <label class="block text-sm font-medium stylish-text mb-3">Enter Search Query</label>
                <input type="text" id="search-input" class="input-field" placeholder="e.g., music videos" required>
              </div>
            </div>
          </div>
          <div class="text-center">
            <button class="cool-button" data-action="search">Search Videos</button>
          </div>
          <p id="search-error" class="error hidden"></p>
          <div id="search-results" class="hidden">
            <h4 class="text-xl font-bold stylish-text mb-6 text-center">Search Results</h4>
            <div id="search-results-container"></div>
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
        <p class="text-base sm:text-lg font-bold stylish-text">© 2025 Smart YouTube DL. All rights reserved.</p>
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
    async function downloadVideo() {
      const errorEl = document.getElementById('dl-error');
      const resultsEl = document.getElementById('download-results');
      const button = document.querySelector('button[data-action="download"]');
      const downloadLink = document.getElementById('dl-url');
      const input = document.getElementById('dl-input').value.trim();
      if (!input) {
        if (errorEl) {
          errorEl.textContent = 'Please enter a valid YouTube URL.';
          errorEl.classList.remove('hidden');
        }
        return;
      }
      button.textContent = 'Downloading...';
      button.disabled = true;
      if (errorEl) errorEl.classList.add('hidden');
      if (resultsEl) resultsEl.classList.add('hidden');
      if (downloadLink) {
        downloadLink.classList.add('disabled');
        downloadLink.textContent = 'Fetching Download Link...';
        downloadLink.removeAttribute('href');
      }
      let formData = new FormData();
      formData.append('url', input);
      try {
        const response = await fetch(window.location.pathname, {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        if (data.error) {
          if (errorEl) {
            errorEl.textContent = data.error;
            errorEl.classList.remove('hidden');
          }
          if (downloadLink) {
            downloadLink.textContent = 'Download Unavailable';
            downloadLink.classList.add('disabled');
            downloadLink.removeAttribute('href');
          }
        } else {
          if (document.getElementById('dl-title')) document.getElementById('dl-title').textContent = data.title || 'N/A';
          if (document.getElementById('dl-channel')) document.getElementById('dl-channel').textContent = data.channel || 'N/A';
          if (document.getElementById('dl-description')) {
            document.getElementById('dl-description').textContent = data.description || 'N/A';
          }
          if (document.getElementById('dl-thumbnail')) document.getElementById('dl-thumbnail').src = data.thumbnail || '';
          if (document.getElementById('dl-duration')) document.getElementById('dl-duration').textContent = data.duration || 'N/A';
          if (document.getElementById('dl-views')) document.getElementById('dl-views').textContent = data.views || 'N/A';
          if (document.getElementById('dl-likes')) document.getElementById('dl-likes').textContent = data.likes || 'N/A';
          if (document.getElementById('dl-comments')) document.getElementById('dl-comments').textContent = data.comments || 'N/A';
          if (downloadLink) {
            if (data.download_url) {
              downloadLink.href = data.download_url;
              downloadLink.textContent = `Download Video (${data.download_format || 'mp4'})`;
              downloadLink.classList.remove('disabled');
            } else {
              downloadLink.textContent = 'Download Unavailable';
              downloadLink.classList.add('disabled');
              downloadLink.removeAttribute('href');
              if (errorEl) {
                errorEl.textContent = data.error || 'No valid download URL provided by the API.';
                errorEl.classList.remove('hidden');
              }
            }
          }
          if (resultsEl) resultsEl.classList.remove('hidden');
        }
      } catch (err) {
        if (errorEl) {
          errorEl.textContent = 'Failed to process request: ' + err.message;
          errorEl.classList.remove('hidden');
        }
        if (downloadLink) {
          downloadLink.textContent = 'Download Unavailable';
          downloadLink.classList.add('disabled');
          downloadLink.removeAttribute('href');
        }
      } finally {
        button.textContent = 'Download Video';
        button.disabled = false;
      }
    }
    async function searchVideos() {
      const errorEl = document.getElementById('search-error');
      const resultsEl = document.getElementById('search-results');
      const button = document.querySelector('button[data-action="search"]');
      const input = document.getElementById('search-input').value.trim();
      if (!input) {
        if (errorEl) {
          errorEl.textContent = 'Please enter a search query.';
          errorEl.classList.remove('hidden');
        }
        return;
      }
      button.textContent = 'Searching...';
      button.disabled = true;
      if (errorEl) errorEl.classList.add('hidden');
      if (resultsEl) resultsEl.classList.add('hidden');
      let formData = new FormData();
      formData.append('query', input);
      try {
        const response = await fetch(window.location.pathname, {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        if (data.error) {
          if (errorEl) {
            errorEl.textContent = data.error;
            errorEl.classList.remove('hidden');
          }
        } else {
          const container = document.getElementById('search-results-container');
          if (container) {
            container.innerHTML = '';
            data.result.forEach((video, index) => {
              const card = document.createElement('div');
              card.className = 'video-card';
              card.innerHTML = `
                <div class="thumbnail-container">
                  <img src="${video.imageUrl}" class="thumbnail-img" alt="${video.title}">
                </div>
                <p><i class="fas fa-video"></i> <strong>Title:</strong> <span>${video.title}</span></p>
                <p><i class="fas fa-user"></i> <strong>Channel:</strong> <span>${video.channel}</span></p>
                <p><i class="fas fa-clock"></i> <strong>Duration:</strong> <span>${video.duration}</span></p>
                <p><i class="fas fa-eye"></i> <strong>Views:</strong> <span>${video.views}</span></p>
                <p><i class="fas fa-thumbs-up"></i> <strong>Likes:</strong> <span>${video.likes}</span></p>
                <p><i class="fas fa-comments"></i> <strong>Comments:</strong> <span>${video.comments}</span></p>
                <div class="buttons-container">
                  <a href="${video.link}" target="_blank" class="action-button">Watch on YouTube</a>
                  <button class="action-button download-video" data-url="${video.link}">Download This Video</button>
                </div>
              `;
              container.appendChild(card);
            });
            if (resultsEl) resultsEl.classList.remove('hidden');
          }
        }
      } catch (err) {
        if (errorEl) {
          errorEl.textContent = 'Failed to process request: ' + err.message;
          errorEl.classList.remove('hidden');
        }
      } finally {
        button.textContent = 'Search Videos';
        button.disabled = false;
      }
    }
    document.addEventListener('click', (e) => {
      if (e.target.classList.contains('download-video')) {
        const url = e.target.getAttribute('data-url');
        if (url) {
          document.getElementById('dl-input').value = url;
          document.querySelector('.tab[data-tab="download"]').click();
          downloadVideo();
        }
      } else if (e.target.classList.contains('tab')) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        e.target.classList.add('active');
        document.getElementById(e.target.getAttribute('data-tab') + '-tab').classList.add('active');
      }
    });
    document.querySelectorAll('.cool-button').forEach(button => {
      button.addEventListener('click', () => {
        const action = button.getAttribute('data-action');
        if (action === 'download') downloadVideo();
        else if (action === 'search') searchVideos();
      });
    });
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.onkeydown = function(e) {
      if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J')) || (e.ctrlKey && e.key === 'U')) {
        return false;
      }
    };
  </script>
</body>
</html>