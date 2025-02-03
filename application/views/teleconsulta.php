<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groomers APP</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        html, body {
            height: 100%;
            background-color: #f5f5f5;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background-color: #0055a4;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            height: 60px;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            padding: 1rem;
            width: 100%;
            flex: 1;
            padding-bottom: 80px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-icon {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        .bottom-menu {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            background: white;
            padding: 0.8rem;
            border-top: 1px solid #eee;
        }

        .menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #0055a4;
            font-size: 0.75rem;
            text-align: center;
            gap: 0.3rem;
        }

        .menu-item:last-child {
            color: #ff0000;
        }

        /* Desktop styles */
        @media (min-width: 768px) {
            .grid-container {
                gap: 2rem;
                padding: 2rem;
            }

            .card {
                display: flex;
                flex-direction: column;
            }

            .card-image {
                height: 100%;
            }

            .card-content {
                position: static;
                background: #4a4a4a;
                padding: 1rem;
            }
        }

        /* Mobile styles */
        @media (max-width: 767px) {
            .grid-container {
                gap: 0.5rem;
                padding: 0.5rem;
            }

            .card-image {
                height: 100%;
            }

            .card-content {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="notification-icon">🔔</div>
        <div class="logo">Groomers APP</div>
        <div class="menu-icon">☰</div>
    </header>

    <div class="grid-container">
        <div class="card">
            <img class="card-image" src="./assets/teleconsulta/1.avif" alt="Doctor with child">
            <div class="info-icon">ⓘ</div>
            <div class="card-content">
                <span>📅</span>
                <span>CITA PRESENCIAL</span>
            </div>
        </div>

        <div class="card">
            <img class="card-image" src="./assets/teleconsulta/2.jpg" alt="Doctor handshake">
            <div class="info-icon">ⓘ</div>
            <div class="card-content">
                <span>🔍</span>
                <span>BUSCA TU MÉDICO</span>
            </div>
        </div>

        <div class="card">
            <img class="card-image" src="./assets/teleconsulta/3.jpg" alt="Doctor with patient">
            <div class="info-icon">ⓘ</div>
            <div class="card-content">
                <span>📋</span>
                <span>MIS CITAS</span>
            </div>
        </div>

        <div class="card">
            <img class="card-image" src="./assets/teleconsulta/4.avif" alt="Doctor with tablet">
            <div class="info-icon">ⓘ</div>
            <div class="card-content">
                <span>📱</span>
                <span>CITA VIRTUAL</span>
            </div>
        </div>
    </div>

    <br><br><br><br>

    <div class="bottom-menu">
        <a href="#" class="menu-item">
            <span>🏥</span>
            <span>SERVICIOS A DOMICILIO</span>
        </a>
        <a href="#" class="menu-item">
            <span>👨‍⚕️</span>
            <span>MÉDICO A DOMICILIO</span>
        </a>
        <a href="#" class="menu-item">
            <span>🚑</span>
            <span>EMERGENCIA</span>
        </a>
    </div>
</body>
</html>