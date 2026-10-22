<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Berhasil Ditambahkan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #66ea6f 0%, #4ba258 100%);
        }
        .container {
            background: #fff;
            padding: 50px 60px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #4CAF50;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pop 0.5s ease;
        }
        @keyframes pop {
            0% { transform: scale(0); }
            80% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .icon svg {
            width: 45px;
            height: 45px;
            stroke: #fff;
            stroke-width: 3;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .countdown {
            color: #141414;
            font-size: 18px;
            margin-top: 20px;
        }
        .countdown span {
            font-weight: bold;
            font-size: 28px;
            color: #4CAF50;
            display: inline-block;
            min-width: 30px;
        }
        .progress-bar {
            width: 100%;
            height: 6px;
            background: #eee;
            border-radius: 3px;
            margin-top: 25px;
            overflow: hidden;
        }
        .progress-bar .fill {
            height: 100%;
            width: 100%;
            background: linear-gradient(90deg, #83ea66, #4ba265);
            border-radius: 3px;
            animation: shrink 3s linear forwards;
        }
        @keyframes shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <svg viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <h1>Data Berhasil Dihapus!</h1>
        <p class="countdown">Kembali ke halaman utama dalam <span id="timer">3</span> detik</p>
        <div class="progress-bar">
            <div class="fill"></div>
        </div>
    </div>

    <script>
        let detik = 3;
        const timerEl = document.getElementById('timer');

        const interval = setInterval(() => {
            detik--;
            timerEl.textContent = detik;
            if (detik <= 0) {
                clearInterval(interval);
                window.location.href = 'index.php';
            }
        }, 1000);
    </script>
</body>
</html>