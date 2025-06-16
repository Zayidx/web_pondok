<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Soal Ujian</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .header h2 {
            color: #333;
            font-size: 24px;
        }

        .total-poin {
            font-size: 18px;
            font-weight: bold;
            color: #2c5282;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #4299e1;
            color: white;
        }

        .btn-primary:hover {
            background-color: #3182ce;
        }

        .soal-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }

        .soal-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .soal-number {
            font-weight: bold;
            font-size: 18px;
        }

        .soal-type {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 14px;
        }

        .type-pg {
            background-color: #ebf8ff;
            color: #2b6cb0;
        }

        .type-essay {
            background-color: #f0fff4;
            color: #2f855a;
        }

        .soal-content {
            margin-bottom: 15px;
        }

        .opsi-list {
            margin-left: 20px;
        }

        .opsi-item {
            margin: 8px 0;
            display: flex;
            align-items: center;
        }

        .opsi-item input[type="radio"] {
            margin-right: 10px;
        }

        .kunci-jawaban {
            color: #2f855a;
            font-weight: bold;
        }

        .jawaban-santri {
            background-color: #f7fafc;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }

        .nilai-input {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .nilai-input input {
            width: 80px;
            padding: 4px 8px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }

        .nilai-info {
            color: #718096;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html> 