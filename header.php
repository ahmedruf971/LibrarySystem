<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Library Management System</title>
  <style>
    :root {
      --bg-color: #1a1a2e;
      --text-color: #f0eaff;
      --card-color: #2d0040;
      --header-bg: #4a0072;
      --accent: #5a00a0;
      --hover: #7c00cc;
    }

    body.light {
      --bg-color: #ffffff;
      --text-color: #222222;
      --card-color: #f0eaff;
      --header-bg: #7b1fa2;
      --accent: #7b1fa2;
      --hover: #9c27b0;
    }

    html, body {
      height: 100%;
      margin: 0;
      background-color: var(--bg-color);
      color: var(--text-color);
      font-family: 'Segoe UI', sans-serif;
    }

    .page-wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    #theme-toggle {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 8px 14px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      background: var(--accent);
      color: white;
      font-weight: bold;
      z-index: 999;
      box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
  </style>
</head>
<body>
  <div class="page-wrapper">
    <button id="theme-toggle">🌙 Toggle Mode</button>

    <script>
      window.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('theme-toggle');
        const body = document.body;

        if (localStorage.getItem('theme') === 'light') {
          body.classList.add('light');
          toggle.textContent = '🌞 Light Mode';
        }

        toggle.addEventListener('click', () => {
          body.classList.toggle('light');
          const isLight = body.classList.contains('light');
          toggle.textContent = isLight ? '🌞 Light Mode' : '🌙 Dark Mode';
          localStorage.setItem('theme', isLight ? 'light' : 'dark');
        });
      });
    </script>


